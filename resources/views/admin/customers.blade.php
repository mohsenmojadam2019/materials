@extends('layouts.admin')
@section('title','مشتریان و CRM')
@section('page-title','مشتریان و CRM')
@section('page-subtitle','مشتریان حقیقی، حقوقی و سابقه خرید')
@section('content')
<div class="kpi-grid four"><article class="kpi green"><span>تعداد مشتریان</span><strong>{{ \App\Support\Jalali::digits($customers->count()) }}</strong></article><article class="kpi blue"><span>حقوقی</span><strong>{{ \App\Support\Jalali::digits($customers->where('type','legal')->count()) }}</strong></article><article class="kpi orange"><span>حقیقی</span><strong>{{ \App\Support\Jalali::digits($customers->where('type','individual')->count()) }}</strong></article><article class="kpi purple"><span>مجموع اعتبار</span><strong>{{ \App\Support\Jalali::digits(number_format($customers->sum('credit_limit'))) }}</strong></article></div>
<section class="panel data-panel"><div class="table-wrap"><table><thead><tr><th>نام</th><th>نوع</th><th>شرکت</th><th>تلفن</th><th>شهر</th><th>اعتبار</th><th>سفارش‌ها</th></tr></thead><tbody>@foreach($customers as $c)<tr><td><b>{{ $c->name }}</b></td><td>{{ $c->type }}</td><td>{{ $c->company ?: '—' }}</td><td>{{ $c->phone }}</td><td>{{ $c->city }}</td><td>{{ \App\Support\Jalali::digits(number_format($c->credit_limit)) }}</td><td>{{ \App\Support\Jalali::digits($c->orders_count) }}</td></tr>@endforeach</tbody></table></div></section>
@endsection