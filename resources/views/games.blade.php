<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Доступные игры</title>
    <link
        href="//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<div class="container-sm">
    <h1 class="text-center">Список существующих игр</h1>
    <table class="table table-hover">
        <tr class="text-center">
            <th>ID игры</th><th>Первый игрок</th><th>Второй игрок</th><th>Статус игры</th><th>Победитель</th>
        </tr>
        @foreach ($records as $record)
            <tr class="text-center">
                <td>
                    {{$record['id']}}
                </td>
                <td>
                    <a href={{ $record['codeLink'] }}> {{$record['code']}} </a>
                </td>
                <td>
                    <a href={{ $record['inviteLink'] }}> {{$record['invite']}} </a>
                </td>
                <td>
                    {{ $record['status'] }}
                </td>
                <td>
                    @if ($record['status'] == "Игра закончена")
                        {{ $record['turn'] }}
                        <a href={{ $record['statsLink'] }}>Статистика</a>
                    @endif
                </td>

            </tr>
        @endforeach
    </table>
</div>

</body>
</html>
