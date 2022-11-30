<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>

    <style>
        .titulo {
            border: 1px;
            background-color: #c2c2c2;
            text-align: center;
            width: 100%;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Tahoma, Geneva, sans-serif;
        }

        table td {
            padding: 15px;
        }

        table thead th {
            text-align: left;
            background-color: #54585d;
            color: #ffffff;
            font-weight: bold;
            font-size: 13px;
            border: 1px solid #54585d;
        }

        table tbody td {
            color: #636363;
            border: 1px solid #dddfe1;
        }

        table tbody tr {
            background-color: #f9fafb;
        }

        table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="titulo">Lista de tarefas</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tarefa</th>
                <th>Data limite para conclusão</th>
                <th>Nome do usuário</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($tarefas as $tarefa)
                <tr>
                    <td>{{ $tarefa->id }}</td>
                    <td>{{ $tarefa->tarefa }}</td>
                    <td>{{ date('d/m/Y', strtotime($tarefa->data_limite_conclusao)) }}</td>
                    <td>{{ $tarefa->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <div class="page-break"></div> --}}
</body>

</html>
