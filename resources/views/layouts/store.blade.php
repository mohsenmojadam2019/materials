<!doctype html>
<html lang="fa" dir="rtl">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta name="description" content="@yield('meta_description',\App\Support\Store::get('store_tagline',config('store.tagline')))">
 <meta name="robots" content="@yield('robots','index,follow')">
 <title>@yield('title',\App\Support\Store::get('store_name',config('store.name')).' | '.\App\Support\Store::get('store_tagline',config('store.tagline')))</title>
 <link rel="canonical" href="{{ url()->current() }}">
 <meta property="og:type" content="website">
 <meta property="og:title" content="@yield('title',\App\Support\Store::get('store_name',config('store.name')))">
 <meta property="og:description" content="@yield('meta_description',\App\Support\Store::get('store_tagline',config('store.tagline')))">
 <meta property="og:url" content="{{ url()->current() }}">
 <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
 @stack('head')
</head>
<body class="store-body" style="--brand-primary:{{ \App\Support\Store::get('primary_color',config('store.primary_color')) }}">
 <div class="topline"><div><a href="{{ route('blog') }}">وبلاگ</a>　|　<a href="{{ route('faq') }}">سوالات متداول</a>　|　 تماس با ما</div><div>پشتیبانی　☎ {{ \App\Support\Store::get('store_phone',config('store.phone')) }}</div></div>
 <header class="store-header">
  <a class="brand" href="{{ route('home') }}">
   @if(\App\Support\Store::get('store_logo'))<img class="brand-logo-image" src="{{ asset(ltrim(\App\Support\Store::get('store_logo'),'/')) }}" alt="{{ \App\Support\Store::get('store_name',config('store.name')) }}">@else<span class="brand-mark">س</span>@endif
   <span><b>{{ \App\Support\Store::get('store_name',config('store.name')) }}</b><small>{{ \App\Support\Store::get('store_tagline',config('store.tagline')) }}</small></span>
  </a>
  <form class="search-box" method="get" action="{{ route('home') }}"><span class="search-icon">⌕</span><input name="q" value="{{ request('q') }}" placeholder="جستجوی محصول، برند یا کد کالا ..."></form>
  <div class="header-actions">
   @auth
    <a class="ghost-btn" href="{{ route('account.dashboard') }}">♙ حساب من</a>
    @if(auth()->user()->role==='admin')<a class="ghost-btn" href="{{ route('admin.dashboard') }}">پنل مدیریت</a>@endif
   @else
    <a class="ghost-btn" href="{{ route('login') }}">♙ ورود</a><a class="ghost-btn" href="{{ route('register') }}">ثبت‌نام</a>
   @endauth
   <a class="cart-btn" href="{{ route('cart.index') }}">🛒 سبد خرید <i>{{ $cartCount ?? array_sum(session('cart',[])) }}</i></a>
  </div>
 </header>
 <nav class="main-nav">
  <a class="nav-main" href="{{ route('home') }}#categories">☰ دسته‌بندی‌ها</a>
  <a href="{{ route('home') }}#products">محصولات</a>
  <a href="{{ route('home',['in_stock'=>1]) }}">موجودی آماده</a>
  <a href="{{ route('home',['sort'=>'change']) }}">قیمت‌های روز</a>
  <a href="{{ route('b2b.create','wholesale') }}">خرید عمده</a>
  <a href="{{ route('wishlist') }}">♡ علاقه‌مندی‌ها</a>
  <a href="{{ route('compare') }}">⇄ مقایسه</a>
  <a href="{{ route('blog') }}">وبلاگ</a>
 </nav>
 @if(session('success'))<div class="flash success">{{ session('success') }}</div>@endif
 @if($errors->any())<div class="flash error">{{ $errors->first() }}</div>@endif
 @yield('content')
 <footer class="footer">
  <div><b>{{ \App\Support\Store::get('store_name',config('store.name')) }}</b><p>{{ \App\Support\Store::get('store_tagline',config('store.tagline')) }}؛ فروش مستقیم به سازنده، پیمانکار و مصرف‌کننده.</p></div>
  <div><b>خدمات</b><a href="{{ route('b2b.create','wholesale') }}">خرید عمده</a><a href="{{ route('b2b.create','credit') }}">فروش اعتباری</a><a href="{{ route('account.orders') }}">پیگیری سفارش</a></div>
  <div><b>راهنما</b><a href="{{ route('faq') }}">سوالات متداول</a><a href="{{ route('blog') }}">راهنمای خرید</a><a href="{{ route('sitemap') }}">نقشه سایت</a></div>
  <div><b>ارتباط</b><p>{{ \App\Support\Store::get('store_phone',config('store.phone')) }}<br>{{ \App\Support\Store::get('store_email',config('store.email')) }}<br>{{ \App\Support\Store::get('store_address',config('store.address')) }}</p></div>
 </footer>
 <script src="{{ asset('assets/js/app.js') }}"></script>
 @stack('scripts')
</body></html>