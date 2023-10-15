<?php
// KONTROLER strony banku
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.
include _ROOT_PATH.'/app/security/check.php';

// 1. pobranie parametrów
function getParams(&$kwota,&$rata,&$op){
	$kwota =isset($_REQUEST['kwota']) ? $_REQUEST['kwota']: null;
	$rata = isset($_REQUEST['rata']) ? $_REQUEST['rata']: null;
	$op = isset($_REQUEST['op']) ? $_REQUEST['op']: null;
}
// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$kwota,&$rata,&$op,&$messages){
	// sprawdzenie, czy parametry zostały przekazane
	if ( ! (isset($kwota) && isset($rata) && isset($op))) {
		//sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
		return false;
	}

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $kwota == "") {
		$messages [] = 'Nie podano kwoty';
	}
	if ( $rata == "") {
		$messages [] = 'Nie podano liczby liczby rat';
	}
	if ( $op == "") {
		$messages [] = 'Nie podano liczby oprocentowania';
	}

	//nie ma sensu walidować dalej gdy brak parametrów
	if (count ( $messages ) != 0) return false;

	// sprawdzenie, czy $kwota, $rata i $op są liczbami całkowitymi
	if (! is_numeric( $kwota )) {
		$messages [] = 'Kwota nie jest liczbą całkowitą';
	}
	
	if (! is_numeric( $rata )) {
		$messages [] = 'Rata wartość nie jest liczbą całkowitą';
	}

	if (! is_numeric( $op )) {
		$messages [] = 'Operacja nie jest liczbą całkowitą';
	}		
	
	if (count ( $messages ) != 0) return false;
	else return true;

}

function procces(&$kwota,&$rata,&$op,&$messages,&$wynik3){
	global $role;

		

	
	$kwota = intval($kwota);
	$rata = intval($rata);
    $op = intval($op);

	//wykonanie operacji

if($role !="admin" && $kwota > 100000){
	$messages[] = "Tylko administrator może udzielać kredytów w kwocie powyżej 100000zł."; 
}	else{
	$procent=$op*0.01;
    $wynik1=$kwota*$procent;
    $wynik2=$kwota+$wynik1;
    $wynik3= $wynik2/$rata;
}


}
	//definicja zmiennych kontrolera
	$kwota = null;
	$rata = null;
	$op = null;
	$wynik3 = null;
	$messages = array();

	//pobierz parametry i wykonaj zadanie jeśli wszystko w porządku
	getParams($kwota,$rata,$op);
	if ( validate($kwota,$rata,$op,$messages) ) { // gdy brak błędów
		procces($kwota,$rata,$op,$messages,$wynik3);
	}


include 'bank_view.php';