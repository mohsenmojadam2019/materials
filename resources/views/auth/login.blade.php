<!doctype html>
<html lang="fa" dir="rtl">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <title>{{ $adminMode ? 'ورود مدیر' : 'ورود کاربر' }} | ساختینو</title>
 <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body class="login-page">
<div class="login-shell">
 <section class="login-visual">
  <div class="login-brand"><span>س</span><div><b>ساختینو</b><small>بازار آنلاین مصالح ساختمانی</small></div></div>
  <div class="login-copy">
   <span>{{ $adminMode ? 'پنل مدیریت حرفه‌ای' : 'حساب مشتری' }}</span>
   <h1>{{ $adminMode ? 'مدیریت فروش، انبار و سفارش‌ها' : 'خرید سریع و مدیریت سفارش‌ها' }}</h1>
   <p>ورود امن به سامانه جامع فروش و تأمین مصالح ساختمانی.</p>
  </div>
 </section>
 <section class="login-card">
  <a class="login-back" href="{{ route('home') }}">← بازگشت به فروشگاه</a>
  <h2>{{ $adminMode ? 'ورود مدیر سیستم' : 'ورود به حساب کاربری' }}</h2>
  <p>{{ $adminMode ? 'اطلاعات حساب مدیر را وارد کنید.' : 'ایمیل و رمز عبور خود را وارد کنید.' }}</p>
  @if($errors->any())<div class="login-error">{{ $errors->first() }}</div>@endif
  <form method="post" action="{{ route('login.submit') }}">
   @csrf
   <input type="hidden" name="admin_login" value="{{ $adminMode ? 1 : 0 }}">
   <label>ایمیل
    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com">
   </label>
   <label>رمز عبور
    <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
   </label>
   <label class="remember"><input type="checkbox" name="remember" value="1"> مرا به خاطر بسپار</label>
   <button type="submit">{{ $adminMode ? 'ورود به پنل مدیریت' : 'ورود به حساب' }}</button>
  </form>
  <div class="login-switch">
   @if($adminMode)
    <a href="{{ route('login') }}">ورود کاربر عادی</a>
   @else
    <a href="{{ route('register') }}">ساخت حساب جدید</a>
    <a href="{{ route('admin.login') }}">ورود مدیر</a>
   @endif
  </div>
 </section>
</div>
</body>
</html>
