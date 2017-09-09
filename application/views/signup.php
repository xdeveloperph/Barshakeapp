<div class="signup-body">
    <div class="signup-content">
        <div class="container">
            <div class="signup-header">
                <div class="text-center">
                    <p><h4>Start your 30-day <strong>free</strong> trial<br> Upload your drinks menu on Bar Shake and reach new customers. </h4></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group text-center">
                            <img src="<?php echo base_url(); ?>/images/no-credit-card.png" height="40px">No billing information required at this point.

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
                    $attributes = array( "id" => "userform", "name" => "userform","autocomplete" => "off",'class'=>'form-horizontal' );
                    echo form_open(base_url()."registration/signup", $attributes);
                    ?>
                    <div class="form-group">
                        <label for="address" class="col-sm-4 control-label">Username</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="userform[email]" name="userform[email]" placeholder="Email" value="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="userform[password]" name="userform[password]" placeholder="Password" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-sm-4 control-label">Retype Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="retpass" name="retpass" placeholder="Password" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <button type="submit" class="btn btn-success">Sign up for the free trial</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
