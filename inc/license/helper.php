<?php if(count(get_included_files()) == 1) exit("No direct script access allowed");

define("LB_API_DEBUG", false);

define("LB_TEXT_CONNECTION_FAILED", 'Server is unavailable at the moment, please try again.');
define("LB_TEXT_INVALID_RESPONSE", 'Server returned an invalid response, please contact support.');

if(!LB_API_DEBUG){
	@ini_set('display_errors', 0);
}

if((@ini_get('max_execution_time')!=='0')&&(@ini_get('max_execution_time'))<600){
	@ini_set('max_execution_time', 600);
}
@ini_set('memory_limit', '256M');


class LicenseBoxInternalAPI{

	private $api_url;
	private $api_key;
	private $api_language;

	public function __construct(){
		$this->api_url = 'https://license.novaworks.net/';
		$this->api_key = '2CCB0C1A6807217A6C97';
		$this->api_language = 'english';
	}

	private function call_api($method, $url, $data = null){
		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			default:
				if($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}
		$this_server_name = getenv('SERVER_NAME')?:
			$_SERVER['SERVER_NAME']?:
			getenv('HTTP_HOST')?:
			$_SERVER['HTTP_HOST'];
		$this_http_or_https = ((
			(isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on"))or
			(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])and
				$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		)?'https://':'http://');
		$this_url = $this_http_or_https.$this_server_name.$_SERVER['REQUEST_URI'];
		$this_ip = getenv('SERVER_ADDR')?:
			$_SERVER['SERVER_ADDR']?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'LB-API-KEY: '.$this->api_key,
			'LB-URL: '.$this_url,
			'LB-IP: '.$this_ip,
			'LB-LANG: '.$this->api_language)
		);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		$result = curl_exec($curl);
		if(!$result&&!LB_API_DEBUG){
			$rs = array(
				'status' => FALSE,
				'message' => LB_TEXT_CONNECTION_FAILED
			);
			return json_encode($rs);
		}
		$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($http_status != 200){
			if(LB_API_DEBUG){
				$temp_decode = json_decode($result, true);
				$rs = array(
					'status' => FALSE,
					'message' => ((!empty($temp_decode['error']))?
						$temp_decode['error']:
						$temp_decode['message'])
				);
				return json_encode($rs);
			}else{
				$rs = array(
					'status' => FALSE,
					'message' => LB_TEXT_INVALID_RESPONSE
				);
				return json_encode($rs);
			}
		}
		curl_close($curl);
		return $result;
	}

	public function check_connection(){
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/check_connection_int'
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function add_product($product_name, $product_id = null){
		$data_array =  array(
			"product_id"  => $product_id,
			"product_name"  => $product_name
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/add_product',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function get_product($product_id){
		$data_array =  array(
			"product_id"  => $product_id
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/get_product',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function get_products(){
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/get_products'
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function create_license($product_id, $data = null, $license_code = null){
		$data_array =  array(
			"product_id"  => $product_id,
			"license_code"  => $license_code,
			"license_type" => (!empty($data['license_type']))?$data['license_type']:null,
			"invoice_number" => (!empty($data['invoice_number']))?$data['invoice_number']:null,
			"client_name" => (!empty($data['client_name']))?$data['client_name']:null,
			"client_email" => (!empty($data['client_email']))?$data['client_email']:null,
			"comments" => (!empty($data['comments']))?$data['comments']:null,
			"licensed_ips" => (!empty($data['licensed_ips']))?$data['licensed_ips']:null,
			"licensed_domains" => (!empty($data['licensed_domains']))?$data['licensed_domains']:null,
			"support_end_date" => (!empty($data['support_end_date']))?$data['support_end_date']:null,
			"updates_end_date" => (!empty($data['updates_end_date']))?$data['updates_end_date']:null,
			"expiry_date" => (!empty($data['expiry_date']))?$data['expiry_date']:null,
			"expiry_days" => (!empty($data['expiry_days']))?$data['expiry_days']:null,
			"license_uses" => (!empty($data['license_uses']))?$data['license_uses']:null,
			"license_parallel_uses" => (!empty($data['license_parallel_uses']))?$data['license_parallel_uses']:null
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/create_license',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function edit_license($license_code, $data){
		$data_array =  array(
			"product_id"  => (isset($data['product_id']))?$data['product_id']:null,
			"license_code"  => $license_code,
			"license_type" => (isset($data['license_type']))?$data['license_type']:null,
			"invoice_number" => (isset($data['invoice_number']))?$data['invoice_number']:null,
			"client_name" => (isset($data['client_name']))?$data['client_name']:null,
			"client_email" => (isset($data['client_email']))?$data['client_email']:null,
			"comments" => (isset($data['comments']))?$data['comments']:null,
			"licensed_ips" => (isset($data['licensed_ips']))?$data['licensed_ips']:null,
			"licensed_domains" => (isset($data['licensed_domains']))?$data['licensed_domains']:null,
			"support_end_date" => (isset($data['support_end_date']))?$data['support_end_date']:null,
			"updates_end_date" => (isset($data['updates_end_date']))?$data['updates_end_date']:null,
			"expiry_date" => (isset($data['expiry_date']))?$data['expiry_date']:null,
			"expiry_days" => (isset($data['expiry_days']))?$data['expiry_days']:null,
			"license_uses" => (isset($data['license_uses']))?$data['license_uses']:null,
			"license_parallel_uses" => (isset($data['license_parallel_uses']))?$data['license_parallel_uses']:null
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/edit_license',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function get_license($license_code){
		$data_array =  array(
			"license_code"  => $license_code
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/get_license',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function mark_product_active($product_id){
		$data_array =  array(
			"product_id"  => $product_id
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/mark_product_active',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function mark_product_inactive($product_id){
		$data_array =  array(
			"product_id"  => $product_id
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/mark_product_inactive',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function delete_license($license_code){
		$data_array =  array(
			"license_code"  => $license_code
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/delete_license',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function block_license($license_code){
		$data_array =  array(
			"license_code"  => $license_code
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/block_license',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function unblock_license($license_code){
		$data_array =  array(
			"license_code"  => $license_code
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/unblock_license',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function deactivate_license_activations($license_code){
		$data_array =  array(
			"license_code"  => $license_code
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/deactivate_license_activations',
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}
}
