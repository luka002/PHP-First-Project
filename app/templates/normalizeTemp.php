<div class="container">
    <div class="row">
        Enter text for normalization:
    </div>
    <form method="post" action="index.php">
        <input type="hidden" name="controller" value="normalize">
        <textarea name="input" rows="4" cols="50"></textarea><br>
        <?php if ($premium) :?>
            <input type="checkbox" name="text" <?= $textChecked ?>>
            <div style="float: left">Normalize text</div><br><br>

            <input type="checkbox" name="phone" <?= $phoneChecked ?>>
            <div style="float: left">Normalize phone number</div><br><br>

            <input type="checkbox" name="date" <?= $dateChecked ?>>
            <div style="float: left">Normalize date</div><br><br>
            <?php if (!$disableSaving) :?>
                <input type="checkbox" name="save" <?= $saveChecked ?>>
                <div style="float: left; color: green">Save normalization</div>
            <?php else: ?>
                <div>
                    <p>You have exceeded allowed number of stored normalizations.
                    Delete stored normalizations to be able to save new ones.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="row">
            <input style="float: right" type="submit" name="submit" value="Normalize">
            <a class="saved-norms" href="#norm-table">My Normalizations</a>
        </div>
    </form>

    <?php if ($transExecuted && !empty(trim($result['transformed']))) :?>
        <?php if ($saved) :?>
            <strong>Normalization successfully saved!</strong><br><br>
        <?php endif; ?>
        Transformation executed <?= $result['total'] ?> times.
        <div class="row transf-containter">
            <pre><?= checkEntry($result['transformed']) ?></pre>
        </div>
    <?php endif; ?>

    <hr>
    <div class="row">
        <h4>How does it work?</h4>
    </div>
    <div class="row">
        <p>
            This form normalizes input provided by user. There are
            three types of normalization available:
        </p><br>
        <p><strong>1. Text normalization</strong></p>
        <p>
            Removes extra space if there is more than one space between characters
            and also adds a missing space between punctuation and the following letter.
        </p>
        <p>Examples:</p>
        <p>
            "My&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;car." -> normalizes to -> "My car."<br>
            "Red car.Blue car." -> normalizes to -> "Red car. Blue car."
        </p><br>
        <p><strong>2. Phone number normalization</strong></p>
        <p>
            Transforms specific number patterns according to the examples shown
            below. <br>The letter "A" represents prefix (01 or xxx) while
            other letters represent single digits.
        </p>
        <p>Examples:</p>
        <p>
            "Abcdefg" -> normalizes to -> "A/bcd-efg"<br>
            "Abcdefgh" -> normalizes to -> "A/bcde-fgh"<br>
            "A/bcdefg" -> normalizes to -> "A/bcd-efg"<br>
            "A/bcdefgh" -> normalizes to -> "A/bcde-fgh"<br>
            "A.bcde.fgh" -> normalizes to -> "A/bcde-fgh"<br>
            "A.bcd.efgh" -> normalizes to -> "A/bcde-fgh"<br>
            "A.bcd.efg" -> normalizes to -> "A/bcd-efg"<br>
            "A-bc-de-fg" -> normalizes to -> "A/bcd-efg"<br>
            "A-bcd-ef-gh" -> normalizes to -> "A/bcde-fgh"<br>
            "A/bc-de-fg" -> normalizes to -> "A/bcd-efg"<br>
            "A/bcd-ef-gh" -> normalizes to -> "A/bcde-fgh"
        </p><br>
        <p><strong>3. Date normalization</strong></p>
        <p>
            Transforms date format YYYY-MM-dd to dd.MM.YYYY
        </p>
        <p>Example:</p>
        <p>
            "1994-9-26" -> normalizes to -> "26.09.1994"<br>
        </p>
        <div class="row">
            <p style="float: left"><strong>*Note: </strong></p>
        </div>
        <p id="norm-table">
            For regular user all of the transformation are applied
            automatically. Only premium user can choose which transformations
            will be applied. Premium users can also store up to 10 normalizations.
        </p>
    </div>
</div>

<?php if (count($norms) === 0) :?>
    <p>You do not have stored normalizations.</p>
<?php endif; ?>
<?php if (!$premium) :?>
        <p>To be able to save normalizations, you have to become premium user.</p>
        <form>
            <input type="hidden" name="controller" value="user">
            <input style="float: none" type="submit" name="submit" value="Become Premium User Here!">
        </form>
<?php endif; ?>
<?php if (count($norms) != 0) :?>
    <table>
        <tr>
            <th>My normalizations</th>
            <th>Number of text normalizations</th>
            <th>Number of phone normalizations</th>
            <th>Number of date normalization</th>
            <th>Delete normalization</th>
        </tr>
        <?php foreach($norms as $norm): ?>
            <tr>
                <td><pre><?= checkEntry($norm->getNorm()) ?></pre></td>
                <td><?= $norm->getText() ?></td>
                <td><?= $norm->getPhone() ?></td>
                <td><?= $norm->getDate() ?></td>
                <td><form method="post">
                        <input type="hidden" name="controller" value="normalize">
                        <input type="hidden" name="id" value="<?= $norm->getId() ?>">
                        <input id="delete" type="submit" name="delete" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
