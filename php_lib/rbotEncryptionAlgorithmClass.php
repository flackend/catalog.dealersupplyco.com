<?php
	/**
	 * This file contains a an key generation class that was never used or tested and
	 * is no deprecated.
	 * 
	 * @author Ethix Systems LLC <support@ethixsystems.com>
	 * @since Unknown
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 * 
	 * @package ES_GeneralPHPLibraries
	 * @subpackage Deprecated
	 * @category Deprecated
	 * 
	 * @deprecated
	 */

	/**
	 * This is an old key generating class that was originally going to be implemented
	 * for a project but never was.  This code is not fully documented, and will not be,
	 * as it has become deprecated due to its lack of use.
	 * 
	 * @since Unknown
	 * @version Unknown
	 * @author Daniel E. Carr <decarr@ethixsystems.com>
	 * 
	 * @deprecated
	 */
	class rbotEncryption {
	
		/*$stringFormat will use the following as a key:
		 * D = numeric digit
		 * L = capital letter
		 * X = capital letter or numeric digit
		 * special characters will be treated literally and ignored as valid key characters
		*/
		private $stringFormat;
		/*
		 * $numValidDigits will hold the number of valid digits for data verification.  This number must be 8 or greater.
		 */
		private $numValidDigits;
		/*
		 * $numKeys will hold the number of keys being used for data verification.  This number must be 4 or greater.
		 */
		private $numKeys;
		/*
		 * $seedValue will hold a given seedValue between 0 and 127 specific to the project.  This will affect the roles of each digit in the algorithm.
		 */
		private $seedValue;
		/*
		 * $numMaxNonKeyRepeats holds the number of regular values (excluding keys) that can be the same value
		 * 		any positions in $characterArray not in this array will be in $valueArray
		 */
		private $numMaxNonKeyRepeats;
		/*
		 * $characterArray will contain an array indexed incrementally storing each valid digit 
		 */
		private $characterArray;
		/*
		 * $seedValueArray will contain an array of boolean values, being a binary representation of the passed in seed value
		 */
		private $seedValueArray;
		/*
		 * $characterPositionArray will contain an array indexed incrementally storing each digit's position in the original string 
		 */
		private $characterPositionArray;
		/*
		 * $valueArray will contain an array correlating the role of each digit to its position in the $characterArray 
		 * 		any positions in $characterArray not in this array will be in $keyArray
		 */
		private $valueArray;
		/*
		 * $keyArray will contain an array correlating the key role of each digit to its position in the $characterArray 
		 * 		any positions in $characterArray not in this array will be in $valueArray
		 */
		private $keyArray;
		
		private $modAssociationArray = array('D' => 10, 'L' => 26, 'X' => 36);
		
		function __construct($stringFormat, $numValidDigits, $numKeys, $seedValue, $numMaxNonKeyRepeats = 3)
		{
			$this->stringFormat = $stringFormat;
			$this->numValidDigits = $numValidDigits;
			$this->numKeys = $numKeys;
			$this->seedValue = $seedValue;
			
			//verify that there are at least 8 total valid digits
			if($numValidDigits < 8)
			{
				echo "You have not chosen enough valid digits for this algorithm to work.";
				return false;
			}
			//verify that there are at least 4 total keys
			if($numKeys < 4)
			{
				echo "You have not chosen enough keys for this algorithm to work.";
				return false;
			}
			//verify that there are at least twice as many valid digits as there are keys
			if($numValidDigits < 2*$numKeys)
			{
				echo "You need more valid digits to have that many keys in this algorithm.";
				return false;
			}
			
			//verify that numValidDigits is the same number of valid digits in stringFormat.
			$stringFormatArray = str_split($stringFormat);
			foreach($stringFormatArray as $index => $characterInString)
			{
				if(array_key_exists($characterInString, $this->modAssociationArray))
				{
					$this->characterPositionArray[] = $index;
					$this->characterArray[] = $characterInString;
				}
			}
			if(sizeof($this->characterArray) != $numValidDigits)
			{
				echo "Your string format does not have $numValidDigits valid digits in it.";
				return false;
			}
			
			//store the seed value as an array of 8 boolean values
			$this->seedValueArray = array();
			$seed8Key = 0; //seed8Key will be used to further randomize the position roles for each character
						   //seed8Key stored the number of 1's in the seedValue's binary key
			$binarySeed = decbin($seedValue);
			for($i=0;$i<8;$i++)
			{
				$this->seedValueArray[$i] = (bool) substr((string) $binarySeed, $i, 1);
				if($this->seedValueArray[$i])
					$seed8Key++;
			}
			
			//setup the character roles
			$i=0;
			$this->valueArray = array();
			$this->keyArray = array();
			$keyValue = true; //the boolean to toggle a value being assigned as a key or a normal value
			//initialize the counter to $seed8Key
			$counter = $seed8Key; //the counter, designating the position in characterArray being assigned
			//initialize the seedValueCounter to $seed8Key
			$seedValueCounter = $seed8Key; //the seed value counter, affecting the current position in seedArray being used to affect the $counter
			for($j=0;$j<$numValidDigits;$j++)
			{
				//find a value that has not been set in the array yet
				while(in_array($counter, $this->keyArray) || in_array($counter, $this->valueArray))
					$counter++;
					
				//depending on the value of $keyValue, assign a key to the keyArray or the valueArray
				if($keyValue)
					$this->keyArray[] = $counter;
				else
					$this->valueArray[] = $counter;
				$counter++; //by default, move to the next counter
				//based on the seedvaluearray, increment the counter by an additional spot if the array contains a 1 at this location
				if($this->seedValueArray[$seedValueCounter])
					$counter++;
				$seedValueCounter++; //increment the seedvaluecounter for next time
				$seedValueCounter = $seedValueCounter % 8; //reset seedValueCounter to a number between 0 and 7
				$counter = $counter % $numValidDigits; //reset counter to a valid array index in the range below
				
				//if the keyArray is filled up, then always select to fill the value array
				if(sizeof($this->keyArray) < $this->numKeys)
				{
					$keyValue = !$keyValue;
				}
				else
					$keyValue = false;
			}
			/*echo "Character Array:";
			print_r($this->characterArray);
			echo "<br/>Character Position Array:";
			print_r($this->characterPositionArray);
			echo "<br/> Value Array:";
			print_r($this->valueArray);
			echo "<br/>Key Array:";
			print_r($this->keyArray);
			echo "<br/>";*/
			
		}
		
		function displayStoredVariables()
		{
			echo "String Format: ".$this->stringFormat."<br/>";
			echo "Number of Valid Digits: ".$this->numValidDigits."<br/>";
			echo "Number of Keys: ".$this->numKeys."<br/>";
			echo "Seed Value: ".$this->seedValue."<br/>";
			echo "Number of Maximum Non-Key Repeats: ".$this->numMaxNonKeyRepeats."<br/>";
			echo "Character Array: ";
			foreach($this->characterArray as $index => $value)
			{
				echo "Character Array: ".$index." => ".$value."<br/>";
			}
			foreach($this->seedValueArray as $index => $value)
			{
				echo "Seed Value Array: ".$index." => ".$value."<br/>";
			}
			foreach($this->characterPositionArray as $index => $value)
			{
				echo "Character Position Array: ".$index." => ".$value."<br/>";
			}
			foreach($this->valueArray as $index => $value)
			{
				echo "Value Array: ".$index." => ".$value."<br/>";
			}
			foreach($this->keyArray as $index => $value)
			{
				echo "Key Array: ".$index." => ".$value."<br/>";
			}
		}
		
		function createNewKeys($numberOfKeys)
		{
			//generate $numberOfKeys keys
			$finalKeyArray = array();
			for($i=0;$i<$numberOfKeys;$i++)
			{
				$tempValueArray = array();
				$stringArray = array();
				foreach($this->valueArray as $valueIndex => $characterIndex)
				{
					$goOn = false;
					while(!$goOn)
					{
						$newRandomNumber = rand(1, $this->modAssociationArray[$this->characterArray[$characterIndex]]);
						$numTimesArray = array_count_values($tempValueArray);
						if($numTimesArray[$newRandomNumber] <= $this->numMaxNonKeyRepeats-1)
							$goOn = true;
					}
					$tempValueArray[] = $newRandomNumber;
					switch($this->characterArray[$characterIndex])
					{
						case 'D':
						 	$newRandomCharacter = (string) ($newRandomNumber % 10);
							break;
						case 'L':
							$newRandomCharacter = chr($newRandomNumber + 64);
							break;
						case 'X':
							if($newRandomNumber <= 10)
								$newRandomCharacter = (string)($newRandomNumber % 10);
							else
								$newRandomCharacter = chr($newRandomNumber + 54);
							break;
					}
					$stringArray[$this->characterPositionArray[$characterIndex]] = $newRandomCharacter;
				}
				echo "<br/><br/>";
				print_r($stringArray);
				$j=2;
				$k=0;
				$seedIterator=0;
				$numValuesToUse = $this->numValidDigits - $this->numKeys;
				$numKeysToDoSumsWith = $this->numKeys - 2;
				$tempKeyArray = array();
				while(isset($this->keyArray[$j]))
				{
					$numValuesToAdd = ($numValuesToUse / $numKeysToDoSumsWith);
					if(($numValuesToUse % $numKeysToDoSumsWith) <= ($j - 2))
						$numValuesToAdd++;
					$keyValue = 0;
					for($l=0;$l<$numValuesToAdd;$l++)
					{
						$keyValue += $tempValueArray[$k];
						$k++;
					}
					//figure out the extra added value based on 5 bits of the seedBinaryArray
					$additionalAdder = 0;
					for($l=0;$l<5;$l++)
					{
						$additionalAdder = ($additionalAdder*2) + $this->seedValueArray[($l+$seedIterator)%8];
					}
					$seedIterator++;
					$keyValue += $additionalAdder;
					$keyValue %= ($this->modAssociationArray[$this->characterArray[$j]]);
					$tempKeyArray[] = $keyValue;
					switch($this->characterArray[$this->keyArray[$j]])
					{
						case 'D':
						 	$newRandomCharacter = (string) ($keyValue % 10);
							break;
						case 'L':
							$newRandomCharacter = chr($keyValue + 64);
							break;
						case 'X':
							if($keyValue <= 10)
								$newRandomCharacter = (string)($keyValue % 10);
							else
								$newRandomCharacter = chr($keyValue + 54);
							break;
					}
					$stringArray[$this->characterPositionArray[$this->keyArray[$j]]] = $newRandomCharacter;
					$j++;
				}
				echo "<br/><br/>";
				print_r($stringArray);
				
				//calculate the key that sums the other keys together
				$tempSumValue = 0;
				foreach($tempKeyArray as $tempKey)
					$tempSumValue += $tempKey;
				for($l=0;$l<5;$l++)
				{
					$additionalAdder = ($additionalAdder*2) + $this->seedValueArray[($l+$seedIterator)%8];
				}
				$seedIterator++;
				$tempSumValue += $additionalAdder;
				$tempSumValue %= $this->modAssociationArray[$this->characterArray[$this->keyArray[1]]];
				//echo $tempSumValue."   ";
				switch($this->characterArray[$this->keyArray[1]])
				{
					case 'D':
					 	$newRandomCharacter = (string) ($tempSumValue % 10);
						break;
					case 'L':
						$newRandomCharacter = chr($tempSumValue + 64);
						break;
					case 'X':
						if($tempSumValue <= 10)
							$newRandomCharacter = (string)($tempSumValue % 10);
						else
							$newRandomCharacter = chr($tempSumValue + 54);
						break;
				}
				$stringArray[$this->characterPositionArray[$this->keyArray[1]]] = $newRandomCharacter;
						
				//calculate the key that sums all of the other keys together
				$tempTotalValue = 0;
				foreach($tempValueArray as $tempKey)
					$tempTotalValue += $tempKey;
				foreach($tempKeyArray as $tempKey)
					$tempTotalValue += $tempKey;
				$tempTotalValue += $tempSumValue;
				$tempTotalValue %= $this->modAssociationArray[$this->characterArray[$this->keyArray[0]]];
				//echo $tempTotalValue;
				switch($this->characterArray[$this->keyArray[0]])
				{
					case 'D':
					 	$newRandomCharacter = (string) ($tempTotalValue % 10);
						break;
					case 'L':
						$newRandomCharacter = chr($tempTotalValue + 64);
						break;
					case 'X':
						if($tempTotalValue <= 10)
							$newRandomCharacter = (string)($tempTotalValue % 10);
						else
							$newRandomCharacter = chr($tempTotalValue + 54);
						break;
				}
				$stringArray[$this->characterPositionArray[$this->keyArray[0]]] = $newRandomCharacter;
				echo "<br/><br/>";
				print_r($stringArray);
				
				//set the values that are not already set
				$sizeOfString = strlen($this->stringFormat);
				//echo "<br/><br/>".$sizeOfString;
				for($j=0;$j<$sizeOfString;$j++)
				{
					if(!isset($stringArray[$j]))
					{
						$stringArray[$j] = substr($this->stringFormat, $j, 1);
					}
				}
				
				//pull the entire string together from the pieces stored in $stringArray
				ksort($stringArray);
				$finalString = implode('', $stringArray);
				if(in_array($finalString, $finalKeyArray))
				{
					$i--;
				}
				else
					$finalKeyArray[] = $finalString;
			}
			
			return $finalKeyArray;
		}
	}
?>