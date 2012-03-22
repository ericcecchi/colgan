=== Snapshot Backup ===

Contributors: versluis
Donate link: http://wpguru.co.uk/say-thanks/
Tags: snapshot backup, backup, complete backup, full backup, archive wordpress, air check, ftp backup
Requires at least: 2.7
Tested up to: 3.1.1
Stable tag: 1.5

Creates a Snapshot Backup of your entire website and uploads it to an FTP repository.

== Description ==

Creates a Snapshot Backup of your entire website: that's your Database, current WP Core, all your Themes, Plugins and Uploads. The resulting single archive file is then uploaded to an FTP repository of your choice.

You can use one FTP repository for snapshots from various sites and group them using File Prefixes and Subdirectories to tell your snapshots apart.

If you don't have an FTP account you can download the file from your local server at the end of the backup.

== Installation ==

1. Upload the entire folder `snapshot-backup` to the `/wp-content/plugins/` directory. Please do not rename this folder.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Enter your FTP details under Dashboard - Snapshop Backup
1. Hit the CREATE BACKUP button, grab a coffee and enjoy piece of mind

FTP Details are optional - if you don't have an FTP account you can leave the details blank and download your snapshot file manually.

== Snapshot Philosophy ==

Archiving dynamic websites isn't all that easy and we all tend to forget that because the web is such a fluid thing. The idea of Snapshot is that you may want to create an 'as is' version of your website for archive purposes. With each click you'll create a *time capsule* of sorts - this could be for legal, sentimental or security reasons.

Other solutions mirror your or snyc your installation. This is a great idea too, however if you only notice a week down the line that your site has been compromised then your synced copy most certainly is too. Snapshot makes it easy to go back to a clean version from x days/weeks/months ago.

== Upgrade Notice ==

If you're upgrading from Version 1.0, please note that the databsae temp files were accidentally daved in your WP root directory. Have a look for rougue .sql files there - feel free to delete them.

== Frequently Asked Questions ==

= What's required server side for this plugin to run? =

Since I'm using shell commands to create the archive file, this Plugin only works on Linux servers - NOT on Windows servers.

= Does this Plugin run on Windows Servers? =

I'm afraid not - you have to be on a Linux server for this to work. I've developed and tested it on CentOS / RHEL.

= When I hit "Create Snapshot Backup" my screen goes blank, but the Wordpress sidebar and header are still here. Is that normal? = 

This happens on Firefox browsers. This is indeed normal - I haven't implemented a more elegant solution just yet but I'm working on it. While the script is active, your browser should appear to be "loading" though and you will receive message reading "All done - thank you" in a yellow box. Internet Explorer appears to be "busy" loading a page - again I'll look at this for future releases.

= I don't have another FTP account. Can I still use this plugin? =

Absolutely - there's a handy download link at the end of the backup procedure so you can save your file locally.
Simply ignore all error messages relating to FTP uploads.

= Can I do these backups automatically, say via a Cron Job or WP Cron? =

Not at the moment, but it's very high on my priority list to implement this feature.

= How to I restore a snapshot? =

I'm aiming to build this option into the plugin and add a standalone script which will do the hard work for you. For now you'll need to do this manually.

In a nutshell: 
Download the TAR archive from your repository, unTAR it using your favourite ZIPping tool and upload the contents back into your web hosting directory (overwriting any existing files). You'll also find a .SQL file under wp-content/uploads. That's your database file which needs to be uploaded to your MySQL server (say via phpMyAdmin or BigDump), replacing any existing tables in said database.

If on this occasion you're restoring a snapshot to another domain or subfolder in your existing domain, you will also have to change certain values in your database. We'll leave this for another time - search for Moving Wordpress for detailed instructions on how to do this.

I am about to release an article a more in-depth article on the Snapshot Backup homepage - it'll be available shortly. 

== Screenshots ==

1. The Snapshot Backup Admin Menu
2. FTP Details Screen
3. Success Screen: if you see this then your backup was successful.

== Changelog ==

= 1.5 =
Fixed incompatibilities with certain servers in the database readout:
Some users experienced PHP Memory issues, others could not restore the database.
Certified Wordpress 3.1.1 compatibility
Replaced hard-coded paths with dynamic ones
Added permanent download option for most recent Snapshot
Added option to include additional directory - useful for people who have moved wp-content

= 1.4 =
Fixed spurious error message upon database readout

= 1.3 =
Added File Prefix option; if you have one FTP repository you can use it for multiple sites easily
Added File Size Display so you know how big your snapshot is
Eliminated whitespace on user input fields

= 1.2 =
Certified compatibility with Wordpress 3.1.
Password was visible in FTP settings form - it's fixed now.

= 1.1 =
Fixed a nasty bug which saved the database temp file in the wrong place. 

= 1.0 =

Initial Release
