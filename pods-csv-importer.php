<?php
/*
Plugin Name: CSV Importer For Pods
Plugin URI: 
Description: A CSV Importer for Pods. You can find the plugin's section in Tools -> CSV Importer For Pods
Version: 0.7
Author: Daniel López - dlopezgonzalez@gmail.com
Author URI: http://www.devlopez.com
License: GPL2
*/
include_once("parsecsv.lib.php");
//HOOKS
add_action('init','pods_csv_importer_init');

function pods_csv_importer_init(){   

}

add_action( 'admin_menu', 'register_pods_csv_importer_menu_page' );

function register_pods_csv_importer_menu_page(){
    //add_menu_page( 'Pods CSV Importer', 'Pods CSV Importer', 'manage_options', 'podcsvsimporter', 'pods_csv_importer_admin_page', '', 6 ); 
    add_management_page('CSV Importer For Pods', 'CSV Importer For Pods', 'manage_options', 'csv-importer-for-pods', 'pods_csv_importer_admin_page');
}

function pods_csv_importer_admin_page(){
  
?>
<div class="wrap">
<h2><?php echo __('CSV Importer for Pods 0.7','csv-importer-for-pods'); ?></h2>
<p class="description"><?php echo __('This plugin only works with <a href="http://wordpress.org/plugins/pods/" target="_blank">Pods</a> installed.','csv-importer-for-pods'); ?></p>
<h3 class="title"><?php echo __('0. See the csv format of your pod','csv-importer-for-pods'); ?></h3>
<table class="form-table">
  <tbody>  
    <tr valign="top">
      <th scope="row"><label for="podname"><?php echo __('What is the format of my pod?','csv-importer-for-pods'); ?></label></th>
      <td>
		<input name="podnametoformat" id="podnametoformat" value="" class="regular-text" type="text">
		<input id="format" class="button button-primary" value="See the csv format" type="submit">
		<p class="description"><?php echo __('Write the pod name to see the csv format. It is necessary al least one item inserted manually to see the format.','csv-importer-for-pods'); ?>.</p>
		<span><strong><?php echo __('CSV file format for this pod (Help information to build your own file):','csv-importer-for-pods'); ?></span></strong><br>
		<textarea rows="2" cols="50" readonly id="pod_csv_format"></textarea></p>	  
	  </td>
	</tr>
</table>
	
	  
<h3 class="title"><?php echo __('1. Select the csv file:','csv-importer-for-pods'); ?></h3>
<table class="form-table">
  <tbody>
    
    <tr valign="top">
      <th scope="row" colspan="2"><input type="radio" name="file_type" value="media" checked><strong><?php echo __('Use Wordpress uploader','csv-importer-for-pods'); ?></strong></th>
    </tr>
    
    <tr valign="top">
      <th scope="row">
        <label for="podname">
         	<?php echo __('File from media','csv-importer-for-pods'); ?>
        </label>
      </th>
      <td>
        <div class="uploader">
          <input type="text" name="settings[_unique_name]" id="fileurl" class="regular-text" readonly /><br>        
          <input class="button" name="_unique_name_button" id="_unique_name_button" value="<?php echo __('Upload or choose','csv-importer-for-pods'); ?>" /><br>
          <input type="text" name="fileid" id="fileid" hidden class="regular-text"/>
        </div>
      </td>
    </tr>
    
    <tr valign="top">
      <th colspan="2" scope="row"><input type="radio" name="file_type" value="ftp"><strong><?php echo __('Use file located in "wp-content/plugins/pod-csv-importer/files"','csv-importer-for-pods'); ?></strong></th>
    </tr>
    
    <tr valign="top">
      <th scope="row"><label for="filename"><?php echo __('Filename','csv-importer-for-pods'); ?></label></th>
      <td><input name="filename" id="filename" value="" class="regular-text" type="text"></td>      
    </tr> 
    
 </tbody>
</table>

<h3 class="title"><?php echo __('2. Select the pod and the pod parameters:','csv-importer-for-pods'); ?></h3>
<table class="form-table">
  <tbody>  
    <tr valign="top">
      <th scope="row"><label for="podname"><?php echo __('Pod name','csv-importer-for-pods'); ?></label></th>
      <td><input name="podname" id="podname" value="" class="regular-text" type="text">
	  <p class="description"><?php echo __('Write the name of the pod (ex: product)','csv-importer-for-pods'); ?></p>	  
	  </td>
	</tr>
    
    <tr valign="top">
      <th scope="row"><label for="podname"><?php echo __('Fields one-to-many in the pod(comma separated)','csv-importer-for-pods'); ?></label></th>
      <td><input name="mv_pod_fields" id="mv_pod_fields" value="" class="regular-text" type="text">
	  <p class="description"><?php echo __('If a field of the pod is a relation one-to-many field, you need put here the field name (column name).<br>' . 
										   'Then in the csv file, you need to put the values of the items related, separated by dot-comma (normally the value of the field must be "name" in the ACT pods and "post_title" in CPT).<br>' . 
										   'Example: If we have two pods, one called "garages" and the other "cars", we could add a garage with three cars with this csv:<br>'.
										   '<i>name,cars<br>'.
										   'name,"car1;car2;car3"<br></i>','csv-importer-for-pods'); ?></p>
	  </td>
    </tr>
    
    <tr valign="top">
      <th scope="row"><label for="maxnumber"><?php echo __('Amount of rows imported in each call','csv-importer-for-pods'); ?></label></th>
      <td>100<!--<input name="maxnumber" type="number" min="0" max="500" step="10" id="maxnumber" value="100" class="small-text">-->
	  <p class="description"><?php echo __('An import requires several calls if the file is big, csv importer will import 100 rows in each call.','csv-importer-for-pods'); ?></p></td>
    </tr>
    
	<tr valign="top">
      <th colspan="2" scope="row"><input type="radio" name="import_type" value="all" checked><strong><?php echo __('Import all rows','csv-importer-for-pods'); ?></strong>
	  <p class="description"><?php echo __('Import all rows of the file selected.','csv-importer-for-pods'); ?></p>
	  </th>
    </tr>
	
	<tr valign="top">
      <th colspan="1" scope="row"><input type="radio" name="import_type" value="indexes"><strong><?php echo __('Import rows by indexes','csv-importer-for-pods'); ?></strong>
	  </th>
	  <td>
	  <p><label for="podname"><?php echo __('Start index: ','csv-importer-for-pods'); ?></label><input style=" width: 8%; " name="ib" id="ib" value="1" class="regular-text" type="text">
	  <label for="podname"><?php echo __('End index: ','csv-importer-for-pods'); ?></label><input style=" width: 8%; " name="ie" id="ie" value="100" class="regular-text" type="text">
	  <p class="description"><?php echo __('Import rows from a start row number to an end row number of the file selected.','csv-importer-for-pods'); ?></p>
	  </p>
	  </td>
	</tr>
	
	<tr valign="top">
      <th scope="row">        
      </th>
      <td>        
        <label for=""><input name="submit" id="import" class="button button-primary" value="Import Pods" type="submit"></label>
		<span class="executing"></span>
		<p class="description"><?php echo __('Wait until you see the message "Finish!"', 'csv-importer-for-pods'); ?></p>
      </td>
    </tr>
    <!--
    <tr valign="top">
      <th scope="row">        
      </th>
      <td>        
        <label for=""><input name="submit" id="import-by-index" class="button button-primary" value="Import Pods By Index" type="submit"></label>
		<span class="executing"></span>
      </td>
    </tr>
    -->
    <tr valign="top">
      <th scope="row">
        <label for="podname">
         	<?php echo __('Results','csv-importer-for-pods'); ?>
        </label>
      </th>
      <td>
      	<div id="result" style="background: lightgrey; width: 100%;">
         	Here you will see the messages of the import process.
        </div>
		<p class="description">
      </td>
    </tr>    
	
	<tr valign="top">
      <th scope="row">
        <label for="podname">
         	<?php echo __('Logs','csv-importer-for-pods'); ?>
        </label>
      </th>
      <td>      	
		<p class="description"><?php echo __('See the last log here:', 'csv-importer-for-pods'); ?><a href="<?php echo plugin_dir_url( __FILE__ ).'import-log.txt'; ?>">import-log.txt</a>
      </td>
    </tr>    

	<tr valign="top">
      <th scope="row">
        <label for="podname">
         	<?php echo __('Send your comments to the author','csv-importer-for-pods'); ?>
        </label>
      </th>
      <td>
      	<label>
         	dlopezgonzalez@gmail.com
        </label>
      </td>
    </tr>  	
    
  </tbody>
  <?php
  	wp_enqueue_media();
  ?>
  
<script>
	jQuery(document).ready(function() {	

		var import_path = "<?php echo plugin_dir_url( __FILE__ ).'import-pod.php'; ?>";
		var format_path = "<?php echo plugin_dir_url( __FILE__ ).'show-fields.php'; ?>";
		var $ = jQuery;
	    
	  var offset = 100;
	    
		function notify_execution(){
			$("span.executing").html('<img class="waiting" src="<?php echo get_site_url() . "/wp-admin/images/wpspin_light.gif"; ?>" alt="" style="display: inline;">');
			$("input#import").attr("disabled", "disabled");
			$("input#import-by-index").attr("disabled", "disabled");
		}
		
		function notify_finish(){
			$("span.executing").html("");
			$("input#import").removeAttr("disabled");
			$("input#import-by-index").removeAttr("disabled");			
		}
		function import_csv(pod, mv_pod_fields, type, file, ib, ie, finish){
		
			var call = false;

			var data = { action: 'import_pod', pod: pod, mv_pod_fields: mv_pod_fields, ib: ib, ie: ie, type: type, file: file};
			$.get( ajaxurl, data )
			.done(function( data ) {
			
				var obj = null;
				
				try{
					obj=JSON.parse(data);
				}catch(e){
					$("div#result").append("Error: " + data);	
					obj = null;
				}
				
				if(obj != null){
					if(obj.success){
						var msg = obj.msg + "<br>";
						$("div#result").append(msg);		
						if(!obj.end){
							if(!finish){
								import_csv(pod, mv_pod_fields, type, file, ib + offset, ie + offset, finish);
							}else{
								notify_finish();
							}
						}else{
							$("div#result").append("Finish!");
							notify_finish();
						}
					}else{
						$("div#result").append(obj.msg);
						notify_finish();
					}
				}else{
					notify_finish();
				}				
			})
			.fail(function() {           
				$("div#result").append("Error");		
				notify_finish();
			});         
		}
		
		$("input#format").click(function(e) { 
			var pod = $("input#podnametoformat").val();
			var data = { action: 'show_fields', pod: pod};
			$.get( ajaxurl, data )
			.done(function( data ) {
				$("textarea#pod_csv_format").html(data);
			})
			.fail(function() {           
				$("label#pod_csv_format").html("Error retrieving the format for pod '" + pod + "'");		
			});  
		});
		  
		$("input#import").click(function(e) {       
	      
			var pod = $("input#podname").val();
			var mv_pod_fields = $("input#mv_pod_fields").val();
			var type = $('input[name=file_type]:checked').val();
			var file = "";
			if(type == "media")
				file = $("input#fileid").val();
			else if("ftp")
				file = $("input#filename").val();
				
			var import_type = $('input[name=import_type]:checked').val();
			
			var ib = 0 + 1;
			var ie = 0 + offset;
			var finish = false;
			
			if(import_type == "indexes"){
				ib = $("input#ib").val();
				ie = $("input#ie").val();
				
				if(isNaN(parseInt(ib)) || 
				   isNaN(parseInt(ie)))
				{   
					$("div#result").html('Index entries are incorrect');
					return;
				}			
				finish = true;
			}
			
			notify_execution();
			$("div#result").html('');
			import_csv(pod, mv_pod_fields, type, file, ib, ie, finish);
	      
		});
	        
    var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment;

    //$('.stag-metabox-table .button').click(function(e) {
    $('input[id=_unique_name_button]').click(function(e) {
    
      var send_attachment_bkp = wp.media.editor.send.attachment;
      var button = $(this);
      _custom_media = true;
      wp.media.editor.send.attachment = function(props, attachment){
        if ( _custom_media ) {
          $("input#fileurl").val(attachment.url.replace("<?php echo get_site_url(); ?>",""));
          $("input#fileid").val(attachment.id);
        } else {
          return _orig_send_attachment.apply( this, [props, attachment] );
        };
      }
  
      wp.media.editor.open(button);
      return false;
    });
  
    $('.add_media').on('click', function(){
      _custom_media = false;
    });

			  
	});
</script>
</table>
</div>
<?php
}

//Wordpress ajax api
add_action( 'wp_ajax_show_fields', 'show_fields_callback' );

function show_fields_callback() {
	$pod_name = "";

	if(isset($_GET["pod"]) && $_GET["pod"]){
	   $pod_name = $_GET["pod"];  
	}
	else
	{
	   echo "Please, enter pod parameter";
	   exit;
	}

	$params = array();
	$pods = pods( $pod_name, $params );
	$data = $pods->export(); 

	if($data == null || count($data) == 0){
		echo __('Sorry, or your pod does not exist or you need to add one item manually of that pod to see the format.', 'csv-importer-for-pods');
		return;
	}
	
	foreach(array_keys($data) as $key)
	{
	   echo '"'.$key.'",';
	}
	echo "\r\n";
	foreach(array_keys($data) as $key)
	{
	   echo '"'.$pods->display($key).'",';
	}
	
	return; // this is required to return a proper result
}

add_action( 'wp_ajax_import_pod', 'import_pod_callback' );

function import_pod_callback() {

	

	$meta_key_prefix = "meta_key_";
	$log_filename = "import-log.txt";

	$pod_name = "";
	$file_name = "";
	$file_id = "";
	$file_type = "";
	$multivalue_pod_fields = "";
	$ib = "";
	$ie = "";

	if(isset($_GET["pod"]) && $_GET["pod"] && 
	   isset($_GET["ib"]) && $_GET["ib"] && 
	   isset($_GET["ie"]) && $_GET["ie"] && 
	   isset($_GET["file"]) && $_GET["file"]){
	  
		$pod_name = $_GET["pod"];  
		$file_name = $_GET["file"]; 
		$file_id = $_GET["file"]; 
		$file_type = $_GET["type"]; 
		$multivalue_pod_fields = (isset($_GET["mv_pod_fields"]) && $_GET["mv_pod_fields"]) ? $_GET["mv_pod_fields"] : ""; 
		$ib = intval($_GET["ib"]);  
			$ie = intval($_GET["ie"]);
	  
			if($ib == 0 || $ie == 0){
		  echo "Please, enter ie and ib";
			exit;
		}    
	  
			if(!($file_type === "ftp" || $file_type === "media")){
			echo "Please, select a correct file selection";
			exit;
		}   
	}
	else
	{
	   echo json_encode(array('success' => false, 'msg' => 'Please enter a valid filename and a valid pod name'));
	   exit;
	}

	if($file_type === "ftp"){
	  
	  $pod_file_path = plugin_dir_path( __FILE__ )."files/".$pod_name;
	  $multivalue_pod_file_path = plugin_dir_path( __FILE__ )."files/".$pod_name . "_multivalues.txt";
	  
	} else if($file_type === "media") {
	  
	  $pod_file_path = get_attached_file($file_id);
	  $multivalue_pod_file_path = get_attached_file($multivalue_file_id);
	  
	}

	$multivaluekeys = array();
	if($multivalue_pod_fields !== ""){
	  $multivaluekeys = explode(",",$multivalue_pod_fields);
	}

	//echo json_encode(array('success' => false, 'msg' => $pod_file_path ));
	//exit;

	//print_r($multivaluekeys);

	if(!file_exists ($pod_file_path)){
	  echo json_encode(array('success' => false, 'msg' => "File '$pod_file_path' cannot be found"));
	  exit;
	}

	$api = pods_api( $pod_name ); 

	if(!$api->pod_exists(array('name'=>$pod_name))){
		echo "The pod '$pod_name' is not exist";
		exit;
	}

	$csv = new parseCSV();
	$csv->delimiter = ",";
	$csv->parse($pod_file_path);

	$total = count($csv->data);

	$ib--;
	$ie--;

	file_put_contents($log_filename, "Begin importing pod '$pod_name' from rows $ib - $ie of file '$pod_file_path' \r\n", FILE_APPEND | LOCK_EX);

	if($pod_name !== "user" && $pod_name !== "image")
	{
		   
		for ($i = 0; $i < $total; $i++){  
		  
		  if($ib <= $i && $i <= $ie)
		  {
		
			$row = $csv->data[$i];
			
			$data = array();    
			$pod_array = array();
			
			foreach ($row as $key => $value){
			
				if(in_array($key, $multivaluekeys)){
					$values = explode(";", $value);
					$pod_array[$key] = $values;
				}
				else{
					$pod_array[$key] = $value;
				} 
			  
			}  
					
			$data[] = $pod_array;  
			$ids = $api->import( $data ); 		
						
			//Add meta key
			for($k = 0; $k < count($ids); $k++){
				
				if($data[$k]["post_type"] === "post"){			
					
					foreach($data[$k] as $key => $value){
						//Starts with "meta_key_"
						if(strpos($key, $meta_key_prefix) === 0){
							add_post_meta(	$ids[$k], 
											str_replace($meta_key_prefix, "", $key), 
											$value);
						}						
					}	
					//Add existing feature_image
					if(isset($data[$k]["feature_image"])){
						add_feature_image_to_post($data[$k]["feature_image"], $ids[$k]);
					}
				}else{
				}
			}
			
			file_put_contents($log_filename, "Row number $i imported\r\n", FILE_APPEND | LOCK_EX);
		
		  }
		  
		  if($ie < $i)
			break;
		}
	}
	else if($pod_name === "user")
	{
		for ($i = 0; $i < $total; $i++){  
		  
		  if($ib <= $i && $i <= $ie)
		  {
		
			$row = $csv->data[$i];
			
			$user_data = array();
			$user_data["pw_user_status"] = "";
		
			foreach ($row as $key => $value){     
			   $user_data[$key] = $value;
			}  
		
			$user_data['ID'] = '';
			
			$user_id = wp_insert_user( $user_data );
			if($user_data["pw_user_status"] !== ""){
			   update_user_meta( $user_id, "pw_user_status", $user_data["pw_user_status"]);
			}	    
		 
			file_put_contents($log_filename, "User '" . $user_data['user_login'] . "' imported\r\n", FILE_APPEND | LOCK_EX);
		
		  }
		  
		  if($ie < $i)
			break;
		}
	}
	else if($pod_name === "image")
	{
		for ($i = 0; $i < $total; $i++){  
		  
			if($ib <= $i && $i <= $ie)
			{
				$row = $csv->data[$i];
				$image_title = isset($row["image_title"]) ? $row["image_title"] : null;
				$image_url = isset($row["image_url"]) ? $row["image_url"] : null;
				
				if($image_title != null && $image_url != null){
					$aux = pathinfo($image_url);
					$image_filename = $image_title . "." . $aux["extension"];
					url_to_media_library($image_url, 
										 $image_filename,
										 $image_title,
										 $image_title);
				}
			}
			
		}
	}

	$end = false;
	if($total <= $ie)
	  $end = true;

	$ib++;
	$ie++;
						
	file_put_contents($log_filename, "End importing pod '$pod_name' from rows $ib - $i of file '$pod_file_path' \r\n", FILE_APPEND | LOCK_EX);

	echo json_encode(array('success' => true, 'msg' => date('H:i:s') . " Importing of pod '$pod_name' from rows $ib - $i of file '$pod_file_path'" .  " ($extra_msg)", 'last_index' => $i, 'end' => $end));

	
	die(); // this is required to return a proper result
}
?>