<div class="mainbox" id="popup_domination_tab_look_and_feel">
    <h3 class="title topbar icon feel"><span>Select Campaigns</span></h3>
          <h4><a id='clear-ab-cookie' href="#">Clear Cookie for this test</a></h4>

    <div class="inside">
        <table>
        	<?php $ticked = array();
        	if(!empty($campdata)):
            	foreach ($campdata as $campaign){
            		if (isset($split['campaigns']) && !empty($split['campaigns'])){
	    				foreach ($split['campaigns'] as $ab_id){
	            				if($ab_id == $campaign->id){
		            				$ticked[$ab_id] = true;
	            				}
	            		}
            		}
            	}
        		foreach ($campdata as $offset => $campaign):
					if ($ticked[$campaign->id]):?>
        		<tr><td><input checked="checked" type="checkbox" name="campaign[]" value="<?php echo $campaign->id; ?>" /><?php echo $campaign->campaign; ?></td></tr>
					<?php else: ?>
            	<tr><td><input type="checkbox" name="campaign[]" value="<?php echo $campaign->id; ?>" /><?php echo $campaign->campaign; ?></td></tr>
    				<?php endif;
        		endforeach;
        	endif;?>
    	</table>
    </div>
    <div class="clear"></div>
</div>