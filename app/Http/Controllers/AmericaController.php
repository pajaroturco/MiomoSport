<?php

namespace MiomoSport\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AmericaController extends Controller
{
    //
    const URL = 'https://api.sportradar.us/soccer-t3/am/es/';
    const APIKEY = 'dx7js5s9syrcvcgcjvq2zcuq';

    public function __construct(){
      $this->client = new Client([
        'base_uri' => self::URL
      ]);
    }

    public function index()
    {
      $response = $this->client->request('GET','tournaments.json',['query' => ['api_key'=>self::APIKEY]]);

      $torneos = json_decode($response->getBody());
      $tournaments = $torneos->tournaments;
      $result = array();
      foreach ($tournaments as $torneo) {
          if (empty($torneo->season_coverage_info->max_coverage_level) ||
          $torneo->season_coverage_info->max_coverage_level == 'gold') {
            array_push($result, $torneo);
          }
      }
      return view('am.index',compact('result'));
    }

    public function show($id)
    {
      $response = $this->client->request('GET','tournaments/'.$id.'/info.json',['query' => ['api_key'=>self::APIKEY]]);

      $responseData = json_decode($response->getBody());

      $name = $responseData->tournament->current_season->name;
      $id = $responseData->tournament->id;
      $category = $responseData->tournament->category;

      if ($category->id != 'sr:category:393') {
        $nombre = $name.' '.$category->name;
      }else {
        $nombre = $name;
      }

    session(['nombreTorneo' => $nombre]);

      return view('am.show',compact('name','id'));

    }

    public function equipos($id)
    {
      $response = $this->client->request('GET','tournaments/'.$id.'/info.json',['query' => ['api_key'=>self::APIKEY]]);

      $responseData = json_decode($response->getBody());

      $name = $responseData->tournament->current_season->name;
      $id = $responseData->tournament->id;
      $groups = $responseData->groups;
      $category =$responseData->tournament->category->id;

      //return count($groups);
      return view('am.equipos',compact('name','id','groups','category'));
    }
}