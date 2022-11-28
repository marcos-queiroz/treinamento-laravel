@extends('site.layouts.basico')

@section('title', 'Login')

@section('conteudo')

    <div class="conteudo-pagina">
        <div class="titulo-pagina">
            <h1>Login</h1>
        </div>

        <div class="informacao-pagina">
            <div style="width: 30%; margin-left: auto; margin-right: auto;">
                <form action="{{ route('site.login') }}" method="POST">
                    @csrf
                    <input name="usuario" type="text" placeholder="Usuário" class="borda-preta" value="{{ old('usuario') }}">
                    {{ $errors->has('usuario') ? $errors->first('usuario') : '' }}
                    <br>
                    <input name="senha" type="password" placeholder="Usuário" class="borda-preta" value="{{ old('senha') }}">
                    {{ $errors->has('senha') ? $errors->first('senha') : '' }}
                    <br>
                    <button type="submit" class="borda-breta">Acessar</button>
                </form>

                {{ isset($erro) && !empty($erro) ? $erro : '' }}
            </div>
        </div>  
    </div>

    @include('site.layouts._partials.rodape')
@endsection