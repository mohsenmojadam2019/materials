<!doctype html>
<html lang="fa" dir="rtl">
<head>
 <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
 <title>@yield('title','پنل مدیریت ساختمان‌یار')</title>
 <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body class="admin-body">
@php
 $menu=[
  ['dashboard','admin.dashboard','داشبورد','▦'],['orders','admin.orders','سفارش‌ها','🛒'],['products','admin.products','محصولات','◇'],
  ['categories','admin.module','دسته‌بندی‌ها','☷'],['inventory','admin.module','انبار و موجودی','⌂'],['suppliers','admin.module','تأمین‌کنندگان','▥'],
  ['pricing','admin.module','قیمت‌گذاری','٪'],['customers','admin.module','مشتریان','♙'],['projects','admin.module','پروژه‌ها و استعلام‌ها','▤'],
  ['logistics','admin.module','ارسال و لجستیک','🚚'],['finance','admin.module','مالی و تسویه','▣'],['reports','admin.module','گزارش‌ها','▥'],
  ['users','admin.module','کاربران و نقش‌ها','♙'],['discounts','admin.module','کد تخفیف','٪'],['content','admin.module','محتوا و بلاگ','▤'],
  ['tickets','admin.module','تیکت‌ها','✉'],['settings','admin.module','تنظیمات','⚙'],
 ];
@endphp
<div class="admin-root">
 <aside class="admin-side">
  <div class="admin-brand"><span class="admin-logo">س</span><div><b>ساختمان‌یار</b><small>مدیریت فروش مصالح</small></div></div>
  <div class="side-cover"></div>
  <nav>
   @foreach($menu as [$key,$route,$label,$icon])
    @php($href=$route==='admin.module'?route($route,$key):route($route))
    <a href="{{ $href }}" class="{{ request()->is('admin'.($key==='dashboard'?'':'/'.$key))?'active':'' }}"><i>{{ $icon }}</i><span>{{ $label }}</span><b>‹</b></a>
   @endforeach
  </nav>
 </aside>
 <section class="admin-body-content">
  <header class="admin-top">
   <div class="admin-user"><span class="avatar">ع</span><div><b>علی محمدی</b><small>مدیر سیستم</small></div></div>
   <button class="icon-btn">⚙</button><button class="icon-btn notify">♧<i>۳</i></button>
   <div class="admin-date"><span>امروز</span><b>{{ \App\Support\Jalali::today() }}</b></div>
   <div class="admin-search">⌕ <input placeholder="جستجوی سریع محصول، سفارش، مشتری، کد فاکتور ..."></div>
   <a class="view-store" href="{{ route('home') }}">مشاهده فروشگاه ↗</a>
  </header>
  <div class="admin-page-head"><div><h1>@yield('page-title')</h1><p>@yield('page-subtitle')</p></div><div class="breadcrumbs">داشبورد　/　@yield('page-title')</div></div>
  <main class="admin-content">@yield('content')</main>
 </section>
</div>
<script src="{{ asset('assets/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>