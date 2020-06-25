<?php

namespace App\Libraries\Facade;

use Illuminate\Support\Facades\Log;

class NationApiConexion
{
    public function get($url,$cookies = ''){
        $response = '{}';
        $curl = curl_init();
        try{
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,     // return web page
                // CURLOPT_HEADER         => true,     //return headers in  addition to content
                CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                CURLOPT_ENCODING       => "",       // handle all encodings
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                CURLOPT_TIMEOUT        => 120,      // timeout on response
                // CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                CURLINFO_HEADER_OUT    => true,
                CURLOPT_SSL_VERIFYPEER => true,     // Validate SSL Certificates
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_COOKIE         => $cookies
            );
            curl_setopt_array( $curl, $options );
            $response = curl_exec($curl);
            
        }catch(\Exception $ex){
            Log::error($ex->getMessage());
        }finally{
            curl_close($curl);
            return json_decode($response);
        }
    }
    public function post($url,$data){
        $response = '{}';
        $curl = curl_init();
        try{
            
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url ,
                CURLOPT_USERAGENT => 'From Front End',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => json_encode($data)
            ));           
            // Send the request & save response to $resp
            $response = curl_exec($curl);
        }catch(\Exception $ex){
            Log::error($ex->getMessage());
        }finally{
            curl_close($curl);
            return json_decode($response);
        }
    }
    public function put($url,$data){
        $response = '{}';
        $curl = curl_init();
        try{
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS =>json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    "x-rapidapi-host: google-translate1.p.rapidapi.com",
                    "x-rapidapi-key: d1ec636ac5msh35885a399298175p14f2e4jsn92997c76c589",
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);
        }catch(\Exception $ex){
            Log::error($ex->getMessage());
        }finally{
            curl_close($curl);
            return json_decode($response);
        }

    }
}
