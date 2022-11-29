@component('mail::message')
# Olá {{ $nome }} bem vindo(a).

Corpo da mensagem de maneira simples.

Segue uma lista de opções
- item 1
- item 2
- item 3
- item 4
- item 5

@component('mail::button', ['url' => $url ])
Texto do botão
@endcomponent

Obrigado,<br>
{{ config('app.name') }}

@endcomponent
