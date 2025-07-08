<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class ClearImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fs = new Filesystem;

        echo "Eliminando imágenes del storage que no estén asociadas a productos...\n";

        // Imagen que siempre quieres conservar
        $exceptFileName = 'no-photo.png';



        // Obtiene todos los archivos en el directorio
        $filePaths = $fs->files(storage_path('app/public/images'));

        if ($this->command->confirm('¿Desea eliminar todas las imagenes del STORAGE?', true)) {
            foreach ($filePaths as $filePath) {
                $fileName = basename($filePath);
                // Verifica si el archivo es el que deseas conservar
                if ($fileName !== $exceptFileName) {
                    $fs->delete($filePath);
                }
            }
            echo "Archivos eliminados exitosamente!\n";
        }
        if ($this->command->confirm('¿Desea eliminar solo las imagenes no asociadas a productos?', true)) {
            // Lista de nombres de imágenes asociadas a productos
            $productImages = Producto::pluck('image')->toArray(); // Asume que la columna se llama 'image'
            $productImages[] = $exceptFileName; // También agregas la imagen por defecto
            foreach ($filePaths as $filePath) {
                $fileName = basename($filePath);
                // Solo elimina si el archivo NO está en la lista de imágenes asociadas a productos
                if (!in_array($fileName, $productImages)) {
                    $fs->delete($filePath);
                }
            }
            echo "Imagenes no asociadas eliminadas!\n";
        }

    }
}
