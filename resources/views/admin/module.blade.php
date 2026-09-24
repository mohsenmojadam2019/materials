@extends('layouts.admin')
@section('title',$title)
@section('page-title',$title)
@section('page-subtitle',$desc)
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<section class="module-hero">
 <div><span>ماژول عملیاتی</span><h2>{{ $title }}</h2><p>{{ $desc }}</p><button class="main-action">+ عملیات جدید</button></div>
 <div class="module-stats"><div><small>رکورد فعال</small><b>{{ $fa($count) }}</b></div><div><small>بروزرسانی امروز</small><b>۴۲</b></div><div><small>ارزش مرتبط</small><b>۸,۴۵۰,۰۰۰,۰۰۰ ریال</b></div></div>
</section>
<section class="panel module-panel">
 <div class="panel-head"><h2>نمای مدیریتی {{ $title }}</h2><div><button>خروجی اکسل</button> <button>فیلترها</button></div></div>
 <div class="generic-cards">
  @foreach($products as $p)<article><img src="{{ asset(ltrim($p->image,'/')) }}"><b>{{ $p->name }}</b><small>{{ optional($p->supplier)->name }}</small><strong>{{ $fa($p->price) }} ریال</strong><button class="mini-btn">مشاهده جزئیات</button></article>@endforeach
 </div>
</section>
<section class="panel module-list">
 <div class="panel-head"><h2>آخرین فعالیت‌ها</h2><a>مشاهده همه</a></div>
 <div class="activity-grid">
  @for($i=1;$i<=8;$i++)<div><span class="activity-icon">{{ $i%3===0?'✓':'▦' }}</span><div><b>بروزرسانی {{ $title }}</b><small>عملیات شماره {{ \App\Support\Jalali::digits($i) }} با موفقیت ثبت شد.</small></div><time>{{ \App\Support\Jalali::today() }}</time></div>@endfor
 </div>
</section>
@endsection