<?php

//Declaration des constantes

define('RIEN', 'vide');
define('DALLE', 'dalle');
define('FIN', 'salade');
define('TROU', 'trou');

//Definition des variables globales

$numCase = 0;
$plateau = array();
$chemin = array();

//Fonction permettant d'initialiser le plateau

function initPlateau($dim) {
	global $plateau;
	for($i=0; $i<$dim; $i++) {
		for($j=0; $j<$dim; $j++) {
			$plateau[$i][$j] = RIEN;
		}
	}
}

//Fonction vérifiant si une case est bien libre

function estLibre($x,$y,$dim) {
	global $plateau;
	if($x >= $dim || $y >= $dim || $x < 0 || $y < 0) {
		return false;
	}
	else {
		return ($plateau[$x][$y] == RIEN);
	}
}

//Fonction permettant de voir si une case est au

function estAuBord($x,$y,$sens,$dim) {
	return (($sens == 'est' && $y == $dim-1) ||
			($sens == 'sud' && $x == $dim-1) ||
			($sens == 'ouest' && $y == 0) ||
			($sens == 'nord' && $x == 0));
}

//Fonction permettant de vérifier si les deux cases suivantes sur le plateau sont bien libres

function avancerEstPossible($x,$y,$sens,$dim) {
	switch($sens) {
		case 'est': return (estLibre($x,$y+1,$dim) && (estLibre($x,$y+2,$dim) || estAuBord($x,$y+1,$sens,$dim)));
		case 'sud': return (estLibre($x+1,$y,$dim) && (estLibre($x+2,$y,$dim) || estAuBord($x+1,$y,$sens,$dim)));
		case 'ouest': return (estLibre($x,$y-1,$dim) && (estLibre($x,$y-2,$dim) || estAuBord($x,$y-1,$sens,$dim)));
		case 'nord': return (estLibre($x-1,$y,$dim) && (estLibre($x-2,$y,$dim) || estAuBord($x-1,$y,$sens,$dim)));
	}
}

//Fonction permettant à un pion d'avancer

function avance($x,$y,$sens) {
	switch($sens) {
		case 'est': return array('x' => $x, 'y' => $y+1);
		case 'sud': return array('x' => $x+1, 'y' => $y);
		case 'ouest': return array('x' => $x, 'y' => $y-1);
		case 'nord': return array('x' => $x-1, 'y' => $y);
	}
}

//Fonction permettant de donner le sens suivant pour le déplacement

function sensSuivant($sens) {
	switch($sens) {
		case 'est': return 'sud';
		case 'sud': return 'ouest';
		case 'ouest': return 'nord';
		case 'nord': return 'est';
	}
}

//Fonction permettant de vérifier s'il est possible de tourner vers la droite

function peutTournerTribord($x,$y,$sens,$dim) {
	return avancerEstPossible($x,$y,sensSuivant($sens),$dim);
}

//Fonction permettant d'affecter une valeur à une case

function affecte ($x,$y,$val) {
	global $numCase, $chemin, $plateau;
	if($val == DALLE){
		$chemin[$numCase] = array($x,$y);
		$plateau[$x][$y] = ++$numCase;
	}
	else {
		$plateau[$x][$y] = $val;
	}
}

//Fonction permetant de tracer le plateau de jeu

function chemine($x,$y,$sens,$dim) {
	while(avancerEstPossible($x,$y,$sens,$dim)) {
		affecte($x,$y,DALLE);
		$coord = avance($x,$y,$sens);
		$x = $coord['x'];
		$y = $coord['y'];
	}
	if(peutTournerTribord($x,$y,$sens,$dim)) {
		chemine($x,$y,sensSuivant($sens),$dim);
	}
	else {
		affecte($x,$y,FIN);
	}
}

//Fonction permettant d'afficher le plateau en HTML

function printChemin($dim, $link, $id) {
	global $plateau;
	echo "<table id='jeuPlateau' cellspacing='0'>";
	for($x=0; $x < $dim; $x++) {
		echo "<tr>";
		for($y=0; $y < $dim; $y++) {
			$val = $plateau[$x][$y];
			if(is_numeric($val)){
				$css = getImagePawn($link, $id, $val);
				echo "<td id = '".$val."' class='dalle'" . $css ."></td>";
			}
			else if ($val == "salade") {
				$css = getImagePawn($link, $id, $val);
				echo "<td class='$val'". $css ."></td>";
			} else {
				echo "<td class='$val'></td>";
			}
		}
		echo "</tr>";
	}
	echo "</table>";
}

//Fonction permettant de mettre un trou sur une case

function placeTrou($case){
	global $plateau, $chemin;
	list($x,$y)=$chemin[$case-1];
	$plateau[$x][$y] = TROU;
}


//Place 2 trous aléatoirement sur le terrain

function earthquake($link, $id) {
	$cases = getTwoPositionPawns($link, $id);
	$info = array();
    foreach ($cases as $pos) {
		placeTrou($pos);
		$pawn = getPawn($link, $pos, $id);
		if ($pawn != "") {
			$info["". $pos] = $pawn;
		}
		setStatePawn($link, $pos, 'perdu', $id);
		setStateSlab($link, 'trou', $pos, $id);
    }
    return convertToGet($info);
}

//Place 1 trou sous 2 pions adverses
function black_magician($link, $pseudo, $id) {
    $cases = getAllEnnemiesPositionPawns($link, $pseudo, $id);

		if($cases[0] == 0) {
			return;
		}

    $info = array();
    for ($i = 0; $i < 2; $i++) {
		placeTrou($cases[$i]);
		$pawn = getPawn($link, $cases[$i], $id);
		if ($pawn != "") {
			$info["". $cases[$i]] = $pawn;
		}
		setStatePawn($link, $cases[$i], 'perdu', $id);
		setStateSlab($link, 'trou', $cases[$i], $id);
    }
    return convertToGet($info);
}

//Place des trous sous tous les pions adverses
function warrior($link, $pseudo, $id) {
    $cases = getAllEnnemiesPositionPawns($link, $pseudo, $id);
    $info = array();
    foreach ($cases as $value) {
        placeTrou($value);
        $pawn = getPawn($link, $value, $id);
		if ($pawn != "") {
			$info["". $value] = $pawn;
		}
        setStatePawn($link, $value, 'perdu', $id);
        setStateSlab($link, 'trou', $value, $id);
    }
    return convertToGet($info);
}

//Bouche deux trous
function oeuf_chance($link, $id) {
	$hole = getOneHole($link, $id);
	$hole2 = getOneHole($link, $id);
	$info = array();
	$info2 = array();
	$info['b'] = $hole;
	$info2['b'] = $hole2;

	setStateSlab($link, 'libre', $hole, $id);
	setStateSlab($link, 'libre', $hole2, $id);

	return convertToGet($info);
	return convertToGet($info2);
}

//Place un trou sous chaque pion en jeu (même ceux du joueur ayant pioché la carte)
function oeuf_malchance($link, $id) {
	$case = getPositionNearestToEnd($link, $id);
	$info = array();
	placeTrou($case);
	$pawn = getPawn($link, $case, $id);
	if ($pawn != "") {
		$info["". $case] = $pawn;
	}
	setStatePawn($link, $case, 'perdu', $id);
	setStateSlab($link, 'trou', $case, $id);
	return convertToGet($info);
}
//Bouche un trou
function magician($link, $id){
    $hole = getOneHole($link, $id);
    $info = array();
    $info['b'] = $hole;
    setStateSlab($link, 'libre', $hole, $id);
	return convertToGet($info);
}


function convertToGet($info) {
	$string = "";
	$i = 0;
	foreach ($info as $key => $val) {
		if ($key == 'b') {
			if ($val == "") {
				return "";
			}
			return "&b=". $val;
		}
		if (!is_numeric($key)) {
			return "&d=". $val ."&p=". $key;
		}
		$string .= "&t$i=" . $key;
		if ($val != "") {
			$string .= "&p$i=" . $val;
		}
		$i++;
	}
	return $string;
}

//Fonction permettant de déplacer un pion

function deplacement($link, $nbCases, $pawn, $id){
    $position = getPawnPosition($link, $pawn, $id);
    setStateSlab($link, 'libre', $position, $id);
    $state = "";
    $i = 0;
    while($i < $nbCases){
        $state = getStateSlab($link, $position+1, $id);
        $position++;
        if ($state == "salade") {
        	break;
        }
        if ($state != 'occupe'){
            $i++;
        }
    }
    $info = array();
    if ($state == 'trou'){
        setPositionPawn($link, $pawn, $position, $id);
        setStatePawn($link, $position, 'perdu', $id);
		$info[$pawn] = $position;
    } else {
        setPositionPawn($link, $pawn, $position, $id);
        setStatePawn($link, $position, 'actif', $id);
	}
    if ($state == 'libre') {
    	setStateSlab($link, 'occupe', $position, $id);
    }
    if ($state == 'salade') {
    	return True;
    }
    return convertToGet($info);
}

//Fonctions utilitaires

function dbToPlateau($link, $id) {
	$holes = getHoles($link, $id);
	foreach ($holes as $one) {
		placeTrou($one);
	}
}

?>
