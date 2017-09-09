<div class="container">


    <?php

    $subitems=0 ;
    if(isset($usersubscription[0]['type'])) {
        $subitems=$usersubscription[0]['type'];
    }

    if( $subitems==0){
    ?>
    <div class="row text-center">
        <div class="">
            <h3><b>Upgrade Now!</b></h3>
        </div>
    </div>
    <div class="row row-step2">
        <div class="col-md-4 col-md-offset-2">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header text-center">

                        <h3 class="modal-title">Basic</h3>
                    </div>
                    <div class="modal-body text-center">
                        <h4 class="modal-title">Subscribe 1 Location</h4>
                        <h4 class="modal-title">$50 Fee</h4>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <div class="text-center">
                            <a href="<?php echo base_url()."cms/basic" ?>" class="btn btn-success">Basic</a>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header text-center">

                        <h3 class="modal-title">Premium</h3>
                    </div>
                    <div class="modal-body text-center">
                        <h4 class="modal-title">Multi Locations</h4>
                        <h4 class="modal-title">$50 first location</h4>
                        <h4 class="modal-title">$15 each additional location</h4>
                    </div>
                    <div class="modal-footer">
                        <div class="text-center">
                            <a href="<?php echo base_url()."cms/premium" ?>" class="btn btn-success ">Premium</a>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
        <?php }elseif( $subitems==1){ ?>
        <div class="row text-center">
            <div class="">
                <h3><b>Upgrade Now!</b></h3>
            </div>
        </div>
        <div class="row row-step2">
            <div class="col-md-4 col-md-offset-4">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header text-center">

                            <h3 class="modal-title">Premium</h3>
                        </div>
                        <div class="modal-body text-center">
                            <h4 class="modal-title">Multi Locations</h4>
                            <h4 class="modal-title">$0 first 50 location</h4>
                            <h4 class="modal-title">$15 each additional location</h4>
                        </div>
                        <div class="modal-footer">
                            <div class="text-center">
                                <a href="<?php echo base_url()."cms/premium/upgrade" ?>" class="btn btn-success ">Premium</a>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div>
            </div>
        <?php }elseif( $subitems==0){  ?>
            <div class="row text-center">
                <div class="">
                    <h3><b>No Upgrade available.</b></h3>
                </div>
            </div>
        <?php }  ?>
    </div>
</div>


