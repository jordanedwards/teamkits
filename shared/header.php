<?php
    session_start();
    function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
    ob_start();
    
    $title = TITLE;
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='author' content='Orchard City Web Development'>
    <meta name='description' content='Team Kits | Soccer Apparel'>
    <meta name='keywords' content='Team Kits, Team Kits Kelowna, Soccer United, Soccer United Kelowna, Soccer Kelowna, Soccer Apparel, Soccer Apparel Kelowna'>

    <title><?php echo $title . 'Team Kits'; ?></title>

    <!-- Favicon -->
    <link rel='icon' type='image/x-icon' href='favicon.ico'>

    <!-- Fonts -->
    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Lato:300'>
    <link rel='stylesheet' type='text/css' media='screen' href='http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css' />

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link rel='stylesheet' href='css/bootstrap.css'>

    <!-- Custom CSS -->
    <link rel='stylesheet' href='css/custom.css'>

    <!-- Custom Fonts -->
    <link rel='stylesheet' type='text/css' href='font-awesome/css/font-awesome.min.css'>
    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic'>
    <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

    <!-- Google Maps -->
    <script src='http://maps.googleapis.com/maps/api/js'></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
        <script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>
    <![endif]-->

</head>

<body id='page-top' class='index'>
    <h1>Team Kits | Soccer Apparel | Kelowna, British Columbia, Canada</h1>
    <!-- Navigation -->
    <nav class='navbar navbar-default navbar-fixed-top gradient-outer'>
        <div class='container gradient-inner'>
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class='navbar-header page-scroll'>
                <a class='navbar-brand' href='index.php'><img src='img/teamkits.png' alt='Team Kits Logo'></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class='navbar-collapse'>
                <ul class='nav navbar-nav navbar-right'>
                    <li>
                        <a href='index.php' class='menu-item' id='menu-1'>Home</a>
                    </li>
                    <li>
                        <a href='about.php' class='menu-item' id='menu-2'>About</a>
                    </li>
                    <li>
                        <a href='brands.php' class='menu-item' id='menu-3'>Brands</a>
                    </li>
                    <li>
                        <a href='products.php' class='menu-item' id='menu-4'>Products</a>
                    </li>
                    <li>
                        <a href='contact.php' class='menu-item' id='menu-5'>Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    
    <div id='navbar-spacer'></div>