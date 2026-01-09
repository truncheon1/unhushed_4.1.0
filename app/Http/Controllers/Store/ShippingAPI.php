<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingAPI extends Controller
{
    const API_USERNAME = '111UNHUS6365';
    const API_PASSWORD = '860ZS72BQ848';
    const ZIP_CODE = '78737';

    private $req = 'https://secure.shippingapis.com/ShippingAPI.dll';
    private $messages = [];
    private $response = [];


    public function check_address($fields){
        $apiRequest = '<AddressValidateRequest USERID="'.self::API_USERNAME.'">'
                . '<Revision>1</Revision>'
                . '<Address ID="0">'
                    .   '<Address1>'.$fields['address'].'</Address1>'
                    .   '<Address2 />'
                    .   '<City>'.$fields['city'].'</City>'
                    .    '<State>'.$fields['state'].'</State>'
                . '<Zip5>'.$fields['zip'].'</Zip5>'
                . '<Zip4 />'
                . '</Address>'
                . '</AddressValidateRequest>';


        $rtn = $this->makeRequest('?API=Verify&XML=', $apiRequest);

        if($rtn->Address->Error){

            $this->log_message(trim($rtn->Address->Error->Description->__toString()));
            return false;
        }else{
            $this->keep_response($rtn);
            return true;
        }

    }

    public function check_cost($zip, $shipment_type, $pounds){
        $machinable = $shipment_type == "MEDIA" ? "" : "<Machinable>true</Machinable>";
        $apiRequest = ''
                . '<RateV4Request USERID="'.self::API_USERNAME.'">'
                . '<Revision>2</Revision>'
                . '<Package ID="0">'
                . '<Service>'.$shipment_type.'</Service>'
                . '<ZipOrigination>'.self::ZIP_CODE.'</ZipOrigination>'
                . '<ZipDestination>'.$zip.'</ZipDestination>'
                .'<Pounds>'.$pounds.'</Pounds><Ounces>0</Ounces><Container>VARIABLE</Container>'.$machinable.'</Package></RateV4Request>';

        $rtn = $this->makeRequest('?API=RateV4&XML=', $apiRequest);

        if($rtn->Package->Error){
            $this->log_message(trim($rtn->Package->Error->Description->__toString()));
            return false;
        }else{
            $this->keep_response($rtn);
            return true;
        }
    }

    private function keep_response($rtn){
        $this->response = $rtn;
    }

    public function get_last_response(){
        return $this->response;
    }

    private function log_message($msg){
        $this->messages[] = $msg;
    }

    public function get_last_message(){
        return count($this->messages)? array_pop($this->messages) : '';
    }

    private function makeRequest($uri, $apiRequest){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->req.$uri.urlencode($apiRequest));
        // SSL important
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


        $output = curl_exec($ch);
        curl_close($ch);
        $xml = new \SimpleXMLElement($output);
        return $xml;


    }
}
