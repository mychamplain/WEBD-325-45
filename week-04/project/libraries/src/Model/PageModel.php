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
class PageModel implements DatabaseModelInterface
{
	use DatabaseModelTrait;

	/**
	 * Array of legal pages
	 *
	 * @var array
	 */
	private $legalPages = ['products', 'blog', 'about-us', 'location', 'contact-us'];

	/**
	 * Array of legal details pages
	 *
	 * @var array
	 */
	private $legalDetailsPages = ['yachts', 'ski-boats', 'drones'];

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
	 * Get a page's data
	 *
	 * @param   string  $pageName  The page to lookup
	 *
	 * @return  string
	 *
	 * @throws  \RuntimeException
	 */
	public function getPage(string $pageName): string
	{
		if (!in_array($pageName, $this->legalPages))
		{
			throw new \RuntimeException(sprintf('Unable to find page data for the `%s`', $pageName), 404);
		}

		return $pageName;
	}

	/**
	 * Get a page's details data
	 *
	 * @param   string  $detailsName  The page details to lookup
	 *
	 * @return  string
	 *
	 * @throws  \RuntimeException
	 */
	public function getDetails(string $detailsName): string
	{
		if (strlen($detailsName) && !in_array($detailsName, $this->legalDetailsPages))
		{
			throw new \RuntimeException(sprintf('Unable to find page details data for the `%s`', $detailsName), 404);
		}

		return $detailsName;
	}
}
