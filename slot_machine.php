<?php

function winLine($line, $symbols)
{
    $winSymbol = null;
    $winLength = 0;

    foreach ($line as $symbol) {
        if ($symbol !== $winSymbol) {
            $winSymbol = $symbol;
            $winLength = 1;
        } else {
            $winLength++;
        }

        if ($winLength >= 3) {
            break;
        }
    }

    if ($winLength >= 3) {
        foreach ($symbols['symbols'] as $symbol) {
            if ($symbol['name'] === $winSymbol) {
                return $symbol['winValue'] * $winLength;
            }
        }
    }
    return 0;
}

$jsonData = file_get_contents('symbols.json');
$symbols = json_decode($jsonData, true);

$boardHeight = 3;
$boardWidth = 5;
$board = [];

echo "1 coin = 0.01 euro. Maximum credit = 100000.\n";
$credit = (int)readline("How many coins do you want to add?: ");

if ($credit < 1 || $credit > 100000) {
    echo "Error: You input an invalid number of coins.\n";
    exit;
}

while ($credit > 0) {
    $stake = (int)readline("Input the value of stake in coins?: ");
    echo "Your stake: $stake\n";

    if ($stake < 0 || $stake > $credit) {
        echo "Error: You input an invalid stake.\n";
        continue;
    }

    for ($i = 0; $i < $boardHeight; $i++) {
        for ($j = 0; $j < $boardWidth; $j++) {
            $random = array_rand($symbols['symbols']);
            $board[$i][$j] = $symbols['symbols'][$random]['name'];
        }
    }

    foreach ($board as $row) {
        foreach ($row as $symbol) {
            echo $symbol . ' ';
        }
        echo "\n";
    }

    $payout = 0;
    for ($i = 0; $i < $boardHeight; $i++) {
        $payout += winLine($board[$i], $symbols);
    }

    if ($payout > 0) {
        echo "You win $payout coins!\n";
        $credit += $payout;
    } else {
        echo "You lose!\n";
    }

    $credit -= $stake;
    echo "You have $credit coins left.\n";

}
