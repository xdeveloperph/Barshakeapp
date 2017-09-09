<script src="<?php echo base_url() ?>js/Chart.js"></script>
<div class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="text-center">
                <h3>Restaurant / Bar Sign Up</h3>
            </div>
            <canvas id="canvas" height="450px" width="600px"></canvas>

        </div>
        <div class="col-md-6">
            <table class="table table-striped">
               <tbody>
               <tr>
                   <td><strong>Total Sign Up:</strong></td>
                   <td><?php echo $totalsignup ?></td>
               </tr>
               <tr>
                   <td><strong>Current Trial accounts:</strong></td>
                   <td><?php echo $Trialaccounts ?></td>
               </tr>
               <tr>
                   <td><strong>Accounts Expiring Today:</strong></td>
                   <td>0</td>
               </tr>
               <tr>
                    <td><strong>Revenue:</strong></td>
                    <td></td>
               </tr>
               <tr>
                   <td colspan="2" class="text-center"><strong><h2><?php echo $TotalExpireToday ?><h2></strong></td>
               </tr>
               <tr>
                   <td><strong>App Downloads:</strong></td>
                   <td></td>
               </tr>
               <tr>
                   <td colspan="2" class="text-center"><strong><h2>0<h2></strong></td>
               </tr>
               </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

    var barChartData = {
        labels : ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
        datasets : [
            {
                fillColor : "rgba(151,187,205,0.5)",
                strokeColor : "rgba(151,187,205,0.8)",
                highlightFill : "rgba(151,187,205,0.75)",
                highlightStroke : "rgba(151,187,205,1)",
                data : <?php print_r(json_encode($result)); ?>
            }
        ]

    }
    window.onload = function(){
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx).Bar(barChartData, {
            responsive : true
        });
    }

</script>
