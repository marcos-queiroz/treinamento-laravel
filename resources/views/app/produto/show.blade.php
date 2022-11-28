@extends('app.layouts.basico')

@section('title', 'Produto')

@section('conteudo')

    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Visualizar - Produto</p>
        </div>

        <div class="menu">
            <ul>
                <li><a href="{{ route('produto.index') }}">Voltar</a></li>
                <li><a href="{{ route('produto.edit', ['produto' => $produto->id]) }}">Editar</a></li>
            </ul>
        </div>

        <div class="informacao-pagina">
            <div style="width: 30%; margin-left: auto; margin-right: auto">
                <table border="1" style="text-align: left">
                    <tr>
                        <td>ID</td>
                        <td>{{ $produto->id }}</td>
                    </tr>
                    <tr>
                        <td>Nome</td>
                        <td>{{ $produto->nome }}</td>
                    </tr>
                    <tr>
                        <td>Descrição</td>
                        <td>{{ $produto->descricao }}</td>
                    </tr>
                    <tr>
                        <td>Peso</td>
                        <td>{{ $produto->peso }} Kg</td>
                    </tr>
                    <tr>
                        <td>Unidade de Medida</td>
                        <td>{{ $produto->unidade->unidade }}</td>
                    </tr>
                    <tr>
                        <td>Comprimento</td>
                        <td>{{ $produto->produtoDetalhe->comprimento ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Altura</td>
                        <td>{{ $produto->produtoDetalhe->altura ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Largura</td>
                        <td>{{ $produto->produtoDetalhe->largura ?? '' }}</td>
                    </tr>
                </table>

                <br>

                <table border="1" style="text-align: left">
                    <tr>
                        <th colspan="2">Fornecedor</th>
                    </tr>
                    <tr>
                        <td>Nome</td>
                        <td>{{ $produto->fornecedor->nome }}</td>
                    </tr>
                    <tr>
                        <td>Site</td>
                        <td>{{ $produto->fornecedor->site }}</td>
                    </tr>
                    <tr>
                        <td>UF</td>
                        <td>{{ $produto->fornecedor->uf }}</td>
                    </tr>
                    <tr>
                        <td>E-mail</td>
                        <td>{{ $produto->fornecedor->email }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

@endsection
