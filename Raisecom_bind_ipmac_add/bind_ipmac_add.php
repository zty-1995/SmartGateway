<?
	require('../php_class/function.php');
		require ('/www/custom/language_resource/dhcp_language.php');
     require ('/www/custom/language_resource/language.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="../lib/indexprivate.css" type="text/css">
  <script src="../js_script/FormValid.js" language="javascript"></script>
  
  <script src="../js_script/FV_onBlur.js"></script>
  <script src="../js_script/FormValid.js"></script>
<script language="javascript">


	FormValid.showError = function(errMsg,errName,formName) {
	if (formName=='form2') {
		for (key in FormValid.allName) {
			document.getElementById('errMsg_'+FormValid.allName[key]).innerHTML = '';
		}
		for (key in errMsg) {
	//		document.getElementById('errMsg_'+errName[key]).innerHTML = errMsg[key];
			if(errMsg[key]==null)	continue;//jump undefined errMsg_...
			if(document.getElementById('errMsg_'+errName[key]).innerHTML!="")
				document.getElementById('errMsg_'+errName[key]).innerHTML+= ",";
			document.getElementById('errMsg_'+errName[key]).innerHTML+= errMsg[key];
		}
	}
}
function check_radius(check_var,check_value,disp_block,disp_block2)
{
	if(check_var == check_value)
	{
		document.getElementById(disp_block).style.display="";
		document.getElementById(disp_block2).style.display = "none";
	}
	else
	{
		document.getElementById(disp_block).style.display="none";
		document.getElementById(disp_block2).style.display = "";
	}
}


</script>
</head>

<body class="body_main" onLoad="initValid(document.form2);" >
 	<div id="BODY">
    <div id="tab_container">
    <DIV style="margin:5px 0" id=DIV_TABCONTROL>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="tab_left_cur" width="5">&nbsp;</td>
            <td class="tab_content_cur" width="40" readonly="readonly"><nobr>
<?php echo_string_by_module('dhcp_bind_mac_add',$dhcp_language_list)?>&nbsp;</nobr></td>
            <td class="tab_right_cur" width="1">&nbsp;</td><!-- 静态IP/MAC绑定 -->
             <td class="tab_text" valign="bottom">&nbsp;</td>
          </tr>
        </table>
      </div>
    </div>
	   <div id='INNER'>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td colspan="2" height="30">
     
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
							<td style="width:15px; background-color:#333;">&nbsp;</td>
							<td class="title_tbl_content"><?php echo_string_by_module('dhcp_bind_mac_add',$dhcp_language_list)?></td>
						</tr><!-- 静态IP/MAC绑定 -->
					</table></td></tr></tbody></table>
      </div>
<form id="form2" name="form2" method="post" action="./list_dhcp3.php?type=add&parts=dhcp_ipbind" onsubmit="return validator(this);">
		<!--To insert a DIV used as tab area [R02617]-->
		<div id="tab_container">
			<script language="javascript"></script>
		</div>
		
		<div id="INNER" align="left" style="padding-left:15px;">
			<div id="div_tab">
					<div id="lanip">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_list">
							<tbody>
								
												
            	 <tr>
            	 		<!--<td width="0">&nbsp;</td>-->
                 	<td width="120" class="template_body_title">
<?php echo_string_by_module('dhcp_bind_name',$dhcp_language_list)?></td>
    							<td><!-- 名称 -->
            					<input type="text"  name="Nbind_name" maxLength=64 valid="required|filter" allow="/^[a-zA-Z0-9_]+$/" errmsg="
<?php echo_string_by_module('dhcp_errmsg_bind_mac_add_name_null',$dhcp_language_list)?>|<?php echo_string_by_module('dhcp_bind_mac_add_name_errmsg',$dhcp_language_list)?>"/>
									<!--<SPAN class=red>*</SPAN>
                			<?php echo_string_by_module('dhcp_ipmac_bind_name_length',$dhcp_language_list)?>-->
                			<?php text_alert_info(0,1,'','');?>
                			<?php text_alert_info(5,0,get_string_by_module('dhcp_ipmac_bind_name_length',$dhcp_language_list),'');?>
                			<span id="errMsg_Nbind_name" style="color:#FF0000"></span><!-- 名称不能为空|该字段只能由字母、数字、下划线组成 -->
            			</td>
  							</tr>
  				
  				
  				
                <tr>
                	  <!--<td width="0">&nbsp;</td>-->
    									<td width="120" class="template_body_title">
<?php echo_string_by_module('dhcp_bind_ip',$dhcp_language_list)?></td>
    								<td><!-- IP地址： -->
            							<input type="text" name="Nbind_ip" valid="required|isIp" errmsg="<?php echo_string_by_module('dhcp_errmsg_ip_addr_null',$dhcp_language_list)?>|<?php echo_string_by_module('dhcp_errmsg_ip_addr_format',$dhcp_language_list)?>" />
            							<!--<SPAN class=red>*</SPAN>-->
            							<?php text_alert_info(0,1,'','');?>
            							<?php text_alert_info(1,0,'','');?>
               						 <span id="errMsg_Nbind_ip" style="color:#FF0000"></span><!-- IP地址不能为空！|IP地址格式不对! -->
            				</td>
  					</tr>
  					
  					
  					
  					
  					 <tr>
  					 	<!--<td width="0">&nbsp;</td>-->
         			<td>
<?php echo_string_by_module('dhcp_bind_mac',$dhcp_language_list)?></td>
            					<td><!-- MAC地址: -->
										<input type="text" name="Nbind_mac" valid="required|isMac2" errmsg="<?php echo_string_by_module('dhcp_errmsg_mac_addr_null',$dhcp_language_list)?>|<?php echo_string_by_module('dhcp_bind_mac_add_mac_errmsg',$dhcp_language_list)?>" />
										<!--<SPAN class=red>*</SPAN>
<?php echo_string_by_module('dhcp_bind_mac_add_mac_message',$dhcp_language_list)?>-->

										<?php text_alert_info(0,1,'','');?>
										<?php text_alert_info(2,0,'','');?>
                					<span id="errMsg_Nbind_mac" style="color:#FF0000"></span><!-- MAC地址不能为空！|MAC地址格式不对! -->
            					</td>
            	</td>
             </tr>	
  				  				

							</tbody>
						</table>		
						<!--<DIV><SPAN class="red">
<?php echo_string_by_module('asterisk',$language_list)?></SPAN></DIV> 			-->
						<?php alert_info(get_string_by_module('asterisk',$language_list));?>
					</div><!-- 星号（*）为必须填写项 -->
					

					
					
					

					<div id="button">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tbody>
								<tr align="right">
									<td>
										<input type='submit' name="submit_Nuser" class='buttonL'  value='<?php echo_string_by_module('dhcp_confirm',$dhcp_language_list)?>'>
										<input type='reset'  name="reset_Nuser" class='buttonL' onclick="javascript:window.history.back();" value='<?php echo_string_by_module('dhcp_cancel',$dhcp_language_list)?>'>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
			</div>
		</div>
</form>   


					
	</div>
</body>
</html>

