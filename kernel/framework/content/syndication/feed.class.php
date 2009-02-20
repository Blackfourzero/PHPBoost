<?php
/*##################################################
 *                            feed.class.php
 *                         -------------------
 *   begin                : April 21, 2008
 *   copyright            : (C) 2005 Lo�c Rouchon
 *   email                : horn@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('FEEDS_PATH', PATH_TO_ROOT . '/cache/syndication/');
define('DEFAULT_FEED_NAME', 'master');
define('ERROR_GETTING_CACHE', 'Error regenerating and / or retrieving the syndication cache of the %s (%s)');

import('functions', LIB_IMPORT);
import('content/syndication/feed_data');

class Feed
{
    ## Public Methods ##
    function Feed($module_id, $name = DEFAULT_FEED_NAME, $id_cat = 0)
    {
        $this->module_id = $module_id;
        $this->name = $name;
        $this->id_cat = $id_cat;
    }

    function load_data($data) { $this->data = $data; }
    function load_file($url) { }

    // Export the feed as a string parsed by the <$tpl> template
    function export($template = false, $number = 10, $begin_at = 0)
    {
        if ($template === false)    // A specific template is used
            $tpl = $this->tpl->copy();
        else
            $tpl = $template->copy();
        
        global $User, $MODULES;
        if ($User->check_auth($MODULES[$this->module_id]['auth'], ACCESS_MODULE))
        {
            if (!empty($this->data))
            {
                $tpl->assign_vars(array(
                    'DATE' => $this->data->get_date(),
                    'DATE_RFC822' => $this->data->get_date_rfc822(),
                    'DATE_RFC3339' => $this->data->get_date_rfc3339(),
                    'TITLE' => $this->data->get_title(),
                    'U_LINK' => $this->data->get_link(),
                    'HOST' => $this->data->get_host(),
                    'DESC' => $this->data->get_desc(),
                    'LANG' => $this->data->get_lang()
                ));

                $items = $this->data->subitems($number, $begin_at);
                foreach ($items as $item)
                {
                    $tpl->assign_block_vars('item', array(
                        'TITLE' => $item->get_title(),
                        'U_LINK' => $item->get_link(),
                        'U_GUID' => $item->get_guid(),
                        'DESC' => $item->get_desc(),
                        'DATE' => $item->get_date(),
                        'DATE_RFC822' => $item->get_date_rfc822(),
                        'DATE_RFC3339' => $item->get_date_rfc3339(),
                        'C_IMG' => ($item->get_image_url() != '') ? true : false,
                        'U_IMG' => $item->get_image_url()
                    ));
                }
            }
        }
        return $tpl->parse(TEMPLATE_STRING_MODE);
    }

    function read()
    {
        if ($this->is_in_cache())
        {
			if (!DEBUG) {
				$include = @include($this->get_cache_file_name());
			} else {
				$include = include($this->get_cache_file_name());
			}
            if ($include)
            {
                $this->data = $feed_object;
                return $this->export();
            }
        }
        return '';
    }

    function cache()
    {
        FEED::update_cache($this->module_id, $this->name, $this->data, $this->id_cat);
    }

    function is_in_cache() { return file_exists($this->get_cache_file_name()); }
   
    function get_cache_file_name() { return FEEDS_PATH . $this->module_id . '_' . $this->name . '_' . $this->id_cat . '.php'; }
   
    ## Private Methods ##
    ## Private attributes ##
    var $module_id = '';        // Module ID
    var $id_cat = 0;        // ID cat
    var $name = '';             // Feed Name
    var $str = '';              // The feed as a string
    var $tpl = null;            // The feed Template to use
    var $data = null;           // The data structure

    ## Statics Methods ##

    // clear the cache
    /*static*/ function clear_cache($module_id = false)
    {
        import('io/filesystem/folder');
        $folder = new Folder(FEEDS_PATH, OPEN_NOW);
       
        $files = null;
        if ($module_id !== false)  // Clear only this module cache
            $files = $folder->get_files('`.+/' . $module_id . '_.*`');
        else                        // Clear the whole cache
            $files = $folder->get_files();
       
        foreach ($files as $file)
            $file->delete();
    }

    /*static*/ function update_cache($module_id, $name, &$data, $idcat = 0)
    {
        import('io/filesystem/file');
        $file = new File(FEEDS_PATH . $module_id . '_' . $name . '_' . $idcat . '.php', WRITE);
        $file->write('<?php $feed_object = unserialize(' . var_export($data->serialize(), true) . '); ?>');
        $file->close();
    }

    /*static*/ function get_parsed($module_id, $name = DEFAULT_FEED_NAME, $idcat = 0, $tpl = false, $number = 10, $begin_at = 0)
    {
        // Choose the correct template
        if (is_object($tpl) and strtolower(get_class($tpl)) == 'template')
            $template = $tpl->copy();
        else
        {
            import('io/template');
            $template = new Template($module_id . '/framework/content/syndication/feed.tpl');
            if (gettype($tpl) == 'array')
                $template->assign_vars($tpl);
        }
       
        // Get the cache content or recreate it if not existing
		$feed_data_cache_file = FEEDS_PATH . $module_id . '_' . $name . '_' . $idcat . '.php';
		if (!DEBUG) {
			$result = @include($feed_data_cache_file);
		} else {
			if (file_exists($feed_data_cache_file)) {
				$result = include($feed_data_cache_file);
			} else {
				$result = FALSE;
			}
		}
        if ($result === false)
        {
            import('modules/modules_discovery_service');
            $modules = new ModulesDiscoveryService();
            $module = $modules->get_module($module_id);
            
            if ( $module->got_error() || !$module->has_functionnality('get_feed_data_struct') )
            {   // If the module is not installed or doesn't have the get_feed_data_struct
                // functionnality we break
                return '';
            }
            
            $data = $module->functionnality('get_feed_data_struct', $idcat);
            if (!$module->got_error())
            {
                Feed::update_cache($module_id, $name, $data, $idcat);
            }
        }
		if (!DEBUG) {
			$result = @include($feed_data_cache_file);
		} else {
			if (file_exists($feed_data_cache_file)) {
				$result = include($feed_data_cache_file);
			} else {
				$result = FALSE;
			}
		}
        if ( $result === false)
        {
            user_error(sprintf(ERROR_GETTING_CACHE, $module_id, $idcat), E_USER_WARNING);
            return '';
        }
        
        $feed = new Feed($module_id, $name);
        $feed->load_data($feed_object);
        return $feed->export($template, $number, $begin_at);
    }
}
?>
