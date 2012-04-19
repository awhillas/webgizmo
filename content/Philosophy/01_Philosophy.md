# The Gizmo Philosophy

After 16 years of trying to engineer the ultimate Content Management System (CSM), the 'Silver Bullet' as its been called, of which there are many hundreds of projects already existing, I finally realised that the majority of small to medium websites do not require the heavy, cumbersome, complicated machinery of these generally large CMS systems. More over, i was just tired of it. 

## Less is more

I wanted a simple, flexible and lightweight CMS. A CMS that could be setup in 5 minutes. It didn't have to do everything but I wanted something that would sit between hand written HTML and a database driven system that would require the server to have a database, which frequently the cheapest hosting accounts don't offer.

Gizmo can be used for **rapid prototyping** of an idea or design as you can drag and drop files into place and without any monkeying around with code all the basics are working as you'd expect so you can focus on the juicy stuff.

The key thing to realise about this approach is that its not trying to do everything. If something ca not be realised without compromising the core design principles then its left out ("Go and get yourself a mega CMS Sir, there are plenty of them!"). Gizmo is for the 60% of the websites in the middle of the bell curve which require simple static content deliver that can be updated.

## The interface is dead: Thank god!

The number of clients who have rang me up after 6 months because they have completely forgotten how to use the admin interface of the wiz-bang CMS I setup for them has really taken the fun out of designing forms and funky widgets. **The problem with interfaces is that people will always have to learn them**. And the problem with people is they will always forget everything you tell them. As a result they will not updated their site and thus the whole "updatable site" project you just engaged in will be a _failure_.

Gizmo is an attempt to side step this whole interface problem by simply using the computer interface everyone already knows: files and folders. There are still people who don't understand this but they are edge cases (I hope). People know this interface and they won't forget it because most likely they manipulate files and folder on a daily basis. FTP also requires a little bit more knowledge but there are ways to make this transparent.

I knew that I was on to something when my cousin, who is a young but not particularly computer savvy, ceramics artist managed to completely reorganise her Gizmo website without any contact with me and only a 30 minute run down of how to FTP (and where to FTP!). Not only that most of the people I have setup with Gizmo, who are mostly of the same computer skill level, are constantly updating their websites: because suddenly they can! Amazingly none of them need help after the first introduction. Its very encouraging.

## Simple but tough!

Given that I wanted Gizmo to be at home in shared hosting environments, that is, all those cheap hosting deals where there are many accounts sharing the same web server, I wanted to tackle the common security problem i was constantly facing. 

Since all the accounts share the same web server, and thus need to give that same web server equal access to all accounts ion that environment, a web script running in one account can access (hack) any other account on the same server. A script-kiddys paradise. 

Its not all files/folders in the other accounts, just the ones the web server can read and write to. If you want to upload files to the website you need to leave at least one folder exposed. 

SO I decided that the only way to get content on and off the server was via FTP since this maintained file security (read/write access) and would by-pass whole shared hosting problem. This does have some limitations such as no file upload interface, but since there is also no authentication why would anyone want this? It also means no caching of pulled in content (i.e. RSS feeds) but given the speed of most servers and their connections these days, its not really a problem for sites of this scale.