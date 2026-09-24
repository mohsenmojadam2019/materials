<!doctype html>
<html lang="fa" dir="rtl">
<head>
 <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>@yield('title','ساختینو | بازار مصالح ساختمانی')</title>
 <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body class="store-body">
 <div class="topline">
  <div>درباره ما　 |　 بلاگ　 |　 سوالات متداول　 |　 تماس با ما</div>
  <div>پشتیبانی ۲۴ ساعته　☎ ۰۲۱ ۹۱۰۰ ۱۲۳۴</div>
 </div>
 <header class="store-header">
  <a class="brand" href="{{ route('home') }}"><span class="brand-mark">س</span><span><b>ساختینو</b><small>بازار آنلاین مصالح ساختمانی</small></span></a>
  <form class="search-box" method="get" action="{{ route('home') }}">
   <span class="search-icon">⌕</span><input name="q" value="{{ request('q') }}" placeholder="جستجوی محصولات، برندها یا دسته‌بندی‌ها ...">
  </form>
  <div class="header-actions">
   <button class="ghost-btn">♙ ورود / ثبت‌نام</button>
   <button class="cart-btn">🛒 سبد خرید <i>{{ $cartCount ?? array_sum(session('cart',[])) }}</i></button>
  </div>
 </header>
 <nav class="main-nav">
  <button class="nav-main">☰ همه دسته‌بندی‌ها</button>
  <a href="#categories">مصالح ساختمانی</a><a>تأسیسات و لوله‌کشی</a><a>ابزار و تجهیزات</a>
  <a>نازک‌کاری و دکوراسیون</a><a>فروش عمده</a><a>قیمت‌ها و بازار</a>
  <a href="{{ route('admin.dashboard') }}">پنل مدیریت</a>
 </nav>
 @if(session('success'))<div class="flash success">{{ session('success') }}</div>@endif
 @if($errors->any())<div class="flash error">{{ $errors->first() }}</div>@endif
 @yield('content')
 <footer class="footer">
  <div><b>ساختینو</b><p>زیرساخت فروش حرفه‌ای مصالح برای شرکت‌های ساختمانی، عمده‌فروشان و تأمین‌کنندگان.</p></div>
  <div><b>خدمات</b><a>خرید عمده</a><a>استعلام پروژه</a><a>پیگیری سفارش</a></div>
  <div><b>راهنما</b><a>قوانین و مقررات</a><a>روش‌های پرداخت</a><a>ارسال و مرجوعی</a></div>
  <div><b>خبرنامه</b><div class="newsletter"><input placeholder="شماره موبایل"><button>عضویت</button></div></div>
 </footer>
 <script src="{{ asset('assets/js/app.js') }}"></script>
 @stack('scripts')
</body>
</html>