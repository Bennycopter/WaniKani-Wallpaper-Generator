<h2 id="mac">Mac OS X Instructions</h2>

<p>1. <strong>Create a Folder</strong></p>
<p>Create a folder to store your Wallpaper files in.  A good location for this is your desktop or your Documents folder.</p>
<p>See <a href="https://support.apple.com/kb/PH25633" target="_blank">this link</a> for how to create a folder.</p>
<p>You can name the folder whatever you want, just keep it tidy.<sup><a href="https://konmari.com/" target="_blank">*</a></sup></p>

<p>2. <strong>Create a Command File</strong></p>

<p>Open TextEdit from your Applications folder. To get to the Application folder, click on your desktop, then click Go on the menu bar, and choose Applications.</p>
<p>With TextEdit open, from the menu bar, click TextEdit->Preferences. In the New Document tab, make sure that the Format is set to "Plain text".</p>

<p>Copy and paste the following code into TextEdit.</p>

<pre style="border: 1px solid white; width: 100%; box-sizing: border-box; padding: 22px 26px; clear:both; white-space: pre-wrap;">#!/bin/bash
cd "$(dirname "$0")"
curl -s -A "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36" -o "wallpaper.png" "<?=SITE_URL?>?k=<?=$api_key?>&d=<?=$device?>"
osascript -e "tell application \"System Events\" to tell every desktop to set picture to \"$(dirname "$0")/wallpaper.png\""</pre>

<p>save the file so that it ends with ".command", for example "WaniKani Wallpaper.command".  Save it to the folder created in step 1.</p>

<p>To test that it works, double-click on the code file.  If you get an error such as "WaniKani Wallpaper.command could not be executed because you do not have appropriate access privileges.", then follow step 2.1.  If you don't get this error and your wallpaper updated, then just skip to step 3.</p>

<p>2.1 <strong>Make the Command File executable</strong></p>
<p>Open Terminal, and run the following command, except with the path to your code file. If you created your code file on the desktop, then the path will be something like “~/Desktop/WaniKani Wallpaper.command”.</p>
<p>chmod a+x "~/Desktop/WaniKani Wallpaper.command"</p>
<p>To test that the code file will execute now, double-click it, and your wallpaper should change.</p>

<p>3. <strong>Create a link to the Command File on your desktop</strong></p>
<p>Click on your code file.  From the File menu, select Make Alias.  The alias (aka a "shortcut" in Windows) will appear in the same folder.  Click and drag the alias to your desktop.</p>
<p>Now, any time you want to update your wallpaper, just double-click the alias on your desktop.</p>

<p>4. <strong>Go burn some turtles</strong></p>

<p>That's it! To test that it all works, right-click on your desktop wallpaper, and you should have a new item labeled “Refresh WaniKani Wallpaper”.</p>
<p>Thank you for checking this out!  If it helps you, <a href="<?=COMMUNITY_TOPIC_URL?>" target="_blank">let me know</a> :)</p>