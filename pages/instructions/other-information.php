<h2>Other Information</h2>
<p><strong>WaniKani Community Topic</strong></p>
<p>For discussions and feature requests, please see the <a href="<?=COMMUNITY_TOPIC_URL?>" target="_blank">WaniKani Community Topic</a>.</p>

<p><strong>Credits</strong></p>
<p>This Wallpaper Generator was made by <a href="https://community.wanikani.com/u/Masayoshiro" target="_blank">Masayoshiro</a> (Natural 20 Design) for the <a href="https://community.wanikani.com/" target="_blank">WaniKani Community</a>.</p>
<p>Special thanks to <a href="https://community.wanikani.com/u/emucat" target="_blank">Emucat</a> for testing and debugging the Mac instructions!</p>
<p>This was inspired by the WaniKani Wallpaper Generator by <a href="https://community.wanikani.com/u/hexagenic" target="_blank">Hexagenic</a>.
    You can see Hexagenic's original post <a href="https://community.wanikani.com/t/wallpaper-generator/1540" target="_blank">here</a>.</p>
<p>Natural 20 Design is not affiliated with WaniKani or Hexagenic.</p>
<p><strong>Font Sources</strong></p>
<p>The fonts were grabbed from the following locations:</p>
<ul>
    <?php foreach ($fonts as $font_title=>$font) {
        print "<li><a href='$font[source]' target='_blank'>$font_title</a></li>\n";
    }?>
</ul>

<p><strong>Code Sources</strong></p>
<p>Color picker is from <a href="https://bgrins.github.io/spectrum/" target="_blank">https://bgrins.github.io/spectrum/</a>.</p>
<p>jQuery is from <a href="https://jquery.com/" target="_blank">https://jquery.com/</a>.</p>
<p>Random Color is from <a href="https://github.com/davidmerfield/randomColor/" target="_blank">https://github.com/davidmerfield/randomColor/</a></p>

<p><strong>Kanji Sets</strong></p>
The kanji sets and their sources can be found here:

<?php

print "<ul>";
$last_link = "";
foreach ($kanji_sets as $link=>$kanji_set) {
    if ($kanji_set["title"] != "Default") print "<br />";
    print "<li>";
    print "<a href=\"order.php?order=$link\" target=\"_blank\">".$kanji_set["title"]."</a>";
    print "</li>";

    if (isset($kanji_set["subsets"])) {
        print "<ul>";
        foreach ($kanji_set["subsets"] as $subset_link=>$subset) {
            print "<li>";
            print "<a href=\"order.php?order=$link-$subset_link\" target=\"_blank\">".$subset["title"]."</a>";
            print "</li>";
        }
        print "</ul>";
    }
}
print "</ul>";
?>

<p><strong>Contact</strong></p>
<p>Please use the <a href="<?=COMMUNITY_TOPIC_URL?>" target="_blank">WaniKani Community Topic</a> for communications about this app.
    <br />For everything else, you can <a href="http://www.natural20design.com/" target="_blank">send me an email</a>.</p>