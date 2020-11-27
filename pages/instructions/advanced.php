<!--<style>
.ui-accordion .ui-accordion-header {
    display: block;
    cursor: pointer;
    position: relative;
    margin: 2px 0 0 0;
    padding: .5em .5em .5em .7em;
    font-size: 100%;
}
.ui-accordion .ui-accordion-content {
    padding: 1em 2.2em;
    border-top: 0;
    overflow: auto;
}
</style>-->

<h2>Advanced Settings and Tutorials</h2>
<p>This section is <strong>under construction</strong>, and you can help by suggesting cool new ideas on the <a href="<?=COMMUNITY_TOPIC_URL?>" target="_blank">WaniKani Community Topic</a>.</p>
<div id="accordion">
<!--
    <h3>Highlight Recently Upgraded Kanji</h3>
    <div>

        <p>If you would like to highlight any kanji that were upgraded recently to a new SRS stage, you can set that up here.</p>
        <p>A value of 0 for the "n Days Ago" column disables highlighting for that stage, and any other value will enable highlighting for that stage.  Decimal numbers are allowed, for example 1.5 would be for one and a half days.</p>
        <p><strong>Example</strong>: Type '3' for Apprentice in the table below, and any kanji that were newly brought to Apprentice stage within the last 3 days will be colored by the specified accent color.</p>
        <p><strong>Note</strong>: Apprentice and Guru kanji that are answered correctly without advancing to the next SRS stage (i.e. without going from Apprentice->Guru or Guru->Master) will not be highlighted.</p>

        <table style="margin: 0 auto;">
            <tr>
                <th>Level</th>
                <th>Accent Color</th>
                <th>n Days Ago</th>
            </tr>
            <tr>
                <td style="text-align: center;">Apprentice</td>
                <td style="padding: 0 18px 0 15px;">
                    <label>
                        <input type="text" id="color-c_apprentice_accent" class="colorpicker" name="c_apprentice_accent" value="<?=$settings["c_apprentice_accent"]?>" />
                        <a class="accent-reset-button">Reset</a>
                    </label>
                </td>
                <td>
                    <label>
                    <input type="number" style="width: 100px;" step="any" name="accent_time_apprentice" value="<?=$settings["accent_time_apprentice"]?>" />
                    </label>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">Guru</td>
                <td style="padding: 0 18px 0 15px;">
                    <label>
                        <input type="text" id="color-c_guru_accent" class="colorpicker" name="c_guru_accent" value="<?=$settings["c_guru_accent"]?>" />
                        <a class="accent-reset-button">Reset</a>
                    </label>
                </td>
                <td>
                    <label>
                    <input type="number" style="width: 100px;" step="any" name="accent_time_guru" value="<?=$settings["accent_time_guru"]?>" />
                    </label>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">Master</td>
                <td style="padding: 0 18px 0 15px;">
                    <label>
                        <input type="text" id="color-c_master_accent" class="colorpicker" name="c_master_accent" value="<?=$settings["c_master_accent"]?>" />
                        <a class="accent-reset-button">Reset</a>
                    </label>
                </td>
                <td>
                    <label>
                    <input type="number" style="width: 100px;" step="any" name="accent_time_master" value="<?=$settings["accent_time_master"]?>" />
                    </label>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">Enlightened</td>
                <td style="padding: 0 18px 0 15px;">
                    <label>
                        <input type="text" id="color-c_enlightened_accent" class="colorpicker" name="c_enlightened_accent" value="<?=$settings["c_enlightened_accent"]?>" />
                        <a class="accent-reset-button">Reset</a>
                    </label>
                </td>
                <td>
                    <label>
                    <input type="number" style="width: 100px;" step="any" name="accent_time_enlightened" value="<?=$settings["accent_time_enlightened"]?>" />
                    </label>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">Burned</td>
                <td style="padding: 0 18px 0 15px;">
                    <label>
                        <input type="text" id="color-c_burned_accent" class="colorpicker" name="c_burned_accent" value="<?=$settings["c_burned_accent"]?>" />
                        <a class="accent-reset-button">Reset</a>
                    </label>
                </td>
                <td>
                    <label>
                    <input type="number" style="width: 100px;" step="any" name="accent_time_burned" value="<?=$settings["accent_time_burned"]?>" />
                    </label>
                </td>
            </tr>
        </table>

        <p style="text-align:center;">Click Reset to change the color to your regular wallpaper color from the top of the page.</p>

        <p style="text-align: center;">Remember to hit <button class="">Save Settings</button>!  (This will save all settings)</p>

        <style>
        .accent-reset-button {
            cursor:pointer;
        }
        </style>

        <script>
        $(".accent-reset-button").click(function(e){
            $input = $(e.target).closest("td").find("input");
            console.log($input.val());
            //$("#color-"+key).spectrum("set",scheme[key]);
            $input.spectrum("set",$("#"+$input.attr("id").replace("_accent","")).val());
        });
        </script>
    </div>

    <h3>Animated Wallpaper with Rainmeter</h3>
    <div>
        <p>
            I'm working on this -- come back later
        </p>
    </div>
    -->

    <h3>Update Lock Screen on Windows 10</h3>
    <div>
        <p><strong>Summary</strong>: This script works by replacing the lock screen image file located at C:\Windows\Web\Screen\img100.jpg.  It's run through PowerShell.</p>
        <h4>1. Disable Windows Spotlight.</h4>
        <p>Right-click on your desktop, and click on "Personalize".
            In the Personalization settings, select "Lock Screen" at the left.
            Then, set the Background option to Picture.</p>
        <h4>2. Enable running PowerShell scripts from batch files.</h4>
        <p>Click on the Windows button in the lower-left corner.</p>
        <img src="<?=ROOT_URL?>/public/images/help/advanced-lock-screen/2-1.png" />
        <p>Type "powershell" and then Right-Click on <em>Windows PowerShell</em> and click <strong>Run as administrator</strong></p>
        <img src="<?=ROOT_URL?>/public/images/help/advanced-lock-screen/2-2.png" />
        <p>Type the following, and then hit enter.</p>
        <pre>Set-ExecutionPolicy Unrestricted</pre>
        <p>If you're asked to answer Yes/No/etc., choose "Yes to All".</p>
        <img src="<?=ROOT_URL?>/public/images/help/advanced-lock-screen/2-3.png" />
        <h4>3. Create a PowerShell script file that updates the lock screen image.</h4>
        Similar to how you made a .bat file, make a .ps1 file with the following code, and name the file lockscreen.ps1 (that's P-S-One, not P-S-L).
        <pre style="border: 1px solid white; width: 100%; box-sizing: border-box; padding: 22px 26px; clear:both; white-space: pre-wrap;">Start-Process -filePath "$env:systemRoot\system32\takeown.exe" -ArgumentList "/F `"$env:programData\Microsoft\Windows\SystemData`" /R /A /D Y" -NoNewWindow -Wait
Start-Process -filePath "$env:systemRoot\system32\icacls.exe" -ArgumentList "`"$env:programData\Microsoft\Windows\SystemData`" /grant Administrators:(OI)(CI)F /T" -NoNewWindow -Wait
Start-Process -filePath "$env:systemRoot\system32\icacls.exe" -ArgumentList "`"$env:programData\Microsoft\Windows\SystemData\S-1-5-18\ReadOnly`" /reset /T" -NoNewWindow -Wait
Remove-Item -Path "$env:programData\Microsoft\Windows\SystemData\S-1-5-18\ReadOnly\LockScreen_Z\*" -Force
Start-Process -filePath "$env:systemRoot\system32\takeown.exe" -ArgumentList "/F `"$env:systemRoot\Web\Screen`" /R /A /D Y" -NoNewWindow -Wait
Start-Process -filePath "$env:systemRoot\system32\icacls.exe" -ArgumentList "`"$env:systemRoot\Web\Screen`" /grant Administrators:(OI)(CI)F /T" -NoNewWindow -Wait
Start-Process -filePath "$env:systemRoot\system32\icacls.exe" -ArgumentList "`"$env:systemRoot\Web\Screen`" /reset /T" -NoNewWindow -Wait
Copy-Item -Path "$env:systemRoot\Web\Screen\img100.jpg" -Destination "$env:systemRoot\Web\Screen\img200.jpg" -Force
Copy-Item -Path "wallpaper.png" -Destination "$env:systemRoot\Web\Screen\img100.jpg" -Force</pre>
        <h4>4. Edit the update.bat file to run the PowerShell script.</h4>
        <p>Update the bottom lines of your update.bat batch file so it looks like this:</p>
        <pre style="border: 1px solid white; width: 100%; box-sizing: border-box; padding: 22px 26px; clear:both; white-space: pre-wrap;">@echo Setting wallpaper
@WallpaperChanger.exe wallpaper.png
@echo Setting lock screen image
@powershell -File "lockscreen.ps1"
@echo Done</pre>

    </div>

</div>

<p>More sections will be added as people suggest them!</p>