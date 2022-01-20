<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    private $token;
    private $secret;
    private $dadata;

    public function __construct(){

        $this->token ='a733faca1da82cb5d79ce4e80787ad4ab6ba9b67';
        $this->secret='581f751618547251874961240d87f1c6130c1a99';
        $this->dadata =  new \Dadata\DadataClient($this->token, $this->secret);
    }

    public function index(){
        echo 'hii';
        $response = $this->dadata->clean("address", "мск сухонская 11 89");

        echo '<br>';
        print_r($response['result']);
    }
}
