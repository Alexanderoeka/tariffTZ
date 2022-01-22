<?php

use Illuminate\Database\Seeder;
use Dadata\DadataClient;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Сидер добавления адресов
class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $token = 'a733faca1da82cb5d79ce4e80787ad4ab6ba9b67';
        $secret = '581f751618547251874961240d87f1c6130c1a99';
        $dadata =  new DadataClient($token, $secret);
        $addresses_array = [
            ' Советская ул., д. 20 ', //г Самар ул Советска 10
            ' ул Советская 10',
            ' Речной пер., д. 18 ',
            ' Шоссейная ул., д. 25',
            '  Мичурина ул., д. 9 ',
            ' Минская ул., д. 20 ',
            ' Песчаная ул., д. 9 ',
            '  Западная ул., д. 22 ',
            ' Дружбы ул., д. 21 ',
            '  Полевой пер., д. 19 ',
            ' 17 Сентября ул., д. 19 ',
            ' Парковая ул., д. 2 ',
            ' Калинина ул., д. 2 ',
            ' Молодежный пер., д. 10 ',
            ' Солнечная ул., д. 11 ',
            ' Солнечная ул., д. 12 ',
            '  Зеленая ул., д. 16 ',
            ' Солнечный пер., д. 19 ',
            ' Зеленая ул., д. 3 ',
            ' Новая ул., д. 21 ',
            // ' Строителей ул., д. 9 ',
            // 'Красноармейская ул., д. 4 ',
            // ' Коммунистическая ул., д. 5 ',
            // ' Полесская ул., д. 9 ',
            // ' Дзержинского ул., д. 9 ',
            // 'Песчаная ул., д. 24 ',
            // '  Нижний Новгород, Лесной пер., д. 9',
            // ' шоссе Балканская, 03',
            // ' въезд Славы, 54',
            // 'город Ступино, спуск Будапештсткая, 51',
            // 'город Луховицы, пл. 1905 года, 53',
            'город Подольск, въезд Будапештсткая, 08',
            'город Коломна, проезд Балканская, 13',
            'улица Мичурина, 3',
            'улица Чкалова, 90А',
            'Больничная улица, 1Б',
            'улица Пушкина, 223',
            'улица Пушкина, 195',
            'улица Пушкина, 181',
            'Арцыбушевская улица, 167',
            'Ленинская улица, 264',
            'Ленинская улица, 219',
            'Ленинская улица, 217',
            'Ленинская улица, 215',
            'Ярмарочная улица, 29',
            'Садовая улица, 233',
            'Садовая улица, 221'



        ];

        // Каждый отдельный адрес проходит через API
        // и если он существует, то добовляется в массив адресов с данными
        // о адресе
        foreach ($addresses_array as $address) {
            $add  = $dadata->suggest("address", $address, 5);
            if (isset($add[0]['value']) != null) {
                $responses[] = $add;
            }
        }
        // Массив, который пойдет в бд
        $addressesForDB = array();




        $i = 0;

        // Запись нужных данных в массив бд
        foreach ($responses as $elem) {

            $addressesForDB[$i]['address'] = $elem[0]['value'];
            $addressesForDB[$i]['house_fias_id'] = $elem[0]['data']['house_fias_id'];
            $addressesForDB[$i]['region_with_type'] = $elem[0]['data']['region_with_type'];
            $addressesForDB[$i]['city_with_type'] = $elem[0]['data']['city_with_type'];
            $addressesForDB[$i]['street_with_type'] = $elem[0]['data']['street_with_type'];
            // Условие, добавляющие время "создания" записи с 20 по 40 секунду
            if ($i > count($responses) - 7) {
                $addressesForDB[$i]['created_at'] = date('Y-m-d H:i:s', strtotime('2022-01-19 17:16:' . rand(21, 39)));
            } else {
                $addressesForDB[$i]['created_at'] = Carbon::now(new DateTimeZone('Europe/Samara'));
            }
            $addressesForDB[$i]['updated_at'] = $addressesForDB[$i]['created_at'];
            $i++;
        }

        // Запись отформатированных адресов в таблицу `addresses`
        foreach ($addressesForDB as $addressForDB) {
            DB::table('addresses')->insert($addressForDB);
        }
    }
}
