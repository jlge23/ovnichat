<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index(){
        $productos = DB::table('productos')->limit(3)->get(); // Asegurar que hay productos
        if ($productos->isEmpty()) {
            $productos = "No hay productos segun este query";
            return view("welcome",compact('productos'));
        }else{
            return view("welcome",compact('productos'));
        }
    }
}
