<?php

namespace App\Http\Controllers;

use App\Models\{ZipCode};
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Cache;

class ZipCodesController extends Controller
{
    use ApiResponse;

    public function __construct(ZipCode $model)
    {
        $this->model = $model;
    }

    public function show($zip_code)
    {
        return Cache::remember("zipcode_$zip_code", '60', function () use ($zip_code) {
            return $this->showOne(
                $this->model
                    ->with(
                        'settlements.settlementType',
                        'settlements.municipality',
                        'settlements.municipality.federalEntity'
                    )
                    ->findByZipCode($zip_code)
                    ->firstOrFail()
            );
        });
    }

}
