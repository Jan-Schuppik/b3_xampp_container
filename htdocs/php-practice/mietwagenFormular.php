<?php

enum Abholort: string
{
    case TS = "Tankstelle";
    case SB = "S-Bahnstation";
    case AN = "Autohaus Nettmann";
    case HBF = "Hauptbahnhof";
}

enum Fahrzeugklasse: string
{
    case KLEINWAGEN = "kleinwagen";
    case MITTELKLASSE = "mittelklasse";
    case FAMILIENWAGEN = "familienwagen";
    case LUXUSKLASSE = "luxusklasse";
}

enum Zusatzausstattung: string
{
    case KLIMAANLAGE = "klimaanlage";
    case NAVIGATOR = "navigator";
    case STANDHEIZUNG = "standheizung";
}

renderPage();

/**
 * renderPage renders the page
 *
 * @return void
 */
function renderPage(): void
{
    echo '
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Autohaus Nettmann</title>
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
    <body style="display: grid; place-items: center;">
    <div style="width: min(100vw, 600px)">
        <header style="display: flex; justify-content: space-between">
            <p><a href="#">Zutrittsversuche</a> | <a href="#">Mitarbeiter</a> | <a href="#">Mietwagen</a></p>
            <img src="../img/Nettmann_logo.png" alt="Nettmann Logo" width="250" height="80">
        </header>
    ';
    if (empty($_POST)) {
        echo '<h1>Mietwagen</h1>';
        renderMietwagenForm();
    } else {
        echo '<h1>Mietwagen - Kundenbeleg</h1>';
        renderMietwagenBeleg();
    }

    echo '
    <footer>
        <hr color="blue"/>
        <div style="display: flex; justify-content: space-between">
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
    ';

}

/**
 * renderMietwagenForm renders the form for the mietwagen
 *
 * @return void
 */
function renderMietwagenForm(): void
{
    echo '
    <form action="mietwagenFormular.php" method="post">
        <label for="kundennummer">Kundennummer:</label>
        <input type="text" id="kundennummer" name="kundennummer"><br><br>
        ';

    echo createFieldset(
        'Welche Fahrzeugklasse bevorzugen Sie:',
        Fahrzeugklasse::cases(),
        'fahrzeugklasse',
        'radio',
        Fahrzeugklasse::KLEINWAGEN
    );

    echo createFieldset(
        'Welche Zusatzausstattung wünschen Sie:',
        Zusatzausstattung::cases(),
        'zusatzausstattung[]',
        'checkbox'
    );

    echo createSelect(
        'abholort',
        Abholort::cases(),
        'Wo wollen sie das Auto abholen:',
        Abholort::AN
    );

    echo '
        <input type="reset" value="Löschen">
        <button type="submit">Absenden</button>
    </form>
    ';
}

/**
 * renderMietwagenBeleg renders the beleg for the mietwagen
 *
 * @return void
 */
function renderMietwagenBeleg() :void
{
    echo "<main>";
    if (empty($_POST["kundennummer"])) {
        echo "
        <h2 style='color: red;'>Bitte geben Sie eine Kundennummer ein!</h2>
        <button><a href='mietwagenFormular.php' style='font-size: 2em; text-decoration: none'>Zurück zum Formular</a></button>
        ";

    } else {
        $values = $_POST;
        if ($values['abholort'] == 'unbekannt') {
            echo "
            <h2 style='color: red;'>Bitte geben Sie einen gültigen Abholort ein!</h2>
            <button><a href='mietwagenFormular.php' style='font-size: 2em; text-decoration: none'>Zurück zum Formular</a></button>
            ";
            return;
        }

        echo "<table>";

        foreach ($values as $key => $value) {
            $ucfKey = ucfirst($key);
            echo "
            <tr>
            <td>$ucfKey:</td>
            ";
            if ($key === "zusatzausstattung") {
                echo "<td>";
                foreach ($value as $item) {
                    echo "<p>$item</p>";
                }
                echo "</td>";
            } else {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</main>";

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
function createInput(string $type, string $kind, string $name, bool $checked = false, bool $required = false): string
{
    $ucfName = ucfirst($name);
    $checked = $checked ? 'checked' : '';
    $required = $required ? 'required' : '';
    return "
    <input type='$type' name='$kind' id='$name' value='$ucfName' $checked $required>
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
function createFieldset(string $legend, array $entries, string $kind, string $type, unitEnum $checkedElement = null): string
{
    $checkedElement = $checkedElement?->value;
    $values = array_map(function($entry) {return $entry->value;}, $entries);

    $result = "
    <fieldset>
        <legend>$legend</legend>
    ";


    for ($i = 0; $i < count($values); $i++) {
        $value = $values[$i];
        $checkedAndRequired = $value === $checkedElement;
        $result .= createInput($type, $kind, $value, $checkedAndRequired, $checkedAndRequired);
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
function createSelect(string $name, array $entries, string $labelContent, unitEnum $selection = null): string
{
    $selection = $selection?->value;
    $values = array_map(function($entry) {return $entry->value;}, $entries);

    $result = "
    <label for='$name'>$labelContent</label>
    <br/>
        <select name='$name' id='$name'>
    ";

    foreach ($values as $value) {
        $selected = $value == $selection ? 'selected=\'selected\'' : '';
        $ucfValue = ucfirst($value);
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