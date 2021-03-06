<!--
  - @file de.md
  -
  - @license http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
  -
  - @package OSTEPU (https://github.com/ostepu/system)
  - @since 0.3.4
  -
  - @author Till Uhlig <till.uhlig@student.uni-halle.de>
  - @date 2015
 -->

#### Datenbank
Die DBGroup ermöglicht den Zugriff auf die `Group` Tabelle der Datenbank, dabei sollen
Arbeitsgruppen verwaltet werden.
Dazu wird bei einem `POST /platform` Aufruf die nachstehende Tabelle erzeugt.

| Spalte        | Struktur  | Beschreibung | Besonderheit |
| :------       |:---------:| :------------| -----------: |
|U_id_leader|INT NOT NULL| ein Verweis auf ein Nutzerkonto, dem dieser Eintrag gehört |-|
|U_id_member|INT NOT NULL| ein Verweis auf ein Nutzerkonto, in dessen Gruppe dieser Nutzer ist |-|
|C_id|INT NULL| ein Verweis auf eine Veranstaltung |-|
|ES_id|INT NOT NULL| ein Verweis auf die zugehörige Übungsserie |-|

Jeder Nutzer besitzt in jeder Übungsserie einen solchen Eintrag. Dabei steht `U_id_leader`
für den Besitzer der Zeile und `U_id_member` für die ID des Nutzers, in
dessen Gruppen der `U_id_leader` in dieser Übungsserie ist (beim Anlegen der
Übungsserie wird daher `U_id_leader`=`U_id_member` gelten, da jeder zunächst seiner eigenen
Gruppe zugeordnet ist.

#### Datenstruktur
Zu dieser Tabelle gehört die `Group` Datenstruktur.

#### Eingänge
- userid = die ID eines Nutzers (`User`)
- courseid = die ID einer Veranstlatung (`Course`)
- esid = die ID einer Übungsserie (`ExerciseSheet`)

| Bezeichnung  | Eingabetyp  | Ausgabetyp | Befehl | Beschreibung |
| :----------- |:-----------:| :---------:| :----- | :----------- |
|editGroup|Group|Group|PUT<br>/group/user/:userid/exercisesheet/:esid| editiert einen Gruppeneintrag |
|deleteGroup|-|Group|DELETE<br>/group/user/:userid/exercisesheet/:esid| enfernt einen Gruppeneintrag (kann danach in dieser Übungsserie nichts mehr einsenden) |
|addGroup|Group|Group|POST<br>/group| fügt einen Gruppeneintrag hinzu (wird beim erstellen einer neuen Übungsserie für alle Teilnehmer der Veranstaltung automatisch durchgeführt) |
|getUserGroups|-|Group|GET<br>/group/user/:userid| liefert alle Gruppeneinträge eines Nutzerkontos |
|getAllGroups|-|Group|GET<br>/group(/group)| liefert alle Gruppeneinträge (für alle veranstaltungen) |
|getUserSheetGroup|-|Group|GET<br>/group/user/:userid/exercisesheet/:esid| gibt den Gruppeneintrag eines Nutzers für eine Übungsserie aus |
|getSheetGroups|-|Group|GET<br>/group/exercisesheet/:esid| liefert alle Gruppen einer Übungsserie |
|getCourseGroups|-|Group|GET<br>/group/course/:courseid| liefert alle Gruppen einer Veranstaltung |
|addPlatform|Platform|Platform|POST<br>/platform|installiert dies zugehörige Tabelle und die Prozeduren für diese Plattform|
|deletePlatform|-|Platform|DELETE<br>/platform|entfernt die Tabelle und Prozeduren aus der Plattform|
|getExistsPlatform|-|Platform|GET<br>/link/exists/platform| prüft, ob die Tabelle und die Prozeduren existieren |

#### Ausgänge
- userid = die ID eines Nutzers (`User`)
- courseid = die ID einer Veranstlatung (`Course`)
- esid = die ID einer Übungsserie (`ExerciseSheet`)

| Bezeichnung  | Ziel  | Verwendung | Beschreibung |
| :----------- |:----- | :--------- | :----------- |
|out2|DBQuery2|POST<br>/query| wird für EDIT, DELETE<br>und POST<br>SQL-Templates verwendet |
|out2|DBQuery|POST<br>/query| wird für EDIT, DELETE<br>und POST<br>SQL-Templates verwendet |
|getUserGroups|DBQuery|GET<br>/query/procedure<br>/DBGroupGetUserGroups/:userid| Prozeduraufruf |
|getSheetGroups|DBQuery|GET<br>/query/procedure<br>/DBGroupGetSheetGroups/:esid| Prozeduraufruf |
|getUserSheetGroup|DBQuery|GET<br>/query/procedure<br>/DBGroupGetUserSheetGroups/:userid/:esid| Prozeduraufruf |
|getCourseGroups|DBQuery|GET<br>/query/procedure<br>/DBGroupGetCourseGroups/:courseid| Prozeduraufruf |
|getAllGroups|DBQuery|GET<br>/query/procedure<br>/DBGroupGetAllGroups| Prozeduraufruf |
|getExistsPlatform|DBQuery2|GET<br>/query/procedure<br>/DBGroupGetExistsPlatform| Prozeduraufruf |

#### Anbindungen
| Bezeichnung  | Ziel  | Priorität | Beschreibung |
| :----------- |:----- | :--------:| :------------|
|request|CLocalObjectRequest|-| damit DBGroup als lokales Objekt aufgerufen werden kann |

Stand 13.06.2015