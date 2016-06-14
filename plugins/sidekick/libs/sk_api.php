<?php

class sk_api{

	function log($msg){
		error_log($msg);
		// file_put_contents('Licensing.log', "$msg\n", FILE_APPEND);
	}

	function login_request($data){
		$this->log("SEND_LOGIN_REQUEST");

		if (!isset($data)) {
			$this->log("login_request: error no data");
			return false;
		}

		$args = array(
			'timeout'     => 15,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'method'      => 'POST',
			'body'        => json_encode($data),
			'headers'     => array('Content-Type' => 'application/json')
			);

		$result =  wp_remote_post(SK_API . '/login' , $args);

		// $this->log($result);

		if ( is_wp_error( $result ) ) {
			$error_message = $result->get_error_message();
			$this->log("login: error -> $error_message");
		} else {
			$this->log("Success!");
			return json_decode($result['body']);
		}

	}

	function send_request($type, $end_point, $data = null, $second_attempt = null) {

		$this->log("SEND_REQUEST [$type] -> " . $end_point);

		if (!$sk_token = get_transient('sk_token')) {
			if (!$login_status = $this->login()) {
				$this->log('send_request: Can\'t send request');
				return false;
			} else {
				$this->log("send_request: logged in");
			}
		} else {
			$this->log("send_request: token found proceeding");
		}

		$url = SK_API . $end_point;


		$args = array(
			'timeout'     => 15,
			'redirection' => 5,
			'httpversion' => '1.0',
			'method'      => strtoupper($type),
			'body'        => (isset($type) && $type == 'post') ? json_encode($data) : $data,
			'blocking'    => true,
			'headers'     => array(
				'Content-Type' => 'application/json',
				'Authorization' => $sk_token
				)
			);

		if (isset($type) && $type == 'post') {
			$args['method'] = 'POST';
			$args['body']   = json_encode($data);
		} else if (isset($type) && $type == 'get') {
			$args['method'] = 'GET';
			$args['body']   = $data;
		} else if (isset($type) && $type == 'delete') {
			$args['method'] = 'DELETE';
			$url .= '?' . http_build_query($data);
		}

		$this->log("send_request: $url");
		$this->log("send_request: RESPONSE");
		$response = wp_remote_post($url, $args);
			// mlog('$response',$response);

		$this->log("send_request: {$response['response']['message']}");

		if ($response['response']['message'] == 'Unauthorized' && !$second_attempt) {
                // var_dump('Getting rid of token and trying again');
			$this->log('send_request: Getting rid of token and trying again');
			delete_transient('sk_token');
			if ($this->login()) {
				return $this->send_request($type, $end_point, $data, true);
			}
		} else {
			$this->log('send_request: Success!');
			return json_decode($response['body']);
		}

	}

	function login() {

		$this->log("login");

		$email    = get_option('sk_account');
		$password = get_option('sk_password');
		delete_option('sk_auto_activation_error');

		if (!$password || !$email) {
			return false;
		}

		$key                = 'hash';
		$decrypted_password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

		$result = $this->login_request(array('email' => $email, 'password' => $decrypted_password));

		if (!isset($result) || !isset($result->success) || (isset($result->response->message) && $result->response->message == 'Unauthorized')) {
			if (isset($result->message)) {
				update_option('sk_auto_activation_error',$result->message);
				$this->log("login: false -> $result->message");
			} else {
				$this->log("login: false");
			}
			delete_option('sk_token');
			return false;
		} else {
			$this->log("login: true -> saving token " . $result->payload->token->value);
			delete_option('sk_auto_activation_error');
			set_transient('sk_token', $result->payload->token->value, 24 * HOUR_IN_SECONDS);
			$this->load_subscriptions($result->payload->token->value);
			return $result->payload->token->value;
		}
	}

	function load_user_data() {
		return $this->send_request('get', '/users/');
	}

	function load_subscriptions() {

		$this->log("load_subscriptions");
		$result = $this->send_request('get', '/users/subscriptions');

		if (isset($result->success) && isset($result->payload)) {

			$sub = $result->payload[0];

				// if (isset($sub->Plan->CreatableProductType) && $sub->Plan->CreatableProductType->name == 'Public') {
				// 	$this->logout();
				// 	update_option('sk_auto_activation_error', 'Public accounts are not compatible with MultiSite activations.');

				// 	return false;
				// }

			update_option('sk_subscription_id', $sub->id);

			$sub->activeDomainCount = 0;

			if (count($sub->Domains) > 0) {
				foreach ($sub->Domains as &$domain) {
					if (!$domain->end) {
						if (isset($sub->activeDomainCount)) {
							$sub->activeDomainCount++;
						} else {
							$sub->activeDomainCount = 1;
						}
					}
				}
			}

			$data['subscriptions'] = $result->payload;
			$data['libraries']     = $this->load_libraries();

			return $data;
		} else if (isset($result->message) && strpos($result->message, 'Invalid Token') !== false) {
			$this->logout();
			update_option('sk_auto_activation_error', 'Please authorize SIDEKICK by logging in.');
		}

		return null;
	}

	function load_libraries() {
		$result = $this->send_request('get', '/products');
		if ($result->success) {
			return $result->payload->products;
		}

		return null;
	}

	function logout() {
		delete_option('sk_account');
		delete_option('sk_password');
		delete_option('sk_subscription_id');
		delete_option('sk_selected_library');
	}


}
