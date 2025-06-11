/* Code adapted from samples at the MDN JS guide; I remain a poor JS coder*/

/* Initialize letter table. This is good for JS browsers, which include most mobile devices. 
If you have a non-JS mobile browser, you're in a fix, but this is better than having a separate 'lite' mode.
*/
$table = document.getElementById("fitb_inner_container");
var $keyboard = []
$keyboard[0] = ["Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P"];
$keyboard[1] = ["A", "S", "D", "F", "G", "H", "J", "K", "L"];
$keyboard[2] = ["Z", "X", "C", "V", "B", "N", "M"];
$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ ";

var $guesses = document.getElementById("guess_string");
$guess_string = $guesses.textContent;
$guess_string = $guess_string.replaceAll(" ", "");
$guess_string = $guess_string.replaceAll("*", " ");
var $unused = document.getElementById("unused_letters");
var $noreuse = "";
if(!$unused || $unused.textContent == "None yet"){$unused = ""; $noreuse = $guess_string;}
else {$unused = $unused.textContent;
    $noreuse = $guesses.textContent.concat($unused);}

// Better way to do this? Probably.
$keyboard.forEach(($subarray) => 
    {
        var $row = document.createElement("div");
        $row.className = "fitb_row";
        $table.append($row);
        $subarray.forEach(($value) =>
        {
            var $cell = document.createElement("div");
            $cell.className = "fitb_cell";
            $row.append($cell);
            var $button = document.createElement("input");
            $button.type = "radio";
            $button.className = "fitb_box";
            $button.name = "letter_box"
            $button.id = $value;
            $button.value = $value;
            $used = $noreuse.includes($value);
            if($used == true)
            {
                $button.disabled = true;
            };
            $cell.append($button);
            var $label = document.createElement("label");
            $label.textContent = $value;
            $label.className = "fitb_box_label";
            $label.setAttribute("for", $value)
            $cell.append($label);
            $row.append(" ");
        }
        )
    }
)

document.addEventListener("keydown", keyDownHandler);

function keyDownHandler($e)
{
    $solve = document.getElementById("solve");
    if($e.key == "Tab")
    {
        $solve.click();
        $solve.focus();
    }
    else // if key isn't tab
    {
        if(!$solve.checked)
        {
            if($e.key == "Enter")
            {
                $submit = document.getElementById("fitb_submit");
                if($submit)
                {
                    $submit.click();
                }
            }
            else 
            {
                $var = $e.key.toUpperCase();
                $button = document.getElementById($var);
                if($button)
                {
                    $button.click();
                }
            }
        }
        else // if the solve box is checked
        {
            if($e.key == "Enter" && $e.shiftKey == true)
            {
                $submit = document.getElementById("fitb_submit");
                if($submit)
                {
                    $e.preventDefault();
                    $submit.click();
                }
            } 
            else 
            {
                $var = $e.key.toUpperCase();
                $textarea = document.getElementById("guess");
                    if
                    (   // This is ludicrously complex. The comments here should help, but the parentheticals are the problem.
                            $e.ctrlKey === false && $e.altKey === false && // allow modifiers
                            ($e.key != "Backspace" && $e.key != "Clear" && $e.key != "Delete" && $e.key != "ArrowLeft" && $e.key != "ArrowRight") && 
                            // allow deletions and navigation
                            (
                                (!$alphabet.includes($var)) || // disallow nonalphabetic chars
                                (
                                    $unused.includes($var) || // disallow letters known not to be used
                                (
                                    $guess_string[$textarea.value.length] != $var && // disallow letter if not same as known letter in spot
                                    $guess_string[$textarea.value.length] != "_" // disallow letter if this spot is a space
                                ) ||
                                ($guess_string[$textarea.value.length] != " " && $var == " ") // disallow space when it's supposed to be a letter
                                )
                            )
                    )
                    {
                        $e.preventDefault();
                    }
            }
        }
    }
}