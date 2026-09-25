@extends('layouts.store')

@section('title', $product->name.' | '.\App\Support\Store::get('store_name', config('store.name')))
@section('meta_description', $product->description ?: $product->name)

@push('head')
<script type="application/ld+json">
{
  "@@context":"https://schema.org",
  "@type":"Product",
  "name":@json($product->name),
  "sku":@json($product->sku),
  "brand":{"@type":"Brand","name":@json($product->brand ?: \App\Support\Store::get('store_name', config('store.name')))},
  "offers":{
    "@type":"Offer",
    "priceCurrency":"IRR",
    "price":"{{ $product->price }}",
    "availability":"{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
    "url":@json(route('product.show', $product))
  }
}
</script>
@endpush

@section('content')
@php
    $fa = fn($n) => \App\Support\Jalali::digits(number_format($n));
    $showTax = request()->boolean('tax');
    $displayPrice = $showTax ? $product->price_with_tax : $product->price;
@endphp

<section class="product-page">
    <div class="product-breadcrumb">
        <a href="{{ route('home') }}">خانه</a> /
        {{ $product->category->name }} /
        {{ $product->name }}
    </div>

    <div class="product-main">
        <div class="product-gallery">
            <div class="product-main-image">
                <img src="{{ asset(ltrim($product->image, '/')) }}" alt="{{ $product->name }}">
            </div>
            <div class="product-badges">
                <span>✓ اصالت کالا</span>
                <span>{{ $fa($product->stock) }} {{ $product->unit }} موجود</span>
                <span>{{ $product->loading_location ?: 'انبار مرکزی' }}</span>
            </div>
        </div>

        <div class="product-info">
            <div class="product-meta">{{ $product->category->name }} • {{ $product->brand }} • {{ $product->sku }}</div>
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->description }}</p>

            <div class="product-status">
                <span class="{{ $product->stock > 0 ? 'available' : 'unavailable' }}">
                    {{ $product->stock > 0 ? '● موجود' : 'ناموجود' }}
                </span>
                <span>ساخت: {{ $product->origin ?: 'ایران' }}</span>
            </div>

            <div class="price-box">
                <div>
                    <small>قیمت {{ $showTax ? 'با' : 'بدون' }} مالیات</small>
                    <strong>{{ $fa($displayPrice) }} ریال</strong>
                    @if($product->old_price)
                        <del>{{ $fa($product->old_price) }}</del>
                    @endif
                </div>
                <div class="tax-switch">
                    <a class="{{ !$showTax ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['tax'=>0]) }}">بدون مالیات</a>
                    <a class="{{ $showTax ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['tax'=>1]) }}">با مالیات</a>
                </div>
            </div>

            <div class="product-actions">
                <form method="post" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <input class="qty-input" type="number" name="qty" value="1" min="1" max="{{ $product->stock }}">
                    <button class="primary-product-btn">🛒 افزودن به سبد</button>
                </form>
                <form method="post" action="{{ route('wishlist.toggle', $product) }}">
                    @csrf
                    <button class="secondary-product-btn">♡ علاقه‌مندی</button>
                </form>
                <form method="post" action="{{ route('compare.toggle', $product) }}">
                    @csrf
                    <button class="secondary-product-btn">⇄ مقایسه</button>
                </form>
            </div>

            <button class="quote-product-btn" data-open-quote>درخواست قیمت پروژه‌ای</button>
        </div>
    </div>

    <div class="product-detail-grid">
        <section class="detail-card">
            <h2>مشخصات فنی</h2>
            <div class="spec-grid">
                @foreach(($product->specs ?? []) as $key => $value)
                    <div><span>{{ $key }}</span><b>{{ $value }}</b></div>
                @endforeach
                <div><span>واحد فروش</span><b>{{ $product->unit }}</b></div>
                <div><span>برند</span><b>{{ $product->brand }}</b></div>
            </div>
        </section>

        <section class="detail-card">
            <h2>موجودی و محل بارگیری</h2>
            <div class="warehouse-list">
                @forelse($product->warehouseStocks as $stock)
                    <div>
                        <div>
                            <b>{{ $stock->warehouse->name }}</b>
                            <small>{{ $stock->warehouse->city }} • {{ $stock->warehouse->address }}</small>
                        </div>
                        <strong>{{ $fa($stock->qty) }} {{ $product->unit }}</strong>
                    </div>
                @empty
                    <div class="empty-state">موجودی از انبار مرکزی تأمین می‌شود.</div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="detail-card price-history-card">
        <div class="detail-head">
            <div><h2>تاریخچه قیمت</h2><p>روند قیمت ثبت‌شده</p></div>
        </div>

        @if($product->priceHistories->count())
            @php($maxPrice = max(1, $product->priceHistories->max('price')))
            <div class="price-history-bars">
                @foreach($product->priceHistories as $history)
                    <div class="price-bar-item">
                        <div class="price-bar" style="height:{{ max(18, round($history->price / $maxPrice * 150)) }}px"></div>
                        <small>{{ \App\Support\Jalali::date($history->recorded_at) }}</small>
                        <b>{{ $fa($history->price) }}</b>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">تاریخچه قیمت هنوز ثبت نشده است.</div>
        @endif
    </section>
</section>

<div class="quote-modal" id="quote-modal" hidden>
    <div class="quote-dialog">
        <button class="quote-close" data-close-quote>×</button>
        <h3>درخواست پیش‌فاکتور</h3>
        <form method="post" action="{{ route('quote.store') }}">
            @csrf
            <input type="hidden" name="project_name" value="استعلام {{ $product->name }}">
            <label>برآورد بودجه
                <input type="number" name="estimated_amount" min="0">
            </label>
            <button class="calculate-btn">ثبت درخواست</button>
        </form>
    </div>
</div>
@endsection