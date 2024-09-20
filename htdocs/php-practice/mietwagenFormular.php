<?php
include 'NettmannFunktionen.inc.php';

session_start();

renderPage();

/**
 * renderPage renders the page
 *
 * @return void
 */
function renderPage(): void
{
    list($content, $loggedIn) = array_values(handleRequest());

    $pageContent = setHeader(getHeaderHeading($content), $loggedIn)
        . ($content === 'FailedLogin' ? 'setLogin' : 'set' . $content)()
        . setFooter($loggedIn ? createSingleButtonForm('logout') : '');

    echo $pageContent;
}


/**
 * handleRequest chooses the content based on the request and session
 *
 * @return array
 */
function handleRequest(): array
{
    if (isset($_POST['logout'])) {
        session_destroy();
    } else {
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'p8n32p9yMLUH0m8s9du309w[08edvf;zmdi') {
            $content = (!isset($_POST['submitted'])) ? 'MietwagenForm' : 'MietwagenBeleg';

            return ['content' => $content, 'loggedIn' => true];
        } elseif (isset($_POST['username']) && isset($_POST['password'])) {
            if (validateUserCredentials($_POST['username'], $_POST['password'])) {
                $_SESSION['loggedIn'] = 'p8n32p9yMLUH0m8s9du309w[08edvf;zmdi';
                $_SESSION['kundennummer'] = getKundennummer($_POST['username']);
                header('Location: mietwagenFormular.php');
                exit();
            } else {

                return ['content' => 'FailedLogin', 'loggedIn' => false];
            }
        }
    }

    return ['content' => 'Login', 'loggedIn' => false];
}


/**
 * setMietwagenForm renders the mietwagen form
 *
 * @return string
 */
function setMietwagenForm(): string
{
    $kundennummer = $_SESSION['kundennummer'] ?? '';
    $result = "
    <form action='mietwagenFormular.php' method='post'>
        <p>Kundennummer: $kundennummer</p>
        ";

    $result .= createFieldset(
        'Welche Fahrzeugklasse bevorzugen Sie:',
        getMietwagenConfig('fahrzeugklasse'),
        'fahrzeugklasse',
        'radio',
        'kleinwagen',
    );

    $result .= createFieldset(
        'Welche Zusatzausstattung wünschen Sie:',
        getMietwagenConfig('zusatzausstattung'),
        'zusatzausstattung[]',
        'checkbox',
    );

    $result .= createSelect(
        'Wo wollen sie das Auto abholen:',
        getMietwagenConfig('abholort'),
        'abholort',
        'Autohaus Nettmann',
    );

    $result .= "
        <input type='reset' value='Löschen'>
        <button type='submit' name='submitted'>Absenden</button>
    </form>
    ";

    return $result;
}

/**
 * setMietwagenBeleg renders the beleg for the mietwagen
 *
 * @return string
 */
function setMietwagenBeleg(): string
{
    $result = "<main>";
    list($fahrzeugklasse, $price) = explode(';', $_POST['fahrzeugklasse']);
    $values = ['fahrzeugklasse' => $fahrzeugklasse, 'bruttobetrag' => $price, 'zusatzausstattung' => $_POST['zusatzausstattung'] ?? ['Nichts angegeben'], 'abholort' => $_POST['abholort']];
    if ($values['abholort'] == 'unbekannt') {
        $result .= "
            <h2 style='color: red;'>Bitte geben Sie einen gültigen Abholort ein!</h2>
            <button><a href='mietwagenFormular.php' style='font-size: 2em; text-decoration: none'>Zurück zum Formular</a></button>
            ";
        return $result;
    }

    $kundennummer = $_SESSION['kundennummer'] ?? '';
    $result .= "
    <p>Kundennummer: $kundennummer</p>
    <table>
    ";

    foreach ($values as $key => $value) {
        $ucfKey = ucfirst($key);
        $result .= "
            <tr>
            <td>$ucfKey:</td>
            ";
        if ($key === "zusatzausstattung") {
            $result .= "<td>";
            foreach ($value as $item) {
                $result .= "<p>$item</p>";
            }
            $result .= "</td>";
        } else {
            $result .= "<td>$value</td>";
        }
        $result .= "</tr>";
    }
    $result .= "
    </table>
    <form action='mietwagenFormular.php' method='post'>
        <button type='submit'>Zurück</button>
    </main>
    ";

    return $result;
}

/**
 * setLogin renders the login form
 *
 * @return string
 */
function setLogin(): string
{
    return "
    <form action='mietwagenFormular.php' method='post'>
        <table>
            <tbody>
                <tr>
                    <td>
                        <label for='username'>User ID:</label>
                    </td>
                    <td>
                        <input type='text' id='username' name='username' required><br><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for='password'>Password:</label>
                    </td>
                    <td>
                        <input type='password' id='password' name='password' required><br><br>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type='submit'>Anmelden</button>
    </form>
    ";
}