<?php
/*##################################################
 *                             common.php
 *                            -------------------
 *   begin                : December 5, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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


 ####################################################
 #						French						#
 ####################################################

$cache = PHPBoostOfficialCache::load();

$lang['module_title'] = 'PHPBoost - Site Officiel';
$lang['site_description'] = 'Cr�ez votre site Internet facilement en moins de 5 minutes';
$lang['site_slide_description'] = 'PHPBoost est un syst�me de gestion de contenu (CMS) fran�ais et libre, qui existe depuis 2005, vous permettant de cr�er facilement votre site Internet. Tr�s complet en terme de fonctionnalit�s il est cependant simple � utiliser. A l\'usage il s\'av�re �tre un CMS fiable et robuste, optimis� pour le r�f�rencement et personnalisable � souhait.';
$lang['versions'] = 'Versions successives de PHPBoost';
$lang['versions.explain'] = 'Permet de mettre � jour automatiquement la page de t�l�chargements du site';
$lang['major_version_number'] = 'Num�ro de version majeure';
$lang['minor_version_number'] = 'Num�ro de version mineure';
$lang['minimal_php_version'] = 'Version PHP minimale';

$lang['phpboost_features'] = 'Fonctionnalit�s de PHPBoost';
$lang['phpboost_features.explain'] = 'D�couvrir les fonctionnalit�s de PHPBoost';
$lang['last_modules'] = 'Les derniers modules';
$lang['last_themes'] = 'Les derniers th�mes';
$lang['modules_for_phpboost'] = 'Modules pour PHPBoost';
$lang['themes_for_phpboost'] = 'Th�mes pour PHPBoost';
$lang['discover_other_modules'] = 'D�couvrir les autres modules';
$lang['discover_other_themes'] = 'D�couvrir les autres th�mes';
$lang['version'] = 'Version';
$lang['try'] = 'Essayer';
$lang['demo'] = 'D�monstration de PHPBoost';
$lang['demo.website'] = 'Site d�mo';
$lang['download'] = 'T�l�charger';
$lang['download.phpboost'] = 'T�l�charger PHPBoost';
$lang['download.display_tree'] = 'Parcourir l\'arborescence';
$lang['download.display_root_cat'] = 'Afficher l\'accueil des t�l�chargements';
$lang['download.updates_phpboost'] = 'Mises � jour PHPBoost';
$lang['download.modules_phpboost'] = 'Modules PHPBoost';
$lang['download.themes_phpboost'] = 'Th�mes PHPBoost';
$lang['download.module_category.description'] = 'Modules compatibles avec PHPBoost';
$lang['download.theme_category.description'] = 'Th�mes compatibles avec PHPBoost';
$lang['download.updates'] = 'Mises � jour';
$lang['download.updates.description'] = 'Mise � jour et migration';
$lang['download.compatible_modules'] = 'Modules compatibles';
$lang['download.compatible_modules.description'] = 'Donnez de nouvelles fonctionnalit�s � votre site.';
$lang['download.compatible_themes'] = 'Th�mes compatibles';
$lang['download.compatible_themes.description'] = 'Trouvez la bonne entit� graphique pour votre site.';
$lang['download.pdk_version'] = 'La version pour d�veloppeurs';
$lang['download.pdk_version_txt'] = 'T�l�charger la version pour d�veloppeurs (PDK)';
$lang['download.last_version_pdk'] = $cache->get_last_version_major_version_number() . 'PDK';
$lang['download.previous_version_pdk'] = $cache->get_previous_version_major_version_number() . 'PDK';
$lang['download.last_major_version_number'] = $cache->get_last_version_major_version_number();
$lang['download.phpboost_last_major_version_number'] = 'PHPBoost ' . $cache->get_last_version_major_version_number();
$lang['download.last_complete_version_number'] = $cache->get_last_version_major_version_number() . '.' . $cache->get_last_version_minor_version_number();
$lang['download.last_minimal_php_version'] = 'PHP ' . $cache->get_last_version_minimal_php_version();
$lang['download.last_version_name'] = $cache->get_last_version_name();
$lang['download.last_version_download_link'] = $cache->get_last_version_download_link();
$lang['download.last_version_updates_cat_link'] = $cache->get_last_version_updates_cat_link();
$lang['download.last_version_pdk_link'] = $cache->get_last_version_pdk_link();
$lang['download.last_version_modules_cat_link'] = $cache->get_last_version_modules_cat_link();
$lang['download.last_version_themes_cat_link'] = $cache->get_last_version_themes_cat_link();
$lang['download.previous_major_version_number'] = $cache->get_previous_version_major_version_number();
$lang['download.previous_complete_version_number'] = $cache->get_previous_version_major_version_number() . '.' . $cache->get_previous_version_minor_version_number();
$lang['download.previous_minimal_php_version'] = $cache->get_previous_version_minimal_php_version();
$lang['download.previous_version_name'] = $cache->get_previous_version_name();
$lang['download.previous_version_download_link'] = $cache->get_previous_version_download_link();
$lang['download.previous_version_updates_cat_link'] = $cache->get_previous_version_updates_cat_link();
$lang['download.previous_version_pdk_link'] = $cache->get_previous_version_pdk_link();
$lang['download.previous_version_modules_cat_link'] = $cache->get_previous_version_modules_cat_link();
$lang['download.previous_version_themes_cat_link'] = $cache->get_previous_version_themes_cat_link();
$lang['download.header.title'] = 'T�l�charger PHPBoost';
$lang['download.header.description'] = 'Bienvenue sur la page de t�l�chargement de PHPBoost.';
$lang['download.page_content.title'] = 'Vous trouverez sur cette page';
$lang['download.page_content.last_stable_version'] = 'La derni�re version stable : PHPBoost ' . $cache->get_last_version_major_version_number() . ' et sa version PDK destin�e aux d�veloppeurs';
$lang['download.page_content.previous_version'] = 'L\'ancienne version PHPBoost ' . $cache->get_previous_version_major_version_number();
$lang['download.page_content.updates'] = 'Mise � jour des versions ' . $cache->get_previous_version_major_version_number() . ' et ' . $cache->get_last_version_major_version_number();
$lang['download.page_content.updates_scripts'] = 'Les scripts de migration pour passer votre site sous PHPBoost ' . $cache->get_previous_version_major_version_number() . ' et ' . $cache->get_last_version_major_version_number();
$lang['download.last_version.description'] = 'La version stable de PHPBoost. A utiliser pour b�n�ficier de toutes les derni�res fonctionnalit�s implant�es.';
$lang['download.previous_version.description'] = 'Pour les nostalgiques, ou pour les personnes ayant besoin de r�parer une version ' . $cache->get_previous_version_major_version_number() . ' encore en production.';

$lang['news.phpboost.rss'] = 'Flux RSS des actualit�s de PHPBoost';
$lang['news.phpboost'] = 'L\'actualit� de PHPBoost';
$lang['news.previous_news'] = 'Les news pr�c�dentes';
$lang['news.category.description'] = 'Toutes les news concernant PHPBoost';

?>
