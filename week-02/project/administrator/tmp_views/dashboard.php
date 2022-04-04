<?php
/**
 * @package    Generic CMS
 *
 * @created    3rd April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_WEBD') or die('Restricted access');

?>
<div class="uk-container uk-margin">
	<h1 class="uk-article-title">Generic CMS Dashboard</h1>
	<div class="uk-grid-small uk-child-width-expand@s uk-text-center" uk-grid>
		<div>
			<div class="uk-card uk-card-default uk-card-body"><a href="?view=users">Users</a></div>
		</div>
		<div>
			<div class="uk-card uk-card-default uk-card-body"><a href="?view=menus">Menus</a></div>
		</div>
		<div>
			<div class="uk-card uk-card-default uk-card-body"><a href="?view=items">Items</a></div>
		</div>
	</div>
</div>