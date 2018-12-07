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

?>
