<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbigeoPeruSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ubigeo_peru')->truncate();

        $deptos    = json_decode(file_get_contents(database_path('seeders/departamentos.json')), true);
        $provs     = json_decode(file_get_contents(database_path('seeders/provincias.json')), true);
        $distritos = json_decode(file_get_contents(database_path('seeders/distritos.json')), true);

        // Mapa id_ubigeo → nombre departamento
        $mapaDeptos = [];
        foreach ($deptos as $d) {
            $mapaDeptos[$d['id_ubigeo']] = $d['nombre_ubigeo'];
        }

        // Mapa id_ubigeo provincia → nombre departamento
        $mapaProv = [];
        foreach ($provs as $depId => $provList) {
            $depto = $mapaDeptos[$depId] ?? null;
            if (!$depto) continue;
            foreach ($provList as $p) {
                $mapaProv[$p['id_ubigeo']] = [
                    'departamento' => $depto,
                    'provincia'    => $p['nombre_ubigeo'],
                ];
            }
        }

        // Insertar distritos con departamento y provincia
        $batch = [];
        foreach ($distritos as $provId => $distList) {
            $info = $mapaProv[$provId] ?? null;
            if (!$info) continue;
            foreach ($distList as $dist) {
                $batch[] = [
                    'departamento'   => $info['departamento'],
                    'provincia'      => $info['provincia'],
                    'distrito'       => $dist['nombre_ubigeo'],
                    'codigo_ubigeo'  => $dist['codigo_ubigeo'],
                ];
                if (count($batch) >= 500) {
                    DB::table('ubigeo_peru')->insert($batch);
                    $batch = [];
                }
            }
        }
        if ($batch) DB::table('ubigeo_peru')->insert($batch);

        $this->command->info('Ubigeo importado: ' . DB::table('ubigeo_peru')->count() . ' distritos.');
    }
}