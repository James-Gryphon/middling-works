/* A helper, with help from MDN examples */
if (window.screen.width <= 692){ /* Limited to prevent Firefox from putting up a huge focus border around an inert box*/
var $original_file = document.getElementsByClassName("last");
var $last = $original_file[0];
$last.focus({focusVisible: false});
}