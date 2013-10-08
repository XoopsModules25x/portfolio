<?php
/*******************************************************************
* $Id: functions.php,v 1.0.1 24/05/2006 00:38 BitC3R0 Exp $        *
* ---------------------------------------------------------        *
* RMSOFT MyFolder 1.0                                              *
* Módulo para el manejo de un portafolio profesional               *
* CopyRight © 2006. RMSOFT                                *
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
* ---------------------------------------------------------        *
* functions.php:                                                   *
* Funciones utilizadas por los bloques                             *
* ---------------------------------------------------------        *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.1                                                  *
* @modificado: 24/05/2006 12:38:59 a.m.                            *
*******************************************************************/

function portfolio_get_config($option, $modname='portfolio'){
	global $xoopsModuleConfig, $xoopsModule;

	if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $modname && $xoopsModule->getVar('isactive'))) {
		if(isset($xoopsModuleConfig[$option])) {
			$retval= $xoopsModuleConfig[$option];
		}
	} else {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname($modname);
		$config_handler =& xoops_gethandler('config');
		if ($module) {
		    $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
	    	if(isset($moduleConfig[$option])) {
	    		$retval= $moduleConfig[$option];
	    	}
		}
	}
	
	return $retval;
}
?>