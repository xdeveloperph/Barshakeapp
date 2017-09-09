<div class="main-body">
    <div class="container">
        <div class="row text-center">

        </div>
        <div class="row">
            <div class="col-md-5 col-md-offset-1">
                <div class="ipad-border">
                    <div class="ipad-content">
                        <div class="signup text-center">
                            <h2><b>Sign Up</b></h2>
                        </div>
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
                        $attributes = array( "id" => "userform", "name" => "userform","autocomplete" => "off");
                        echo form_open(base_url()."registration/user", $attributes);
                        ?>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Username</label>
                            <input type="email" class="form-control" id="userform[email]" name="userform[email]" placeholder="Email" value="">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="userform[password]" name="userform[password]" placeholder="Password" value="">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Retype Password</label>
                            <input type="password" class="form-control" id="retpass" name="retpass" placeholder="Password" value="">
                        </div>
                        <div class="form-group text-center">
                        <button type="submit" class="btn btn-warning">Register</button>
                        </div>
                        </form>

                    </div>

                </div>
            </div>
            <div class="col-md-5">
                <img src="<?php echo base_url() ?>images/12062015-1.png" width="100%">
                <div class="text-center">
                    <a href="https://itunes.apple.com/us/app/bar-shake/id1054102068?mt=8"><img src="<?php echo base_url() ?>images/btn1.png"></a>
                </div>
            </div>

        </div>
    </div>
</div>

