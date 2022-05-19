<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexZipCodeRequest;
use App\Models\{ZipCode, Settlement};
use DB;
use Illuminate\Http\{JsonResponse, Request};
use App\Traits\ApiResponser;

class ZipCodesController extends Controller
{
  use ApiResponser;

  public function __construct(ZipCode $model)
  {
      $this->model = $model;
  }

  public function index($zip_code)
  {
    return
      $this->showOne(
        $this->model
             ->with(
               'settlements.settlementType',
               'settlements.municipality',
               'settlements.municipality.federalEntity'
             )
             ->findByZipCode($zip_code)
             ->first()
      );
  }

}
