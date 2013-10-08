<?php
/*******************************************************************
* $Id: work.class.php,v 1.0.3 24/05/2006 00:42 BitC3R0 Exp $       *
* ----------------------------------------------------------       *
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
* ----------------------------------------------------------       *
* work.class.php:                                                  *
* Clase para el manejo de trabajos                                 *
* ----------------------------------------------------------       *
* @copyright: � 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT MyFolder v1.0                                   *
* @version: 1.0.3                                                  *
* @modificado: 24/05/2006 12:42:37 a.m.                            *
*******************************************************************/

include_once XOOPS_ROOT_PATH.'/modules/portfolio/class/object.class.php';

class MFWork extends MFObject
{
	var $_tbl = '';
	
	function __construct($id=null){
		
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		
		if (is_null($id)){ return; }
		
		$this->_tbl = $this->db->prefix("portfolio_works");
		
		$result = $this->db->query("SELECT * FROM $this->_tbl WHERE id_w='$id'");
		if ($this->db->getRowsNum($result)<=0){ $this->initVar('found', false); return; }
		
		$row = $this->db->fetchArray($result);
		
		foreach ($row as $k => $v){
			$this->initVar($k, $v);
		}
		
		$this->initVar('found',true);
		$this->initVar('images', $this->getImages());
		
	}
	
	/**
	 * Obtenemos las im�genes
	 */
	function getImages(){
		$result = $this->db->query("SELECT * FROM ".$this->db->prefix("portfolio_images")." WHERE work='".$this->getVar('id_w')."'");
		$this->initVar('total_images', $this->db->getRowsNum($result));
		$images = array();
		while ($row=$this->db->fetchArray($result)){
			$images[] = $row;
		}
		
		return $images;
	}
}

?>