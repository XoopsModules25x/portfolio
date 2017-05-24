<?php
/*******************************************************************
* $Id: header.php,v 1.0.0 24/05/2006 00:36 BitC3R0 Exp $           *
* ------------------------------------------------------           *
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
* ------------------------------------------------------           *
* header.php:                                                      *
* Archivo para cargar funciones                                    *
* ------------------------------------------------------           *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.0                                                  *
* @modificado: 24/05/2006 12:36:20 a.m.                            *
*******************************************************************/

include '../../../include/cp_header.php';

/**
 * Nos aseguramos que exista el lenguage buscaado
 */
if (file_exists(XOOPS_ROOT_PATH . '/modules/portfolio/language/' . $xoopsConfig['language'] . '/admin.php')) {
    include_once XOOPS_ROOT_PATH. '/modules/portfolio/language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/portfolio/language/spanish/admin.php';
}

$mc =& $xoopsModuleConfig;
$db =& $xoopsDB;
$myts = MyTextSanitizer::getInstance();

include 'admin.func.php';
