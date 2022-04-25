<?php
/**
 * @package    Change Calculator
 *
 * @created    24th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Change\Calculator\Model;

/**
 * Model class for calculations
 */
class CalculatorModel
{
	const MSG_INFO = 'info';

	/**
	 * @var array
	 */
	private $values = [];

	/**
	 * @var array
	 */
	private $messages = [];

	/**
	 * Get the change
	 *
	 * @param   float  $cost
	 * @param   float  $payment
	 *
	 * @return  array
	 */
	public function getChange(float $cost, float $payment): array
	{
		if (empty($cost) && empty($payment))
		{
			// show a heads-up message
			$this->enqueueMessage('Add your transaction cost and payment received values', 'info');

			// return no change
			return [];
		}
		// check that we have a cost value
		elseif (empty($cost))
		{
			// show a warning message
			$this->enqueueMessage('Add a transaction cost value', 'warning');
			// keep payment
			$this->values['payment'] = $payment;

			// return no change
			return [];
		}
		// check that we have a payment value
		elseif (empty($payment))
		{
			// show a warning message
			$this->enqueueMessage('Add a payment received value', 'warning');
			// keep cost
			$this->values['cost'] = $cost;

			// return no change
			return [];
		}
		// last check make sure the payment is more than the cost
		if ($payment <= $cost)
		{
			// show a warning message
			$this->enqueueMessage('Add a higher payment received value, that is more than the cost value', 'warning');
			// keep cost
			$this->values['cost'] = $cost;

			// return no change
			return [];
		}

		// good we have both values to lets calculate
		return ['cost' => $cost, 'payment' => $payment, 'result' => $this->calculateChange($cost, $payment)];
	}

	/**
	 * Get already submitted form values
	 *
	 * @return  array
	 */
	public function getFormValues(): array
	{
		return $this->values;
	}

	/**
	 * Enqueue a system message.
	 *
	 * @param   string  $message  The message to enqueue.
	 * @param   string  $type     The message type. Default is message.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function enqueueMessage(string $message, string $type = self::MSG_INFO)
	{
		// Don't add empty messages.
		if (empty($message) || trim($message) === '')
		{
			return;
		}

		if (!\in_array($message, $this->messages))
		{
			// Enqueue the message.
			$this->messages[] = ['type' => $type, 'message' => $message];
		}
	}

	/**
	 * Get the message queue.
	 *
	 * @return  array  The system message queue.
	 *
	 * @since   1.0.0
	 */
	public function getMessageQueue(): array
	{
		// Get messages
		return $this->messages;
	}

	/**
	 * Do calculation of the change
	 *
	 * @return  array  The change breakdown
	 *
	 * @since   1.0.0
	 */
	private function calculateChange(float $cost, float $payment): array
	{
		// the denomination breakdown to pennies
		$denominations = [
			(object) ['names' => '$100 bills', 'name' => '$100 bill', 'value' => '100.00', 'pennies' => 10000],
			(object) ['names' => '$50 bills', 'name' => '$50 bill', 'value' => '50.00', 'pennies' => 5000],
			(object) ['names' => '$20 bills', 'name' => '$20 bill', 'value' => '20.00', 'pennies' => 2000],
			(object) ['names' => '$10 bills', 'name' => '$10 bill', 'value' => '10.00', 'pennies' => 1000],
			(object) ['names' => '$5 bills', 'name' => '$5 bill', 'value' => '5.00', 'pennies' => 500],
			(object) ['names' => '$1 bills', 'name' => '$1 bill', 'value' => '1.00', 'pennies' => 100],
			(object) ['names' => 'quarters', 'name' => 'quarter', 'value' => '0.25', 'pennies' => 25],
			(object) ['names' => 'dimes', 'name' => 'dime', 'value' => '0.10', 'pennies' => 10],
			(object) ['names' => 'nickles', 'name' => 'nickle', 'value' => '0.05', 'pennies' => 5],
			(object) ['names' => 'pennies', 'name' => 'penny', 'value' => '0.01', 'pennies' => 1]
		];
		// the current change
		$change = $this->bc('sub', $payment, $cost, 2);
		// convert to pennies
		$pennies = $this->bc('mul', $change, 100);
		// the breakdown
		$breakdown = [];
		// work out the number of pennies per denomination
		foreach ($denominations as $denomination)
		{
			if ($pennies >= $denomination->pennies)
			{
				// get the number of times this denomination goes into the change
				$number_of = floor($this->bc('div', $pennies, $denomination->pennies, 2));
				// get the number of pennies to deduct from total pennies remaining
				$deduction = $this->bc('mul', $denomination->pennies, $number_of);
				// make the deduction
				$pennies = $this->bc('sub', $pennies, $deduction);
				// spacer for any
				$spacer = ', ';
				// spacer for last
				if ($pennies < 1)
				{
					$spacer = ', and ';
				}
				// spacer for one or first
				if (count($breakdown) == 0)
				{
					$spacer = '';
				}
				// set the current change of this denomination
				$breakdown[] = [
					'name'        => ($number_of == 1) ? $denomination->name : $denomination->names,
					'value'       => $denomination->value,
					'number'      => $number_of,
					'total'      => $this->bc('mul', $number_of, (float) $denomination->value, 2),
					'number_name' => ($number_of == 1) ? 'a' : $this->numberName($number_of),
					'spacer'         => $spacer
				];
			}
		}

		return ['change' => $change, 'breakdown' => $breakdown];
	}

	/**
	 * bc math wrapper (very basic not for accounting)
	 * I wrote this a few years ago for a private project
	 *
	 * @param   string  $type   The type bc math
	 * @param   float   $val1   The first value
	 * @param   float   $val2   The second value
	 * @param   int     $scale  The scale value
	 *
	 * @return bool|string
	 *
	 * @since  1.0.0
	 */
	private function bc(string $type, float $val1, float $val2, int $scale = 0)
	{
		// build function name
		$function = 'bc' . $type;
		// use the bcmath function if available
		if (function_exists($function))
		{
			return $function($val1, $val2, $scale);
		}
		// if function does not exist we use +-*/ operators (fallback - not ideal)
		switch ($type)
		{
			// Multiply two numbers
			case 'mul':
				return (string) round($val1 * $val2, $scale);
			// Divide of two numbers
			case 'div':
				return (string) round($val1 / $val2, $scale);
			// Adding two numbers
			case 'add':
				return (string) round($val1 + $val2, $scale);
			// Subtract one number from the other
			case 'sub':
				return (string) round($val1 - $val2, $scale);
			// Raise an arbitrary precision number to another
			case 'pow':
				return (string) round(pow($val1, $val2), $scale);
			// Compare two arbitrary precision numbers
			case 'comp':
				return (round($val1, 2) == round($val2, 2));
		}

		return false;
	}

	/**
	 * Convert an integer into an English word string
	 * Thanks to Tom Nicholson <http://php.net/manual/en/function.strval.php#41988>
	 *
	 * @input    int
	 * @returns string
	 *
	 * @since    1.0.0
	 */
	private function numberName($x)
	{
		$nwords = array("zero", "one", "two", "three", "four", "five", "six", "seven",
			"eight", "nine", "ten", "eleven", "twelve", "thirteen",
			"fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
			"nineteen", "twenty", 30 => "thirty", 40 => "forty",
			                      50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
			                      90 => "ninety");

		if (!is_numeric($x))
		{
			$w = $x;
		}
		elseif (fmod($x, 1) != 0)
		{
			$w = $x;
		}
		else
		{
			if ($x < 0)
			{
				$w = 'minus ';
				$x = -$x;
			}
			else
			{
				$w = '';
				// ... now $x is a non-negative integer.
			}

			if ($x < 21)   // 0 to 20
			{
				$w .= $nwords[$x];
			}
			elseif ($x < 100)  // 21 to 99
			{
				$w .= $nwords[10 * floor($x / 10)];
				$r = fmod($x, 10);
				if ($r > 0)
				{
					$w .= ' ' . $nwords[$r];
				}
			}
			elseif ($x < 1000)  // 100 to 999
			{
				$w .= $nwords[floor($x / 100)] . ' hundred';
				$r = fmod($x, 100);
				if ($r > 0)
				{
					$w .= ' and ' . $this->numberName($r);
				}
			}
			elseif ($x < 1000000)  // 1000 to 999999
			{
				$w .= $this->numberName(floor($x / 1000)) . ' thousand';
				$r = fmod($x, 1000);
				if ($r > 0)
				{
					$w .= ' ';
					if ($r < 100)
					{
						$w .= 'and ';
					}
					$w .= $this->numberName($r);
				}
			}
			else //  millions
			{
				$w .= $this->numberName(floor($x / 1000000)) . ' million';
				$r = fmod($x, 1000000);
				if ($r > 0)
				{
					$w .= ' ';
					if ($r < 100)
					{
						$w .= 'and ';
					}
					$w .= $this->numberName($r);
				}
			}
		}

		return $w;
	}
}
