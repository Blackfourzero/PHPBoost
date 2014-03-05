<?php
/*##################################################
 *                             install.php
 *                            -------------------
 *   begin                : April 09, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software, you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program, if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						French						#
 ####################################################

$lang['cat.name'] = 'Test';
$lang['cat.description'] = 'Cat�gorie de test';
$lang['news.title'] = 'Votre site sous PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version();
$lang['news.content'] = 'Votre site boost� par PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version() . ' est bien install�. Afin de vous aider � le prendre en main, 
l\'accueil de chaque module contient un message pour vous guider vos premiers pas. Voici �galement quelques recommandations suppl�mentaires que nous vous proposons de lire avec attention : <br />
<br />
<h4 class="stitle1">N\'oubliez pas de supprimer le r�pertoire "install"</h4><br />
<br />
Supprimez le r�pertoire /install � la racine de votre site pour des raisons de s�curit� afin que personne ne puisse recommencer l\'installation.<br />
<br />
<h4 class="stitle1">Administrez votre site</h4><br />
<br />
Acc�dez au <a href="/admin/admin_index.php">panneau d\'administration de votre site</a> afin de le param�trer comme vous le souhaitez!  Pour cela : <br />
<br />
<ul class="bb-ul">
<li class="bb-li"><a href="/admin/admin_maintain.php">Mettez votre site en maintenance</a> en attendant que vous le configuriez � votre guise.
</li><li class="bb-li">Rendez vous � la <a href="/admin/config/?url=/general/">Configuration g�n�rale du site</a>.
</li><li class="bb-li"><a href="/admin/modules/?url=/installed/">Configurez les modules</a> disponibles et donnez leur les droits d\'acc�s (si vous n\'avez pas install� le pack complet, tous les modules sont disponibles sur le site de <a href="http://www.phpboost.com">phpboost.com</a> dans la section t�l�chargement).
</li><li class="bb-li"><a href="/admin/content/?url=/config/">Choisissez le langage de formatage du contenu</a> par d�faut du site.
</li><li class="bb-li"><a href="/admin/member/?url=/config/">Configurez l\'inscription des membres</a>.
</li><li class="bb-li"><a href="/admin/themes/?url=/installed/">Choisissez le th�me par d�faut de votre site</a> pour changer l\'apparence de votre site (vous pouvez en obtenir d\'autres sur le site de <a href="http://www.phpboost.com">phpboost.com</a>).
</li><li class="bb-li">Avant de donner l\'acc�s de votre site � vos visiteurs, prenez un peu de temps pour y mettre du contenu.
</li><li class="bb-li">Enfin <a href="/admin/admin_maintain.php">d�sactivez la maintenance</a> de votre site afin qu\'il soit visible par vos visiteurs.<br />
</li></ul><br />
<br />
<h4 class="stitle1">Que faire si vous rencontrez un probl�me ?</h4><br />
<br />
N\'h�sitez pas � consulter <a href="http://www.phpboost.com/wiki/wiki.php">la documentation de PHPBoost</a> ou � poser vos question sur le <a href="http://www.phpboost.com/forum/index.php">forum d\'entraide</a>.<br /> <br />
<br />
<p class="float-right">Toute l\'�quipe de PHPBoost vous remercie d\'utiliser son logiciel pour cr�er votre site web !</p>';
?>