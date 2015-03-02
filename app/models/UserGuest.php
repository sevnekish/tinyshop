<?php 

class UserGuest extends User {
	protected $_role = 'guest';

	public function login($params) {
		$valid_params = Validator::validateUserLoginParams($params);
		$this -> logout();
		$_SESSION[$valid_params['role']] = $valid_params['email'];
	}
}