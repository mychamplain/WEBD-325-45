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

use Joomla\Application\AbstractApplication;
use Joomla\Controller\AbstractController;
use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Controller handling the site's homepage
 *
 * @method         \Octoleo\CMS\Application\SiteApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\SiteApplication  $app              Application object
 */
class HomepageController extends AbstractController
{
	/**
	 * The template renderer.
	 *
	 * @var  RendererInterface
	 */
	private $renderer;

	/**
	 * Constructor.
	 *
	 * @param   RendererInterface    $renderer  The template renderer.
	 * @param   Input                $input     The input object.
	 * @param   AbstractApplication  $app       The application object.
	 */
	public function __construct(RendererInterface $renderer, Input $input = null, AbstractApplication $app = null)
	{
		parent::__construct($input, $app);

		$this->renderer = $renderer;
	}

	/**
	 * Execute the controller.
	 *
	 * @return  boolean
	 */
	public function execute(): bool
	{
		// Enable browser caching
		$this->getApplication()->allowCache(true);

		$this->getApplication()->setResponse(new HtmlResponse($this->renderer->render('homepage.twig')));

		return true;
	}
}
