@extends('layouts.admin')
@section('title','تنظیمات فروشگاه')
@section('page-title','تنظیمات White-label')
@section('page-subtitle','نام، اطلاعات تماس و رنگ سازمانی شرکت')
@section('content')
<section class="panel settings-card"><form method="post" action="{{ route('admin.settings.save') }}" enctype="multipart/form-data">@csrf<div class="form-grid">
<label>نام شرکت / فروشگاه<input name="store_name" value="{{ $settings['store_name'] ?? config('store.name') }}" required></label>
<label>تلفن<input name="store_phone" value="{{ $settings['store_phone'] ?? config('store.phone') }}" required></label>
<label>ایمیل<input type="email" name="store_email" value="{{ $settings['store_email'] ?? config('store.email') }}" required></label>
<label>رنگ سازمانی<input type="color" name="primary_color" value="{{ $settings['primary_color'] ?? config('store.primary_color') }}" required></label>
<label>لوگوی شرکت<input type="file" name="store_logo" accept="image/*"></label>
<label class="span2">آدرس<textarea name="store_address" required>{{ $settings['store_address'] ?? config('store.address') }}</textarea></label>
</div><button class="main-action">ذخیره تنظیمات</button></form></section>
@endsection