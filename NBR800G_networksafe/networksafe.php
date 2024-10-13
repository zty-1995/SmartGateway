<?php

/**
 * 入口处，主要实现地址分发
 */
define('IN', true);     //定位该文件是入口文件
define('DS', DIRECTORY_SEPARATOR);  //定义系统目录分隔符
define('AROOT', dirname(__FILE__) . DS);    //定义入口所在的目录
include_once(dirname(dirname(__FILE__)) . DS . 'mvc' . DS . 'controller' . DS . 'core.controller.php');

class defaultController extends coreController {

    private $filename;
    private $setshell;
    private $cli = "evpn-server config all-clients file \"/data/evpn/cfg_wifi.text\"";

    function __construct() {
        parent::__construct();
        $this->filename = DS . "data" . DS . "evpn" . DS . "cfg_wifi.text";
        $this->setshell = "/usr/local/evpn/server/cfg_wifi.sh";
    }
    /**
         * 获取分支带宽设置列表
         */
    public function listAction(){
        $province = p("province");
        $city = p("city");
        $district = p("district");
        $shell = "/usr/local/evpn/server/sh_clients_bandwidth.sh";
        if($province !== FALSE && $province != ""){
            $shell .= " province ".iconv("UTF-8", "GB2312//IGNORE", $province);
        }
        if($city !== FALSE && $city != ""){
            $shell .= " city ".iconv("UTF-8", "GB2312//IGNORE", $city);
        }
        if($district !== FALSE && $district != ""){
            $shell .= " district ".iconv("UTF-8", "GB2312//IGNORE", $district);
        }
        header("Content-type: text/html;charset=gbk");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        echo `$shell`;
    }
    /**
     * 获取分支带宽
     */
    function getAction() {
        $command = "/usr/local/evpn/server/echo_bandwidth.sh";
        $content = [];
        exec(EscapeShellCmd($command), $content);
        $data = array("status" => true,
            "data" => isset($content[0]) && $content[0] === "" ? "" : $content);
        json_echo($data);
    }
    /**
     * 不限速
     */
    function closeAction(){
        evpnShell("/usr/local/evpn/server/cfg_bandwidth.sh disable");
    }

    public function setAction() {
            $bandwidth = p("bandwidth");
            if ($bandwidth == FALSE) {
                json_echo(false);
                return;
            }
            $command = "/usr/local/evpn/server/cfg_bandwidth.sh config " . $bandwidth;
            evpnShell($command);
        }

}

include_once dirname(dirname(__FILE__)) . '/init.php';     //mvc架构初始化