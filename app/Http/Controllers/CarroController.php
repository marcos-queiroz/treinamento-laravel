<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CarroController extends Controller
{
    protected $carro;

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
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

        $carros = Cache::remember($request->fullUrl(), 600, function () {
            $carroRepository = new CarroRepository($this->carro);

            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
            $carroRepository->selectAtributosRegistrosRelacionados('clientes');

            if ($this->request->has('atributos')) {
                $carroRepository->selectAtributos('modelo_id,' . $this->request->atributos);
            }

            if ($this->request->has('atributos_modelo')) {
                $carroRepository->selectAtributosRegistrosRelacionados('modelo:id,' . $this->request->atributos_modelo);
            }

            if ($this->request->has('filtro')) {
                $carroRepository->filtro($this->request->filtro);
            }

            return $carroRepository->getResultadoPaginado(4);
        });

        return response()->json($carros, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules());

        $carro = $this->carro->create($request->all());

        Cache::flush();

        return response()->json($carro, 201);
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

        $carro = Cache::remember('carro_' . $id, 600, function () {
            $this->carro->with('modelo')->find($this->id);
        });

        if (is_null($carro)) {
            return response()->json(['error' => 'recurso solicitado não existe'], 404);
        }

        return response()->json($carro, 200);
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
        $carro = $this->carro->find($id);

        if (is_null($carro)) {
            return response()->json(['error' => 'impossível realizar a atualização, recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];

            foreach ($carro->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($carro->rules());
        }

        $carro->fill($request->all());

        $carro->save();

        Cache::flush();

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if (is_null($carro)) {
            return response()->json(['error' => 'impossível realizar a exclusão, recurso solicitado não existe'], 404);
        }

        $carro->delete();

        Cache::flush();

        return response()->json(['msg' => 'O carro ' . $carro->placa . ' foi removido com sucesso'], 200);
    }
}
