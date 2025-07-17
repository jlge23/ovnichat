<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        Categoria::create(["categoria" => strtoupper('S/N'), "descripcion" => strtoupper('Sin categoria')]);
        Categoria::create(["categoria" => strtoupper('Lacteos'), "descripcion" => strtoupper('Derivados de la leche')]);
        Categoria::create(["categoria" => strtoupper('Pelagicos'), "descripcion" => strtoupper('Pescados en general')]);
        Categoria::create(["categoria" => strtoupper('Mariscos'), "descripcion" => strtoupper('Marisqueria en general')]);
        Categoria::create(["categoria" => strtoupper('Snacks'), "descripcion" => strtoupper('Chucherias en general')]);
        Categoria::create(["categoria" => strtoupper('Frutas envasadas'), "descripcion" => strtoupper('Frutas enlatadas en general')]);
        Categoria::create(["categoria" => strtoupper('Cereales'), "descripcion" => strtoupper('Derivados del cereal en general')]);
        Categoria::create(["categoria" => strtoupper('Fungi y legumbres'), "descripcion" => strtoupper('Derivados de setas y hortalizas')]);
        Categoria::create(["categoria" => strtoupper('Oleaginosas'), "descripcion" => strtoupper('Derivados de granos de cafe')]);
        Categoria::create(["categoria" => strtoupper('Frijoles'), "descripcion" => strtoupper('Proteinas vegetales en general')]);
        Categoria::create(["categoria" => strtoupper('Bebidas'), "descripcion" => strtoupper('Bebidas frias y calientes')]);
        Categoria::create(["categoria" => strtoupper('Carnes'), "descripcion" => strtoupper('Cortes frescos y embutidos')]);
        Categoria::create(["categoria" => strtoupper('Aves'), "descripcion" => strtoupper('Pollo, pavo y similares')]);
        Categoria::create(["categoria" => strtoupper('Panaderia'), "descripcion" => strtoupper('Pan, bolleria y reposteria')]);
        Categoria::create(["categoria" => strtoupper('Pastas'), "descripcion" => strtoupper('Fideos, lasañas y derivados')]);
        Categoria::create(["categoria" => strtoupper('Conservas'), "descripcion" => strtoupper('Alimentos enlatados variados')]);
        Categoria::create(["categoria" => strtoupper('Dulces'), "descripcion" => strtoupper('Dulces tradicionales y caramelos')]);
        Categoria::create(["categoria" => strtoupper('Golosinas'), "descripcion" => strtoupper('Chucherias y snacks infantiles')]);
        Categoria::create(["categoria" => strtoupper('Huevos'), "descripcion" => strtoupper('Huevos frescos de gallina y codorniz')]);
        Categoria::create(["categoria" => strtoupper('Aceites'), "descripcion" => strtoupper('Vegetales, oliva y otros aceites')]);
        Categoria::create(["categoria" => strtoupper('Salsas'), "descripcion" => strtoupper('Salsas para cocina y aderezo')]);
        Categoria::create(["categoria" => strtoupper('Limpieza'), "descripcion" => strtoupper('Productos de limpieza general')]);
        Categoria::create(["categoria" => strtoupper('Higiene personal'), "descripcion" => strtoupper('Cuidado corporal y cosmetica')]);
        Categoria::create(["categoria" => strtoupper('Papeleria'), "descripcion" => strtoupper('utiles escolares y de oficina')]);
        Categoria::create(["categoria" => strtoupper('Mascotas'), "descripcion" => strtoupper('Comida y accesorios para mascotas')]);
        Categoria::create(["categoria" => strtoupper('Electronica'), "descripcion" => strtoupper('Pequeños electrodomesticos y gadgets')]);
        Categoria::create(["categoria" => strtoupper('Hogar'), "descripcion" => strtoupper('Productos para uso domestico')]);
        Categoria::create(["categoria" => strtoupper('Ropa'), "descripcion" => strtoupper('Vestimenta casual y formal')]);
        Categoria::create(["categoria" => strtoupper('Calzado'), "descripcion" => strtoupper('Zapatos, sandalias y deportivos')]);
        Categoria::create(["categoria" => strtoupper('Juguetes'), "descripcion" => strtoupper('Juguetes educativos y recreativos')]);
        Categoria::create(["categoria" => strtoupper('Ferreteria'), "descripcion" => strtoupper('Herramientas y suministros')]);
        Categoria::create(["categoria" => strtoupper('Verduras'), "descripcion" => strtoupper('Vegetales frescos variados')]);
        Categoria::create(["categoria" => strtoupper('Frutas frescas'), "descripcion" => strtoupper('Frutas de estacion')]);
        Categoria::create(["categoria" => strtoupper('Congelados'), "descripcion" => strtoupper('Alimentos listos para congelar')]);
        Categoria::create(["categoria" => strtoupper('Productos gourmet'), "descripcion" => strtoupper('Alimentos especiales y delicatessen')]);
        Categoria::create(["categoria" => strtoupper('Productos organicos'), "descripcion" => strtoupper('Cultivo limpio y natural')]);
        Categoria::create(["categoria" => strtoupper('Productos importados'), "descripcion" => strtoupper('Articulos de origen internacional')]);
        Categoria::create(["categoria" => strtoupper('Productos artesanales'), "descripcion" => strtoupper('Hechos a mano y tradicionales')]);
    }
}
