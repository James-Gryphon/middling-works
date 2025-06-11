-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 11, 2025 at 01:46 AM
-- Server version: 10.6.21-MariaDB-cll-lve
-- PHP Version: 8.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `middling_kumquat`
--

-- --------------------------------------------------------

--
-- Table structure for table `fitb_puzzles`
--

CREATE TABLE `fitb_puzzles` (
  `puzzle_date` date NOT NULL,
  `puzzle_text` text NOT NULL,
  `puzzle_clue` text NOT NULL,
  `puzzle_blurb` text NOT NULL,
  `puzzle_sunday` tinyint(1) NOT NULL DEFAULT 0,
  `puzzle_repeat` smallint(5) NOT NULL,
  `puzzle_clone` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `fitb_puzzles`
--

TRUNCATE TABLE `fitb_puzzles`;
--
-- Dumping data for table `fitb_puzzles`
--

INSERT INTO `fitb_puzzles` (`puzzle_date`, `puzzle_text`, `puzzle_clue`, `puzzle_blurb`, `puzzle_sunday`, `puzzle_repeat`, `puzzle_clone`) VALUES
('2022-08-19', 'Test strings!- they are fun, aren\'t they?', 'Demo text', 'Made as my test string in the game prototype.', 0, 1, NULL),
('2022-08-20', 'Mistakes were made.', 'A politician\'s apology', '', 0, 1, NULL),
('2022-08-21', 'Father of lights', 'From the book of James', 'James 1:17. Of all of God\'s titles, this is one of my favorites.', 1, 1, NULL),
('2022-08-22', 'The future for the rest of us.', '', 'This is a mashup of two slogans from a certain Cupertino computer company\'s advertising.', 0, 1, NULL),
('2022-08-23', 'Thank you for calling.', 'Telephone technical support', '', 0, 1, NULL),
('2022-08-24', 'Self-checkout lines', 'Bad store service', '', 0, 1, NULL),
('2022-08-25', 'Long live the King!', 'Royalist motto', '', 0, 1, NULL),
('2022-08-26', '...readies himself for the faceoff', 'Hockey commentating', '', 0, 1, NULL),
('2022-08-27', 'Shields up', 'Something said before a space battle', '', 0, 1, NULL),
('2022-08-28', 'My beloved that knocketh', 'From Song of Solomon', 'Song of Sol. 5:2.', 1, 1, NULL),
('2022-08-29', 'Follow the money', 'Journalism saying', 'My mother has used this one a few times.', 0, 1, NULL),
('2022-08-30', 'Bad command or filename', 'DOS error messages', '', 0, 1, NULL),
('2022-08-31', 'Water, water everywhere', 'A sailor\'s lament', 'Originally from \'Rime of the Ancient Mariner\'.', 0, 1, NULL),
('2022-09-01', 'Showers and thunderstorms', 'Weather reports', '', 0, 1, NULL),
('2022-09-02', 'Prepared variation', 'Chess terms', '', 0, 1, NULL),
('2022-09-03', 'Ketchup and mustard', 'Condiments', '', 0, 1, NULL),
('2022-09-04', 'New every morning', 'From Lamentations', 'Lam. 3:23. One of the most positive statements is in the center of one of the darkest books.', 1, 0, NULL),
('2022-09-05', 'When it rains, it pours', 'Old saying', '', 0, 1, NULL),
('2022-09-06', 'George Gordon Meade', 'Union Army general', 'He won the battle of Gettysburg, but wasn\'t popular with the press.', 0, 1, NULL),
('2022-09-07', 'Target destroyed', '', '', 0, 1, NULL),
('2022-09-08', 'The Industrial Devolution', '', 'From the old Mac game \'Factory\'.', 0, 1, NULL),
('2022-09-09', 'Take it easy', '\'Calm down\', or \'get some rest\'', 'I thought this was appropriate after two days with no clues.', 0, 1, NULL),
('2022-09-10', 'Sleepless nights', 'Restless', 'I hope you don\'t have many of these.', 0, 1, NULL),
('2022-09-11', 'In the presence of mine enemies', 'From Psalms', 'Psalm 23:5.', 1, 0, NULL),
('2022-09-12', 'Lemonade stands', 'Childhood capitalism', 'Now illegal in most states!', 0, 1, NULL),
('2022-09-13', 'Chirping crickets', 'Outside ambient noise', 'I felt like this is the sound the stars would make.', 0, 1, NULL),
('2022-09-14', 'Fastest man alive', 'A sports title', '', 0, 1, NULL),
('2022-09-15', 'Barbara Blackburn', 'Famous typists', 'She set world records in typing and peaked at over 200 words per minute on a Dvorak keyboard.', 0, 1, NULL),
('2022-09-16', 'Motherboards', 'Computer hardware', '', 0, 1, NULL),
('2022-09-17', 'Battle of Antietam', 'American Civil War', 'Fought eightscore years ago, the Union victory enabled the release of the Emancipation Proclamation.', 0, 1, NULL),
('2022-09-18', 'My sister, my love, my dove', 'From Song of Solomon', 'Song of Sol. 5:2.', 1, 0, NULL),
('2022-09-19', 'Tent', '', 'Word week - one extra letter each day.', 0, 1, NULL),
('2022-09-20', 'Plate', '', 'Specializes in nouns - ends on Saturday', 0, 1, NULL),
('2022-09-21', 'Bottle', '', 'Be glad you don\'t have clues to slow you down!', 0, 1, NULL),
('2022-09-22', 'Beloved', 'Can be an adjective', '...but it is also a noun.', 0, 1, NULL),
('2022-09-23', 'Elephant', '', 'One of the few animals that can handle a hippopotamus.', 0, 1, NULL),
('2022-09-24', 'Telephone', '', 'They were best when they had cords. So ends noun week!', 0, 1, NULL),
('2022-09-25', 'Him whom my soul loveth', 'From Song of Solomon', 'Repeated in Song of Sol 3.', 1, 0, NULL),
('2022-09-26', 'William and Margaret Huggins', 'Welcome to astronomy week!', 'Aging William experienced a renaissance in his research after marrying an able colleague.', 0, 1, NULL),
('2022-09-27', 'George\'s Star', 'An early name for the seventh planet', 'Would have been named for King George III.', 0, 1, NULL),
('2022-09-28', 'Lunar Lander', '', 'Both a spacecraft and a game.', 0, 1, NULL),
('2022-09-29', 'The north star', 'Polaris', 'The north star changes over time. Kochab was a previous north star.', 0, 1, NULL),
('2022-09-30', 'Michael Collins', 'Apollo missions', 'Part of the first lunar landing, but never walked on the moon.', 0, 1, NULL),
('2022-10-01', 'Good morning, Discovery!', 'Space shuttle greetings', 'My favorite saying for my favorite shuttle.', 0, 1, NULL),
('2022-10-02', 'He made the stars also', 'From Genesis', 'Genesis 1:16.', 1, 0, NULL),
('2022-10-03', 'Oklahoma', 'State anthems', 'From the musical, about the state, of the same name', 0, 1, NULL);
INSERT INTO `fitb_puzzles` (`puzzle_date`, `puzzle_text`, `puzzle_clue`, `puzzle_blurb`, `puzzle_sunday`, `puzzle_repeat`, `puzzle_clone`) VALUES
('2022-10-04', 'Boldest and grandest', 'State anthem lyrics', 'From \'Texas, Our Texas\'.', 0, 1, NULL),
('2022-10-05', '...happy when skies are gray', 'State anthem lyrics', 'From \'You Are My Sunshine\', a Louisiana state song.', 0, 1, NULL),
('2022-10-06', 'The cool summer breeze in the evergreen trees', 'State anthem lyrics', 'From \'Where the Columbines Grow\', the Colorado state anthem.', 0, 1, NULL),
('2022-10-07', 'The air is so pure and the breezes so free', 'State anthem lyrics\'', 'From \'Home on the Range\', the Kansas state song.', 0, 1, NULL),
('2022-10-08', 'Go read the story of thy past', 'State anthem lyrics', 'From \'The Song of Iowa\'.', 0, 1, NULL),
('2022-10-09', 'Doest thou well to be angry?', 'From Jonah', 'From Jonah 5:4.', 1, 0, NULL),
('2022-10-10', 'Louis Pasteur', 'Contributors to modern medicine', 'Created a rabies vaccine, developed germ theory, and refuted spontaneous generation.', 0, 1, NULL),
('2022-10-11', 'Joseph Lister', 'Contributors to modern medicine', 'Pioneer in antiseptics.', 0, 1, NULL),
('2022-10-12', 'Banting, Best, Macleod, and Collip', 'Contributors to modern medicine', 'They might not have gotten along, but their work with insulin saved millions of lives.', 0, 1, NULL),
('2022-10-13', 'William Morton', 'Contributors to modern medicine', 'One of the first modern anesthesiologists.', 0, 1, NULL),
('2022-10-14', 'Raymond Damadian', 'Contributors to modern medicine', 'Credited with inventing the MRI scanner.', 0, 1, NULL),
('2022-10-15', 'Edward Jenner', 'Contributors to modern medicine', 'Developed the first smallpox vaccine.', 0, 1, NULL),
('2022-10-16', 'He healeth the broken in heart, and bindeth up their wounds', 'From Psalms', 'From Psalm 147:3.', 1, 0, NULL),
('2022-10-17', 'It\'s as simple as that', 'Common saying', '', 0, 1, NULL),
('2022-10-18', 'Pinch, dash and smidgen', 'Traditional \'measurements\'', 'Historically these weren\'t precise measurements, although some in recent times have taken it upon themselves to define them. In one modern scheme, they are respectively 1/16th, 1/8th, and 1/32nd of a teaspoon.', 0, 1, NULL),
('2022-10-19', 'A moment', 'Traditional time unit', 'The moment used to correspond to sundials and had a clear definition. On average it\'s said to have been about 90 seconds long.', 0, 1, NULL),
('2022-10-20', 'Good things come to those who wait', 'Traditional saying', '', 0, 1, NULL),
('2022-10-21', 'Peaches and cream', 'Traditional dessert', '', 0, 1, NULL),
('2022-10-22', 'Writer\'s block', '', 'A condition that also afflicts puzzle writers.', 0, 1, NULL),
('2022-10-23', 'Let thy garments be always white', 'From Ecclesiastes', 'From Ecclesiastes 9:8.', 1, 0, NULL),
('2022-10-24', 'Have the cats been fed?', 'Mundane saying', 'There are many things that are said that are not original or interesting.', 0, 0, NULL),
('2022-10-25', 'The Dark Sector', 'Antarctica', 'A place where radio silence is maintained, for astronomical purposes.', 0, 0, NULL),
('2022-10-26', 'Darkroom', 'Traditional photography', 'Where film was processed.', 0, 0, NULL),
('2022-10-27', 'Anechoic chamber', 'Soundproof', 'A place where sound devices are tested, they are said to be the quietest places on earth.', 0, 0, NULL),
('2022-10-28', 'Surgical scrub', 'Medical practices', 'The process of sterilizing one\'s hands in preparation for a surgery.', 0, 0, NULL),
('2022-10-29', 'Distillation', 'Water purification', '', 0, 0, NULL),
('2022-10-30', 'Take away the dross from the silver', 'From Proverbs', 'From Proverbs 25:4.', 1, 0, NULL),
('2022-10-31', 'Glow-in-the-dark', 'Fireflies, and plastic', 'I used to have glow-in-the-dark stars covering my bedroom ceiling.', 0, 0, NULL),
('2022-11-01', 'Xylophones', 'Musical instruments', '', 0, 0, NULL),
('2022-11-02', 'Clinically proven', 'Health products', '', 0, 0, NULL),
('2022-11-03', 'Calico cats', '', 'They are almost always female.', 0, 0, NULL),
('2022-11-04', 'Solid state drives', 'Computing hardware', '', 0, 0, NULL),
('2022-11-05', 'Pike and shot', 'Renaissance warfare', 'Before the invention of the bayonet, pikemen provided close-range protection.', 0, 0, NULL),
('2022-11-06', 'Cast your bread upon the waters', 'From Ecclesiastes', 'From Ecclesiastes 11:1.', 1, 0, NULL),
('2022-11-07', 'Night vision', 'Many animals have this', 'I can see fairly well in the dark.', 0, 0, NULL),
('2022-11-08', 'Glass bottles', 'Used to have trade-in value', '', 0, 0, NULL),
('2022-11-09', 'Toaster ovens', 'Modern conveniences', '', 0, 0, NULL),
('2022-11-10', 'Washing machine', 'Modern conveniences', '', 0, 0, NULL),
('2022-11-11', 'Armistice', 'Diplomatic term', 'A temporary end of fighting.', 0, 0, NULL),
('2022-11-12', 'Refrigerators', 'Modern conveniences', '', 0, 0, NULL),
('2022-11-13', 'No new thing under the sun', 'From Ecclesiastes', 'From Ecclesiastes 1:9.', 1, 0, NULL);
INSERT INTO `fitb_puzzles` (`puzzle_date`, `puzzle_text`, `puzzle_clue`, `puzzle_blurb`, `puzzle_sunday`, `puzzle_repeat`, `puzzle_clone`) VALUES
('2022-11-14', 'Fruit basket turnover', 'Children\'s games', 'I\'ve found success in sliding directly to the seat nearest to me. But if you\'re never out, you can never call the next play.', 0, 0, NULL),
('2022-11-15', 'Hide and seek', 'Children\'s games', '', 0, 0, NULL),
('2022-11-16', 'Duck duck goose', 'Children\'s games', '', 0, 0, NULL),
('2022-11-17', 'Pat-a-cake', 'Children\'s games', 'My experience is that hand-clapping games seem to be almost exclusively played by girls, for some reason.', 0, 0, NULL),
('2022-11-18', 'Marble shooting', 'Children\'s games', 'This seems to have gone out of vogue, but maybe one day it will make a comeback.', 0, 0, NULL),
('2022-11-19', 'The alphabet game', 'Children\'s games', 'A classic for long car trips.', 0, 0, NULL),
('2022-11-20', '...the city shall be full of boys and girls playing', 'From Zechariah', 'From Zechariah 8:5', 1, 0, NULL),
('2022-11-21', 'Attitude of gratitude', 'A proper mindset', '', 0, 0, NULL),
('2022-11-22', 'Count your blessings', 'Old sayings', 'Probably from a hymn by Johnson Oatman.', 0, 0, NULL),
('2022-11-23', 'It\'s the little things that count', 'Common sayings', '', 0, 0, NULL),
('2022-11-24', 'In every thing give thanks', 'From 1 Thessalonians', 'From 1 Thessalonians 5:18.', 0, 0, NULL),
('2022-11-25', 'When eating fruit, remember the one who planted the tree.', 'A saying about thankfulness', 'Said to be a Vietnamese proverb.', 0, 0, NULL),
('2022-11-26', 'Rest and be thankful.', '', 'Attributed to William Wordsworth.', 0, 0, NULL),
('2022-11-27', 'Gratitude in your hearts', 'From Colossians', 'From Colossians 3:16.', 1, 0, NULL),
('2022-11-28', 'Green Bank, West Virginia', 'You can\'t get a signal here', 'Most wireless technology is banned in this town, to facilitate astronomical research.', 0, 0, NULL),
('2022-11-29', 'Undocumented feature', '\'It\'s not a bug, it\'s an...\'', '', 0, 0, NULL),
('2022-11-30', 'One day at a time', '\'Take it...\'', 'Of late, the puzzle writing schedule seems to follow this motto.', 0, 0, NULL),
('2022-12-01', 'Intentionally left blank', 'A common placeholder message', 'If you showed up at midnight, you\'d have seen something unintentionally left blank!', 0, 0, NULL),
('2022-12-02', 'Delayed reaction', '', '', 0, 0, NULL),
('2022-12-03', 'Slipping on ice', 'A common feature of amateur broomball', 'Elite players wear special shoes.', 0, 0, NULL),
('2022-12-04', 'Understandest thou what thou readest?', 'From the book of Acts', 'From Acts 8:30.', 1, 0, NULL),
('2022-12-05', 'White Christmas', 'A Christmas song', 'The Bing Crosby version we hear today was a re-recording after the original version\'s master recording wore out. If you heard a copy of the original, you\'d be unlikely to notice the difference.', 0, 0, NULL),
('2022-12-06', 'O Tannenbaum', 'A Christmas song', 'A German song about Christmas trees.', 0, 0, NULL),
('2022-12-07', 'Time synchronization', 'Clocks need this', 'My current Python project is a clock.', 0, 0, NULL),
('2022-12-08', 'Better late than never', 'Common saying', 'There\'s no excuse for this taking so long to get up.', 0, 0, NULL),
('2022-12-09', 'A rock and a hard place', 'Cliches', '', 0, 0, NULL),
('2022-12-10', 'Less is more', 'Common saying', '', 0, 0, NULL),
('2022-12-11', 'To Him that made great lights', 'From Psalms', 'From Psalm 139:7.', 1, 0, NULL),
('2022-12-12', 'Warm white lights', 'Relevant to holiday decorating', 'There are multiple types of white!', 0, 0, NULL),
('2022-12-13', 'Hippopotamuses', 'The perfect holiday gift', 'According to a certain song sung by Gayla Peevey.', 0, 0, NULL),
('2022-12-14', 'Room temperature', 'Cold in the summer, warm in the winter', '', 0, 0, NULL),
('2022-12-15', 'Vanilla ice cream', 'A common dessert', 'Although this ice cream is often considered generic and boring, vanilla is an added flavoring, not the standard flavor of ice cream. Without vanilla, it would likely taste like a sweet plain yogurt.', 0, 0, NULL),
('2022-12-16', 'Dark chocolate', 'A common dessert', 'Some people say that it has health benefits. My experience is that it goes well with coffee beans.', 0, 0, NULL),
('2022-12-17', 'Space bar', 'Creating emptiness', 'This would have won you the puzzle, if you came on before it was created.', 0, 0, NULL),
('2022-12-18', 'The light of the world', 'From John', 'From John 8:12.', 1, 0, NULL),
('2022-12-19', 'Feeding the hungry', 'Charity', 'I may write an article about this soon.', 0, 0, NULL),
('2022-12-20', 'Winter wonderland', 'Not many of these in Texas', '', 0, 0, NULL),
('2022-12-21', 'Hand lotion', 'Useful in cold weather', '', 0, 0, NULL),
('2022-12-22', 'Warm mittens', 'Useful in cold weather', '', 0, 0, NULL),
('2022-12-23', 'Thick coats', 'Useful in cold weather', '', 0, 0, NULL),
('2022-12-24', 'Blazing fires', 'Useful in cold weather', '', 0, 0, NULL);
INSERT INTO `fitb_puzzles` (`puzzle_date`, `puzzle_text`, `puzzle_clue`, `puzzle_blurb`, `puzzle_sunday`, `puzzle_repeat`, `puzzle_clone`) VALUES
('2022-12-25', 'A Branch shall grow out of his roots', 'From Isaiah', 'From Isaiah 11:1. Merry Christmas!', 1, 0, NULL),
('2022-12-26', 'Ham and turkey', 'Holiday meats', 'You may still have some of these left over.', 0, 0, NULL),
('2022-12-27', 'Marshall Attack', 'Chess openings', '', 0, 0, NULL),
('2022-12-28', 'Vanilla wafers', 'Go with banana pudding', '', 0, 0, NULL),
('2022-12-29', 'Cheesecake', 'Desserts', '', 0, 0, NULL),
('2022-12-30', 'Whist', 'Classic card games', '', 0, 0, NULL),
('2022-12-31', 'Wandered many a weary foot', 'Song lyrics', 'From \'Auld Lang Syne\'', 0, 0, NULL),
('2023-01-01', 'The acceptable year of the LORD', 'From Isaiah', 'From Isaiah 61:2.', 1, 0, NULL),
('2023-01-02', 'Pepperoni', 'A common pizza topping', '', 0, 0, NULL),
('2023-01-03', 'Boiling point', 'Two hundred and twelve degrees', 'Fahrenheit, of course.', 0, 0, NULL),
('2023-01-04', 'Zero degrees', 'Water\'s freezing point', 'This time it\'s in celsius!', 0, 0, NULL),
('2023-01-05', 'Scrabble', 'Not a real word, but a word in:', '\'za\' is the main example to come to mind.', 0, 0, NULL),
('2023-01-06', 'Blue lights', 'As good as caffeine', 'Reportedly, exposure to blue light can help keep drivers (and others) awake.', 0, 0, NULL),
('2023-01-07', 'Distress signals', '', 'The first standard distress signal was established this day in 1904.', 0, 0, NULL),
('2023-01-08', 'A refiner\'s fire', 'From Malachi', 'From Malachi 3:2', 1, 0, NULL),
('2023-01-09', 'Elderberries', 'Produce', 'Studies have shown that elderberries help with common colds and flus.', 0, 0, NULL),
('2023-01-10', 'Pipe organs', 'Musical instruments', 'Pipe organs used to be the most complex machines on earth.', 0, 0, NULL),
('2023-01-11', 'A hope deferred makes the heart sick', 'Proverbs', 'Actually based on Proverbs 13:12.', 0, 0, NULL),
('2023-01-12', 'Pretty is as pretty does', 'Sayings', '', 0, 0, NULL),
('2023-01-13', 'Package delivery', 'Something expected', '', 0, 0, NULL),
('2023-01-14', 'A cat in your lap', 'Warm and fuzzy', '', 0, 0, NULL),
('2023-01-15', 'Mighty man of valour', 'From Judges', 'From Judges 6:12.', 1, 0, NULL),
('2023-01-16', 'This and that', 'Miscellaneous', '', 0, 0, NULL),
('2023-01-17', 'Purified water', 'A good beverage', '', 0, 0, NULL),
('2023-01-18', 'Coffee beans', 'Are ground', 'Coffee \'beans\' are actually the stones of the coffee fruit, analogous to pits in cherries.', 0, 0, NULL),
('2023-01-19', 'Disappointment', '', '', 0, 0, NULL),
('2023-01-20', 'Falling behind', 'Something neither racers nor students like', '', 0, 0, NULL),
('2023-01-21', 'Midday', 'At noon', '', 0, 0, NULL),
('2023-01-22', 'He feedeth among the lilies', 'From Song of Solomon', 'Song of Sol. 6:3', 1, 0, NULL),
('2023-01-23', 'Chinese Checkers', 'A misnamed board game.', 'Neither checkers nor Chinese, this game is originally a German variant of the game \'Halma.\'', 0, 0, NULL),
('2023-01-24', 'Rainy days', 'Found in Seattle', '', 0, 0, NULL),
('2023-01-25', 'Debian', 'The second-oldest extant Linux distribution.', 'It was first released in 1993, and appeared in the news in 1997 when it was onboard the space shuttle Columbia.', 0, 0, NULL),
('2023-01-26', 'Full of compassion', 'Maranatha song lyrics', 'From \'Awesome in Power\'. Likely originally taken from Psalm 145:8.', 0, 0, NULL),
('2023-01-27', 'Through the power of Your blood', 'Maranatha song lyrics', 'From \'White as Snow\'.', 0, 0, NULL),
('2023-01-28', 'Through the wonder of Your love', 'Maranatha song lyrics', 'Also from \'White as Snow\'.', 0, 0, NULL),
('2023-01-29', 'Though your sins be as scarlet, they shall be as white as snow', 'From Isaiah.', 'From Isaiah 1:18', 1, 0, NULL),
('2023-01-30', 'You\'ve dealt with me so graciously', 'Maranatha song lyrics', 'From \'I\'m Amazed\'.', 0, 0, NULL),
('2023-01-31', 'Emanuel Lasker', 'Chess players', 'The longest-reigning world chess champion, he held a PhD in mathematics and was often referred to as \'Dr. Lasker\'.', 0, 0, NULL),
('2023-02-01', 'Num lock', 'Obscure keyboard keys', '', 0, 0, NULL),
('2023-02-02', 'Kerosene lanterns', 'Vintage light sources', '', 0, 0, NULL),
('2023-02-03', 'Writing a story', 'Something harder than it seems', '', 0, 0, NULL),
('2023-02-04', 'Migraine headaches', 'Throbbing pain', 'Women are several times more likely to have migraines than men.', 0, 0, NULL),
('2023-02-05', '...how good and how pleasant it is for brethren to dwell together in unity!', 'From Psalms.', 'From Psalm 133:1.', 1, 0, NULL),
('2023-02-09', '', '', '', 0, -1, '2022-08-19'),
('2023-02-10', '', '', '', 0, -1, '2022-08-20'),
('2023-02-12', 'I sat down under his shadow with great delight', 'From Song of Solomon.', 'From Song of Solomon 2:3.', 1, 0, NULL),
('2023-02-26', '', '', '', 1, -1, '2022-08-21'),
('2023-02-27', '', '', '', 0, -1, '2022-08-22'),
('2023-06-17', '', '', '', 0, -1, '2022-08-23'),
('2023-06-27', '', '', '', 0, -1, '2022-08-24');
INSERT INTO `fitb_puzzles` (`puzzle_date`, `puzzle_text`, `puzzle_clue`, `puzzle_blurb`, `puzzle_sunday`, `puzzle_repeat`, `puzzle_clone`) VALUES
('2023-07-25', '', '', '', 0, -1, '2022-08-25'),
('2023-07-27', '', '', '', 0, -1, '2022-08-26'),
('2023-07-28', '', '', '', 0, -1, '2022-08-27'),
('2023-08-19', 'Truly the light is sweet', 'From Ecclesiastes.', 'From Ecclesiastes 11:7.', 1, 0, NULL),
('2023-08-21', '', '', '', 0, -1, '2022-08-29'),
('2023-09-02', '', '', '', 0, -1, '2022-08-30'),
('2023-09-04', '', '', '', 0, -1, '2022-08-31'),
('2023-09-05', '', '', '', 0, -1, '2022-09-01'),
('2023-09-06', '', '', '', 0, -1, '2022-09-02'),
('2023-09-07', '', '', '', 0, -1, '2022-09-03'),
('2023-09-08', '', '', '', 0, -1, '2022-09-05'),
('2023-09-09', '', '', '', 0, -1, '2022-09-06'),
('2023-09-11', '', '', '', 0, -1, '2022-09-07'),
('2023-09-12', '', '', '', 0, -1, '2022-09-08'),
('2023-09-15', '', '', '', 0, -1, '2022-09-09'),
('2023-09-16', '', '', '', 0, -1, '2022-09-10'),
('2023-09-27', '', '', '', 0, -1, '2022-09-12'),
('2023-09-28', '', '', '', 0, -1, '2022-09-13'),
('2023-09-29', '', '', '', 0, -1, '2022-09-14'),
('2023-10-10', '', '', '', 0, -1, '2022-09-15'),
('2023-10-12', '', '', '', 0, -1, '2022-09-16'),
('2023-10-16', '', '', '', 0, -1, '2022-09-17'),
('2023-10-18', '', '', '', 0, -1, '2022-09-19'),
('2023-11-04', '', '', '', 0, -1, '2022-09-20'),
('2023-11-22', '', '', '', 0, -1, '2022-09-21'),
('2023-12-16', '', '', '', 0, -1, '2022-09-22'),
('2024-01-03', '', '', '', 0, -1, '2022-09-23'),
('2024-01-09', '', '', '', 0, -1, '2022-09-24'),
('2024-04-05', '', '', '', 0, -1, '2022-09-26'),
('2024-06-06', '', '', '', 0, -1, '2022-09-27'),
('2024-06-07', '', '', '', 0, -1, '2022-09-28'),
('2025-01-25', '', '', '', 0, -1, '2022-09-29'),
('2025-02-15', '', '', '', 0, -1, '2022-09-30'),
('2025-02-20', '', '', '', 0, -1, '2022-10-01'),
('2025-02-24', '', '', '', 0, -1, '2022-10-03'),
('2025-02-25', '', '', '', 0, -1, '2022-10-04'),
('2025-02-27', '', '', '', 0, -1, '2022-10-05'),
('2025-02-28', '', '', '', 0, -1, '2022-10-06'),
('2025-03-01', '', '', '', 0, -1, '2022-10-07'),
('2025-03-05', '', '', '', 0, -1, '2022-10-08'),
('2025-03-06', '', '', '', 0, -1, '2022-10-10'),
('2025-03-07', '', '', '', 0, -1, '2022-10-11'),
('2025-03-08', '', '', '', 0, -1, '2022-10-12'),
('2025-03-13', '', '', '', 0, -1, '2022-10-13'),
('2025-03-15', '', '', '', 0, -1, '2022-10-14'),
('2025-03-22', '', '', '', 0, -1, '2022-10-15'),
('2025-03-26', '', '', '', 0, -1, '2022-10-17'),
('2025-04-02', '', '', '', 0, -1, '2022-10-18'),
('2025-04-17', '', '', '', 0, -1, '2022-10-19'),
('2025-04-20', '', '', '', 1, -1, '2022-08-28'),
('2025-05-01', '', '', '', 0, -1, '2022-10-20'),
('2025-05-09', '', '', '', 0, -1, '2022-10-21'),
('2025-06-06', '', '', '', 0, -1, '2022-10-22');

-- --------------------------------------------------------

--
-- Table structure for table `home_accounts`
--

CREATE TABLE `home_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(255) NOT NULL,
  `seen_time` datetime NOT NULL DEFAULT current_timestamp(),
  `failed_logins` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `failed_forgets` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `home_accounts`
--

TRUNCATE TABLE `home_accounts`;
-- --------------------------------------------------------

--
-- Table structure for table `home_account_auths`
--

CREATE TABLE `home_account_auths` (
  `id` int(10) UNSIGNED NOT NULL,
  `auth_type` varchar(12) NOT NULL,
  `auth_level` tinyint(3) UNSIGNED NOT NULL,
  `start` datetime NOT NULL DEFAULT current_timestamp(),
  `end` datetime NOT NULL,
  `notime` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `home_account_auths`
--

TRUNCATE TABLE `home_account_auths`;
-- --------------------------------------------------------

--
-- Table structure for table `home_gpress_posts`
--

CREATE TABLE `home_gpress_posts` (
  `post_id` mediumint(9) NOT NULL,
  `post_name` varchar(255) NOT NULL,
  `post_text` mediumtext NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `home_gpress_posts`
--

TRUNCATE TABLE `home_gpress_posts`;
-- --------------------------------------------------------

--
-- Table structure for table `home_gpress_topics`
--

CREATE TABLE `home_gpress_topics` (
  `topic_id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `home_gpress_topics`
--

TRUNCATE TABLE `home_gpress_topics`;
-- --------------------------------------------------------

--
-- Table structure for table `home_gpress_topic_links`
--

CREATE TABLE `home_gpress_topic_links` (
  `post_id` int(11) NOT NULL,
  `topic_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `home_gpress_topic_links`
--

TRUNCATE TABLE `home_gpress_topic_links`;
-- --------------------------------------------------------

--
-- Table structure for table `home_prefs`
--

CREATE TABLE `home_prefs` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `setting` varchar(9) NOT NULL,
  `value` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `home_prefs`
--

TRUNCATE TABLE `home_prefs`;
-- --------------------------------------------------------

--
-- Table structure for table `home_tracks`
--

CREATE TABLE `home_tracks` (
  `track_id` bigint(20) UNSIGNED NOT NULL,
  `id` int(11) UNSIGNED NOT NULL,
  `ip` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `track_time` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `home_tracks`
--

TRUNCATE TABLE `home_tracks`;
-- --------------------------------------------------------

--
-- Table structure for table `maths_cats`
--

CREATE TABLE `maths_cats` (
  `cat_id` smallint(10) UNSIGNED NOT NULL,
  `cat_name` varchar(30) NOT NULL,
  `cat_subtitle` varchar(30) NOT NULL,
  `guide_text` varchar(10000) NOT NULL,
  `format` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `maths_cats`
--

TRUNCATE TABLE `maths_cats`;
--
-- Dumping data for table `maths_cats`
--

INSERT INTO `maths_cats` (`cat_id`, `cat_name`, `cat_subtitle`, `guide_text`, `format`) VALUES
(0, 'Addition 1', 'Single Digits', '<p>If you are an early learner, this is a good place to start. If not, this is a speed bump on your way to more interesting things.</p>             <p>If you are teaching addition to a child, I would suggest using physical objects, such as coins, to illustrate the differences in groups and sums before moving to text, which abstracts things. This feature assumes an ability to think abstractly and store figures in memory in a way that might be difficult for children. You may, however, find this useful for skill drills.</p>             <p>This section is very basic and features only numbers that will result in 1-digit sums, that is, nothing larger than 9. I probably could have skipped it, but I didn\'t.</p>', ''),
(1, 'Addition 2', 'Two-Digit Sums', '<p>The only difference from Addition 1 is that each <i>addend</i> (a number that is being added) can now be up to 9. This allows for double digit sums. The concept of rolling over is important to a number system, but this understanding has been acquired if you or your student can count to 10.</p>  <p>Solving with pen-and-paper, you would learn here to \'carry the one\':</p> <img src=\'stuff/maths_diagram_add2_1.png\'>', ''),
(2, 'Subtraction 1', 'Single Digits', '<p>The numbers will always be single-digit in this category, and the <i>minuend</i> (the first number, the one being subtracted from) will always be larger than or equal to the <i>subtrahend</i> (the second number, the amount subtracted).</p>', ''),
(3, 'Addition 3', 'Two-Digit Problems', '<p>These problems feature two-digit problems and sums. The first number can be anything between 10 and 98; the second one can be anything from 1 up to the largest number that won\'t result in the sum being higher than 99. Some problems will have single-digits being added to double-digits; this is intentional.</p>             <p>Although you likely can do it, mental mathematics get harder here. One trick applicable from here on is to convert the problem into a simpler one. Look out for pairs of numbers that are known to add up to 10 (two 5s, a 4 and 6, a 3 and 7, and so on). Another way you can do this is to solve intermediate problems - for instance, you can add just enough to one side (while subtracting it from the other) to make it a round number (that is, one ending in 0). Then the two modified numbers should be easier to resolve. It\'s also helpful to consider whether the addition causes any carryovers. If there are none, then the sum can be treated as the concatenation of a series of 1-digit problems.</p>', ''),
(4, 'Addition 4', 'Three-Digit Sums', '', ''),
(5, 'Subtraction 2', 'Two-Digit Problems', '<p>These problems feature two-digit problems and sums. The first number can be anything between 10 and 98; the second one can be anything from 1 up to the largest number that won\'t result in the sum being higher than 99.</p>', ''),
(6, 'Addition 5', '3-Digit Addition', '<p>As we get further, I take more things for granted and get into more difficult problems more quickly. In this section, you add three-digit numbers and can have up to four-digit sums. For these and similar problems, you may find that your memory has trouble keeping up with what\'s been added to what. For these problems, when I have trouble, I try to store no more than, say, four variables at a time in my head, and solve the problem by incrementally getting one number larger and the other one smaller. In any case, it will take time and practice. I don\'t say I\'ve arrived myself.</p>', ''),
(7, 'Subtraction 3', '3-Digit Subtraction', '', ''),
(8, 'Multiplication 1', '1-Digit Multiplication', '<p>Multiplication is shorthand for the repeated addition of numbers. This deals with single-digit <i>multiplicands</i> (the number being multiplied) and <i>multipliers</i>, but the sums can be up to two digits.</p>', ''),
(9, 'Multiplication 2', 'Times Table Practice', '<p>The times table I was taught in grade school went up to 12, but I\'ve heard that in earlier times, some students learned it higher, up to 15. This section covers all those, albeit with a minimum of 2, since any positive number times 1 will equal itself.</p>             <img src=\'stuff/maths_diagram_times_table.png\'>', ''),
(10, 'Division 1', '1-Digit Denominators', '<p>Mental division takes practice. One thing that helps is to recall that it usually (although not absolutely always) functions like multiplication in reverse. The number you\'re looking for, the <i>quotient</i>,  is the number it\'d take to make the smaller number the same as the larger one (the <i>dividend</i>).</p>             <p>In this section, the problems are generated so that there are two-digit dividends and single-digit divisors, with no remainders and no decimals.</p>', '');
INSERT INTO `maths_cats` (`cat_id`, `cat_name`, `cat_subtitle`, `guide_text`, `format`) VALUES
(11, 'Multiplication 3', 'Expanded Times Table', '<p>This is like the previous one, but multiplicands and multipliers can be as large as 25.</p>', ''),
(12, 'Division 2', '2-Digit Division', '<p>This section introduces <i>remainders</i> to division problems. You perform the division problem as usual; if you reach a number that is smaller than your divisor, you add it to the quotient in the format \"<u>r</u>1\".</p><img src=\'stuff/maths_diagram_division2.png\'>', 'Include the remainder at the end, even if it\'s 0: 6 ÷ 4 = 1r2'),
(13, 'Multiplication 4', '3-Digit Multiplicand', '<p>This section has three-digit multiplicands, but only one-digit multipliers. The sum can be up to four digits. If you\'re like me, you might struggle with this initially. One way I sometimes try difficult or lengthy multiplication problems is to multiply it by a multiplier I find simpler - often 10, or 5, which is half of 10 - and then add or subtract the number as many times as the difference between that and the real multiplier, until I get it to the right product. If you have a good working memory and recall all the variables, however, you might find it better to work the problem out without these intermediate steps.</p>', ''),
(14, 'Multiplication 5', '4-Digit Sums', '<p>This is a challenge level that features multiplicands and multipliers up to 99.</p>', ''),
(15, 'Addition 6', '4-Digit Addition', '<p>This and Subtraction 4 feature 4-digit numbers, and this can have 5-digit sums. This is a challenge level that isn\'t considered necessary to advance, but may be useful later.</p>', ''),
(16, 'Subtraction 4', '4-Digit Subtraction', '<p>This and Addition 6 feature 4-digit numbers. This is a challenge level that isn\'t considered necessary to advance, but may be useful later.</p>', ''),
(17, 'Decimals 1', 'Adding and Subtracting', '<p>It\'s unusual to do decimals before fractions, but I feel decimals are both conceptually simpler, and help people understand the purpose of fractions. I don\'t feel like I fully understood fractions until after I had learned decimals.</p><p>This focuses on basic addition and subtraction with decimals. The second number will always be smaller than the first.</p>', ''),
(18, 'Division 3', '3-Digit Division', '', 'Include the remainder at the end, even if it\'s 0: 6 ÷ 4 = 1r2'),
(19, 'Decimals 2', 'Multiplication', '<p>Although decimal addition is relatively simple, many are stymied when they reach multiplication, on account of the decimal point.</p><p>If you\'re not sure if a product looks right, consider what it would look like if the numbers after the decimal point were gone. The number before the decimal point should be similar in size to that one. If you multiply 12 * 5.1, you know that your final answer should be near 60, not 600.</p><p>The answers here should be rounded to two decimal digits, e.g. \"9.12\".</p>\n<b>Rounding</b><br>\n<p>You often reach a point working with decimals where the result is not practical to type out. Sometimes it\'s an infinite repeating number; other times it\'s only prohibitively long. The custom in such cases is to find a point to leave off writing. If the number after the point we\'ve chosen is as close or closer to being 10 than 0 (5 and above), we count it as if it were a 10 and add it to our chosen cutoff point.</p>\n<p>Now for some practical examples. Suppose we have this decimal number, 97.22222222222. If this were currency, we would like to keep track of it down to cents. (If we\'re accountants, we might even keep track down to mills <span class=\'z3\'>(a tenth of a cent)</span>). However, anything below that seems unnecessary. If we round the number to the second (decimal) digit, the number can be recorded as 97.22.</p>\n<p>For another example, suppose we want to know how far away something is in meters. After extensive calculations, we discover the distance is 115.165747474 meters away.\nIn this case, the thing we care about the most is before the decimal. It might be interesting to keep track of the distance down to the millimeter, but anything below that seems excessive. If we round the number off at millimeters, we get 115.166 meters. The reason why it is .166 and not .165 meters is because the number after is a 7, which is closer to 10.</p>', 'Round the results to the second digit: 19.9126 becomes 19.91'),
(20, 'Modulo 1', 'Introduction', '<p>The modulo operator is unknown in regular maths courses, but it is useful in computer science, and you may do more of these operations in your daily life than you realize.</p><p>The modulo is essentially just the remainder left over after division. To solve these, perform standard division and report only what is left over.</p>', ''),
(21, 'Modulo 2', '3-Digit Problems', '', '');
INSERT INTO `maths_cats` (`cat_id`, `cat_name`, `cat_subtitle`, `guide_text`, `format`) VALUES
(22, 'Decimals 3', 'Division', '<p>Decimal division effectively abolishes the remainder. However, there are so many repeating numbers in division that it\'s necessary to stop writing somewhere. Like multiplication, you should round these off at two digits, checking the third digit to make sure that it doesn\'t round up.</p>', 'Round the results to the second digit: 19.9126 becomes 19.91'),
(23, 'Fractions 1', 'Adding and Subtracting', '<p>Fractions illustrate an important concept, and as such are typically taught fairly early. But the process of actually using them in mathematic operations is relatively difficult. It strikes me as being a little reminiscent of adding numbers from arbitrary bases together.</p><p>This section focuses on addition and subtraction. There are two ways of going about performing operations with fractions. The first way, which is taught in school, is to get matching <i>denominators</i> (the number at the bottom of a fraction), multiplying the <i>numerators</i> (the top number) by the opposing denominator. The section below will show this process in detail. The other way is to convert the problems into decimals and figure out how to arrive back at a fraction after the fact. This is how I\'ve often done it, and in fact, how this computer program generates the correct solutions for these problems.</p><b class=\"z5\">Adding Fractions</b><br> <img src=\'stuff/maths_diagram_fractions1_1.png\'> <p>First, find a common denominator. This is a number that each number can be multiplied to make. This can always be done by multiplying the two numbers together, but there may be other options that are smaller. In this case, 6 and 8 can both be multiplied to make 24.</p> <img src=\'stuff/maths_diagram_fractions1_2.png\'> <p>We multiply 5 by 3, because it takes multiplying 8 by 3 to make 24. A learner might suppose that this process is changing the numbers. They should always remember that a fraction represents the value that is made by division, dividing the top by the bottom . 1 ÷ 2, 3 ÷ 6, and 100 ÷ 200 all equal 0.5. (This is why I feel it is helpful to discuss decimals before fractions.)</p> <img src=\'stuff/maths_diagram_fractions1_3.png\'> <p>With the numbers adjusted, we add the numerators together.</p> <img src=\'stuff/maths_diagram_fractions1_4.png\'> <p>When the numerator exceeds the denominator, it becomes a whole number.</p> <img src=\'stuff/maths_diagram_fractions1_5.png\'> <p>Finally, simplify the fraction if possible, making it the smallest equivalent fraction. In some cases, this can be obtained by dividing the denominator by the numerator. The new denominator is the result of that division; the new numerator is 1. However, there are some situations where this trick doesn\'t work, yet the fraction can still be simplified. Some recommend breaking each number down into its component \'factors\', a topic I haven\'t discussed here yet, and cancelling the overlapping factors out. In any case, this process requires experience, intuition, and sometimes trial and error.</p> <p>For the record, this program finds simplified fractions by brute force: it converts the fraction to a decimal, then increments an integer, starting at 2, and multiplies it by the decimal, until the product of the two is a whole number. Then the integer is the denominator and the product is the numerator. This method will always eventually find the lowest simplified fraction, but it is too laborious for human use.</p>', 'Simplify all fractions: 3/6 becomes 1/2'),
(24, 'Percentages 1', 'Multiplication', '<p>Mathematically, percentages are nearly identical to decimals, requiring minimal effort to convert: multiply the decimal by 100 and add the % sign to it. Conceptually, percentages are closer to fractions. Percentages are a way of showing the rate of something relative to 100. This allows you to compare the ratios between numbers, and can be a useful means of guessing how someone or something would be if it had a smaller or large sample size.</p>  <p>If one student correctly answers 9 problems out of 10, and a second student gets 18 out of 20, they have both scored 90%. A millionaire giving away $100,000 and a billionaire giving away $100,000,000 are giving equal amounts - 10% - relative to all their wealth. If you are allowed to take two out of eight identical pieces of pizza, you\'ve gotten 25% of the pizza.</p>', 'Round the results to the second digit: 19.9126 becomes 19.91'),
(25, 'Multiple Numbers 1', 'Adding and Subtracting', '', '');
INSERT INTO `maths_cats` (`cat_id`, `cat_name`, `cat_subtitle`, `guide_text`, `format`) VALUES
(26, 'Negative Numbers 1', 'Adding and Subtracting', '<p>I\'ve considered putting negative numbers earlier on the map than they are, immediately after subtraction is introduced. They\'re abstract enough, however, that they\'re currently down here.</p>  <p>A negative number is simply the equal opposite of a regular number. The normal basis for explaining this is a number line.</p> <img src=\'stuff/maths_diagram_nn1_line.png\'> <p>Positive numbers are on the right side of 0, and are reached by adding; negative numbers are on the left and are reached by subtracting. You could say that the negative number is the result of 0 - the given positive number.</p> <p>The main difficulty in negative numbers is that adding and subtracting them can be counterintuitive, particularly when they\'re in problems with positive numbers. Have a look at the following equations, which illustrate the dynamics.</p>  5 + 4 = 9<br> 5 - 4 = 1<br> 5 + -4 = 1<br> 5 - -4 = 9<br> -5 + -4 = -9<br> -5 - -4 = -1<br> -5 + 4 = -1<br> -5 - 4 = -9<br>  <p>The minus sign here is logically similar to the word \'not\'. As in English, a pair of negatives cancels each other out; if you say that you will not not do something, that means you will do it. But if you will not not not do it, you\'re back to one not, and so you won\'t. This effect will come up again in a spectacular and confusing way in Negative Numbers 2, but for now, enjoy adding and subtracting.</p>', ''),
(27, 'Fractions 2', 'Multiplying and Dividing', '<p>Paradoxically, multiplying fractions is a simpler procedure than adding them.</p> <img src=\"stuff/maths_diagram_fractions2_1.png\"> <p>Simply multiply the numerators by each other, and the denominators by each other, and you\'re good.</p>  <p>Division is only slightly more complex.</p> <img src=\"stuff/maths_diagram_fractions2_2.png\"> <p>Multiply \'cross-wise\': the numerator by the opposing denominator, and the denominator by the opposing multiplier. The first becomes the new numerator, the second the new denominator.</p> <p>If you like, you can flip the second fraction and treat it as a standard multiplication problem. The result is the same.</p> <p>This is another situation where it is helpful to understand that fractions are equivalent to decimals, or to division problems. The number can end up larger after division, sometimes to the point where it becomes a whole number, a perplexing outcome when you only understand fractions as sections of pizza.</p>', 'Simplify all fractions: 3/6 becomes 1/2'),
(28, 'Percentages 2', 'Finding Percentages', '<p>The idea here is to find the percentage that you derive from dividing the first number by the second. For example, if you completed 12 math problems and got 10 correct, the percentage is going to be 83.33%.</p>  <p>The wording in these problems is admittedly confusing. If you can find a better way of expressing it, let me know. This section may get word problems later.</p>', 'Round the results to the second digit, and include the percentage sign: 91.825 becomes 91.83%'),
(29, 'Multiple Numbers 2', 'Multiplying and Dividing', '', ''),
(30, 'Negative Numbers 2', 'Multiplying and Dividing', '<p>All the regular rules of multiplying and dividing apply here. However, there is one important detail that needs to be mentioned, even if it can\'t be perfectly explained.</p>  <p>Based on how you add and subtract them, you might expect that the result of -4 • -4 is -16, but you\'d be wrong: it\'s 16. When multiplying or dividing, two negative numbers make a positive number!</p>  <p>This can be broken down to make some sense. Multiplying is the result of adding something to 0 a certain number of times. To get -16, you add -4 to 0 4 times, not -4 times. And since the opposite of adding is subtracting, you could say that you subtract -4 from 0 4 times, giving you 16.</p>  <p>It\'s logical, but it\'s a stretch to say that it is intuitive. It will take time to develop this skill.</p>', 'Round the results to the second digit: 19.9126 becomes 19.91');
INSERT INTO `maths_cats` (`cat_id`, `cat_name`, `cat_subtitle`, `guide_text`, `format`) VALUES
(31, 'Currencies', 'British Old Money', '<p>Although it isn\'t used now, old British currency comes up so often in historical reading that I thought it would be useful to learn how it worked.</p> <b>Denominations</b><br> <p>It is distinctly non-decimal in character, like other English measurements. The basic unit is the pound, which is broken up into 240 pence, or 20 shillings. Each shilling is worth 12 pence.</p> <b>Value</b><br> <p>The value of the pound varied throughout history, depending on its physical characteristics and the strength of the British economy. I\'ve chosen to use the last value it had before the decimal era. That conveniently happens to be $2.40, which means that the British (old) pence and the US cent are of equal value!</p> <b>Formatting</b><br> <p>Some say that this was not totally standardized. I\'ve developed a system based on what seems to have been common practice; it is subject to change if I learn more about it. For now:</p> <ul> <li>Pounds are always preceded by the pound symbol, £.</li> <li>Shillings follow pounds in formatting. They are written with a slash afterwards, and the pence count: 16/4. If there are no pence, that is recorded with a dash, not a zero: 11/-.</li> <li>If there are only pence, no pounds or shillings, they are listed alone, and followed by d: 6d. The d symbol is derived from the Roman denarius.</li> <li>If there are pounds before shillings, there is a slash between the two: £1/5/-.</li> <li>If there are pounds and pence and no shillings, keep the shilling space: £1/-/1. This is conjecture, because I couldn\'t find any price listed in just pounds and pence. It seems just as logical to do something like £1/1d, but I had to decide on one, and this way is consistent with the practice for shillings and pence.</li> </ul>', 'Read the guide for formatting instructions'),
(32, 'Discounts', 'Single Discount', '<p>Even when they\'re allegedly saving you money, merchants phrase their numbers in a way to trick consumers.</p> <p>When something is, say, \"10% off\", what they mean is that it is 90% of the original price. Further discounts are conducted in the same fashion. If you have a $100 item that is 40% off, and then apply a second discount that is 40% off, you don\'t get the item for $20, but rather the product of $100 • 0.60 • 0.60, which is $36.</p>', 'Round the results to the second digit: 19.9126 becomes 19.91'),
(33, 'Measurements 1', 'English Liquids', '<p>I thought it would be useful to have a section that focuses on English liquid measurements, on the grounds that they are both frequently used (unlike dry measures), and require frequent conversions between units (unlike distances or weights, where we tend to care about one unit at a time, the others being irrelevant to the scale of the problem). There are other units besides these, but these are the ones most likely to be used.</p>  <p>The chart of measures used here goes like this:</p> <ul> <li>Teaspoons, or tsp</li> <li>Tablespoons, or tbs : 1 = 3 tsp</li> <li>Ounces, or oz : 1 = 2 tbs, or 6 tsp</li> <li>Cups, or cup : 1 = 8 oz, or 48 tsp </li> <li>Pints, or pt : 1 = 2 cup, or 96 tsp</li> <li>Quarts, or qt : 1 = 2 pt, or 192 tsp</li> <li>Gallons, or gal : 1 = 4 qt, or 768 tsp</li></ul><p>Be sure to format your answers with proper spaces and commas. The names for measurements should be the same as on the right side, with no S added to plurals.</p>', 'Read the guide for formatting instructions'),
(34, 'Division 4', 'Long Division', '<p>This is a challenge level, specializing in the most (in)famous of basic math problems. The divisors go from 11 to 99; the dividend can be as high as 2,500.</p>', 'Include the remainder at the end, even if it\'s 0: 6 ÷ 4 = 1r2');
INSERT INTO `maths_cats` (`cat_id`, `cat_name`, `cat_subtitle`, `guide_text`, `format`) VALUES
(35, 'Roman Numerals', 'Arithmetic', '<p>Before the popularization of Arabic numerals, which are now a global standard, various other means existed of expressing numerical concepts. This section will provide a terse and dirty guide to the Roman method, which is used in niche areas today.</p>  <p>The chief fundamental difference between the Arabic and Roman systems is that the latter lacks a zero. In practice, this absence means that the number of digits is less important than the combination of the symbols. In the Arabic system, you can know 1000 is worth somewhat more than 978 because of the number of digits (and lack of a decimal point), but to someone uninitiated in Roman numerals, CMLXXVIII may well look more impressive than M.</p>  <p>There are fewer Roman numerals than Arabic ones, and some numbers are made by repeating certain symbols. A symbol will never be repeated more than three times in a row. Instead, larger numbers are modified by prefixing a single numeral, the next-highest one that represents a decimal number beginning with 1. In this case, the larger number is subtracted by the smaller one. Examples of this will be provided shortly.</p>  <p>Here\'s a table of Roman numeral values; examples of numbers will be shown below.</p> <ul> <li> M - 1000 <li> D - 500 <li> C - 100 <li> L - 50 <li> X - 10 <li> V - 5 <li> I - 1 </ul> <p>Some examples of valid numbers:<br> I = 1<br> III = 3<br> IV = 4<br> IX = 9<br> XXXIX = 39<br> XL = 40<br> XC = 90<br> CD = 400<br> CM = 900<br> CMXC = 990<br> MCMXCI = 1991<br>  <p>Some examples of invalid numbers:<br> IIII - you can\'t repeat a letter more than thrice<br> VL - the V, L, and D are never used to prefix a numeral<br> XXC - you can only prefix a single Roman numeral to a larger one<br> XM - X is not the next-highest numeral starting with 1; that\'s C<br> DD - you never repeat the V, L or D<br> </p>', 'Answer in Roman numerals'),
(36, 'Exponents 1', 'Basics', '<p>Exponents are a special way of representing multiplication. The main number is called the base, and the small number to the upper-right is the exponent. To carry out an exponential operation, you multiply the base by itself, as many times as the number in the exponent.</p>  <p>So, 2⁵ = 2 • 2 • 2 • 2 • 2 = 32.</p>  <p>In this exercise, bases go up to 9 and the exponents go up to 3.</p>', ''),
(37, 'Liquid Conversions', 'US and Metric', '<p>Liquid conversions are complicated by the fact that there are so many US measurements of relatively similar sizes. However, because all US measurements are evenly divisible by teaspoons, someone could get by with only knowing the conversion factor for teaspoons and milliliters, so long as they also know how many tsp are in each US measure.</p>  <p><b><u>Inch-pound -> Metric</u></b> <ul> <li> gal -> L: • by 3.785412 <li>  qt -> L: • by 0.9463529 <li>  pt -> L: • by 0.4731765 <li> pt -> mL: • by 473.1765 <li> cup -> L: • by 0.23658825 <li>cup -> mL: • by 236.58825 <li> oz -> mL: • by 29.57353 <li>tbs -> mL: • by 14.786765001 <li>tsp -> mL: • by 4.928921667 </ul></p>  <p><b><u>Metric -> Inch-pound</u></b> <ul> <li> L -> gal: • by 0.264172037</li> <li>  L -> qt: • by 1.056688261</li> <li>  L -> pt: • by 2.113376298</li> <li> L -> cup: • by 4.226752597</li> <li> mL -> pt: • by 0.002113376</li> <li>mL -> cup: • by 0.004226753</li> <li> mL -> oz: • by 0.033814022</li> <li>mL -> tbs: • by 0.067628044</li> <li>mL -> tsp: • by 0.202884133</li> </ul></p>', 'Truncate results as described in the \'corporate standard\', in \"Weight Conversions\"');
INSERT INTO `maths_cats` (`cat_id`, `cat_name`, `cat_subtitle`, `guide_text`, `format`) VALUES
(38, 'Weight Conversions', 'US and Metric', '<details><summary><b>Introduction to Measure Conversions</b></summary>\n<p>While the metric system is easy to learn and work with in comparison to the US customary system (also referred to as the \'inch-pound\' system, or more ambiguously as the \'English\' system), we still use both, and since we use both, it follows that we must sometimes compare measures between the two.</p>\n\n<p>This and the following sections concentrate on giving you the ability to convert between inch-pound and metric measures, when the comparison is likely to come up in daily life. You\'re not likely to find grains to grams, but miles to kilometers, kilograms to pounds, and milliliters to ounces, along with other similar operations, are all covered.</p></details>\n\n<details><summary><b>Rounding: \"The Corporate Standard\"</b></summary>\n<p>Because the US and metric systems are based on different things, it isn\'t typical to get a clean conversion from one to the other. A \'precise\' conversion can often involve 8+ decimal digits; for instance, the best number given for converting pounds into kilograms is 0.45359237. This is inexpedient for human calculation, and the resulting products are as cumbersome to display as they were to calculate. For daily use, we need a means of coming up with smaller numbers.</p>\n\n<p>I consulted the National Institute of Standards and Technology (NIST) for their guidance in this area. They mention two procedures, one for \'technical documents or specifications\', and another methodology recommended for commercial purposes. Upon investigation, I decided to aim for the latter, primarily because it seems to actually produce more precise results, and secondly because it is in line with the numbers you will see in daily life.</p>\n\n<p>It is important to clarify that the system I use is not codified. It works in a way like the NIST document describes, and produces numbers that look like the ones companies use, but I have no way of knowing for sure that this is \'the\' system. However, it works for my program, and if I can explain it to a computer, a human should be able to work it out more readily, and so this might be useful for your life.</p>\n\n<p>Begin by taking the number you want to convert, and multiplying it by the conversion factor. You will need to choose an appropriate number of significant digits (a \"significant digit\" not including 0s at the beginning or end of the number) to multiply. For many (but not all!) numbers you\'re likely to work with, three should suffice (e.g., 48 * 4.92). With a few small numbers you might be able to get by with less (4 * 4.9); others, including but not limited to large numbers, require more digits (767 * 4.928).</p>\n\n<p>After you\'ve multiplied, if there are any decimals, begin the rounding process. All the whole digits should be preserved. If the number has fewer than three total digits, include the number of decimal digits required to take it up to three. Keep the decimal point in the appropriate place, follow the number with a space and the appropriate symbol, and you\'re done.</p>\n</details>\n\n<details><summary><b>Converting Pounds and Kilograms</b></summary>\n<p>There are numerous weight measures in the inch-pound system and the metric system, but for the purpose of this section, we will focus on the far-most-common ones, pounds and kilograms.</p>  <ul> <li>lb -> kg: • by 0.45359237 <li>kg -> lb: • by 2.204622622 </ul>  <p>For small numbers, multiplying by the first two significant digits is good enough, but you\'ll eventually have to start using three. This makes these more difficult than the hardest multiplication problems you have faced in these exercises up to this point.</p>  <p>The first kg > lb number to require three digits is 44. Every fifth number after this (49, 54, etc.) requires three, up until 88, which also requires three, forming a new pattern. This appears to gradually expand until all numbers require three digits.</p>  <p>The first lb > kg number to require three digits is 15. While there is a resulting pattern, it is not obvious and is of limited utility in solving these problems.</p><p>Due to this, you will likely find converting to pounds to be the easier exercise.</p></details>', 'Truncate results as described in the \'corporate standard\' (view the guide for more information)');
INSERT INTO `maths_cats` (`cat_id`, `cat_name`, `cat_subtitle`, `guide_text`, `format`) VALUES
(39, 'Length Conversions', 'US and Metric', '<p>Broadly speaking, there are three categories of length measurements that are used in day to day life. These are:  <ul> <li>Short: Useful for small items that can be manipulated or carried by humans. Inches, centimeters, and millimeters fit this category. <li>Medium: Distances about the size of a person, or a little longer, up to the range of human vision. Feet, yards, and meters meet this need.</li> <li>Long: Things much larger or longer than a person which may not be entirely visible at once. Miles and kilometers are useful here.</li> </ul></p>  <p>For practical purposes, people don\'t generally convert measurements from one category to another, so we won\'t do so here either.</p>  <p><b><u>Conversion Tables</u></b><br> <b>Short</b><br> An inch is legally defined as being exactly 2.4 centimeters, which means it is 24 millimeters. This makes converting from the US system to the metric a relatively simple process, but the reverse is tricky. <ul> <li>in -> cm: • by 2.4 <li>in -> mm: • by 24 <li>cm -> in: • by 0.416666667 <li>mm -> in: • by 0.041666667 </ul></p>  <b>Medium</b><br> <ul> <li>ft -> cm: • by 0.3048 <li>yd -> mm: • by 0.9144 <li>m -> ft: • by 3.280839895 <li>m -> yd: • by 1.093613298 </ul>  <b>Long</b><br> <ul> <li>mi -> km: • by 1.609344 <li>km -> mi: • by 0.621371192 </ul>', 'Truncate results as described in the \'corporate standard\', in \"Weight Conversions\"'),
(40, 'Exponents 2', 'Varied Exponents', '<p>This exercise features exponents that range from -1 to 4. The process for positive exponents has been discussed, but what is the process for exponents lower than 1?</p>  <p>When the exponent is 0, the product is always 1. Negative exponents are a little more complicated; in this case, divide 1 by the base the number of times in the exponent.<br> <br> So, 4⁻² = 1 ÷ 4 ÷ 4 = 0.0625. </p>', ''),
(41, 'Price Comparisons', 'Higher or Lower', '<p>Although stores often list prices per weight, particularly in produce sections, they often then present them in different measures, with the effect of making it difficult to compare one to the other. This section is about determining which is cheaper.</p>  <p>I thought about making you calculate the price difference between the two, when one measure is converted to the other. But I\'m not going to, at least not as of this writing <i>(Dec 1, 2023)</i>. To correctly answer these questions, just write whether the first price is more expensive than (<b>></b>), less expensive than (<b><</b>), or equal to (<b>=</b>) the latter. This will make use of your intuition and ability to give accurate approximations, and after all the lengthy series of decimal problems, it should be refreshing.</p>', 'Write whether the former is higher than (>), lower than (<), or equal to (=) the latter'),
(42, 'Exponents 3', 'Challenge', '<p>This exercise features bases that range from -15 to 15, and exponents that range from -3 to 3, and is an advanced exponent challenge.</p>  <p>While multiplying negative numbers normally produces a positive number, this case is an exception; the final product should also be a negative. You may think of the exponent as modifying only the base, not the base and its negative sign.</p>', ''),
(43, 'Binary 1', 'Identification', '<p>Binary numbers are essential to modern computing, and these units will attempt to familiarize you with reading, writing, and performing mathematical basic operations with them.</p>  <p>The word \'binary\' can be defined as \'something with only two options\'. While we have ten numbers in the decimal number system, binary works with only two, the 0 and 1. In computing, each binary digit is referred to as a \'bit\', and represent true-false states; eight consecutive binary digits are a byte.</p>  <p>Each new digit is worth double the previous one. The diagram below shows the progress of a byte.<br> <pre>   0  0  0  0  0  0  0  0<br> 128 64 32 16  8  4  2  1<br> </pre>  Here are some sample numbers, with their decimal equivalents:<br>  <code>00000000</code> - 0<br> <code>00000001</code> - 1<br> <code>00000111</code> - 7<br> <code>00100101</code> - 37<br> <code>00110010</code> - 50<br> <code>01100010</code> - 100<br> <code>11111111</code> - 255 </p>  <p>In this section, either write the decimal value of the binary number, or write the binary value of the decimal number. When writing in binary, include all of the eight digits of the byte.</p>', 'Write all eight digits of the byte, whether they\'re empty or not'),
(44, 'Binary 10', 'Adding and Subtracting', '<p>This section focuses on adding and subtracting numbers up to 255, the maximum number storable in a byte.</p>', 'Write all eight digits of the byte, whether they\'re empty or not');
INSERT INTO `maths_cats` (`cat_id`, `cat_name`, `cat_subtitle`, `guide_text`, `format`) VALUES
(45, 'Binary 11', 'Multiplying and Dividing', '<p>This section focuses on multiplying and dividing.</p>', 'Write all eight digits of the byte, whether they\'re empty or not');

-- --------------------------------------------------------

--
-- Table structure for table `maths_problems`
--

CREATE TABLE `maths_problems` (
  `inc_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `cat_id` smallint(11) UNSIGNED NOT NULL,
  `prob_string` varchar(100) NOT NULL,
  `real_answer` varchar(100) NOT NULL,
  `user_answer` varchar(100) NOT NULL,
  `correct` tinyint(1) NOT NULL,
  `tooktime` decimal(7,2) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `maths_problems`
--

TRUNCATE TABLE `maths_problems`;
-- --------------------------------------------------------

--
-- Table structure for table `maths_user_cats`
--

CREATE TABLE `maths_user_cats` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `cat_id` smallint(5) UNSIGNED NOT NULL,
  `problems` tinyint(3) UNSIGNED NOT NULL,
  `misses` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `maths_user_cats`
--

TRUNCATE TABLE `maths_user_cats`;
--
-- Dumping data for table `maths_user_cats`
--

INSERT INTO `maths_user_cats` (`user_id`, `cat_id`, `problems`, `misses`) VALUES
(1, 1, 50, 25);

-- --------------------------------------------------------

--
-- Table structure for table `msgr_chapters`
--

CREATE TABLE `msgr_chapters` (
  `thread_id` int(10) UNSIGNED NOT NULL,
  `chapter_num` tinyint(5) UNSIGNED NOT NULL,
  `first_post` int(10) UNSIGNED NOT NULL,
  `last_post` int(10) UNSIGNED NOT NULL,
  `chapter_name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `msgr_chapters`
--

TRUNCATE TABLE `msgr_chapters`;
-- --------------------------------------------------------

--
-- Table structure for table `msgr_msgs`
--

CREATE TABLE `msgr_msgs` (
  `msg_id` bigint(20) UNSIGNED NOT NULL,
  `local_msg_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `thread_id` int(10) UNSIGNED NOT NULL,
  `msg_body` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `msgr_msgs`
--

TRUNCATE TABLE `msgr_msgs`;
-- --------------------------------------------------------

--
-- Table structure for table `msgr_threads`
--

CREATE TABLE `msgr_threads` (
  `thread_id` int(11) UNSIGNED NOT NULL,
  `thread_starter` int(11) UNSIGNED NOT NULL,
  `thread_name` varchar(40) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `msgr_threads`
--

TRUNCATE TABLE `msgr_threads`;
-- --------------------------------------------------------

--
-- Table structure for table `msgr_thread_auths`
--

CREATE TABLE `msgr_thread_auths` (
  `thread_id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `auth_level` tinyint(3) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `msgr_thread_auths`
--

TRUNCATE TABLE `msgr_thread_auths`;
-- --------------------------------------------------------

--
-- Table structure for table `plus_minus_games`
--

CREATE TABLE `plus_minus_games` (
  `game_id` int(10) UNSIGNED NOT NULL,
  `team_plus_name` varchar(30) NOT NULL,
  `team_minus_name` varchar(30) NOT NULL,
  `started` datetime NOT NULL DEFAULT current_timestamp(),
  `ended` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `winner` tinyint(4) NOT NULL DEFAULT 0,
  `count` tinyint(3) UNSIGNED NOT NULL DEFAULT 50,
  `minusmin` smallint(5) UNSIGNED NOT NULL,
  `plusmax` smallint(5) UNSIGNED NOT NULL,
  `last_player_id` int(10) UNSIGNED DEFAULT NULL,
  `last_plus_or_minus` tinyint(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `plus_minus_games`
--

TRUNCATE TABLE `plus_minus_games`;
-- --------------------------------------------------------

--
-- Table structure for table `plus_minus_movers`
--

CREATE TABLE `plus_minus_movers` (
  `local_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip` varchar(20) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `plus_minus_movers`
--

TRUNCATE TABLE `plus_minus_movers`;
-- --------------------------------------------------------

--
-- Table structure for table `plus_minus_scores`
--

CREATE TABLE `plus_minus_scores` (
  `game_id` int(10) UNSIGNED NOT NULL,
  `local_id` int(10) UNSIGNED NOT NULL,
  `impact` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `plus_minus_scores`
--

TRUNCATE TABLE `plus_minus_scores`;
--
-- Dumping data for table `plus_minus_scores`
--

INSERT INTO `plus_minus_scores` (`game_id`, `local_id`, `impact`) VALUES
(1, 1, 4),
(1, 2, 1),
(1, 3, -1),
(1, 4, -2),
(1, 5, 1),
(1, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sm_batches`
--

CREATE TABLE `sm_batches` (
  `batch` varchar(32) NOT NULL,
  `count` tinyint(3) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `sm_batches`
--

TRUNCATE TABLE `sm_batches`;
-- --------------------------------------------------------

--
-- Table structure for table `sm_msgs`
--

CREATE TABLE `sm_msgs` (
  `batch` varchar(32) NOT NULL,
  `msg` mediumtext NOT NULL,
  `local_id` tinyint(3) UNSIGNED NOT NULL,
  `hidden` bit(1) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `sm_msgs`
--

TRUNCATE TABLE `sm_msgs`;
-- --------------------------------------------------------

--
-- Table structure for table `sm_users`
--

CREATE TABLE `sm_users` (
  `batch` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip` varchar(20) DEFAULT NULL,
  `local_id` tinyint(3) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `sm_users`
--

TRUNCATE TABLE `sm_users`;
-- --------------------------------------------------------

--
-- Table structure for table `sys_settings`
--

CREATE TABLE `sys_settings` (
  `setting` varchar(32) NOT NULL,
  `value` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `sys_settings`
--

TRUNCATE TABLE `sys_settings`;
--
-- Dumping data for table `sys_settings`
--

INSERT INTO `sys_settings` (`setting`, `value`) VALUES
('fitb_fallback_sunday', '2022-08-21'),
('fitb_fallback_weekday', '2022-08-19');

-- --------------------------------------------------------

--
-- Table structure for table `test_table`
--

CREATE TABLE `test_table` (
  `id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `test_table`
--

TRUNCATE TABLE `test_table`;
--
-- Dumping data for table `test_table`
--

INSERT INTO `test_table` (`id`, `set_id`) VALUES
(1, 1),
(2, 2),
(3, 1),
(4, 4),
(5, 5),
(6, 6);

-- --------------------------------------------------------

--
-- Table structure for table `thnt_boards`
--

CREATE TABLE `thnt_boards` (
  `board_id` tinyint(3) UNSIGNED NOT NULL,
  `board_name` varchar(50) NOT NULL,
  `board_image` mediumtext NOT NULL,
  `board_deck` mediumtext NOT NULL,
  `board_blurb` mediumtext NOT NULL,
  `agent_card_count` tinyint(3) UNSIGNED NOT NULL,
  `locale` varchar(16) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `thnt_boards`
--

TRUNCATE TABLE `thnt_boards`;
--
-- Dumping data for table `thnt_boards`
--

INSERT INTO `thnt_boards` (`board_id`, `board_name`, `board_image`, `board_deck`, `board_blurb`, `agent_card_count`, `locale`, `active`) VALUES
(1, 'Alphabet Soup', '{\"image_name\":\"AlphabetSoup\",\"background\":\"8\",\"link\":\"0\",\"text\":\"0\",\"players\":[\"6\",\"1\",\"7\",\"3\", \"9\", \"11\"],\"circle_width\":\"17\"}', '{\"1\":6,\"2\":4,\"3\":6,\"4\":4,\"6\":4,\"7\":4,\"8\":4,\"9\":8,\"10\":4,\"14\":4,\"15\":4}', 'A small, simple board consisting of four regions with five territories each. This was the first map implemented, and is useful for testing purposes and small, quick games.', 3, 'world', 1),
(2, 'North America', '{\"image_name\":\"NorthAmerica\",\"background\":\"8\",\"link\":\"0\",\"text\":\"0\",\"players\":[\"6\",\"1\",\"7\",\"3\", \"9\", \"11\"],\"circle_width\":\"5\"}', '{\"1\":20,\"2\":4,\"3\":16,\"4\":8,\"5\":4,\"6\":4,\"7\":4,\"9\":8,\"10\":4}', 'Search nine regions in the United States, Canada, and Mexico. A large map suited to multiple players and a long game.', 4, 'continent', 0),
(3, 'Texas', 'Texas', '', '', 4, 'state', 0),
(4, 'The City', 'TheCity', '', '', 4, 'city', 0),
(5, 'Cabatia', '{\"image_name\":\"Cabatia\",\"background\":\"8\",\"link\":\"0\",\"text\":\"0\",\"players\":[\"6\",\"1\",\"7\",\"3\", \"9\", \"11\"],\"circle_width\":\"5\"}', '{\"1\":8,\"2\":4,\"3\":8,\"4\":4,\"6\":6,\"7\":4,\"8\":6,\"9\":8,\"10\":4,\"15\":4}', 'Play in six regions of a fictional continent. This is a moderate-sized map suited to 2-4 players.', 4, 'continent', 1);

-- --------------------------------------------------------

--
-- Table structure for table `thnt_card_types`
--

CREATE TABLE `thnt_card_types` (
  `card_type_id` smallint(5) UNSIGNED NOT NULL,
  `card_name` varchar(50) NOT NULL,
  `card_abbrev` varchar(20) NOT NULL,
  `card_blurb` varchar(255) NOT NULL,
  `card_power` varchar(20) NOT NULL,
  `card_param` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `thnt_card_types`
--

TRUNCATE TABLE `thnt_card_types`;
--
-- Dumping data for table `thnt_card_types`
--

INSERT INTO `thnt_card_types` (`card_type_id`, `card_name`, `card_abbrev`, `card_blurb`, `card_power`, `card_param`) VALUES
(1, 'Count Links to Mole', 'search', 'You can either search for the Mastermind, or attempt to capture him. A search reveals your current distance from the Mastermind in steps. A capture attempt will win the game if the Mastermind is in the same territory as you.', 'search_mm', ''),
(2, 'Safe Capture', 'capture', 'You can either search for the Mastermind, or attempt to capture him. A search reveals your current distance from the Mastermind in steps. A capture attempt will win the game if the Mastermind is in the same territory as you.', 'capture_mm', ''),
(3, 'Guide to Mole', 'point', 'This card shows you an adjacent territory that is one step closer to the Mastermind.', 'point_mm', ''),
(4, 'Get Another\'s Distance', 'bug_agent', 'This reveals the current distance of a random other player from the Mastermind in steps.', 'bug_agent', ''),
(5, 'Close Borders', 'close_borders_na', 'This card closes the borders between each of the countries (US, Canada, Mexico), so that regular travel is prohibited.', 'close_borders_na', ''),
(6, 'Local Lockdown', 'local_lockdown', 'This card prohibits travel into or out of the region the player is in from another region.', 'local_lockdown', ''),
(7, 'Take Another Turn', 'take_turn', 'Play this card to gain one extra turn.', 'take_turn', ''),
(8, 'Long Jump', 'global_jump', 'Play this card to be transported to the other side of the world.', 'global_jump', ''),
(9, 'Flight Check', 'flight_check', '', 'flight_check', ''),
(10, 'Unlock Links', 'open_borders', 'This card permits travel between all territories again, cancelling the effects of Close Borders and Local Lockdown cards.', 'open_borders', ''),
(11, 'Close Roads', 'close_roads', 'Travel that depends on land vehicles (cars, motorcycles, etc.) is prevented.', 'close_roads', ''),
(12, 'Close Airports', 'stop_jets', 'All travel by jet is prohibited.', 'stop_jets', ''),
(13, 'Open Roads and Airports', 'open_road_ports', 'This ends the effect of all Close Roads and all Close Airports cards.', 'close_road_ports', ''),
(14, 'Monolingual', 'monoling', 'When this card is played, nobody can leave one region to go to another.', 'monoling', ''),
(15, 'Compass Direction', 'compass_dir', '', 'compass_dir', '');

-- --------------------------------------------------------

--
-- Table structure for table `thnt_continents`
--

CREATE TABLE `thnt_continents` (
  `board_id` smallint(5) UNSIGNED NOT NULL,
  `continent_id` tinyint(3) UNSIGNED NOT NULL,
  `continent_name` varchar(50) NOT NULL,
  `continent_blurb` varchar(255) NOT NULL,
  `agent_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `thnt_continents`
--

TRUNCATE TABLE `thnt_continents`;
--
-- Dumping data for table `thnt_continents`
--

INSERT INTO `thnt_continents` (`board_id`, `continent_id`, `continent_name`, `continent_blurb`, `agent_name`) VALUES
(1, 1, 'Latin', 'Contains territories named after the Latin alphabet.', 'Marcus'),
(1, 2, 'Hebrew', 'Contains territories named after the Hebrew alefbet.', 'Shim\'on'),
(1, 3, 'Greek', 'Contains territories named after the Greek alphabet.', 'Jason'),
(1, 4, 'Armenian', 'Contains territories named after the Armenian aybuben.', 'Tigran'),
(2, 1, 'Pacific', '', 'Aaron'),
(2, 2, 'North', '', 'Oliver'),
(2, 3, 'West', '', 'Max'),
(2, 4, 'Central', '', 'James'),
(2, 5, 'Midwest', '', 'Jordan'),
(2, 6, 'Northeast', '', 'Francis'),
(2, 7, 'East', '', 'Nathan'),
(2, 8, 'South', '', 'Juan'),
(5, 1, 'Northwest', 'This region, adjacent to the Latific Ocean, enjoys year-round temperate climates and moderate rainfall.', 'Nat'),
(5, 2, 'Northeast', 'This chilly subarctic region contains most of the oldest cities of the Domain of Lumenda, which appropriately styles itself as the \"Real North\".', 'Olar'),
(5, 3, 'West', 'This famous frontier makes up for a lack of population with a lot of acreage. It\'s yesterday\'s next big thing.', 'Billiam'),
(5, 4, 'Midwest', 'The Federation\'s old new land, the moderate Midwest region is both essential to industry and important in agriculture.', 'Wake'),
(5, 5, 'East', 'Situated in a favorable location on the Brevantic coast, this region contains many of the largest Federal cities.', 'Jaime'),
(5, 6, 'Central', 'Centered around the great Gulf, this region is known for its heat and abundant humidity.', 'Saeri'),
(5, 7, 'South', 'This region was the first colonized by Eraepaens, and contains many of the most influential cities of the United State of Jenesce (or Unidestat-Daljenesce).', 'Frese');

-- --------------------------------------------------------

--
-- Table structure for table `thnt_games`
--

CREATE TABLE `thnt_games` (
  `game_id` int(10) UNSIGNED NOT NULL,
  `game_name` varchar(32) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `board_id` tinyint(3) UNSIGNED NOT NULL,
  `player_count` tinyint(3) UNSIGNED NOT NULL,
  `private` tinyint(1) NOT NULL,
  `access_code` varchar(100) NOT NULL,
  `game_status` varchar(20) NOT NULL,
  `triggers` text NOT NULL DEFAULT '',
  `current_player` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `round` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `current_energy` tinyint(3) UNSIGNED NOT NULL DEFAULT 2,
  `agents` text NOT NULL DEFAULT '',
  `card_decks` mediumtext NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `thnt_games`
--

TRUNCATE TABLE `thnt_games`;
-- --------------------------------------------------------

--
-- Table structure for table `thnt_messages`
--

CREATE TABLE `thnt_messages` (
  `game_id` int(10) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `secret` tinyint(3) UNSIGNED NOT NULL,
  `recipient` tinyint(3) UNSIGNED NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `move_id` smallint(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `thnt_messages`
--

TRUNCATE TABLE `thnt_messages`;
-- --------------------------------------------------------

--
-- Table structure for table `thnt_players`
--

CREATE TABLE `thnt_players` (
  `game_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip` varchar(20) NOT NULL,
  `local_id` tinyint(3) UNSIGNED NOT NULL,
  `move_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `location` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `inventory` text NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `thnt_players`
--

TRUNCATE TABLE `thnt_players`;
-- --------------------------------------------------------

--
-- Table structure for table `thnt_territories`
--

CREATE TABLE `thnt_territories` (
  `board_id` smallint(5) UNSIGNED NOT NULL,
  `continent_id` smallint(5) UNSIGNED NOT NULL,
  `territory_id` tinyint(3) UNSIGNED NOT NULL,
  `territory_name` varchar(100) NOT NULL,
  `territory_blurb` varchar(255) NOT NULL,
  `x` smallint(5) UNSIGNED NOT NULL,
  `y` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `thnt_territories`
--

TRUNCATE TABLE `thnt_territories`;
--
-- Dumping data for table `thnt_territories`
--

INSERT INTO `thnt_territories` (`board_id`, `continent_id`, `territory_id`, `territory_name`, `territory_blurb`, `x`, `y`) VALUES
(1, 1, 1, 'A', 'The letter A, an appropriate beginning to the alphabet. It is used for words like alphabet and abacus.', 49, 44),
(1, 1, 2, 'B', 'The letter B is the beautiful second letter of the alphabet, and is found in words like \'beloved\' and \'blueness\'.', 136, 72),
(1, 1, 3, 'C', 'The letter C is the third letter of the alphabet, used in words like \'cantaloupe\'. It is arguably superfluous, since its sounds are both used in other letters.', 60, 106),
(1, 1, 4, 'D', 'The letter D is a delightful fourth letter to the alphabet. It is in words like \"dandelion\" and \"directly\".', 53, 156),
(1, 1, 5, 'E', 'The letter E is the fifth letter of the alphabet. It is used in words like \"e\", \"ee\", \"eee\", and \"eeee\".', 190, 46),
(1, 2, 6, 'Alef', 'Alef is the first letter of the alefbet. It has a numeric value of 1. It is the first letter in the Shema.', 42, 259),
(1, 2, 7, 'Bet', 'Bet is the second letter of the alefbet. It has a numeric value of 2. It is the first letter of the Torah.', 105, 273),
(1, 2, 8, 'Gimel', 'Gimel is the third letter of the alefbet. It has a numeric value of 3.', 39, 317),
(1, 2, 9, 'Dalet', 'Dalet is the fourth letter of the alefbet. It has a numeric value of 4.', 115, 333),
(1, 2, 10, 'Hay', 'Hay is the fifth letter of the alefbet, and has a numeric value of 5. It is not for horses.', 103, 380),
(1, 3, 11, 'Alpha', 'Alpha is the first letter of the Greek alphabet. Positively, it is associated with being first. Negatively, it is associated with wanting to be and acting as though one is first.', 266, 407),
(1, 3, 12, 'Beta', 'Beta is the second letter of the Greek alphabet, and it is much more chill than alpha.', 333, 401),
(1, 3, 13, 'Gamma', 'Gamma is the third letter of the Greek alphabet, and has lent its name to dangerous electromagnetic radiation.', 305, 343),
(1, 3, 14, 'Delta', 'Delta is the fourth letter of the Greek alphabet. It is well-loved by aerospace engineers and rocket scientists.', 393, 370),
(1, 3, 15, 'Epsilon', 'Epsilon is the fifth letter of the Greek alphabet. It exists!', 372, 307),
(1, 4, 16, 'Ayb', 'Ayb is the first letter of the Armenian aybuben.', 415, 219),
(1, 4, 17, 'Ben', 'Ben is the second letter of the Armenian aybuben. It also happens to be a common name. In Hebrew, it is a word that means \"son of\".', 372, 183),
(1, 4, 18, 'Gim', 'Gim is the third letter in the Armenian aybuben.', 398, 129),
(1, 4, 19, 'Da', 'Da is the fourth letter in the Armenian aybuben. In some other non-Armenian languages, it is also the word for \"yes\".', 352, 105),
(1, 4, 20, 'Yech', 'Yech is the fifth letter in the Armenian aybuben. It unfortunately also happens to resemble a sound of disgust in English.', 298, 65),
(1, 1, 21, 'F', 'The fantastic letter F is the sixth letter of the Latin alphabet. It is used in words like \"fast\" and \"favor\".', 147, 21),
(1, 1, 22, 'G', 'G is the seventh letter of the Latin alphabet, and it\'s a good one. It is used in words like \"glorious\", \"grin\", and \"arpeggiated\".', 109, 141),
(1, 3, 23, 'Zeta', 'Zeta is the sixth letter of the Greek alphabet.', 332, 281),
(1, 4, 24, 'Za', 'Za is the sixth letter of the Armenian aybuben. It is also a wonderful word to play in word-building board games.', 300, 149),
(1, 4, 25, 'Eh', 'Eh is the seventh letter of the Armenian aybuben. I thought about writing something clever to go here, but, eh...', 259, 98),
(1, 4, 26, 'Et', 'Et is appropriately the eighth letter of the Armenian aybuben.', 217, 130),
(1, 4, 27, 'Tu', 'Tu is the ninth letter of the Armenian aybuben. Whatever region you want to get tu, you can get tu thru here.', 251, 154),
(2, 1, 1, 'Anchorage', 'More residents of Alaska than not are near Anchorage. This arctic city experiences perpetual light in summer, but seemingly endless darkness in winter.', 13, 16),
(2, 1, 2, 'Honolulu', 'The old capital of the island kingdom of Hawaii, Honolulu is now one of the most popular tourist destinations in the world, as the scenery is always lovely and the seasons never seem to change from summer.', 25, 248),
(2, 1, 3, 'Vancouver', 'A longstanding lumber town, Vancouver is now considered one of the most livable cities in Canada. Thousand-acre Stanley Park contains a seawall and the largest aquarium in Canada.', 118, 45),
(2, 1, 4, 'Seattle', 'Continuous clouds and rain dampen the roads and moods of this seaside city. The Boeing Company was founded here, however, and the city hosts some of the largest space and aviation attractions in the country.', 123, 74),
(2, 1, 5, 'Portland', 'After narrowly avoiding being named after Boston, the city of Portland gradually became known for its roses, which grow well there. It also hosts some of the oldest operating steam locomotives in the United States.', 112, 97);
INSERT INTO `thnt_territories` (`board_id`, `continent_id`, `territory_id`, `territory_name`, `territory_blurb`, `x`, `y`) VALUES
(2, 1, 6, 'San Francisco', 'A prominent port city for some years, San Francisco is the site of the Golden Gate Bridge, and for the layers of fog that routinely cover the city.', 87, 182),
(2, 1, 7, 'Los Angeles', 'A major port city and manufacturing center, smoggy Los Angeles suffers regular earthquakes, and is always on the lookout for the next \'big one\'. The first ARPANET transmission was sent from the local University of California.', 112, 234),
(2, 1, 8, 'Boise', 'The capital of the \"Potato State\", Boise is a viable getaway for someone who wants to experience nature, with an abundance of parks, trails, and gardens.', 161, 135),
(2, 2, 9, 'Calgary', 'The center of the Calgary region, this is a famous cattle, oil and tech city. Visitors can see the cityscape from the eponymous Calgary Tower. Calgary is much like Dallas, except for a less original naming scheme.', 197, 51),
(2, 2, 10, 'Edmonton', 'Once an obscure provincial capital, Edmonton became prominent as the greatest oil city in Canada. Now it houses the largest mall in Canada, and the Muttart Conservatory, notable for its glass pyramids.', 203, 20),
(2, 2, 11, 'Regina', 'Once named Wascana, which, being translated, means \"Pile-of-Bones\", the Princess Louise renamed this city in honor of her mother Queen Victoria. All Royal Canadian Mounted Police officers, or \"Mounties\", are trained at the academy here.', 260, 62),
(2, 2, 12, 'Winnipeg', 'Winnipeg is a key city in the grain industry. It is also the looniest city in Canada, thanks to the local Royal Mint.', 317, 70),
(2, 2, 13, 'Minneapolis', 'Once the world\'s leading city in flour mills, Minneapolis is now better known for honeycrisp apples, an abundance of skyways, and its tie to cartoonist Charles Schulz, who was born here.', 347, 134),
(2, 2, 14, 'Fargo', 'Fargo became prominent as a railroad city in the 19th century, and now offers North Dakota big-city amenities with small-town lines. It played a role in meteorology when Ted Fujita studied tornadic activity here.', 316, 112),
(2, 2, 15, 'Sioux City', 'At the head of the massive Missouri River, Sioux City happens to fall into three states. Outdoorsmen are likely to enjoy the thousand-acre Stone Park. The city houses a sizable Lewis and Clark Interpretive Center.', 317, 151),
(2, 2, 16, 'Thunder Bay', '', 381, 89),
(2, 3, 17, 'Billings', '', 237, 112),
(2, 3, 18, 'Cheyenne', '', 247, 171),
(2, 3, 19, 'Salt Lake City', '', 187, 174),
(2, 3, 20, 'Denver', '', 245, 193),
(2, 3, 21, 'Albuquerque', '', 228, 241),
(2, 3, 22, 'Phoenix', '', 175, 254),
(2, 3, 23, 'Ciudad Juárez', '', 222, 285),
(2, 3, 24, 'Chihuahua', '', 222, 330),
(2, 4, 25, 'Omaha', '', 323, 180),
(2, 4, 26, 'Des Moines', 'Besides its role in kicking off presidential campaigns and anchoring the insurance industry, Des Moines is host to the Iowa state fair, one of the largest and best in the country.', 346, 171),
(2, 4, 27, 'Kansas City', 'Kansas City is important to the federal government, hosting an IRS center. But it is not without its charms, as it is also home to SubTropolis, one of the world\'s largest artificial caves, and the Kauffman Center, which resembles the Sydney Opera House.', 342, 203),
(2, 4, 28, 'Wichita', '', 307, 213),
(2, 4, 29, 'Oklahoma City', '', 308, 244),
(2, 4, 30, 'Branson', '', 353, 226),
(2, 4, 31, 'Little Rock', '', 364, 252),
(2, 4, 32, 'Dallas', 'The heart of the Metroplex, Dallas is a famous cattle, oil and tech city. Visitors can see the sprawling cityscape from the Reunion Tower.', 316, 277),
(2, 4, 33, 'Shreveport', '', 350, 277),
(2, 5, 34, 'Milwaukee', 'German immigration in the 1800s helped mold this city and region. Known for cheese and dairy products, the Qwerty keyboard was developed here, and you can find hundreds of exotic plants at Mitchell Conservatory\'s \"Domes\".', 394, 151),
(2, 5, 35, 'Chicago', 'All railroads lead to Chicago. For generations this city has been a focal point for the nation\'s transportation system. O\'Hare Airport was the largest and busiest in the country for decades. The Sears Tower was once the tallest in the world.', 397, 166),
(2, 5, 36, 'Detroit', '', 437, 155),
(2, 5, 37, 'Indianapolis', 'Indianapolis is called the \'crossroads of America\', due to the highways that run through it. The famous Indy 500 race is held here, and the city is also home to the Children\'s Museum of Indianapolis, the largest museum of its kind in the world.', 414, 189),
(2, 5, 38, 'Columbus', '', 445, 182),
(2, 5, 39, 'St. Louis', 'St. Louis\' central location and easy access to two rivers have made it an ideal travel hub, perhaps leading to its reputation as the \"Gateway to the West\". ', 376, 208),
(2, 5, 40, 'Louisville', 'Louisville, the city that Muhammad Ali resided in, is an important shipping city. It is most recognized, however, for hosting the Kentucky Derby.', 423, 208);
INSERT INTO `thnt_territories` (`board_id`, `continent_id`, `territory_id`, `territory_name`, `territory_blurb`, `x`, `y`) VALUES
(2, 5, 41, 'Memphis', 'Once a leading cotton city of the south, Memphis is now known for its ties to Elvis Presley, whose home \'Graceland\' is here. It also houses the National Civil Rights Museum.', 386, 244),
(2, 5, 42, 'Nashville', 'While best-known as \"Music City\", Nashville is the most important health care city in the United States. Visitors may appreciate the sights of the Opryland hotel, and should know that the local hot chicken is said to be excellent.', 416, 234),
(2, 6, 43, 'Sudbury', '', 445, 105),
(2, 6, 44, 'Toronto', '', 469, 132),
(2, 6, 45, 'Ottawa', '', 488, 108),
(2, 6, 46, 'Montreal', '', 503, 106),
(2, 6, 47, 'Quebec City', '', 521, 84),
(2, 6, 48, 'Augusta', '', 543, 108),
(2, 6, 49, 'Concord', '', 530, 125),
(2, 6, 50, 'Boston', '', 539, 136),
(2, 6, 51, 'Hartford', '', 528, 147),
(2, 7, 52, 'Buffalo', '', 472, 145),
(2, 7, 53, 'New York City', '', 520, 155),
(2, 7, 54, 'Pittsburgh', '', 469, 175),
(2, 7, 55, 'Philadelphia', '', 511, 171),
(2, 7, 56, 'Washington D.C.', '', 495, 186),
(2, 7, 57, 'Richmond', '', 496, 205),
(2, 7, 58, 'Charlotte', '', 470, 232),
(2, 7, 59, 'Atlanta', '', 441, 260),
(2, 7, 60, 'Charleston', '', 487, 259),
(2, 8, 61, 'Austin', '', 314, 302),
(2, 8, 62, 'San Antonio', '', 303, 315),
(2, 8, 63, 'Houston', '', 336, 310),
(2, 8, 64, 'Monterrey', '', 284, 363),
(2, 8, 65, 'Mexico City', '', 282, 431),
(2, 8, 66, 'New Orleans', '', 387, 307),
(2, 8, 67, 'Mérida', '', 397, 415),
(2, 8, 68, 'Tampa', '', 469, 321),
(2, 8, 69, 'Miami', 'The largest passenger port in the world, Miami is a popular vacation location. Nearby Everglades National Park covers more than a million acres and houses dozens of threatened species, including the American crocodile and Florida panther.', 495, 344),
(5, 1, 1, 'Oak Harbour', 'The Oak Harbour proper is not as prominent as it was, but the vast Oak Airport has kept this city on the map. It is widely acknowledged as the best place to plan an arctic vacation.', 63, 81),
(5, 1, 2, 'Elkwood', 'This municipality is remarkable for its absence of metal or concrete buildings. It is difficult to tell whether this is caused by an unusual sensitivity to the environment, or the efforts of an incredibly powerful lumber union.', 76, 115),
(5, 1, 3, 'Persistence', 'Difficult terrain stymied early settlers coming this direction, so that it was only founded after several failed expeditions. In recent times it has become the core of manufacturing in Lumenda.', 170, 97),
(5, 1, 4, 'Perring', 'Perring\'s weather is the wettest in the west. When the world\'s first permanent underwater living spaces were built just off the coast, many residents moved there for drier conditions.', 87, 143),
(5, 1, 5, 'Mt. Thomas', 'Mt. Thomas is the foundation of the recycling and renovation movements, and one of the few places on the Latific coast that is inexpensive to live in, provided you plan on buying everything used.', 91, 170),
(5, 1, 6, 'Montanore', 'The city of Montenore is built into the eponymous Northern Mountains - at first by finding caves and open areas, then by excavation. Rumor has it that a gigantic government bunker was (and may still be) located right below the deepest rocks.', 76, 219),
(5, 1, 7, 'Bluebrooks', 'Pioneers settled here as soon as they saw the water springs. This thriving coastal city is now home to Bluebrooks Beverages, as well as Blueberry Computers and Bluebound Airlines.', 54, 267),
(5, 2, 8, 'Fog Peaks', 'Perpetually misty mountains overshadow this vacation city, which is otherwise known for its association with foxes, and the quality of local garlic bread.', 253, 116),
(5, 2, 9, 'Camilina', 'The city of the coal mines, Camilina prospered when it provided all of the Domain\'s power. The technology has changed, but the investments from the coal fund help support registered citizens to this day.', 285, 91),
(5, 2, 10, 'Lakensaid', 'This Svinling pirate colony was captured by the Royal Marines over a century ago. Some residents still agitate for independence, irritating the Crown, which has invested billions of crowns into the port\'s development.', 407, 113),
(5, 2, 11, 'Quicksey', 'Originally developed like an Englic country town, the capital of Lumenda is known for massive manors, abundant antiques, and tacky knicknacks. By tradition, the second-in-line to the throne presides over the Lumendan government from here.', 462, 89),
(5, 2, 12, 'New Narberth', 'This far-north port still has a touch of Waelen in it. The new world\'s largest castle, Caer Carnaevon broods over the city, which it still guards as the nation\'s oldest active military fortress.', 486, 55),
(5, 2, 13, 'Mentey', 'A epicenter for those seeking to devise \'fresh\' and \'new\' things, Mentey is known as the city with the most bizarre buildings and art in Lumenda. The Gallery of Invisible Drawings remains a perennial attraction.', 501, 113);
INSERT INTO `thnt_territories` (`board_id`, `continent_id`, `territory_id`, `territory_name`, `territory_blurb`, `x`, `y`) VALUES
(5, 3, 14, 'Goodfeet', 'The founders of this town reportedly came all the distance without the advantage of livestock. Once a decade, the city hosts a hundreds-of-miles race to retrace their steps.', 250, 149),
(5, 3, 15, 'Major', 'A military town from its earliest days, Major is now the permanent training grounds and headquarters of the Federal Guard, which is commanded by a man of the same title.', 189, 176),
(5, 3, 16, 'Frankly', '\"The most honest city in Cabatia\", Frankly passed a law against lying over a century ago, and it is enforced to this day. The nation\'s most reliable newspaper, the Frankly News & Reviews, operates from here.', 135, 202),
(5, 3, 17, 'Walana', 'The governing city of the Walachake Nation, and endowed with rich croplands after centuries of wise agriculture, Walana is the heartland of the Federation.', 250, 207),
(5, 3, 18, 'Alpine', 'Alpine was founded as a timber town, but this high-altitude city\'s economy has refocused around technology and tourism. Alpine is claimed to have a higher number of ski resorts per capita than any other city in the country.', 177, 256),
(5, 3, 19, 'Wise', 'The wisdom of settling in a desert wasteland seemed questionable, but this remote location has proven useful to astronomers, who benefit from clear skies and radio silence.', 184, 297),
(5, 4, 20, 'Yarrow', 'Yarrow was founded around oats, explaining the Common Cereal Company\'s attraction to this place for their mills and refineries. The first self-propelled harvester was tested here.', 290, 165),
(5, 4, 21, 'Inora', 'Affectionately known as the land of frozen lakes, Inora combines country charm with urban opportunities. The Cow Queen ice cream company is based here, and visitors enjoy visiting the city\'s renowned Silver Suspension Bridge.', 321, 147),
(5, 4, 22, 'Magtaw', 'Once the industrial capital of the country - perhaps of the world - Magtaw now has a reputation of being rusted out and run down, due to ongoing mismanagement. But it remains the key city in the region, and the hub for automotive companies.', 393, 165),
(5, 4, 23, 'Walloon', 'Although Walloon is a prosperous mid-nation city, it is notorious for wanton wildlife. Officials imported a variety of species from other countries, which have become invasive and cost the natives trillions of pence and even more peace of mind.', 307, 205),
(5, 4, 24, 'Harvey', 'Far into the Great Woods, Harvey always seems twenty years behind the rest of the world, and has earned a reputation as the nation\'s \'biggest small town\'.', 394, 220),
(5, 4, 25, 'Milkrow', 'Acclaimed the most average city in Cabatia, this is a favorite city for pollsters. More surveys, studies, and consumer tests have been performed here than in any other city over the past fifty years.', 285, 235),
(5, 5, 26, 'Herford', 'The most overlooked of the three great lake ports, Herford is primarily known for leather shoes and vacuum cleaners. It was the site of the first atomic generator, which is still in service today.', 454, 140),
(5, 5, 27, 'Mandril', 'This old metropolis is indelibly known for the colonial-era \'Mocha Mutiny\', to the extent that although the city remains prominent in its region, nobody remembers anything else about it.', 478, 151),
(5, 5, 28, 'Big Town', 'Always living up to its name, this erstwhile home of the new-world elite has transformed into a sprawling urban jungle. It features the tallest towers in the Federation, the largest population, and the longest traffic jams.', 481, 163),
(5, 5, 29, 'Opali', 'The meaning of the name \'Opali\' is  forgotten, other than that it has nothing to do with opals. It used to be a major steelworking center; during those times, magnate Jae Caernaeg financed most of the town\'s amenities, for the benefit of his workers.', 457, 174),
(5, 5, 30, 'Preston', 'The capital of the Cabatian Federation, named after the country\'s most prominent founder, besides being the site of the presidential palace and council buildings, hosts the National University, the largest educational institution in the hemisphere.', 491, 180),
(5, 5, 31, 'Ematrapoli', 'Although \'The Mother City\' has finally been superseded by its northern neighbors in political and economic importance, it remains the center of Cabatian medicine, and is considered one of the most pleasant cities in the nation to live and work in.', 479, 196),
(5, 5, 32, 'Jamton', 'This old southern city, named in honor of the sea captain who first put it on the map, gained most of its modern fame after the world\'s largest fruit preserve company set up their headquarters here.', 465, 240),
(5, 6, 33, 'Zip City', 'Zip City is known for seemingly endless suburbs and satellite cities, an equally extensive subway and skywalk system, and the most striking skyline in the state.', 286, 283);
INSERT INTO `thnt_territories` (`board_id`, `continent_id`, `territory_id`, `territory_name`, `territory_blurb`, `x`, `y`) VALUES
(5, 6, 34, 'Olivapoli', 'Constantly threatened by hurricanes, everything in Olivapoli is built with surviving the \'next one\' in mind, and each summer it is all put to the test. The Coastal Force training center is located here.', 363, 251),
(5, 6, 35, 'Seaport', 'The creatively-named city of Seaport features one of the largest harbors on the continent, as well as the largest shipyard in Cabatia. It is also a significant brownwater port, thanks to the state\'s ample supply of rivers and marshes.', 435, 265),
(5, 6, 36, 'Eggwater', 'Bereft of decent local drinking water, this hamlet remained obscure for some years until a Bible college set up their campus here.', 254, 311),
(5, 6, 37, 'Rocham', 'Although already a major sea port and oil city, Rocham truly took off when the Burrell Cosmos Center, the headquarters and center of the Cabatian space program, was built here.', 301, 310),
(5, 6, 38, 'Biemberg', 'The bourg of Biemberg exemplifies old-world architecture, which, when combined with its location along the coast, makes for the region\'s tourist trap. It is also known for its local bakeries and their countless varieties of pretzels.', 330, 324),
(5, 7, 39, 'Ft. John', 'Ft. John was built during fear of war, but kept due to its convenient mercantile location. It is now the most populated city on the west coast, as well as the most boring one.', 61, 315),
(5, 7, 40, 'Cansrice', 'Cansrice gained its name and fame during the Fool\'s Rush. Rumors of gold brought fortune-seekers, who learned the mines were excellent sources of pyrite. Since then, however, the city has grown into a successful commercial center.', 73, 342),
(5, 7, 41, 'Casesdatarres', 'This city is best known, and named for, its earthen hives, continuously inhabited since long-ago natives built them to provide refuge from the desert heat.', 124, 396),
(5, 7, 42, 'Dallies', 'Dallies is the birthplace of the modern air-refresher, invented by a local desperate to get out of the scorching heat. Since becoming marginally livable, the city has become a thriving scientific center.', 159, 348),
(5, 7, 43, 'Masmines', 'Built in good grounds near several rivers, Masmines is the most prominent farming community in Jenesce, as well as the second-most-populated.', 207, 382),
(5, 7, 44, 'Jenesce City', 'By virtue of its nearly impregnable defensive position, it seemed good to the old colonial government to build their capital here. Since then, it has overflowed to become the most populated and conspicuous city in the country.', 279, 420),
(5, 7, 45, 'Dalraya', 'The \'royal city\' housed kings for centuries. Although Jenesce no longer has a monarchy, the palace is still used for state functions, and to house high-ranking visitors; its grounds are open to tourists a few times a year.', 305, 375),
(5, 7, 46, 'Vesagees', 'Settlers came from \'over the seas\' to found this city, the first colony on the continental mainland. Previously a prominent port, it is now better-known as a tourist town and waypoint for Gulf cruise lines.', 348, 349);

-- --------------------------------------------------------

--
-- Table structure for table `thnt_territory_links`
--

CREATE TABLE `thnt_territory_links` (
  `board_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `territory_one_id` tinyint(3) UNSIGNED NOT NULL,
  `territory_two_id` tinyint(3) UNSIGNED NOT NULL,
  `one_way` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT '',
  `x` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `y` smallint(5) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `thnt_territory_links`
--

TRUNCATE TABLE `thnt_territory_links`;
--
-- Dumping data for table `thnt_territory_links`
--

INSERT INTO `thnt_territory_links` (`board_id`, `territory_one_id`, `territory_two_id`, `one_way`, `type`, `x`, `y`) VALUES
(1, 1, 2, 0, '', 90, 56),
(1, 1, 3, 0, '', 54, 72),
(1, 2, 3, 0, '', 99, 89),
(1, 2, 5, 0, '', 163, 57),
(1, 2, 21, 0, '', 140, 45),
(1, 3, 4, 0, '', 55, 131),
(1, 4, 6, 0, '', 48, 187),
(1, 4, 22, 0, '', 81, 146),
(1, 5, 20, 0, '', 236, 54),
(1, 5, 21, 0, '', 179, 30),
(1, 5, 25, 0, '', 230, 70),
(1, 6, 7, 0, '', 70, 264),
(1, 6, 8, 0, '', 40, 285),
(1, 7, 9, 0, '', 110, 301),
(1, 8, 9, 0, '', 74, 325),
(1, 9, 10, 0, '', 111, 357),
(1, 10, 11, 0, '', 179, 393),
(1, 11, 12, 0, '', 300, 404),
(1, 11, 13, 0, '', 279, 372),
(1, 12, 13, 0, '', 318, 375),
(1, 12, 14, 0, '', 364, 388),
(1, 13, 15, 0, '', 339, 323),
(1, 13, 23, 0, '', 321, 313),
(1, 14, 15, 0, '', 383, 339),
(1, 15, 16, 0, '', 396, 262),
(1, 15, 23, 0, '', 356, 288),
(1, 16, 17, 0, '', 389, 205),
(1, 17, 18, 0, '', 392, 161),
(1, 17, 19, 0, '', 357, 142),
(1, 17, 24, 0, '', 336, 168),
(1, 18, 19, 0, '', 381, 112),
(1, 19, 20, 0, '', 330, 80),
(1, 20, 24, 0, '', 297, 99),
(1, 20, 25, 0, '', 280, 80),
(1, 23, 24, 0, '', 0, 0),
(1, 25, 26, 0, '', 243, 117),
(1, 26, 27, 0, '', 238, 138),
(1, 27, 2, 1, '', 237, 148),
(1, 27, 7, 1, '', 110, 215),
(1, 27, 23, 1, '', 296, 207),
(2, 1, 2, 0, '', 0, 0),
(2, 1, 3, 0, '', 0, 0),
(2, 2, 6, 0, '', 0, 0),
(2, 3, 4, 0, '', 0, 0),
(2, 3, 9, 0, '', 0, 0),
(2, 4, 5, 0, '', 0, 0),
(2, 5, 6, 0, '', 0, 0),
(2, 5, 8, 0, '', 0, 0),
(2, 6, 7, 0, '', 0, 0),
(2, 7, 19, 0, '', 0, 0),
(2, 7, 22, 0, '', 0, 0),
(2, 8, 17, 0, '', 0, 0),
(2, 8, 19, 0, '', 0, 0),
(2, 9, 10, 0, '', 0, 0),
(2, 9, 11, 0, '', 0, 0),
(2, 10, 12, 0, '', 0, 0),
(2, 11, 12, 0, '', 0, 0),
(2, 12, 13, 0, '', 0, 0),
(2, 12, 16, 0, '', 0, 0),
(2, 13, 14, 0, '', 0, 0),
(2, 13, 26, 0, '', 0, 0),
(2, 13, 34, 0, '', 0, 0),
(2, 14, 15, 0, '', 0, 0),
(2, 14, 17, 0, '', 0, 0),
(2, 15, 25, 0, '', 0, 0),
(2, 16, 43, 0, '', 0, 0),
(2, 17, 18, 0, '', 0, 0),
(2, 18, 19, 0, '', 0, 0),
(2, 18, 20, 0, '', 0, 0),
(2, 18, 25, 0, '', 0, 0),
(2, 20, 21, 0, '', 0, 0),
(2, 20, 28, 0, '', 0, 0),
(2, 21, 22, 0, '', 0, 0),
(2, 21, 23, 0, '', 0, 0),
(2, 21, 29, 0, '', 0, 0),
(2, 22, 23, 0, '', 0, 0),
(2, 23, 24, 0, '', 0, 0),
(2, 24, 64, 0, '', 0, 0),
(2, 25, 26, 0, '', 0, 0),
(2, 25, 27, 0, '', 0, 0),
(2, 26, 27, 0, '', 0, 0),
(2, 26, 35, 0, '', 0, 0),
(2, 27, 28, 0, '', 0, 0),
(2, 27, 30, 0, '', 0, 0),
(2, 27, 39, 0, '', 0, 0),
(2, 28, 29, 0, '', 0, 0),
(2, 29, 30, 0, '', 0, 0),
(2, 29, 31, 0, '', 0, 0),
(2, 29, 32, 0, '', 0, 0),
(2, 30, 39, 0, '', 0, 0),
(2, 31, 33, 0, '', 0, 0),
(2, 31, 41, 0, '', 0, 0),
(2, 32, 33, 0, '', 0, 0),
(2, 32, 61, 0, '', 0, 0),
(2, 32, 63, 0, '', 0, 0),
(2, 33, 66, 0, '', 0, 0),
(2, 34, 35, 0, '', 0, 0),
(2, 35, 36, 0, '', 0, 0),
(2, 35, 37, 0, '', 0, 0),
(2, 36, 38, 0, '', 0, 0),
(2, 36, 52, 0, '', 0, 0),
(2, 37, 38, 0, '', 0, 0),
(2, 37, 39, 0, '', 0, 0),
(2, 37, 40, 0, '', 0, 0),
(2, 38, 40, 0, '', 0, 0),
(2, 38, 54, 0, '', 0, 0),
(2, 39, 40, 0, '', 0, 0),
(2, 39, 41, 0, '', 0, 0),
(2, 40, 42, 0, '', 0, 0),
(2, 41, 42, 0, '', 0, 0),
(2, 42, 59, 0, '', 0, 0),
(2, 43, 44, 0, '', 0, 0),
(2, 43, 45, 0, '', 0, 0),
(2, 44, 45, 0, '', 0, 0),
(2, 44, 52, 0, '', 0, 0),
(2, 45, 46, 0, '', 0, 0),
(2, 46, 47, 0, '', 0, 0),
(2, 46, 49, 0, '', 0, 0),
(2, 47, 48, 0, '', 0, 0),
(2, 48, 49, 0, '', 0, 0),
(2, 48, 50, 0, '', 0, 0),
(2, 49, 50, 0, '', 0, 0),
(2, 50, 51, 0, '', 0, 0),
(2, 51, 53, 0, '', 0, 0),
(2, 52, 53, 0, '', 0, 0),
(2, 52, 54, 0, '', 0, 0),
(2, 53, 55, 0, '', 0, 0),
(2, 54, 55, 0, '', 0, 0),
(2, 54, 56, 0, '', 0, 0),
(2, 55, 56, 0, '', 0, 0),
(2, 56, 57, 0, '', 0, 0),
(2, 57, 58, 0, '', 0, 0),
(2, 58, 59, 0, '', 0, 0),
(2, 58, 60, 0, '', 0, 0),
(2, 59, 66, 0, '', 0, 0),
(2, 59, 68, 0, '', 0, 0),
(2, 60, 69, 0, '', 0, 0),
(2, 61, 62, 0, '', 0, 0),
(2, 61, 63, 0, '', 0, 0),
(2, 62, 64, 0, '', 0, 0),
(2, 63, 66, 0, '', 0, 0),
(2, 64, 65, 0, '', 0, 0),
(2, 65, 67, 0, '', 0, 0),
(2, 66, 67, 0, '', 0, 0),
(2, 66, 68, 0, '', 0, 0),
(2, 67, 68, 0, '', 0, 0),
(2, 68, 69, 0, '', 0, 0),
(5, 1, 2, 0, '', 0, 0),
(5, 1, 3, 0, '', 0, 0),
(5, 2, 3, 0, '', 0, 0),
(5, 2, 4, 0, '', 0, 0),
(5, 3, 8, 0, '', 0, 0),
(5, 4, 5, 0, '', 0, 0),
(5, 5, 6, 0, '', 0, 0),
(5, 5, 16, 0, '', 0, 0),
(5, 6, 7, 0, '', 0, 0),
(5, 6, 16, 0, '', 0, 0),
(5, 6, 18, 0, '', 0, 0),
(5, 7, 39, 0, '', 0, 0),
(5, 8, 9, 0, '', 0, 0),
(5, 8, 14, 0, '', 0, 0),
(5, 9, 10, 0, '', 0, 0),
(5, 10, 11, 0, '', 0, 0),
(5, 10, 26, 0, '', 0, 0),
(5, 11, 12, 0, '', 0, 0),
(5, 11, 13, 0, '', 0, 0),
(5, 12, 13, 0, '', 0, 0),
(5, 13, 26, 0, '', 0, 0),
(5, 14, 15, 0, '', 0, 0),
(5, 14, 20, 0, '', 0, 0),
(5, 15, 16, 0, '', 0, 0),
(5, 15, 17, 0, '', 0, 0),
(5, 17, 18, 0, '', 0, 0),
(5, 17, 23, 0, '', 0, 0),
(5, 18, 19, 0, '', 0, 0),
(5, 19, 36, 0, '', 0, 0),
(5, 19, 42, 0, '', 0, 0),
(5, 20, 21, 0, '', 0, 0),
(5, 20, 23, 0, '', 0, 0),
(5, 21, 22, 0, '', 0, 0),
(5, 22, 23, 0, '', 0, 0),
(5, 22, 24, 0, '', 0, 0),
(5, 22, 26, 0, '', 0, 0),
(5, 22, 29, 0, '', 0, 0),
(5, 23, 25, 0, '', 0, 0),
(5, 24, 29, 0, '', 0, 0),
(5, 24, 34, 0, '', 0, 0),
(5, 25, 33, 0, '', 0, 0),
(5, 26, 27, 0, '', 0, 0),
(5, 27, 28, 0, '', 0, 0),
(5, 28, 29, 0, '', 0, 0),
(5, 28, 30, 0, '', 0, 0),
(5, 29, 30, 0, '', 0, 0),
(5, 29, 31, 0, '', 0, 0),
(5, 30, 31, 0, '', 0, 0),
(5, 31, 32, 0, '', 0, 0),
(5, 32, 35, 0, '', 0, 0),
(5, 33, 34, 0, '', 0, 0),
(5, 33, 37, 0, '', 0, 0),
(5, 34, 35, 0, '', 0, 0);
INSERT INTO `thnt_territory_links` (`board_id`, `territory_one_id`, `territory_two_id`, `one_way`, `type`, `x`, `y`) VALUES
(5, 35, 38, 0, '', 0, 0),
(5, 35, 46, 0, '', 0, 0),
(5, 36, 37, 0, '', 0, 0),
(5, 37, 38, 0, '', 0, 0),
(5, 38, 46, 0, '', 0, 0),
(5, 39, 40, 0, '', 0, 0),
(5, 39, 42, 0, '', 0, 0),
(5, 40, 41, 0, '', 0, 0),
(5, 41, 43, 0, '', 0, 0),
(5, 42, 43, 0, '', 0, 0),
(5, 43, 44, 0, '', 0, 0),
(5, 44, 45, 0, '', 0, 0),
(5, 45, 46, 0, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ult_games`
--

CREATE TABLE `ult_games` (
  `game_id` tinyint(3) UNSIGNED NOT NULL,
  `match_id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `host_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `guest_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `opening` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT '',
  `draw_offer` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `outcome` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `host_score` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `guest_score` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `first_mover` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `ult_games`
--

TRUNCATE TABLE `ult_games`;
-- --------------------------------------------------------

--
-- Table structure for table `ult_invites`
--

CREATE TABLE `ult_invites` (
  `match_id` int(11) NOT NULL,
  `match_name` varchar(50) NOT NULL,
  `event` int(11) DEFAULT NULL,
  `host` int(10) UNSIGNED NOT NULL,
  `guest` int(10) UNSIGNED DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'open',
  `match_length` tinyint(3) UNSIGNED DEFAULT 1,
  `private` int(11) NOT NULL DEFAULT 1,
  `access_code` varchar(100) NOT NULL,
  `mover` varchar(10) NOT NULL,
  `alternating` tinyint(1) NOT NULL DEFAULT 1,
  `ot` tinyint(1) NOT NULL DEFAULT 0,
  `clock` varchar(16) NOT NULL DEFAULT 'none',
  `rules` varchar(10) NOT NULL DEFAULT 'ult',
  `opening` int(11) NOT NULL DEFAULT 0,
  `start_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `outcome` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `ult_invites`
--

TRUNCATE TABLE `ult_invites`;
-- --------------------------------------------------------

--
-- Table structure for table `ult_matches`
--

CREATE TABLE `ult_matches` (
  `match_id` int(11) NOT NULL,
  `match_name` varchar(50) NOT NULL,
  `event` int(11) DEFAULT NULL,
  `host` int(10) UNSIGNED NOT NULL,
  `guest` int(10) UNSIGNED DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'open',
  `match_length` tinyint(3) UNSIGNED DEFAULT 1,
  `private` int(11) NOT NULL DEFAULT 1,
  `access_code` varchar(100) NOT NULL,
  `mover` varchar(10) NOT NULL,
  `alternating` tinyint(1) NOT NULL DEFAULT 1,
  `ot` tinyint(1) NOT NULL DEFAULT 0,
  `clock` varchar(16) NOT NULL DEFAULT 'none',
  `rules` varchar(10) NOT NULL DEFAULT 'ult',
  `opening` int(11) NOT NULL DEFAULT 0,
  `start_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `outcome` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `ult_matches`
--

TRUNCATE TABLE `ult_matches`;
-- --------------------------------------------------------

--
-- Table structure for table `ult_moves`
--

CREATE TABLE `ult_moves` (
  `match_id` int(10) UNSIGNED NOT NULL,
  `move_id` int(10) UNSIGNED NOT NULL,
  `game_id` tinyint(3) UNSIGNED NOT NULL,
  `move` varchar(8) NOT NULL,
  `performed` datetime NOT NULL DEFAULT current_timestamp(),
  `player` tinyint(3) UNSIGNED NOT NULL,
  `piece` varchar(2) DEFAULT NULL,
  `performed_string` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Truncate table before insert `ult_moves`
--

TRUNCATE TABLE `ult_moves`;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `fitb_puzzles`
--
ALTER TABLE `fitb_puzzles`
  ADD PRIMARY KEY (`puzzle_date`);

--
-- Indexes for table `home_accounts`
--
ALTER TABLE `home_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `home_account_auths`
--
ALTER TABLE `home_account_auths`
  ADD PRIMARY KEY (`id`,`auth_type`);

--
-- Indexes for table `home_gpress_posts`
--
ALTER TABLE `home_gpress_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `home_gpress_topics`
--
ALTER TABLE `home_gpress_topics`
  ADD PRIMARY KEY (`topic_id`);

--
-- Indexes for table `home_gpress_topic_links`
--
ALTER TABLE `home_gpress_topic_links`
  ADD PRIMARY KEY (`post_id`,`topic_id`);

--
-- Indexes for table `home_prefs`
--
ALTER TABLE `home_prefs`
  ADD PRIMARY KEY (`user_id`,`setting`) USING BTREE;

--
-- Indexes for table `home_tracks`
--
ALTER TABLE `home_tracks`
  ADD PRIMARY KEY (`track_id`),
  ADD KEY `id` (`id`,`ip`) USING BTREE;

--
-- Indexes for table `maths_cats`
--
ALTER TABLE `maths_cats`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `maths_problems`
--
ALTER TABLE `maths_problems`
  ADD PRIMARY KEY (`inc_id`);

--
-- Indexes for table `maths_user_cats`
--
ALTER TABLE `maths_user_cats`
  ADD PRIMARY KEY (`user_id`,`cat_id`);

--
-- Indexes for table `msgr_chapters`
--
ALTER TABLE `msgr_chapters`
  ADD PRIMARY KEY (`thread_id`,`chapter_num`);

--
-- Indexes for table `msgr_msgs`
--
ALTER TABLE `msgr_msgs`
  ADD PRIMARY KEY (`local_msg_id`,`thread_id`),
  ADD UNIQUE KEY `msg_id` (`msg_id`);

--
-- Indexes for table `msgr_threads`
--
ALTER TABLE `msgr_threads`
  ADD PRIMARY KEY (`thread_id`);

--
-- Indexes for table `msgr_thread_auths`
--
ALTER TABLE `msgr_thread_auths`
  ADD PRIMARY KEY (`thread_id`,`member_id`);

--
-- Indexes for table `plus_minus_games`
--
ALTER TABLE `plus_minus_games`
  ADD PRIMARY KEY (`game_id`);

--
-- Indexes for table `plus_minus_movers`
--
ALTER TABLE `plus_minus_movers`
  ADD PRIMARY KEY (`local_id`);

--
-- Indexes for table `plus_minus_scores`
--
ALTER TABLE `plus_minus_scores`
  ADD PRIMARY KEY (`game_id`,`local_id`);

--
-- Indexes for table `sys_settings`
--
ALTER TABLE `sys_settings`
  ADD PRIMARY KEY (`setting`);

--
-- Indexes for table `test_table`
--
ALTER TABLE `test_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thnt_boards`
--
ALTER TABLE `thnt_boards`
  ADD PRIMARY KEY (`board_id`);

--
-- Indexes for table `thnt_card_types`
--
ALTER TABLE `thnt_card_types`
  ADD PRIMARY KEY (`card_type_id`);

--
-- Indexes for table `thnt_continents`
--
ALTER TABLE `thnt_continents`
  ADD PRIMARY KEY (`board_id`,`continent_id`);

--
-- Indexes for table `thnt_games`
--
ALTER TABLE `thnt_games`
  ADD PRIMARY KEY (`game_id`),
  ADD KEY `game_id` (`game_id`,`game_status`);

--
-- Indexes for table `thnt_messages`
--
ALTER TABLE `thnt_messages`
  ADD PRIMARY KEY (`game_id`,`move_id`);

--
-- Indexes for table `thnt_players`
--
ALTER TABLE `thnt_players`
  ADD PRIMARY KEY (`game_id`,`local_id`),
  ADD KEY `user_id` (`user_id`,`local_id`);

--
-- Indexes for table `thnt_territories`
--
ALTER TABLE `thnt_territories`
  ADD PRIMARY KEY (`board_id`,`territory_id`);

--
-- Indexes for table `thnt_territory_links`
--
ALTER TABLE `thnt_territory_links`
  ADD PRIMARY KEY (`board_id`,`territory_one_id`,`territory_two_id`);

--
-- Indexes for table `ult_games`
--
ALTER TABLE `ult_games`
  ADD UNIQUE KEY `game_id` (`game_id`,`match_id`);

--
-- Indexes for table `ult_invites`
--
ALTER TABLE `ult_invites`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `ult_matches`
--
ALTER TABLE `ult_matches`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `ult_moves`
--
ALTER TABLE `ult_moves`
  ADD PRIMARY KEY (`match_id`,`move_id`,`game_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `home_accounts`
--
ALTER TABLE `home_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home_gpress_posts`
--
ALTER TABLE `home_gpress_posts`
  MODIFY `post_id` mediumint(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home_gpress_topics`
--
ALTER TABLE `home_gpress_topics`
  MODIFY `topic_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `home_tracks`
--
ALTER TABLE `home_tracks`
  MODIFY `track_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maths_problems`
--
ALTER TABLE `maths_problems`
  MODIFY `inc_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `msgr_msgs`
--
ALTER TABLE `msgr_msgs`
  MODIFY `msg_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `msgr_threads`
--
ALTER TABLE `msgr_threads`
  MODIFY `thread_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plus_minus_games`
--
ALTER TABLE `plus_minus_games`
  MODIFY `game_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plus_minus_movers`
--
ALTER TABLE `plus_minus_movers`
  MODIFY `local_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_table`
--
ALTER TABLE `test_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `thnt_card_types`
--
ALTER TABLE `thnt_card_types`
  MODIFY `card_type_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `thnt_games`
--
ALTER TABLE `thnt_games`
  MODIFY `game_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ult_invites`
--
ALTER TABLE `ult_invites`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ult_matches`
--
ALTER TABLE `ult_matches`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
