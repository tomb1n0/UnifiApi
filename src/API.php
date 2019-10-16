<?php

namespace UnifiAPI;

use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;

use UnifiAPI\Exceptions\InvalidLoginException;
use UnifiAPI\Exceptions\ClientException;
use UnifiAPI\Exceptions\ServerException;
use UnifiAPI\Exceptions\ConnectionException;
use UnifiAPI\Exceptions\UnifiAPIException;

class API {

	protected $client;
	protected $site_name;
	protected $controller;
	protected $global_controller;
	private $username;
	private $password;
	protected $logged_in;

	public function __construct($url, $site_name, $username, $password, $custom_options = []) {
		$options = array_replace([
			'base_uri' => $url,
			'verify' => false,
			'cookies' => new \GuzzleHttp\Cookie\CookieJar()
		], $custom_options);
		$this->client = new \GuzzleHttp\Client($options);
		$this->site_name = $site_name;
		$this->username = $username;
		$this->password = $password;
		$this->logged_in = false;
	}

	public function request($request_type, $url, $data = []) {
		if (!$this->logged_in && $url !== '/api/login') {
			$this->login();
			$this->logged_in = true;
		}
		try {
			$response = $this->client->request($request_type, $url, ['json' => $data]);
			$contents = $response->getBody()->getContents();
			return json_decode($contents)->data;
		} catch (GuzzleConnectException $e) {
			throw new ConnectionException($e->getMessage());
		} catch (GuzzleClientException $e) {
			if ($e->hasResponse()) {
				$error = json_decode($e->getResponse()->getBody()->getContents());
				if ($url == '/api/login' && $error->meta->msg == 'api.err.Invalid') {
					$this->logged_in = false;
					throw new InvalidLoginException("Incorrect username or password.");
				}
			}
			throw new ClientException($e->getMessage());
		} catch (GuzzleServerException $e) {
			throw new ServerException($e->getMessage());
		}
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

	public function login() {
		return $this->post('/api/login',['username' => $this->username , 'password' => $this->password]);
	}

	public function logout() {
		return $this->get('/logout');
	}

	public function controller() {
		if (!isset($this->controller)) {
			$this->controller = new Controller($this->site_name, $this);
		}
		return $this->controller;
	}

	public function global_controller() {
		if (!isset($this->global_controller)) {
			$this->global_controller = new GlobalController($this->controller());
		}
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

	/**
	 * Try calling the /api/self endpoint.
	 * If we don't get an exception then we got a valid response and our controller must be online.
	 *
	 * @return bool|throws UnifiAPIException
	 */
	public function online() {
		try {
			$this->get('/api/self');
			return true;
		} catch (InvalidLoginException $e) {
			throw $e;
		} catch (UnifiAPIException $e) {
			return false;
		}
		return false;
	}

}
