<div class="container">
    <div class="row"><?= $message ?></div><br>

    <?php if (!$success) :?>
        <form method="post">
            <input type="hidden" name="controller" value="forgottenPassword">

            <div class="row">
                <div class="col-25">
                    <label>E-mail:</label>
                </div>
                <div class="col-75">
                    <input type="text" name="email" value="<?= checkEntry($email) ?>">
                </div>
            </div>

            <div class="row">
                <input type="submit" name="submit" value="Send">
            </div>
        </form>
    <?php elseif (null != $link) :?>
        <div class="row">
            <h3>Link is provided here for simpler demonstration, in real world it would have been sent to user email.</h3>
        </div>
        <div class="row">
            <a href="<?= $link ?>">Create new password link</a>
        </div>
    <?php endif; ?>
</div>
