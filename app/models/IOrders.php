<?php

interface IOrders {

	function getOrders($confirmed, $total_orders, $orders_per_page, $cur_page);

	function getOrder($order_id);

	function countOrders($confirmed);
}