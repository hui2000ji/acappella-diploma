=== Contact Form 7 - Conditional Fields ===
Contributors: Jules Colle
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=j_colle%40hotmail%2ecom&lc=US&item_name=Jules%20Colle%20%2d%20WP%20plugins%20%2d%20Responsive%20Gallery%20Grid&item_number=rgg&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Author: Jules Colle
Website: http://bdwm.be
Tags: wordpress, contact form 7, forms, conditional fields
Requires at least: 4.1
Tested up to: 5.4
Stable tag: 1.8.6
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds conditional logic to Contact Form 7.

== Description ==

This plugin adds conditional logic to [Contact Form 7](https://wordpress.org/plugins/contact-form-7/).

If you edit your CF7 form, you will see an additional tag called "Conditional fields Group". Everything you put between the start and end tag will be hidden by default.
After you have added the field group(s), click Save and go to the "Conditional fields" tab to create one or more conditions that will make the group(s) appear.

= How to use it =

[Follow this tutorial](https://conditional-fields-cf7.bdwm.be/conditional-fields-for-contact-form-7-tutorial/)

== Main/ New features ==

= Support for required fields =

Required fields can be used inside hidden groups without causing validation problems.

= Hide/show info in emails based on what groups are visible =

Conditional groups can now be added to the emails as well.
Just wrap the content with `[group-name] ... [/group-name]` tags.

= Groups can be nested =
Groups can be nested, both in the form and in the email

Example form:
`
[group group-1]
  [group group-inside-1]
    ...
  [/group]
[/group]`

Example email:
`
[group-1]
  [group-inside-1]
    ...
  [/group-inside-1]
[/group-1]`

= Advanced =

Advanced users can code up the conditions as plain text instead of using the select boxes, using the import/export feature.

== Installation ==

Please follow the [standard installation procedure for WordPress plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

Follow [this tutorial](https://conditional-fields-cf7.bdwm.be/conditional-fields-for-contact-form-7-tutorial/) if you are not sure how to use the plugin.

== Frequently Asked Questions ==

= Email message is not showing the correct values / Wrong values are submitted =

<strong>All field names should be unique</strong>

Even though your fields might never show up at the same time, it is still important to realize that WPCF7CF will not remove the fields, it merely hides them. So all fields will be submitted when the form is sent. Because of this no two fields can have the same name.

Incorrect form (2 input elements having the same name "a"):
`
[group group-1][select a "1" "2" "3"][/group]
[group group-2][select a "1" "2" "3"][/group]
`

Correct form (all groups and fields have unique names):
`
[group group-1][select a "1" "2" "3"][/group]
[group group-2][select b "1" "2" "3"][/group]
`

= All my groups show up all the time and never get hidden. =

<strong>Reason #1: Javascript error</strong>
Check your browser console (F12) for any javascript errors. WPCF7CF loads it's scripts at the bottom of the HTML page, so if some javascript error gets triggered before that, the code will not be executed in most browsers.
Before reaching out to the support forum try to determine which plugin or theme is causing the problem, by gradually disabling plugins and changing theme.

<strong>Reason #2: wp_footer() isn't loaded</strong>
Check if your theme is calling the `wp_footer()` function. Typically this function will be called in your theme's footer.php file.
The conditional fields javascript code is loaded during wp_footer, so a call to this function is crucial. If there is no such call in your theme, go to your theme's footer.php file and add this code right before the closing `</body>` tag:
`&lt;?php wp_footer(); ?&gt;`

== Screenshots ==

1. Conditional fields in action
2. Defining rules to show/hide groups of input elements in the backend interface

== Changelog ==

= 1.8.6 (2020-04-18) =
* Fixed: accidentally packed the entire jQuery library in scripts.js
* Cleaned up some console.logs that was still hanging around in the code.

= 1.8.5 (2020-04-17) =
* Tested with WP 5.4
* fix multiselect
* fix required file field inside mutistep
* allow group names to contain `.` and `:` (https://github.com/pwkip/contact-form-7-conditional-fields/issues/46)
* fix possible conflicts with require_once 'init.php' (https://wordpress.org/support/topic/conflict-with-theme-because-of-require_once/)
* Created some unit tests and integration tests, so hopefully no more regression bugs from now on! (next step: automated CI before publish)
* Got rid of warnings and notices when creating a new form.
* Fix small clear_on_hide bug with select field (https://github.com/pwkip/contact-form-7-conditional-fields/issues/51)
* PRO: fix "not empty" when using first_as_label in select field. (closes guthub issue #48)
* PRO: fix error with nested repeater buttons text
* PRO: new version of plugin_update_checker, compatible with PHP 7.3+

= 1.8.4 (2020-03-05) =
* PRO: Multistep: Improve autoscroll behavior (Tried to make it smoother and less annoying)
* PRO: Multistep: Add wpcf7-not-valid class to input fields as needed after step-validation. Removed some other useless classes.

= 1.8.3 (2020-03-01) =
* Fix JS error on submit (reported by [@wasi7186](https://wordpress.org/support/topic/js-uncaught-typeerror-when-form-is-submitted/))
* PRO multistep: On the last step of a multistep form, instead of cloning and hiding the submit button, we now simply move it to the next-step-container. This solves a compatibility issue with a third party recaptcha plugin (and probably some other plugins too)

= 1.8.2 (2020-02-27) =
* Small patch. Add a check in the ajaxComplete event, to make sure xhr.responseJSON is not null or undefined. (this caused a JS error on some websites)

= 1.8.1 (2020-02-23) =
* PRO: Bring form in initial state after submit, including going back to first step of multistep and resetting the initial number of sub-repeaters in each repeater.
* PRO: New field: [[summary]](https://conditional-fields-cf7.bdwm.be/summary/)
* PRO: Make form submission trigger next step (if not on last step)
* PRO: Multistep: automatically scroll to top of form after moving to a different step.
* PRO: Multistep: Replace h3 tag with div as step-title.
* PRO: Made some small changes to the default multistep styles, for basic mobile friendly behaviour.
* PRO: Make multistep compatibility with cf7-image-captcha plugin

= 1.8 (2020-02-03) =
* WPML compatibility: Force conditions to be an array. [link](https://wordpress.org/support/topic/wpml-conditional-fields-not-working-on-translations/#post-12390827)

= 1.7.9 (2020-01-22) =
* PRO (01-02-20) Added quick fix for problem with [_format_date "d m Y"] inside repeater. [link](https://github.com/pwkip/contact-form-7-conditional-fields/issues/38)
* (01-22-20) Fixed Ajax by adding global wpcf7cf variable to window.
* Changed min PHP version to 5.6 (might still be compatible with 5.4, but I suspect some edge case errors with older versions of PHP)

= 1.7.8 (2019-11-26) =
* Updated/added Javascript events for groups, repeaters and multistep + added [documentation](https://conditional-fields-cf7.bdwm.be/docs/examples/javascript-events/)
* Fixed bug where using invalid regex as a value would give a JS error [link](https://wordpress.org/support/topic/star-ratings-6/)
* PRO Multistep: Added attribute to multistep `data-current_step` which holds the current step index. (can be used as css selector like this: `div[data-current_step=1]`)
* PRO Repeater: The string `{{your-repeater_index}}` inside a repeater called `your-repeater` will be replaced with the index of the current sub repeater. [Check out the updated example](https://conditional-fields-cf7.bdwm.be/repeater/)  

= 1.7.7 (2019-11-05) =
* PRO: Fix problem with parsing the "function" operator

= 1.7.6 (2019-11-01) =
* Fixed small compatibility problem with CF7 Smart Grid [link](https://wordpress.org/support/topic/problem-on-save-form-when-the-active-tabs-are-not-conditional-form/#post-12085173)
* Fixed some more porblems with parsing conditions (regex changes)
* Got rid of screen_icon notice on CF settings page

= 1.7.5 (2019-10-31) =
* Fixed bug in admin where settings got cleared if using some operators (mostly PRO operators)

= 1.7.4 (2019-10-29) =
* PRO: made repeater (80%) compatible with material-design-for-contact-form-7
* PRO: made exclusive checkbox work with repeater fields
* PRO: trigger events when a repeater adds fields: 'wpcf7cf_repeater_added' - and when a repeater removes fields: 'wpcf7cf_repeater_removed'. Can be called with `$('form').on('wpcf7cf_repeater_removed', function() { /*...*/ })`
* PRO: fixed bug with mutistep (formn did not work correctly if there were multiple forms on one page).

= 1.7.3 (2019-10-24) =
* removed @babel/polyfill. All seems to be working fine without it in IE11. JS file is now back to 25kb instead of 100kb.

= 1.7.2 (2019-10-24) =
* Bug fix: new javascript files where throwing errors. Should be okay now. (Also included JS source map for easier debugging)

= 1.7.1 (2019-10-23) =
* PRO: Added basic support for multistep. No options available yet. You can insert [step] tags inside your code. [More info](https://conditional-fields-cf7.bdwm.be/multistep/)
* Set up an NPM dev environment with babel and webpack. This means all the client side JS code will look super ugly, and it's also more bytes. But the plus side is that the plugin should also work fine in older browsers now.
* Tested with WP version 5.3

= 1.7 (2019-10-18) =
* code rewrite. Made code more testable by focusing more on a functional approach. Not completely finished yet, but getting there.
* FIXED clear_on_hide not working for multi select [github issue 35](https://github.com/pwkip/contact-form-7-conditional-fields/issues/35)
* PRO: FIXED [github issue 34](https://github.com/pwkip/contact-form-7-conditional-fields/issues/34) - A real nest fest is now possible. You can put groups inside repeaters inside repeaters inside groups ...
* FIXED make clear_on_hide restore initial values instead of clearing [github issue 31](https://github.com/pwkip/contact-form-7-conditional-fields/issues/31)
* WP-admin: Renamed "Import/Export" to "Text view". Conditions specified in the input fields are now semi-automatically synced with the text view.
* Internal change: When saving conditions, instead of posting all the input fields, the input fields are added to the "text view" textarea, and only the textarea will be sent. This is to prevent issues with PHP max_input_vars

= 1.6.5 (2019-10-15) =
* Patched a minor security issue. From now on, only users with the 'wpcf7_edit_contact_form' capability will be able to reset the Conditional Fields settings to their defaults. Big thanks to Chloe from Wordfence for pointing this out!
* Tested the plugin with WP version 5.2.4

= 1.6.4 (2019-07-04) =
* PRO: Repeater: Fixed invalid HTML for the remove button
* Free: Initialize form.$groups as a new jQuery object instead of an empty array, in order to prevent exotic bugs in case $groups aren't loaded by the time form.displayFields() is called. (https://wordpress.org/support/topic/typeerror-cannot-read-property-addclass-of-undefined-at-wpcf7cfform/)

= 1.6.3 (2019-07-04) =
* Removed the word "Pro" from the title in the free plugin

= 1.6.2 (2019-06-25) =
* Small changes to tag generator buttons
* Multistep bug fix. All group conditions are evaluated a second time after the page has fully loaded.
* PRO: added new operator 'function', allowing you to write custom javascript functions to determine whether or not a group should be shown. [link](https://conditional-fields-cf7.bdwm.be/advanced-conditional-logic-with-custom-javascript-functions/)
* PRO: fix bug with < (less than) operator

= 1.6.1 (2019-06-03) =
* JS refactoring and small compatibility fix after code rewrite.
* FREE: Added "Get PRO" button under Contact > Conditional Fields

= 1.6 (2019-06-01) =
* JS code rewrite
* PRO: allow groups inside repeater
* PRO: make plugin ready for PRO release.

= 1.5.5 (2019-05-20) =
* Fixed and explained how to disable loading of the styles and scripts and only enable it on certain pages. [More info](https://conditional-fields-cf7.bdwm.be/docs/faq/can-i-load-js-and-css-only-when-necessary/)
* Made sure default settings get set after activating plugin, without the need to visit the Contact > Conditional Fields page first.
* PRO: extended the repeater with min and max paramaters and the possibility to change the add and remove buttons texts
* PRO: enabling the pro plugin will show a notification to disable the free plugin, instead of throwing a PHP error.

= 1.5.4 (2019-05-06) =
* Make sure scripts get loaded late enough (wp_enqueue_scripts priority set to 20), because there was a problem with multistep where the multistep script was changing a value after the cf script ran. https://wordpress.org/support/topic/1-5-x-not-expanding-selected-hidden-groups-with-multi-step-on-previous-page/

= 1.5.3 (2019-05-03) =
* Refix the fix from version 1.4.3 that got unfixed in version 1.5 somehow 🙄

= 1.5.2 (2019-05-03) =
* by reverting changes in 1.5.1, the possibility to load forms via AJAX was destroyed. So, from now on the wpcf7cf scripts will be loaded in the 'wp_enqueue_scripts' hook. Analogous with the WPCF7_LOAD_JS constant, a new constant is defined called WPCF7CF_LOAD_JS wich is set to true by default.

= 1.5.1 (2019-05-02) =
* revert changes: enqueue scripts in 'wpcf7_contact_form' hook instead of 'wpcf7_enqueue_scripts', because loading it in the latter would cause problems with plugins that disable WPCF7_LOAD_JS (like for example contact-form-7-paypal-add-on).

= 1.5 (2019-04-21) =
* Make it possible to load forms with AJAX. [fixed github issue 25](https://github.com/pwkip/contact-form-7-conditional-fields/issues/25). [updated docs](https://conditional-fields-cf7.bdwm.be/docs/faq/how-to-initialize-the-conditional-logic-after-an-ajax-call/)
* Massive code reorganization in scripts.js
* Fixed bug that could appear after removing an AND condition.
* solve WPCF7_ADMIN_READ_WRITE_CAPABILITY - https://github.com/pwkip/contact-form-7-conditional-fields/pull/16
* disable part of the faulty remove_hidden_post_data function. - https://github.com/pwkip/contact-form-7-conditional-fields/pull/17
* Fix "Dismiss notice" on Conditional Fields Settings page
* use the "wpcf7_before_send_mail" hook instead of "wpcf7_mail_components" to hide mail groups. The wpcf7_before_send_mail hook is called earlier, so it allows to also hide groups in the attachment field and in messages.
* Allow conditional group tags in success and error messages. https://github.com/pwkip/contact-form-7-conditional-fields/issues/23
* duplicating a form will also duplicate conditions https://github.com/pwkip/contact-form-7-conditional-fields/issues/28

= 1.4.3 (2019-04-12) =
* Really fix clear_on_hide problem (https://wordpress.org/support/topic/clear_on_hide-still-not-working-right-after-1-4-2-update/)

= 1.4.2 (2019-04-10) =
* Disabled mailbox syntax errors if there are group tags present. (this is overkill, and should be changed if the necassary hooks become available) https://wordpress.org/support/topic/filter-detect_invalid_mailbox_syntax/
* Checked issue: https://github.com/pwkip/contact-form-7-conditional-fields/issues/26 (nothing changed, but turns out to be working fine)
* Fixed issue where mail_2 added extra lines in the email message. https://github.com/pwkip/contact-form-7-conditional-fields/issues/30
* Made the clear_on_hide property a bit more useful (https://github.com/pwkip/contact-form-7-conditional-fields/issues/27)
* Got rid of warning in PHP 7 (https://wordpress.org/support/topic/compatibility-warning-message-regarding-wpcf7_admin_read_write_capability/)
* Fixed some javascript errors that appeared on non-CF7CF subpages of CF7
* Tested WP version 5.1.1

= 1.4.1 (2018-08-21) =
* Fixed some CSS issues (https://wordpress.org/support/topic/crash-view-admin-the-list-of-posts-entry/)
* Dropped support for PHP version 5.2, now PHP 5.3+ is required to run the plugin. Let's push things forward!
* Added conditional group support to mail attachments field (https://github.com/pwkip/contact-form-7-conditional-fields/issues/22)
* Added repeater field to PRO version.

= 1.4 (2018-08-15) =
* Added basic drag and drop functionality to the back-end so conditional rules can be rearranged.
* Added possibility to create inline groups by adding the option inline. Example: `[group my-group inline] ... [/group]`
* Added property clear_on_hide to clear all fields within a group the moment the group gets hidden. Example: `[group my-group clear_on_hide] ... [/group]`
* Added AND conditions and added a bunch of other options in the PRO version (should be released very soon now)
* Bug fix thanks to Aurovrata Venet (@aurovrata) https://wordpress.org/support/topic/bug-plugin-overwrite-cf7-hidden-fields/
* Bug fix thanks to 972 creative (@toddedelman) https://wordpress.org/support/topic/conditional-fields-not-opening-using-radio-buttons/#post-10442923

= 1.3.4 =
* small fix (https://wordpress.org/support/topic/wpcf7_contactform-object-is-no-longer-accessible/)

= 1.3.3 =
* Changes tested with WP 4.7.5 and CF7 4.8
* Changed the inner mechanics a bit to make the plugin more edge-case proof and prepare for future ajax support
* Fix problems introduced by CF7 4.8 update
* Because the CF7 author, Takayuki Miyoshi, decided to get rid of the 'form-pre-serialize' javascript event, the hidden fields containing data about which groups are shown/hidden will now be updated when the form is loaded and each time a form value changes. This might make the plugin slightly slower, but it is the only solution I found so far.
* Small bug fix (https://wordpress.org/support/topic/php-depreciated-warning/#post-9151404)

= 1.3.2 =
* Removed a piece of code that was trying to load a non existing stylesheet
* Updated FAQ
* Code rearangement and additions for the upcomming Conditional Fields Pro plugin

= 1.3.1 =
* Fixed bug in 1.3 that broke everything

= 1.3 =
* Fixed small bug with integration with Contact Form 7 Multi-Step Forms
* Also trigger hiding/showing of groups while typing or pasting text in input fields
* Added support for input type="reset"
* Added animations
* Added settings page to wp-admin: Contact > Conditional Fields

= 1.2.3 =
* Make plugin compatible with CF7 Multi Step by NinjaTeam https://wordpress.org/plugins/cf7-multi-step/
* Improve compatibility with Signature Addon some more.

= 1.2.2 =
* Fix critical bug that was present in version 1.2 and 1.2.1

= 1.2.1 =
* Improve compatibility with <a href="https://wordpress.org/plugins/contact-form-7-signature-addon/">Contact Form 7 Signature Addon</a>: now allowing multiple hidden signature fields.

= 1.2 =
* Made compatible with <a href="https://wordpress.org/plugins/contact-form-7-multi-step-module/">Contact Form 7 Multi-Step Forms</a>
* Small bug fix by Manual from advantia.net: now only considering fields which are strictly inside hidden group tags with form submit. Important in some edge cases where form elements get hidden by other mechanisms, i.e. tabbed forms.
* Started work on WPCF7CF Pro, made some structural code modifications so the free plugin can function as the base for both plugins.
* Removed some debug code
* Updated readme file

= 1.1 =
* Added import feature
* Added support for nested groups in email
* Tested on WP version 4.7.2 with Contact Form 7 version 4.6.1

= 1.0 =
* I feel that at this point the plugin is stable enough in most cases, so it's about time to take it out of beta :)
* Update JS en CSS version numbers
* Fix PHP warning with forms that are not using conditional fields (https://wordpress.org/support/topic/conditional-formatting-error/)
* Tested on WP 4.7.1

= 0.2.9 =
* Re-added wpcf7_add_shortcode() function if wpcf7_add_form_tag() is not found, because some people claimed to get a "function not found" error for the wpcf7_add_form_tag function with the latest version of CF7 installed. (https://wordpress.org/support/topic/activation-issue-5/ and https://wordpress.org/support/topic/http-500-unable-to-handle-request-error-after-update/)
* Fixed some PHP notices (https://wordpress.org/support/topic/undefined-index-error-in-ajax-response/)
* Attempted to fix error with the CF7 success page redirects plugin (https://wordpress.org/support/topic/warning-invalid-argument-error-for-forms-without-conditional-fields/)

= 0.2.8 =
* forgot to update version number in 0.2.7, so changing version to 0.2.8 now.

= 0.2.7 =
* Added support for conditional fields in the email (2) field
* Got rid of some PHP warnings
* Saving a form only once, directly after adding or removing conditions, caused conditional logic not to work. This is fixed now. Thanks to @cpaprotna for pointing me in the right direction. (https://wordpress.org/support/topic/no-more-than-3-conditional-statements/)
* Fix validation error with hidden checkbox groups (https://wordpress.org/support/topic/hidden-group-required-field-is-showing-error/)

= 0.2.6 =
* Fixed problems with exclusive checkboxes in IE (https://wordpress.org/support/topic/internet-explorer-conditional-exclusive-checkboxes/)

= 0.2.5 =
* Changed deprecated function wpcf7_add_shortcode to wpcf7_add_form_tag as it was causing errors in debug mode. (https://wordpress.org/support/topic/wpcf7_add_shortcode-deprecated-notice-2/)
* Removed the hide option and fixed the not-equals option for single checkboxes

= 0.2.4 =
* Fixed bug that destroyed the conditional fields in email functionality

= 0.2.3 =
* Added support for conditional fields in the other email fields (subject, sender, recipient, additional_headers). Thanks @stevish!
* WP 4.7 broke the required conditional fields inside hidden groups, implemented in version 0.2. Thanks again to @stevish for pointing this out.
* Got rid of checking which groups are hidden both on the front-end (JS) and in the back-end (PHP). Now this is only done in the front-end.
* Tested the plugin with WP 4.7

= 0.2.2 =
* Prevent strict standards notice to appear while adding new group via the "Conditional Fields Group" popup.
* Only load cf7cf admin styles and scripts on cf7 pages.
* groups are now reset to their initial states after the form is successfully submitted.

= 0.2.1 =
* Bug fix: arrow kept spinning after submitting a form without conditional fields. (https://wordpress.org/support/topic/version-0-2-gives-a-continues-spinning-arrow-after-submitting/)
* Removed anonymous functions from code, so the plugin also works for PHP versions older than 5.3.
* Suppress errors generated if user uses invalid HTML markup in their form code. These errors could prevent form success message from appearing.

= 0.2 =
* Added support for required conditional fields inside hidden groups. A big thank you to @stevish for implementing this.
* Added support for conditional fields in the email messages. This one also goes entirely to @stevish. Thanks man!
* Added @stevish as a contributer to the project :)
* Fix form not working in widgets or other places outside of the loop. Thanks to @ciprianolaru for the solution (https://wordpress.org/support/topic/problem-with-unit_tag-when-not-in-the-loop-form-not-used-in-post-or-page/#post-8299801)

= 0.1.7 =
* Fix popup warning to leave page even tough no changes have been made. Thanks to @hhmaster2045 for reporting the bug. https://wordpress.org/support/topic/popup-warning-to-leave-page-even-though-no-changes-have-been-made
* Added export option for easier troubleshooting.
* Don't include front end javascript in backend.

= 0.1.6 =
* made compatible with wpcf7-form-control-signature-wrap plugin https://wordpress.org/support/topic/signature-add-on-not-working

= 0.1.5 =
* fixed PHP notice thanks to @natalia_c https://wordpress.org/support/topic/php-notice-80
* tested with WP 4.5.3

= 0.1.4 =
* Prevent conflicts between different forms on one page.
* Prevent conflicts between multiple instances of the same form on one page. (https://wordpress.org/support/topic/bug-153)
* Changed regex to convert \[group\] tags to &lt;div&gt; tags, as it was posing some conflicts with other plugins (https://wordpress.org/support/topic/plugin-influence-cf7-send-button-style)

= 0.1.3 =
* Removed fielset, id and class attributes for group tags, because they weren't used anyway and broke the shortcode
* If extra attributes are added to the group shortcode, this will no longer break functionality (even though no attributes are supported)

= 0.1.2 =
* Make code work with select element that allows multiple options.
* Only load javascript on pages that contain a CF7 form

= 0.1.1 =
Fixed bug with exclusive checkboxes (https://wordpress.org/support/topic/groups-not-showing)

= 0.1 =
First release


