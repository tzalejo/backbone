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
        $file = file(public_path() . '/CPdescarga.txt',);
        $file = array_chunk($file, 100, true);

        $zipCodes = [];
        $federals = [];
        $settTypes = [];
        $munis = [];
        $settements = [];

        foreach ($file as $lineas) {
            foreach ($lineas as $i => $linea) {
                $linea = rtrim(utf8_encode($linea), '\r\n');
                $lineaConFormato = explode('|', $linea);
                // zip code
//                $zipCodes[$lineaConFormato[0]] = $lineaConFormato[0]; //d_codigo
//
//                $settTypes[(int)$lineaConFormato[9]] = $lineaConFormato[2]; // c_tipo_asenta = d_tipo_asenta
//
//                $settements[] = [
//                    'key' => $lineaConFormato[11], // id_asenta_cpcons
//                    'name' => $lineaConFormato[1], // d_asenta
//                    'zone_type' => $lineaConFormato[12], //d_zona
//                    'settlement_type_id' => (int)$lineaConFormato[9], // c_tipo_asenta
//                    'municipality_id' => (int)$lineaConFormato[10], //c_mnpio
//                    'zipcode' => $lineaConFormato[0] //d_codigo
//                ];
//
//                $munis[(int)$lineaConFormato[10]] = [ // c_mnpio
//                    'name' => $lineaConFormato[3], //D_mnpio
//                    'federal_entity_id' => (int)$lineaConFormato[7] //c_estado
//                ];
//
//                $federals[(int)$lineaConFormato[7]] = $lineaConFormato[4]; // c_estado = d_estado

                $zipcode = trim($lineaConFormato[0]);
//                $c_tipo_asenta = trim($lineaConFormato[10]);/**/
                $d_tipo_asenta = trim($lineaConFormato[2]);
                $id_asenta_cpcons = trim($lineaConFormato[12]);
                $d_asenta = trim($lineaConFormato[1]);
                $d_zona = trim($lineaConFormato[13]);
//                $c_mnpio = trim($lineaConFormato[11]);/**/
                $D_mnpio = trim($lineaConFormato[3]);
//                $c_estado = trim($lineaConFormato[7]);/**/
                $d_estado = trim($lineaConFormato[4]);

                $zipCodes[$zipcode] = $zipcode; //d_codigo
                $settTypes[$d_tipo_asenta] = $d_tipo_asenta; // c_tipo_asenta = d_tipo_asenta
                $settements[] = [
                    'key' => $id_asenta_cpcons, // id_asenta_cpcons
                    'name' => $d_asenta, // d_asenta
                    'zone_type' => $d_zona, //d_zona
                    'settlement_type_id' => $d_tipo_asenta, // c_tipo_asenta
//                    'municipality_id' => $c_mnpio, //c_mnpio
                    'zipcode' => $zipcode, //d_codigo
                    'municipality_id' => $D_mnpio
                ];

                $munis[$D_mnpio] = [ // c_mnpio
//                    'id' => $c_mnpio,
                    'name' => $D_mnpio, //D_mnpio
//                    'federal_entity_id' => $c_estado, //c_estado
                    'federal_entity_id' => $d_estado
                ];

                $federals[$d_estado] = $d_estado; // c_estado = d_estado
            }
        }

        // creo los codigo postal
        $this->info('Creando zipcodes');
        foreach ($zipCodes as $zc) {
            ZipCode::create([
                'zip_code' => $zc
            ]);
        }

        // creo lo settlement type
        $this->info('Creando settlement type');
        foreach ($settTypes as $i => $st) {
            SettlementType::create([
                'name' => $st
            ]);
        }

        // creo federal_entities
         $this->info('Creando federals');
        foreach ($federals as $i => $fe) {
            FederalEntity::create([
                'name' => $fe,
            ]);
        }

        // creo municipalities
        $this->info('Creando municipality');
        foreach ($munis as $i => $muni) {
            $fe = FederalEntity::where('name', $muni['federal_entity_id'])->first();
            Municipality::create([
                'name' => $muni['name'],
                'federal_entity_id' => $fe['id']
            ]);
        }

        // creo settlement
        $this->info('Creando settements');
        foreach ($settements as $sett) {
            $zipCode = ZipCode::where('zip_code', $sett['zipcode'])->first();
            $muni = Municipality::where('name',$sett['municipality_id'])->first();
            $settType = SettlementType::where('name',$sett['settlement_type_id'])->first();
            Settlement::create([
                'key' => $sett['key'],
                'name' => $sett['name'],
                'zone_type' => $sett['zone_type'],
                'settlement_type_id' => $settType->id,
                'municipality_id' => $muni->id,
                'zip_code_id' => $zipCode->id
            ]);
        }

    }
}
