<?php
namespace Controllers;

use \Core\Controller;
use \Models\Usuarios;

class HomeController extends Controller {

	public function index() {
		header('Location: ./control');
		exit;
	}

}