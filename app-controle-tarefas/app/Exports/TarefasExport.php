<?php

namespace App\Exports;

use App\Models\Tarefa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TarefasExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Tarefa::all();
        return auth()->user()->tarefas()->get();
    }

    public function headings(): array
    {
        return [
            'ID da tarefa',
            'Nome do usuário',
            'Tarefa',
            'Data limite para conclusão',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->user->name,
            $row->tarefa,
            date('d/m/Y', strtotime($row->data_limite_conclusao)),
        ];
    }
}
