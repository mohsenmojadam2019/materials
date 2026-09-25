@extends('layouts.admin')
@section('title','مالی و مطالبات')
@section('page-title','مالی، مطالبات و تسویه')
@section('page-subtitle','دریافت‌ها، بدهی‌ها و وضعیت تسویه سفارش‌ها')
@section('content')
<div class="kpi-grid four"><article class="kpi green"><span>وصول‌شده</span><strong>{{ \App\Support\Jalali::digits(number_format($paid)) }} ریال</strong></article><article class="kpi red"><span>مطالبات باز</span><strong>{{ \App\Support\Jalali::digits(number_format($receivable)) }} ریال</strong></article></div>
<section class="panel data-panel"><div class="table-wrap"><table><thead><tr><th>سفارش</th><th>نوع</th><th>مبلغ</th><th>وضعیت</th><th>سررسید</th></tr></thead><tbody>@foreach($transactions as $t)<tr><td>{{ optional($t->order)->order_no ?: '—' }}</td><td>{{ $t->type }}</td><td>{{ \App\Support\Jalali::digits(number_format($t->amount)) }} ریال</td><td><span class="{{ $t->status==='paid'?'success':'warn' }}">{{ $t->status }}</span></td><td>{{ $t->due_date }}</td></tr>@endforeach</tbody></table></div></section>
@endsection