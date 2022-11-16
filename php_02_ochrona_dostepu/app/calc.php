<?php
require_once dirname(__FILE__).'/../config.php';

// KONTROLER strony kalkulatora

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

//ochrona kontrolera - poniższy skrypt przerwie przetwarzanie w tym punkcie gdy użytkownik jest niezalogowany
include _ROOT_PATH.'/app/security/check.php';

function getParams(&$x,&$y,&$r){
    $x = isset($_REQUEST['x']) ? $_REQUEST['x'] : null;
    $y = isset($_REQUEST['y']) ? $_REQUEST['y'] : null;
    $r = isset($_REQUEST['r']) ? $_REQUEST['r'] : null;
}

function validate(&$x,&$y,&$r,&$messages)
{
    // sprawdzenie, czy parametry zostały przekazane
    if (!(isset($x) && isset($y) && isset($r))) {
        // sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
        // teraz zakładamy, ze nie jest to błąd. Po prostu nie wykonamy obliczeń
        return false;
    }


// sprawdzenie, czy potrzebne wartości zostały przekazane
    if ($x == "") {
        $messages [] = 'Nie podano kwoty';
    }
    if ($y == "") {
        $messages [] = 'Nie podano lat';
    }
    if ($r == "") {
        $messages [] = 'Nie podano oprocentowania';
    }
//nie ma sensu walidować dalej gdy brak parametrów
    if (count ( $messages ) != 0) return false;

    // sprawdzenie, czy $x i $y są liczbami całkowitymi
    if (! is_numeric( $x )) {
        $messages [] = 'Pierwsza wartość nie jest liczbą całkowitą';
    }

    if (! is_numeric( $y )) {
        $messages [] = 'Druga wartość nie jest liczbą całkowitą';
    }
    if (! is_numeric( $r )) {
        $messages [] = 'Trzecia wartość nie jest liczbą całkowitą';
    }

    if (count ( $messages ) != 0) return false;
    else return true;
}


function process(&$x,&$y,&$r,&$result)
{
    global $role;
    $x=intvaL($x);
    $y=intvaL($y);
    $r=intvaL($r);

    if($role == 'admin') {
    $result = ($x * (($r/100)/12) * ((1+(($r/100)/12))**($y*12)))/((((1+($r/12/100))**($y*12)))-1);
        }else{$messages [] = 'Tylko administrator może cokolwiek robić bo po to jest ta achrona';}


}

//konwersja parametrów na int

    $x = null;
    $y = null;
    $r = null;
    $messages = array();
    getParams($x,$y,$r);

    if (validate($x,$y,$r,$messages)) {
    process($x,$y,$r,$result);
    }

// 4. Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$x,$y,$operation,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';