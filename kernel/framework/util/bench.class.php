<?php
/*##################################################
*                                bench.class.php
*                            -------------------
*   begin                : March 14, 2006
*   copyright            : (C) 2005 R�gis Viarre, Lo�c Rouchon
*   email                : crowkait@phpboost.com, horn@phpboost.com
*
###################################################
*
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
###################################################*/

/**
 * @author loic
 * @desc This class is done to time a process easily. You choose when to start and when to stop.
 * @package util
 */
class Bench
{
    ## Public Methods ##
    /**
     * @desc starts the bench now
     */
    function Bench() { $this->start = $this->_get_microtime(); }
    
    /**
     * @desc stops the bench now
     */
    function stop() { $this->duration = $this->_get_microtime() - $this->start; }
    
    /**
     * @desc returns the number formatted with $digits floating numbers
     * @param int $digits the desired display precision
     * @return string the formatted duration
     */
    function to_string($digits = 3) { return number_round($this->duration, $digits); }
    
    ## Private Methods ##
    /**
     * @desc computes the time with a microsecond precision
     * @access protected
     * @return float
     */
    function _get_microtime() 
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    ## Private Attributes ##
    /**
     * @access protected
     * @var int start microtime
     */
    var $start = 0;
    /**
     * @access protected
     * @var int duration microtime
     */
    var $duration = 0;
}

?>
