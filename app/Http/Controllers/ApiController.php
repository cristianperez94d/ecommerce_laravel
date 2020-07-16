<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;

/* 
* Clase controlador para los controladores de la Api
*/

class ApiController extends Controller
{
    use ApiResponser;
    public function __construct(){
    }
}
