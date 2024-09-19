<?php

session_start();

$operand1 = 0;
$operand2 = 0;
$result = '';
$history = [];
$selected = ['add' => '', 'subtract' => '', 'multiply' => '', 'divide' => ''];

if (!isset($_SESSION['result'])) {
    $_SESSION['result'] = '';
}

if (isset($_POST['operand1']) && isset($_POST['operand2']) && isset($_POST['operator'])) {
    $operand1 = doubleval($_POST['operand1']);
    $operand2 = doubleval($_POST['operand2']);
    $operator = $_POST['operator'];

    $selected[$operator] = 'selected';

    if (isset($_POST['calculate'])) {
        switch ($operator) {
            case 'add':
                $result = $operand1 + $operand2;
                break;
            case 'subtract':
                $result = $operand1 - $operand2;
                break;
            case 'multiply':
                $result = $operand1 * $operand2;
                break;
            case 'divide':
                $result = $operand2 != 0 ? $operand1 / $operand2 : 'Division by zero';
                break;
        }
        $_SESSION['result'] = $result;
        $_SESSION['history'][] = $result;
    }
}

if (isset($_POST['saveResult'])) {
    $_SESSION['savedResult'] = $_SESSION['result'];
} elseif (isset($_POST['loadResult'])) {
    $operand1 = $_SESSION['savedResult'] ?? '';
} elseif (isset($_POST['clear'])) {
    $operand1 = 0;
    $operand2 = 0;
    $_SESSION['result'] = '';
} elseif (isset($_POST['clearHistory'])) {
    $_SESSION['history'] = [];
}

?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Calculator</title>
        <style>

            h1 {
                margin: 0;
            }
            .main-wrapper {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .calculator {
                display: flex;
                flex-direction: row;
                justify-content: space-evenly;
                margin-top: 20px;
                width: 90vw;
            }

            .calculator form {
                display: flex;
                flex-direction: column;
                justify-content: space-evenly;
                align-items: end;
                width: 90%;
            }

            .calculator form .history {
                width: 200px;
                margin: 0 0 5vh;
            }

            .calculator form .history textarea {
                width: 95%;
                height: 100px;
                text-align: right;
            }

            .calculator form .inputs {
                display: flex;
                justify-content: space-between;
                width: 100%;
            }

            .calculator form .inputs div {
                display: flex;
                flex-direction: column;
                justify-content: end;
            }

            .calculator form .result {
                width: 200px;
            }

            .calculator form .result input {
                width: 97%;
                padding-right: 0;
                padding-left: 0;
            }

            .calculator form .btns {
                display: flex;
                flex-direction: column;
                justify-content: space-evenly;
                align-items: end;
                width: 100%;
                margin: 5vh 0 0;
            }

            .calculator button {
                padding: 10px;
                margin: 2vh 0 0;
                font-size: 20px;
                border: 1px solid black;
                background-color: #f0f0f0;
                width: 200px;
            }

            .calculator input, .calculator select {
                width: 100px;
                padding: 10px;
                font-size: 20px;
                text-align: right;
            }


            .calculator button:hover {
                background-color: #e0e0e0;
            }

            .calculator button:active {
                background-color: #d0d0d0;
            }

            .calculator button:disabled {
                background-color: #c0c0c0;
            }

            .calculator button:disabled:hover {
                background-color: #c0c0c0;
            }

            .calculator input {
                padding: 10px;
                font-size: 20px;
                text-align: right;
            }
        </style>
    </head>
    <body>
    <main class="main-wrapper">
        <h1>Calculator</h1>
        <main class="calculator">
            <form method="post">
                <div class="history">
                    <label for="history">History</label>
                    <textarea name="history" id="history" cols="30" rows="10" disabled><?php
                        if (isset($_SESSION['history'])) {
                            foreach ($_SESSION['history'] as $entry) {
                                echo "$entry\n";
                            }
                        }
                        ?></textarea>
                </div>
                <div class="inputs">
                    <div>
                        <label for="operand1">Operand1:</label>
                        <input id="operand1" type="text" name="operand1" value="<?= $operand1 ?>">
                    </div>
                    <div>
                        <label for="operator">Operator:</label>
                        <select id="operator" name="operator">
                            <option value="add" <?= $selected['add'] ?>>+</option>
                            <option value="subtract" <?= $selected['subtract'] ?>>-</option>
                            <option value="multiply" <?= $selected['multiply'] ?>>*</option>
                            <option value="divide" <?= $selected['divide'] ?>>/</option>
                        </select>
                    </div>
                    <div>
                        <label for="operand2">Operand2:</label>
                        <input id="operand2" type="text" name="operand2" value="<?= $operand2 ?>">
                    </div>
                    <div>
                        <p>=</p>
                    </div>
                    <div class="result">
                        <label for="result">Result:</label>
                        <input id="result" type="text" name="result" value="<?= $_SESSION['result'] ?>" disabled>
                    </div>
                </div>

                <div class="btns">
                    <button type="submit" name="calculate">Calculate</button>
                    <button type="submit" name="clear">C</button>
                    <button type="submit" name="clearHistory">RC</button>
                    <button type="submit" name="saveResult">M+</button>
                    <button type="submit" name="loadResult">MR</button>
                </div>
            </form>
        </main>
    </body>
    </html>
<?php

