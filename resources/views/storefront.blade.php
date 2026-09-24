@extends('layouts.store')
@section('title','ساختینو | بازار آنلاین مصالح ساختمانی')
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<section class="hero-wrap">
 <div class="hero">
  <div class="hero-shade"></div>
  <div class="hero-copy">
   <span>تأمین مستقیم برای پروژه‌های ساختمانی</span>
   <h1>همه مصالح ساختمانی<br>در یک بازار، با بهترین قیمت</h1>
   <p>مقایسه قیمت، خرید مطمئن و تحویل سریع از تأمین‌کنندگان معتبر سراسر کشور.</p>
   <div class="hero-btns"><a href="#products" class="primary-btn">مشاهده محصولات ←</a><button class="light-btn" data-open-quote>درخواست پیش‌فاکتور</button></div>
  </div>
  <div class="smart-tag">ساخت بهتر<br>با انتخاب هوشمندانه</div>
 </div>
 <aside class="market-card">
  <div class="section-head"><div><b>نبض بازار مصالح امروز</b><small>{{ \App\Support\Jalali::today() }}</small></div><span class="live-dot">● زنده</span></div>
  <div class="market-grid">
   @foreach($market as $item)
    <div class="market-item">
     <img src="{{ asset(ltrim($item->image,'/')) }}" alt="{{ $item->name }}">
     <div><b>{{ $item->name }}</b><small>{{ optional($item->supplier)->name }}</small></div>
     <strong>{{ $fa($item->price) }}</strong>
     <span class="{{ $item->market_change>=0?'up':'down' }}">{{ $item->market_change>=0?'▲':'▼' }} {{ \App\Support\Jalali::digits(abs($item->market_change)) }}٪</span>
    </div>
   @endforeach
  </div>
  <button class="market-more">مشاهده کامل قیمت‌ها و تحلیل بازار ←</button>
 </aside>
</section>

<section id="categories" class="categories-row">
 @foreach($categories as $category)
  <a class="category-card" href="{{ route('home',['q'=>$category->name]) }}">
   <img src="{{ asset(ltrim($category->image,'/')) }}" alt="{{ $category->name }}">
   <b>{{ $category->name }}</b><small>{{ \App\Support\Jalali::digits($category->products_count) }} محصول</small>
  </a>
 @endforeach
</section>
<section class="content-grid">
 <div id="products" class="featured">
  <div class="section-title"><div><h2>{{ $query ? 'نتایج جستجو برای «'.$query.'»' : 'پیشنهادهای ویژه برای شما' }}</h2><p>محصولات پرفروش با قیمت رقابتی از تأمین‌کنندگان معتبر</p></div><a href="{{ route('home') }}">مشاهده همه ←</a></div>
  <div class="products-grid">
   @forelse($products->take(10) as $product)
    <article class="product-card">
     <button class="heart">♡</button>
     @if($product->low_stock)<span class="discount">موجودی محدود</span>@endif
     <div class="product-visual"><img src="{{ asset(ltrim($product->image,'/')) }}" alt="{{ $product->name }}"></div>
     <h3>{{ $product->name }}</h3><small>{{ optional($product->supplier)->name }}</small>
     <strong>{{ $fa($product->price) }} ریال</strong>
     <form method="post" action="{{ route('cart.add',$product) }}">@csrf<button class="add-btn">🛒 افزودن به سبد</button></form>
    </article>
   @empty
    <div class="empty-state">محصولی با این عبارت پیدا نشد.</div>
   @endforelse
  </div>
 </div>
 <aside class="calculator">
  <div class="calc-hero"><img src="{{ asset('assets/img/banners/hero-home.svg') }}" alt="پروژه ساختمانی"><div><h3>محاسبه سریع هزینه پروژه</h3><p>متراژ را وارد کنید تا برآورد اولیه هزینه مصالح محاسبه شود.</p></div></div>
  <div class="calc-tabs"><button class="active" data-factor="15400000">ساخت خانه</button><button data-factor="11800000">بازسازی</button><button data-factor="6900000">دیوارچینی</button></div>
  <label>متراژ زیربنا (مترمربع)<input id="project-area" type="number" min="1" value="250"></label>
  <label>نوع ساخت<select><option>ساخت معمولی</option><option>اقتصادی</option><option>لوکس</option></select></label>
  <button class="calculate-btn" id="calculate-project">محاسبه هزینه پروژه ←</button>
  <div class="estimate"><small>برآورد تقریبی هزینه مصالح</small><strong id="estimate-value">{{ $fa(250*15400000) }} ریال</strong></div>
 </aside>
</section>

<section class="benefits">
 <div><i>✓</i><b>تضمین اصالت کالا</b><small>خرید مطمئن از تأمین‌کنندگان معتبر</small></div>
 <div><i>🚚</i><b>ارسال سریع سراسر کشور</b><small>تحویل در کوتاه‌ترین زمان</small></div>
 <div><i>٪</i><b>مقایسه قیمت هوشمند</b><small>بهترین قیمت از چند فروشنده</small></div>
 <div><i>☎</i><b>پشتیبانی تخصصی</b><small>مشاوره رایگان قبل از خرید</small></div>
</section>

<div class="quote-modal" id="quote-modal" hidden>
 <div class="quote-dialog"><button class="quote-close" data-close-quote>×</button><h3>درخواست استعلام پروژه</h3><p>نام پروژه و برآورد اولیه را ثبت کنید؛ درخواست مستقیماً وارد پنل مدیریت می‌شود.</p>
  <form method="post" action="{{ route('quote.store') }}">@csrf
   <label>نام پروژه<input name="project_name" required placeholder="مثلاً مجتمع مسکونی مهر"></label>
   <label>برآورد بودجه مصالح (ریال)<input type="number" min="0" name="estimated_amount" placeholder="8500000000"></label>
   <button class="calculate-btn">ثبت درخواست استعلام</button>
  </form>
 </div>
</div>
@endsection
