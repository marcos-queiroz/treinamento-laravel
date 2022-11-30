@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                Tarefas 
                            </div>
                            <div class="col-6">
                                <div class="float-end">
                                    <a href="{{ route('tarefa.create') }}" class="btn">Novo</a>
                                    <a href="{{ route('tarefa.exportacao', ['extensao' => 'xlsx']) }}" class="btn">XLXS</a>
                                    <a href="{{ route('tarefa.exportacao', ['extensao' => 'csv']) }}" class="btn">CSV</a>
                                    <a href="{{ route('tarefa.exportacao', ['extensao' => 'pdf']) }}" class="btn">PDF</a>
                                    <a href="{{ route('tarefa.exportar') }}" target="_blank" class="btn">PDF (dom)</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tarefa</th>
                                    <th>Data limite</th>
                                    <th colspan="2">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarefas as $tarefa)
                                    <tr>
                                        <th scope="row">{{ $tarefa['id'] }}</th>
                                        <td>{{ $tarefa['tarefa'] }}</td>
                                        <td>{{ date('d/m/Y', strtotime($tarefa['data_limite_conclusao'])) }}</td>
                                        <td>
                                            <a href="{{ route('tarefa.edit', $tarefa['id']) }}" class="btn btn-warning">
                                                Editar
                                            </a>
                                        </td>
                                        <td>
                                            <form id="form_{{ $tarefa['id'] }}"
                                                action="{{ route('tarefa.destroy', ['tarefa' => $tarefa['id']]) }}"
                                                method="post">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" class="btn btn-danger"
                                                    onclick="document.getElementById('form_{{ $tarefa['id'] }}').submit()">
                                                    Excluir
                                                </a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="{{ $tarefas->previousPageUrl() }}">Voltar</a>
                                </li>

                                @for ($i = 1; $i <= $tarefas->lastPage(); $i++)
                                    <li class="page-item {{ $tarefas->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $tarefas->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <li class="page-item">
                                    <a class="page-link" href="{{ $tarefas->nextPageUrl() }}">Avançar</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
