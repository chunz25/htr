<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class m_absensi extends CI_Model
{
	private $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
	}

	function getDataAbsensi($params)
	{
		// Validating parameters to ensure data integrity
		if (!isset($params["username"], $params["tglmulai"], $params["tglselesai"], $params["top"])) {
			return $this->errorResponse('Missing parameters');
		}

		// var_dump($params);die;

		$nik = $params["username"];
		$strdate = $params["tglmulai"];
		$enddate = $params["tglselesai"];
		$top = $params["top"];

		// die(var_dump(curl_init()));
		$host = 'http://10.101.0.85/attendance/login.php';
		$data = array(
			"username" => $nik,
		);

		try {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Ensuring proper data encoding
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);
			curl_close($ch);

			if ($response === false) {
				return $this->errorResponse('CURL Error: ' . curl_error($ch));
			}

			$return = json_decode($response);

			if ($return === null || !isset($return->accessToken)) {
				return $this->errorResponse('Failed to obtain token.');
			}

			$token = $return->accessToken;

			// Prepare data for the attendance request
			$data = array(
				'nik' => $nik,
				'startdate' => $strdate,
				'enddate' => $enddate,
				'top' => $top
			);

			// Get the attendance data from both methods
			$absen = $this->jwt_request($token, $data);
			if (!isset($absen['data'])) {
				$absen['data'] = null;
				$absen['count'] = 0;
				$absen['paging'] = 0;
			}

			$absen2 = $this->jwt_request2($token, $data);
			if (!isset($absen2['data'])) {
				$absen2['data'] = null;
				$absen2['count'] = 0;
				$absen2['paging'] = 0;
			}

			// Combine the data from both results and filter out empty arrays
			$dataAbsen['data'] = array_merge((array) $absen['data'], (array) $absen2['data']);
			$dataAbsen['count'] = $absen['count'] + $absen2['count'];
			$dataAbsen['paging'] = $absen['paging'] + $absen2['paging'];

			if ($dataAbsen === null) {
				// return $this->errorResponse($absen);
				return $this->errorResponse('Failed to retrieve attendance data.');
			}

			return $dataAbsen;

		} catch (Exception $e) {
			return $this->errorResponse($e->getMessage());
		}
	}

	function jwt_request($token, $param)
	{
		$url = 'http://10.101.0.85/attendance/getabsen.php';
		$ch = curl_init($url);

		$authorization = 'Authorization: Bearer ' . $token;
		$headers = array(
			'Content-Type: application/json',
			$authorization
		);

		// Set cURL options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));

		$result = curl_exec($ch);

		if ($result === false) {
			return $this->errorResponse('CURL Error: ' . curl_error($ch));
		}

		curl_close($ch);

		return json_decode($result, true); // Decode JSON and return as an array
	}

	function jwt_request2($token, $param)
	{
		$url = 'http://10.101.0.85/attendance/getabsen2.php';
		$ch = curl_init($url);

		$authorization = 'Authorization: Bearer ' . $token;
		$headers = array(
			'Content-Type: application/json',
			$authorization
		);

		// Set cURL options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));

		$result = curl_exec($ch);

		if ($result === false) {
			return $this->errorResponse('CURL Error: ' . curl_error($ch));
		}

		curl_close($ch);

		return json_decode($result, true); // Decode JSON and return as an array
	}

	private function errorResponse($message)
	{
		http_response_code(401);
		$return = array(
			"status" => false,
			"message" => $message
		);
		echo json_encode($return);
		exit();
	}

	public function addPengajuanAbsensi($params)
	{
		try {
			$result = $this->tp_connpgsql->callSpReturn('kehadiran.sp_addpengajuanabsen', $params);
			if ($result === false) {
				throw new Exception('Database query failed in addPengajuanAbsensi');
			}
			return $result;
		} catch (Exception $e) {
			log_message('error', 'Error in addPengajuanAbsensi: ' . $e->getMessage());
			return array();
		}
	}
}
