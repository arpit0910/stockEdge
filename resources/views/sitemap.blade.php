{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($urls as $url)
    <url>
        <loc>{{ $url['location'] }}</loc>
        @if($url['modified'])<lastmod>{{ \Illuminate\Support\Carbon::parse($url['modified'])->toAtomString() }}</lastmod>@endif
        <changefreq>{{ $url['frequency'] }}</changefreq>
        <priority>{{ $url['priority'] }}</priority>
    </url>
@endforeach
</urlset>
