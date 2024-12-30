<?php

/**
 * @author Asakura
 * @update_credits Buzzard, Niek, Erik
 * @desc Generates a random unpronounceable password of any type and length that you choose
 * @param $length int : Integer representation of the password length
 * @param $include_symbols bool : Determines the use of symbols in the password
 * @param $limit string : Determines the set of characters to use; "all", "alpha", "numeric", or "symbols"
 * @param $case string : Determines the case of the password; "lower", "upper", or "mixed"
 * @return string
 * @exception When using for direct html output iff $include_symbols == true; use str_replace('<','&lt;',randpass()) to prevent mismatched lengths and/or accidental html tags from appearing
 * @example $my_pass = randpass(12, false, "all", "upper"); //returns a 12 character alphanumeric upper case string
 */

function randpass($length="8", $include_symbols = true, $limit = "all", $case = "mixed" )
{
    // Initial Check
    if(!$length || !(is_numeric($length)))
        die('$length cannot be "null", "0", or any alpha or symbolic character!');
    if(!(is_bool($include_symbols)))
        die('Non-Boolean value passed to $include_symbols, please use "true" or "false"');
    if($limit == "symbols" && !$include_symbols)
        die('$limit cannot be "symbols" when $include_sybmbols is "false"');

    $password = "";

    // Generate a to z
    for($i = 97; $i <= 122; $i++) $alpha[]   = chr($i);
    // Generate 0 to 9
    for($i = 48; $i <= 57; $i++)  $numbers[] = chr($i);
    // Generate 32 symbols
    for($i=33; $i<=126; $i++)
    {
        if($i>=33 && $i<=47)
            $symbols[] = chr($i);
        if($i>=58 && $i<=64)
            $symbols[] = chr($i);
        if($i>=91 && $i<=96)
            $symbols[] = chr($i);
        if($i>=123)
            $symbols[] = chr($i);
    }

    $limit = strtolower($limit);

    if($limit == "all")          $characters = array_merge($alpha, $numbers);
    else if($limit == "alpha")   $characters = $alpha;
    else if($limit == "numeric") $characters = $numbers;

    /*
    Doesn't matter if the symbols is false and the limit is 'symbols', initial check should
    have stop the process if it has happened already.
    */
    if($include_symbols === true && ($limit == "symbols" || $limit == "all" || $limit == "numeric" || $limit == "alpha"))
    {
        if($characters) $characters = array_merge($characters, $symbols);
        else $characters = $symbols;
    }

    // Error Checking
    if(empty($characters))
        die('Invalid key passed to $limit, valid keys include; "all", "alpha", "numeric" and "symbols"');
    
    // Generating Password
    for($i=0; $i<=$length; $i++)
    {                     
        $rand = rand(0, COUNT($characters));
        if($case == "lower")         
            $password .= strtolower($characters[$rand]);
        elseif($case == "upper")
    
            $password .= strtoupper($characters[$rand]);
        elseif ($case == "mixed")
        {
            if (rand(1, 2) == 1)
                $password .= strtolower($characters[$rand]);
            else
                $password .= strtoupper($characters[$rand]);
        }
        else
            $password .= $characters[$rand];               
    }
    
    return $password;
}

//call function;
//echo randpass($length="3", $include_symbols = false, $limit = "alpha", $case = "upper" );
?>
