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
        Categoria::create(["nombre" => '📁 S/N', "descripcion" => 'Sin categoría']);
        Categoria::create(["nombre" => '🥛 Lácteos', "descripcion" => 'Derivados de la leche']);
        Categoria::create(["nombre" => '🐟 Pelágicos', "descripcion" => 'Pescados en general']);
        Categoria::create(["nombre" => '🦐 Mariscos', "descripcion" => 'Marisquería en general']);
        Categoria::create(["nombre" => '🍿 Snacks', "descripcion" => 'Chucherías en general']);
        Categoria::create(["nombre" => '🍑 Frutas envasadas', "descripcion" => 'Frutas enlatadas en general']);
        Categoria::create(["nombre" => '🌾 Cereales', "descripcion" => 'Derivados del cereal en general']);
        Categoria::create(["nombre" => '🍄 Fungi y legumbres', "descripcion" => 'Derivados de setas y hortalizas']);
        Categoria::create(["nombre" => '☕ Oleaginosas', "descripcion" => 'Derivados de granos de café']);
        Categoria::create(["nombre" => '🫘 Frijoles', "descripcion" => 'Proteínas vegetales en general']);
        Categoria::create(["nombre" => '🥤 Bebidas', "descripcion" => 'Bebidas frías y calientes']);
        Categoria::create(["nombre" => '🥩 Carnes', "descripcion" => 'Cortes frescos y embutidos']);
        Categoria::create(["nombre" => '🍗 Aves', "descripcion" => 'Pollo, pavo y similares']);
        Categoria::create(["nombre" => '🥖 Panadería', "descripcion" => 'Pan, bollería y repostería']);
        Categoria::create(["nombre" => '🍝 Pastas', "descripcion" => 'Fideos, lasañas y derivados']);
        Categoria::create(["nombre" => '🥫 Conservas', "descripcion" => 'Alimentos enlatados variados']);
        Categoria::create(["nombre" => '🍬 Dulces', "descripcion" => 'Dulces tradicionales y caramelos']);
        Categoria::create(["nombre" => '🍭 Golosinas', "descripcion" => 'Chucherías y snacks infantiles']);
        Categoria::create(["nombre" => '🥚 Huevos', "descripcion" => 'Huevos frescos de gallina y codorniz']);
        Categoria::create(["nombre" => '🛢️ Aceites', "descripcion" => 'Vegetales, oliva y otros aceites']);
        Categoria::create(["nombre" => '🧂 Salsas', "descripcion" => 'Salsas para cocina y aderezo']);
        Categoria::create(["nombre" => '🧼 Limpieza', "descripcion" => 'Productos de limpieza general']);
        Categoria::create(["nombre" => '🧴 Higiene personal', "descripcion" => 'Cuidado corporal y cosmética']);
        Categoria::create(["nombre" => '📄 Papelería', "descripcion" => 'Útiles escolares y de oficina']);
        Categoria::create(["nombre" => '🐾 Mascotas', "descripcion" => 'Comida y accesorios para mascotas']);
        Categoria::create(["nombre" => '🔌 Electrónica', "descripcion" => 'Pequeños electrodomésticos y gadgets']);
        Categoria::create(["nombre" => '🏠 Hogar', "descripcion" => 'Productos para uso doméstico']);
        Categoria::create(["nombre" => '👕 Ropa', "descripcion" => 'Vestimenta casual y formal']);
        Categoria::create(["nombre" => '👟 Calzado', "descripcion" => 'Zapatos, sandalias y deportivos']);
        Categoria::create(["nombre" => '🧸 Juguetes', "descripcion" => 'Juguetes educativos y recreativos']);
        Categoria::create(["nombre" => '🔧 Ferretería', "descripcion" => 'Herramientas y suministros']);
        Categoria::create(["nombre" => '🥦 Verduras', "descripcion" => 'Vegetales frescos variados']);
        Categoria::create(["nombre" => '🍎 Frutas frescas', "descripcion" => 'Frutas de estación']);
        Categoria::create(["nombre" => '❄️ Congelados', "descripcion" => 'Alimentos listos para congelar']);
        Categoria::create(["nombre" => '🍽️ Productos gourmet', "descripcion" => 'Alimentos especiales y delicatessen']);
        Categoria::create(["nombre" => '🌱 Productos orgánicos', "descripcion" => 'Cultivo limpio y natural']);
        Categoria::create(["nombre" => '🌍 Productos importados', "descripcion" => 'Artículos de origen internacional']);
        Categoria::create(["nombre" => '🧵 Productos artesanales', "descripcion" => 'Hechos a mano y tradicionales']);
    }
}
