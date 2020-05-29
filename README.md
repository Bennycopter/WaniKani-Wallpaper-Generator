# WaniKani Wallpaper Generator

# THIS IS A WORK IN PROGRESS

# GO AWAY AND COME BACK SOON

A wallpaper generator for [WaniKani](https://www.wanikani.com/), with pretty colors to display your wonderful progress.

Read the [WaniKani Community Page](https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321) for more information.

Access it here: [wkw.natural20design.com](http://wkw.natural20design.com)

## Walkthrough

This explains every file and folder to get you comfortable with reading the code.

### `index.php`
- All front-end traffic goes through here because of the rules in `.htaccess`.
- It includes `config.php` and all of the files in `/includes/`, and then passes control to `router.php`.
- The line `define("INDEX", true)` helps prevent other pages from being accessed directly.
- Other scripts can include this file without invoking the router by defining `NO_ROUTE`.

### `config.php`
- Contains configuration information.
  - Defines constants for paths and other configuration information.
  - Sets time zone and session path.

### `router.php`
- It passes control to some other file based on `$_GET["_url"]` and `$_SESSION`.
  - `$_GET["_url"]` is set from `.htaccess`'s rewrite rules.

### `/pages`
Contains the pages and sub-pages seen by the user in the front-end, as well as virtual pages that are only  functional.
  - `login.php` - Login form
  - `logout.php` - This logs the user out, then forwards the user to the login form
  - `settings.php` - This is the page the user sees after logging in.
    - `instructions.php` - This is included by `settings.php` to reduce file size.
    - `instructions/*` - These files are included by `instructions.php` to reduce file size.
  - [`download.php`](pages/download.php) - This generates the wallpaper and outputs it as a PNG.
  - [`download.php`](LICENSE) - This generates the wallpaper and outputs it as a PNG.
  - [download.php](LICENSE) - This generates the wallpaper and outputs it as a PNG.
  - `order.php` - This file shows the contents of a specified Kanji Set Order file.     

### `/assets`

### `/notes`
- Contains notes. You can ignore these


## Handling Updates to WaniKani and other Kanji Sets

WaniKani has content updates.  Use the info in `/notes/Kanji Ordering.md`

## Requirements

- PHP 7.0+

## How to Contribute

Do you want to help make this tool even better?

**If you have an idea but don't know how to code**, then please share on the [WaniKani Community Page](https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321)

**If you have an idea and know how to code**, then, uhhhhhh, idk.  Pull request?  I don't know how Git works.  

## Change Log

**May 29, 2020** - Added to VCS, major refactors, and open source'd.

**June 15, 2019** - [Public release 2](https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321)

**December 22, 2018** - [~~Public release 1~~](https://community.wanikani.com/t/automatically-generate-new-wallpaper/34275) (deprecated)