<?php
/*******************************************************************
* $Id: formdates.class.php,v 1.0.1 24/05/2006 00:45 BitC3R0 Exp $  *
* ---------------------------------------------------------------  *
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
* ---------------------------------------------------------------  *
* formdates.class.php:                                             *
* Clase para generación de campos de Fecha                         *
* ---------------------------------------------------------------  *
* @copyright: © 2006. BitC3R0.                                     *
* @autor: BitC3R0                                                  *
* @paquete: RMSOFT GS 2.0                                          *
* @version: 1.0.1                                                  *
* @modificado: 24/05/2006 12:45:32 a.m.                            *
*******************************************************************/

class RMDate extends RMFormElement
{
    var $_start = '';
    var $_options = array();
    
    function __construct($caption, $name, $start=''){
        $this->setName($name);
        $this->setCaption($caption);
        $this->_start = $start;
    }
    
    function showDay($format='d', $caption=''){
        $ret['format'] = ($format!='') ? $format : 'd';
        $ret['caption'] = $caption;
        $this->_options['day'] = $ret;
    }
    function showMonth($format='F', $caption=''){
        $ret['format'] = ($format!='') ? $format : 'F';
        $ret['caption'] = $caption;
        $this->_options['month'] = $ret;
    }
    function showYear($caption='', $start=0,$end=0){
        $ret['format'] = 'Y';
        $ret['caption'] = $caption;
        $ret['start'] = intval($start);
        $ret['end'] = ($end > date($ret['format'], time())) ? $end : date($ret['format'], time()) + 10;
        $this->_options['year'] = $ret;
    }
    function showHour($caption=''){
        $ret['format'] = 'H';
        $ret['caption'] = $caption;
        $this->_options['hour'] = $ret;
    }
    function showMinute($caption=''){
        $ret['format'] = 'i';
        $ret['caption'] = $caption;
        $this->_options['minute'] = $ret;
    }
    function showSecond($caption=''){
        $ret['format'] = 's';
        $ret['caption'] = $caption;
        $this->_options['second'] = $ret;
    }
    /**
     * Creamos el HTML de los campos
     */
    function render(){
        $ret = "<table cellspacing='1' cellpadding='0' border='0' style='width: 10%;'>
				 <tr align='left'>";
        foreach ($this->_options as $k => $v){
            $ret .= "<td style='font-size: 10px;'>$v[caption]</td>";
        }
        $ret .= "</tr><tr align='left'>";
        foreach ($this->_options as $k => $v){
            $ret .= "<td nowrap='nowrap'>";
            switch ($k){
                case 'day':
                    $day = ($this->_start > 0) ? date($v['format'], $this->_start) : 0;
                    $ret .= "<select name='".$this->getName()."_day'>";
                    for ($i=1;$i<=31;$i++){
                        $ret .= "<option value='$i'".(($i==$day) ? " selected='selected'" : '').">$i</option>";
                    }
                    $ret .= "</select>";
                    break;
                case 'month':
                    $month = ($this->_start > 0) ? date($v['format'], $this->_start) : 0;
                    $ret .= "<select name='".$this->getName()."_month'>";
                    for ($i=1;$i<=12;$i++){
                        $ret .= "<option value='$i'".((date($v['format'], mktime(0,0,0,$i,1,0))==$month) ? " selected='selected'" : '').">".date($v['format'], mktime(0,0,0,$i,1,0))."</option>";
                    }
                    $ret .= "</select> ";
                    break;
                case 'year':
                    $year = ($this->_start > 0) ? date($v['format'], $this->_start) : 0;
                    $ret .= "<select name='".$this->getName()."_year'>";
                    for ($i=$v['start'];$i<=$v['end'];$i++){
                        $ret .= "<option value='$i'".((date($v['format'], mktime(0,0,0,1,1,$i))==$year) ? " selected='selected'" : '').">".date($v['format'], mktime(0,0,0,1,1,$i))."</option>";
                    }
                    $ret .= "</select> ";
                    break;
                case 'hour':
                    $hour = ($this->_start > 0) ? date($v['format'], $this->_start) : 0;
                    $ret .= "- <select name='".$this->getName()."_hour'>";
                    for ($i=0;$i<=23;$i++){
                        $ret .= "<option value='$i'".((date($v['format'], mktime($i,0,0,1,1,0))==$hour) ? " selected='selected'" : '').">".date($v['format'], mktime($i,0,0,1,1,0))."</option>";
                    }
                    $ret .= "</select> ";
                    break;
                case 'minute':
                    $min = ($this->_start > 0) ? date($v['format'], $this->_start) : 0;
                    $ret .= " : <select name='".$this->getName()."_minute'>";
                    for ($i=0;$i<=59;$i++){
                        $ret .= "<option value='$i'".((date($v['format'], mktime(0,$i,0,1,1,0))==$min) ? " selected='selected'" : '').">".date($v['format'], mktime(0,$i,0,1,1,0))."</option>";
                    }
                    $ret .= "</select> ";
                    break;
                case 'second':
                    $second = ($this->_start > 0) ? date($v['format'], $this->_start) : 0;
                    $ret .= " : <select name='".$this->getName()."_second'>";
                    for ($i=0;$i<=59;$i++){
                        $ret .= "<option value='$i'".((date($v['format'], mktime(0,0,$i,1,1,0))==$second) ? " selected='selected'" : '').">".date($v['format'], mktime(0,0,$i,1,1,0))."</option>";
                    }
                    $ret .= "</select> ";
                    break;
            }
            $ret .= "</td>";
        }
        $ret .= "</tr></table>";

        return $ret;
    }
}
