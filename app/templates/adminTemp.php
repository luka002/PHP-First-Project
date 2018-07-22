<?php if ($showUsers) :?>
    <div class="container">
        <?php if ($userAdded): ?>
        <div class="row">
            <h4><?= $form->getName() ?> successfully added!</h4>
        </div>
        <?php endif; ?>
        <div class="row"><h4>Add new user:</h4></div>
        <form method="post">
            <input type="hidden" name="controller" value="admin">

            <div class="row">
                <div class="col-35">
                    <label>Name:</label>
                </div>
                <div class="col-65">
                <?php if ($userAdded): ?>
                    <input type="text" name="name" value="">
                <?php else: ?>
                    <input type="text" name="name" value="<?=checkEntry($form->getName() ?? '')?>">
                <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-65" style="color: red">
                    <?php if ($form->hasError('name')) :?>
                        <?= $form->getError('name') ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-35">
                    <label>Email:</label>
                </div>
                <div class="col-65">
                    <?php if ($userAdded): ?>
                        <input type="email" name="email" value="">
                    <?php else: ?>
                        <input type="email" name="email" value="<?=checkEntry($form->getEmail() ?? '')?>">
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-65" style="color: red">
                    <?php if ($form->hasError('email')) :?>
                        <?= $form->getError('email') ?><br>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-35">
                    <label>Password:</label>
                </div>
                <div class="col-65">
                    <input type="password" name="passwordNew">
                </div>
            </div>
            <div class="row">
                <div class="col-65" style="color: red">
                    <?php if ($form->hasError('passwordNew')) :?>
                        <?= $form->getError('passwordNew') ?><br>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-35">
                    <label>Confirmed password:</label>
                </div>
                <div class="col-65">
                    <input type="password" name="passwordNew2">
                </div>
            </div>

            <div class="row">
                <input type="submit" name="addUser" value="Add User">
            </div>
        </form>
    </div>
    <?php if (count($users) === 0) :?>
        <p>There are no registered users.</p>
    <?php else: ?>
        <table style="width: 600px">
            <tr>
                <th>User</th>
                <th>Premium</th> 
                <th>Admin</th>
                <th>Normalizations</th>
                <th>Delete User</th>
            </tr>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?= $user->getName() ?></td>
                    <td>
                        <?php if ($user->getPremium()) :?>
                            <form method="post">
                                <input type="hidden" name="controller" value="admin">
                                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                                <input style="width: 100%" id="warning" type="submit" name="premium" value="YES">
                            </form>
                        <?php else: ?>
                            <form method="post">
                                <input type="hidden" name="controller" value="admin">
                                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                                <input style="width: 100%" type="submit" name="premium" value="NO">
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user->getAdmin()) :?>
                            <form method="post">
                                <input type="hidden" name="controller" value="admin">
                                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                                <input style="width: 100%" id="warning" type="submit" name="admin" value="YES">
                            </form>
                        <?php else: ?>
                            <form method="post">
                                <input type="hidden" name="controller" value="admin">
                                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                                <input style="width: 100%" type="submit" name="admin" value="NO">
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="controller" value="admin">
                            <input type="hidden" name="id" value="<?= $user->getId() ?>">
                            <input style="width: 100%" id="forgot" type="submit" name="edit" value="Edit">
                        </form>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="controller" value="admin">
                            <input type="hidden" name="id" value="<?= $user->getId() ?>">
                            <input style="width: 100%" id="delete" type="submit" name="delete" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
<?php else: ?>
    <div><strong> Normalizations from user: <?= $user->getName() ?> </strong></div>
    <?php if (count($norms) === 0): ?>
        <p>No stored norms.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Normalization</th>
                <th>Number of text normalizations</th>
                <th>Number of phone normalizations</th>
                <th>Number of date normalizations</th>
                <th>Delete normalization</th>
            </tr>
            <?php foreach($norms as $norm): ?>
                <tr>
                    <td><pre><?= checkEntry($norm->getNorm()) ?></pre></td>
                    <td><?= $norm->getText() ?></td>
                    <td><?= $norm->getPhone() ?></td>
                    <td><?= $norm->getDate() ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="controller" value="admin">
                            <input type="hidden" name="id" value="<?= $user->getId() ?>">
                            <input type="hidden" name="removeNorm" value="<?= $norm->getId() ?>">
                            <input id="delete" type="submit" name="delete" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <form method="post" action="index.php">
        <input type="hidden" name="controller" value="admin">
        <input id="snow" type="submit" name="submit" value="Back">
    </form>
<?php endif; ?>
