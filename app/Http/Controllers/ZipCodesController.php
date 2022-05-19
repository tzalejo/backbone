<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexZipCodeRequest;
use App\Models\{ZipCode, Settlement};
use DB;
use Illuminate\Http\{JsonResponse, Request};
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Cache;

class ZipCodesController extends Controller
{
  use ApiResponser;

  public function __construct(ZipCode $model)
  {
      $this->model = $model;
  }

  public function index($zip_code)
  {
    return Cache::remember("zipcode_$zip_code", '15', function () use($zip_code) {
      return $this->showOne(
        $this->model
             ->with(
               'settlements.settlementType',
               'settlements.municipality',
               'settlements.municipality.federalEntity'
             )
             ->findByZipCode($zip_code)
             ->first()
      );
    });
  }

}
