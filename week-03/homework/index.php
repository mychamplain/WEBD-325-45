<?php
/**
 * @package    Movie Class
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/champlain/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// the movie interface
interface movieInterface
{
	// get the cost in money notation
	function getCost(): string;
}

// the movie class
class Movie implements movieInterface
{
	/**
	 * The moviegoer's age
	 *
	 * @var    int
	 * @since  1.0.0
	 */
	private $age;

	/**
	 * The currency symbol
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $currency = '';

	/**
	 * The movie prices
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	protected $prices;

	/**
	 * Constructor
	 *
	 * @param   int     $age       moviegoer's age
	 * @param   array   $prices    the prices of the movies
	 * @param   string  $currency  the current currency
	 *
	 * @since  1.0.0
	 */
	public function __construct(int $age, array $prices = [5 => 0, 17 => 5, 55 => 10], string $currency = '$')
	{
		// we set the age
		$this->age = $age;

		// The default prices
		//----------------------
		// Under 5	Free
		// 5 to 17	Half Price
		// 18 to 55	Full Price
		// Over 55	$2 off
		$this->prices = $prices;

		// set the currency symbol
		$this->currency = $currency;
	}

	/**
	 * Get the cost of this movie goer
	 *
	 * @return  string  the cost value
	 * @since  1.0.0
	 *
	 */
	public function getCost(): string
	{
		return $this->currency . number_format($this->getPrice(), 2);
	}

	/**
	 * Get the price of a movie by age
	 *
	 * @return int the movie price
	 */
	protected function getPrice(): int
	{
		foreach ($this->prices as $age => $cost)
		{
			if ($this->age <= $age)
			{
				return $cost;
			}
		}

		return 8;
	}
}
// check if we have an age value
$age = (isset($_POST['age']) && is_numeric($_POST['age'])) ? (int) $_POST['age'] : null;

?><html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Movie Cost Calculator</title>
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
	<![endif]-->
	<!-- UIkit CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.13.7/dist/css/uikit.min.css" />
	<!-- UIkit JS -->
	<script src="https://cdn.jsdelivr.net/npm/uikit@3.13.7/dist/js/uikit.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/uikit@3.13.7/dist/js/uikit-icons.min.js"></script>
</head>
<body>
<?php if ($age > 0) : ?>
<div class="uk-container">
	<h2>Movie Cost is: <b><?php echo (new Movie($age))->getCost(); ?></b></h2>
	<form method="post">
		<fieldset class="uk-fieldset">
			<input class="uk-button" type="submit" value="Try Another"/>
		</fieldset>
	</form>
<?php else: ?>
<div class="uk-container">
	<h2>Movie Cost Calculator</h2>
	<form method="post">
		<fieldset class="uk-fieldset">
			<div class="uk-margin">
				<label>
					<input name="age" class="uk-input" type="text" placeholder="Enter Your Age">
				</label>
			</div>
			<input class="uk-button" type="submit" value="Calculate"/>
		</fieldset>
	</form>
</div>
<?php endif; ?>
</body>
</html>
