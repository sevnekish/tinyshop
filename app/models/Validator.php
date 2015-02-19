<?php
/**
 * Class with static methods for parameters sended by user validation 
 */

abstract class Validator {

	private function connect_db() {
		return Database::getInstance();
	}


	static function validateCheckout($params) {
		self::findEmpty(array(
								$params['name'],
								$params['email'],
								$params['telephone'],
								$params['address'],
							));

		if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
			throw new Exception('This (' . $email . ') email address is not valid!');
		}

		$name = $params['name'];
		$email = $params['email'];
		$telephone = filter_var($params['telephone'], FILTER_SANITIZE_NUMBER_INT);
		$address = filter_var($params['address'], FILTER_SANITIZE_STRING);

		self::check_length(array(
									array(
										'name' => 'Name',
										'value' => $name,
										'min_length' => '2',
										'max_length' => '50'
										),
									array(
										'name' => 'Email',
										'value' => $email,
										'min_length' => '6',
										'max_length' => '100'
										),
									array(
										'name' => 'Telephone',
										'value' => $telephone,
										'min_length' => '6',
										'max_length' => '16'
										),
									array(
										'name' => 'Address',
										'value' => $address,
										'min_length' => '7',
										'max_length' => '160'
										)
								));

		return array(
						'name' => $name,
						'email' => $email,
						'telephone' => $telephone,
						'address' => $address
					);

	}


	static function validateCategory($category) {

		self::findEmpty(array('category' => $category));

		$category = filter_var($category, FILTER_SANITIZE_STRING);

		if(self::find_matches('categories', 'category', $category)) {
			throw new Exception('Category(' . $category . ') is already exists!');
		}

		return array('category' => $category);
	}

	static function validateBrand($brand) {

		self::findEmpty(array('brand' => $brand));

		$brand = filter_var($brand, FILTER_SANITIZE_STRING);

		if(self::find_matches('brands', 'brand', $brand)) {
			throw new Exception('Brand(' . $brand . ') is already exists!');
		}

		return array('brand' => $brand);
	}

	static function validateItemParams($params) {

		self::findEmpty(array(
								$params['category'],
								$params['brand'],
								$params['model'],
								$params['characteristics'],
								$params['description'],
								$params['price'],
								$params['instock'],
								$params['photo']
							));

		$category = self::clearInt($params['category']);
		$brand = self::clearInt($params['brand']);
		$model = filter_var($params['model'], FILTER_SANITIZE_STRING);
		$characteristics = filter_var($params['characteristics'], FILTER_SANITIZE_STRING);
		$description = filter_var($params['description'], FILTER_SANITIZE_STRING);
		$price = self::clearFloat($params['price']);
		$instock = self::clearInt($params['instock']);

		//check length of params
		self::check_length(array(
									array(
										'name' => 'Model',
										'value' => $model,
										'min_length' => '1',
										'max_length' => '50'
										),
									array(
										'name' => 'Characteristics',
										'value' => $characteristics,
										'min_length' => '15',
										'max_length' => '1200'
										),
									array(
										'name' => 'Description',
										'value' => $description,
										'min_length' => '15',
										'max_length' => '1200'
										)
								));

		$photo_name = self::validateImage($params['photo'], $params['upload_dir']);

		return array(
						'category_id' => $category,
						'brand_id' => $brand,
						'model' => $model,
						'characteristics' => $characteristics,
						'description' => $description,
						'photo' => $photo_name,
						'price' => $price,
						'instock' => $instock
					);
	}

	static function validateUserLoginParams($params) {

		$id = '';

		self::findEmpty(array(
								$params['email'],
								$params['password']
							));

		$email = $params['email'];
		$password = $params['password'];

		if (!($user_data = self::find_user_by_email($email))) {
			throw new Exception("User with this email($email) didn't exists");
		}
		//password checking
		if (!(self::genHash($password, $user_data['salt'], $user_data['iterations']) == $user_data['hash'])) {
			throw new Exception("Unable to log in.<br>Please check that you have entered your email and password correctly.");
		}

		return array(
						'email' => $email,
						'role' => $user_data['role'],
						'id' => $user_data['id']
					);
	}

	static function validateUserRegParams($params) {

		//check for empty params(that guest can send manualy)
		self::findEmpty(array(
								$params['name'],
								$params['password'],
								$params['email'],
								$params['telephone'],
								$params['address']
							));

		$name = $params['name'];
		$password = $params['password'];
		$email = $params['email'];
		$telephone = filter_var($params['telephone'], FILTER_SANITIZE_NUMBER_INT);
		$address = filter_var($params['address'], FILTER_SANITIZE_STRING);

		//
		self::validateEmail($email);

		//check length of params that guest can send manualy
		self::check_length(array(
									array(
										'name' => 'Name',
										'value' => $name,
										'min_length' => '2',
										'max_length' => '50'
										),
									array(
										'name' => 'Password',
										'value' => $password,
										'min_length' => '6',
										'max_length' => '25'
										),
									array(
										'name' => 'Email',
										'value' => $email,
										'min_length' => '6',
										'max_length' => '100'
										),
									array(
										'name' => 'Telephone',
										'value' => $telephone,
										'min_length' => '6',
										'max_length' => '16'
										),
									array(
										'name' => 'Address',
										'value' => $address,
										'min_length' => '7',
										'max_length' => '160'
										)
								));

		//checking parameters that can be sended only by admin 
		$role = $params['role'];
		if (empty($role)) {
			$role = 'user';
		}

		$salt = $params['salt'];
		if (empty($salt)) {
			$salt = self::genSalt();
		}

		$iterations = self::clearInt($params['iterations']);
		if (empty($iterations) || ($iterations > 100)) {
			$iterations = rand(1,100);
		}

		$hash = self::genHash($password, $salt, $iterations);


		return array(
						'name' => $name,
						'email' => $email,
						'telephone' => $telephone,
						'address' => $address,
						'hash' => $hash,
						'salt' => $salt,
						'iterations' => $iterations,
						'role' => $role
					);
	}

	static function validateEmail($email) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new Exception('This (' . $email . ') email address is not valid!');
		}

		if(self::find_matches('users', 'email', $email)) {
			throw new Exception('Email(' . $email . ') is already taken!');
		}
	}

	static function validatePassword($password) {
	}

	static function validateImage($file, $path) {
		self::validateFile($file, array('image/jpeg'), $path);

		return $file['name'];
	}

	static function validateFile($file, $formats, $path) {
		$error = $file['error'];
		if ($error != 0) {
			switch ($error) {
				case 1:
				case 2:
					throw new Exception('File is too large to upload!');
				break;

				default:
					throw new Exception('Upload failed!');
			}
		}

		if (!in_array($file['type'], $formats)) {
			throw new Exception('Wrong format of sending file!');
		}

		$files = scandir($path);
		if (in_array($file['name'], $files)) {
			throw new Exception('A file with this name already exists!');
		}
	}

	static function findEmpty($params) {
		foreach ($params as $key => $value) {
			if (empty($value)) {
				throw new Exception('Please fill in all fields!');
			}
		}
	}

	static function check_length($strs_array) {
		foreach ($strs_array as $str) {
			$str_name = $str['name'];
			$str_value = $str['value'];
			$min_length = $str['min_length'];
			$max_length = $str['max_length'];

			if (!self::check_str_min_length($str_value, $min_length)) {
				throw new Exception($str_name . ' must be at least '.$min_length.' characters long!');
			}

			if (!self::check_str_max_length($str_value, $max_length)) {
				throw new Exception('Your ' . $str_name . ' must be less than ' . $max_length . ' characters');
			}
		}
	}

	static function check_str_min_length($str, $min_length) {
		if (strlen($str) < $min_length) {
			return false;
		}
		return true;
	}

	static function check_str_max_length($str, $max_length) {
		if (strlen($str) > $max_length) {
			return false;
		}
		return true;
	}

	static function find_matches($table, $field, $field_value) {
		$db = self::connect_db();
		$field_value = $db -> quote($field_value);
		$sql = "SELECT id
				FROM $table
				WHERE $field=$field_value
				";
		$stmt = $db -> query($sql);
		$stmt = $stmt -> fetch(PDO::FETCH_ASSOC);

		if (isset($stmt['id'])) {
			return $stmt['id'];
		}

		return false;
	}

	static function find_user_by_email($email) {
		$db = self::connect_db();
		$email = $db -> quote($email);
		$sql = "SELECT id, hash, salt, iterations, role
				FROM users
				WHERE email=$email
				";
		$stmt = $db -> query($sql);
		$stmt = $stmt -> fetch(PDO::FETCH_ASSOC);
		
		if (isset($stmt['hash'])) {
			return array(
							'id' => $stmt['id'],
							'hash' => $stmt['hash'],
							'salt' => $stmt['salt'],
							'iterations' => $stmt['iterations'],
							'role' => $stmt['role']
						);
		}

		return false;
	}

	private function genSalt()
	{
			return str_replace('=', '', base64_encode(md5(microtime() . '4324IHDDDS8127DAS992NSQ')));
	}

	private function genHash($password, $salt, $iterations)
	{
		for($i = 0; $i < $iterations; $i++)
		{
			$hash = sha1($password . $salt);
		}
		return $hash;
	}

	static function clearInt($int) {
		return abs((int)$int);
	}

	static function clearFloat($float) {
		return abs(floatval($float));
	}

}