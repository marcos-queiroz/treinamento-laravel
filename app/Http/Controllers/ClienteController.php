<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    protected $cliente;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        $clienteRepository->selectAtributosRegistrosRelacionados('carros');

        if ($request->has('atributos')) {
            $clienteRepository->selectAtributos('id,' . $request->atributos);
        }

        if ($request->has('filtro')) {
            $clienteRepository->filtro($request->filtro);
        }

        $clientes = $clienteRepository->getResultado();

        return response()->json($clientes, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules());

        $cliente = $this->cliente->create($request->all());

        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);

        if (is_null($cliente)) {
            return response()->json(['error' => 'recurso solicitado não existe'], 404);
        }

        return response()->json($cliente, 200);
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
        $cliente = $this->cliente->find($id);

        if (is_null($cliente)) {
            return response()->json(['error' => 'impossível realizar a atualização, recurso solicitado não existe'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];

            foreach ($cliente->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($cliente->rules());
        }

        $cliente->fill($request->all());

        $cliente->save();

        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if (is_null($cliente)) {
            return response()->json(['error' => 'impossível realizar a exclusão, recurso solicitado não existe'], 404);
        }

        $cliente->delete();

        return response()->json(['msg' => 'O cliente ' . $cliente->nome . ' foi removido com sucesso'], 200);
    }
}
