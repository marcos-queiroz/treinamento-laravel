<form action="{{ route('pedido-produto.store', ['pedido' => $pedido]) }}" method="POST">
    @csrf

    <div class="informacao-pagina">
        <hr>
        <h4>Detalhes do pedido</h4>
        <p>ID do pedido: {{ $pedido->id }}</p>
        <p>Cliente: {{ $pedido->cliente_id }}</p>
    </div>

    <select name="produto_id" class="borda-preta">
        <option value="">Selecione um produto</option>
        @foreach ($produtos as $produto)
            <option value="{{ $produto->id }}" {{ old('produto_id') == $produto->id ? 'selected' : '' }}>
                {{ $produto->nome }}
            </option>
        @endforeach
    </select>
    {{ $errors->has('produto_id') ? $errors->first('produto_id') : '' }}

    <input type="number" name="quantidade" class="borda-preta" placeholder="quantidade" value="{{ old('quantidade') ?? '' }}">
    {{ $errors->has('quantidade') ? $errors->first('quantidade') : '' }}

    <button type="submit" class="borda-preta">
        {{ isset($pedido_produto->id) ? 'Atualizar' : 'Cadastrar' }}
    </button>
</form>
