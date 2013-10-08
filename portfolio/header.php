<?php
/*******************************************************************
* $Id: view.php,v 1.0.4 24/05/2006 00:52 BitC3R0 Exp $             *
* ----------------------------------------------------             *
* RMSOFT MyFolder 1.0                                              *
* Módulo para el manejo de un portafolio profesional               *
* CopyRight © 2006. Red México Soft                                *
* Autor: BitC3R0                                                   *
* http://www.redmexico.com.mx                                      *
* http://www.xoops-mexico.net                                      *
* --------------------------------------------                     *
* This program is free software; you can redistribute it and/or    *
* modify it under the terms of the GNU General Public License as   *
* published by the Free Software Foundation; either version 2 of   *
* the License, or (at your option) any later version.              *
*                                                                  *
* This program is distributed in the hope that it will be useful,  *
* but WITHOUT ANY WARRANTY; without even the implied warranty of   *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the     *
* GNU General Public License for more details.                     *
*                                                                  *
* You should have received a copy of the GNU General Public        *
* License along with this program; if not, write to the Free       *
* Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,   *
* MA 02111-1307 USA                                                *
*                                                                  *
* ----------------------------------------------------             *
* view.php:                                                        *
* Información completa de un trabajo                               *
* ----------------------------------------------------             *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT GS 2.0                                          *
* @version: 1.0.4                                                  *
* @modificado: 24/05/2006 12:52:24 a.m.                            *
*******************************************************************/

include("../../mainfile.php");
include XOOPS_ROOT_PATH."/header.php";
$myts =& MyTextSanitizer::getInstance();

if (!file_exists(XOOPS_ROOT_PATH."/modules/portfolio/language/".$xoopsConfig['language']."/main.php") ) {
	include "language/spanish/main.php";
	$xoopsTpl->assign('mod_language', 'spanish');
}

include 'admin/admin.func.php';
$mc =& $xoopsModuleConfig;
$myts = MyTextSanitizer::getInstance();
$db =& $xoopsDB;
$tpl =& $xoopsTpl;

$tpl->assign('storedir', portfolio_add_slash(portfolio_web_dir($mc['storedir'])));
$tpl->assign('module_path', XOOPS_ROOT_PATH . '/modules/rmshop');
$tpl->assign('module_title', $mc['title']);
?>