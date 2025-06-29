<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\UsesIAModelsList;
use Illuminate\Support\Str;

class WelcomeController extends Controller
{
    use UsesIAModelsList;
    public function index(){
        $modelos = $this->modelosLocales();
        //return $modelos;
        return view('welcome',compact('modelos'));
    }

}
