<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tour;

class ExportarToursSeeder extends Command
{
    protected $signature   = 'tours:exportar-seeder';
    protected $description = 'Exporta todos los tours de la BD al ToursDesdeWebSeeder.php';

    public function handle(): void
    {
        $tours = Tour::orderBy('nombre')->get(['nombre', 'categoria']);

        $lines = $tours->map(fn($t) =>
            "            ['nombre' => '" . addslashes($t->nombre) . "', 'categoria' => '{$t->categoria}'],"
        )->implode("\n");

        $content = <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;

class ToursDesdeWebSeeder extends Seeder
{
    public function run(): void
    {
        \$tours = [
{$lines}
        ];

        \$this->command->info('Importando ' . count(\$tours) . ' tours...');

        foreach (\$tours as \$tour) {
            Tour::updateOrCreate(
                ['nombre' => \$tour['nombre']],
                ['categoria' => \$tour['categoria'], 'veces_usado' => 0, 'activo' => true]
            );
        }

        \$this->command->info('Listo! ' . count(\$tours) . ' tours.');
    }
}
PHP;

        file_put_contents(database_path('seeders/ToursDesdeWebSeeder.php'), $content);
        $this->info("Exportados {$tours->count()} tours al seeder.");
    }
}