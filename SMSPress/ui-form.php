<?php 
if(isset($_POST['send_sms'])):
$SelectedAPI=get_option('current_sms_api');
$file=dirname(__FILE__)."\classes\api\\".$SelectedAPI.".php";
require_once dirname(__FILE__)."\classes\SMS.php";
require_once dirname(__FILE__)."\classes\SMSAPI.php";
require $file;

$SMSClass=new SMS();
$class=new $SelectedAPI();
$SMSClass->setApiGateway($class);
$SMSClass->send();
$errorMessage=$SMSClass->getMessage();
endif;
?>
<script>
var dojoConfig={
async:true,
};
</script>
<?php 
$css=get_bloginfo("wpurl")."/wp-content/plugins/SMSPress/js/themes/claro/claro.css";
?>
<link rel="stylesheet" type="text/css" href="<?php echo $css ?>">
<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2>SMSPress Setup</h2>
<h3>Send SMS</h3>
<hr/>

	<div data-dojo-type="dijit/form/Form" id="myForm" data-dojo-id="myForm"
encType="multipart/form-data" action="" method="post">
		<table style="width:500px">
			<tr>
				<td>Mobile: </td>
				<td><input type="text" data-dojo-type="dijit/form/ValidationTextBox"
				 data-dojo-props="regExp:'[^a-zA-Z]{13}', invalidMessage:'Phone Number must not be less than 11 Characters',required:true" style="width:100%" name="mobile" id="mobile" value="<?php echo $_SESSION['mobile'] ?>" /></td>
			</tr>
			<tr>

				<td valign="top">Message: </td>
				<td>Character Remains: <span id="char_count"><?php echo get_option('maximum_characters'); ?></span>
				<br />
				<textarea name="message" data-dojo-type="dijit/form/Textarea" data-dojo-props="required:true" style="width:100%; height:100px" id="message" max="<?php echo get_option('maximum_characters'); ?>"><?php echo $_SESSION['message'] ?></textarea></td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" id="submit" name="send_sms" class="button-primary" value="Send SMS" /></td>
			</tr>
		</table>
		<?php 
		if(isset($errorMessage)):
		?>
		<div id="error" class="updated settings-error">
		<p><?php echo $errorMessage; ?></p>
		</div>
		<?php 
		endif;
		?>
		<?php wp_nonce_field('send_sms','send_sms_nonce'); ?>
	</div>

</div>
<script>
require(['dojo/ready','dojo/parser','dijit/registry','dojo/dom-style'],function(ready,parser,dijit,style){
ready(function(){
function triggerForm(form){
	var Widget=form;
}
dojo.body().className+=" claro";
parser.parse();
if(dijit.byId("message")){
var message=dijit.byId("message");

message.on("Input",function(evt){

var LengthMessage=(message.get('value').length+ 1);
document.getElementById('char_count').innerHTML=LengthMessage;	
var isFilled=(LengthMessage >= 140) ? true: false;
if(LengthMessage >= 130){
style.set(message,"color","red");
}
else
{
style.set(message,"color","black");
}
if(isFilled && evt.keyCode != 8){
evt.preventDefault();
return false

}
else{
return true;
}
});
}	
});
});
</script>