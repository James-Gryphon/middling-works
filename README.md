# Introduction
This is a complete archive of the files and settings needed to run the Middling Works development page, including its special features, as of early June, 2025.
I've decided to give up computer programming. My initial inclination was to burn my bridges, but after getting counsel, I've decided instead to release it all under a special license. I worked for a long time on all of this, and it would be a shame to see it, especially ULT and The Hunt go to waste. Maybe you can complete the job and succeed where I wasn't meant to. If you do, let me know. I'd love to see it.
## This release includes:
1) An account registration system, with forgotten password reset, etc.
2) A blog system for admin usage, meant to be placed in a secure directory and protected with IP address.
3) Dice Games (buried in the 'oldfiles' directory), a toy where virtual competitors battle it out for a championship.
4) Fill in the Blanks, a game where you try to guess the word/phrase.
5) Plus-Minus, a count game.
6) Synchronous Messages, a service that allows you to share messages with others that are only simultaneously revealed.
7) Maths Map, a math practice game based on a certain old website, prior to their changes that took the galactic conquest away.
8) The Hunt, where you and/or other players try to track down the location of a "Mole"
9) Ultimate (ULT), the best ever (even in its current imperfect state) service for playing Ultimate Tic-Tac-Toe with a person.
10) Mail, a forum-inspired private/group messaging service with a unique 'chapters' feature for organizing information in threads. I've seen a lot of forums, but I've never seen one with something like this.
11) Early work towards setting up a subscription service option. This worked as far as restricting access to certain features, but there's no mechanism to actually automatically promote an account or to link it with a payment service. I didn't get to that part.
###  Why the (EVIL NON-FREE) license?
I like the MIT license myself. But I told one of my collaborators that he would be a 'life member'. I may not benefit from this anymore myself, but I intend to make sure that if someone starts a subscription service that does the same things I had in mind, he'll keep that perk. If he tells me he doesn't want this, then we'll switch.
### Are there any pictures?
I'm afraid not. Archive.org isn't much help either, since it breaks the CSS files. I give you my personal guarantee, though, that when this was up on the site early June, 2025, not only did the vast majority of the site work, it even looked pretty spiffy (provided that you like solarized websites with a '90s/early '00s aesthetic). This repository isn't a bunch of stuff I never got close to completing - it's a lot of finished features, topped off with a couple of big ones that were nearly done.
### If it's so great why did you stop?
Personal reasons. (You can find some of them, but not the most important ones, if you look hard enough.)
### What's the code like?
Pretty much like you would expect a paranoid, self-taught amateur's code to be like, I suppose. It's mostly procedural (Dice Games was my only real experiment with object orientation) and uses inconsistent styling (I preferred Allman, but earlier code was more K&R, and the formatting and indentation practices were inconsistently followed). It is probably better than it sounds, and not as good as it should be. 
### Does it have an installer?
Nope.
You have to import the SQL tables, then go into the settings files and adjust the password/directories/etc. for your case, and then some other things, before you'll have a working implementation.
It is a leap for a beginner, but if you're not as good as, or better than, I was at coding, it's doubtful you would be able to get great use out of this project. There are risks to server-side software.
### Is it secure?
M*aaaay*be?

I think you could do worse, following random basic guides. I took this problem seriously, looked all over for people's writings on this matter, and tried to lock things down as much as possible, in accordance with the best practices I saw. I once bought a gent's third-party solution, and was gratified to find that it offered very little in comparison to what I already had, so there's that.

I'm not specifically aware of any direct vulnerabilities that allow malicious user input or output, or access to the internals of the system. There is a known weakness in the system that locks people out when they have too many attempts, but that doesn't allow malicious access, it just makes life hard for everybody. There are a few times that I use unprepared queries, but only when there is no user input involved, or it has been carefully filtered by the same means I used for front-end display.

The admin panel, and other similar tools scattered here and there, are insecure. With the site set up I had, though, it 1) was in a password-protected directory that the general public never knew about, and 2) depended on my IP for access. If you try to reproduce my setup (as opposed to doing it all on your local machine and only syncing when you have a release in mind), I recommend you try something at least as secure as that.
### What's the structure?
The way the site was originally laid out was like this:

The parent directory (*middlingworks*), top-level, inaccessible except through SFTP or cPanel, contained the cron job scripts, the session folder, and the settings.ini file that was used by the public-facing directory. This release is based on the testing version, so it doesn't include the public face, but you would include that in this parent directory. The admin panel features a functionality that allows you to auto-copy all the relevant files over to it.

*sessions_kumquat*: Stores all the sessions. Would a DB be better? Maybe. We never got to that point.

*oldfiles*: An archive folder that contains stuff I never got around to getting rid of. If you like Dice Games, you should be glad I didn't.

*kumquat*: Contains the meat of the code. This was originally where my Git setup was synced to. The online prototype would be run here, and then when I was ready for a public release, I could export things. Critical code would go just to "files" in the top directory; the index.php file and front-end things go to public_html.

*files-kumquat's contents:*
The gist is something like this:*index.php* is a bottom layer, which then loads a 'module' (the term I oft used was 'feature'; the GET param is 's', short for 'site') if one is asked for by the GET param. The modules, besides the 'links' files (which have lists of permitted GET params) are split up roughly into '*files*' and '*temp*' folders. The former contains backend code, the *temp* folders contain the display templates (which may occasionally overlap into logic, but I tried, on some level, to avoid this).
### Why no error handling?
Because my coding is *exhaustive*, and servers ***never*** have any technical problems that would result in some of that code working and not other parts.
### Do you really believe that?
Well, I _did_. But when I actually write it out, yes, it looks silly.

In practice, I think the coding *is* pretty exhaustive when there might be a breaking error, and it can ignore the others. I had some doubts myself later on, but the time to leave came up before I got around to addressing it.
### Does it use x framework?
Nope. I eschewed them. I think I ended up making my own instead.
### PHP? Ew.
Well, don't knock it until you understand why I chose it. I felt that given the restrictions I was under, it made sense. It's a common language for shared servers, which is the budget I was on, it is one of the most performant options of its type, I had some experience with it, and I wanted my services to be able to run on low-end computers and browsers (which meant minimal front-end JavaScript).

If you change any of those premises, maybe you end up deciding Java or Python or something is better. But PHP was best for me.
### Do I have to abide by the terms of code use described in the terms page?
No; the license for this release supercedes that. I wrote those terms for people looking at the site in their browsers. (Incidentally it contains an interesting theoretical vulnerability; if anyone was able to get publicdomain tags to show up somewhere, they could arbitrarily make things inside it open-source. If you base anything on my terms, I suggest you revise that.)
### James, you left a password in there!
Whoops.

It's annoying if I did this, since I did a scan to see that I didn't leave anything like that in. But I don't think any of them are valid anymore, since the server setup I use for my personal site has changed.
### What didn't you finish?
ULT was the main big feature that wasn't more or less ready for production (according to my standards), but it was working pretty well, enough for us to play some test matches. There might be some bugs when it comes to handling overtime/tiebreaks, since we didn't get around to testing that (recently). I recommend doing extensive testing.

The subscription manager didn't get finished, as far as actually giving you a way to get your account access promoted. I intended to do this last. It would have worked with BMT Micro's servers for the purpose. In principle, I suppose you could use any MoR or payment processor you trust. If you decide you don't want subscription service (admirable, so long as you have the money and don't want to do this professionally), it shouldn't be hard to remove the things that need it from the code.

The Hunt works, but it isn't great for mobile use. (A lot of things aren't, actually.) Also, the process of making new maps requires a lot of SQL editing. That's annoying but I never got around to doing anything more. You can at least use the map viewer tool to see whether things link up to each other.

(Something similar is true for Maths Map; it uses a SVG file, *and* has coordinates that need updated in the database, and that's a hassle to do quickly if you're not focused on the project and used to it.)

As far as improvements? There are so many things. But I don't have a complete list of that anymore.
### What are some possible hangups in getting this working?
Directory and path things, maybe hardcoded stuff that I forgot about. You need your own account, ID number 1, as I recall. Some of the permissions for things require you to enter a SQL client of your choice and give yourself powers. You need email service. I used PHPMailer, but I don't want to include their installation in my files, so you need to download it separately and include it (or something equivalent). There are a few dead files that aren't actually used in anything (The Hunt in particular is bad about this; it's littered with stuff from the early development process, that I never got around to getting rid of). You need PHP 8.x, probably a minimum of 8.2, to be safe.
### Your coding is so bad, James!
Thanks. I'm glad you agree with my big decision, even if not for all the same reasons I had.
### I like the ideas, but this codebase isn't good enough for production.
Well, use what you can and improve the rest. Or don't, and make software that does what I meant.

### I have questions about *x*.
Well, write me about it at mw @ soopergrape.com. If it doesn't take too much time, and I remember what I was thinking, I may try to explain it. If it's frequent enough, your question may get added here.
### You need to fix *x*, because *y*/You need to accept my pull request.
I'm sorry, but this will not happen. I'm serious about leaving this field.

If there are known good forks of these projects, and I feel the maintainer is trustworthy, I'm willing to link to or endorse them. But this project is necessarily unmaintained. If I were working on it, it wouldn't be here. I can't work on my code, or review yours.
