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
<nav class="navbar navbar-default navbar-fixed-top head-container">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="nav-icon">
                <a class="navbar-brand" href="<?php echo base_url() ?>"><img src="<?php echo base_url(); ?>images/logo-item1.png" height="25px"></a>
            </div>

        </div>
        <div id="navbar" class="navbar-form navbar-right">
            <div class="form-group">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target=".bs-example-modal-sm">Login</button>
            </div>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <a href="<?php echo base_url() ?>"><h4 class="modal-title">Bar Shake</h4></a>
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
<div class="body">
