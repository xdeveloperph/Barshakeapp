<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="text-center">
            <h3>Restaurant/Bar Information</h3>
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

        $templang=0;
        $templong=0;


        if(isset($data[0]['lat'])&& isset($data[0]['lon'])){
            $templang=$data[0]['lat'];
            $templong=$data[0]['lon'];
        }elseif(!empty($geolocdata) || $geolocdata !=null ){
            $templang = $geolocdata->latitude;
            $templong = $geolocdata->longitude;
        }
        $attributes = array( "id" => "user", "name" => "user", "class"=>"form-horizontal","enctype"=>"multipart/form-data");
        echo form_open(base_url().'cms/restaurant/'.$action."/".$reference,$attributes,$hidden);
        ?>
        <?php if(isset($data[0]['logo'])) {
            if($data[0]['logo'] !=""){?>
        <div class="form-group text-center">
            <img src='<?php echo $data[0]['logo']; ?>' alt='' width='200px' class=' img-thumbnail'>
        </div>
        <?php }}     ?>
        <input type="hidden" name="user[lat]" id="user[lat]" value="<?php echo $templang   ?>">
        <input type="hidden" name="user[lon]" id="user[lon]" value="<?php echo $templong ?>">
        <div class="form-group">
            <label for="fname">Restaurant/Bar Name</label>
            <input type="text" class="form-control" name="user[barName]" id="user[barName]" placeholder="Bar Name" value="<?php if(isset($data[0]['barName'])) echo $data[0]['barName'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Street Address</label>
            <input type="text" class="form-control" name="user[street]" onchange="searchgeoloc();" id="user[street]" placeholder="Street Address" value="<?php if(isset($data[0]['street'])) echo $data[0]['street'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">City</label>
            <input type="text" class="form-control" name="user[city]" onchange="searchgeoloc();" id="user[city]" placeholder="City" value="<?php if(isset($data[0]['city'])) echo $data[0]['city'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">State</label>
            <input type="text" class="form-control" name="user[state]" onchange="searchgeoloc();" id="user[state]" placeholder="State" value="<?php if(isset($data[0]['state'])) echo $data[0]['state'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Country</label>
            <input type="text" class="form-control" name="user[country]" onchange="searchgeoloc();" id="user[country]" placeholder="Country" value="<?php if(isset($data[0]['country'])) echo $data[0]['country'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Zip Code</label>
            <input type="text" class="form-control" name="user[zip]" onchange="searchgeoloc();" id="user[zip]" placeholder="Zip" value="<?php if(isset($data[0]['zip'])) echo $data[0]['zip'];   ?>" required>
        </div>
        <div class="form-group">
            <label for="fname">Logo</label>
            <input type="file" class="form-control" name="photo[]" id="photo[]" >
        </div>
        <div class="form-group">
            <label for="fname">Background Image</label>
            <input type="file" class="form-control" name="photo[]" id="photo[]"  >
        </div>

        <div class="form-group">
            <label for="fname">Map Location</label>
            <p>If the location on the map does not match your current address please pin point or click the exact location in the map to update the map of your Restaurant/Bar .</p>
            <div id="map"  style="width: 100%;height: 300px;"></div>
        </div>
        <div class="form-group text-right">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="<?php echo $returnUrl ?>" class="btn btn-danger">Back</a>
        </div>
        </form>
    </div>
</div>
<script>
    function searchgeoloc(){
        var seardata= [];
        seardata[0]= document.getElementById('user[street]').value;
        seardata[1]= document.getElementById('user[city]').value;
        seardata[2]= document.getElementById('user[state]').value;
        seardata[3]= document.getElementById('user[country]').value;
        seardata[4]= document.getElementById('user[zip]').value;
        var inputdata=seardata.join(', ');
        var tempdata= ajaxcall(inputdata)
        if(!tempdata){
            if(seardata[1] !="" && seardata[2] !="" && seardata[3] !="" && seardata[4] !="" ){
                inputdata=seardata[1]+", "+seardata[2]+", "+seardata[3]+", "+seardata[4];
                tempdata= ajaxcall(inputdata);
            }
        }
    }
    function ajaxcall(inputdata){
        $.ajax({
            url: "https://maps.googleapis.com/maps/api/geocode/json?address="+inputdata.replace(" ", "+")+"&sensor=false&key=AIzaSyAUwWL8t5TF0FvNXzz3OGuUMdVouCQqizU",
            type: "POST",
            success: function(data) {
                var result= data.results[0];
                if(result) {
                    addMarker(result.geometry.location);
                    return true;
                }else{
                    return false;
                }

            }
        });
    }
</script>
<script>

    var map;
    var markers = [];

    function initMap() {

        var myLatLng = {lat: <?php echo $templang   ?>, lng: <?php echo $templong  ?>};
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: myLatLng
        });

        // This event listener will call addMarker() when the map is clicked.
        map.addListener('click', function(event) {
            addMarker(event.latLng);
            document.getElementById('user[lat]').value=event.latLng.lat();
            document.getElementById('user[lon]').value=event.latLng.lng();

        });

        // Adds a marker at the center of the map.
        addMarker(myLatLng);


    }


    // Adds a marker to the map and push to the array.
    function addMarker(location) {
        clearMarkers();
        var marker = new google.maps.Marker({
            position: location,
            map: map,
            title: '<?php if(isset($data[0]['barName'])) echo $data[0]['barName']; ?>',
        });
        map.setCenter(location);
        markers.push(marker);
    }

    // Sets the map on all markers in the array.
    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setMapOnAll(null);
    }

    // Shows any markers currently in the array.
    function showMarkers() {
        setMapOnAll(map);
    }

    // Deletes all markers in the array by removing references to them.
    function deleteMarkers() {
        clearMarkers();
        markers = [];
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUwWL8t5TF0FvNXzz3OGuUMdVouCQqizU&signed_in=true&libraries=places&callback=initMap"></script>