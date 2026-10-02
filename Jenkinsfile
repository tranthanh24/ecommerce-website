pipeline {
    agent any

    options {
        skipDefaultCheckout(true)
        disableConcurrentBuilds()
        timeout(time: 45, unit: 'MINUTES')
        timestamps()
    }

    // Deploy images built from this commit.
    parameters {
        string(
            name: 'IMAGE_TAG',
            defaultValue: '',
            description: 'Full Git commit SHA already published to both ECR repositories'
        )
        booleanParam(
            name: 'DEPLOY_MONITORING',
            defaultValue: false,
            description: 'Install or update the Prometheus and Grafana monitoring stack'
        )
    }

    environment {
        AWS_REGION = 'ap-southeast-1'
        APP_NAMESPACE = 'ecommerce'
        DB_NAMESPACE = 'database'
        MONITORING_NAMESPACE = 'monitoring'
        APP_REPOSITORY = 'laravel-app'
        WEB_REPOSITORY = 'laravel-web'
    }

    stages {
        // Reject missing or shortened tags before accessing AWS or Kubernetes.
        stage('Validate release') {
            steps {
                script {
                    if (!(params.IMAGE_TAG ==~ /^[0-9a-f]{40}$/)) {
                        error('IMAGE_TAG must be the full 40-character Git commit SHA produced by CI.')
                    }
                }
            }
        }

        stage('Checkout release') {
            steps {
                deleteDir()
                checkout scm

                sh '''
                    set -eu
                    git fetch --no-tags origin "$IMAGE_TAG"
                    git checkout --detach "$IMAGE_TAG"
                    echo "Deploying commit:"
                    git rev-parse HEAD
                    test "$(git rev-parse HEAD)" = "$IMAGE_TAG"
                '''
            }
        }

        stage('Verify tools and images') {
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'aws-ecr-reader',
                        usernameVariable: 'AWS_ACCESS_KEY_ID',
                        passwordVariable: 'AWS_SECRET_ACCESS_KEY'
                    )
                ]) {
                    script {
                        sh '''
                            set -eu
                            command -v git >/dev/null
                            command -v aws >/dev/null
                            command -v kubectl >/dev/null
                            command -v sed >/dev/null
                        '''

                        def awsAccountId = sh(
                            script: 'set +x; aws sts get-caller-identity --query Account --output text',
                            returnStdout: true
                        ).trim()

                        if (!(awsAccountId ==~ /^[0-9]{12}$/)) {
                            error('AWS returned an invalid account ID.')
                        }

                        env.ECR_REGISTRY = "${awsAccountId}.dkr.ecr.${env.AWS_REGION}.amazonaws.com"
                    }

                    sh '''
                        set -eu
                        test "$(aws ecr batch-get-image \
                            --region "$AWS_REGION" \
                            --repository-name "$APP_REPOSITORY" \
                            --image-ids "imageTag=$IMAGE_TAG" \
                            --query 'length(images)' --output text)" = "1"
                        test "$(aws ecr batch-get-image \
                            --region "$AWS_REGION" \
                            --repository-name "$WEB_REPOSITORY" \
                            --image-ids "imageTag=$IMAGE_TAG" \
                            --query 'length(images)' --output text)" = "1"
                    '''
                }
            }
        }

        // Create namespaces and verify manually managed application/database secrets.
        stage('Prepare Kubernetes') {
            steps {
                withCredentials([
                    file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')
                ]) {
                    sh '''
                        set -eu
                        kubectl apply -f k8s/base/namespace.yaml

                        kubectl -n "$APP_NAMESPACE" get secret app-secret >/dev/null
                        kubectl -n "$DB_NAMESPACE" get secret mysql-secret >/dev/null

                        if grep -q 'https://example.com' k8s/base/configmaps.yaml; then
                            echo 'Replace APP_URL=https://example.com in k8s/base/configmaps.yaml before deploying.' >&2
                            exit 1
                        fi
                    '''
                }
            }
        }

        // Refresh the expiring ECR pull token.
        stage('Refresh ECR pull secret') {
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'aws-ecr-reader',
                        usernameVariable: 'AWS_ACCESS_KEY_ID',
                        passwordVariable: 'AWS_SECRET_ACCESS_KEY'
                    ),
                    file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')
                ]) {
                    sh '''
                        set +x
                        ECR_PASSWORD=$(aws ecr get-login-password --region "$AWS_REGION")
                        kubectl -n "$APP_NAMESPACE" create secret docker-registry ecr-registry \
                            --docker-server="$ECR_REGISTRY" \
                            --docker-username=AWS \
                            --docker-password="$ECR_PASSWORD" \
                            --dry-run=client -o yaml | kubectl apply -f -
                        unset ECR_PASSWORD
                    '''
                }
            }
        }

        stage('Deploy database and shared resources') {
            steps {
                withCredentials([
                    file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')
                ]) {
                    sh '''
                        set -eu
                        kubectl apply -f k8s/base/configmaps.yaml
                        kubectl apply -f k8s/base/storage.yaml
                        kubectl apply -f k8s/base/mysql.yaml
                        kubectl apply -f k8s/base/network-policy.yaml
                        kubectl -n "$DB_NAMESPACE" rollout status statefulset/mysql --timeout=300s
                    '''
                }
            }
        }

        stage('Deploy Laravel app') {
            steps {
                withCredentials([
                    file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')
                ]) {
                    sh '''
                        set -eu
                        sed \
                            -e "s#YOUR_ECR_REGISTRY#$ECR_REGISTRY#g" \
                            -e "s#REPLACE_WITH_COMMIT_SHA#$IMAGE_TAG#g" \
                            k8s/base/app.yaml | kubectl apply -f -

                        kubectl -n "$APP_NAMESPACE" rollout status deployment/app --timeout=300s
                        kubectl -n "$APP_NAMESPACE" exec deployment/app -- \
                            php artisan migrate --force --no-interaction
                    '''
                }
            }
        }

        stage('Deploy web server') {
            steps {
                withCredentials([
                    file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')
                ]) {
                    sh '''
                        set -eu
                        sed \
                            -e "s#YOUR_ECR_REGISTRY#$ECR_REGISTRY#g" \
                            -e "s#REPLACE_WITH_COMMIT_SHA#$IMAGE_TAG#g" \
                            k8s/base/web.yaml | kubectl apply -f -

                        kubectl -n "$APP_NAMESPACE" rollout status deployment/web --timeout=300s
                        kubectl -n "$APP_NAMESPACE" get deployment app web
                    '''
                }
            }
        }

        // Install monitoring only when selected.
        stage('Deploy monitoring') {
            when {
                expression { params.DEPLOY_MONITORING }
            }
            steps {
                withCredentials([
                    file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')
                ]) {
                    sh '''
                        set -eu
                        command -v helm >/dev/null
                        kubectl -n "$MONITORING_NAMESPACE" get secret grafana-admin \
                            -o go-template='{{if and (index .data "admin-user") (index .data "admin-password")}}ok{{end}}' \
                            | grep -qx ok
                        kubectl get storageclass local-path >/dev/null

                        helm upgrade --install monitoring \
                            oci://ghcr.io/prometheus-community/charts/kube-prometheus-stack \
                            --version 91.8.1 \
                            --namespace "$MONITORING_NAMESPACE" \
                            --values k8s/monitoring/values.yaml \
                            --atomic --wait-for-jobs --timeout 15m

                        helm --namespace "$MONITORING_NAMESPACE" status monitoring
                        kubectl -n "$MONITORING_NAMESPACE" get pods,pvc,services
                    '''
                }
            }
        }
    }
}
