<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Статистика</title>
    <link
        href="//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
    <div class="container">
        <h1 class="text-center">Игровая статистика</h1>
        <br>
        <br>
        <table class="text-center">
            @foreach($stats as $name => $stat)
                @if($name == 'winner')
                    <dl class="row">
                        <dt class="col-sm-3">Победитель</dt>
                        <dd class="col-sm-9"> {{$stat}} </dd>
                    </dl>
                @endif
                @if($name == 'allShots')
                    <dl class="row">
                        <dt class="col-sm-3">Всего выстрелов за игру:</dt>
                        <dd class="col-sm-9"> {{$stat}} </dd>
                    </dl>
                @endif
                    @if($name == 'firstPlayer' || $name == 'secondPlayer')
                        <td>
                            <dl class="row">
                                <dt class="col-sm-3">Имя игрока:</dt>
                                <dd class="col-sm-9"> {{$stat['name']}} </dd>

                                <dt class="col-sm-3">Сделано выстрелов:</dt>
                                <dd class="col-sm-9"> {{$stat['shots']}} </dd>

                                <dt class="col-sm-3">Оставшееся здоровье:</dt>
                                <dd class="col-sm-9"> {{$stat['health']}}</dd>
                            </dl>
                        </td>
                    @endif
            @endforeach
        </table>
    </div>
    <br>
    <footer class="text-center">
        <a href="/">На главную</a>
    </footer>
</body>
</html>
