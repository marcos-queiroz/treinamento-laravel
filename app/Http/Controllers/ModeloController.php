<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    protected $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
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

        $modelos = Cache::remember($request->fullUrl(), 600, function () {
            $modeloRepository = new ModeloRepository($this->modelo);

            $modeloRepository->selectAtributosRegistrosRelacionados('marca');

            if ($this->request->has('atributos')) {
                $modeloRepository->selectAtributos('marca_id,' . $this->request->atributos);
            }

            if ($this->request->has('atributos_marca')) {
                $modeloRepository->selectAtributosRegistrosRelacionados('marca:id,' . $this->request->atributos_marca);
            }

            if ($this->request->has('filtro')) {
                $modeloRepository->filtro($this->request->filtro);
            }

            return $modeloRepository->getResultado();
        });

        return response()->json($modelos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $urn_imagem = $imagem->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $urn_imagem,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);

        Cache::flush();

        return response()->json($modelo, 201);
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

        $modelo = Cache::remember('modelo_' . $id, 600, function () {
            $this->modelo->with('marca')->find($this->id);
        });

        if (is_null($modelo)) {
            return response()->json(['error' => 'recurso solicitado não existe'], 404);
        }

        return response()->json($modelo, 200);
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
        $modelo = $this->modelo->find($id);

        if (is_null($modelo)) {
            return response()->json(['error' => 'impossível realizar a atualização, recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];

            foreach ($modelo->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($modelo->rules());
        }

        $modelo->fill($request->all());

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);

            $imagem = $request->file('imagem');
            $modelo->imagem = $imagem->store('imagens/modelos', 'public');
        }

        $modelo->save();

        Cache::flush();

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if (is_null($modelo)) {
            return response()->json(['error' => 'impossível realizar a exclusão, recurso solicitado não existe'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();

        Cache::flush();

        return response()->json(['msg' => 'O modelo ' . $modelo->nome . ' foi removida com sucesso'], 200);
    }
}
