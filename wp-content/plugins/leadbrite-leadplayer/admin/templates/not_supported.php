<div class="wrap bootstrap-lp-wpadmin container">
    <div class="row" style="margin-top:15px">
        <div class="span11">
            <img border="0" src="<?php echo LEADPLAYER_ABS_URL; ?>admin/img/lp_logo.png"><br><br>
        </div>
    </div>
    <div class="row">
        <div class="span11">
            
            <p><span class="label label-warning">Important!</span> &nbsp;Your system does not meet the minimum requirements to run LeadPlayer. See below for more specifics...</p>
            <BR><BR>           
            <p>
                <B>Specific Issues:</b>
                <ul>
                <?php
                    foreach($env_errors as $e){
                        echo '<li>'.htmlspecialchars($e,ENT_QUOTES).'</li>';
                    }
                ?>
                </ul>
            </p>
                                    
        </div>
    </div>
</div>
