<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dadata\DadataClient;

use App\Models\Address;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use DateTimeZone;

class AddressController extends Controller
{
    private $token;
    private $secret;
    private $dadata;

    public function __construct()
    {

        $this->token = 'a733faca1da82cb5d79ce4e80787ad4ab6ba9b67';
        $this->secret = '581f751618547251874961240d87f1c6130c1a99';
        $this->dadata =  new DadataClient($this->token, $this->secret);
    }


    public function show()
    {


        $addresses = Address::orderBy('id','desc')->take(30)->get();




        $addressWithoutFias =  $addresses->where('house_fias_id', null);



        $addressesFormat = $this->getFormattedAddresses($addresses);



        $addressesforTime = array();




        foreach ($addresses as $address) {

            if (explode(":", date($address->created_at))[2] > 20 && explode(":", date($address->created_at))[2] < 40){
                $addressesforTime[] =$address;
            }

        }
       // dd(__METHOD__,$addressesforTime);



        return view('addresses.all_addresses', compact('addressesFormat', 'addressWithoutFias','addressesforTime'));
    }

    public function formAddress(){

        return view('addresses.form_address');
    }

    public function storeAddress(Request $request)
    {



        $address = $this->dadata->suggest('address',$request->address,1);

        // dd(Address::select()->where('address',$address[0]['value'])->get()->isEmpty());
        $message = '';
        $formatedAddress = array();

        if($address!=null && $address!=''){

            if(!Address::select()->where('city_with_type',$address[0]['data']['city_with_type'])->get()->isEmpty())
            {

                // dd($address[0]['data']['city_with_type']);
                $success[] = 'Такой город уже присутствует в базе данных';
                if(!Address::select()->where('address',$address[0]['value'])->get()->isEmpty())
                {
                    $error = 'Ошибка -> Такой адрес уже присутствует в базе данных!';
                    return redirect()->back()->withErrors($error);
                }
            }



            $formatedAddress['address'] = $address[0]['value'];
            $formatedAddress['house_fias_id'] = $address[0]['data']['house_fias_id'];
            $formatedAddress['region_with_type'] = $address[0]['data']['region_with_type'];
            $formatedAddress['city_with_type'] = $address[0]['data']['city_with_type'];
            $formatedAddress['street_with_type'] = $address[0]['data']['street_with_type'];


        } else{
            return redirect()->back()->withErrors('Адрес не был найден');;

        }
        $newAddress = new Address($formatedAddress);
        $newAddress->save();

        $success[] = ' Сохранение в базу было успешно'  ;

        return redirect()->route('formaddress')->with('success',$success);






    }

    public function try()
    {

    }


    public function getFormattedAddresses($addresses)
    {

        $regions = array();
        foreach ($addresses as $address) {
            // dd(__METHOD__,$address->created_at);
            if (in_array($address->region_with_type, $regions) == null) {
                $regions[] = $address->region_with_type;
            }
        }

        $addressesFormat = new Collection();

        foreach ($regions as $region) {

            $addresesRegion = $addresses->where('region_with_type', $region);


            $addressesFormat = $addressesFormat->merge($addresesRegion);
        }
        return $addressesFormat;
    }
}
