<?php
/*##################################################
 *		      GoogleMapsFormFieldMapAddress.class.php
 *                            -------------------
 *   begin                : April 3, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class GoogleMapsFormFieldMapAddress extends AbstractFormField
{
	/**
	 * @var Usefull to know if we have to include all the necessary JS includes
	 */
	private $include_api = true;
	
	/**
	 * @var Always display marker on the map or not
	 */
	private $always_display_marker = false;
	
	/**
	 * @desc Constructs a GoogleMapsFormFieldSimpleAddress.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}
	
	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();
		$config   = GoogleMapsConfig::load();
		
		$field_tpl = new FileTemplate('GoogleMaps/GoogleMapsFormFieldMapAddress.tpl');
		$field_tpl->add_lang(LangLoader::get('common', 'GoogleMaps'));
		
		$this->assign_common_template_variables($template);
		
		$unserialized_value = @unserialize($this->get_value());
		$value = $unserialized_value !== false ? $unserialized_value : $this->get_value();
		
		if (!($value instanceof GoogleMapsMarker))
		{
			$marker = new GoogleMapsMarker();
			$marker->set_properties(array(
				'address' => !is_array($value) ? $value : (isset($value['address']) ? $value['address'] : ''),
				'latitude' => is_array($value) && isset($value['latitude']) ? $value['latitude'] : '',
				'longitude' => is_array($value) && isset($value['longitude']) ? $value['longitude'] : '',
				'zoom' => is_array($value) && isset($value['zoom']) ? $value['zoom'] : 0
			));
		}
		else
			$marker = $value;
		
		$field_tpl->put_all(array_merge($marker->get_array_tpl_vars(), array(
			'C_INCLUDE_API' => $this->include_api,
			'C_ALWAYS_DISPLAY_MARKER' => $this->always_display_marker,
			'C_HIDE_MARKER' => !$this->always_display_marker ? !$marker->get_address() : false,
			'C_CLASS' => !empty($this->get_css_class()),
			'API_KEY' => $config->get_api_key(),
			'DEFAULT_LATITUDE' => $config->get_default_marker_latitude(),
			'DEFAULT_LONGITUDE' => $config->get_default_marker_longitude(),
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'CLASS' => $this->get_css_class(),
			'C_READONLY' => $this->is_readonly(),
			'C_DISABLED' => $this->is_disabled()
		)));
		
		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field_tpl->render()
		));
		
		return $template;
	}
	
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$marker = new GoogleMapsMarker();
		$field_address_id = $this->get_html_id();
		if ($request->has_postparameter($field_address_id))
		{
			$field_latitude_id = 'latitude-' . $this->get_html_id();
			$field_longitude_id = 'longitude-' . $this->get_html_id();
			$field_zoom_id = 'zoom-' . $this->get_html_id();
			
			$marker->set_properties(array(
				'address' => $request->get_poststring($field_address_id), 
				'latitude' => $request->get_poststring($field_latitude_id),
				'longitude' => $request->get_poststring($field_longitude_id),
				'zoom' => $request->get_poststring($field_zoom_id)
			));
		}
		
		$this->set_value(TextHelper::serialize($marker->get_properties()));
	}
	
	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'include_api':
					$this->include_api = (bool)$value;
					unset($field_options['include_api']);
					break;
				case 'always_display_marker':
					$this->always_display_marker = (bool)$value;
					unset($field_options['always_display_marker']);
					break;
			}
		}
		parent::compute_options($field_options);
	}
	
	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>