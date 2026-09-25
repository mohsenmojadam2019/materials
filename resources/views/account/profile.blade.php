@extends('layouts.store')
@section('title','پروفایل | '.config('store.name'))
@section('content')
<section class="simple-page"><div class="page-hero-small"><div><span>حساب کاربری</span><h1>پروفایل من</h1><p>اطلاعات اصلی حساب خود را ویرایش کنید.</p></div></div><form class="panel profile-form" method="post" action="{{ route('account.profile.update') }}">@csrf @method('PATCH')<label>نام و نام خانوادگی<input name="name" value="{{ old('name',auth()->user()->name) }}" required></label><label>ایمیل<input type="email" name="email" value="{{ old('email',auth()->user()->email) }}" required></label><button class="primary-product-btn">ذخیره تغییرات</button></form></section>
@endsection