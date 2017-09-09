</div>
<div class="modal fade" id="remove-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete . .</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the data?</p>
            </div>
            <div class="modal-footer">
                <a href="" id="remove-link" class="btn btn-danger">Yes</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
    function removeData(url){
        $("#remove-link").prop("href", url);
        $("#remove-modal").modal("show");
    }
</script>
<script>
    var dataCount=1;
    var selectvalue1 ="<?php
        if(isset($category)){
           $sort=array('Mixed','Frozen','On the rocks','Straight up / Neat','Shot / Shooter','Beers and Ales','Non-Alcoholic');
           foreach($sort as $items){
               $index=array_search($items,array_column($category, 'category_name'));
               echo"<option value='".$category[$index]['id']."' >".$category[$index]['category_name']."</option>";
           }
        }
        ?>";
    var selectvalue2 ="<?php if(isset($flavor))foreach($flavor as $items){echo"<option value='".$items['id']."' >".$items['flavor']."</option>";} ?>";
    var selectvalue3 ="<?php if(isset($restolist))foreach($restolist as $items){echo"<option value='".$items['objectId']."' '".$selected.">".$items['barName']."</option>";} ?>";
    function addRow(){
        $tbrow=" <tr id='row["+dataCount+"]'> <td class='imgtb'><div class='upload'><input type='file' name='image[]' id='image[]' placeholder='Photo'><div></div></td> <td><input type='text' class='form-control' name='drink["+dataCount+"][drinkName]' id='drink["+dataCount+"][drinkName]' placeholder='Name' value='' required></td> <td><textarea class='form-control' name='drink["+dataCount+"][ingredients]' rows='1' id='drink["+dataCount+"][ingredients]' placeholder='Ingredients' required></textarea></td> <td><input type='text' class='form-control' name='drink["+dataCount+"][tags]' id='drink["+dataCount+"][tags]' placeholder='Example: Vodka, Lime, Soda, etc..' value='' required></td> <td id='maincategory'><select class='form-control' name='drink["+dataCount+"][category]' id='drink["+dataCount+"][category]' placeholder='Category' required> "+selectvalue1+" </select></td> <td id='mainflavor'><select class='form-control' name='drink["+dataCount+"][flavor]' id='drink["+dataCount+"][flavor]' placeholder='Flavor' required> "+selectvalue2+" </select></td> </select></td> <td id='mainrestaurant'><select class='form-control' name='drink["+dataCount+"][restaurant]' id='drink["+dataCount+"][restaurant]' placeholder='Restaurant' > "+selectvalue3+" </select></td> <td class='text-right'> <button class='btn btn-danger btn-sm' onclick='removeRow(this);'> <span class='glyphicon glyphicon-trash'></span> Remove</button> </td> </tr>";
        $('#product > tbody ').append($tbrow);
        dataCount++;
        return false;
        event.preventDefault();
    }
    function removeRow($file){
        event.preventDefault();
        $($file).parent().parent().remove();
    }
</script>


</body>

</html>