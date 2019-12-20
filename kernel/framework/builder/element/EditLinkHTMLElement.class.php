<?php
/**
 * @package     Builder
 * @subpackage  Element
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 20
 * @since       PHPBoost 5.3 - 2019 12 20
*/

class Edit extends LinkHTMLElement
{
	public function __construct($url, $content, $attributs = array(), $css_class = '', $use_icon = true)
	{
		parent::__construct($url, $content, array('aria-label' => LangLoader::get_message('edit', 'common')), 'far fa-fw fa-edit')
	}
}
?>