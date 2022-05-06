<?php
/**
 * @package    Octoleo CMS
 *
 * @created    21th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\User;

use Exception;

/**
 * Interface defining a factory which can create User objects
 *
 * @since  1.0.0
 */
interface UserFactoryInterface
{
	/**
	 * Method to get an instance of a user for the given id or session.
	 *
	 * @param   int|null  $id  The id
	 *
	 * @return  User
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	public function getUser(?int $id = null): User;

	/**
	 * Method to get an instance of a user for the given id.
	 *
	 * @param   int  $id  The id
	 *
	 * @return  User
	 *
	 * @since   1.0.0
	 */
	public function loadUserById(int $id): User;

	/**
	 * Method to get an instance of a user for the given username.
	 *
	 * @param   string  $username  The username
	 *
	 * @return  User
	 *
	 * @since   1.0.0
	 */
	public function loadUserByUsername(string $username): User;

	/**
	 * Method to get an instance of a user for the session.
	 *
	 * @return  User
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	public function loadUserBySession(): User;

	/**
	 * Check if user is active
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function active(): bool;

	/**
	 * Check if we have users
	 *
	 * @return bool true if we have
	 */
	public function has(): bool;

	/**
	 * Check if a user exist based on give key value pair
	 *
	 * @param   string  $value
	 * @param   string  $key
	 *
	 * @return false|mixed  on success return user ID
	 */
	public function exist(string $value, string $key = 'username');

	/**
	 * Attempt to login user
	 *
	 * @return  boolean  true on success
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	public function login(): bool;

	/**
	 * Logout user
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function logout(): bool;

	/**
	 * Attempt to great user
	 *
	 * @param   string|null  $name
	 * @param   string|null  $username
	 * @param   string|null  $email
	 * @param   string|null  $password
	 * @param   string|null  $password2
	 *
	 * @return  boolean  true on success
	 *
	 * @throws Exception
	 * @since   1.0.0
	 */
	public function create(
		string $name = null,
		string $username = null,
		string $email = null,
		string $password = null,
		string $password2 = null): bool;
}
