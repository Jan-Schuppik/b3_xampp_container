<?php

enum Abholort: string
{
    case TS = "Tankstelle";
    case SB = "S-Bahnstation";
    case AN = "Autohaus Nettmann";
    case HBF = "Hauptbahnhof";
}

renderPage();


function getAbholort(String $string): string
{
    return match ($string) {
        "TS" => Abholort::TS->value,
        "SB" => Abholort::SB->value,
        "AN" => Abholort::AN->value,
        "HBF" => Abholort::HBF->value,
        default => "Unbekannt",
    };
}

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

function renderMietwagenForm(): void
{
    echo '
    <form action="mietwagenFormular.php" method="post">
        <label for="kundennummer">Kundennummer:</label>
        <input type="text" id="kundennummer" name="kundennummer"><br><br>
        <fieldset aria-required="true">
            <legend>Welche Fahrzeugklasse bevorzugen Sie:</legend>
            <input type="radio" name="fahrzeugklasse" value="Kleinwagen" id="kleinwagen" required checked>
            <label for="kleinwagen">Kleinwagen</label>
            <br/>
            <input type="radio" name="fahrzeugklasse" value="Mittelklasse" id="mittelklasse">
            <label for="mittelklasse">Mittelklasse</label>
            <br/>
            <input type="radio" name="fahrzeugklasse" value="Familenwagen" id="familienwagen">
            <label for="familienwagen">Familienwagen</label>
            <br/>
            <input type="radio" name="fahrzeugklasse" value="Luxusklasse" id="luxusklasse">
            <label for="luxusklasse">Luxusklasse</label>
        </fieldset>
        <br/>
        <fieldset>
            <legend>Welche Zusatzausstattung wünschen Sie:</legend>
            <input type="checkbox" name="zusatzausstattung[]" value="Klimaanlage" id="klimaanlage">
            <label for="klimaanlage">Klimaanlage</label>
            <br/>
            <input type="checkbox" name="zusatzausstattung[]" value="Navigator" id="navigator">
            <label for="navigator">Navigator</label>
            <br/>
            <input type="checkbox" name="zusatzausstattung[]" value="Standheizung" id="Standheizung">
            <label for="Standheizung">Standheizung</label>
        </fieldset>
        <br/>
        <label for="abholort">Wo wollen sie das Auto abholen:</label>
        <br/>
        <select name="abholort" id="abholort">
            <option value="TS">Tankstelle</option>
            <option value="SB">S-Bahnstation</option>
            <option value="AN" selected="selected">Autohaus Nettmann</option>
            <option value="HBF">Hauptbahnhof</option>
        </select>
        <br/>
        <br/>
        <input type="reset" value="Löschen">
        <button type="submit">Absenden</button>
    </form>
    ';
}


function renderMietwagenBeleg() :void
{
    echo "<main>";
    if (empty($_POST["kundennummer"])) {
        echo "<h2 style='color: red;'>Bitte geben Sie eine Kundennummer ein!</h2>";
        echo "<button><a href='mietwagenFormular.php' style='font-size: 2em; text-decoration: none'>Zurück zum Formular</a></button>";

    } else {
        $values = $_POST;
        $values['abholort'] = getAbholort($_POST["abholort"]);
        if ($values['abholort'] == 'unbekannt') {
            echo "<h2 style='color: red;'>Bitte geben Sie einen gültigen Abholort ein!</h2>";
            echo "<button><a href='mietwagenFormular.php' style='font-size: 2em; text-decoration: none'>Zurück zum Formular</a></button>";
            return;
        }

        echo "<table>";

        foreach ($values as $key => $value) {
            echo "<tr>";
            if ($key === "zusatzausstattung") {
                echo "<td>Zusatzausstattung:</td>";
                echo "<td>";
                foreach ($value as $item) {
                    echo "<p>$item</p>";
                }
                echo "</td>";
            } else {
                echo "<td>$key:</td>";
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</main>";

}


