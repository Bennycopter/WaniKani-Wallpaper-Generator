# WaniKani Wallpaper Generator

# THIS IS A WORK IN PROGRESS

# GO AWAY AND COME BACK SOON

A wallpaper generator for [WaniKani](https://www.wanikani.com/), with pretty colors to display your wonderful progress.

See the [WaniKani Community Page](https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321) for more information.

Access it here: [wkw.natural20design.com](http://wkw.natural20design.com)

## Walkthrough

This section explains every file and folder to get you familiar with the code.

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
    - [`instructions.php`](pages/instructions.php) - This is included by `settings.php` to reduce file size.
    - [`instructions/*`](pages/instructions) - These are included by `instructions.php` to reduce file size.
  - [`download.php`](pages/download.php) - This generates the wallpaper and outputs it as a PNG.
  - `order.php` - This file shows the contents of a specified Kanji Set Order file.

### `/includes`
Contains functions that are used elsewhere.
 - <details>
   <summary>`helpers.php` - Adds basic features to PHP</summary>
   
   ```php
   clamp($var, $min, $max)
   interpolate($a, $b, $weight)
   imagecolorallocate_from_hex($image, $hex)
   file_put_prepended($string, $filename)
   file_put_json($file, $array)
   file_get_json($file)
   ```
   </details>
 - `error_handling.php` - Puts errors in /data/errors
   ```
   log_error($msg, $line, $file)
   ```
 - `wanikani.php` - Making API requests
   ```
   wanikani_request($endpoint, $api_key, $raw_response=false)
   ```
 - `users.php` - Everything related to users
   ```
   log_in_user($api_key, $device)
   log_out_user()
   get_user_settings($api_key, $device)
   get_user_username($api_key)
   save_user_settings($api_key, $device, $settings)
   user_settings_file($api_key, $device)
   user_username_file($api_key)
   default_user_settings()
   prepare_user_folder($api_key, $username)
   user_folder_exists($api_key)
   log_generation($api_key)
   get_user_progress_report($api_key)
   ```
 - `presets.php` - Everything related to presets.
   ```
   font_file_exists($name)
   color_scheme_exists($name)
   screen_preset_exists($name)
   kanji_set_exists($name)
   load_kanji_order_file($kanji_set)
   ```
   The last four functions define the order that presets appear in the front-end.  The last function includes extra information to describe the kanji sets.
   ```
   load_color_scheme_presets()
   load_font_presets()
   load_screen_presets()
   load_kanji_set_presets()
   ```
 - `post.php` - Contains functions used to filter and validate $_POST input.
   ```
   sanitize_filename($v)
   filter_color_code($v)
   validate_color_code($v)
   filter_integer($v)
   return_false()
   filter_checkbox_value($v)
   ```
 - `image.php` - Contains exit functions that output an image instead of text
   ```
   die_with_image($file)
   die_with_text_on_image($text)
   ```

### `/assets`
Contains resources that are **not directly** presented to the end-user:
  - Font files
  - Error graphic files
  - SSL certificate file

### '/public'
Contains resources that are **directly** presented to the end-user:
  - 3rd-party files (see their licenses [here](public/3rd-party/3rd%20Party%20Licenses.md))
    - jQuery / jQuery UI
    - Spectrum Colorpicker
    - randomColor.js
  - CSS files
  - JS files
  - Images
    - *(**Exception** - source Photoshop images are in [public/images/source](public/images/source) just to be organized)*

### '/presets'
These are data files that define the Fonts, Color Schemes, Kanji Sets ('orders'), and Screen Presets.
- Color Schemes 
  - Each file looks like the following example.
  - Example: `color-schemes/Default.txt`
  - ```
    c_background:      #000000
    c_unseen:          #303030
    c_apprentice:      #DD0093
    c_guru:            #882D9E
    c_master:          #294DDB
    c_enlightened:     #0093DD
    c_burned:          #FFFFFF
    c_section_titles:  #10cafe
    c_wallpaper_title: #cafe10
    ```
- Fonts
  - Line 1: Font file name, as found in /assets/fonts
  - Line 2: Source download location
  - Example: `fonts/Komorebi Gothic.txt`
    ```
    komorebi-gothic.ttf
    https://www.freejapanesefont.com/komorebi-gothic-download/
    ```
- Kanji Sets ("Orders")
  - These define the order of kanji as they appear on the wallpaper, hence the name "orders".
  - These files are presently as-is to the end-user via `order.php`.  When used to generate wallpapers, the following rules apply:
    - Line 1 is ignored
    - Blank lines are ignored
    - Lines that start with `#` are ignored
    - Lines that have `:` split sections
      - Section markers are optional (i.e. some sets don't have sections)
      - Section markers can be on their own line or with kanji, for example:
        ```
        Section 1 (own line):
        一二三四五六七八九十
        口日月田目古吾冒明唱
        Section 2 (in-line with kanji): 晶品呂昌早世胃旦胆凹
        ```  
- Screen Presets
  - Contains the dimensions and margins of various screens.
  - Example: `screen-presets/iPad Pro 10.5-inch.txt`
    ```
    width:  1668
    height: 2224
    top:    20
    left:   20
    right:  20
    bottom: 20
    ```
- WaniKani Kanji Cache
  - `all-wk-kanji.txt` contains the kanji from WaniKani with their subject ID.  This file should be regenerated whenever WaniKani has a content update. 

### '/data'
These are files that are created as the app is used.
- Users
  - A folder is created for each user with their API key.  For example:
    - `12345678-abcd-aaaa-1234-0123456789ab`
      - `generations.txt` - A new line is prepended to this file each time a wallpaper is attempted to be generated.  It contains the time and remote IP of each request.
        - Example:
        - ```
          1590773902|127.0.0.1
          1590766006|127.0.0.1
          1590765827|127.0.0.1
          ```
      - `settings-__.txt` - Replace __ with a number from 1 to 10 for the device #.  Contains the user settings, JSON-encoded.
      - `username.txt` - Contains the user's username.
        - Example:
        - ```
          Masayoshiro
          ```
- Sessions
  - Session data is stored here.  To log everyone out, empty this file.
- Errors
  - Errors are stored here.

### '/secret'
This contains admin tools.  Navigate your browser to this folder to access the tools.  To password protect it, create an empty folder inside the /secret/password directory.

### `/notes`
- Contains further documentation. You can ignore these


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