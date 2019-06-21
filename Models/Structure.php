<?php
namespace Models;

use \Core\Model;

class Structure extends Model {

	public function getItem($id_user, $id) {
		$array = array();

		$columns = $this->getStructure($id_user);
		$columns = $columns['columns'];

		$sql = "SELECT * FROM data_values WHERE id_user = :uid AND id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':uid', $id_user);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetch(\PDO::FETCH_ASSOC);

			$rows = json_decode($data['row_values']);

			foreach($rows as $col => $val) {
				if(in_array($col, $columns)) {
					$array[$col] = $val;
				}
			}

			$array['id'] = $data['id'];
		}

		return $array;
	}

	public function deleteItem($id_user, $id) {
		$sql = "DELETE FROM data_values WHERE id_user = :uid AND id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':uid', $id_user);
		$sql->bindValue(':id', $id);
		$sql->execute();
	}

	public function updateItem($id_user, $id, $data) {
		unset($data['jwt']);
		
		$item = $this->getItem($id_user, $id);
		unset($item['id']);

		foreach($item as $k => $v) {
			if(!empty($data[$k])) {
				$item[$k] = $data[$k];
				unset($data[$k]);
			}
		}

		if(count($data)) {
			$item = array_merge($item, $data);
		}

		$json = json_encode($item);

		$sql = "UPDATE data_values SET row_values = :json WHERE id_user = :uid AND id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->bindValue(':uid', $id_user);
		$sql->bindValue(':json', $json);
		$sql->execute();
	}

	public function getValues($id_user) {
		$array = array();

		$columns = $this->getStructure($id_user);
		$columns = $columns['columns'];

		$sql = "SELECT * FROM data_values WHERE id_user = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id_user);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll(\PDO::FETCH_ASSOC);
			foreach($data as $k => $item) {
				$rows = json_decode($item['row_values']);

				foreach($rows as $col => $val) {
					if(in_array($col, $columns)) {
						$array[$k][$col] = $val;
					}
				}

				$array[$k]['id'] = $item['id'];
			}
		}

		return $array;
	}

	public function addValue($id_user, $data) {
		$columns = $this->getStructure($id_user);
		$columns = $columns['columns'];

		$array = array();
		foreach($columns as $col) {
			if(!empty($data[$col])) {
				$array[$col] = $data[$col];
			}
		}

		if(count($array) > 0) {
			$json = json_encode($array);

			$sql = "INSERT INTO data_values (id_user, row_values) VALUES (:id, :json)";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id_user);
			$sql->bindValue(':json', $json);
			$sql->execute();

			return $this->db->lastInsertId();
		} else {
			return false;
		}
	}

	public function getStructure($id_user) {
		$array = array('columns' => array(), 'structure_name' => '');

		$sql = "SELECT structure_name FROM users WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id_user);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetch();
			$array['structure_name'] = $data['structure_name'];
		}

		$sql = "SELECT column_name FROM users_structure WHERE id_user = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id_user);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll(\PDO::FETCH_ASSOC);

			foreach($data as $item) {
				$array['columns'][] = $item['column_name'];
			}
		}

		return $array;
	}

	public function addColumn($id_user, $name) {
		$name = strtolower($name);

		if(preg_match('/^[a-z_-]{2,}$/s', $name)) {
			$sql = "INSERT INTO users_structure (id_user, column_name) VALUES (:id, :name)";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id_user);
			$sql->bindValue(':name', $name);
			$sql->execute();

			return $this->db->lastInsertId();
		} else {
			return false;
		}
	}

	public function deleteColumn($id_user, $name) {
		if($name != 'id') {
			$sql = "DELETE FROM users_structure WHERE id_user = :id AND column_name = :name";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id_user);
			$sql->bindValue(':name', $name);
			$sql->execute();
		}
	}

}