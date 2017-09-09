<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="text-center">
            <h3>Basic Information</h3>
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
        echo form_open(base_url().'cms/accounts/'.$action."/".$reference,$attributes,$hidden);
        ?>
        <input type="hidden" id="user[lat]" name="user[lat]" value="<?php if(isset($data[0]['lat'])) echo $data[0]['lat'];   ?>">
        <input type="hidden" id="user[lon]" name="user[lon]" value="<?php if(isset($data[0]['lon'])) echo $data[0]['lon'];   ?>">
        <div class="form-group">
            <label for="fname">First Name</label>
            <input type="text" class="form-control" name="user[firstName]" id="user[firstName]" placeholder="First Name" value="<?php if(isset($data[0]['firstName'])) echo $data[0]['firstName'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Last Name</label>
            <input type="text" class="form-control" name="user[lastName]" id="user[lastName]" placeholder="Last Name" value="<?php if(isset($data[0]['lastName'])) echo $data[0]['lastName'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Mobile</label>
            <input type="text" class="form-control" name="user[mobile]" id="user[mobile]" placeholder="Mobile" value="<?php if(isset($data[0]['mobile'])) echo $data[0]['mobile'];   ?>" >
        </div>
        <div class="form-group">
            <label for="fname">Street address</label>
            <input type="text" class="form-control" name="user[address]" id="user[address]" placeholder="Lot / Block / Phase " value="<?php if(isset($data[0]['address'])) echo $data[0]['address'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">City</label>
            <input type="text" class="form-control" name="user[city]" id="user[city]" placeholder="City" value="<?php if(isset($data[0]['city'])) echo $data[0]['city'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">State</label>
            <input type="text" class="form-control" name="user[state]" id="user[state]" placeholder="State" value="<?php if(isset($data[0]['state'])) echo $data[0]['state'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Country</label>
            <input type="text" class="form-control" name="user[country]" id="user[country]" placeholder="Country" value="<?php if(isset($data[0]['country'])) echo $data[0]['country'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Zip Code</label>
            <input type="text" class="form-control" name="user[zip]" id="user[zip]" placeholder="Zip" value="<?php if(isset($data[0]['zip'])) echo $data[0]['zip'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Photo</label>
            <input type="file" class="form-control" name="photo" id="photo" placeholder="Name" >
        </div>
        <div class="form-group">
            <label for="fname">Access</label>
            <select name="user[type]" id="user[type]" placeholder="Type" class="form-control">
                <option value="0" <?php echo ($data[0]['type'] == 0)? "selected":"";   ?>>Guest</option>
                <option value="1" <?php echo ($data[0]['type'] == 1)? "selected":"";   ?>>Administrator</option>
            </select>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="<?php echo $returnUrl ?>" class="btn btn-danger">Back</a>
        </div>
        </form>
    </div>
</div>
