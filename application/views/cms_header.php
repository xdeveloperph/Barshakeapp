<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Barshakeapp" />
    <meta name="keywords" content="storm, news, weather, local, online, daily" />
    <meta name="author" content="Codrops" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">

    <title>Bar Shake</title>
</head>

<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url() ?>">Bar Shake</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if($userType == 1 || $userType == "1"){ ?>
                    <li class="<?php echo $hdashboard; ?>"><a href="<?php echo base_url(); ?>cms/dashboard" >Dashboard</a></li>
                    <li class="<?php echo $haccount; ?>"><a href="<?php echo base_url(); ?>cms/accounts" >Accounts</a></li>
                    <li class="<?php echo $hbars; ?>"><a href="<?php echo base_url(); ?>cms/bars" >Restaurant</a></li>
                <?php } ?>
                <li class="<?php echo $hproduct; ?>">
                    <a href="<?php echo base_url(); ?>cms/products" >
                        Products
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="<?php echo ""; ?>">
                    <a href="<?php echo base_url(); ?>cms/notification" >
                    Notification
                    <span class="badge">0</span></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $username ;?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li  class="<?php echo $hprofile; ?>"><a href="<?php echo base_url(); ?>cms/profile"  >Profile</a></li>
                        <li class="<?php echo $hpupgrade; ?>"><a href="<?php echo base_url(); ?>cms/upgrade" >Upgade</a></li>
                        <li><a href="#">Settings</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?php echo base_url()."cms/logout" ?>">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Bar Shake</h4>
            </div>

            <?php
            $attributes = array( "id" => "userform", "name" => "userform");
            echo form_open(base_url()."registration/login", $attributes);
            ?>

            <div class="modal-body">
                <?php
                if($errorlogin !=""){
                    echo '<div class="container-fluid bg-danger error-container">';
                    echo $errorlogin;
                    echo '</div>';
                }
                if( $successlogin !=""){
                    echo '<div class="container-fluid bg-success success-container" >';
                    echo $successlogin;
                    echo '</div>';

                }
                ?>
                <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                    <input type="email" class="form-control" id="loguser" name="log[user]" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="logpass" name="log[pass]" placeholder="Password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Login</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="container">
    <?php
    $display="display:none";
    $remainigndays="";

    if($userType == 0){

        if(empty($usersubscription) || $usersubscription[0]['type'] == 0){
            $display="";
            if($free_days <= 30){

            }
            $remainigndays= 30-(int)$free_days;
        }
    }

    ?>
    <div class="alert alert-warning text-center" role="alert" style="<?php echo $display ?>">
        You only have <?php echo $remainigndays ?> days remaining please upgrade to continue working with your account. <a href="<?php echo base_url(); ?>cms/upgrade" class="btn btn-warning">Upgrade Now!</a>
    </div>
</div>
<div class="body">
    <div class="container">