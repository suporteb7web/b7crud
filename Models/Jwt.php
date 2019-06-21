<?php
namespace Models;

use \Core\Model;

class Jwt extends Model {

	public function create($data) {
		global $config;

		$header = json_encode(array("typ"=>"JWT", "alg"=>"HS256"));

		$payload = json_encode($data);

		$hbase = $this->base64url_encode($header);
		$pbase = $this->base64url_encode($payload);

		$signature = hash_hmac("sha256", $hbase.".".$pbase, $config['jwt_secret_key'], true);
		$bsig = $this->base64url_encode($signature);

		$jwt = $hbase.".".$pbase.".".$bsig;

		return $jwt;
	}

	public function validate($jwt) {
		global $config;
		$array = array();

		$jwt_splits = explode('.', $jwt);

		if(count($jwt_splits) == 3) {
			$signature = hash_hmac("sha256", $jwt_splits[0].".".$jwt_splits[1], $config['jwt_secret_key'], true);
			$bsig = $this->base64url_encode($signature);

			if($bsig == $jwt_splits[2]) {
				$array = json_decode($this->base64url_decode($jwt_splits[1]));
			}
		}

		return $array;
	}

	private function base64url_encode( $data ){
	  return rtrim( strtr( base64_encode( $data ), '+/', '-_'), '=');
	}

	private function base64url_decode( $data ){
	  return base64_decode( strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
	}

}