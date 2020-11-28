<style>
.ui-accordion>h3 {
    display: block;
    cursor: pointer;
    position: relative;
    margin: 2px 0 0 0;
    padding: .5em .5em .5em .7em;
    font-size: 100%;
}
.ui-accordion>div {
    padding: 1em 2.2em;
    border-top: 0;
    overflow: auto;
}
.ui-accordion>div img {
    max-width: 100%;
}
</style>

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
        <p><strong>Summary</strong>: This script works by replacing the lock screen image file located at C:\Windows\Web\Screen\img100.jpg.</p>
        <h4>1. Disable Windows Spotlight.</h4>
        <p>Right-click on your desktop, and click on "Personalize".
            In the Personalization settings, select "Lock Screen" at the left.
            Then, set the Background option to Picture.</p>
        <h4>2. Add this code to the bottom of your update.bat file</h4>
        <pre class="code"
>:: Update lock screen
   @echo Updating lock screen
   @takeown /F C:\Windows\Web\Screen /R
   @icacls C:\Windows\Web\Screen /grant:r Administrators:F /T
   @icacls C:\Windows\Web\Screen /setowner Administrators /T
   @del /F /Q C:\Windows\Web\Screen\*.*
   @copy /Y "%~dp0\wallpaper.png" C:\Windows\Web\Screen\img100.jpg
   @takeown /F C:\ProgramData\Microsoft\Windows\SystemData
   @icacls C:\ProgramData\Microsoft\Windows\SystemData /grant:r Administrators:F
   @icacls C:\ProgramData\Microsoft\Windows\SystemData /setowner Administrators
   @takeown /F C:\ProgramData\Microsoft\Windows\SystemData\S-1-5-18 /R /D Y
   @icacls C:\ProgramData\Microsoft\Windows\SystemData\S-1-5-18 /grant:r Administrators:(OI)(CI)F
   @icacls C:\ProgramData\Microsoft\Windows\SystemData\S-1-5-18 /grant:r SYSTEM:(OI)(CI)F
   @icacls C:\ProgramData\Microsoft\Windows\SystemData\S-1-5-18\* /inheritance:e /T
   @icacls C:\ProgramData\Microsoft\Windows\SystemData\S-1-5-18 /setowner Administrators /T
   @rmdir /s /q C:\ProgramData\Microsoft\Windows\SystemData\S-1-5-18\ReadOnly\
:: End of Lock Screen code</pre>
        <h4>3. Always Run as Administrator</h4>
        <p>Because the lock screen image is technically a system file, this code must be run with elevated privileges or else it won't work.
            If you are running this script from a scheduled task, make sure to enable "Run with highest privileges".
            For all other use cases, see the instructions for "Always Run 'update.bat' as an Administrator" below.</p>
    </div>

    <h3>Always Run 'update.bat' as an Administrator</h3>
    <div>
        <h4>1. Add this code to the <strong style="color: yellow">TOP</strong> of your update.bat file</h4>
        <pre class="code">
:: Always run as admin
   @echo off
   setlocal DisableDelayedExpansion
   set cmdInvoke=1
   set winSysFolder=System32
   set "batchPath=%~0"
   for %%k in (%0) do set batchName=%%~nk
   set "vbsGetPrivileges=%temp%\OEgetPriv_%batchName%.vbs"
   setlocal EnableDelayedExpansion
   NET FILE 1>NUL 2>NUL
   if '%errorlevel%' == '0' ( goto gotPrivileges ) else ( goto getPrivileges )
   :getPrivileges
   if '%1'=='ELEV' (echo ELEV & shift /1 & goto gotPrivileges)
   echo Set UAC = CreateObject^("Shell.Application"^) > "%vbsGetPrivileges%"
   echo args = "ELEV " >> "%vbsGetPrivileges%"
   echo For Each strArg in WScript.Arguments >> "%vbsGetPrivileges%"
   echo args = args ^& strArg ^& " "  >> "%vbsGetPrivileges%"
   echo Next >> "%vbsGetPrivileges%"
   if '%cmdInvoke%'=='1' goto InvokeCmd
   echo UAC.ShellExecute "!batchPath!", args, "", "runas", 1 >> "%vbsGetPrivileges%"
   goto ExecElevation
   :InvokeCmd
   echo args = "/c """ + "!batchPath!" + """ " + args >> "%vbsGetPrivileges%"
   echo UAC.ShellExecute "%SystemRoot%\%winSysFolder%\cmd.exe", args, "", "runas", 1 >> "%vbsGetPrivileges%"
   :ExecElevation
   "%SystemRoot%\%winSysFolder%\WScript.exe" "%vbsGetPrivileges%" %*
   exit /B
   :gotPrivileges
   setlocal & cd /d %~dp0
   if '%1'=='ELEV' (del "%vbsGetPrivileges%" 1>nul 2>nul  &  shift /1)
   setlocal DisableDelayedExpansion
:: End of Always run as admin
        </pre>
    </div>

</div>

<p>More sections will be added as people suggest them!</p>