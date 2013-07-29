<?php
/*  
    Copyright (c) 2011-2013 www.mysolrserver.com

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
*/
//require_once("advanced-search-by-my-solr-server.inc.php");

//get the plugin settings
$options = mss_get_option();
#set defaults if not initialized
if ($options['mss_solr_initialized'] != 1) {
	$options2['mss_solr_initialized'] = 1;
	$options2['mss_connect_type'] = 'selfhosted';
	$options2['mss_solr_host'] = 'localhost';
	$options2['mss_solr_port'] = 8983;
	$options2['mss_solr_path'] = '/solr';
	$options2['mss_num_results'] = 10;
	$options2['mss_max_display_tags'] = 5;

	$options = $options2;
	//save our options array
	mss_update_option($options);
}

wp_reset_vars(array('action'));

// checks if we need to check the checkbox
function mss_checkCheckbox( $options, $fieldName ) {
	if (isset($options[$fieldName]) && $options[$fieldName]=='1') echo 'checked="checked"';
	
  //if( $fieldValue == '1'){
  //  echo 'checked="checked"';
  //}
}

function mss_checkCheckboxInGroup( $groupValues, $checkboxValue ) {
	$aValues = explode(',', $groupValues);
	if (in_array($checkboxValue, $aValues))
		echo 'checked="checked"';
}

function mss_checkConnectOption($optionType, $connectType) {
    if ( $optionType === $connectType ) {
        echo 'checked="checked"';
    }
}
?>
  
 <div class="wrap">
  	<div id="icon-options-general" class="icon32"><br /></div>
	<div id="mss_admin">
  		<h2>Search via Searchbox-Server Settings</h2>
		<p><strong>Search via Searchbox-Server</strong> plugin replaces the default WordPress search with powerfull <strong>Solr search</strong>.</p>
<?php 
$mss_id = $options['mss_id'];
$mss_passwd = decrypt($options['mss_passwd']);
$mss_url = $options['mss_url'];

$mss_proxy = $options['mss_solr_proxy'];
$mss_proxyport = $options['mss_solr_proxyport'];
$mss_proxyusername = $options['mss_solr_proxyusername'];
$mss_proxypassword = decrypt($options['mss_solr_proxypassword']);

global $url_mysolrserver;
global $url_extraparam ;

$connected = false;

?>		
		<form id='mss_form'>

		<h3><?php _e('Proxy setting - Optional (for http connection to Solr server) ', 'solrmss') ?></h3>
		<table class="form-table">
		    <tr valign="top">
		        <th scope="row" style="width:200px; padding:0px;"><?php _e('Proxy address', 'solrmss') ?></th>
		        <td style="float:left; margin-bottom:0px; padding:0px;">
		        	<input type="text" id="mss_solr_proxy" name="settings[mss_solr_proxy]" value="<?php print($mss_proxy); ?>" />
		        </td>
			</tr>
		    <tr valign="top">
		        <td scope="row" style="width:200px; padding:0px;"><?php _e('Proxy port', 'solrmss') ?></th>
		        <td style="float:left; margin-bottom:0px; padding:0px;">
		        	<input type="text" id="mss_solr_proxyport" name="settings[mss_solr_proxyport]" value="<?php print($mss_proxyport); ?>" />
		        </td>
			</tr>
		    <tr valign="top">
		        <th scope="row" style="width:200px; padding:0px;"><?php _e('Proxy username (optional)', 'solrmss') ?></th>
		        <td style="float:left; margin-bottom:0px; padding:0px;">
		        	<input type="text" id="mss_solr_proxyusername" name="settings[mss_solr_proxyusername]" value="<?php print($mss_proxyusername); ?>" />
		        </td>
			</tr>
		    <tr valign="top">
		        <th scope="row" style="width:200px; padding:0px;"><?php _e('Proxy password (optional)', 'solrmss') ?></th>
		        <td style="float:left; margin-bottom:0px; padding:0px;">
		        	<input type="password" id="mss_solr_proxypassword" name="settings[mss_solr_proxypassword]" value="<?php print($mss_proxypassword); ?>" />
		        </td>
			</tr>
					<tr>
						<td class="label">&nbsp;</td>
						<td><input class="button-primary" type="button" name="mss_btn_save_proxy" id="mss_btn_save_proxy" value="Apply Changes" /><span id="mss_save_proxy_status"></span></td>
					</tr>
			</table>

		<h3><?php _e('Configure Solr', 'solrmss') ?></h3>
		
		<div class="solr_admin clearfix">
			<div class="solr_adminR">
							
				<div class="solr_adminR2">

				<div id="solr_admin_tab_selfhosted">
					<h3><?php _e('Solr instance settings', 'mss') ?></h3>
					<label><?php _e('Solr Host', 'solrmss') ?></label>
					<input type="text" id="mss_solr_host" name="settings[mss_solr_host]" value="<?php _e($options['mss_solr_host'], 'solrmss'); ?>" /><br /><br />
					<label><?php _e('Solr Port', 'solrmss') ?></label>
					<input type="text" id="mss_solr_port" name="settings[mss_solr_port]" value="<?php _e($options['mss_solr_port'], 'solrmss'); ?>" style="width:50px;" /><br /><br />
					<label><?php _e('Solr Path', 'solrmss') ?></label>
					<input type="text" id="mss_solr_path" name="settings[mss_solr_path]" value="<?php _e($options['mss_solr_path'], 'solrmss'); ?>" style="width:350px;" /><br /><br />
				    <tr valign="top">
				        <td><input type="button" class="button-primary" name="mss_btn_ping" value="<?php _e('Check Solr instance settings', 'solrmss') ?>" /><span id="mss_ping_status"></span></td>
				    </tr>
				</div>
				</div>
			</div>
		</div>
		<hr />	
		
		<h3><?php _e('Indexing Options', 'solrmss') ?></h3>
		<table class="form-table">
		    <tr valign="top">
		        <th scope="row" style="width:200px;"><?php _e('Post types to be indexed', 'solrmss') ?></th>
		        <td style="float:left;">
		        <span class="nobr"><input type="checkbox" name="post_types" value="post" <?php mss_checkCheckboxInGroup( $options['mss_post_types'], "post" ); ?> />&nbsp;Posts&nbsp;&nbsp;</span>
		        <span class="nobr"><input type="checkbox" name="post_types" value="page" <?php mss_checkCheckboxInGroup( $options['mss_post_types'], "page" ); ?> />&nbsp;Pages&nbsp;&nbsp;</span>		        		        
<?php 		        
	$args=array(
  		'public'   => true,
  		'_builtin' => false
	); 
	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'
	$post_types=get_post_types($args,$output,$operator); 
	if ($post_types) {
  		foreach ($post_types  as $post_type) {
 ?>
  		<span class="nobr"><input type="checkbox" name="post_types" value="<?php echo $post_type;?>" <?php mss_checkCheckboxInGroup( $options['mss_post_types'], $post_type ); ?> />&nbsp;<?php echo ucfirst($post_type); ?>&nbsp;&nbsp;</span>
 <?php  		  		
 		}
	}
 ?>		      
 				<input type="hidden" id="mss_post_types" name="settings[mss_post_types]" value="" />  
		        </td>
		    </tr>
 		    <tr valign="top">
		        <th scope="row" style="width:200px;"><?php _e('Custom Taxonomies to be indexed', 'solrmss') ?></th>
		        <td style="float:left;">
<?php 	
 	$args=array(
	  'public'   => true,
	  '_builtin' => false
	
	);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies=get_taxonomies($args,$output,$operator);
	if  ($taxonomies) {
		foreach ($taxonomies  as $taxonomy ) {
 ?>
  		<span class="nobr"><input type="checkbox" name="custom_taxonomies" value="<?php echo $taxonomy;?>" <?php mss_checkCheckboxInGroup( $options['mss_custom_taxonomies'], $taxonomy ); ?> />&nbsp;<?php echo ucfirst($taxonomy); ?>&nbsp;&nbsp;</span>
 <?php  		  		
  		}
	}
	else {
?>
none	
<?php
	}
 ?>		        
 				<input type="hidden" id='mss_custom_taxonomies' name="settings[mss_custom_taxonomies]" value="" />  
		        </td>
		    </tr>
		    <tr valign="top">
		        <th scope="row" style="width:200px;"><?php _e('Custom Fields to be indexed', 'solrmss') ?></th>
		        <td style="float:left;">
<?php 			
	global $wpdb;
	$limit = (int) apply_filters( 'postmeta_form_limit', 30 );
	$keys = $wpdb->get_col( "
			SELECT meta_key
			FROM $wpdb->postmeta
			GROUP BY meta_key
			HAVING meta_key NOT LIKE '\_%'
			ORDER BY meta_key" );

	if ($keys) {
		foreach ( $keys as $key ) {
 ?>
  		<span class="nobr"><input type="checkbox" name="custom_fields" value="<?php echo $key?>" <?php mss_checkCheckboxInGroup( $options['mss_custom_fields'], $key ); ?> />&nbsp;<?php echo ucfirst($key); ?>&nbsp;&nbsp;</span>
 <?php  		  		
		}
	}
	else {
?>
none	
<?php
	}
 ?>		        
 				<input type="hidden" id='mss_custom_fields' name="settings[mss_custom_fields]" value="" />  
		        </td>
		    </tr>
		    <tr valign="top">
		        <th scope="row" style="width:200px;"><?php _e('Index Comments', 'solrmss') ?></th>
		        <td style="width:10px;float:left;"><input type="checkbox" name="settings[mss_index_comments]" value="1" <?php echo mss_checkCheckbox($options,'mss_index_comments'); ?> /></td>
		    </tr>
		    <tr valign="top">
		        <th scope="row"><?php _e('Exclude items (posts, pages, ...)<br />(comma separated ids list)') ?></th>
		        <td><input type="text" name="settings[mss_exclude_pages]" value="<?php echo $options['mss_exclude_pages']; ?>" style="width:400px;"/></td>
		    </tr>
 		</table>
		
		<br />
		<h3><?php _e('Result Options', 'solrmss') ?></h3>
		<table class="form-table">
		    <tr valign="top">
		        <th scope="row" style="width:200px;"><?php _e('Enable Spellchecking', 'solrmss') ?></th>
		        <td style="width:10px;float:left;"><input type="checkbox" name="settings[mss_enable_dym]" value="1" <?php echo mss_checkCheckbox($options,'mss_enable_dym'); ?> /></td>
		        <th scope="row" style="width:200px;float:left;margin-left:20px;"><?php _e('Output Result Info', 'solrmss') ?></th>
		        <td style="width:10px;float:left;"><input type="checkbox" name="settings[mss_output_info]" value="1" <?php echo mss_checkCheckbox($options,'mss_output_info'); ?> /></td>
		    </tr>
		    
		    <tr valign="top">
		        <th scope="row"><?php _e('Number of Results Per Page', 'solrmss') ?></th>
		        <td><input type="text" name="settings[mss_num_results]" value="<?php echo $options['mss_num_results']; ?>" style="width:50px;"/></td>
		    </tr>   
<!--		    
		    <tr valign="top">
		        <th scope="row"><?php _e('Max Number of Tags to Display', 'solrmss') ?></th>
		        <td><input type="text" name="settings[mss_max_display_tags]" value="<?php echo $options['mss_max_display_tags']; ?>" style="width:50px;"/></td>
		    </tr>
-->	 
		    <tr valign="top">
		        <th scope="row" style="width:200px;"><?php _e('Output Facets', 'solrmss') ?></th>
		        <td style="width:10px;float:left;"><input type="checkbox" name="settings[mss_output_facets]" value="1" <?php echo mss_checkCheckbox($options,'mss_output_facets'); ?> /></td>
		        <th scope="row" style="width:200px;float:left;margin-left:20px;"><?php _e('Category Facet as Taxonomy', 'solrmss') ?></th>
		        <td style="width:10px;float:left;"><input type="checkbox" name="settings[mss_cat_as_taxo]" value="1" <?php echo mss_checkCheckbox($options,'mss_cat_as_taxo'); ?> /></td>
		    </tr>
		</table>
		    
		<br />
		<h3><?php _e('Facets Options', 'solrmss') ?></h3>
		<table class="facet-form-table">
	    	<tr valign="top">
	        	<td scope="row" style="width:200px;">
	        		<h4>Available items for facets</h4>
	        		<div id='available_facets'></div>
	        	</td>
	        	<td scope="row" style="width:200px;">
	        		<h4>Selected items for facets</h4>
	        		<!--
	        		<?php _e('Include these selected items in search scope', 'solrmss') ?>
		            <input type="checkbox" name="settings[mss_facets_search]" value="1" <?php echo mss_checkCheckbox($options,'mss_facets_search'); ?> />
	        		<br />&nbsp;
	        		-->
	        		<div id='selected_facets'></div>
 					<input type="hidden" id='mss_facets' name="settings[mss_facets]" value="<?php echo $options['mss_facets']; ?>" />  
				</td>
	     	</tr>		                   
		</table>
		<br />
<!-- input type="hidden" name="action" value="saveall" -->
<input class="button-primary" type="button" name="mss_btn_save_options" id="mss_btn_save_options" value="Save Changes" /><span id="mss_save_option_status"></span>
		<hr />


</form>
		
		<h3><?php _e('Actions', 'solrmss') ?></h3>
				<table class="form-table">				 
				    <tr valign="top">
				        <td><input type="submit" class="button-primary" name="mss_btn_index" value="<?php _e('Load content', 'solrmss') ?>" /><span id="mss_index_status"></td>
				    </tr>				
				    <tr valign="top">
				        <td><input type="submit" class="button-primary" name="mss_btn_optimize" value="<?php _e('Optimize Index', 'solrmss') ?>" /><span id="mss_optimize_status"></td>
				    </tr>
				        
				    <tr valign="top">
				        <td><input type="submit" class="button-primary" name="mss_btn_deleteall" value="<?php _e('Delete All', 'solrmss') ?>" /><span id="mss_deleteall_status"></td>
				    </tr>
				</table>
				
				
	<?php //settings_fields('mss-options-group'); ?>


  	</div> <!-- id="mss_admin" -->
 </div>  <!-- class="wrap" -->
