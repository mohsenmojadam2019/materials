<?php
return [
 "name"=>env("STORE_NAME","ساختینو"),
 "tagline"=>env("STORE_TAGLINE","فروشگاه تخصصی مصالح ساختمانی"),
 "phone"=>env("STORE_PHONE","۰۲۱ ۹۱۰۰ ۱۲۳۴"),
 "email"=>env("STORE_EMAIL","info@sakhtino.ir"),
 "address"=>env("STORE_ADDRESS","تهران"),
 "primary_color"=>env("STORE_PRIMARY_COLOR","#0d725b"),
 "currency"=>"ریال",
 "tax_percent"=>(float)env("STORE_TAX_PERCENT",10),
];
