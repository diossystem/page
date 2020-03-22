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
            <article>
                <header>
                    <h1>{{ $page->title }}</h1>
                    <p>{{ $page->description }}</p>
                </header>

                {!! $page->content !!}

                <footer>
                    {{ $page->created_at }}
                </footer>
            </article>
        </main>
    </body>
</html>
