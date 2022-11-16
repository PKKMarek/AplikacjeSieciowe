<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';
//załaduj Smarty
require_once _ROOT_PATH.'/lib/smarty/Smarty.class.php';

//pobranie parametrów
function getParams(&$form){
    $form['x'] = isset($_REQUEST['x']) ? $_REQUEST['x'] : null;
    $form['y'] = isset($_REQUEST['y']) ? $_REQUEST['y'] : null;
    $form['r'] = isset($_REQUEST['r']) ? $_REQUEST['r'] : null;
}

function validate(&$form,&$infos,&$msgs,&$hide_intro){

    if ( ! (isset($form['x']) && isset($form['y']) && isset($form['r']) ))	return false;
    $hide_intro = true;

    $infos [] = 'Przekazano parametry.';

    if ( $form['x'] == "") $msgs [] = 'Nie podano liczby 1';
    if ( $form['y'] == "") $msgs [] = 'Nie podano liczby 2';
    if ( $form['r'] == "") $msgs [] = 'Nie podano liczby 3';

    //nie ma sensu walidować dalej gdy brak parametrów
    if ( count($msgs)==0 ) {
        // sprawdzenie, czy $x i $y są liczbami całkowitymi
        if (! is_numeric( $form['x'] )) $msgs [] = 'Pierwsza wartość nie jest liczbą';
        if (! is_numeric( $form['y'] )) $msgs [] = 'Druga wartość nie jest liczbą';
        if (! is_numeric( $form['r'] )) $msgs [] = 'Druga wartość nie jest liczbą';
    }

    if (count($msgs)>0) return false;
    else return true;
}


function process(&$form,&$infos,&$msgs,&$result)
{

    $infos [] = 'Parametry poprawne. Wykonuję obliczenia.';

    //konwersja parametrów na int
    $form['x'] = floatval($form['x']);
    $form['y'] = floatval($form['y']);
    $form['r'] = floatval($form['r']);


        $result = ($form['x'] * (($form['r']/100)/12) * ((1+(($form['r']/100)/12))**($form['y']*12)))/((((1+($form['r']/12/100))** ($form['y']*12)))-1);


}

//konwersja parametrów na int

$form = null;
$infos = array();
$messages = array();
$result = null;
$hide_intro = false;
getParams($form);
if ( validate($form,$infos,$messages,$hide_intro) ){
    process($form,$infos,$messages,$result);
}

// 4. Przygotowanie danych dla szablonu

$smarty = new Smarty();

$smarty->assign('app_url',_APP_URL);
$smarty->assign('root_path',_ROOT_PATH);
$smarty->assign('page_title','Przykład 04');
$smarty->assign('page_description','Profesjonalne szablonowanie oparte na bibliotece Smarty');
$smarty->assign('page_header','Szablony Smarty');

$smarty->assign('hide_intro',$hide_intro);

//pozostałe zmienne niekoniecznie muszą istnieć, dlatego sprawdzamy aby nie otrzymać ostrzeżenia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);
$smarty->assign('infos',$infos);

// 5. Wywołanie szablonu
$smarty->display(_ROOT_PATH.'/app/calc.tpl');