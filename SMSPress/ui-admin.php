<?php
	function getFiles(){
		$dataAPI=array();
		$file=dirname(__FILE__)."\classes\api";
		$directory=new DirectoryIterator($file);
		foreach ($directory as $key => $value) {
			if($value->isDot()):
			continue;
			endif;
			$dataAPI[]=$value->getBasename('.php');
		}
		return $dataAPI;
	}
	$dataAPI=array();
    //If API has been Selected retrieve from DB else just get from files
    if(!isset($_GET['smspress_api_selected'])):
    //fetch the list from the API
    $dataAPI=getFiles();
    else:
    $dataAPI=getFiles();
    $getSelectedClass=$_GET['smspress_api_selected'];
    require_once "classes/SMSAPI.php";
    require "classes/api/".$getSelectedClass.".php";
    $class=new $getSelectedClass();
    $fieldArrays=$class->getHTMLFields();
    endif;
	
?>

<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2>SMSPress Setup</h2>
<h3>Configure Settings for the <?php echo $getSelectedClass ?> API </h3>
<hr/>
<?php
if(isset($_POST['settings_submit']) && $_POST['settings_submit']=='true')
	{
		//add_option( $option, $value, $deprecated, $autoload );
		$options=$_POST;
		foreach($options as $option=>$value) 
		{
			update_option( $option, $value );
		}
		 echo "<pre>";
		print_r($options);
		echo "</pre>";
		//set as current API
		update_option("current_sms_api",$options['api_selected']);
	}
?>
<form action="" method="post">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="wpsms_api1"><strong>Select API:</strong> <br /> Select the SMS Api you are using</label></th>
				<td>
				<select name="smspress_api_selected" id="smspress_api_selected" class="regular-text" 
				onchange='document.location="<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin.php?page=smspress&smspress_api_selected="+this.value'>
				<option>Select an API</option>
				<?php
				foreach ($dataAPI as $key => $value): 
				?>
				<option value="<?php echo $value?>"><?php echo $value ?></option>
				<?php
				endforeach; 
				?>

				</select>
				<p><strong>Current API:</strong>&nbsp;<?php echo get_option("current_sms_api","None Selected");?></p>
				</td>

			</tr>
			<?php 
			if($fieldArrays):
			foreach($fieldArrays as $field):?>
			<tr valign="top">
				<th scope="row"><label for="remove_bad_words"><strong><?php echo ucfirst($field) ?>: </strong></label></th>
				<td><input type="text" name="<?php echo $getSelectedClass ?>_<?php echo $field ?>" id="<?php echo $field?>"
				value="<?php echo get_option($getSelectedClass._.$field)?>"
				 </td>
			</tr>
			<?php endforeach;
			endif;
			?>
			
			
			
		</tbody>
	</table>
	<br />
	<input type="hidden" name="api_selected" value="<?php echo $getSelectedClass ?>" />
	<input type="hidden" name="settings_submit" value="true" />
	<input type="submit" value="Update Settings" class="button-primary" />
	<div>
		<p>Updating Settings will Set this as the Current API to use !</p>
	</div>
</form>
</div>