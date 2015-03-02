<?php

interface IOrders {

	public function getOrders($confirmed, $total_orders, $orders_per_page, $cur_page);

	public function getOrder($order_id);

	public function countOrders($confirmed);
}