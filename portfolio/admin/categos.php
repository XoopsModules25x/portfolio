<?php
/*******************************************************************
* $Id: categos.php,v 1.0.1 24/05/2006 00:35 BitC3R0 Exp $          *
* -------------------------------------------------------          *
* RMSOFT MyFolder 1.0                                              *
* M�dulo para el manejo de un portafolio profesional               *
* CopyRight � 2006. RMSOFT                                *
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
* -------------------------------------------------------          *
* categos.php:                                                     *
* Manejo de Categor�as                                             *
* -------------------------------------------------------          *
* @copyright: � 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.1                                                  *
* @modificado: 24/05/2006 12:35:09 a.m.                            *
*******************************************************************/

include_once 'admin_header.php';
/**
 * Mostramos las categor�as
 */

$indexAdmin = new ModuleAdmin();
$indexAdmin->addItemButton( _MI_PORTFOLIO_AM4, 'categos.php?op=new', 'add' , '');


function portfolioShow(){
	global $db, $indexAdmin, $pathIcon16;
	define('_PORTFOLIO_LOCATION','CATEGOS');
	xoops_cp_header();
    echo $indexAdmin->addNavigation("categos.php");
    echo $indexAdmin->renderButton('right', '');


	echo "<script type='text/javascript'>
			<!--
				function decision(message, url){
					if(confirm(message)) location.href = url;
				}
			-->
		   </script>";
	//portfolio_make_adminnav();
	
	$result = array();
	portfolio_get_categos($result);
	
	include_once '../class/table.class.php';
	$table = new MFTable(true);
	$table->setCellStyle("padding: 0px; padding-left: 10px; border-bottom: 1px solid #0066CC; border-right: 1px solid #0066CC; background: url(../images/bgth.jpg) repeat-x; height: 20px; color: #FFFFFF;");
	$table->openTbl('100%','',1);
	$table->openRow('left');
	$table->addCell(_MA_PORTFOLIO_CATEGOS, 1,3);
	$table->closeRow();
	$table->setRowClass('head');
	$table->setCellStyle("padding: 0px; padding-left: 3px; padding-right: 3px; border-bottom: 1px solid #DBE691; border-right: 1px solid #DBE691; background: url(../images/bghead.jpg) repeat-x; height: 20px; color: #000000;");
	$table->openRow('center');
	$table->addCell(_MA_PORTFOLIO_NAME, 0, '','center');
	$table->addCell(_MA_PORTFOLIO_ORDER, 0, '','center');
	$table->addCell(_MA_PORTFOLIO_OPTIONS,0,'','center');
	$table->closeRow();
	
	$table->setRowClass('odd,even', true);
	$table->setCellStyle('');
	foreach ($result as $k=>$v){
		$table->openRow();
		$table->addCell((($v['saltos']<=0) ? "<img src='../images/plus.gif' border='0' align='absmiddle' />" : str_repeat("&nbsp;", $v['saltos']) .  "<img src='../images/root.gif' border='0' align='absmiddle' />")
						." <strong>$v[nombre]</strong>", 0, '', 'left');
		$table->addCell($v['orden'], 0, '', 'center');
		$table->addCell("<a href='?op=edit&amp;id=$v[id_cat]'><img src=".$pathIcon16.'/edit.png'." title='"._MA_PORTFOLIO_EDIT."'></a> &nbsp;| &nbsp;
					<a href=\"javascript:decision('".sprintf(_MA_PORTFOLIO_CONFIRM, $v['nombre'])."','?op=del&id=$v[id_cat]');\"><img src=".$pathIcon16.'/delete.png'." title='"._MA_PORTFOLIO_DELETE."'></a>", 0, '', 'center');
		$table->closeRow();
	}
	
	$table->closeTbl();
	//portfolio_make_footer();
	include_once 'admin_footer.php';
}

/**
 * Creamos una nueva categor�a
 */
function portfolioNew(){
	global $db, $mc,$xoopsModuleConfig, $indexAdmin;
	define('_PORTFOLIO_LOCATION','NEWCATEGO');
	xoops_cp_header();
	//portfolio_make_adminnav();
    echo $indexAdmin->addNavigation("categos.php?op=new");
	
	include_once '../common/form.class.php';
	$form = new RMForm(_MA_PORTFOLIO_NEWCATEGO, 'frmNew', 'categos.php?op=save');
	$form->addElement(new RMText(_MA_PORTFOLIO_NAME, 'nombre', 50, 150));
	$result = array();
	$select = "<select name='parent'>
				<option value='0'>"._MA_PORTFOLIO_SELECT."</option>";
	portfolio_get_categos($result);
	foreach ($result as $k => $v){
		$select .= "<option value='$v[id_cat]'>$v[nombre]</option>";
	}
	$select .= "</select>";
	$form->addElement(new RMLabel(_MA_PORTFOLIO_PARENT, $select));
	$form->addElement(new RMText(_MA_PORTFOLIO_ORDER, 'orden', 5, 5, 0));

    if (class_exists('XoopsFormEditor')) {
            $options['name'] = 'desc';
            $options['value'] = ((isset($desc)) ? $desc : '');
            $options['rows'] = 5;
            $options['cols'] = '100%';
            $options['width'] = '100%';
            $options['height'] = '200px';
        $formmnote  = new XoopsFormEditor('', $xoopsModuleConfig['editor'], $options, $nohtml = false, $onfailure = 'textarea');
        } else {
        $formmnote  = new XoopsFormDhtmlTextArea('', 'formmnote', $item->getVar('formmnote', 'e'), '100%', '100%');
    }

    //$form->addElement(new RMLabel(_MA_PORTFOLIO_DESC, portfolio_select_editor('desc',$mc['editor'],'','100%','250px')));
    $form->addElement(new RMLabel(_MA_PORTFOLIO_DESC,$formmnote->render() ));

	$form->addElement(new RMButton('sbt',_MA_PORTFOLIO_SEND));
	$form->display();
	portfolio_make_footer();
    include_once 'admin_footer.php';
}

function portfolioSave(){
	global $db, $myts;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($nombre==''){
		redirect_header('?op=new', 1, _MA_PORTFOLIO_ERRNAME);
		die();
	}
	
	$tbl = $db->prefix("portfolio_categos");
	list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM $tbl WHERE nombre='$nombre' AND parent='$parent'"));
	if ($num>0){
		redirect_header('?op=new', 1, _MA_PORTFOLIO_ERREXISTS);
		die();
	}
	
	$desc = $myts->makeTareaData4Save($desc);
	$sql = "INSERT INTO $tbl (`nombre`,`orden`,`desc`,`parent`) VALUES
			('$nombre','$orden','$desc','$parent')";
	$db->query($sql);
	if ($db->error()!=''){
		redirect_header('?op=new', 2, sprintf(_MA_PORTFOLIO_ERRDB, $db->error()));
		die();
	} else {
		header('location: categos.php'); die();
	}
	
}

/**
 * Editamos una categor�a
 */
function portfolioEdit(){
	global $db, $mc, $myts, $indexAdmin, $xoopsModuleConfig;

	$id = isset($_GET['id']) ? $_GET['id'] : 0;
	
	if ($id<=0){ header('location: categos.php'); die(); }
	
	define('_PORTFOLIO_LOCATION','NEWCATEGO');
	xoops_cp_header();
    echo $indexAdmin->addNavigation("categos.php");
	//portfolio_make_adminnav();
	
	include_once '../class/catego.class.php';
	include_once '../common/form.class.php';
	
	$catego = new MFCategory($id);
	
	$form = new RMForm(_MA_PORTFOLIO_MODCATEGO, 'frmmod', 'categos.php?op=saveedit');
	$form->addElement(new RMText(_MA_PORTFOLIO_NAME, 'nombre', 50, 150, $catego->getVar('nombre')));
	$result = array();
	$select = "<select name='parent'>
				<option value='0'>"._MA_PORTFOLIO_SELECT."</option>";
	portfolio_get_categos($result);
	foreach ($result as $k => $v){
		$select .= "<option value='$v[id_cat]'".(($v['id_cat']==$catego->getVar('parent')) ? " selected='selected'" : '').">$v[nombre]</option>";
	}
	$select .= "</select>";
	$form->addElement(new RMLabel(_MA_PORTFOLIO_PARENT, $select));
	$form->addElement(new RMText(_MA_PORTFOLIO_ORDER, 'orden', 5, 5, $catego->getVar('orden')));

        if (class_exists('XoopsFormEditor')) {
                $options['name'] = 'desc';
                $options['value'] = $catego->getVar('desc');
                $options['rows'] = 5;
                $options['cols'] = '100%';
                $options['width'] = '100%';
                $options['height'] = '200px';
            $formmnote  = new XoopsFormEditor('', $xoopsModuleConfig['editor'], $options, $nohtml = false, $onfailure = 'textarea');
            } else {
            $formmnote  = new XoopsFormDhtmlTextArea('', 'formmnote', $item->getVar('formmnote', 'e'), '100%', '100%');
        }
        //$form->addElement(new RMLabel(_MA_PORTFOLIO_DESC, portfolio_select_editor('desc',$mc['editor'],$myts->makeTareaData4Edit($catego->getVar('desc')),'100%','250px')));
        $form->addElement(new RMLabel(_MA_PORTFOLIO_DESC,$formmnote->render() ));



	$form->addElement(new RMButton('sbt',_MA_PORTFOLIO_SEND));
	$form->addElement(new RMHidden('id',$id));
	$form->display();
	//portfolio_make_footer();
    include_once 'admin_footer.php';
	
	
}

/**
 * Guardamos los valores editados
 */
function portfolioSaveEdit(){
	global $db, $myts;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($id<=0){ header('location: categos.php'); die(); }
	
	if ($nombre==''){
		redirect_header('?op=edit&amp;id='.$id, 1, _MA_PORTFOLIO_ERRNAME);
		die();
	}
	
	$tbl = $db->prefix("portfolio_categos");
	list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM $tbl WHERE id_cat<>'$id' AND nombre='$nombre' AND parent='$parent'"));
	if ($num>0){
		redirect_header('?op=edit&amp;id='.$id, 1, _MA_PORTFOLIO_ERREXISTS);
		die();
	}
	
	$desc = $myts->makeTareaData4Save($desc);
	$sql = "UPDATE $tbl SET `nombre`='$nombre',`orden`='$orden',`desc`='$desc',
			`parent`='$parent' WHERE id_cat='$id'";
	$db->query($sql);
	if ($db->error()!=''){
		redirect_header('?op=edit&amp;id='.$id, 2, sprintf(_MA_PORTFOLIO_ERRDB, $db->error()));
		die();
	} else {
		header('location: categos.php'); die();
	}
}

/** 
 * Eliminamos una categoria
 */
function portfolioDelete(){
	global $db;
	$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0);
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	$catego = isset($_POST['catego']) ? $_POST['catego'] : 0;
	
	if ($id<=0){ header('location: categos.php'); die(); }
	
	include_once '../class/catego.class.php';
	$catego = new MFCategory($id);
	$pass = false;
	if ($catego->getWorksNumber()<=0){ $ok = 1; $pass = true;}
	
	if ($ok){
		if ($catego<=0 && !$pass){
			redirect_header('?op=del&amp;id='.$id, 2, _MA_PORTFOLIO_SELECTCAT);
			die();
		}
		
		if (!$pass){
			$db->queryF("UPDATE ".$db->prefix("portfolio_works")." SET catego='$catego' WHERE catego='$id'");
		}
		
		$db->queryF("UPDATE ".$db->prefix("portfolio_categos")." SET parent='0' WHERE parent='$id'");
		
		$db->queryF("DELETE FROM ".$db->prefix("portfolio_categos")." WHERE id_cat='$id'");
		if ($db->error()!=''){
			redirect_header('categos.php', 2, sprintf(_MA_PORTFOLIO_ERRDB, $db->error()));
			die();
		} else {
			header('location: categos.php'); die();
		}
	} else {
		xoops_cp_header();
		//portfolio_make_adminnav();
		$result = array();
		$select = "<select name='catego'>
				<option value='0'>"._MA_PORTFOLIO_SELECT."</option>";
		portfolio_get_categos($result);
		foreach ($result as $k => $v){
			$select .= "<option value='$v[id_cat]'>$v[nombre]</option>";
		}
	$select .= "</select>";
		echo "<div class='confirmMsg'><form name='frmDel' method='post' action='categos.php?op=del'>
				"._MA_PORTFOLIO_SELECTCAT."<br /><br />$select
				</form></div>";
		portfolio_make_footer();
        include_once 'admin_footer.php';
	}
}

$op = isset($_GET['op']) ? $_GET['op'] : (isset($_POST['op']) ? $_POST['op'] : '');

switch ($op){
	case 'new':
		portfolioNew();
		break;
	case 'save':
		portfolioSave();
		break;
	case 'edit':
		portfolioEdit();
		break;
	case 'saveedit':
		portfolioSaveEdit();
		break;
	case 'del':
		portfolioDelete();
		break;
	default:
		portfolioShow();
		break;
}
?>