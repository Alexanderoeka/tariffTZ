<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dadata\DadataClient;

use App\Models\Address;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use DateTimeZone;

// Класс контроллер для адресов
class AddressController extends Controller
{
    // Токен API
    private $token;
    // Пароль от  API
    private $secret;
    // Переменная для работы с API ( будущий объект класса )
    private $dadata;

    // Конструктор объявления токена, пароля и объекта ДаДаты
    public function __construct()
    {

        $this->token = 'a733faca1da82cb5d79ce4e80787ad4ab6ba9b67';
        $this->secret = '581f751618547251874961240d87f1c6130c1a99';
        $this->dadata =  new DadataClient($this->token, $this->secret);
    }

    // Функция вывода всех таблиц адресов(данные)
    public function show()
    {

        // Вывод 30 последних записей из БД `addresses`
        $addresses = Address::orderBy('id', 'desc')->take(30)->get();



        // Получения всех адресов где у домов нет ФИАС
        $addressWithoutFias =  $addresses->where('house_fias_id', null);


        // Форматирование адресов - группирование их по регионам
        $addressesFormat = $this->getFormattedAddresses($addresses);





        // Массив адресов с временем создания записи с 20 по 40 секунду
        $addressesforTime = $this->getAddressesbySeconds($addresses);






        return view('addresses.all_addresses', compact('addressesFormat', 'addressWithoutFias', 'addressesforTime'));
    }

    // Функция вывода страницы формы ввода адреса
    public function formAddress()
    {

        return view('addresses.form_address');
    }

    // Функция сохранения адреса введенного в форме 'formAddress'
    public function storeAddress(Request $request)
    {


        // Использование API подсказок для получения данных введенного адреса
        $address = $this->dadata->suggest('address', $request->address, 1);

        // Массив сообщений об успешном завершении записи в бд
        $success = array();

        // Массив который будет содержать в себе только нужные поля и данные
        // для последующей записи их в бд
        $formatedAddress = array();

        // Если API подсказон найдет адрес, то в последствии будут форматированны данные
        if ($address != null && $address != '') {

            // Проверка наличия города из адреса введенного в форме в бд
            if (!Address::select()->where('city_with_type', $address[0]['data']['city_with_type'])->get()->isEmpty()) {


                $success[] = 'Такой город уже присутствует в базе данных';
                // Проверка наличия полного совпадения введенного адреса с записями в бд
                // В случае наличия данной записи, будет редирект на страницу формы с сообщением об ошибке
                // Запись в бд не происходит
                if (!Address::select()->where('address', $address[0]['value'])->get()->isEmpty()) {
                    $error = 'Ошибка -> Такой адрес уже присутствует в базе данных!';
                    return redirect()->back()->withErrors($error);
                }
            }


            // Форматирование данных
            $formatedAddress['address'] = $address[0]['value'];
            $formatedAddress['house_fias_id'] = $address[0]['data']['house_fias_id'];
            $formatedAddress['region_with_type'] = $address[0]['data']['region_with_type'];
            $formatedAddress['city_with_type'] = $address[0]['data']['city_with_type'];
            $formatedAddress['street_with_type'] = $address[0]['data']['street_with_type'];
        } else {
            // В случае если API не смогла найти адреса, происходит редирект
            // на страницу формы с ошибкой
            // Запись в бд не происходит
            return redirect()->back()->withErrors('Адрес не был найден');;
        }

        // Создание объекта модели Address и запись адреса в бд
        $newAddress = new Address($formatedAddress);
        $newAddress->save();

        $success[] = ' Сохранение в базу было успешно';

        return redirect()->route('formaddress')->with('success', $success);
    }



    // Функция групировки адресов по регионам
    public function getFormattedAddresses($addresses)
    {
        // Массив колличества регионов в бд
        $regions = array();

        // Запись регионов в массив
        foreach ($addresses as $address) {

            if (in_array($address->region_with_type, $regions) == null) {
                $regions[] = $address->region_with_type;
            }
        }

        // Создание колекции для более удобного оперирования записями адресов
        $addressesFormat = new Collection();

        // Групировка адресов
        foreach ($regions as $region) {

            // Получение всех адресов с данным регионом
            $addresesRegion = $addresses->where('region_with_type', $region);

            // Добавление адресов с одним и тем же регионом в конец массива
            $addressesFormat = $addressesFormat->merge($addresesRegion);
        }

        return $addressesFormat;
    }

    // Функция возвращающая все записи адресов, созданные в промежутке с 20ой по 40ю секунду
    public function getAddressesbySeconds($addresses)
    {
        // Массив для адресов с датой создания с 20ю по 40ю секунду
        $addressesforTime = array();

        foreach ($addresses as $address) {

            // Время создания записи(только секунды  - "дата" 19:20:25) -> 25
            $secondsofCreation = explode(":", date($address->created_at))[2];

            // Если дата создания между 20 и 40 секундами, то данный адрес добовляется в массив
            if ($secondsofCreation > 20 && $secondsofCreation < 40) {
                $addressesforTime[] = $address;
            }
        }
        return $addressesforTime;
    }
}
