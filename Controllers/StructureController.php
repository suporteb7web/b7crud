<?php
namespace Controllers;

use \Core\Controller;
use \Models\Users;
use \Models\Structure;

class StructureController extends Controller {

	public function index() {}

	public function list($structure) {
		$array = array('error'=>'', 'logged'=>false);

		$method = $this->getMethod();
		$data = $this->getRequestData();

		$users = new Users();
		$s = new Structure();

		if(!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
			$array['logged'] = true;

			switch($method) {
				case 'GET':
					$array['data'] = $s->getValues($users->getId());
					break;
				case 'POST':
					$data = $s->addValue($users->getId(), $data);

					if(!$data) {
						$array['error'] = 'Erro ao adicionar';
					} else {
						$array['data'] = array('id_item' => $data);
					}
					break;
				default:
					$array['error'] = 'Método '.$method.' não disponível';
					break;
			}

		}

		$this->returnJson($array);
	}

	public function item($structure, $id) {
		$array = array('error'=>'', 'logged'=>false);

		$method = $this->getMethod();
		$data = $this->getRequestData();

		$users = new Users();
		$s = new Structure();

		if(!empty($data['jwt']) && $users->validateJwt($data['jwt'])) {
			$array['logged'] = true;

			switch($method) {
				case 'GET':
					$array['data'] = $s->getItem($users->getId(), $id);
					break;
				case 'PUT':
					$s->updateItem($users->getId(), $id, $data);
					break;
				case 'DELETE':
					$s->deleteItem($users->getId(), $id);
					break;
				default:
					$array['error'] = 'Método '.$method.' não disponível';
					break;
			}

		}

		$this->returnJson($array);
	}

}