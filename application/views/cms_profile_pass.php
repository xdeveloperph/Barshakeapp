<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="text-center">
            <h3>Credentials</h3>
            <hr>
        </div>
        <?php
        /// Default header for error and form creation

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

        $hidden =array();
        if($action == "new"){
            $hidden =array('action' => $action);
        }elseif($action =="edit"){
            $hidden =array('action' => $action, 'reference' => $reference);
        }

        $attributes = array( "id" => "user", "name" => "user", "class"=>"form-horizontal","enctype"=>"multipart/form-data");
        echo form_open(base_url().'cms/profile/'.$action."/".$reference,$attributes,$hidden);
        ?>

        <div class="form-group">
            <label for="fname">Old Password</label>
            <input type="password" class="form-control" name="user[old]" id="user[old]" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="fname">New Password</label>
            <input type="password" class="form-control" name="user[new]" id="user[new]" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="fname">Retype Password</label>
            <input type="password" class="form-control" name="user[ret]" id="user[ret]" placeholder="Password"  required>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="<?php echo $returnUrl ?>' ?>" class="btn btn-danger">Back</a>
        </div>
        </form>
    </div>
</div>
