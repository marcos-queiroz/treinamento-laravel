<?php

namespace App\Http\Controllers;

use App\Exports\TarefasExport;
use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Excel;
use PDF;

class TarefaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;

        $tarefas = Tarefa::where('user_id', $user_id)->paginate(10);

        return view('tarefa.index', ['tarefas' => $tarefas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tarefa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all('tarefa', 'data_limite_conclusao');
        $data['user_id'] = auth()->user()->id;

        $tarefa = Tarefa::create($data);

        $destinatario = auth()->user()->email; // recupera o e-mail da session

        Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));

        return redirect()->route('tarefa.show', ['tarefa' => $tarefa]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefa.show', compact('tarefa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarefa $tarefa)
    {
        if (auth()->user()->id != $tarefa->user_id) {
            return view('acesso-negado', compact('tarefa'));
        }

        return view('tarefa.edit', compact('tarefa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        if (auth()->user()->id != $tarefa->user_id) {
            return view('acesso-negado', compact('tarefa'));
        }

        $tarefa->update($request->all());

        return redirect()->route('tarefa.show', ['tarefa' => $tarefa]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarefa $tarefa)
    {
        if (auth()->user()->id != $tarefa->user_id) {
            return view('acesso-negado', compact('tarefa'));
        }

        $tarefa->delete();

        return redirect()->route('tarefa.index');
    }

    public function exportacao($extensao)
    {
        if (!in_array($extensao, ['xlsx', 'csv', 'pdf'])) {
            return redirect()->route('tarefa.index');
        }

        return Excel::download(new TarefasExport, date('Y-m-d_H-i-s_') . 'tasks.' . $extensao);
    }

    public function exportar()
    {
        $tarefas = auth()->user()->tarefas()->get();

        $pdf = PDF::loadView('tarefa.pdf', compact('tarefas'));

        $pdf->setPaper('a4', 'portrait'); // tipo de papel, orientacao 
        
        // return $pdf->download(date('Y-m-d_H-i-s_') . 'tasks.pdf');
        
        return $pdf->stream(date('Y-m-d_H-i-s_') . 'tasks.pdf');
    }
}
