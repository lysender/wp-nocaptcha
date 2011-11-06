# WP NoCaptcha Plugin

Having problems with spam but don't want to distract users with hard to read captcha? Afraid of false positives brought by Akismet? Afraid of losing possibly valid comment? This WordPress plugin is for you.

## The basics

WP NoCaptcha Plugin uses a JavaScript based solution to identify real people with spam bots. It is due to the fact that most bots has no ability to run JavaScript codes. Another consideration is that spam usually don't interact with the comment form and thus doesn't even press the keyboard.

The concept is very simple: a token is generated for each WordPress comment form. This token is checked once the comment form is posted. If the token is not present or does not match the generated token, therefore the user is a possible spam bot. 

When the comment form is loaded, an AJAX request is called and returns a token. This token is then attached to the form only when the user types in the comment text field. Therefore, copy and paste spammer will also not work.

## How to get the plugin

To download the plugin, goto https://github.com/lysender/wp-nocaptcha/tags and download the latest version. Extract the compressed plugin and copy it to your WordPress plugin directory.

## How to install

First, you have to download the plugin as described above. Next, extract the contents into your WordPress plugin directory which is usually at `wp-content/plugins` directory. Activate the plugin in your WordPress admin panel and your good to go. If you have page caching plugin, clear the cached contents first for it to take effect.

## Known issues

Due to the JavaScript requirement, the plugin does not work if JavaScript is disabled. If you think that many of your visitors don't meet this requirement, then this plugin is not for you.

Copy pasting text will not work if the user only press `CTRL + V` or equivalent since these keys are not considered by the plugin as valid keys. The user must at least press a single character for the token to register itself to the form. 

In some instances, like network problems, the token may be missing. The user is required to reload the page so that the token is properly set.

This plugin only works for generic spam bots and will not protect you for bots specifically design for your site. However, the plugin may evolve in the future to block those kind of attacks.

## Bugs? Suggestion?

If you think you found a bug or you have a cool suggestion for the plugin, please file a ticket at: https://github.com/lysender/wp-nocaptcha/issues