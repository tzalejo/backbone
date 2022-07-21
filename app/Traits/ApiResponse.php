<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    # para cuando fue satisfactorio
    private function successResponse($data, $code): JsonResponse
    {
        return response()->json($data, $code);
    }


    protected function showOne(Model $instancia, $code = Response::HTTP_OK): JsonResponse
    {
        $municipality = $instancia->settlements->first()->municipality ?? '';
        $response = [
            'zip_code' => $instancia->zip_code ?? '',
            'locality' => $municipality->name ?? '' ,
            'federal_entity' => $municipality->federalEntity ?? '',
            'settlements' => $instancia->settlements ?? '',
            'municipality' => $municipality ?? '',
        ];

        return $this->successResponse($response, $code);
    }
}
