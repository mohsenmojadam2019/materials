@extends('layouts.store')
@section('title','سبد خرید | '.config('store.name'))
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<section class="simple-page">
 <div class="page-hero-small"><div><span>سبد خرید</span><h1>سبد خرید شما</h1><p>تعداد کالاها را بررسی و سپس وارد مرحله پرداخت شوید.</p></div><a href="{{ route('home') }}">ادامه خرید ←</a></div>
 @if(count($items))
 <div class="cart-layout">
  <section class="panel cart-list">
   @foreach($items as $row)
   <div class="cart-row">
    <a href="{{ route('product.show',$row['product']) }}"><img src="{{ asset(ltrim($row['product']->image,'/')) }}"></a>
    <div><a href="{{ route('product.show',$row['product']) }}"><b>{{ $row['product']->name }}</b></a><small>{{ $row['product']->brand }} • {{ $row['product']->loading_location }}</small></div>
    <form method="post" action="{{ route('cart.update',$row['product']) }}">@csrf @method('PATCH')<input type="number" name="qty" min="0" max="{{ $row['product']->stock }}" value="{{ $row['qty'] }}"><button>بروزرسانی</button></form>
    <strong>{{ $fa($row['line_total']) }} ریال</strong>
    <form method="post" action="{{ route('cart.remove',$row['product']) }}">@csrf @method('DELETE')<button class="danger-link">حذف</button></form>
   </div>
   @endforeach
  </section>
  <aside class="panel cart-summary"><h2>خلاصه سفارش</h2><div><span>جمع کالاها</span><b>{{ $fa($subtotal) }} ریال</b></div><div><span>هزینه ارسال</span><b>{{ $subtotal>=500000000?'رایگان':'در مرحله بعد' }}</b></div><a class="primary-product-btn" href="{{ route('checkout') }}">ادامه و ثبت سفارش</a><form method="post" action="{{ route('cart.clear') }}">@csrf @method('DELETE')<button class="secondary-product-btn">پاک کردن سبد</button></form></aside>
 </div>
 @else
 <div class="empty-state large-empty"><h3>سبد خرید خالی است</h3><a class="primary-btn" href="{{ route('home') }}">مشاهده محصولات</a></div>
 @endif
</section>
@endsection