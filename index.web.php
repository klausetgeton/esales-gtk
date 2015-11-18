<?php
// define the autoloader
include_once 'lib/adianti/util/TAdiantiLoader.class.php';
spl_autoload_register(array('TAdiantiLoader', 'autoload_web'));

// read configurations
$ini  = parse_ini_file('application.ini');
date_default_timezone_set($ini['timezone']);

// define constants
define('APPLICATION_NAME', $ini['application']);
define('OS', strtoupper(substr(PHP_OS, 0, 3)));
define('PATH', dirname(__FILE__));
$uri = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

new TSession;
$template = 'theme1';
$lang = TSession::getValue('language') ? TSession::getValue('language') : 'en';
TAdiantiCoreTranslator::setLanguage($lang);
TApplicationTranslator::setLanguage($lang);

if (TSession::getValue('logged'))
{
    TTransaction::open('library');
    $member = User::newFromLogin(TSession::getValue('login'));
    if ($member-> role -> mnemonic == 'LIBRARIAN')
    {
        $content = file_get_contents("app/templates/{$template}/librarian.html");
    }
    else if ($member-> role -> mnemonic == 'OPERATOR')
    {
        $content = file_get_contents("app/templates/{$template}/operator.html");
    }
    else if ($member-> role -> mnemonic == 'ADMINISTRATOR')
    {
        $content = file_get_contents("app/templates/{$template}/admin.html");
    }
    TTransaction::close();
}
else
{
    $content = file_get_contents("app/templates/{$template}/login.html");
}

$content  = TApplicationTranslator::translateTemplate($content);
$content  = str_replace('{LIBRARIES}', file_get_contents("app/templates/{$template}/libraries.html"), $content);
$content  = str_replace('{URI}', $uri, $content);
$content  = str_replace('{class}', isset($_REQUEST['class']) ? $_REQUEST['class'] : '', $content);
$content  = str_replace('{template}', $template, $content);
$content  = str_replace('{login}', TSession::getValue('login'), $content);
$css      = TPage::getLoadedCSS();
$js       = TPage::getLoadedJS();
$content  = str_replace('{HEAD}', $css.$js, $content);

if (isset($_REQUEST['class']))
{
    $url = http_build_query($_REQUEST);
    $content = str_replace('//#javascript_placeholder#', "__adianti_load_page('engine.php?{$url}');", $content);
}
echo $content;
?>