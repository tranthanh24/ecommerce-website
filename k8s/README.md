# Triển khai Kubernetes

## Chuẩn bị

- Namespace `ecommerce` chạy web, app và Tunnel; `database` chạy MySQL;
  `monitoring` chạy Prometheus và Grafana.
- Đổi `APP_URL` trong `k8s/base/configmaps.yaml` thành tên miền HTTPS thật.
- Jenkins tự điền ECR registry và tag commit SHA cho image `laravel-app`, `laravel-web`.
- Cấu hình quyền kéo image ECR và tự làm mới token (hết hạn sau 12 giờ).
- Cluster cần StorageClass hỗ trợ PVC, fsGroup và CNI hỗ trợ NetworkPolicy.
  Web và app chạy cùng node để chia sẻ volume ảnh RWO.
- Copy `k8s/secrets/*.env.example` thành file `.env` tương ứng, điền giá trị thật.
- `MYSQL_PASSWORD` trong `mysql.env` phải giống `DB_PASSWORD` trong `app.env`.
  Secret được tạo riêng ở từng namespace.

## Chạy web, app và MySQL

```bash
kubectl apply -f k8s/base/namespace.yaml
kubectl -n ecommerce create secret generic app-secret --from-env-file=k8s/secrets/app.env
kubectl -n database create secret generic mysql-secret --from-env-file=k8s/secrets/mysql.env
kubectl apply -f k8s/base/
kubectl -n ecommerce get pods,pvc,svc
kubectl -n database get pods,pvc,svc
kubectl -n ecommerce exec deployment/web -- nginx -t
```

Nếu đã có MySQL ở namespace cũ, cần backup/restore dữ liệu trước khi chuyển.
PVC không tự chuyển namespace; không xóa PVC cũ khi chưa xác nhận dữ liệu mới.

## Nối Cloudflare Tunnel

Lưu token vào `k8s/secrets/tunnel.env`. Trỏ tên miền tới dịch vụ HTTP
`web.ecommerce.svc.cluster.local:8080` và bật chuyển hướng HTTPS trên Cloudflare.

```bash
kubectl -n ecommerce create secret generic tunnel-secret --from-env-file=k8s/secrets/tunnel.env
kubectl apply -f k8s/tunnel/
kubectl -n ecommerce rollout status deployment/cloudflared --timeout=180s
```

## Prometheus và Grafana

Jenkins chỉ cài monitoring khi chọn `DEPLOY_MONITORING`. Jenkins agent cần Helm 3,
cluster cần StorageClass `local-path`, và VM cần đủ CPU, RAM, dung lượng đĩa
cho PVC Grafana 2 GiB và Prometheus 10 GiB.

```bash
kubectl apply -f k8s/base/namespace.yaml
cp k8s/secrets/grafana.env.example k8s/secrets/grafana.env
chmod 600 k8s/secrets/grafana.env

kubectl -n monitoring create secret generic grafana-admin \
  --from-env-file=k8s/secrets/grafana.env \
  --dry-run=client -o yaml | kubectl apply -f -
```

Push cấu hình, chờ CI tạo đủ hai image của commit mới, sau đó chạy Jenkins với
`IMAGE_TAG` là SHA 40 ký tự và `DEPLOY_MONITORING=true`. Jenkins cài chart
`kube-prometheus-stack` theo `k8s/monitoring/values.yaml` và giữ Grafana nội bộ.
Tạo Cloudflare Access application giới hạn email được phép vào hostname Grafana
trước, rồi thêm Published Application trong Tunnel hiện có:

```text
Service:  http://monitoring-grafana.monitoring.svc.cluster.local:80
```

Không public service Prometheus. Sau khi xác nhận Secret đã tạo đúng, xóa file
`k8s/secrets/grafana.env` trên Ubuntu.

## Cập nhật cấu hình

```bash
kubectl apply -f k8s/base/
kubectl -n ecommerce rollout restart deployment/app deployment/web
```
