<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Product,Supplier,Customer,ProjectQuote,Shipment,FinancialTransaction,Ticket,Category};
class ModuleController extends Controller {
 public function show(string $module){
  $map=[
   'categories'=>['مدیریت دسته‌بندی‌ها','ساختار دسته‌بندی، ویژگی و فیلترهای فروشگاه',Category::count()],
   'inventory'=>['انبار و موجودی','ورود و خروج کالا و کنترل نقطه سفارش',Product::sum('stock')],
   'suppliers'=>['تأمین‌کنندگان','قرارداد، لیست قیمت و تامین کالا',Supplier::count()],
   'pricing'=>['قیمت‌گذاری','قیمت فروش، همکاری و تغییرات بازار',Product::count()],
   'customers'=>['مشتریان','CRM مشتریان حقیقی، حقوقی و پروژه‌ای',Customer::count()],
   'projects'=>['پروژه‌ها و استعلام‌ها','پیش‌فاکتور و چرخه مذاکره پروژه',ProjectQuote::count()],
   'logistics'=>['ارسال و لجستیک','باربری، مرسوله و رهگیری تحویل',Shipment::count()],
   'finance'=>['مالی و تسویه','فاکتور، مطالبات، دریافت و تسویه',FinancialTransaction::count()],
   'reports'=>['گزارش‌ها و آمار','فروش، موجودی، مشتریان و عملکرد',Product::count()],
   'users'=>['کاربران و نقش‌ها','سطوح دسترسی و گزارش فعالیت',4],
   'discounts'=>['بازاریابی و تخفیف','کمپین، کوپن و قیمت همکاری',8],
   'content'=>['محتوا و بلاگ','مقالات، صفحات و محتوای سئو',24],
   'tickets'=>['تیکت‌ها','پشتیبانی و پیگیری درخواست مشتریان',Ticket::count()],
   'settings'=>['تنظیمات فروشگاه','پرداخت، ارسال، اعلان و تنظیمات عمومی',12],
  ];
  if(!isset($map[$module])) abort(404);
  [$title,$desc,$count]=$map[$module];
  $products=Product::with(['category','supplier'])->take(6)->get();
  return view('admin.module',compact('module','title','desc','count','products'));
 }
}
