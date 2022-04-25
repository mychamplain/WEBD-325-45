<?php
/**
 * @package    Change Calculator
 *
 * @created    24th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Change\Calculator\Controller;

use Exception;
use Joomla\Application\AbstractApplication;
use Joomla\Controller\AbstractController;
use Joomla\Input\Input;
use Change\Calculator\Application\ChangeCalculatorApplication;
use Change\Calculator\View\CalculatorHtmlView;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Controller handling the site's dashboard
 *
 * @method         ChangeCalculatorApplication  getApplication()  Get the application object.
 * @property-read  ChangeCalculatorApplication $app              Application object
 */
class CalculatorController extends AbstractController
{

	/**
	 * The view object.
	 *
	 * @var  CalculatorHtmlView
	 */
	private $view;

	/**
	 * Constructor.
	 *
	 * @param   CalculatorHtmlView        $view   The view object.
	 * @param   Input|null                $input  The input object.
	 * @param   AbstractApplication|null  $app    The application object.
	 */
	public function __construct(CalculatorHtmlView $view, Input $input = null, AbstractApplication $app = null)
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

		// get the input
		$this->view->setCost($this->getInput()->getFloat('cost', 0.00));
		$this->view->setPayment($this->getInput()->getFloat('payment', 0.00));

		// render the table
		$this->getApplication()->setResponse(new HtmlResponse($this->view->render()));

		return true;
	}
}
