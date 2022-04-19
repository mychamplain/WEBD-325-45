<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Model;

use Joomla\Database\DatabaseDriver;
use Joomla\Database\ParameterType;
use Joomla\Model\DatabaseModelInterface;
use Joomla\Model\DatabaseModelTrait;

/**
 * Model class for pages
 * source: https://github.com/joomla/framework.joomla.org/blob/master/src/Model/PackageModel.php
 */
class HomepageModel implements DatabaseModelInterface, MenuInterface, PageInterface
{
	use DatabaseModelTrait, SiteMenuTrait, SitePageTrait;

	/**
	 * Instantiate the model.
	 *
	 * @param   DatabaseDriver  $db  The database adapter.
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->setDb($db);
	}

	/**
	 * @return \stdClass
	 */
	public function getHomePage(): \stdClass
	{
		$db = $this->getDb();

		$query = $db->getQuery(true)
			->select('a.*')
			->from($db->quoteName('#__menu', 'a'))
			->where($db->quoteName('a.parent_id') . ' = 0')
			->where($db->quoteName('a.published') . ' = 1')
			->where($db->quoteName('a.home') . ' = 1')
			->setLimit(1);

		try
		{
			$home = $db->setQuery($query)->loadObject();
		}
		catch (\RuntimeException $e)
		{
			return new \stdClass();
		}

		if ($home)
		{
			return $home;
		}

		return new \stdClass();
	}
}
