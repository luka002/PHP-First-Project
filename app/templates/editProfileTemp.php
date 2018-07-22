<div class="container">
    <?php if ($success) :?>
        <div class="row" style="color: green">
            <p>Password successfully updated.</p>
        </div>
    <?php elseif (true) : ?>
        <div class="row">
            <p>Change your password.</p>
        </div>
    <?php endif; ?>

    <?php if ($showForm) :?>
        <form method="post">
            <input type="hidden" name="controller" value="editProfile">
            <input type="hidden" name="name" value="<?= $name ?>">

            <div class="row">
                <div class="col-35">
                    <label>Current password:</label>
                </div>
                <div class="col-65">
                    <input type="password" name="passwordCurrent">
                </div>
            </div>
            <div class="row">
                <div class="col-65" style="color: red">
                    <?php if ($form->hasError('password')) :?>
                        <?= $form->getError('password') ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-35">
                    <label>New password:</label>
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
                    <label>Confirm new password:</label>
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
