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
use Octoleo\CMS\Date\Date;
use Octoleo\CMS\Factory;
use Octoleo\CMS\Filter\InputFilter;
use Octoleo\CMS\Model\ItemModel;
use Octoleo\CMS\User\UserFactoryInterface;
use Octoleo\CMS\View\Admin\ItemHtmlView;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Controller handling the site's dashboard
 *
 * @method         \Octoleo\CMS\Application\AdminApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\AdminApplication  $app              Application object
 */
class ItemController extends AbstractController implements AccessInterface, CheckTokenInterface
{
	use AccessTrait, CheckTokenTrait;

	/**
	 * The view object.
	 *
	 * @var  ItemHtmlView
	 */
	private $view;

	/**
	 * The model object.
	 *
	 * @var  ItemModel
	 */
	private $model;

	/**
	 * @var InputFilter
	 */
	private $inputFilter;

	/**
	 * Constructor.
	 *
	 * @param   ItemModel            $model  The model object.
	 * @param   ItemHtmlView         $view   The view object.
	 * @param   Input                $input  The input object.
	 * @param   AbstractApplication  $app    The application object.
	 */
	public function __construct(ItemModel $model, $view, Input $input = null, AbstractApplication $app = null)
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
			if ($id > 0 && $this->model->linked($id))
			{
				$this->getApplication()->enqueueMessage('This item is still linked to a menu, first remove it from the menu.', 'error');
			}
			elseif ($id > 0 && $this->model->delete($id))
			{
				$this->getApplication()->enqueueMessage('Item was deleted!', 'success');
			}
			else
			{
				$this->getApplication()->enqueueMessage('Item could not be deleted!', 'error');
			}
			// go to set page
			$this->_redirect('items');

			return true;
		}

		if ('POST' === $method)
		{
			$id = $this->setItem();
		}

		$this->view->setActiveId($id);
		$this->view->setActiveView('item');

		// check if user is allowed to access
		if ($this->allow('item'))
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
		$tempItem['id'] = $post->getInt('item_id', 0);
		$tempItem['title'] = $post->getString('title', '');
		$tempItem['fulltext'] = $this->inputFilter->clean($post->getRaw('fulltext', ''), 'html');
		$tempItem['created_by_alias']  = $post->getString('created_by_alias', '');
		$tempItem['state'] = $post->getInt('state', 1);
		$tempItem['metakey'] = $post->getString('metakey', '');
		$tempItem['metadesc'] = $post->getString('metadesc', '');
		$tempItem['metadata'] = $post->getString('metadata', '');
		$tempItem['publish_up'] = $post->getString('publish_up', '');
		$tempItem['publish_down'] = $post->getString('publish_down', '');
		$tempItem['featured']  = $post->getInt('featured', 0);

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
		if (empty($tempItem['fulltext']))
		{
			// we show a warning message
			$tempItem['fulltext'] = '';
			$this->getApplication()->enqueueMessage('Content field is required.', 'error');
			$can_save = false;
		}
		// can we save the item
		if ($can_save)
		{
			/** @var \Octoleo\CMS\User\User $user */
			$user = Factory::getContainer()->get(UserFactoryInterface::class)->getUser();

			$user_id = (int) $user->get('id', 0);
			$today = (new Date())->toSql();

			return $this->model->setItem(
				$tempItem['id'],
				$tempItem['title'],
				$tempItem['fulltext'],
				$tempItem['state'],
				$today,
				$user_id,
				$tempItem['created_by_alias'],
				$today,
				$user_id,
				$tempItem['publish_up'],
				$tempItem['publish_down'],
				$tempItem['metakey'],
				$tempItem['metadesc'],
				$tempItem['metadata'],
				$tempItem['featured']);
		}

		// add to model the post values
		$this->model->tempItem = $tempItem;

		return $tempItem['id'];
	}
}
