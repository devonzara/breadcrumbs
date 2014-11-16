<?php namespace App\Http\Controllers;

/**
 * @Ancestor("users", only={"filteredUsers", "profile"})
 */
class OnlyController {

	/**
	 * @Crumb("home")
	 */
	public function index() {}

	/**
	 * @Crumb({"users", "Users"}, ancestor="home")
	 */
	public function users() {}

	/**
	 * @Crumb({"filtered.users", "Search Results"}, ancestor="home")
	 */
	public function filteredUsers() {}

	/**
	 * @Crumb({"profile", "{username}'s Profile"})
	 */
	public function profile($username) {}

}
