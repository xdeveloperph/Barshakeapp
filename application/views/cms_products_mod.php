<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="text-center">
            <h3>Drinks</h3>
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

        $attributes = array( "id" => "products", "name" => "products", "class"=>"form-horizontal","enctype"=>"multipart/form-data");
        echo form_open(base_url().'cms/products/'.$action."/".$reference,$attributes,$hidden);
        ?>

        <div class="form-group">
            <label for="fname">Drink</label>
            <input type="text" class="form-control" name="drink[drinkName]" id="drink[drinkName]" placeholder="Name" value="<?php if(isset($data[0]['drinkName'])) echo $data[0]['drinkName'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Type of Drinks</label>
            <select class="form-control" name="drink[category]" id="drink[category]" placeholder="Category" required>
               <?php
               $sort=array('Mixed','Frozen','On the rocks','Straight up / Neat','Shot / Shooter','Beers and Ales','Non-Alcoholic');
               foreach($sort as $items){
                   $index=array_search($items,array_column($category, 'category_name'));
                   $selected="";
                   if($category[$index]['category_name'] ==$data[0]['category'] ){
                       $selected="selected";
                   };
                   echo'<option value="'.$category[$index]['id'].'"  '.$selected.'>'.$category[$index]['category_name'].'</option>';
               }
               ?>
            </select>

        </div>
        <div class="form-group">
            <label for="fname">Flavor</label>
            <select class="form-control" name="drink[flavor]" id="drink[flavor]" placeholder="Flavor" required>
                <?php
                foreach($flavor as $items){
                    $selected="";
                    if($items['flavor'] ==$data[0]['flavor'] ){
                        $selected="selected";
                    };
                    echo'<option value="'.$items['id'].'" '.$selected.'>'.$items['flavor'].'</option>';
                }
                ?>
            </select>

        </div>
        <div class="form-group">
            <label for="fname">Restaurant</label>
            <select class="form-control" name="drink[restaurant]" id="drink[restaurant]" placeholder="Restaurant">
                <?php
                foreach($restolist as $items){
                    $selected="";
                    if($items['objectId'] ==$data[0]['restaurant'] ){
                        $selected="selected";
                    };
                    echo'<option value="'.$items['objectId'].'" '.$selected.'>'.$items['barName'].'</option>';
                }
                ?>
            </select>

        </div>
        <div class="form-group">
            <label for="fname">Tags</label>
             <textarea class="form-control" name="drink[tags]" id="drink[tags]" placeholder="Tags" required>
<?php if(isset($data[0]['tags'])) echo implode(",", $data[0]['tags']);   ?></textarea>
        </div>
        <div class="form-group">
            <label for="fname">Ingredients</label>
             <textarea class="form-control" name="drink[ingredients]" id="drink[ingredients]" placeholder="Ingredients" required>
<?php if(isset($data[0]['ingredients'])) echo $data[0]['ingredients'];   ?></textarea>
        </div>

        <div class="form-group">
            <label for="fname">Photo</label>
            <input type="file"  class="form-control" name="photo" id="photo" placeholder="Photo">
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="<?php echo $returnUrl ?>" class="btn btn-danger">Back</a>
        </div>
        </form>
    </div>
</div>