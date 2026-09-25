@extends('layouts.admin')
@section('title','ویرایش محصول')
@section('page-title','ویرایش محصول')
@section('page-subtitle',$product->name)
@section('content')
<section class="panel settings-card">
 <form method="post" action="{{ route('admin.products.update',$product) }}" enctype="multipart/form-data">@csrf @method('PATCH')
  <div class="form-grid">
   <label>نام محصول<input name="name" value="{{ old('name',$product->name) }}" required></label>
   <label>دسته‌بندی<select name="category_id" required>@foreach($categories as $c)<option value="{{ $c->id }}" @selected($product->category_id===$c->id)>{{ $c->name }}</option>@endforeach</select></label>
   <label>برند<input name="brand" value="{{ old('brand',$product->brand) }}"></label>
   <label>واحد<input name="unit" value="{{ old('unit',$product->unit) }}" required></label>
   <label>قیمت ریال<input type="number" name="price" min="0" value="{{ old('price',$product->price) }}" required></label>
   <label>موجودی<input type="number" name="stock" min="0" value="{{ old('stock',$product->stock) }}" required></label>
   <label>تصویر جدید<input type="file" name="image" accept="image/*"></label>
   <label class="span2">محل بارگیری<input name="loading_location" value="{{ old('loading_location',$product->loading_location) }}"></label>
  </div>
  <div class="form-actions"><button class="main-action">ذخیره تغییرات</button><a class="mini-btn" href="{{ route('admin.products') }}">بازگشت</a></div>
 </form>
</section>
@endsection