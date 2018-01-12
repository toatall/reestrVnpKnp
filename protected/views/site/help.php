<?php

    $this->breadcrumbs=array(
	'Справка',
);            
?>

<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.js');
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/extension/spoiler/spoiler.css');
?>

<h1>Справка</h1>

<hr />

<br />

<?php
    
    //var_dump(Yii::app()->controller->id);
        
    /*
    $ldap = ldap_connect('regions.tax.nalog.ru',389) or die("Cant connect to LDAP Server");
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    if ($ldap)
    {
        
        $bind = @ldap_bind($ldap, 'REGIONS\8600_SVC_User1', '123456Qwerty');
        
 
        if ($bind)
        {
            $search = @ldap_search($ldap, 'OU=UFNS86,DC=regions,DC=tax,DC=nalog,DC=ru',
                '(sAMAccountName='.Yii::app()->user->name.')');
                        
            $res = ldap_get_entries($ldap, $search);
            
            
            echo $res[0]['name'][0];           
            echo 'YAHOO!';
        }
        else
        {
            die('Вы ввели неправильный логин или пароль. попробуйте еще раз<br /> <a href="index.php">Вернуться назад</a>');
        }
    }
    */
?>


<!--ol>
    <li><a href="#">Быстрый старт</a>
        <ol >
            <li>ajsdhkasj</li>
            <li>ajsdhkasj</li>
            <li>ajsdhkasj</li>
            <li>ajsdhkasj</li>
        </ol>
    </li>
</ol>
<p></p>
<p><a href="#">Быстрый старт</a></p>
<p><a href="#">Быстрый старт</a></p>
<p><a href="#">Быстрый старт</a></p>
<p><a href="#">Быстрый старт</a></p-->


<!--div class="spoiler-wrap">
    <div class="spoiler-head folded clickable">Скачать</div>
    <div class="spoiler-body">
        <i class="icon-download-alt"></i> <a href="/files/distr/browsers/ChromeStandaloneSetup_oneUser.exe">1. Скачать для однго аккаутна (41.2 МБ)</a><br />
        <i class="icon-download-alt"></i> <a href="/files/distr/browsers/ChromeStandaloneSetup.exe">2. Скачать для всех аккаунтов (41.2 МБ)</a>
    </div>
</div--> 
    
    
В стадии разработки.