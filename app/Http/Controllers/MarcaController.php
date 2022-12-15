<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\CashFlow\Constant\Periodic\Interest;
use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\Cache;

class MarcaController extends Controller
{
    protected $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
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

        $marcas = Cache::remember($request->fullUrl(), 600, function () {
            $marcaRepository = new MarcaRepository($this->marca);

            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');

            if ($this->request->has('atributos')) {
                $marcaRepository->selectAtributos($this->request->atributos);
            }

            if ($this->request->has('atributos_modelos')) {
                $marcaRepository->selectAtributosRegistrosRelacionados('modelos:id,marca_id,' . $this->request->atributos_modelos);
            }

            if ($this->request->has('filtro')) {
                $marcaRepository->filtro($this->request->filtro);
            }

            return $marcaRepository->getResultadoPaginado(4);
        });

        return response()->json($marcas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules());

        $imagem = $request->file('imagem');
        $urn_imagem = $imagem->store('imagens/marcas', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $urn_imagem
        ]);

        Cache::flush();

        return response()->json($marca, 201);
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

        $marca = Cache::remember('marca_'. $id, 600, function () {
            return $this->marca->with('modelos')->find($this->id);
        });
        
        if (is_null($marca)) {
            return response()->json(['error' => 'recurso solicitado não existe'], 404);
        }

        return response()->json($marca, 200);
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
        $marca = $this->marca->find($id);

        if (is_null($marca)) {
            return response()->json(['error' => 'impossível realizar a atualização, recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];

            foreach ($marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($marca->rules());
        }

        $marca->fill($request->all());

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);

            $imagem = $request->file('imagem');
            $marca->imagem = $imagem->store('imagens/marcas', 'public');
        }

        $marca->save();

        Cache::flush();

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if (is_null($marca)) {
            return response()->json(['error' => 'impossível realizar a exclusão, recurso solicitado não existe'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();

        Cache::flush();

        return response()->json(['msg' => 'A marca ' . $marca->nome . ' foi removida com sucesso'], 200);
    }
}
