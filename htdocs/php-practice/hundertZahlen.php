<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test</title>

    <style>
        body {
            height: 100vh;
            display: grid;
            place-items: center;
        }

        table, th, td {
            border: 1px solid black;
        }

        td {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
        }

        tr {
            display: flex;

        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="main-wrapper">
    <h1>Hundert Zahlen</h1>
    <?php
    $numbers = getHundredNumbers(1);
    createHundredFieldTable($numbers);
    ?>
</div>
</body>
</html>


<?php

/**
 * getHundertNumbers creates an array of 100 numbers starting from the given number
 *
 * @param $start
 *
 * @return array
 */
function getHundredNumbers($start = 0): array
{
    $numbers = [];
    for ($i = $start; $i < $start + 100; $i++) {
        $numbers[] = $i;
    }
    return $numbers;
}

//
/**
 * createTableFromNumbers creates a table with 10 rows and 10 columns from the given numbers
 *
 * @param $numbers
 *
 * @return void
 */
function createHundredFieldTable($numbers): void
{
    echo "<table>";
    for ($i = 0; $i < 10; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 10; $j++) {
            echo "<td>{$numbers[$i * 10 + $j]}</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
?>

