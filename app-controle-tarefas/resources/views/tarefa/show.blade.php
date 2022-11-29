@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $tarefa->tarefa }}</div>

                    <div class="card-body">
                        Data limite para conclusão: {{ date('d/m/Y', strtotime($tarefa->data_limite_conclusao)) }} <br>
                        Responsável: {{ $tarefa->user->name }}
                    </div>
                    <div class="card-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-primary">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
