<?php

namespace UnifiAPI;

class API {

	protected $client;
	protected $controller;

	public function __construct($url, $site_name, $username, $password) {
		$this->client = new \GuzzleHttp\Client([
			'base_uri' => $url,
			'verify' => false,
			'cookies' => new \GuzzleHttp\Cookie\CookieJar()
		]);
		$this->login($username, $password);
		$this->controller = new Controller($site_name, $this);
	}

	public function request($request_type, $url, $data = []) {
		return json_decode($this->client->request($request_type, $url, ['json' => $data])->getBody()->getContents())->data;
	}

	public function get($url, $data = []) {
		return $this->request('GET', $url, $data);
	}

	public function post($url, $data = []) {
		return $this->request('POST', $url, $data);
	}

	public function delete($url, $data = []) {
		return $this->request('DELETE', $url, $data);
	}

	public function head($url, $data = []) {
		return $this->request('HEAD', $url, $data);
	}

	public function options($url, $data = []) {
		return $this->request('OPTIONS', $url, $data);
	}

	public function patch($url, $data = []) {
		return $this->request('PATCH', $url, $data);
	}

	public function put($url, $data = []) {
		return $this->request('PUT', $url, $data);
	}

	public function login($username, $password) {
		$this->post('/api/login',['username' => $username , 'password' => $password]);
	}

	public function logout() {
		$this->get('/logout');
	}

	public function controller() {
		return $this->controller;
	}

}
