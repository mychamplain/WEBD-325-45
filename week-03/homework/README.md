# Movie Class

> We have a basic interface

```php
// the movie interface
interface movieInterface
{
	// get the cost in money notation
	function getCost(): string;
}
```

> With a basic class that implement that interface so we can be sure we can call that class method `getCost()`.

```php
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
```

> We get the age from the global POST array:

```php
$age = (isset($_POST['age']) && is_numeric($_POST['age'])) ? (int) $_POST['age'] : null;
```

> Once we have the age submitted in the form we simple instantiate the class with the age and then call the `getCost()` method like this:

```php
<h2>Movie Cost is: <b><?php echo (new Movie($age))->getCost(); ?></b></h2>
```

