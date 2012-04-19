# The Gentle Guide to FTP

To FTP you will need an [FTP client](http://cyberduck.ch/) and your FTP logon details which you should have received when you setup the hosting for your domain name. If you did not then send their support an email!

## What is FTP?

FTP lets you "upload" your files from you computer to another computer. In the Gizmo case this "other computer" will be the web server which is online 24/7 (hopefully) i.e. the guys you pay to host your domain name (you domain is something like: www.example.com).

To make FTP easy (and it really is), you will need an FTP program (or "FTP client").

## FTP Client

There are a few FTP programs around. Here are some free ones that work on both Windows and Mac (and sometimes Linux):

[Cyberduck](http://cyberduck.ch/) 
: has a **very easy interface**. Has a great [Synchronize Folders](http://trac.cyberduck.ch/wiki/help/en/howto/sync) function which can save you a lot of time and handy of you have your website mirrored on you laptop. For both Windows and Mac.

[FileZilla](http://filezilla-project.org/)
: the "Firefox of FTP" and has the advantage of allowing you to see hidden files (a file who's name begins with a dot '.') which are sometimes a problem (especially on Macs). Mac, Windows & Linux.

Download one of them and lets get going. We recommend [Cyberduck](http://cyberduck.ch/) and is what the examples bellow will use.

## Uploading content to your website

SO, the main idea behind FTP programs is that they show you the files and folders on the "remote" machine (the other computer, i.e. the web server) just like you would see in Finder/Windows Explorer. So if you know how to get around the files&folder on your computer you sort of already know how this works. They only new thing here is **transferring**, sending files&folders or receiving them from the remote machine. This is what FTP is all about!

### Note about SFTP

SFTP is just **Secure FTP** and if your host provides this option, and you can figure out how to set it up (which should just be fiddling with the connection settings of your FTP client), then _you should use it_ as its more secure but doesn't change any thing covered here.

## Connecting to your website

TO connect to your website you'll need the "FTP login details" for your account on your server. You should have received these in an email after you signed up and paid for your domain hosting. If not then email/phone the support at your hosting provider.

1. Start your FTP client i.e. Cyberduck
2. Click the "Open Connection" button in the top left.
3. Fill in the connection details
	- Server: should be your domain name e.g. www.example.com
	- Username & Password: these should be supplied by your hosting provider.
	- Note: If you want to use SFTP you can select it from the drop down menu (which says "FTP (File Transfer Protocol)" here).
4. Click the "Connect" button.

If all went well you should be looking at a list of files&folders. This is like your local files&folder except they are on remote machine (i.e. the web server for your domain).

## Transferring files

OK, now your in you can try uploading some content. 

### Setting up Gizmo for the first time

To setup Gizmo for the first time you should first [download it and unzip](/Download) it to somewhere on your computer.


