<?php

/**
 * setHeader sets the header
 *
 * @param string $heading
 * @param string $title
 *
 * @return string
 */
function setHeader(string $heading, bool $loggedIn = false, string $title = 'Autohaus Nettmann'): string
{

    return "
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>$title</title>
        <style>
    table {
        row-gap: 10px;
            }

            td {
        align-content: start;
                align-items: start;
            }

            tr {
        line-height: 20px;
            }

            td > p {
        margin: 0;
        padding: 0;
    }
        </style>
    </head>
    <body style='display: grid; place-items: center;'>
    <div style='width: min(100vw, 600px)'>
        <header style='display: flex; justify-content: space-between'>
            <img src='../img/Nettmann_logo.png' alt='Nettmann Logo' width='250' height='80'>
        " .
        ($loggedIn ? "<nav><a href='#'>Zutrittsversuche</a> | <a href='#'>Mitarbeiter</a> | <a href='#'>Mietwagen</a></nav>" : '')
        . "
        </header>
            <h1$heading</h1>
    ";
}

/**
 * setFooter sets the footer
 *
 * @param string $footerAction
 *
 * @return string
 */
function setFooter(string $footerAction = ''): string
{
    return "
    <footer>
        <hr color='blue'/>
        $footerAction
        <div style='display: flex; justify-content: space-between'>
            <div>
                <p>Kontakt:</p>
                <ul>
                    <li>Ottostraße 22, 90652 Fürth</li>
                    <li>0911/11...</li>
                    <li>info@autohaus-nettmann.de</li>
                </ul>
            </div>
            <div>
                <p>Bankdaten:</p>
                <ul>
                    <li>IBAN:DE761231 ...</li>
                    <li>BIC: 123 ...</li>
                    <li>Institut: SuperBank</li>
                </ul>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
";
}


/**
 * createInput creates an input element
 *
 * @param string $type
 * @param string $kind
 * @param string $name
 * @param bool $checked
 * @param bool $required
 *
 * @return string
 */
function createInput(
    string $type,
    string $kind,
    string $name,
    string $value,
    bool   $checked = false,
    string $id = '',
): string
{
    $id = $id !== '' ? $id : $name;
    $ucfName = ucfirst($name);
    $checked = $checked ? 'checked required' : '';
    return "
    <input type='$type' name='$kind' id='$id' value='$value' $checked>
    <label for='$name'>$ucfName</label>
    <br/>
    ";
}

/**
 * createFieldset creates a fieldset
 *
 * @param string $legend
 * @param array $entries
 * @param string $kind
 * @param string $type
 * @param unitEnum|null $checkedElement
 *
 * @return string
 */
function createFieldset(
    string $legend,
    array  $entries,
    string $kind,
    string $type,
    string $checkedElement = null,
): string
{
    $result = "
    <fieldset>
        <legend>$legend</legend>
    ";


    foreach ($entries as $value) {
        if (is_object($value)) {
            $id = $value->name;
            $netto = $value->preis;
            $name = "$id  ($netto EUR Netto)";
            $brutto = getBrutto($netto);
            $value = "$id;$brutto";
        } else {
            $id = $value;
            $name = $value;
            $value = ucfirst($value);
        }
        $checked = $id === $checkedElement;
        $result .= createInput($type, $kind, $name, $value, $checked, $id);
    }

    $result .= "
    </fieldset>
    <br/>
    ";

    return $result;
}

/**
 * createSelect creates a select element
 *
 * @param string $name
 * @param array $entries
 * @param string $labelContent
 * @param unitEnum|null $selection
 *
 * @return string
 */
function createSelect(string $labelContent, array $entries, string $name, string $selection = null): string
{
    $result = "
    <label for='$name'>$labelContent</label>
    <br/>
        <select name='$name' id='$name'>
    ";

    foreach ($entries as $key => $value) {
        $selected = $value == $selection ? 'selected=\'selected\'' : '';
        $ucfValue = ucfirst($value);
        $value = is_string($key) ? $key : $value;
        $result .= "
        <option value='$value' $selected>$ucfValue</option>";
    }

    $result .= "
        </select>
        <br/>
        <br/>
        ";

    return $result;
}

/**
 * createSingleButtonForm creates a single button form
 *
 * @param string $buttonName
 * @param string $action
 *
 * @return string
 */
function createSingleButtonForm(string $buttonName, string $action = 'mietwagenFormular.php'): string
{
    $lcButtonName = strtolower($buttonName);

    return "
    <form action='$action' method='POST'>
        <input type='submit' name='$lcButtonName' value='$buttonName'>
    </form>
    ";
}


/**
 * getHeaderHeading creates the heading for the header
 *
 * @param string $pagename
 *
 * @return string
 */
function getHeaderHeading(string $pagename): string
{
    $heading = '';
    switch ($pagename) {
        case 'Login':
            $heading = '>Login';
            break;
        case 'FailedLogin':
            $heading = ' style="color: red;">Login Failed';
            break;
        case 'MietwagenFormular':
            $heading = '>Mietwagen';
            break;
        case 'MietwagenBeleg':
            $heading = '>Mietwagen - Kundenbeleg';
            break;
    }

    return $heading;
}


/**
 * validateUserCredentials validates the user credentials
 *
 * @param string $username
 * @param string $password
 *
 * @return bool
 */
function validateUserCredentials(string $username, string $password): bool
{
    $usersJson = file_get_contents(__DIR__ . '/../../config/user.json');
    $users = json_decode($usersJson);

    if (isset($users->$username)) {
        $user = $users->$username;
        if ($user->password === $password) {
            return true;
        }
    }

    return false;
}

/**
 * getKundennummer gets the kundennummer from the user.json
 *
 * @param string $username
 *
 * @return string
 */
function getKundennummer(string $username): string
{
    $usersJson = file_get_contents(__DIR__ . '/../../config/user.json');

    return json_decode($usersJson)->$username->kundennummer;
}

/**
 * getMietwagenConfig gets the mietwagen config
 *
 * @param string $kind
 *
 * @return array
 */
function getMietwagenConfig(string $kind): array
{
    $configJson = file_get_contents(__DIR__ . '/../../config/mietwagen.json');
    $config = json_decode($configJson);

    return (array)$config->$kind;
}


/**
 * debugOutput outputs the plain source code
 *
 * @param string $str
 * @return void
 */
function debugOutput(string $str): void
{
    echo "<pre>";
    var_dump(htmlspecialchars($str));
    echo "</pre>";

}

/**
 * getBrutto calculates the brutto from the netto
 *
 * @param float $netto
 *
 * @return float
 */
function getBrutto(float $netto): float
{
    return ceil($netto * 1.19 * 100) / 100;
}

