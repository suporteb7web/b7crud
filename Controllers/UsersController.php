<?php
namespace Controllers;

use \Core\Controller;
use \Models\Users;
use \Models\Structure;

class UsersController extends Controller {

	public function index() {
		$this->view();
	}

	public function login() {
		$array = array('error'=>'');

		$method = $this->getMethod();
		$data = $this->getRequestData();

		if($method == 'POST') {
			if(!empty($data['email']) && !empty($data['pass'])) {
				$users = new Users();

				if($users->checkCredentials($data['email'], $data['pass'])) {
					$array['jwt'] = $users->createJwt();
				} else {
					$array['error'] = 'Acesso negado';
				}
			} else {
				$array['error'] = 'E-mail e/ou senha não preenchido.';
			}
		} else {
			$array['error'] = 'Método de requisição incompatível';
		}

		$this->returnJson($array);
	}

	public function new_record() {
		$array = array('error' => '');

		$method = $this->getMethod();
		$data = $this->getRequestData();

		if($method == 'POST') {

			if(!empty($data['structure_name']) && !empty($data['email']) && !empty($data['pass'])) {

				if(preg_match('/^[a-z]{2,}$/s', strtolower($data['structure_name']))) {

					if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {

						$users = new Users();

						if($users->create($data['structure_name'], $data['email'], $data['pass'])) {

							$array['jwt'] = $users->createJwt();

						} else {
							$array['error'] = 'E-mail já existente';
						}

					} else {
						$array['error'] = 'E-mail inválido';
					}

				} else {
					$array['error'] = 'Nome de estrutura inválido';
				}

			} else {
				$array['error'] = 'Dados não preenchidos';
			}

		} else {
			$array['error'] = 'Método de requisição incompatível';
		}

		$this->returnJson($array);
	}

	public function view() {
		$array = array('error'=>'', 'logged'=>false);

		$method = $this->getMethod();
		$data = $this->getRequestData();

		$users = new Users();
		$s = new Structure();

		if(!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
			$array['logged'] = true;

			switch($method) {
				case 'GET':
					$array['data'] = $s->getStructure($users->getId());
					break;
				case 'POST':
					if(!empty($data['column_name'])) {
						if(!$s->addColumn($users->getId(), $data['column_name'])) {
							$array['error'] = 'Coluna não válida';
						}
					} else {
						$array['error'] = 'Digite o nome da coluna';
					}
					break;
				case 'DELETE':
					if(!empty($data['column_name'])) {
						$s->deleteColumn($users->getId(), $data['column_name']);
					} else {
						$array['error'] = 'Digite o nome da coluna';
					}
					break;
				default:
					$array['error'] = 'Método '.$method.' não disponível';
					break;
			}


		} else {
			$array['error'] = 'Acesso negado';
		}

		$this->returnJson($array);
	}


}



















