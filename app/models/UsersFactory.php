<?php 
/**
 * This is a factory class;
 */

abstract class UsersFactory {
	static function createUser() {
		$role = self::defineUserRole();

		switch ($role) {
			case 'guest': return new UserGuest(); break;
			case 'user' : return new UserAuthorized($_SESSION['user']); break;
			case 'admin' : return new UserAdmin($_SESSION['admin']); break;
		}
	}

	private function defineUserRole() {
		if (isset($_SESSION['user'])) {
			return 'user';
		}
		if (isset($_SESSION['admin'])) {
			return 'admin';
		}
		return 'guest';
	}
}