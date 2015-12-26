<?php
/**
Copyright (c) 2011, Matthew Craig
All rights reserved.

This license is a legal agreement between you and Matthew Craig for the use 
of this Sanitation Class (the "Software"). By obtaining the Software you 
agree to comply with the terms and conditions of this license.

Permitted Use

You are permitted to use, copy, modify, and distribute the Software and its 
documentation, with or without modification, for any purpose, provided that 
the following conditions are met:

   1. A copy of this license agreement must be included with the distribution.
   2. Redistributions of source code must retain the above copyright notice 
        in all source code files.
   3. Redistributions in binary form must reproduce the above copyright notice 
        in the documentation and/or other materials provided with the 
        distribution.
   4. Any files that have been modified must carry notices stating the nature 
        of the change and the names of those who changed them.
   5. Products derived from the Software must include an acknowledgment that 
        they are derived from the "Software" in their documentation and/or 
        other materials provided with the distribution.

Indemnity

You agree to indemnify and hold harmless the authors of the Software and any 
contributors for any direct, indirect, incidental, or consequential 
third-party claims, actions or suits, as well as any related expenses, 
liabilities, damages, settlements or fees arising from your use or misuse of 
the Software, or a violation of any terms of this license.

Disclaimer of Warranty

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESSED OR 
IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF QUALITY, PERFORMANCE, 
NON-INFRINGEMENT, MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE.

Limitations of Liability

YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE 
FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM, OUT OF, OR IN CONNECTION 
WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY RESPONSIBLE FOR DETERMINING THE 
APPROPRIATENESS OF USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING 
BUT NOT LIMITED TO THE RISKS OF PROGRAM ERRORS, DAMAGE TO EQUIPMENT, LOSS OF 
DATA OR SOFTWARE PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.
*/

/**
 * Sanitize 
 * 
 * @package TaggedZi's Sanitation Library 
 * @copyright 2011 Matthew Craig
 * @author Matthew Craig <matt@taggedzi.com> 
 */
if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }
 
      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}
if (!function_exists("filter_var")){
      // define the constants used by the function 
      define("FILTER_VALIDATE_EMAIL", "email");

      function filter_var(){
          $args = func_get_args();
          // $args[1] is the filter type (second parameter)
          switch ($args[1]){
               case FILTER_VALIDATE_EMAIL:
                   return ( preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $args[0] ))?$args[0]:false;
                   break;
          }
      }
}
class Sanitize
{

    /**
     * replaced  
     * 
     * The number of characters replaced.
     * @var int
     * @access public
     */
    var $total_replaced = 0;

    /**
     * current_replaced 
     * 
     * @var int 
     * @access public
     */
    var $current_replaced = 0;

    // Define some common string types to be used.
    const ALPHA             = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const ALPHA_NUM         = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const ALPHA_SPACE       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789';
    const ALPHA_DASH        = 'áéíóúabcdefghijklmnopqrstuvwxyzÁÉÍÓÚABCDEFGHIJKLMNOPQRSTUVWXYZñÑ 0123456789-_';
    const ALPHA_ARRAY        = 'áéíóúabcdefghijklmnopqrstuvwxyzÁÉÍÓÚABCDEFGHIJKLMNOPQRSTUVWXYZñÑ 0123456789-_=';
    const ALPHA_PUNCTUATION = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789-_.,!?:;';
    const HEX               = '0123456789abcdefABCDEF';
    const HEX_UPPER         = '0123456789ABCDEF';
    const HEX_LOWER         = '0123456789abcdef';
    const OCTAL             = '0124567';
    const INTEGER           = '0123456789-';
    const FLOAT             = '0123456789.e-';

    /**
     * __construct 
     * 
     * @access protected
     * @return void
     */
    function __construct()
    {
        return TRUE;
    }

    /**
     * unique  
     * 
     * This funtion takes an array of input and performs a "double" flip 
     * unique array entry. (Forces unique array data much faster than array_unique)
     * @param array $input 
     * @access private
     * @return mixed
     */
    private function unique($input = array())
    {
        return array_flip(array_flip($input));
    }

    /**
     * magic_quotes 
     * 
     * If magic_quotes are turned on... Remove them. If not return the string.
     * @param mixed $input 
     * @access private
     * @return mixed 
     */
    private function magic_quotes($input)
    {
        if(get_magic_quotes_gpc())
        {
            return stripslashes($input);
        }
        else
        {
            return $input;
        }
    }

    /**
     * remove_null_byte  
     * 
     * This is designed to remove null bytes from strings.
     * @param mixed $input 
     * @access private
     * @return mixed.
     */
    private function remove_null_byte($input)
    {
        if ((is_string($input)) && (!empty($input))) 
        {
            return str_replace("\0", '', $input);
        }
        else 
        {
            return $input;
        }
    }

    /**
     * clean_boolean 
     * 
     * This function attempts to figure out what $input is and force it to bool.
     * @param mixed $input 
     * @access public
     * @return boolean 
     */
    public function clean_boolean($input)
    {
        // This forces php to try to make $input a boolean.
        settype($input, 'boolean');
        return $input;
    }

    /**
     * white_list_cleaner  
     * 
     * This is a "white list" cleaner. Given an input string it removes ALL 
     * characters that are not on the "white list".  Thereby cleaning the text
     * from unwanted/expected characters.
     * @param string $input 
     * @param string $allowable 
     * @param string $replacement 
     * @access public
     * @return mixed (Boolean FALSE on failure, Clean String on success)
     */
    public function white_list_cleaner($input = '', $allowable = self::ALPHA_DASH, $replacement = '')
    {
        // Init the current test
        $this->current_replaced = 0;

        // deal with magic_quotes right off the bat
        $input = $this->magic_quotes($input);
        // Remove any NULL BYTES from the input.
        $input = $this->remove_null_byte($input);

        // Sanity Checks
        if ((!is_string($allowable)))
        {
            throw new Exception('Allowable characters must be of type string.');
        }

        if (!is_string($replacement)) 
        {
            // The replacement is "broken" and something is wrong 
            throw new Exception('The specified replacement is broken nothing should be returned.');
        }

        $input = (string) $input;   // Juggle the type to a string.
        if (($input === '') || ($allowable === ''))
        {
            // if the input is empty no need to clean... return an empty string.
            // OR if there are no allowe chars.
            return '';
        }

        // Create an array of allowed chars.
        $allowed_chars = str_split($allowable);
        $size = strlen($input);
        $output = '';

        // Walk through the input
        for ($i = 0; $i < $size; $i++) {
            $char = $input[$i];
            // Check to see if the current char is allowed...
            if (in_array($char, $allowed_chars))
            {
                // if allowed... add to the output string.
                $output .= $char;
            }
            else 
            {
                // if not allowed... replace with the replacement string
                $output .= $replacement;
                $this->total_replaced++;
                $this->current_replaced++;
            }
        }
        return $output;
    }

    /**
     * white_list_array  
     * 
     * This method is a "helper" to allow for input of arrays of strings to be "batch
     * processed" at a single time. This does PRESERVER the original array keys.
     * However BECAUSE of that, keys are not cleaned in any way.  Be aware if you 
     * are using keys that can be produced by a user... they do not get filtered HERE.
     * You must manually clean your keys.
     * @param array $input 
     * @param mixed $allowed_chars 
     * @param string $replacement 
     * @access public
     * @return array
     */
    public function white_list_array($input = array(), $allowed_chars = self::ALPHA_DASH, $replacement = '')
    {
        if(is_array($input))
        {
            try
            {
                foreach ($input as $dirty_key => $dirty_string) {
                    $clean[$dirty_key] = $this->white_list_cleaner($dirty_string, $allowed_chars, $replacement);
                }
            }
            catch (Exception $e)
            {
                throw new Exception('Error processing request.');
            }
            return $clean;
        } 
        else
        {
            throw new Exception('Input for batch processing must be an array of strings.');
        }
    }
    
    public function white_array_list($input = array(), $allowed_chars = self::ALPHA_ARRAY, $replacement = '')
    {
        if(is_array($input))
        {
            try
            {
                foreach ($input as $dirty_key => $dirty_string) {
                    $clean[$dirty_key] = $this->white_list_cleaner($dirty_string, $allowed_chars, $replacement);
                }
            }
            catch (Exception $e)
            {
                throw new Exception('Error processing request.');
            }
            return $clean;
        } 
        else
        {
            throw new Exception('Input for batch processing must be an array of strings.');
        }
    }

    /**
     * black_list_cleaner  
     * 
     * This function takes a string input, and goes character by character through
     * the string.  IF a character is on the "black_list" then it is removed and
     * replaced with a specified character (or string). 
     *
     * Note: It is not recommended to use this function for USER input data.
     * When ever possible it is recommended to use the white_list_cleaner function
     * above.  "black_list_cleaner" was added because there are cases where this behavior
     * is desirable. But under most circumstances it is not the most secure method.
     * Make sure you understand the difference, and security implications of these
     * methods BEFORE you use them.
     *
     * @param string $input 
     * @param string $banned_chars 
     * @param string $replace 
     * @access public
     * @return string
     */
    public function black_list_cleaner($input = '', $banned_chars = '', $replacement = '')
    {
        // Init the current test.
        $this->current_replaced = 0;

        // deal with magic_quotes right off the bat
        $input = $this->magic_quotes($input);
        // Remove any NULL BYTES from the input.
        $input = $this->remove_null_byte($input);

        // Sanity Checks
        if ((!is_string($banned_chars)))
        {
            throw new Exception('Banned characters must be of type string.');
        }

        if (!is_string($replacement)) 
        {
            // The replacement is "broken" and something is wrong 
            throw new Exception('The specified replacement is broken nothing should be returned.');
        }

        $input = (string) $input;
        if ($input === '')
        {
            // if the input is empty no need to clean... return an empty string.
            return '';
        }

        // Create an array of banned chars.
        $banned_char_array = str_split($banned_chars);
        $size = strlen($input);
        $output = '';

        // Walk through the input
        for ($i = 0; $i < $size; $i++) {
            $char = $input[$i];
            // Check to see if the curren char is allowed...
            if (in_array($char, $banned_char_array))
            {
                // if blacklisted.. replace the char
                $output .= $replacement;
                $this->total_replaced++;
                $this->current_replaced++;
            }
            else 
            {
                // if allowed... add it to the string
                $output .= $char;
            }
        }
        return $output;

    }

    /**
     * clean_integer 
     * 
     * This takes an input string, runs it through the white list
     * filer for "integers" and removed all non-integer characters.
     * If it passed the cleaner, it then forces a typecast of Integer.
     * 
     * @param string $input
     * @access public
     * @return integer
     */
    public function clean_integer($input = "0")
    {
        try 
        {
            $value = $this->white_list_cleaner($input, self::INTEGER);
            settype($value, 'int');
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
        return $value;
    }

    /**
     * clean_hex  
     * 
     * @param string $input 
     * @param string $case (Allowed: MIXED, UPPER, LOWER)
     * @access public
     * @return string  This returns a STRING that only contains VALID hex characters.
     */
    public function clean_hex($input, $case = 'MIXED')
    {
        switch ($case) {
            case 'UPPER':
                try 
                {
                    $value = $this->white_list_cleaner($input, self::HEX_UPPER);
                    settype($value, 'string');
                }
                catch (Exception $e) 
                {
                    throw new Exception($e->getMessage()); 
                }
                break;
            case 'LOWER':
                try 
                {
                    $value = $this->white_list_cleaner($input, self::HEX_LOWER);
                    settype($value, 'string');
                }
                catch (Exception $e)
                {
                    throw new Exception($e->getMessage()); 
                }
                break;
            case 'MIXED':
            default:
                try 
                {
                    $value = $this->white_list_cleaner($input, self::HEX);
                    settype($value, 'string');
                }
                catch (Exception $e)
                {
                    throw new Exception($e->getMessage()); 
                }
                break;
        }
        return $value;
    }

    /**
     * clean_octal 
     * 
     * This function takes an input string and REMOVES all invalid octal chars.
     * @param mixed $input 
     * @access public
     * @return string This returns a STRING representation of an octal value.
     */
    public function clean_octal($input)
    {
        try 
        {
            $value = $this->white_list_cleaner($input, self::OCTAL);
            settype($value, 'string');
        }
        catch (Execption $e)
        {
            throw new Exception($e->getMessage()); 
        }
        return $value;
    }

    /**
     * clean_float  
     * 
     * This method takes a string any removes any characters that are not
     * valid float characters from the string.
     * @param string $input 
     * @access public
     * @return string.
     */
    public function clean_float($input = "0.0")
    {
        try 
        {
            $value = $this->white_list_cleaner($input, self::FLOAT);
            settype($value, 'float');
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage()); 
        }
        return $value;
    }

    /**
     * clean_string 
     * 
     * @param string $input 
     * @access public
     * @return void
     */
    public function clean_string($input = '')
    {
        try 
        {
            $value = $this->white_list_cleaner($input, self::ALPHA_DASH);
            settype($value, 'string');
        }
        catch (Exception $e) 
        {
            throw new Exception($e->getMessage());
        }
        return $value;
    }
    
    public function clean_email( $email = '' ){
	    
	    $sanitized_c = filter_var($email , "email");
		if (filter_var($sanitized_c, "email")):
		    
		    return $sanitized_c;   
		else:
			return $email;
		endif;
    }
}
/* End of Sanitize.php */
