<!DOCTYPE html>
<html>

<head>
    <title>Добавление адреса</title>
</head>

<body>

    <form method="POST" action="{{ route('storeaddress') }}">
        @csrf
        Адрес
        <input type="text" name="address" value="" placeholder="Введите адрес" style="width:25%;">

        <input type="submit" value="Отправить адресс">
    </form>
    <br>
    @if(!$errors->isEmpty())
        <div>{{$errors->first()}}</div>
    @endif

    @if(Session::get('success')!=null)
        <div>{{Session::get('success')}}</div>
    @endif
    <br>
    <a href="{{ route('show.addresses') }}">Таблица адресов</a>

</body>

</html>
