=== Video Lead Form ===
Contributors: fillup17
Tags: contact form, lead form, video player
Requires at least: 3.4.2
Tested up to: 3.4.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Flash based video player that embeds a contact or lead form into the video for easy submission by viewers.

== Description ==

Video Lead Form is a sales and marketing dream tool. Many people use video as a means of engaging their visitors
but then have to find awkward ways to ask their visitors to submit a contact or lead form. Video Lead Form solves
this problem by embedding the form directly into the video. Users of Video Lead Form can choose where in the video 
the form should appear, either at the beginning, the end, or five seconds after the video starts.

When a viewer submits the form, their information is emailed to you, simple as that. Video Lead Form can also be 
integrated with Salesforce for an even easier way to generate and manage sales leads.

We hope you find it as useful we we have ourselves and we love to hear your feedback at contact@video-lead-form.com

== Installation ==

The easiest way to install Video Lead Form is to use the built in plugin search and install functionality.

If that does not work for you, no problem, it is also simple to upload the file manually:
1. Upload VideoLeadForm.php to the /wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress

Once the plugin is installed via either method:
1. Click on the Video Lead Form link in the menu in WordPress
2. Register with your name and email address to receive an API token
3. Check your email for an email from us and copy your API token from it
4. Return to the Video Lead Form link in the menu in WordPress and enter your email address and API token to login
5. Start uploading videos :-)

After your video has been uploaded and processed, you'll have an option next to it to copy the WordPress shortcode.
You can paste that shortcode into any post or page on your site to have the video embedded automatically and test it out.

== Frequently Asked Questions ==

= Is Video Lead Form free? =

Yes and no. It is free for a certain number of leads/contacts, but for significant commercial usage please 
check out http://video-lead-form.com/ for more information.

= What size is the video that will be displayed? =

By default the videos are resized to 800x450. They can be resized using the shortcode but the contact form looks best 
at 800x450.

== Screenshots ==

1. Register or login page (screenshot-1.png).
2. Manage videos page (screenshot-2.png). 

== Changelog ==

= 0.6 =
* Fixed XSS bug in admin register/login page

= 0.5 =
* Initial Release

== Upgrade Notice ==

= 0.6 =
* Fixes XSS bug in admin register/login page. It is recommended you upgrade.

= 0.5 =
* Initial Release
