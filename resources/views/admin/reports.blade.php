@extends('layouts.admin')
@section('title','گزارش‌ها')
@section('page-title','گزارش‌ها و تحلیل عملکرد')
@section('page-subtitle','فروش، موجودی و مطالبات در یک نگاه')
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<div class="kpi-grid four"><article class="kpi green"><span>ارزش موجودی</span><strong>{{ $fa($inventoryValue) }} ریال</strong></article><article class="kpi red"><span>مطالبات باز</span><strong>{{ $fa($receivable) }} ریال</strong></article><article class="kpi blue"><span>روزهای گزارش</span><strong>{{ \App\Support\Jalali::digits($sales->count()) }}</strong></article></div>
<section class="panel data-panel"><div class="toolbar"><h2>فروش روزانه</h2><a class="main-action" href="{{ route('admin.orders.export') }}">خروجی CSV سفارش‌ها</a></div><div class="table-wrap"><table><thead><tr><th>تاریخ</th><th>تعداد سفارش</th><th>مبلغ فروش</th></tr></thead><tbody>@foreach($sales as $s)<tr><td>{{ $s->d }}</td><td>{{ \App\Support\Jalali::digits($s->count) }}</td><td>{{ $fa($s->total) }} ریال</td></tr>@endforeach</tbody></table></div></section>
@endsection