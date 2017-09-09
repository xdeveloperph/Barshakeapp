<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="text-center">
                <h3>Paypal Payment</h3>
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
                $hidden =array('action' => $action, 'reference' => $reference, 'class'=>'form-horizontal');
            }

            $attributes = array( "id" => "user", "name" => "user", "class"=>"form-horizontal","enctype"=>"multipart/form-data");
            echo form_open(base_url().'cms/paypal',$attributes,$hidden);
            ?>
            <input type="hidden" id="user[upgrade]" name="user[upgrade]" value="<?php echo $action ?>">
            <div class="form-group">
                <label for="fname" class="col-sm-3 control-label">First Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="user[first_name]" id="user[first_name]" placeholder="First Name" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="lastName" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="user[last_name]" id="user[last_name]" placeholder="Last Name" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-sm-3 control-label">Address</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="user[address1]" id="user[address1]" placeholder="Address" value="" >
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" name="user[email]" id="user[email]" placeholder="Email" value="" >
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-sm-3 control-label">Acount Type</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control " name="user[type]" id="user[type]" placeholder="Acount Type" value="<?php echo $upgradeTo; ?>" disabled>
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-sm-3 control-label">Amount</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control " name="user[Amount]" id="user[Amount]" placeholder="Amount" value="<?php echo $amount; ?>" disabled>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3  text-center">
                    <input type="image" name="submit" border="0"
                           src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
                           alt="PayPal - The safer, easier way to pay online">
                </div>

            </div>
            </form>
        </div>
    </div>
</div>