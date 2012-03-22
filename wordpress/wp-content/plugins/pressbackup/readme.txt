=== Pressbackup ===
Contributors: infinimediainc
Tags: pressbackup, backup, schedule, S3
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.2
License: GPLv2

Pressbackup is the easiest plugin available for backing up your wordpress site automatically.


== Description ==

Pressbackup is the easiest plugin available for backing up your wordpress site automatically.

Using Amazon cloud technology, pressbackup allows your wordpress blog administrator to schedule backups of your entire site, restore backups, and migrate your site in the event of your server failure or moving.

Pressbackup is free to use with your own AWS S3 credentials or you can purchase a pro subscription and we handle the backups for you.

Without S3 or a Pro account, pressbackup will allow you to manually download and upload backup files from your site.

http://pressbackup.com


== Installation ==

* Download plugin
* Copy it into your folder: /wp-content/plugins
* Activate the plugin
* [ Modify permissions for the folder plugins/pressbackup/tmp to 777 (read and write for all) if you get a permission error ]
* Go to Tools > PressBackup
* [ Create an account Pressbackup PRO (the link is on page “Configuration wizard (step 1 of 2)”) ]
* Enter data for S3 or Pressbackup Pro
* Enter your backups preferences

  DONE :)

steps between [ ] are alternative


== Requirements == 

* Sufficient disk space to store the temporary zip of your site. 
* GZip extension or zip app via shell
* Curl extension

= Warnings =

To restore the backups you need to change permissions of ” themes” “plugins” and “uploads” folders to 777 (read and write for all)
then do the restore and then change back to original

Please be careful about doing a restore from a previous version of Wordpress if you have upgraded Wordpress core files between backups.
We cannot ensure a smooth transition between each upgrade.

We also recommend running a manual backup now after each upgrade you perform

== INCOMPATIBILITIES == 

* IIS web servers

== Screenshots ==

1. A simple and easy way to create and manage backups

== Changelog ==

= 1.2 =
* Fixed: backup now (download) for users with no credentials of S3 or Pressbackup Pro
* Fixed zip creation without php-zip module
* Added Compatibility tab
* Fixed minor bugs

= 1.1 =
* Fixed: backup now (download) for users with no credentials of S3 or Pressbackup Pro
* Added zip creation without php-zip module (when it is available)

= 1.0 =
* Changed the way to make backups ( no PHP type dependent )

= 0.7.1 =
* Added popup off function
* Added a fix for some PHP-CGI users

= 0.7 =
* Improved data organization in UI
* Fixed "wrong file type" on upload
* Changed the way to make backups
* Updated version of Framepress core
* Added more restrictions for incompatible hosts
* Added more info about errors
* Added a fix for some PHP-CGI users

= 0.6.7 =
* Fixed minor bugs
* Added Option for european S3 servers
* Added a progress bar For S3 uploads

= 0.6.6.4  =
* Fixed minor bugs

= 0.6.6.3  =
* Fixed minor bugs on main file
* added host info page

= 0.6.6.2  =
* Fixed minor bugs on dashboard page

= 0.6.6.1  =
* Fixed minor bugs

= 0.6.6 =
* Fixed bug for Pressbackup Pro users that prevented send of backups

= 0.6.5.1 =
* Change the way to inform errors to prevent WP blocking

= 0.6.5 =
* Improved backup sorting
* Improved data organization in UI
* Updated version of Framepress core

= 0.6.4 =
* Eliminated need to CHMOD your tmp folder 
* Streamlined install process
* Added requirement for GZip

= 0.6.3 = 
* Changed the way to inform permissions errors
* Added more integrity checks for backups

= 0.6.2 = 
* Fixed bug for S3 users that prevented creation of buckets

= 0.6.1 = 
* Fixed reload htaccess

= 0.6 = 
* Change auto-scheduling system

= 0.5.2 = 
* Fix scheduling bug that was failing to trigger backups
* Fix a bug that could result in broken backup files

= 0.5.1 = 
* Fix a bug that caused lock ups on large sites

