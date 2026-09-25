@extends('layouts.store')
@section('title','تکمیل خرید | '.config('store.name'))
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<section class="simple-page"><div class="page-hero-small"><div><span>Checkout</span><h1>تکمیل سفارش</h1><p>آدرس، روش ارسال و پرداخت را مشخص کنید.</p></div></div>
<form class="checkout-layout" method="post" action="{{ route('checkout.store') }}">@csrf
 <section class="panel checkout-form">
  <h2>اطلاعات تحویل</h2>
  @if($addresses->count())<div class="saved-addresses">@foreach($addresses as $a)<button type="button" data-fill-address='@json($a)'><b>{{ $a->title }}</b><small>{{ $a->city }}، {{ $a->address }}</small></button>@endforeach</div>@endif
  <div class="form-grid"><label>تحویل‌گیرنده<input name="recipient" value="{{ old('recipient',auth()->user()->name) }}" required></label><label>موبایل<input name="phone" value="{{ old('phone') }}" required></label><label>استان<input name="province" value="{{ old('province') }}" required></label><label>شهر<input name="city" value="{{ old('city') }}" required></label><label class="span2">آدرس<textarea name="shipping_address" required>{{ old('shipping_address') }}</textarea></label><label>کدپستی<input name="postal_code" value="{{ old('postal_code') }}"></label><label>نوع فاکتور<select name="invoice_type"><option value="personal">شخصی</option><option value="legal">حقوقی / رسمی</option></select></label></div>
  <h2>روش ارسال</h2>
  <div class="payment-options"><label><input type="radio" name="shipping_method" value="carrier" checked> باربری استاندارد</label><label><input type="radio" name="shipping_method" value="company"> ارسال با ناوگان شرکت</label><label><input type="radio" name="shipping_method" value="pickup"> تحویل حضوری از انبار</label></div>
  <h2>روش پرداخت</h2><div class="payment-options"><label><input type="radio" name="payment_method" value="online" checked> پرداخت آنلاین</label><label><input type="radio" name="payment_method" value="bank_transfer"> حواله بانکی</label><label><input type="radio" name="payment_method" value="on_delivery"> پرداخت هنگام تحویل</label></div>
  <label class="save-address"><input type="checkbox" name="save_address" value="1"> ذخیره این آدرس</label><label>توضیحات<textarea name="notes"></textarea></label>
 </section>
 <aside class="panel cart-summary"><h2>خلاصه خرید</h2>@foreach($items as $row)<div><span>{{ $row['product']->name }} × {{ $row['qty'] }}</span><b>{{ $fa($row['line_total']) }}</b></div>@endforeach<hr><div><span>جمع</span><b>{{ $fa($subtotal) }}</b></div><div><span>ارسال</span><b>{{ $shipping?$fa($shipping):'رایگان' }}</b></div><div class="grand-total"><span>مبلغ نهایی</span><strong>{{ $fa($subtotal+$shipping) }} ریال</strong></div><button class="primary-product-btn" type="submit">ثبت نهایی سفارش</button></aside>
</form></section>
@endsection