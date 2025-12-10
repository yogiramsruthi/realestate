<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tally_http
{
    protected $endpoint;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->endpoint = get_option('real_estat_tally_http_endpoint');
        $this->username = get_option('real_estat_tally_http_username');
        $this->password = get_option('real_estat_tally_http_password');
    }

    public function configured()
    {
        return !empty($this->endpoint);
    }

    public function send($payload)
    {
        if (!$this->configured()) {
            return [
                'success' => false,
                'error'   => 'Tally HTTP endpoint not configured'
            ];
        }

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/xml',
        ]);

        if (!empty($this->username) && !empty($this->password)) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        }

        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        return [
            'success' => ($code >= 200 && $code < 300),
            'code'    => $code,
            'response'=> $response,
            'error'   => $error,
        ];
    }
}
