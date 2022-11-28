<form action="{{ isset($pedido->id) ? route('pedido.update', ['pedido' => $pedido->id]) : route('pedido.store') }}"
    method="POST">
    @csrf
    @if (isset($pedido->id))
        @method('PUT')
    @endif

    <select name="cliente_id" class="borda-preta">
        <option value="">Selecione um cliente</option>
        @foreach ($clientes as $cliente)
            <option value="{{ $cliente->id }}"
                {{ ($pedido->cliente_id ?? old('cliente_id')) == $cliente->id ? 'selected' : '' }}>
                {{ $cliente->nome }}
            </option>
        @endforeach
    </select>
    {{ $errors->has('cliente_id') ? $errors->first('cliente_id') : '' }}

    <button type="submit" class="borda-preta">
        {{ isset($pedido->id) ? 'Atualizar' : 'Cadastrar' }}
    </button>
</form>
