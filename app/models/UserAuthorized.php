<?php 

class UserAuthorized extends User implements IOrders{
	protected $_role = 'user';

	public function registrateNewUser() {}


	public function getOrders($confirmed = null, $total_orders = null, $orders_per_page = null, $cur_page = null) {
		$user_id = Validator::find_matches('users', 'email', $this -> _email);

		$sql = "SELECT order_id, sum, date, confirmed
				FROM orders
				WHERE user_id='$user_id'
				";

		if (!$stmt = $this -> _db -> query($sql)) {
			return false;
		}

		$orders = array();

		while ($order = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			$orders[] = $order;
		}

		return $orders;
	}

	public function getOrder($order_id) {
		$user_id = Validator::find_matches('users', 'email', $this -> _email);

		$sql_order_info = "
							SELECT order_id, name, email, telephone, address, sum, date, confirmed
							FROM orders
							WHERE order_id='$order_id'
							AND user_id='$user_id'
							";

		if (!$stmt = $this -> _db -> query($sql_order_info)) {
			return false;
		}

		$order_info = array();

		$order_info = $stmt -> fetch(PDO::FETCH_ASSOC);

		$sql_ordered_items = "
							SELECT br.brand, ct.model, ct.id, ct.photo, ot.old_price, ot.quantity
							FROM (
								SELECT item_id, old_price, quantity
								FROM ordered_items
								WHERE order_id='$order_id'
								) AS ot
							JOIN (
								SELECT brand_id, model, id, photo
								FROM catalog
								) AS ct
							ON ot.item_id = ct.id
							JOIN (
								SELECT id, brand
								FROM brands
								) AS br
							ON ct.brand_id = br.id
							";

		if (!$stmt = $this -> _db -> query($sql_ordered_items)) {
			return false;
		}

		$ordered_items = array();
		while ($item = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			$ordered_items[] = $item;
		}

		$order_info['ordered_items'] = $ordered_items;

		$order = array(
						'order' => $order_info
					);

		return $order;

	}

	public function countOrders($confirmed = 'all') {
		$user_id = Validator::find_matches('users', 'email', $this -> _email);

		switch ($confirmed) {
			case 'all':
				$clause = '';
				break;
			case 'notconfirmed':
				$clause = " confirmed='0' AND ";
				break;
			case 'confirmed':
				$clause = " confirmed='1' AND ";
				break;

			default:
				return false;
				break;
		}

		$sql = "SELECT count(id)
				FROM orders
				WHERE $clause
				user_id='$user_id'
				";

		if (!$stmt = $this -> _db -> query($sql)) {
			return false;
		}

		$stmt = $stmt -> fetch(PDO::FETCH_ASSOC);

		$orders = $stmt['count(id)'];

		return $orders;
	}
}