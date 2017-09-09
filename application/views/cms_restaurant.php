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
            $hidden =array('search[tab]' => 'bars');
            $attributes = array( "id" => "bars", "name" => "bars", "class"=>"form-horizontal","enctype"=>"multipart/form-data");
            echo form_open(base_url().'cms/bars/search',$attributes,$hidden);
            ?>

            <div class="col-md-6 col-md-offset-3">
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
            <caption>List of Restaurant</caption>
            <thead>
            <tr>
                <th>Photo</th>
                <th>Restaurant</th>
                <th>Created</th>
                <th>Email</th>
                <th></th>
            </tr>
            </thead>
            <?php

            if($result != null){

                foreach ($result as $items) {
                    $tempdate=(array)$items['created'];
                    echo "<tr>";
                    if($items['logo'] != ""){
                        echo "<td><img src='".$items['logo']."' width='100px' height='100px'></td>";
                    }else{
                        echo "<td></td>";
                    }
                    echo "<td>".$items['barName']."</td>";
                    echo "<td>".date_format(date_create($tempdate['date']),"m/d/Y")."</td>";
                    echo "<td>".$this->Subscriptiondb->GetemailByObjectId($items['owner'])."</td>";
                    echo "<td><a href='".base_url()."cms/bars/edit/".$items['objectId']."' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Edit</a> ";
                    echo "<a href='".base_url()."cms/products/restaurant/".$items['objectId']."' class='btn btn-success btn-xs'><span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span> View Products</a> ";
                    echo "<button onclick=removeData('".base_url()."cms/bars/delete/".$items['objectId']."'); class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete </button></td>";
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
                    window.location = "<?php echo base_url() ?>cms/bars/Ascending/";
                }else if(value =="za"){
                    window.location = "<?php echo base_url() ?>cms/bars/Descending/";
                }
            }
        </script>