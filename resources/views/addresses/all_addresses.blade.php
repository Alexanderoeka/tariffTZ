<!DOCTYPE html>

<html>

<head>
    <title>Все адреса</title>

</head>

<body>
    <a href="{{route('formaddress')}}">Ввести адрес</a>
    <h4>Все адреса сгруппированные по регионам</h4>
    <table border="1">
        <tr>
            <th>#</th>
            <th>Адрес</th>
            <th>ФИАС</th>
            <th>Регион</th>
            <th>Город</th>
            <th>Улица</th>

        </tr>
        @foreach ($addressesFormat as $address)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>

                <td>{{ $address->address }}</td>
                <td>{{ $address->house_fias_id ?? 'ФИАС отсутствует' }}</td>
                <td>{{ $address->region_with_type }}</td>
                <td>{{ $address->city_with_type }}</td>
                <td>{{ $address->street_with_type }}</td>
            </tr>
        @endforeach
    </table>

    <br><br><br><br><br>

    <h4>Все адреса у которых отсутствует ФИАС дома</h4>
    <table border="1">
        <tr>
            <th>#</th>
            <th>Адрес</th>
            <th>Регион</th>
            <th>Город</th>
            <th>Улица</th>

        </tr>
        @foreach ($addressWithoutFias as $address)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>

                <td>{{ $address->address }}</td>
                <td>{{ $address->region_with_type }}</td>
                <td>{{ $address->city_with_type }}</td>
                <td>{{ $address->street_with_type }}</td>
            </tr>
        @endforeach
    </table>

    <br><br><br><br>

    <h4>Все адреса которые были сохранены в интервале с 20 по 40 секунду</h4>
    <table border="1">
        <tr>
            <th>#</th>
            <th>Адрес</th>
            <th>Регион</th>
            <th>Город</th>
            <th>Улица</th>
            <th>Дата создания</th>

        </tr>
        @foreach ($addressesforTime as $address)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>

                <td>{{ $address->address }}</td>
                <td>{{ $address->region_with_type }}</td>
                <td>{{ $address->city_with_type }}</td>
                <td>{{ $address->street_with_type }}</td>
                <td>{{$address->created_at}}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>
