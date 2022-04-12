<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\User;

use Joomla\Registry\Registry;
use Joomla\Database\DatabaseInterface;
use Octoleo\CMS\Factory;

/**
 * User class.  Handles all application interaction with a user
 *
 * @since  1.0.0
 */
class User extends Registry
{
	/**
	 * Constructor activating the default information of the language
	 *
	 * @param   integer  $identifier  The primary key of the user to load (optional).
	 *
	 * @throws \Exception
	 * @since   1.1.0
	 */
	public function __construct($identifier = 0)
	{
		// Load the user if it exists
		if (!empty($identifier))
		{
			$data = $this->load($identifier);
			// not a guest
			$data->guest = 0;
		}
		else
		{
			// Initialise guest
			$data = (object) ['id' => 0, 'sendEmail' => 0, 'aid' => 0, 'guest' => 1];
		}
		// set the data
		parent::__construct($data);
	}

	/**
	 * Method to load a User object by user id number
	 *
	 * @param   int  $id  The user id of the user to load
	 *
	 * @return  Object  on success
	 *
	 * @throws \Exception
	 * @since   1.0.0
	 */
	protected function load( int $id): object
	{
		// Get the database
		$db = Factory::getContainer()->get(DatabaseInterface::class);

		// Initialise some variables
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__users'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $id)
			->setLimit(1);
		$db->setQuery($query);

		return $db->loadObject();
	}
}
