@extends('layouts.admin')
@section('title','مدیریت محصولات و موجودی')
@section('page-title','مدیریت محصولات و موجودی')
@section('page-subtitle','کنترل کالا، قیمت، انبار و مشخصات فنی')
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<div class="kpi-grid four">
 <article class="kpi green"><span>تعداد محصولات</span><strong>{{ $fa($productCount) }}</strong><small>کالاهای ثبت‌شده</small></article>
 <article class="kpi orange"><span>کم‌موجودی</span><strong>{{ $fa($lowCount) }}</strong><small>نیازمند تامین</small></article>
 <article class="kpi blue"><span>ارزش موجودی</span><strong>{{ $fa($inventoryValue) }} ریال</strong><small>بر مبنای قیمت فروش</small></article>
 <article class="kpi purple"><span>تأمین‌کنندگان خرید</span><strong>{{ $fa($suppliers->count()) }}</strong><small>فقط بک‌آفیس</small></article>
</div>
<section class="panel product-create">
 <div class="panel-head"><h2>افزودن محصول جدید</h2><span>محصول مستقیماً متعلق به همین فروشگاه است</span></div>
 <form method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">@csrf
  <div class="form-grid">
   <label>نام محصول<input name="name" required></label><label>SKU<input name="sku" required></label>
   <label>دسته‌بندی<select name="category_id" required>@foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></label>
   <label>برند<input name="brand"></label><label>واحد<input name="unit" value="عدد" required></label>
   <label>قیمت ریال<input type="number" name="price" min="0" required></label><label>موجودی<input type="number" name="stock" min="0" required></label>
   <label>حداقل موجودی<input type="number" name="min_stock" min="0" value="10"></label><label>مبدأ / کشور<input name="origin" value="ایران"></label>
   <label>محل بارگیری<input name="loading_location" value="انبار مرکزی"></label><label>مالیات ٪<input type="number" step="0.01" name="tax_percent" value="10"></label>
   <label>تصویر محصول<input type="file" name="image" accept="image/*"></label>
   <label class="span2">توضیحات<textarea name="description"></textarea></label>
  </div>
  <label class="checkline"><input type="checkbox" name="featured" value="1"> نمایش در محصولات پیشنهادی</label>
  <button class="main-action">+ ثبت محصول</button>
 </form>
</section>
<section class="panel data-panel">
 <form class="filters" method="get">
  <input name="q" value="{{ request('q') }}" placeholder="جستجوی نام یا کد کالا ...">
  <select name="category"><option value="">همه دسته‌بندی‌ها</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category')==$c->id)>{{ $c->name }}</option>@endforeach</select>
  <button>اعمال فیلتر</button>
 </form>
 <div class="table-wrap products-table"><table><thead><tr><th>تصویر</th><th>محصول</th><th>دسته</th><th>برند</th><th>قیمت</th><th>موجودی</th><th>محل بارگیری</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
 @foreach($products as $p)<tr>
  <td><img class="table-img" src="{{ asset(ltrim($p->image,'/')) }}"></td>
  <td><b>{{ $p->name }}</b><small class="code">{{ $p->sku }}</small></td><td>{{ optional($p->category)->name }}</td><td>{{ $p->brand ?: '—' }}</td>
  <td>{{ $fa($p->price) }} ریال</td><td><span class="stock-num {{ $p->stock<20?'red':($p->stock<100?'orange':'') }}">{{ $fa($p->stock) }}</span></td>
  <td>{{ $p->loading_location ?: '—' }}</td><td><span class="{{ $p->active?'success':'danger' }}">{{ $p->active?'فعال':'غیرفعال' }}</span></td>
  <td><div class="row-actions"><a class="mini-btn" href="{{ route('admin.products.edit',$p) }}">ویرایش</a><a class="mini-btn" target="_blank" href="{{ route('product.show',$p) }}">نمایش</a><form method="post" action="{{ route('admin.products.delete',$p) }}" onsubmit="return confirm('حذف شود؟')">@csrf @method('DELETE')<button class="danger-link">حذف</button></form></div></td>
 </tr>@endforeach
 </tbody></table></div>
 <div class="pagination-wrap">{{ $products->links() }}</div>
</section>
@endsection