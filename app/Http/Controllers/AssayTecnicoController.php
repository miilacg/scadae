<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LoginService;
use App\Entities\Login;


class AssayTecnicoController extends Controller {
  private $loginService;

  public function __construct(LoginService $loginService) {
    $this->loginService = $loginService;
  }

  public function company(){
    return view('informatica.company');
  }

  public function knowledge(){
    return view('informatica.knowledge');
  }

  public function personalData(){
    return view('informatica.PersonalData');
  }

  public function professionalData(){
    return view('informatica.professionalData');
  }
}