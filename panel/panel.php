<?php
/**
 * panel.php
 *
 * @ copyright         (C) 2008 Alain GANDON
 *  @email                
 * @license               GPL
 *
 */

require_once('../kernel/begin.php'); 
require_once('../panel/panel_begin.php');
require_once('../kernel/header.php');

import('content/syndication/feed');

//Chargement du cache
$Cache->load('panel');

	$Template->set_filenames(array(
		'panel' => 'panel/panel.tpl'
	));

	$locations = array (10 => 'top', 20 => 'aboveleft', 30 => 'aboveright', 40 => 'center', 50 => 'belowleft', 60 => 'belowright', 70 => 'bottom');
	
	$Template->assign_vars(array(
		'THEME' => $CONFIG['theme']
		));
	
	$get_feed_menu = '';
	if (!empty($CONFIG_PANEL)) {
		foreach ($locations as $id => $name) {
			if (!empty($CONFIG_PANEL[$id])) foreach ($CONFIG_PANEL[$id] as $k => $v) {
				if (!empty($MODULES[$v['module']])) {
						import('modules/modules_discovery_service');
						$modulesLoader = new ModulesDiscoveryService();
						$module_name = $v['module'];
						$module = $modulesLoader->get_module($module_name);
						$get_feed_menu = get_feed_menu('/'.$v['module'].'/syndication.php');						
						switch ($v['type']) {
							default:
							case 'feed':
								$get_content = Feed::get_parsed($module_name, DEFAULT_FEED_NAME, 0);
								break;
							case 'home':
								if ($module->has_functionnality('get_home_page')) {
									$get_content = $module->functionnality('get_home_page');
								} else {
									$get_content = 'Panel - Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!';
								}
						}
				} else {
					$get_content = "module ".$v['module']." non install�";
				}
				$Template->assign_block_vars($name, array(
					'NAME' => $v['module'],
					'GET_FEED_MENU' => $get_feed_menu,
					'GET_CONTENT' => $get_content,
				));
			}
		}
	}
		
	$Template->pparse('panel');

require_once('../kernel/footer.php'); 

?>