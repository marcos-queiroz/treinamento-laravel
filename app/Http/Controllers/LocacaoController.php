<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
        $this->request = $request;

        $locacoes = Cache::remember($request->fullUrl(), 600, function () {
            $locacaoRepository = new LocacaoRepository($this->locacao);

            $locacaoRepository->selectAtributosRegistrosRelacionados('cliente');
            $locacaoRepository->selectAtributosRegistrosRelacionados('carro');

            if ($this->request->has('atributos')) {
                $locacaoRepository->selectAtributos('cliente_id,carro_id,' . $this->request->atributos);
            }

            if ($this->request->has('atributos_cliente')) {
                $locacaoRepository->selectAtributosRegistrosRelacionados('cliente:id,' . $this->request->atributos_cliente);
            }

            if ($this->request->has('atributos_carro')) {
                $locacaoRepository->selectAtributosRegistrosRelacionados('carro:id,' . $this->request->atributos_carro);
            }

            if ($this->request->has('filtro')) {
                $locacaoRepository->filtro($this->request->filtro);
            }

            return $locacaoRepository->getResultado();
        });


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

        Cache::flush();

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
        $this->id = $id;

        $locacao = Cache::remember('locacao_' . $id, 600, function () {
            return $this->locacao->with('cliente', 'carro')->find($this->id);
        });

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

        Cache::flush();

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

        Cache::flush();

        return response()->json(['msg' => 'A Locação ' . $locacao->id . ' foi removida com sucesso'], 200);
    }
}
