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
<div class="body">
<div class="main-body">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
<img src="<?php echo base_url() ?>images/12062015-1.png" width="100%">
                <?php
                echo validation_errors('<div class="container-fluid bg-danger error-container">','</div>');
                if($errornotice !=""){
                    echo '<div class="container-fluid bg-danger error-container">';
                    echo $errornotice;
                    echo '</div>';
                }
                if( $successnotice !=""){
                    echo '<div class="container-fluid bg-success success-container" >';
                    echo $successnotice;
                    echo '</div>';

                }
                ?>
<?php
            $attributes = array( "id" => "userform", "name" => "userform");
            echo form_open(base_url()."registration/login", $attributes);
            ?>

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
                        <label for="exampleInputEmail1" style="color:white">Username</label>
                        <input type="email" class="form-control" id="loguser" name="log[user]" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" style="color:white">Password</label>
                        <input type="password" class="form-control" id="logpass" name="log[pass]" placeholder="Password">
                    </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            </form>
            </div>
        </div>
    </div>
</div>

