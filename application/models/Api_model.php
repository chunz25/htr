<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{

    // Function to make GET request
    public function getData($url, $headers = [])
    {
        $ch = curl_init();

        // Set options for the cURL request
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute the request
        $response = curl_exec($ch);

        // Handle errors
        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    // Function to make POST request
    public function postData($url, $data, $headers = [])
    {
        $ch = curl_init();

        // Set options for the cURL request
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, ['Content-Type: application/json']));

        // Execute the request
        $response = curl_exec($ch);

        // Handle errors
        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
