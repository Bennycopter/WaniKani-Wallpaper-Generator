<h2 id="windows">Windows Instructions</h2>

<p>1. <strong>Create a Folder</strong></p>
<p>Create a folder to store your Wallpaper files in.  A good location for this is your desktop or your user folder (located at C:\Users\{username}\).</p>
<p>Eventually, you will have three items in this folder:<br />&nbsp;&nbsp;1) a batch file,<br />&nbsp;&nbsp;2) a small exe, and<br />&nbsp;&nbsp;3) the wallpaper itself.</p>
<p>You can name the folder whatever you want, just keep it tidy.<sup><a href="https://konmari.com/" target="_blank">*</a></sup></p>

<p>2. <strong>Download WallpaperChanger</strong></p>
<p>Download the <strong>WallpaperChanger.exe</strong> program from <a href="https://github.com/philhansen/WallpaperChanger/releases/" target="_blank">this link</a>.  Save it to the folder you created in step 1.</p>
<img src="<?=ROOT_URL?>public/images/help/run-notepad.jpg" style="width: 340px; padding-left: 30px; float:right;" alt="Run Notepad" />

<p>3. <strong>Create a Batch File</strong></p>
<p>Open up Notepad.  One way to open Notepad is to do Windows+R (hold down the Windows key on your keyboard and tap the "R" key once) and type "notepad", as shown on the right.</p>
<p>Copy and paste the text below into Notepad.</p>
<pre style="border: 1px solid white; width: 100%; box-sizing: border-box; padding: 22px 26px; clear:both; white-space: pre-wrap;">@cd /d %~dp0
@echo Downloading wallpaper
@curl -s -A "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36" -o wallpaper.png "<?=SITE_URL?>?k=<?=$api_key?>&d=<?=$device?>"
@echo Setting wallpaper
@WallpaperChanger.exe wallpaper.png
@echo Done</pre>

<p>Now save this text to the folder you created in step 1.  Call the file <strong>update.bat</strong>, and make sure to change the"Save as type" to "All Files (*.*)", as shown below.</p>

<div style="text-align: center;">
    <img src="<?=ROOT_URL?>public/images/help/save-bat.jpg" alt="Save Bat" />
</div>

<p>At this point, you can double-click the batch file, and your wallpaper should update.</p>
<p>If you get an error, like "curl is not recognized as an internal or external command," then install curl in step 3.1.</p>
<p>If it displays some other error, make sure that you placed WallpaperChanger and the batch file in the same folder, and make sure you copied and saved the batch file properly.</p>
<p>If the Command Prompt window disappears before you can read the error message, add the word "pause" without quotes on a new line at the bottom of the batch file.</p>

<p>3.1 <strong>Install curl</strong></p>

<p>If you were able to run the batch file from step 3 with no issues, then skip to step 4.</p>
<p>
    Download curl <a href="https://curl.haxx.se/windows/dl-<?=CURL_FOR_WINDOWS_VERSION?>/curl-<?=CURL_FOR_WINDOWS_VERSION?>-win32-mingw.zip" target="_blank">here (direct link)</a> or <a href="https://curl.haxx.se/windows/">here (backup link)</a> and extract the "curl-<?=CURL_FOR_WINDOWS_VERSION?>-win32-mingw" folder to the folder you created in step 1.
    <span style="color: grey">(If the direct link is broken, please <span id="email-no-bots">email me</span>)</span>
    <script>
    $(()=>{
        let email = 'ben' + '@' + 'natural20design' + '.' + 'com';
        $("#email-no-bots").text("email me at "+email);
    })
    </script>
</p>
<p>Open up the batch file in Notepad again, and change the batch file to the following:</p>
<pre style="border: 1px solid white; width: 100%; box-sizing: border-box; padding: 22px 26px; clear:both; white-space: pre-wrap;">@cd /d %~dp0
@echo Downloading wallpaper
@curl-<?=CURL_FOR_WINDOWS_VERSION?>-win32-mingw\bin\curl -s -A "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36" -o wallpaper.png "<?=SITE_URL?>?k=<?=$api_key?>&d=<?=$device?>"
@echo Setting wallpaper
@WallpaperChanger.exe wallpaper.png
@echo Done</pre>

<p>It should work this time.  If it doesn't, <a href="<?=COMMUNITY_TOPIC_URL?>" target="_blank">let me know</a>.</p>

<p>4. <strong>Add it to the context menu</strong></p>

<div style="text-align: center;">
    <img src="<?=ROOT_URL?>public/images/help/context-menu.jpeg" alt="Context Menu" />
</div>

<p>To do this, we need to edit the registry.</p>

<p><strong style="color: red; font-weight: bold;">Please proceed very cautiously and at your own risk. Editing the computer registry is serious business, and deleting and/or modifying items you shouldn’t can result in system crashes, data corruption, and more. I am not responsible for any losses caused by your mistakes here in this step.</strong></p>

<div>
    <img src="<?=ROOT_URL?>public/images/help/open-regedit.png" style="padding-left: 40px; float:right;" alt="Open RegEdit" />
    <p>Open up Registry Editor. Do Windows+R (hold down the Windows key on the keyboard and tap the R key once), and type “regedit” and hit OK.</p>
</div>

<div style="clear: both; padding-top: 20px;">
    <img src="<?=ROOT_URL?>public/images/help/new-key.png" style="padding-left: 40px; float: right" alt="New Key" />
    <p>In the left panel, open up HKEY_CLASSES_ROOT, scroll down until you see DesktopBackground and open it, and then open Shell, and create a new key under it by right-clicking on Shell and choosing New->Key.</p>
</div>

<div style="clear: both; padding-top: 20px;">
    <img src="<?=ROOT_URL?>public/images/help/new-subkey.png" style="padding-left: 40px; float: right;" alt="New Subkey" />
    <p>Name the new Key as “Refresh WaniKani Wallpaper” or something similar (this will be the text that appears when you right-click on your desktop).  Right-click this new Key and create another Key underneath it exactly called “command”. After that, select the new command Key that you just created.</p>
</div>

<div style="clear: both; padding-top: 20px;">
    <img src="<?=ROOT_URL?>public/images/help/command-enter.png" style="padding-left: 40px; float: right" alt="Command entry" />
    <p>Now, in the right panel, double-click on “(Default)”, and type in the path to the batch file that you created in step 3, surrounded in double-quotes "like this".</p>
</div>

<p style="clear: both; padding-top: 20px;">5. <strong>Go burn some turtles</strong></p>

<p>That's it! To test that it all works, right-click on your desktop wallpaper, and you should have a new item labeled “Refresh WaniKani Wallpaper”.</p>
<p>Thank you for checking this out!  If it helps you, <a href="<?=COMMUNITY_TOPIC_URL?>" target="_blank">let me know</a> :)</p>