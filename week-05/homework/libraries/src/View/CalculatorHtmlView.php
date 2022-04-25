<?php
/**
 * @package    Change Calculator
 *
 * @created    24th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Change\Calculator\View;

use Change\Calculator\Model\CalculatorModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;

/**
 * Page HTML view class for the application
 */
class CalculatorHtmlView extends HtmlView
{
	/**
	 * The table model object.
	 *
	 * @var  CalculatorModel
	 */
	private $model;

	/**
	 * The payment amount
	 *
	 * @var  float
	 */
	private $payment;

	/**
	 * The cost amount
	 *
	 * @var  float
	 */
	private $cost;

	/**
	 * Instantiate the view.
	 *
	 * @param   CalculatorModel       $model     The calculator model object.
	 * @param   RendererInterface     $renderer  The renderer object.
	 */
	public function __construct(CalculatorModel $model, RendererInterface $renderer)
	{
		parent::__construct($renderer);

		$this->model      = $model;
	}

	/**
	 * Method to render the view
	 *
	 * @return  string  The rendered view
	 */
	public function render(): string
	{
		// start the data bucket
		$data = [];
		// only load the change if there is any
		$data['change'] = $this->model->getChange($this->cost, $this->payment);
		// if we still have from values we load them back
		$data['form'] = $this->model->getFormValues();
		// add any messages we may have in the model
		$data['messages_queue'] = $this->model->getMessageQueue();

		// now set the data
		$this->setData($data);

		return parent::render();
	}

	/**
	 * Method to set the cost
	 *
	 * @param   float  $cost  The cost amount
	 *
	 * @return void
	 */
	public function setCost(float $cost)
	{
		$this->cost = $cost;
	}

	/**
	 * Method to set the payment
	 *
	 * @param   float  $payment  The payment amount
	 *
	 * @return void
	 */
	public function setPayment(float $payment)
	{
		$this->payment = $payment;
	}
}
