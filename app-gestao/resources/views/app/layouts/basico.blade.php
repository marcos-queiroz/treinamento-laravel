<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>APP Gestão - @yield('title')</title>
        <meta charset="utf-8">

        <linK rel="stylesheet" href="{{ asset('css/styless.css?0.0.2') }}" >
    </head>

    <body>
        @include('app.layouts._partials.topo')
        @yield('conteudo')
    </body>
</html>