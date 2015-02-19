<?php 

class UserAdmin extends User implements IOrders {
	protected $_role = 'admin';

	function getOrders($confirmed, $total_orders, $orders_per_page, $cur_page) {
		switch ($confirmed) {
					case 'all':
						$clause = '';
						break;
					case 'notconfirmed':
						$clause = " WHERE confirmed='0' ";
						break;
					case 'confirmed':
						$clause = " WHERE confirmed='1' ";
						break;

					default:
						return false;
						break;
		}


		$first_id = $cur_page * $orders_per_page - $orders_per_page;

		//checking amount of orders on current page
		if (($total_orders - ($cur_page * $orders_per_page)) < 0) {
			$orders_per_page = $total_orders - (($cur_page - 1) * $orders_per_page);
		}

		$sql = "
				SELECT order_id, user_id, sum, date, confirmed
				FROM orders
				$clause
				ORDER by date DESC
				LIMIT $first_id,$orders_per_page
			";

		if (!$stmt = $this -> _db -> query($sql)) {
			return false;
		}

		$orders = array();
		
		while ($order = $stmt -> fetch(PDO::FETCH_ASSOC)) {
			if ($order['user_id'] == 0) {
				$order['user_id'] = 'unregistered';
			}
			$orders[] = $order;
		}
		
		return $orders;
	}

	function getOrder($order_id) {
		$sql_order_info = "
							SELECT order_id, user_id, name, email, telephone, address, sum, date, confirmed
							FROM orders
							WHERE order_id='$order_id'
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

		if ($order_info['user_id'] == 0) {
			$order_info['user_id'] = 'unregistered';
		}

		$order = array(
						'order' => $order_info
					);

		return $order;

	}

	function confirmOrder($order_id) {
		$sql_ordered_items_info = "
						SELECT item_id, quantity
						FROM ordered_items
						WHERE order_id='$order_id'
					";

		$stmt_ordered_items_info = $this -> _db -> query($sql_ordered_items_info);

		$ordered_items_info = array();
		while ($item = $stmt_ordered_items_info -> fetch(PDO::FETCH_ASSOC)) {
			$ordered_items_info[$item['item_id']] = array('quantity' => $item['quantity']);
		}

		$ids_array = array_keys($ordered_items_info);
		$ids = implode(', ', $ids_array);

		$sql_items = "
						SELECT br.brand, ct.id, ct.model, ct.instock
						FROM (
							SELECT id, brand_id, model, photo, price, instock
							FROM catalog
							WHERE id
							IN ($ids)
							) AS ct
						JOIN (
							SELECT id, brand
							FROM brands
							) AS br
						ON ct.brand_id = br.id
					";

		$stmt_items = $this -> _db -> query($sql_items);

		$ordered_items = array();
		while ($item = $stmt_items -> fetch(PDO::FETCH_ASSOC)) {
			if (($item['instock'] - $ordered_items_info[$item['id']]['quantity']) < 0) {
				throw new Exception("Not enough " . $item['brand'] . " " . $item['model'] . " in stock");
			}
			$item['instock'] = $item['instock'] - $ordered_items_info[$item['id']]['quantity'];
			$ordered_items[] = $item;
		}

		try {
			$this -> _db -> beginTransaction();

			$sql_orders = "
							UPDATE orders
							SET confirmed= '1'
							WHERE order_id='$order_id'
						";

			$this -> _db -> exec($sql_orders);

			foreach ($ordered_items as $item) {
				$instock = $item['instock'];
				$item_id = $item['id'];
				$sql_catalog_items = "
										UPDATE catalog
										SET instock='$instock'
										WHERE id='$item_id'
									";
				$this -> _db -> exec($sql_catalog_items);
			}

			$this -> _db -> commit();

		} catch (PDOException $e) {
			$this -> _db -> rollBack();
			throw new Exception("Something went wrong. Please try again later.");
		}


	}

	function delOrder($order_id) {
		$sql_orders = "
						DELETE FROM orders
						WHERE order_id='$order_id'
					";

		$sql_ordered_items = "
								DELETE FROM ordered_items
								WHERE order_id='$order_id'
							";

		try {
			$this -> _db -> beginTransaction();

			$this -> _db -> exec($sql_orders);

			$this -> _db -> exec($sql_ordered_items);

			$this -> _db -> commit();

		} catch (PDOException $e) {
			$this -> _db -> rollBack();
			throw new Exception("Something went wrong. Please try again later.");
		}
	}


	function countOrders($confirmed) {

		switch ($confirmed) {
			case 'all':
				$clause = '';
				break;
			case 'notconfirmed':
				$clause = " WHERE confirmed='0'";
				break;
			case 'confirmed':
				$clause = " WHERE confirmed='1'";
				break;

			default:
				return false;
				break;
		}

		$sql = "SELECT count(id)
				FROM orders
				$clause
				";

		if (!$stmt = $this -> _db -> query($sql)) {
			return false;
		}

		$stmt = $stmt -> fetch(PDO::FETCH_ASSOC);

		$orders = $stmt['count(id)'];

		return $orders;
	}

	function addCategory($category) {
		$valid_category = Validator::validateCategory($category);

		
		if(!self::addToDb('categories', $valid_category)) {
			throw new Exception('Adding operation failed!');
		}
	}

	function addBrand($brand) {

		$valid_brand = Validator::validateBrand($brand);

		if(!self::addToDb('brands', $valid_brand)) {
			throw new Exception('Adding operation failed!');
		}
	}

	function addItem($params) {

		$valid_params = Validator::validateItemParams($params);

		$upload_dir = $params['upload_dir'];
		$upload_file = $upload_dir . $params['photo']['name'];

		if (!self::makeImageSquare($params['photo'])) {
			throw new Exception('Resize operation failed!');
		}

		if (self::uploadFile($params['photo']['tmp_name'], $upload_file )) {
			if (!self::addToDb('catalog', $valid_params)) {
				//delete alredy aploaded file if ading to DB failed
				unlink($upload_file);
				throw new Exception('Adding operation failed!');
			}
		} else {
			throw new Exception('File uploading failed');
		}
	}

	function uploadFile($tmp_name, $upload_file) {
		if (!move_uploaded_file($tmp_name, $upload_file)) {
			return false;
		}
		return true;
	}

	function makeImageSquare($file) {
		$tmp_file = $file['tmp_name'];

		$image_orig = imagecreatefromjpeg($tmp_file);

		$width_orig = imagesx($image_orig);
		$height_orig = imagesy($image_orig);

		if ($height_orig > $width_orig || $height_orig < $width_orig) {
			
			if ($height_orig > $width_orig) {;
				$height_new = $height_orig;
				$width_new = $height_orig;

				$dst_x = ceil(($width_new - $width_orig) / 2);
				$dst_y = 0;
			}
			if ($height_orig < $width_orig) {
				$width_new = $width_orig;
				$height_new = $width_orig;

				$dst_x = 0;
				$dst_y = ceil(($height_new - $height_orig) / 2);
			}

			$new_image = imagecreatetruecolor($width_new,$height_new); 

			$white = imagecolorallocate($new_image, 255, 255, 255);

			imagefill($new_image, 0, 0, $white);

			imagecopyresized($new_image, $image_orig, $dst_x, $dst_y, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);

			if (!imagejpeg($new_image, $tmp_file)) {
				return false;
			}
		}

		return true;

	}

}