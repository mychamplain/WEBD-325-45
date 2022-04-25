<?php
/**
 * Joomla! Framework Website
 *
 * @copyright  Copyright (C) 2014 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Change\Calculator\Renderer;

use InvalidArgumentException;
use Joomla\Application\AbstractApplication;
use Joomla\Preload\PreloadManager;

/**
 * Twig runtime class
 * source: https://github.com/joomla/framework.joomla.org/blob/master/src/Renderer/FrameworkTwigRuntime.php
 */
class FrameworkTwigRuntime
{
	/**
	 * Application object
	 *
	 * @var  AbstractApplication
	 */
	private $app;

	/**
	 * The HTTP/2 preload manager
	 *
	 * @var  PreloadManager
	 */
	private $preloadManager;

	/**
	 * Constructor
	 *
	 * @param   AbstractApplication    $app              The application object
	 * @param   PreloadManager         $preloadManager   The HTTP/2 preload manager
	 */
	public function __construct(AbstractApplication $app, PreloadManager $preloadManager)
	{
		$this->app             = $app;
		$this->preloadManager  = $preloadManager;
	}

	/**
	 * Retrieves the current URI
	 *
	 * @return  string
	 */
	public function getRequestUri(): string
	{
		return $this->app->get('uri.request');
	}

	/**
	 * Get the URI for a route
	 *
	 * @param   string  $route  Route to get the path for
	 *
	 * @return  string
	 */
	public function getRouteUri(string $route = ''): string
	{
		return $this->app->get('uri.base.path') . $route;
	}

	/**
	 * Get the full URL for a route
	 *
	 * @param   string  $route  Route to get the URL for
	 *
	 * @return  string
	 */
	public function getRouteUrl(string $route = ''): string
	{
		return $this->app->get('uri.base.host') . $this->getRouteUri($route);
	}

	/**
	 * Shorten a string
	 *
	 * @input	string   The you would like to shorten
	 *
	 * @returns string on success
	 *
	 * @since  1.0.0
	 */
	public function shortenString($string, $length = 100)
	{
		if (is_string($string) && strlen($string) > $length)
		{
			$words = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
			$words_count = count((array)$words);

			$word_length = 0;
			$last_word = 0;
			for (; $last_word < $words_count; ++$last_word)
			{
				$word_length += strlen($words[$last_word]);
				if ($word_length > $length)
				{
					break;
				}
			}

			$newString	= implode(array_slice($words, 0, $last_word));
			return trim($newString) . '...';
		}
		return $string;
	}

	/**
	 * Get any messages in the queue
	 *
	 * @return  array
	 */
	public function getMessageQueue(): array
	{
		if (method_exists($this->app, 'getMessageQueue'))
		{
			return $this->app->getMessageQueue(true);
		}
		return [];
	}

	/**
	 * Preload a resource
	 *
	 * @param   string  $uri         The URI for the resource to preload
	 * @param   string  $linkType    The preload method to apply
	 * @param   array   $attributes  The attributes of this link (e.g. "array('as' => true)", "array('pr' => 0.5)")
	 *
	 * @return  string
	 *
	 * @throws  InvalidArgumentException
	 */
	public function preloadAsset(string $uri, string $linkType = 'preload', array $attributes = []): string
	{
		$this->preloadManager->link($uri, $linkType, $attributes);

		return $uri;
	}
}