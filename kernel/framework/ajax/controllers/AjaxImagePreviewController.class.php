<?php
/*##################################################
 *                      	AjaxImagePreviewController.class.php
 *                            -------------------
 *   begin                : June 25, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AjaxImagePreviewController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$status = 200;
		$url = '';
		$image_to_check = $request->get_string('image', '');
		$image = new Url($image_to_check);
		
		if ($image_to_check)
		{
			if (function_exists('get_headers') && ($file_headers = get_headers($image->relative(), true)) && isset($file_headers[0]))
			{
				if(preg_match('/^HTTP\/[12]\.[01] (\d\d\d)/', $file_headers[0], $matches))
					$status = (int)$matches[1];
			}
		}
		
		if ($status == 200)
			$url = $image->absolute();
		
		return new JSONResponse(array('url' => $url));
	}
}
?>
