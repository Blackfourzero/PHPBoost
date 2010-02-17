<?php
/*##################################################
 *                        FormButtonSubmit.class.php
 *                            -------------------
 *   begin                : February 16, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
 * @package builder
 * @subpackage form/button
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class FormButtonSubmit implements FormButton
{
	private $name = 'submit';
	private $label = '';
	
	public function __construct($label, $name)
	{
		$this->label = $label;
		$this->name = $name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function display()
	{
		global $LANG;

		$template = new StringTemplate('<input type="submit" name="{BUTTON_NAME}" value="{L_SUBMIT}" class="submit" />');

		$template->assign_vars(array(
			'L_SUBMIT' => $this->label,
			'BUTTON_NAME' => $this->name
		));

		return $template;
	}

	public function has_been_submited()
	{
		$request = AppContext::get_request();

		$button_attribute = $request->get_string($this->name, '');
		if (!empty($button_attribute))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function get_onsubmit_action()
	{

	}
}

?>