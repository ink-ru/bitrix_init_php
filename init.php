<?
/*
You can place here your functions and event handlers

AddEventHandler("module", "EventName", "FunctionName");
function FunctionName(params)
{
	//code
}
*/

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

?>
