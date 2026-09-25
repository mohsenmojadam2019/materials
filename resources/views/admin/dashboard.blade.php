@extends('layouts.admin')
@section('title','داشبورد مدیریت ساختمان‌یار')
@section('page-title','داشبورد مدیریت')
@section('page-subtitle','وضعیت فروش، سفارش، موجودی و استعلام‌ها در یک نگاه')
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<div class="kpi-grid">
 @foreach([
  ['فروش امروز',$fa($salesToday).' ریال','↑ ۱۲٪ نسبت به روز گذشته','green'],
  ['سفارش‌های باز','۲۸ سفارش','↑ ۷٪ در انتظار پردازش','orange'],
  ['هشدار کمبود موجودی',\App\Support\Jalali::digits($lowStock->count()).' کالا','نیاز به تأمین','red'],
  ['استعلام‌های پروژه',\App\Support\Jalali::digits($quotes->count()).' استعلام','↑ ۲۵٪ این ماه','blue'],
  ['مطالبات باز',$fa($receivables).' ریال','از مشتریان حقوقی','purple'],
  ['فروش ماهانه',$fa($salesMonth).' ریال','↑ ۱۸٪ نسبت به ماه قبل','gold'],
 ] as [$t,$v,$n,$tone])
 <article class="kpi {{ $tone }}"><span>{{ $t }}</span><strong>{{ $v }}</strong><small>{{ $n }}</small></article>
 @endforeach
</div>

<div class="dash-grid">
 <section class="panel sales-panel">
  <div class="panel-head"><h2>نمودار فروش</h2><select><option>ماه اخیر</option></select></div>
  <div class="chart-area"><svg viewBox="0 0 680 260" preserveAspectRatio="none"><defs><linearGradient id="salesFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#0d725b" stop-opacity=".22"/><stop offset="1" stop-color="#0d725b" stop-opacity="0"/></linearGradient></defs><g class="grid-lines"><path d="M30 30H660M30 85H660M30 140H660M30 195H660M30 245H660"/></g><path d="M30 218 C80 195,100 204,145 176 S220 180,260 155 S320 165,365 128 S425 155,470 122 S540 142,575 104 S625 117,660 87 L660 245L30 245Z" fill="url(#salesFill)"/><path d="M30 218 C80 195,100 204,145 176 S220 180,260 155 S320 165,365 128 S425 155,470 122 S540 142,575 104 S625 117,660 87" fill="none" stroke="#0d725b" stroke-width="4"/></svg><div class="chart-axis"><span>۱ مهر</span><span>۵ مهر</span><span>۱۰ مهر</span><span>۱۵ مهر</span><span>۲۰ مهر</span><span>۲۵ مهر</span><span>۳۰ مهر</span></div></div>
 </section>
 <section class="panel mix-panel"><div class="panel-head"><h2>ترکیب فروش بر اساس دسته‌بندی</h2></div><div class="donut"></div><div class="mix-legend"><span><i class="c1"></i> سیمان و مصالح بنایی ۳۲٪</span><span><i class="c2"></i> آهن‌آلات ۲۶٪</span><span><i class="c3"></i> سنگ و سرامیک ۱۸٪</span><span><i class="c4"></i> تأسیسات ۱۲٪</span><span><i class="c5"></i> سایر ۱۲٪</span></div></section>
 <section class="panel price-panel"><div class="panel-head"><h2>قیمت‌های روز بازار</h2><a>مشاهده همه</a></div>
  @foreach($market as $p)<div class="price-row"><img src="{{ asset(ltrim($p->image,'/')) }}"><div><b>{{ $p->name }}</b><small>{{ optional($p->supplier)->name }}</small></div><strong>{{ $fa($p->price) }}</strong><span class="{{ $p->market_change>=0?'ok':'bad' }}">{{ $p->market_change>=0?'↑':'↓' }} {{ \App\Support\Jalali::digits(abs($p->market_change)) }}٪</span></div>@endforeach
 </section>
</div>
<div class="dash-grid bottom">
 <section class="panel orders-panel"><div class="panel-head"><h2>آخرین سفارش‌ها</h2><a href="{{ route('admin.orders') }}">مشاهده همه سفارش‌ها</a></div><div class="table-wrap"><table><thead><tr><th>شماره سفارش</th><th>خریدار</th><th>مبلغ</th><th>وضعیت</th><th>تاریخ ثبت</th><th>عملیات</th></tr></thead><tbody>
 @foreach($orders as $i=>$o)<tr><td>{{ $o->order_no }}</td><td>{{ optional($o->customer)->name }}</td><td>{{ $fa($o->total) }} ریال</td><td><span class="status s{{ $i%5 }}">{{ ['ready'=>'آماده ارسال','preparing'=>'در حال آماده‌سازی','in_transit'=>'در مسیر','delivered'=>'تحویل شد'][$o->shipping_status] ?? $o->shipping_status }}</span></td><td>{{ \App\Support\Jalali::date($o->ordered_at) }}</td><td><button class="mini-btn">مشاهده</button></td></tr>@endforeach
 </tbody></table></div></section>
 <section class="panel stock-panel"><div class="panel-head"><h2>کالاهای کم‌موجودی</h2><a href="{{ route('admin.products') }}">مشاهده همه</a></div>
  @foreach($lowStock as $p)<div class="stock-row"><img src="{{ asset(ltrim($p->image,'/')) }}"><div><b>{{ $p->name }}</b><small>{{ optional($p->category)->name }}</small></div><strong>{{ \App\Support\Jalali::digits($p->stock) }}</strong><span class="{{ $p->stock<20?'danger':'warn' }}">{{ $p->stock<20?'کمبود موجودی':'در آستانه اتمام' }}</span></div>@endforeach
 </section>
 <section class="panel quote-panel"><div class="panel-head"><h2>آخرین استعلام‌های پروژه</h2><a href="{{ route('admin.quotes') }}">مشاهده همه</a></div>
  @foreach($quotes as $i=>$q)<div class="quote-row"><div><b>{{ $q->project_name }}</b><small>{{ optional($q->customer)->name ?: 'مشتری جدید' }}</small></div><span class="status q{{ $i%4 }}">{{ ['new'=>'جدید','review'=>'در حال بررسی','quoted'=>'پیشنهاد ارسال شد','negotiation'=>'مذاکره'][$q->status] ?? $q->status }}</span><small>{{ \App\Support\Jalali::date($q->requested_at) }}</small></div>@endforeach
 </section>
</div>
<section class="quick-banner"><div><b>مدیریت هوشمند کسب‌وکار مصالح ساختمانی</b><small>از تأمین و قیمت‌گذاری تا فروش، انبار و لجستیک در یک پنل یکپارچه</small></div><div class="quick-actions"><a href="{{ route('admin.products') }}">+ افزودن محصول</a><a href="{{ route('admin.orders') }}">+ ثبت سفارش</a><a href="{{ route('admin.reports') }}">گزارش فروش</a><a href="{{ route('admin.finance') }}">گزارش مالی</a></div></section>
@endsection
