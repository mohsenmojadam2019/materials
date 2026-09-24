@extends('layouts.admin')
@section('title','مدیریت محصولات و موجودی')
@section('page-title','مدیریت محصولات و موجودی')
@section('page-subtitle','کنترل کالا، قیمت، تامین‌کننده و موجودی چند انبار')
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format($n)))
<div class="kpi-grid four">
 <article class="kpi green"><span>تعداد محصولات</span><strong>{{ $fa($productCount) }}</strong><small>محصول فعال</small></article>
 <article class="kpi orange"><span>کالاهای کم‌موجودی</span><strong>{{ $fa($lowCount) }}</strong><small>کمتر از نقطه سفارش</small></article>
 <article class="kpi blue"><span>ارزش موجودی انبار</span><strong>{{ $fa($inventoryValue) }} ریال</strong><small>ارزش فروش موجودی</small></article>
 <article class="kpi purple"><span>تأمین‌کنندگان فعال</span><strong>{{ $fa($suppliers->count()) }}</strong><small>تامین‌کننده تاییدشده</small></article>
</div>
<div class="product-summary">
 <section class="panel warehouse-panel"><div class="panel-head"><h2>وضعیت موجودی در انبارها</h2><a>مشاهده همه</a></div>
 @foreach([['انبار مرکزی - تهران','۱۲,۴۵۰',42],['انبار غرب - کرج','۸,۷۶۰',29],['انبار جنوب - اصفهان','۵,۳۲۰',18],['انبار شمال - تبریز','۳,۲۸۰',11]] as $x)
 <div class="bar-row"><span>{{ $x[0] }}</span><b>{{ $x[1] }}</b><div><i style="width:{{ $x[2] }}%"></i></div><em>{{ \App\Support\Jalali::digits($x[2]) }}٪</em></div>@endforeach
 </section>
 <section class="panel mix-panel"><div class="panel-head"><h2>موجودی بر اساس دسته‌بندی</h2></div><div class="donut inventory"></div><div class="mix-legend compact"><span><i class="c1"></i> مصالح پایه ۲۸٪</span><span><i class="c2"></i> آهن و میلگرد ۲۲٪</span><span><i class="c3"></i> لوله و اتصالات ۱۵٪</span><span><i class="c4"></i> کاشی و سرامیک ۱۲٪</span></div></section>
 <section class="panel supplier-panel"><div class="panel-head"><h2>آخرین بروزرسانی تأمین‌کنندگان</h2></div>
 @foreach($suppliers->take(4) as $i=>$s)<div class="supplier-row"><span class="supplier-logo">{{ mb_substr($s->name,0,1) }}</span><div><b>{{ $s->name }}</b><small>{{ $s->city }} • {{ \App\Support\Jalali::today() }}</small></div><span class="{{ $i===1?'bad':'ok' }}">{{ $i===1?'↑ +۳٪':'↓ -۲٪' }}</span></div>@endforeach
 </section>
</div>
<section class="panel data-panel">
 <div class="toolbar"><div class="tabs"><button class="active">همه محصولات</button><button>محصولات فعال</button><button>غیرفعال</button><button>کم‌موجودی</button></div><div class="bulk-btns"><button class="main-action">+ افزودن محصول</button><button>ورود موجودی</button><button>انتقال بین انبارها</button><button>چاپ بارکد</button><button>خروجی اکسل</button></div></div>
 <form class="filters" method="get">
  <input name="q" value="{{ request('q') }}" placeholder="جستجوی نام محصول یا کد کالا ...">
  <select name="category"><option value="">همه دسته‌بندی‌ها</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category')==$c->id)>{{ $c->name }}</option>@endforeach</select>
  <select><option>همه انبارها</option><option>انبار مرکزی</option><option>انبار غرب</option></select>
  <select><option>همه تأمین‌کنندگان</option>@foreach($suppliers as $s)<option>{{ $s->name }}</option>@endforeach</select>
  <button>اعمال فیلتر</button>
 </form>
 <div class="table-wrap products-table"><table><thead><tr><th>تصویر</th><th>نام محصول</th><th>دسته‌بندی</th><th>تأمین‌کننده</th><th>قیمت فروش</th><th>موجودی</th><th>انبار</th><th>وضعیت</th><th>بروزرسانی</th><th>عملیات</th></tr></thead><tbody>
 @foreach($products as $i=>$p)<tr>
  <td><img class="table-img" src="{{ asset(ltrim($p->image,'/')) }}"></td>
  <td><b>{{ $p->name }}</b><small class="code">{{ $p->sku }}</small></td><td>{{ optional($p->category)->name }}</td><td>{{ optional($p->supplier)->name }}</td>
  <td>{{ $fa($p->price) }} ریال</td><td><span class="stock-num {{ $p->stock<20?'red':($p->stock<100?'orange':'') }}">{{ $fa($p->stock) }}</span></td><td>{{ $i%3===0?'انبار غرب':'انبار مرکزی' }}</td>
  <td><span class="{{ $p->stock<20?'danger':($p->stock<100?'warn':'success') }}">{{ $p->stock<20?'در آستانه اتمام':($p->stock<100?'کم‌موجودی':'موجود') }}</span></td>
  <td>{{ \App\Support\Jalali::date($p->updated_at) }}<br><small>{{ $p->updated_at->format('H:i') }}</small></td><td><div class="row-actions"><button>⋮</button><button>✎</button><button>▥</button></div></td>
 </tr>@endforeach
 </tbody></table></div>
 <div class="pagination-wrap">{{ $products->links() }}</div>
</section>
@endsection
