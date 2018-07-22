<div class="container" style="padding-bottom: 50px; font-family: 'Jura'">
    <div class="row"><?= $message ?></div>

    <?php if ($admin) :?>
        <div class="row">
            <h3>You are administrator</h3><br>
        </div>
        <div class="row">
            <a class="admin-btn admin-btn1" href="index.php?controller=admin">Manage Users</a>
        </div>
    <?php elseif ($premium) :?>
        <div class="row">
            <h3>You are currently premium user, become administrator to be able to manage users and their normalizations</h3><br>
        </div>
        <div class="row">
            <form method="post">
                <input type="hidden" name="controller" value="user">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input class="admin-btn admin-btn1" id="special" type="submit" name="admin" value="Become Administrator">
            </form>
        </div>
    <?php else :?>
        <div class="row">
            <h3>You are currently regular user, become premium to be able to store up to 10 normalizations</h3><br>
        </div>
        <div class="row">
            <form method="post">
                <input type="hidden" name="controller" value="user">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input id="special" type="submit" name="premium" value="Become Premium">
            </form>
        </div>
    <?php endif; ?>
</div>