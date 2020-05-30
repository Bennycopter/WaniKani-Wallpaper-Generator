<?php defined("INDEX") or die;


///// Helper Functions /////////////////////////////////////////////////////////////////////////////////////////////////

function font_file_exists($name) {
    return file_exists(PRESETS_DIR."/fonts/$name.txt");
}
function color_scheme_exists($name) {
    return file_exists(PRESETS_DIR."/color-schemes/$name.txt");
}
function screen_preset_exists($name) {
    return file_exists(PRESETS_DIR."/screen-presets/$name.txt");
}
function kanji_set_exists($name) {
    return file_exists(PRESETS_DIR."/orders/$name.txt");
}

function load_kanji_order_file($kanji_set) {
    return file_get_contents(PRESETS_DIR."/orders/$kanji_set.txt");
}

///// Preset Data //////////////////////////////////////////////////////////////////////////////////////////////////////

function load_color_scheme_presets() {
    // Define the order
    $color_schemes = [
        "Default" => [],
        "Default Bright" => [],
        "Reverse Default" => [],
        "Inverted Default" => [],
        "Yellow Sunset" => [],
        "Yellow Sunrise" => [],
        "White Sunset" => [],
        "White Sunrise" => [],
        "Fruit Smoothie" => [],
    ];

    $dir = PRESETS_DIR . "/color-schemes";

    // Load the color scheme data
    $files = array_diff(scandir($dir),[".",".."]);
    foreach ($files as $file) {
        $scheme_title = str_replace(".txt","",$file);
        $scheme_text = file_get_contents("$dir/$file");
        $scheme_lines = explode(PHP_EOL, $scheme_text);
        foreach ($scheme_lines as $scheme_line) {
            $scheme_line_parts = explode(":", $scheme_line);
            $color_schemes[$scheme_title][trim($scheme_line_parts[0])] = strtoupper(trim($scheme_line_parts[1]));
        }
    }

    // Remove empty data (in case file was missing)
    foreach ($color_schemes as $k => $v)
        if (!sizeof($v))
            unset($color_schemes[$k]);

    return $color_schemes;
}

function load_font_presets() {
    // Define the order
    $fonts = [
        // Uncomment these lines to set your own order
        // Since this is effectively blank, they will be loaded alphabetically
        // "Appli Mincho" => [],
        // "HonyaJi Re" => [],
        // "Komorebi Gothic" => [],
        // "Motoya Kusugi Maru" => [],
        // "Otsutome" => [],
    ];

    $dir = PRESETS_DIR . "/fonts";

    // Load the font data
    $files = array_diff(scandir($dir), [".",".."]);
    foreach ($files as $file) {
        $font_title = str_replace(".txt","", $file);
        $font_text = file_get_contents("$dir/$file");
        $font_lines = explode(PHP_EOL, $font_text);
        $fonts[$font_title] = [
            "file" => $font_lines[0],
            "source" => $font_lines[1],
            "realpath" => realpath(ASSETS_DIR."/fonts/" . $font_lines[0]),
        ];
    }

    // Remove empty data (in case file was missing)
    foreach ($fonts as $k=>$v)
        if (!sizeof($v))
            unset($fonts[$k]);

    return $fonts;
}

function load_screen_presets() {
    // Define the order
    $screen_presets = [
        "Default" => [],
        "Windows 10 Widescreen Desktop (1920x1080)" => [],
        "Windows 10 Laptop (1366x768)" => [],
        "Windows 10 Standard Desktop (1280x1024)" => [],
        "Apple MacBook Pro (13-inch)" => [],
        "iMac (27-inch)" => [],
        "iMac (21.5-inch)" => [],
        "iPhone X" => [],
        "iPhone 8 Plus" => [],
        "iPhone 8" => [],
        "iPhone 7 Plus" => [],
        "iPhone 6s Plus" => [],
        "iPhone 6 Plus" => [],
        "iPhone 7" => [],
        "iPhone 6s" => [],
        "iPhone 6" => [],
        "iPhone SE" => [],
        "iPad Pro 12.9-inch" => [],
        "iPad Pro 10.5-inch" => [],
        "iPad Pro 9.7-inch" => [],
        "iPad Air 2" => [],
        "iPad Mini 4" => [],
    ];

    $dir = PRESETS_DIR . "/screen-presets";

    // Load the screen preset data
    $files = array_diff(scandir($dir), [".",".."]);
    foreach ($files as $file) {
        $title = str_replace(".txt","", $file);
        $file_text = file_get_contents("$dir/$file");
        $file_lines = explode(PHP_EOL, $file_text);
        foreach ($file_lines as $file_line) {
            if (trim($file_line) == "") continue;
            $file_line_parts = explode(":", $file_line);
            $screen_presets[$title][trim($file_line_parts[0])] = intval(trim($file_line_parts[1]));
        }
    }

    // Remove empty data (in case file was missing)
    foreach ($screen_presets as $k => $v)
        if (!sizeof($v))
            unset($screen_presets[$k]);

    return $screen_presets;
}

function load_kanji_set_presets() {

    $wanikani_sections = [
        "快 Pleasant","苦 Painful","死 Death",
        "地獄 Hell","天国 Paradise","現実 Reality"
    ];
    $wanikani_description = "This list is pulled from WaniKani itself, and
                             it has been updated on May 30, 2020.";

    return [
        ///// Default /////
        "default" => [
            "title"=>"Default",
            "description"=>"This kanji set is a variation on the Heisig set:
                            all missing WaniKani kanji have been added, and
                            all non-WaniKani kanji have been removed.",
            "num_kanji"=>1993,
        ],

        ///// Frequency /////
        "frequency" => [
            "title"=>"Frequency of use",
            "description"=>"The kanji in this list are sorted by their frequency of use.
                            The first 500 make up roughly 80% of all kanji that is written.",
            "num_kanji" => 2501,
        ],

        ///// Heisig /////
        "heisig" => [
            "title"=>"Complete Heisig",
            "description"=>"The kanji in this list are ordered logically and generally
                            next to other kanji that share a same radicals.
                            It is from Heisig's Remembering the Kanji Vol. 1, 6th edition.",
            "num_kanji" => 2200,
            "more_info"=>
                "https://en.wikipedia.org/wiki/Remembering_the_Kanji_and_Remembering_the_Hanzi",
        ],

        ///// WaniKani /////
        "wanikani" => [
            "title"=>"WaniKani Levels",
            "num_levels" => 60,
            "num_kanji" => 2048,
            "sections" => /* Level 1 - Level 60 */
                          array_map(
                              function($v){return"Level $v";},
                              range(1,60)
                          ),
            "has_levels" => true,
            "description" => "$wanikani_description
                              <strong>Only 10 levels at a time is
                              recommended with section headers!</strong>",
            "subsets" => [
                "ordered-by-heisig" => [
                    "title"=>"Sorted by Heisig order",
                ],
                "ordered-by-frequency" => [
                    "title"=>"Sorted by frequency of use",
                ],
                "sections" => [
                    "title"=>"Grouped in sections",
                    "has_levels" => false,
                    "description" => $wanikani_description,
                    "sections" => $wanikani_sections,
                ],
                "sections-ordered-by-heisig" => [
                    "title"=>"Grouped in sections, sorted by Heisig order",
                    "has_levels" => false,
                    "description" => $wanikani_description,
                    "sections" => $wanikani_sections,
                ],
                "sections-ordered-by-frequency" => [
                    "title"=>"Grouped in sections, sorted by frequency of use",
                    "has_levels" => false,
                    "description" => $wanikani_description,
                    "sections" => $wanikani_sections,
                ],
            ],
        ],

        ///// JLPT /////
        "jlpt" => [
            "title"=>"JLPT Levels",
            "description" => "There are five sections of the Japanese-Language
                              Proficiency Test (JLPT) from N5 to N1.  This set
                              has these sections.",
            "more_info" =>
                "https://en.wikipedia.org/wiki/Japanese-Language_Proficiency_Test",
            "sections" => /* JLPT N5 - JLPT N1 */
                          array_map(function($v){return"JLPT N$v";},range(5,1)),
            "num_kanji" => 2220,
            "subsets"=> [
                "ordered-by-wanikani" => [
                    "title"=>"Sorted by WaniKani order",
                ],
                "ordered-by-heisig" => [
                    "title"=>"Sorted by Heisig order",
                ],
                "ordered-by-frequency" => [
                    "title"=>"Sorted by frequency of use",
                ],
            ],
        ],

        ///// Kanken /////
        "kanken" => [
            "title"=>"Kanken Levels",
            "description" => "Also known as Kanji Kentei, Kanken is a proficiency test
                              originally developed for native Japanese speakers ranging
                              from level 10 to level 1.  About 80% of people pass
                              levels 10 through 7, while only 400 people a year
                              pass level 1.",
            "more_info" => "https://en.wikipedia.org/wiki/Kanji_Kentei",
            "sections" => array_merge(
                            /* Kanken Level 10 - Kanken Level 3 */
                            array_map(
                                function($v){return"Kanken Level $v";},
                                range(10,3)
                            ),
                            /* Kanken Level 2.5 - Kanken Level 1 */
                            array_map(
                                function($v){return"Kanken Level $v";},
                                range(2.5,1, 0.5)
                            )
                          ),
            "num_kanji" => 2219,
            "subsets"=> [
                "ordered-by-wanikani" => [
                    "title"=>"Sorted by WaniKani order"
                ],
                "ordered-by-heisig" => [
                    "title"=>"Sorted by Heisig order"
                ],
                "ordered-by-frequency" => [
                    "title"=>"Sorted by frequency of use"
                ],
            ],
        ],

        ///// Jouyou /////
        "jouyou" => [
            "title"=>"Jōyō Kanji",
            "description" => "This set of kanji is officially managed
                              by the Japanese Ministry of Education.",
            "more_info" => "https://en.wikipedia.org/wiki/Jōyō_kanji",
            "sections" => [
                "1st Grade", "2nd Grade", "3rd Grade",
                "4th Grade", "5th Grade", "6th Grade",
                "Secondary School"],
            "num_kanji" => 2136,
            "subsets" => [
                "ordered-by-wanikani" => [
                    "title"=>"Sorted by WaniKani order"
                ],
                "ordered-by-heisig" => [
                    "title"=>"Sorted by Heisig order"
                ],
                "ordered-by-frequency" => [
                    "title"=>"Sorted by frequency of use"
                ],
            ],
        ],

        ///// Kyouiku /////
        "kyouiku" => [
            "title"=>"Kyōiku Kanji",
            "description" => "This is a subset of Jōyō kanji, holding the 1,026
                              kanji that Japanese schoolchildren should learn
                              for each year of primary school.",
            "more_info" => "https://en.wikipedia.org/wiki/Ky%C5%8Diku_kanji",
            "sections" => [
                "1st Grade", "2nd Grade", "3rd Grade",
                "4th Grade", "5th Grade", "6th Grade",
                "Prefectures"],
            "num_kanji" => 1026,
            "subsets" => [
                "ordered-by-wanikani" => [
                    "title"=>"Sorted by WaniKani order"
                ],
                "ordered-by-heisig" => [
                    "title"=>"Sorted by Heisig order"
                ],
                "ordered-by-frequency" => [
                    "title"=>"Sorted by frequency of use"
                ],
            ],
        ]
    ];
}