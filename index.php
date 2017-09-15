<?PHP
	
	/*
		A class that converts text to its associated numerological number (arcane mumbo jumbo)
		In numerology all characters have an associated number from 1 - 9, and whole words can 
		be reduced to a single digit -
		
		COFFEE : 3 + 6 + 6 + 6 + 5 + 5 = 31
		3 + 1 = (4)
		
		public functions:
			NumerologyCalculator [constructor] (string:text, string:method)
			setText (string:text)
			setMethod (string:method)
			getResult (returns integer)
	*/
	class NumerologyCalculator {
		
		private $result = 0;
		private $valid_methods = array();
		private $selected_method = 'pythagorean';
		private $text = '';
		private $count;
		
		/*
			define character to number mappings
			associative array is used as chaldean set does not contain the full 26 characters
		*/
		protected $numbers = array(
			'pythagorean' => array(
			'a'=>1, 'b'=>2, 'c'=>3, 'd'=>4, 'e'=>5, 'f'=>6, 'g'=>7, 'h'=>8, 'i'=>9, 'j'=>1, 'k'=>2, 'l'=>3, 'm'=>4, 'n'=>5, 
			'o'=>6, 'p'=>7, 'q'=>8, 'r'=>9, 's'=>1, 't'=>2, 'u'=>3, 'v'=>4, 'w'=>5, 'x'=>6, 'y'=>7, 'z'=>8, '1'=>1, '2'=>2, 
			'3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9
			),
			'chaldean' => array(
			'a'=>1, 'i'=>1, 'j'=>1, 'q'=>1, 'y'=>1, 'b'=>2, 'k'=>2, 'r'=>2, 'c'=>3, 'g'=>3, 'l'=>3, 's'=>3, 'd'=>4, 'm'=>4, 't'=>4, 
			'e'=>5, 'h'=>5, 'n'=>5, 'x'=>5, 'u'=>6, 'v'=>6, 'w'=>6, 'o'=>7, 'z'=>7, 'f'=>8, 'p'=>8, '1'=>1, '2'=>2, '3'=>3, '4'=>4, 
			'5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9
			)
		);
		
		/*
			constructor - sets input string and which numerological method to use
		*/
		public function NumerologyCalculator ($text = 'David Lynch', $method = 'pythagorean') {
			foreach ($this->numbers as $key => $val) {
				$this->valid_methods[] = $key;
			}
			$this->setText($text);
			$this->setMethod($method);
			$this->calculate();
		}
		
		/*
			set text
		*/
		public function setText ($text) {
			if (is_string(trim($text))) {
				$this->text = trim($text);
			}
		}
		
		/*
			set numerological method
		*/
		public function setMethod ($method) {
			if (is_string(trim($method))) {
				if (in_array($method, $this->valid_methods)) {
					$this->selected_method = $method;
				}
			}
		}
		
		/*
			get result
		*/
		public function getResult () {
			return (int) $this->result;
		}
		
		/*
			perform the calculation / conversion
		*/
		private function calculate () {
			if (trim($this->text) == '') {
				return;
			}
			// which method (number set) to use
			if (array_key_exists($this->selected_method, $this->numbers)) {
				$set = $this->numbers[$this->selected_method];
			}
			$this->text = strtolower($this->text);
			$this->count = 0;
			
			// perform pythagorean 'special characters' calculation first
			if ($this->selected_method == 'pythagorean') {
				$greek_result = $this->handleGreekChars($this->text);
				$this->text = $greek_result['text'];
				$this->count += $greek_result['total'];
			}
			
			// calculate total for remaining characters, add to prev total
			$num_chars = strlen($this->text);
			for ($i = 0; $i < $num_chars; $i++) {
				$char = substr($this->text, $i, 1);
				if (trim($char) != '') {
					$this->count += $set[$char];
				}
			}
			
			// reduce final total to single digit
			$this->result = $this->reduceNumber($this->count);
		}
		
		/*
			reduce number to single digit
			39 = 3 (3 + 9 = 12, 1 + 2 = 3)
		*/
		private function reduceNumber ($num) {
			$total = 0;
			$num_str = (string) $num;
			$num_length = strlen($num_str);
			if ($num_length > 1) {
				for ($i = 0; $i < $num_length; $i++) {
					$total += $num_str[$i];
				}
				return $this->reduceNumber($total);
			}
			return $num;
		}
		
		/*
			Certain greek character combinations are assigned single digits.
			This function calculates the total for all occurances and removes the characters from the string
		*/
		private function handleGreekChars ($str) {
			$greek = array(
				'th' => 8,
				'ph' => 3,
				'ch' => 4,
				'ps' => 5
			);
			$total = 0;
			foreach ($greek as $chars => $val) {
				$total += (substr_count($str, $chars) * $val);
				$str = str_replace($chars, '', $str);
			}
			return array('text' => $str, 'total' => $total);
		}
		
	} // end class NumerologyCalculator
	
	
	/*
	class altCalc extends NumerologyCalculator {
		protected $numbers = array(
			'pythagorean' => array(
			'a'=>1, 'b'=>2, 'c'=>3, 'd'=>4, 'e'=>5, 'f'=>6, 'g'=>7, 'h'=>8, 'i'=>9, 'j'=>1, 'k'=>2, 'l'=>3, 'm'=>4, 'n'=>5, 
			'o'=>6, 'p'=>7, 'q'=>8, 'r'=>9, 's'=>3, 't'=>9, 'u'=>3, 'v'=>4, 'w'=>5, 'x'=>6, 'y'=>7, 'z'=>8, '1'=>1, '2'=>2, 
			'3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9
			),
			'chaldean' => array(
			'a'=>1, 'i'=>1, 'j'=>1, 'q'=>1, 'y'=>1, 'b'=>2, 'k'=>2, 'r'=>2, 'c'=>3, 'g'=>3, 'l'=>3, 's'=>1, 'd'=>4, 'm'=>4, 't'=>5, 
			'e'=>5, 'h'=>5, 'n'=>5, 'x'=>5, 'u'=>6, 'v'=>6, 'w'=>6, 'o'=>7, 'z'=>7, 'f'=>8, 'p'=>8, '1'=>1, '2'=>2, '3'=>3, '4'=>4, 
			'5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9
			)
		);
	}
	*/
	
	
	$numerology_html = '';
	
	/*
		FORM SUBMITTED - perform numerological calculation
	*/
	if (isset($_POST['text'])) {
		$_POST['method'] = ($_POST['method'] != '') ? $_POST['method'] : 'pythagorean';
		$text = trim(strip_tags($_POST['text']));
		
		$calc = new NumerologyCalculator($text, $_POST['method']);
		$numerology_result = $calc->getResult();
		
		// create html for result
		if ($numerology_result > 0) {
			$numerology_html = '<div class="container">';
			$numerology_html .= '<h2>'.htmlspecialchars($text).' = '.$numerology_result.'</h2>';
			$file_name = $numerology_result.'.txt';
			if (file_exists($file_name)) {
				$contents = file_get_contents($file_name);
				$numerology_html .= nl2br($contents);
			}
			$numerology_html .= '</div>';
		}
	}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Numerology Calculator</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>

    <div class="container">
		<div class="header clearfix">
			<h3 class="text-muted">Numerology Calculator</h3>
		</div>

		<div class="jumbotron">
			<form class="navbar-form" action="index.php" method="post">
				<div class="form-group">
					<input class="form-control" 
						name="text" id="txt" type="text" 
						value="<?=htmlspecialchars($_POST['text']) ?>" 
						placeholder="Enter some text" maxlength="100" 
						onfocus="this.select()" 
					/>
				</div>
				<div class="form-group">
					<select class="form-control" name="method">
						<option value="pythagorean"<?=($_POST['method'] == 'pythagorean') ? ' selected="selected"' : '' ?>>Pythagorean</option>
						<option value="chaldean"<?=($_POST['method'] == 'chaldean') ? ' selected="selected"' : '' ?>>Chaldean</option>
					</select>
				</div>
				<input class="btn btn-success" name="btnCalc" type="submit" value=" Do it &raquo; " />
			</form>

			<?PHP
				echo $numerology_html;
			?>
		</div>
	</div>

</body>
</html>
