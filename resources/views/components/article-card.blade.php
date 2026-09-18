@props(['article'])
<article class="article-card">
    <a class="article-image {{ $article->image }}" href="{{ route('article', $article->slug) }}" @if($article->image_path) style="background-image: linear-gradient(rgba(6, 18, 36, 0.25), rgba(6, 18, 36, 0.88)), url('{{ route('media.file', basename($article->image_path)) }}')" @endif aria-label="Read {{ $article->title }}">
        <span class="image-caption">{{ $article->topic }}</span><span class="image-arrow">↗</span>
    </a>
    <div class="article-meta">{{ $article->topic }} <span>· {{ $article->created_at->format('d M Y') }}</span></div>
    <h3><a href="{{ route('article', $article->slug) }}">{{ $article->title }}</a></h3>
    <p>{{ $article->summary }}</p>
    <a class="text-link" href="{{ route('article', $article->slug) }}">Read the story <span>↗</span></a>
</article>
