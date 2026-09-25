@extends('layouts.store')
@section('title','وبلاگ مصالح ساختمانی | '.config('store.name'))
@section('meta_description','راهنمای خرید، قیمت، اجرای پروژه و انتخاب مصالح ساختمانی')
@section('content')
<section class="simple-page"><div class="page-hero-small"><div><span>مجله تخصصی</span><h1>وبلاگ ساختینو</h1><p>راهنمای خرید، قیمت، اجرا و انتخاب مصالح ساختمانی.</p></div><a href="{{ route('faq') }}">سوالات متداول ←</a></div><div class="blog-grid">@forelse($articles as $a)<article class="panel blog-card"><span>{{ $a->category }}</span><h2><a href="{{ route('article',$a) }}">{{ $a->title }}</a></h2><p>{{ $a->excerpt }}</p><small>{{ $a->published_at?\App\Support\Jalali::date($a->published_at):'' }}</small><a class="mini-btn" href="{{ route('article',$a) }}">ادامه مطلب</a></article>@empty<div class="empty-state">مقاله‌ای منتشر نشده است.</div>@endforelse</div>{{ $articles->links() }}</section>
@endsection