<?

	@session_start();

	@header('Cache-control:private');

	require_once('guish_class.php');

	require_once('time_zone.php');

	require_once('content_type_arr.php');
	require_once('custom_function.php');
	$obj_guish = new guish();

	$doc = new DOMDocument();

	$doc_ospf = new DOMDocument();

	$obj_guish->set_fd();

	function start_all_advanced_security()
	{
		if(get_custom_info('ips_av_control') == 'yes')
		{
				return 1;
		}
			return 0;
	}
	function close_advanced_security_and_stat_audit()
	{
		if(get_custom_info('ips_av_control') == 'no' && get_custom_info('user_behaviour_control') == 'no')
		{

				return 1;
		}

		return 0;
	}

		function start_advanced_security_close_ips_av()
	{
			if(get_custom_info('ips_av_control') == 'no' && get_custom_info('user_behaviour_control') == 'yes')
			{
						return 1;
			}

		return 0;
	}

/***************add by zhangbin for shanghai dingzhi***************************/


function uplink_qos_stop()
	{
			if(get_custom_info('uplink_qos') == 'stop')
			{
						return 1;
			}

		return 0;
	}

/***************************************************************************/
	function checkstring($str,$sum)
	{
		$i = 0;
		$len = strlen($str);
		if($str == "0" && $len == 1)
		$str = "";
		if ($len > $sum)
		{
			for($i = $sum; $i <= $len; $i++)
			{
				if ((0xc0 & ord($str[$i])) != 0x80)
				{
					$str = substr($str, 0, $i);
					$str .= "...";
					return $str;
				}
			}
		}
		return $str;
	}
	class service_port_array_class
{
	var $service ;


	function service_port_array_class()
	{
		$filename = '/etc/security/service_port.conf';
		$handle = fopen($filename, "r");
		if ($handle) {
			$file_head = fread($handle, 3);
			$head_flag_array = str_split($file_head);

			if (sizeof($head_flag_array) < 3) {
				fclose($handle);
				return;
			}

			if (!(ord($head_flag_array[0]) == 0xef && ord($head_flag_array[1]) == 0xbb && ord($head_flag_array[2]) == 0xbf))
				fseek($handle, 0);
		while (!feof($handle))
		{
			$buffer = fgets($handle, 256);
			$line_array = explode(";", $buffer);

				$this->service[intval($line_array[0])] = $line_array[1];
		}
        	fclose($handle);
	}

	}

	function get_service()
	{
		return $this->service;
	}
}
	function compatible2ipv4($ip)
	{
		$ip = htmlspecialchars($ip);
    	if (strpos($ip, '::') === 0) {
        	$ip = substr($ip, strrpos($ip, ':')+1);
    	}
		return $ip;
	}

	function is_validIP($ip)
	{
		$ret=false;
		$RegIP = "/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])){2}(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-4]))$/";
		if(@preg_match($RegIP,$ip) == 1 )
		{
			$ret=true;
		}

		return $ret;
	}

	//check whether the session timeout
	function check_session_timeout()
	{
		if (empty ( $_SESSION ['session_id'] )) {
				echo '<script language="javascript">
				var path =window.location.hostname;
				var path_arr = path.split("/");
				var page = path_arr[path_arr.length-1];

				if((page == "main.php")||(page == "login.php"))
				{
					window.location ="/";
				}
	            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
				{

					window.parent.parent.parent.location ="/";

				}
				else
				{
					window.parent.parent.location="/";
				}
				</script>';
		}
	}
	/*for LAVA:checking current admin is default password*/
	function check_default_admin_pwd()
	{
		if ($_SESSION['sub_customer'] == 'LAVA')
		{
			if (($_SESSION['qx'] == 'telecomadmin')&&($_SESSION['password'] == 'admin'))
			{
				echo'<script language="javascript"> 
				alert("current user password is default value, please modify it!");
				</script>';
			}
		}
	}

	function check_file_type($file)

	{
		if(strtolower($file)!="")		//modified by liangxia
		{

			$file_ext = strtolower(substr($file,-3));

			if($file_ext != "gif")

			{

				echo "<script language = 'javascript'>";

				echo "alert(\"图片格式不正确，只允许上传gif格式的图片！\");";

				echo "window.history.back();";

				echo "</script>";



				return false;

			}
		}



		return true;

	}

	function init_css_by_browser_type()
	{
		@unlink('/www/public/css/self_service.css');
		if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0') >0)
			copy('/www/public/css/self_service_ie.css','/www/tmp/css/self_service.css');
		else if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0') >0)
			copy('/www/public/css/self_service_firefox.css','/www/tmp/css/self_service.css');
		else if(strpos($_SERVER['HTTP_USER_AGENT'],'Firefox') > 0)
			copy('/www/public/css/self_service_firefox.css','/www/tmp/css/self_service.css');
		else
			copy('/www/public/css/self_service_firefox.css','/www/tmp/css/self_service.css');
	}


	function check_css($file)

	{

		$file_ext = strtolower(substr($file,-3));



		if($file_ext != "css")

		{

			echo "<script language = 'javascript'>";

			echo "alert(\"样式表文件不正确，只允许上传css样式表格式的文件！\");";

			echo "window.history.back();";

			echo "</script>";



			return false;

		}



		return true;

	}
	/*Begin: added for  MSG00001580 and MSG00001661 by chenjian 2011-5-19*/
	function check_err2($str_xml,$action)
	{
		global $doc;
		if(trim($str_xml) !="" && trim($str_xml) != NULL)

		{

			$doc->loadXML($str_xml);

			if($doc->documentElement->tagName == 'return_code')

			{
				$err_list = $doc->getElementsByTagName('group')->item(0)->childNodes;

				if ($err_list->item(0)->nodeValue == 30000)
				{
					echo'<script language="javascript">


					var path =window.location.hostname;

					var path_arr = path.split("/");

					var page = path_arr[path_arr.length-1];



					if((page == "main.php")||(page == "login.php"))

					{

						window.location ="/";

					}
					else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
					{

						window.parent.parent.parent.location ="/";

					}
					else

					{

						window.parent.parent.location="/";

					}

					</script>';
					return 0;
				}

				header("Content-Type:text/html; charset=utf-8");
				echo'<script language="javascript">

				alert("'.$err_list->item(1)->nodeValue.'");


				document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";
				</script>';
                		return 1;
			}
		}
        	return 0;
	}
	/*End: added for  MSG00001580 and MSG00001661 by chenjian 2011-5-19*/

	// added by lvfei for ips update invalid domain message
	function check_err3($str_xml,$action)
	{
		global $doc;
		$str = 0;
		if(trim($str_xml) !="" && trim($str_xml) != NULL)
		{
			$doc->loadXML($str_xml);
			if($doc->documentElement->tagName == 'return_code')
			{
				$err_list = $doc->getElementsByTagName('group')->item(0)->childNodes;
				$str = $err_list->item(0)->nodeValue;
			}
		}
		return $str;
	}

	function check_err($str_xml,$action)
	{
		global $doc;
		if(trim($str_xml) !="" && trim($str_xml) != NULL)
		{
			$doc->loadXML($str_xml);
			if($doc->documentElement->tagName == 'return_code')
			{
				$err_list = $doc->getElementsByTagName('group')->item(0)->childNodes;
				if ($err_list->item(0)->nodeValue == 30000)
				{
					echo'<script language="javascript">

					var path =window.location.hostname;
					var path_arr = path.split("/");
					var page = path_arr[path_arr.length-1];

					if((page == "main.php")||(page == "login.php"))
					{
						window.location ="/";
					}
		            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
					{

						window.parent.parent.parent.location ="/";

					}
					else
					{
						window.parent.parent.parent.location="/";
					}
					</script>';
					return false;
				}
				echo'<script language="javascript">
			    alert("'.$err_list->item(1)->nodeValue.'");
				 document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";
				//window.history.go(window.location.pathname);
			//	window.history.go(-1);
				</script>';
				return false;
			}
		}
		else
		{
			switch($action)
			{
				case 'add':

					echo'<script language="javascript">

						alert("'.get_string('add_success').'");//添加成功！

						document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";

						</script>';

					break;
					//zhangbin add
					case 'set':

					echo'<script language="javascript">

						alert("'.get_string('set_success').'");//添加成功！

						document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";

						</script>';

					break;
        //zhangbin add
				case 'mod':

					echo '<script language="javascript">

						alert("'.get_string('modified_success').'");//修改成功！

						document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";

						</script>';

					break;

				case 'export':

					echo '<script language="javascript">

						alert("'.get_string('exported_success').'");//导出成功！

						document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";

						</script>';

					break;

				case 'del':

					if($_REQUEST['node_root'] == "movable_memory_detail")
					{
						$temp_path = $_REQUEST['del_value'];
					    $total_path = explode('/', $temp_path);
					    $num_elements=count($total_path);
                        for ($i=0;$i<$num_elements-1; $i++)
                        {
                        	if($i != $num_elements-2)
                        	{
                        		$my_path .= $total_path[$i];
                        		$my_path .= '/';
                        	}
                        	else
                        	{
                        		$my_path .= $total_path[$i];
                        	}
                        }
					    echo'<script language="javascript">
								alert("'.get_string('deleted_success').'"); //删除成功！
								document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'&path='.$my_path.'";
							</script>';
					    break;
					}
					else
					{
						echo '<script language="javascript">

						alert("'.get_string('deleted_success').'");//删除成功！

						document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";

						</script>';
						break;
					}

				case 'chg_pwd':

					echo '<script language="javascript">

						alert("'.get_string('password_changed').'"); //密码修改成功!

						window.close();

						</script>';

					break;

				case 'clear':

					echo '<script language="javascript">

						alert("'.get_string('dynamic_domain_name_cleared').'"); //清空动态域名成功!

						window.close();

						</script>';

					break;

				case 'conn':
					echo '<script language="javascript">

						alert("'.get_string('begin_to_connect').'"); //开始连接！

						document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";

						</script>';

					break;
				case 'disconn':
					echo '<script language="javascript">

						alert("'.get_string('disconnect').'");//断开连接！

						document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";

						</script>';

					break;
				case 'mail_test':
					echo '<script language="javascript">

						alert("'.get_string('mail_test').'");//断开连接！

						document.location=window.location.pathname+"?parts='.$_REQUEST['parts'].'&type=list&pages=1&serch_value='.$_REQUEST['serch_value'].'";

						</script>';

					break;
				default:
					break;

			}
			return true;
		}
		return true;
	}
	
	
	/*对象名称处理函数：策略模块添加对象后，回显需删除自动添加的字段，仅显示添加的数据
	 例如：将	 serv_inner@TCP/1-65535:8000-8100改成  TCP/1-65535:8000-8100
	 add by wanyunlong*/
	function auto_object_name_deal($obj_name)
	{	
		if(stripos($obj_name, '@') != 0)
		{	/*时间对象显示优化, 将17_6_17_29_0000011 改成17:06_17:29_0000011*/
			$result = stripos($obj_name, 'time');
			if($result !== false)
			{
				$time = substr($obj_name,stripos($obj_name, '@')+1);
			
				$arr = explode('_', $time);
			
				$improved_obj_time = date('H:i', mktime($arr[0], $arr[1])).'~'.date('H:i', mktime($arr[2], $arr[3])).'-'.$arr[4];

//				echo '<script language="javascript">'.'alert("'.$time.'---'.$improved_obj_time.'")'.'</script>';
	
				return $improved_obj_time;
			}
			else
			{
				return substr($obj_name,stripos($obj_name, '@')+1);
			}
		}
		else
		{
			return $obj_name;
		}
	}

	function Node_update($name,$data,$type)

	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],$name,$data,$type);

		$str_xml = $obj_guish->get_xml();

		return $str_xml;

	}


	/*if get the wrong prompt,but do not want to show,call it*/
	function Node_add3($node_xml)

	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);
		$str_xml = $obj_guish->get_xml();

	}

	/*Begin: added for MSG00001991 and MSG00001980 by chenjian 2011-5-26*/
	function Node_add2($node_xml)

	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);
		$str_xml = $obj_guish->get_xml();
		check_err2($str_xml,'add');

	}

	/*End: added for   MSG00001991 and MSG00001980 by chenjian 2011-5-26*/
	function Node_add($node_xml)

	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		return check_err($str_xml,'add');

		//header('Location:'.$_SERVER['PHP_SELF'].'?parts='.$_REQUEST['parts'].'&type=list&pages=1');

	}

	function Node_add4($node_xml)

	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		return check_err($str_xml,'set');

		//header('Location:'.$_SERVER['PHP_SELF'].'?parts='.$_REQUEST['parts'].'&type=list&pages=1');

	}



		function Node_del($node_xml)
	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		check_err($str_xml,'del');

		//header('Location:'.$_SERVER['PHP_SELF'].'?parts='.$_REQUEST['parts'].'&type=list&pages=1');

	}



	function Node_del_delay($node_xml)
	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		sleep(2); //暂停2秒

		check_err($str_xml,'del');

	}

	function Node_del2($node_xml)
	{

		global $obj_guish;
		if($node_xml != "")
		{
			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);
			$str_xml = $obj_guish->get_xml();
			check_err($str_xml,'del');
		}
	}

	function Node_del_new($tagName,$del_name,$del_value)

	{

		global $obj_guish;

		$del_xml ='<'.$tagName.' action="del"><group>';

		$del_xml .='<'.$del_name.'>'.$del_value.'</'.$del_name.'>';

		$del_xml .='</group></'.$tagName.'>';

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$del_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		check_err($str_xml,'del');

		//header('Location:'.$_SERVER['PHP_SELF'].'?parts='.$_REQUEST['parts'].'&type=list&pages=1');

	}
	/*Begin: added for  MSG00001580 and MSG00001661 by chenjian 2011-5-19*/
	function Node_mod2($node_xml)
	{

		global $obj_guish;
        $str = 0;
		if($node_xml !="")
		{
			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);
			$str_xml = $obj_guish->get_xml();
			$str = check_err2($str_xml,'mod');
		}

        return $str;
	}
	/*End: added for  MSG00001580 and MSG00001661 by chenjian 2011-5-19*/

	// added by lvfei for ips update invalid domain message
	function Node_mod_nomsg($node_xml)
	{
		global $obj_guish;
		if($node_xml !="")
		{
			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);
			$str_xml = $obj_guish->get_xml();
			return check_err($str_xml, NULL);
		}
		return true;
	}

	function Node_mod3($node_xml)
	{
		global $obj_guish;
		$str = 0;
		if($node_xml !="")
		{
			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);
			$str_xml = $obj_guish->get_xml();
			$str = check_err3($str_xml,'mod');
		}

		return $str;
	}

	function Node_mod($node_xml)
	{
		global $obj_guish;
		if($node_xml !="")
		{
			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);
			$str_xml = $obj_guish->get_xml();
			return check_err($str_xml,'mod');
		}
		//header('Location:'.$_SERVER['PHP_SELF'].'?parts='.$_REQUEST['parts'].'&type=list&pages=1');
		return true;
	}

	//add for l2tp connection and disconnection by jikun 2011.9.13
	function Node_mod_conn($node_xml)

	{

		global $obj_guish;

		if($node_xml !="")

		{

			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

			$str_xml = $obj_guish->get_xml();

			check_err($str_xml,'conn');

		}

		//header('Location:'.$_SERVER['PHP_SELF'].'?parts='.$_REQUEST['parts'].'&type=list&pages=1');

	}
	function Node_mod_disconn($node_xml)

	{

		global $obj_guish;

		if($node_xml !="")

		{

			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

			$str_xml = $obj_guish->get_xml();

			check_err($str_xml,'disconn');

		}

		//header('Location:'.$_SERVER['PHP_SELF'].'?parts='.$_REQUEST['parts'].'&type=list&pages=1');

	}
	//end

	//add by liuzhifen 2012/1/4 start
	function Node_mail_test($node_xml)
	{
		global $obj_guish;
		if ( $node_xml != "" )
		{
			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);
			$str_xml = $obj_guish->get_xml();
			check_err($str_xml,'mail_test');
		}
	}

	//add by liuzhifen 2012/1/4 end


	function qos_Node_mod($node_xml)

	{

		global $obj_guish;

		if($node_xml !="")

		{

			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

			$str_xml = $obj_guish->get_xml();

			//check_err($str_xml,'mod');  not check err for qos address object, schedule object , service object

		}

		//header('Location:'.$_SERVER['PHP_SELF'].'?parts='.$_REQUEST['parts'].'&type=list&pages=1');

	}



	/*kongxinyu add 清除动态域名 ------start--------*/

	function Node_clear($node_xml)

	{

		global $obj_guish;



		if($node_xml != "")

		{

			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

			$str_xml = $obj_guish->get_xml();



			check_err($str_xml,'clear');

		}

	}

	/*kongxinyu add 清除动态域名 ------end--------*/



	function Node_export($node_xml)

	{

		global $obj_guish;

		if($node_xml !="")

		{

			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

			$str_xml = $obj_guish->get_xml();

			check_err($str_xml,'export');

		}

		//header('Location:'.$_SERVER['PHP_SELF'].'?parts='.$_REQUEST['parts'].'&type=list&pages=1');

	}

	function Node_ajaxmod($node_xml)

	{

		global $obj_guish;
		global $doc;

		if($node_xml !="")

		{

			$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

			$str_xml = $obj_guish->get_xml();

			if(trim($str_xml) !="" && trim($str_xml) != NULL)
			{
				$doc->loadXML($str_xml);

				if($doc->documentElement->tagName == 'return_code')
				{
					$err_list = $doc->getElementsByTagName('group')->item(0)->childNodes;
					SetCookie('ajaxmod_result', $err_list->item(0)->nodeValue);
					SetCookie('ajaxmod_str', $err_list->item(1)->nodeValue);
					return $str_xml;
				}
				SetCookie('ajaxmod_result', 'error');
				return $str_xml;
			}
			SetCookie('ajaxmod_result', 'success');
			return $str_xml;
		}
	}

	function Node_show($node_name,$action)

	{

		global $obj_guish;

		$node_xml = '<'.$node_name.' action="'.$action.'"></'.$node_name.'>';

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		if(empty($str_xml) == true)
		{
			echo '<script language="javascript">
			alert("'.get_string('data_is_empty').'");	 //	有错误发生，得到数据为空!
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
			return;

		}

		else

		{

			check_err($str_xml,NULL);

			return $str_xml;

		}

	}

	function Node_show_by_some($node_name,$action,$some_name,$some_value)
	{
		global $obj_guish;
		$node_xml = '<'.$node_name.' action="'.$action.'"><group><'.$some_name.'>'.$some_value.'</'.$some_name.'></group></'.$node_name.'>';
		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		if(empty($str_xml) == true)
		{
			echo '<script language="javascript">


			alert("'.get_string('data_is_empty').'");	//有错误发生，得到数据为空!
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
			return;
		}
		else
		{
			check_err($str_xml,NULL);
			return $str_xml;
		}
	}

	function usb_Node_show($node_name,$action)

	{

		global $obj_guish;

		$node_xml = '<'.$node_name.' action="'.$action.'"></'.$node_name.'>';

		$obj_guish->usb_set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		if(empty($str_xml) == true)

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];



			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

		else

		{

			check_err($str_xml,NULL);

			return $str_xml;

		}

	}

	function Node_show_file($node_name,$action)

	{

		global $obj_guish;

		$node_xml = '<'.$node_name.' action="'.$action.'"></'.$node_name.'>';

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		if(empty($str_xml) == true)

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}
            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}



	function Node_show_syslog($node_xml)

	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();

		//check_err($str_xml,NULL);

		if(empty($str_xml) == true)

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}
            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php")||(page == "list_usermanagement.php"))
			{

				window.parent.parent.parent.location ="/";

			}
			else
			{

				window.parent.parent.location="/";

			}

			</script>';

		}

		else

		{

			//check_err($str_xml,NULL);

			return $str_xml;

		}

		//return $str_xml;

	}



	function Node_showIndex($node_xml)

	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();



		//check_err($str_xml,NULL);

		//return $str_xml;

		if(empty($str_xml) == true)

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}
            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

		else

		{

			check_err($str_xml,NULL);

			return $str_xml;

		}

	}



		function usb_Node_showIndex($node_xml)

	{

		global $obj_guish;

		$obj_guish->usb_set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();



		//check_err($str_xml,NULL);

		//return $str_xml;

		if(empty($str_xml) == true)

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

		else

		{

			check_err($str_xml,NULL);

			return $str_xml;

		}

	}



	function Node_showOne($node_xml)

	{

		global $obj_guish;

		$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$node_xml,UCT_CMD);

		$str_xml = $obj_guish->get_xml();



		//check_err($str_xml,NULL);

		//return $str_xml;

		if(empty($str_xml) == true)

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}
            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

		return $str_xml;

	}
	
	/*Default:0--not match;1:match*/
	function objtab_checklist_search_byname($node_name, $action, $name)
	{
		$ret = 0;
		global $doc;
		$str_xml = Node_show($node_name,$action);
		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$name_list = $doc->getElementsByTagName('name');
			for($i=0; $i<$name_list->length;$i++)
			{
				if($name_list->item($i)->nodeValue == $name)
				{
					$ret = 1;
					break;
				}
			}
		}
		
		return $ret;
	}
	
	
	
	function objtab_show_inverse_select_byname($node_name, $action, $name){
		global $doc;
		$str_xml = Node_show($node_name,$action);
		
		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$name_list = $doc->getElementsByTagName('name');
			for($i=0; $i<$name_list->length;$i++)
			{
				if($name_list->item($i)->nodeValue == $name)
					continue;
				
				if($node_name == 'addr_obj_table' || $node_name == 'sev_obj_table'){
					if(strchr($name_list->item($i)->nodeValue,'inner@')){
						continue;
					}
				}
				echo '<option value="'.$name_list->item($i)->nodeValue.'">'.
				$name_list->item($i)->nodeValue.'</option>';
			}
		}
		else
		{
			echo '<script language="javascript">
				var path =window.location.hostname;
				var path_arr = path.split("/");
				var page = path_arr[path_arr.length-1];
				if((page == "main.php")||(page == "login.php"))
				{
					window.location ="/";
				}
	           		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
				{
					window.parent.parent.parent.location ="/";
				}
				else
				{
					window.parent.parent.location="/";
				}
				</script>';
		}
	}
	
	function objtab_show($node_name,$action)

	{

		global $doc;

		$str_xml = Node_show($node_name,$action);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('name');

			for($i=0; $i<$name_list->length;$i++)

			{
				if($node_name == 'addr_obj_table' || $node_name == 'sev_obj_table' || $node_name == 'tr_periodic_table'){
					if(strchr($name_list->item($i)->nodeValue,'inner@') 
					|| strchr($name_list->item($i)->nodeValue,'NAT*')){
						continue;
					}
				}
				
				echo '<option value="'.$name_list->item($i)->nodeValue.'">'.

				$name_list->item($i)->nodeValue.'</option>';

			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}
            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
			else
			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}

	function app_objtab_show($node_name,$action)
	{

		global $doc;

		$str_xml = Node_show($node_name,$action);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('name');

			for($i=0; $i<$name_list->length;$i++)
			{
 				if(stristr($name_list->item($i)->nodeValue, 'QOS') == FALSE)
 				{
					echo '<option value="'.$name_list->item($i)->nodeValue.'">'.
					$name_list->item($i)->nodeValue.'</option>';
				}

			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}
            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
			else
			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}


	function objtab_showbyindex($node_name,$action,$index_name)

	{

		global $doc;

		$str_xml = Node_show($node_name,$action);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName($index_name);

			for($i=0; $i<$name_list->length;$i++)

			{

				echo '<option value="'.$name_list->item($i)->nodeValue.'">'.

				$name_list->item($i)->nodeValue.'</option>';

			}

		}

		else
		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}
            		else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
			else
			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}

	function usergrp_show($type)

	{

		global $doc;

		$str_xml = Node_show('user_group','show');

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$groups = $doc->getElementsByTagName('group');

			$grp_len = $groups->length;

			for($i=0; $i < $grp_len;$i++)

			{

				$elm_list = $groups->item($i)->childNodes;

				if($elm_list->item(1)->nodeValue == $type)

				echo '<option value="'.$elm_list->item(0)->nodeValue.'">'.

				$elm_list->item(0)->nodeValue.'</option>';

			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else
			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}

	function sevgrp_show($node_name,$action,$type)

	{

		global $doc;

		$str_xml = Node_show($node_name,$action);

		if(trim($str_xml) != "")

		{

			$doc->loadXML($str_xml);

			$n_list = $doc->getElementsByTagName('group');

			for($i=0;$i <$n_list->length; $i++)

			{

				$elm_list = $n_list->item($i)->childNodes;

				if($elm_list->item(1)->nodeValue != $type)

					continue;

				else

					echo '<option value="'.$elm_list->item(0)->nodeValue.'">'.

				$elm_list->item(0)->nodeValue.'</option>';

			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}

	function l2tpgrp_show($node_name,$action)

	{

		global $doc;

		$str_xml = Node_show($node_name,$action);

		if(trim($str_xml) != "")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('group_name');

			for($i=0;$i <$name_list->length; $i++)

			{

				echo '<option value="'.$name_list->item($i)->nodeValue.'">'.

				$name_list->item($i)->nodeValue.'</option>';

			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}

	function objtype_show($node_name,$action,$type)

	{

		global $doc;

		$str_xml = Node_show($node_name,$action);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$groups = $doc->getElementsByTagName($node_name)->item(0)->childNodes;

			for($i=0;$i<$groups->length;$i++)

			{

				$n_list = $groups->item($i)->childNodes;

				if($n_list->item(1)->nodeValue == $type)

				{

					echo '<option value="'.$n_list->item(0)->nodeValue.'">'.$n_list->item(0)->nodeValue.'</option>';

				}

				else

				{

					continue;

				}

			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}
	
	function alert_debug($info)
	{
		echo'<script language="javascript">
			alert("'.$info.'");
		</script>';
	}
	
	function is_mroteck_cus()
	{
		global $doc;
		$ret=0;
		$wire_mode=0;
		$is_mroteck_custom=0;
		
		$serch_xml = '<shdsl_web_config action="show_index"><group></group></shdsl_web_config>';
		$str_xml = Node_showIndex($serch_xml);

		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$wire_mode=$doc->getElementsByTagName('wire_mode')->item(0)->nodeValue;
			$is_mroteck_custom=$doc->getElementsByTagName('is_mroteck_custom')->item(0)->nodeValue;
		}
		else
		{
			echo '<script language="javascript">
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];
			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
		}
		if(($wire_mode==8&&$is_mroteck_custom)||!$is_mroteck_custom)
			$ret=1;
		return $ret;
	}
	
	function stu_mode_show()
	{
		global $doc;
		$stu_mode=0;
		
		$serch_xml = '<shdsl_eoc_status action="show_index"><group></group></shdsl_eoc_status>';
		$str_xml = Node_showIndex($serch_xml);

		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$stu_mode=$doc->getElementsByTagName('stu_mode')->item(0)->nodeValue;
		}
		else
		{
			echo '<script language="javascript">
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];
			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
		}
		return $stu_mode;
	}
	
	function wire_mode_show()
	{
		global $doc;
		$wire_mode=0;
		
		$serch_xml = '<shdsl_web_config action="show_index"><group></group></shdsl_web_config>';
		$str_xml = Node_showIndex($serch_xml);

		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$wire_mode=$doc->getElementsByTagName('wire_mode')->item(0)->nodeValue;
			echo '<option value="0" selected="selected">Two-Wire</option>';
			if($wire_mode==4||$wire_mode==8)
			{
				echo '<option value="1">Four-Wire</option>';
			}          	
			if($wire_mode==8)
			{
      			echo '<option value="3">Eight-Wire</option>';
			}
		}
		else
		{
			echo '<script language="javascript">
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];
			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
		}
		return $wire_mode;
	}

	function phyface_show($node_name,$action)

	{

		global $doc;

		$start = 0;

		$filter_py = array('3gppp','wifi0','ath7','eth0','eth1','eth2','eth3','eth4','eth5','eth6','eth7');

		foreach ($filter_py as $value)
        {
            echo '<option value="'.$value.'">'.$value.'</option>';
		}
        $filter_except = array('3gppp','wifi0','ath7','eth0','eth1','eth2','eth3','eth4','eth5','eth6','eth7','LAN0','LAN1','LAN2','LAN3','LAN4','LAN5','LAN6','LAN7','eth9','eth10');

		$str_xml = Node_show($node_name,$action);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('name');

			$elm_len = $name_list->length;

			if($start<$elm_len)

			{

				for($i=$start; $i<$elm_len;$i++)

				{

					if(!in_array($name_list->item($i)->nodeValue,$filter_except))
				        {
                                           echo '<option value="'.$name_list->item($i)->nodeValue.'">'.$name_list->item($i)->nodeValue.'</option>';
                                        }
				}

			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}

	function getno_phyface_type($type)
	{

		global $doc;

		$start = 0;

		$serch_xml = '<interface_sub action="show_i"><group><get_type>'.$type.'</get_type></group></interface_sub>';

		$str_xml = Node_showIndex($serch_xml);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('name');

			$elm_len = $name_list->length;
			return $elm_len;

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}

	function get_eth_port_type($ifname)
	{
		global $doc;

		$serch_xml = '<port_cfg action="show_one"><group><port_name>'.$ifname.'</port_name></group></port_cfg>';

		$str_xml = Node_showOne($serch_xml);

		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);

			$type_list = $doc->getElementsByTagName('type');

			return $type_list->item(0)->nodeValue;
		}
	}

	function eth_port_type_set($ifname, $port_type)
	{

			$mod_xml ='<port_cfg_wan_group action="mod">';

			$mod_xml .='<group>';

			$mod_xml .='<port_cfg_list>';

			$mod_xml .='<group>';

			$mod_xml .='<port_name>'.$ifname.'</port_name>';

			//port_type : 1:access 2:trunk 3:no switchport
			switch($port_type)
			{
				case "access":
					$mod_xml .='<type>1</type>';
					$mod_xml .='<pvid>1</pvid>';
					break;
				case "trunk":
					$mod_xml .='<type>2</type>';
					$mod_xml .='<pvid>1</pvid>';
					break;
				case "no switchport":
					$mod_xml .='<type>3</type>';
					break;

				default:
					return;
			}

			$mod_xml .='</group>';

			$mod_xml .='</port_cfg_list>';

			$mod_xml .='</group>';

			$mod_xml .='</port_cfg_wan_group>';

			Node_mod($mod_xml);

	}

	function phyface_app_get_type_show($apptype, $get_type)

	{
		$first_if = "";
		global $doc;

		$start = 0;

		$search_xml = '<interface_sub action="show_i"><group><get_type>'.$get_type.'</get_type><apptype>'.$apptype.'</apptype></group></interface_sub>';

		$str_xml = Node_showIndex($search_xml);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('name');

			$elm_len = $name_list->length;

			if($start<$elm_len)

			{

				for($i=$start; $i<$elm_len;$i++)

				{
					if( $i == $start ){
						$first_if = $name_list->item($i)->nodeValue;
					}
					echo '<option value="'.$name_list->item($i)->nodeValue.'">'.$name_list->item($i)->nodeValue.'</option>';

				}
			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}
		/*return the first interface*/
		return $first_if;
	}

	function phyface_type_show_similar($type)

	{

		global $doc;

		$start = 0;

		$serch_xml = '<interface_sub action="show_i"><group><get_type>'.$type.'</get_type></group></interface_sub>';

		$str_xml = Node_showIndex($serch_xml);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('name');

			$elm_len = $name_list->length;

			if($start<$elm_len)

			{

				for($i=$start; $i<$elm_len;$i++)
					echo '<option value="'.$name_list->item($i)->nodeValue.'">'.$name_list->item($i)->nodeValue.'</option>';


			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}


	function phyface_type_show($type)
	{
		$first_if = "";
		global $doc;
		$start = 0;
		$serch_xml = '<interface_sub action="show_i"><group><get_type>'.$type.'</get_type></group></interface_sub>';
		$str_xml = Node_showIndex($serch_xml);
		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$name_list = $doc->getElementsByTagName('name');
			$elm_len = $name_list->length;
			if($start<$elm_len)
			{
				for($i=$start; $i<$elm_len;$i++)
				{
					/*
					switch($name_list->item($i)->nodeValue)
					{
						case("ge0"):
							$flag = "wan0";
							break;
						case("ge1"):
							$flag = "wan1";
							break;
						case("WAN0"):
							$flag = "wan0";
							break;
						case("WAN1"):
							$flag = "wan1";
							break;
						case("ath1"):
							$flag = "wlan1";
							break;
						case("ath2"):
							$flag = "wlan2";
							break;
						case("ath3"):
							$flag = "wlan3";
							break;
           				default:
                            $flag = $name_list->item($i)->nodeValue;
							break;
					}
					*/
					if( $i == $start ){
						$first_if = $name_list->item($i)->nodeValue;
					}
					echo '<option value="'.$name_list->item($i)->nodeValue.'">'.$name_list->item($i)->nodeValue.'</option>';
				}
			}
		}
		else
		{

			echo '<script language="javascript">
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];
			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
		}
		/*return the first interface*/
		return $first_if;
	}
	
	function phyface_type_show_no_module($type)
	{
		$first_if = "";
		global $doc;
		$start = 0;
		$serch_xml = '<interface_sub action="show_i"><group><get_type>'.$type.'</get_type></group></interface_sub>';
		$str_xml = Node_showIndex($serch_xml);
		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$name_list = $doc->getElementsByTagName('name');
			$elm_len = $name_list->length;
			if($start<$elm_len)
			{
				for($i=$start; $i<$elm_len;$i++)
				{
					/*
					switch($name_list->item($i)->nodeValue)
					{
						case("ge0"):
							$flag = "wan0";
							break;
						case("ge1"):
							$flag = "wan1";
							break;
						case("WAN0"):
							$flag = "wan0";
							break;
						case("WAN1"):
							$flag = "wan1";
							break;
						case("ath1"):
							$flag = "wlan1";
							break;
						case("ath2"):
							$flag = "wlan2";
							break;
						case("ath3"):
							$flag = "wlan3";
							break;
           				default:
                            $flag = $name_list->item($i)->nodeValue;
							break;
					}
					*/
					if(strncmp("module", $name_list->item($i)->nodeValue, 6) == 0)
					{
						continue;
					}
					
					if( $i == $start )
					{
						$first_if = $name_list->item($i)->nodeValue;
					}
					echo '<option value="'.$name_list->item($i)->nodeValue.'">'.$name_list->item($i)->nodeValue.'</option>';
				}
			}
		}
		else
		{

			echo '<script language="javascript">
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];
			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
		}
		/*return the first interface*/
		return $first_if;
	}

		function phyface_apptype_show($apptype)

	{

		global $doc;

		$start = 0;

		$search_xml = '<interface_sub action="show_i"><group><apptype>'.$apptype.'</apptype></group></interface_sub>';

		$str_xml = Node_showIndex($search_xml);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('name');

			$elm_len = $name_list->length;

			if($start<$elm_len)

			{

				for($i=$start; $i<$elm_len;$i++)

				{

					echo '<option value="'.$name_list->item($i)->nodeValue.'">'.$name_list->item($i)->nodeValue.'</option>';

				}

			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}
	
	function phyface_gettype_show($gettype)

	{

		global $doc;

		$start = 0;



		$search_xml = '<interface_sub action="show_i"><group><get_type>'.$gettype.'</get_type></group></interface_sub>';

		$str_xml = Node_showIndex($search_xml);



		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('name');

			$elm_len = $name_list->length;



			if($start<$elm_len)

			{

				for($i=$start; $i<$elm_len;$i++)

				{

					echo '<option value="'.$name_list->item($i)->nodeValue.'">'.$name_list->item($i)->nodeValue.'</option>';

				}

			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}

	function wlan_getwep()

	{

		global $doc;

		$start = 0;



		$search_xml = '<wlan_cfg action="show"></wlan_cfg>';

		$str_xml = Node_showIndex($search_xml);

		if(trim($str_xml) !="")
		{

			$doc->loadXML($str_xml);

			$groups = $doc->getElementsByTagName('group')->item(0)->childNodes;

			if(1 == $groups->item(7)->nodeValue)
			{
				//printf("gaobo 1\n");
				return 1;//wep 64,128
			}
			else
			{
				//printf("gaobo 2 \n");
				return 2;//wep 64,128,152
			}

		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}
	function wlan_getwep_5g()
		{

		global $doc;

		$start = 0;

		$search_xml = '<wlan_cfg action="show"><group>';
		$search_xml .= '<wlan_5g>1</wlan_5g>';
		$search_xml .= '</group></wlan_cfg>';

		$str_xml = Node_showIndex($search_xml);

		if(trim($str_xml) !="")
		{

			$doc->loadXML($str_xml);

			$groups = $doc->getElementsByTagName('group')->item(0)->childNodes;

			if(1 == $groups->item(7)->nodeValue)
			{
				//printf("gaobo 1\n");
				return 1;//wep 64,128
			}
			else
			{
				//printf("gaobo 2 \n");
				return 2;//wep 64,128,152
			}

		}
/*
		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}*/

	}	
    function get_wlan_feature()

	{

		global $doc;

		$start = 0;
        $wlan_feature = array();



		$search_xml = '<wlan_cfg action="show"></wlan_cfg>';

		$str_xml = Node_showIndex($search_xml);

		if(trim($str_xml) !="")
		{

			$doc->loadXML($str_xml);

			$groups = $doc->getElementsByTagName('group')->item(0)->childNodes;

			$wlan_feature[0] = $groups->item(8)->nodeValue;  //wps
            $wlan_feature[1] = $groups->item(9)->nodeValue; //wlan statistic
            $wlan_feature[2] = $groups->item(10)->nodeValue; //wlan sitesurvey
            $wlan_feature[3] = $groups->item(11)->nodeValue; //detect invalid ap
            $wlan_feature[4] = $groups->item(12)->nodeValue; //wds

            return $wlan_feature;
		}

		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}

	}
    function get_wlan_feature_5g()

	{

		global $doc;

		$start = 0;
        $wlan_feature = array();

		$search_xml = '<wlan_cfg action="show"><group>';
		$search_xml .= '<wlan_5g>1</wlan_5g>';
		$search_xml .= '</group></wlan_cfg>';	
		$str_xml = Node_showIndex($search_xml);

		if(trim($str_xml) !="")
		{

			$doc->loadXML($str_xml);

			$groups = $doc->getElementsByTagName('group')->item(0)->childNodes;

			$wlan_feature[0] = $groups->item(8)->nodeValue;  //wps
            $wlan_feature[1] = $groups->item(9)->nodeValue; //wlan statistic
            $wlan_feature[2] = $groups->item(10)->nodeValue; //wlan sitesurvey
            $wlan_feature[3] = $groups->item(11)->nodeValue; //detect invalid ap
            $wlan_feature[4] = $groups->item(12)->nodeValue; //wds

            return $wlan_feature;
		}
/*
		else

		{

			echo '<script language="javascript">

			var path =window.location.hostname;

			var path_arr = path.split("/");

			var page = path_arr[path_arr.length-1];

			if((page == "main.php")||(page == "login.php"))

			{

				window.location ="/";

			}

			else

			{

				window.parent.parent.location="/";

			}

			</script>';

		}*/

	}
	
	function g5wlan_ssid()
	{
		global $doc;
		$start = 0;
        $search_xml = '<wlan_secure action="show"><group>';
		$search_xml .='<wlan_5g>1</wlan_5g>';
		$search_xml .= '</group></wlan_secure>';
		$str_xml = Node_showIndex($search_xml);
		error_log("###########", 3, "/tmp/my_log.log");
		error_log($str_xml, 3, "/tmp/my_log.log");

		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$grp_list = $doc->getElementsByTagName('group');
			$elm_len = $grp_list->length;
			for($i=$start; $i<$elm_len;$i++)
			{
				$elms = $grp_list->item($i)->childNodes;
				echo '<option value="'.$elms->item(0)->nodeValue.'">'.$elms->item(3)->nodeValue.'</option>';
			}
		}
	}	
	
	function wlan_ssid()
	{
		global $doc;
		$start = 0;
		$search_xml = '<wlan_secure action="show"></wlan_secure>';
		$str_xml = Node_showIndex($search_xml);
		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$grp_list = $doc->getElementsByTagName('group');
			$elm_len = $grp_list->length;
			for($i=$start; $i<$elm_len;$i++)
			{
				$elms = $grp_list->item($i)->childNodes;
				echo '<option value="'.$elms->item(0)->nodeValue.'">'.$elms->item(3)->nodeValue.'</option>';
			}
		}
		/*
		else
		{
			echo '<script language="javascript">
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];
			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
		}*/
	}

	function checkbox_show($node_name,$action,$name)

	{

		global $doc;

		$str_xml = Node_show($node_name,$action);

		if(trim($str_xml) !="")

		{

			$doc->loadXML($str_xml);

			$name_list = $doc->getElementsByTagName('name');

			echo '<input type="hidden" name="if_count" value="'.$name_list->length.'" />';

			for($i=0; $i<$name_list->length;$i++)

			{

				echo '<input type="checkbox" style="width:20px;" name="'.$name.$i.'"/>'.$name_list->item($i)->nodeValue;

			}

		}

	}

function um_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$id,$grpname)

{

	echo'<td class=content_value><nobr>';

	if(($_SESSION['qx'] == 'useradmin') || ($_SESSION['qx'] == 'telecomadmin'))

	{

		if(!empty($tagName))

		{
				if( ($id ==0) ||($id==9999))
				{
					}
				else
				{
						//echo " tagName=$tagName</br>";
						//echo " del_name=$del_name</br>";
						//echo " del_value=$del_value</br>";


							echo'<span class=page_link)">

							<a href="javascript:um_index_del(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\');">

							<img src="../images/icon_del.gif" />

							</a>';
				}
		}
		switch($mod_page)

		{

			case '':

				echo '&nbsp;';



			break;


			default:
					if( ($id ==0) ||($id==9999))
					{

					}
					else
					{

					echo'<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">

						<img src="../images/icon_mdf.gif" />

					</a>';

					}
			break;

		}

	}

	echo'</span></nobr></td></tr>';

}
function register_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name="",$inface_value="")
{
	require('../custom/language_resource/sip_language.php');
	echo'<td class="content_td"><nobr>';

	if(($_SESSION['qx'] == 'useradmin') || ($_SESSION['qx'] == 'telecomadmin'))
	{

		echo'<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">

				<img src="../images/icon_mdf.gif" />

			</a>';

	 	echo'|<a href="javascript:index_register(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\');"target="">
			'.get_string_by_module("register", $sip_language_list).'
						 </a>';
		echo'|<a href="javascript:index_unregister(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\');"target="">
		'.get_string_by_module("remove_register", $sip_language_list).'
						 </a>';
	}
	echo'</span></nobr></td></tr>';

}

function clear_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$check_priviledge=1,$can_delete=1)
{
    require('../custom/language_resource/sip_language.php');
    echo'<td class="content_td"><nobr>';
    if (!$check_priviledge || !is_service_user())
    {
        if(!empty($tagName))
        {
            if ($can_delete)
            {
                echo'<span class=page_link)">
                <a href="javascript:index_clear(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\');"target="">
                '.get_string_by_module("clear", $sip_language_list).'
                </a>';
            }
        }
    }
    echo'</span></nobr></td></tr>';
}

function _operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name="",$inface_value="",$check_priviledge=1,$can_delete=1,$can_modify=1)

{
	require('../custom/language_resource/statistics_monitor_language.php');
	require('../custom/language_resource/sys_language.php');
	echo'<td class="content_td"><nobr><span>';

	if (!$check_priviledge || !is_service_user())

	{

		if(!empty($tagName))

		{

			if(($_SESSION['qx'] == 'useradmin')&&($tagName == 'interface_sub'))
			{
			}
			else
			{
			if(!empty($inface_name) && !empty($inface_value))

			{
				echo '<a href="javascript:index_del(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\',\''.$inface_name.'\',\''.$inface_value.'\');">

					 <img title="'.get_string_by_module('operation_del', $operation_language_list).'" src="../images/icon_del.gif" />

					 </a>';

			}

			else

			{
                if ($can_delete) {
				echo'<span class="page_link">

				<a href="javascript:index_del(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\');">

				<img title="'.get_string_by_module('operation_del', $operation_language_list).'" src="../images/icon_del.gif" />

				</a>';
                }
			}
			}
		}

        if ($can_modify) {
		switch($mod_page)
		{
		case '':
			echo '&nbsp;';
		break;

		case 'capture':
		
			 echo'|<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">
			
			<img src="../images/icon_mdf.gif" />下载
			
			</a>';
		
		break;

		case 'routing_filter':
			echo'<a href="javascript:index_mod_routing_filter(\''.$mod_page.'\',\''.$mod_value.'\',\''.$del_value.'\')" target="">
			<img src="../images/icon_mdf.gif" />
			    </a>';
		break;

		case 'routing_filter_application':
			echo'<a href="javascript:index_mod_routing_filter(\''.$mod_page.'\',\''.$mod_value.'\',\''.$del_value.'\')" target="">
			<img src="../images/icon_mdf.gif" />
		    </a>';
		break;

		case 'fw_policy_table':

			echo'<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">
			<img src="../images/icon_mdf.gif" />
		    </a>';
		break;

		case 'history_flow_stat_table':
			echo'<a href="javascript:index_detail(\''.$mod_page.'\',\''.$mod_value.'\')" target="">
			<img src="../images/icon_mdf.gif" />
		    </a>';
		break;
		
		case 'session_stat':
			
			if($del_value=='')
			{
				$mod_value1=$del_name;
				echo'<a href="javascript:index_detail(\''.$mod_page.'\',\''.$mod_value.'\')" target="">
				
				<img src="../images/icon_mdf.gif" />
				</a>';
				$mod_page='temp_blocking_policy';
				   echo'<td  class="content_td">
				<div align="left">
				<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">
				
				<img src="../images/tab_del.gif" /></a>
				</div>
				</td>';
			}
			else 
			{
			  $mod_value1=$del_name;
			       $mod_page='temp_blocking_policy';
			  echo '<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">
			   <img src="../images/tab_del.gif" /></a>';
			}
		break;


		case 'flow_stat_table':
			if($del_value=='')
			{
					$mod_value1=$del_name;
				echo'<a href="javascript:index_detail(\''.$mod_page.'\',\''.$mod_value.'\')" target="">
			
				<img src="../images/icon_mdf.gif" />'.get_string_by_module("Detail", $statistics_monitor_language_list).'
			
			</a>';
				$mod_page='temp_blocking_policy';
			   echo'<td class="content_td">
							<div align="left">
				<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">
			
				<img src="../images/tab_del.gif" /></a>
							</div>
						</td>';
			}
			else
			{
			  $mod_value1=$del_name;
			       $mod_page='temp_blocking_policy';
			  echo '<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">
				   <img src="../images/tab_del.gif" /></a>';
			}
			break;
			default:
				echo'<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">
	
					<img title="'.get_string_by_module('operation_mod', $operation_language_list).'" src="../images/icon_mdf.gif" />
	
				</a>';

			break;

		}
        }
	}

	echo'</span></nobr></td></tr>';

}

function operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name="",$inface_value="")
{
    return _operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name,$inface_value,1,1,1);
}

function operation_by_address_pool_name($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name="",$inface_value="",$address_pool_name="",$address_pool_value="")

{
	require('../custom/language_resource/statistics_monitor_language.php');
	echo'<td class="content_td"><nobr>';

	if(($_SESSION['qx'] == 'useradmin') || ($_SESSION['qx'] == 'telecomadmin'))

	{

		if(!empty($tagName))

		{

			if(($_SESSION['qx'] == 'useradmin')&&($tagName == 'interface_sub'))
			{
			}
			else
			{
			if(!empty($inface_name) && !empty($inface_value))

			{

				echo '<a href="javascript:index_del_by_address_pool_name(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\',\''.$inface_name.'\',\''.$inface_value.'\',\''.$address_pool_name.'\',\''.$address_pool_value.'\');">

					 <img src="../images/icon_del.gif" />

					 </a>';

			}

			else

			{

				echo'<span class=page_link)">

				<a href="javascript:index_del(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\');">

				<img src="../images/icon_del.gif" />

				</a>';

			}
			}
		}

		switch($mod_page)

		{

			case '':

				echo '&nbsp;';



			break;

			case 'capture':

			 	echo'|<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">

					<img src="../images/icon_mdf.gif" />下载

			</a>';

			break;

			case 'routing_filter':
				echo'<a href="javascript:index_mod_routing_filter(\''.$mod_page.'\',\''.$mod_value.'\',\''.$del_value.'\')" target="">
				<img src="../images/icon_mdf.gif" />
			    </a>';
			break;

			case 'routing_filter_application':
				echo'<a href="javascript:index_mod_routing_filter(\''.$mod_page.'\',\''.$mod_value.'\',\''.$del_value.'\')" target="">
				<img src="../images/icon_mdf.gif" />
			    </a>';
			break;

			case 'fw_policy_table':

				echo'<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">
				<img src="../images/icon_mdf.gif" />
			    </a>';
			break;

				case 'session_stat':

			if($del_value==''){


				$mod_value1=$del_name;
				echo'<a href="javascript:index_detail(\''.$mod_page.'\',\''.$mod_value.'\')" target="">

				<img src="../images/icon_mdf.gif" />
				</a>';
				$mod_page='temp_blocking_policy';
			   echo'<td  class="content_td">
							<div align="left">
				<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">

				<img src="../images/tab_del.gif" /></a>
							</div>
						</td>';}
			   else {
			  $mod_value1=$del_name;
			       $mod_page='temp_blocking_policy';
			  echo '<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">
				   <img src="../images/tab_del.gif" /></a>';
			    }


			break;


			case 'flow_stat_table':
			if($del_value==''){
					$mod_value1=$del_name;
				echo'<a href="javascript:index_detail(\''.$mod_page.'\',\''.$mod_value.'\')" target="">

				<img src="../images/icon_mdf.gif" />'.get_string_by_module("Detail", $statistics_monitor_language_list).'

			</a>';
				$mod_page='temp_blocking_policy';
			   echo'<td class="content_td">
							<div align="left">
				<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">

				<img src="../images/tab_del.gif" /></a>
							</div>
						</td>';}
		 else {
			  $mod_value1=$del_name;
			       $mod_page='temp_blocking_policy';
			  echo '<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">
				   <img src="../images/tab_del.gif" /></a>';
			    }

			break;

			default:

			echo'<a href="javascript:index_mod_by_address_pool_name(\''.$mod_page.'\',\''.$mod_value.'\',\''.$address_pool_name.'\',\''.$address_pool_value.'\')" target="">

				<img src="../images/icon_mdf.gif" />

			</a>';

			break;

		}

	}

	echo'</span></nobr></td></tr>';

}
function detail_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name,$inface_value="")

{
	require('../custom/language_resource/statistics_monitor_language.php');
	echo'<td class="content_td"><nobr>';

	if(($_SESSION['qx'] == 'useradmin') || ($_SESSION['qx'] == 'telecomadmin'))

	{

		if(!empty($tagName))

		{

			if(($_SESSION['qx'] == 'useradmin')&&($tagName == 'interface_sub'))
			{
			}
			else{
			if(!empty($inface_name) && !empty($inface_value))

			{

				echo '<a href="javascript:index_del(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\',\''.$inface_name.'\',\''.$inface_value.'\');">

					 <img src="../images/icon_del.gif" />

					 </a>';

			}

			else

			{

				echo'<span class=page_link)">

				<a href="javascript:index_del(\''.$tagName.'\',\''.$del_name.'\',\''.$del_value.'\');">

				<img src="../images/icon_del.gif" />

				</a>';

			}
			}
		}

		switch($mod_page)

		{

			case '':

				echo '&nbsp;';



			break;

			case 'capture':

			 	echo'|<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">

					<img src="../images/icon_mdf.gif" />下载

			</a>';

			break;

			case 'fw_policy_table':

				echo'<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">
				<img src="../images/icon_mdf.gif" />
			    </a>';
			break;

				case 'session_stat':

			if($del_value==''){


				$mod_value1=$del_name;
				echo'<a href="javascript:index_detail(\''.$mod_page.'\',\''.$mod_value.'\')" target="">

				<img src="../images/icon_mdf.gif" />
				</a>';
				$mod_page='temp_blocking_policy';
			   echo'<td  class="content_td">
							<div align="left">
				<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">

				<img src="../images/tab_del.gif" /></a>
							</div>
						</td>';}
			   else {
			  $mod_value1=$del_name;
			  $mod_value2=$inface_name;
			       $mod_page='temp_blocking_policy';
			  echo '<a href="javascript:index_temp_add2(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\',\''.$mod_value2.'\')" target="">
				   <img src="../images/tab_del.gif" /></a>';
			    }


			break;


			case 'flow_stat_table':
			if($del_value==''){
					$mod_value1=$del_name;
				echo'<a href="javascript:index_detail(\''.$mod_page.'\',\''.$mod_value.'\')" target="">

				<img src="../images/icon_mdf.gif" />'.get_string_by_module("Detail", $statistics_monitor_language_list).'

			</a>';
				$mod_page='temp_blocking_policy';
			   echo'<td class="content_td">
							<div align="left">
				<a href="javascript:index_temp_add(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\')" target="">

				<img src="../images/tab_del.gif" /></a>
							</div>
						</td>';}
		 else {
			  $mod_value1=$del_name;
			  $mod_value2=$inface_name;
			  $mod_page='temp_blocking_policy';
			  echo '<a href="javascript:index_temp_add2(\''.$mod_page.'\',\''.$mod_value.'\',\''.$mod_value1.'\',\''.$mod_value2.'\')" target="">
				   <img src="../images/tab_del.gif" /></a>';
			    }

			break;

			default:

			echo'<a href="javascript:index_mod(\''.$mod_page.'\',\''.$mod_value.'\')" target="">

				<img src="../images/icon_mdf.gif" />

			</a>';

			break;

		}

	}

	echo'</span></nobr></td></tr>';

}

// return: count value
function set_auth_count($type)
{
	$write_flag = 0;
	$filename = "/tmp/auth_count";
	if (file_exists($filename))
	{
		$file_pointer = fopen($filename, "r");
		$count = (int)fgets($file_pointer, 4);
		fclose($file_pointer);
	}
	else
	{
		$file_pointer = fopen($filename, "w");	
		$count = 0;
		fwrite($file_pointer, strval($count), 4);
		fclose($file_pointer);			
	}
	if ($type == 'add')
	{
		$count++;	
		$write_flag = 1;		
	}
	else if ($type == 'div')
	{
		$count--;	
		$write_flag = 1;		
	}

	if ($count > 5)
	{
		$count = 5;
		$write_flag = 1;
	}
	if ($count < 0)
	{
		$count = 0;
		$write_flag = 1;
	}
		
	if ($write_flag == 1)
	{
		$file_pointer = fopen($filename, "w");	
		fwrite($file_pointer, strval($count), 4);
		fclose($file_pointer);			
	}
	return $count;
}



function show_ftype()

{

	global $type_content_arr;

	foreach($type_content_arr as $key => $value)

	{

		echo '<option value="'.$key.'">'.$key.':'.$value.'</option>';

	}

}

function show_av_file_type()

{

	global $type_content_arr;

	foreach($type_content_arr as $key => $value)

	{

		echo '<option value="'.$key.'">'.$key.'</option>';

	}

}

function show_timezone()

{
	require '/www/custom/language_resource/time_zone_language.php';
	global $time_zone;

	for($i=0;$i<count($time_zone);$i++)

	{

		if($i == 56)

		{

			echo'<option value="'.($i+1).'" selected="selected">';

			echo_string_by_module($time_zone[$i+1], $time_zone_language_list);
			//echo $time_zone[$i+1];

			echo '</option>';

		}

		else

		{

			echo'<option value="'.($i+1).'">';

			echo_string_by_module($time_zone[$i+1], $time_zone_language_list);
			//echo $time_zone[$i+1];

			echo '</option>';

		}

	}

}

function index_zonetime($index)

{

	global $time_zone;

	if($index <= count($time_zone) && $index >=1)

	{

		echo $time_zone[$index-1];

	}

}



function show_year()

{

	//$Ystart = date('Y')-10;

	//$Yend = date('Y')+10;

	$Ystart = 0;

	$Yend = 36;

	for($i= $Ystart;$i<$Yend;$i++)

	{

		$year_txt = 2000 + $i;

		echo '<option value="'.$i.'">'.$year_txt.'</option>';

	}

}

function show_month()

{

	for($i=1;$i<=9;$i++)

		echo'<option value="'.$i.'">0'.$i.'</option>';

	for($i=10;$i<=12;$i++)

		echo '<option value="'.$i.'">'.$i.'</option>';

}

function show_date()

{

	for($i=1;$i<=9;$i++)

		echo'<option value="'.$i.'">0'.$i.'</option>';

	for($i=10;$i<=31;$i++)

		echo '<option value="'.$i.'">'.$i.'</option>';

	}

	function show_hour()

	{

		for($i=0;$i<=9;$i++)

			echo'<option value="'.$i.'">0'.$i.'</option>';

		for($i=10;$i<=23;$i++)

			echo '<option value="'.$i.'">'.$i.'</option>';

	}

	function show_minute()

	{

		for($i=0;$i<=9;$i++)

			echo'<option value="'.$i.'">0'.$i.'</option>';

		for($i=10;$i<=59;$i++)

			echo'<option value="'.$i.'">'.$i.'</option>';

	}

	function list_header($title,$add_page,$content_title,$nodes,$checkbox)//$checkbox用来控制是否有复选框

	{

	switch($nodes)

		{

			case 'um_localuserlist':
				echo '

						<DIV id=INNER align=left style="margin-top:5pt;">

							<table border="0" cellpadding="0" cellspacing="0" width="100%">

						  <tbody>

						    <tr>

						      <td colspan="2" height="30">

								<table width="100%" border="0" cellspacing="0" cellpadding="0">


								</table>

							 </td>

						 	</tr>

						 </tbody>

						</table>
						</DIV>

						<DIV id=INNER align=left>

				        <DIV id=div_tab >

						<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist" >

						<tr height="30">

						</tr>';

					break;
			case 'um_localdepartmentlist':

				echo '

						<DIV id=INNER align=left  style="margin-top:5pt;" >

							<table border="0" cellpadding="0" cellspacing="0" width="97%">

						  <tbody>

						    <tr>

						      <td colspan="2" height="30">

								<table width="100%" border="0" cellspacing="0" cellpadding="0">

										<tr>

											<td style="width:15px; background-color:#333;">&nbsp;</td>

											<td class="title_tbl_content" >'.$title.'</td>

										</tr>

								</table>

							 </td>

						 	</tr>

						 </tbody>

						</table>
						</DIV>

						<DIV id=INNER align=left>

				        <DIV id=div_tab style="padding-left:15px;">

						<table border="0" cellpadding="0" cellspacing="0" width="98%" class="mlist" >

						<tr height="30">

						</tr>';

					break;
				case 'um_onlineuser':
						echo '
						<DIV id=INNER align=left style="padding-left:15px">

				        <DIV id=div_tab >

						<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist">

						<tr height="10">

						</tr>';

					break;
			  case 'rule':
									echo '
									<DIV id=INNER align="left" style="margin-top:2pt;width:100%;">
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tbody>
											<tr>
												<td colspan="2" height="30">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td style="width:15px; background-color:#333;">&nbsp;</td>
															<td class="title_tbl_content" >'.$title.'</td>
														</tr>
													</table>
												</td>
											</tr>
											</tbody>
										</table>
									</DIV>	
									<table border="0" cellpadding="0" cellspacing="0" id="switch_eth0_T4" width="100%" class="mlist" style="padding-left:40px">
									<tr height="18">
						 			</tr>';	
							break;	
									
		require '/www/custom/language_resource/sys_language.php';
	switch($nodes)
	{
		case 'um_onlineuser':
			echo '
			<DIV id=INNER align=left style="padding-left:15px">
			<DIV id=div_tab >
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist">
			<thead id="mlist_thead">
			';
		break;
		default:
			echo '
			<DIV id=INNER align=left  style="padding-left:15px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist">
			<thead id="mlist_thead">
			';
			break;
	}
			default:
				echo '
					<DIV id=INNER align=left style="margin-top:2pt;">

						<table border="0" cellpadding="0" cellspacing="0" width="100%">

					  <tbody>

					    <tr>

					      <td colspan="2" height="30">

							<table width="100%" border="0" cellspacing="0" cellpadding="0">

							<tr>

								<td style="width:15px; background-color:#333;">&nbsp;</td>

								<td class="title_tbl_content" >'.$title.'</td>

							</tr>

							</table>

						 </td>

					 	</tr>

					 </tbody>

					</table>
					</DIV>

					<DIV id=INNER align=left  style="padding-left:15px;">

			        <DIV id=div_tab >

					<table border="0" cellpadding="0" cellspacing="0" id="get_flag_lantowan" width="100%" class="mlist">

					<tr height="18">

					</tr>';


				break;

		}


		switch($nodes)

		{

			case 'rule':

				//echo '<input type="radio"  id="rule_type1" name="rule_type" value="1" checked="checked" onclick="read_url(\'nat_rule_table\',this.value)"/>静态地址转换';

				//echo '<input type="radio" id="rule_type2" name="rule_type" value="2" onclick="read_url(\'nat_rule_table\',this.value);" />源地址转换';

				//echo '<input type="radio" id="rule_type4" name="rule_type" value="4"  onclick="read_url(\'nat_rule_table\',this.value);"/>目的地址转换';

			default:

				//echo'<input type="checkbox" name="checkbox11" id="checkbox11" />全选';

				break;

		}

		/*

		if(($_SESSION['qx'] =='useradmin') || ($_SESSION['qx'] == 'telecomadmin'))

		{

			if(!empty($add_page) && $add_page == 'l2tp_grp')

			{

				echo'</span><span class="tab_head_opration" id="op_add">

              	<a href="javascript:new_dir(\''.$add_page.'\');">

					<img src="../images/add.gif" width="10" height="10" />  编辑

				</a>

            </span>';

			}

			else if(!empty($add_page)&&$add_page !='syslog_filter')

			{

        	echo'</span><span class="tab_head_opration" id="op_add">

              	<a href="javascript:index_add(\''.$add_page.'\');">

					<img src="../images/add.gif" width="10" height="10" /> 添加

				</a>

            </span>';

			}

			else if($add_page == 'syslog_filter')

			{

			echo'</span><span class="tab_head_opration" id="op_add">

              	<a href="javascript:index_mod(\''.$add_page.'\',\' \');">

					<img src="../images/add.gif" width="10" height="10" /> 编辑

				</a>

            </span>';

			}

		}

         echo'<span class="tab_head_opration" id="op_del">

            	<a href="#"><img src="../images/del.gif" width="10" height="10" />待定 </a>

            </span>

            <span class="tab_head_opration" id="op_mod">

                <a href="#"><img src="../images/edit.gif" width="10" height="10" /> 帮助</a>

            </span>

		*/



		echo '<tr>';

		if ($checkbox == 1)

			echo '<td width=28><INPUT type=checkbox value="-1" onclick="this.value=check(this.form.list)"></td>';

		$count = count($content_title);



		for($i=0;$i<$count;$i++)

		{

   			//echo "<TD width=\"1201\" class=content_value>".$content_title[$i]."</TD>\n";
   			echo "<td width=120>".$content_title[$i]."</td>\n";

		}

		echo '</tr>';

	}

	function list_header1($title,$add_page,$content_title,$nodes,$checkbox)//$checkbox用来控制是否有复选框
	{
		require '/www/custom/language_resource/sys_language.php';
				echo '
					<DIV id=INNER align=left style="margin-top:2pt;">
					  <tbody>
					    <tr>
					      <td colspan="2" height="30">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							</table>
						 </td>
					 	</tr>
					 </tbody>
					</DIV>
					<DIV id=INNER align=left  style="padding-left:15px;">
			        <DIV id=div_tab >
					<table border="0" cellpadding="0" cellspacing="0" id="get_flag_lantowan" width="100%" class="mlist">
					<tr height="2">
					</tr>';

		$count = count($content_title);
		for($i=0;$i<$count;$i++)
		{
   			echo "<td width=120>".$content_title[$i]."</td>\n";
		}
		echo '</tr>';
	}
function list_header2($title,$add_page,$content_title,$nodes,$checkbox)//$checkbox用来控制是否有复选框
	{
				echo '
					<DIV id=INNER align=left style="margin-top:2pt; padding-left:15px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
					  <tbody>
					    <tr>
					      <td colspan="2" height="30">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="width:15px; background-color:#333;">&nbsp;</td>
								<td class="title_tbl_content" >'.$title.'</td>
							</tr>
							</table>
						 </td>
					 	</tr>
					 </tbody>
					</table>
					</DIV>
					<DIV id=INNER align=left  style="padding-left:15px;">
			        <DIV id=div_tab >
					<table border="0" cellpadding="0" cellspacing="0" id="get_flag_lantowan" width="100%" class="mlist">
					<tr height="2">
					</tr>';
		$count = count($content_title);
		for($i=0;$i<$count;$i++)
		{
				echo "<td width=120>".$content_title[$i]."</td>\n";
		}
		echo '</tr>';
	}

	function list_Sheader($title,$content_title,$nodes)
	{   require '/www/custom/language_resource/sys_language.php';
  	    require '/www/custom/language_resource/statistics_monitor_language.php';
		echo '
		<script src="../js_script/datepicker/WdatePicker.js" language="javascript"></script>

		<DIV id=INNER align=left style="margin-top:5pt;">

		<table border="0" cellpadding="0" cellspacing="0" width="100%">

		  <tbody>

		    <tr>

		      <td colspan="2" height="30">

				<table width="100%" border="0" cellspacing="0" cellpadding="0">

				<tr>

					<td style="width:15px; background-color:#333;">&nbsp;</td>

					<td class="title_tbl_content">'.$title.'</td>

				</tr>

				</table>

			 </td>

		 	</tr>

		 </tbody>

		</table>

		</DIV>';

		echo '

		<DIV id=INNER align=left style="padding-top:1px;">

		<TABLE id=ssid_tbl class=mlist cellSpacing=0 cellPadding=0 width="100%" style="padding-top:5px;>

				<tr>

    				<td height="30" colspan="'.count($content_title).'" class="tab_header">

    					<div class="tab_head_div1" style="padding-left:15px"><form action="" method="post" style="margin:0px;padding:0px;" >';

		switch($nodes)

		{

			case 'session_stat':

				echo '<span>'.get_string_by_module("statistics_type", $statistics_monitor_language_list).'';


          //  if((start_all_advanced_security() == 1) || (start_advanced_security_close_ips_av() == 1))
          //  {
               	echo '
									<select name="hhtj_type"  id="hhtj_type" style="width:170px;" onchange="serch_hhtj(this.value,\'disp_title\',\'disp_xy\');">
                	<option value="0">'.get_string_by_module("sip_statistics", $statistics_monitor_language_list).'</option>

                    <option value="1">'.get_string_by_module("dip_statistics", $statistics_monitor_language_list).'</option>

                    <option value="2">'.get_string_by_module("dport_statistics", $statistics_monitor_language_list).'</option>
                       <option value="3">'.get_string_by_module("protocol_statistics", $statistics_monitor_language_list).'</option>
                </select>';
           //   }

        //    else if(close_advanced_security_and_stat_audit() == 1)
		      //  {
        //       	echo '
			//	<select name="hhtj_type" id="hhtj_type" style="width:170px;" onchange="serch_hhtj(this.value,\'disp_title\',\'disp_xy\');">
       //         	<option value="0">'.get_string_by_module("sip_statistics", $statistics_monitor_language_list).'</option>

      //              <option value="1">'.get_string_by_module("dip_statistics", $statistics_monitor_language_list).'</option>

                //    <option value="2">'.get_string_by_module("dport_statistics", $statistics_monitor_language_list).'</option>
     //           </select>';
          //    }

                echo '

            	</span>

            	<span id="disp_title">'.get_string_by_module("source_ip", $statistics_monitor_language_list).'</span>

            	<span id="disp_value">

       				<input type="text" name="serch_ip" id="serch_ip"  valid="isIp"/>

            	</span>

            	<span  id="disp_xy" style="display:none;">'.get_string_by_module("protocol", $statistics_monitor_language_list).'

					<select name="hhtj_proto" id="hhtj_proto" onchange="SetCookie(\'hhtj_proto\',this.value);">

                		<option value="0">ALL</option>

                    	<option value="1">TCP</option>

                    	<option value="2">UDP</option>
                	</select>

            	</span>

            	<span>

            		<input type="submit" class="buttonL" id="search_session_stat" name="search_session_stat" value='.get_string_by_module("search", $statistics_monitor_language_list).'>

            	</span>

            	<span id="errMsg_serch_ip" style="color:#FF0000"></span>

            	<span id="errMsg_serch_port" style="color:#FF0000"></span>

				</div><div class="tab_head_div2">';

				break;

			case 'flow_stat_table':
		 // if((start_all_advanced_security() == 1) || (start_advanced_security_close_ips_av() == 1))

			//	{
							echo '<span>'.get_string_by_module("statistics_type", $statistics_monitor_language_list).'
                <select name="lltj_type" style="width:170px;" id="lltj_type" onchange="serch_lltj(this.value,\'disp_title\');">
									<option value="0">'.get_string_by_module("sip_statistics", $statistics_monitor_language_list).'</option>

                    <option value="1">'.get_string_by_module("dip_statistics", $statistics_monitor_language_list).'</option>

                    <option value="2">'.get_string_by_module("dport_statistics", $statistics_monitor_language_list).'</option>
                    <option value="3">'.get_string_by_module("protocol_statistics", $statistics_monitor_language_list).'</option>
                </select>

            	</span>';
        //  }
			// else if(close_advanced_security_and_stat_audit() == 1)

			//	{
			//					echo '<span>'.get_string_by_module("statistics_type", $statistics_monitor_language_list).'
       //         <select name="lltj_type" style="width:170px;" id="lltj_type" onchange="serch_lltj(this.value,\'disp_title\');">
    	  //          	<option value="0">'.get_string_by_module("sip_statistics", $statistics_monitor_language_list).'</option>

        //            <option value="1">'.get_string_by_module("dip_statistics", $statistics_monitor_language_list).'</option>

         //           <option value="2">'.get_string_by_module("dport_statistics", $statistics_monitor_language_list).'</option>
         //       </select>

          //  	</span>';
					//	}
					echo '
            	<span id="disp_title">'.get_string_by_module("source_ip", $statistics_monitor_language_list).'</span>

            	<span id="disp_value">

            		<input type="text" name="serch_ip" id="serch_ip" valid="isIp" />

            	</span>

				<span>

					<input type="submit" class="buttonL" id="search_flow_stat" name="search_flow_stat"  value='.get_string_by_module("search", $statistics_monitor_language_list).'>

				</span>

            	<span id="errMsg_serch_ip" style="color:#FF0000"></span>

            	<span id="errMsg_serch_port" style="color:#FF0000"></span>

				</div><div class="tab_head_div2">';

				break;

			case 'history_flow_stat_table':

				echo '<span>'.get_string_by_module("statistics_type", $statistics_monitor_language_list).'';
		//		 if((start_all_advanced_security() == 1) || (start_advanced_security_close_ips_av() == 1))
			//	 {
				echo '

                <select name="history_flow_stat_type" style="width:140px;" id="history_flow_stat_type" onchange="serch_history_flow_stat(this.value,\'disp_title\');">

                			<option value="0">'.get_string_by_module("sip_statistics", $statistics_monitor_language_list).'</option>
                      <option value="1">'.get_string_by_module("protocol_statistics", $statistics_monitor_language_list).'</option>
                </select>';
         //     }
             //  else if(close_advanced_security_and_stat_audit() == 1)
             //   	 {
						//		echo '

          //      <select name="history_flow_stat_type" style="width:140px;" id="history_flow_stat_type" onchange="serch_history_flow_stat(this.value,\'disp_title\');">

            //    			<option value="0">'.get_string_by_module("sip_statistics", $statistics_monitor_language_list).'</option>
            //    </select>';
            //  }
                echo '

            	</span>
            	<span>'.get_string_by_module("time_range", $statistics_monitor_language_list).'
                <select name="time_range" id="time_range" style="width:130px;" onchange="SetCookie(\'time_range\',this.value);SetCookie(\'current_page\', 0);">
                			<option value="0">'.get_string_by_module("ten_minutes", $statistics_monitor_language_list).'</option>
                			<option value="2">'.get_string_by_module("one_hour", $statistics_monitor_language_list).'</option>
                      <option value="1">'.get_string_by_module("twenty_four_hours", $statistics_monitor_language_list).'</option>
                </select>

            	</span>
            	<span id="disp_title">'.get_string_by_module("source_ip", $statistics_monitor_language_list).'</span>

            	<span id="disp_value">

            		<input type="text" name="serch_ip" id="serch_ip" valid="isIp" />

            	</span>
            	<input type="submit"  class="buttonL" id="search_history_flow_stat_table" name="search_history_flow_stat_table" value='.get_string_by_module("search", $statistics_monitor_language_list).' >';

				break;
			case 'session_scout':

				echo'<span>协议

            		<select style="width:50px;" name="hhjk_proto" id="hhjk_proto" onchange="serch_hhjk(this.value,\'disp_value\');">

                		<option value="0">ANY</option>

                    	<option value="1">TCP</option>

                    	<option value="2">UDP</option>

                    	<option value="3">ICMP</option>

                    	<option value="4">OTHER</option>

            		</select>

            	</span>

            	<span>类型

                <select style="width:50px;" name="hhjk_linktype">

                	<option value="0">所有</option>

                    <option value="1">全连接</option>

                    <option value="2">半连接</option>

                </select>

            	</span>

            	<span>源IP/掩码

                	<input type="text" style="width:80px;" name="serch_sip" valid="isIp" errmsg="IP格式错误!" />

            	</span>

            	<span>目的IP/掩码

                	<input type="text" style="width:80px;" name="serch_dip" valid="isIp" errmsg="IP格式错误!"/>

            	</span>

            	<span id="disp_title">目的端口/范围(a-b)

					<input type="text" style="width:80px;" name="serch_dport" id="serch_dport" valid="range" min="1" max="65535" errmsg="端口号范围溢出!" />

            	</span>&nbsp;

            	<span>

            		<input type="submit" class="buttonL" value="检索"  />

            	</span>

            	<span id="errMsg_serch_sip" style="color:#FF0000"></span>

            	<span id="errMsg_serch_dip" style="color:#FF0000"></span>

            	<span id="errMsg_serch_dport" style="color:#FF0000"></span>';

				break;

			case 'fw_policy_table':

				echo'';

				break;

			case 'event_log':
				if (0 == $_SESSION['flag_gshdsl'])
				{

                    	echo '<span>&nbsp;&nbsp;'.get_string_by_module('log_type', $log_language_list).'

                    	<select name="log_type" id="log_type" onchange="SetCookie(\'log_type\',this.value);">

                    	<option value="0">'.get_string_by_module('log_all_log', $log_language_list).'</option>

                    	<option value="1">'.get_string_by_module('log_equip_alarm', $log_language_list).'</option>

                    	<option value="2">'.get_string_by_module('log_login', $log_language_list).'</option>

                    	<option value="3">'.get_string_by_module('log_operation', $log_language_list).'</option>

                    	<option value="4">'.get_string_by_module('log_ARP', $log_language_list).'</option>

                    	<option value="5">'.get_string_by_module('log_DDos', $log_language_list).'</option>

                    	<option value="6">'.get_string_by_module('log_URL_filter', $log_language_list).'</option>

                    	<option value="12">'.get_string_by_module('log_flow', $log_language_list).'</option>';
				}
				else
				{
					echo '<span>&nbsp;&nbsp;'.get_string_by_module('log_type', $log_language_list).'

                    	<select name="log_type" id="log_type" onchange="SetCookie(\'log_type\',this.value);">

                    	<option value="0">'.get_string_by_module('log_all_log', $log_language_list).'</option>

                    	<option value="1">'.get_string_by_module('log_equip_alarm', $log_language_list).'</option>

                    	<option value="2">'.get_string_by_module('log_login', $log_language_list).'</option>

                    	<option value="3">'.get_string_by_module('log_operation', $log_language_list).'</option>';
					
				}

                    //	if(get_custom_info('board') == 'MSG2300')
                    	if(start_all_advanced_security() == 1)
                    	{
                    	   echo'
                    		<option value="7">'.get_string_by_module('log_virus', $log_language_list).'</option>

                    		<option value="95">'.get_string_by_module('log_IPS', $log_language_list).'</option>';
                    	}

                   echo'

            		</select>

            	</span>

				<span>&nbsp;&nbsp;'.get_string_by_module('log_level', $log_language_list).'

						<select name="log_level" id="log_level" onchange="SetCookie(\'log_level\',this.value);" style="width:85px">

						<option value="0">'.get_string_by_module('log_all', $log_language_list).'</option>

                		<option value="1">'.get_string_by_module('log_emergency', $log_language_list).'</option>

                        <option value="2">'.get_string_by_module('log_alarm', $log_language_list).'</option>

                        <option value="3">'.get_string_by_module('log_serious', $log_language_list).'</option>

                        <option value="4">'.get_string_by_module('log_error', $log_language_list).'</option>

                        <option value="5">'.get_string_by_module('log_warning', $log_language_list).'</option>

                        <option value="6">'.get_string_by_module('log_notice', $log_language_list).'</option>

                        <option value="7">'.get_string_by_module('log_Info', $log_language_list).'</option>

                        <option value="8">'.get_string_by_module('log_debug', $log_language_list).'</option>

            		</select>

            	</span>

				<span>&nbsp;&nbsp;

					'.get_string_by_module('log_time_range', $log_language_list).'  <input  type="text" class="Wdate"  onFocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'})" style="width:15%;" name="serchlog_stime" id="serchlog_stime" valid="isTime2" errmsg="'.get_string_by_module('log_time_format', $log_language_list).'"/> ~

					          <input  type="text" class="Wdate"  onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'})" style="width:15%;" name="serchlog_etime" id="serchlog_etime" Aatime="serchlog_stime" valid="isTime2|sametime|timesequence" errmsg="'.get_string_by_module('log_time_format2', $log_language_list).'" />

				</span>

				<span>&nbsp;&nbsp;

					'.get_string_by_module('log_records', $log_language_list).'  <input type="text" style="width:50px" name="record_num" id="record_num" valid="range" min="1" max="1000" errmsg="'.get_string_by_module('log_records_exceed', $log_language_list).'"/>

				</span>

				<div  align="right">
				<br/>

				<span>

					<input type="submit" class="buttonL" name="serch_submit" value="'.get_string_by_module('log_search', $log_language_list).'" />&nbsp;&nbsp;

				</span>

				<span>

					<input type="submit" class="buttonL" name="clear_submit"  onclick="return onCheck();" value="'.get_string_by_module('log_clear_log', $log_language_list).'" />

				</span>

                </div>

				</div><div class="tab_head_div2">';

				break;

			case 'max_station':

				echo '<span>无线接口

						<select name="wlan_name" id="wlan_name" onchange="check_dev(this);">

                		<option value="0">WLAN1</option>

                    	<option value="1">WLAN2</option>

                    	<option value="2">WLAN3</option>

						<!--

						<option value="3">WLAN4</option>

						!-->

            		</select>

            	</span>

				<span>

					<input type="submit" style="width:50px;" value="查看" />

				</span>

				</div><div class="tab_head_div2">';

				break;

			case 'audit_log':

				echo '<span>&nbsp;&nbsp;'.get_string_by_module('log_time_range', $log_language_list).'  <input type="text"  class="Wdate"  onFocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\',minDate:\'%y-%M-%#{%d - 7}\',maxDate:\'%y-%M-%d\'})" style="width:15%;" name="serchlog_stime" id="serchlog_stime"/> ~
					<input type="text" class="Wdate"  onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\',minDate:\'%y-%M-%#{%d - 7}\',maxDate:\'%y-%M-%d\'})" style="width:15%;" name="serchlog_etime" id="serchlog_etime"  Aatime="serchlog_stime" valid="timesequence"  errmsg="'.get_string_by_module('log_time_wrong', $log_language_list).'"/></span>
					
					<span>&nbsp;&nbsp;&nbsp;&nbsp;'.get_string_by_module('source_ip', $log_language_list).'   <input type="text" style="width:12%" name="sipaddr" id="sipaddr" valid="isIp" errmsg='.get_string_by_module('log_ip_format', $log_language_list).' />(xxx.xxx.xxx.xxx)&nbsp;&nbsp;&nbsp;&nbsp;</span>
					<span>&nbsp;'.get_string_by_module('log_user', $log_language_list).': <input type="text" style="width:10%" name="audit_user" id="audit_user"/></span>
				<span>
					'.get_string_by_module('log_records_search', $log_language_list).'  <input type="text" style="width:50px" name="record_num" id="record_num" valid="range" min="1" max="100000" errmsg='.get_string_by_module('log_records_exceed', $log_language_list).' value="1000"/>
				</span>
				<div  align="right">
				<br/>
				<span>&nbsp;&nbsp;
					<input type="submit" class="buttonL" name="serch_submit" value='.get_string_by_module('log_search', $log_language_list).' />&nbsp;&nbsp;
				</span>
				<!--span>
					<input type="submit" class="buttonL" name="clear_submit"  value='.get_string_by_module('log_clear_log', $log_language_list).' />
				</span-->
				</div><br/><div class="tab_head_div2">';

				break;
			default:

				break;

		}

        	echo'</div>

   		  </td>

  		</tr>';

		echo '<tr>';

		for($i=0;$i<count($content_title);$i++)

		{

   			echo'<td  class="list_row">

					<div align="left">

						<span>'.$content_title[$i].'</span>

        			</div>

				</td>';

		}

		echo '</tr>';

	}


	function list_bottom_um($length,$page,$page_count,$col_span,$add_page)

	{
		echo '</table>

		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist">';

		switch($length)

		{

			case 'um_localdepartmentlist':
				  echo'</table><table style="width:100%;">';
				break;

			default:
					  echo'<tr>
				             <td colspan="'.$col_span.'" style="width:100%;">
				            <div style="float:left; height:20px; padding-top:10px;">
				                	'.get_string("ch_page1").'
				                    <span id="list_count">'.$length.'</span>'.get_string("ch_page2").'
				                    <span id="page_index">'.$page.'</span>'.get_string("ch_page3").'
				                    <span id="page_count">'.$page_count.'</span>'.get_string("ch_page4").'
				            </div>
				            <div style="float:right; height:10px;  padding-top:10px;">
				               	<span id="page_one">
				             			<INPUT class="button"  onclick="javascript:show_page(1)" type=button value="'.get_string("ch_page6").'">
				                </span>
				            	<span id="page_prev">
				             			<INPUT class="button"   onclick="javascript:show_page(2)" type=button value="'.get_string("ch_page7").'">
				                </span>
				                <span id="page_next">
										<INPUT class="button"   onclick="javascript:show_page(3)" type=button value='.get_string("ch_page8").'>
				                </span>
				                <span id="page_last">
										<INPUT class="button"   onclick="javascript:show_page(4)" type=button value="'.get_string("ch_page9").'">
				                </span>
				                <span style="margin-bottom:10px; font-size:12px; vertical-align:middle ;">'.get_string("ch_page10").'<input type="text"  name="textfield" id="page_jump" style="width:30px; height:17px; font-size:12px; border:solid 1px #7aaebd; margin-bottom:10px;" />'.get_string("ch_page5").'
				                </span>
				                <span>
										<INPUT class="button" style="width:45px;"   onclick="javascript:show_page(5)" type=button value="'.get_string("ch_page11").' ">
				                </span>
				            </div>
				        </td>
				      </tr></table><table style="width:100%;">';
				break;
		}



		if(($_SESSION['qx'] =='useradmin') || ($_SESSION['qx'] == 'telecomadmin'))

		{

			if(!empty($add_page) && $add_page == 'l2tp_grp')

			{

				echo'<tr><td style="width:100%; text-align:right"><span>

				<input type="button" class="buttonL" onclick="javascript:new_dir(\''.$add_page.'\');" value="'.get_string('edit').'">

            </span></td ></tr>';//编辑

			}

			else if(!empty($add_page)&&$add_page !='syslog_filter')

			{

	        	echo'<tr><td style="width:100%; text-align:right"><span class="tab_head_opration" id="op_add">

	        	<input type="button" class="buttonL" onclick="javascript:index_add(\''.$add_page.'\');" value="'.get_string('add').'">

	            </span></td></tr>';//添加

			}

			else if($add_page == 'syslog_filter')

			{

				echo'<tr><td style="width:100%; text-align:right"><span>

				<input type="button" class="buttonL" onclick="javascript:index_mod(\''.$add_page.'\');" value="'.get_string('edit').'">

	            </span></td ></tr>';//编辑

			}

		}



    	echo '</table>' ;

	}

		function multi_list_bottom($length,$page,$page_count,$col_span,$add_page,$list_num,$list_no,$reset)

	{    $i = $list_no;
	     $j = $list_num;
	     $k=  $reset;
		echo '</table>

		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist" >';

		switch($length)

		{
          	default:
					  echo'<tr>

		             <td colspan="'.$col_span.'" style="width:100%;">

		            <div style="float:left; height:20px; padding-top:10px;">

		                	'.get_string("ch_page1").'

		                    <span id="list_count'.$i.'">'.$length.'</span>'.get_string("ch_page2").'

		                    <span id="page_index'.$i.'">'.$page.'</span>'.get_string("ch_page3").'

		                    <span id="page_count'.$i.'">'.$page_count.'</span>'.get_string("ch_page4").'

		            </div>

		            <div style="float:right; height:10px;  padding-top:10px;">

		               	<span id="page_one'.$i.'">



		             			<INPUT class="button"  onclick="javascript:multi_show_page(1,'.$i.','.$j.','.$k.')" type=button value="'.get_string("ch_page6").'">



		                </span>

		            	<span id="page_prev'.$i.'">

		             			<INPUT class="button"   onclick="javascript:multi_show_page(2,'.$i.','.$j.','.$k.')" type=button value="'.get_string("ch_page7").'">



		                </span>

		                <span id="page_next'.$i.'">

								<INPUT class="button"   onclick="javascript:multi_show_page(3,'.$i.','.$j.','.$k.')" type=button value='.get_string("ch_page8").'>

		                </span>

		                <span id="page_last'.$i.'">

								<INPUT class="button"   onclick="javascript:multi_show_page(4,'.$i.','.$j.','.$k.')" type=button value="'.get_string("ch_page9").'">

		                </span>

		                <span style="margin-bottom:10px; font-size:12px; vertical-align:middle ;">'.get_string("ch_page10").'<input type="text"  name="textfield" id="page_jump'.$i.'" style="width:30px; height:17px; font-size:12px; border:solid 1px #7aaebd; margin-bottom:10px;" />'.get_string("ch_page5").'

		                </span>

		                <span>

								<INPUT class="button" style="width:45px;"   onclick="javascript:multi_show_page(5,'.$i.','.$j.','.$k.')" type=button value="'.get_string("ch_page11").' ">

		                </span>

		            </div>

		        </td>

		      </tr></table><table style="width:100%;">';


			break;

		}



		if(($_SESSION['qx'] =='useradmin') || ($_SESSION['qx'] == 'telecomadmin'))

		{

			if(!empty($add_page) && $add_page == 'l2tp_grp')

			{

				echo'<tr><td style="width:100%; text-align:right"><span>

				<input type="button" class="buttonL" onclick="javascript:new_dir(\''.$add_page.'\');" value="'.get_string('edit').'">

            </span></td ></tr>';//编辑

			}

			else if(!empty($add_page)&&$add_page !='syslog_filter')

			{

	        	echo'<tr><td style="width:100%; text-align:right"><span class="tab_head_opration" id="op_add">

	        	<input type="button" class="buttonL" onclick="javascript:index_add(\''.$add_page.'\');" value="'.get_string('add').'">

	            </span></td></tr>';//添加

			}

			else if($add_page == 'syslog_filter')

			{

				echo'<tr><td style="width:100%; text-align:right"><span>

				<input type="button" class="buttonL" onclick="javascript:index_mod(\''.$add_page.'\');" value="'.get_string('edit').'">

	            </span></td ></tr>';//编辑

			}

		}



    	echo '</table>' ;

	}
    
    function list_ap_scan_bottom($length,$page,$page_count,$col_span,$add_page)
    {
        echo '</table>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist" >';
        echo'<tr>
        <td colspan="'.$col_span.'" style="width:100%;">
        <div style="float:left; height:20px; padding-top:15px;">
        '.get_string("ch_page1").'
        <span id="list_count">'.$length.'</span>'.get_string("ch_page2").'
        <span id="page_index">'.$page.'</span>'.get_string("ch_page3").'
        <span id="page_count">'.$page_count.'</span>'.get_string("ch_page4").'
	    </div>
        <div style="float:right; height:10px;  padding-top:10px;">
           	<span id="page_one">
         		<INPUT class="button"  onclick="javascript:show_ap_scan_page(1)" type=button value="'.get_string("ch_page6").'">
            </span>
        	<span id="page_prev">
         		<INPUT class="button"   onclick="javascript:show_ap_scan_page(2)" type=button value="'.get_string("ch_page7").'">
            </span>
            <span id="page_next">
				<INPUT class="button"   onclick="javascript:show_ap_scan_page(3)" type=button value='.get_string("ch_page8").'>
            </span>
            <span id="page_last">
				<INPUT class="button"   onclick="javascript:show_ap_scan_page(4)" type=button value="'.get_string("ch_page9").'">
            </span>
            <span style="margin-bottom:10px; font-size:12px; vertical-align:middle ;">'.get_string("ch_page10").'<input type="text"  name="textfield" id="page_jump" style="width:30px; height:17px; font-size:12px; border:solid 1px #7aaebd; margin-bottom:10px;" />'.get_string("ch_page5").'
            </span>
            <span>
                <INPUT class="button" style="width:45px;"   onclick="javascript:show_ap_scan_page(5)" type=button value="'.get_string("ch_page11").' ">
           </span>
        </div>
        </td>
        </tr>
        </table>
        <div style="text-align: right; margin-top: 5px;"></div>';  
    }

    function list_bottom_for_ucs($length,$page,$page_count,$col_span,$add_page,$parts)

	{
		echo '</table>

		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist" >';

		switch($length)

		{

//			case 'um_localdepartmentlist':
//
//				  echo'</table><table style="width:100%;">';
//				break;


			default:
					  echo'<tr>

		             <td colspan="'.$col_span.'" style="width:100%;">

		            <div style="float:left; height:20px; padding-top:15px;">

		                	'.get_string("ch_page1").'

		                    <span id="list_count">'.$length.'</span>'.get_string("ch_page2").'

		                    <span id="page_index">'.$page.'</span>'.get_string("ch_page3").'

		                    <span id="page_count">'.$page_count.'</span>'.get_string("ch_page4").'

		            </div>

		            <div style="float:right; height:10px;  padding-top:10px;">

		               	<span id="page_one">



		             			<INPUT class="button"  onclick="javascript:show_page_for_ucs(1,\''.$parts.'\')" type=button value="'.get_string("ch_page6").'">



		                </span>

		            	<span id="page_prev">

		             			<INPUT class="button"   onclick="javascript:show_page_for_ucs(2,\''.$parts.'\')" type=button value="'.get_string("ch_page7").'">



		                </span>

		                <span id="page_next">

								<INPUT class="button"   onclick="javascript:show_page_for_ucs(3,\''.$parts.'\')" type=button value='.get_string("ch_page8").'>

		                </span>

		                <span id="page_last">

								<INPUT class="button"   onclick="javascript:show_page_for_ucs(4,\''.$parts.'\')" type=button value="'.get_string("ch_page9").'">

		                </span>

		                <span style="margin-bottom:10px; font-size:12px; vertical-align:middle ;">'.get_string("ch_page10").'<input type="text"  name="textfield" id="page_jump" style="width:30px; height:17px; font-size:12px; border:solid 1px #7aaebd; margin-bottom:10px;" />'.get_string("ch_page5").'

		                </span>

		                <span>

								<INPUT class="button" style="width:45px;"   onclick="javascript:show_page_for_ucs(5,\''.$parts.'\')" type=button value="'.get_string("ch_page11").' ">

		                </span>

		            </div>

		        </td>

		      </tr></table><div style="text-align: right; margin-top: 5px;">';


			break;

		}



		if(($_SESSION['qx'] =='useradmin') || ($_SESSION['qx'] == 'telecomadmin'))

		{

			if(!empty($add_page) && $add_page == 'l2tp_grp')

			{

				echo'
				<input type="button" class="buttonL" onclick="javascript:new_dir(\''.$add_page.'\');" value="'.get_string('edit').'">';//编辑

			}

			else if(!empty($add_page)&&$add_page !='syslog_filter')

			{

	        	echo'<input type="button" class="buttonL" onclick="javascript:index_add(\''.$add_page.'\');" value="'.get_string('add').'">';//添加

			}

			else if($add_page == 'syslog_filter')

			{

				echo'
				<input type="button" class="buttonL" onclick="javascript:index_mod(\''.$add_page.'\');" value="'.get_string('edit').'">';//编辑
			}
			else if($add_page == 'ippbx_sip_fwkz')
			{
				echo'
				<input type="button" class="buttonL" onclick="javascript:index_mod(\''.$add_page.'\');" value="'.get_string('add').'">';//编辑
			}

		}



    	echo '</div>' ;

	}
    
    
    
	function list_bottom($length,$page,$page_count,$col_span,$add_page)

	{
		echo '</table>

		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist" >';

		switch($length)

		{

//			case 'um_localdepartmentlist':
//
//				  echo'</table><table style="width:100%;">';
//				break;


			default:
					  echo'<tr>

		             <td colspan="'.$col_span.'" style="width:100%;">

		            <div style="float:left; height:20px; padding-top:15px;">

		                	'.get_string("ch_page1").'

		                    <span id="list_count">'.$length.'</span>'.get_string("ch_page2").'

		                    <span id="page_index">'.$page.'</span>'.get_string("ch_page3").'

		                    <span id="page_count">'.$page_count.'</span>'.get_string("ch_page4").'

		            </div>

		            <div style="float:right; height:10px;  padding-top:10px;">

		               	<span id="page_one">



		             			<INPUT class="button"  onclick="javascript:show_page(1)" type=button value="'.get_string("ch_page6").'">



		                </span>

		            	<span id="page_prev">

		             			<INPUT class="button"   onclick="javascript:show_page(2)" type=button value="'.get_string("ch_page7").'">



		                </span>

		                <span id="page_next">

								<INPUT class="button"   onclick="javascript:show_page(3)" type=button value='.get_string("ch_page8").'>

		                </span>

		                <span id="page_last">

								<INPUT class="button"   onclick="javascript:show_page(4)" type=button value="'.get_string("ch_page9").'">

		                </span>

		                <span style="margin-bottom:10px; font-size:12px; vertical-align:middle ;">'.get_string("ch_page10").'<input type="text"  name="textfield" id="page_jump" style="width:30px; height:17px; font-size:12px; border:solid 1px #7aaebd; margin-bottom:10px;" />'.get_string("ch_page5").'

		                </span>

		                <span>

								<INPUT class="button" style="width:45px;"   onclick="javascript:show_page(5)" type=button value="'.get_string("ch_page11").' ">

		                </span>

		            </div>

		        </td>

		      </tr></table><div style="text-align: right; margin-top: 5px;">';


			break;

		}



		if(($_SESSION['qx'] =='useradmin') || ($_SESSION['qx'] == 'telecomadmin'))

		{

			if(!empty($add_page) && $add_page == 'l2tp_grp')

			{

				echo'
				<input type="button" class="buttonL" onclick="javascript:new_dir(\''.$add_page.'\');" value="'.get_string('edit').'">';//编辑

			}

			else if(!empty($add_page)&&$add_page !='syslog_filter')

			{

	        	echo'<input type="button" class="buttonL" onclick="javascript:index_add(\''.$add_page.'\');" value="'.get_string('add').'">';//添加

			}

			else if($add_page == 'syslog_filter')

			{

				echo'
				<input type="button" class="buttonL" onclick="javascript:index_mod(\''.$add_page.'\');" value="'.get_string('edit').'">';//编辑
			}
			else if($add_page == 'ippbx_sip_fwkz')
			{
				echo'
				<input type="button" class="buttonL" onclick="javascript:index_mod(\''.$add_page.'\');" value="'.get_string('add').'">';//编辑
			}

		}



    	echo '</div>' ;

	}
function list_btn($content,$show_page,$param_name,$param_value)
{
	echo '<table width="100%">';
	if(!empty($content)&&$content=='add')
	{
		if(($_SESSION['qx'] =='useradmin') || ($_SESSION['qx'] == 'telecomadmin'))
		{
			echo'<tr><td style="width:100%; text-align:right"><span class="tab_head_opration" id="op_add">

	        	<input type="button" class="buttonL" onclick="javascript:index_add_with_param(\''.$show_page.'\',\''.$param_name.'\',\''.$param_value.'\');" value="'.get_string($content).'">

	            </span></td></tr>';//添加
		}
	}
	echo '</table>';
}
function node_info($title,$opration,$inf_arr,$mod_page)

{

	echo '<div id="INNER" align="left" style="margin-top:10px; padding-left:10px">

		   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">

			<tr>

				<td style="width:15px; background-color:#333;">&nbsp;</td>

				<td class="title_tbl_content">'.$title.'</td>

			</tr>

		  </table></div>

		<div id="INNER" align="left" style="padding-left:20px">';

	echo '<table width="100%" border="0"  cellpadding="0" cellspacing="0" class="table_list">';

	echo '<tbody class="content_body_area">';



	foreach($inf_arr as $key => $value)

	{

		if($value != "")

			echo "<tr><td width=\"150\">".$key."</td><td colspan=\"2\">".$value."</td></tr>";

		else

			echo "<tr><td width=\"150\">".$key."</td><td>&nbsp;</td></tr>";

	}

	echo '</tbody>

		</table></div>';



	if(($_SESSION['qx'] == 'useradmin' || $_SESSION['qx'] == 'telecomadmin')&& ($opration !=''))

		//echo '<div class="buttonL">'.$opration.'</div></caption>';
		echo '<input class="buttonL" type="button" style="margin-left:92%;margin-top:10px;" value='.get_string("edit").' onclick="javascript:index_mod(\''.$mod_page.'\',\'\')"></caption>';
	else

		echo '</caption>';

}

function usb_header($path,$content_title,$mkdir_page,$upload_page,$dev_type)

{  require '/www/custom/language_resource/usb_language.php';

	echo '

		<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#a8c7ce" 	onmouseover="changeto()"  onmouseout="changeback()" style="clear:both">

				<tr>

    				<td height="30" colspan="'.count($content_title).'" class="tab_header">

    					<div class="tab_head_div1">'.get_string_by_module('Current_Location', $usb_language_list).'<span id="list_title">'.$path.'</span></div>

       				 	<div class="tab_head_div2">';

	$top_arr = preg_split('/\//',$path,-1,PREG_SPLIT_NO_EMPTY);

	$flag_top = count($top_arr);

	array_pop($top_arr);

	if(count($top_arr) ==0)

	{

		$top_path ='/';

	}

	else

	{

		$top_path = join('/',$top_arr);

	}

	if( $top_path !='/')

	{

		echo'<span class="tab_head_opration" id="op_add">

            <a href="javascript:list_dir(\''.$top_path.'\', \''.$dev_type.'\');">

				<img src="../images/add.gif" width="10" height="10" />'.get_string_by_module('Parent_direct', $usb_language_list).'

			</a>

         </span>';

	}

	else if($flag_top == 1)

	{

		echo'<span class="tab_head_opration" id="op_add">

            <a href="javascript:list_dir(\''.$top_path.'\', \''.$dev_type.'\');">

				<img src="../images/add.gif" width="10" height="10" />'.get_string_by_module('Parent_direct', $usb_language_list).'

			</a>

         </span>';

	}

	if($mkdir_page != '')
	{
		echo'<span class="tab_head_opration" id="op_add">

        <a href="javascript:new_dir(\''.$mkdir_page.'\',\''.$path.'\');">

		<img src="../images/add.gif" width="10" height="10" />'.get_string_by_module('New_directory', $usb_language_list).'

		</a>

        </span>';
	}

    if($upload_page != '')
    {
    		echo'<span class="tab_head_opration" id="op_add">

           	<a href="javascript:new_upload(\''.$upload_page.'\',\''.$path.' \');">

				<img src="../images/add.gif" width="10" height="10" />'.get_string_by_module('Upload_file', $usb_language_list).'

			</a>

        </span>';
    }


	echo'</td></tr>';

	echo '<tr>';

	for($i=0;$i<count($content_title);$i++)

	{

   		echo'<td  class="list_row" bgcolor="#d3eaef">

				<div align="center">

					<span>'.$content_title[$i].'</span>

        		</div>

			</td>';

	}

	echo '</tr>';

}

function ftp_header($path,$content_title,$mkdir_page,$upload_page)

{

	echo '

		<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#a8c7ce" 	" style="clear:both">

				<tr>

    				<td height="30" colspan="'.count($content_title).'" class="tab_header">

    					<div class="tab_head_div1">当前位置:<span id="list_title">'.$path.'</span></div>

       				 	<div class="tab_head_div2">';

	$top_arr = explode('/',$path);

	array_pop($top_arr);

	if(count($top_arr) ==1)

	{

		$top_path ='/';

	}

	else

	{

		$top_path = join('/',$top_arr);

	}

	if( $top_path !='/')

	{

		echo'<span class="tab_head_opration" id="op_add">

            <a href="javascript:list_dir(\''.$top_path.'\');">

				<img src="../images/add.gif" width="10" height="10" />上级目录

			</a>

         </span>';

	}

	echo'<span class="tab_head_opration" id="op_add">

            <a href="javascript:new_dir(\''.$mkdir_page.'\',\''.$path.'\');">

				<img src="../images/add.gif" width="10" height="10" />服务器配置

			</a>

         </span>';

	echo'<span class="tab_head_opration" id="op_add">

           	<a href="javascript:new_upload(\''.$upload_page.'\',\''.$path.' \');">

				<img src="../images/add.gif" width="10" height="10" />上传文件

			</a>

        </span>';

	echo'</td></tr>';

	echo '<tr>';

	for($i=0;$i<count($content_title);$i++)

	{

   		echo'<td  class="list_row" bgcolor="#d3eaef">

				<div align="center">

					<span>'.$content_title[$i].'</span>

        		</div>

			</td>';

	}

	echo '</tr>';

}

function usb_operation($tagName,$del_name,$del_value,$down_file,$dev_type)

{ 	require '/www/custom/language_resource/usb_language.php';

	echo'<td ><div align="center" class="content_operation"><span class="content_td">';

	if(!empty($tagName))

	{

		echo'<a href="javascript:index_del(\''.$tagName.'\',\''.$del_name.'\',\''.$down_file.'\',\''.$dev_type.'\');">

				<img src="../images/icon_del.gif" />'.get_string_by_module('Del', $usb_language_list).'

			</a>';

	}

	if($del_value == 1)

	{

		echo' <a href="javascript:ftpdown_file(\''.$down_file.'\',\''.$dev_type.'\')">

				<img src="../images/tab_download.gif" />'.get_string_by_module('Download', $usb_language_list).'

			</a>';

	}

	echo'</span></div></td></tr>';

}



function show_allinfo($title1,$title2,$info_arr)

{

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title></title>

<link href="../css/mod_add.css" rel="stylesheet" type="text/css" />

<script src="../js_script/common.js" language="javascript"></script>

</head>

<body>

<table border="0" cellspacing="0" cellpadding="0"  align="center" class="template_tab">

<thead>

	<tr>

  		<td width="12px"><img src="../images/tab_03.gif" /></td>

    	<td class="template_head_tab">

        	<span id="pos_title"><img  src="../images/tb_pos.gif"/>当前位置：</span>

        	<span id="pos_leve1">'.$title1.'</span>->

        	<span id="pos_leve2">'.$title2.'</span>

    	</td>

    	<td width="12px;"><img src="../images/tab_07.gif" /></td>

  	</tr>

</thead>

<tbody>

  	<tr>

  		<td height="100%"><img src="../images/tab_12.gif" height="660px" width="8px"/></td>

        <td height="660px" valign="top">

        	<table class="template_body">';

			foreach($info_arr as $key => $value)

			{

				echo '<tr><td width="40%">'.$key.'</td><td>'.$value.'</td></tr>';

			}

           echo'</table>

        </td>

      	<td height="100%"><img src="../images/tab_15.gif"  height="660px" width="8px" align="right"/></td>

    </tr>

  </tbody>

  <tr>

  	<td width="12px"><img src="../images/tab_18.gif" /></td>

    <td class="template_foot"></td>

    <td width="12px;"><img src="../images/tab_20.gif" /></td>

  </tr>

</table>

</body>

</html>';

}



function template_header($menu_arr,$cur_index,$help_text,$notice_text)

{

	echo '<div id="BODY">
			<div id="tab_container">

    		<div align="left" style="margin:5px 0;" id="div_tabcontrol" >

			<table cellSpacing=0 cellPadding=0  border=0>

			<tbody><tr>';

	$index =0;

	foreach($menu_arr as $title => $page)

	{

	    if($index == $cur_index)

		{

		echo '<td class=tab_left_cur width="1">&nbsp;</td>

		<td class=tab_content_cur onclick=\'window.location.replace("'.$page.'")\' width="40"><nobr>'.$title.'</nobr></td>

		<td class=tab_right_cur width="1">&nbsp;</td>';

		}

		else

		{

		echo '<td class=tab_left_nr width="1">&nbsp;</td>

		<td class=tab_content_nr onclick=\'window.location.replace("'.$page.'");\' width="40"><nobr>'.$title.'</nobr></td>

		<td class=tab_right_nr width="1">&nbsp;</td>';

		}

		$index++;

	}

	echo'</tr></tbody></table></div></div>';

	if($help_text !='')

	{

	echo '<div id=inner align=left style="margin-top:12pt;margin-bottom:10pt;">

		<font SIZE=2>'.$help_text.'</font><font size=2 color=red>'.$notice_text.'</font></div>';

	echo '<div id=INNER align=left>

	<div id=div_tab></div><div id=tbl_LIMIT_LIST pagesize="" _wnm_control="CTRL_MLIST" oParent="null" m_bSearchFlag="false" m_bMatchAll="false" m_bShowAdvance="false" m_nPageSize="15" m_sId="tbl_LIMIT_LIST" m_sTabHead="<table width=\'100%\' cellpadding=\'0\' cellspacing=\'0\' class=\'mlist\'><thead id=\'mlist_thead\'><tr><td width=15% &amp;#36215;&amp;#22987;&amp;#22320;&amp;#22336;</td><td width=15%>&amp;#32467;&amp;#26463;&amp;#22320;&amp;#22336;</td><td width=15%>&amp;#31867;&amp;#22411;</td><td width=15%>&amp;#25509;&amp;#21475;</td><td width=15%>&amp;#26041;&amp;#21521;</td><td width=*>&amp;#38480;&amp;#36895;&amp;#36895;&amp;#29575;&amp;#65288;kbps&amp;#65289;</td><td>&amp;#25805;&amp;#20316;</td></tr></thead><tbody>" m_bAutoRedraw="true" m_nColNum="6" m_nLineNum="3" m_nTotalPage="1" m_nCurPage="1" nSelectedRows="0" nMode="NaN" oSelectedRow="null" sSortStatus="null" m_bShowNo="false" pfGroupBy="null" bMakeTabTail="true" m_nFrom="1" m_nTo="3">';



	}

	/*

	<div style="DISPLAY: none"><IMG src="icon_del.gif"><IMG src="icon_mdf.gif"></div> <div id=tbl_LIMIT_LIST_tab_body name="tbl_LIMIT_LIST_tab_body"> <table id=listtbl class=mlist cellSpacing=0 cellPadding=0 width="100%"> <thead id=mlist_thead><tr> ';*/

}

function template_bottom($add_page)

{

	echo '</tbody></table></div>

	<div style="text-align:right; margin-top:5px;">

	<input class=buttonL id="createNew" onClick="javascript:location.href=\''.$add_page.'\'" type=button value="新 建">

	<script language="javascript">

		createNewButtonStatus();

	</script>

	</div></div></div></div>

	<div id=MESSAGE style="DISPLAY: none"></div>

	<div id=FOOTER>

	<form id=tab_form wtype="system">

		<input type=hidden name=uid><INPUT type=hidden name="info">

		<input type=hidden name=xml>

	</form></div>';

}

function get_policy_id()

{

	global $obj_guish;

	global $doc;

	$get_xml ='<fw_policy_table action="show_index"></fw_policy_table>';

	$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$get_xml,UCT_CMD);

	$str_xml = $obj_guish->get_xml();

	if($str_xml !="")

	{

		$doc->loadXML($str_xml);

		$id = $doc->getElementsByTagname('id')->item(0)->nodeValue;

		return $id;

	}

	return 0;

}

function get_ipv6_acl_id()
{
	global $obj_guish;
	global $doc;

	$get_xml ='<ipv6_acl_table action="show_index"></ipv6_acl_table>';
		
	$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$get_xml,UCT_CMD);

	$str_xml = $obj_guish->get_xml();

	if($str_xml !="")
	{
		$doc->loadXML($str_xml);

		$id = $doc->getElementsByTagname('id')->item(0)->nodeValue;

		return $id;
	}
	return 0;
}

function get_local_policy_id()
{

	global $obj_guish;

	global $doc;

	$get_xml ='<local_policy_table action="show_index"></local_policy_table>';

	$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$get_xml,UCT_CMD);

	$str_xml = $obj_guish->get_xml();

	if($str_xml !="")

	{

		$doc->loadXML($str_xml);

		$id = $doc->getElementsByTagname('id')->item(0)->nodeValue;

		return $id;

	}

	return 0;

}

function get_qos_policy_id()

{

	global $obj_guish;

	global $doc;

	$get_xml ='<qos_policy_table action="show_index"></qos_policy_table>';

	$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$get_xml,UCT_CMD);

	$str_xml = $obj_guish->get_xml();

	if($str_xml !="")

	{

		$doc->loadXML($str_xml);

		$id = $doc->getElementsByTagname('id')->item(0)->nodeValue;

		return $id;

	}

	return 0;

}

function get_app_ctrl_policy_id()

{

	global $obj_guish;

	global $doc;

	$get_xml ='<app_ctrl_policy_table action="show_index"></app_ctrl_policy_table>';

	$obj_guish->set_rsgui($_SESSION['session_id'],NULL,$get_xml,UCT_CMD);

	$str_xml = $obj_guish->get_xml();

	if($str_xml !="")

	{

		$doc->loadXML($str_xml);

		$id = $doc->getElementsByTagname('id')->item(0)->nodeValue;

		return $id;

	}

	return 0;

}

class um_tree_class
{

	function objtab_show_localuserlist($node_name,$action)
    {
        global $doc;
        $str_xml = Node_show($node_name,$action);
        if(trim($str_xml) !="")
        {
            $doc->loadXML($str_xml);
            $groups = $doc->getElementsByTagName('group');
            $length = $groups->length;
            for($i=0; $i<$length;$i++)
            {
                $n_list = $groups->item($i)->childNodes;
                $user_list = $n_list->item(0)->nodeValue;

                echo '<option value="'.$user_list.'">'.
                $user_list. '</option>';
                echo "</br>";
            }
        }
        else
        {
            echo '<script language="javascript">
            var path =window.location.hostname;
            var path_arr = path.split("/");
            var page = path_arr[path_arr.length-1];
            if((page == "main.php")||(page == "login.php"))
            {
                window.location ="/";
            }
            else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
            else
            {

                window.parent.parent.location="/";
            }
            </script>';
        }
    }

	function objtab_show_localdepartmentlist($node_name,$action)
    {
        global $doc;
        $str_xml = Node_show($node_name,$action);
        if(trim($str_xml) !="")
        {
            $doc->loadXML($str_xml);
            $groups = $doc->getElementsByTagName('group');
            $length = $groups->length;
            for($i=0; $i<$length;$i++)
            {
                $n_list = $groups->item($i)->childNodes;
                $ld_list = $n_list->item(0)->nodeValue;

                echo '<option value="'.$ld_list.'">'.
                $ld_list. '</option>';
                echo "</br>";
            }
        }
        else
        {
            echo '<script language="javascript">
            var path =window.location.hostname;
            var path_arr = path.split("/");
            var page = path_arr[path_arr.length-1];
            if((page == "main.php")||(page == "login.php"))
            {
                window.location ="/";
            }
            else if((page == "list_usermanagement_left.php")||(page == "list_usermanagement_right.php"))
			{

				window.parent.parent.parent.location ="/";

			}
            else
            {

                window.parent.parent.location="/";
            }
            </script>';
        }
    }

		//用户组打印；
		function objtab_show_localdepartment_architecture()
		{
			require '/www/custom/language_resource/basic_um_language.php';
			require('../custom/language_resource/appctrl_language.php');
			global $doc;
			$str_xml = Node_show('treegrp_convert_database','show');
		  	if(trim($str_xml) !="")
			{
				$doc->loadXML($str_xml);
		  		$groups = $doc->getElementsByTagName('group');
		  		$length = $groups->length;
		  		echo '<div class="dtree">
			<p><a href="javascript: dld.openAll();">'.get_string_by_module("appctrl_openall", $appctrl_language_list).'</a> | <a href="javascript: dld.closeAll();">'.get_string_by_module("appctrl_closeall", $appctrl_language_list).'</a></p>
			<script language="javascript">
			dld = new dTree("dld","../js_script","testFormld","testNameld");';

		  	$name0 = "";
		  	$id0 = 0;
		  	$pid0 = -1;
		  	echo 'dld.add("'.$id0.'","'.$pid0.'","'.$name0.'");';

		  	$name1 =get_string_by_module('all_groups',$um_list); //所有组
		  	$id1 = 1;
		  	$pid1 = 0;
		  	echo 'dld.add("'.$id1.'","'.$pid1.'","'.$name1.'");';
		  	for($i=0;$i<$length;$i++)
			{
				$n_list = $groups->item($i)->childNodes;
				$id = $n_list->item(0)->nodeValue +2;
				$pid = $n_list->item(1)->nodeValue +2;
				$name = $n_list->item(2)->nodeValue;
				$parname = $n_list->item(3)->nodeValue;
				echo 'dld.add("'.$id.'","'.$pid.'","'.$name.'");';
			}
			echo 'document.write(dld);
		</script>
		<input type="text" style="display:none" value="" name="submit_tree_ld" id="submit_tree_ld" />
						<!--td align="right">
									<input type="submit" name="submit_Ngrp_name" class="buttonL" value="确定" />
	                        	    <input type="reset" name="reset_Ngrp_name" class="buttonL" value="取消" onClick="javascript:window.history.back();" />
	                    </td-->
		</div>
		';

			}

		}

		//用户组回显；
    	function fill_umld_tree($n_list)
		{
			$nodes_tr = explode(",", $n_list);
			$tr_len = count($nodes_tr);
			//echo'<script language="javascript">';
			echo 'var grouplist = new Array();';
			for($i =0; $i<$tr_len;$i++){
				echo 'grouplist['.($i).'] = "'.$nodes_tr[$i].'";';
			}
			echo '
			var formtree = document.getElementsByName("testNameld");
			for(var j =0; j<grouplist.length; j++)
			{
				if(grouplist[j] == "root")
					grouplist[j] = "Company";
				for (var i=0; i< formtree.length; i++) {
					var ele = formtree.item(i).value;
					if(ele == grouplist[j])
						formtree.item(i).checked = true;
				}
			}
			';
			//echo '</script>';
			/* 保留
			echo "tr_len= $tr_len</br>";
			$idslength = $_REQUEST['Mylength'];
			echo "idslength= $idslength</br>";
			$idslist = $_REQUEST["Mylist"];
			if($idslength)
		 	{	echo "idslist = $idslist</br>";
	      		$idsarray = explode('?',$idslist);
	      	echo '<script language="javascript">';
	      	echo 'document.getElementById("c1-3-").checked = true;';
 			//idslist = c1-|g1?c1-3-|g2?c1-3-4-|g4?c1-3-4-5-|g5?c1-3-7-|g7?c2-|g190?c2-6-|g6?c2-8-|g11?
	      	for($i =0;i<$idslength;$i++)
				{	//echo $idsarray[$i];
					$element = explode('|',$idsarray[$i]);
					for($j =0;j<$tr_len;$j++)
					{
						if($nodes_tr[$j] == $element[1])
						{
							echo 'document.getElementById($element[0]).checked = true;';

						}
					}
				}
		     }
			echo '</script>';
		 	*/
		}


		//用户打印；
		function objtab_show_localuser_architecture()
		{
			require '/www/custom/language_resource/basic_um_language.php';
require('../custom/language_resource/appctrl_language.php');
			global $doc;
			$str_xml = Node_show('treegrp_convert_database','show_i');
		  	if(trim($str_xml) !="")
			{
				$doc->loadXML($str_xml);
		  		$groups = $doc->getElementsByTagName('group');
		  		$length = $groups->length;
		  		echo '<div class="dtree">
			<p><a href="javascript: dlu.openAll();">'.get_string_by_module("appctrl_openall", $appctrl_language_list).'</a> | <a href="javascript: dlu.closeAll();">'.get_string_by_module("appctrl_closeall", $appctrl_language_list).'</a></p>
			<script language="javascript">
			dlu = new dTree("dlu","../js_script","testFormlu","testNamelu");';
		  	$name0 = "";
		  	$id0 = 0;
		  	$pid0 = -1;
		  	echo 'dlu.add("'.$id0.'","'.$pid0.'","'.$name0.'");';
		  	$name1 = get_string_by_module('all_users',$um_list);   //所有用户
		  	$id1 = 1;
		  	$pid1 = 0;
		  	echo 'dlu.add("'.$id1.'","'.$pid1.'","'.$name1.'");';
			for($i=0;$i<$length;$i++)
			{
				$n_list = $groups->item($i)->childNodes;
				$id = $n_list->item(0)->nodeValue +2;
				$pid = $n_list->item(1)->nodeValue +2;
				$name = $n_list->item(2)->nodeValue;
				$parname = $n_list->item(3)->nodeValue;
				if($id < 5000)
					echo 'dlu.add("'.$id.'","'.$pid.'","Grp:'.$name.'");';
				else
					echo 'dlu.add("@'.$id.'","'.$pid.'","'.$name.'");';
			}
			echo 'document.write(dlu);
			</script>
			<input type="text" style="display:none" value="" name="submit_tree_lu" id="submit_tree_lu" />
					  	<!--td align="right">
									<input type="submit" name="submit_Ngrp_name" class="buttonL" value="确定" />
	                        	    <input type="reset" name="reset_Ngrp_name" class="buttonL" value="取消" onClick="javascript:window.history.back();" />
	                    </td-->
			</div>';

			}

		}

		//用户回显；
		function fill_umlu_tree($n_list)
		{

			$nodes_tr = explode(",", $n_list);
			$tr_len = count($nodes_tr);

			//echo'<script language="javascript">';
			echo 'var userlist = new Array();';
			for($i =0; $i<$tr_len;$i++){
				echo 'userlist['.($i).'] = "'.$nodes_tr[$i].'";';
			}

			echo '
			var formtree = document.getElementsByName("testNamelu");
			for(var j =0; j<userlist.length; j++)
			{
				for (var i=0; i< formtree.length; i++) {
					var ele = formtree.item(i).value;
					if(ele == userlist[j])
						formtree.item(i).checked = true;
				}
			}
			';
			//echo '</script>';
		}

}

class app_array_class
{
	var $apps ;
	var $app_groups ;
	var $tmp_p = false;
	var $tmp_len = 0;
	function app_array_class()
	{
		$filename = '/config/security/apps_new.conf';
		
		if (!file_exists($filename))
		{
			$filename = '/config/security/apps.conf';
		}
		
		$handle = fopen($filename, "r");
		if ($handle) {
			$file_head = fread($handle, 3);
			$head_flag_array = str_split($file_head);

			if (sizeof($head_flag_array) < 3) {
				fclose($handle);
				return;
			}

			if (!(ord($head_flag_array[0]) == 0xef && ord($head_flag_array[1]) == 0xbb && ord($head_flag_array[2]) == 0xbf))
				fseek($handle, 0);
				$i=0;
		while (!feof($handle))
		{
			$buffer = fgets($handle, 256);
			$line_array = explode(";", $buffer);
			if($line_array[0] == "" || $line_array[0] == "\n")
			{
				continue;
			}
			$tmp_p = strchr($line_array[3]," ");
			if ($tmp_p)
			{
				$tmp_len = strlen($line_array[3])- strlen($tmp_p);
				$line = substr($line_array[3], 0, $tmp_len);
			}
			else
			{
				$tmp_p = strchr($line_array[3],"\n");
				if($tmp_p)
				{
					$tmp_len = strlen($line_array[3])- strlen($tmp_p);
					$line = substr($line_array[3], 0, $tmp_len);
				}
				else
				{
					$line = $line_array[3];
				}
			}

			if($_SESSION['device_language'] == 'english')
			{
				$this->appname[$i]=$line_array[2];
			}
			else
			{
				$this->appname[$i]=$line_array[1];
			}
			$this->appid[$i]=$line_array[0];
			$this->app_pid[$i]=$line;
			$this->pid_by_id[$line_array[0]]=$line;
			$i++;
			if($line_array[0][0] != 'g')
			{
				if($_SESSION['device_language'] == 'english')
					$this->apps[intval($line_array[0])] = $line_array[2];
				else
					$this->apps[intval($line_array[0])] = $line_array[1];
			}
			else
			{
				if($_SESSION['device_language'] == 'english')
				{
					$this->app_groups[intval($line_array[0])] =$line_array[2];
				}
				else
				{
					$this->app_groups[intval($line_array[0])] = $line_array[1];
				}
			}

		}
     fclose($handle);
	}

	}

	function get_apps()
	{
		return $this->apps;
	}
	function get_app_groups()
	{
		return $this->app_groups;
	}
	function get_appid()
	{
		return $this->appid;
	}
	function get_appname()
	{
		return $this->appname;
	}
	function get_app_pid()
	{
		return $this->app_pid;
	}
	function get_pid_by_id()
	{
		return $this->pid_by_id;
	}

	function print_app_tree()
	{
		require('../custom/language_resource/appctrl_language.php');
	echo '<div class="dtree">
	<p><a href="javascript: d.openAll();">'.get_string_by_module("appctrl_openall", $appctrl_language_list).'</a> | <a href="javascript: d.closeAll();">'.get_string_by_module("appctrl_closeall", $appctrl_language_list).'</a></p>
	<script language="javascript">
	d = new dTree("d","../js_script","testForm","testName");
	d.add("0","-1","");';

	$app_obj = new app_array_class();
	$apps ;
	$apps_parent;
	$appid = $this->appid;
	$appname= $this->appname;
	$app_pid = $this->app_pid;
	$len = count($appid);

 	for($i=0; $i < $len; $i++)
 	{
		echo 'd.add("'.$appid[$i].'","'.$app_pid[$i].'","'.$appname[$i].'");';
	}

	echo 'document.write(d);
	</script>
	<input type="text" style="display:none" value="" name="submit_tree" id="submit_tree" />
	</div>';
	}

	function fill_app_tree($n_app_list)
	{
		$app_obj = new app_array_class();
		$apps = $this->pid_by_id;
		for($i=0; $i < $n_app_list->length ; $i++)
		{
			$items = $n_app_list->item($i)->childNodes;
			$id = $items->item(0)->nodeValue;
			if($items->item(1)->nodeValue == 1)
			{
				$groupid = $apps[$id];
				$str_id = "c".$groupid."-".$nodes_tr[$i]."-";
				$str_pid = "c".$groupid."-";
				echo 'document.getElementById("'.$str_pid.'").checked = true;';
				echo 'document.getElementById("'.$str_id.'").checked = true;';
			}
		}
	}

	function fill_policy_app_tree($n_app_list)
	{
		$app_obj = new app_array_class();
		$apps = $this->pid_by_id;
		$nodes_tr = explode(',', $n_app_list);
		$tr_len = count($nodes_tr) - 1;

		for($i=0; $i < $tr_len ; $i++)
		{
			$groupid = $apps[$nodes_tr[$i]];
			$group_pid = $apps[$groupid];
			$str_id = "c".$group_pid."-".$groupid."-".$nodes_tr[$i]."-";
			$str_gid = "c".$group_pid."-".$groupid."-";
			$str_gpid = "c".$group_pid."-";
			if($group_pid == '0')
			continue;
			echo 'document.getElementById("'.$str_id.'").checked = true;';
			echo 'document.getElementById("'.$str_gid.'").checked = true;';
			echo 'document.getElementById("'.$str_gpid.'").checked = true;';
		}
	}
}

function list_div_title($title)
{
	echo '
	<DIV id=INNER align=left style="margin-top:5pt;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td colspan="2" height="30">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="width:15px; background-color:#333;">&nbsp;</td>
								<td class="title_tbl_content" >'.$title.'</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</DIV>';
}

function list_table_header($content_title,$checkbox=0, $have_oper=0,$nodes="")//$checkbox用来控制是否有复选框
{
	require '/www/custom/language_resource/sys_language.php';
	switch($nodes)
	{
		case 'um_onlineuser':
			echo '
			<DIV id=INNER align=left style="padding-left:15px">
			<DIV id=div_tab >
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist">
			<thead id="mlist_thead">
			';
		break;
		default:
			echo '
			<DIV id=INNER align=left  style="padding-left:15px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist">
			<thead id="mlist_thead">
			';
			break;
	}
	echo '<tr>';
	if ($checkbox == 1)
	{
		echo '<TD width=25><INPUT type=checkbox value="-1" onclick="this.value=check(this.form.list)"></TD>';
	}
	$count = count($content_title);
	$col_width = 100/$count;

	for($i=0;$i<$count;$i++)
	{
		if($i==0)
		{
			echo "<TD width='.$col_width.'>".$content_title[$i]."</TD>\n";
		}

		else
		{
			echo "<TD>".$content_title[$i]."</TD>\n";
		}
	}

  if ($have_oper != 0)
	{
		$opera_title = get_string_by_module('user_operation', $user_language_list);
		echo "<TD>".$opera_title."</TD>\n";
	}
	echo'	</tr>
		<tbody>';
}

function list_table_td($content_title,$checkbox=0, $check_value="", $have_oper=0,
$tagName="",$del_name="",$del_value="",$mod_page="",$mod_value="",$inface_name="",$inface_value="")//$checkbox用来控制是否有复选框
{
	echo '<tr class="row135">';
	if ($checkbox == 1)
	{
		echo "<TD ><INPUT type=checkbox name=list value=".$check_value."></TD>";
	}
	$count = count($content_title);
	for($i=0;$i<$count;$i++)
	{
		//echo "<TD>".$content_title[$i]."</TD>\n";
	if ($content_title[$i] != "")
		{
			echo "<TD>".$content_title[$i]."</TD>\n";
		}
		else
		{
			echo "<TD>&nbsp;</TD>\n";
		}
	}
	if ($have_oper == 1)/*只有修改*/
	{
		_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name,$inface_value,1,0,1);
	}
	if ($have_oper == 2)/*只有删除*/
	{
		_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name,$inface_value,1,1,0);
	}
	if ($have_oper == 3)/*有删除\修改*/
	{
		_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name,$inface_value,1,1,1);
	}
	echo'</tr>';
}
function list_table_td_ext($content_title,$checkbox=0, $check_value="", $have_oper=0,
$tagName="",$del_name="",$del_value="",$mod_page="",$mod_value="",$inface_name="",$inface_value="",$has_pwd_td="")//$checkbox用来控制是否有复选框
{
	echo '<tr class="row135">';
	if ($checkbox == 1)
	{
		echo "<TD ><INPUT type=checkbox name=list value=".$check_value."></TD>";
	}
	$count = count($content_title);
	for($i=0;$i<$count;$i++)
	{
		//echo "<TD>".$content_title[$i]."</TD>\n";
		if ($content_title[$i] != "")
		{
			if ($i == $has_pwd_td)
			{
				echo "<TD ><INPUT type=password style='width:130px; border:#FFF 1PX solid;' value=".$content_title[$i]."></TD>";
			}
			else
			{
				echo "<TD>".$content_title[$i]."</TD>\n";
			}
		}
		else
		{
			echo "<TD>&nbsp;</TD>\n";
		}
	}
	if ($have_oper == 1)/*只有修改*/
	{
		_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name,$inface_value,1,0,1);
	}
	if ($have_oper == 2)/*只有删除*/
	{
		_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name,$inface_value,1,1,0);
	}
	if ($have_oper == 3)/*有删除\修改*/
	{
		_operation($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name,$inface_value,1,1,1);
	}
	echo'</tr>';
}
function list_table_bottom($checkbox=1,$length="",$page="",$page_count="",$col_span="",$add_page="")
{
	echo'</tbody>';
	echo '</table>';
	if ($checkbox != 1)
	{
		echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mlist" >';
		switch($length)
		{
			default:
					  echo'<tr>
					 <td colspan="'.$col_span.'" style="width:100%;">
					<div style="float:left; height:20px; padding-top:10px;">
							'.get_string("ch_page1").'
							<span id="list_count">'.$length.'</span>'.get_string("ch_page2").'
							<span id="page_index">'.$page.'</span>'.get_string("ch_page3").'
							<span id="page_count">'.$page_count.'</span>'.get_string("ch_page4").'
					</div>
					<div style="float:right; height:10px;  padding-top:10px;">
						<span id="page_one">
								<INPUT class="button"  onclick="javascript:show_page(1)" type=button value="'.get_string("ch_page6").'">
						</span>
						<span id="page_prev">
								<INPUT class="button"   onclick="javascript:show_page(2)" type=button value="'.get_string("ch_page7").'">
						</span>
						<span id="page_next">
								<INPUT class="button"   onclick="javascript:show_page(3)" type=button value='.get_string("ch_page8").'>
						</span>
						<span id="page_last">
								<INPUT class="button"   onclick="javascript:show_page(4)" type=button value="'.get_string("ch_page9").'">
						</span>
						<span style="margin-bottom:10px; font-size:12px; vertical-align:middle ;">'.get_string("ch_page10").'<input type="text"  name="textfield" id="page_jump" style="width:30px; height:17px; font-size:12px; border:solid 1px #7aaebd; margin-bottom:10px;" />'.get_string("ch_page5").'
						</span>
						<span>
								<INPUT class="button" style="width:45px;"   onclick="javascript:show_page(5)" type=button value="'.get_string("ch_page11").' ">
						</span>
					</div>
				</td>
			  </tr></table><div style="text-align: right; margin-top: 5px;">';
			break;
		}

		if(($_SESSION['qx'] =='useradmin') || ($_SESSION['qx'] == 'telecomadmin'))
		{
			if(!empty($add_page) && $add_page == 'l2tp_grp')
			{
				echo'<tr><td style="width:100%; text-align:right"><span>
				<input type="button" class="buttonL" onclick="javascript:new_dir(\''.$add_page.'\');" value="'.get_string('edit').'">
			</span></td ></tr>';//编辑
			}
			else if(!empty($add_page)&&$add_page !='syslog_filter')
			{
				echo'<tr><td style="width:100%; text-align:right"><span class="tab_head_opration" id="op_add">
				<input type="button" class="buttonL" onclick="javascript:index_add(\''.$add_page.'\');" value="'.get_string('add').'">
				</span></td></tr>';//添加
			}
			else if($add_page == 'syslog_filter')
			{
				echo'<tr><td style="width:100%; text-align:right"><span>
				<input type="button" class="buttonL" onclick="javascript:index_mod(\''.$add_page.'\');" value="'.get_string('edit').'">
				</span></td ></tr>';//编辑
			}
		}
		echo '</div>' ;
	}
}
/*提示信息*/
function alert_info($title)
{
	echo '<div style="margin-top:10px;">
			<span class="red">'.$title.'</span>
		</div>';
}
/*文本后面提示信息--统一格式：文本、单位、*、提示信息*/
function text_alert_info($type,$flag,$title,$unit)
{
	//flag=1表示带"*",unit表示单位，type表示类型（1：ip，2：ipv4_mac,3:ipv6_mac,4:取值范围类，5：输入长度类，6：其它），title：信息
	require '/www/custom/language_resource/language.php';
	if ($unit != "")
	{
		echo '<span>&nbsp;'.$unit.'<span>';
	}
	if ($flag == 1)
	{
		echo '<span class="red">&nbsp;*</span>';
	}
	if ($type == 1)
	{
		echo '<span>&nbsp;(xxx.xxx.xxx.xxx)</span>';
	}
	else if ($type == 2)
	{
		echo '<span>&nbsp;(xx:xx:xx:xx:xx:xx)</span>';
	}
	else if ($type == 3)
	{
		echo '<span>&nbsp;(xx:xx::xx:xx)</span>';
	}
	else if ($type == 4)
	{
		echo '<span>&nbsp;(</span>';
		echo '<span>'.get_string_by_module('range', $language_list).'</span>';
		echo '<span>'.$title.'</span>';
		echo '<span>)</span>';
	}
	else if ($type == 5)
	{
		echo '<span>&nbsp;(</span>';
		echo '<span>'.get_string_by_module('length', $language_list).'</span>';
		echo '<span>'.$title.'</span>';
		echo '<span>'.get_string_by_module('value_unit', $language_list).'</span>';
		echo '<span>)</span>';
	}
	else
	{
		echo '<span>&nbsp;</span>';
		echo '<span>'.$title.'</span>';
	}

}

/*新增函数：生成标量表行*/
function  table_content_table($tr_flag,$content_tr_title,$content_tr_type,$content_tr_type_id,$value_type,$value,$value_min,$value_max,
			$flag,$flag_code,$error_code_id,$default_value,$radio_value1,$radio_value2,$error_masage1,$error_masage2,$error_masage3,
			$cbx_num,&$cbx_id_arr,&$cbx_value_arr,&$cbx_ck_flag,$select_elem)
{
	if ($tr_flag == 1)
	{
        echo '<table id="guish_set_table" width="100%" class="mlist" border="0" cellpadding="0" cellspacing="0">
         	<tbody>
         	<tr>
        	<td width="150">'.$content_tr_title.'</td>';
	}
	else
	{
		echo ' <tr>
			   <td>'.$content_tr_title.'</td>';
	}
	echo ' <td colspan="2">';
	if ($content_tr_type == 0)/*text*/
	{
      	echo '<input type="text" value="'.$value.'" id="'.$content_tr_type_id.'" valid="'.$value_type.'" min="'.$value_min.'" max="'.$value_max.'" errmsg="'.$error_masage1.'|'.$error_masage2.'|'.$error_masage3.'" />';
      	if ($flag != 0)
		{
			echo '<span class="red">*</span>';
		}
		if ($flag_code != 0)
		{
			echo '<span>'.$default_value.'</span>';
		}
		if (!empty($error_code_id))
		{
			echo'<span id="'.$error_code_id.'" class="red"></span>';
		}
    }

    else if ($content_tr_type == 1)/*radio*/
    {
    	echo '<input type="radio" name="'.$content_tr_type_id.'" value="1" id="'.$content_tr_type_id.'1" checked="checked"  />
                 <span class="radio_span">'.$radio_value1.'</span>
                 <input type="radio" name="'.$content_tr_type_id.'" value="0" id="'.$content_tr_type_id.'0" />
                 <span class="radio_span">'.$radio_value2.'</span>';
    }
    else if ($content_tr_type == 2)/*select:需要修改*/
    {
    	echo '<select name="'.$content_tr_type_id.'" id="'.$content_tr_type_id.'" onchange="display_onevlan(this.value)">';
		$select_elem;
		echo '</select>';
    }
    else if ($content_tr_type == 3)/*checkbox*/
    {
    	for ($i=0;$i<$cbx_num;$i++)
    	{
    		echo '<input type="checkbox" name="'.$cbx_id_arr[$i].'" id="'.$cbx_id_arr[$i].'" value="0" checked />
				 <span class="radio_span">'.$cbx_value_arr[$i].'</span>';
    	}

    }
    echo '</td></tr>';
	if ($tr_flag == 2)
	{
		echo '</tbody></table>';
    }
}
function phyface_type_show1()
	{
		
		$first_if = "";
		global $doc;
		$start = 0;
		$str_xml = Node_show ( 'debug_interface_sub', 'show' );
		/***Modified by wangzhibin*****
		$first_if = "";
		global $doc;
		$start = 0;
		$serch_xml = '<debug_interface_sub action="show"></debug_interface_sub>';
		$session_id = uniqid();
		if($_SESSION['session_id'] == NULL)
		{
			$_SESSION['session_id'] = $session_id;
		}
		$obj_guish = new guish();  
		$obj_guish->set_fd();            
		$obj_guish->set_rsgui($session_id,NULL,$serch_xml,UCT_CMD);
		$str_xml = $obj_guish->get_xml();
		/*****************************/
		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$name_list = $doc->getElementsByTagName('name');
			$elm_len = $name_list->length;
			if($start<$elm_len)
			{
				for($i=$start; $i<$elm_len;$i++)
				{

					if( $i == $start ){
						$first_if = $name_list->item($i)->nodeValue;
					}
					echo '<option value="'.$name_list->item($i)->nodeValue.'">'.$name_list->item($i)->nodeValue.'</option>';
				}
			}
		}
		else
		{
			echo '<script language="javascript">
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];
			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
		}
		/*return the first interface*/
		return $first_if;
	}
	/*according to user authority,get exclude_func*/
	function load_exclude_func()
	{
		require ('./tmp/exclude_func.php');
		
		if ($_SESSION['qx'] == 'telecomadmin')
		{			
			$exclude_func = $exclude_func1;
		}
		else if ($_SESSION['qx'] == 'useradmin')
		{
			$exclude_func = $exclude_func2;
		}
		else if ($_SESSION['qx'] == 'user')
		{
			$exclude_func = $exclude_func3;
		}
		else 
		{
			$exclude_func = $exclude_func1;
		}
		 
		return $exclude_func;
	}

function port_all_show()
{
		$first_if = "";
		global $doc;
		$start = 0;
		$str_xml = Node_show ( 'test_port_sub', 'show' );
		if(trim($str_xml) !="")
		{
			$doc->loadXML($str_xml);
			$name_list = $doc->getElementsByTagName('voip_port');
			$elm_len = $name_list->length;
			if($start<$elm_len)
			{
				for($i=$start; $i<$elm_len;$i++)
				{
					if( $i == $start ){
						$first_if = $name_list->item($i)->nodeValue;
					}
					if ($name_list->item($i)->nodeValue)
					{
						$str_name = explode(" ",$name_list->item($i)->nodeValue);
						echo '<option value="'.$str_name[1].'">'.$name_list->item($i)->nodeValue.'</option>';
					}
				}
			}
		}
		else
		{
			echo '<script language="javascript">
			var path =window.location.hostname;
			var path_arr = path.split("/");
			var page = path_arr[path_arr.length-1];
			if((page == "main.php")||(page == "login.php"))
			{
				window.location ="/";
			}
			else
			{
				window.parent.parent.location="/";
			}
			</script>';
		}
		return $first_if;
}

function unescape($str){ 
		$ret = ''; 
		$len = strlen($str); 
		for ($i = 0; $i < $len; $i++){ 
		if ($str[$i] == '%' && $str[$i+1] == 'u'){ 
		$val = hexdec(substr($str, $i+2, 4)); 
		if ($val < 0x7f) $ret .= chr($val); 
		else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f)); 
		else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f)); 
		$i += 5; 
		} 
		else if ($str[$i] == '%'){ 
		$ret .= urldecode(substr($str, $i, 3)); 
		$i += 2; 
		} 
		else $ret .= $str[$i]; 
		} 
		return $ret; 
} 

function detailed_statistic_info($tagName,$del_name,$del_value,$mod_page,$mod_value,$inface_name="",$inface_value="")
{
	require('../custom/language_resource/wlan_language.php');
	echo'<td class="content_item_center"><nobr>';

	if(($_SESSION['qx'] == 'useradmin') || ($_SESSION['qx'] == 'telecomadmin'))
	{
	 	echo'<a href="javascript:detailed_info(\''.$mod_page.'\',\''.$mod_value.'\',\''.$del_value.'\');"target="">
			'.get_string_by_module("detailed_info", $wlan_language_list).'
						 </a>';
	}
	echo'</span></nobr></td></tr>';

}

/*BEGIN:	Added by wangzhibin for MSG00025641 2014-6-25*/
function convertbackslash($str)
{
	$ret = '';
	$ret = htmlspecialchars(addslashes($str));

	return $ret;
}
/*END:	Added by wangzhibin for MSG00025641 2014-6-25*/
?>