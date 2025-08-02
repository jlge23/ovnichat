<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [

            ["nombre" => Str::upper('S/N'), "descripcion" => Str::ucfirst('Sin nombre')],
            ["nombre" => Str::ucfirst('Lacteos'), "descripcion" => Str::ucfirst('Derivados de la leche')],
            ["nombre" => Str::ucfirst('Pelagicos'), "descripcion" => Str::ucfirst('Pescados en general')],
            ["nombre" => Str::ucfirst('Mariscos'), "descripcion" => Str::ucfirst('Marisqueria en general')],
            ["nombre" => Str::ucfirst('Snacks'), "descripcion" => Str::ucfirst('Chucherias en general')],
            ["nombre" => Str::ucfirst('Frutas envasadas'), "descripcion" => Str::ucfirst('Frutas enlatadas en general')],
            ["nombre" => Str::ucfirst('Cereales'), "descripcion" => Str::ucfirst('Derivados del cereal en general')],
            ["nombre" => Str::ucfirst('Fungi y legumbres'), "descripcion" => Str::ucfirst('Derivados de setas y hortalizas')],
            ["nombre" => Str::ucfirst('Oleaginosas'), "descripcion" => Str::ucfirst('Derivados de granos de cafe')],
            ["nombre" => Str::ucfirst('Frijoles'), "descripcion" => Str::ucfirst('Proteinas vegetales en general')],
            ["nombre" => Str::ucfirst('Bebidas'), "descripcion" => Str::ucfirst('Bebidas frias y calientes')],
            ["nombre" => Str::ucfirst('Carnes'), "descripcion" => Str::ucfirst('Cortes frescos y embutidos')],
            ["nombre" => Str::ucfirst('Aves'), "descripcion" => Str::ucfirst('Pollo, pavo y similares')],
            ["nombre" => Str::ucfirst('Panaderia'), "descripcion" => Str::ucfirst('Pan, bolleria y reposteria')],
            ["nombre" => Str::ucfirst('Pastas'), "descripcion" => Str::ucfirst('Fideos, lasañas y derivados')],
            ["nombre" => Str::ucfirst('Conservas'), "descripcion" => Str::ucfirst('Alimentos enlatados variados')],
            ["nombre" => Str::ucfirst('Dulces'), "descripcion" => Str::ucfirst('Dulces tradicionales y caramelos')],
            ["nombre" => Str::ucfirst('Golosinas'), "descripcion" => Str::ucfirst('Chucherias y snacks infantiles')],
            ["nombre" => Str::ucfirst('Huevos'), "descripcion" => Str::ucfirst('Huevos frescos de gallina y codorniz')],
            ["nombre" => Str::ucfirst('Aceites'), "descripcion" => Str::ucfirst('Vegetales, oliva y otros aceites')],
            ["nombre" => Str::ucfirst('Salsas'), "descripcion" => Str::ucfirst('Salsas para cocina y aderezo')],
            ["nombre" => Str::ucfirst('Limpieza'), "descripcion" => Str::ucfirst('Productos de limpieza general')],
            ["nombre" => Str::ucfirst('Higiene personal'), "descripcion" => Str::ucfirst('Cuidado corporal y cosmetica')],
            ["nombre" => Str::ucfirst('Papeleria'), "descripcion" => Str::ucfirst('utiles escolares y de oficina')],
            ["nombre" => Str::ucfirst('Mascotas'), "descripcion" => Str::ucfirst('Comida y accesorios para mascotas')],
            ["nombre" => Str::ucfirst('Electronica'), "descripcion" => Str::ucfirst('Pequeños electrodomesticos y gadgets')],
            ["nombre" => Str::ucfirst('Hogar'), "descripcion" => Str::ucfirst('Productos para uso domestico')],
            ["nombre" => Str::ucfirst('Ropa'), "descripcion" => Str::ucfirst('Vestimenta casual y formal')],
            ["nombre" => Str::ucfirst('Calzado'), "descripcion" => Str::ucfirst('Zapatos, sandalias y deportivos')],
            ["nombre" => Str::ucfirst('Juguetes'), "descripcion" => Str::ucfirst('Juguetes educativos y recreativos')],
            ["nombre" => Str::ucfirst('Ferreteria'), "descripcion" => Str::ucfirst('Herramientas y suministros')],
            ["nombre" => Str::ucfirst('Verduras'), "descripcion" => Str::ucfirst('Vegetales frescos variados')],
            ["nombre" => Str::ucfirst('Frutas frescas'), "descripcion" => Str::ucfirst('Frutas de estacion')],
            ["nombre" => Str::ucfirst('Congelados'), "descripcion" => Str::ucfirst('Alimentos listos para congelar')],
            ["nombre" => Str::ucfirst('Productos gourmet'), "descripcion" => Str::ucfirst('Alimentos especiales y delicatessen')],
            ["nombre" => Str::ucfirst('Productos organicos'), "descripcion" => Str::ucfirst('Cultivo limpio y natural')],
            ["nombre" => Str::ucfirst('Productos importados'), "descripcion" => Str::ucfirst('Articulos de origen internacional')],
            ["nombre" => Str::ucfirst('Productos artesanales'), "descripcion" => Str::ucfirst('Hechos a mano y tradicionales')],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create(["nombre" => $categoria['nombre'], "descripcion" => $categoria['descripcion']]);
        }
    }
}
