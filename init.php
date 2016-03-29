<?
/*
You can place here your functions and event handlers

AddEventHandler("module", "EventName", "FunctionName");
function FunctionName(params)
{
	//code
}
*/

define("PATH_TO_404", "/404.php");

// AddEventHandler("main", "OnAfterEpilog", "SetSeoData");
// AddEventHandler("main", "OnEpilog", "SetSeoData");
// AddEventHandler("main", "OnEndBufferContent", "ChangeMyContent");
// AddEventHandler("main", "OnEndBufferContent", "GoogleBotDirective");
// AddEventHandler("main", "OnEpilog", "Redirect404");

/**
* Фунция для просмотра и отладки данных на сайте
* $ar - параметр, принимающий любой тип данных для просмотра его содержания
* $userid - id пользователя в системе
* Пример: d($arr, 1);
* (дамп виден только под админом)
* 
* @param mixed $ar
* @param mixed $userid
*/
function d($ar, $userid = 0)
{
    global $USER;
    $arGroups = $USER->GetUserGroupArray();
    if($USER->IsAdmin() && (!empty($userid) ? (is_array($userid) ? in_array($USER->GetID(), $userid) : $userid == $USER->GetID()) : in_array(1, $arGroups))) {
         if($ar !== NULL)
            echo '<pre>'.print_r($ar,true).'</pre>';
        else 
            echo '<pre>NULL</pre>';
    } 
}


/**
* Фунция для просмотра и отладки данных на сайте
* $ar - параметр, принимающий любой тип данных для просмотра его содержания
* $userid - id пользователя в системе
* Пример: d($arr, 1);
* (дамп виден только под админом)
* 
* @param mixed $content
*/

//============= Закрываем индексацию страниц ===========//
// uncomment next line to init
// AddEventHandler("main", "OnEndBufferContent", "GoogleBotDirective");

function GoogleBotDirective(&$content)
{
    $path = $_SERVER["DOCUMENT_ROOT"].'robots.txt';
    $noindex = '<meta name="googlebot" content="noindex">';
    $buffered = false;
    
    if(@file_exists($path)) $sR = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        else $sR = file('http://'.$_SERVER['SERVER_NAME'].'/robots.txt');
    $sR = array_unique(array_filter($sR));
    
    foreach($sR as $sRule) 
    {
        if(strpos($sRule, '#')) $sRule = preg_replace('~^([^#]*)#~i', "$1", $sRule); // comment removing
        
        if(!preg_match('~Disallow.*~', $sRule)) continue; // нам нужны только запрещающие правила
        
        if($content !== false)
        {
          if(!preg_match('~<meta[^>]+name\s*=\s*["|\']googlebot["|\'][^>]+noindex[^>]+>~siU', $content)) $buffered = true;
            else continue;
        }
        $sRepFrom = array( '*',  '?' );
        $sRepTo =   array( '.*', '\?');
        $sRule = preg_replace('~^\s*Disallow\s*:\s*~i', '', $sRule);
        $sRule = trim(str_replace($sRepFrom,$sRepTo,$sRule));
        if(!strpos($sRule,'$')) // на всякий случай
        {
          $sRule = preg_replace('~([^\*])$~i', "$1.*", $sRule);
        }
        
        
        if(preg_match('~'.$sRule.'~i', $_SERVER['REQUEST_URI']))
        {
            if($buffered) $content = str_ireplace('</title>', '</title>'.$noindex, $content);
              else print $noindex;
            
            return true;
            break;
        }
        
    }
    
    return true;
}

function ChangeMyContent($content)
{
    global $APPLICATION;
    $dir = $APPLICATION->GetCurDir();
    $uri = $_SERVER['REQUEST_URI'];
    if(empty($uri)) $uri = $APPLICATION->GetCurUri();

    $content = str_replace("<head>", "<head><!-- Content rewriter enabled -->", $content);
    $content = str_replace('<head>', '<head><!--origUrl="' . $$uri . '"-->' , $content);

    if(empty($_GET['PAGEN_1']))
    {
    switch ($uri)
    {

	}

	if(!empty($aSEOData['h1']))    $APPLICATION->SetTitle($aSEOData['h1']);
	if(!empty($aSEOData['text_alt'])) $sContent = preg_replace('#(<div\s+[^>]*id\s*=\s*["\']call["\'][^>]*>)#siU', '<div class="wide_wraper"><div class="seo_txt">'.$aSEOData['text_alt']."</div></div>$1", $sContent
}

function SetSeoData()
{
    // TODO: перхватить и сбросить любой вывод

    global $APPLICATION;
    $dir = $APPLICATION->GetCurDir();
    $uri = $APPLICATION->GetCurUri();
    // $title = CMain::GetTitle();
    $title = $APPLICATION->GetTitle();
    $m_title = $APPLICATION->GetProperty("title");

    $aSEOData['title'] = '';
    $aSEOData['descr'] = '';
    $aSEOData['keywr'] = '';
    $aSEOData['h1'] = '';

	switch ($uri)
    {
    			/*
      case '':
        $aSEOData['title'] = '';
        $aSEOData['descr'] = '';
        $aSEOData['keywr'] = '';
		$aSEOData['h1'] = '';
      break;
*/
    }
    // Установка новых значений
    if(!empty($aSEOData['title'])) $APPLICATION->SetPageProperty('title', $aSEOData['title']);
    if(!empty($aSEOData['descr'])) $APPLICATION->SetPageProperty('description', $aSEOData['descr']);
    if(!empty($aSEOData['keywr'])) $APPLICATION->SetPageProperty('keywords', mb_strtolower($aSEOData['keywr']));
    if(!empty($aSEOData['h1']))    $APPLICATION->SetTitle($aSEOData['h1']);
}

?>
