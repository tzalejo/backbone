<?php

namespace App\Console\Commands;

use App\Models\FederalEntity;
use App\Models\Municipality;
use App\Models\Settlement;
use App\Models\SettlementType;
use App\Models\ZipCode;
use Illuminate\Console\Command;
use phpDocumentor\Reflection\Types\This;
use PHPExcel_IOFactory;

class ImportZipCodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:zipcode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $file = file(public_path().'/zipcode.txt');
        $file = array_chunk($file, 20, true);

        $zipCodes = [];
        $federals = [];
        $settTypes = [];
        $munis = [];
        $settements= [];

        foreach($file as $lineas){
          foreach($lineas as $i => $linea){
            if($i==0){
              logger($linea);
              continue;
            }
            $linea = rtrim($linea, '\r\n');
            $lineaConFormato = explode('|', $linea);
            // zip code
            $zipCodes[$lineaConFormato[0]] = $lineaConFormato[0]; //d_codigo

            $settTypes[(int) $lineaConFormato[9]] = $lineaConFormato[2]; // c_tipo_asenta = d_tipo_asenta

            $settements[]= [
              'key' => $lineaConFormato[11],
              'name' =>$lineaConFormato[1],
              'zone_type' =>$lineaConFormato[12],
              'settlement_type_id' => (int) $lineaConFormato[9],
              'municipality_id' => (int) $lineaConFormato[10],
              'zipcode' => $lineaConFormato[0]
            ];

            $munis[(int) $lineaConFormato[10]] = [
              'name' => $lineaConFormato[3],
              'federal_entity_id' => (int) $lineaConFormato[7]
            ];

            $federals[(int) $lineaConFormato[7]] = $lineaConFormato[4]; // c_estado = d_estado
          }
        }
        // creo los codigo postal
        foreach($zipCodes as $zc){
          ZipCode::create([
            'zip_code' => $zc
          ]);
        }

        // creo lo settlement type
        foreach($settTypes as $i => $st){
          SettlementType::create([
            'id' => $i,
            'name' => $st
          ]);
        }

        // creo federal_entities
        foreach($federals as $i => $fe){
          FederalEntity::create([
            'id' => $i,
            'name' => $fe,
          ]);
        }

        // creo municipalities
        foreach($munis as $i => $muni){
          Municipality::create([
            'id' => $i,
            'name' => $muni['name'],
            'federal_entity_id' => $muni['federal_entity_id']
          ]);
        }

        // creo settlement
        foreach($settements as $sett){
          $zipCode = ZipCode::where('zip_code', $sett['zipcode'])->first();
          logger("Busco ". $sett['zipcode']);
          logger($zipCode->zip_code);
          Settlement::create([
            'key' => $sett['key'],
            'name' => $sett['name'],
            'zone_type' => $sett['zone_type'],
            'settlement_type_id' => $sett['settlement_type_id'],
            'municipality_id' =>  $sett['municipality_id'],
            'zip_code_id' => $zipCode->id
          ]);
        }

    }
}
