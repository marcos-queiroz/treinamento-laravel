<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    protected $locacao;

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        $locacaoRepository->selectAtributosRegistrosRelacionados('cliente');
        $locacaoRepository->selectAtributosRegistrosRelacionados('carro');

        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos('cliente_id,carro_id,' . $request->atributos);
        }

        if ($request->has('atributos_cliente')) {
            $locacaoRepository->selectAtributosRegistrosRelacionados('cliente:id,' . $request->atributos_cliente);
        }

        if ($request->has('atributos_carro')) {
            $locacaoRepository->selectAtributosRegistrosRelacionados('carro:id,' . $request->atributos_carro);
        }

        if ($request->has('filtro')) {
            $locacaoRepository->filtro($request->filtro);
        }

        $locacoes = $locacaoRepository->getResultado();

        return response()->json($locacoes, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules());

        $locacao = $this->locacao->create($request->all());

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->with('cliente', 'carro')->find($id);

        if (is_null($locacao)) {
            return response()->json(['error' => 'recurso solicitado não existe'], 404);
        }

        return response()->json($locacao, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if (is_null($locacao)) {
            return response()->json(['error' => 'impossível realizar a atualização, recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];

            foreach ($locacao->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($locacao->rules());
        }

        $locacao->fill($request->all());

        $locacao->save();

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if (is_null($locacao)) {
            return response()->json(['error' => 'impossível realizar a exclusão, recurso solicitado não existe'], 404);
        }

        $locacao->delete();

        return response()->json(['msg' => 'A Locação ' . $locacao->id . ' foi removida com sucesso'], 200);
    }
}
