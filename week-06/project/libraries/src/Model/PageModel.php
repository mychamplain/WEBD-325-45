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
use Joomla\Model\DatabaseModelInterface;
use Joomla\Model\DatabaseModelTrait;
use Octoleo\CMS\Model\Util\MenuInterface;
use Octoleo\CMS\Model\Util\PageInterface;
use Octoleo\CMS\Model\Util\HomeMenuInterface;
use Octoleo\CMS\Model\Util\HomeMenuTrait;
use Octoleo\CMS\Model\Util\SiteMenuTrait;
use Octoleo\CMS\Model\Util\SitePageTrait;

/**
 * Model class
 */
class PageModel implements DatabaseModelInterface, MenuInterface, PageInterface, HomeMenuInterface
{
	use DatabaseModelTrait, HomeMenuTrait, SiteMenuTrait, SitePageTrait;

	/**
	 * Instantiate the model.
	 *
	 * @param   DatabaseDriver  $db  The database adapter.
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->setDb($db);
	}
}
