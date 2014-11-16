<?php namespace App\Http\Controllers;

/**
 * @Ancestor("users", except={"home", "filteredUsers"})
 */
class ExceptController {

	/**
	 * @Crumb("home")
	 */
	public function index() {}

	/**
	 * @Crumb({"users", "Users"})
	 */
	public function users() {}

	/**
	 * @Crumb({"filtered.users", "Search Results"}, ancestor="users")
	 */
	public function filteredUsers() {}

	/**
	 * @Crumb({"profile", "{username}'s Profile"}, ancestor="users")
	 */
	public function profile($username) {}

}
