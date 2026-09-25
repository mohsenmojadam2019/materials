@extends('layouts.admin')
@section('title','انبار و موجودی')
@section('page-title','مدیریت انبار و موجودی')
@section('page-subtitle','موجودی هر محصول در انبارهای شرکت')
@section('content')
<section class="panel data-panel"><div class="toolbar"><h2>ثبت/اصلاح موجودی</h2><form class="inline-create" method="post" action="{{ route('admin.inventory.update') }}">@csrf<select name="warehouse_id" required>@foreach($warehouses as $w)<option value="{{ $w->id }}">{{ $w->name }}</option>@endforeach</select><select name="product_id" required>@foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select><input type="number" min="0" name="qty" required placeholder="موجودی"><button class="main-action">ذخیره</button></form></div></section>
<div class="warehouse-grid">@foreach($warehouses as $w)<section class="panel warehouse-card"><div class="panel-head"><div><h2>{{ $w->name }}</h2><small>{{ $w->city }} • {{ $w->address }}</small></div></div><div class="table-wrap"><table><thead><tr><th>محصول</th><th>موجودی</th></tr></thead><tbody>@foreach($w->stocks as $s)<tr><td>{{ $s->product->name }}</td><td><b>{{ \App\Support\Jalali::digits(number_format($s->qty)) }}</b></td></tr>@endforeach</tbody></table></div></section>@endforeach</div>
@endsection