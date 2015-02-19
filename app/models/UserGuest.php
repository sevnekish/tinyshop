<?php 

class UserGuest extends User {
	protected $_role = 'guest';

	function login($params) {
		$valid_params = Validator::validateUserLoginParams($params);
		self::logout();
		$_SESSION[$valid_params['role']] = $valid_params['email'];
	}
}