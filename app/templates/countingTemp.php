<div class="container">
    <p>Enter arguments:</p>
    <form>
        <input type="hidden" name="controller" value="counting">
        <div class="row">
            <div class="col-25">
                <label>Text:</label>
            </div>
            <div class="col-75">
                <input type="text" name="text" value="<?=$error ? checkEntry($text) : '' ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label>Stop Character:</label>
            </div>
            <div class="col-75">
                <input type="text" name="stop" value="<?=$error ? checkEntry($stop) : '' ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label>Search:</label>
            </div>
            <div class="col-75">
                <input type="text" name="search" value="<?=$error ? checkEntry($search) : '' ?>">
            </div>
        </div>
        <div class="row">
            <input type="submit" name="submit" value="Calculate">
        </div>
    </form>

    <div><strong><?= $result ?></strong></div><br>

    <hr>
    <div class="row">
        <h4>How does it work?</h4>
    </div>
    <div class="row">
        <p>
            This form calculates how many times have characters from
            "Search" field appeared in "Text" field before character
            in "stop" field appears. There are three fields, "Text" - accepts
            any sequence of characters, "Stop Character" - marks the end
            of searching, "Search" - defines the characters whose occurrences
            will be counted before the stop character appears (characters have
            to be separated by comma without any spaces).
        </p>
    </div>
    <div class="row">
        <p>Examples:</p>
    </div>
    <div class="row">
        <strong>Text = "abcdefhij", Stop Character = "c", Search = "a,h" => Result = 1;</strong><br>
        <em>Counts cumulative number of occurrences of letters "a" and "h" before letter "c".</em><br><br>
        <strong>Text = "žđščćžđš", Stop Character = "ć", Search = "ž,š" => Result = 2;</strong><br>
        <em>Both letters "ž" and "š" have appeared once before character "ć".</em><br><br>
        <strong>Text = "abcdef", Stop Character = "a", Search = "b,c" => Result = 0;</strong><br>
        <strong>Text = "abcdef", Stop Character = "a", Search = "b, c" => Result = Error;</strong><br>
        <em>Error because there is a space between "," and "c".</em><br><br>
        <strong>Text  = "!@#$%", Stop Character = "$", Search = "@,!" => Result = 2;</strong><br><br>
        <strong>Text  = "abcdefghij", Stop Character = "l", Search = "a,b" => Result = 0;</strong><br>
        <em>Result is 0 because letter "l" does not exist in the text.</em><br><br>
    </div>
</div>

