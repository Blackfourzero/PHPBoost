<?php
/*##################################################
 *                         FormFieldConstraintUrlExists.class.php
 *                            -------------------
 *   begin                : November 27, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 * @desc
 * @package {@package}
 */
class FormFieldConstraintUrlExists extends FormFieldConstraintRegex
{
	private $error_message;
 
	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('form.invalid_url', 'status-messages-common');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}
 
	public function validate(FormField $field)
	{
		return $this->url_is_valid($field);
	}
 
	public function url_is_valid(FormField $field)
	{
		$status = 200;
		$url = new Url($field->get_value());
		$file_headers = get_headers($url->absolute(), true);
		if (is_array($file_headers))
		{
			if(preg_match('/^HTTP\/[12]\.[01] (\d\d\d)/', $file_headers[0], $matches))
				$status = (int)$matches[1];
		}
		
		return $status == 200 || $status == 302;
	}
 
	public function get_js_validation(FormField $field)
	{
		return 'UrlExistsValidator(' . TextHelper::to_js_string($field->get_id()) .', '. $this->error_message . ')';
	}
}

?>