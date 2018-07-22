<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" href="../app/mycss.css" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Advent Pro' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Jura' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <title><?= $title; ?></title>
    </head>
    <body>
        <ul>
            <li><a id="title" href="index.php">PHP Project</a></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php?controller=addition">Addition</a></li>
            <li><a href="index.php?controller=replacement">Replacement</a></li>
            <li><a href="index.php?controller=counting">Counting</a></li>
            <li><a href="index.php?controller=normalize">Normalization</a></li>
            <?php if ($loggedIn) :?>
                <li style="float:right"><a href="index.php?controller=logout">Logout</a></li>
                <li style="float:right"><a href="index.php?controller=editProfile">Edit Profile</a></li>
                <li style="float:right"><a href="index.php?controller=user"><?= $userName ?>(<?= $type ?>)</a></li>
            <?php else: ?>
                <li style="float:right"><a href="index.php?controller=register">Register</a></li>
                <li style="float:right"><a href="index.php?controller=login">Login</a></li>
            <?php endif; ?>
        </ul>
        <?= $body; ?>

        <div class="footer">
            <p>Created by: Luka Grgić</p>
        </div>
    </body>
</html>