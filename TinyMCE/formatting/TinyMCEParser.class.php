<?php
/*##################################################
 *                           TinyMCEParser.class.php
 *                            -------------------
 *   begin                : July 3 2008
 *   copyright            : (C) 2008 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package {@package}
 * @desc This class enables to use TinyMCE without breaking the compatibility with the
 * BBCode formatting. PHPBoost has a reference syntax, it in HTML with specific CSS classes.
 * The HTML code generated by TinyMCE must be modified to conform itself to this specific syntax.
 * This class makes the translation from the TinyMCE HTML to the PHPBoost HTML.
 * @see The TinyMCEUnparser class which makes the reverse operation.
 * @author Benoit Sautel
 */
class TinyMCEParser extends ContentFormattingParser
{
	private static $fonts_array = array(
	'\'andale mono\', monospace' => 'andale mono',
	'arial, helvetica, sans-serif' => 'arial',
	'\'arial black\', sans-serif' => 'arial black',
	'\'book antiqua\', palatino, serif' => 'book antiqua',
	'\'comic sans ms\', sans-serif' => 'comic sans ms',
	'\'courier new\', courier, monospace' => 'courier new',
	'georgia, palatino, serif' => 'georgia',
	'helvetica, arial, sans-serif' => 'helvetica',
	'impact, sans-serif' => 'impact',
	'symbol' => 'symbol',
	'tahoma, arial, helvetica, sans-serif' => 'tahoma',
	'terminal, monaco, monospace' => 'terminal',
	'\'times new roman\', times, serif' => 'times new roman',
	'\'trebuchet ms\', geneva, sans-serif' => 'trebuchet ms',
	'verdana, geneva, sans-serif' => 'verdana',
	'webdings' => 'webdings',
	'wingdings, \'zapf dingbats\'' => 'wingdings'
	);

	/**
	 * @desc Builds this kind of parser
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @desc Parses the content of the parser.
	 * Translates the whole content from the TinyMCE syntax to the PHPBoost one.
	 */
	public function parse()
	{
		//On supprime d'abord toutes les occurences de balises CODE que nous réinjecterons à la fin pour ne pas y toucher
		if (!in_array('code', $this->forbidden_tags))
		{
			$this->pick_up_tag('code', '=[A-Za-z0-9#+-_.\s]+(?:,[01]){0,2}');
		}

		//On prélève tout le code HTML afin de ne pas l'altérer
		if (!in_array('html', $this->forbidden_tags) && AppContext::get_current_user()->check_auth($this->html_auth, 1))
		{
			$this->pick_up_tag('html');
		}

		//Prepare the content (HTML modifications such as entities treatment)
		$this->prepare_content();
		
		//Module eventual special tags replacement
		$this->parse_module_special_tags();

		//Parse the HTML code generated by TinyMCE
		$this->parse_tinymce_formatting();

		//Parse the HTML tables generated by TinyMCE
		if (!in_array('table', $this->forbidden_tags))
		{
			$this->parse_tables();
		}

		//Replace smilies code by smilies images
		$this->parse_smilies();

		//Parse the tags which are not supported by TinyMCE but expected in BBCode
		$this->parse_bbcode_tags();

		$this->correct();

		parent::parse();
		
		//On remet le code HTML mis de côté
		if (!empty($this->array_tags['html']))
		{
			$this->array_tags['html'] = array_map(create_function('$string', 'return str_replace("[html]", "<!-- START HTML -->\n", str_replace("[/html]", "\n<!-- END HTML -->", $string));'), $this->array_tags['html']);

			//If we don't protect the HTML code inserted into the tags code and HTML TinyMCE will parse it!
			$this->array_tags['html'] = array_map(array('TinyMCEParser', 'clear_html_and_code_tag'), $this->array_tags['html']);

			$this->reimplant_tag('html');
		}
		
		//On réinsère les fragments de code qui ont été prélevés pour ne pas les considérer
		if (!empty($this->array_tags['code']))
		{
			$this->array_tags['code'] = array_map(create_function('$string', 'return preg_replace(\'`^\[code(=.+)?\](.+)\[/code\]$`isU\', \'[[CODE$1]]$2[[/CODE]]\', TextHelper::htmlspecialchars($string, ENT_NOQUOTES));'), $this->array_tags['code']);

			//If we don't protect the HTML code inserted into the tags code and HTML TinyMCE will parse it!
			$this->array_tags['code'] = array_map(array($this, 'clear_html_and_code_tag'), $this->array_tags['code']);

			$this->reimplant_tag('code');
		}
	}

	/**
	 * @desc Prepares the content of the parser. Treats the HTML entities contained by the content, most of them has been added by TinyMCE.
	 * It also authorizes the non-utf-8 characters by accepting their HTML entity.
	 */
	private function prepare_content()
	{
		//On enlève toutes les entités HTML rajoutées par TinyMCE
		$this->content = TextHelper::html_entity_decode($this->content);

		//On casse toutes les balises HTML (sauf celles qui ont été prélevées dans le code et la balise HTML)
		$this->content = TextHelper::htmlspecialchars($this->content, ENT_NOQUOTES);

		//While we aren't in UTF8 encoding, we have to use HTML entities to display some special chars, we accept them.
		$this->content = preg_replace('`&amp;((?:#[0-9]{2,5})|(?:[a-z0-9]{2,8}));`i', "&$1;", $this->content);
	}
	/**
	 * @desc Processes the table generated by TinyMCE.
	 * @param string[] $matches The matched elements
	 * @return string The PHPBoost syntax corresponding to the table generated by TinyMCE.
	 */
	private function parse_table_tag($matches)
	{
		$table_properties = $matches[1];
		$style_properties = '';

		$temp_array = array();

		//Border ?
		if (preg_match('`border="([0-9]+)"`iU', $table_properties, $temp_array))
		{
			if ($temp_array[1] > 0)
				$style_properties .= 'border:' . $temp_array[1] . ';';
		}

		//Width ?
		if (preg_match('`width="([0-9]+)"`iU', $table_properties, $temp_array))
		{
			$style_properties .= 'width:' . $temp_array[1] . 'px;';
		}

		//Height ?
		if (preg_match('`height="([0-9]+)"`iU', $table_properties, $temp_array))
		{
			$style_properties .= 'height:' . $temp_array[1] . 'px;';
		}

		//Alignment
		if (preg_match('`align="([^"]+)"`iU', $table_properties, $temp_array))
		{
			if ($temp_array[1] == 'center')
			{
				$style_properties .= 'margin:auto;';
			}
			elseif ($temp_array[1] == 'right')
			{
				$style_properties .= 'margin-left:auto;';
			}
		}

		//Style ?
		if (preg_match('`style="([^"]+)"`iU', $table_properties, $temp_array))
		{
			$style_properties .= $temp_array[1];
		}

		return '<table class="formatter-table"' . (!empty($style_properties) ? ' style="' . $style_properties . '"' : '') . '>' . $matches[2] . '</table>';
	}

	/**
	 * Parses the rows (which corresponds to the tr HTML tags)
	 * @param string[] $matches The matched elements
	 * @return string The cell correctly formatted.
	 */
	private function parse_row_tag($matches)
	{
		$col_properties = $matches[1];
		$col_new_properties = '';
		$col_style = '';

		$temp_array = array();
		//Alignment
		if (preg_match('`align="([^"]+)"`iU', $col_properties, $temp_array))
		{
			$col_style .= 'text-align:' . $temp_array[1] . ';';
		}

		//Style ?
		if (preg_match('`style="([^"]+)"`iU', $col_properties, $temp_array))
		{
			$col_style .= ' style="' . $temp_array[1] . ' ' . $col_style . '"';
		}
		elseif (!empty($col_style))
		{
			$col_style = ' style="' . $col_style . '"';
		}

		return '<tr class="formatter-table-row"' . $col_new_properties . $col_style . '>' . $matches[2] . '</tr>';
	}

	/**
	 * Parses the col/head (which corresponds to the td and th HTML tags)
	 * @param string[] $matches The matched elements
	 * @return string The cell correctly formatted.
	 */
	private function parse_col_tag($matches)
	{
		$tag = $matches[1] == 'th' ? 'th' : 'td';
		$bbcode_tag = $tag == 'th' ? 'head' : 'col';
		$col_properties = $matches[2];
		$col_new_properties = '';
		$col_style = '';

		$temp_array = array();

		//Colspan ?
		if (preg_match('`colspan="([0-9]+)"`iU', $col_properties, $temp_array))
		{
			$col_new_properties .= ' colspan="' . $temp_array[1] . '"';
		}

		//Rowspan ?
		if (preg_match('`rowspan="([0-9]+)"`iU', $col_properties, $temp_array))
		{
			$col_new_properties .= ' rowspan="' . $temp_array[1] . '"';
		}

		//Alignment
		if (preg_match('`align="([^"]+)"`iU', $col_properties, $temp_array))
		{
			$col_style .= 'text-align:' . $temp_array[1] . ';';
		}

		//Style ?
		if (preg_match('`style="([^"]+)"`iU', $col_properties, $temp_array))
		{
			$col_style .= ' style="' . $temp_array[1] . ' ' . $col_style . '"';
		}
		elseif (!empty($col_style))
		{
			$col_style = ' style="' . $col_style . '"';
		}

		return '<' . $tag . ' class="formatter-table-' . $bbcode_tag . '"' . $col_new_properties . $col_style . '>' . ltrim($matches[3]) . '</' . $tag . '>';
	}
	
	/**
	 * @desc Parses module special tags if any.
	 * The special tags are [link] for module pages or wiki for example.
	 */
	protected function parse_module_special_tags()
	{
		foreach ($this->get_module_special_tags() as $pattern => $replacement)
			$this->content = preg_replace($pattern, $replacement, $this->content);
	}

	/**
	 * @desc Parses all the features provided by TinyMCE
	 */
	private function parse_tinymce_formatting()
	{
		global $LANG;

		//Modification de quelques tags HTML envoyés par TinyMCE
		$this->content = str_replace(
			array(
				'&amp;nbsp;&amp;nbsp;&amp;nbsp;',
				'&amp;gt;',
				'&amp;lt;',
				'&lt;br /&gt;',
				'&lt;br&gt;',
				'&amp;nbsp;'
			), array(
				"\t",
				'&gt;',
				'&lt;',
				"<br />",
				"<br />",
				' '
			), $this->content);

		$array_preg = array(
			'`&lt;p&gt;\s*&nbsp;\s*&lt;/p&gt;\s*`',
			'`&lt;p&gt;&nbsp;&lt;/p&gt;\s*`',
			'`&lt;p&gt;&nbsp;&nbsp;&lt;/p&gt;\s*`',
			'`&lt;div&gt;(.+)&lt;/div&gt;`isU',
			'`&lt;/p&gt;[\s]*`i'
		);
		$array_preg_replace = array(
			'',
			'',
			'',
			'$1' . "<br />",
			'&lt;/p&gt;'
		);

		//Replacement
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);

		//On supprime tous les retours à la ligne ajoutés par TinyMCE (seuls les nouveaux paragraphes (<p>) compteront)
		$this->content = str_replace('\r\n', '\n', $this->content);
		$this->content = preg_replace('`\s*\n+\s*`isU', "\n", $this->content);
		
		$array_preg = array();
		$array_preg_replace = array();
		
		//Colors, underline and strike replacement
		//Color tag
		if (!in_array('color', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;span style="color: *([#a-f0-9]+);"&gt;(.+)&lt;/span&gt;`isU');
			array_push($array_preg_replace, '<span style="color:$1;">$2</span>');
		}
		//Background color tag
		if (!in_array('bgcolor', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;span style="background-color: *([#a-f0-9]+);"&gt;(.+)&lt;/span&gt;`isU');
			array_push($array_preg_replace, '<span style="background-color:$1;">$2</span>');
		}
		//Underline tag
		if (!in_array('u', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;span style="text-decoration: underline;"&gt;(.+)&lt;/span&gt;`isU');
			array_push($array_preg_replace, '<span style="text-decoration: underline;">$1</span>');
		}
		//Strike tag
		if (!in_array('s', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;span style="text-decoration: line-through;"&gt;(.+)&lt;/span&gt;`isU');
			array_push($array_preg_replace, '<s>$1</s>');
			array_push($array_preg, '`&lt;s&gt;(.+)&lt;/s&gt;`isU');
			array_push($array_preg_replace, '<s>$1</s>');
		}
		
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);
		
		//On dissocie les styles des span et div
		$this->content = preg_replace_callback('`&lt;span style="color: *([#a-f0-9]+); background-color: *([#a-f0-9]+);( text-decoration: line-through;| text-decoration: underline;)?"&gt;(.*)&lt;/span&gt;`isU', array($this, 'parse_span_color_and_background_style'), $this->content );
		$this->content = preg_replace_callback('`&lt;span style="([^;]*); ([^"]*)"&gt;(.*)&lt;/span&gt;`isU', array($this, 'parse_span_style'), $this->content );
		$this->content = preg_replace_callback('`&lt;div style="([^;]*); ([^"]*)"&gt;(.*)&lt;/div&gt;`isU', array($this, 'parse_div_style'), $this->content );
		
		$array_preg = array();
		$array_preg_replace = array();

		//Strong tag
		if (!in_array('b', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;strong&gt;(.+)&lt;/strong&gt;`isU');
			array_push($array_preg_replace, '<strong>$1</strong>');
		}
		//Italic tag
		if (!in_array('i', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;em&gt;(.+)&lt;/em&gt;`isU');
			array_push($array_preg_replace, '<em>$1</em>');
		}
		//Paragraph tag
		if (!in_array('p', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;p&gt;(.+)&lt;/p&gt;`isU');
			array_push($array_preg_replace, '<p>$1</p>');
		}
		//Link tag
		if (!in_array('url', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;a(?: title="([^"]+)")? href="(' . Url::get_wellformness_regex() . ')"&gt;(.+)&lt;/a&gt;`sU');
			array_push($array_preg_replace, '<a title="$1" href="$2">$3</a>');
			array_push($array_preg, '`&lt;a title="" href="(' . Url::get_wellformness_regex() . ')"&gt;(.+)&lt;/a&gt;`sU');
			array_push($array_preg_replace, '<a title="" href="$1">$2</a>');
		}
		//Link tag with target
		if (!in_array('url', $this->forbidden_tags))
		{

			array_push($array_preg, '`&lt;a(?: title="([^"]+)")? href="(' . Url::get_wellformness_regex() . ')" target="_blank"&gt;(.+)&lt;/a&gt;`sU');
			array_push($array_preg_replace, '<a title="$1" href="$2" target="_blank">$3</a>');
		}
		//Sub tag
		if (!in_array('sub', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;sub&gt;(.+)&lt;/sub&gt;`isU');
			array_push($array_preg_replace, '<sub>$1</sub>');
		}
		//Sup tag
		if (!in_array('sup', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;sup&gt;(.+)&lt;/sup&gt;`isU');
			array_push($array_preg_replace, '<sup>$1</sup>');
		}
		//Pre tag
		if (!in_array('pre', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;pre&gt;(.+)(<br />[\s]*)*&lt;/pre&gt;`isU');
			array_push($array_preg_replace, '<pre>$1</pre>');
		}
		//Youtube tag
		if (!in_array('youtube', $this->forbidden_tags))
		{
			$this->content = preg_replace_callback('`&lt;video class=\"youtube-player\" width="([^"]+)" height="([^"]+)" controls &gt;&lt;source src="([^"]+)"  type=\"video/mp4\" /&gt;&lt;/video&gt;`is', array($this, 'parse_youtube_tag'), $this->content);
			$this->content = preg_replace_callback('`&lt;iframe src="(?:https?:)?//www.youtube.com/(.*)" width="([^"]+)" height="([^"]+)"(?: frameborder="0")?(?: allowfullscreen="allowfullscreen")?&gt;(.+)?&lt;/iframe&gt;`isU', array($this, 'parse_youtube_tag'), $this->content);
		}
		//Flash tag
		if (!in_array('swf', $this->forbidden_tags))
		{
			$this->content = preg_replace_callback('`&lt;video(?: style="([^"]+)")?(?: controls="([^"]+)")? width="([^"]+)" height="([^"]+)"(?: controls="([^"]+)")?&gt;\s?&lt;source src="(.*).flv" /&gt;&lt;/video&gt;`is', array($this, 'parse_swf_tag'), $this->content);
		}
		//Movie tag
		if (!in_array('movie', $this->forbidden_tags))
		{
			$this->content = preg_replace_callback('`&lt;video(?: style="([^"]+)")?(?: controls="([^"]+)")? width="([^"]+)" height="([^"]+)"(?: controls="([^"]+)")?&gt;\s?&lt;source src="(.*)" /&gt;&lt;/video&gt;`is', array($this, 'parse_movie_tag'), $this->content);
		}
		//Anchor tag
		if (!in_array('anchor', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;a id="([^"]+)" class="mce-item-anchor"?&gt;&lt;/a&gt;`isU');
			array_push($array_preg_replace, '<span id="$1" class="anchor"></span>');
			array_push($array_preg, '`&lt;a(?: class="[^"]+")?(?: title="[^"]+")?(?: name="[^"]+")? id="([^"]+)"&gt;(.*)&lt;/a&gt;`isU');
			array_push($array_preg_replace, '<span id="$1" class="anchor">$2</span>');
		}
		//Title tag
		if (!in_array('title', $this->forbidden_tags))
		{
			//Title 1
			array_push($array_preg, '`&lt;h1[^&]*&gt;(.+)&lt;/h1&gt;`isU');
			array_push($array_preg_replace, '<h2 class="formatter-title">$1</h2>');
			//Title 2
			array_push($array_preg, '`&lt;h2[^&]*&gt;(.+)&lt;/h2&gt;`isU');
			array_push($array_preg_replace, '<h3 class="formatter-title">$1</h3>');
			//Title 3
			array_push($array_preg, '`&lt;h3[^&]*&gt;(.+)(<br />[\s]*)?&lt;/h3&gt;`isU');
			array_push($array_preg_replace, '<h4 class="formatter-title">$1</h4>');
			//Title 4
			array_push($array_preg, '`&lt;h4[^&]*&gt;(.+)(<br />[\s]*)?&lt;/h4&gt;`isU');
			array_push($array_preg_replace, '<h5 class="formatter-title">$1</h5>');
			//Title 5
			array_push($array_preg, '`&lt;h5[^&]*&gt;(.+)(<br />[\s]*)?&lt;/h5&gt;`isU');
			array_push($array_preg_replace, '<h6 class="formatter-title">$1</h6>');
		}
		
		//Style tag
		if (!in_array('style', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;span class="(success|question|notice|warning|error)"&gt;(.+)&lt;/span&gt;`isU');
			array_push($array_preg_replace, '<span class="$1">$2</span>');
		}
		//Align tag
		if (!in_array('align', $this->forbidden_tags))
		{
			array_push($array_preg, '`&lt;p style="text-align: (left|right|center|justify);"&gt;(.+)&lt;/p&gt;`isU');
			array_push($array_preg_replace, '<p style="text-align:$1">$2</p>' . "\n");
		}
		
		//Replacement
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);

		//List tag
		if (!in_array('list', $this->forbidden_tags))
		{
			//On doit repasser plusieurs fois pour que ça soit pris en compte (comportement un peu bizarre)
			//Par mesure de sécurité on s'arrête à 10
			$nbr_list_parsing = 0;
			while (preg_match('`&lt;o|ul&gt;(.+)&lt;/o|ul&gt;`isU', $this->content) && $nbr_list_parsing++ < 10)
			{
				$this->content = preg_replace('`&lt;ul&gt;(.+)&lt;/ul&gt;`isU', '<ul class="formatter-ul">' . "\n" .'$1</ul>', $this->content);
				$this->content = preg_replace('`&lt;ol&gt;(.+)&lt;/ol&gt;`isU', '<ol class="formatter-ol">' . "\n" .'$1</ol>', $this->content);
				$this->content = preg_replace('`&lt;li&gt;(.*)&lt;/li&gt;`isU', '<li class="formatter-li">$1</li>' . "\n", $this->content);
			}
		}

		//Tags which are useless
		$array_str = array(
		'&lt;address&gt;', '&lt;/address&gt;', '&lt;caption&gt;', '&lt;/caption&gt;', '&lt;tbody&gt;', '&lt;/tbody&gt;', '&lt;thead&gt;', '&lt;/thead&gt;', '&lt;!DOCTYPE html&gt;', '&lt;html&gt;', '&lt;head&gt;', '&lt;/head&gt;', '&lt;body&gt;', '&lt;/body&gt;', '&lt;/html&gt;'
		);

		$this->content = str_replace($array_str, '', $this->content);

		//callback replacements
		// size tag
		if (!in_array('size', $this->forbidden_tags))
		{
			//On doit repasser plusieurs fois pour que ça soit pris en compte (comportement un peu bizarre)
			//Par mesure de sécurité on s'arrête à 10
			$nbr_size_parsing = 0;
			while (preg_match('`&lt;span style="font-size: ([0-9a-z-]+);"&gt;(.+)&lt;/span&gt;`isU', $this->content) && $nbr_size_parsing++ < 10)
			{
				$this->content = preg_replace_callback('`&lt;span style="font-size: ([0-9a-z-]+);"&gt;(.+)&lt;/span&gt;`isU', array($this, 'parse_size_tag'), $this->content);
			}
		}

		//image tag
		if (!in_array('img', $this->forbidden_tags))
		{
			$this->content = preg_replace_callback('`&lt;img(?: style="([^"]+)")? src="([^"]+)"(?: alt="([^"]+)")?((?: ?[a-z]+="[^"]*")*) /&gt;`is', array($this, 'parse_img'), $this->content);
		}

		//indent tag
		if (!in_array('indent', $this->forbidden_tags))
		{
			$this->content = preg_replace_callback('`&lt;p style="padding-left: ([0-9]+)px;"&gt;(.+)&lt;/p&gt;`isU', array($this, 'parse_indent_tag'), $this->content);
		}

		//Line tag
		if (!in_array('line', $this->forbidden_tags))
		{
			$this->content = preg_replace('`&lt;hr(?: class="([^"]+)?")? /&gt;`isU', '<hr class="formatter-hr" />', $this->content);
		}

		//Quote tag
		if (!in_array('quote', $this->forbidden_tags))
		{
			$this->content = preg_replace('`(.)(?:\s*<br />\s*)?\s*&lt;blockquote&gt;\s*(?:&lt;p&gt;)?(.+)(?:<br />[\s]*)*\s*(&lt;/p&gt;)?&lt;/blockquote&gt;`isU', '$1<div class="formatter-container formatter-blockquote"><span class="formatter-title">' . $LANG['quotation'] . ' :</span><div class="formatter-content">$2</div></div>', $this->content);
		}

		//Font tag
		if (!in_array('font', $this->forbidden_tags))
		{
			//TinyMCE a un comportement un peu spécial avec la gestion des polices, il les imbrique les unes dans les autres de façon pas très logique
			//Tant qu'il existe des occurences de cette balise à travailler, on les traite
			//Sécurité : on traite au maximum 10 fois pour éviter les boucles infinies éventuelles
			$nbr_font_parsing = 0;
			while (preg_match('`&lt;span style="font-family: (.*);"&gt;(.*)&lt;/span&gt;`isU', $this->content) && $nbr_font_parsing++ < 10)
			{
				$this->content = preg_replace_callback('`&lt;span style="font-family: (.*);"&gt;(.*)&lt;/span&gt;`isU', array($this, 'parse_font_tag'), $this->content );
			}
		}
		
	}

	/**
	 * @desc Launches the table tag parsing
	 */
	private function parse_tables()
	{
		$content_contains_table = false;
		while (preg_match('`&lt;table([^&]*)&gt;(.+)&lt;/table&gt;`is', $this->content))
		{
			$this->content = preg_replace_callback('`&lt;table([^&]*)&gt;(.+)&lt;/table&gt;`isU', array($this, 'parse_table_tag'), $this->content);
			$content_contains_table = true;
		}

		if ($content_contains_table)
		{
			//Rows
			while (preg_match('`&lt;tr([^&]*)&gt;(.+)&lt;/tr&gt;`is', $this->content))
			{
				$this->content = preg_replace_callback('`&lt;tr([^&]*)&gt;(.+)&lt;/tr&gt;`isU', array($this, 'parse_row_tag'), $this->content);
			}

			//Cols
			while (preg_match('`&lt;td|h([^&]*)&gt;(.+)&lt;/td|h&gt;`is', $this->content))
			{
				$this->content = preg_replace_callback('`&lt;(td)([^&]*)&gt;(.+)?&lt;/td&gt;`isU', array($this, 'parse_col_tag'), $this->content);
				$this->content = preg_replace_callback('`&lt;(th)([^&]*)&gt;(.+)?&lt;/th&gt;`isU', array($this, 'parse_col_tag'), $this->content);
			}
		}
	}

	/**
	 * @desc Parses the smilies: it replaces their codes by the associated image.
	 * For instance :) will become <img src="urlImage" alt=".." ... />
	 */
	private function parse_smilies()
	{
		$this->content = preg_replace('`&lt;img(?: class="smiley")? title="([^"]+)" src="(.+)?/images/smileys/([^"]+)" alt="" /&gt;`i',
			'<img src="/images/smileys/$3" title="$1" alt="$1" class="smiley" />', $this->content);
		$this->content = preg_replace('`&lt;img(?: class="smiley")? title="([^"]+)" src="(.+)?/images/smileys/([^"]+)"(?: alt="([^"]+)")? /&gt;`i',
			'<img src="/images/smileys/$3" title="$1" alt="$1" class="smiley" />', $this->content);
		$this->content = preg_replace('`&lt;img class="smiley" src="(.+)?/images/smileys/([^"]+)" alt="([^"]+)" /&gt;`i',
			'<img src="/images/smileys/$2" title="$3" alt="$3" class="smiley" />', $this->content);

		//Smilies
		$smileys_cache = SmileysCache::load()->get_smileys();
		if (!empty($smileys_cache))
		{
			//Création du tableau de remplacement.
			foreach ($smileys_cache as $code => $infos)
			{
				$smiley_code[] = '`(?:(?![a-z0-9]))(?<!&[a-z]{4}|&[a-z]{5}|&[a-z]{6}|")(' . str_replace('\'', '\\\\\\\'', preg_quote($code)) . ')(?:(?![a-z0-9]))`';
				$smiley_img_url[] = '<img src="/images/smileys/' . $infos['url_smiley'] . '" alt="' . addslashes($code) . '" class="smiley" />';
			}
			$this->content = preg_replace($smiley_code, $smiley_img_url, $this->content);
		}
	}

	/**
	 * @desc Parses the formatting which is not supported by TinyMCE and is made in BBCode.
	 */
	private function parse_bbcode_tags()
	{
		global $LANG;

		$array_preg = array(
			'b' => '`\[b\](.+)\[/b\]`isU',
			'i' => '`\[i\](.+)\[/i\]`isU',
			'u' => '`\[u\](.+)\[/u\]`isU',
			's' => '`\[s\](.+)\[/s\]`isU',
			'p' => '`\[p\](.+)\[/p\]`isU',
			'pre' => '`\[pre\](.+)\[/pre\]`isU',
			'float' => '`\[float=(left|right)\](.+)\[/float\]`isU',
			'acronym' => '`\[acronym=([^\n[\]<]+)\](.*)\[/acronym\]`isU',
			'style' => '`\[style=(success|question|notice|warning|error)\](.+)\[/style\]`isU',
			'swf' => '`\[swf=([0-9]{1,3}),([0-9]{1,3})\]([a-z0-9_+.:?/=#%@&;,-]*)\[/swf\]`iU',
			'movie' => '`\[movie=([0-9]{1,3}),([0-9]{1,3})\]([a-z0-9_+.:?/=#%@&;,-]*)\[/movie\]`iU',
			'sound' => '`\[sound\]([a-z0-9_+.:?/=#%@&;,-]*)\[/sound\]`iU',
			'math' => '`\[math\](.+)\[/math\]`iU',
			'url' => '`(\s+)(' . Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_REQUIRED) . ')(\s|<+)`sU',
			'url2' => '`(\s+)(www\.' . Url::get_wellformness_regex(RegexHelper::REGEX_MULTIPLICITY_NOT_USED) . ')(\s|<+)`sU',
			'mail' => '`(\s+)([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})(\s+)`i',
			'lightbox' => '`\[lightbox=((?!javascript:)' . Url::get_wellformness_regex() . ')\]([^\n\r\t\f]+)\[/lightbox\]`isU',
		);

		$array_preg_replace = array(
			'b' => "<strong>$1</strong>",
			'i' => "<em>$1</em>",
			'u' => "<span style=\"text-decoration: underline;\">$1</span>",
			's' => "<s>$1</s>",
			'p' => "<p>$1</p>",
			'pre' => "<pre>$1</pre>",
			'float' => "<p class=\"float-$1\">$2</p>",
			'acronym' => "<abbr title=\"$1\">$2</abbr>",
			'style' => "<span class=\"$1\">$2</span>",
			'swf' => "[[MEDIA]]insertSwfPlayer('$3', $1, $2);[[/MEDIA]]",
			'movie' => "[[MEDIA]]insertMoviePlayer('$3', $1, $2);[[/MEDIA]]",
			'sound' => "[[MEDIA]]insertSoundPlayer('$1');[[/MEDIA]]",
			'math' => '[[MATH]]$1[[/MATH]]',
			'url' => "$1<a href=\"$2\">$2</a>$3",
			'url2' => "$1<a href=\"http://$2\">$2</a>$3",
			'mail' => "$1<a href=\"mailto:$2\">$2</a>$3",
			'lightbox' => '<a href="$1" data-lightbox="formatter">$2</a>',
		);

		//Suppression des remplacements des balises interdites.
		if (!empty($this->forbidden_tags))
		{
			//Si on interdit les liens, on ajoute toutes les manières par lesquelles elles peuvent passer
			if (in_array('url', $this->forbidden_tags))
			{
				$this->forbidden_tags[] = 'url2';
			}

			$other_tags = array('table', 'quote', 'hide', 'indent', 'list');
			foreach ($this->forbidden_tags as $key => $tag)
			{
				//Balise interdite : on la supprime
				if (in_array($tag, $other_tags))
				{
					$array_preg[$tag] = '`\[' . $tag . '.*\](.+)\[/' . $tag . '\]`isU';
					$array_preg_replace[$tag] = "$1";
				}
				else
				{
					unset($array_preg[$tag]);
					unset($array_preg_replace[$tag]);
				}
			}
		}

		//Remplacement : on parse les balises classiques
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);
		
		##Callbacks
		//FA tag
		if (!in_array('fa', $this->forbidden_tags))
		{
			$this->content = preg_replace_callback('`\[fa(=[a-z0-9-]+)?(,[a-z0-9-]+)?(,[a-z0-9-]+)?\]([a-z0-9-]+)\[/fa\]`iU', array($this, 'parse_fa'), $this->content);
		}

		##Nested tags
		//Hide tag
		if (!in_array('hide', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[hide]', '`\[hide\](.+)\[/hide\]`sU', '<div class="formatter-container formatter-hide" onclick="bb_hide(this)"><span class="formatter-title">' . LangLoader::get_message('hidden', 'common') . ' :</span><div class="formatter-content">$1</div></div>', $this->content);
			$this->_parse_imbricated('[hide=', '`\[hide=([^\]]+)\](.+)\[/hide\]`sU', '<div class="formatter-container formatter-hide" onclick="bb_hide(this)"><span class="formatter-title title-perso">$1 :</span><div class="formatter-content">$2</div></div>', $this->content);
		}

		//Block tag
		if (!in_array('block', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[block]', '`\[block\](.+)\[/block\]`sU', '<div class="formatter-container formatter-block">$1</div>', $this->content);
			$this->_parse_imbricated('[block style=', '`\[block style="([^"]+)"\](.+)\[/block\]`sU', '<div class="formatter-container formatter-block" style="$1">$2</div>', $this->content);
		}

		//Fieldset tag
		if (!in_array('fieldset', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[fieldset', '`\[fieldset(?: legend="(.*)")?(?: style="([^"]*)")?\](.+)\[/fieldset\]`sU', '<fieldset class="formatter-container formatter-fieldset" style="$2"><legend>$1</legend><div class="formatter-content">$3</div></fieldset>', $this->content);
		}

		//Wikipedia tag
		if (!in_array('wikipedia', $this->forbidden_tags))
		{
			$this->content = preg_replace_callback('`\[wikipedia(?: page="([^"]+)")?(?: lang="([a-z]+)")?\](.+)\[/wikipedia\]`isU', array($this, 'parse_wikipedia_links'), $this->content);
		}

		//Hide tag  //Code en doublon ?
		if (!in_array('hide', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[hide]', '`\[hide\](.+)\[/hide\]`sU', '<span class="formatter-hide">' . LangLoader::get_message('hidden', 'common') . ' :</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">$1</div></div>', $this->content);
		}

		//Quote tag (this tag is managed by TinyMCE but it can also be used in BBCode syntax)
		if (!in_array('quote', $this->forbidden_tags))
		{
			$this->_parse_imbricated('[quote]', '`\[quote\](.+)\[/quote\]`sU', '<div class="formatter-container formatter-blockquote"><span class="formatter-title">' . $LANG['quotation'] . ' :</span><div class="formatter-content">$1</div></div>', $this->content);
			$this->_parse_imbricated('[quote=', '`\[quote=([^\]]+)\](.+)\[/quote\]`sU', '<div class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">$1 :</span><div class="formatter-content">$2</div></div>', $this->content);
		}
		
		if (!in_array('feed', $this->forbidden_tags))
		{
			$this->parse_feed_tag();
		}
	}
	
	/**
	 * @desc Dissociates color and background-color in span style.
	 * @param string[] $matches The matched elements
	 * @return string The dissociated span string
	 */
	private function parse_span_color_and_background_style($matches)
	{
		return '<span style="color:' . $matches[1] . ';"><span style="background-color:' . $matches[2] . ';">' . $matches[4] . '</span></span>';
	}
	
	/**
	 * @desc Dissociates each span style.
	 * @param string[] $matches The matched elements
	 * @return string The dissociated span string
	 */
	private function parse_span_style($matches)
	{
		$span = $matches[3];
		$styles = array_merge(array($matches[1]), explode(';', $matches[2]));
		
		if (count($styles) > 1)
		{
			if (in_array('text-decoration: line-through', $styles))
				unset($styles[array_search('text-decoration: line-through', $styles)]);
			if (in_array('text-decoration: underline', $styles))
				unset($styles[array_search('text-decoration: underline', $styles)]);
		}
		
		foreach ($styles as $style)
		{
			if (mb_strstr($style, 'font-size:'))
				$style = str_replace('pt', 'px', $style);
			if (mb_strstr($style, 'font-family:'))
			{
				$font = explode(' ', $style);
				if (self::$fonts_array[$font[0]])
					$style = 'font-family: ' . self::$fonts_array[$font[0]];
				else
					$style = '';
			}
			if ($style)
				$span = '<span style="' . trim($style) . ';">' . $span . '</span>';
		}
		return $span;
	}
	
	/**
	 * @desc Dissociates each div style.
	 * @param string[] $matches The matched elements
	 * @return string The dissociated div string
	 */
	private function parse_div_style($matches)
	{
		$span = $matches[3];
		$styles = array_merge(array($matches[1]), explode(';', $matches[2]));
		
		if (count($styles) > 1)
		{
			if (in_array('text-decoration: line-through', $styles))
				unset($styles[array_search('text-decoration: line-through', $styles)]);
			if (in_array('text-decoration: underline', $styles))
				unset($styles[array_search('text-decoration: underline', $styles)]);
		}
		
		foreach ($styles as $style)
		{
			if(mb_strstr($style, 'font-size:'))
				$style = str_replace('pt', 'px', $style);
			if (mb_strstr($style, 'font-family:'))
			{
				$font = explode(' ', $style);
				if (self::$fonts_array[$font[0]])
					$style = 'font-family: ' . self::$fonts_array[$font[0]];
				else
					$style = '';
			}
			if ($style)
				$div = '<div style="' . trim($style) . ';">' . $div . '</div>';
		}
		return $div;
	}

	/**
	 * @desc Parses the wikipedia links. This tag is a BBCode one.
	 * @param string[] $matches The matched elements
	 * @return string The PHPBoost HTML syntax
	 */
	private function parse_wikipedia_links($matches)
	{
		//Langue
		$lang = LangLoader::get_message('wikipedia_subdomain', 'editor-common');
		if (!empty($matches[2]))
		{
			$lang = $matches[2];
		}

		$page_url = !empty($matches[1]) ? $matches[1] : $matches[3];

		return '<a href="http://' . $lang . '.wikipedia.org/wiki/' . $page_url . '" class="wikipedia-link">' . $matches[3] . '</a>';
	}

	/**
	 * @desc Processes the indentation tag.
	 * It's doesn't work by the same way in the two formatting syntaxes.
	 * Indeed, in the PHPBoost HTML, we nest the tags when we want to indent twice an element.
	 * However, TinyMCe doesn't uses this way, it has a parameter explaining how much times it indents.
	 * This functions has a very important and difficult treatment.
	 * @param string[] $matches The matched elements
	 * @return string The PHPBoost HTML syntax
	 */
	private function parse_indent_tag($matches)
	{
		if ((int)$matches[1] > 0)
		{
			$nbr_indent = (int)$matches[1] / 30;
			return str_repeat('<div class="indent">', $nbr_indent) . $matches[2] . str_repeat('</div>', $nbr_indent) . "\n<br />";
		}
		else
		{
			return $matches[2];
		}
	}
	
	private function parse_youtube_tag($matches)
	{
		return '[[MEDIA]]insertYoutubePlayer(\'https://www.youtube.com/' . $matches[1] . '\', ' . $matches[2] . ', ' . $matches[3] . ');[[/MEDIA]]';
	}
	
	private function parse_swf_tag($matches)
	{
		$video = '[[MEDIA]]insertSwfPlayer(\'' . $matches[6] . '.flv\', ' . $matches[3] . ', ' . $matches[4] . ');[[/MEDIA]]';
		if (!empty($matches[1]))
		{
			$style = trim($matches[1]);
			switch ($style)
			{
				case 'float: left;':
					$style = 'class="float-left"';
					break;
				case 'float: right;':
					$style = 'class="float-right"';
					break;
				case 'display: block; margin-left: auto; margin-right: auto;':
					$style = 'style="text-align:center"';
					break;
				default:
					break;
			}
			$video = '<p ' . $style . '>' . $video . '</p>';
		}
		return $video;
	}
	
	private function parse_movie_tag($matches)
	{
		$video = '[[MEDIA]]insertMoviePlayer(\'' . $matches[6] . '\', ' . $matches[3] . ', ' . $matches[4] . ');[[/MEDIA]]';
		if (!empty($matches[1]))
		{
			$style = trim($matches[1]);
			switch ($style)
			{
				case 'float: left;':
					$style = 'class="float-left"';
					break;
				case 'float: right;':
					$style = 'class="float-right"';
					break;
				case 'display: block; margin-left: auto; margin-right: auto;':
					$style = 'style="text-align:center"';
					break;
				default:
					break;
			}
			$video = '<p ' . $style . '>' . $video . '</p>';
		}
		return $video;
	}
	
	private function parse_img($matches)
	{
		$width = $height = 0;
		$alt = !empty($matches[3]) ? $matches[3] : '';
		$style = !empty($matches[1]) ? $matches[1] : '';
		
		if (preg_match('`width:.?([0-9]+)px;`iU', $style, $temp_array))
		{
			$width = $temp_array[1];
			$style = preg_replace('`width:.?' . $width . 'px;`iU', '', $style);
		}
		
		if (preg_match('`height:.?([0-9]+)px;`iU', $style, $temp_array))
		{
			$height = $temp_array[1];
			$style = preg_replace('`height:.?' . $height . 'px;`iU', '', $style);
		}
		$style = explode(';', $style);
		
		foreach (explode('" ', $matches[4] . ' ') as $raw_property)
		{
			$exp = explode('="', $raw_property);
			if (count($exp) < 2)
			{
				continue;
			}
			$value = trim($exp[1]);
			
			switch (trim($exp[0]))
			{
				case 'style':
					array_merge($style, explode(';', $value));
					break;
				case 'width':
					$width = $value;
					break;
				case 'height':
					$height = $value;
					break;
				default:
					break;
			}
		}
		
		if ($width > 0)
		{
			$style[] = 'width: ' . $width . 'px';
			
			if (!empty($height) && $width <= 600)
				$style[] = 'height: ' . $height . 'px';
		}
		
		$style = !empty($style) ? ' style="' . implode(';', array_filter($style)) . '"' : '';
		
		return '<img src="' . $matches[2] . '" alt="' . $alt . '"' . $style .' />';
	}

	protected function parse_fa($matches)
	{
		$icon2 = !empty($matches[1]) ? ' fa-' . str_replace('=', '', $matches[1]) : '';
		$icon3 = !empty($matches[2]) ? ' fa-' . str_replace(',', '', $matches[2]) : '';
		$icon4 = !empty($matches[3]) ? ' fa-' . str_replace(',', '', $matches[3]) : '';
		
		return '<i class="fa fa-' . $matches[4] . $icon2 . $icon3 . $icon4 . '"></i>';
	}

	/**
	 * @desc Processes the size tag. PHPBoost and TinyMCE don't work similary.
	 * PHPBoost needs to have a size in pixels, whereas TinyMCE explains it differently,
	 * with a name associated to each size (for instance xx-small, medium, x-large...).
	 * This method converts from TinyMCE to PHPBoost.
	 * @param string[] $matches The matched elements.
	 * @return string The good PHPBoost syntax.
	 */
	private function parse_size_tag($matches)
	{
		$size = 0;
		//We retrieve the size (in pt)
		switch ($matches[1])
		{
			case '5pt':
				$size = 5;
				break;
			case '10pt':
				$size = 10;
				break;
			case '15pt':
				$size = 15;
				break;
			case '20pt':
				$size = 20;
				break;
			case '25pt':
				$size = 25;
				break;
			case '30pt':
				$size = 30;
				break;
			case '35pt':
				$size = 35;
				break;
			case '40pt':
				$size = 40;
				break;
			case '45pt':
				$size = 45;
				break;
			default:
				$size = 0;
		}
		//If the size is known, we put the HTML code and convert the size into pixels
		if ($size > 0)
		{
			return '<span style="font-size: ' . $size . 'px;">' . $matches[2] . '</span>';
		}
		else
		{
			return $matches[2];
		}
	}

	/**
	 * @desc Transfors the TinyMCE tag because TinyMCE asks differents fonts to be sure
	 * that the user browser will have one of theses fonts.
	 * PHPBoost asks only one font, this method makes the mapping between the two systems.
	 * @param string[] $matches The matched elements
	 * @return string The parsed font tag
	 */
	private function parse_font_tag($matches)
	{
		if (!empty(self::$fonts_array[$matches[1]]))
		{
			return '<span style="font-family: ' . self::$fonts_array[$matches[1]] . ';">' . $matches[2] . '</span>';
		}
		else
		{
			return $matches[2];
		}
	}

	/**
	 * @desc Clears a string of HTML code.
	 * It replaces the paragraphes generated by TinyMCE by the br tag used in the PHPBoost HTML.
	 * @param string[] $var The matched elemets
	 * @return string The clean code.
	 */
	private static function clear_html_and_code_tag($var)
	{
		$var = preg_replace('`</p>\s*<p>`i', "\n", $var);
		$var = str_replace('<br />', "\n", $var);
		$var = TextHelper::html_entity_decode($var);
		return $var;
	}

	/**
	 * @desc Correct some TinyMCE parse problems
	 */
	private function correct()
	{
		// Trim manuel
		$this->content = preg_replace(
			array(
				'`^(\s|(?:<br />))*`i',
				'`(\s|(?:<br />))*$`i',
				// We delete the spaces which are at the begening of the line (inserted by TinyMCE to indent the HTML code)
				"`(\n<br />)[\s]*`"
			),
			array(
				'',
				'',
				'$1'
			),
			$this->content
		);
		 
		$this->content = str_replace(
			array("\n", "\r", '<br />'),
			array(' ', ' ', "\n<br />"),
			$this->content
		);
		 
		//We delete all remaining HTML tags which are not recognized by the parser
		$this->content = preg_replace(
			array(
				'`&lt;(?:p|span|div)[^&]*&gt;`is',
				'`&lt;/(?:p|span|div)*&gt;`is'
			),
			array(
				'',
				''
			),
				$this->content
		);
	}
}
?>
