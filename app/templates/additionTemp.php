<div class="container">
    <div class="row" >
        Enter a number and all digits will be added:
    </div><br>

    <form>
        <input type="hidden" name="controller" value="addition">
        <div class="row">
            <div class="col-25">
                <label>Number:</label>
            </div>
            <div class="col-75">
                <input type="text" name="entry" value="<?= checkEntry($entry) ?>">
            </div>
        </div>
        <div class="row">
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>

    <div class="row" ><?= $result ?></div><br>

    <hr>
    <div class="row">
        <h4>How does it work?</h4>
    </div>
    <div class="row">
        <p>
            User is expected to put series of digits in the input field. After pressing submit
            button all of those digits will be added together and result will be shown.
        </p>
        <p>
            Example: from input "23" result will be "2+3" which is "5".
        </p>
    </div>
</div>

