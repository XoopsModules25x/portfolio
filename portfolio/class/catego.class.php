<?php
/*******************************************************************
* $Id: catego.class.php,v 1.0.1 24/05/2006 00:40 BitC3R0 Exp $     *
* ------------------------------------------------------------     *
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
* ------------------------------------------------------------     *
* catego.class.php:                                                *
* Clase para el manejo de categorías                               *
* ------------------------------------------------------------     *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.1                                                  *
* @modificado: 24/05/2006 12:40:28 a.m.                            *
*******************************************************************/

include_once XOOPS_ROOT_PATH.'/modules/portfolio/class/object.class.php';

class MFCategory extends MFObject
{
	var $_tbl = '';
	
	function MFCategory($id=null){
		
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		
		if (is_null($id)){ return; }
		
		$this->_tbl = $this->db->prefix("portfolio_categos");
		
		$result = $this->db->query("SELECT * FROM $this->_tbl WHERE id_cat='$id'");
		if ($this->db->getRowsNum($result)<=0){ $this->initVar('found', false); return; }
		
		$row = $this->db->fetchArray($result);
		
		foreach ($row as $k => $v){
			$this->initVar($k, $v);
		}
		
		$this->initVar('found',true);
		
	}
	
	/**
	 * Obtenemos el numero de trabajos en esta categoría
	 */
	function getWorksNumber(){
		$result = $this->db->query("SELECT COUNT(*) FROM ".$this->db->prefix("portfolio_works")." WHERE catego='".$this->getVar('id_cat')."'");
		list($num) = $this->db->fetchRow($result);
		return $num;
	}
}
?>