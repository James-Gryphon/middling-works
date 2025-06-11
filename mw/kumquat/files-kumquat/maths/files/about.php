<?php
$content_array = array();

$content_array[] = array(
"title" => "What is this?",
"anchor" => "whatsthis",
"text" => "<p>\"Maths Map\" is a math(s) skill development game where you conquer increasingly difficult categories of problems. It is similar to the nifty star map that a well-known math education site used to have a few years back, before they made it big and had to lose the fun.</p>");

$content_array[] = array(
    "title" => "Is it a complete math course?",
    "anchor" => "mathcourse",
    "text" => "<p>No, not yet.</p>
    <p>I'd like for it to get bigger than it is, and it'd be flattering to think this could become a major component in someone's education. But right now, it is primarily a skill drill game. It's long on practice, medium on grading, and short on teaching.</p>
");

$content_array[] = array(
    "title" => "There's not enough room for me to work the problem out.",
    "anchor" => "noroom",
    "text" => "<p>That's intentional.</p>
    <p>One of my special priorities for this project is to emphasize mental mathematical ability. While it's important for kids to learn how to do problems on paper, to learn principles of mathematics, it unfortunately isn't a skill you're likely to use in the real world anymore, because of electronics. It is difficult to devise a realistic life situation where it is both a bulky enough problem to require an aid, and more convenient to write it out than to type.</p>
    <p>You can still write these problems out on a scratchpad if you want or need to, but my desire and ultimate goal is for you to store all the numbers and do the whole problem with your brain.</p>
");

$content_array[] = array(
    "title" => "The problems are too easy!",
    "anchor" => "tooeasy",
    "text" => "<p>If they're in the early categories, think of them as a warmup.</p>
    <p>If they're later, congratulations! Your math skills (<i>assuming you're doing these mentally, as previously recommended</i>) exceed the ability I had when this project was first released.</p>
");

$content_array[] = array(
    "title" => "The problems are too hard!",
    "anchor" => "toohard",
    "text" => "<p>They can be tricky. There are several possible reasons for why you're struggling.</p>
    <p>You could be out of practice. Don't feel bad; few nowadays have the experience or the working memory to readily solve a decent-sized math problem, even if it is something that they did on paper in third grade. It may take a little persistence to get the hang of it.</p>
    <p>Maybe you're trying to do it too quickly. Although the game keeps track of time, it isn't used for anything important (<i>as of this writing, 10/18/23</i>). Anyway, the game is not about seeing how quickly you can do things incorrectly. If you have to take extra time to get it right, do so. Slow successes sustain; fast failures frustrate.</p>
    <p>You may not be using the correct techniques for longer problems. Some people are blessed with phenomenal memories and can solve problems in the exact fashion they would on paper, but most of us need to rely on different tricks. Figure out a part of the problem you can handle, and break it down a piece at a time.</p>
    <p>Finally, the problem may actually be too hard, at least for the level it seems to be at. If you're going to hang around here and 'master' things, you're going to have to solve pretty lengthy problems sooner or later, but I don't deny it's discouraging to fall flat trying to do something like \"3125 - 1853\" before you've even gotten to decimals. I may change up the pacing, so that you can get more of a variety of skills before you get bogged down with long and difficult problems. Let me know if you have any suggestions for improving the categories.</p>
");

$content_array[] = array(
    "title" => "How does the grading system work?",
    "anchor" => "grading",
    "text" => "<p>The system keeps track of the last questions you solved in a category (up to 30) and gets the percentage of correct ones.</p>
    <p>If you haven't attempted any problems in a category, the box will be dark grey and have a dash in the middle of it.
    <p>If you have solved 90% or more, your performance is considered 'sound'. On the map, it will show up as a bright green. You are considered expert in this skill.</p>
    <p>If you have solved 80+%, your performance is 'good' and will be chartreuse (brightish yellow-green). There's room for improvement, but you're welcome to move on if you like.</p>
    <p>70+% is 'middling' and shows up as a moderate yellow. Your development in this area is undeniable, but I recommend trying to raise it to the next grade before proceeding to a tougher level.</p>
    <p>Anything below is 'weak' and shows up in dark orange. These territories are hostile and will require more effort to subdue. It would be unwise to proceed without doing so; you would leave enemies to your rear!</p>
    <p>When you are first attempting to solve a category, you may have a high percentage, but a lower color grade. If you've attempted fewer than 30 problems, your score is multiplied by the number of problems you've attempted minus one, divided by the number of problems attempted.</p>
    <p>If you get 30 consecutive questions right, the category is considered 'aced', the highest trackable grade of performance.</p>
    <p>Eventually, I might set something up so that your progress will decay over time, so that categories will require refreshers to stay sound. At present, though, the only thing that affects your score is your play.</p>
");

$content_array[] = array(
    "title" => "Why is the game so bad on phones?",
    "anchor" => "phonehelp",
    "text" => "<p>If it's really <i>bad</i>, <a href=\"mailto:support@middlingworks.com\">let me know how and why</a>.</p>
    <p>If it's because there's a lot of scrolling. I don't think there's much I can do about that. I can (and intend) to do things to mitigate this as the map gets bigger, like by taking you back to the last point you played at, but the basic problem seems unavoidable on small screens, and in fact, every other map-based mobile game I'm aware of is in basically the same shape. If you can think of a solution I haven't (besides zooming, which seems no better than scrolling), please make me aware of it.</p>
");

$content_array[] = array(
    "title" => "Why is the game so bad on terminal browsers (or screen readers)?",
    "anchor" => "terminaldreams",
    "text" => "<p>It hasn't undergone much testing, for one.</p>
    <p>I'm also uncertain how much of an audience there is for this, given that the game's main feature that distinguishes it from other math games seems graphical in nature. I'd like to be proven wrong, and have reason to suspect I might be, but it remains to be shown, and in the meantime there are other things that seem like priorities.</p>
    <p>If you are interested in this feature, but are prevented in some way from using it to its potential, <a href=\"mailto:support@middlingworks.com\">let me know</a> and I will do my best to accommodate you. The same goes for any other area of this site.</p>
");

$content_array[] = array(
    "title" => "What's the version history?",
    "anchor" => "versionhistory",
    "text" => "<p>v1.2.3 was 6/22/24, and fixed a bug with Roman numerals.<br>
    v1.2.2 was 12/12/23, and fixed some more bugs.</br>
    v1.2.1 was 12/9/23, and fixed bugs, both major and minor.<br>
    v1.2.0 was 12/6/23. It added a stats section, numerous new categories, the first major revision of the map layout, a change so that the map automatically centers on problem categories you were engaged with, and various other tweaks.<br>
    v1.1.5 was 11/13/23. It featured very minor fixes.<br>
    v1.1.4 was 10/30/23. It featured a new feature which can help show how many questions you need to solve to improve your score, as well as various fixes and tweaks for problem generation.</br>
    v1.1.3 was 10/28/23. It featured modifications to the grading system, fixes for several nasty bugs, and some other minor tweaks and improvements.<br>
    v1.1.2 was also 10/25/23, and included new guides for problems, as well as minor tweaks to things, including currency formatting.<br>
    v1.1.1 was early 10/25/23. It introduced formatting tips, as well as many tweaks and fixes following up from v1.1.0.<br>
    v1.1.0 came out early 10/24/23. New features included a lot of new categories, the guide feature, a performance tracker inside the play page, and improved feedback when solving or missing a question. Some old categories were rebalanced. Some bugs were also squished, one breaking bug and perhaps a few minor ones. While this version was known not to be perfect when it was released, the improvements were such I didn't want to delay it any longer.<br>
    v1.0.3 featured an expanded about page and a minor interface tweak. Not all updates are exciting!<br>
    v1.0.2 hid the timekeeping on the play page <i>(too distracting)</i>, while making backend changes to prepare for future stats pages that will include this information.<br>
    v1.0.1 featured various fixes and tweaks for interface and appearance, and behind-the-scenes changes. Multiplication 3 and 4 were switched.<br>
    v1.0.0 was the original release.<br>
    </p>
");


$local_site_name = "About Maths Map";
?>
