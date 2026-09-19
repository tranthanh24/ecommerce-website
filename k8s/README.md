# Triển khai Kubernetes

## Chuẩn bị

- Namespace `ecommerce` chạy web, app và Tunnel; `database` chạy MySQL và PVC database.
- Đổi `APP_URL` trong `k8s/base/configmaps.yaml` thành tên miền HTTPS thật.
- Đổi cả hai image trong `app.yaml` sang ECR `laravel-app`, image trong `web.yaml`
  sang ECR `laravel-web`; dùng cùng tag commit SHA.
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

## Cập nhật cấu hình

```bash
kubectl apply -f k8s/base/
kubectl -n ecommerce rollout restart deployment/app deployment/web
```
