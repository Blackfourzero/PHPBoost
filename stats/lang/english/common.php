<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 09 28
 * @since       PHPBoost 1.6 - 2007 09 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

$lang['stats.module.title'] = 'Statistics';
$lang['more.stats'] = 'More statistics';
$lang['last.member'] = 'Latest member';
$lang['percentage'] = 'Percentage';
$lang['member.registered'] = '<strong>%d</strong> registered member';
$lang['member.registered.s'] = '<strong>%d</strong> registered members';
$lang['admin.authorizations'] = 'Permissions';
$lang['admin.authorizations.read'] = 'Permission to display the statistics';
$lang['config.elements.number.per.page'] = 'Elements number per page';
$lang['config.elements.number.per.page.explain'] = 'For referent websites and keywords.';
$lang['config.require.elements.number'] = 'The number of elements per page can\'t be null.';


// Robots
$lang['robot'] = 'Robot';
$lang['robots'] = 'Robots';
$lang['unknown_bot'] = 'Unknown robot';
$lang['erase.rapport'] = 'Erase rapport';
$lang['number.r.visit'] = 'Number of views';

// Statistiques
$lang['site'] = 'Site';
$lang['referer.s'] = 'Referent websites';
$lang['no.referer'] = 'no referent website';
$lang['page.s'] = 'Displayed pages';
$lang['browser.s'] = 'Browsers';
$lang['keyword.s'] = 'Keywords';
$lang['no.keyword'] = 'no keyword';
$lang['os'] = 'Operating systems';
$lang['number'] = 'Number of ';
$lang['start'] = 'Creation of the website';
$lang['stat.lang'] = 'Visitor\'s Countries';
$lang['visits.year'] = 'See statistics for the year';
$lang['unknown'] = 'Unknown';
$lang['top.10.posters'] = 'Top 10: posters';
$lang['version'] = 'Version';
$lang['colors'] = 'Colors';
$lang['calendar'] = 'Calendar';
$lang['events'] = 'Events';

// Referent websites
$lang['total.visit'] = 'Total visits';
$lang['average.visit'] = 'Average visits';
$lang['trend'] = 'Trend';
$lang['last.update'] = 'Last visit';

// Browsers
global $stats_array_browsers;
$stats_array_browsers = array(
	'brave'            => array('Brave', 'brave.png'),
	'chrome'           => array('Chrome', 'chrome.png'),
	'chromium'         => array('Chromium', 'chromium.png'),
	'edge'             => array('Edge', 'edge.png'),
	'firefox'          => array('Firefox', 'firefox.png'),
	'opera'            => array('Opera', 'opera.png'),
	'safari'           => array('Safari', 'safari.png'),
	'tor'              => array('Tor', 'tor.png'),

	'epic'             => array('Epic Privacy', 'epic.png'),
	'falcon'           => array('Falcon', 'falcon.png'),
	'internetexplorer' => array('Internet Explorer', 'internetexplorer.png'),
	'icab'             => array('Icab', 'icab.png'),
	'iron'             => array('SRWare Iron', 'iron.png'),
	'konqueror'        => array('Konqueror', 'konqueror.png'),
	'lynx'             => array('Lynx', 'lynx.png'),
	'links'            => array('Links', 'links.png'),
	'lunascape'        => array('Lunascape', 'lunascape.png'),
	'maxthon'          => array('Maxthon', 'maxthon.png'),
	'phoenix'          => array('Phoenix', 'phoenix.png'),
	'silk'             => array('Amazone Silk', 'silk.png'),
	'seamonkey'        => array('SeaMonkey', 'seamonkey.png'),
	'uc'               => array('UC Browser', 'uc.png'),
	'vivaldi'          => array('Vivaldi', 'vivaldi.png'),
	'yandex'           => array('Yandex', 'yandex.png'),

	'phone'            => array('Mobile', 'phone.png'),
	'other'            => array('Other', 'other.png')
	);

// Operating systems
global $stats_array_os;
$stats_array_os = array(
	'macintosh'         => array('Mac OS', 'mac.png'),

	'windows10'         => array('Windows 10', 'windows10.png'),
	'windows8.1'        => array('Windows 8.1', 'windows8.png'),
	'windows8'          => array('Windows 8', 'windows8.png'),
	'windowsseven'      => array('Windows 7', 'windowsseven.png'),
	'windowsvista'      => array('Windows Vista', 'windowsvista.png'),
	'windowsserver2003' => array('Windows Serveur 2003', 'windowsxp.png'),
	'windowsxp'         => array('Windows XP', 'windowsxp.png'),
	'windowsold'        => array('Older Windows (before 2000)', 'windowsold.png'),

	'linux'             => array('Linux', 'linux.png'),
	'sunos'             => array('SunOS', 'sun.png'),
	'os2'               => array('OS2', 'os2.png'),
	'freebsd'           => array('FreeBSD', 'freebsd.png'),
	'netbsd'            => array('NetBSD', 'freebsd.png'),
	'aix'               => array('AIX', 'aix.png'),
	'irix'              => array('Irix', 'irix.png'),
	'hp-ux'             => array('HP-UX', 'hpux.png'),

	'wii'               => array('Wii', 'wii.png'),
	'psp'               => array('PSP', 'psp.png'),
	'playstation3'      => array('Playstation 3', 'ps3.png'),
	'playstation4'      => array('Playstation 4', 'ps3.png'),
	'xboxone'           => array('Xbox One', 'other.png'),

	'android'           => array('Android', 'android.png'),
	'ios'               => array('IOS', 'iphone.png'),
	'phone'             => array('Mobile', 'phone.png'),
	'windowsphone'      => array('Windows phone', 'windows8.png'),

	'other'             => array('Other', 'other.png')
);

// Countries
global $stats_array_lang;
$stats_array_lang = array(
	'ad' => array('Andorra', 'ad.png'),
	'ae' => array('United Arab Emirates', 'ae.png'),
	'af' => array('Afghanistan', 'af.png'),
	'ag' => array('Antigua and Barbuda', 'ag.png'),
	'ai' => array('Anguilla', 'ai.png'),
	'al' => array('Albania', 'al.png'),
	'am' => array('Armenia', 'am.png'),
	'an' => array('Netherlands Antilles', 'an.png'),
	'ao' => array('Angola', 'ao.png'),
	'aq' => array('Antarctica', 'aq.png'),
	'ar' => array('Argentina', 'ar.png'),
	'as' => array('American Samoa', 'as.png'),
	'at' => array('Austria', 'at.png'),
	'au' => array('Australia', 'au.png'),
	'aw' => array('Aruba', 'aw.png'),
	'az' => array('Azerbaijan', 'az.png'),
	'ba' => array('Bosnia and Herzegovina', 'ba.png'),
	'bb' => array('Barbados', 'bb.png'),
	'bd' => array('Bangladesh', 'bd.png'),
	'be' => array('Belgium', 'be.png'),
	'bf' => array('Burkina Faso', 'bf.png'),
	'bg' => array('Bulgaria', 'bg.png'),
	'bh' => array('Bahrain', 'bh.png'),
	'bi' => array('Burundi', 'bi.png'),
	'bj' => array('Benin', 'bj.png'),
	'bm' => array('Bermuda', 'bm.png'),
	'bn' => array('Bruneo', 'bn.png'),
	'bo' => array('Bolivia', 'bo.png'),
	'br' => array('Brazil', 'br.png'),
	'bs' => array('Bahamas', 'bs.png'),
	'bt' => array('Bhutan', 'bt.png'),
	'bv' => array('Bouvet Island', 'bv.png'),
	'bw' => array('Botswana', 'bw.png'),
	'by' => array('Belarus', 'by.png'),
	'bz' => array('Belize', 'bz.png'),
	'ca' => array('Canada', 'ca.png'),
	'cc' => array('Cocos (Keeling) Islands', 'cc.png'),
	'cd' => array('Democratic Republic of the Congo', 'cd.png'),
	'cf' => array('Central African Republic', 'cf.png'),
	'cg' => array('Congo', 'cg.png'),
	'ch' => array('Switzerland', 'ch.png'),
	'ci' => array('Cote D\'Ivoire', 'ci.png'),
	'ck' => array('Cook Islands', 'ck.png'),
	'cl' => array('Chile', 'cl.png'),
	'cm' => array('Cameroon', 'cm.png'),
	'cn' => array('China', 'cn.png'),
	'co' => array('Colombia', 'co.png'),
	'cr' => array('Costa Rica', 'cr.png'),
	'cu' => array('Cuba', 'cu.png'),
	'cv' => array('Cape Verde', 'cv.png'),
	'cx' => array('Christmas Island', 'cx.png'),
	'cy' => array('Cyprus', 'cy.png'),
	'cz' => array('Czech Republic', 'cz.png'),
	'de' => array('Germany', 'de.png'),
	'dj' => array('Djibouti', 'dj.png'),
	'dk' => array('Denmark', 'dk.png'),
	'dm' => array('Dominica', 'dm.png'),
	'do' => array('Dominican Republic', 'do.png'),
	'dz' => array('Algeria', 'dz.png'),
	'ec' => array('Ecuador', 'ec.png'),
	'ee' => array('Estonia', 'ee.png'),
	'eg' => array('Egypt', 'eg.png'),
	'eh' => array('Western Sahara', 'eh.png'),
	'er' => array('Eritrea', 'er.png'),
	'es' => array('Spain', 'es.png'),
	'et' => array('Ethiopia', 'et.png'),
	'fi' => array('Finland', 'fi.png'),
	'fj' => array('Fiji', 'fj.png'),
	'fk' => array('Falkland Islands (Malvinas)', 'fk.png'),
	'fm' => array('Micronesia, (Federated States of', 'fm.png'),
	'fo' => array('Faroe Islands', 'fo.png'),
	'fr' => array('France', 'fr.png'),
	'ga' => array('Gabon', 'ga.png'),
	'gb' => array('Great Britain', 'gb.png'),
	'gd' => array('Grenada', 'gd.png'),
	'ge' => array('Georgia', 'ge.png'),
	'gf' => array('French Guyana', 'gf.png'),
	'gg' => array('Guernsey', 'gg.png'),
	'gh' => array('Ghana', 'gh.png'),
	'gi' => array('Gibraltar', 'gi.png'),
	'gl' => array('Greenland', 'gl.png'),
	'gm' => array('Gambia', 'gm.png'),
	'gn' => array('Guinea', 'gn.png'),
	'gp' => array('Guadeloupe', 'gp.png'),
	'gq' => array('Equatorial Guinea', 'gq.png'),
	'gr' => array('Greece', 'gr.png'),
	'gs' => array('South Georgia and the South Sandwich Islands', 'gs.png'),
	'gt' => array('Guatemala', 'gt.png'),
	'gu' => array('Guam', 'gu.png'),
	'gw' => array('Guinea-Bissau', 'gw.png'),
	'gy' => array('Guyana', 'gy.png'),
	'hk' => array('Hong Kong', 'hk.png'),
	'hm' => array('Heard Island and McDonald Islands', 'hm.png'),
	'hn' => array('Honduras', 'hn.png'),
	'hr' => array('Croatia', 'hr.png'),
	'ht' => array('Haiti', 'ht.png'),
	'hu' => array('Hungary', 'hu.png'),
	'id' => array('Indonesia', 'id.png'),
	'ie' => array('Ireland', 'ie.png'),
	'il' => array('Israel', 'il.png'),
	'im' => array('Man Island', 'im.png'),
	'in' => array('India', 'in.png'),
	'io' => array('British Indian Ocean Territory', 'io.png'),
	'iq' => array('Iraq', 'iq.png'),
	'ir' => array('Iran, (Islamic Republic of', 'ir.png'),
	'is' => array('Iceland', 'is.png'),
	'it' => array('Italy', 'it.png'),
	'je' => array('Jersey', 'je.png'),
	'jm' => array('Jamaica', 'jm.png'),
	'jo' => array('Jordan', 'jo.png'),
	'jp' => array('Japan', 'jp.png'),
	'ke' => array('Kenya', 'ke.png'),
	'kg' => array('Kyrgyzstan', 'kg.png'),
	'kh' => array('Cambodia', 'kh.png'),
	'ki' => array('Kiribati', 'ki.png'),
	'km' => array('Comoros', 'km.png'),
	'kn' => array('Saint Kitts and Nevis', 'kn.png'),
	'kp' => array('Korea, (Democratic People\'s Republic of', 'kp.png'),
	'kr' => array('Korea, (Republic of', 'kr.png'),
	'kw' => array('Kuwait', 'kw.png'),
	'ky' => array('Cayman Islands', 'ky.png'),
	'kz' => array('Kazakhstan', 'kz.png'),
	'la' => array('Laos', 'la.png'),
	'lb' => array('Lebanon', 'lb.png'),
	'lc' => array('Saint Lucia', 'lc.png'),
	'li' => array('Liechtenstein', 'li.png'),
	'lk' => array('Sri Lanka', 'lk.png'),
	'lr' => array('Liberia', 'lr.png'),
	'ls' => array('Lesotho', 'ls.png'),
	'lt' => array('Lithuania', 'lt.png'),
	'lu' => array('Luxembourg', 'lu.png'),
	'lv' => array('Latvia', 'lv.png'),
	'ly' => array('Libya', 'ly.png'),
	'ma' => array('Morocco', 'ma.png'),
	'mc' => array('Monaco', 'mc.png'),
	'md' => array('Republic of Moldova', 'md.png'),
	'me' => array('Montenegro', 'me.png'),
	'mg' => array('Madagascar', 'mg.png'),
	'mh' => array('Marshall Islands', 'mh.png'),
	'mk' => array('Macedonia', 'mk.png'),
	'ml' => array('Mali', 'ml.png'),
	'mm' => array('Myanmar', 'mm.png'),
	'mn' => array('Mongolia', 'mn.png'),
	'mo' => array('Macau', 'mo.png'),
	'mp' => array('Northern Mariana Islands', 'mp.png'),
	'mq' => array('Martinique', 'mq.png'),
	'mr' => array('Mauritania', 'mr.png'),
	'ms' => array('Montserrat', 'ms.png'),
	'mt' => array('Malta', 'mt.png'),
	'mu' => array('Mauritius', 'mu.png'),
	'mv' => array('Maldives', 'mv.png'),
	'mw' => array('Malawi', 'mw.png'),
	'mx' => array('Mexico', 'mx.png'),
	'my' => array('Malaysia', 'my.png'),
	'mz' => array('Mozambique', 'mz.png'),
	'na' => array('Namibia', 'na.png'),
	'nc' => array('New Caledonia', 'nc.png'),
	'ne' => array('Niger', 'ne.png'),
	'nf' => array('Norfolk Island', 'nf.png'),
	'ng' => array('Nigeria', 'ng.png'),
	'ni' => array('Nicaragua', 'ni.png'),
	'nl' => array('Netherlands', 'nl.png'),
	'no' => array('Norway', 'no.png'),
	'np' => array('Nepal', 'np.png'),
	'nr' => array('Nauru', 'nr.png'),
	'nu' => array('Niue', 'nu.png'),
	'nz' => array('New Zealand', 'nz.png'),
	'om' => array('Oman', 'om.png'),
	'pa' => array('Panama', 'pa.png'),
	'pe' => array('Peru', 'pe.png'),
	'pf' => array('French Polynesia', 'pf.png'),
	'pg' => array('Papua New Guinea', 'pg.png'),
	'ph' => array('Philippines', 'ph.png'),
	'pk' => array('Pakistan', 'pk.png'),
	'pl' => array('Poland', 'pl.png'),
	'pm' => array('Saint Pierre and Miquelon', 'pm.png'),
	'pn' => array('Pitcairn', 'pn.png'),
	'pr' => array('Puerto Rico', 'pr.png'),
	'ps' => array('Palestinian Territory', 'ps.png'),
	'pt' => array('Portugal', 'pt.png'),
	'pw' => array('Palau', 'pw.png'),
	'py' => array('Paraguay', 'py.png'),
	'qa' => array('Qatar', 'qa.png'),
	're' => array('Reunion Island', 're.png'),
	'ro' => array('Romania', 'ro.png'),
	'ru' => array('Russia', 'ru.png'),
	'rs' => array('Serbia', 'rs.png'),
	'rw' => array('Rwanda', 'rw.png'),
	'sa' => array('Saudi Arabia', 'sa.png'),
	'sb' => array('Solomon Islands', 'sb.png'),
	'sc' => array('Seychelles', 'sc.png'),
	'sd' => array('Sudan', 'sd.png'),
	'se' => array('Sweden', 'se.png'),
	'sg' => array('Singapore', 'sg.png'),
	'sh' => array('Saint Helena', 'sh.png'),
	'si' => array('Slovenia', 'si.png'),
	'sj' => array('Svalbard and Jan Mayen', 'sj.png'),
	'sk' => array('Slovakia', 'sk.png'),
	'sl' => array('Sierra Leone', 'sl.png'),
	'sm' => array('San Marino', 'sm.png'),
	'sn' => array('Senegal', 'sn.png'),
	'so' => array('Somalia', 'so.png'),
	'sr' => array('Suriname', 'sr.png'),
	'st' => array('Sao Tome and Principe', 'st.png'),
	'sv' => array('El Salvador', 'sv.png'),
	'sy' => array('Syria', 'sy.png'),
	'sz' => array('Swaziland', 'sz.png'),
	'tc' => array('Turks and Caicos Islands', 'tc.png'),
	'td' => array('Chad', 'td.png'),
	'tf' => array('French Southern Territories', 'tf.png'),
	'tg' => array('Togo', 'tg.png'),
	'th' => array('Thailand', 'th.png'),
	'tj' => array('Tajikistan', 'tj.png'),
	'tk' => array('Tokelau', 'tk.png'),
	'tm' => array('Turkmenistan', 'tm.png'),
	'tn' => array('Tunisia', 'tn.png'),
	'to' => array('Tonga', 'to.png'),
	'tp' => array('East Timor', 'tp.png'),
	'tr' => array('Turkey', 'tr.png'),
	'tt' => array('Trinidad and Tobago', 'tt.png'),
	'tv' => array('Tuvalu', 'tv.png'),
	'tw' => array('Taiwan', 'tw.png'),
	'tz' => array('Tanzania', 'tz.png'),
	'ua' => array('Ukraine', 'ua.png'),
	'ug' => array('Uganda', 'ug.png'),
	'uk' => array('United Kingdom', 'uk.png'),
	'um' => array('United States Minor Outlying Islands', 'um.png'),
	'us' => array('United States', 'us.png'),
	'uy' => array('Uruguay', 'uy.png'),
	'uz' => array('Uzbekistan', 'uz.png'),
	'va' => array('Vatican', 'va.png'),
	'vc' => array('Saint Vincent and the Grenadines', 'vc.png'),
	've' => array('Venezuela', 've.png'),
	'vg' => array('Virgin Islands, (British', 'vg.png'),
	'vi' => array('Virgin Islands, (U.S.', 'vi.png'),
	'vn' => array('Vietnam', 'vn.png'),
	'vu' => array('Vanuatu', 'vu.png'),
	'wf' => array('Wallis and Futuna', 'wf.png'),
	'ws' => array('Samoa', 'ws.png'),
	'xk' => array('Kosovo', 'rs.png'),
	'ye' => array('Yemen', 'ye.png'),
	'yt' => array('Mayotte', 'yt.png'),
	'za' => array('South Africa', 'za.png'),
	'zm' => array('Zambia', 'zm.png'),
	'zw' => array('Zimbabwe', 'zw.png'),
	'other' => array('Other', 'other.png')
);
?>
