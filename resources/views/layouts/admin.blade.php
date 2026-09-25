<!doctype html><html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>@yield("title","پنل مدیریت")</title><link rel="stylesheet" href="{{ asset("assets/css/app.css") }}"></head>
<body class="admin-body"><div class="admin-root">
<aside class="admin-side"><div class="admin-brand">@if(\App\Support\Store::get("store_logo"))<img class="admin-logo-image" src="{{ asset(ltrim(\App\Support\Store::get("store_logo"),"/")) }}" alt="logo">@else<span class="admin-logo">س</span>@endif<div><b>{{ \App\Support\Store::get("store_name",config("store.name")) }}</b><small>مدیریت فروشگاه مصالح</small></div></div><div class="side-cover"></div><nav>
<a class="{{ request()->routeIs("admin.dashboard")?"active":"" }}" href="{{ route("admin.dashboard") }}"><i>▦</i><span>داشبورد</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.orders*")?"active":"" }}" href="{{ route("admin.orders") }}"><i>🛒</i><span>سفارش‌ها</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.products*")?"active":"" }}" href="{{ route("admin.products") }}"><i>◇</i><span>محصولات</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.categories*")?"active":"" }}" href="{{ route("admin.categories") }}"><i>☷</i><span>دسته‌بندی‌ها</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.inventory*")?"active":"" }}" href="{{ route("admin.inventory") }}"><i>⌂</i><span>انبار و موجودی</span><b>‹</b></a>
<a href="{{ route("admin.module","suppliers") }}"><i>▥</i><span>تأمین و خرید داخلی</span><b>‹</b></a>
<a href="{{ route("admin.module","pricing") }}"><i>٪</i><span>قیمت‌گذاری</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.customers")?"active":"" }}" href="{{ route("admin.customers") }}"><i>♙</i><span>مشتریان و CRM</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.quotes*")?"active":"" }}" href="{{ route("admin.quotes") }}"><i>▤</i><span>استعلام و پیش‌فاکتور</span><b>‹</b></a>
<a href="{{ route("admin.module","logistics") }}"><i>🚚</i><span>ارسال و لجستیک</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.finance")?"active":"" }}" href="{{ route("admin.finance") }}"><i>▣</i><span>مالی و مطالبات</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.reports")?"active":"" }}" href="{{ route("admin.reports") }}"><i>▥</i><span>گزارش‌ها</span><b>‹</b></a>
<a href="{{ route("admin.module","users") }}"><i>♙</i><span>کاربران و نقش‌ها</span><b>‹</b></a>
<a href="{{ route("admin.module","discounts") }}"><i>٪</i><span>تخفیف‌ها</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.content*")?"active":"" }}" href="{{ route("admin.content") }}"><i>▤</i><span>محتوا و بلاگ</span><b>‹</b></a>
<a href="{{ route("admin.module","tickets") }}"><i>✉</i><span>تیکت‌ها</span><b>‹</b></a>
<a class="{{ request()->routeIs("admin.settings*")?"active":"" }}" href="{{ route("admin.settings") }}"><i>⚙</i><span>تنظیمات White-label</span><b>‹</b></a>
</nav></aside>
<section class="admin-body-content"><header class="admin-top"><div class="admin-user"><span class="avatar">{{ mb_substr(auth()->user()->name,0,1) }}</span><div><b>{{ auth()->user()->name }}</b><small>مدیر سیستم</small></div></div><div class="admin-date"><span>امروز</span><b>{{ \App\Support\Jalali::today() }}</b></div><div class="admin-search">⌕ <input placeholder="جستجوی محصول، سفارش یا مشتری ..."></div><a class="view-store" href="{{ route("home") }}">مشاهده فروشگاه ↗</a><form method="post" action="{{ route("logout") }}" class="admin-logout">@csrf<button>خروج</button></form></header>
<div class="admin-page-head"><div><h1>@yield("page-title")</h1><p>@yield("page-subtitle")</p></div><div class="breadcrumbs">داشبورد / @yield("page-title")</div></div><main class="admin-content">@if(session("success"))<div class="flash success">{{ session("success") }}</div>@endif @if($errors->any())<div class="flash error">{{ $errors->first() }}</div>@endif @yield("content")</main></section>
</div><script src="{{ asset("assets/js/app.js") }}"></script></body></html>
