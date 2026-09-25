@extends('layouts.store')
@section('title','حساب کاربری | '.config('store.name'))
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<section class="simple-page">
 <div class="account-head"><div><span>حساب مشتری</span><h1>سلام {{ auth()->user()->name }}</h1><p>سفارش‌ها، آدرس‌ها و درخواست‌های سازمانی را مدیریت کنید.</p></div><div class="account-actions"><a href="{{ route('account.profile') }}">پروفایل</a><a href="{{ route('account.addresses') }}">آدرس‌ها</a><a href="{{ route('account.orders') }}">سفارش‌های من</a><a href="{{ route('b2b.create','wholesale') }}">خرید عمده</a><a href="{{ route('b2b.create','credit') }}">فروش اعتباری</a></div></div>
 <div class="account-kpis"><div><span>تعداد سفارش‌ها</span><b>{{ $fa(auth()->user()->orders()->count()) }}</b></div><div><span>در حال پردازش</span><b>{{ $fa(auth()->user()->orders()->where('status','processing')->count()) }}</b></div><div><span>آدرس‌ها</span><b>{{ $fa(auth()->user()->addresses()->count()) }}</b></div></div>
 <section class="panel data-panel"><div class="panel-head"><h2>آخرین سفارش‌ها</h2><a href="{{ route('account.orders') }}">مشاهده همه</a></div><div class="table-wrap"><table><thead><tr><th>کد سفارش</th><th>مبلغ</th><th>پرداخت</th><th>ارسال</th><th>تاریخ</th><th></th></tr></thead><tbody>@forelse($orders as $o)<tr><td>{{ $o->order_no }}</td><td>{{ $fa($o->total) }} ریال</td><td>{{ $o->payment_status }}</td><td>{{ $o->shipping_status }}</td><td>{{ \App\Support\Jalali::date($o->ordered_at) }}</td><td><a class="mini-btn" href="{{ route('account.order',$o) }}">جزئیات</a></td></tr>@empty<tr><td colspan="6">سفارشی ندارید.</td></tr>@endforelse</tbody></table></div></section>
</section>
@endsection