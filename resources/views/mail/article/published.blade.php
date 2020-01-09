@component('mail::message')
# Опубликована статья: {{ $article->title }}

{{ $article->abstract }}

@component('mail::button', ['url' => route('articles.show', compact('article'))])
Прочитать статью
@endcomponent

С уважением,
{{ config('app.name') }}
@endcomponent
