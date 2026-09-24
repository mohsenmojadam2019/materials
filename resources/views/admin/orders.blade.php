@extends('layouts.admin')
@section('title','مدیریت سفارش‌ها، استعلام‌ها و مالی')
@section('page-title','مدیریت سفارش‌ها، استعلام‌ها و مالی')
@section('page-subtitle','عملیات فروش، ارسال، پیش‌فاکتور و تسویه پروژه‌ها')
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<div class="kpi-grid">
 @foreach([
 ['سفارش‌های در حال پردازش','۳۲','↑ ۱۴٪','orange'],['سفارش‌های آماده ارسال','۱۹','↑ ۲۵٪','green'],['استعلام‌های جدید پروژه','۱۲','↑ ۷۱٪','blue'],
 ['فاکتورهای پرداخت‌نشده','۱۸','↓ ۸٪','red'],['مرسولات در مسیر','۲۷','↑ ۱۲٪','green'],['تسویه‌های در انتظار','۸','↓ ۶٪','gold']
 ] as [$t,$v,$n,$tone])<article class="kpi {{ $tone }}"><span>{{ $t }}</span><strong>{{ $v }}</strong><small>{{ $n }}</small></article>@endforeach
</div>
<div class="ops-grid">
 <section class="panel orders-ops">
  <div class="toolbar"><div class="tabs"><a class="{{ request('type')?'':'active' }}" href="{{ route('admin.orders') }}">همه سفارش‌ها</a><a class="{{ request('type')==='project'?'active':'' }}" href="{{ route('admin.orders',['type'=>'project']) }}">پروژه‌ای</a><a class="{{ request('type')==='wholesale'?'active':'' }}" href="{{ route('admin.orders',['type'=>'wholesale']) }}">عمده</a><a>مرجوعی</a></div><button class="main-action">+ ثبت سفارش</button></div>
  <div class="filters"><input placeholder="جستجو در سفارش‌ها ..."><select><option>همه نوع سفارش</option></select><select><option>همه وضعیت‌ها</option></select><input placeholder="از تاریخ"><input placeholder="تا تاریخ"></div>
  <div class="table-wrap"><table><thead><tr><th>کد سفارش</th><th>مشتری</th><th>نوع سفارش</th><th>مبلغ</th><th>وضعیت پرداخت</th><th>وضعیت ارسال</th><th>باربری</th><th>تاریخ</th><th>عملیات</th></tr></thead><tbody>
  @foreach($orders as $i=>$o)<tr><td>{{ $o->order_no }}</td><td><b>{{ optional($o->customer)->name }}</b></td><td><span class="type-tag">{{ ['project'=>'پروژه‌ای','wholesale'=>'عمده','retail'=>'خرده'][$o->type] ?? $o->type }}</span></td><td>{{ $fa($o->total) }} ریال</td><td><span class="{{ $o->payment_status==='paid'?'success':($o->payment_status==='unpaid'?'danger':'warn') }}">{{ ['paid'=>'پرداخت شده','unpaid'=>'پرداخت نشده','pending'=>'در انتظار پرداخت','partial'=>'پیش‌پرداخت'][$o->payment_status] ?? $o->payment_status }}</span></td><td><span class="status s{{ $i%5 }}">{{ ['ready'=>'آماده ارسال','preparing'=>'در حال آماده‌سازی','in_transit'=>'در مسیر','delivered'=>'تحویل شد'][$o->shipping_status] ?? $o->shipping_status }}</span></td><td>{{ $i%2?'باربری پارس':'باربری وطن' }}</td><td>{{ \App\Support\Jalali::date($o->ordered_at) }}</td><td><button class="mini-btn">مشاهده</button></td></tr>@endforeach
  </tbody></table></div>
 </section>
 <section class="panel overview-panel"><div class="panel-head"><h2>نمای کلی سفارش‌ها</h2><select><option>۳۰ روز گذشته</option></select></div><div class="mini-chart"><svg viewBox="0 0 420 190" preserveAspectRatio="none"><g class="grid-lines"><path d="M20 25H410M20 70H410M20 115H410M20 160H410"/></g><path d="M20 150 C50 116,82 140,112 105 S160 120,190 80 S230 104,260 67 S300 92,338 50 S382 70,410 40" fill="none" stroke="#0d725b" stroke-width="4"/></svg></div><div class="overview-stats"><div><small>مجموع سفارش‌ها</small><b>{{ $fa($orders->total()) }}</b></div><div><small>مطالبات باز</small><b>{{ $fa($receivables) }}</b></div><div><small>میانگین سفارش</small><b>۱۵۳.۸ میلیون</b></div></div></section>
</div>
<div class="ops-bottom">
 <section class="panel quotes-table"><div class="panel-head"><h2>استعلام‌های پروژه</h2><button class="main-action">+ ثبت استعلام جدید</button></div><div class="table-wrap"><table><thead><tr><th>نام پروژه</th><th>مشتری</th><th>مبلغ تقریبی</th><th>وضعیت</th><th>تاریخ</th><th>عملیات</th></tr></thead><tbody>
 @foreach($quotes as $i=>$q)<tr><td><b>{{ $q->project_name }}</b></td><td>{{ optional($q->customer)->name ?: 'مشتری جدید' }}</td><td>{{ $fa($q->estimated_amount) }} ریال</td><td><span class="status q{{ $i%4 }}">{{ ['new'=>'جدید','review'=>'در حال بررسی','quoted'=>'پیش‌فاکتور ارسال شد','negotiation'=>'مذاکره'][$q->status]??$q->status }}</span></td><td>{{ \App\Support\Jalali::date($q->requested_at) }}</td><td><button class="mini-btn">مشاهده</button></td></tr>@endforeach
 </tbody></table></div></section>
 <section class="panel crm-card"><div class="panel-head"><h2>اطلاعات مشتری</h2><a>مشاهده همه</a></div>
  @if($customer)<div class="crm-head"><div class="crm-avatar">س</div><div><b>{{ $customer->name }}</b><span>مشتری حقوقی</span><small>{{ $customer->city }}　•　{{ $customer->phone }}</small></div></div>
  <div class="credit"><div><small>سطح اعتباری</small><b class="grade">A</b></div><div><small>سقف اعتبار</small><b>{{ $fa($customer->credit_limit) }} ریال</b></div><div><small>تعداد سفارش</small><b class="ok">{{ $fa($customer->orders->count()) }}</b></div></div>
  <h3>خریدهای اخیر</h3>@foreach($customer->orders->take(3) as $o)<div class="crm-purchase"><span>▣</span><div><b>{{ $o->order_no }}</b><small>{{ ['ready'=>'آماده ارسال','preparing'=>'آماده‌سازی','in_transit'=>'در حال ارسال','delivered'=>'تحویل شد'][$o->shipping_status]??$o->shipping_status }}</small></div><strong>{{ $fa($o->total) }}</strong></div>@endforeach @endif
 </section>
 <section class="panel finance-card"><div class="panel-head"><h2>وضعیت مالی و نقدینگی</h2><button>گزارش مالی</button></div><div class="finance-nums"><div><small>درآمد کل</small><b>۲۸,۷۶۰,۰۰۰,۰۰۰ ریال</b><span class="ok">↑ ۱۸٪</span></div><div><small>دریافت‌ها</small><b>۱۹,۲۵۰,۰۰۰,۰۰۰ ریال</b><span class="ok">↑ ۲۴٪</span></div><div><small>مبالغ معوق</small><b>{{ $fa($receivables) }} ریال</b><span class="bad">↓ ۸٪</span></div></div><div class="cash-bars"><div><span>دریافت‌شده</span><i style="width:67%"></i><b>۶۷٪</b></div><div><span>در انتظار پرداخت</span><i class="orange" style="width:15%"></i><b>۱۵٪</b></div><div><span>سررسید نشده</span><i class="gray" style="width:18%"></i><b>۱۸٪</b></div></div></section>
</div>
@endsection
