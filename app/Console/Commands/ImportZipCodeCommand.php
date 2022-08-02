<?php

namespace App\Console\Commands;

use App\Models\FederalEntity;
use App\Models\Municipality;
use App\Models\Settlement;
use App\Models\SettlementType;
use App\Models\ZipCode;
use Illuminate\Console\Command;
use PHPExcel_IOFactory;

class ImportZipCodeCommand extends Command
{
    protected $signature = 'import:zipcode';

    protected $description = 'Command description';

    private array $unwanted_array;

    public function __construct()
    {
        parent::__construct();
        $this->unwanted_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y');

    }

    public function handle(): void
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

                $zipcode = trim($lineaConFormato[0]);
                $d_tipo_asenta = strtr(trim($lineaConFormato[2]), $this->unwanted_array);
                $id_asenta_cpcons = (int)trim($lineaConFormato[12]);
                $d_asenta = strtr(trim($lineaConFormato[1]), $this->unwanted_array);
                $d_zona = strtr(trim($lineaConFormato[13]), $this->unwanted_array);
                $D_mnpio = strtr(trim($lineaConFormato[3]), $this->unwanted_array);
                $c_mnpio = (int)strtr(trim($lineaConFormato[10]), $this->unwanted_array);
                $d_estado = strtr(trim($lineaConFormato[4]), $this->unwanted_array);

                $zipCodes[$zipcode] = $zipcode; //d_codigo
                $settTypes[$d_tipo_asenta] = $d_tipo_asenta; // d_tipo_asenta
                $settements[] = [
                    'key' => $id_asenta_cpcons, // id_asenta_cpcons
                    'name' => $d_asenta, // d_asenta
                    'zone_type' => $d_zona, //d_zona
                    'settlement_type_id' => $d_tipo_asenta, // c_tipo_asenta
                    'zipcode' => $zipcode, //d_codigo
                    'municipality_id' => $D_mnpio // antes $c_mnpio
                ];

                $munis[$D_mnpio] = [ // c_mnpio
                    'name' => $D_mnpio, //D_mnpio
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
//                'id' => $muni['id'], //
                'name' => $muni['name'],
                'federal_entity_id' => $fe['id']
            ]);
        }
        try {
            // creo settlement
            $this->info('Creando settements');
            foreach ($settements as $sett) {
                $zipCode = ZipCode::where('zip_code', $sett['zipcode'])->first();
                $muni = Municipality::where('name', $sett['municipality_id'])->first();
                $settType = SettlementType::where('name', $sett['settlement_type_id'])->first();
                Settlement::create([
                    'key' => $sett['key'],
                    'name' => $sett['name'],
                    'zone_type' => $sett['zone_type'],
                    'settlement_type_id' => $settType->id,
                    'municipality_id' => $muni->id,
                    'zip_code_id' => $zipCode->id
                ]);
            }

            $this->info('finalizo.');
        } catch (\Exception $e) {
            logger($e->getMessage());
            logger('Settlement');
            logger($sett);
        }
    }
}
