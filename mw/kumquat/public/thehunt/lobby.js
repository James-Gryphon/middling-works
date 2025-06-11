/* A helper, with help from MDN examples - copied from gPress again*/
if (window.screen.width <= 692){ /* Limited to prevent Firefox from putting up a huge focus border around an inert box*/
var $original_file = document.getElementsByClassName("third");
var $last = $original_file[0];
$last.focus({focusVisible: false});
}