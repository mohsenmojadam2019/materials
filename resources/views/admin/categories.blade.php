@extends('layouts.admin')
@section('title','دسته‌بندی‌ها')
@section('page-title','مدیریت دسته‌بندی‌ها')
@section('page-subtitle','ساختار کاتالوگ و گروه‌بندی محصولات')
@section('content')
<section class="panel data-panel">
 <div class="toolbar"><h2>دسته‌بندی‌های فروشگاه</h2><form class="inline-create" method="post" action="{{ route('admin.categories.store') }}">@csrf<input name="name" required placeholder="نام دسته"><input name="slug" required placeholder="slug-en"><button class="main-action">+ افزودن</button></form></div>
 <div class="table-wrap"><table><thead><tr><th>نام</th><th>Slug</th><th>محصولات</th><th>وضعیت</th></tr></thead><tbody>@foreach($categories as $c)<tr><td><b>{{ $c->name }}</b></td><td>{{ $c->slug }}</td><td>{{ \App\Support\Jalali::digits($c->products_count) }}</td><td><span class="{{ $c->active?'success':'danger' }}">{{ $c->active?'فعال':'غیرفعال' }}</span></td></tr>@endforeach</tbody></table></div>
</section>
@endsection