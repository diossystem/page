<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $page->title }}</title>
        <meta name="description" content="{{ $page->description_tag }}">
        <meta name="keywords" content="{{ $page->keywords_tag }}">
    </head>
    <body>
        <main>
            <header>
                <h1>{{ $page->title }}</h1>
                <p>{{ $page->description }}</p>
            </header>

            {!! $page->content !!}

            @if (isset($pages) && $pages->count())
                @foreach ($pages as $item)
                    <article>
                        <h1>
                            <a href="{{ route('website.page', $item->link) }}">
                                {{ $item->title }}
                            </a>
                        </h1>
                    </article>
                @endforeach
            @endif
        </main>
    </body>
</html>
