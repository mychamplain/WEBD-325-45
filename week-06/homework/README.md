# WEBD-325-45 – PHP Programming, Adv.
> Llewellyn van der Merwe<br />
Week 6: Homework – Understanding Frameworks<br />
May 1st, 2022

### Little background

So many years ago I did a course by Bernard Pineda on Symphony 3 and I build a little basic application for (Landon Hotel), so this time I chose to use Zend, now called Laminas and the experience was better. This could be because I am now much more knowledgeable then I was back in the day when I looked at Symphony, and since my daily work environment is Ubuntu Linux, the installation and startup of a projects was easy. I made use for their online documentation tutorial to setup an MVC wire-frame.

### How does the framework work?

So with the help of composer commands we got a basic list of questions that help speedup some of the development flow. Once we finished making our selection composer installs our skeleton set of files with the basic defaults ready to start development.

To view these we can either link our project to an Apache server, or simple run a PHP command to serve our application over a port of our choosing.

At this point we can say the application is working, as to how it works… this can get very technical.

Again looking at the documentation they start by explaining that laminas-mvc uses a module system.

https://docs.laminas.dev/tutorials/getting-started/modules/

This is easy to understand and a great entry point into the whole MVC architecture of Laminas. Once your able to understand how these things come together in the router and controller the door is open and you can start extending your business logic.

https://docs.laminas.dev/tutorials/getting-started/database-and-models/

Needless to say I found all these examples super easy, and very powerful and extremely quick to achieve otherwise very complex and difficult tasks.

My overall feel is that Laminas works very well and will be a great choice for any application need.

### Does using a framework make sense all the time? Why? Why not?

This issue also came up in one of our conversation in the discussion last week, and I will quote my reply to one of the students.

> Yes the MVC adds unnecessary complexity to small projects. But how will you determine that a project is no longer small? And if a small project grows... when will we need to change the project's architecture?<br /><br />
You see my problem is that I have written way to many small projects that ended up becoming huge and then to refactor those projects is much harder then having just started it with scalability in mind from the start.<br /><br />
So my question is when is a project no longer small? Is it the number of lines or the number of views? I mean the CMS we are building for this course is a small project in comparison to some of the projects I work on everyday.<br /><br />
Yet, it has more than 10k lines (excluding the composer libraries) of code, and it already has about ten MVC sets of classes. Having all that code in one file will be very hard to maintain.<br /><br />
But yes, I realize working with MVC in Joomla for so many years, it no longer feels complex to me, but in fact seems like the easy way out from complexity... :)

So in my world I hardly every not use MVC frameworks, yet having said that I am not saying that is the normal, or the best way, it is just how I work. Testing a small snipped of PHP and looking at the result of a small algorithmic I still have a few test.php files in my server path to do quick little bits of code. But the moment I am in projects that are for online systems I hardly ever no use some kind of framework.

### Reference

- "Laminas." Wikipedia, 1 May 2022. Wikipedia, https://en.wikipedia.org/w/index.php?title=Laminas&oldid=1078914186.
- Tutorials - Laminas Docs. https://docs.laminas.dev/tutorials/. Accessed 1 May 2022.

