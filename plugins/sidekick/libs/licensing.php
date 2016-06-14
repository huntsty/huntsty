<?php

// licensing.php

if (!class_exists('sidekickMassActivator')) {

	require_once('sk_api.php');

	class sidekickMassActivator extends sk_api{

		var $sites_per_page = 50;
		var $offet = 0;

		function activate($blog_id, $user_id, $domain, $path) {
			// mlog("FUNCTION: activate [$blog_id, $user_id, $domain, $path]");

			$this->log("activate: $blog_id, $user_id, $domain, $path");

			$email = '';

			if ($user_id) {
				$user  = get_user_by('id', $user_id);
				$email = ($user) ? $user->user_email : 'unknown';
			}
			$sk_subscription_id  = get_option("sk_subscription_id");
			$sk_selected_library = get_option("sk_selected_library");

			if (isset($sk_selected_library) && $sk_selected_library && $sk_selected_library !== -1 && $sk_selected_library !== '-1') {
				$data = array('domainName' => $domain . '/' . $path, 'productId' => $sk_selected_library);
			} elseif (isset($sk_subscription_id) && intval($sk_subscription_id)) {
				$data = array('domainName' => $domain . '/' . $path, 'subscriptionId' => $sk_subscription_id);
			} else {
				update_option('sk_auto_activation_error', "No selected library or subscriptionId set");
				return false;
			}

			$result = $this->send_request('post', '/domains', $data);

			if (isset($result->success) && $result->success == true && $result->payload->domainKey) {
				$this->log("activate: success");
				$this->activationSuccessful($result,$blog_id,$email);
			} else {
				$this->log("activate: error");
				$this->activationError($result);
			}

			return $result;
		}

		function activationSuccessful($result,$blog_id,$email){

			$this->log("activationSuccessful:" . $result->payload->domainKey);

			$this->setup_super_admin_key($result->payload->domainKey);

			switch_to_blog($blog_id);
			update_option('sk_activation_id', $result->payload->domainKey);
			update_option('sk_email', $email);
			restore_current_blog();

			update_option('sk_last_setup_blog_id', $blog_id);

			delete_option('sk_auto_activation_error');
		}

		function activationError($result){

			$this->log("activationError: $result->message");

			update_option('sk_auto_activation_error', $result->message);
            // wp_mail( 'support@sidekick.pro', 'Failed Mass Domain Add', json_encode($result));
			wp_mail('bart@sidekick.pro', 'Failed Mass Domain Add', json_encode($result));
		}

		function deactivate($blog_id) {

			$this->log("deactivate: $blog_id");

			switch_to_blog($blog_id);
			$sk_activation_id = get_option('sk_activation_id');
			delete_option('sk_activation_id');
			restore_current_blog();

			$result = $this->send_request('delete', '/domains', array('domainKey' => $sk_activation_id));

			$this->log("result: $result->success");

			if (isset($result) && isset($result->success) && $result->success == true) {
				delete_option('sk_auto_activation_error');
			} else {
				update_option('sk_auto_activation_error', $result->message);
				wp_mail('bart@sidekick.pro', 'Failed Domain Deactivation', json_encode($result));
			}

			return $result;
		}

		function getAffiliateId(){
			if (defined('SK_AFFILIATE_ID')) {
				$affiliate_id = intval(SK_AFFILIATE_ID);
			} else if (get_option( "sk_affiliate_id")){
				$affiliate_id = intval(get_option( "sk_affiliate_id"));
			} else {
				$affiliate_id = '';
			}
			return $affiliate_id;
		}

		function setup_super_admin_key($domainKey) {
			// Use the super admin's site activation key if not set using last activation key
			if (!get_option('sk_activation_id')) {
				update_option('sk_activation_id', $domainKey);
			}
		}

		function activate_batch() {

			$this->log("activate_batch");

			$count         = 0;
			$blogs = $this->get_blogs(true);

			foreach ($blogs as $key => $blog) {

				$userId = null;
				if (isset($blog->user_id)) {
					$userId = $blog->user_id;
				}

				$this->activate($blog->blog_id, $userId, $blog->domain, $blog->path);
			}

			die();
		}

		function activate_single() {
			$this->log("activate_single {$_POST['blog_id']}");

			$result = $this->activate($_POST['blog_id'], null, $_POST['domain'], $_POST['path']);
			die(json_encode($result));
		}

		function deactivate_single() {
			$this->log("deactivate_single {$_POST['blog_id']}");

			$blog_id       = $_POST['blog_id'];

			if ($this->deactivate($blog_id)){
				die('{"success":1}');
			} else {
				die('{"payload":{"message":"Error #13a"}}');
			}
		}

		function setup_menu() {
			add_submenu_page('settings.php', 'Sidekick - Licensing', 'Sidekick - Licensing', 'activate_plugins', 'sidekick-licensing', array(&$this, 'admin_page'));
		}

		function get_blogs($noCache = false) {
			global $wpdb;

			if ($noCache || false === ($blogs = get_transient('sk_blog_list'))) {
				$blogs = $wpdb->get_results($wpdb->prepare("SELECT *
					FROM $wpdb->blogs
					WHERE spam = '%d' AND deleted = '%d'
					"
					, 0, 0));
				set_transient('sk_blog_list', $blogs, 24 * HOUR_IN_SECONDS);
			}

			return $blogs;
		}

		function check_batch_status(){
			$blogList = $_POST['blogIdList'];
			$activeList = array();

			foreach ($blogList as $blogList_key => $blog_id) {
				switch_to_blog($blog_id);
				$sk_activation_id = get_option('sk_activation_id');
				if ($sk_activation_id) {
					$activeList[] = $blog_id;
				}
				restore_current_blog();
			}
			die(json_encode($activeList));
		}

		function admin_page() {

			$this->log("admin_page");

			if (isset($_POST['sk_account'])) {

				delete_option('sk_auto_activation_error');

				if (isset($_POST['sk_password']) && $_POST['sk_password'] && isset($_POST['sk_account']) && $_POST['sk_account']) {
					$key    = 'hash';
					$string = $_POST['sk_password'];

					$encrypted_password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
					$decrypted_password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted_password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

					update_option('sk_account', $_POST['sk_account']);
					update_option('sk_password', $encrypted_password);
					delete_option('sk_auto_activation_error');
					delete_transient('sk_token');
				}

				if (isset($_POST['sk_auto_activations'])) {
					update_option('sk_auto_activations', true);
				} else {
					delete_option('sk_auto_activations');
				}

				if (isset($_POST['sk_selected_library'])) {
					update_option('sk_selected_library', $_POST['sk_selected_library']);
				}

			}

			$this->log("admin_page sk_token = " . get_transient('sk_token'));

			if (!$sk_token = get_transient('sk_token')) {
				$login_status = $this->login();
			}

			$sk_subs                         = $this->load_subscriptions();
			$user_data                       = $this->load_user_data();
			$sk_auto_activations             = get_option('sk_auto_activations');
			$sk_auto_activation_error        = get_option('sk_auto_activation_error');
			$sk_subscription_id              = get_option('sk_subscription_id');
			$sk_selected_library             = get_option('sk_selected_library');
			$sk_hide_composer_taskbar_button = get_option('sk_hide_composer_taskbar_button');
			$sk_hide_config_taskbar_button   = get_option('sk_hide_config_taskbar_button');
			$sk_hide_composer_upgrade_button = get_option('sk_hide_composer_upgrade_button');
			$is_ms_admin                     = true;
			$affiliate_id                    = $this->getAffiliateId();
			$all_sites                       = $this->get_blogs(true);

			require_once('ms_admin_page.php');
		}
	}
}

// //licensing.php
