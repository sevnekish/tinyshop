<?php 

interface IUser {

	public function getRole();

	public function getEmail();

	public function getParams();

	public function getItems($category_id, $sort, $total_items, $items_per_page, $cur_page);

	public function getItem($item_id);

	public function getReviews($total_reviews, $reviews_per_page, $cur_page);

	public function addReview($params);

	public function logout();

}