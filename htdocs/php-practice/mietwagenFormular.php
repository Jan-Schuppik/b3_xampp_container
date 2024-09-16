<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Autohaus Nettmann</title>
</head>
<body style="display: grid; place-items: center;">
<div style="width: min(100vw, 600px)">
    <div style="display: flex; justify-content: space-between">
        <p><a href="#">Zutrittsversuche</a> | <a href="#">Mitarbeiter</a> | <a href="#">Mietwagen</a></p>
        <img src="../img/Nettmann_logo.png" alt="Nettmann Logo" width="250" height="80">
    </div>
    <h1>Mietwagen</h1>
    <form action="mietwagenBeleg.php" method="post">
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
        <hr color="blue"/>
        <div style="display: flex; justify-content: space-between">
            <div>
                <p>Kontakt:</p>
                <ul>
                    <li>Ottostraße 22, 90652 Fürth/li>
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
    </form>
</div>
</body>
</html>


