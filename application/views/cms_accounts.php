<div class="row">
    <div class="col-md-12">
        <?php
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
        <div class="row">
            <?php
            $hidden =array('search[tab]' => 'products');
            $attributes = array( "id" => "products", "name" => "products", "class"=>"form-horizontal","enctype"=>"multipart/form-data");
            echo form_open(base_url().'cms/accounts/search',$attributes,$hidden);
            ?>
            <div class="col-md-2 col-md-offset-2">
                <div class="form-group">
                    <select name="search[cat]" class="form-control">
                        <option value="first">First Name</option>
                        <option value="last">Last Name</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input name="search[text]" type="text" class="form-control" placeholder="Search for...">
                          <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">Go!</button>
                          </span>
                </div>
            </div>
            </form>

        </div>
        <div class="row text-right">
            <div class="form-group">
                <div class="col-md-2 col-md-offset-10">
                    <select name="sort" id="sort" onchange="sortItems()" class="form-control">
                        <option value="Sort">Sort</option>
                        <option value="az" <?php if(isset($sortaz)) echo ($sortaz =='az')?'selected':''?> >Alphabetical (A-Z)</option>
                        <option value="za" <?php if(isset($sortaz)) echo ($sortaz =='za')?'selected':''?> >Alphabetical (Z-A)</option>
                    </select>
                </div>
            </div>
        </div>

        <table class="table">
            <caption>List of Accounts</caption>
            <thead>
            <tr>
                <th>Photo</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Created</th>
                <th>Activation</th>
                <th>Subscription</th>
                <th></th>
            </tr>
            </thead>
            <?php
            if($result != null){
                foreach ($result as $items) {
                    $tempdate=(array)$items['created'];
                    echo "<tr>";
                    if($items['image'] != ""){
                        echo "<td><img src='".$items['photo']."' width='100px' height='100px'></td>";
                    }else{
                        echo "<td></td>";
                    }

                    $email=$this->Subscriptiondb->GetemailByObjectId($items['owner']);
                    $tempsubscription=$this->Subscriptiondb->GetSubscription($email);
                    echo "<td>".$items['firstName']."</td>";
                    echo "<td>".$items['lastName']."</td>";
                    echo "<td>".$email."</td>";
                    echo "<td>".date_format(date_create($tempdate['date']),"m/d/Y")."</td>";
                    if($tempsubscription[0]['activatedate'] !="0000-00-00 00:00:00"){
                        echo "<td>".date_format(date_create($tempsubscription[0]['activatedate']),"m/d/Y")."</td>";
                    }else{
                        echo "<td>Unverified</td>";
                    }

                    if($tempsubscription[0]['type'] == 0){
                        if($tempsubscription[0]['activatedate'] !="0000-00-00 00:00:00"){
                            $date1 = new DateTime();
                            $date2 = new DateTime($tempsubscription[0]['activatedate']);
                            $diff=date_diff($date1,$date2);

                            if($diff->m == 0){
                                echo "<td> Free(".($diff->d >0 ? (30-$diff->d) : "0")." days)</td>";
                            }else{
                                echo "<td> Free( 0 day)</td>";
                            }

                        }else{
                            echo "<td>N/A</td>";
                        }

                    }else if($tempsubscription[0]['type'] == 1){
                        echo "<td>Basic</td>";
                    }else if($tempsubscription[0]['type'] == 2){
                        echo "<td>Premium</td>";
                    }
                    echo "<td><a href='".base_url()."cms/accounts/edit/".$items['objectId']."' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Edit</a>";
                    echo " <a href='".base_url()."cms/products/view/".$items['owner']."' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span> View Products</a></td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
        <div class="text-center">
            <?php echo $pagination ?>
        </div>
    </div>
    <div>
        <script>
            function sortItems(){
                var value = document.getElementById('sort').value;
                if(value =="az"){
                    window.location = "<?php echo base_url() ?>cms/accounts/Ascending/";
                }else if(value =="za"){
                    window.location = "<?php echo base_url() ?>cms/accounts/Descending/";
                }
            }
        </script>