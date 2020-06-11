<?php defined("INDEX") or die;

set_time_limit(5);

// URL pattern /?k=__&d=__
// Old URL pattern /download.php?api-key=__&device=__
$api_key    = sanitize_filename($_GET["k"] ?? $_GET["api-key"]);
$device     = filter_integer($_GET["d"] ?? $_GET["device"] ?? 1);


///// Error Handling /////

if (strlen($api_key) == 32)
    // v1 API Key is 32 characters
    die_with_image(ASSETS_DIR."/error-graphics/old-api-key.png");

if (!user_folder_exists($api_key))
    die_with_text_on_image("Error: Cannot find user folder");

/** @var array User settings (var name shortened for brevity) */
$s = get_user_settings($api_key, $device);

if (is_null($s["width"]))
    die_with_text_on_image("Did you remember to click on 'Save Settings'?");

if ($s["width"] < 10 || $s["height"] < 10)
    die_with_text_on_image("Error: Width or height is too small.");

if (num_generations_today($api_key) > DAILY_GENERATION_LIMIT)
    die_with_image(ASSETS_DIR."/error-graphics/daily-limit-exceeded.jpg");


///// Make Wallpaper /////

log_generation($api_key);
$progress_report = get_user_progress_report($api_key);
$font_scale = calculate_font_scale($s);
$kanji_sections = load_kanji_sections($s);
if ($s["collapse_sections"] == 1) {
    $kanji_sections = collapse_sections($kanji_sections);
}
$fittings = calculate_fittings($s, $kanji_sections, $font_scale);
create_and_output_wallpaper($s, $progress_report, $kanji_sections, load_kanji_set_presets(), load_font_presets(), $fittings);
exit;


#### Functions #########################################################################################################

function calculate_font_scale($s) {
    return interpolate(
        MAX_FONT_SCALE, // Tighter
        MIN_FONT_SCALE, // Looser
        ($s["kanji_spacing"]-1) / 9 // 1=>0% (tight), 10=>100% (loose)
    );
}

function calculate_fittings($s, $kanji_sections, $font_scale) {

    // Figure out the best fit
    $drawable_width = $s["width"] - $s["left"] - $s["right"];
    $drawable_height = $s["height"] - $s["top"] - $s["bottom"];
    $num_kanji_x = 1; // starting point to test
    $num_kanji_y = null;
    $highest_valid_num_kanji_x = null;
    $kanji_width = null;

    // Reduce drawable height by the size of the section headers and the title
    if ($s["show_section_titles"] == 1) {
        $drawable_height -= sizeof($kanji_sections) * $s["section_title_height"];
        $drawable_height -= $s["section_title_padding_top"] * (sizeof($kanji_sections) - (($s["show_wallpaper_title"] == 0)?1:0));
        $drawable_height -= $s["section_title_padding_bottom"] * sizeof($kanji_sections);
    }
    if ($s["show_wallpaper_title"] == 1) {
        $drawable_height -= $s["wallpaper_title_height"];
        $drawable_height -= $s["wallpaper_title_padding_top"];
        $drawable_height -= $s["wallpaper_title_padding_bottom"];
    }
    if ($drawable_height < 100)
        die_with_text_on_image("The drawable height is too little.\nPlease reduce your text sizes and/or padding and/or level span and try again.");

    // Figure out how many kanji to draw in the x direction by testing the y direction.
    while (is_null($highest_valid_num_kanji_x))
    {
        $kanji_width = $drawable_width / ($num_kanji_x);
        $kanji_height = $kanji_width;

        $num_kanji_y = 0;

        // Sum each section's height
        foreach ($kanji_sections as $kanji_section) {
            $num_kanji_y += ceil(mb_strlen($kanji_section) / $num_kanji_x);
        }

        if ($drawable_height >= $kanji_height * $num_kanji_y)
            $highest_valid_num_kanji_x = $num_kanji_x;
        else
            $num_kanji_x++;
    }

    // Figure out kanji_dx
    $kanji_dx = $kanji_width;
    $font_size = $kanji_width * $font_scale;

    // Spread out the $kanji_dy
    $kanji_dy = $drawable_height / $num_kanji_y;

    // Adjust outwards for the furthest possible edges
    $kanji_dx += (MAX_FONT_SCALE-$font_scale)*($font_size/$font_scale)/($num_kanji_x-1);
    if ($num_kanji_y != 1)
        $kanji_dy += (MAX_FONT_SCALE-$font_scale)*($font_size/$font_scale)/($num_kanji_y-1);

    return [
        "kanji_dx" => $kanji_dx,
        "kanji_dy" => $kanji_dy,
        "font_size" => $font_size,
        "num_kanji_x" => $num_kanji_x,
        "y_nudge" => 0
            // Compensate for character origin and line height
            + $font_size * 1.2
            // Compensate vertical-center, based on padding around kanji (i.e. "Kanji Spacing"/tightness/looseness)
            + (MAX_FONT_SCALE-$font_scale)*($font_size/$font_scale)/2,
    ];
}

####################################
### Generate the wallpaper image ###
####################################

function create_and_output_wallpaper($s, $progress_report, $kanji_sections, $kanji_sets, $fonts, $fittings) {
    # Create Canvas
    $canvas = imagecreatetruecolor($s["width"], $s["height"]);

    # Allocate colors
    $colors = array_flip([
        "unseen", "apprentice", "guru",
        "master", "enlightened", "burned",
        "background", "section_titles", "wallpaper_title",
    ]);
    foreach ($colors as $k=>&$v) {
        $v = imagecolorallocate_from_hex($canvas,$s["c_$k"]);
    }

    // Fill the background
    imagefill($canvas, 0, 0, $colors["background"]);

    // Get fonts
    $font_path = $fonts[$s["font"]]["realpath"];
    $section_titles_font_path = $fonts[$s["section_titles_font"]]["realpath"];
    $wallpaper_title_font_path = $fonts[$s["wallpaper_title_font"]]["realpath"];

    // DRAW
    $section_offset_y = $s["top"];

    // Draw Wallpaper Title
    if ($s["show_wallpaper_title"] == 1) {
        $wallpaper_title = $s["custom_title"];
        if (trim($wallpaper_title) == "") {
            $kanji_set_pieces = explode("-",$s["kanji_set"]);
            $kanji_set_main = array_shift($kanji_set_pieces);
            $kanji_subset_name = implode("-",$kanji_set_pieces);
            $ks = $kanji_sets[$kanji_set_main];
            $wallpaper_title = $ks["title"];
            if ($kanji_subset_name != "") {
                $wallpaper_title .= " - " . $ks["subsets"][$kanji_subset_name]["title"];
            }
        }
        $section_offset_y += $s["wallpaper_title_padding_top"];
        imagettftext($canvas, $s["wallpaper_title_height"], 0, $s["left"]+$s["wallpaper_title_padding_left"], $section_offset_y+$s["wallpaper_title_height"], $colors["wallpaper_title"], $wallpaper_title_font_path, $wallpaper_title);
        $section_offset_y += $s["wallpaper_title_height"];
        $section_offset_y += $s["wallpaper_title_padding_bottom"];
    }

    // Remove padding if drawing section titles
    if ($s["show_section_titles"] == 1 && $s["show_wallpaper_title"] == 0)
        $section_offset_y -= $s["section_title_padding_top"];

    // Draw each character
    foreach ($kanji_sections as $section_title => $kanji_section) {
        //print $kanji_section;
        $num_kanji = mb_strlen($kanji_section);

        // Draw section title
        if ($s["show_section_titles"] == 1) {
            $section_offset_y += $s["section_title_padding_top"];
            imagettftext($canvas, $s["section_title_height"], 0, $s["left"] + $s["section_title_padding_left"], $section_offset_y + $s["section_title_height"], $colors["section_titles"], $section_titles_font_path, $section_title);
            $section_offset_y += $s["section_title_height"];
            $section_offset_y += $s["section_title_padding_bottom"];
        }

        // Draw characters
        for ($i = 0; $i < $num_kanji; $i++) {
            $c = mb_substr($kanji_section, $i, 1);

            // Set the color
            $color = $colors["unseen"];
            if (isset($progress_report[$c]))
                $color = $colors[$progress_report[$c]];

            // Figure out the position
            $x = $s["left"] + ($i % $fittings["num_kanji_x"]) * $fittings["kanji_dx"];
            $y = $section_offset_y + floor($i / $fittings["num_kanji_x"]) * $fittings["kanji_dy"];

            // Draw text
            imagettftext($canvas, $fittings["font_size"], 0, $x, $y + $fittings["y_nudge"], $color, $font_path, $c);
        }
        $section_offset_y += ceil($num_kanji / $fittings["num_kanji_x"]) * $fittings["kanji_dy"];
    }

    // Output and free from memory
    header('Content-Type: image/png');
    imagepng($canvas);
    imagedestroy($canvas);
}


function load_kanji_sections($s) {
    $kanji_order_file = load_kanji_order_file($s["kanji_set"]);
    $kanji_lines = explode(PHP_EOL, $kanji_order_file);

    // Step 1 - Remove First Line
    array_shift($kanji_lines);

    // Step 2 - Remove Lines that have a #
    foreach ($kanji_lines as $i => $line)
        if (mb_strpos($line, "#") !== false)
            unset($kanji_lines[$i]);

    // Step 3 - Separate by section, if applicable, split like "this:"
    $kanji_sections = [];
    $current_section = null;
    foreach ($kanji_lines as $i => $line) {
        $section_split = mb_strpos($line, ":");
        if ($section_split !== false) {
            $current_section = trim(mb_substr($line, 0, $section_split));
            $line = mb_substr($line, $section_split+1);
        }

        if ($current_section != null) {
            if (!isset($kanji_sections[$current_section]))
                $kanji_sections[$current_section] = array();

            $kanji_sections[$current_section][] = $line;
        }
    }

    // Step 4 - Combine all lines (in each section if applicable)
    if (sizeof($kanji_sections) > 0) {
        foreach ($kanji_sections as $i=>$section) {
            $kanji_sections[$i] = implode($section);
        }
    }
    else {
        $kanji_lines = implode($kanji_lines);
    }

    // Step 5 - Remove all whitespace
    if (sizeof($kanji_sections) > 0) {
        foreach ($kanji_sections as $i=>$section) {
            $kanji_sections[$i] = preg_replace('/\s/u', '', $section);
        }
    }
    else {
        $kanji_lines = preg_replace('/\s/u', '', $kanji_lines);
    }

    // Step 6 - Only keep the sections we want ...
    if (sizeof($kanji_sections) > 0) {
        $first_section_found = false;
        $last_section_found = false;
        foreach ($kanji_sections as $section_title=>$kanji_section) {
            if ($section_title == $s["first_section"]) {
                $first_section_found = true;
            }
            if ($section_title == $s["last_section"]) {
                $last_section_found = true;
                continue; // always include the last_section
            }
            if (!$first_section_found || $last_section_found) {
                unset($kanji_sections[$section_title]);
            }
        }
    }
    // ... OR only keep the kanji we want.
    else {

        $first_kanji = $s["first_kanji"] - 1;
        $last_kanji = $s["last_kanji"];

        $kanji_lines = mb_substr($kanji_lines, $first_kanji, $last_kanji - $first_kanji);
    }

    // Upgrade lines to sections if necessary
    if (sizeof($kanji_sections) == 0) {
        $kanji_sections[""] = $kanji_lines;
        $s["show_section_titles"] = 0;
    }

    return $kanji_sections;
}

function collapse_sections($kanji_sections) {
    $collapsed_sections = "";
    foreach ($kanji_sections as $kanji_section) {
        $collapsed_sections .= $kanji_section;
    }
    return [
        ""=>$collapsed_sections,
    ];
}