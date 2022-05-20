<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;
trait ApiResponser
{
    # para cuando fue satisfactorio
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function showOne(Model $instancia, $code=Response::HTTP_OK)
    {
      /* isset($instancia)? return []: ''; */
      $response = [
            'zip_code'          => $instancia->zip_code,
            'locality'          => $instancia->settlements->first()->settlementType->name,
            'federal_entity'    => $instancia->settlements->first()->municipality->federalEntity,
            'settlements'       => $instancia->settlements,
            'municipality'      => $instancia->settlements->first()->municipality,
      ];

      return $this->successResponse($response, $code);
    }
}
