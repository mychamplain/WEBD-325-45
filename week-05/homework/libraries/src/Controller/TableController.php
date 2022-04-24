<?php
/**
 * @package    Sport Stars
 *
 * @created    19th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sport\Stars\Controller;

use Exception;
use Joomla\Application\AbstractApplication;
use Joomla\Controller\AbstractController;
use Joomla\Input\Input;
use Sport\Stars\Application\SportStarsApplication;
use Sport\Stars\View\TableHtmlView;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Controller handling the site's dashboard
 *
 * @method         SportStarsApplication  getApplication()  Get the application object.
 * @property-read  SportStarsApplication $app              Application object
 */
class TableController extends AbstractController
{

	/**
	 * The view object.
	 *
	 * @var  TableHtmlView
	 */
	private $view;

	/**
	 * Constructor.
	 *
	 * @param   TableHtmlView        $view   The view object.
	 * @param   Input                $input  The input object.
	 * @param   AbstractApplication  $app    The application object.
	 */
	public function __construct(TableHtmlView $view, Input $input = null, AbstractApplication $app = null)
	{
		parent::__construct($input, $app);

		$this->view = $view;
	}

	/**
	 * Execute the controller.
	 *
	 * @return  boolean
	 * @throws Exception
	 */
	public function execute(): bool
	{
		// Do not Enable browser caching
		$this->getApplication()->allowCache(false);

		// render the table
		$this->getApplication()->setResponse(new HtmlResponse($this->view->render()));

		return true;
	}
}
