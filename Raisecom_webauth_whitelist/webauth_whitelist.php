<?
	require_once('../php_class/um_class.php');
	require '/www/custom/language_resource/basic_um_language.php';
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type"> 
<link rel="stylesheet" href='../lib/indexprivate.css' type='text/css'>
<script src="../js_script/FormValid.js" language="javascript"></script>
<script src="../js_script/FV_onBlur.js" language="javascript"></script>
<script src="../js_script/function.js"></script>
<script src="../js_script/ajax_function.js"></script>

<script language="javascript"> 
FormValid.showError = function(errMsg,errName,formName) {
	if (formName=='addRule') {
		for (key in FormValid.allName) {
			document.getElementById('errMsg_'+FormValid.allName[key]).innerHTML = '';
		}
		for (key in errMsg) {
			document.getElementById('errMsg_'+errName[key]).innerHTML = errMsg[key];
		}
	}
}


function AddUrlFilter()
{
   var urladdr = document.getElementById("url");
 
	var re= /[^0-9]/;

	/*
	if (urladdr.value.length > 99)
	{
	    alert( 'Url 地址长度必须不超过99个字符');
	    return false;
	}
	else if(urladdr.value.indexOf(";") >= 0 | urladdr.value.indexOf("|")>= 0)
	{
		alert("您输入的过滤关键字中有非法字符！");
		return false;
	}
	else if(urladdr.value.indexOf(":") > 0)
	{
		if (urladdr.value.match("http://") != null)
		{
			urladdr.value = urladdr.value.substr(7);
		}
		else
		{
			alert("您输入的过滤关键字中有非法字符！");
			return false;
		}
	}
*/
	if (urladdr.value.length > 15)
	{
	    alert( "<?php echo_string_by_module('url_address_length', $um_list)//Url 地址长度必须不超过15个字符?>");
	    return false;
	}
	else if(urladdr.value.indexOf(";") >= 0 | urladdr.value.indexOf("|")>= 0
			|urladdr.value.indexOf("@") >= 0 |urladdr.value.indexOf("#") >= 0 
			|urladdr.value.indexOf("$") >= 0)
	{
		alert("<?php echo_string_by_module('filter_keyword', $um_list)//您输入的过滤关键字中有非法字符?>");
		return false;
	}
	else if(urladdr.value.indexOf(".") > 0)
	{
		var i = urladdr.value.indexOf(".");	
		var j = urladdr.value.indexOf(".",i+1);
		var k = urladdr.value.indexOf(".",j+1);
		if( (i>0) && (j>0) && (k>0) )
		{
			var ip0 = urladdr.value.substr(0,i);
			var ip1 = urladdr.value.substr(i,j);
			var ip2 = urladdr.value.substr(j,k);
			var ip3 = urladdr.value.substr(k+1);
		}
		else
		{
			alert("<?php echo_string_by_module('filter_keyword', $um_list)//您输入的过滤关键字中有非法字符 ?>");
			return false;
		}
	}
	if (urladdr.value.length == 0)
	{
		alert("<?php echo_string_by_module('inputIP', $um_list)//请输入正确的IP地址!?>");
		return false;
	}
	
/*
	var downFile = document.getElementById("resume");
	if (downFile.value.length > 0)
	{
	var fileTypeList = downFile.value.split(".");
	var fileType = fileTypeList[fileTypeList.length - 1];

	if (fileType != "txt")
	{
		alert("只允许上传TXT类型的文件！");
		return false

	}
}

if ((downFile.value.length == 0) && (urladdr.value.length == 0))
{
	alert("请输入正确的URL地址或选择需要上传的文件！");
	return false;
}
	*/
return true;
} 

function clickHeadCheckbox()
{ 
	var inputs = document.getElementById("ruleTable").getElementsByTagName("input");
	for (var i=0; i < inputs.length; i++)
	{
		if (inputs[0].checked)
		{
			inputs[i].checked = true;
		}
		else
		{
			inputs[i].checked = false;
		}
	}
}

function checkAll()
{
	var inputs = document.getElementById("ruleTable").getElementsByTagName("input");
	for (var i=0; i < inputs.length; i++)
	{		
		inputs[i].checked = true;		
	}
}

function uncheckAll()
{
	var inputs = document.getElementById("ruleTable").getElementsByTagName("input");
	for (var i=0; i < inputs.length; i++)
	{		
		inputs[i].checked = false;		
	}	
}

function delCheckedRule()
{
		var inputs = document.getElementById("ruleTable").getElementsByTagName("input");
			var span = document.getElementById("ruleTable").getElementsByTagName("span");
			var black_list = "";
			var white_list = "";
		var j = 0;	
			var ruleList = document.getElementById("ruleList");

	
	for (var i=1; i < inputs.length; i++)
	{		
		if(inputs[i].checked == true)
		{

        if(!span[j].innerHTML.indexOf("<?php echo_string_by_module('white_list', $um_list)//白名单?>")==true)
        {
        	white_list += span[j+1].innerHTML;
        	white_list += ";";
        }
               
		}				
		j += 2;
	}	
	
	
	ruleList.value = black_list + "|" + white_list;
}






</script>

</head>

<body onload="initValid(document.addRule);">
<?
	$menu_arr = array(get_string_by_module('authent_option', $um_list)=>'list_umwebauth.php',
				get_string_by_module('white_list', $um_list)=>'webauth_whitelist.php');
	$help_text ='';
	$notice_text = '';
	$cur_index = 1;
	template_header($menu_arr,$cur_index,$help_text,$notice_text);

$type ='';
$type = isset($_REQUEST['type'])? $_REQUEST['type']:$type;
$obj_filter = new um_class();
//echo "$parts 888  <br />";
//echo "$type   555 <br />";

switch($type)
{
		case 'add':
	    		//echo "$parts <br />";
	     //   echo "$type 11111111111<br />";
		    $obj_filter->webauth_whitelist_add();
		    break;	  
	    case 'del':
		    $obj_filter->webauth_whitelist_del();
		    break;


			    default:
		    break;
}	
?>


<DIV id=BODY> 
<DIV id=INNER>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td colspan="2" height="20">
     
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
							<td style="width:15px; background-color:#333;">&nbsp;</td>
							<td class="title_tbl_content"><?php echo_string_by_module('add_list', $um_list)//添加白名单 ?></td>
						</tr>
					</table></td></tr></tbody></table>
</DIV>




<DIV id=INNER align=left style="padding-left:15px;">  
<FORM id="addRule" name="addRule" method="post" enctype="multipart/form-data" action="webauth_whitelist.php?parts=webauth_whitelist&type=add" onSubmit="return validator(this);">
<INPUT type=hidden name=listType value=1 />
<div><TABLE id=ssid_tbl class=mlist cellSpacing=0 cellPadding=0 width="100%"> 
	
			<TR> 
				<TD width=130><?php echo_string_by_module('IP_addr', $um_list)//IP地址?></TD> 
		
				<td><input type="text" name="url" id="url" style="width:160px" valid="isIp" errmsg="<? echo_string_by_module('inputIP', $um_list)?>"/>
				<span class="red">*</span>
                   	<span id="errMsg_url" style="color:#FF0000"></span></td>
                   	
			</TR>
			
	

</TABLE>
 <?php 
/*提示信息*/

$title = get_string('asterisk');
alert_info($title);
?>
</div>

<DIV style="text-align:right; width:100%;  margin-top:5px;">
<INPUT  type=submit onclick="return AddUrlFilter()" class=buttonL id=apply value="<?php echo_string_by_module('add', $um_list)//增加?>" />
<tr>
</DIV>
</FORM>
</DIV>


<DIV id=INNER>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td colspan="2" height="5">
     
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
							<td style="width:15px; background-color:#333;">&nbsp;</td>
							<td class="title_tbl_content"><?php echo_string_by_module('delete_list', $um_list)//删除白名单?></td>
						</tr>
					</table></td></tr></tbody></table>
</DIV>





<DIV id=INNER align=left style="padding-left:15px">	
<FORM id="delRule" name="delRule" method="post" action="webauth_whitelist.php?parts=webauth_whitelist&type=del" onSubmit="return validator(this);">
<INPUT type=hidden id=ruleList name=ruleList value="" />  	 	
<DIV id=INNER>	

<?
		$pages =1;
		$pages =isset($_REQUEST['pages'])? $_REQUEST['pages']:$pages;
		$obj_filter = new um_class();
		//echo" pages= $pages <br />";
 		$obj_filter->webauth_whitelist_show($pages);
										 
?>
</DIV>

<div style="clear: both"></div>
<DIV id=INNER style="width:59%;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tbody>
	    <tr>
	      <td colspan="2" height="30">
	     
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
					  <!--
								<td><INPUT value="<?php echo_string_by_module('select_all', $um_list)//全部选中?>" type=button onclick='checkAll()' class=buttonL>
	                  <INPUT value="<?php echo_string_by_module('cancel_all', $um_list)//全部取消 ?>" type=button onclick='uncheckAll()' class=buttonL></td>-->
								<td style="text-align:right;"><INPUT value="<?php echo_string('Delete')//删除?>" type=submit onclick='delCheckedRule()' class=buttonL /></td>
						</tr>
						</table></td></tr></tbody></table>
</DIV>
</FORM>
</DIV>


</DIV>


</body>
</html>

