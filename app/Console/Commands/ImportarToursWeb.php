<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Tour;

class ImportarToursWeb extends Command
{
    protected $signature   = 'tours:importar';
    protected $description = 'Importa todos los tours desde adventur.pe';

    public function handle(): void
    {
        // 1. Obtener todas las URLs del sitemap
        $sitemap = Http::timeout(15)->get('https://adventur.pe/product-sitemap.xml')->body();
        preg_match_all('/<loc>([^<]+)<\/loc>/i', $sitemap, $all);
        $urls = array_filter($all[1], fn($u) => str_contains($u, '/tours/'));
        $urls = array_values($urls);

        $this->info("URLs encontradas: " . count($urls));
        $bar = $this->output->createProgressBar(count($urls));
        $bar->start();

        $importados = 0;
        $errores    = 0;

        foreach ($urls as $url) {
            try {
                $html = Http::timeout(10)->get($url)->body();

                // Título desde h1
                preg_match('/<h1[^>]*>([^<]+)<\/h1>/i', $html, $h1);
                $nombre = isset($h1[1]) ? trim(html_entity_decode($h1[1])) : null;

                if (!$nombre || strlen($nombre) < 3) {
                    $errores++;
                    $bar->advance();
                    sleep(0);
                    continue;
                }

                // Detectar categoría
                $categoria = 'nacional';
                $nombreLower = strtolower($nombre);
                $urlLower    = strtolower($url);
                if (str_contains($nombreLower, 'full day') || str_contains($urlLower, 'full-day'))
                    $categoria = 'full_day';
                elseif (str_contains($nombreLower, 'half day') || str_contains($urlLower, 'half-day'))
                    $categoria = 'half_day';
                elseif (str_contains($nombreLower, 'crucero') || str_contains($urlLower, 'crucero'))
                    $categoria = 'crucero';
                elseif (str_contains($nombreLower, 'escolar') || str_contains($urlLower, 'escolar'))
                    $categoria = 'escolar';
                elseif (str_contains($nombreLower, 'carnaval') || str_contains($nombreLower, 'fiestas patrias'))
                    $categoria = 'fecha_festiva';
                elseif (preg_match('/(europa|cartagena|caribe|mexico|brasil|argentina|tailandia|egipto|orlando|panama|aruba|curacao|bahamas|disney)/i', $nombre))
                    $categoria = 'internacional';

                Tour::updateOrCreate(
                    ['nombre' => $nombre],
                    ['categoria' => $categoria, 'activo' => true]
                );

                $importados++;

            } catch (\Exception $e) {
                $errores++;
            }

            $bar->advance();
            usleep(300000); // 0.3s entre requests
        }

        $bar->finish();
        $this->newLine();
        $this->info("Importados: {$importados} | Errores: {$errores}");
    }
}