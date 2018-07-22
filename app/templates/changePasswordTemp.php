<div class="container">
    <div><?= $message ?></div>

    <?php if ($showForm) :?>
        <form method="post">
            <input type="hidden" name="controller" value="changePassword">
            <input type="hidden" name="email" value="<?= $email ?>">

            <div class="row">
                <div class="col-35">
                    <label>New Password:</label>
                </div>
                <div class="col-65">
                    <input type="password" name="passwordNew">
                </div>
            </div>
            <div class="row">
                <div class="col-65" style="color: red">
                    <?php if ($form->hasError('passwordNew')) :?>
                        <?= $form->getError('passwordNew') ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-35">
                    <label>Confirm New Password:</label>
                </div>
                <div class="col-65">
                    <input type="password" name="passwordNew2">
                </div>
            </div>

            <div class="row">
                <input type="submit" name="submit" value="Submit">
            </div>
        </form>
    <?php endif; ?>
</div>