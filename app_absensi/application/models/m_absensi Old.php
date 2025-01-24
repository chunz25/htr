<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_absensi extends CI_Model
{
	var $CI;
	function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
	}

	function getDataAbsensi($params)
	{
		$nik = $params["username"];
		$strdate = $params["tglmulai"];
		$enddate = $params["tglselesai"];

		$host = 'http://10.101.0.85/attendance/login.php';
		$data = array(
			"username" => $nik,
		);

		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt(
				$ch,
				CURLOPT_POSTFIELDS,
				$data
			);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$return = json_decode(curl_exec($ch));
			// $token = "Authorization: Bearer " . $return->accessToken;
			$token = $return->accessToken;

			curl_close($ch);

			if ($return !== null) {
				$data = array(
					'nik' => $nik,
					'strdate' => $strdate,
					'enddate' => $enddate,
				);

				$absen = $this->jwt_request($token, $data);
				// var_dump($absen);die;
			}
		} catch (Exception $e) {
			// Bagian ini akan jalan jika terdapat error saat JWT diverifikasi atau di-decode
			http_response_code(401);
			$return = array(
				"status" => false,
				"message" => "unauthorized"
			);
			echo json_encode($return);
			exit();
		}
	}

	function jwt_request($token, $param)
	{
		header('Content-Type: application/json');
		$ch = curl_init('http://10.101.0.85/attendance/getabsen.php');
		$authorization = 'Authorization: Bearer ' . $token;

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param)); // Encode param sebagai JSON
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);
		curl_close($ch);

		return json_decode($result, true); // Decode JSON dan kembalikan sebagai array
	}

}
