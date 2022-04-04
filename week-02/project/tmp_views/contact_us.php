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
<div class="uk-container">
	<form>
		<fieldset class="uk-fieldset">

			<legend class="uk-legend">Legend</legend>

			<div class="uk-margin">
				<input class="uk-input" type="text" placeholder="Input">
			</div>

			<div class="uk-margin">
				<select class="uk-select">
					<option>Option 01</option>
					<option>Option 02</option>
				</select>
			</div>

			<div class="uk-margin">
				<textarea class="uk-textarea" rows="5" placeholder="Textarea"></textarea>
			</div>

			<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
				<label><input class="uk-radio" type="radio" name="radio2" checked> A</label>
				<label><input class="uk-radio" type="radio" name="radio2"> B</label>
			</div>

			<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
				<label><input class="uk-checkbox" type="checkbox" checked> A</label>
				<label><input class="uk-checkbox" type="checkbox"> B</label>
			</div>

			<div class="uk-margin">
				<input class="uk-range" type="range" value="2" min="0" max="10" step="0.1">
			</div>

		</fieldset>
	</form>
	<!-- Source: https://getuikit.com/docs/form -->
</div>
