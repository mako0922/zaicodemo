<?php

namespace App\Http\Controllers;

//use App\Person;
//use App\Attendance;
use Illuminate\Http\Request;

class GraphController extends Controller
{
  public function index(Request $request){
  $file = public_path() . '\data\templary.json';
  $json = file_get_contents($file);
  $data = json_decode($json, true);

  return view('graph.index',['data' => $data]);
}

}
