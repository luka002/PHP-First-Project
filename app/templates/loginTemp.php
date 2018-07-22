<div class="container">
    <div class="row"><h3>Login:</h3></div>

    <form method="post">
        <input type="hidden" name="controller" value="login">

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
                    <?= $form->getError('name') ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-25">
                <label>Password:</label>
            </div>
            <div class="col-75">
                <input type="password" name="password" autocomplete="new-password">
            </div>
        </div>
        <div class="row">
            <div class="col-75" style="color: red">
                <?php if ($form->hasError('password')) :?>
                    <?= $form->getError('password') ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <input type="submit" name="submit" value="Login">
        </div>
    </form>

    <form method="get" action="index.php">
        <input type="hidden" name="controller" value="forgottenPassword">
        <div class="row">
            <input id="forgot" type="submit" name="submit" value="Forgot Password?">
        </div>
    </form>
</div>