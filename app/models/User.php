<?php 
/**
 * This abstract class describes every user that comes to the site
 */
abstract class User implements IUser {

	/**
	 * @var _role Role of current user
	 */
	protected $_role = '';

	/**
	 * @var _email Has value: Empty for guest, email of user if user is authorized(for admin too).
	 */
	protected $_email = '';

	/**
	 * @var _db Should contain PDO object 
	 */
	protected $_db;

	public function __construct($email = null) {
		$this -> _email = $email;

		$this -> _db = Database::getInstance();
	}

	public function getRole() {
		return $this -> _role;
	}

	public function getEmail() {
		return $this -> _email;
	}

	public function getParams() {
		return array(
						'role' => $this -> getRole(),
						'email' => $this -> getEmail()
					);
	}



	public function getCategories() {
		$sql = 'SELECT * FROM categories';
		$stmt = $this -> _db -> query($sql);
		$categories = array();
		foreach ($stmt as $row) {
			$categories[$row['id']] = $row['category'];
		}
		return $categories;
	}

	public function getBrands() {
		$sql = 'SELECT * FROM brands';
		$stmt = $this -> _db -> query($sql);
		$brands = array();
		foreach ($stmt as $row) {
			$brands[$row['id']] = $row['brand'];
		}
		asort($brands);
		return $brands;
	}

	public function getInfo() {

		if ($this -> _role == 'user' or $this -> _role == 'admin') {
			$email = $this -> _db -> quote($this -> _email);

			$sql = "SELECT id, name, email, telephone, address 
					FROM users 
					WHERE email=$email
					";
			$stmt = $this -> _db -> query($sql);
			$stmt = $stmt -> fetch(PDO::FETCH_ASSOC);

			return array(
							'id' => $stmt['id'],
							'name' => $stmt['name'],
							'email' => $stmt['email'],
							'telephone' => $stmt['telephone'],
							'address' => $stmt['address']
						);
		} else {
			return false;
		}
	}


	/**
	 * Get Items from (DB)catalog
	 * 
	 * @return array Array of Products 
	 */
	public function getItems($category_id, $sort, $total_items, $items_per_page, $cur_page) {
		$category_id = $this -> _db -> quote($category_id);

		switch ($sort) {
			case 'cheap':
				$sort = 'price ';
				break;
			case 'expensive':
				$sort = 'price DESC ';
				break;
			case 'brand':
				$sort = 'brand_id ';
				break;

			default:
				return false;
				break;
		}

		$first_id = $cur_page * $items_per_page - $items_per_page;

		//checking amount of items on current page
		if (($total_items - ($cur_page * $items_per_page)) < 0) {
			$items_per_page = $total_items - (($cur_page - 1) * $items_per_page);
		}

		$sql = "
				SELECT br.brand, ct.id, ct.model, ct.photo, ct.price, ct.instock
				FROM (
					SELECT id, brand_id, model, photo, price, instock
					FROM catalog
					WHERE category_id=$category_id
					ORDER by $sort
					LIMIT $first_id,$items_per_page
					) AS ct
				JOIN (
					SELECT id, brand
					FROM brands
					) AS br
				ON ct.brand_id = br.id
			";

		if (!$stmt = $this -> _db -> query($sql)) {
			return false;
		}

		$items = array();
		
		while ($item = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			$items[] = $item;
		}

		return $items;
	}

	public function getItem($item_id) {
		$item_id = $this -> _db -> quote($item_id);


		$sql = "
				SELECT br.brand, ct.id, ct.model, ct.characteristics, ct.description, ct.photo, ct.price, ct.instock
				FROM (
					SELECT id, category_id, brand_id, model, characteristics, description, photo, price, instock
					FROM catalog
					WHERE id=$item_id
					) AS ct
				JOIN (
					SELECT id, brand
					FROM brands
					) AS br
				ON ct.brand_id = br.id
			";

		if (!$stmt = $this -> _db -> query($sql)) {
			return false;
		}

		$item = $stmt -> fetch(PDO::FETCH_ASSOC);
		return $item;
	}

	public function getItemsRandom($number) {
		$sql_ids = "
						SELECT id
						FROM catalog
						WHERE instock > 0
					";
		$stmt = $this -> _db -> query($sql_ids);

		$ids = array();

		while ($item = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			$ids[] = $item['id'];
		}
		shuffle($ids);

		$items_random = array();

		for ($i = 0; $i < $number; $i++) {
			$items_random[] = $this -> getItem($ids[$i]);
		}

		return $items_random;
	}

	public function countPages($total_items, $items_per_page) {

		$total_pages = ceil($total_items / $items_per_page);
		
		return $total_pages;
	}

	public function countItems($category) {
		$category = $this -> _db -> quote($category);
		$sql = "SELECT count(id)
				FROM catalog
				WHERE category_id=$category";
		if (!$stmt = $this -> _db -> query($sql)) {
			return false;
		}

		$stmt = $stmt -> fetch(PDO::FETCH_ASSOC);

		$total_items = $stmt['count(id)'];

		if ($total_items == 0) {
			return false;
		}

		return $total_items;
	}

	public function countReviews() {
		$sql = "
				SELECT count(id)
				FROM reviews
				";
		if (!$stmt = $this -> _db -> query($sql)) {
			return false;
		}

		$stmt = $stmt -> fetch(PDO::FETCH_ASSOC);

		$total_reviews = $stmt['count(id)'];

		if ($total_reviews == 0) {
			return false;
		}

		return $total_reviews;
	}


	/**
	 * Get Reviews from DB
	 * 
	 * @return array Array of Reviews with elements: 
	 */
	public function getReviews($total_reviews, $reviews_per_page, $cur_page) {

		$first_id = $cur_page * $reviews_per_page - $reviews_per_page;

		//checking amount of reviews on current page
		if (($total_reviews - ($cur_page * $reviews_per_page)) < 0) {
			$reviews_per_page = $total_reviews - (($cur_page - 1) * $reviews_per_page);
		}

		$sql = "
				SELECT id, name, email, review, rating, date
				FROM reviews
				ORDER by date DESC
				LIMIT $first_id,$reviews_per_page
			";

		if (!$stmt = $this -> _db -> query($sql)) {
			return false;
		}

		$reviews = array();
		
		while ($review = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			$reviews[] = $review;
		}

		return $reviews;
	}

	public function addReview($params) {
		$valid_review_params = Validator::validateReview($params);

		if ($user_params = $this -> getInfo()) {
			$valid_review_params['name'] = $user_params['name'];
			$valid_review_params['email'] = $user_params['email'];
		}
		
		if(!$this -> addToDb('reviews', $valid_review_params)) {
			throw new Exception('Adding operation failed!');
		}

	}


	public function logout() {
		unset($_SESSION[$this -> _role]);
	}


	public function registrateNewUser($params) {

		$valid_params = Validator::validateUserRegParams($params);

		if(!$this -> addToDb('users', $valid_params)) {
			throw new Exception('Adding operation failed!');
		}
	}

	public function checkout($params, $cart_array, $sum) {
		//name, email, telephone, address
		$valid_user_params = Validator::validateCheckout($params);

		$name = $this -> _db -> quote($valid_user_params['name']);
		$email = $this -> _db -> quote($valid_user_params['email']);
		$telephone = $this -> _db -> quote($valid_user_params['telephone']);
		$address = $this -> _db -> quote($valid_user_params['address']);

		$user_id = '';

		if ($this -> _role != 'guest') {
			$user_id = Validator::find_matches('users', 'email', $this -> _email);
		}

		try {
			$this -> _db -> beginTransaction();

			foreach ($cart_array as $order_id => $cart_items) {

				$sql_orders = "
								INSERT INTO orders(order_id, user_id, name, email, telephone, address, sum)
								VALUES('$order_id', '$user_id', $name, $email, $telephone, $address, '$sum')
							";

				$this -> _db -> exec($sql_orders);

				foreach ($cart_items as $cart_item) {

				$item_id = $cart_item['id'];
				$price = $cart_item['price'];
				$quantity = $cart_item['quantity'];

				$sql_ordered_item = "
										INSERT INTO ordered_items(order_id, item_id, old_price, quantity)
										VALUES('$order_id', '$item_id', '$price', '$quantity')
									";
				$this -> _db -> exec($sql_ordered_item);
				}
			}

			$this -> _db -> commit();

		} catch (PDOException $e) {
			$this -> _db -> rollBack();
			throw new Exception("Something went wrong. Please try again later.");
		}
	}

	public function addToDb($table_name, $params) {

		$names_array = array_keys($params);
		$values_arr = array_values($params);

		$values_array = array();

		foreach ($values_arr as $value) {
			$values_array[] = $this -> _db -> quote($value);
		}

		$names = implode(', ', $names_array);
		$values = implode(', ', $values_array);


		$sql = "INSERT INTO $table_name($names)
				VALUES($values)
				";

		if (!($this -> _db -> exec($sql))) {
			return false;
		}
		return true;
	}


}

?>