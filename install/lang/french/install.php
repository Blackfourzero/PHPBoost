<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : May 30, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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


 ####################################################
#                      French                      #
 ####################################################


$lang = array(
	'chmod.cache.notWritable' => '<h1>Installation de PHPBoost</h1>
<p><strong>Attention</strong> : les dossiers /cache et /cache/tpl n\'existent pas ou ne sont pas inscriptibles. Veuillez les créer et/ou changer leur CHMOD (mettre 777) pour pouvoir lancer l\'installation.</p>
<p>Une fois ceci fait, actualisez la page pour continuer ou cliquez <a href="">ici</a>.</p>',

	//Variables générales
	'installation.title' => 'Installation de PHPBoost',
	'steps.list' => 'Liste des étapes',
	'step.list.introduction' => 'Préambule',
	'step.list.license' => 'Licence',
	'step.list.server' => 'Configuration du serveur',
	'step.list.database' => 'Configuration base de données',
	'step.list.website' => 'Configuration du site',
	'step.list.admin' => 'Compte administrateur',
	'step.list.end' => 'Fin de l\'installation',
	'installation.progression' => 'Progression de l\'installation',
	'appendices' => 'Annexes',
	'documentation' => 'Documentation',
	'documentation.link' => 'http://www.phpboost.com/wiki/installer-phpboost',
	'installation.restart' => 'Recommencer l\'installation',
	'installation.confirmRestart' => 'Etes-vous certain de vouloir recommencer l\'installation ?',
	'language.change' => 'Changer de langue',
	'change' => 'Changer',
    'step.previous' => 'Etape précédente',
    'step.next' => 'Etape suivante',
    'yes' => 'Oui',
    'no' => 'Non',
	'unknown' => 'Inconnu',
	'generatedBy' => 'Généré par %s',
	'poweredBy' => 'Boosté par',
	'phpboost.link' => 'Lien vers le site officiel de PHPBoost CMS',
	'phpboost.rights' => '',
	'phpboost.slogan' => 'Créez votre site internet facilement et gratuitement !',
	'phpboost.logo' => 'Logo PHPBoost',

//Introduction
	'step.welcome.title' => 'Préambule',
	'step.welcome.message' => 'Bienvenue dans l\'assistant d\'installation de PHPBoost',
    'step.welcome.explanation' => '<p>Merci d\'avoir accordé votre confiance à PHPBoost pour créer votre site web.</p>
<p>Pour installer PHPBoost vous devez disposer d\'un minimum d\'informations concernant votre hébergement qui devraient être fournies par votre hébergeur. L\'installation est entièrement automatisée, elle ne devrait prendre que quelques minutes. Cliquez sur la flèche ci-dessous pour démarrer le processus d\'installation.</p>
<p>Cordialement, l\'équipe PHPBoost</p>',
	'step.welcome.distribution' => 'Distribution :distribution',
	'step.welcome.distribution.explanation' => '<p>Il existe différentes distributions de PHPBoost permettant à l\'utilisateur d\'obtenir automatiquement une configuration appropriée à ses besoins. Une distribution contient des modules ainsi que quelques paramétrages du système (noyau).</p><p>PHPBoost va s\'installer selon la configuration de cette distribution, vous pourrez évidemment par la suite modifier sa configuration et ajouter ou supprimer des modules.</p>',
	'start_install' => 'Commencer l\'installation',

//licence
	'step.license.title' => 'Licence',
	'step.license.agreement' => 'Acceptation des termes de la licence',
	'step.license.require.agreement' => '<p>Vous devez accepter les termes de la licence GNU/GPL pour installer PHPBoost.</p><p>Vous trouverez une traduction non officielle de cette licence en français <img src="../images/stats/countries/fr.png" alt="Français" /> <a href="http://org.rodage.com/gpl-3.0.fr.txt">ici</a>.</p>',
	'step.license.terms.title' => 'Termes de la licence',
	'step.license.please_agree' => 'J\'ai pris connaissance des termes de la licence et je les accepte',
	'step.license.submit.alert' => 'Vous devez accepter la licence en cochant le formulaire associé pour pouvoir continuer !',

//Configuration du serveur
	'step.server.title' => 'Vérification de la configuration du serveur',
	'step.server.explanation' => '<p>Avant de commencer les étapes d\'installation de PHPBoost, la configuration de votre serveur va être vérifiée afin d\'établir sa compatibilité avec PHPBoost.</p>
<div class="message-helper notice">Veillez à ce que chaque condition obligatoire soit vérifiée sans quoi vous risquez d\'avoir des problèmes en utilisant le logiciel.</div>
<p>En cas de problème n\'hésitez pas à poser vos questions sur le <a href="http://www.phpboost.com/forum/">forum de support</a>.</p>',
	'php.version' => 'Version de PHP',
	'php.version.check' => 'PHP supérieur à :min_php_version',
	'php.version.check.explanation' => '<span style="font-weight:bold;color:red;">Obligatoire :</span> Pour faire fonctionner PHPBoost, votre serveur doit être équipé d\'une version supérieure ou égale à PHP :min_php_version. Sans cela il vous sera impossible de le faire fonctionner correctement, contactez votre hébergeur ou migrez vers un serveur plus récent.',
	'php.extensions' => 'Extensions',
	'php.extensions.check' => 'L\'activation de ces extensions permet d\'apporter des fonctionnalités supplémentaires mais n\'est en aucun cas indispensable (sauf la librairie MBstring).',
	'php.extensions.check.gdLibrary' => 'Librairie GD',
	'php.extensions.check.gdLibrary.explanation' => 'Librairie utilisée pour générer des images. Utile par exemple pour la protection anti robots, ou les diagrammes des statistiques du site. Certains modules peuvent également s\'en servir.',
	'php.extensions.check.curlLibrary' => 'Librairie Curl',
	'php.extensions.check.curlLibrary.explanation' => 'Librairie utilisée pour récupération d\'éléments distants. Nécessaire pour faire fonctionner l\'authentification externe par exemple.',
	'php.extensions.check.mbstringLibrary' => 'Librairie MBstring',
	'php.extensions.check.mbstringLibrary.explanation' => 'Librairie utilisée pour la gestion des caractères UTF-8. Obligatoire pour avoir un site fonctionnel.',
	'php.extensions.check.mbstringLibrary.error' => 'L\'extension php <b>mbstring</b> n\'est pas activée. Veuillez l\'activer ou contactez votre hébergeur avant de pouvoir poursuivre l\'installation.',
	'server.urlRewriting' => 'URL Rewriting',
	'server.urlRewriting.explanation' => 'Réécriture des adresses des pages qui les rend plus lisibles et plus propices au référencement sur les moteurs de recherche',
	'folders.chmod' => 'Autorisations des dossiers',
	'folders.chmod.check' => '<span style="font-weight:bold;color:red;">Obligatoire :</span> PHPBoost nécessite que certains dossiers soient inscriptibles. Si votre serveur le permet, leurs autorisations sont changées de façon automatique. Cependant certains serveurs empêchent la modification automatique des autorisations, il faut donc faire la manipulation manuellement, pour cela contactez votre hébergeur.',
	'folders.chmod.refresh' => 'Revérifier les dossiers',
	'folder.exists' => 'Existant',
	'folder.doesNotExist' => 'Inexistant',
	'folder.isWritable' => 'Inscriptible',
	'folder.isNotWritable' => 'Non inscriptible',
	'folders.chmod.error' => 'Les répertoires ne sont pas tous existants et/ou inscriptibles. Merci de le faire à la main pour pouvoir continuer.',

//Base de données
    'step.dbConfig.title' => 'Configuration base de données',
	'db.parameters.config' => 'Paramètres de connexion à la base de données',
	'db.parameters.config.explanation' => '<p>Cette étape permet de générer le fichier de configuration qui retiendra les identifiants de connexion à votre base de données. Les tables permettant de faire fonctionner PHPBoost seront automatiquement créées lors de cette étape. Si vous ne connaissez pas les informations ci-dessous, contactez votre hébergeur qui vous les transmettra.</p>',
	'dbms.parameters' => 'Paramètres d\'accès au <acronym title="Système de Gestion de Base de Données">SGBD</acronym>',
	'dbms.host' => 'Nom de l\'hôte',
	'dbms.host.explanation' => 'URL du serveur qui gère la base de données, <em>localhost</em> la plupart du temps.',
    'dbms.port' => 'Port du serveur',
    'dbms.port.explanation' => 'Port du serveur qui gère la base de données, <em>3306</em> la plupart du temps.',
	'dbms.login' => 'Identifiant',
	'dbms.login.explanation' => 'Fourni par l\'hébergeur',
	'dbms.password' => 'Mot de passe',
	'dbms.password.explanation' => 'Fourni par l\'hébergeur',
	'schema.properties' => 'Propriétés de la base de données',
	'schema' => 'Nom de la base de données',
	'schema.explanation' => 'Fourni par l\'hébergeur. Si cette base n\'existe pas, PHPBoost essaiera de la créer si la configuration le lui permet.',
	'schema.tablePrefix' => 'Préfixe des tables',
	'schema.tablePrefix.explanation' => 'Par défaut <em>phpboost_</em>. A changer si vous souhaitez installer plusieurs fois PHPBoost dans la même base de données.',
	'db.config.check' => 'Essayer',
	'db.connection.success' => 'La connexion à la base de données a été effectuée avec succès. Vous pouvez poursuivre l\'installation',
	'db.connection.error' => 'Impossible de se connecter à la base de données. Merci de vérifier vos paramètres.',
	'db.creation.error' => 'La base de données que vous avez indiquée n\'existe pas et le système n\'a pas l\'autorisation de la créer.',
	'db.unknown.error' => 'Une erreur inconnue a été rencontrée.',
	'phpboost.alreadyInstalled.alert' => 'Il existe déjà une installation de PHPBoost sur cette base de données avec ce préfixe. Si vous continuez, ces tables seront supprimées et vous perdrez certainement des données.',
	'db.required.host' => 'Vous devez renseigner le nom de l\'hôte !',
	'db.required.port' => 'Vous devez renseigner le port !',
	'db.required.login' => 'Vous devez renseigner l\'identifiant de connexion !',
	'db.required.schema' => 'Vous devez renseigner le nom de la base de données !',
	'phpboost.alreadyInstalled' => 'Installation existante',
	'phpboost.alreadyInstalled.explanation' => '<p>La base de données sur laquelle vous souhaitez installer PHPBoost contient déjà une installation de PHPBoost.</p>
<p>Si vous effectuez l\'installation sur cette base de données avec cette configuration, vous écraserez les données présentes actuellement. Si vous voulez installer deux fois PHPBoost sur la même base de données, utilisez des préfixes différents.</p>',
	'phpboost.alreadyInstalled.overwrite' => 'Je souhaite écraser l\'installation de PHPBoost déjà existante',
	'phpboost.alreadyInstalled.overwrite.confirm' => 'Vous devez confirmer l\'écrasement de la précédente installation',

//configuraton du site
    'step.websiteConfig.title' => 'Configuration du serveur',
	'websiteConfig' => 'Configuration du site',
	'websiteConfig.explanation' => '<p>La configuration de base du site va être créée dans cette étape afin de permettre à PHPBoost de fonctionner. Sachez cependant que toutes les données que vous allez rentrer seront ultérieurement modifiables dans le panneau d\'administration dans la rubrique configuration du site. Vous pourrez dans ce même panneau renseigner davantage d\'informations facultatives à propos de votre site.</p>',
	'website.yours' => 'Votre site',
	'website.host' => 'Adresse du site',
	'website.host.explanation' => 'De la forme http://www.phpboost.com',
	'website.path' => 'Chemin de PHPBoost',
	'website.path.explanation' => 'Vide si votre site est à la racine du serveur, de la forme /dossier sinon',
	'website.name' => 'Nom du site',
	'website.slogan' => 'Slogan du site',
    'website.description' => 'Description du site',
    'website.description.explanation' => '(facultatif) Utile pour le référencement dans les moteurs de recherche',
    'website.timezone' => 'Fuseau horaire du site',
	'website.timezone.explanation' => 'La valeur par défaut est celle correspondant à la localisation de votre serveur. Pour la France, il s\'agit d\'<strong>Europe/Paris</strong>. Vous pourrez changer cette valeur par la suite dans le panneau d\'administration.',
	'website.host.required' => 'Vous devez entrer l\'adresse de votre site !',
	'website.name.required' => 'Vous devez entrer le nom de votre site !',
	'website.host.warning' => 'L\'adresse du site que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir cette adresse ?',
	'website.path.warning' => 'Le chemin du site sur le serveur que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir ce chemin ?',
//	'site_config_mail_signature' => 'Cordialement, l\'équipe du site.',

//administration
    'step.admin.title' => 'Compte administrateur',
	'adminCreation' => 'Création du compte administrateur',
	'adminCreation.explanation' => '<p>Ce compte donne accès au panneau d\'administration par lequel vous configurerez votre site. Vous pourrez modifier les informations concernant ce compte par la suite en consultant votre profil.</p>
<p>Par la suite, il sera possible de donner à plusieurs personnes le statut d\'administrateur, ce compte est celui du premier administrateur, sans lequel vous ne pourriez pas gérer le site.</p>',
	'admin.account' => 'Compte administrateur',
	'admin.login' => 'Pseudo',
	'admin.login.explanation' => 'Minimum 3 caractères',
	'admin.password' => 'Mot de passe',
	'admin.password.explanation' => 'Minimum :number caractères',
	'admin.password.repeat' => 'Répéter le mot de passe',
	'admin.email' => 'Courrier électronique',
	'admin.email.explanation' => 'Doit être valide pour recevoir le code de déverrouillage',
	'admin.login.required' => 'Vous devez choisir un pseudo',
	'admin.login.length' => 'Votre pseudo est trop court (3 caractères minimum)',
	'admin.password.required' => 'Vous devez entrer un mot de passe',
	'admin.password.length' => 'Votre mot de passe est trop court (:number caractères minimum)',
	'admin.confirmPassword.required' => 'Vous devez confirmer votre mot de passe',
	'admin.passwords.mismatch' => 'Les deux mots de passe que vous avez entrés ne correspondent pas',
	'admin.email.required' => 'Vous devez entrer une adresse de courier électronique',
	'admin.email.invalid' => 'L\'adresse de courier électronique que vous avez entrée n\'a pas une forme correcte',
	'admin.connectAfterInstall' => 'Me connecter à la fin de l\'installation',
	'admin.autoconnect' => 'Rester connecté systématiquement à chacune de mes visites',
	'admin.created.email.object' => 'Identifiants de votre site créé avec PHPBoost (message à conserver)',
	'admin.created.email.unlockCode' => 'Cher %s,

Tout d\'abord, merci d\'avoir choisi PHPBoost pour réaliser votre site, nous espérons qu\'il répondra au mieux à vos besoins. Pour tout problème n\'hésitez pas à vous rendre sur le forum http://www.phpboost.com/forum/

Voici vos identifiants (ne les perdez pas, ils vous seront utiles pour administrer votre site et ne pourront plus être récupérés).

Identifiant: %s
Si vous perdez votre mot de passe, vous pouvez en générer un nouveau à partir de ce lien %s

Cordialement l\'équipe PHPBoost.',

//Fin de l'installation
	'congratulations' => 'Félicitations !',
    'step.finish.title' => 'Fin de l\'installation',
	'finish.message' => '<fieldset>
                            <legend>PHPBoost est désormais installé !</legend>
							<div class="fielset-inset">
								<p class="message-helper success">L\'installation de PHPBoost s\'est déroulée avec succès. L\'équipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.</p>
								<p>Nous vous conseillons de vous tenir au courant de l\'évolution de PHPBoost via le site de la communauté francophone, <a href="http://www.phpboost.com">www.phpboost.com</a>. Vous serez automatiquement averti dans le panneau d\'administration de l\'arrivée de nouvelles mises à jour. Il est fortement conseillé de tenir votre système à jour afin de profiter des dernières nouveautés et de corriger les éventuelles failles ou erreurs.</p>
								<p class="message-helper warning">Par mesure de sécurité nous vous conseillons fortement de supprimer le dossier <b>install</b> et tout ce qu\'il contient. Des personnes mal intentionnées pourraient relancer le script d\'installation et écraser certaines de vos données ! Une option vous sera proposée une fois connecté sur le site pour effectuer cette suppression.</p>
								<p>N\'oubliez pas de consulter la <a href="http://www.phpboost.com/wiki/">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost ainsi que la <a href="http://www.phpboost.com/faq/"><acronym title="Foire Aux Questions">FAQ</acronym></a> qui répond aux questions les plus fréquentes.</p>
								<p>En cas de problème, rendez-vous sur le <a href="http://www.phpboost.com/forum/">forum du support de PHPBoost</a>.</p>
							</div>
                        </fieldset>
                        <fieldset>
                            <legend>Remerciements</legend>
							<div class="fielset-inset">
								<h2>Membres de la communauté</h2>
								<p>Merci à tous les membres de la communauté qui nous encouragent au quotidien et contribuent à la qualité du logiciel que ce soit en suggérant des nouvelles fonctionnalités ou en signalant des dysfonctionnements, ce qui permet d\'aboutir entre autres à un CMS stable et efficace.</p>
								<p>Merci aux membres des équipes de PHPBoost et particulièrement à <strong>benflovideo</strong> de l\'équipe communication, <strong>PaperToss</strong>, <strong>mipel</strong>, <strong>olivierb</strong> et <strong>xela</strong> pour la documentation, <strong>ElenWii</strong> et <strong>babsolune</strong> pour les graphismes, <strong>benflovideo</strong> et <strong>mipel</strong> pour la modération de la communauté et <strong>janus57</strong> pour l\'appui aux développements et à l\'aide de la communauté sur le forum.</p>
								<h2>Projets</h2>
								<p>PHPBoost utilise différents outils afin d\'élargir ses fonctionnalités sans augmenter trop le temps de développement. Ces outils sont tous libres, distribués sous la licence GNU/GPL pour la plupart.</p>
								<ul>
									<li><a href="https://notepad-plus-plus.org/fr">Notepad++</a> et <a href="http://sublimetext.com">Sublime Text</a> : Editeurs de texte puissants utilisés pour le développement de PHPBoost.</li>
									<li><a href="http://www.phpconcept.net/pclzip/">PCLZIP</a> : Librairie permettant de travailler sur des archives au format Zip.</li>
									<li><a href="http://www.xm1math.net/phpmathpublisher/index_fr.html">PHPMathPublisher</a> : Ensemble de fonctions permettant de mettre en forme des formules mathématiques à partir d\'une syntaxe proche de celle du <a href="http://fr.wikipedia.org/wiki/LaTeX">LaTeX</a>.</li>
									<li><a href="http://www.tinymce.com">TinyMCE</a> : Editeur <acronym title="What You See Is What You Get">WYSIWYG</acronym> permettant la mise en page à la volée.</li>
									<li><a href="http://qbnz.com/highlighter/">GeSHi</a> : Colorateur de code source dans de nombreux langages informatiques.</li>
									<li><a href="http://jquery.com">jQuery</a> : Framework Javascript et <acronym title="Asynchronous Javascript And XML">AJAX</acronym></li>
									<li><a href="http://flowplayer.org">Flowplayer</a> : lecteur vidéo au format flash</li>
									<li><a href="http://fontawesome.io">Font Awesome</a> : librairie d\'icônes</li>
									<li><a href="http://l-lin.github.io/font-awesome-animation/">Font Awesome Animation</a> : Animation pour la librairie Font Awesome</li>
									<li><a href="http://cornel.bopp-art.com/lightcase/">Lightcase.js</a> : Lightbox responsive</li>
									<li><a href="https://github.com/cssmenumaker/jQuery-Plugin-Responsive-Drop-Down">CssMenuMaker</a> : Menus responsive</li>
									<li><a href="http://www.jerrylow.com/basictable/">BasicTable.js</a> : Tables responsive</li>
								</ul>
							</div>
                        </fieldset>
                        <fieldset>
                            <legend>Crédits</legend>
							<div class="fielset-inset">
								<ul>
									<li><strong>Régis VIARRE</strong> <em>(alias CrowkaiT)</em>, fondateur du projet PHPBoost et développeur retraité</li>
									<li><strong>Benoît SAUTEL</strong> <em>(alias ben.popeye)</em>, développeur retraité</li>
									<li><strong>Loic ROUCHON</strong> <em>(alias horn)</em>, développeur retraité</li>
									<li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, développeur retraité</li>
									<li><strong>Julien BRISWALTER</strong> <em>(alias j1.seth)</em>, développeur</li>
								</ul>
							</div>
                        </fieldset>',
	'site.index' => 'Aller à l\'accueil du site',
	'admin.index' => 'Aller dans le panneau d\'administration'
);
?>
