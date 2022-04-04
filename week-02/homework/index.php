<?php
/**
 * @package    PHP Scripts
 *
 * @created    3rd April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * To print out the whole factorial sum
 *
 * @param   int  $n
 *
 * @return mixed
 *
 * @since 1.0
 */
function factorialPrint(int $n)
{
	static $r;
	if ($n > 0)
	{
		$r[] = $n;
		return factorialPrint(--$n);
	}
	return 'RESULT: ' . implode(' x ', array_reverse($r)) . ' = ' . array_product($r);
}

/**
 * Simple recursive factorial function
 *
 * @param   int  $n
 *
 * @return int
 *
 * @since 1.0
 */
function factorial(int $n): int
{
	if ($n > 1)
	{
		return $n * factorial($n - 1);
	}
	else
	{
		return 1;
	}
}

/**
 * Show a range of factorials
 *
 * @param   int  $n
 *
 * @return mixed
 *
 * @since 1.0
 */
function rangeFactorials(int $n)
{
	// show the factorial
	echo "RESULT: factorial for $n = " . factorial($n) . '<br />';
	// check if we are done
	if ($n > 0)
	{
		return rangeFactorials(--$n);
	}
	return true;
}

?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Date & Factorials - Homework - PHP Scripts</title>

	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
	<![endif]-->
</head>
<body>
<?php
	// Here is a single line of code to print out the date in the following mm/dd/yyyy format.
	$day = 23; $month = 10; $year = 2021; printf("%s", date('m/d/Y', strtotime("$day-$month-$year")));
	echo '<br /><br />';

	// simple factorial option in PHP (does not work for zero)
	echo 'RESULT: factorial for 9 = ' . array_product(range(1, 9)) . '<br />';
	// RESULT: factorial for 9 = 362880

	// get the factorial of 5
	echo factorialPrint(5) . '<br />';
	// RESULT: 1 x 2 x 3 x 4 x 5 = 120

	// get the factorial of 10
	echo 'RESULT: factorial for 10 = ' . factorial(10) . '<br /><br />';
	// RESULT: factorial for 10 = 3628800

	// Show all factorials for 0 - 10
	rangeFactorials(10);
	// RESULT: factorial for 10 = 3628800
	// RESULT: factorial for 9 = 362880
	// RESULT: factorial for 8 = 40320
	// RESULT: factorial for 7 = 5040
	// RESULT: factorial for 6 = 720
	// RESULT: factorial for 5 = 120
	// RESULT: factorial for 4 = 24
	// RESULT: factorial for 3 = 6
	// RESULT: factorial for 2 = 2
	// RESULT: factorial for 1 = 1
	// RESULT: factorial for 0 = 1
?>
</body>
</html>
