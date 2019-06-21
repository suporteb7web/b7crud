<?php
namespace Core;

class Controller {

	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	public function getRequestData() {

		switch($this->getMethod()) {
			case 'GET':
				return $_GET;
				break;
			case 'PUT':
				$data = $this->parse_raw_http_request();
				return $data;
				break;
			case 'DELETE':
			case 'POST':
				$data = json_decode(file_get_contents('php://input'));

				if(is_null($data)) {
					$data = $_POST;
				}

				return (array) $data;
				break;
		}

	}

	public function parse_raw_http_request($a_data = [])
{
    // read incoming data
    $input = file_get_contents('php://input');
    // grab multipart boundary from content type header
    preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
    // content type is probably regular form-encoded
    if (!count($matches))
    {
        // we expect regular puts to containt a query string containing data
        parse_str(urldecode($input), $a_data);
        return $a_data;
    }
    $boundary = $matches[1];
    // split content by boundary and get rid of last -- element
    $a_blocks = preg_split("/-+$boundary/", $input);
    array_pop($a_blocks);
    $keyValueStr = '';
    // loop data blocks
    foreach ($a_blocks as $id => $block)
    {
        if (empty($block))
            continue;
        // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char
        // parse uploaded files
        if (strpos($block, 'application/octet-stream') !== FALSE)
        {
            // match "name", then everything after "stream" (optional) except for prepending newlines
            preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
            $a_data['files'][$matches[1]] = $matches[2];
        }
        // parse all other fields
        else
        {
            // match "name" and optional value in between newline sequences
            preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
            if(!empty($matches[2])) {
	            $keyValueStr .= $matches[1]."=".$matches[2]."&";
	        } else {
	        	$keyValueStr .= $matches[1]."=&";
	        }
        }
    }
    $keyValueArr = [];
    parse_str($keyValueStr, $keyValueArr);
    return array_merge($a_data, $keyValueArr);
}

	public function returnJson($array) {
		header("Content-Type: application/json");
		echo json_encode($array);
		exit;
	}

}










