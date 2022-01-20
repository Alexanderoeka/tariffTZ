<?php

use Illuminate\Database\Seeder;
use Dadata\DadataClient;
use Illuminate\Support\Facades\DB;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $token ='a733faca1da82cb5d79ce4e80787ad4ab6ba9b67';
        $secret='581f751618547251874961240d87f1c6130c1a99';
        $dadata =  new DadataClient($token, $secret);
        $addresses_array = [
            'Россия, г. Екатеринбург, Советская ул., д. 20 ',
            'г. Стерлитамак, Садовый пер., д. 25 ',
            'г. Щёлково, Речной пер., д. 18 ',
            'г. Миасс, Шоссейная ул., д. 25',
            ' г. Вологда, Мичурина ул., д. 9 ',
            ' г. Ярославль, Минская ул., д. 20 ',
            'г. Ставрополь, Песчаная ул., д. 9 ',
            ' г. Майкоп, Западная ул., д. 22 ',
            'г. Орехово-Зуево, Дружбы ул., д. 21 ',
            ' г. Тверь, Полевой пер., д. 19 ',
            'г. Кисловодск, 17 Сентября ул., д. 19 ',
            'г. Коломна, Парковая ул., д. 2 ',
            'г. Курган, Калинина ул., д. 2 ',
            'г. Тамбов, Молодежный пер., д. 10 ',
            'г. Сочи, Солнечная ул., д. 11 ',
            'г. Димитровград, Солнечная ул., д. 12 ',
            ' г. Долгопрудный, Зеленая ул., д. 16 ',
            'г. Дзержинск, Солнечный пер., д. 19 ',
            'г. Невинномысск, Зеленая ул., д. 3 ',
            'г. Салават, Новая ул., д. 21 ',
            'г. Коломна, Строителей ул., д. 9 ',
            'г. Камышин, Красноармейская ул., д. 4 ',
            'г. Ессентуки, Коммунистическая ул., д. 5 ',
            'г. Рыбинск, Полесская ул., д. 9 ',
            'г. Орехово-Зуево, Дзержинского ул., д. 9 ',
            'г. Барнаул, Песчаная ул., д. 24 ',
            ' г. Нижний Новгород, Лесной пер., д. 9'
        ];
        foreach ($addresses_array as $address) {
            $responses[] = $dadata->clean("address", $address);
        }
        $addressesForDB = array();

        $i = 0;
        foreach ($responses as $response) {
            $addressesForDB[$i]['address'] = $response['result'];
            $addressesForDB[$i]['house_fias_id'] = $response['house_fias_id'];

                $i++;
        }
        foreach($addressesForDB as $addressForDB){
        DB::table('addresses')->insert($addressForDB);
}
    }
}
