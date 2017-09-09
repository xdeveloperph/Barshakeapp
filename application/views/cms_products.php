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
        <div class="text-right">
            <a href="<?php echo base_url(); ?>cms/products/bulk/" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Bulk Upload</a>
        </div>
        <div class="row">
            <?php
            $hidden =array('search[tab]' => 'products');
            $attributes = array( "id" => "products", "name" => "products", "class"=>"form-horizontal","enctype"=>"multipart/form-data");
            echo form_open(base_url().'cms/searchproducts/',$attributes,$hidden);
            ?>
                <div class="col-md-2 col-md-offset-2">
                    <div class="form-group">
                        <select name="search[cat]" class="form-control">
                            <option value="Drinks">Drinks</option>
                            <option value="Category">Category</option>
                            <option value="Flavor">Flavor</option>
                            <option value="Ingredients">Ingredients</option>
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
            <caption>List of Drinks</caption>
            <thead>
            <tr>
                <th>Photo</th>
                <th>Drinks</th>
                <th>Category</th>
                <th>Ingredients</th>
                <th>Flavor</th>
                <th></th>
            </tr>
            </thead>
            <?php
            if($result != null){
                foreach ($result as $items) {
                    echo "<tr>";
                    if($items['image'] != ""){
                        echo "<td><img src='".$items['image']."' width='100px' height='100px'></td>";
                    }else{
                        echo "<td></td>";
                    }
                    echo "<td>".$items['drinkName']."</td>";
                    echo "<td>".$items['category']."</td>";
                    echo "<td><p>".$items['ingredients']."<p></td>";
                    echo "<td>".$items['flavor']."</td>";
                    echo "<td> <a href='".base_url()."cms/products/edit/".$items['objectId']."' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Edit</a> ";
                    echo "<button onclick=removeData('".base_url()."cms/products/delete/".$items['objectId']."'); class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Delete </button></td>";
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
                    window.location = "<?php echo base_url() ?>cms/products/Ascending/";
                }else if(value =="za"){
                    window.location = "<?php echo base_url() ?>cms/products/Descending/";
                }
            }
        </script>