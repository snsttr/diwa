<?php

function menuEntry($pTitle, $pPage = null) {
    return '<li' . ((!isset($_GET['page']) && null === $pPage) || (isset($_GET['page']) && $_GET['page'] === $pPage) ? ' class="active"' : '') . '><a href="' . (null === $pPage ? '/' : '/?page=' . $pPage) . '">' . $pTitle . '</a></li>';
}

?>


<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>DIWA</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body>
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">DIWA</a>
        </div>
        <ul class="nav navbar-nav">
            <?php echo menuEntry('Home'); ?>
            <?php echo menuEntry('Documentation', 'documentation'); ?>
            <?php echo menuEntry('Downloads', 'downloads'); ?>
            <?php if(isLoggedIn()) { ?>
                <?php echo menuEntry('Board', 'board'); ?>
            <?php } ?>
            <?php echo menuEntry('Contact', 'contact'); ?>
        </ul>
        <?php if(isLoggedIn()) { ?>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <?php echo icon('user'); ?> <strong><?php echo $_SESSION['user']['username'] ?></strong> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php echo menuEntry('Profile', 'profile'); ?>
                        <?php
                        if(1 === $_SESSION['user']['is_admin']) {
                            echo '<li role="separator" class="divider"></li>';
                            echo '<li class="dropdown-header">Administration</li>';
                            echo menuEntry('Users', 'users');
                            echo menuEntry('Downloads', 'downloads&review=1');
                        }
                        ?>
                        <li role="separator" class="divider"></li>
                        <?php echo menuEntry('Log Out', 'logout'); ?>
                    </ul>
                </li>
            </ul>
        <?php } else { ?>
            <form class="navbar-form navbar-right" role="form" method="post" action="?page=login">
                <div class="form-group"><input type="text" name="username" placeholder="Username" class="form-control"></div>
                <div class="form-group"><input type="password" name="password" placeholder="Password" class="form-control"></div>
                <button type="submit" class="btn btn-success">Sign in</button>
                <a href="/?page=register" class="btn btn-primary">Register</a>
            </form>
        <?php } ?>
    </div>
</nav>
