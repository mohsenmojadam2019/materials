@extends('layouts.store')
@section('title',\App\Support\Store::get('store_name',config('store.name')).' | فروشگاه مصالح ساختمانی')
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<section class="hero-wrap">
 <div class="hero"><div class="hero-shade"></div><div class="hero-copy">
  <span>فروش مستقیم مصالح برای پروژه‌های ساختمانی</span>
  <h1>همه مصالح پروژه شما<br>یکجا، با قیمت و موجودی شفاف</h1>
  <p>خرید مستقیم از فروشگاه، استعلام پروژه‌ای، فاکتور و ارسال به محل پروژه.</p>
  <div class="hero-btns"><a href="#products" class="primary-btn">مشاهده محصولات ←</a><button class="light-btn" data-open-quote>درخواست پیش‌فاکتور</button></div>
 </div><div class="smart-tag">ساخت بهتر<br>با انتخاب دقیق‌تر</div></div>
 <aside class="market-card">
  <div class="section-head"><div><b>قیمت‌های امروز</b><small>{{ \App\Support\Jalali::today() }}</small></div><span class="live-dot">● بروزشده</span></div>
  <div class="market-grid">@foreach($market as $item)
   <div class="market-item"><img src="{{ asset(ltrim($item->image,'/')) }}" alt="{{ $item->name }}"><div><b>{{ $item->name }}</b><small>{{ $item->brand ?: \App\Support\Store::get('store_name',config('store.name')) }}</small></div><strong>{{ $fa($item->price) }}</strong><span class="{{ $item->market_change>=0?'up':'down' }}">{{ $item->market_change>=0?'▲':'▼' }} {{ \App\Support\Jalali::digits(abs($item->market_change)) }}٪</span></div>
  @endforeach</div><a class="market-more" href="{{ route('home',['sort'=>'change']) }}">مشاهده همه قیمت‌ها ←</a>
 </aside>
</section>
<section id="categories" class="categories-row">@foreach($categories as $category)
 <a class="category-card" href="{{ route('home',['category'=>$category->slug]) }}"><img src="{{ asset(ltrim($category->image,'/')) }}" alt="{{ $category->name }}"><b>{{ $category->name }}</b><small>{{ \App\Support\Jalali::digits($category->products_count) }} محصول</small></a>
@endforeach</section>

<form class="catalog-filters" method="get" action="{{ route('home') }}">
 <div class="filter-search"><span>⌕</span><input name="q" value="{{ request('q') }}" placeholder="نام محصول، برند یا کد کالا"></div>
 <select name="category"><option value="">همه دسته‌بندی‌ها</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected(request('category')===$category->slug)>{{ $category->name }}</option>@endforeach</select>
 <select name="brand"><option value="">همه برندها</option>@foreach($brands as $brand)<option value="{{ $brand }}" @selected(request('brand')===$brand)>{{ $brand }}</option>@endforeach</select>
 <select name="sort"><option value="featured" @selected(request('sort','featured')==='featured')>پیشنهادی</option><option value="newest" @selected(request('sort')==='newest')>جدیدترین</option><option value="price_asc" @selected(request('sort')==='price_asc')>ارزان‌ترین</option><option value="price_desc" @selected(request('sort')==='price_desc')>گران‌ترین</option><option value="change" @selected(request('sort')==='change')>بیشترین نوسان</option></select>
 <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="حداقل قیمت">
 <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="حداکثر قیمت">
 <label class="stock-filter"><input type="checkbox" name="in_stock" value="1" @checked(request()->boolean('in_stock'))> فقط موجود</label>
 <button type="submit">اعمال فیلتر</button><a href="{{ route('home') }}">حذف فیلتر</a>
</form>

<section class="content-grid"><div id="products" class="featured">
 <div class="section-title"><div><h2>{{ $query ? 'نتایج جستجو برای «'.$query.'»' : 'محصولات پیشنهادی' }}</h2><p>کالاهای موجود فروشگاه با قیمت شفاف</p></div><a href="{{ route('home') }}">مشاهده همه ←</a></div>
 <div class="products-grid">@forelse($products->take(10) as $product)
  <article class="product-card">
   <form method="post" action="{{ route('wishlist.toggle',$product) }}">@csrf<button class="heart {{ in_array($product->id,$wishlist)?'active-heart':'' }}">{{ in_array($product->id,$wishlist)?'♥':'♡' }}</button></form>
   @if($product->low_stock)<span class="discount">موجودی محدود</span>@endif
   <a href="{{ route('product.show',$product) }}"><div class="product-visual"><img src="{{ asset(ltrim($product->image,'/')) }}" alt="{{ $product->name }}"></div><h3>{{ $product->name }}</h3></a>
   <small>{{ $product->brand ?: \App\Support\Store::get('store_name',config('store.name')) }} • {{ $product->loading_location ?: 'انبار مرکزی' }}</small>
   <strong>{{ $fa($product->price) }} ریال</strong>
   <div class="card-actions"><form method="post" action="{{ route('cart.add',$product) }}">@csrf<button class="add-btn">🛒 افزودن به سبد</button></form><form method="post" action="{{ route('compare.toggle',$product) }}">@csrf<button class="mini-outline">⇄</button></form></div>
  </article>
 @empty<div class="empty-state">محصولی با این فیلتر پیدا نشد.</div>@endforelse</div>
 </div>
 <aside class="calculator">
  <div class="calc-hero"><img src="{{ asset('assets/img/real/materials-overview.webp') }}"><div><h3>محاسبه سریع هزینه پروژه</h3><p>متراژ را وارد کنید تا برآورد اولیه مصالح نمایش داده شود.</p></div></div>
  <div class="calc-tabs"><button class="active" data-factor="15400000">ساخت خانه</button><button data-factor="11800000">بازسازی</button><button data-factor="6900000">دیوارچینی</button></div>
  <label>متراژ زیربنا<input id="project-area" type="number" min="1" value="250"></label>
  <label>نوع ساخت<select><option>ساخت معمولی</option><option>اقتصادی</option><option>لوکس</option></select></label>
  <button class="calculate-btn" id="calculate-project">محاسبه هزینه ←</button>
  <div class="estimate"><small>برآورد تقریبی</small><strong id="estimate-value">{{ $fa(250*15400000) }} ریال</strong></div>
 </aside>
</section>

<section class="benefits">
 <div><i>✓</i><b>فاکتور معتبر</b><small>شفافیت کامل خرید</small></div>
 <div><i>🚚</i><b>ارسال به پروژه</b><small>هماهنگی حمل و باربری</small></div>
 <div><i>٪</i><b>قیمت روز</b><small>قیمت و موجودی بروزشده</small></div>
 <div><i>☎</i><b>مشاوره تخصصی</b><small>قبل از خرید و پروژه</small></div>
</section>

<div class="quote-modal" id="quote-modal" hidden><div class="quote-dialog"><button class="quote-close" data-close-quote>×</button><h3>درخواست پیش‌فاکتور پروژه</h3><p>نام پروژه و برآورد بودجه را ثبت کنید؛ کارشناسان فروشگاه پیگیری می‌کنند.</p><form method="post" action="{{ route('quote.store') }}">@csrf<label>نام پروژه<input name="project_name" required></label><label>برآورد بودجه (ریال)<input type="number" min="0" name="estimated_amount"></label><button class="calculate-btn">ثبت درخواست</button></form></div></div>
@endsection