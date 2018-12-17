<?php

namespace UnifiAPI;

class API {

	protected $client;
	protected $site_name;
	protected $controller;
	protected $global_controller;

	public function __construct($url, $site_name, $username, $password) {
		$this->client = new \GuzzleHttp\Client([
			'base_uri' => $url,
			'verify' => false,
			'cookies' => new \GuzzleHttp\Cookie\CookieJar()
		]);
		$this->site_name = $site_name;
		$this->login($username, $password);
		$this->controller = new Controller($site_name, $this);
		$this->global_controller = new GlobalController($this->controller);
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

	public function global_controller() {
		return $this->global_controller;
	}

	public function site_name() {
		return $this->site_name;
	}

	public function switch_site($new_site_name) {
		$this->site_name = $new_site_name;
		$this->controller = new Controller($new_site_name, $this);
		$this->global_controller = new GlobalController($this->controller);
	}

}
