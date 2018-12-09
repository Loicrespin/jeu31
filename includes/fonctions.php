<?php
function calculScoreRED($link, $hand) {
    $score = 0;

  	foreach ($hand as $value) {
      if(getColorCard($link, $value) == "red") {
        $score += getScore($link, $value);
      }
    }
    return $score;
}

function calculScoreBLACK($link, $hand) {
    $score = 0;
  	foreach ($hand as $value) {
      if(getColorCard($link, $value) == "black") {
        $score += getScore($link, $value);
      }
    }
      return $score;
}

//déterminer les proba en fonction des params des échantillons
function weighted_random($values, $weights){
    $count = count($values);
    $i = 0;
    $n = 0;
    $num = mt_rand(0, array_sum($weights));
    while($i < $count){
        $n += $weights[$i];
        if($n >= $num){
            break;
        }
        $i++;
    }
    return $values[$i];
}

function IaDefausse($link) {
  if(count($_SESSION['main2']) > 3) {
  $low = 11;
  if(calculScoreBLACK($link,  $_SESSION['main2']) > calculScoreRED($link,  $_SESSION['main2'])) {
    foreach ($_SESSION['main2'] as $value) {
      if(getColorCard($link, $value) == "black") {
        if(getScore($link, $value) < $low) {
          $low = getScore($link, $value);
          $id = $value;
        }
      }
    }
  } else {
    foreach ($_SESSION['main2'] as $value) {
      if(getColorCard($link, $value) == "red") {
        if(getScore($link, $value) < $low) {
          $low = getScore($link, $value);
          $id = $value;
        }
      }
    }
  }
  $_SESSION['defausselast'] = $id; 
  array_splice($_SESSION['main2'], array_search($id, $_SESSION['main2']),1);
  array_push($_SESSION['deckdefausse'],  $id);
  addAction($link, "Defausse", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);
  }
}

function IaPlay($link, $idj) {

    $values = array('piocher', 'cogner', 'piochedefausse', 'fintour');
    $weights = array(getIaProbPioche($link, $idj), getIaProbCogne($link, $idj), getIaProbPioche($link, $idj), getIaProbfinTour($link, $idj));
    $weighted_value = weighted_random($values, $weights);

  switch ($weighted_value) {
    case 'piocher': {
      if($_SESSION['piocher'] == 1) {
        break;
      } else {
      $_SESSION['piocher'] = 1;
			$_SESSION['card'] = getCard($link, $_SESSION['id']);
			$ans = $_SESSION['card'];

			if ($ans == "endDeck") {
			createNewDeckFromDeff($link, $_SESSION['id'], $_SESSION['deckdefausse']);
			   header("Location: index.php?page=jeu&card=$ans");
			}

      array_push($_SESSION['main2'], $_SESSION['card']);
      addAction($link, "Pioche", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);

      IaDefausse($link);
    }
      break;
    }

    case 'piochedefausse' : {
      if($_SESSION['piocher'] == 1) {
        break;
      } else {
      $_SESSION['piocher'] = 1;
      //Ajout de la carte en main
      array_push($_SESSION['main2'], $_SESSION['defausselast']);

      array_splice($_SESSION['deckdefausse'], array_search($_SESSION['defausselast'], $_SESSION['deckdefausse']),1);

      addAction($link, "Pioche Defausse", 	$_SESSION['id_tour'], 	$_SESSION['id_manche'], 	$_SESSION['id'], 	$_SESSION['player' . $_SESSION['currPlayer']]);
      IaDefausse($link);
    }
      break;
    }

    case 'cogner' : {
      header('Location: index.php?page=working&action=cogner');
      break;
    }

    case 'fintour' : {
        header('Location: index.php?page=working&action=endturn');
      break;
    }

    default:
      	header('Location: index.php');
      break;
  }

  //Après avoir joué
  $values1 = array('cogner', 'fintour');
  $weights1 = array(getIaProbCogne($link, $idj), getIaProbfinTour($link, $idj));
  $weighted_value1 = weighted_random($values1, $weights1);

  if($weighted_value1 == 'cogner') {
    header('Location: index.php?page=working&action=cogner');
  } else {
    header('Location: index.php?page=working&action=endturn');
    }

      return $weighted_value1;
}
?>
