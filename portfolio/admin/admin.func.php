<?php
/*******************************************************************
* $Id: admin.func.php,v 1.0.1 24/05/2006 00:34 BitC3R0 Exp $       *
* ----------------------------------------------------------       *
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
* ----------------------------------------------------------       *
* admin.func.php:                                                  *
* Funciones para la sección administrativa                         *
* ----------------------------------------------------------       *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.1                                                  *
* @modificado: 24/05/2006 12:34:23 a.m.                            *
*******************************************************************/
/**
 * Crea la barra de navegación superior del módulo
 */
function portfolio_make_adminnav(){
	
	echo "<table width='100%' class='outer' cellspacing='1'>
			<tr align='center' style='background: url(../images/bgmenu.jpg) repeat-x; height: 20px;'>
			<td style='".((_PORTFOLIO_LOCATION=='INDEX') ? "background: url(../images/bgmenuselec.jpg) repeat-x; " : "")."vertical-align: middle; border-right: 1px solid #CCCCCC; border-bottom: 1px solid #CCCCCC;' onmouseover=\"this.style.background='url(../images/bgmenu1.jpg) repeat-x;';\" onmouseout=\"this.style.background='url(../images/".((_PORTFOLIO_LOCATION=='INDEX') ? "bgmenuselec.jpg" : "bgmenu.jpg").") repeat-x;';\">
			<a href='./'>"._MA_PORTFOLIO_WORKS."</a></td>
			<td style='".((_PORTFOLIO_LOCATION=='NEWWORK') ? "background: url(../images/bgmenuselec.jpg) repeat-x; " : "")."vertical-align: middle; border-right: 1px solid #CCCCCC; border-bottom: 1px solid #CCCCCC;' onmouseover=\"this.style.background='url(../images/bgmenu1.jpg) repeat-x;';\" onmouseout=\"this.style.background='url(../images/".((_PORTFOLIO_LOCATION=='NEWWORK') ? "bgmenuselec.jpg" : "bgmenu.jpg").") repeat-x;';\">
			<a href='./?op=new'>"._MA_PORTFOLIO_NEWWORK."</a></td>
			<td style='".((_PORTFOLIO_LOCATION=='CATEGOS') ? "background: url(../images/bgmenuselec.jpg) repeat-x; " : "")."vertical-align: middle; border-right: 1px solid #CCCCCC; border-bottom: 1px solid #CCCCCC;' onmouseover=\"this.style.background='url(../images/bgmenu1.jpg) repeat-x;';\" onmouseout=\"this.style.background='url(../images/".((_PORTFOLIO_LOCATION=='CATEGOS') ? "bgmenuselec.jpg" : "bgmenu.jpg").") repeat-x;';\">
			<a href='categos.php'>"._MA_PORTFOLIO_CATEGOS."</a></td>
			<td style='".((_PORTFOLIO_LOCATION=='NEWCATEGO') ? "background: url(../images/bgmenuselec.jpg) repeat-x; " : "")."vertical-align: middle; border-right: 1px solid #CCCCCC; border-bottom: 1px solid #CCCCCC;' onmouseover=\"this.style.background='url(../images/bgmenu1.jpg) repeat-x;';\" onmouseout=\"this.style.background='url(../images/".((_PORTFOLIO_LOCATION=='NEWCATEGO') ? "bgmenuselec.jpg" : "bgmenu.jpg").") repeat-x;';\">
			<a href='categos.php?op=new'>"._MA_PORTFOLIO_NEWCATEGO."</a></td>
			</tr></table><br />";
}

// Pie de la página
function portfolio_make_footer($echo = true){
	$rtn = "<div style='font-size: 10px; text-align: center; padding: 4px;'>";
	if ($echo){
		$rtn.= "";
	}
	$rtn .= "";
	if ($echo){ echo $rtn; } else { return $rtn; }
}

// Obtenemos las categorías
function portfolio_get_categos(&$rtn, $parent=0, $saltos=0, $current=0){
	global $db;
	
	$result = $db->query("SELECT * FROM ".$db->prefix("portfolio_categos")." WHERE `parent`='$parent' ORDER BY `orden`");
	while ($row=$db->fetchArray($result)){
		if ($row['id_cat']==$current){ continue; }
		$row['saltos'] = $saltos;
		$rtn[] = $row;
		portfolio_get_categos($rtn, $row['id_cat'], $saltos + 2);
	}
	
}

/**
 * Obtenemos el editor correcto
 */
//TODO: replace it with standard dynamic selection
function portfolio_select_editor($name, $type='dhtml', $value='', $width='100%', $height='400px', $addon=''){
	
	$editor = false;
	$caption = '';
	$x22=false;
	$xv=str_replace('XOOPS ','',XOOPS_VERSION);
	if(substr($xv,2,1)=='2') {
		$x22=true;
	}
	$editor_configs=array();
	$editor_configs["name"] =$name;
	$editor_configs["value"] = $value;
	$editor_configs["rows"] = 15;
	$editor_configs["cols"] = 50;
	$editor_configs["width"] = $width;
	$editor_configs["height"] = $height;

	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

	switch(strtolower($type)){
		case "spaw":
			if(!$x22) {
				if (is_readable(XOOPS_ROOT_PATH . "/class/spaw/formspaw.php"))	{
					include_once(XOOPS_ROOT_PATH . "/class/spaw/formspaw.php");
					$editor = new XoopsFormSpaw($caption, $name, $value);
				}
			} else {
				$editor = new XoopsFormEditor($caption, "spaw", $editor_configs);
			}
			break;

		case "fck":
			if(!$x22) {
				if ( is_readable(XOOPS_ROOT_PATH . "/class/fckeditor/formfckeditor.php"))	{
					include_once(XOOPS_ROOT_PATH . "/class/fckeditor/formfckeditor.php");
					$editor = new XoopsFormFckeditor($caption, $name, $value);
				}
			} else {
				$editor = new XoopsFormEditor($caption, "fckeditor", $editor_configs);
			}
			break;

		case "htmlarea":
			if(!$x22) {
				if ( is_readable(XOOPS_ROOT_PATH . "/class/htmlarea/formhtmlarea.php"))	{
					include_once(XOOPS_ROOT_PATH . "/class/htmlarea/formhtmlarea.php");
					$editor = new XoopsFormHtmlarea($caption, $name, $value);
				}
			} else {
				$editor = new XoopsFormEditor($caption, "htmlarea", $editor_configs);
			}
			break;

		case "dhtml":
			if(!$x22) {
				$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 50, $supplemental);
			} else {
				$editor = new XoopsFormEditor($caption, "dhtmltextarea", $editor_configs);
			}
			break;

		case "textarea":
			$editor = new XoopsFormTextArea($caption, $name, $value);
			break;

		case "koivi":
			if(!$x22) {
				if ( is_readable(XOOPS_ROOT_PATH . "/class/wysiwyg/formwysiwygtextarea.php"))	{
					include_once(XOOPS_ROOT_PATH . "/class/wysiwyg/formwysiwygtextarea.php");
					$editor = new XoopsFormWysiwygTextArea($caption, $name, $value, '100%', '400px', '');
				}
			} else {
				$editor = new XoopsFormEditor($caption, "koivi", $editor_configs);
			}
			break;
	}

	return $editor->render();

}

/**
 * Generamos una cadena aleatroria
 */
function portfolio_make_random($size=8, $prefix=''){
	$chars = "abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$ret = '';
	$len = strlen($chars);
	for($i=1;$i<=$size;$i++){
		mt_srand((double) microtime() * 1000000);
		$sel = mt_rand(0, $len);
		$ret .= substr($chars, $sel, 1);
	}
	return $prefix.$ret;
}

/**
 * Función que agrega una diagonal al final de una ruta
 * @return string
 */
function portfolio_add_slash($text){
	if ($text==''){ return; }
	if (substr($text, strlen($text) - 1, 1) != '/'){
		$text = $text . '/';
	}
	
	return $text;
}

/**
 * Obtenemos el directorio web
 */
function portfolio_web_dir($dir){
	
	$dir = portfolio_add_slash($dir);
	$dir = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $dir);
		
	return $dir;
}
/**
 * Permite redimensionar una imágen
 * a un tamaño dado
 * Es necesario contar con la extension GD2 de PHP
 */
function portfolio_image_resize($source,$target,$width, $height){
      //calculamos la altura proporcional
      $datos = getimagesize($source);
	  
	  if ($datos[0] >= $datos[1]){
	  	if ($datos[0] <= $width){
			$ratio = 1;
			$width = $datos[0];
		} else {
			$ratio = ($datos[0] / $width);
		}
		$height = round($datos[1] / $ratio);
	  } else {
	  	if ($datos[1] <= $height){
			$ratio = 1;
			$height = $datos[1];
		} else {
			$ratio = ($datos[1] / $height);
		}
	  	$ratio = ($datos[1] / $height);
		$width = round($datos[0] / $ratio);
	  }
	  $type = strrchr($target, ".");
	  $type = strtolower($type);
	  
	  if ($width >= $datos[0] && $height >= $datos[1]){
	  	if ($source != $target){
			copy($source, $target);
			return;
		}
	  }
	  
      // esta será la nueva imagen reescalada
      $thumb = imagecreatetruecolor($width,$height);
	  switch ($type){
	  	case '.jpg':
			$img = imagecreatefromjpeg($source);
			break;
		case '.gif':
			$img = imagecreatefromgif($source);
			break;
		case '.png':
			$img = imagecreatefrompng($source);
			break;
	  }
      // con esta función la reescalamos
      imagecopyresampled ($thumb, $img, 0, 0, 0, 0, $width, $height, $datos[0], $datos[1]);
      // la guardamos con el nombre y en el lugar que nos interesa.
	  switch ($type){
	  	case '.jpg':
      		imagejpeg($thumb,$target,80);
			break;
		case '.gif':
			imagegif($thumb,$target,80);
			break;
		case '.png':
			imagepng($thumb,$target,80);
			break;
	  }
	  
}

/**
 * Rediemsion de Imágenes
 */
function resize_then_crop( $filein,$fileout,$imagethumbsize_w,$imagethumbsize_h,$red,$green,$blue)
{
	// Get new dimensions
	list($width, $height) = getimagesize($filein);
	$new_width = $width * $percent;
	$new_height = $height * $percent;

	if(preg_match("/.jpg/i", "$filein")){
		$format = 'image/jpeg';
   	}
   	if (preg_match("/.gif/i", "$filein")){
		$format = 'image/gif';
	}
   	if(preg_match("/.png/i", "$filein")){
		$format = 'image/png';
	}
  
	switch($format){
    	case 'image/jpeg':
        	$image = imagecreatefromjpeg($filein);
           	break;
        case 'image/gif';
           	$image = imagecreatefromgif($filein);
           	break;
        case 'image/png':
           	$image = imagecreatefrompng($filein);
           	break;
	}

	$width = $imagethumbsize_w ;
	$height = $imagethumbsize_h ;
	list($width_orig, $height_orig) = getimagesize($filein);

	if ($width_orig < $height_orig) {
  		$height = ($imagethumbsize_w / $width_orig) * $height_orig;
	} else {
   		$width = ($imagethumbsize_h / $height_orig) * $width_orig;
	}

	if ($width < $imagethumbsize_w){
		//if the width is smaller than supplied thumbnail size
		$width = $imagethumbsize_w;
		$height = ($imagethumbsize_w/ $width_orig) * $height_orig;;
	}

	if ($height < $imagethumbsize_h){
		$height = $imagethumbsize_h;
		$width = ($imagethumbsize_h / $height_orig) * $width_orig;
	}

	$thumb = imagecreatetruecolor($width , $height); 
	$bgcolor = imagecolorallocate($thumb, $red, $green, $blue); 
	ImageFilledRectangle($thumb, 0, 0, $width, $height, $bgcolor);
	imagealphablending($thumb, true);

	imagecopyresampled($thumb, $image, 0, 0, 0, 0,
	$width, $height, $width_orig, $height_orig);
	$thumb2 = imagecreatetruecolor($imagethumbsize_w , $imagethumbsize_h);
	// true color for best quality
	$bgcolor = imagecolorallocate($thumb2, $red, $green, $blue); 
	ImageFilledRectangle($thumb2, 0, 0,
	$imagethumbsize_w , $imagethumbsize_h , $white);
	imagealphablending($thumb2, true);

	$w1 =($width/2) - ($imagethumbsize_w/2);
	$h1 = ($height/2) - ($imagethumbsize_h/2);

	imagecopyresampled($thumb2, $thumb, 0,0, $w1, $h1,
	$imagethumbsize_w , $imagethumbsize_h ,$imagethumbsize_w, $imagethumbsize_h);

	// Output
	//header('Content-type: image/gif');
	//imagegif($thumb); //output to browser first image when testing

	switch($format){
    	case 'image/jpeg':
        	imagejpeg($thumb2, $fileout);
           	break;
        case 'image/gif';
           	imagegif($thumb2, $fileout);
           	break;
        case 'image/png':
           imagepng($thumb2, $fileout);
           	break;
	} //write to file
	//header('Content-type: image/gif');
	//imagegif($thumb2); //output to browser
}

// Localización
function portfolio_localize($id, $by){
	global $db;
	
	$ret = '';
	if ($by==0){
		$result = $db->query("SELECT id_cat, nombre, parent FROM ".$db->prefix("portfolio_categos")." WHERE id_cat='$id'");
		if ($db->getRowsNum($result)<=0){ return; }
		$row = $db->fetchArray($result);
		if ($row['parent']>0){ $ret .= portfolio_localize($row['parent'], 0); }
		$ret .= " &raquo; <a href='categos.php?id=$id'>$row[nombre]</a>";
	} else {
		$result = $db->query("SELECT id_w, titulo, catego FROM ".$db->prefix("portfolio_works")." WHERE id_w='$id'");
		if ($db->getRowsNum($result)<=0){ return; }
		$row = $db->fetchArray($result);
		$ret .= portfolio_localize($row['catego'], 0);
		$ret .= " &raquo; <a href='view.php?id=$id' style='color: #CC0000;'>$row[titulo]</a>";
	}
	
	return $ret;
}
?>