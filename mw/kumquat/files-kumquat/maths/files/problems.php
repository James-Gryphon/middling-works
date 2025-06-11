<?php
/* Problems:
0: 1-digit addition, max 1-digit
1: 1-digit addition, max 2-digits
2: 1-digit subtraction
3: 2-digit addition, max 2-digits
4: 2-digit addition, max 3-digits
5: 2-digit subtraction
6: 3-digit addition
7: 3-digit subtraction
8: 1-digit multiplication
9: 2-digit multiplication, up to 15
10: 2-digit division, with 1-digit denominators; no decimals
11: 3-digit multiplication, with 1-digit multipliers
12: 2-digit division, with 2-digit denominators; no decimals
13: 2-digit multiplication, up to 25
14: 3-digit multiplication, with 2-digit multipliers
15: 4-digit addition
16: 4-digit subtraction
17: Decimal addition
18: Decimal subtraction
19: Single-digit fractions
20: Multi-group addition
*/

// 0: 1-digit addition, max 1-digit
function prob_0()
{
    $array['identifier'] = "Addition 1: Single Digits";
    $var = rand(1,8);
    $temp_var = 9 - $var;
    $var2 = rand(1,$temp_var);
    $_SESSION['maths_solution'] = $var + $var2;
    $array['string'] = "{$var} + {$var2} = ?";
    return $array;
}

// 1: 1-digit addition, max 2-digits
function prob_1()
{
    $array['identifier'] = "Addition 2: Two-Digit Sums";
    $var = rand(1,9);
    $var2 = rand(1,9);
    $_SESSION['maths_solution'] = $var + $var2;
    $array['string'] = "{$var} + {$var2} = ?";
    return $array;
}

// 2: 1-digit subtraction
function prob_2()
{
    $array['identifier'] = "Subtraction 1: Single-Digit Sums";
    $var = rand(1,9);
    $var2 = rand(1,$var);
    $_SESSION['maths_solution'] = $var - $var2;
    $array['string'] = "{$var} - {$var2} = ?";
    return $array;
}

// 3: 2-digit addition, max 2-digits
function prob_3()
{
    $array['identifier'] = "Addition 3: Two-Digit Problems";
    $var = rand(10,98);
    $var2max = 99 - $var; // to prevent a three-digit solution
    $var2 = rand(1,$var2max);
    $_SESSION['maths_solution'] = $var + $var2;
    $array['string'] = "{$var} + {$var2} = ?";
    return $array;
}

// 4: 2-digit addition, max 3-digits
function prob_4()
{
    $array['identifier'] = "Addition 4: Three-Digit Sums";
    $var = rand(10,99);
    $var2 = rand(10,99);
    $_SESSION['maths_solution'] = $var + $var2;
    $array['string'] = "{$var} + {$var2} = ?";
    return $array;
}

// 5: 2-digit subtraction
function prob_5()
{
    $array['identifier'] = "Subtraction 2: Two-Digit Problems";
    $var = rand(10,99);
    $var2 = rand(10,$var);
    $_SESSION['maths_solution'] = $var - $var2;
    $array['string'] = "{$var} - {$var2} = ?";
    return $array;
}

// 6: 3-digit addition
function prob_6()
{
    $array['identifier'] = "Addition 5: 3-Digit Addition";
    $var = rand(100,999);
    $var2 = rand(100,999);
    $_SESSION['maths_solution'] = $var + $var2;
    $array['string'] = "{$var} + {$var2} = ?";
    return $array;
}

function prob_7()
{
    $array['identifier'] = "Subtraction 3: Three-Digit Problems";
    $var = rand(100,999);
    $var2 = rand(100,$var);
    $_SESSION['maths_solution'] = $var - $var2;
    $array['string'] = "{$var} - {$var2} = ?";
    return $array;
}

function prob_8()
{
    $array['identifier'] = "Multiplication 1";
    $var = rand(1,9);
    $var2 = rand(1,9);
    $_SESSION['maths_solution'] = $var * $var2;
    $array['string'] = "{$var} • {$var2} = ?";
    return $array;
}

function prob_9()
{
    $array['identifier'] = "Multiplication 2";
    $var = rand(2,15);
    $var2 = rand(2,15);
    $_SESSION['maths_solution'] = $var * $var2;
    $array['string'] = "{$var} • {$var2} = ?";
    return $array;
}

function prob_10()
{
    $array['identifier'] = "Division 1";
    /* Most problems up to this point have been simple.
    Later on we might be able to make division problems
    that aren't, but right now, we want them to have no
    decimals or fractions. That ironically means that
    this code will be comparatively complex.
    */
    $primetest = 2;
    while($primetest > 0)
    {
        $var = rand(10,99);
        $primetest = gmp_prob_prime($var);
    }
    $modtest = 1;
    while($modtest != 0){
    $var2 = rand(2,9);
    $modtest = $var % $var2;
    }
    $_SESSION['maths_solution'] = $var / $var2;
    $array['string'] = "{$var} ÷ {$var2} = ?";
    return $array;
}

function prob_11()
{
    $array['identifier'] = "Multiplication 3";
    $var = rand(2,25);
    $var2 = rand(2,25);
    $_SESSION['maths_solution'] = $var * $var2;
    $array['string'] = "{$var} • {$var2} = ?";
    return $array;
}

function prob_12()
{
    $array['identifier'] = "Division 2";
    /* The original Division 2 was supposed to be two-digit evenly divisible problems,
    but it constantly broke and I couldn't seem to make a loop that fixed it, so
    there's a change of plans. Now we're starting remainders here (they were previously introduced in Div3)
    ---
    This is a complicated one for the user.
    Not only are we doing division problems, we also want to get the remainder left after dividing.
    This is going to take clarification, so that the user understands what is expected of them and won't arbitrarily miss problems.
    */
        $var = rand(10,99);
        $var2 = rand(2,9);
    $_SESSION['maths_solution'] = intdiv($var, $var2);
    $_SESSION['maths_solution'] .= "r";
    $temp = $var % $var2;
    $_SESSION['maths_solution'] .= $temp;
    $array['string'] = "{$var} ÷ {$var2} = ?";
    return $array;
}

function prob_13()
{
    $array['identifier'] = "Multiplication 4";
    $var = rand(100,999);
    $var2 = rand(2,9);
    $_SESSION['maths_solution'] = $var * $var2;
    $array['string'] = "{$var} • {$var2} = ?";
    return $array;
}

function prob_14()
{
    $array['identifier'] = "Multiplication 5";
    $var = rand(10,99);
    $var2 = rand(10,99);
    $_SESSION['maths_solution'] = $var * $var2;
    $array['string'] = "{$var} • {$var2} = ?";
    return $array;
}

function prob_15()
{
    $array['identifier'] = "Addition 6";
    $var = rand(1000,9999);
    $var2 = rand(1000,9999);
    $_SESSION['maths_solution'] = $var + $var2;
    $array['string'] = "{$var} + {$var2} = ?";
    return $array;
}

function prob_16()
{
    $array['identifier'] = "Subtraction 4";
    $var = rand(1000,9999);
    $var2 = rand(1000,9999);
    if($var2 > $var){var_swap($var, $var2);}
    $_SESSION['maths_solution'] = $var - $var2;
    $array['string'] = "{$var} - {$var2} = ?";
    return $array;
}

function prob_17()
{
    $array['identifier'] = "Decimals 1";
    $var = rand(100,998);
    $random = rand(1,2);
    $var += 1;
    $var = round($var / (10**$random),2);
    $var2 = rand(100,998);
    $random = rand(1,2);
    $var2 += 1;
    $var2 = round($var2 / (10**$random),2);
    $random = rand(1,2);
    if($random === 1)
    {
        $_SESSION['maths_solution'] = strval($var + $var2);
        $array['string'] = "{$var} + {$var2} = ?";
    }
    else 
    { 
        if($var2 > $var) {var_swap($var, $var2);}
        $_SESSION['maths_solution'] = strval($var - $var2);
        $array['string'] = "{$var} - {$var2} = ?";
    }
    return $array;
}

function prob_18()
{
    $array['identifier'] = "Division 3";
    /* This no longer introduces remainders, since Div2 got overhauled.
    */
        $var = rand(100,999);
        $var2 = rand(2,9);
    $_SESSION['maths_solution'] = intdiv($var, $var2);
    $_SESSION['maths_solution'] .= "r";
    $temp = $var % $var2;
    $_SESSION['maths_solution'] .= $temp;
    $array['string'] = "{$var} ÷ {$var2} = ?";
    return $array;
}

function prob_19()
{
    $array['identifier'] = "Decimals 2";
    $var = rand(10,50);
    $random = rand(1,2);
    $var = round($var / (10**$random),1);
    $var2 = rand(10,50);
    $random = rand(1,2);
    $var2 = round($var2 / (10**$random),1);
        $_SESSION['maths_solution'] = strval(round($var * $var2,2));
        $array['string'] = "{$var} • {$var2} = ?";
    return $array;
}

function prob_20()
{
    $array['identifier'] = "Modulo 1";
    // This will require explanation, since most people aren't familiar with it
    $var = rand(10,99);
    $var2 = rand(1,9);
        $_SESSION['maths_solution'] = $var % $var2;
        $array['string'] = "{$var} % {$var2} = ?";
    return $array;
}

function prob_21()
{
    $array['identifier'] = "Modulo 2";
    $var = rand(100,999);
    $var2 = rand(1,99);
        $_SESSION['maths_solution'] = $var % $var2;
        $array['string'] = "{$var} % {$var2} = ?";
    return $array;
}

function prob_22()
{
    $array['identifier'] = "Decimals 3";
    $var = rand(100,500);
    $random = rand(1,2);
    $var = round($var / (10**$random),1);
    $var2 = rand(11,99);
    if($var2 > 14 && $var2 < 95)
    { // bit of a hacky way to prevent 1s and 0.1s -- revise this later
    $random = rand(1,2);
    $var2 = round($var2 / (10**$random),1);
    }
        $_SESSION['maths_solution'] = strval(round($var / $var2, 2));
        $array['string'] = "{$var} ÷ {$var2} = ?";
    return $array;
}

function prob_23()
{
    // This will require special instructions for users, on how to get the format right.
    /*
    This could be a complex section to program.
    Solving a fraction involves several steps.
    First, see if the denominators match.
    If they don't, then you need to get them to.
    In practice, you want to find the least common denominator, but in our case, it might be simpler to multiply the two, and figure
    out how to simplify later. (or use gmp_lcm)
    We also have to adjust the numerator.
    Then we do whatever we're doing with the numerator - add, divide, etc.
    If the numerator is greater than the denominator, then figure out the answer by dividing the numerator as many times as it takes
    by the denominator. The remainder goes into the numerator, the denominator stays the same, and the overflow is in a whole int before.
    How to simplify: check if the numerator is a prime. If it is, nothing doing.

    */
    $array['identifier'] = "Fractions 1";
    $var = rand(1,9);
    $var2 = rand($var+1,$var*2);
    $var3 = rand(1,9);
    $var4 = rand($var3+1,$var3*2);
    $random = rand(1,2); // used to decide if adding or subtracting (in that order)
    // There's quite a bit of difference between the solution and the problem string here.
    // The problem string should be prettified fractions. The solution, however, will take advantage of decimals.
    if($var2 != $var4)
    { // The denominators aren't equal, so make them (and the numerators) equal.
        $tmp_d = $var2 * $var4;
        $num_1 = $var * $var4; // multiply by opposing denominators
        $num_2 = $var3 * $var2;
    } 
    else {$tmp_d = $var2; $num_1 = $var; $num_2 = $var3;}
    if($random == 2 && $num_2 > $num_1)
    { // flip $vars, because we can't allow negatives
        $temp_var = $var3;
        $var3 = $var;
        $var = $temp_var;
        $temp_var = $var4;
        $var4 = $var2;
	$var2 = $temp_var;
	$temp_var = $num_1;
	$num_1 = $num_2;
	$num_2 = $temp_var;
    }
    // Now, the denominators are the same and the numerators are adjusted.
    // If adding, account for overflow.
        if($random == 1)
    {
        $solution = "";
        $temp_add = $num_1 + $num_2;
        if($temp_add >= $tmp_d)
        {
            $fv = gmp_div_qr($temp_add, $tmp_d);
            $fnf = intval($fv[1]) / $tmp_d;
            $fnf = lcd_finder($fnf);
            $solution = "{$fv[0]}";
        }
        if($fnf['d'] != 0)
        {
        $solution .= " {$fnf['d']}/{$fnf['n']}";
        }
        $_SESSION['maths_solution'] = $solution;
        $array['string'] = "<span class='fraction'>$var<hr>$var2</span> + <span class='fraction'>$var3<hr>$var4</span>";
    }
    else
    {
        $temp_sub = $num_1 - $num_2;
        $fnf = $temp_sub / $tmp_d;
        $fnf = lcd_finder($fnf);
        if($fnf['d'] != 0) // we'll need to rewrite this later on, to allow for integers, not just fractions
        {
            $_SESSION['maths_solution'] = "{$fnf['d']}/{$fnf['n']}";
        }
        else {$_SESSION['maths_solution'] = "0";}
        $array['string'] = "<span class='fraction'>$var<hr>$var2</span> - <span class='fraction'>$var3<hr>$var4</span>";
    }
    return $array;
}

function prob_24()
{   // Need to make this different from decimals somehow; right now it seems real similar
    $array['identifier'] = "Percentages 1";
    $var = rand(1,100);
    $var2 = rand(1,100);
    $percent = $var / 100;
    $solution = strval(round($percent * $var2,2));
    $_SESSION['maths_solution'] = $solution;
    $array['string'] = "{$var}% of {$var2} = ?";
    return $array;
}

function prob_26()
{ // Negative numbers, adding and subtracting
    $array['identifier'] = "Negative Numbers 1";
    // Include something here to make sure that positive addition, and higher-than-0 subtraction, is ruled out
    $var = rand(-99,99);
    $var2 = rand(-99,99);
    $random = rand(1,2);
    if($random === 1)
    {
        if($var > 0 && $var2 > 0)
        {// positive-only addition; can't have that
            $random = rand(1,2);
            if($random === 1){$var *= -1;} else {$var2 *= -1;}
        }
        $solution = $var + $var2;
        $array['string'] = "{$var} + {$var2} = ?";
    }
    else 
    {
        if($var > 0 && $var2 > 0 && $var >= $var2){
            // positive subtraction; can't have that
            $random = rand(1,4); // first two make negative numbers; otherwise, we make positive numbers that end with a negative sum
            if($random === 1){ $var *= -1;} elseif($random === 2){$var2 *= -1;}elseif($random === 3){ $temp_var = $var; $var = $var2; $var2 = $temp_var;}
        }
        $solution = $var - $var2;
        $array['string'] = "{$var} - {$var2} = ?";
    }
    $_SESSION['maths_solution'] = $solution;
    return $array;
}

function prob_27()
{
    // Almost the same as the other one, but with multiplication and division
    $array['identifier'] = "Fractions 2";
    $var = rand(1,9);
    $var2 = rand($var+1,$var*2);
    $var3 = rand(1,9);
    $var4 = rand($var3+1,$var3*2);
    $random = rand(1,2); // used to decide if adding or subtracting (in that order)
    // There's quite a bit of difference between the solution and the problem string here.
    // The problem string should be prettified fractions. The solution, however, will take advantage of decimals.
    if($var2 != $var4)
    { // The denominators aren't equal, so make them (and the numerators) equal.
        $tmp_d = $var2 * $var4;
        $num_1 = $var * $var4; // multiply by opposing denominators
        $num_2 = $var3 * $var2;
    } 
    else {$tmp_d = $var2; $num_1 = $var; $num_2 = $var3;}
    // Now, the denominators are the same and the numerators are adjusted.
    // If adding, account for overflow.
        if($random == 1)
    {
        $solution = "";
        $temp_add = $num_1 * $num_2;
        if($temp_add >= $tmp_d)
        {
            $fv = gmp_div_qr($temp_add, $tmp_d);
            $fnf = intval($fv[1]) / $tmp_d;
            $fnf = lcd_finder($fnf);
            $solution = "{$fv[0]}";
        }
        if($fnf['d'] != 0)
        {
        $solution .= " {$fnf['d']}/{$fnf['n']}";
        }
        $_SESSION['maths_solution'] = $solution;
        $array['string'] = "<span class='fraction'>$var<hr>$var2</span> • <span class='fraction'>$var3<hr>$var4</span>";
    }
    else
    {
        $temp_sub = $num_1 / $num_2;
        $fnf = $temp_sub / $tmp_d;
        $fnf = lcd_finder($fnf);
        if($fnf['d'] != 0) // we'll need to rewrite this later on, to allow for integers, not just fractions
        {
            $_SESSION['maths_solution'] = "{$fnf['d']}/{$fnf['n']}";
        }
        else {$_SESSION['maths_solution'] = "0";}
        $array['string'] = "<span class='fraction'>$var<hr>$var2</span> ÷ <span class='fraction'>$var3<hr>$var4</span>";
    }
    return $array;
}

function prob_28()
{
    $array['identifier'] = "Percentages 2";
    $var = rand(1,100);
    $var2 = rand(1,100);
    $solution = strval(round($var / $var2, 2) * 100);
    $solution .= "%";
    $_SESSION['maths_solution'] = $solution;
    $array['string'] = "What percentage of {$var2} is {$var} = ?";
    return $array;
}

function prob_30()
{
    $array['identifier'] = "Negative Numbers 2";
    $var = 0;
    while($var == 0){
    $var = rand(-25,25); }
    $var2 = 0;
    while($var2 == 0){
    $var2 = rand(-25,25);}
    if($var > 0 && $var2 > 0){ // prevent positive-only problems
        $random = rand(1,2);
        if($random === 1){$var *= -1;} else {$var2 *= -1;}
    }
    $random = rand(1,2);
    if($random == 1)
    {
        $_SESSION['maths_solution'] = $var * $var2;
        $array['string'] = "{$var} • {$var2} = ?"; 
    }
    else {
        $_SESSION['maths_solution'] = strval(round($var / $var2, 2));
        $array['string'] = "{$var} ÷ {$var2} = ?"; 
        }
    return $array;
}

function prob_32()
{
    // This gets more into "word problem" territory. Maybe it will eventually have word problems.
    $array['identifier'] = "Discounts";
    $var = rand(1,99);
    $var2 = rand(1,999);
    $percent = $var / 100;
    $solution = round($var2 - ($var2 * $percent), 2);
    $_SESSION['maths_solution'] = $solution;
    $array['string'] = "If {$var}% off, \${$var2} = ?";
    return $array;
}

function prob_25()
{
    $array['identifier'] = "Multiple Numbers 1";
    $problems = [];
    $operators = [];
    $p = rand(3,4);
    while($p > 0)
    {
        $var = rand(1,99);
        $random = rand(1,2);
        $var = round($var / (10**$random),2);
        $problems[] = $var;
        $p -= 1;
        $random = rand(1,2);
        $operators[] = $random;
    }
    $_SESSION['maths_solution'] = $problems[0];
    $array['string'] = "{$problems[0]}";
    unset($problems[0]);
    foreach($problems as $key => $value)
    {
        if($operators[$key-1] == 1)
        {
        $_SESSION['maths_solution'] = strval($_SESSION['maths_solution'] + $value);
        $array['string'] .= " + {$value}";
        }
        else
        {
            $_SESSION['maths_solution'] = strval($_SESSION['maths_solution'] - $value);
            $array['string'] .= " - {$value}";
        }
        
    }
    return $array;
}

function prob_29()
{
    $array['identifier'] = "Multiple Numbers 2";
    $problems = [];
    $operators = [];
    $p = rand(3,4);
    while($p > 0)
    {
        $var = rand(1,100);
        $random = rand(1,2);
        $var = round($var / (10**$random),1);
        $problems[] = $var;
        $p -= 1;
        $random = rand(1,2);
        $operators[] = $random;
    }
    $_SESSION['maths_solution'] = $problems[0];
    $array['string'] = "{$problems[0]}";
    unset($problems[0]);
    foreach($problems as $key => $value)
    {
        if($operators[$key-1] == 1)
        {
        $_SESSION['maths_solution'] = strval($_SESSION['maths_solution'] * $value);
        $array['string'] .= " • {$value}";
        }
        else
        {
            $_SESSION['maths_solution'] = strval($_SESSION['maths_solution'] / $value);
            $array['string'] .= " ÷ {$value}";
        }
    }
    return $array;
}

function prob_31()
{
    // The focus here is on old British currency
    $array['identifier'] = "British Old Money";
    /* There are a lot of minor British coins, including crowns, but in Unit 1, we're going
    to stick with pence, shillings, and pounds.
    */
    $var = rand(2,480);
    $var2 = rand(1,$var);
    $random = rand(1,2);
    if($random === 1)
    {
        $solution = $var + $var2;
        $var_string = UKCurrency($var);
        $var2_string = UKCurrency($var2);
        $solution = UKCurrency($solution);
        $array['string'] = "{$var_string} + {$var2_string}";
    } 
    else 
    {
        $solution = $var - $var2;
        $var_string = UKCurrency($var);
        $var2_string = UKCurrency($var2);
        $solution = UKCurrency($solution);
        $array['string'] = "{$var_string} - {$var2_string}";    
    }
    $_SESSION['maths_solution'] = $solution;
    return $array;
}

function prob_33()
{
    /* The focus here is on US liquid measurements */
    $array['identifier'] = "Liquid Measurements";
    $var = rand(1,1024);
    $var2 = rand(1,1024);
    if($var2 > $var){
        // no need for negative measurements
        $temp_var = $var; $var = $var2; $var2 = $temp_var;
    }
    $var_measures = USLiquidMeasures($var);
    $var2_measures = USLiquidMeasures($var2);
    $var_measures = USLiquidMeasureString($var_measures);
    $var2_measures = USLiquidMeasureString($var2_measures);

    $random = rand(1,2); // adding and subtracting
    if($random === 1)
    {
        $var_number = $var + $var2;
        $solution_measures = USLiquidMeasures($var_number);
        $solution = USLiquidMeasureString($solution_measures);
        $array['string'] = "{$var_measures} + {$var2_measures}";
    }
    if($random === 2)
    {
        $var_number = $var - $var2;
        $solution_measures = USLiquidMeasures($var_number);
        $solution = USLiquidMeasureString($solution_measures);
        $array['string'] = "{$var_measures} - {$var2_measures}";
    }
    $_SESSION['maths_solution'] = $solution;
    return $array;
}

function prob_34()
{
    $array['identifier'] = "Division 4";
    /* Harder than decimals?
    */
        $var = rand(100,2500);
        $var2 = rand(11,99);
    $_SESSION['maths_solution'] = intdiv($var, $var2);
    $_SESSION['maths_solution'] .= "r";
    $temp = $var % $var2;
    $_SESSION['maths_solution'] .= $temp;
    $array['string'] = "{$var} ÷ {$var2} = ?";
    return $array;
}

function prob_35()
{
    $array['identifier'] = "Roman Numerals";
    $random = rand(1,3); // Division is broken for now

    switch($random)
    {
        case 2:
            $var = rand(2,3999);
            $var2 = rand(1,$var);
            $solution = $var - $var2;
            $operator = "-";
            break;
        case 3:
            $var = rand(2,63);
            $var2 = rand(2,63);
            $solution = $var * $var2;
            $operator = "•";
            break;
        case 4:
            $var = rand(2,2500);
            $var2 = rand(2,50);
            $solution = $var / $var2;
            $solution .= "r"; $solution .= $var % $var2; // This doesn't work with Roman numerals, refactor or round
            $operator = "÷";
            break;
        case 1:
        default: 
            $var = rand(2,2000);
            $var2 = rand(1,1999);
            $solution = $var + $var2;
            $operator = "+";
        break;
    }
    $var_string = RomanNumerals($var);
    $var2_string = RomanNumerals($var2);
    $array['string'] = "{$var_string} {$operator} {$var2_string}";
    $solution = RomanNumerals($solution);
    $_SESSION['maths_solution'] = $solution;
    return $array;
}

function prob_36()
{
    $array['identifier'] = "Exponents 1";
    $var = rand(2,9);
    $var2 = rand(1,3);
    $_SESSION['maths_solution'] = $var ** $var2;
    $array['string'] = "{$var}<sup>{$var2}</sup> = ?";
    return $array;
}

function prob_37()
{
    $array['identifier'] = "Liquid Conversions";
    $measure_type = "liquid";
    $type = rand(1,2); // 1 means the problem is English > Metric; 2 means Metric > English
    if($type === 1) // US
    {
        $var = rand(1,2029); // in tsp; 2.64 gallons, 10000 mL
        $measures = USMeasureChooser($var, "liquid", "random", 1);
    }
    else
    {
        $var = rand(1,10000); // in mL; 2029 tsp, 2.64 gallons
        $measures = MetricChooser($var);
    }
    if(isset($measures['var'])){$var = $measures['var'];}
    $question = $measures['string'];
    $solution = MeasureConverter($measures, $type, $measure_type, $var);
    if($type === 1){$problem_string = "{$solution['prefix']}liters";}
    else{$problem_string = "{$solution['type']}";}
    // Now to adjust the digits
    $solution_num = comp_rounder($solution['number']);
    $_SESSION['maths_solution'] = "{$solution_num} {$solution['type']}";
    $array['string'] = "Convert to {$problem_string}: {$question} = ?";
    return $array;
}

function prob_38()
{
    $array['identifier'] = "Weight Conversions";
    // Because we're only using two measures, this is very simple
    // I could expand the measure converter, and maybe should, but for now...
    $question_string = "lb"; $solution_string = "kg";
    $type = rand(1,2); // 1 means the problem is US > Metric; 2 means Metric > US
    if($type === 1) // US
    {
        $question = rand(1,250); // in kg, 113.4
        $solution = $question * 0.45359237;
    }
    else
    {
        $question = rand(1,113); // in pounds, 249.12
        $solution = $question * 2.204622622;
        var_swap($question_string, $solution_string);
    }
    // Now to adjust the digits
    $solution = comp_rounder($solution);
    $_SESSION['maths_solution'] = "{$solution} {$solution_string}";
    $array['string'] = "Convert to {$solution_string}: {$question} {$question_string} = ?";
    return $array;
}

function prob_39()
{
    $array['identifier'] = "Length Conversions";
    /* so we have four lengths of each:
    miles - kilometers
    yards - meters
    feet - 
    inches - centimeters
           - millimeters
    ten types of problems; let's see if we can make it simple
    */
    $array = [
        0 => ["name" => "mile", "plural" => "s", "symbol" => "mi", "convert" => 1.609344, "to" => "km"],
        1 => ["name" => "kilometer", "plural" => "s", "symbol" => "km", "convert" => 0.621371192, "to" => "mi"],
        2 => ["name" => "yard", "plural" => "s", "symbol" => "yd", "convert" => 0.9144, "to" => "m"],
        3 => ["name" => "feet", "plural" => "", "symbol" => "ft", "convert" => 0.3048, "to" => "m"],
        4 => ["name" => "meter", "plural" => "s", "symbol" => "m", "convert" => 1.093613298, "to" => "yd"],
        5 => ["name" => "meter", "plural" => "s", "symbol" => "m", "convert" => 3.280839895, "to" => "ft"],
        6 => ["name" => "inch", "plural" => "es", "symbol" => "in", "convert" => 2.54, "to" => "cm"],
        7 => ["name" => "inch", "plural" => "es", "symbol" => "in", "convert" => 25.4, "to" => "mm"],
        8 => ["name" => "centimeter", "plural" => "s", "symbol" => "cm", "convert" => 0.393700787, "to" => "in"],
        9 => ["name" => "millimeter", "plural" => "s", "symbol" => "mm", "convert" => 0.039370079, "to" => "in"],
    ];
    $type = rand(0,9);
    $question = rand(1,100);
    $solution = $question * $array[$type]['convert'];
    // Now to adjust the digits
    $solution = comp_rounder($solution);
    $_SESSION['maths_solution'] = "{$solution} {$array[$type]['to']}";
    $array['string'] = "Convert to {$array[$type]['to']}: {$question} {$array[$type]['symbol']} = ?";
    return $array;
    // I think we did it! This might be the new model for these problems
}

function prob_40()
{
    $array['identifier'] = "Exponents 2";
    $var = rand(2,9);
    $var2 = rand(-1,4);
    $_SESSION['maths_solution'] = round($var ** $var2,3);
    $array['string'] = "{$var}<sup>{$var2}</sup> = ?";
    return $array;
}

function prob_41()
{ // Let's try to make this as easy as possible
    $array = [
        0 => ["name" => "pound", "plural" => "s", "symbol" => "lb", "convert" => 0.45359237, "to" => "kg"],
        1 => ["name" => "pound", "plural" => "s", "symbol" => "lb", "convert" => 16, "to" => "oz"],
        2 => ["name" => "kilogram", "plural" => "s", "symbol" => "kg", "convert" => 2.204622622, "to" => "lb"],
        3 => ["name" => "kilogram", "plural" => "s", "symbol" => "kg", "convert" => 35.273962105, "to" => "oz"],
        4 => ["name" => "ounce", "plural" => "s", "symbol" => "oz", "convert" => 0.0625, "to" => "lb"],
        5 => ["name" => "ounce", "plural" => "s", "symbol" => "oz", "convert" => 0.028349523, "to" => "kg"],
    ];
    
    $array['identifier'] = "Price Comparisons";
    $type = rand(0,5);
    $var = rand(10,99);
    $price = rand(3,50);
    // this is kinda stupid, but...
    $var2_floor = $var * .75 * $array[$type]['convert'] * 10000000000;
    $var2_ceil = $var * 1.25 * $array[$type]['convert'] * 10000000000;
    $var2 = rand($var2_floor, $var2_ceil);
    $var2 /= 10000000000;
    $rand = rand(0,2);
    $var2 = round($var2, $rand);
    $price2_floor = $price * .75 * 10000000000;
    $price2_ceil = $price * 1.25 * 10000000000;
    $price2 = rand($price2_floor, $price2_ceil);
    $price2 /= 10000000000;
    $price2 = round($price2, 2);
    $problem_string_1 = "{$var} {$array[$type]['symbol']} at \$$price";
    $problem_string_2 = "{$var2} {$array[$type]['to']} at \$$price2";
    $solution_1 = round($price / $var,2);
    $solution_2 = round($price2 / $var2 * $array[$type]['convert'],2);
    if($solution_1 > $solution_2)
    {
        $solution = ">";
    }
    elseif($solution_2 > $solution_1){$solution = "<";}
    else{$solution = "=";}

    $_SESSION['maths_solution'] = $solution;
    $array['string'] = "Compare {$problem_string_1} and {$problem_string_2}.";
    return $array;
}

function prob_42()
{
    $array['identifier'] = "Exponents 3";
    $var = rand(-15,15);
    $var2 = rand(-3,3);
    $_SESSION['maths_solution'] = $var ** $var2;
    $array['string'] = "{$var}<sup>{$var2}</sup> = ?";
    return $array;
}

function prob_43()
{ // The first binary problem!
    $array['identifier'] = "Binary 1";
    $rand = rand(1,2);
    $var = rand(1,255);
    if($rand === 1)
    { // decimal to binary
        $string = sprintf("%d to a binary", $var);
        $solution = sprintf("%'08b", $var);
    }
    else
    {
        $string = sprintf("<code>%'08b</code> to a decimal", $var);
        $solution = $var;
    }
    $_SESSION['maths_solution'] = $solution;
    $array['string'] = "Convert {$string} number.";
    return $array;
}

function prob_44()
{   // This should be fun
    // We're doing addition/subtraction up to 255 here
    $array['identifier'] = "Binary 10";
    $rand = rand(1,2);
    if($rand === 1)
    {
        $solution = rand(2,255);
        $var = rand(1,$solution-1);
        $var2 = $solution - $var;
        $operator = "+";
    }
    else
    {
        $solution = rand(0,254);
        $var = rand($solution+1,255);
        $var2 = $var - $solution;
        $operator = "-";
    }
    $solution = sprintf("%'08b", $solution);
    $var = sprintf("%'08b", $var);
    $var2 = sprintf("%'08b", $var2);
    $_SESSION['maths_solution'] = $solution;
    $array['string'] = "{$var} {$operator} {$var2} = ?";
    return $array;
}

function prob_45()
{   // Multiplication and division
    $array['identifier'] = "Binary 11";
    $rand = rand(1,2);
    if($rand === 1)
    {
        $var = rand(2,16);
        $var2 = rand(2,16);
        if($var === 16 && $var2 === 16){ --$var2; } // prevent overflow
        $operator = "•";
        $solution = $var * $var2;
    }
    else
    { // based on Division 1
        $primetest = 2;
        while($primetest > 0)
        {
            $var = rand(9,255);
            $primetest = gmp_prob_prime($var);
        }
        $modtest = 1;
        while($modtest != 0){
        $var2 = rand(2,9);
        $modtest = $var % $var2;
        }
        $operator = "÷";
        $solution = $var / $var2;
    }
    $solution = sprintf("%'08b", $solution);
    $var = sprintf("%'08b", $var);
    $var2 = sprintf("%'08b", $var2);
    $_SESSION['maths_solution'] = $solution;
    $array['string'] = "{$var} {$operator} {$var2} = ?";
    return $array;
}

/* Tools: Not problems, but used in problems */

function lcd_finder($m)
{ // this is a method for finding the smallest possible denominator for a fraction
    // convert it to a decimal, then brute-force
    $t = 0;
    $i = 2;
    while($t === 0)
    {
        $var = $m * $i;
        if(intval($var) == $var)
        {
            $result = ["n" => $i, "d" => $var];
            $t = 1;
        }
        $i += 1;
    }
    return $result;
}

function UKCurrency($pence, $only = 0)
{ // converts random pence into UK currency
    $currency = [];
    if($only === 0 || $only === "pounds")
    {
        $currency['gpnd'] = gmp_div_qr($pence, 240);
        $pence = $currency['gpnd'][1];
        $currency['gpnd'] = $currency['gpnd'][0];
        if($only !== 0){$currency['gpnd'] += $pence/240;}
    };
    if($only === 0 || $only === "shillings")
    {
        $currency['gshl'] = gmp_div_qr($pence, 12);
        $pence = $currency['gshl'][1];
        $currency['gshl'] = $currency['gshl'][0];
        if($only !== 0){$currency['gshl'] += $pence/12;}
    };
    if($only === 0 || $only === "pence")
    {
        $currency['gp'] = gmp_div_qr($pence, 1);
        $pence = $currency['gp'][1];
        $currency['gp'] = $currency['gp'][0];
        if($only !== 0){$currency['gp'] += $pence;}
    };
    // now build the string
    $string = "";
    if($currency['gpnd'] != 0){ $string .= "£{$currency['gpnd']}";}
    if($currency['gpnd'] != 0 && ($currency['gshl'] > 0 || $currency['gp'] > 0)){$string .= "/";} 
    if($currency['gshl'] != 0){ $string .= "{$currency['gshl']}/";}
    elseif($currency['gpnd'] > 0){ $string .= "-/";}
    if($currency['gp'] > 0){ $string .= "{$currency['gp']}";}
    if($currency['gshl'] > 0 && $currency['gp'] == 0) {$string .= "-";}
    if($currency['gshl'] == 0){ $string .= "d";}
    if($string == ""){$string = "0";}
    return $string;
}

function USLiquidMeasures($numbers, $only = 0)
{
    /* Used in problems that concern US liquid measures. The base unit (in $numbers) is the teaspoon.
    If "only" is set, it returns the number in only the measure given, with a decimal remainder.
    The function returns an array, with the name of the unit as the key, and the quantity as the value.
    */
    $measures = [];
    if($only === 0 || $only === "gallons")
    {
        if($only !== 0){$measures[$only] = $numbers/768;}
        else {
        $measures['gallons'] = gmp_div_qr($numbers, 768);
        $numbers = $measures['gallons'][1];
        $measures['gallons'] = $measures['gallons'][0];
        }
    };
    if($only === 0 || $only === "quarts")
    {
        if($only !== 0){$measures[$only] = $numbers/192;}
        else {
        $measures['quarts'] = gmp_div_qr($numbers, 192);
        $numbers = $measures['quarts'][1];
        $measures['quarts'] = $measures['quarts'][0];
        }
    };
    if($only === 0 || $only === "pints")
    {
        if($only !== 0){$measures[$only] = $numbers/96;}
        else{
        $measures['pints'] = gmp_div_qr($numbers, 96);
        $numbers = $measures['pints'][1];
        $measures['pints'] = $measures['pints'][0];
        }
    };
    if($only === 0 || $only === "cups")
    {
        if($only !== 0){$measures[$only] = $numbers/48;}
        else {
        $measures['cups'] = gmp_div_qr($numbers, 48);
        $numbers = $measures['cups'][1];
        $measures['cups'] = $measures['cups'][0];
        }
    };
    if($only === 0 || $only === "ounces")
    {
        if($only !== 0){$measures[$only] = $numbers/6;}
        else {
        $measures['ounces'] = gmp_div_qr($numbers, 6);
        $numbers = $measures['ounces'][1];
        $measures['ounces'] = $measures['ounces'][0];
        }
    };
    if($only === 0 || $only === "tablespoons")
    {
        if($only !== 0){$measures[$only] = $numbers/3;}
        else{
        $measures['tablespoons'] = gmp_div_qr($numbers, 3);
        $numbers = $measures['tablespoons'][1];
        $measures['tablespoons'] = $measures['tablespoons'][0];
        }
    };
    if($only === 0 || $only === "teaspoons")
    {
        if($only !== 0){$measures[$only] = $numbers;}
        else{
        $measures['teaspoons'] = gmp_div_qr($numbers, 1);
        $measures['teaspoons'] = $measures['teaspoons'][0];
        }
    };
    return $measures;
}

function USLiquidMeasureString($measures)
{
    $string = "";
    if(isset($measures['gallons']) && $measures['gallons'] > 0){$string .= "{$measures['gallons']} gal";}
    if(isset($measures['quarts']) && $measures['quarts'] > 0){if($string !== ""){$string .= ", ";} $string .= "{$measures['quarts']} qt";}
    if(isset($measures['pints']) && $measures['pints'] > 0){if($string !== ""){$string .= ", ";} $string .= "{$measures['pints']} pt";}
    if(isset($measures['cups']) && $measures['cups'] > 0){if($string !== ""){$string .= ", ";} $string .= "{$measures['cups']} cup";}
    if(isset($measures['ounces']) && $measures['ounces'] > 0){if($string !== ""){$string .= ", ";} $string .= "{$measures['ounces']} oz";}
    if(isset($measures['tablespoons']) && $measures['tablespoons'] > 0){if($string !== ""){$string .= ", ";} $string .= "{$measures['tablespoons']} tbs";}
    if(isset($measures['teaspoons']) && $measures['teaspoons'] > 0){if($string !== ""){$string .= ", ";} $string .= "{$measures['teaspoons']} tsp";}
    return $string;
}

function USMeasureChooser($number, $measure = "liquid", $type = "random", $original = 0)
{ // Used for English-metric conversion problems
    if($measure === "liquid")
    { // the rate is 4.928921667 milliliters a teaspoon, or 0.202884133 mL to 1 teaspoon
        if($number >= 768 || $type === "gallons"){ $type = "gallons"; $short = "gal";}
        elseif($number >= 192 || $type === "quarts"){$type = "quarts"; $short = "qt";}
        elseif($number >= 96 || $type === "pints"){$type = "pints"; $short = "pt";}
        elseif($number >= 48 || $type === "cups"){$type = "cups"; $short = "cup";}
        elseif($number >= 6 || $type === "ounces"){$type = "ounces"; $short = "oz";}
        elseif($number >= 3 || $type === "tablespoons"){$type = "tablespoons"; $short = "tbs";}
        else{$type = "teaspoons"; $short = "tsp";}
        $result['array'] = USLiquidMeasures($number, $type);
        if($original === 1)
        {
            $random = rand(0,2); 
            $result['array'][$type] = round($result['array'][$type],$random);
            switch($type)
            {
                case "gallons": round($result['var'] = $result['array'][$type] * 768,0); break;
                case "quarts": round($result['var'] = $result['array'][$type] * 192,0); break;
                case "pints": round($result['var'] = $result['array'][$type] * 96,0); break;
                case "cups": round($result['var'] = $result['array'][$type] * 48,0); break;
                case "ounces": round($result['var'] = $result['array'][$type] * 6,0); break;
                case "tablespoons": round($result['var'] = $result['array'][$type] * 3,0); break;
                default: break;
            }
        }
        $result['number'] = $result['array'][$type];
        $result['type'] = $short;
        $result['string'] = USLiquidMeasureString($result['array']);
    }
    if($measure === "weight")
    { // write something analogous to the above liquid sections
        
    }
    if($measure === "length")
    { // write something analogous to the above liquid sections
    }
    return $result;
}

function MetricChooser($number, $measure = "liquid", $prefix = "random")
{ // Used to convert a number to a metric value; randomized if no prefix is provided
    if($prefix === "random")
    {
        if($measure === "liquid")
        { // the liter is the most used, with milliliters second
            if($number >= 1000){ $prefix = "base"; $number = round($number, -2); $mills = $number;}
            else{$prefix = "milli"; $mills = $number;}
        }
        elseif($measure === "weight")
        { // the kilogram is the most used, with grams and milligrams roughly tied for distant second
            $rand_num = rand(1,20); // make adjustments here to match liquids
            if($rand_num <= 10){ $prefix = "kilo";}
            elseif($rand_num <= 12){$prefix = "milli";}
            else{$prefix = "base";}
        }
        elseif($measure === "length")
        { // several lengths here enjoy regular use
            $rand_num = rand(1,20); // make adjustments here to match liquids
            if($rand_num <= 5){ $prefix = "kilo";}
            elseif($rand_num <= 10){$prefix = "milli";}
            elseif($rand_num <= 15){$prefix = "base";}
            elseif($rand_num <= 20){$prefix = "centi";}
        }
    } else {$mills = $number;}

    switch($prefix)
    {
        case "kilo": $number = $number / 1000000; $string = "k"; break;
        case "hecto": $number = $number / 100000; $string = "h"; break;
        case "deka": $number = $number / 10000; $string = "da"; break;
        case "base": $number = $number / 1000; $string = ""; break;
        case "deci": $number = $number / 100; $string = "d"; break;
        case "centi": $number = $number / 10; $string = "c"; break;
        case "milli": $string = "m"; break;
    }
    switch($measure)
    {
        case "length": $string .= "m"; break;
        case "weight": $string .= "g"; break;
        case "liquid":
        default: $string .= "L";
    }
    if($prefix !== "base"){$result['prefix'] = $prefix;}
    else{$result['prefix'] = "";}
    $result['number'] = $number;
    $result['var'] = $mills;
    $result['type'] = $string;
    $result['string'] = "{$number} {$string}";
    return $result;
}

function MeasureConverter($measures, $type, $measure_type, $var)
{ /* Depending on the type, this figures out the way to convert things */
    if($type === 1)
    { // We want a metric array, since it is an inch-pound to metric problem
        // We need to convert the inch-pound number to a metric one
        if($measure_type === "liquid")
        { // this seems like it could be streamlined...
            $var *= 4.928921667;
            if($measures['type'] === "gal")
            {
                $result = MetricChooser($var, "liquid", "base");
            }
            elseif($measures['type'] === "qt")
            {
                $result = MetricChooser($var, "liquid", "base");
            }
            elseif($measures['type'] === "pt")
            {
                $type_choose = rand(1,2);
                if($type_choose === 1)
                {
                    $result = MetricChooser($var, "liquid", "base");
                }
                else
                {
                    $result = MetricChooser($var, "liquid", "milli");
                }
            }
            elseif($measures['type'] === "cup")
            {
                $type_choose = rand(1,2);
                if($type_choose === 1)
                {
                    $result = MetricChooser($var, "liquid", "base");
                }
                else
                {
                    $result = MetricChooser($var, "liquid", "milli");
                }
            }
            elseif($measures['type'] === "oz")
            {
                $result = MetricChooser($var, "liquid", "milli");
            }
            elseif($measures['type'] === "tbs")
            {
                $result = MetricChooser($var, "liquid", "milli");
            }
            else
            {
                $result = MetricChooser($var, "liquid", "milli");
            }
        } // end liquid section
    }
    else
    { // We want an inch-pound array

        if($measure_type === "liquid")
        { // only two to use here
            $var *= 0.202884133;
            if($measures['prefix'] === "base")
            {
                $type_choose = rand(1,4);
                if($type_choose === 1)
                {
                    $result = USMeasureChooser($var, "liquid", "gallons");
                }
                elseif($type_choose === 2)
                {
                    $result = USMeasureChooser($var, "liquid", "quarts");
                }
                elseif($type_choose === 3)
                {
                    $result = USMeasureChooser($var, "liquid", "pints");
                }
                else
                {
                    $result = USMeasureChooser($var, "liquid", "cups");
                }
            }
            else
            {
                $type_choose = rand(1,5);
                if($type_choose === 1)
                {
                    $result = USMeasureChooser($var, "liquid", "pints");
                }
                elseif($type_choose === 2)
                {
                    $result = USMeasureChooser($var, "liquid", "cups");
                }
                elseif($type_choose === 3)
                {
                    $result = USMeasureChooser($var, "liquid", "ounces");
                }
                elseif($type_choose === 4)
                {
                    $result = USMeasureChooser($var, "liquid", "tablespoons");
                }
                else
                {
                    $result = USMeasureChooser($var, "liquid", "teaspoons");
                }
            }
        }
    }
    return $result;
}

function RomanNumerals($numbers)
{ // Will work like the UKCurrency function
    $letters = [];
    $letters['M'] = gmp_div_qr($numbers, 1000);
    $numbers = $letters['M'][1];
    $letters['M'] = (int)$letters['M'][0];
    $letters['CM'] = gmp_div_qr($numbers, 900);
    $numbers = $letters['CM'][1];
    $letters['CM'] = (int)$letters['CM'][0];
    $letters['D'] = gmp_div_qr($numbers, 500);
    $numbers = $letters['D'][1];
    $letters['D'] = (int)$letters['D'][0];
    $letters['CD'] = gmp_div_qr($numbers, 400);
    $numbers = $letters['CD'][1];
    $letters['CD'] = (int)$letters['CD'][0];
    $letters['C'] = gmp_div_qr($numbers, 100);
    $numbers = $letters['C'][1];
    $letters['C'] = (int)$letters['C'][0];
    $letters['XC'] = gmp_div_qr($numbers, 90);
    $numbers = $letters['XC'][1];
    $letters['XC'] = (int)$letters['XC'][0];
    $letters['L'] = gmp_div_qr($numbers, 50);
    $numbers = $letters['L'][1];
    $letters['L'] = (int)$letters['L'][0];
    $letters['XL'] = gmp_div_qr($numbers, 40);
    $numbers = $letters['XL'][1];
    $letters['XL'] = (int)$letters['XL'][0];
    $letters['X'] = gmp_div_qr($numbers, 10);
    $numbers = $letters['X'][1];
    $letters['X'] = (int)$letters['X'][0];
    $letters['IX'] = gmp_div_qr($numbers, 9);
    $numbers = $letters['IX'][1];
    $letters['IX'] = (int)$letters['IX'][0];
    $letters['V'] = gmp_div_qr($numbers, 5);
    $numbers = $letters['V'][1];
    $letters['V'] = (int)$letters['V'][0];
    $letters['IV'] = gmp_div_qr($numbers, 4);
    $numbers = $letters['IV'][1];
    $letters['IV'] = (int)$letters['IV'][0];
    $letters['I'] = gmp_div_qr($numbers, 1);
    $numbers = $letters['I'][1];
    $letters['I'] = (int)$letters['I'][0];
    // Now build the string
    $string = "";
    if(!empty($letters['M']))
    {
        $new = str_repeat("M", $letters['M']);
        $string .= $new;
    }
    if(!empty($letters['CM']))
    {
        $string .= "CM";
    }
    if(!empty($letters['D']))
    {
        $new = str_repeat("D", $letters['D']);
        $string .= $new;
    }
    if(!empty($letters['CD']))
    {
        $string .= "CD";
    }
    if(!empty($letters['C']))
    {
        $new = str_repeat("C", $letters['C']);
        $string .= $new;
    }
    if(!empty($letters['L']))
    {
        $string .= "L";
    }
    if(!empty($letters['XL']))
    {
        $string .= "XL";
    }
    if(!empty($letters['X']))
    {
        $new = str_repeat("X", $letters['X']);
        $string .= $new;
    }
    if(!empty($letters['IX']))
    {
        $string .= "IX";
    }
    if(!empty($letters['V']))
    {
        $string .= "V";
    }
    if(!empty($letters['IV']))
    {
        $string .= "IV";
    }
    if(!empty($letters['I']))
    {
        $new = str_repeat("I", $letters['I']);
        $string .= $new;
    }
    return $string;
}

function InputStyleStandard()
{ // Used for most math problems, that expect a single answer
    $input = "<input type='text' id='solution' name='solution' autocomplete='off' inputmode='decimal'>";
    return $input;
}

function InputStyleRemainder()
{ // Used for division problems with remainders
    $input = "<input type='text' id='solution' name='solution['number']' autocomplete='off' inputmode='decimal'>";
    $input .= "<input type='text' id='solution' name='solution['remainder']' autocomplete='off' inputmode='decimal'>";
    return $input;
}

?>
