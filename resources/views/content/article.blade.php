@extends('layouts.store')
@section('title',$article->title.' | '.config('store.name'))
@section('meta_description',$article->excerpt)
@section('content')
<article class="simple-page article-page"><div class="product-breadcrumb"><a href="{{ route('home') }}">خانه</a> / <a href="{{ route('blog') }}">وبلاگ</a> / {{ $article->title }}</div><header><span>{{ $article->category }}</span><h1>{{ $article->title }}</h1><p>{{ $article->excerpt }}</p><small>{{ $article->published_at?\App\Support\Jalali::date($article->published_at):'' }}</small></header><div class="panel article-body">{!! nl2br(e($article->body)) !!}</div></article>
@endsection