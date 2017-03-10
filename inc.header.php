<?php require_once 'lib/index.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Quiz</title>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
    <!-- plugins -->
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/datatables/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/datatables/buttons.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/flipclock/compiled/flipclock.css"/>
    <link rel="stylesheet" type="text/css" href="bower_components/summernote/dist/summernote.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css"/>
    <!-- ./CSS -->

    <link rel="shortcut icon" href="asset/img/logomi.png">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body id="mimin" class="dashboard">
<!-- start: Header -->
<nav class="navbar navbar-default header navbar-fixed-top">
    <div class="col-md-12 nav-wrapper">
        <div class="navbar-header" style="width:100%;">
                <span class="nav-title">
<!--                    <img src="asset/img/logo.png" height="50px">-->
                    <b>Online Quiz</b>
                </span>

            <ul class="nav navbar-nav navbar-right user-nav">
                <?php if (isset($_SESSION) && $_SESSION['registered'] == true): ?>
                    <li class="countdown">
                        <span id="clock"></span>
                    </li>
                    <li class="user-name">
                        <span><?= $_SESSION['fullname'] ?></span>
                    </li>
                    <li>
                        <a href="logout.php">
                            <span class="fa fa-sign-out fa-lg"></span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<!-- end: Header -->

<!-- start: wrapper -->
<div class="container-fluid mimin-wrapper">

    <!-- start: Content -->
    <?php
    // Define Object
    $rjkKodObj = new RjkKod($dbCon, $debug);
    $penggunaObj = new Pengguna($dbCon, $debug);
    $participantObj = new Participant($dbCon, $debug);
    $setQuestionObj = new SetQuestion($dbCon, $debug);
    $questionObj = new Question($dbCon, $debug);
    $answerObj = new Answer($dbCon, $debug);

    if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
        require_once 'inc.menu.php';
        echo '<div id="content">';
    } else {
        echo '<div id="content" style="padding-left: 0px;">';
    }
