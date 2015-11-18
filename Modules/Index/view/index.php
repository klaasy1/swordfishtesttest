<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Swordfish - Test</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo LIBRARY_PATH ?>/css/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo LIBRARY_PATH ?>/css/small-business.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo LIBRARY_PATH ?>/gritter/css/jquery.gritter.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="images/logo3.png" alt="">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="link" href="Issue" target="dynamicContent">Home</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        
        <!-- Content Row -->
        <div class="row">
            <!-- /.col-md-12 -->
            <div id="dynamicContent" class="col-md-12">
                <?php $content->render(); ?>
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->
        
        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Swordfish - Test 2015</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="<?php echo LIBRARY_PATH ?>/js/jquery/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo LIBRARY_PATH ?>/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo BASE_HTTP_PATH ?>Modules/Index/view/js/index.js"></script>
    <script src="<?php echo BASE_HTTP_PATH ?>Modules/Index/view/js/forms.js"></script>
    
    <!-- SpinJQ JavaScript -->
    <script src="<?php echo LIBRARY_PATH ?>/js/spinJQ/spin.js"></script>
    <script src="<?php echo LIBRARY_PATH ?>/js/spinJQ/spinJQ.js"></script>
    <script type="text/javascript" src="<?php echo LIBRARY_PATH ?>/gritter/js/jquery.gritter.js"></script>

</body>

</html>