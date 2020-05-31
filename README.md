# WaniKani Wallpaper Generator

A wallpaper generator for [WaniKani](https://www.wanikani.com/), with pretty colors to display your wonderful progress.

See the [WaniKani Community Page](https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321) for more information.

Access it here: [wkw.natural20design.com](http://wkw.natural20design.com)

## How to Run This Locally

*This is a beginner's guide to running this on Windows.  You can ask for help on the [community page](https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321) for instructions with Mac and Linux. This will not run on ChromeOS.*

**Part 1 - XAMPP**
1) Install [XAMPP for *Windows*](https://www.apachefriends.org/index.html).  *(These instructions assume that you install it at `C:\xampp`)*
2) Open the XAMPP Control Panel as an *administrator*, found at `C:\xampp\xampp-control.exe`.
3) Click the Start button to the right of "Apache".
4) Click the red X to the left of "Apache" to install the Apache service.

**Part 2 - This App**
1) [Download the source code](https://github.com/Bennycopter/WaniKani-Wallpaper-Generator/archive/master.zip) 
2) Extract the folder to `C:\xampp\htdocs`
3) Rename the `WaniKani-Wallpaper-Generator-master` folder to something nice and short, like `wkw`
4) Open your browser to [http://localhost/wkw](http://localhost/wkw)

## How to Contribute

Do you want to help make this tool even better?

**If you have an idea but don't know how to code**, then please share on the [WaniKani Community Page](https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321).  I would still like to hear from you!

**If you do know how to code**, then read the [Code Walkthrough](#code-walkthrough) and email me so we can get to work :)

## Change Log

**May 30, 2020** - Added to VCS, major refactors, and open source'd.

**June 15, 2019** - [Public release 2](https://community.wanikani.com/t/new-and-improved-wallpaper-generator/37321)

**December 22, 2018** - [~~Public release 1~~](https://community.wanikani.com/t/automatically-generate-new-wallpaper/34275) (deprecated)

## Requirements

- PHP 7.0+ with GD
- Apache
  
OR
- The latest version of [XAMPP](https://www.apachefriends.org/index.html)

## <a name="code-walkthrough"></a>Code Walkthrough

This section explains every file and folder to get you familiar with the code.

### [`index.php`](index.php)
- All front-end traffic goes through here because of the rules in [`.htaccess`](.htaccess).
- It includes [`config.php`](config.php) and all the files in [`/includes/`](includes), and then passes control to [`router.php`](router.php).
- The line `define("INDEX", true)` helps prevent other pages from being accessed directly.
- Other scripts can include this file without invoking the router by defining `NO_ROUTE`.

### [`config.php`](config.php)
- Contains configuration information.
  - Defines constants for paths and other configuration information.
  - Sets time zone and session path.

### [`router.php`](router.php)
- It passes control to some other file based on `$_GET["_url"]` and `$_SESSION`.
  - `$_GET["_url"]` is set from [`.htaccess`](.htaccess)'s rewrite rules.

### [`/pages`](pages)
Contains the pages and sub-pages seen by the user in the front-end, as well as virtual pages that are only  functional.
  - [`login.php`](pages/login.php) - Login form
  - [`logout.php`](pages/logout.php) - This logs the user out, then forwards the user to the login form
  - [`settings.php`](pages/settings.php) - This is the page the user sees after logging in.
    - [`instructions.php`](pages/instructions.php) - This is included by [`settings.php`](pages/settings.php) to reduce file size.
    - [`instructions/*`](pages/instructions) - These are included by [`instructions.php`](pages/instructions.php) to reduce file size.
  - [`download.php`](pages/download.php) - This generates the wallpaper and outputs it as a PNG.
  - [`order.php`](pages/order.php) - This file shows the contents of a specified Kanji Set Order file.

### [`/includes`](includes)
Contains functions that are used elsewhere.
 - [`helpers.php`](includes/helpers.php) - Adds basic functionality to PHP
   <details>
     <summary>Function Declarations</summary>
     
     ```php 
     clamp($var, $min, $max)
     interpolate($a, $b, $weight)
     imagecolorallocate_from_hex($image, $hex)
     file_put_prepended($string, $filename)
     file_put_json($file, $array)
     file_get_json($file)
     ```
   </details>
 - [`error_handling.php`](includes/error_handling.php) - Puts errors in [`/data/errors`](data/errors)
   <details>
      <summary>Function declarations</summary>
      
      ```php
      log_error($msg, $line, $file)
      ```
   </details>
 - [`wanikani.php`](includes/wanikani.php) - Making API requests
   <details>
      <summary>Function declarations</summary>
      
      ```php
   wanikani_request($endpoint, $api_key, $raw_response=false)
      ```
   </details>
 - [`users.php`](includes/users.php) - Everything related to users
   <details>
      <summary>Function declarations</summary>
      
      ```php
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
      num_generations_today($api_key)
      get_user_progress_report($api_key)
      ```
   </details>
 - [`presets.php`](includes/presets.php) - Everything related to presets.
   <details>
     <summary>Function declarations</summary>
     
     ```php
     font_file_exists($name)
     color_scheme_exists($name)
     screen_preset_exists($name)
     kanji_set_exists($name)
     load_kanji_order_file($kanji_set)
     ```
     The last four functions define the order that presets appear in the front-end.  The last function includes extra information to describe the kanji sets.
     ```php
     load_color_scheme_presets()
     load_font_presets()
     load_screen_presets()
     load_kanji_set_presets()
     ```
   </details>
 - [`post.php`](includes/post.php) - Contains functions used to filter and validate $_POST input.
   <details>
     <summary>Function declarations</summary>
     
     ```php
     sanitize_filename($v)
     filter_color_code($v)
     validate_color_code($v)
     filter_integer($v)
     return_false()
     filter_checkbox_value($v)
     ```
   </details>
 - [`image.php`](includes/image.php) - Contains exit functions that output an image instead of text.
   <details>
     <summary>Function declarations</summary>
     
     ```php
     die_with_image($file)
     die_with_text_on_image($text)
     ```
   </details>

### [`/assets`](assets)
Contains resources that are **not directly** presented to the end-user:
  - Font files
  - Error graphic files
  - SSL certificate file

### [`/public`](public)
Contains resources that are **directly** presented to the end-user:
  - 3rd-party files (see their licenses [here](public/3rd-party/3rd%20Party%20Licenses.md))
    - jQuery / jQuery UI
    - Spectrum Colorpicker
    - randomColor.js
  - CSS files
  - JS files
  - Images
    - *(**Exception** - source Photoshop images are in [public/images/source](public/images/source) just to be organized)*

### [`/presets`](presets)
These are data files that define the Fonts, Color Schemes, Kanji Sets ('orders'), and Screen Presets.
- [Color Schemes](presets/color-schemes) 
  <details>
  <summary>Details</summary>
  
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
  </details>
- [Fonts](presets/fonts)
  <details>
    <summary>Details</summary>
    
    - Line 1: Font file name, as found in /assets/fonts
    - Line 2: Source download location
    - Example: `fonts/Komorebi Gothic.txt`
      ```
      komorebi-gothic.ttf
      https://www.freejapanesefont.com/komorebi-gothic-download/
      ```
  </details>
- [Kanji Sets](presets/orders) ("Orders")
  <details>
    <summary>Details</summary>
    
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
  </details>
- [Screen Presets](presets/screen-presets)
  <details>
    <summary>Details</summary>
    
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
  </details>
- [WaniKani Kanji Cache](presets/all-wk-kanji.txt)
  <details>
    <summary>Details</summary>
    
    - [`all-wk-kanji.txt`](presets/all-wk-kanji.txt) contains the kanji from WaniKani with their subject ID.  This file should be regenerated whenever WaniKani has a content update.
  </details> 

### [`/data`](data)
These are files that are created as the app is used.
- [Users](data/users)
  <details>
    <summary>Details</summary>
    
    - A folder with files is created for each user with their API key.  For example:
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
  </details>
- [Sessions](data/sessions)
  - Session data is stored here.  To log everyone out, empty this folder.
- [Errors](data/errors)
  - Errors are stored here.

### [`/secret`](secret)
This contains admin tools.  Navigate your browser to this folder to access the tools.  It is password-protected by an empty folder inside the [`/secret/password`](secret/password) directory.
- [`/secret/password`](secret/password) - Contains nothing, don't look!
- [`/secret/tools`](secret/tools) - Contains tool files
  - [`generate-suborders.php`](secret/tools/generate-suborders.php) - Generates the Kanji Sets
  - [`request-wanikani.php`](secret/tools/request-wanikani.php) - Just a utility for requesting data from the WaniKani API
  - [`update-wanikani-cache.php`](secret/tools/update-wanikani-cache.php) - Updates the [`/presets/all-wk-kanji.txt`](secret/tools/update-wanikani-cache.php) file
  - [`log-everyone-out.php`](secret/tools/log-everyone-out.php) - This logs everyone out, just in case I need to

### [`/notes`](notes)
- Contains further documentation. You can ignore these.