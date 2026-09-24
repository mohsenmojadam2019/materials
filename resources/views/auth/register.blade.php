<!doctype html>
<html lang="fa" dir="rtl">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <title>ثبت‌نام | ساختینو</title>
 <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body class="login-page">
<div class="login-shell">
 <section class="login-visual">
  <div class="login-brand"><span>س</span><div><b>ساختینو</b><small>بازار آنلاین مصالح ساختمانی</small></div></div>
  <div class="login-copy">
   <span>عضویت مشتری</span>
   <h1>حساب کاربری خود را بسازید</h1>
   <p>سفارش‌ها، استعلام‌ها و خریدهای پروژه‌ای را یکجا مدیریت کنید.</p>
  </div>
 </section>
 <section class="login-card">
  <a class="login-back" href="{{ route('home') }}">← بازگشت به فروشگاه</a>
  <h2>ثبت‌نام کاربر جدید</h2>
  <p>اطلاعات زیر را کامل کنید.</p>
  @if($errors->any())<div class="login-error">{{ $errors->first() }}</div>@endif
  <form method="post" action="{{ route('register.submit') }}">
   @csrf
   <label>نام و نام خانوادگی
    <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name">
   </label>
   <label>ایمیل
    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
   </label>
   <label>رمز عبور
    <input type="password" name="password" required autocomplete="new-password">
   </label>
   <label>تکرار رمز عبور
    <input type="password" name="password_confirmation" required autocomplete="new-password">
   </label>
   <button type="submit">ایجاد حساب کاربری</button>
  </form>
  <div class="login-switch">
   <span>حساب دارید؟</span>
   <a href="{{ route('login') }}">ورود</a>
  </div>
 </section>
</div>
</body>
</html>
