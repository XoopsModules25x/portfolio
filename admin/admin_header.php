<?php

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';
require_once $path . '/class/xoopsformloader.php';

//include_once dirname(dirname(__FILE__)) . '/include/common.php';
//include_once dirname(__FILE__) . '/admin_functions.php';

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new XoopsTpl();
}

global $xoopsModule;

$thisModuleDir = $GLOBALS['xoopsModule']->getVar('dirname');

$mc =& $xoopsModuleConfig;
$db =& $xoopsDB;
$myts =& MyTextSanitizer::getInstance();

include 'admin.func.php';

// Load language files
xoops_loadLanguage('admin', $thisModuleDir);
xoops_loadLanguage('modinfo', $thisModuleDir);
xoops_loadLanguage('main', $thisModuleDir);

$pathIcon16 = '../'.$xoopsModule->getInfo('icons16');
$pathIcon32 = '../'.$xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

if ( file_exists($GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php'))){
        include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');
    }else{
        redirect_header("../../../admin.php", 5, _AM_PORTFOLIO_MODULEADMIN_MISSING, false);
    }
