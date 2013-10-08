<?php
/*******************************************************************
* $Id: view.php,v 1.0.4 24/05/2006 00:52 BitC3R0 Exp $             *
* ----------------------------------------------------             *
* RMSOFT MyFolder 1.0                                              *
* M�dulo para el manejo de un portafolio profesional               *
* CopyRight � 2006. Red M�xico Soft                                *
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
* Informaci�n completa de un trabajo                               *
* ----------------------------------------------------             *
* @copyright: � 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT GS 2.0                                          *
* @version: 1.0.4                                                  *
* @modificado: 24/05/2006 12:52:24 a.m.                            *
*******************************************************************/

$xoopsOption['template_main'] = 'portfolio_view.html';
$xoops_module_header='';
$xoops_module_header .= '<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />';
include 'header.php';


	function clear_unicodeslashes($text) {
		$text = str_replace(array("\\'"), "'", $text);
		$text = str_replace(array("\\\\\\'"), "'", $text);
		$text = str_replace(array('\\"'), '"', $text);
		return $text;
	}


$id = isset($_GET['id']) ? $_GET['id'] : 0;
if ($id<=0){ header('location: index.php'); die(); }

$tpl->assign('localize_bar', ":: <a href='index.php'>$mc[title]</a>" . portfolio_localize($id, 1));

include_once('class/work.class.php');
$work = new MFWork($id);

$tpl->assign('work', array('id'=>$work->getVar('id_w'),'titulo'=>$work->getVar('titulo'),
		'desc'=>$myts->displayTarea(clear_unicodeslashes($work->getVar('desc')), 1, 1, 1, 1, 1),
		'cliente'=>$work->getVar('cliente'),
		'comentario'=>$myts->displayTarea($work->getVar('comentario')),
		'url'=>$work->getVar('url'),'imagen'=>$work->getVar('imagen')));

$tpl->assign('lang_for', _PORTFOLIO_FOR);
$tpl->assign('lang_desc', _PORTFOLIO_DESC);
$tpl->assign('lang_url', _PORTFOLIO_URL);
$tpl->assign('lang_comment', sprintf(_PORTFOLIO_COMMENT, $work->getVar('cliente')));
$tpl->assign('lang_moreimgs', _PORTFOLIO_MOREIMAGES);

foreach ($work->getVar('images') as $k => $v){
	$tpl->append('images', $v['archivo']);
}

include 'footer.php';
?>