<?php
namespace App\Http\Controllers;
use App\Models\{Article,Product};
use Illuminate\Support\Facades\DB;
class ContentController extends Controller {
 public function blog(){return view("content.blog",["articles"=>Article::where("published",true)->latest("published_at")->paginate(9)]);}
 public function article(Article $article){abort_unless($article->published,404);return view("content.article",compact("article"));}
 public function faq(){return view("content.faq",["faqs"=>DB::table("faqs")->where("active",1)->orderBy("sort_order")->get()]);}
 public function sitemap(){
  $articles=Article::where("published",true)->get();$products=Product::where("active",true)->get();
  return response()->view("content.sitemap",compact("articles","products"))->header("Content-Type","application/xml");
 }
 public function robots(){return response("User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: ".url("/sitemap.xml")."\n",200,["Content-Type"=>"text/plain"]);}
}
