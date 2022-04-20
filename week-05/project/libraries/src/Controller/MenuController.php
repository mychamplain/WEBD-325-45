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
use Joomla\Filter\InputFilter as InputFilterAlias;
use Joomla\Input\Input;
use Octoleo\CMS\Filter\InputFilter;
use Octoleo\CMS\Model\MenuModel;
use Laminas\Diactoros\Response\HtmlResponse;
use Octoleo\CMS\View\Admin\MenuHtmlView;

/**
 * Controller handling the site's dashboard
 *
 * @method         \Octoleo\CMS\Application\AdminApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\AdminApplication  $app              Application object
 */
class MenuController extends AbstractController implements AccessInterface, CheckTokenInterface
{
	use AccessTrait, CheckTokenTrait;

	/**
	 * The view object.
	 *
	 * @var  MenuHtmlView
	 */
	private $view;

	/**
	 * The model object.
	 *
	 * @var  MenuModel
	 */
	private $model;

	/**
	 * @var InputFilter
	 */
	private $inputFilter;

	/**
	 * Constructor.
	 *
	 * @param   MenuModel            $model  The model object.
	 * @param   MenuHtmlView         $view   The view object.
	 * @param   Input                $input  The input object.
	 * @param   AbstractApplication  $app    The application object.
	 */
	public function __construct(MenuModel $model, MenuHtmlView $view, Input $input = null, AbstractApplication $app = null)
	{
		parent::__construct($input, $app);

		$this->model = $model;
		$this->view = $view;
		$this->inputFilter = InputFilter::getInstance(
			[],
			[],
			InputFilterAlias::ONLY_BLOCK_DEFINED_TAGS,
			InputFilterAlias::ONLY_BLOCK_DEFINED_ATTRIBUTES
		);
	}

	/**
	 * Execute the controller.
	 *
	 * @return  boolean
	 * @throws \Exception
	 */
	public function execute(): bool
	{
		// Do not Enable browser caching
		$this->getApplication()->allowCache(false);

		$method = $this->getInput()->getMethod();
		$task = $this->getInput()->getString('task', '');
		$id = $this->getInput()->getInt('id', 0);

		// if task is delete
		if ('delete' === $task)
		{
			if ($this->model->delete($id))
			{
				$this->getApplication()->enqueueMessage('Menu was deleted!', 'success');
			}
			else
			{
				$this->getApplication()->enqueueMessage('Menu could not be deleted!', 'error');
			}
			// go to set page
			$this->_redirect('menus');

			return true;
		}

		if ('POST' === $method)
		{
			$id = $this->setItem();
		}

		$this->view->setActiveId($id);
		$this->view->setActiveView('menu');

		// check if user is allowed to access
		if ($this->allow('menu'))
		{
			$this->getApplication()->setResponse(new HtmlResponse($this->view->render()));
		}
		else
		{
			// go to set page
			$this->_redirect();
		}

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
		// always check the post token
		$this->checkToken();
		// get the post
		$post = $this->getInput()->getInputForRequestMethod();

		// we get all the needed items
		$tempItem = [];
		$tempItem['id'] = $post->getInt('menu_id', 0);
		$tempItem['title'] = $post->getString('title', '');
		$tempItem['alias'] = $post->getString('alias', '');
		$tempItem['path']  = $post->getString('path', '');
		$tempItem['item_id'] = $post->getInt('item_id', 0);
		$tempItem['published'] = $post->getInt('published', 1);
		$tempItem['publish_up'] = $post->getString('publish_up', '');
		$tempItem['publish_down'] = $post->getString('publish_down', '');
		$tempItem['position'] = $post->getString('position', 'center');
		$tempItem['home']  = $post->getInt('home', 0);

		// check that we have a Title
		$can_save = true;
		if (empty($tempItem['title']))
		{
			// we show a warning message
			$tempItem['title'] = '';
			$this->getApplication()->enqueueMessage('Title field is required.', 'error');
			$can_save = false;
		}
		// we actually can also not continue if we don't have content
		if (empty($tempItem['item_id']) || $tempItem['item_id'] == 0)
		{
			// we show a warning message
			$tempItem['item_id'] = 0;
			$this->getApplication()->enqueueMessage('Item field is required.', 'error');
			$can_save = false;
		}

		// can we save the item
		if ($can_save)
		{
			return $this->model->setItem(
				$tempItem['id'],
				$tempItem['title'],
				$tempItem['alias'],
				$tempItem['item_id'],
				$tempItem['path'],
				$tempItem['published'],
				$tempItem['publish_up'],
				$tempItem['publish_down'],
				$tempItem['position'],
				$tempItem['home']);
		}

		// add to model the post values
		$this->model->tempItem = $tempItem;

		return $tempItem['id'];
	}
}
