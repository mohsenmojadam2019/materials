@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url><loc>{{ route('home') }}</loc></url>
<url><loc>{{ route('blog') }}</loc></url>
<url><loc>{{ route('faq') }}</loc></url>
@foreach($products as $product)<url><loc>{{ route('product.show',$product) }}</loc><lastmod>{{ $product->updated_at->toAtomString() }}</lastmod></url>@endforeach
@foreach($articles as $article)<url><loc>{{ route('article',$article) }}</loc><lastmod>{{ $article->updated_at->toAtomString() }}</lastmod></url>@endforeach
</urlset>