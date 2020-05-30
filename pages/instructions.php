<h1 style="margin-top: 80px;">Wallpaper Instructions</h1>

<p>If you just want to download your wallpaper, use the <strong>Manual Download</strong>.</p>
<p>If you want to be extra fancy, this tool also provides methods to update your WaniKani wallpaper automatically for <strong>Windows</strong> and <strong>Mac OS X</strong> and <strong>multiple monitors with different wallpapers or resolutions</strong>.</p>

<div id="tabs">
    <ul>
        <li><a href="#tab-manual-download">Manual Download</a></li>
        <li><a href="#tab-windows">Windows</a></li>
        <li><a href="#tab-mac">Mac</a></li>
        <li><a href="#tab-multiple-monitors">Multiple Monitors</a></li>
        <li><a href="#tab-other-information">Other Information</a></li>
        <!--Not ready--><li><a href="#tab-advanced" style="display: none;">Advanced</a></li>
    </ul>
    <div id="tab-manual-download">
		<?php include PAGES_DIR."/instructions/manual-download.php"; ?>
    </div>
    <div id="tab-windows">
		<?php include PAGES_DIR."/instructions/windows.php"; ?>
    </div>
    <div id="tab-mac">
		<?php include PAGES_DIR."/instructions/mac.php"; ?>
    </div>
    <div id="tab-multiple-monitors">
		<?php include PAGES_DIR."/instructions/multiple-monitors.php"; ?>
    </div>
    <div id="tab-other-information">
		<?php include PAGES_DIR."/instructions/other-information.php"; ?>
    </div>
    <div id="tab-advanced">
		<?php include PAGES_DIR."/instructions/advanced.php"; ?>
    </div>
</div>

<script>
$(()=>{
    $("#tabs").tabs();
    $("#accordion").accordion({
        heightStyle: "content",
        active: false,
        collapsible: true,
    });
});
</script>