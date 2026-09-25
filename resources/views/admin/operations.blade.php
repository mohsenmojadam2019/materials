@extends('layouts.admin')
@section('title',$title)
@section('page-title',$title)
@section('page-subtitle','مدیریت عملیاتی و بروزرسانی مستقیم اطلاعات')
@section('content')
@php($fa=fn($n)=>\App\Support\Jalali::digits(number_format((int)$n)))
<section class="panel data-panel">
 @if($module==='suppliers')
  <div class="panel-head"><h2>تأمین‌کنندگان داخلی</h2></div>
  <form class="inline-create" method="post" action="{{ route('admin.suppliers.store') }}">@csrf
   <input name="name" placeholder="نام تأمین‌کننده" required><input name="phone" placeholder="تلفن"><input name="city" placeholder="شهر">
   <select name="status"><option value="active">فعال</option><option value="inactive">غیرفعال</option></select><button class="main-action">ثبت تأمین‌کننده</button>
  </form>
  <div class="table-wrap"><table><thead><tr><th>نام</th><th>تلفن</th><th>شهر</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
  @foreach($suppliers as $supplier)<tr><form method="post" action="{{ route('admin.suppliers.update',$supplier) }}">@csrf @method('PATCH')
   <td><input name="name" value="{{ $supplier->name }}" required></td><td><input name="phone" value="{{ $supplier->phone }}"></td><td><input name="city" value="{{ $supplier->city }}"></td>
   <td><select name="status"><option value="active" @selected($supplier->status==='active')>فعال</option><option value="inactive" @selected($supplier->status==='inactive')>غیرفعال</option></select></td><td><button class="mini-btn">ذخیره</button></td>
  </form></tr>@endforeach</tbody></table></div>

 @elseif($module==='pricing')
  <div class="panel-head"><h2>قیمت‌گذاری و مالیات</h2></div>
  <div class="table-wrap"><table><thead><tr><th>محصول</th><th>دسته</th><th>قیمت فعلی</th><th>قیمت قبل</th><th>مالیات ٪</th><th>عملیات</th></tr></thead><tbody>
  @foreach($products as $product)<tr><form method="post" action="{{ route('admin.pricing.update',$product) }}">@csrf @method('PATCH')
   <td><b>{{ $product->name }}</b><small class="code">{{ $product->sku }}</small></td><td>{{ optional($product->category)->name }}</td>
   <td><input type="number" name="price" value="{{ $product->price }}" required></td><td><input type="number" name="old_price" value="{{ $product->old_price }}"></td><td><input type="number" step="0.01" name="tax_percent" value="{{ $product->tax_percent }}" required></td><td><button class="mini-btn">بروزرسانی</button></td>
  </form></tr>@endforeach</tbody></table></div>

 @elseif($module==='logistics')
  <div class="panel-head"><h2>مرسولات و باربری</h2></div>
  <div class="table-wrap"><table><thead><tr><th>سفارش</th><th>باربری</th><th>کد رهگیری</th><th>مقصد</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
  @foreach($shipments as $shipment)<tr><form method="post" action="{{ route('admin.logistics.update',$shipment) }}">@csrf @method('PATCH')
   <td>{{ $shipment->order?->order_no }}</td><td><input name="carrier" value="{{ $shipment->carrier }}"></td><td><input name="tracking_code" value="{{ $shipment->tracking_code }}"></td><td><input name="destination" value="{{ $shipment->destination }}"></td>
   <td><select name="status">@foreach(['preparing'=>'آماده‌سازی','ready'=>'آماده ارسال','in_transit'=>'در مسیر','delivered'=>'تحویل شد','returned'=>'مرجوع'] as $k=>$v)<option value="{{ $k }}" @selected($shipment->status===$k)>{{ $v }}</option>@endforeach</select></td><td><button class="mini-btn">ذخیره</button></td>
  </form></tr>@endforeach</tbody></table></div>

 @elseif($module==='users')
  <div class="panel-head"><h2>کاربران و نقش‌ها</h2></div>
  <div class="table-wrap"><table><thead><tr><th>نام</th><th>ایمیل</th><th>نقش</th><th>عضویت</th><th>عملیات</th></tr></thead><tbody>
  @foreach($users as $user)<tr><td><b>{{ $user->name }}</b></td><td>{{ $user->email }}</td><td><form class="row-edit" method="post" action="{{ route('admin.users.role',$user) }}">@csrf @method('PATCH')
   <select name="role"><option value="user" @selected($user->role==='user')>کاربر</option><option value="admin" @selected($user->role==='admin')>مدیر</option></select><button>ذخیره</button></form></td><td>{{ \App\Support\Jalali::date($user->created_at) }}</td><td>{{ $user->id===auth()->id()?'حساب فعلی':'—' }}</td></tr>@endforeach
  </tbody></table></div>

 @elseif($module==='discounts')
  <div class="panel-head"><h2>کدهای تخفیف</h2></div>
  <form class="inline-create" method="post" action="{{ route('admin.discounts.store') }}">@csrf
   <input name="code" placeholder="کد" required><select name="type"><option value="percent">درصدی</option><option value="fixed">مبلغ ثابت</option></select><input type="number" name="value" placeholder="مقدار" required><input type="number" name="min_order" placeholder="حداقل سفارش"><input type="date" name="expires_at"><button class="main-action">ساخت کد</button>
  </form>
  <div class="table-wrap"><table><thead><tr><th>کد</th><th>نوع</th><th>مقدار</th><th>حداقل سفارش</th><th>انقضا</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
  @foreach($coupons as $coupon)<tr><td><b>{{ $coupon->code }}</b></td><td>{{ $coupon->type==='percent'?'درصدی':'ثابت' }}</td><td>{{ $fa($coupon->value) }}</td><td>{{ $fa($coupon->min_order) }}</td><td>{{ $coupon->expires_at?\App\Support\Jalali::date($coupon->expires_at):'بدون انقضا' }}</td>
   <td>{{ $coupon->active?'فعال':'غیرفعال' }}</td><td><form class="row-edit" method="post" action="{{ route('admin.discounts.update',$coupon) }}">@csrf @method('PATCH')<select name="active"><option value="1" @selected($coupon->active)>فعال</option><option value="0" @selected(!$coupon->active)>غیرفعال</option></select><input type="date" name="expires_at" value="{{ optional($coupon->expires_at)->format('Y-m-d') }}"><button>ذخیره</button></form></td></tr>@endforeach
  </tbody></table></div>

 @elseif($module==='tickets')
  <div class="panel-head"><h2>تیکت‌های پشتیبانی</h2></div>
  <div class="table-wrap"><table><thead><tr><th>موضوع</th><th>مشتری</th><th>اولویت</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
  @foreach($tickets as $ticket)<tr><td><b>{{ $ticket->subject }}</b></td><td>{{ $ticket->customer_name }}</td><td>{{ $ticket->priority }}</td><td>{{ $ticket->status }}</td><td><form class="row-edit" method="post" action="{{ route('admin.tickets.update',$ticket) }}">@csrf @method('PATCH')
   <select name="priority">@foreach(['low','normal','high','urgent'] as $p)<option value="{{ $p }}" @selected($ticket->priority===$p)>{{ $p }}</option>@endforeach</select><select name="status">@foreach(['open','waiting','resolved','closed'] as $s)<option value="{{ $s }}" @selected($ticket->status===$s)>{{ $s }}</option>@endforeach</select><button>ذخیره</button></form></td></tr>@endforeach
  </tbody></table></div>
 @endif
</section>
@endsection
