<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2019 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\User;

/**
 * Interface defining a factory which can create User objects
 *
 * @since  1.0.0
 */
interface UserFactoryInterface
{
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
	 * Attempt to authenticate the username and password pair.
	 *
	 * @return  string|boolean  A string containing a username if authentication is successful, false otherwise.
	 *
	 * @since   1.1.0
	 */
	public function authenticate();
}
