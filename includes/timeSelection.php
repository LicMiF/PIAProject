<div class="availability-container">
<h2>Odaberite vreme dostupnosti</h2>
<form action="<?=$_SERVER['PHP_SELF']?>" method='post'>
    <div class="time-selection">
        <div class='time-selection-header'>
            <h3>Od:</h3>
        </div>
        <div class="selecting part">
            <select id="hourSelect" name='fromHour'>
                <?php
                    for ($i = 1; $i <= 12; $i++) {
                        $val=$i;
                        if($i<10)
                            $val='0'.$i;
                        echo "<option value=\"$val\">$val</option>";
                    }
                ?>
            </select>
            <span>:</span>
            <select id="minuteSelect" name='fromMinutes'>
                <?php
                    for ($i = 0; $i <= 59; $i++) {
                        $val=$i;
                        if($i<10)
                            $val='0'.$i;
                        echo "<option value=\"$val\">" . sprintf("%02d", $val) . "</option>";
                    }
                ?>
            </select>
            <select name='fromAmPm'>
                <option value="AM">AM</option>
                <option value="PM">PM</option>
            </select>
        </div>
    </div>


    <div class="time-selection">
        <div class='time-selection-header'>
            <h3>Do:</h3>
        </div>
        <div class="selecting part">
            <select id="hourSelect" name='toHour'>
                <?php
                    for ($i = 1; $i <= 12; $i++) {
                        $val=$i;
                        if($i<10)
                            $val='0'.$i;
                        echo "<option value=\"$val\">$val</option>";
                    }
                ?>
            </select>
            <span>:</span>
            <select id="minuteSelect" name='toMinutes'>
                <?php
                    for ($i = 0; $i <= 59; $i++) {
                        $val=$i;
                        if($i<10)
                            $val='0'.$i;
                        echo "<option value=\"$val\">" . sprintf("%02d", $val) . "</option>";
                    }
                ?>
            </select>
            <select name='toAmPm'>
                <option value="AM">AM</option>
                <option value="PM">PM</option>
            </select>
        </div>
    </div>
    <br>
    <input type="submit" value="Potvrdi" name="changeAvailability" class="button">

</form>
</div>