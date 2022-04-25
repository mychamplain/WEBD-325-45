<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Application;

/**
 * Application Session Message Aware Interface
 *
 * @since  1.0.0
 */
interface  SessionMessageAwareInterface
{
	const MSG_INFO = 'info';

	/**
	 * Enqueue a system message.
	 *
	 * @param   string  $msg   The message to enqueue.
	 * @param   string  $type  The message type. Default is message.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function enqueueMessage(string $msg, string $type = self::MSG_INFO);

	/**
	 * Get the system message queue.
	 *
	 * @param   boolean  $clear  Clear the messages currently attached to the application object
	 *
	 * @return  array  The system message queue.
	 *
	 * @since   3.2
	 */
	public function getMessageQueue(bool $clear = false): array;
}
