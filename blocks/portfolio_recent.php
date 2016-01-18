<?php
/*******************************************************************
* $Id: portfolio_recent.php,v 1.0.2 24/05/2006 00:39 BitC3R0 Exp $      *
* -----------------------------------------------------------      *
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
* -----------------------------------------------------------      *
* portfolio_recent.php:                                                 *
* Bloques del Módulo.                                              *
* Trabajos recientes, destacados y comentarios                     *
* de clientes                                                      *
* -----------------------------------------------------------      *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.2                                                  *
* @modificado: 24/05/2006 12:39:48 a.m.                            *
*******************************************************************/

function portfolio_bk_recent($options){

    $db =& XoopsDatabaseFactory::getDatabaseConnection();
    $myts =& MyTextSanitizer::getInstance();
    
    include_once XOOPS_ROOT_PATH.'/modules/portfolio/blocks/functions.php';
    
    $dir = portfolio_get_config('storedir');
    if (substr($dir, strlen($dir) - 1, 1)!='/'){
        $dir .= '/';
    }
    $dir = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $dir);
    
    $result = $db->query("SELECT * FROM ".$db->prefix("portfolio_works")." ORDER BY id_w DESC LIMIT 0,$options[0]");
    $block = array();
    while ($row = $db->fetchArray($result)){
        $rtn = array();
        $rtn['id'] = $row['id_w'];
        $rtn['titulo'] = $row['titulo'];
        $rtn['img'] = $dir .'ths/'.$row['imagen'];
        $rtn['desc'] = $myts->displayTarea($row['short']);
        $block['works'][] = $rtn;
    }

    return $block;
}

function portfolio_bk_recent_edit($options){
    $form = _BK_PORTFOLIO_NUMWORKS."<br /><input type='text' size='5' name='options[]' value='$options[0]' />";

    return $form;
}

/**
 * Mostramos los comentarios de los clientes
 **/
function portfolio_bk_comments($options){
    $db =& XoopsDatabaseFactory::getDatabaseConnection();
    $myts =& MyTextSanitizer::getInstance();
    $result = $db->query("SELECT * FROM ".$db->prefix("portfolio_works")." ORDER BY id_w DESC LIMIT 0,$options[0]");
    $block = array();
    while ($row = $db->fetchArray($result)){
        $rtn = array();
        $rtn['id'] = $row['id_w'];
        $rtn['titulo'] = $row['titulo'];
        $rtn['texto'] = $myts->displayTarea($row['comentario']);
        $rtn['cliente'] = $row['cliente'];
        $block['works'][] = $rtn;
    }

    return $block;
}

function portfolio_bk_comments_edit($options){
    $form = _BK_PORTFOLIO_NUMCOMMS."<br /><input type='text' size='5' name='options[]' value='$options[0]' />";

    return $form;
}

/**
 * Mostramos trabajos resaltados
 */
function portfolio_bk_featured($options){

    $db =& XoopsDatabaseFactory::getDatabaseConnection();
    $myts =& MyTextSanitizer::getInstance();
    
    include_once XOOPS_ROOT_PATH.'/modules/portfolio/blocks/functions.php';
    
    $dir = portfolio_get_config('storedir');
    if (substr($dir, strlen($dir) - 1, 1)!='/'){
        $dir .= '/';
    }
    $dir = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $dir);
    
    $result = $db->query("SELECT * FROM ".$db->prefix("portfolio_works")." WHERE resaltado='1' ORDER BY id_w DESC LIMIT 0,$options[0]");
    $block = array();
    while ($row = $db->fetchArray($result)){
        $rtn = array();
        $rtn['id'] = $row['id_w'];
        $rtn['titulo'] = $row['titulo'];
        $rtn['img'] = $dir .'ths/'.$row['imagen'];
        $rtn['desc'] = $myts->displayTarea($row['short']);
        $block['works'][] = $rtn;
    }

    return $block;
}

function portfolio_bk_featured_edit($options){
    $form = _BK_PORTFOLIO_NUMWORKS."<br /><input type='text' size='5' name='options[]' value='$options[0]' />";

    return $form;
}
