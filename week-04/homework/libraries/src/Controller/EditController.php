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
use Joomla\Filter\InputFilter as InputFilterAlias;
use Joomla\Input\Input;
use Joomla\Uri\Uri;
use Sport\Stars\Application\SportStarsApplication;
use Sport\Stars\Filter\InputFilter;
use Sport\Stars\Model\EditModel;
use Sport\Stars\View\EditHtmlView;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Controller handling the edit area
 *
 * @method         SportStarsApplication  getApplication()  Get the application object.
 * @property-read  SportStarsApplication $app              Application object
 */
class EditController extends AbstractController
{
	/**
	 * The page model object.
	 *
	 * @var  EditModel
	 */
	private $model;

	/**
	 * The view object.
	 *
	 * @var  EditHtmlView
	 */
	private $view;

	/**
	 * Constructor.
	 *
	 * @param   EditModel            $model  The page model object.
	 * @param   EditHtmlView         $view   The view object.
	 * @param   Input                $input  The input object.
	 * @param   AbstractApplication  $app    The application object.
	 */
	public function __construct(EditModel $model, EditHtmlView $view, Input $input = null, AbstractApplication $app = null)
	{
		parent::__construct($input, $app);

		$this->model = $model;
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

		$method = $this->getInput()->getMethod();
		$id = $this->getInput()->getInt('id', 0);
		$task = $this->getInput()->getString('task', '');

		// if task is delete
		if ('delete' === $task)
		{
			// delete the item
			if ($id > 0 && $this->model->delete($id))
			{
				$this->model->enqueueMessage('Item was deleted!', 'success');
			}
			else
			{
				$this->model->enqueueMessage('Item could not be deleted!', 'error');
			}
			// go to set page
			$this->redirect();

			return true;
		}

		if ('POST' === $method)
		{
			$id = $this->setItem();
		}

		$this->view->setActiveItem($id);

		// check if user is allowed to access
		$this->getApplication()->setResponse(new HtmlResponse($this->view->render()));

		return true;
	}

	/**
	 * Set an item
	 *
	 *
	 * @return  int
	 * @throws \Exception
	 */
	protected function setItem(): int
	{
		// get the post
		$post = $this->getInput()->getInputForRequestMethod();

		// we get all the needed items
		$tempItem = [];
		$tempItem['id'] = $post->getInt('item_id', 0);
		$tempItem['name'] = $post->getString('name', '');
		$tempItem['age'] = $post->getInt('age', 0);
		$tempItem['sport'] = $post->getString('sport', '');

		// check that we have a Title
		$can_save = true;
		if (empty($tempItem['name']) || is_numeric($tempItem['name']))
		{
			// we show a warning message
			$tempItem['name'] = '';
			$this->model->enqueueMessage('Name field is required.', 'error');
			$can_save = false;
		}
		// we actually can also not continue if we don't have age
		if (empty($tempItem['age']) || $tempItem['age'] > 120 || $tempItem['age'] < 1)
		{
			// we show a warning message
			$tempItem['age'] = '';
			$this->model->enqueueMessage('Age field is required.', 'error');
			$can_save = false;
		}
		// we actually can also not continue if we don't have sport
		if (empty($tempItem['sport']) || is_numeric($tempItem['sport']))
		{
			// we show a warning message
			$tempItem['sport'] = '';
			$this->model->enqueueMessage('Sport field is required.', 'error');
			$can_save = false;
		}
		// can we save the item
		if ($can_save)
		{
			return $this->model->setItem(
				$tempItem['id'],
				$tempItem['name'],
				$tempItem['age'],
				$tempItem['sport']);
		}

		// add to model the post values
		$this->model->tempItem = $tempItem;

		return $tempItem['id'];
	}

	/**
	 * @param   string|null  $target
	 *
	 * @return void
	 */
	private function redirect()
	{
		// get uri request to get host
		$uri = new Uri($this->getApplication()->get('uri.request'));

		// fix the path
		$path = $uri->getPath();
		$path = substr($path, 0, strripos($path, '/'));

		// redirect to the set area
		$this->getApplication()->redirect($uri->getScheme() . '://' . $uri->getHost() . $path);
	}
}
