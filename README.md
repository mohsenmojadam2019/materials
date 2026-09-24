# Materials — فروشگاه جامع مصالح ساختمانی

پروژه فروشگاهی B2B/B2C برای شرکت‌های فروش مصالح ساختمانی، پیاده‌سازی‌شده با **Laravel 13 + Blade + CSS + JavaScript**.

## ویژگی‌ها

- رابط کامل فارسی و RTL
- تاریخ‌های جلالی
- تمام قیمت‌ها بر مبنای ریال
- فروشگاه، جستجو، دسته‌بندی، سبد خرید و استعلام پروژه
- قیمت روز مصالح
- محاسبه تقریبی هزینه پروژه
- پنل مدیریت حرفه‌ای
- مدیریت محصولات، انبار، سفارش، تامین‌کننده و مشتری
- استعلام پروژه، مالی، لجستیک، گزارش‌ها، کاربران، تخفیف، محتوا، تیکت و تنظیمات
- SQLite برای راه‌اندازی سریع دمو
- Responsive برای دسکتاپ، تبلت و موبایل

## اجرا با Docker

```bash
docker compose up --build
```

سپس:

- فروشگاه: http://localhost:8012
- پنل مدیریت: http://localhost:8012/admin
- محصولات: http://localhost:8012/admin/products
- سفارش‌ها: http://localhost:8012/admin/orders

## راه‌اندازی دیتابیس

```bash
docker compose run --rm app php artisan migrate:fresh --seed
```

## تست

```bash
docker compose run --rm app php artisan test
```

## مستندات

مستندات UI/UX و اسکرین‌شات‌های واقعی در پوشه `docs/` قرار دارند.
