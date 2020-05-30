<?php defined("INDEX") or die;

// User data
$api_key = $_SESSION["api-key"];
$device = $_SESSION["device"];
$settings = get_user_settings($api_key, $device);
$username = get_user_username($api_key);

// Presets
$fonts = load_font_presets();
$color_schemes = load_color_scheme_presets();
$screen_presets = load_screen_presets();
$kanji_sets = load_kanji_set_presets();

/// Handle Post ////////////////////////////////////////////////////////////////////////////////////////////////////////
if (sizeof($_POST) > 0) {

    $inputs = [

        ///// Presets /////
        [ // Fonts
            "keys" => ["font","wallpaper_title_font","section_titles_font"],
            "filter" => "sanitize_filename",
            "validate" => "font_file_exists",
        ],
        [ // Color Schemes
            "keys" => ["color_scheme"],
            "filter" => "sanitize_filename",
            "validate" => function ($v) {
                return $v == "Custom" || color_scheme_exists($v);
            },
        ],
        [ // Screen Presets
            "keys" => ["screen_preset"],
            "filter" => "sanitize_filename",
            "validate" => function ($v) {
                return $v == "Custom" || screen_preset_exists($v);
            },
        ],
        [ // Kanji Set
            "keys" => ["kanji_set"],
            "filter" => "sanitize_filename",
            "validate" => "kanji_set_exists",
        ],

        ///// Everything Else /////
        [ // Colors
            "keys" => [
                "c_unseen","c_apprentice","c_guru","c_master",
                "c_enlightened","c_burned","c_background",
                "c_section_titles","c_wallpaper_title"
            ],
            "filter" => "filter_color_code",
            "validate" => "validate_color_code",
        ],
        [ // Screen dimensions
            "keys" => [
                "width","height",
                "left","right",
                "top","bottom",
            ],
            "filter" => function($v) {
                $v = filter_integer($v);
                return clamp($v, 0, 4100);
            }
        ],
        [ // Kanji Subset limits
            "keys"=>[
                "first_kanji","last_kanji",
                "first_section","last_section",
            ],
        ],
        [ // Checkboxes
            "keys" => [
                "show_wallpaper_title",
                "show_section_titles",
            ],
            "on_empty" => "return_false",
            "filter" => "filter_checkbox_value"
        ],
        [ // Custom Title
            "keys" => ["custom_title"],
            "filter" => "trim",
        ],
        [ // Title heights
            "keys" => [
                "wallpaper_title_height",
                "section_title_height"
            ],
            "filter" => function($v) {
                $v = filter_integer($v);
                return clamp($v, 0, 200);
            }
        ],
        [ // Title Paddings
            "keys" => [
                "wallpaper_title_padding_top",
                "wallpaper_title_padding_left",
                "wallpaper_title_padding_bottom",
                "section_title_padding_top",
                "section_title_padding_left",
                "section_title_padding_bottom"
            ],
            "filter" => function($v) {
                $v = filter_integer($v);
                return clamp($v, 0, 4100);
            }
        ],
        [ // Kanji Spacing
            "keys" => ["kanji_spacing"],
            "filter" => function($v) {
                $v = filter_integer($v);
                return clamp($v, 1, 10);
            }
        ],
    ];

    foreach ($inputs as $input) {
        foreach ($input["keys"] as $key) {
            if (!isset($_POST[$key])) {
                if (isset($input["on_empty"]))
                    $_POST[$key] = $input["on_empty"]();
                else
                    continue;
            }
            // Filters sanitize the data coming in
            if (isset($input["filter"])) {
                $_POST[$key] = $input["filter"]($_POST[$key]);
            }

            // Validators
            if (!isset($input["validate"]) || $input["validate"]($_POST[$key])) {
                $settings[$key] = $_POST[$key];
            }
            else {
                $errors[] = "Input rejected for $key: " . $_POST[$key];
            }
        }
    }

    save_user_settings($api_key, $device, $settings);
}


/// Page ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///
/// The following HTML is real messy
/// I wrote this before I learned about things like Flexbox, Grid, and
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WaniKani Wallpaper Generator</title>

    <!-- jQuery -->
    <script src="<?=ROOT_URL?>/public/3rd-party/jquery-3.5.1.min.js"></script>
    <script src="<?=ROOT_URL?>/public/3rd-party/jquery-ui.min.js"></script>

    <!-- Colorpicker -->
    <script src='<?=ROOT_URL?>/public/3rd-party/spectrum.js'></script>
    <link rel="stylesheet" href="<?=ROOT_URL?>/public/3rd-party/spectrum.css">

    <!-- Random Color Generator -->
    <script src='<?=ROOT_URL?>/public/3rd-party/randomColor.js'></script>

    <!-- Styles -->
    <link rel="stylesheet" href="<?=ROOT_URL?>/public/css/styles.css?<?=V?>" />
    <link rel="stylesheet" href="<?=ROOT_URL?>/public/css/style-jquery-tabs.css?<?=V?>" />

</head>
<body>

<div id="wrapper">

    <h1>WaniKani Wallpaper Generator</h1>

    <?php if (isset($errors) && sizeof($errors) > 0): ?>
        <ul style="color: red">
            <?php foreach ($errors as $error): ?>
                <li><?=$error?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif;?>

    <p style="text-align: center; line-height: 140%; margin: 40px;">
        You are logged in as <strong><?=$username?></strong>, editing
        your settings for <strong>Device <?=$_SESSION["device"]?></strong>.
        <br /><a href="?logout">Log out</a> or
        <a href="?logout&api-key=<?=$api_key?>">Change Device</a>
    </p>

    <form method="post">

        <div style="float: left; width: 40%; margin-right: 5%;">
            <h2><label for="fonts">Font</label></h2>
            <select id="fonts" name="font" style="margin-bottom: 20px;">
                <?php foreach ($fonts as $font_title => $font) {
                    if ($settings["font"] == $font_title)
                        print "<option selected>$font_title</option>\n";
                    else
                        print "<option>$font_title</option>\n";
                }
                ?>
            </select>
            <div id="c_background" style="text-align: center;">
                <div style="display: inline-block;">
                    <div id="c_unseen" class="masked"></div>
                    <div id="c_apprentice" class="masked"></div>
                    <div id="c_guru" class="masked"></div>
                    <div id="c_master" class="masked"></div>
                    <div id="c_enlightened" class="masked"></div>
                    <div id="c_burned" class="masked"></div>
                </div>
            </div>
            <div>
                <h2 style="padding-top: 10px;">Color Effects</h2>
                <div>
                    <div style="float:left;width:30%;">
                        <button type="button" id="random-colors-btn">Random Colors</button>
                    </div>
                    <div style="float:left;width:65%; margin-left:5%;">
                        <p style="margin: 0;">Generate random colors for Apprentice to Wallpaper Title</p>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div style="padding-top: 10px;">
                    <div style="float:left;width:30%;">
                        <button type="button" id="reverse-colors-btn">Reverse Colors</button>
                    </div>
                    <div style="float:left;width:65%; margin-left:5%;">
                        <p style="margin: 0;">Reverses the colors from Apprentice->Enlightened</p>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>

        <div style="float: left; width: 25%; margin-right: 5%;">
            <h2><label for="color_schemes">Color Scheme</label></h2>
            <select id="color_schemes" name="color_scheme" style="margin-bottom: 20px;">
                <?php
                foreach ($color_schemes as $color_scheme_title=>$color_scheme) {
                    if ($settings["color_scheme"] == $color_scheme_title)
                        print "<option selected>$color_scheme_title</option>\n";
                    else
                        print "<option>$color_scheme_title</option>\n";
                }
                ?>
                <option<?php if ($settings["color_scheme"] == "Custom") print " selected";?>>Custom</option>
            </select>
            <!--<h2>Colors</h2>-->
            <div id="color-pickers">
                <input type="text" id="color-c_background" class="colorpicker" name="c_background" value="<?=$settings["c_background"]?>" /><label for="color-c_background">Background</label><br />
                <input type="text" id="color-c_unseen" class="colorpicker" name="c_unseen" value="<?=$settings["c_unseen"]?>" /><label for="color-c_unseen">Unseen</label><br />
                <input type="text" id="color-c_apprentice" class="colorpicker" name="c_apprentice" value="<?=$settings["c_apprentice"]?>" /><label for="color-c_apprentice">Apprentice</label><br />
                <input type="text" id="color-c_guru" class="colorpicker" name="c_guru" value="<?=$settings["c_guru"]?>" /><label for="color-c_guru">Guru</label><br />
                <input type="text" id="color-c_master" class="colorpicker" name="c_master" value="<?=$settings["c_master"]?>" /><label for="color-c_master">Master</label><br />
                <input type="text" id="color-c_enlightened" class="colorpicker" name="c_enlightened" value="<?=$settings["c_enlightened"]?>" /><label for="color-c_enlightened">Enlightened</label><br />
                <input type="text" id="color-c_burned" class="colorpicker" name="c_burned" value="<?=$settings["c_burned"]?>" /><label for="color-c_burned">Burned</label><br />
                <input type="text" id="color-c_section_titles" class="colorpicker" name="c_section_titles" value="<?=$settings["c_section_titles"]?>" /><label for="color-c_section_titles">Section Titles</label><br />
                <input type="text" id="color-c_wallpaper_title" class="colorpicker" name="c_wallpaper_title" value="<?=$settings["c_wallpaper_title"]?>" /><label for="color-c_wallpaper_title">Wallpaper Title</label><br />
            </div>
            <em>Click on a color bubble to choose a custom color!</em>
        </div>
        <div style="float: left; width: 25%;">

            <h2><label for="screen_presets">Screen Presets</label></h2>
            <select id="screen_presets" name="screen_preset" style="margin-bottom: 20px;">
                <?php
                foreach ($screen_presets as $screen_preset_title=>$screen_preset) {
                    if ($settings["screen_preset"] == $screen_preset_title)
                        print "<option selected>$screen_preset_title</option>\n";
                    else
                        print "<option>$screen_preset_title</option>\n";
                }
                ?>
                <option<?php if ($settings["screen_preset"] == "Custom") print " selected";?>>Custom</option>
            </select>

            <h2 style="">Screen Size</h2>

            <div class="half-width-inputs">
                <div><label for="width">Width</label><input type="number" value="<?=$settings["width"]?>" name="width" id="width" /></div>
                <div><label for="height">Height</label><input type="number" value="<?=$settings["height"]?>" name="height" id="height" /></div>
            </div>
            <div style="clear: both;"></div>
            <h2 style="margin-top:15px;">Margins</h2>
            <div class="half-width-inputs">
                <div><label for="left">Left</label><input type="number" value="<?=$settings["left"]?>" name="left" id="left" /></div>
                <div><label for="right">Right</label><input type="number" value="<?=$settings["right"]?>" name="right" id="right" /></div>
                <div><label for="top">Top</label><input type="number" value="<?=$settings["top"]?>" name="top" id="top" /></div>
                <div><label for="bottom">Bottom</label><input type="number" value="<?=$settings["bottom"]?>" name="bottom" id="bottom" /></div>
            </div>
            <div style="clear: both;"></div>
            <em>Adjust the margins for your taskbar/dock location/size!</em>

            <h2 style="margin-top: 20px;"><label for="kanji_spacing">Kanji Spacing</label></h2>

            <input id="kanji_spacing" type="range" min="1" max="10" step="1" list="steplist" value="<?=$settings["kanji_spacing"]?>" style="width: 100%;" name="kanji_spacing">
            <datalist id="steplist">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
                <option>7</option>
                <option>8</option>
                <option>9</option>
                <option>10</option>
            </datalist>

            <div style="text-align:center;width:100%; font-style: italic">Tight &xhArr; Loose<br />3rd Tick = Default</div>
        </div>
        <div style="clear:both;"></div>
        <div style="float: left; width: 47.5%; padding-top: 60px;">

            <h2><label for="kanji_sets">Kanji Set</label></h2>
            <select name="kanji_set" id="kanji_sets">
                <?php

                $last_link = "";
                $last_title = "";
                foreach ($kanji_sets as $link=>$kanji_set) {
                    print "<option value='$link' ".($settings["kanji_set"]==$link?"selected":"").">";
                    print $kanji_set["title"];
                    print "</option>";
                    if (isset($kanji_set["subsets"])) {
                        foreach ($kanji_set["subsets"] as $subset_link=>$subset) {
                            print "<option value='$link-$subset_link'".($settings["kanji_set"]=="$link-$subset_link"?"selected":"").">";
                            print $kanji_set["title"] . " - " . $subset["title"];
                            print "</option>";
                        }
                    }
                }
                ?>
            </select>
            <p style="text-align: center;" id="kanjiset-description">This kanji set is a variation on the Heisig set.  All missing WaniKani kanji have been added, and all non-WaniKani kanji have been removed.</p>
            <p style="text-align: center;">Click <a href="order.php?order=default" target="_blank" id="kanjiset-full-list-link">here</a> to see this set's kanji<span id="kanjiset-and-sections-text"> and sections</span>.</p>
            <p style="text-align: center;" id="kanjiset-more-info">Click <a href="#" target="_blank" id="kanjiset-more-info-link">here</a> for more info on this set.</p>
        </div>

        <div style="float: left; width: 47.5%; padding-top: 60px; padding-left: 5%;">
            <h2>Subset Options</h2>
            <p style="text-align: center;" id="kanjiset-list-count-info">This list has <span id="kanjiset-num-kanji">2034</span> kanji and <span id="kanjiset-num-sections">no</span> sections.<br />You can limit which <span id="kanjiset-limit-which-thing">sections</span> are shown here.</p>

            <div id="kanjiset-sections-select-container" style="display: none;">
                <div style="float: left; width: 47.5%;">
                    <div style="text-align: center; padding-bottom: 8px;"><label for="kanjiset-first-section-select">First Section</label></div>
                    <select id="kanjiset-first-section-select" name="first_section">
                        <!-- to be populated by JS -->
                    </select>
                </div>
                <div style="float: left; width: 47.5%; padding-left: 5%;">
                    <div style="text-align: center; padding-bottom: 8px;"><label for="kanjiset-last-section-select">Last Section</label></div>
                    <select id="kanjiset-last-section-select" name="last_section">
                        <!-- to be populated by JS -->
                    </select>
                </div>
            </div>

            <div id="kanjiset-kanji-select-container">
                <div style="float: left; width: 47.5%;">
                    <div style="text-align: center; padding-bottom: 8px;"><label for="kanjiset-first-kanji-num-input">First Kanji #</label></div>
                    <input type="number" style="width: 100%;" min="1" id="kanjiset-first-kanji-num-input" name="first_kanji" value="<?=$settings["first_kanji"]?>" />
                </div>
                <div style="float: left; width: 47.5%; padding-left: 5%;">
                    <div style="text-align: center; padding-bottom: 8px;"><label for="kanjiset-last-kanji-num-input">Last Kanji #</label></div>
                    <input type="number" style="width: 100%;" max="2034" id="kanjiset-last-kanji-num-input" name="last_kanji" value="<?=$settings["last_kanji"]?>" />
                </div>
            </div>


        </div>

        <div style="clear:both;">
            <div style="float: left; width:47.5%;">
                <h2 style="padding-top:30px;">Wallpaper Title</h2>

                <div style="float: left; width: 55%; margin-right: 5%;">
                    <div style="text-align: center;">
                        <label class="checkbox-container">Show wallpaper title?
                            <input type="checkbox" <?php if ($settings["show_wallpaper_title"]==1) print 'checked="checked"'; ?> name="show_wallpaper_title">
                            <span class="checkbox-checkmark"></span>
                        </label>
                    </div>
                    <div style="float:left; width:100%; padding-bottom: 10px;">
                        <div style="float: left; width: 40%;">
                            <label for="wallpaper_title_height" style="position:relative;top:4px;">Text Height</label>
                        </div>
                        <div style="float: left; width: 55%; margin-left: 5%;">
                            <input type="number" style="width: 100%;" value="<?=$settings["wallpaper_title_height"]?>" min="1" max="200" name="wallpaper_title_height" id="wallpaper_title_height" />
                        </div>
                    </div>

                    <div style="float: left; width: 20%;">
                        <label style="position:relative;top:10px;" for="wallpaper_title_font">Font</label>
                    </div>
                    <div style="float: left; width: 75%; margin-left: 5%;">
                        <select name="wallpaper_title_font" id="wallpaper_title_font" style="margin-bottom: 20px;">
                            <?php foreach ($fonts as $font_title => $font) {
                                if ($settings["wallpaper_title_font"] == $font_title)
                                    print "<option selected>$font_title</option>\n";
                                else
                                    print "<option>$font_title</option>\n";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div style="float:left; width: 40%;">
                    <div style="text-align: center;">Text Padding</div>
                    <div style="margin-top:6px;margin-bottom: 8px;">
                        <div style="float: left; width: 40%; text-align: center;">
                            <label style="position:relative;top:4px;" for="wallpaper_title_padding_top">Top</label>
                        </div>
                        <div style="float: left; width: 55%; margin-left: 5%;">
                            <input type="number" style="width: 100%;" value="<?=$settings["wallpaper_title_padding_top"]?>" min="0" name="wallpaper_title_padding_top" id="wallpaper_title_padding_top" />
                        </div>
                    </div>
                    <div style="margin-top:6px;margin-bottom: 8px;">
                        <div style="float: left; width: 40%; text-align: center;">
                            <label style="position:relative;top:4px;" for="wallpaper_title_padding_left">Left</label>
                        </div>
                        <div style="float: left; width: 55%; margin-left: 5%;">
                            <input type="number" style="width: 100%;" value="<?=$settings["wallpaper_title_padding_left"]?>" min="0" name="wallpaper_title_padding_left" id="wallpaper_title_padding_left" />
                        </div>
                    </div>
                    <div style="margin-top:6px;margin-bottom: 8px;">
                        <div style="float: left; width: 40%; text-align: center;">
                            <label style="position:relative;top:4px;" for="wallpaper_title_padding_bottom">Bottom</label>
                        </div>
                        <div style="float: left; width: 55%; margin-left: 5%;">
                            <input type="number" style="width: 100%;" value="<?=$settings["wallpaper_title_padding_bottom"]?>" min="0" name="wallpaper_title_padding_bottom" id="wallpaper_title_padding_bottom" />
                        </div>
                    </div>
                </div>
                <div style="float:left; width:100%;">
                    <div style="float: left; width: 100%; text-align: center;">
                        <div style="margin-bottom: 3px;"><label for="custom_title">Custom Wallpaper Title</label></div>
                        <input type="text" name="custom_title" style="width: 100%;" value="<?php print htmlspecialchars($settings["custom_title"]); ?>" id="custom_title" />
                    </div>
                </div>
            </div>

            <div style="float: left; width:47.5%; margin-left: 5%; color: #444;" id="section-title-settings-container">
                <h2 style="padding-top:30px;">Section Titles</h2>

                <div style="float: left; width: 55%; margin-right: 5%;">
                    <div style="text-align: center;">
                        <label class="checkbox-container">Show section titles?
                            <input type="checkbox" <?php if ($settings["show_section_titles"]==1) print 'checked="checked"'; ?> name="show_section_titles">
                            <span class="checkbox-checkmark"></span>
                        </label>
                    </div>
                    <div style="float:left; width:100%; padding-bottom: 10px;">
                        <div style="float: left; width: 40%; text-align: center;">
                            <label style="position:relative;top:4px;" for="section_title_height">Text Height</label>
                        </div>
                        <div style="float: left; width: 55%; margin-left: 5%;">
                            <input type="number" style="width: 100%;" value="<?=$settings["section_title_height"]?>" min="1" max="200" name="section_title_height" id="section_title_height" />
                        </div>
                    </div>
                    <div style="float: left; width: 20%;">
                        <label style="position:relative;top:10px;" for="section_titles_font">Font</label>
                    </div>
                    <div style="float: left; width: 75%; margin-left: 5%;">
                        <select name="section_titles_font" style="margin-bottom: 20px;" id="section_titles_font">
                            <?php foreach ($fonts as $font_title => $font) {
                                if ($settings["section_titles_font"] == $font_title)
                                    print "<option selected>$font_title</option>\n";
                                else
                                    print "<option>$font_title</option>\n";
                            }
                            ?>
                        </select>
                    </div>

                </div>

                <div style="float:left; width: 40%;">
                    <div style="text-align: center;">Text Padding</div>
                    <div style="margin-top:6px;margin-bottom: 8px;">
                        <div style="float: left; width: 40%; text-align: center;">
                            <label style="position:relative;top:4px;" for="section_title_padding_top">Top</label>
                        </div>
                        <div style="float: left; width: 55%; margin-left: 5%;">
                            <input type="number" style="width: 100%;" value="<?=$settings["section_title_padding_top"]?>" min="0" name="section_title_padding_top" id="section_title_padding_top" />
                        </div>
                    </div>
                    <div style="margin-top:6px;margin-bottom: 8px;">
                        <div style="float: left; width: 40%; text-align: center;">
                            <label style="position:relative;top:4px;" for="section_title_padding_left">Left</label>
                        </div>
                        <div style="float: left; width: 55%; margin-left: 5%;">
                            <input type="number" style="width: 100%;" value="<?=$settings["section_title_padding_left"]?>" min="0" name="section_title_padding_left" id="section_title_padding_left" />
                        </div>
                    </div>
                    <div style="margin-top:6px;margin-bottom: 8px;">
                        <div style="float: left; width: 40%; text-align: center;">
                            <label style="position:relative;top:4px;" for="section_title_padding_bottom">Bottom</label>
                        </div>
                        <div style="float: left; width: 55%; margin-left: 5%;">
                            <input type="number" style="width: 100%;" value="<?=$settings["section_title_padding_bottom"]?>" min="0" name="section_title_padding_bottom" id="section_title_padding_bottom" />
                        </div>
                    </div>
                </div>
                <p style="clear:both; text-align: center; color: white;" id="section-title-settings-warning">Section titles are not available<br />for the <strong id="section-title-settings-warning-kanjiset-name">Default</strong> Kanji Set</p>
            </div>

        </div>


        <div style="clear: both; height: 0;"></div>

        <div style="text-align: center; margin-top: 40px;">
        <button class="btn-big">Save Settings</button>
        </div>

        <?php include "instructions.php"; ?>

    </form>



</div>

<script>

let color_keys = ["c_unseen","c_apprentice","c_guru","c_master","c_enlightened","c_burned","c_background","c_section_titles","c_wallpaper_title"];

// Prepare presets
let color_schemes = <?php print json_encode($color_schemes); ?>;
let screen_presets = <?php print json_encode($screen_presets); ?>;
let kanji_sets = <?php print json_encode($kanji_sets); ?>;

function apply_color_scheme(scheme_title) {
    if (!color_schemes.hasOwnProperty(scheme_title)) {
        console.log("Scehem not found");
        return false;
    }
    let scheme = color_schemes[scheme_title];
    for (let key in scheme) {
        if (scheme.hasOwnProperty(key)) {
            $("#"+key).css("background-color", scheme[key]);
            $("#color-"+key).spectrum("set",scheme[key]);
        }
    }
}

function apply_font(font_title) {
    for (let i = 0; i < color_keys.length; i++) {
        if (color_keys[i] === "c_background" || color_keys[i] === "c_section_titles" || color_keys[i] === "c_wallpaper_title")
            continue;
        $("#"+color_keys[i])
            .css("-webkit-mask-image", "url('<?=ROOT_URL?>public/images/"+font_title+"-"+color_keys[i]+".png')")
            .css("mask-image", "url('<?=ROOT_URL?>public/images/"+font_title+"-"+color_keys[i]+".png')");
    }
}

function apply_screen_preset(preset_title) {
    if (!screen_presets.hasOwnProperty(preset_title)) return false;
    let preset = screen_presets[preset_title];
    for (let key in preset) {
        if (preset.hasOwnProperty(key)) {
            $("#"+key).val(preset[key]);
        }
    }
}

function apply_kanji_set(id, default_first_section,default_last_section,default_first_kanji,default_last_kanji) {

    // default parameters
    default_first_section = default_first_section || "";
    default_last_section = default_last_section || "";
    default_first_kanji = default_first_kanji || "";
    default_last_kanji = default_last_kanji || "";

    console.log(id);
    let subset = "";
    let kanji_set = id;
    if (id.indexOf("-") !== -1) {
        subset = id.substr(id.indexOf("-")+1);
        kanji_set = id.substr(0, id.indexOf("-"));
    }

    console.log(kanji_set);
    console.log(subset);

    let ks = $.extend({}, kanji_sets[kanji_set]);
    if (subset !== "") {
        ks = $.extend(ks, kanji_sets[kanji_set]["subsets"][subset]);
        delete ks["subsets"];
    }
    console.log(ks);

    $("#kanjiset-description").html(ks["description"]);
    if (ks.hasOwnProperty("more_info")) {
        $("#kanjiset-more-info-link").attr("href", ks["more_info"]);
        $("#kanjiset-more-info").show();
    }
    else {
        $("#kanjiset-more-info").hide();
    }

    $("#kanjiset-num-kanji").html(ks["num_kanji"]);
    if (ks.hasOwnProperty("sections")) {
        $("#kanjiset-num-sections").html(ks["sections"].length);
        $("#kanjiset-limit-which-thing").html("sections");
        $("#kanjiset-sections-select-container").show();
        $("#kanjiset-kanji-select-container").hide();

        // Remove validation from the first and last kanji fields to prevent an error
        $("#kanjiset-first-kanji-num-input").attr("min", "").attr("max","");
        $("#kanjiset-last-kanji-num-input").attr("min", "").attr("max","");
    }
    else {
        $("#kanjiset-num-sections").html("no");
        $("#kanjiset-limit-which-thing").html("kanji");
        $("#kanjiset-sections-select-container").hide();
        $("#kanjiset-kanji-select-container").show();

        let first_kanji = "1";
        let last_kanji = ks["num_kanji"];
        if (default_first_kanji !== "") first_kanji = default_first_kanji;
        if (default_last_kanji !== "") last_kanji = default_last_kanji;

        $("#kanjiset-first-kanji-num-input").val(first_kanji).attr("min", 1).attr("max",ks["num_kanji"]);
        $("#kanjiset-last-kanji-num-input").val(last_kanji).attr("min", 1).attr("max",ks["num_kanji"]);
    }

    $("#kanjiset-full-list-link").attr("href", "order.php?order="+id);

    // Populate section selects
    let $first_select = $("#kanjiset-first-section-select");
    let $last_select = $("#kanjiset-last-section-select");

    // Prepare the Subset Options
    if (ks.hasOwnProperty("sections")) {
        let prev_first_value = $first_select.val();
        let prev_last_value = $last_select.val();

        if (default_first_section !== "")
            prev_first_value = default_first_section;
        if (default_last_section !== "")
            prev_last_value = default_last_section;

        $first_select.empty();
        $last_select.empty();
        for (let i = 0; i < ks.sections.length; i++) {
            $(document.createElement("option")).html(ks.sections[i]).appendTo($first_select);
            $(document.createElement("option")).html(ks.sections[i]).appendTo($last_select);
        }
        if (ks.sections.indexOf(prev_first_value) >= 0) {
            $first_select.val(prev_first_value);
        }
        if (ks.sections.indexOf(prev_last_value) >= 0) {
            $last_select.val(prev_last_value);
        }
        else {
            if (ks.sections[ks.sections.length-1] === "Level 60")
                $last_select.val("Level 10");
            else
                $last_select.val(ks.sections[ks.sections.length-1]);
        }
    }

    // Active or de-active the Section Titles settings
    if (ks.hasOwnProperty("sections")) {
        $("#section-title-settings-container").css("color", "#fff");
        $("#section-title-settings-warning").hide();
    }
    else {
        $("#section-title-settings-container").css("color", "#444");
        $("#section-title-settings-warning").show();
        $("#section-title-settings-warning-kanjiset-name").html(ks["title"]);
    }
}

$(document).ready(function() {

    <?php if ($settings["color_scheme"] == "Custom") { ?>
    // Set the Custom color schemes to whatever is currently in settings
    color_schemes["Custom"] = {
        c_apprentice: "<?=$settings["c_apprentice"]?>",
        c_background: "<?=$settings["c_background"]?>",
        c_burned: "<?=$settings["c_burned"]?>",
        c_enlightened: "<?=$settings["c_enlightened"]?>",
        c_guru: "<?=$settings["c_guru"]?>",
        c_master: "<?=$settings["c_master"]?>",
        c_unseen: "<?=$settings["c_unseen"]?>",
        c_section_titles: "<?=$settings["c_section_titles"]?>",
        c_wallpaper_title: "<?=$settings["c_wallpaper_title"]?>",
    };
    <?php } ?>

    <?php if ($settings["screen_preset"] == "Custom") { ?>
    // Set the Custom screen presets to whatever is currently in settings
    screen_presets["Custom"] = {
        width: <?=$settings["width"]?>,
        height: <?=$settings["height"]?>,
        top: <?=$settings["top"]?>,
        bottom: <?=$settings["bottom"]?>,
        left: <?=$settings["left"]?>,
        right: <?=$settings["right"]?>,
    };
    <?php } ?>

    // Color Pickers
    $(".colorpicker").spectrum({
        change: function(color) {
            let tag = $(this).attr("id").replace("color-","");
            $("#color_schemes").val("Custom");
            $("#"+tag).css("background-color", color.toHexString());
            $("#color-"+tag).val(color.toHexString());

            // Set all colors
            color_schemes["Custom"] = {};
            for (let i = 0; i < color_keys.length; i++) {
                color_schemes["Custom"][color_keys[i]] = $("#color-"+color_keys[i]).val();
            }
        },
        move: function(color) {
            let tag = $(this).attr("id").replace("color-","");
            $("#color_schemes").val("Custom");
            $("#"+tag).css("background-color", color.toHexString());
            $("#color-"+tag).val(color.toHexString());

            // Set all colors
            color_schemes["Custom"] = {};
            for (let i = 0; i < color_keys.length; i++) {
                color_schemes["Custom"][color_keys[i]] = $("#color-"+color_keys[i]).val();
            }
        },
        showButtons: false,
        showInput: true,
        preferredFormat: "hex",
        replacerClassName: "plain-colorpicker",
    });

    // When you change an item manually
    $("#width, #height, #left, #right, #top, #bottom").change(function(){
        // Set to custom
        $("#screen_presets").val("Custom");
        screen_presets["Custom"] = {};
        screen_presets["Custom"]["width"] = $("#width").val();
        screen_presets["Custom"]["height"] = $("#height").val();
        screen_presets["Custom"]["left"] = $("#left").val();
        screen_presets["Custom"]["right"] = $("#right").val();
        screen_presets["Custom"]["top"] = $("#top").val();
        screen_presets["Custom"]["bottom"] = $("#bottom").val();
    });

    // Color scheme default and controller
    apply_color_scheme("<?=$settings["color_scheme"]?>");
    $("#color_schemes").change(function() {
        apply_color_scheme($(this).val());
    });

    // Font default and controller
    apply_font("<?=$settings["font"]?>");
    $("#fonts").change(function() {
        apply_font($(this).val());
    });

    // Screen Preset default and controller
    apply_screen_preset("<?=$settings["screen_preset"]?>");
    $("#screen_presets").change(function() {
        apply_screen_preset($(this).val());
    });

    // Kanji Set default and controller
    apply_kanji_set("<?=$settings["kanji_set"]?>","<?=$settings["first_section"]?>","<?=$settings["last_section"]?>","<?=$settings["first_kanji"]?>","<?=$settings["last_kanji"]?>");
    $("#kanji_sets").change(function() {
        apply_kanji_set($(this).val());
    });

    $("#kanjiset-first-section-select").change(function() {
        if (this.selectedIndex > document.getElementById("kanjiset-last-section-select").selectedIndex)
            document.getElementById("kanjiset-last-section-select").selectedIndex = this.selectedIndex;
    });

    $("#kanjiset-last-section-select").change(function() {
        if (this.selectedIndex < document.getElementById("kanjiset-first-section-select").selectedIndex)
            document.getElementById("kanjiset-first-section-select").selectedIndex = this.selectedIndex;
    });

    $("#random-colors-btn").click(function(){

        let colors = [
            randomColor(),
            randomColor(),
            randomColor(),
            randomColor(),
            randomColor(),
            randomColor(),
            randomColor(),
        ];
        console.log(colors);

        $("#c_apprentice").css("background-color", colors[0]);
        $("#color-c_apprentice").spectrum("set", colors[0]);

        $("#c_guru").css("background-color", colors[1]);
        $("#color-c_guru").spectrum("set", colors[1]);

        $("#c_master").css("background-color", colors[2]);
        $("#color-c_master").spectrum("set", colors[2]);

        $("#c_enlightened").css("background-color", colors[3]);
        $("#color-c_enlightened").spectrum("set", colors[3]);

        $("#c_burned").css("background-color", colors[4]);
        $("#color-c_burned").spectrum("set", colors[4]);

        $("#c_section_titles").css("background-color", colors[5]);
        $("#color-c_section_titles").spectrum("set", colors[5]);

        $("#c_wallpaper_title").css("background-color", colors[6]);
        $("#color-c_wallpaper_title").spectrum("set", colors[6]);

        return false;
    });
    $("#reverse-colors-btn").click(function(){
        let $c_apprentice = $("#c_apprentice");
        let $c_guru = $("#c_guru");
        let $c_master = $("#c_master");
        let $c_enlightened = $("#c_enlightened");
        let colors = [
            $c_apprentice.css("background-color"),
            $c_guru.css("background-color"),
            $c_master.css("background-color"),
            $c_enlightened.css("background-color"),
        ];

        $c_apprentice.css("background-color", colors[3]);
        $("#color-c_apprentice").spectrum("set", colors[3]);

        $c_guru.css("background-color", colors[2]);
        $("#color-c_guru").spectrum("set", colors[2]);

        $c_master.css("background-color", colors[1]);
        $("#color-c_master").spectrum("set", colors[1]);

        $c_enlightened.css("background-color", colors[0]);
        $("#color-c_enlightened").spectrum("set", colors[0]);
        return false;
    });
});
</script>

<script>
    let preloaded_images = {};
<?php
// Preload font images
foreach ($fonts as $font_title=>$font) {
    foreach (["c_apprentice","c_burned","c_enlightened","c_guru","c_master","c_unseen"] as $level) {
        ?>
        preloaded_images["<?=$font_title?>-<?=$level?>"] = new Image();
        preloaded_images["<?=$font_title?>-<?=$level?>"].src = "<?=ROOT_URL?>public/images/<?=$font_title?>-<?=$level?>.png";
        <?php
    }
}
?>
</script>

</body>
</html>