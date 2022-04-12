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
class DashboardModel implements DatabaseModelInterface
{
	use DatabaseModelTrait;

	/**
	 * Array of legal dashboards
	 *
	 * @var array
	 */
	private $legalDashboards = [
		'items' => 'items.twig',
		'item' => 'edit.twig',
		'menus' => 'menus.twig',
		'menu' => 'edit.twig',
		'users' => 'users.twig',
		'user' => 'edit.twig'
	];

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
	 * Get a active dashboard template name
	 *
	 * @param   string  $dashboardName  The dashboard to lookup
	 *
	 * @return  string
	 *
	 */
	public function getDashboard(string $dashboardName): string
	{
		if (!isset($this->legalDashboards[$dashboardName]))
		{
			return 'dashboard.twig';
		}

		return $this->legalDashboards[$dashboardName];
	}
}
