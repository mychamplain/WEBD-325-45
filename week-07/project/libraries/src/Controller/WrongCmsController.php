<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Controller;

use Joomla\Controller\AbstractController;
use Laminas\Diactoros\Response\TextResponse;

/**
 * Controller class to display a message to individuals looking for the wrong CMS
 *
 * @method         \Octoleo\CMS\Application\SiteApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\SiteApplication  $app              Application object
 */
class WrongCmsController extends AbstractController
{
	/**
	 * Execute the controller.
	 *
	 * @return  boolean
	 */
	public function execute(): bool
	{
		// Enable browser caching
		$this->getApplication()->allowCache(true);

		$response = new TextResponse("This isn't the what you're looking for.", 404);

		$this->getApplication()->setResponse($response);

		return true;
	}
}
