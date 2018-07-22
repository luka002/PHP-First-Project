<div class="container">
    <?php if ($justRegistered) :?>
        <p>You have successfully registered.</p>
    <?php else: ?>
        <div class="row"><h3>Register:</h3></div>

        <form method="post">
            <input type="hidden" name="controller" value="register">

            <div class="row">
                <div class="col-25">
                    <label>Name:</label>
                </div>
                <div class="col-75">
                    <input type="text" name="name" value="<?=checkEntry($form->getName() ?? '')?>">
                </div>
            </div>
            <div class="row">
                <div class="col-75" style="color: red">
                    <?php if ($form->hasError('name')) :?>
                        <?= $form->getError('name') ?><br>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-25">
                    <label>Email:</label>
                </div>
                <div class="col-75">
                    <input type="email" name="email" value="<?=checkEntry($form->getEmail() ?? '')?>">
                </div>
            </div>
            <div class="row">
                <div class="col-75" style="color: red">
                <?php if ($form->hasError('email')) :?>
                    <?= $form->getError('email') ?><br>
                <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-25">
                    <label>Password:</label>
                </div>
                <div class="col-75">
                    <input type="password" name="passwordNew">
                </div>
            </div>
            <div class="row">
                <div class="col-75" style="color: red">
                    <?php if ($form->hasError('passwordNew')) :?>
                        <?= $form->getError('passwordNew') ?><br>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-25">
                    <label>Confirm Password:</label>
                </div>
                <div class="col-75">
                    <input type="password" name="passwordNew2">
                </div>
            </div>

            <div class="row">
                <input type="submit" name="submit" value="Register">
            </div>
        </form>
    <?php endif; ?>
</div>