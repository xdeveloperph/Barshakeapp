<div class="container">
    <div class="row">
        <div class="col-md-2 col-md-offset-5">
            <?php
            if(isset($data[0]['image'])){
                if($data[0]['image'] !=""){
                    echo '<img src="'.$data[0]['image'].'" alt="..." class="img-thumbnail">';
                }
                echo '<img src="'.base_url().'images/profile-default.png" alt="..." class="img-thumbnail">';
            }else{
                echo '<img src="'.base_url().'images/profile-default.png" alt="..." class="img-thumbnail">';
            }
            ?>

        </div>

    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <table class="table">
                <thead>
                <tr>
                    <th>Basic Information</th>
                    <th class="text-right">
                        <a href="<?php echo base_url().'cms/profile/edit' ?>" class="btn btn-warning btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> Edit
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><b>First Name</b></td>
                    <td><?php if(isset($data[0]['firstName'])) echo $data[0]['firstName'];   ?></td>
                </tr>
                <tr>
                    <td><b>Last Name</b></td>
                    <td><?php if(isset($data[0]['lastName'])) echo $data[0]['lastName'];   ?></td>
                </tr>
                <tr>
                    <td><b>Mobile</b></td>
                    <td><?php if(isset($data[0]['mobile'])) echo $data[0]['mobile'];   ?></td>
                </tr>
                <tr>
                    <td><b>Email</b></td>
                    <td><?php echo $username ?></td>
                </tr>
                <tr>
                    <td><b>Address</b></td>
                    <td><?php if(isset($data[0]['address'])) echo $data[0]['address'];   ?></td>
                </tr>
                <tr>
                    <td><b>City</b></td>
                    <td><?php if(isset($data[0]['city'])) echo $data[0]['city'];   ?></td>
                </tr>
                <tr>
                    <td><b>State</b></td>
                    <td><?php if(isset($data[0]['state'])) echo $data[0]['state'];   ?></td>
                </tr>
                <tr>
                    <td><b>Country</b></td>
                    <td><?php if(isset($data[0]['country'])) echo $data[0]['country'];   ?></td>
                </tr>
                <tr>
                    <td><b>Zip</b></td>
                    <td><?php if(isset($data[0]['zip'])) echo $data[0]['zip'];   ?></td>
                </tr>
                <tr>
                    <td><b>Credentials</b></td>
                    <td class="text-right">
                        <a href="<?php echo base_url().'cms/profile/password' ?>" class="btn btn-warning btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> Edit
                        </a>
                    </td>
                </tr>
                <tr>
                    <td><b>Features</b></td>
                    <td class="text-right">

                        <?php ///if(($usersubscription[0]['type'] =='1' && $restaurant_limit <=1) || $usersubscription[0]['type'] =='0')
if(false){ ?>
                            <a href="<?php echo base_url(); ?>cms/upgrade" class="btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-cutlery"></span> Upgrade to Create Restaurant
                            </a>
                        <?php }else{ ?>
                        <a href="<?php echo base_url().'cms/restaurant/new' ?>" class="btn btn-success btn-xs">
                            <span class="glyphicon glyphicon-cutlery"></span> Create Restaurant
                        </a>
                        <?php } ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    foreach($restaurant as $item){
    ?>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <table class="table">
                <thead>
                <tr>
                    <th>Restaurant / Bar Information</th>
                    <th class="text-right">
                        <a href="<?php echo base_url().'cms/restaurant/edit/'.$item['objectId'] ?>" class="btn btn-warning btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> Edit
                        </a>
                        <button onclick="removeData('<?php echo base_url()."cms/Profile/delres/".$item['objectId'] ?>')"; class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete </button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($item['logo'])) {
                    if($item['logo'] != ''){
                       echo "<tr><td colspan='2' class='text-center'><img src='".$item['logo']."' alt='".$item['logo']."' width='100px' class=' img-thumbnail'></td></tr>";
                    }
                }
                ?>
                <tr>
                    <td><b>Restaurant</b></td>
                    <td><?php if(isset($item['barName'])) echo $item['barName'];   ?></td>
                </tr>
                <tr>
                    <td><b>Address</b></td>
                    <td><?php if(isset($item['street'])) echo $item['street'];   ?></td>
                </tr>
                <tr>
                    <td><b>City</b></td>
                    <td><?php if(isset($item['city'])) echo $item['city'];   ?></td>
                </tr>
                <tr>
                    <td><b>State</b></td>
                    <td><?php if(isset($item['state'])) echo $item['state'];   ?></td>
                </tr>
                <tr>
                    <td><b>Country</b></td>
                    <td><?php if(isset($item['country'])) echo $item['country'];   ?></td>
                </tr>
                <tr>
                    <td><b>Zip</b></td>
                    <td><?php if(isset($item['zip'])) echo $item['zip'];   ?></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
    <?php
    }
    ?>
</div>