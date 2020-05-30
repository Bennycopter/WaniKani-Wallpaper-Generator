<h2 id="multiple-monitors">Multiple Monitors with Different Wallpapers or Resolutions</h2>

<p>If you have multiple monitors and want a different wallpaper on each, set up a different device on this site for each monitor and make a custom script for yourself (see the examples below).  Make sure to adjust the &d=1 and &d=2 as necessary to make the device numbers.</p>
<p>You can change your device at the top of this page, or by clicking here: <a href="?logout&api-key=<?=$api_key?>">Change Device</a>.</p>
<p>These are just example scripts.  You will still have to follow the steps for Windows and Mac in the other tabs.</p>
<p><strong>Windows Example</strong></p>
<pre style="border: 1px solid white; width: 100%; box-sizing: border-box; padding: 22px 26px; clear:both; white-space: pre-wrap;">@cd /d %~dp0
@echo Downloading wallpapers
@curl -s -A "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36" -o wallpaper1.png "<?=SITE_URL?>?k=<?=$api_key?>&d=1"
@curl -s -A "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36" -o wallpaper2.png "<?=SITE_URL?>?k=<?=$api_key?>&d=2"
@echo Setting wallpapers
@WallpaperChanger.exe -m 0 wallpaper1.png
@timeout 1
@WallpaperChanger.exe -m 1 wallpaper2.png
@echo Done</pre>
<p><strong>Mac OS X Example</strong></p>
<pre style="border: 1px solid white; width: 100%; box-sizing: border-box; padding: 22px 26px; clear:both; white-space: pre-wrap;">#!/bin/bash
cd "$(dirname "$0")"
curl -s -A "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36" -o "wallpaper1.png" "<?=SITE_URL?>?k=<?=$api_key?>&d=1"
curl -s -A "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36" -o "wallpaper2.png" "<?=SITE_URL?>?k=<?=$api_key?>&d=2"
osascript -e "tell application \"System Events\" to tell desktop 1 to set picture to \"$(dirname "$0")/wallpaper1.png\""
osascript -e "tell application \"System Events\" to tell desktop 2 to set picture to \"$(dirname "$0")/wallpaper2.png\""</pre>

<p>You'll have to do some experimentation to see what works for you.  Feel free to post in the <a href="<?=COMMUNITY_TOPIC_URL?>" target="_blank">community topic</a> for help.  Good luck!</p>