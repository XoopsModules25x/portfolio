<?php
/*******************************************************************
* $Id: main.php,v 1.0.0 24/05/2006 00:37 BitC3R0 Exp $            *
* -----------------------------------------------------            *
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
* -----------------------------------------------------            *
* main.php:                                                       *
* Control de Trabajos existentes                                   *
* -----------------------------------------------------            *
* @copyright: � 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.0                                                  *
* @modificado: 24/05/2006 12:37:27 a.m.                            *
*******************************************************************/

include 'admin_header.php';
$indexAdmin = new ModuleAdmin();
$indexAdmin->addItemButton( _MI_PORTFOLIO_AM2, 'main.php?op=new', 'add' , '');

function portfolioShow(){
	global $db, $pathIcon16, $indexAdmin;
	define('_PORTFOLIO_LOCATION','INDEX');
	xoops_cp_header();
    echo $indexAdmin->addNavigation("main.php");
    echo $indexAdmin->renderButton('right', '');
	echo "<script type='text/javascript'>
			<!--
				function decision(message, url){
					if(confirm(message)) location.href = url;
				}
			-->
		   </script>";
	//portfolio_make_adminnav();
	
	$result = $db->query("SELECT * FROM ".$db->prefix("portfolio_works")." ORDER BY titulo");
	
	include_once '../class/table.class.php';
	include_once '../class/catego.class.php';
	$table = new MFTable(true);
	$table->setCellStyle("padding: 0px; padding-left: 10px; padding-right: 10px; vertical-align: middle; border-bottom: 1px solid #0066CC; border-right: 1px solid #0066CC; background: url(../images/bgth.jpg) repeat-x; height: 20px; color: #FFFFFF;");
	$table->openTbl('100%','',1);
	$table->openRow('left');
	$table->addCell(_MA_PORTFOLIO_WORKS, 1,4);
	$table->closeRow();
	$table->setRowClass('head');
	$table->setCellStyle("padding: 0px; padding-left: 3px; padding-right: 3px; border-bottom: 1px solid #DBE691; border-right: 1px solid #DBE691; background: url(../images/bghead.jpg) repeat-x; height: 20px; color: #000000;");
	$table->openRow('center');
	$table->addCell(_MA_PORTFOLIO_TITLE, 0, '','center');
	$table->addCell(_MA_PORTFOLIO_CATEGO, 0, '','center');
	$table->addCell(_MA_PORTFOLIO_FEATURED,0,'','center');
	$table->addCell(_MA_PORTFOLIO_OPTIONS,0,'','center');
	$table->closeRow();
	
	$table->setRowClass('odd,even', true);
	$table->setCellStyle('');
	while ($row=$db->fetchArray($result)){
		$table->openRow();
		$table->addCell("<strong>$row[titulo]</strong>", 0, '', 'left');
		$catego = new MFCategory($row['catego']);
		$table->addCell($catego->getVar('nombre'), 0, '', 'center');
		$table->addCell(($row['resaltado']==1) ? _MA_PORTFOLIO_YES :  _MA_PORTFOLIO_NO, 0, '', 'center');
		$table->addCell("<a href='?op=imgs&amp;id=$row[id_w]'><img src=".$pathIcon16.'/add.png'." title='"._MA_PORTFOLIO_ADDIMAGES."'></a>
		        &nbsp;| &nbsp;<a href='?op=edit&amp;id=$row[id_w]'><img src=".$pathIcon16.'/edit.png'." title='"._MA_PORTFOLIO_EDIT."'></a>
				&nbsp;| &nbsp;<a href=\"javascript:decision('".sprintf(_MA_PORTFOLIO_CONFIRM, $row['titulo'])."','?op=del&amp;id=$row[id_w]')\"><img src=".$pathIcon16.'/delete.png'." title='"._MA_PORTFOLIO_DELETE."'></a>", 0, '', 'center');
		$table->closeRow();
	}
	
	$table->closeTbl();
	//portfolio_make_footer();
    include_once 'admin_footer.php';
}

/**
 * Creamos un nuevo trabajo
 */
function portfolioNew(){
	global $db, $mc, $indexAdmin, $xoopsModuleConfig;
	
	list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("portfolio_categos")));
	if ($num<=0){
		redirect_header('categos.php?op=new', 1, _MA_PORTFOLIO_CATEGOFIRST);
		die();
	}
	
	define('_PORTFOLIO_LOCATION','NEWWORK');
	xoops_cp_header();
	//portfolio_make_adminnav();
    echo $indexAdmin->addNavigation("main.php?op=new");
	
	include_once '../common/form.class.php';
	$form = new RMForm(_MA_PORTFOLIO_NEWWORK, 'frmNew', 'main.php?op=save');
	$form->setExtra("enctype='multipart/form-data'");
	$form->addElement(new RMText(_MA_PORTFOLIO_TITLE, 'titulo', 50, 150));
	$result = array();
	$select = "<select name='catego'>
				<option value='0'>"._MA_PORTFOLIO_SELECT."</option>";
	portfolio_get_categos($result);
	foreach ($result as $k => $v){
		$select .= "<option value='$v[id_cat]'>".str_repeat('-', $v['saltos'])." $v[nombre]</option>";
	}
	$select .= "</select>";
	$form->addElement(new RMLabel(_MA_PORTFOLIO_CATEGO, $select));
	$form->addElement(new RMText(_MA_PORTFOLIO_CLIENT, 'cliente', 50, 255));
	$form->addElement(new RMText(_MA_PORTFOLIO_URL, 'url', 50, 255, 'http://'));
	$form->addElement(new RMTextArea(_MA_PORTFOLIO_SHORT, 'short', 4, 45));

           if (class_exists('XoopsFormEditor')) {
                $options['name'] = 'desc';
                $options['value'] =((isset($desc)) ? $desc : '');
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


	$form->addElement(new RMTextArea(_MA_PORTFOLIO_COMMENT, 'comentario', 4, 45));
	$form->addElement(new RMFile(_MA_PORTFOLIO_IMG, 'imagen', 45));
	$form->addElement(new RMYesNo(_MA_PORTFOLIO_FEATURED, 'resaltado', 0));
	$form->addElement(new RMButton('sbt',_MA_PORTFOLIO_SEND));
	$form->display();
	//portfolio_make_footer();
    include_once 'admin_footer.php';
}

function portfolioSave(){
	global $db, $mc, $myts;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($titulo==''){ redirect_header('?op=new', 1, _MA_PORTFOLIO_ERRNAME); die(); }
	if ($catego<=0){ redirect_header('?op=new', 1, _MA_PORTFOLIO_ERRCATEGO); die(); }
//	if ($cliente==''){ redirect_header('?op=new', 1, _MA_PORTFOLIO_ERRCLIENTE); die(); }
	if ($desc==''){ redirect_header('?op=new', 1, _MA_PORTFOLIO_ERRDESC); die(); }
	
	include_once XOOPS_ROOT_PATH.'/class/uploader.php';
	$dir = portfolio_add_slash($mc['storedir']);
	if (is_uploaded_file($_FILES['imagen']['tmp_name'])){
	$upload = new XoopsMediaUploader($mc['storedir'], array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/jpg', 'image/pjpg', 'image/x-png', 'image/png','application/pdf'),1024 * 1024);
	$ext = strrchr($_FILES['imagen']['name'], ".");
	$ext = strtolower($ext);
		do{
			$newname = portfolio_make_random(9, 'mf') . $ext;
		}while(file_exists($dir . $newname));
		
		$upload->setTargetFileName($newname);
		$upload->fetchMedia('imagen');
		if (!$upload->upload()) {
			redirect_header("?op=new", 1, $upload->getErrors());
			exit();
		} else {
			$newname = $upload->getSavedFileName();
		}	
		
		// Redimensionamos las im�genes
		portfolio_image_resize($dir . $newname, $dir . $newname, $mc['imgw'], $mc['imgh']);
		if (!is_dir($dir . 'ths/')){ mkdir($dir . 'ths/', 0777); }
		//portfolio_image_resize($dir . $newname, $dir . 'ths/' . $newname, $mc['thw'], $mc['thh']);
		resize_then_crop( $dir . $newname,$dir . 'ths/' . $newname,$mc['thw'],$mc['thh'],255,255,255);
	}
	
	$tbl = $db->prefix("portfolio_works");
	$desc = $myts->makeTareaData4Save($desc);
	$short = $myts->makeTareaData4Save(substr($short, 0, 255), 0, 0);
	$comentario = $myts->makeTareaData4Save($comentario, 0, 0);
	$sql = "INSERT INTO $tbl (`titulo`,`short`,`desc`,`catego`,`cliente`,`comentario`,
			`url`,`resaltado`,`imagen`) VALUES ('$titulo','$short','$desc','$catego',
			'$cliente','$comentario','$url','$resaltado','$newname')";
	$db->query($sql);
	if ($db->error()!=''){
		redirect_header('?op=new', 2, sprintf(_MA_PORTFOLIO_ERRDB, $db->error()));
		die();
	} else {
		header('location: main.php'); die();
	}
}

/**
 * Editamos un trabajo
 */
function portfolioEdit(){
	global $db, $mc, $myts, $xoopsModuleConfig;
	
	$id = isset($_GET['id']) ? $_GET['id'] : 0;
	if ($id<=0){ header('location: main.php'); die(); }
	
	define('_PORTFOLIO_LOCATION','WORKS');
	
	include_once '../class/work.class.php';
	xoops_cp_header();
	//portfolio_make_adminnav();
	
	include_once '../common/form.class.php';
	
	$work = new MFWork($id);
	
	$form = new RMForm(_MA_PORTFOLIO_NEWWORK, 'frmMod', 'main.php?op=saveedit');
	$form->setExtra("enctype='multipart/form-data'");
	$form->addElement(new RMText(_MA_PORTFOLIO_TITLE, 'titulo', 50, 150, $work->getVar('titulo')));
	$result = array();
	$select = "<select name='catego'>
				<option value='0'>"._MA_PORTFOLIO_SELECT."</option>";
	portfolio_get_categos($result);
	foreach ($result as $k => $v){
		$select .= "<option value='$v[id_cat]'".(($v['id_cat']==$work->getVar('catego')) ? " selected='selected'" : '').">".str_repeat('-', $v['saltos'])." $v[nombre]</option>";
	}
	$select .= "</select>";
	$form->addElement(new RMLabel(_MA_PORTFOLIO_CATEGO, $select));
	$form->addElement(new RMText(_MA_PORTFOLIO_CLIENT, 'cliente', 50, 255, $work->getVar('cliente')));
	$form->addElement(new RMText(_MA_PORTFOLIO_URL, 'url', 50, 255, $work->getVar('url')));
	$form->addElement(new RMTextArea(_MA_PORTFOLIO_SHORT, 'short', 4, 45, $myts->makeTareaData4Edit($work->getVar('short'), 0, 0)));

        if (class_exists('XoopsFormEditor')) {
                $options['name'] = 'desc';
                $options['value'] = $work->getVar('desc');
                $options['rows'] = 5;
                $options['cols'] = '100%';
                $options['width'] = '100%';
                $options['height'] = '200px';
            $formmnote  = new XoopsFormEditor('', $xoopsModuleConfig['editor'], $options, $nohtml = false, $onfailure = 'textarea');
            } else {
            $formmnote  = new XoopsFormDhtmlTextArea('', 'formmnote', $item->getVar('formmnote', 'e'), '100%', '100%');
        }

    //$form->addElement(new RMLabel(_MA_PORTFOLIO_DESC, portfolio_select_editor('desc',$mc['editor'],$myts->makeTareaData4Edit($work->getVar('desc')),'100%','250px')));
        $form->addElement(new RMLabel(_MA_PORTFOLIO_DESC,$formmnote->render() ));

    $form->addElement(new RMTextArea(_MA_PORTFOLIO_COMMENT, 'comentario', 4, 45, $work->getVar('comentario')));
	$ele = new RMFile(_MA_PORTFOLIO_IMG, 'imagen', 45);
	$ele->setDescription(_MA_PORTFOLIO_IMG_INFO);
	$form->addElement($ele);
	if ($work->getVar('imagen')!=''){
		$form->addElement(new RMLabel(_MA_PORTFOLIO_CURRIMG, "<img src='".portfolio_add_slash(portfolio_web_dir($mc['storedir'])).'ths/'.$work->getVar('imagen')."' border='0' />"));
	}
	$form->addElement(new RMYesNo(_MA_PORTFOLIO_FEATURED, 'resaltado', ($work->getVar('resaltado')==1) ? 1 : 0));
	$form->addElement(new RMButton('sbt',_MA_PORTFOLIO_SEND));
	$form->addElement(new RMHidden('id', $work->getVar('id_w')));
	$form->display();
	//portfolio_make_footer();
    include_once 'admin_footer.php';
}

function portfolioSaveEdit(){
	global $db, $mc, $myts;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if ($id<=0){ header('location: main.php'); die(); }
	
	if ($titulo==''){ redirect_header("?op=edit&amp;id=$id", 1, _MA_PORTFOLIO_ERRNAME); die(); }
	if ($catego<=0){ redirect_header("?op=edit&amp;id=$id", 1, _MA_PORTFOLIO_ERRCATEGO); die(); }
//	if ($cliente==''){ redirect_header("?op=edit&amp;id=$id", 1, _MA_PORTFOLIO_ERRCLIENTE); die(); }
	if ($desc==''){ redirect_header("?op=edit&amp;id=$id", 1, _MA_PORTFOLIO_ERRDESC); die(); }
	
	include_once XOOPS_ROOT_PATH.'/class/uploader.php';
	include_once '../class/work.class.php';
	
	$work = new MFWork($id);
	
	$dir = portfolio_add_slash($mc['storedir']);
	$newname = $work->getVar('imagen');
	if (is_uploaded_file($_FILES['imagen']['tmp_name'])){
		
		// Eliminamos las im�genes anteriores si existen
		if ($work->getVar('imagen')!=''){
			if (file_exists($dir . $work->getVar('imagen'))){
				unlink($dir . $work->getVar('imagen'));
			}
			if (file_exists($dir . 'ths/' . $work->getVar('imagen'))){
				unlink($dir . 'ths/' . $work->getVar('imagen'));
			}		
		}
		
		$upload = new XoopsMediaUploader($mc['storedir'], array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/jpg', 'image/pjpg', 'image/x-png', 'image/png'),1024 * 1024);
		$ext = strrchr($_FILES['imagen']['name'], ".");
		$ext = strtolower($ext);
		do{
			$newname = portfolio_make_random(9, 'mf') . $ext;
		}while(file_exists($dir . $newname));
		
		$upload->setTargetFileName($newname);
		$upload->fetchMedia('imagen');
		if (!$upload->upload()) {
			redirect_header("?op=new", 1, $upload->getErrors());
			exit();
		} else {
			$newname = $upload->getSavedFileName();
		}	
		
		// Redimensionamos las im�genes
		portfolio_image_resize($dir . $newname, $dir . $newname, $mc['imgw'], $mc['imgh']);
		if (!is_dir($dir . 'ths/')){ mkdir($dir . 'ths/', 0777); }
		//portfolio_image_resize($dir . $newname, $dir . 'ths/' . $newname, $mc['thw'], $mc['thh']);
		resize_then_crop( $dir . $newname,$dir . 'ths/' . $newname,$mc['thw'],$mc['thh'],255,255,255);
	}
	
	$tbl = $db->prefix("portfolio_works");
	$desc = $myts->makeTareaData4Save($desc);
	$short = $myts->makeTareaData4Save($short, 0, 0);
	$comentario = $myts->makeTareaData4Save($comentario, 0, 0);
	$sql = "UPDATE $tbl SET `titulo`='$titulo',`short`='$short',`desc`='$desc',
			`catego`='$catego',`cliente`='$cliente',`comentario`='$comentario',
			`url`='$url',`resaltado`='$resaltado',`imagen`='$newname' WHERE id_w='$id'";
	$db->queryF($sql);
	if ($db->error()!=''){
		redirect_header('?op=edit&amp;id='.$id, 2, sprintf(_MA_PORTFOLIO_ERRDB, $db->error()));
		die();
	} else {
		header('location: main.php'); die();
	}
}

/**
 * Eliminamos un trabajo
 */
function portfolioDelete(){
	global $db, $mc;
	
	$id = isset($_GET['id']) ? $_GET['id'] : 0;
	
	if ($id<=0){ header('location: main.php'); die(); }
	
	include_once '../class/work.class.php';
	$work = new MFWork($id);
	$dir = portfolio_add_slash($mc['storedir']);
	foreach ($work->getVar('images') as $k => $v){
		file_exists($dir . $v['archivo']) ? unlink($dir . $v['archivo']) : '';
		file_exists($dir . 'ths/' . $v['archivo']) ? unlink($dir . 'ths/' . $v['archivo']) : '';
	}
	
	if ($work->getVar('imagen')!=''){
		file_exists($dir . $work->getVar('imagen')) ? unlink($dir . $work->getVar('imagen')) : '';
		file_exists($dir . 'ths/' . $work->getVar('imagen')) ? unlink($dir . 'ths/' . $work->getVar('imagen')) : '';
	}
	
	$db->queryF("DELETE FROM ".$db->prefix("portfolio_images")." WHERE work='$id'");
	$db->queryF("DELETE FROM ".$db->prefix("portfolio_works")." WHERE id_w='$id'");
	
	if ($db->error()!=''){
		redirect_header('main.php', 2, sprintf(_MA_PORTFOLIO_ERRDB, $db->error()));
		die();
	} else {
		header('location: main.php'); die();
	}
	
}

function portfolioAddImgs(){
	global $db, $mc;
	define('_PORTFOLIO_LOCATION','');
	$id = isset($_GET['id']) ? $_GET['id'] : 0;
	
	if ($id<=0){ header('location: main.php'); die(); }
	
	include '../class/work.class.php';
	$work = new MFWork($id);
	
	if (!$work->getVar('found')){
		redirect_header('main.php', 1, _MA_PORTFOLIO_ERRNOEXIST);
		die();
	}
	
	xoops_cp_header();
	echo "<script type='text/javascript'>
			<!--
				function decision(message, url){
					if(confirm(message)) location.href = url;
				}
			-->
		   </script>";
	//portfolio_make_adminnav();
	$dir = portfolio_add_slash($mc['storedir']);
	$dir = portfolio_web_dir($dir);
	echo "<table class='outer' cellspacing='1'>
			<tr><th colspan='2'>"._MA_PORTFOLIO_CURRIMAGES."</th></tr>";
	$class = 'odd';
	foreach ($work->getVar('images') as $k => $v){
		$class = ($class=='even') ? 'odd' : 'even';
		echo "<tr class='$class' align='center'>
				<td><img src='".$dir."ths/".$v['archivo']."' border='0' /></td>
				<td><a href=\"javascript:decision('".sprintf(_MA_PORTFOLIO_CONFIRM, $v['archivo'])."','?op=delimg&amp;id=$id&amp;img=$v[id_img]')\">"._MA_PORTFOLIO_DELETE."</a></td></tr>";
	}
	
	if ($work->getVar('total_images')<$mc['imgnum']){
		echo "<tr class='even' align='left'><form name='addimg' action='main.php?op=addimg' method='post' enctype='multipart/form-data'>
				<td colspan='2'>"._MA_PORTFOLIO_ADDIMAGES." 
				<input type='file' name='archivo' size='45'>
				<input type='submit' name='sbt' value='"._MA_PORTFOLIO_SEND."' />
				<input type='hidden' name='id' value='$id' /></td></tr>";
	}
	
	echo "</table>";
	
	//portfolio_make_footer();
    include_once 'admin_footer.php';
}

/**
 * Guardamos una im�gen
 */
function portfolioSaveImg(){
	global $db, $mc;
	$id = isset($_POST['id']) ? $_POST['id'] : 0;
	if ($id<=0){ header('location: main.php'); die(); }
	
	include_once '../class/work.class.php';
	$work = new MFWork($id);
	
	if (!$work->getVar('found')){
		redirect_header('main.php', 1, _MA_PORTFOLIO_ERRNOEXIST);
		die();
	}
	
	$dir = portfolio_add_slash($mc['storedir']);
	if (!is_uploaded_file($_FILES['archivo']['tmp_name'])){
		redirect_header('main.php?op=imgs&id='.$id, 1, _MA_PORTFOLIO_ERRIMG);
		die();
	}
	
	include_once XOOPS_ROOT_PATH.'/class/uploader.php';
	$upload = new XoopsMediaUploader($mc['storedir'], array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/jpg', 'image/pjpg', 'image/x-png', 'image/png'),1024 * 1024);
	$ext = strrchr($_FILES['archivo']['name'], ".");
	$ext = strtolower($ext);
	do{
		$newname = portfolio_make_random(8, 'mfex') . $ext;
	}while(file_exists($dir . $newname));
		
	$upload->setTargetFileName($newname);
	$upload->fetchMedia('archivo');
	if (!$upload->upload()) {
		redirect_header("?op=imgs&amp;id=".$id, 1, $upload->getErrors());
		exit();
	} else {
		$newname = $upload->getSavedFileName();
	}	
		
	// Redimensionamos las im�genes
	portfolio_image_resize($dir . $newname, $dir . $newname, $mc['imgw'], $mc['imgh']);
	if (!is_dir($dir . 'ths/')){ mkdir($dir . 'ths/', 0777); }
	//portfolio_image_resize($dir . $newname, $dir . 'ths/' . $newname, $mc['thw'], $mc['thh']);
	resize_then_crop( $dir . $newname,$dir . 'ths/' . $newname,$mc['thw'],$mc['thh'],255,255,255);
	
	// Guardamos en la base de datos
	$db->query("INSERT INTO ".$db->prefix("portfolio_images")." (`archivo`,`work`)
			VALUES ('$newname', '$id')");
	if ($db->error()!=''){
		redirect_header('main.php?op=imgs&amp;id='.$id, 2, sprintf(_MA_PORTFOLIO_ERRDB, $db->error()));
		die();
	} else {
		redirect_header('main.php?op=imgs&amp;id='.$id, 2, '');
		die();
	}
}

/**
 * Eliminamos una im�gen
 */
function portfolioDelImg(){
	global $db, $mc;

	$id = isset($_GET['id']) ? $_GET['id'] : 0;	
	if ($id<=0){ header('location: main.php'); die(); }
	
	$img = isset($_GET['img']) ? $_GET['img'] : 0;	
	if ($img<=0){ header("location: main.php?op=imgs&amp;id=$id"); die(); }
	
	$result = $db->query("SELECT * FROM ".$db->prefix("portfolio_images")." WHERE id_img='$img' AND work='$id'");
	if ($db->getRowsNum($result)<=0){
		redirect_header("main.php?op=imgs&amp;id=$id", 1, _MA_PORTFOLIO_ERRNOIMG);
		die();
	}
	$row = $db->fetchArray($result);
	$dir = portfolio_add_slash($mc['storedir']);
	if (file_exists($dir . $row['archivo'])){
		unlink ($dir . $row['archivo']);
		unlink ($dir . 'ths/' . $row['archivo']);
	}
	
	$db->queryF("DELETE FROM ".$db->prefix("portfolio_images")." WHERE id_img='$img' AND work='$id'");
	if ($db->error()!=''){
		redirect_header('main.php?op=imgs&amp;id='.$id, 2, sprintf(_MA_PORTFOLIO_ERRDB, $db->error()));
		die();
	} else {
		redirect_header('main.php?op=imgs&amp;id='.$id, 2, '');
		die();
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
	case 'imgs':
		portfolioAddImgs();
		break;
	case 'addimg':
		portfolioSaveImg();
		break;
	case 'delimg':
		portfolioDelImg();
		break;
	default:
		portfolioShow();
		break;
}
?>