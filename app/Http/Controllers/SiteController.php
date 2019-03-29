<?php

namespace App\Http\Controllers;  /*caminho da pasta*/

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Resources\Views;

class SiteController extends Controller{
    
    public function __construct() {
        //$this->meddleware('auth');  /*meddleawere aula 07*/
    }                                
    
    public function index() {
        
        return view('site.home.index');
        
    }

}
