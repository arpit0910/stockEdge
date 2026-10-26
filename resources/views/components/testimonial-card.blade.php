@props(['testimonial'])

<article class="sr-testimonial-card">
    <div class="sr-testimonial-rating" aria-label="{{ $testimonial->rating }} out of 5 stars">
        @for($star = 1; $star <= 5; $star++)
            <svg viewBox="0 0 24 24" aria-hidden="true" @class(['is-filled' => $star <= $testimonial->rating])><path d="m12 3 2.8 5.7 6.3.9-4.5 4.4 1.1 6.2-5.7-3-5.7 3 1.1-6.2-4.5-4.4 6.3-.9Z"/></svg>
        @endfor
    </div>
    <blockquote class="sr-testimonial-quote">
        <p>{{ $testimonial->quote }}</p>
    </blockquote>
    <div class="sr-reviewer-wrap">
        <div class="sr-reviewer-meta">
            <b>{{ $testimonial->name }}</b>
            @if(filled($testimonial->role))
                <small>{{ $testimonial->role }}</small>
            @endif
        </div>
    </div>
</article>
