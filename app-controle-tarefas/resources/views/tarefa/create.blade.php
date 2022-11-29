@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Adicionar tarefa
                    </div>

                    <div class="card-body">
                        <form action="{{ route('tarefa.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="tarefa">Tarefa</label>
                                <input type="text" name="tarefa" id="tarefa" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="data_limite_conclusao">Data limite para conclusão</label>
                                <input type="date" name="data_limite_conclusao" id="data_limite_conclusao"
                                    class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Cadastrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
