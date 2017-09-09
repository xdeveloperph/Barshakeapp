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
<div class="container">
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="text-center"><strong>
            <h3>Welcome to Bar Shake. To get started, please complete your profile. 
</br></br>
Step 1: Your Information</strong></h3>
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

        $attributes = array( "id" => "user", "name" => "user", "class"=>"form-horizontal","enctype"=>"multipart/form-data");
        echo form_open(base_url().'cms/info/',$attributes);
        ?>
        <input type="hidden" id="user[lat]" name="user[lat]" value="<?php if(isset($data[0]['lat'])) echo $data[0]['lat'];   ?>">
        <input type="hidden" id="user[lon]" name="user[lon]" value="<?php if(isset($data[0]['lon'])) echo $data[0]['lon'];   ?>">
        <input type="hidden" id="user[mobile]" name="user[mobile]" >
        <div class="form-group">
            <label for="fname">First Name</label>
            <input type="text" class="form-control" name="user[firstName]" id="user[firstName]" placeholder="First Name" value="<?php if(isset($data[0]['firstName'])) echo $data[0]['firstName'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Last Name</label>
            <input type="text" class="form-control" name="user[lastName]" id="user[lastName]" placeholder="Last Name" value="<?php if(isset($data[0]['lastName'])) echo $data[0]['lastName'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Street address</label>
            <input type="text" class="form-control" name="user[address]" id="user[address]" placeholder="Street Address" value="<?php if(isset($data[0]['address'])) echo $data[0]['address'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">City</label>
            <input type="text" class="form-control" name="user[city]" id="user[city]" placeholder="City" value="<?php if(isset($data[0]['state'])) echo $data[0]['state'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">State</label>
            <input type="text" class="form-control" name="user[state]" id="user[state]" placeholder="State" value="<?php if(isset($data[0]['state'])) echo $data[0]['state'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Country</label>
            <input type="text" class="form-control" name="user[country]" id="user[country]" placeholder="Country" value="<?php if(isset($data[0]['state'])) echo $data[0]['state'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Zip Code</label>
            <input type="text" class="form-control" name="user[zip]" id="user[zip]" placeholder="Zip" value="<?php if(isset($data[0]['state'])) echo $data[0]['state'];   ?>" required>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success">Next Step</button>
        </div>
        </form>
    </div>
</div>
</div>
</body>
</html>