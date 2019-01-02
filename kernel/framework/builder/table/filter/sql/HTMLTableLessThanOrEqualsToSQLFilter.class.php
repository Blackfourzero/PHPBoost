<?php
/**
 * @package     Builder
 * @subpackage  Table\filter\sql
 * @category    Framework
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 03 02
*/

class HTMLTableLessThanOrEqualsToSQLFilter extends HTMLTableNumberComparatorSQLFilter
{
    protected function get_sql_comparator_symbol()
    {
        return '<=';
    }
}

?>
