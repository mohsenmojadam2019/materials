<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Category,Supplier,Product,Customer,Order,OrderItem,ProjectQuote,Shipment,FinancialTransaction,Ticket,User,PriceHistory,Warehouse,WarehouseStock,Article,StoreSetting};

class DatabaseSeeder extends Seeder {
 public function run(): void {
  if (env('DEMO_ADMIN_EMAIL') && env('DEMO_ADMIN_PASSWORD')) {
   User::updateOrCreate(['email'=>env('DEMO_ADMIN_EMAIL')],['name'=>env('DEMO_ADMIN_NAME','مدیر سیستم'),'password'=>env('DEMO_ADMIN_PASSWORD'),'role'=>'admin']);
  }
  if (env('DEMO_USER_EMAIL') && env('DEMO_USER_PASSWORD')) {
   User::updateOrCreate(['email'=>env('DEMO_USER_EMAIL')],['name'=>env('DEMO_USER_NAME','کاربر نمونه'),'password'=>env('DEMO_USER_PASSWORD'),'role'=>'user']);
  }
  $cats=[
   ['مصالح ساختمانی','masaleh','materials-overview.webp'],
   ['سیمان و ملزومات','cement','cement-mortar.webp'],
   ['آهن‌آلات','steel','steel-rebar.webp'],
   ['آجر و بلوک','brick-block','brick-block.webp'],
   ['لوله و اتصالات','pipes','pipes-fittings.webp'],
   ['برق و سیم‌کشی','electric','electrical-supplies.webp'],
   ['روشنایی','lighting','lighting-electrical.webp'],
   ['کاشی و سرامیک','tile','tile-stone.webp'],
   ['چسب و ملات کاشی','tile-adhesive','tile-adhesives.webp'],
   ['رنگ و عایق','paint','insulation-paint.webp'],
   ['ابزار و تجهیزات','tools','tools-equipment.webp'],
   ['چوب و MDF','wood','wood-mdf.webp'],
   ['نما و دکوراسیون','facade','facade-cladding.webp'],
   ['کناف و درای‌وال','drywall','drywall.webp'],
   ['سقف و عایق رطوبتی','roofing','roofing-waterproof.webp'],
   ['یراق‌آلات','hardware','hardware-fasteners.webp'],
   ['پنجره و آلومینیوم','windows','windows-aluminum.webp'],
   ['لوازم بهداشتی','sanitary','bathroom-sanitary.webp'],
   ['تهویه و HVAC','hvac','hvac-ventilation.webp'],
   ['داربست و تجهیزات کارگاهی','scaffolding','scaffolding.webp'],
  ];
  foreach($cats as $i=>$c) Category::create([
   'name'=>$c[0],'slug'=>$c[1],'image'=>'/assets/img/real/'.$c[2],'sort_order'=>$i+1
  ]);
  $suppliers=[];
  foreach([['سیمان تهران','02188770001','تهران'],['ذوب آهن اصفهان','03136660002','اصفهان'],['پلیمر گلپایگان','03157440003','گلپایگان'],['کاشی سینا','03536220004','یزد'],['رنگ پارس','02144550005','تهران'],['ماکیتا ایران','02166770006','تهران']] as $s){$suppliers[]=Supplier::create(['name'=>$s[0],'phone'=>$s[1],'city'=>$s[2]]);}
  $c=Category::all()->keyBy('slug');
  $products=[
   ['میلگرد آجدار A3 سایز ۱۴','rebar-a3-14','REB-A3-14','steel',1,418000,850,120,'steel-rebar.webp',2.1],
   ['سیمان پرتلند تیپ ۲','cement-type-2','CMT-002','cement',0,1980000,1240,300,'cement-mortar.webp',0.5],
   ['تیرآهن IPE ۱۸','ipe-18','IPE-18','steel',1,468000,12,50,'steel-rebar.webp',-1.2],
   ['آجر سفال ۱۵ سانتی','brick-15','BRK-015','brick-block',0,340000,5200,1000,'brick-block.webp',0.8],
   ['لوله PVC فشار قوی ۱۱۰','pvc-110','PVC-110','pipes',2,2450000,50,100,'pipes-fittings.webp',0.0],
   ['کاشی پرسلان ۶۰×۱۲۰','tile-60120','TILE-60120','tile',3,5980000,310,70,'tile-stone.webp',0.0],
   ['رنگ اکریلیک سفید ۲۰ کیلویی','paint-20w','PAINT-20W','paint',4,49800000,25,40,'insulation-paint.webp',0.0],
   ['دریل چکشی ۸۵۰ وات','drill-850','DRL-850','tools',5,24900000,115,20,'tools-equipment.webp',0.0],
   ['کابل افشان ۲.۵','wire-25','WIRE-25','electric',5,59000000,180,50,'electrical-supplies.webp',0.0],
   ['پارکت لمینت AC4','laminate-ac4','WOOD-AC4','wood',5,7680000,95,30,'wood-mdf.webp',0.0],
   ['سنگ نمای دکوراتیو','facade-stone','FCD-101','facade',3,12800000,180,30,'facade-cladding.webp',0.4],
   ['صفحه گچ ضد رطوبت','drywall-moisture','DRY-012','drywall',0,6450000,420,80,'drywall.webp',0.2],
   ['عایق رطوبتی رول پلیمری','roof-membrane','ROOF-20','roofing',4,18900000,140,30,'roofing-waterproof.webp',0.7],
   ['ست یراق درب ساختمانی','door-hardware-set','HRD-100','hardware',5,15400000,90,20,'hardware-fasteners.webp',0.0],
   ['پنل LED سقفی ۶۰×۶۰','led-panel-6060','LED-6060','lighting',5,22400000,130,25,'lighting-electrical.webp',-0.3],
   ['چسب کاشی پودری ۲۰ کیلویی','tile-adhesive-20','ADH-020','tile-adhesive',3,5200000,260,60,'tile-adhesives.webp',0.5],
   ['داربست مدولار گالوانیزه','modular-scaffolding','SCF-200','scaffolding',1,88500000,55,12,'scaffolding.webp',1.1],
   ['پروفیل آلومینیوم ترمال‌بریک','thermalbreak-profile','WIN-THB','windows',1,37500000,75,20,'windows-aluminum.webp',0.6],
   ['ست شیرآلات و لوازم بهداشتی','sanitary-fixture-set','SAN-500','sanitary',2,98500000,44,10,'bathroom-sanitary.webp',0.0],
   ['کانال گالوانیزه تهویه','hvac-duct','HVAC-400','hvac',2,46500000,68,15,'hvac-ventilation.webp',0.9],
  ];
  foreach($products as $i=>$p) Product::create([
   'name'=>$p[0],'slug'=>$p[1],'sku'=>$p[2],'category_id'=>$c[$p[3]]->id,
   'supplier_id'=>$suppliers[$p[4]]->id,'unit'=>$p[3]==='steel'?'کیلوگرم':'عدد',
   'price'=>$p[5],'old_price'=>$i%3===0?(int)($p[5]*1.05):null,
   'stock'=>$p[6],'min_stock'=>$p[7],'image'=>'/assets/img/real/'.$p[8],
   'market_change'=>$p[9],'featured'=>$i<10
  ]);
  $catalog=[
   'rebar-a3-14'=>['brand'=>'ذوب آهن اصفهان','origin'=>'ایران','weight'=>14,'loading_location'=>'کارخانه اصفهان','description'=>'میلگرد آجدار A3 مناسب سازه‌های بتنی و پروژه‌های عمرانی با کنترل کیفیت کارخانه.','specs'=>['استاندارد'=>'A3','سایز'=>'۱۴ میلی‌متر','طول شاخه'=>'۱۲ متر','محل بارگیری'=>'اصفهان']],
   'cement-type-2'=>['brand'=>'سیمان تهران','origin'=>'ایران','weight'=>50,'loading_location'=>'انبار تهران','description'=>'سیمان پرتلند تیپ ۲ مناسب بتن‌ریزی عمومی و پروژه‌های ساختمانی.','specs'=>['نوع'=>'پرتلند تیپ ۲','وزن'=>'۵۰ کیلوگرم','بسته‌بندی'=>'پاکت','محل بارگیری'=>'تهران']],
   'ipe-18'=>['brand'=>'ذوب آهن اصفهان','origin'=>'ایران','weight'=>228,'loading_location'=>'کارخانه اصفهان','description'=>'تیرآهن IPE 18 استاندارد برای اسکلت فلزی و پروژه‌های ساختمانی.','specs'=>['استاندارد'=>'IPE','سایز'=>'۱۸','طول'=>'۱۲ متر','وزن تقریبی'=>'۲۲۸ کیلوگرم']],
   'brick-15'=>['brand'=>'آجرین','origin'=>'ایران','weight'=>3.2,'loading_location'=>'تهران','description'=>'آجر سفال ۱۵ سانتی مناسب دیوارچینی داخلی و پیرامونی.','specs'=>['ضخامت'=>'۱۵ سانتی‌متر','نوع'=>'سفال','کاربرد'=>'دیوارچینی']],
   'pvc-110'=>['brand'=>'پلیمر گلپایگان','origin'=>'ایران','weight'=>8.6,'loading_location'=>'گلپایگان','description'=>'لوله PVC فشار قوی مناسب فاضلاب و تأسیسات ساختمانی.','specs'=>['قطر'=>'۱۱۰ میلی‌متر','جنس'=>'PVC','رده'=>'فشار قوی']],
   'tile-60120'=>['brand'=>'کاشی سینا','origin'=>'ایران','weight'=>22,'loading_location'=>'یزد','description'=>'کاشی پرسلان ۶۰×۱۲۰ با جذب آب پایین و مناسب فضاهای داخلی.','specs'=>['ابعاد'=>'۶۰×۱۲۰','جنس'=>'پرسلان','سطح'=>'مات']],
   'paint-20w'=>['brand'=>'رنگ پارس','origin'=>'ایران','weight'=>20,'loading_location'=>'تهران','description'=>'رنگ اکریلیک سفید پایه آب با پوشش مناسب برای دیوارهای داخلی.','specs'=>['وزن'=>'۲۰ کیلوگرم','پایه'=>'آب','رنگ'=>'سفید']],
   'drill-850'=>['brand'=>'ماکیتا','origin'=>'ژاپن','weight'=>2.4,'loading_location'=>'تهران','description'=>'دریل چکشی ۸۵۰ وات مناسب کارگاه و مصارف ساختمانی.','specs'=>['توان'=>'۸۵۰ وات','نوع'=>'چکشی','گارانتی'=>'۱۸ ماه']],
   'wire-25'=>['brand'=>'افشارنژاد','origin'=>'ایران','weight'=>null,'loading_location'=>'انبار تهران','description'=>'کابل افشان ساختمانی مناسب مدارهای روشنایی و پریز.','specs'=>['سطح مقطع'=>'۲.۵ میلی‌متر مربع','نوع'=>'افشان','روکش'=>'PVC']],
   'laminate-ac4'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>null,'loading_location'=>'انبار مرکزی','description'=>'پارکت لمینت مقاوم مناسب فضاهای مسکونی و اداری.','specs'=>['کلاس سایش'=>'AC4','نوع'=>'لمینت','کاربرد'=>'کف داخلی']],
   'facade-stone'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>18,'loading_location'=>'انبار مرکزی','description'=>'سنگ نمای دکوراتیو برای نمای داخلی و خارجی ساختمان.','specs'=>['کاربرد'=>'نما','نوع'=>'دکوراتیو','بسته‌بندی'=>'کارتن']],
   'drywall-moisture'=>['brand'=>'کناف ایران','origin'=>'ایران','weight'=>24,'loading_location'=>'تهران','description'=>'صفحه گچی مقاوم در برابر رطوبت مناسب سقف و دیوار خشک.','specs'=>['ضخامت'=>'۱۲.۵ میلی‌متر','نوع'=>'MR','ابعاد'=>'۱۲۰×۲۴۰ سانتی‌متر']],
   'roof-membrane'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>40,'loading_location'=>'انبار مرکزی','description'=>'عایق رطوبتی پلیمری رول‌شونده برای بام و سطوح ساختمانی.','specs'=>['عرض'=>'۱ متر','طول'=>'۱۰ متر','نوع'=>'پلیمری']],
   'door-hardware-set'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>2.1,'loading_location'=>'تهران','description'=>'ست کامل یراق‌آلات درب شامل دستگیره، لولا و متعلقات نصب.','specs'=>['جنس'=>'فلزی','کاربرد'=>'درب ساختمانی','نوع'=>'ست کامل']],
   'led-panel-6060'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>2.8,'loading_location'=>'تهران','description'=>'پنل LED سقفی کم‌مصرف مناسب فضاهای اداری و تجاری.','specs'=>['ابعاد'=>'۶۰×۶۰','نوع'=>'LED','نصب'=>'توکار']],
   'tile-adhesive-20'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>20,'loading_location'=>'یزد','description'=>'چسب کاشی پودری پایه سیمانی برای نصب کاشی و سرامیک.','specs'=>['وزن'=>'۲۰ کیلوگرم','نوع'=>'پودری','کاربرد'=>'کاشی و سرامیک']],
   'modular-scaffolding'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>32,'loading_location'=>'تهران','description'=>'داربست مدولار گالوانیزه مناسب پروژه‌های ساختمانی و صنعتی.','specs'=>['جنس'=>'فولاد گالوانیزه','نوع'=>'مدولار','کاربرد'=>'کارگاهی']],
   'thermalbreak-profile'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>8.4,'loading_location'=>'تهران','description'=>'پروفیل آلومینیوم ترمال‌بریک برای پنجره و درب‌های عایق.','specs'=>['جنس'=>'آلومینیوم','نوع'=>'ترمال‌بریک','کاربرد'=>'پنجره و درب']],
   'sanitary-fixture-set'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>18,'loading_location'=>'تهران','description'=>'ست شیرآلات و تجهیزات بهداشتی مناسب سرویس و حمام.','specs'=>['نوع'=>'ست کامل','آبکاری'=>'کروم','کاربرد'=>'سرویس بهداشتی']],
   'hvac-duct'=>['brand'=>'ساختینو','origin'=>'ایران','weight'=>15,'loading_location'=>'تهران','description'=>'کانال گالوانیزه تهویه برای انتقال هوا در سیستم‌های HVAC.','specs'=>['جنس'=>'گالوانیزه','نوع'=>'کانال هوا','کاربرد'=>'تهویه مطبوع']],
  ];
  foreach(Product::all() as $product){
   $meta=$catalog[$product->slug]??['brand'=>'ساختینو','origin'=>'ایران','weight'=>null,'loading_location'=>'انبار مرکزی','description'=>'کالای ساختمانی تامین‌شده از فروشندگان معتبر.','specs'=>['واحد'=>$product->unit]];
   $product->update($meta);
  }
  $warehouses=[
   Warehouse::create(['name'=>'انبار مرکزی تهران','city'=>'تهران','address'=>'بازار آهن تهران']),
   Warehouse::create(['name'=>'انبار غرب کرج','city'=>'کرج','address'=>'منطقه صنعتی کرج']),
   Warehouse::create(['name'=>'انبار اصفهان','city'=>'اصفهان','address'=>'شهرک صنعتی اصفهان']),
  ];
  foreach(Product::all() as $i=>$product){
   foreach($warehouses as $wi=>$warehouse){
    WarehouseStock::create(['warehouse_id'=>$warehouse->id,'product_id'=>$product->id,'qty'=>max(0,(int)floor($product->stock/[2,3,5][$wi]))]);
   }
   foreach(range(6,0) as $month){
    $delta=(($month%3)-1)*0.035;
    PriceHistory::create(['product_id'=>$product->id,'price'=>(int)round($product->price*(1+$delta)),'recorded_at'=>now()->subMonths($month)]);
   }
   PriceHistory::create(['product_id'=>$product->id,'price'=>$product->price,'recorded_at'=>now()]);
  }
  $customers=[];
  foreach([['شرکت سازه نوین','legal','09121230001','سازه نوین','تهران',10000000000],['گروه ساختمانی پارس','legal','09121230002','گروه پارس','تهران',7000000000],['مهندس رضایی','individual','09121230003',null,'کرج',2000000000],['عمارت گستر','legal','09121230004','عمارت گستر','اصفهان',8500000000],['پروژه برج سپهر','legal','09121230005','سپهر','تهران',12000000000]] as $x){$customers[]=Customer::create(['name'=>$x[0],'type'=>$x[1],'phone'=>$x[2],'company'=>$x[3],'city'=>$x[4],'credit_limit'=>$x[5]]);}
  $orderRows=[
   ['ORD-1405-0462',0,'project',4350000000,'paid','ready'],['ORD-1405-0461',1,'wholesale',2870000000,'pending','preparing'],
   ['ORD-1405-0460',2,'project',1240000000,'paid','in_transit'],['ORD-1405-0459',3,'wholesale',3100000000,'unpaid','delivered'],
   ['ORD-1405-0458',4,'project',5630000000,'partial','in_transit'],['ORD-1405-0457',0,'retail',870500000,'paid','delivered']
  ];
  $allProducts=Product::all();
  foreach($orderRows as $i=>$o){
   $order=Order::create(['customer_id'=>$customers[$o[1]]->id,'order_no'=>$o[0],'type'=>$o[2],'status'=>'processing','payment_status'=>$o[4],'shipping_status'=>$o[5],'subtotal'=>$o[3],'shipping'=>0,'total'=>$o[3],'ordered_at'=>now()->subDays($i)]);
   foreach($allProducts->take(2) as $p){OrderItem::create(['order_id'=>$order->id,'product_id'=>$p->id,'qty'=>2+$i,'unit_price'=>$p->price,'total'=>$p->price*(2+$i)]);}
   Shipment::create(['order_id'=>$order->id,'carrier'=>$i%2?'باربری پارس':'باربری وطن','tracking_code'=>'TRK-1405-'.str_pad((string)(1452-$i),4,'0',STR_PAD_LEFT),'status'=>$o[5],'destination'=>$customers[$o[1]]->city,'dispatched_at'=>now()->subDays(max(0,$i-1))]);
   FinancialTransaction::create(['order_id'=>$order->id,'type'=>'receivable','amount'=>$o[3],'status'=>$o[4]==='paid'?'paid':'pending','due_date'=>now()->addDays(10+$i)]);
  }
  foreach([['REQ-1405-0214',0,'پروژه مجتمع مسکونی مهر','new',8500000000],['REQ-1405-0213',1,'ساختمان اداری پارس','review',4200000000],['REQ-1405-0212',3,'پروژه تجاری آفتاب','quoted',12000000000],['REQ-1405-0211',4,'پروژه صنعتی سپهر','negotiation',15400000000]] as $i=>$q){ProjectQuote::create(['customer_id'=>$customers[$q[1]]->id,'code'=>$q[0],'project_name'=>$q[2],'status'=>$q[3],'estimated_amount'=>$q[4],'requested_at'=>now()->subDays($i)]);}
  foreach([['پیگیری زمان تحویل میلگرد','شرکت سازه نوین','open','high'],['اصلاح فاکتور سفارش','گروه ساختمانی پارس','waiting','normal'],['استعلام هزینه باربری','مهندس رضایی','resolved','normal']] as $t){Ticket::create(['subject'=>$t[0],'customer_name'=>$t[1],'status'=>$t[2],'priority'=>$t[3]]);}
  foreach([
   ["store_name","ساختینو"],
   ["store_phone","۰۲۱ ۹۱۰۰ ۱۲۳۴"],
   ["store_email","info@sakhtino.ir"],
   ["store_address","تهران، دفتر مرکزی فروش"],
   ["primary_color","#0d725b"],
   ["hero_image","/assets/img/real/hero-construction.webp"],
   ["calculator_image","/assets/img/real/materials-overview.webp"]
  ] as [$key,$value]) StoreSetting::updateOrCreate(["key"=>$key],["value"=>$value]);

  DB::table("faqs")->insert([
   ["question"=>"چطور قیمت روز مصالح را ببینم؟","answer"=>"قیمت هر کالا در صفحه محصول و بخش قیمت‌های روز نمایش داده می‌شود.","sort_order"=>1,"active"=>1,"created_at"=>now(),"updated_at"=>now()],
   ["question"=>"ارسال سفارش چگونه انجام می‌شود؟","answer"=>"پس از تایید سفارش، واحد لجستیک باربری را تخصیص می‌دهد و وضعیت مرسوله در حساب کاربری قابل رهگیری است.","sort_order"=>2,"active"=>1,"created_at"=>now(),"updated_at"=>now()],
   ["question"=>"امکان دریافت پیش‌فاکتور پروژه‌ای وجود دارد؟","answer"=>"بله، درخواست مستقیماً توسط واحد فروش همین شرکت بررسی می‌شود.","sort_order"=>3,"active"=>1,"created_at"=>now(),"updated_at"=>now()],
  ]);

  foreach([
   ["راهنمای انتخاب میلگرد برای پروژه ساختمانی","guide-rebar","راهنمای انتخاب سایز و استاندارد میلگرد برای پروژه‌های متداول.","در انتخاب میلگرد باید نوع سازه، نقشه محاسباتی، استاندارد تولید، قطر و محل بارگیری بررسی شود. قیمت روز تنها یکی از معیارهای خرید است.","راهنمای خرید"],
   ["چگونه هزینه مصالح پروژه را مدیریت کنیم؟","project-cost-control","چند اصل عملی برای کنترل هزینه خرید مصالح.","برای مدیریت هزینه مصالح، خرید باید براساس برنامه زمان‌بندی پروژه، موجودی انبار، هزینه حمل و نوسان قیمت انجام شود.","اجرای پروژه"],
   ["تفاوت سیمان تیپ ۱ و تیپ ۲","cement-types","مقایسه کاربردهای رایج انواع سیمان پرتلند.","سیمان تیپ ۲ در بسیاری از پروژه‌های عمومی به دلیل حرارت هیدراتاسیون متوسط و مقاومت مناسب استفاده می‌شود.","راهنمای خرید"],
  ] as $a) Article::create(["title"=>$a[0],"slug"=>$a[1],"excerpt"=>$a[2],"body"=>$a[3],"category"=>$a[4],"published"=>true,"published_at"=>now()]);

  if($demoUser=User::where("email",env("DEMO_USER_EMAIL","user@sakhtino.ir"))->first()){
   Order::query()->orderBy("id")->take(2)->update(["user_id"=>$demoUser->id]);
  }
 }
}
