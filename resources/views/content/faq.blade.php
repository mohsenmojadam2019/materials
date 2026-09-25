@extends('layouts.store')
@section('title','سوالات متداول | '.config('store.name'))
@section('content')
<section class="simple-page"><div class="page-hero-small"><div><span>راهنما</span><h1>سوالات متداول</h1><p>پاسخ سوالات رایج درباره خرید، ارسال و استعلام پروژه.</p></div></div><div class="faq-list">@foreach($faqs as $f)<details class="panel"><summary>{{ $f->question }}</summary><p>{{ $f->answer }}</p></details>@endforeach</div></section>
@endsection