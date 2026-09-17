# E-Commerce Website

Website thương mại điện tử xây dựng bằng Laravel, hỗ trợ khách hàng, người bán và quản trị viên.

## Chức năng chính

- Tìm kiếm sản phẩm, giỏ hàng, yêu thích và đánh giá.
- Đặt hàng, sử dụng mã giảm giá và theo dõi đơn hàng.
- Thanh toán PayPal, VNPay và COD.
- Quản lý cửa hàng, sản phẩm, khách hàng và đơn hàng.
- Flash sale, blog, chat realtime và chatbot tư vấn sản phẩm.

## Công nghệ

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)

## Cài đặt

Cần PHP 8.2+, Composer, Node.js 20+ và MySQL.

```bash
git clone https://github.com/tranthanh24/ecommerce-website.git
cd ecommerce-website
composer install --no-scripts
npm install
```

Sao chép `.env.example` thành `.env`, sau đó chỉnh `APP_URL` và thông tin kết nối database.

**Lưu ý:** Cần khôi phục database của dự án trước khi chạy Artisan vì ứng dụng đọc các bảng cấu hình ngay khi khởi động. Repository chưa kèm file SQL.

Sau khi database sẵn sàng:

```bash
composer run-script post-autoload-dump
php artisan key:generate
php artisan migrate
php artisan storage:link
```

Chỉ tạo key khi dùng `.env` mới; giữ `APP_KEY` cũ nếu khôi phục dữ liệu đã mã hóa.

## Chạy dự án

Mở hai terminal, chạy lần lượt:

```bash
php artisan serve
```

```bash
npm run dev
```

Truy cập `http://127.0.0.1:8000`. Trang đăng nhập quản trị: `/admin/login`.

Cấu hình email, Pusher và chatbot trong trang quản trị. PayPal và VNPay được cấu hình tại phần cài đặt thanh toán.

Build frontend:

```bash
npm run build
```
