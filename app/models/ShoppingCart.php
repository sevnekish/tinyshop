<?php

/**
 * This class describes shopping cart
 */

class ShoppingCart {

	/**
	 * @var $basket contain array of products in cart
	 */
	private $_cart = array();

	public function __construct() {
		$this -> _initCart();
	}

	private function _connect_db() {
		return Database::getInstance();
	}

	private function _initCart() {
		if (!isset($_COOKIE['cart'])) {
			$this -> _cart = array('order_id' => uniqid());
			$this -> saveCart();
		} else {
			$this -> _cart = unserialize(base64_decode($_COOKIE['cart']));
		}
	}

	public function saveCart() {
		$cart_serialized = base64_encode(serialize($this -> _cart));
		setcookie('cart', $cart_serialized, strtotime("+1 day"), '/');
	}


	public function getCart() {
		return $this -> _cart;
	}

	public function getCount() {
		$products = $this -> _cart;
		unset($products['order_id']);
		return array_sum($products);
	}

	public function addToCart($id, $quantity = 1) {
		if (array_key_exists($id, $this -> _cart)) {
			$new_quantity = $this -> _cart[$id] + $quantity;
			$this -> _cart[$id] = $new_quantity;
			$this -> saveCart();
		} else {
			$this -> _cart[$id] = $quantity;
			$this -> saveCart();
		}
	}

	public function deleteItemFromCart($id) {
		if (!array_key_exists($id, $this -> _cart)) {
			return false;
		}

		unset($this -> _cart[$id]);
		$this -> saveCart();
	}

	public function getCartArray() {
		$db = $this -> _connect_db();

		$cart = $this -> _cart;

		$order_id = $cart['order_id'];

		unset($cart['order_id']);

		$cart_items = $cart;

		$keys = array_keys($cart);

		if(count($keys)) {
			$ids = implode(',', $keys);
		} else {
			$ids = 0;
		}

		$sql = "
				SELECT br.brand, ct.id, ct.model, ct.photo, ct.price, ct.instock
				FROM (
					SELECT id, brand_id, model, photo, price, instock
					FROM catalog
					WHERE id IN($ids)
					) AS ct
				JOIN (
					SELECT id, brand
					FROM brands
					) AS br
				ON ct.brand_id = br.id
				";

		if (!$stmt = $db -> query($sql)) {
			return false;
		}

		$items = array();

		while ($item = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			//add row with quantity
			$item['quantity'] = $cart_items[$item['id']];
			$items[] = $item;
		}

		$cart_array[$order_id] = $items;

		return $cart_array;
	}

	public function getSum($cart_array) {
		$sum = 0;
		foreach ($cart_array as $order) {
			foreach ($order as $item) {
				$sum += $item['price'] * $item['quantity'];
			}
		}

		return $sum;
	}

	public function clearCart() {
		setcookie('cart', '', time()-3600, '/');
	}
}