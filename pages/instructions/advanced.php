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

        <!--<style>
        .accent-reset-button {
            cursor:pointer;
        }
        </style>-->

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

</div>

<p>More sections will be added as people suggest them!</p>