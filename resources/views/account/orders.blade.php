@extends('layouts.store')
@section('title','سفارش‌های من | '.config('store.name'))
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<section class="simple-page"><div class="page-hero-small"><div><span>حساب مشتری</span><h1>سفارش‌های من</h1><p>سوابق خرید و وضعیت ارسال سفارش‌ها.</p></div></div><section class="panel data-panel"><div class="table-wrap"><table><thead><tr><th>کد</th><th>مبلغ</th><th>وضعیت</th><th>پرداخت</th><th>ارسال</th><th>تاریخ</th><th></th></tr></thead><tbody>@foreach($orders as $o)<tr><td>{{ $o->order_no }}</td><td>{{ $fa($o->total) }} ریال</td><td>{{ $o->status }}</td><td>{{ $o->payment_status }}</td><td>{{ $o->shipping_status }}</td><td>{{ \App\Support\Jalali::date($o->ordered_at) }}</td><td><a class="mini-btn" href="{{ route('account.order',$o) }}">مشاهده</a></td></tr>@endforeach</tbody></table></div>{{ $orders->links() }}</section></section>
@endsection