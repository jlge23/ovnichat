<?php

namespace Database\Seeders;

use App\Models\BusinessModel;
use App\Models\Categoria;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategoriaSeeder::class);
        $this->call(CanaleSeeder::class);
        $this->call(EstadoLeadSeeder::class);
        $this->call(ProveedorSeeder::class);
        $this->call(EmbalajeSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(BusinessModelSeeder::class);
        $this->call(IntentSeeder::class);
        $this->call(EntitieSeeder::class);
        $this->call(BusinessModelIntentSeeder::class);
        $this->call(EntitieIntentSeeder::class);
    }
}
