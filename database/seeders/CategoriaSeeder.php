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
    public function run(): void{
        Categoria::create(["nombre" => Str::upper('S/N'), "descripcion" => Str::ucfirst('Sin nombre')]);
        Categoria::create(["nombre" => Str::ucfirst('Lacteos'), "descripcion" => Str::ucfirst('Derivados de la leche')]);
        Categoria::create(["nombre" => Str::ucfirst('Pelagicos'), "descripcion" => Str::ucfirst('Pescados en general')]);
        Categoria::create(["nombre" => Str::ucfirst('Mariscos'), "descripcion" => Str::ucfirst('Marisqueria en general')]);
        Categoria::create(["nombre" => Str::ucfirst('Snacks'), "descripcion" => Str::ucfirst('Chucherias en general')]);
        Categoria::create(["nombre" => Str::ucfirst('Frutas envasadas'), "descripcion" => Str::ucfirst('Frutas enlatadas en general')]);
        Categoria::create(["nombre" => Str::ucfirst('Cereales'), "descripcion" => Str::ucfirst('Derivados del cereal en general')]);
        Categoria::create(["nombre" => Str::ucfirst('Fungi y legumbres'), "descripcion" => Str::ucfirst('Derivados de setas y hortalizas')]);
        Categoria::create(["nombre" => Str::ucfirst('Oleaginosas'), "descripcion" => Str::ucfirst('Derivados de granos de cafe')]);
        Categoria::create(["nombre" => Str::ucfirst('Frijoles'), "descripcion" => Str::ucfirst('Proteinas vegetales en general')]);
        Categoria::create(["nombre" => Str::ucfirst('Bebidas'), "descripcion" => Str::ucfirst('Bebidas frias y calientes')]);
        Categoria::create(["nombre" => Str::ucfirst('Carnes'), "descripcion" => Str::ucfirst('Cortes frescos y embutidos')]);
        Categoria::create(["nombre" => Str::ucfirst('Aves'), "descripcion" => Str::ucfirst('Pollo, pavo y similares')]);
        Categoria::create(["nombre" => Str::ucfirst('Panaderia'), "descripcion" => Str::ucfirst('Pan, bolleria y reposteria')]);
        Categoria::create(["nombre" => Str::ucfirst('Pastas'), "descripcion" => Str::ucfirst('Fideos, lasañas y derivados')]);
        Categoria::create(["nombre" => Str::ucfirst('Conservas'), "descripcion" => Str::ucfirst('Alimentos enlatados variados')]);
        Categoria::create(["nombre" => Str::ucfirst('Dulces'), "descripcion" => Str::ucfirst('Dulces tradicionales y caramelos')]);
        Categoria::create(["nombre" => Str::ucfirst('Golosinas'), "descripcion" => Str::ucfirst('Chucherias y snacks infantiles')]);
        Categoria::create(["nombre" => Str::ucfirst('Huevos'), "descripcion" => Str::ucfirst('Huevos frescos de gallina y codorniz')]);
        Categoria::create(["nombre" => Str::ucfirst('Aceites'), "descripcion" => Str::ucfirst('Vegetales, oliva y otros aceites')]);
        Categoria::create(["nombre" => Str::ucfirst('Salsas'), "descripcion" => Str::ucfirst('Salsas para cocina y aderezo')]);
        Categoria::create(["nombre" => Str::ucfirst('Limpieza'), "descripcion" => Str::ucfirst('Productos de limpieza general')]);
        Categoria::create(["nombre" => Str::ucfirst('Higiene personal'), "descripcion" => Str::ucfirst('Cuidado corporal y cosmetica')]);
        Categoria::create(["nombre" => Str::ucfirst('Papeleria'), "descripcion" => Str::ucfirst('utiles escolares y de oficina')]);
        Categoria::create(["nombre" => Str::ucfirst('Mascotas'), "descripcion" => Str::ucfirst('Comida y accesorios para mascotas')]);
        Categoria::create(["nombre" => Str::ucfirst('Electronica'), "descripcion" => Str::ucfirst('Pequeños electrodomesticos y gadgets')]);
        Categoria::create(["nombre" => Str::ucfirst('Hogar'), "descripcion" => Str::ucfirst('Productos para uso domestico')]);
        Categoria::create(["nombre" => Str::ucfirst('Ropa'), "descripcion" => Str::ucfirst('Vestimenta casual y formal')]);
        Categoria::create(["nombre" => Str::ucfirst('Calzado'), "descripcion" => Str::ucfirst('Zapatos, sandalias y deportivos')]);
        Categoria::create(["nombre" => Str::ucfirst('Juguetes'), "descripcion" => Str::ucfirst('Juguetes educativos y recreativos')]);
        Categoria::create(["nombre" => Str::ucfirst('Ferreteria'), "descripcion" => Str::ucfirst('Herramientas y suministros')]);
        Categoria::create(["nombre" => Str::ucfirst('Verduras'), "descripcion" => Str::ucfirst('Vegetales frescos variados')]);
        Categoria::create(["nombre" => Str::ucfirst('Frutas frescas'), "descripcion" => Str::ucfirst('Frutas de estacion')]);
        Categoria::create(["nombre" => Str::ucfirst('Congelados'), "descripcion" => Str::ucfirst('Alimentos listos para congelar')]);
        Categoria::create(["nombre" => Str::ucfirst('Productos gourmet'), "descripcion" => Str::ucfirst('Alimentos especiales y delicatessen')]);
        Categoria::create(["nombre" => Str::ucfirst('Productos organicos'), "descripcion" => Str::ucfirst('Cultivo limpio y natural')]);
        Categoria::create(["nombre" => Str::ucfirst('Productos importados'), "descripcion" => Str::ucfirst('Articulos de origen internacional')]);
        Categoria::create(["nombre" => Str::ucfirst('Productos artesanales'), "descripcion" => Str::ucfirst('Hechos a mano y tradicionales')]);
    }
}
