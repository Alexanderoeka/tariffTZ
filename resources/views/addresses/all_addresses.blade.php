<!DOCTYPE html>

<html>

<head>
    <title>Все адреса</title>

</head>

<body>
    <table>
        <tr>
            <th>#</th>
            <th>Адрес</th>
            <th>ФИАС</th>

        </tr>
        @foreach ($addresses as $address)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>

                <td>{{ $address->address }}</td>
                <td>{{ $address->house_fias_id ?? 'ФИАС отсутствует' }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>
