<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class Httpsimprodi {
    private $secret;
    private $apiUrl;
    private $apiKey;
    public  $apiAuth;

    function __construct() {
      $this->secret   = env('API_SRT_SIMPRODI', '');
      $this->apiUrl   = env('API_URL_SIMPRODI', 'https://simprodi.ummetro.ac.id/');
      $this->apiKey   = env('API_KEY_SIMPRODI', 'UmMetro021004*@*');
    }

    public function request($method, $route, $data = [])
    {
      $data['apikey'] = sha1($this->apiKey);
      try {
        if($method == 'GET') {
          $response   = Http::withHeaders([
            'Accept' => 'application/json',
          ])->get($this->apiUrl. $route, $data);
        }
        if($method == 'POST') {
          $response   = Http::withHeaders([
            'Accept' => 'application/json',
          ])->post($this->apiUrl. $route, $data);
        }

        if($method == 'PUT') {
          $response   = Http::withHeaders([
            'Accept' => 'application/json',
          ])->put($this->apiUrl. $route, $data);
        }

        if($method == 'DELETE') {
          $response   = Http::withHeaders([
            'Accept' => 'application/json',
          ])->delete($this->apiUrl. $route, $data);
        }
        
        if($response->status() !== 200) {
            $body     = json_decode($response->body());
            $isMsg    = (isset($body->message)) ? $body->message : '';
            return (object) array('code' => $response->status(), 'messages'=> $isMsg, 'data'=> []);
        }
        
        $dataBody = json_decode($response->body());
        $data = (isset($dataBody->data)) ? $dataBody->data : $dataBody;
        return (object) array('code' => 200, 'messages'=> '', 'data'=> $data);
      }
      catch (\Exception $e) {
        return (object) array('code' => 500, 'messages'=> $e->getMessage(), 'data'=> []);
      }
    }
}
