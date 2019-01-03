<?php
/**
 * @package     IO
 * @subpackage  Template
 * @category    Framework
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 13
*/

interface View
{
	/**
	 * Returns a string representation of the view.
     * @return string a string representation of the view
     * @throws TemplateRenderingException
     */
	function render();
}
?>
