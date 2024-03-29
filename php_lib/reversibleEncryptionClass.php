<?php
	/**
	 * This file contains a reversible encryption class that can be used in projects to
	 * pass encrypted data through the session or get parameters if necessary.  It is
	 * recommended that all sensitive data be stored on the database when possible so
	 * that this class doesn't see much use.
	 * 
	 * @author A J Marston <http://www.tonymarston.net>
	 * @since 2003
	 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
	 * 
	 * @package ES_GeneralPHPLibraries
	 * @subpackage 3rd Party
	 * @category 3rd Party
	 */

	// ******************************************************************************
	// A reversible password encryption routine by:
	// Copyright 2003-2007 by A J Marston <http://www.tonymarston.net>
	// Distributed under the GNU General Public Licence
	// Modification: May 2007, M. Kolar <http://mkolar.org>:
	// No need for repeating the first character of scramble strings at the end;
	// instead using the exact inverse function transforming $num2 to $num1.
	// ******************************************************************************
	
	/**
	 * The class that enables you to encrypt data and unencrypt it on the otherside using
	 * a common privately held key.  Because this class is supplied by a third-party, the only
	 * comments available are the ones provided by the third-party.
	 * 
	 * @since 2003
	 * @version May 2007
	 * @author A J Marston <http://www.tonymarston.net>
	 */
	class ReversibleEncryption {
	
	    private $scramble1;     // 1st string of ASCII characters
	    private $scramble2;     // 2nd string of ASCII characters
	
	    private $errors;        // array of error messages
	    private $adj;           // 1st adjustment value (optional)
	    private $mod;           // 2nd adjustment value (optional)
	
	    // ****************************************************************************
	    // class constructor
	    // ****************************************************************************
	    function __construct ()
	    {
	        $this->errors = array();
	
	        // Each of these two strings must contain the same characters, but in a different order.
	        // Use only printable characters from the ASCII table.
	        // Do not use single quote, double quote or backslash as these have special meanings in PHP.
	        // Each character can only appear once in each string.
	        //$this->scramble1 = '!apXOs#ieNfH)^,gtUy1:8%<76(~_ldS52*ABCD@EF/|&G-IJKLMP;>QRTVWYZ][bch=jkmnoqruvw.xz}+{1349?';
	        //$this->scramble2 = '|pxH^1saiTyU>eo8f;g-/n%67(~_&d5l.2?s*qa)jKLDFZWEBGR!YPV:CIMT][nhuxwbm<rzvojqkc}+{31,9@=4#';
	        //$this->scramble1 = '! #$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
	        //$this->scramble2 = 'f^jAE]okIOzU[2&q1{3`h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!#$~(;Lt-R}Ma,NvW+Ynb*0X';
	        $this->scramble1 = '!#$%&()*+-./0123456789:<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
	        $this->scramble2 = 'f^jAE]okIOzU[2&q1{3`h5w_794p@6s8?BgP>dFV=mD<TcS%Ze|r:lGK/uCy.Jx)HiQ!#$~(Lt-R}MaNvW+Ynb*0X';
	
	        if (strlen($this->scramble1) <> strlen($this->scramble2)) {
	            trigger_error('** SCRAMBLE1 is not same length as SCRAMBLE2 **', E_USER_ERROR);
	        } // if
	
	        $this->adj = 1.75;  // this value is added to the rolling fudgefactors
	        $this->mod = 3;     // if divisible by this the adjustment is made negative
	
	    } // constructor
	
	    // ****************************************************************************
	    public function decrypt ($key, $source)
	    // decrypt string into its original form
	    {
	        $this->errors = array();
	
	        // convert $key into a sequence of numbers
	        $fudgefactor = $this->_convertKey($key);
	        if ($this->errors) return;
	
	        if (empty($source)) {
	            $this->errors[] = 'No value has been supplied for decryption';
	            return;
	        } // if
	
	        $target = null;
	        $factor2 = 0;
	
	        for ($i = 0; $i < strlen($source); $i++) {
	            // extract a character from $source
	            $char2 = substr($source, $i, 1);
	
	            // identify its position in $scramble2
	            $num2 = strpos($this->scramble2, $char2);
	            if ($num2 === false) {
	                $this->errors[] = "Source string contains an invalid character ($char2)";
	                return;
	            } // if
	
	            // get an adjustment value using $fudgefactor
	            $adj     = $this->_applyFudgeFactor($fudgefactor);
	
	            $factor1 = $factor2 + $adj;                 // accumulate in $factor1
	            $num1    = $num2 - round($factor1);         // generate offset for $scramble1
	            $num1    = $this->_checkRange($num1);       // check range
	            $factor2 = $factor1 + $num2;                // accumulate in $factor2
	
	            // extract character from $scramble1
	            $char1 = substr($this->scramble1, $num1, 1);
	
	            // append to $target string
	            $target .= $char1;
	
	            //echo "char1=$char1, num1=$num1, adj= $adj, factor1= $factor1, num2=$num2, char2=$char2, factor2= $factor2<br />\n";
	
	        } // for
	
	        return rtrim($target);
	
	    } // decrypt
	
	    // ****************************************************************************
	    public function encrypt ($key, $source, $sourcelen = 0)
	    // encrypt string into a garbled form
	    {
	        $this->errors = array();
	
	        // convert $key into a sequence of numbers
	        $fudgefactor = $this->_convertKey($key);
	        if ($this->errors) return;
	
	        if (empty($source)) {
	            $this->errors[] = 'No value has been supplied for encryption';
	            return;
	        } // if
	
	        // pad $source with spaces up to $sourcelen
	        while (strlen($source) < $sourcelen) {
	            $source .= ' ';
	        } // while
	
	        $target = null;
	        $factor2 = 0;
	
	        for ($i = 0; $i < strlen($source); $i++) {
	            // extract a character from $source
	            $char1 = substr($source, $i, 1);
	
	            // identify its position in $scramble1
	            $num1 = strpos($this->scramble1, $char1);
	            if ($num1 === false) {
	                $this->errors[] = "Source string contains an invalid character ($char1)";
	                return;
	            } // if
	
	            // get an adjustment value using $fudgefactor
	            $adj     = $this->_applyFudgeFactor($fudgefactor);
	
	            $factor1 = $factor2 + $adj;             // accumulate in $factor1
	            $num2    = round($factor1) + $num1;     // generate offset for $scramble2
	            $num2    = $this->_checkRange($num2);   // check range
	            $factor2 = $factor1 + $num2;            // accumulate in $factor2
	
	            // extract character from $scramble2
	            $char2 = substr($this->scramble2, $num2, 1);
	
	            // append to $target string
	            $target .= $char2;
	
	            //echo "char1=$char1, num1=$num1, adj= $adj, factor1= $factor1, num2=$num2, char2=$char2, factor2= $factor2<br />\n";
	
	        } // for
	
	        return $target;
	
	    } // encrypt
	
	    // ****************************************************************************
	    public function getAdjustment ()
	    // return the adjustment value
	    {
	        return $this->adj;
	
	    } // setAdjustment
	
	    // ****************************************************************************
	    public function getModulus ()
	    // return the modulus value
	    {
	        return $this->mod;
	
	    } // setModulus
	
	    // ****************************************************************************
	    public  function setAdjustment ($adj)
	    // set the adjustment value
	    {
	        $this->adj = (float)$adj;
	
	    } // setAdjustment
	
	    // ****************************************************************************
	    public function setModulus ($mod)
	    // set the modulus value
	    {
	        $this->mod = (int)abs($mod);    // must be a positive whole number
	
	    } // setModulus
	
	    // ****************************************************************************
	    // private methods
	    // ****************************************************************************
	    private function _applyFudgeFactor (&$fudgefactor)
	    // return an adjustment value  based on the contents of $fudgefactor
	    // NOTE: $fudgefactor is passed by reference so that it can be modified
	    {
	        $fudge = array_shift($fudgefactor);     // extract 1st number from array
	        $fudge = $fudge + $this->adj;           // add in adjustment value
	        $fudgefactor[] = $fudge;                // put it back at end of array
	
	        if (!empty($this->mod)) {               // if modifier has been supplied
	            if ($fudge % $this->mod == 0) {     // if it is divisible by modifier
	                $fudge = $fudge * -1;           // make it negative
	            } // if
	        } // if
	
	        return $fudge;
	
	    } // _applyFudgeFactor
	
	    // ****************************************************************************
	    private function _checkRange ($num)
	    // check that $num points to an entry in $this->scramble1
	    {
	        $num = round($num);         // round up to nearest whole number
	
	        $limit = strlen($this->scramble1);
	
	        while ($num >= $limit) {
	            $num = $num - $limit;   // value too high, so reduce it
	        } // while
	        while ($num < 0) {
	            $num = $num + $limit;   // value too low, so increase it
	        } // while
	
	        return $num;
	
	    } // _checkRange
	
	    // ****************************************************************************
	    private function _convertKey ($key)
	    // convert $key into an array of numbers
	    {
	        if (empty($key)) {
	            $this->errors[] = 'No value has been supplied for the encryption key';
	            return;
	        } // if
	
	        $array[] = strlen($key);    // first entry in array is length of $key
	
	        $tot = 0;
	        for ($i = 0; $i < strlen($key); $i++) {
	            // extract a character from $key
	            $char = substr($key, $i, 1);
	
	            // identify its position in $scramble1
	            $num = strpos($this->scramble1, $char);
	            if ($num === false) {
	                $this->errors[] = "Key contains an invalid character ($char)";
	                return;
	            } // if
	
	            $array[] = $num;        // store in output array
	            $tot = $tot + $num;     // accumulate total for later
	        } // for
	
	        $array[] = $tot;            // insert total as last entry in array
	
	        return $array;
	
	    } // _convertKey
	
	// ****************************************************************************
	} // end encryption_class