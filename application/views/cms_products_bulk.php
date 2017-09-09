<style>
    .upload {
        width: 100px;
        height: 33px;
        background: url(https://lh6.googleusercontent.com/-dqTIJRTqEAQ/UJaofTQm3hI/AAAAAAAABHo/w7ruR1SOIsA/s157/upload.png);
        overflow: hidden;
        background-repeat: no-repeat;
        background-size: 100%;
    }

    .upload input {
        display: block !important;
        width: 100px !important;
        height: 33px !important;
        opacity: 0 !important;
        overflow: hidden !important;
    }
    .imgtb{
        width: 100px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="text-center">
            <h3>Drinks Bulk Upload</h3>
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
        <div class="container">
            <div class="row text-right">
                <button onclick="addRow();" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-plus"></span> Row</button>
            </div>
        </div>


        <table id="product" class="table table-striped">
            <thead>
                <tr>
                    <td>Image</td>
                    <td>Drink Name</td>
                    <td>Ingredients</td>
                    <td>Tags</td>
                    <td>Type of Drink</td>
                    <td>Flavor</td>
                    <td>Restaurant</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr id="row[0]">
                    <td class="imgtb"><div class="upload"><input type="file" name="image[]" id="image[]" placeholder="Photo"><div></div></td>
                    <td><input type="text" class="form-control" name="drink[0][drinkName]" id="drink[0][drinkName]" placeholder="Name" value="" required></td>
                    <td><textarea class="form-control" name="drink[0][ingredients]" rows="1" id="drink[0][ingredients]" placeholder="Ingredients" required></textarea></td>
                    <td><input type="text" class="form-control" name="drink[0][tags]" id="drink[0][tags]" placeholder="Example: Vodka, Lime, Soda, etc.." value="" required></td>
                    <td id="maincategory"><select class="form-control" name="drink[0][category]" id="drink[0][category]" placeholder="Category" required>
                            <?php
                            $sort=array('Mixed','Frozen','On the rocks','Straight up / Neat','Shot / Shooter','Beers and Ales','Non-Alcoholic');
                            foreach($sort as $items){
                                $index=array_search($items,array_column($category, 'category_name'));
                                echo'<option value="'.$category[$index]['id'].'" >'.$category[$index]['category_name'].'</option>';
                            }
                            ?>
                        </select></td>
                    <td id="mainflavor"><select class="form-control" name="drink[0][flavor]" id="drink[0][flavor]" placeholder="Flavor" required>
                            <?php
                            foreach($flavor as $items){
                                echo'<option value="'.$items['id'].'" '.$selected.'>'.$items['flavor'].'</option>';
                            }
                            ?>
                        </select></td>
                    </select></td>
                    <td id="mainrestaurant"><select class="form-control" name="drink[0][restaurant]" id="drink[0][restaurant]" placeholder="Restaurant" >
                            <?php
                            foreach($restolist as $items){
                                $selected="";
                                if($items['objectId'] ==$data[0]['restaurant'] ){
                                    $selected="selected";
                                };
                                echo'<option value="'.$items['objectId'].'" '.$selected.'>'.$items['barName'].'</option>';
                            }
                            ?>
                        </select></td>
                    <td class="text-right">
                        <button  class="btn btn-danger btn-sm" onclick='removeRow(this);'> <span class="glyphicon glyphicon-trash"></span> Remove</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="container">
            <div class="row text-center">
                <input type="submit" class="btn btn-success" value="Save All">
            </div>
        </div>
        </form>
    </div>

</div>