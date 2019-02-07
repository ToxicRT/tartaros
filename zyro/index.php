<?php
	error_reporting(E_ALL); @ini_set('display_errors', true);
	$tz = @date_default_timezone_get(); @date_default_timezone_set($tz ? $tz : 'UTC');
	require_once dirname(__FILE__).'/polyfill.php';
	$pages = array(
		'0'	=> array('id' => '2', 'alias' => '', 'file' => '2.php','controllers' => array()),
		'1'	=> array('id' => '1', 'alias' => 'Mirs-Legend-The-Forgotten-Ones', 'file' => '1.php','controllers' => array()),
		'2'	=> array('id' => '12', 'alias' => 'Mirs-Legend-The-Forgotten-Ones-1', 'file' => '12.php','controllers' => array()),
		'3'	=> array('id' => '4', 'alias' => 'Mirs-Legend-The-Forgotten-Ones-2', 'file' => '4.php','controllers' => array()),
		'4'	=> array('id' => '5', 'alias' => 'The-Hunter', 'file' => '5.php','controllers' => array()),
		'5'	=> array('id' => '14', 'alias' => 'The-Hunter-1', 'file' => '14.php','controllers' => array()),
		'6'	=> array('id' => '15', 'alias' => 'The-Hunter-2', 'file' => '15.php','controllers' => array()),
		'7'	=> array('id' => '6', 'alias' => 'La-Bliblioteca-De-Lang-Huan', 'file' => '6.php','controllers' => array()),
		'8'	=> array('id' => '16', 'alias' => '1', 'file' => '16.php','controllers' => array()),
		'9'	=> array('id' => '17', 'alias' => '2', 'file' => '17.php','controllers' => array()),
		'10'	=> array('id' => '7', 'alias' => 'All-Heavenly-Days', 'file' => '7.php','controllers' => array()),
		'11'	=> array('id' => '18', 'alias' => '1-1', 'file' => '18.php','controllers' => array()),
		'12'	=> array('id' => '19', 'alias' => '2-1', 'file' => '19.php','controllers' => array()),
		'13'	=> array('id' => '8', 'alias' => 'Legend-Of-Phoenix', 'file' => '8.php','controllers' => array()),
		'14'	=> array('id' => '20', 'alias' => '1-2', 'file' => '20.php','controllers' => array()),
		'15'	=> array('id' => '21', 'alias' => '2-2', 'file' => '21.php','controllers' => array())
	);
	$forms = array(

	);
	$langs = null;
	$def_lang = null;
	$base_lang = 'en';
	$site_id = "c1ca6a11";
	$websiteUID = "736c561889e222080313fea2262860784d9fe25ec596b0158bab06aaa2e929f6edfb6fc5c1c142cf";
	$base_dir = dirname(__FILE__);
	$base_url = '/';
	$user_domain = 'tartarosscans.000webhostapp.com';
	$home_page = '2';
	$mod_rewrite = true;
	$show_comments = false;
	$comment_callback = "http://us.zyro.com/comment_callback/";
	$user_key = "3tVaToce7G9kiOTeFER/vVzc4dt/ul/2Citg15hSTA==";
	$user_hash = "853f3a256b587351078d676e65ede0b0";
	$ga_code = (is_file($ga_code_file = dirname(__FILE__).'/ga_code') ? file_get_contents($ga_code_file) : null);
	require_once dirname(__FILE__).'/src/SiteInfo.php';
	require_once dirname(__FILE__).'/src/SiteModule.php';
	require_once dirname(__FILE__).'/functions.inc.php';
	$siteInfo = SiteInfo::build(array('siteId' => $site_id, 'websiteUID' => $websiteUID, 'domain' => $user_domain, 'homePageId' => $home_page, 'baseDir' => $base_dir, 'baseUrl' => $base_url, 'defLang' => $def_lang, 'baseLang' => $base_lang, 'userKey' => $user_key, 'userHash' => $user_hash, 'commentsCallback' => $comment_callback, 'langs' => $langs, 'pages' => $pages, 'forms' => $forms, 'modRewrite' => $mod_rewrite, 'gaCode' => $ga_code, 'gaAnonymizeIp' => false, 'port' => null, 'pathPrefix' => null, 'useTrailingSlashes' => true,));
	$requestInfo = SiteRequestInfo::build(array('requestUri' => getRequestUri($siteInfo->baseUrl),));
	SiteModule::init(null, $siteInfo);
	list($page_id, $lang, $urlArgs, $route) = parse_uri($siteInfo, $requestInfo);
	$preview = false;
	$lang = empty($lang) ? $def_lang : $lang;
	$requestInfo->{'page'} = (isset($pages[$page_id]) ? $pages[$page_id] : null);
	$requestInfo->{'lang'} = $lang;
	$requestInfo->{'urlArgs'} = $urlArgs;
	$requestInfo->{'route'} = $route;
	handleTrailingSlashRedirect($siteInfo, $requestInfo);
	SiteModule::setLang($requestInfo->{'lang'});
	$hr_out = '';
	$page = $requestInfo->{'page'};
	if (!is_null($page)) {
		handleComments($page['id'], $siteInfo);
		if (isset($_POST["wb_form_id"])) handleForms($page['id'], $siteInfo);
	}
	ob_start();
	if ($page) {
		$fl = dirname(__FILE__).'/'.$page['file'];
		if (is_file($fl)) {
			ob_start();
			include $fl;
			$out = ob_get_clean();
			$ga_out = '';
			if ($lang && $langs) {
				foreach ($langs as $ln => $default) {
					$pageUri = getPageUri($page['id'], $ln, $siteInfo);
					$out = str_replace(urlencode('{{lang_'.$ln.'}}'), $pageUri, $out);
				}
			}
			if (is_file($ga_tpl = dirname(__FILE__).'/ga.php')) {
				ob_start(); include $ga_tpl; $ga_out = ob_get_clean();
			}
			$out = str_replace('<ga-code/>', $ga_out, $out);
			$out = str_replace('{{base_url}}', getBaseUrl(), $out);
			$out = str_replace('{{curr_url}}', getCurrUrl(), $out);
			$out = str_replace('{{hr_out}}', $hr_out, $out);
			header('Content-type: text/html; charset=utf-8', true);
			echo $out;
		}
	} else {
		header("Content-type: text/html; charset=utf-8", true, 404);
		if (is_file(dirname(__FILE__).'/404.html')) {
			include '404.html';
		} else {
			echo "<!DOCTYPE html>\n";
			echo "<html>\n";
			echo "<head>\n";
			echo "<title>404 Not found</title>\n";
			echo "</head>\n";
			echo "<body>\n";
			echo "404 Not found\n";
			echo "</body>\n";
			echo "</html>";
		}
	}
	ob_end_flush();

?>
