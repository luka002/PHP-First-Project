<div class="container">
    <div class="row">
        <div><?= $message ?></div><br>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="controller" value="replacement">
            <label>
                <input type="file" name="entry">
            </label>
            <input type="submit" name="submit" value="Send">
        </form>
    </div>

    <hr>
    <div class="row">
        <h4>How does it work?</h4>
    </div>
    <div class="row">
        <p>User is expected to upload file containing text by pressing
            "Choose file" and confirming chosen file by pressing
            "Send" button. File will then be transformed and sent back to
            the user as HTML file with extension .html. Transformation
            works in such a way that it transforms text that is between
            special tags. There are three special tags that can be used:
        </p>
        <ol>
            <li>"#" - Everything between this tag will become bold</li>
            <li>"*" - Everything between this tag will become italic</li>
            <li>"'" - Everything between this tag will become underlined</li>
        </ol>
    </div>
    <div class="row">
        <p>Examples:</p>
    </div>
    <div class="row">
        #car# -> transforms to -><strong>car</strong><br>
        *car* -> transforms to -><em>car</em><br>
        'car' -> transforms to -><u>car</u><br>
        *car#car*car# -> transforms to -><em>car<strong>car</em>car</strong><br>
        #car#car#car# -> transforms to -><strong>car</strong>car<strong>car</strong><br>
        *car#car#car* -> transforms to -><em>car<strong>car</strong>car</em><br>
        *#'car'#* -> transforms to -><em><strong><u>car</u></strong></em><br>
    </div>
    <div class="row">
        <p style="float: left"><strong><br>*Note: </strong></p>
    </div>
    <div class="row">
        <ol>
            <li>Opening tag has to be closed, tags that are not closed will result in an error</li>
            <li>Uploaded file can not be larger than 1024 bytes</li>
        </ol>
    </div>
</div>

