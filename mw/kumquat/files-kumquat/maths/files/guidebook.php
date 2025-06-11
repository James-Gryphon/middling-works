<?php
$content_array = array();

$content_array[] = array(
"title" => "What is this?",
"anchor" => "whatsthis",
"text" => "<p>The guide is intended to explain the features and intentions of this feature, the categories of problems, and techniques for solving them, so that those who are inexperienced might improve. It assumes a higher reading level, though. If you're using this as a supplement to a child's education, you will want to translate my instructions into something comprehensible.</p>");

$content_array[] = array(
    "title" => "The overall approach to problem solving.",
    "anchor" => "problemsolving",
    "text" => "<p>The goal is to develop practical math skills that will allow you to hold your own in daily life without a calculator or writing materials. I may discuss traditional problem-solving techniques and how to 'show your work', when these are simple and enlightening, but the primary focus will be on getting the right answer, mentally and quickly.</p>");

    $content_array[] = array(
        "title" => "The map.",
        "anchor" => "map",
        "text" => "<p>The map features a tree of problem category boxes. While there is no required order, it is recommended that you follow the arrows to cumulatively develop skills. Many users will already be past the first categories, but they can be a warmup before moving on to more interesting things.</p><p>When you are registered, we store some of your problems so that you can track your performance. The percentage of questions you've gotten correct will be shown in each box. The more questions you get correct (of the last 30 in that category), the brighter and greener the box's color will tend to be. There are four percentile grades: 90%+, 80%+, 70%+, and anything below. I considered making grading tougher, in line with my personal standard <span class='z3'>(which is based on hockey goaltending)</span>, but decided a more forgiving approach would be better; this way you can make a few mistakes early on, figure it out, and then get a good score without having to forever grind problems.</p>");
    
        $content_array[] = array(
            "title" => "Addition 1.",
            "anchor" => "q0",
            "text" => "<p>If you are an early learner, this is a good place to start. If not, this is a speed bump on your way to more interesting things.</p>
            <p>If you are teaching addition to a child, I would suggest using physical objects, such as coins, to illustrate the differences in groups and sums before moving to text, which abstracts things. This feature assumes an ability to think abstractly and store figures in memory in a way that might be difficult for children. You may, however, find this useful for skill drills.</p>
            <p>This section is very basic and features only numbers that will result in 1-digit sums, that is, nothing larger than 9. I probably could have skipped it, but I didn't.</p>
            ");
        $content_array[] = array(
            "title" => "Addition 2.",
            "anchor" => "q1",
            "text" => "<p>The only difference from Addition 1 is that each addend can now be up to 9. This allows for double digit sums. The concept of rolling over is important to a number system, but you have acquired this understanding if you or your student can count to 10.</p>
            <p>Solving with pen-and-paper, you would learn here to 'carry the one':</p>
            <img src='stuff/maths_diagram_add2_1.png'>
            ");
        $content_array[] = array(
            "title" => "Subtraction 1.",
            "anchor" => "q2",
            "text" => "<p>The numbers will always be single-digit in this category, and the minuend (the first number, the one being subtracted from) will always be equal to or larger than the subtrahend (the second number, the amount subtracted).</p>
            ");

        $content_array[] = array(
            "title" => "Addition 3.",
            "anchor" => "q3",
            "text" => "<p>These problems feature two-digit problems and sums. The first number can be anything between 10 and 98; the second one can be anything from 1 up to the largest number that won't result in the sum being higher than 99. Some problems will have single-digits being added to double-digits; this is intentional.</p>
            <p>Although you likely can do it, mental mathematics get harder here. One trick applicable at this level is to convert the problem into a simpler one. Look out for pairs of numbers that are known to add up to 10 (two 5s, a 4 and 6, a 3 and 7, and so on). Another way you can do this is to solve intermediate problems - for instance, you can add just enough to one side (while subtracting it from the other) to make it a round number (that is, one ending in 0). Then the two modified numbers should be easier to resolve. It's also helpful to consider whether the addition causes any carryovers. If there are none, then the sum can be treated as the concatenation of a series of 1-digit problems.</p>
            ");
        $content_array[] = array(
            "title" => "Subtraction 2.",
            "anchor" => "q4",
            "text" => "<p>These problems feature two-digit problems and sums. The first number can be anything between 10 and 98; the second one can be anything from 1 up to the largest number that won't result in the sum being higher than 99.</p>
            ");
        $content_array[] = array(
            "title" => "Multiplication 1.",
            "anchor" => "q8",
            "text" => "<p>Multiplication is shorthand for the repeated addition of numbers. This deals with single-digit <i>multiplicands</i> (the number being multiplied) and <i>multipliers</i>, but the sums can be up to two digits.</p>
            ");
        $content_array[] = array(
            "title" => "Multiplication 2.",
            "anchor" => "q9",
            "text" => "<p>The times table I was taught in grade school went up to 12, but I've heard that in earlier times, some students learned it higher, up to 15. This section covers all those, albeit with a minimum of 2, since any positive number times 1 will equal itself.</p>
            <img src='stuff/maths_diagram_times_table.png'>
            ");
        $content_array[] = array(
            "title" => "Division 1.",
            "anchor" => "q10",
            "text" => "<p>Mental division takes practice. One thing that helps is to recall that it usually (although not absolutely always) functions like multiplication in reverse. The number you're looking for is the number it'd take to make the smaller number the same as the larger one (the dividend).</p>
            <p>In this section, the problems are generated so that there are two-digit dividends and single-digit divisors, with no remainders and no decimals.</p>
            ");
        $content_array[] = array(
            "title" => "Addition 5.",
            "anchor" => "q6",
            "text" => "<p>As we get further, I take more things for granted and get into more difficult problems more quickly. In this section, you add three-digit numbers and can have up to four-digit sums. For these and similar problems, you may find that your memory has trouble keeping up with what's been added to what. For these problems, when I have trouble, I try to store no more than, say, four variables at a time in my head, and solve the problem by incrementally getting one number larger and the other one smaller. In any case, it will take time and practice. I don't say I've arrived myself.</p>
            ");
        $content_array[] = array(
            "title" => "Multiplication 3.",
            "anchor" => "q11",
            "text" => "<p>This section has three-digit multiplicands, but only one-digit multipliers. The sum can be up to four digits. If you're like me, you might struggle with this initially. One way I sometimes try difficult or lengthy multiplication problems is to multiply it by a multiplier I find simpler - often 10, or 5, which is half of 10 - and then add or subtract the number as many times as the difference between that and the real multiplier, until I get it to the right product. If you have a good working memory and recall all the variables, however, you might find it better to work the problem out without these intermediate steps.</p>
            ");
        $content_array[] = array(
            "title" => "Addition 6 and Subtraction 4.",
            "anchor" => "q15",
            "text" => "<p>These sections feature 4-digit numbers and can have 5-digit sums. After this point, the addition and subtraction lines merge into a new topic, decimals. Most math texts do fractions well before decimals, but I struggled with fractions, and I feel like I only really understood them after I understood decimals. As a result, I'll try it the other way, and we'll see how it works. If I'm wrong and it's harder to learn this way, it can be changed later.</p>
            ");


    
    



$local_site_name = "About";
?>