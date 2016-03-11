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
?>
