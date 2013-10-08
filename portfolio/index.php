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

$xoopsOption['template_main'] = "portfolio_index.html";
include 'header.php';

// Cargo las categoras
$result = $db->query("SELECT * FROM ".$db->prefix("portfolio_categos")." WHERE parent='0' ORDER BY orden");
while ($row=$db->fetchArray($result)){
	$tpl->append('categos', array('id'=>$row['id_cat'],'nombre'=>$row['nombre'],'desc'=>$myts->displayTarea($row['desc'],1,1,1,1,1)));
}

$tpl->assign('lang_categos', _PORTFOLIO_CATEGOS);
$tpl->assign('lang_featured', _PORTFOLIO_FEATURED);
$tpl->assign('lang_recent', _PORTFOLIO_RECENTS);

// Cargo los trabajos destacados
$result = $db->query("SELECT * FROM ".$db->prefix("portfolio_works")." WHERE resaltado='1' ORDER BY id_w DESC LIMIT 0,$mc[featured]");
while ($row=$db->fetchArray($result)){
	$tpl->append('destacados', array('id'=>$row['id_w'],'titulo'=>$row['titulo'],'desc'=>$myts->displayTarea($row['short'],1,1,1,1,1),'img'=>$row['imagen']));
}

$result = $db->query("SELECT * FROM ".$db->prefix("portfolio_works")." ORDER BY id_w DESC LIMIT 0,$mc[recents]");
while ($row=$db->fetchArray($result)){
	$tpl->append('recientes', array('id'=>$row['id_w'],'titulo'=>$row['titulo'],'desc'=>$myts->displayTarea($row['short'],1,1,1,1),'img'=>$row['imagen']));
}

include 'footer.php';
?>