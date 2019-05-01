# Ratify #
Contributors: secretsource,tedmaster
Tags: seo,accessibility,html5,checklist
Requires at least: 4.8
Tested up to: 4.9
Requires PHP: 7.1
License: GPL 2
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

A plugin that helps keep us from publishing sites with basic configuration errors. It is an automated checklist of common issues in WordPress sites.

# Ratify - Your technical seal of approval #

This small and unintrusive plugin provides a fast way to spot technical WordPress configuration issues and fixes some of them without being asked. Examples include:

* verifying that the home page has a valid Title tag
* verifying that the site is not blocking robots (Google)
* verifying that the site and all assets are being delivered over HTTPS
* verifying that the home page is delivered gzipped (compressed)
* verifying that images on the home page have ALT attributes
    * that the home page has H1, H2, H3, etc. tags
    * that the HTML on the home page is valid
    * that the HTML on the home page has Open Graph tags
* much, much more

In most cases the plugin can improve your Google Lighthouse score simply by installing it.

# The story of Ratify #

## Why? ##
When you create more than 1 site per week (as we have at Secret Source Technology) using a different premium theme on each site, there are dozens or even hundreds of issues that can arise.

The Ratify plugin is our latest attempt at formalizing our quality control. It is our guarantee that a site meets a certain minimum of quality. It greatly reduces the potential for human error and allows you to see at a glance issues the site may be having.

This plugin:

* Started as a spreadsheet with nearly one hundred line items
* Turned into a bash script
* Then a bash script with a graphic web front end
* Then into Selenium automated test script (for testing forms)
* Then into a plugin using the Herbert framework
* Then into a plugin using the WordPress Plugin Boilerplate (current version)

The plugin structure is very basic and tests can be added simply (if you know how to write object oriented PHP - have a look in the "Models" folder…). In future versions we hope to include additional tests and speed improvements for your site. We are very interested in hearing from users of the plugin so please do not hesitate to contact us with questions / concerns.

# Installation #
There are no special requirements to install this plugin. Just use the standard WordPress plugin installation routine.

# Frequently Asked Questions #
(waiting for feedback from users… there are no FAQs at this time)

# Screenshots #
1. This is a sample report from a site that is not very good

# Copyright #
The "[approved](https://svgsilh.com/image/1966719.html)" stamp used with permission.

# Changelog #

1.0 - Initial release

1.0.1 - Fixed formatting in readme and made it pretty and tweaked the test for Google Analytics (in the prior version)

1.0.2 - Fixed some readme formatting

1.0.3 - Moved the master repository to [github](https://github.com/SecretSourceWeb/ratify)