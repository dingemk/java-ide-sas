<?php
/*
 * txt_engine.11.13.php
 * menu-reiter erzeugt relative Links innerhalb des aktuellen Produkts.
 */
/*//============================================================================
    class txt_engine

    Autoren: Berthold Hufnagel (hufnagel@net-schulbuch.de)
             Klaus Dingemann (klaus@dingemann.de)
    Start: 21.Dezember 2012

    ACHTUNG: In dieser Dokumentation stehen die jüngsten Änderungen (=höchste Versionsnummer) oben!

    11.12 28.09.2026
    Aussenabstand der Dropdown-Panels ddp und ddpmenu reduziert.

    11.11 27.09.2026
    Karussellpfeile und vertikale Ausrichtung der Vorschaubilder fuer Bootstrap 5.

    11.10 27.09.2026
    Karussells verwenden die Bootstrap-5-Klasse carousel-item.

    11.09 27.09.2026
    Bootstrap-5-Datenattribute fuer Collapse, Modal und Karussell.

    11.08 27.09.2026
    Quellcode-Shortcuts lesen ihre Parameter auch bei fehlenden Dateien vollstaendig,
    damit nachfolgende TCML-Shortcuts weiterhin ausgewertet werden.

    10.06 19.10.2023
    Es wurde die Möglichkeit hinzugefügt, speicherbare Textfelde in das buch einzufügen mit
    [@[savetext::|Überschrift (darf auch Leerzeichen enthalten)|::|Anzahl der Zeilen|::|Anzahl der Spalten|]@]

    09.48 25.3.2022 dingemann
    Bereits in früheren Versionen wurde die Einbindung eines Glossars hinzugefügt
    [@[glossar::<begriff>_<buch::<beliebige Überschrift>]]

    Lückentext mit verschiebbaren Wörtern wurde hinzugefügt
    [@[dndluecke::<Lösungswörter durch | getrennt::<Text mit # als Platzhalter für gesuchte Wörter>]]

    Handschrifteingabe für Aufgabenlösungen wurde integriert
    [@[handschrift::<Aufgabentext]@]

    10.02 24.08.2023 Dingemann
      DIe Geogebra-Dateien wurden auf die neue Version 6.0 Teilwiese wurden die Variablennamen geändert, um Doppelbezeiichnungen zu vermeiden
      Nicht mehr benötigte Funktionen (geogebra2d, geogebramp wurden gelöscht.)

    09.33 19.02.2021 hufnagelb

    09.31 06.02.2021 hufnagelb
    neuen shortcut [@[hinweis::|Hinweis|::|Text|]@] eingefügt mit "Fernglas"
        auf blauem Hintergrund
    neuen shortcut [@[net-codet::|netcode|::|Text|]@] eingefügt, der einen8
        Link für einen Netcode in einem neuen TAB öffnet
    Textumbruch in PUW entfernt dadurch, dass modale Fenster an den
        Anfang der Content-Ausgabe geschrieben werden.

    09.30 08.11.2020 dingemann
    geogebra3d wurde so geändert, dass jetzt auch mehrere Apss in eine Seite eingeführt werden können.

    09.28 dingemann 21.10.2020
    [@[notiz::!!Anzahl der Zeilen!::!Anzahl der Spalten!]@]
    Fügt ein Textfeld für Notizen ein, dessen Inhalt mit dem Button "kopieren" in die Zwischenablage kopiert werden kann.


    09.25  hufnagel    12.07.2020
    Die Funktion include-file um die Option "htmlo" erweitert.
    "htmlo" = HTML-Original
    ersetzt den Aufruf durch den Inhalt der benannten HTML-Datei.
    ACHTUNG: die HTML-Datei sollte keine Umlaute, sondern die in
    HTML vorgesehenen Spezial-Zeichen enthalten.


    27.11.2019 Kleinere Fehler beseitigt
    [@[dragdrop2::
    eingefügt. Erlaubt mehrere dragdrop-Aufgaben auf einer Seite
    [@[checkboxes2::
    eingefügt, stellt Checkboxen in einer Zeile dar
    [@[edittableol::..
    eingefügt. Editierbare Tabelle ohne Lösungsbutton

    18.11. Löschen-Button beim Bilderverschieben eingefügt
    Lösen Button bei checkboxen und radiobuttons entfernt
    (dragdrop)

    5.11.: Drop-Down-Panel eingefügt (ddp);

    09.04
    27.07.2019 Klaus Dingemann
        Korrekturen und Ergänzungen
    02.08.2019 Berthold Hufnagel
        Dokumentation überarbeitet

    09.03
    10.07.2019 Berthold Hufnagel (hufnagel@net-schulbuch.de)
        Diese Doklumentation überarbeitet und ergänzt

    09.02
    17.05.2018 Klaus Dingemann

    09.01
    15.01.2019 Klaus Dingemann

    08.43
    24.05.2018 Berthold Hufnagel (hufnagel@net-schulbuch.de)
        Umstellung auf https

    08.41
    24.08.2018 Klaus Dingemann
        Kurztipp erweitert zur Verschachtelung mehrerer [@[...]@]-Elemente
        Neue Funktion inner

    21.04.2018 Berthold Hufnagel (hufnagel@net-schulbuch.de)
        class Textzeile auskommentiert, da sie offensichtlich nix tut!?
        inner() auskommentiert. Diese globale Funktion erzeugt ggf. Namenskonflikte.
            An Stelle des Aufrufs dieser Funktion wird eine lokale Instanz der Klasse erzeugt
            und deren output-Attribut genutzt.
        In saveHeadline($text) die Berücksichtigung eines leeren Pfades ergänzt.
        geogebra2d (...) ergänzt durch "HTML-Rahmen"
            Achtung: für diesen Rahmen muss die bootstrap-Definition panel-body lokal manipuliert
            werden durch setzen von padding auf den Wert 0px. Das verhindert einen zusätzlichen Abstand,
            der durch die Verwendung der umgebenden table-responsive im Inhalt des panel-body entsteht.
        analog wurde die Funktionen geogebra, geogebra3d und geogebratube überarbeitet.
        kurztipp () überarbeitet
        weblink()  ergänzt:
            [@[weblink::httplink::Linktext::Vorschaubild::ErlaeuternderText]@]
            httplink: muss mit https:// oder https:// beginnen
            Linktext: zum Anklicken angezeigter Text
            Vorschaubild: muss hochgeladen sein; Dateiname mit Pfad angeben
            ErlaeuternderText: Enthält den Text links neben dem Bild

    17.04.18 Klaus Dingemann
        Fehler beim Suchen korrigiert

    21.03.2018 Klaus Dingemann
        Neue Komponente [@[geogebra..]]
        Einbinden von Geogebra-Dateien

    08.03.2018 Klaus Dingemann
        Automatische Speichern der h1 Überschriften in der Such-Datenbank
        [@[search...
        Suchwörter speichern

    28.09.2017 Klaus Dingemann (klaus@dingemann.de)
        Neue Komponenten hinzugefügt:
        [@[lueckentext::...]@]
        [@[dragdrop::...]@]
        [@[dragline::...]@]
        [@[checkboxe::...]@]
        [@[checkboxes::...]@]
        [@[radiobuttons::...]@]
        [@[search ... eingefügt

    09.08.2017 Berthold Hufnagel (hufnagel@net-schulbuch.de)
        CSS-Klassen zur Unterscheidung zwischen Print- und Browser-Ausgabe ergänzt:
        CSS-Klassen für das Verbergen von Elementen in der Druckausgabe, d.h. die  Elemente
        sind nur im Browser sichtbar
            .hidden-print
        CSS-Klassen für das Verbergen in der Browserausgabe, d.h. die Elemente sind nur in
        der Druckausgabe sichtbar
            .visible-print-block .visible-print-inline .visible-print-inline-block

    26.04.2017 Klaus Dingemann (klaus@dingemann.de)
        [@[editVT::... hinzugefügt editierbare Vierfeldertafel
        Stand 14.05.2017 Version 08.17 Berthold Hufnagel
        [@[exec-php::net-code]@]   hinzugefügt. Es werden als php-Seiten erzeugte Dateien
            ausgeführt und das Ergebnis includiert.
        [@[php-text::net-code]@]

    21.03.2017 Klaus Dingemann (klaus@dingemann.de)
        function java_source überarbeitet Quelltext kann jetzt ohne Zeilennummern angezeigt werden
        function java_text neu eingefügt
        [@[javatext::ITextI]@] wird mit Highlighting angezeigt
        [@[karussel:: ... hinzugefügt
        [@[edittable::... hinzugefügt editierbare Tabelle horizontal mit Prüf und Lösen-Button

    11.03.2017 Klaus Dingemann (klaus@dingemann.de)
        parser javasource ergänzt
        function java_source überarbeitet
        Neue Funktionen eingefügt: kurztipp, tippbild, tippvideo Spoiler für Tipps



*/ //============================================================================
_debug('include', __FILE__); { // INIT
   _mark(__LINE__, __FILE__);
   _validate_necessary_constants('_AUTHOR_PATH');

   // Symbolfolgen für Anfang, Ende und Separator einer TCML-Sequenz
   _create_constant('TCML_BEGIN', '[@[');
   _create_constant('TCML_END', ']@]');
   _create_constant('TCML_SEP', '::');

   include_once(_script('filesystem'));
   include_once(_script('db_search'));
   include_once(_script('db_texte'));
   $gloss = array();
}
class Glossar
{
   public $modalnr;
   public $buch;
   public $datei;
   public $begriff;

   function __construct($mn, $b, $dat, $bg)
   {
      $this->modalnr = $mn;
      $this->buch = $b;
      $this->datei = $dat;
      $this->begriff = $bg;
   }

   function gibLink()
   {
      $link = strtolower("https://glossar.net-schulbuch.de/" . $this->buch . "/index.php?s=" . $this->datei . "");
      // $filename = $b.$bg.".txt";
      //$file = fopen($filename,"w");
      //fwrite($file,$link);
      return $link;
   }

   function gibModfensterLink()
   {
      return txt_engine::modalLink('puw' . $this->modalnr, $this->begriff, '', 'style="display:inline;"');
   }

   function gibModalfenster()
   {
      $modalId = 'puw' . $this->modalnr;
      $iframeId = $this->modalnr . 'inhalt';
      $onClose = 'changeSrc' . $this->modalnr . '()';
      $before = '
          <style>
                #' . $modalId . ' .modal-header { position:relative; padding-right:3.5rem; }
                #' . $modalId . ' .modal-header .close {
                  position:absolute; top:0.75rem; right:1rem; z-index:1;
                  float:none; margin:0; padding:0; border:0; background:transparent;
                  font-size:2rem; line-height:1; opacity:0.7;
                }
                #' . $modalId . ' .modal-header .close:hover,
                #' . $modalId . ' .modal-header .close:focus { opacity:1; }
          </style>
          <script>
                function changeSrc' . $this->modalnr . '() {
                  document.getElementById("' . $iframeId . '").src="' . $this->gibLink() . '";
                }
          </script>';
      $body = '<iframe src="' . $this->gibLink() . '" width="100%" height="98%" style="border-width:0px;" id="' . $iframeId . '" name="inhalt" onchange="merken(src)" allow="camera; microphone"></iframe>';
      $footer = '<button class="btn btn-danger" id="' . $this->begriff . '" type="button" onclick="' . $onClose . '" data-bs-dismiss="modal">schliessen</button>';
      return txt_engine::modalHtml($modalId, $this->begriff, $body, $footer, 'id="dialogmodalfenster" style="width:calc(100vw - 2rem); max-width:1200px; margin-left:auto; margin-right:auto;"', 'style="width:100%; overflow-y:scroll;"', 'style="height:80vh;" id="mbody"', 'h1', $before, $onClose);
   }
}

class Textzeile
{
   public $zeilennummer;
   public $text;

   function __construct($zn, $txt)
   {
      $this->zeilennummer = $zn;
      $this->text = $txt;
   }
}

class Bildzeile
{
   public $zeilennummer;
   public $url;

   function __construct($zn, $txt)
   {
      $this->zeilennummer = $zn;
      $this->url = $txt;
   }
}


class txt_engine
{


   private $input;
   public $output;
   public $modal;   //neu in Version 31
   public $modalnr;   //neu in Version 31
   private $iRequest;
   //---var $stack;
   private $file;
   private $path;
   private $view;
   private $inplen;    //inputlength
   //---var $nowrap = 0; // Zeilenumbruchstack, wenn > 0, dann kein Umbruch
   private $istable = false; // Tabellenflag
   //---var $flag_aufzaehlung = 0;
   private $debug = 0;
   private $debuglast = -1;
   private $syntaxhighlighter = false;
   private $nowrap = 0; // Zeilenumbruchstack, wenn > 0, dann kein Umbruch
   private $tablenumber = 0;
   private $suchdatenbank;
   private $textedatenbank;


   function __construct(&$tcml_content, &$iRequest, $search = true)
   {
      /*
       Der Inhalt der durch den übergebenen Netcode referenzierten Datei wird per
       Call-by-Reference übergeben.
       Ebenso wird eine Reference auf das iRequest-Objekt übergeben.
       Der Parser dieser Engine liefert sein Ergebnis nach Aufruf des Construktors
       in der public VAR $output ab, aus der es vom instantiierenden Objekt abgeholt
       werden kann.
       Das Schreiben in die Variable $output übernimmt die Methode write dieses Objekts.
       Soll die Engine um ein Feature erweitert werden, muss dieses die Form
       [@[<Stichwort>:: ... ]@]
       haben. In der zentralen Fallunterscheidung (switch) des Parsers muss das neue
       Stichwort eingepflegt und mit einer eigenen auswertenden Methode versehen werden. Dieser Methode müssen mindestens die Parameter $s und $p per Call-By-Reference übergeben werden.
           $s enthält den von der Methode get_Symbol() übergebenen String
           $p zeigt auf die nächste Position im zu parsenden Input-String
       Es sind ausreichend Beispiele für auswertende Methoden vorhanden.
   */
      ///_ddebug ('$tcml_content', $tcml_content, __LINE__, __FILE__);
      $this->input = &$tcml_content;
      //$this->input = $script.$this->input;
      $this->iRequest = &$iRequest;
      $this->output = '';
      $this->modal = '';
      $this->modalnr = 100;
      $p = 0;
      $this->inplen = strlen($this->input);
      $l = strlen($this->input);
      if ($l < 1) {
         return;
      }
      // debug ($l . '[[' . htmlentities($this->input) . ']]', '$this->input', '', tcml_engine', __LINE__, __FILE__);
      $this->suchdatenbank = new suchedatenbank(_ini('productkey'));
      $this->textedatenbank = new schuelerTexteDatenbank(_ini('productkey'));

      if ($search) {
         $this->saveHeadline($this->input);
      }
      $s = $this->get_symbol($p);
      do {
         $this->parse($s, $p);
      } while ($p < $l);
      $this->parse($s, $p);
      $this->write('<script>
   document.addEventListener("fullscreenchange", changeButton);
   document.addEventListener("webkitfullscreenchange", changeButton);
   document.addEventListener("mozfullscreenchange", changeButton);
   document.addEventListener("msfullscreenchange", changeButton);
   function changeButton() {
      var element =  $("#fullscreenlink").find("span");
      if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement){
       element.removeClass("glyphicon glyphicon-resize-small");
       element.addClass("glyphicon glyphicon-resize-full");
      } else {
         element.removeClass("glyphicon glyphicon-resize-full");
         element.addClass("glyphicon glyphicon-resize-small");
      }
    }
</script>');
      $this->output = $this->modal . "\n" . $this->output;
   } //constructor

   //Speichert die h1-Überschrift in der Suchdatenbank
   function saveHeadline($text)
   {
      $h1anf = strpos($text, "<h1>");
      $h1end = strpos($text, "</h1>");
      $netCode = $GLOBALS['_iRequest']->value('s');
      if ($h1anf < $h1end) {
         $ueberschrift = substr($text, $h1anf + 4, $h1end - $h1anf - 4);
         $ersterUnterstrich = strpos($netCode, '_');
         $rest = substr($netCode, $ersterUnterstrich + 1);
         $zweiterUnterstrich = strpos($rest, '_');
         $pfad = substr($netCode, 0, strpos($netCode, '_'));
         // 08.41 Achtung: $pfad kann auch leer sein!
         //Keine Lösungen  und keine Lehrerseiten und keine Lehrerseiten speichern

         if (($pfad == '') || ($netCode[strlen($pfad) - 1] != 'u' && $netCode[strlen($pfad) - 1] != 't') && $zweiterUnterstrich === false) {
            $prio = 1;
            $this->suchdatenbank->save($ueberschrift, $netCode, $prio, $ueberschrift);
         }
      } else {
         $this->suchdatenbank->delete($netCode);
      }
   }

   function get_symbol(&$p)
   {
      $l = $this->inplen;
      if ($this->debuglast >= $p) {
         if ($p < $l) {
            $errormsg = 'ERROR: ' . $this->debuglast . '.' . $p . '.' . $l
               . ' [' . substr($this->input, $this->debuglast) . ']';
            $this->error($errormsg, __LINE__);
            $p = 1 + $this->inplen;
         } //if
         else {
            $errormsg = substr($this->input, $p);
            if ($errormsg != '')
               $this->error($errormsg, __LINE__);
            $p = 1 + $this->inplen;
         } //else
      } //if
      else {
         $this->debuglast = $p;
      } //else
      if ($p >= $this->inplen) {
         return '';
      }
      $s = '';
      $p1 = strpos($this->input, TCML_BEGIN, $p);
      $p1 = ($p1 === false) ? ($l + 1) : $p1;
      if ($p1 == $p) {
         $p += strlen(TCML_BEGIN);
         //*****/ echo '[[---' . TCML_BEGIN . '---]]<br />';
         return TCML_BEGIN;
      }
      $p2 = strpos($this->input, TCML_SEP, $p);
      $p2 = ($p2 === false) ? ($l + 1) : $p2;
      if ($p2 == $p) {
         $p += strlen(TCML_SEP);
         //*****/ echo '[[---' . TCML_SEP . '---]]<br />';
         return TCML_SEP;
      }
      $p3 = strpos($this->input, TCML_END, $p);
      $p3 = ($p3 === false) ? ($l + 1) : $p3;
      if ($p3 == $p) {
         $p += strlen(TCML_END);
         //*****/ echo '[[---' . TCML_END . '---]]<br />';
         return TCML_END;
      } else
         $pos = min($p1, $p2, $p3);
      if ($pos > $p) {
         $ph = $p;
         $p = $pos;
         //*****/    echo '[' . htmlentities (substr ($this->input, $ph, $pos - $ph)) . ']';
         $result = substr($this->input, $ph, $pos - $ph);
      } else {
         $ph = $p;
         $p = $l + 1;
         //*****/    echo '[' . htmlentities (substr ($this->input, $ph)) . ']';
         $result = substr($this->input, $ph);
      }
      return $result;
   } //function get symbol


   function parse(&$s, &$p)
   {
      global $_ini;
      $this->debug++;
      if ($this->debug == 500) {
         $errormsg = 'FATAL ERROR: 0.' . $this->debug . '.' . $p . '.' . $this->inplen;
         $this->error($errormsg, __LINE__);
         $this->write(htmlentities(substr($this->input, 0, $p)));
         $errormsg = ' Nicht erwartet: [' . $s . '] ';
         $this->error($errormsg, __LINE__);
         $this->write(htmlentities(substr($this->input, $p)));
         $p = $this->inplen;
      }
      if ($this->debug > 500) {
         $errormsg = 'FATAL ERROR: 0.' . $this->debug . '.' . $p . '.' . $this->inplen;
         $this->error($errormsg, __LINE__);
         $p = 1 + $this->inplen;
      }
      switch ($s) {
         case TCML_BEGIN:
            $s = $this->get_symbol($p);
            switch (strtolower($s)) {
               case 'autor': //  !!!deprecated!!!
                  $this->textstyle($s, $p, '<!-- - - -Autor:', '- - - -->');
                  break;
               case 'exec-php': // [@[exec-php::|net-code|]@]
                  //echo $s.' '.__LINE__.'<br>';
                  $this->exec_php($s, $p);
                  break;
               case 'formel': //  !!!deprecated!!!
                  $this->formel($s, $p);
                  break;
               case 'lesezeichen': //   !!!deprecated!!!
                  $this->textstyle($s, $p, '<!-- - - -Lesezeichen:', '- - - -->');
                  break;
               case 'hilfe':  // [@[hilfe::|net-code|::|ToolTipText|]@]
                  $this->hilfe($s, $p);
                  break;
               case 'include-text':  // [@[include-text::]@]
                  $this->include_file($s, $p, 'text');
                  break;
               case 'include-html':
                  $this->include_file($s, $p, 'html');
                  break;
               case 'include-htmlo':
                  $this->include_file($s, $p, 'htmlo');
                  break;
               case 'include-mml':  // [@[include-mml::|DateinameOhnePfadMitExtension|]@]
                  $this->include_file($s, $p, 'mml');
                  break;
               case 'java-source':
                  $this->include_file($s, $p, 'java_source');
                  break;
               case 'java':
                  $this->java_source($s, $p);
                  break;
               case 'java-datei':
                  $this->java_source($s, $p);
                  break;
               case 'sql-datei':
                  $this->sql_source($s, $p);
                  break;
               case 'php-datei':
                  $this->php_source($s, $p);
                  break;
               case 'python-datei':
                  $this->python_datei($s, $p);
                  break;
               case 'java-text':
                  $this->java_text($s, $p);
                  break;
               case 'sql-text':
                  $this->sql_text($s, $p);
                  break;
               case 'php-text':
                  $this->php_text($s, $p);
                  break;
	               case 'python-text':
	                  $this->python_code($s, $p);
	                  break;
               case 'html-text':
                  $this->html_code($s, $p);
                  break;
               case 'html-datei':
                  $this->html_datei($s, $p);
                  break;
               case '!':
               case 'kommentar':
                  $this->kommentar($s, $p);
                  break;
               case 'menu-reiter':
                  $this->menu_reiter($s, $p);
                  break;
               case 'net-code':
                  $this->net_code($s, $p);
                  break;
               case 'net-codet':
                  $this->net_codet($s, $p);
                  break;
               case 'php-source':
                  $this->include_file($s, $p, 'php_source');
                  break;
               case 'python-source':
                  $this->include_file($s, $p, 'python_source');
                  break;
               case 'python-source-zn':
                  $this->include_file($s, $p, 'python_source_zn');
                  break;
               case 'source-code':
                  $this->include_file($s, $p, 'source_code');
                  break;
               case 'tipp':
                  $this->tipp($s, $p);
                  break;
               case 'tipptext':
                  $this->kurztipp($s, $p);
                  break;
               case 'hinweis':
                  $this->hinweis($s, $p);
                  break;
               /*
          case 'hinweis2':
           $this->hinweis2 ($s, $p);
           break;
           */
               case 'tippauf':
                  $this->tippauf($s, $p);
                  break;
               case 'tippbild':
                  $this->tippbild($s, $p);
                  break;
               case 'tippvideo':
                  $this->tippvideo($s, $p);
                  break;
               case 'ddp':
                  $this->ddp($s, $p);
                  break;
               case 'ddpmenu':
                  $this->ddpmenu($s, $p);
                  break;
               case 'aufg':
                  $this->aufg($s, $p);
                  break;
               case 'karussell':
                  $this->karussell($s, $p);
                  break;
               case 'karussell-thumbs':
                  $this->karussellth($s, $p);
                  break;
               case 'edittable':
                  $this->edittable($s, $p);
                  break;
               case 'edittableol':
                  $this->edittableol($s, $p);
                  break;
               case 'edittable2':
                  $this->edittable2($s, $p);
                  break;
               case 'edittable3':
                  $this->edittable2($s, $p);
                  break;
               case 'edittable2ol':
                  $this->edittable2ol($s, $p);
                  break;
               case 'edittabletext':
                  $this->edittabletext($s, $p);
                  break;
               case 'editvt':
                  $this->editVT($s, $p);
                  break;
               case 'lueckentext':
                  $this->lueckentext($s, $p);
                  break;
               case 'dndluecke':
                  $this->dndluecke($s, $p);
                  break;
               case 'dragdrop':
                  $this->dragdrop2($s, $p);
                  break;
               case 'dragdrop2':
                  $this->dragdrop2($s, $p);
                  break;
               case 'dragline':
                  $this->dragline($s, $p);
                  break;
               case 'dragimg':
                  $this->dragimg($s, $p);
                  break;
               case 'checkboxes':
                  $this->checkboxes($s, $p);
                  break;
               case 'checkboxes1':
                  $this->checkboxes1($s, $p);
                  break;
               case 'checkboxes2':
                  $this->checkboxes2($s, $p);
                  break;
               case 'radiobuttons':
                  $this->radiobuttons($s, $p);
                  break;
               case 'search':
                  $this->search($s, $p);
                  break;
               case 'geogebra':
                  $this->geogebra($s, $p);
                  break;
               case 'geogebramp':
                  $this->unsupported_shortcut($s, $p, 'geogebramp');
                  break;
               case 'geogebra3d':
                  $this->geogebra3d($s, $p);
                  break;
               case 'geogebra2d':
                  $this->geogebra2d($s, $p);
                  break;
               case 'geogebratool':
                  $this->geogebratool($s, $p);
                  break;
               case 'geogebratube':
                  $this->geogebratube($s, $p);
                  break;
               case 'notiz':
                  $this->notiz($s, $p);
                  break;
               case 'weblink':
                  $this->weblink($s, $p);
                  break;
               case 'tooltip':
                  $this->tooltip($s, $p);
                  break;
               case 'puw':
                  $this->puw($s, $p);
                  break;
               case 'glossar':
                  $this->glossar($s, $p);
                  break;
               case 'handschrift':
                  $this->handschrift($s, $p);
                  break;
               case 'imagemap':
                  $this->unsupported_shortcut($s, $p, 'imagemap');
                  break;
               case 'iframe':
                  $this->iframe($s, $p);
                  break;
               case 'netcode_modal':
                  $this->netcode_modal($s, $p);
                  break;
               case 'wtp':
                  $this->wtp($s, $p);
                  break;
               case 'savetext':
                  $this->savetext($s, $p);
                  break;
               default:
                  //$this->write ('<font color="red">' . $s . '</font>');
                  $this->write($s);
                  $s = $this->get_symbol($p);
            } //switch
            break;
         case TCML_END:
            break;
         default:
            $this->write($s);
            $s = $this->get_symbol($p);
      } //switch
   } // function parse


   function tagbegin(&$s, &$p)
   {
      $s = $this->get_symbol($p);
      if ($s != TCML_BEGIN) {
         $this->error($s, __LINE__);
      }
      $s = $this->get_symbol($p);
   } //function


   function tagdivision(&$s, &$p)
   {
      $s = $this->get_symbol($p);
      if ($s != TCML_SEP) {
         $this->error($s, __LINE__);
      }
      $s = $this->get_symbol($p);
   } //function


   function tag_data(&$s, &$p, $skip = false)
   {
      $this->tagdivision($s, $p);
      while (($s != TCML_END) and ($p < $this->inplen)) {
         $this->parse($s, $p);
      }
      if ($s != TCML_END) {
         $this->error($s, __LINE__);
      }
      $s = $this->get_symbol($p);
   } //function tag_data


   function tagend(&$s, &$p)
   {
      $s = $this->get_symbol($p);
      if ($s != TCML_END) {
         $this->error($s, __LINE__);
      }
      $s = $this->get_symbol($p);
   } //function


   function exec_php(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $phpseite = substr($s, strpos($s, '_') + 1) . '.tcml';
      //echo $phpseite.' '.__LINE__.'<br>';
      $s = $this->get_symbol($p);
      if ($s != TCML_END) {
         $this->error(substr($s, $p), __LINE__);
      } else {
         $file = $GLOBALS['_iRequest']->get_SeitePathIntern() . $phpseite;
         if (!file_exists($file)) {
            $this->write('<div class="alert alert-danger" role="alert">Die Datei ' . basename($file) . ' existiert nicht!</div>');
         } else {

            $prgfile = $file . '.prg';
            //***/ _debug ('$file', $file, '', __LINE__, __FILE__);
            if (!file_exists($file)) {
               $content = file_get_contents($prgfile);
               $content = explode(content::delimiter, $content);
               file_put_contents($file, '<?php' . "\n" . $content[1] . "\n" . '?>');
            }
            _mark(__LINE__, __FILE__);
            include_once($prgfile);
            $LocalPage = new localphppage($GLOBALS['_iRequest']->get_SeitePathIntern(), $GLOBALS['_iRequest']->get_SeitePathPublic());
            $this->write($LocalPage->html());
         }
      }
      $s = $this->get_symbol($p);
   }   // function exec_php


   function textstyle(&$s, &$p, $b, $e, $skip = false)
   {
      $this->write($b);
      //*****/ echo '<br />' . __LINE__ . '[[', htmlentities (substr ($this->input, $p, 30)), ']]<br />';
      $this->tag_data($s, $p, $skip);
      $this->write($e);
      //*****/ echo '<br />' . __LINE__ . '[[', htmlentities (substr ($this->input, $p, 30)), ']]<br />';
   } //function textstyle


   function formel(&$s, &$p)
   {
      global $_script;
      $this->tagdivision($s, $p);
      $this->write('<span class="formel">');
      $p3 = strpos($this->input, TCML_END, $p);
      $_line = __LINE__;
      $_file = __FILE__;
      include_once($_script['math']);
      $math = new math($s);
      $this->write($math->html());
      $this->write('</span>');
      $s = $this->get_symbol($p);
      if ($s != TCML_END) {
         $this->error($s, __LINE__);
      }
      $s = $this->get_symbol($p);
   } // function formel


   function kommentar(&$s, &$p)
   {
      global $_script;
      $this->tagdivision($s, $p);
      $s = $this->get_symbol($p);
      if ($s != TCML_END) {
         $this->error($s, __LINE__);
      }
      $s = $this->get_symbol($p);
   } // function kommentar


   function hilfe(&$s, &$p)
   {
      global $_ini;
      $this->tagdivision($s, $p);
      //***/debug ($s, '$s', '', 'tcml_engine:symbol', __LINE__, __FILE__, true);
      $seite = $s;
      $s = $this->get_symbol($p);
      $title = "HILFE";
      if ($s == TCML_SEP) {
         $s = $this->get_symbol($p);
         $title = $s;
         $s = $this->get_symbol($p);
      }
      if ($s != TCML_END) {
         $this->error($s, __LINE__);
      }
      $this->write('<a href="javascript:openNewWindow(' . "'"
         . iRequest::href('t', 'd=popup') . '&nobar&s=' . $seite . "'"
         . ',800,500,' . "'" . 'help' . "'" . ')'
         . '">');
      $this->write('<img src="' . $_ini['icon_helpdesk']
         . '" border="0" title="' . $title
         . '" style="cursor:help; vertical-align:text-bottom;">');
      $this->write('</a>');
      //$s = $this->tagend($s, $p);
      $s = $this->get_symbol($p);
   } // function kommentar


   function text(&$s, &$p, $type)
   {
      $this->tagdivision($s, $p);
      switch ($type) {
         case 'php':
            $this->write(highlight_string(stripslashes($s), true) . "\n");
            $this->tagend($s, $p);
            break;
         case 'html':
            $this->write(stripslashes($s) . "\n");
            $this->tagend($s, $p);
            break;
         case 'text':
            if ($this->nowrap)
               $this->write(str_replace(
                  '&amp;',
                  '&',
                  htmlspecialchars(stripslashes($s))
               ));
            else
               $this->write(nl2br(str_replace(
                  '&amp;',
                  '&',
                  htmlspecialchars(stripslashes($s))
               )));
            //$this->tagend($s, $p); // alte Version!
            //ab hier der neue Versuch mit eingebauten Auszeichnungen
            $s = $this->get_symbol($p);
            while ($s != TCML_END) {
               if (($s == TCML_BEGIN) and ($p < $this->inplen)) {
                  $this->parse_vcml_2($s, $p);
               } else {
                  if ($this->nowrap)
                     $this->write(str_replace(
                        '&amp;',
                        '&',
                        htmlspecialchars(stripslashes($s))
                     ));
                  else
                     $this->write(nl2br(str_replace(
                        '&amp;',
                        '&',
                        htmlspecialchars(stripslashes($s))
                     )));
                  $s = $this->get_symbol($p);
               }
            }
            $s = $this->get_symbol($p);
      } //switch
   } //function text

  

   function sql_source(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      //$path = dirname ($this->iRequest->get_SeitePathIntern ());
      $file = $this->iRequest->get_SeitePathPublic() /* . '/' */ . $s;
      $this->tagdivision($s, $p);
      $parameterarray = _paramarray($s);
      //$file = _ROOT_PATH . $s;
      if (file_exists($file)) {
         $javasource = filesystem::LoadFile($file);
         $javasource = str_replace("\r\n", "\n", $javasource); # windows -> linux
         $javasource = str_replace("\r", "\n", $javasource); # mac -> linux */
         $filearray = explode("\n", $javasource);
         $von = $parameterarray['von'];
         $bis = $parameterarray['bis'];
         if ($bis == 0) {
            $bis = count($filearray);
         }
         $zn = $parameterarray['Zeilennummern'];
         if ($von != 0) {
            $dummy = array_splice($filearray, $bis);
            $dummy = array_splice($filearray, 0, $von - 1);
         } else {
            $von = 1;
         }
         $javasource = implode("\n", $filearray);
         $javasource = htmlspecialchars($javasource);
         $codefile = '<pre class="brush:sql;gutter:' . $zn . ';">' . $javasource . '</pre>';
         $this->write($codefile);
         if (!$this->syntaxhighlighter) {
            $this->write(' <script type="text/javascript">SyntaxHighlighter.all()</script>');
            $this->syntaxhighlighter = true;
         }
      }
      $this->tagend($s, $p);
   }

   function php_source(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      //$path = dirname ($this->iRequest->get_SeitePathIntern ());
      $file = $this->iRequest->get_SeitePathPublic() /* . '/' */ . $s;
      $this->tagdivision($s, $p);
      $parameterarray = _paramarray($s);
      //$file = _ROOT_PATH . $s;
      if (file_exists($file)) {
         $javasource = filesystem::LoadFile($file);
         $javasource = str_replace("\r\n", "\n", $javasource); # windows -> linux
         $javasource = str_replace("\r", "\n", $javasource); # mac -> linux */
         $filearray = explode("\n", $javasource);
         $von = $parameterarray['von'];
         $bis = $parameterarray['bis'];
         if ($bis == 0) {
            $bis = count($filearray);
         }
         $zn = $parameterarray['Zeilennummern'];
         if ($von != 0) {
            $dummy = array_splice($filearray, $bis);
            $dummy = array_splice($filearray, 0, $von - 1);
         } else {
            $von = 1;
         }
         $javasource = implode("\n", $filearray);
         $javasource = htmlspecialchars($javasource);
         $codefile = '<pre class="brush:php;gutter:' . $zn . ';">' . $javasource . '</pre>';
         $this->write($codefile);
         if (!$this->syntaxhighlighter) {
            $this->write(' <script type="text/javascript">SyntaxHighlighter.all()</script>');
            $this->syntaxhighlighter = true;
         }
      }
      $this->tagend($s, $p);
   }

   function python_source(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      //$path = dirname ($this->iRequest->get_SeitePathIntern ());
      $file = $this->iRequest->get_SeitePathPublic() /* . '/' */ . $s;
      $this->tagdivision($s, $p);
      $parameterarray = _paramarray($s);
      //$file = _ROOT_PATH . $s;
      if (file_exists($file)) {
         $javasource = filesystem::LoadFile($file);
         $javasource = str_replace("\r\n", "\n", $javasource); # windows -> linux
         $javasource = str_replace("\r", "\n", $javasource); # mac -> linux */
         $filearray = explode("\n", $javasource);
         $von = $parameterarray['von'];
         $bis = $parameterarray['bis'];
         if ($bis == 0) {
            $bis = count($filearray);
         }
         $zn = $parameterarray['Zeilennummern'];
         if ($von != 0) {
            $dummy = array_splice($filearray, $bis);
            $dummy = array_splice($filearray, 0, $von - 1);
         } else {
            $von = 1;
         }
         $javasource = implode("\n", $filearray);
         $javasource = htmlspecialchars($javasource);
         $codefile = '<pre class="brush:python;gutter:' . $zn . ';">' . $javasource . '</pre>';
         $this->write($codefile);
         if (!$this->syntaxhighlighter) {
            $this->write(' <script type="text/javascript">SyntaxHighlighter.all()</script>');
            $this->syntaxhighlighter = true;
         }
      }
      $this->tagend($s, $p);
   }

  // Replace java_source() and java_text() in the engine class and insert
// the three java_shortcut_* helper methods there as well. Do not include
// this file directly: these functions are members of the existing class.

function java_source(&$s, &$p)
{
    $this->tagdivision($s, $p);
    $filename = basename(str_replace('\\', '/', $s));
    $file = $this->iRequest->get_SeitePathPublic() . $s;
    $parts = $this->java_shortcut_parameters($p);
    $flags = '';
    if ($parts && preg_match('/^[CNI]+$/', trim(end($parts)))) {
        $flags = trim(array_pop($parts));
    }
    $options = $parts ? $parts[0] : '';
    $parameters = $options !== '' ? _paramarray($options) : array();
    if (file_exists($file)) {
        $source = str_replace(array("\r\n", "\r"), "\n", filesystem::LoadFile($file));
        $lines = explode("\n", $source);
        $from = isset($parameters['von']) ? (int)$parameters['von'] : 0;
        $to = isset($parameters['bis']) ? (int)$parameters['bis'] : 0;
        // Preserve the existing range behaviour, including von=0 (whole file).
        if ($from > 0) {
            $lines = array_slice($lines, $from - 1, $to > 0 ? max(0, $to - $from + 1) : null);
        }
        $gutter = isset($parameters['Zeilennummern'])
            && in_array(strtolower(trim((string)$parameters['Zeilennummern'])), array('true', '1'), true);
        $this->java_shortcut_render(implode("\n", $lines), $flags, $filename, $gutter);
    }
    $this->tagend($s, $p);
}

function java_text(&$s, &$p)
{
    $this->tagdivision($s, $p);
    $source = preg_replace('/<p(?:\s+[^>]*)?>/i', '', $s);
    $source = preg_replace('/<\/p\s*>|<br\s*\/?>/i', "\n", $source);
    $source = html_entity_decode($source, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $source = str_replace(array("\xc2\xa0", "\r\n", "\r"), array(' ', "\n", "\n"), $source);
    $parts = $this->java_shortcut_parameters($p);
    $flags = $parts ? trim($parts[count($parts) - 1]) : '';
    if (!preg_match('/^[CNI]+$/', $flags)) $flags = '';
    $filename = 'Program.java';
    if (preg_match('/\b(?:class|interface|enum|record)\s+([A-Za-z_$][A-Za-z0-9_$]*)\b/', $source, $match)) {
        $filename = $match[1] . '.java';
    }
    $this->java_shortcut_render($source, $flags, $filename, false);
    $this->tagend($s, $p);
}

function java_shortcut_parameters(&$p)
{
    $parts = array();
    // Read optional fields without consuming TCML_END, even for empty fields.
    while (substr($this->input, $p, strlen(TCML_SEP)) === TCML_SEP) {
        $p += strlen(TCML_SEP);
        $end = strpos($this->input, TCML_END, $p);
        $next = strpos($this->input, TCML_SEP, $p);
        if ($end === false) $end = strlen($this->input);
        if ($next !== false && $next < $end) $end = $next;
        $parts[] = substr($this->input, $p, $end - $p);
        $p = $end;
    }
    return $parts;
}

function java_shortcut_render($source, $flags, $filename, $gutter)
{
    $escape = function ($value) { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
    $this->write('<div data-java-shortcut><pre class="brush:java;gutter:'
        . ($gutter ? 'true' : 'false') . ';">' . $escape($source) . '</pre>');
    if ($flags !== '') {
        $payload = json_encode(array('code' => $source, 'filename' => $filename),
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        $this->write('<script type="application/json" data-java-source>' . $payload . '</script>');
        if (strpos($flags, 'I') !== false) {
            $this->write('<label>Dateiname: <input data-java-filename type="text" value="'
                . $escape($filename) . '" aria-label="Java-Dateiname"></label> ');
        }
        foreach (array('C' => 'Kopieren', 'N' => 'In IDE öffnen', 'I' => 'Zur IDE hinzufügen') as $flag => $label) {
            if (strpos($flags, $flag) !== false) {
                $this->write('<button type="button" class="btn btn-info" data-java-action="'
                    . $flag . '">' . $label . '</button> ');
            }
        }
        $this->write('<span data-java-status role="status" aria-live="polite"></span>');
        $this->java_shortcut_script();
    }
    $this->write('</div>');
    if (!$this->syntaxhighlighter) {
        $this->write('<script type="text/javascript">SyntaxHighlighter.all()</script>');
        $this->syntaxhighlighter = true;
    }
}


function java_shortcut_script()
{
    $this->write(<<<'HTML'
<script>
(function () {
    if (window.netJavaShortcutsInstalled) return;
    window.netJavaShortcutsInstalled = true;
    const runner = new URL('https://net-schulbuch.de/java-ide/sas-book-demo/runner.html');
    const tabName = 'net-schulbuch-java-shortcuts';
    let ideWindow, ready = false, pending = null, timer;
    function status(block, text) { block.querySelector('[data-java-status]').textContent = text; }
    function finish(text) {
        clearTimeout(timer);
        if (pending) {
            pending.button.disabled = false;
            status(pending.block, text);
        }
        pending = null;
    }
    function send() {
        if (!ready || !pending || pending.sent) return;
        pending.sent = true;
        ideWindow.postMessage({type:'sas-book:add-file', code:pending.code,
            filename:pending.filename, requestId:pending.requestId}, runner.origin);
    }
    window.addEventListener('message', event => {
        if (!ideWindow || event.source !== ideWindow || event.origin !== runner.origin) return;
        const message = event.data;
        if (!message || typeof message !== 'object') return;
        if (message.type === 'sas-book:ready') { ready = true; send(); }
        else if (pending && message.requestId === pending.requestId) {
            if (message.type === 'sas-book:added') finish('Datei hinzugefügt.');
            else if (message.type === 'sas-book:error') finish(message.error || 'Datei konnte nicht hinzugefügt werden.');
        }
    });
    async function copy(code) {
        if (navigator.clipboard && window.isSecureContext) {
            try { await navigator.clipboard.writeText(code); return; } catch (error) { /* Fallback below. */ }
        }
        const field = document.createElement('textarea'); field.value = code;
        field.style.cssText = 'position:fixed;left:-9999px;'; document.body.append(field);
        field.select();
        try { if (!document.execCommand('copy')) throw new Error('Kopieren wurde vom Browser blockiert.'); }
        finally { field.remove(); }
    }
    document.addEventListener('click', async event => {
        const button = event.target.closest('[data-java-action]');
        if (!button) return;
        const block = button.closest('[data-java-shortcut]');
        const payload = JSON.parse(block.querySelector('[data-java-source]').textContent);
        const action = button.dataset.javaAction;
        try {
            if (action === 'C') { await copy(payload.code); status(block, 'Kopiert.'); return; }
            if (pending) { status(block, 'Bitte die laufende Dateiübertragung abwarten.'); return; }
            const input = block.querySelector('[data-java-filename]');
            let filename = (input ? input.value : payload.filename).trim();
            if (!filename.endsWith('.java')) filename += '.java';
            if (!/^[\p{ID_Start}_$][\p{ID_Continue}$]*\.java$/u.test(filename)) {
                throw new Error('Bitte einen gültigen Java-Dateinamen angeben.');
            }
            if (input) input.value = filename;
            const url = new URL(runner);
            url.searchParams.set('parentOrigin', location.origin);
            url.searchParams.set('transfer', Date.now() + '-' + Math.random().toString(36).slice(2));
            url.hash = new URLSearchParams({code:payload.code, filename}).toString();
            if (action === 'N') {
                ready = false;
                ideWindow = window.open(url.href, tabName);
                if (!ideWindow) throw new Error('Bitte Pop-ups für diese Buchseite erlauben.');
                ideWindow.focus();
                status(block, 'IDE geöffnet.');
            } else {
                ready = false;
                ideWindow = window.open('', tabName);
                if (!ideWindow) throw new Error('Bitte Pop-ups für diese Buchseite erlauben.');
                ideWindow.focus();
                let blank = false;
                try { blank = ideWindow.location.href === 'about:blank'; } catch (error) { /* Existing cross-origin IDE. */ }
                if (blank) {
                    ideWindow.location.href = url.href;
                    status(block, 'IDE geöffnet.');
                    return;
                }
                pending = {block, button, code:payload.code, filename,
                    requestId:Date.now() + '-' + Math.random().toString(36).slice(2), sent:false};
                button.disabled = true; status(block, 'Datei wird hinzugefügt …');
                timer = setTimeout(() => finish('Keine Antwort von der IDE. Bitte erneut versuchen.'), 20000);
                ideWindow.postMessage({type:'sas-book:ready?'}, runner.origin);
            }
        } catch (error) { status(block, error.message); }
    });
})();
</script>
HTML
    );
}


   function sql_text(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $sqlsource = $s;
      $sqlsource = str_replace('<p class="programmcode">', "", $sqlsource);
      $sqlsource = str_replace("<p>", "", $sqlsource);
      $sqlsource = str_replace("</p>", "\n", $sqlsource);
      $sqlsource = str_replace("<br />", "\n", $sqlsource);
      $sqlsource = str_replace("&nbsp;", " ", $sqlsource);
      $codefile = '<pre class="brush:sql; gutter:false;">' . $sqlsource . '</pre>';
      $this->write($codefile);
      if (!$this->syntaxhighlighter) {
         $this->write(' <script type="text/javascript">SyntaxHighlighter.all()</script>');
         $this->syntaxhighlighter = true;
      }
      $this->tagend($s, $p);
   }

   function php_text(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $sqlsource = $s;
      $sqlsource = str_replace('<p class="programmcode">', "", $sqlsource);
      $sqlsource = str_replace("<p>", "", $sqlsource);
      $sqlsource = str_replace("</p>", "\n", $sqlsource);
      $sqlsource = str_replace("<br />", "\n", $sqlsource);
      $sqlsource = str_replace("&nbsp;", " ", $sqlsource);
      $codefile = '<pre class="brush:php;gutter:false;">' . $sqlsource . '</pre>';
      $this->write($codefile);
      if (!$this->syntaxhighlighter) {
         $this->write(' <script type="text/javascript">SyntaxHighlighter.all()</script>');
         $this->syntaxhighlighter = true;
      }
      $this->tagend($s, $p);
   }

   function python_text(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $sqlsource = $s;
      $sqlsource = str_replace('<p class="programmcode">', "", $sqlsource);
      $sqlsource = str_replace("<p>", "", $sqlsource);
      $sqlsource = str_replace("</p>", "\n", $sqlsource);
      $sqlsource = str_replace("<br />", "\n", $sqlsource);
      $sqlsource = str_replace("&nbsp;", " ", $sqlsource);
      $codefile = '<pre class="brush:python;gutter:false;">' . $sqlsource . '</pre>';
      $this->write($codefile);
      if (!$this->syntaxhighlighter) {
         $this->write(' <script type="text/javascript">SyntaxHighlighter.all()</script>');
         $this->syntaxhighlighter = true;
      }
      $this->tagend($s, $p);
   }

   function python_code(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $pythoncodenr = random_int(1000000, 2000000);
      $codenr = strval($pythoncodenr);

      // --- eingebetteten Code aufbereiten ---
      $pythoncode = $s;
      $pythoncode = preg_replace('/<p\\s*>/i', '', $pythoncode);
      $pythoncode = preg_replace('/<\\/p>/i', '<br>', $pythoncode);
      $pythoncode = preg_replace('/<br\\s*\\/?>(\\r?\\n)?/i', "\n", $pythoncode);
      $pythoncode_clean = str_replace("\t", "    ", $pythoncode);
      $pythoncode_clean = html_entity_decode($pythoncode_clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
      $pythoncode_clean = str_replace("\xC2\xA0", " ", $pythoncode_clean);

      // --- Modus + Zeilennummern ---
      $this->tagdivision($s, $p);
      $mode = $s;
      $lz = substr($mode, -1, 1);
      if ($lz == "Z") {
         $mode = substr($mode, 0, strlen($mode) - 1);
         $zeilennummer = true;
      } else {
         $zeilennummer = false;
      }
      $class = $zeilennummer ? 'class="line-numbers"' : '';

      // --- Ausgabe Codeblock + verstecktes Textarea ---
      $out = '<div class="highlightedCode" id="highlightedCode' . $codenr . '">
              <pre ' . $class . '><code class="language-python">' . htmlspecialchars($pythoncode_clean, ENT_NOQUOTES, 'UTF-8') . '</code></pre>
          </div>
<textarea id="pycode' . $codenr . '" style="position:absolute; left:-9999px;">' . htmlspecialchars($pythoncode_clean, ENT_NOQUOTES, 'UTF-8') . '</textarea>';

      // --- Buttons je Modus ---
      if ($mode != "O") {
         $out .= '<button id="copyButton' . $codenr . '" class="btn btn-info" style="margin-right:1em;">kopieren</button>
               <span id="copyStatus' . $codenr . '" style="margin-left: 10px; color: #666;"></span>';

         if ($mode == "WTP" || $mode == "WTPC") {
            $out .= '<button id="wtpbutton' . $codenr . '" class="btn btn-info" style="margin-right:1em;">WTP öffnen</button>';
         }
         if ($mode == "NP" || $mode == "NPC") {
            $out .= '<button id="npbutton' . $codenr . '" class="btn btn-info" style="margin-right:1em;">Netbuch-Python öffnen</button>';
         }
         if ($mode == "NPC" || $mode == "WTPC") {
            $out .= '<span id="devicePicker' . $codenr . '" style="margin-left:8px;">
                   <label style="margin-right:10px;">
                     <input type="radio" name="device-' . $codenr . '" value="Calliope mini 1/2">
                     Calliope 1/2
                   </label>
                   <label>
                     <input type="radio" name="device-' . $codenr . '" value="Calliope mini 3" checked>
                     Calliope 3
                   </label>
                 </span>';
         }
      }

      // --- Skripte ---
      $out .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/lz-string/1.5.0/lz-string.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const layoutmode = ' . json_encode($mode, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ';
  const textarea   = document.getElementById("pycode' . $codenr . '");

  const copyButton = document.getElementById("copyButton' . $codenr . '");
  const copyStatus = document.getElementById("copyStatus' . $codenr . '");
  const wtpbtn     = document.getElementById("wtpbutton' . $codenr . '");
  const npbtn      = document.getElementById("npbutton' . $codenr . '");
  const pickerBox  = document.getElementById("devicePicker' . $codenr . '");

  // Copy mit Bestätigung
  if (copyButton && textarea && copyStatus) {
    copyButton.addEventListener("click", async function() {
      try {
        await navigator.clipboard.writeText(textarea.value);
        copyStatus.textContent = "✓ In Zwischenablage kopiert!";
        copyStatus.style.color = "#2196F3";
        setTimeout(() => { copyStatus.textContent = ""; }, 2000);
      } catch (err) {
        copyStatus.textContent = "Fehler beim Kopieren";
        copyStatus.style.color = "#f44336";
        setTimeout(() => { copyStatus.textContent = ""; }, 2000);
      }
    });
  }

  function getDeviceLabel() {
    if (!pickerBox) return null;
    const sel = pickerBox.querySelector("input[name=\\"device-' . $codenr . '\\"]:checked");
    return sel ? sel.value : null;
  }
  function npDeviceCode(label) {
    if (!label) return "none";
    return label.indexOf("3") >= 0 ? "c3" : "c12";
  }

  // --- Net-Schulbuch-Python öffnen (NP / NPC) ---
  if (npbtn && textarea) {
    npbtn.addEventListener("click", function() {
      const isNPC = (layoutmode === "NPC");
      const payload = { code: textarea.value, source: "netbuch-schulbuch", ts: Date.now() };

      if (isNPC) {
        payload.layout = "output-files";
        const label = getDeviceLabel();
        payload.device = npDeviceCode(label);
      }

      const target = "https://python.net-schulbuch.de/";
      const w = window.open("about:blank", "NETBUCH_IDE");
      if (!w || w.closed) { alert("Popup blockiert."); return; }

      // 1) Payload in localStorage unter eindeutigem Key ablegen
      const transferKey = "netbuch_xfer_" + Date.now() + "_" + Math.floor(Math.random() * 1e9);
      try {
        localStorage.setItem(transferKey, JSON.stringify(payload));
      } catch (e) {
        alert("Konnte Daten nicht in localStorage speichern (zu groß?).");
        return;
      }

      // 2) IDE mit transferKey öffnen (Empfänger liest sehr früh im <head>)
      try {
        w.location.replace(target + "?transferKey=" + encodeURIComponent(transferKey) + "&r=" + Date.now());
      } catch (e) {}
      try { w.focus(); } catch (e) {}

      // 3) Optional: Handshake -> UPDATE erst nach ACK
      let tries = 0;
      const timer = setInterval(() => {
        tries++;
        try { w.postMessage({ type: "PING" }, "*"); } catch (_) {}
        if (tries > 20) clearInterval(timer);
      }, 150);

     window.addEventListener("message", function onAck(ev) {
  if (ev.source !== w) return;
  if (ev.data && ev.data.type === "ACK") {
    try { w.postMessage({ type: "UPDATE", payload }, "*"); } catch (_) {}
    clearInterval(timer);
  }
}, { once: true });
    });
  }

  // --- WebTigerPython (WTP / WTPC) ---
  if (wtpbtn && textarea) {
    wtpbtn.addEventListener("click", function() {
      const files = [{ name: "main.py", data: textarea.value }];
      let codeParam = "";
      try { codeParam = encodeURIComponent(LZString.compressToBase64(JSON.stringify(files))); }
      catch (e) { alert("Kodierung für WTP fehlgeschlagen."); return; }

	      let url = "https://python.net-schulbuch.de/?code=" + codeParam;
      if (pickerBox) {
        const label = getDeviceLabel() || "Calliope mini 1/2";
        url += "&device=" + encodeURIComponent(label);
      }

      const w = window.open("about:blank", "WTP_IDE");
      if (!w || w.closed) { alert("Popup blockiert."); return; }
      try { w.location.href = url; } catch (e) {}
      try { w.focus(); } catch (e) {}
    });
  }

  // Prism
  if (window.Prism && window.Prism.languages && window.Prism.languages.python) {
    Prism.languages.python.keyword = /(?:False|None|True|and|as|assert|async|await|break|class|continue|def|del|elif|else|except|finally|for|from|global|if|import|in|is|lambda|nonlocal|not|or|pass|raise|return|try|while|with|yield|repeat)\\b/;
    Prism.highlightAll();
  }
});
</script>

<style>
  .highlightedCode {
     margin: 1px 0;
     padding: 2px;
     background-color: #f8f8f8;
     border-radius: 4px;
  }
  .highlightedCode pre { margin: 0; padding: 0.5em; }
  .highlightedCode code {
     font-family: "Consolas", "Monaco", monospace;
     font-size: 14px;
     line-height: 1.5;
     white-space: pre-wrap;
  }
</style>
';

      $this->write($out);
      $this->tagend($s, $p);
   }

   function python_datei(&$s, &$p)
   {
      // --- Dateipfad aus Tag lesen ---
      $this->tagdivision($s, $p);
      $file = $this->iRequest->get_SeitePathPublic() . $s;

      if (!file_exists($file)) {
         $this->write('<div style="color:#b00">Datei nicht gefunden: ' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . '</div>');
         $this->tagend($s, $p);
         return;
      }

      // --- Datei laden & Code aufbereiten ---
      $code = filesystem::LoadFile($file);
      $pythoncodenr = random_int(1000000, 2000000);
      $codenr = strval($pythoncodenr);

      $pythoncode_clean = str_replace("\t", "    ", $code);
      $pythoncode_clean = html_entity_decode($pythoncode_clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
      $pythoncode_clean = str_replace("\xC2\xA0", " ", $pythoncode_clean);

      // --- Modus + Zeilennummern ---
      $this->tagdivision($s, $p);
      $mode = $s;
      $lz = substr($mode, -1, 1);
      if ($lz == "Z") {
         $mode = substr($mode, 0, strlen($mode) - 1);
         $zeilennummer = true;
      } else {
         $zeilennummer = false;
      }
      $class = $zeilennummer ? 'class="line-numbers"' : '';

      // --- Ausgabe Codeblock + verstecktes Textarea ---
      $out = '<div class="highlightedCode" id="highlightedCode' . $codenr . '">
              <pre ' . $class . '><code class="language-python">' . htmlspecialchars($pythoncode_clean, ENT_NOQUOTES, 'UTF-8') . '</code></pre>
          </div>
<textarea id="pycode' . $codenr . '" style="position:absolute; left:-9999px;">' . htmlspecialchars($pythoncode_clean, ENT_NOQUOTES, 'UTF-8') . '</textarea>';

      // --- Buttons je Modus ---
      if ($mode != "O") {
         $out .= '<button id="copyButton' . $codenr . '" class="btn btn-info" style="margin-right:1em;">kopieren</button>
               <span id="copyStatus' . $codenr . '" style="margin-left: 10px; color: #666;"></span>';

         if ($mode == "WTP" || $mode == "WTPC") {
            $out .= '<button id="wtpbutton' . $codenr . '" class="btn btn-info" style="margin-right:1em;">WTP öffnen</button>';
         }
         if ($mode == "NP" || $mode == "NPC") {
            $out .= '<button id="npbutton' . $codenr . '" class="btn btn-info" style="margin-right:1em;">Netbuch-Python öffnen</button>';
         }
         if ($mode == "NPC" || $mode == "WTPC") {
            $out .= '<span id="devicePicker' . $codenr . '" style="margin-left:8px;">
                   <label style="margin-right:10px;">
                     <input type="radio" name="device-' . $codenr . '" value="Calliope mini 1/2">
                     Calliope 1/2
                   </label>
                   <label>
                     <input type="radio" name="device-' . $codenr . '" value="Calliope mini 3" checked>
                     Calliope 3
                   </label>
                 </span>';
         }
      }

      // --- Skripte ---
      $out .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/lz-string/1.5.0/lz-string.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const layoutmode = ' . json_encode($mode, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ';
  const textarea   = document.getElementById("pycode' . $codenr . '");

  const copyButton = document.getElementById("copyButton' . $codenr . '");
  const copyStatus = document.getElementById("copyStatus' . $codenr . '");
  const wtpbtn     = document.getElementById("wtpbutton' . $codenr . '");
  const npbtn      = document.getElementById("npbutton' . $codenr . '");
  const pickerBox  = document.getElementById("devicePicker' . $codenr . '");

  // Copy mit Bestätigung
  if (copyButton && textarea && copyStatus) {
    copyButton.addEventListener("click", async function() {
      try {
        await navigator.clipboard.writeText(textarea.value);
        copyStatus.textContent = "✓ In Zwischenablage kopiert!";
        copyStatus.style.color = "#2196F3";
        setTimeout(() => { copyStatus.textContent = ""; }, 2000);
      } catch (err) {
        copyStatus.textContent = "Fehler beim Kopieren";
        copyStatus.style.color = "#f44336";
        setTimeout(() => { copyStatus.textContent = ""; }, 2000);
      }
    });
  }

  function getDeviceLabel() {
    if (!pickerBox) return null;
    const sel = pickerBox.querySelector("input[name=\\"device-' . $codenr . '\\"]:checked");
    return sel ? sel.value : null;
  }
  function npDeviceCode(label) {
    if (!label) return "none";
    return label.indexOf("3") >= 0 ? "c3" : "c12";
  }

  // --- Net-Schulbuch-Python öffnen (NP / NPC) ---
  if (npbtn && textarea) {
    npbtn.addEventListener("click", function() {
      const isNPC = (layoutmode === "NPC");
      const payload = { code: textarea.value, source: "netbuch-schulbuch", ts: Date.now() };

      if (isNPC) {
        payload.layout = "output-files";
        const label = getDeviceLabel();
        payload.device = npDeviceCode(label);
      }

      const target = "https://python.net-schulbuch.de/";
      const w = window.open("about:blank", "NETBUCH_IDE");
      if (!w || w.closed) { alert("Popup blockiert."); return; }

      // 1) Payload in localStorage unter eindeutigem Key ablegen
      const transferKey = "netbuch_xfer_" + Date.now() + "_" + Math.floor(Math.random() * 1e9);
      try {
        localStorage.setItem(transferKey, JSON.stringify(payload));
      } catch (e) {
        alert("Konnte Daten nicht in localStorage speichern (zu groß?).");
        return;
      }

      // 2) IDE mit transferKey öffnen (Empfänger liest sehr früh im <head>)
      try {
        w.location.replace(target + "?transferKey=" + encodeURIComponent(transferKey) + "&r=" + Date.now());
      } catch (e) {}
      try { w.focus(); } catch (e) {}

      // 3) Optional: Handshake -> UPDATE erst nach ACK
      let tries = 0;
      const timer = setInterval(() => {
        tries++;
        try { w.postMessage({ type: "PING" }, "*"); } catch (_) {}
        if (tries > 20) clearInterval(timer);
      }, 150);

window.addEventListener("message", function onAck(ev) {
  if (ev.source !== w) return;
  if (ev.data && ev.data.type === "ACK") {
    try { w.postMessage({ type: "UPDATE", payload }, "*"); } catch (_) {}
    clearInterval(timer);
  }
}, { once: true });

    });
  }

  // --- WebTigerPython (WTP / WTPC) ---
  if (wtpbtn && textarea) {
    wtpbtn.addEventListener("click", function() {
      const files = [{ name: "main.py", data: textarea.value }];
      let codeParam = "";
      try { codeParam = encodeURIComponent(LZString.compressToBase64(JSON.stringify(files))); }
      catch (e) { alert("Kodierung für WTP fehlgeschlagen."); return; }

	      let url = "https://python.net-schulbuch.de/?code=" + codeParam;
      if (pickerBox) {
        const label = getDeviceLabel() || "Calliope mini 1/2";
        url += "&device=" + encodeURIComponent(label);
      }

      const w = window.open("about:blank", "WTP_IDE");
      if (!w || w.closed) { alert("Popup blockiert."); return; }
      try { w.location.href = url; } catch (e) {}
      try { w.focus(); } catch (e) {}
    });
  }

  // Prism
  if (window.Prism && window.Prism.languages && window.Prism.languages.python) {
    Prism.languages.python.keyword = /(?:False|None|True|and|as|assert|async|await|break|class|continue|def|del|elif|else|except|finally|for|from|global|if|import|in|is|lambda|nonlocal|not|or|pass|raise|return|try|while|with|yield|repeat)\\b/;
    Prism.highlightAll();
  }
});
</script>

<style>
  .highlightedCode {
     margin: 1px 0;
     padding: 2px;
     background-color: #f8f8f8;
     border-radius: 4px;
  }
  .highlightedCode pre { margin: 0; padding: 0.5em; }
  .highlightedCode code {
     font-family: "Consolas", "Monaco", monospace;
     font-size: 14px;
     line-height: 1.5;
     white-space: pre-wrap;
  }
</style>
';

      $this->write($out);
      $this->tagend($s, $p);
   }
   private function formatHtmlCode($html)
   {
      $voidTags = array('area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr');
      $inlineTags = array('a', 'b', 'i', 'u', 'strong', 'em', 'small', 'span', 'label', 'abbr', 'acronym', 'cite', 'code', 'sub', 'sup', 'time', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');

      $indent = 0;
      $indentSize = 2;
      $formatted = '';
      $inlineBuffer = '';
      $inInlineContext = false;
      $html = trim(preg_replace('/>\s*</', ">\n<", $html));
      $tokens = preg_split('/(<\/?[a-zA-Z][a-zA-Z0-9]*(?:\s+[^<>]*?)?>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

      foreach ($tokens as $token) {
         $token = trim($token);
         if ($token === '') {
            continue;
         }
         if (preg_match('/^<\/([a-zA-Z0-9]+)/', $token, $match)) {
            $tagName = strtolower($match[1]);
            if (in_array($tagName, $inlineTags)) {
               $inlineBuffer .= $token;
               $inInlineContext = false;
               $formatted .= str_repeat(' ', $indent * $indentSize) . $inlineBuffer . "\n";
               $inlineBuffer = '';
            } else {
               $indent = max(0, $indent - 1);
               $formatted .= str_repeat(' ', $indent * $indentSize) . $token . "\n";
            }
            continue;
         }
         if (preg_match('/^<\s*([a-zA-Z0-9]+)/', $token, $match)) {
            $tagName = strtolower($match[1]);
            $isVoid = in_array($tagName, $voidTags);
            $isInline = in_array($tagName, $inlineTags);
            if ($isInline) {
               $inlineBuffer = $token;
               $inInlineContext = true;
               continue;
            }
            if ($inInlineContext) {
               $formatted .= str_repeat(' ', $indent * $indentSize) . $inlineBuffer . "\n";
               $inlineBuffer = '';
               $inInlineContext = false;
            }
            $formatted .= str_repeat(' ', $indent * $indentSize) . $token . "\n";
            if (!$isVoid && !str_ends_with($token, '/>')) {
               $indent++;
            }
            continue;
         }
         if ($inInlineContext) {
            $inlineBuffer .= $token;
         } else {
            $formatted .= str_repeat(' ', $indent * $indentSize) . $token . "\n";
         }
      }

      if ($inlineBuffer !== '') {
         $formatted .= str_repeat(' ', $indent * $indentSize) . $inlineBuffer . "\n";
      }
      return trim($formatted);
   }

   private function htmlCodeOutput($htmlcode, $mode, $sourceFile = '')
   {
      $codenr = strval(random_int(1000000, 2000000));
      $lineNumbers = false;
      if (substr($mode, -1, 1) == 'Z') {
         $mode = substr($mode, 0, strlen($mode) - 1);
         $lineNumbers = true;
      }
      $class = $lineNumbers ? ' class="line-numbers"' : '';
      $htmlcode = html_entity_decode($htmlcode, ENT_QUOTES | ENT_HTML5, 'UTF-8');
      $htmlcode = str_replace("\xC2\xA0", ' ', $htmlcode);
      $htmlcode = str_replace(array('&lt;', '&gt;', '&amp;', "\t"), array('<', '>', '&', '    '), $htmlcode);
      $formattedCode = $this->formatHtmlCode($htmlcode);
      $textareaId = 'htmlcode' . $codenr;

      $out = '<div class="highlightedCode" id="highlightedCode' . $codenr . '">
               <pre' . $class . '><code class="language-html">' . htmlspecialchars($formattedCode, ENT_NOQUOTES, 'UTF-8') . '</code></pre>
            </div>
            <textarea id="' . $textareaId . '" style="position:absolute; left:-9999px;">' . htmlspecialchars($htmlcode, ENT_NOQUOTES, 'UTF-8') . '</textarea>';

      if ($mode == 'H') {
         $out .= '<button id="htmlbutton' . $codenr . '" class="btn btn-info" style="margin-right:1em;">HTML im Browser öffnen</button>';
      } else if ($mode == 'E' && $sourceFile != '') {
         $out .= '<button id="editorbutton' . $codenr . '" class="btn btn-info" style="margin-right:1em;">Im HTML-Editor öffnen</button>';
      }

      $editorUrl = ($sourceFile == '') ? '' : 'htmleditor/index.html?html=' . $sourceFile;
      $out .= '<script>
document.addEventListener("DOMContentLoaded", function() {
  var textarea = document.getElementById("' . $textareaId . '");
  var htmlbtn = document.getElementById("htmlbutton' . $codenr . '");
  var editorbtn = document.getElementById("editorbutton' . $codenr . '");
  if (htmlbtn && textarea) {
    htmlbtn.addEventListener("click", function() {
      var blob = new Blob([textarea.value], { type: "text/html;charset=utf-8" });
      var url = URL.createObjectURL(blob);
      window.open(url, "_blank");
      setTimeout(function() { URL.revokeObjectURL(url); }, 1000);
    });
  }
  if (editorbtn) {
    editorbtn.addEventListener("click", function() {
      window.open("' . $editorUrl . '", "_blank");
    });
  }
  if (window.Prism) {
    Prism.highlightAll();
  }
});
</script>
<style>
  .highlightedCode {
     margin: 1px 0;
     padding: 2px;
     background-color: #f8f8f8;
     border-radius: 4px;
  }
  .highlightedCode pre { margin: 0; padding: 0.5em; }
  .highlightedCode code {
     font-family: "Consolas", "Monaco", monospace;
     font-size: 14px;
     line-height: 1.5;
     white-space: pre-wrap;
  }
</style>';

      return $out;
   }

   function html_code(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $htmlcode = $s;
      $htmlcode = preg_replace('/<p\s*>/i', '', $htmlcode);
      $htmlcode = preg_replace('/<\/p>/i', '<br>', $htmlcode);
      $htmlcode = preg_replace('/<br\s*\/?>/i', "\n", $htmlcode);
      $this->tagdivision($s, $p);
      $mode = $s;
      $this->write($this->htmlCodeOutput($htmlcode, $mode));
      $this->tagend($s, $p);
   }

   function html_datei(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $sourceFile = $s;
      $file = $this->iRequest->get_SeitePathPublic() . $sourceFile;
      if (!file_exists($file)) {
         $this->error('Ungültiger Dateiname: ' . $sourceFile . ' (siehe: Hochgeladene Dateien)', __LINE__);
         $this->tagend($s, $p);
         return;
      }
      $htmlcode = filesystem::LoadFile($file);
      $this->tagdivision($s, $p);
      $mode = $s;
      $this->write($this->htmlCodeOutput($htmlcode, $mode, $sourceFile));
      $this->tagend($s, $p);
   }


   function include_file(&$s, &$p, $type)
   {
      $this->tagdivision($s, $p);
      //$path = dirname ($this->iRequest->get_SeitePathIntern ());
      $file = $this->iRequest->get_SeitePathPublic() /* . '/' */ . $s;
      //$file = _ROOT_PATH . $s;
      if (file_exists($file)) {
         switch ($type) {
            case 'html':
               $this->write('<pre>' . htmlentities(stripslashes(filesystem::LoadFile($file))) . '</pre>');
               break;
            case 'htmlo':
               $this->write(stripslashes(filesystem::LoadFile($file)));
               break;
            case 'java':
            case 'java_source':
               $this->write('<pre class="language-java" style="margin: 0; padding: 5;"><code>');
               $this->write(htmlentities(stripslashes(filesystem::LoadFile($file))));
               $this->write('</code></pre>');
               break;
            case 'mml':
               $this->write('<span class="formel">');
               $this->write(stripslashes(filesystem::LoadFile($file)));
               $this->write('</span>');
               break;
            case 'php_source':
               // $this->write ( /*'<pre>' .*/ show_source ($file, true) /*. '</pre>'*/ );
               $this->write("<pre>");
               $this->write(highlight_string(file_get_contents($file), true));
               $this->write("</pre>");
               break;
            case 'python_source':
               //$this->write ( /*'<pre>' .*/ show_source ($file, true) /*. '</pre>'*/ );
               $this->write('<pre class="language-python" style="margin: 0; padding: 5;"><code>');
               $this->write(htmlentities(filesystem::LoadFile($file))); //, ENT_HTML5, "UTF-8"
               $this->write('</code></pre>');
               break;
            case 'python_source_zn':
               $code = preg_split('/\r\n|\r|\n/', filesystem::LoadFile($file));
               $this->write('<ol class="programmcode">');
               for ($i = 0; $i < count($code); $i++) {
                  $this->write('<li class="programmcode"><pre class="language-python" style="margin: 0; padding: 0;"><code>' . htmlentities($code[$i]) . '</code></pre></li>');
               }
               $this->write('</ol>');
               break;
            case 'source_code':
               // [@[source-code::filename::lang=python,znr=false,von=0,bis=0,tab=4]@]
               $this->tagdivision($s, $p);
               $param = explode(',', $s);
               $parm = array(
                  'lang' => 'text',
                  'znr' => 'false',
                  'von' => 0,
                  'bis' => 0,
                  'tab' => 4,
               );
               for ($i = 0; $i < count($param); $i++) {
                  if (strpos($param[$i], '=') === false) {
                     continue;
                  }
                  list($name, $wert) = explode('=', trim($param[$i]), 2);
                  $parm[trim($name)] = trim($wert);
               }
               //***/$this->write ('<br />' . var_export ($parm, true) . '<br />');
               $code = preg_split('/\r\n|\r|\n/', file_get_contents($file));
               if (intval($parm['von']) == 0)
                  $von = 1;
               else
                  $von = intval($parm['von']);
               if (intval($parm['bis']) <= $von)
                  $bis = count($code);
               else
                  $bis = min(intval($parm['bis']), count($code));
               $selectedCode = array_slice($code, $von - 1, $bis - $von + 1);
               for ($i = 0; $i < count($selectedCode); $i++) {
                  $selectedCode[$i] = str_replace("\t", str_repeat(' ', intval($parm['tab'])), $selectedCode[$i]);
               }
               $gutter = ($parm['znr'] == 'true') ? 'true' : 'false';
               $this->write('<pre class="brush:' . $parm['lang'] . ';gutter:' . $gutter . ';">' . htmlspecialchars(implode("\n", $selectedCode)) . '</pre>');
               if (!$this->syntaxhighlighter) {
                  $this->write(' <script type="text/javascript">SyntaxHighlighter.all()</script>');
                  $this->syntaxhighlighter = true;
               }
               break;
            case 'text':
               $this->write(nl2br(filesystem::LoadFile($file)));
               break;
         } //switch
      } //if
      else {
         $this->error('Ungültiger Dateiname: ' . $s
            . ' (siehe: Hochgeladene Dateien)', __LINE__);
      } //else
      $this->tagend($s, $p);
   } //function include_file



   function tipp(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $seite = $s;
      $s = $this->get_symbol($p);
      if ($s == TCML_SEP) {
         $s = $this->get_symbol($p);
         $title = $s;
         $s = $this->get_symbol($p);
      }
      $breite = 0;
      $hoehe = 0;
      if ($s == TCML_SEP) {
         $s = $this->get_symbol($p);
         list($breite, $hoehe) = explode(',', $s);
         $breite = intval($breite);
         $hoehe = intval($hoehe);
         /***/
         _debug('$s', $s, 'tipp()', __LINE__, __FILE__);
         $s = $this->get_symbol($p);
      }
      if ($s != TCML_END) {
         $this->error(substr($s, $p), __LINE__);
      } else {
         if ($breite == 0)
            $breite = _ini('tippwindow_width');
         if ($hoehe == 0)
            $hoehe = _ini('tippwindow_height');
         $this->write('<a href="javascript:openNewWindow(' . "'" . iRequest::href('d=popup,s=' . $seite . ',e=popup') . "'" . ',' . $breite . ','
            . $hoehe . ',' . "'tipp'" . ')" title="' . $title
            . '">' . html::img_ini(_ini('icon.tipp'))
            . $title
            . '</a>');
      }
      $s = $this->get_symbol($p);
   } // function tipp



   function kurztipp(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $title = $s;
      $this->tagdivision($s, $p);
      if ($s == TCML_BEGIN) {
         $text = "";
      } else {
         $text = $s;
         $s = $this->get_symbol($p);
      }
      while ($s == TCML_BEGIN) {
         $anzbegin = 0;
         $fertig = 0;
         $innercode = $s;
         while ($fertig == 0) {
            $s = $this->get_symbol($p);
            $innercode = $innercode . $s;
            if ($s == TCML_BEGIN) {
               $anzbegin++;
            }
            if ($s == TCML_END) {
               if ($anzbegin == 0) {
                  $fertig = 1;
               } else {
                  $anzbegin--;
               }
            }
         }
         $inner = new txt_engine($innercode, $this->iRequest, false);
         $neu = $inner->output;
         $text = $text . $neu;
         $s = $this->get_symbol($p);
         if ($s != TCML_END) {
            $text = $text . $s;
            $s = $this->get_symbol($p);
         }
      }
      $image = html::img_ini(_ini("icon.tipp"));
      $zufall = rand(1, 10000);
      $id = 'textpanel' . $zufall;
      $teil1 = '<div class="tcml-shortcut-block" id ="kurztipp" style="margin:0 0 12px 0;"><a href="#' . $id . '" data-bs-toggle="collapse" style="padding:5px 0px 5px 0px;">' . html::img_ini(_ini('icon.tipp')) . $title . '</a></div>';
      $teil2 = '<div id="' . $id . '" class ="panel panel-warning collapse"><div class = "panel-heading"><div  class = "tipp">' . $text . '</div></div></div>';
      $this->write($teil1 . $teil2);
      $s = $this->get_symbol($p);
   }

   /*
  function hinweis2 (&$s, &$p) {
   $this->tagdivision($s, $p);
   $title = $s;
   $this->tagdivision($s,$p);
   if ($s==TCML_BEGIN) {
      $text="";
   } else {
       $text = $s;
       $s = $this->get_symbol($p);
   }
   while ($s==TCML_BEGIN) {
        $anzbegin= 0;
        $fertig=0;
        $innercode = $s;
        while ($fertig==0) {
          $s = $this->get_symbol($p);
          $innercode = $innercode.$s;
          if ($s == TCML_BEGIN) {
             $anzbegin++;
          }
          if ($s == TCML_END) {
            if ($anzbegin==0) {
              $fertig=1;
            } else {
               $anzbegin--;
            }
          }
        }
        $inner = new txt_engine($innercode, $this->iRequest, false);
        $neu = $inner->output;
        $text = $text.$neu;
        $s = $this->get_symbol($p);
        if ($s!=TCML_END) {
           $text = $text.$s;
           $s=$this->get_symbol($p);
        }
   }
   $image = html::img_ini (_ini ("icon.hinweis"));
   $zufall = rand(1,10000);
   $id = 'textpanel'.$zufall;
   $teil1 = '<div id ="kurztipp"><a href="#'.$id.'" data-bs-toggle="collapse" style="padding:5px 0px 5px 0px;">'.html::img_ini (_ini ('icon.hinweis'))
       . "&nbsp;" . $title.'</a></div>';
   $teil2 = '<div id="'.$id.'" class ="panel panel-info collapse"><div class = "panel-heading"><div class="tipp">'.$text.'</div></div></div>';
   $this->write ($teil1.$teil2);
   $s = $this->get_symbol($p);
}
*/

   function hinweis(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $title = $s;
      $this->tagdivision($s, $p);
      if ($s == TCML_BEGIN) {
         $text = "";
      } else {
         $text = $s;
         $s = $this->get_symbol($p);
      }
      while ($s == TCML_BEGIN) {
         $anzbegin = 0;
         $fertig = 0;
         $innercode = $s;
         while ($fertig == 0) {
            $s = $this->get_symbol($p);
            $innercode = $innercode . $s;
            if ($s == TCML_BEGIN) {
               $anzbegin++;
            }
            if ($s == TCML_END) {
               if ($anzbegin == 0) {
                  $fertig = 1;
               } else {
                  $anzbegin--;
               }
            }
         }
         $inner = new txt_engine($innercode, $this->iRequest, false);
         $neu = $inner->output;
         $text = $text . $neu;
         $s = $this->get_symbol($p);
         if ($s != TCML_END) {
            $text = $text . $s;
            $s = $this->get_symbol($p);
         }
      }
      $image = html::img_ini(_ini("icon.hinweis"));
      $zufall = rand(1, 10000);
      $id = 'textpanel' . $zufall;
      $teil1 = '<div class="tcml-shortcut-block" id ="kurztipp" style="margin:0 0 12px 0;"><a href="#' . $id . '" data-bs-toggle="collapse" style="padding:5px 0px 5px 0px;">' . html::img_ini(_ini('icon.hinweis'))
         . "&nbsp;" . $title . '</a></div>';
      $teil2 = '<div id="' . $id . '" class ="panel panel-info collapse" style="border-width: 1px; border-radius:10px; background-color: #edfaff;"><div class = "panel-body"><div class="tipp">' . $text . '</div></div></div>';
      $this->write($teil1 . $teil2);
      $s = $this->get_symbol($p);
   }


   function aufg(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $nr = $s;
      $this->tagdivision($s, $p);
      if ($s == TCML_BEGIN) {
         $text = "";
      } else {
         $text = $s;
         $s = $this->get_symbol($p);
      }
      while ($s == TCML_BEGIN) {
         $anzbegin = 0;
         $fertig = 0;
         $innercode = $s;
         while ($fertig == 0) {
            $s = $this->get_symbol($p);
            $innercode = $innercode . $s;
            if ($s == TCML_BEGIN) {
               $anzbegin++;
            }
            if ($s == TCML_END) {
               if ($anzbegin == 0) {
                  $fertig = 1;
               } else {
                  $anzbegin--;
               }
            }
         }
         $inner = new txt_engine($innercode, $this->iRequest, false);
         $neu = $inner->output;
         $text = $text . $neu;
         $s = $this->get_symbol($p);
         if ($s != TCML_END) {
            $text = $text . $s;
            $s = $this->get_symbol($p);
         }
      }
      $teil1 = '<div class="tcml-shortcut-block" style="margin:0 0 12px 0;"><button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#' . $nr . '">Aufgabenstellung ' . $nr . '<span class="caret"></span></button></div>';
      $teil2 = '<div class="panel panel-primary collapse" id="' . $nr . '">
             <div class="panel-body">' . $text . '</div>
           </div>';
      $this->write($teil1 . $teil2);
      $s = $this->get_symbol($p);
   }

   function tippauf(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $title = $s;
      $this->tagdivision($s, $p);
      if ($s == TCML_BEGIN) {
         $text = "";
      } else {
         $text = $s;
         $s = $this->get_symbol($p);
      }
      while ($s == TCML_BEGIN) {
         $anzbegin = 0;
         $fertig = 0;
         $innercode = $s;
         while ($fertig == 0) {
            $s = $this->get_symbol($p);
            $innercode = $innercode . $s;
            if ($s == TCML_BEGIN) {
               $anzbegin++;
            }
            if ($s == TCML_END) {
               if ($anzbegin == 0) {
                  $fertig = 1;
               } else {
                  $anzbegin--;
               }
            }
         }
         $inner = new txt_engine($innercode, $this->iRequest, false);
         $neu = $inner->output;
         $text = $text . $neu;
         $s = $this->get_symbol($p);
         if ($s != TCML_END) {
            $text = $text . $s;
            $s = $this->get_symbol($p);
         }
      }
      $image = html::img_ini(_ini("icon.tipp"));
      $id = 'textpanel' . $p;
      $teil1 = '<div class="tcml-shortcut-block" id ="kurztipp" style="margin:0 0 12px 0;"><a href="#' . $id . '" data-bs-toggle="collapse" style="padding:5px 0px 5px 0px;">' . html::img_ini(_ini('icon.tipp')) . $title . '</a></div>';
      $teil2 = '<div id="' . $id . '" class ="panel panel-warning collapse"><div class = "panel-heading"><div  class = "tipp">' . $text . '</div></div></div>';
      $this->write($teil1 . $teil2);
      $s = $this->get_symbol($p);
   }

   function ddp(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $bezeichnung = $s;
      $this->tagdivision($s, $p);
      $header = $s;
      $this->tagdivision($s, $p);
      if ($s == TCML_BEGIN) {
         $text = "";
      } else {
         $text = $s;
         $s = $this->get_symbol($p);
      }
      while ($s == TCML_BEGIN) {
         $anzbegin = 0;
         $fertig = 0;
         $innercode = $s;
         while ($fertig == 0) {
            $s = $this->get_symbol($p);
            $innercode = $innercode . $s;
            if ($s == TCML_BEGIN) {
               $anzbegin++;
            }
            if ($s == TCML_END) {
               if ($anzbegin == 0) {
                  $fertig = 1;
               } else {
                  $anzbegin--;
               }
            }
         }
         $inner = new txt_engine($innercode, $this->iRequest, false);
         $neu = $inner->output;
         $text = $text . $neu;
         $s = $this->get_symbol($p);
         if ($s != TCML_END) {
            $text = $text . $s;
            $s = $this->get_symbol($p);
         }
      }
      $teil1 = '<style>
            .panel-heading .panel-dropdown:after {
            /* symbol for "opening" panels */
            font-family: "Glyphicons Halflings";  /* essential for enabling glyphicon */
            content: "\e114";    /* adjust as needed, taken from bootstrap.css */
            float: right;        /* adjust as needed */
            color: grey;         /* adjust as needed */
            }
          .panel-heading .panel-dropdown.collapsed:after {
              /* symbol for "collapsed" panels */
              content: "\e080";    /* adjust as needed, taken from bootstrap.css */
          }
       </style>';

      $teil2 = '<div class="panel panel-default tcml-shortcut-block" style="margin:2px 0;">
               <div class="panel-heading" >
               <h4 class  ="panel-title" >
                   <a class="panel-dropdown collapsed" data-bs-toggle="collapse"
                   href="#' . $bezeichnung . '" style="font-variant:normal;font-weight:bold;">'
         . $header .
         '</a>
                </h4>
              </div>
              <div id="' . $bezeichnung . '" class="panel-collapse collapse">
              <div class="panel-body">' . $text . '</div>
           </div>
           </div>';
      $this->write($teil1 . $teil2);
      $s = $this->get_symbol($p);
   }

   function ddpmenu(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $bezeichnung = $s;
      $this->tagdivision($s, $p);
      $header = $s;
      $this->tagdivision($s, $p);
      $f = $s;
      switch ($f) {
         case "blau": {
            $farbe = "rgba(0, 153, 208, 0.3)";
            break;
         }
         case "rot": {
            $farbe = "rgba(242, 222, 222, 0.5)";
            break;
         }
         case "gelb": {
            $farbe = "rgba(255, 239, 52, 0.3)";
            break;
         }
         default:
            $farbe = "grey";
      }
      $this->tagdivision($s, $p);
      if ($s == TCML_BEGIN) {
         $text = "";
      } else {
         $text = $s;
         $s = $this->get_symbol($p);
      }
      while ($s == TCML_BEGIN) {
         $anzbegin = 0;
         $fertig = 0;
         $innercode = $s;
         while ($fertig == 0) {
            $s = $this->get_symbol($p);
            $innercode = $innercode . $s;
            if ($s == TCML_BEGIN) {
               $anzbegin++;
            }
            if ($s == TCML_END) {
               if ($anzbegin == 0) {
                  $fertig = 1;
               } else {
                  $anzbegin--;
               }
            }
         }
         $inner = new txt_engine($innercode, $this->iRequest, false);
         $neu = $inner->output;
         $text = $text . $neu;
         $s = $this->get_symbol($p);
         if ($s != TCML_END) {
            $text = $text . $s;
            $s = $this->get_symbol($p);
         }
      }
      $teil1 = '<style>
            .panel-default>.panel-heading .panel-dropdown:after {
            /* symbol for "opening" panels */
            font-family: "Glyphicons Halflings";  /* essential for enabling glyphicon */
            content: "\e114";    /* adjust as needed, taken from bootstrap.css */
            float: right;        /* adjust as needed */
            color: grey;
            }

          .panel-heading .panel-dropdown.collapsed:after {
              /* symbol for "collapsed" panels */
              content: "\e080";    /* adjust as needed, taken from bootstrap.css */
          }
       </style>';

      $teil2 = '<div class="panel panel-default tcml-shortcut-block" style="margin:2px 0;">
               <div class="panel-heading" style="background:' . $farbe . ';">
               <h4 class  ="panel-title" >
                   <a class="panel-dropdown collapsed" data-bs-toggle="collapse"
                   href="#' . $bezeichnung . '" style="font-variant:normal;font-weight:bold;">'
         . $header .
         '</a>
                </h4>
              </div>
              <div id="' . $bezeichnung . '" class="panel-collapse collapse">
              <div class="panel-body">' . $text . '</div>
           </div>
           </div>';
      $this->write($teil1 . $teil2);
      $s = $this->get_symbol($p);
   }


   function tippbild(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $title = $s;
      $this->tagdivision($s, $p);
      $bild = $s;
      $this->tagdivision($s, $p);
      $groesse = $s;
      $text = '<img src=' . $bild . ' width = ' . $groesse . '/>';

      $image = html::img_ini(_ini("icon.tipp"));
      $id = 'textpanel' . $p;
      $teil1 = '<div id ="kurztipp"><a href="#' . $id . '" data-bs-toggle="collapse" style="padding:5px 0px 5px 0px;">' . html::img_ini(_ini('icon.tipp')) . $title . '</a></div>';
      $teil2 = '<div id="' . $id . '" class ="panel panel-warning collapse"><div  class = "panel-heading">' . $text . '</div></div>';
      $this->write($teil1 . $teil2);
      $this->tagend($s, $p);
   } // function tippbild


   function tippvideo(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $title = $s;
      $this->tagdivision($s, $p);
      $video = $s;
      $this->tagdivision($s, $p);
      $groesse = $s;
      $text = '<video controls="controls" width=' . $groesse . ' ><source src=' . $video . ' type="video/mp4" /></video>';

      $image = html::img_ini(_ini("icon.tipp"));
      $id = 'textpanel' . $p;
      $teil1 = '<div id ="kurztipp"><a href="#' . $id . '" data-bs-toggle="collapse" style="padding:5px 0px 5px 0px;">' . html::img_ini(_ini('icon.tipp')) . $title . '</a></div>';
      $teil2 = '<div id="' . $id . '" class ="panel panel-warning collapse"><div  class = "panel-heading">' . $text . '</div></div>';
      $this->write($teil1 . $teil2);
      $this->tagend($s, $p);
   } // function tipvideo

   function datei(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $file = $this->iRequest->get_SeitePathIntern() . $s;
      //$file = $this->path . '/' . $s;
      // !!!!! Erst mal prüfen, ob die Datei da ist!
      if (file_exists($file)) {
         $this->write('<a href="' . $file . '" target="text">');
      } else {
         $this->error('Ungültiger Dateiname: ' . $s
            . ' (siehe: Hochgeladene Dateien)', __LINE__);
      } //else
      $this->tagdivision($s, $p);
      while (($s != TCML_END) and ($p < $this->inplen)) {
         $this->parse_vcml_2($s, $p);
      }
      if ($s != TCML_END) {
         $this->error($s, __LINE__);
      }
      $s = $this->get_symbol($p);
      $this->write('</a>');
   } //function datei

   function karussell(&$s, &$p)
   {
      static $karussellNr = 0;
      $this->tagdivision($s, $p);
      $name = $s;
      $karussellNr++;
      $carouselId = "karussell_" . preg_replace('/[^A-Za-z0-9_-]/', '_', $name) . "_" . $karussellNr;
      $this->tagdivision($s, $p);
      $pfad = $s;
      $this->tagdivision($s, $p);
      $anzahl = $s;
      $html1 = "<style> .carousel-control.right, .carousel-control.left {
              background-image:none;
            }

.carousel-inner {
  margin-bottom:35px;
}
.carousel-indicators {
    position: absolute;
    bottom:-30px;
    background-color: #fff;
    left: 50%;
    z-index: 15;
    width: 60%;
    padding-left: 0;
    text-align:center;
    list-style: none;
    display: inline-block;
}
.carousel-indicators .active{
  background-color:black;
  border-color: white;
}
.carousel-indicators li{
  background-color:white;
  border-color: black;
}
  </style>";
      $html1 = $html1 . "<div class ='row'><div class = 'col-md-12'>";
      if (file_exists($pfad . $name . '1.png.jpg')) {
         $ext = '.png.jpg';
      } else if (file_exists($pfad . $name . '1.png')) {
         $ext = '.png';
      } else if (file_exists($pfad . $name . '1.jpg')) {
         $ext = '.jpg';
      } else {
         $ext = '';
      }
      $html1 = $html1 . "<div id='" . $carouselId . "' class = 'carousel slide' data-wrap='true' data-interval='false' >";
      $html1 = $html1 . " <ol class='carousel-indicators' style = 'float: right;'> <li data-bs-target='#" . $carouselId . "' data-bs-slide-to='0' class='active' ></li>";
      for ($zaehler = 1; $zaehler < $anzahl; $zaehler++) {
         $nummer = $zaehler;
         $html2 = "<li data-bs-target='#" . $carouselId . "' data-bs-slide-to='" . $nummer . "' </li>";
         $html1 = $html1 . $html2;
      }
      $html1 = $html1 . "</ol><div class='carousel-inner' role='listbox'>";
      $html1 = $html1 . "<div class='carousel-item active'><img src='" . $pfad . $name . "1" . $ext . "' alt='" . $name . "'1 class='img-responsive center-block'>
    </div>";
      for ($zaehler = 2; $zaehler <= $anzahl; $zaehler++) {
         $nummer = $zaehler;
         $html2 =
            "<div class='carousel-item'>
      <img src='" . $pfad . $name . $nummer . $ext . "' alt='" . $name . $nummer . "' class='img-responsive center-block'>
     </div>";
         $html1 = $html1 . $html2;
      }
      $html1 = $html1 . "</div>";
      $html2 = "<a class='left carousel-control' href='#" . $carouselId . "' role='button' data-bs-slide='prev'>
      <span class='glyphicon glyphicon-chevron-left' aria-hidden='true' style='color:black;'></span>
      <span class='sr-only'>Previous</span>
    </a>
    <a class='right carousel-control' href='#" . $carouselId . "' role='button' data-bs-slide='next'>
      <span class='glyphicon glyphicon-chevron-right' aria-hidden='true' style='color:black;'></span>
      <span class='sr-only'>Next</span>
    </a>
  </div></div></div>";
      $html1 = $html1 . $html2;
      $this->write($html1);
      $this->tagend($s, $p);
   }


   function karussellth(&$s, &$p)
   {
      static $karussellThNr = 0;
      $this->tagdivision($s, $p);
      $name = $s;
      $karussellThNr++;
      $carouselId = "karussellth_" . preg_replace('/[^A-Za-z0-9_-]/', '_', $name) . "_" . $karussellThNr;
      $id = "#" . $carouselId;
      $this->tagdivision($s, $p);
      $pfad = $s;
      $this->tagdivision($s, $p);
      $anzahl = intval($s);
      $endindex = $anzahl - 1;
      $html1 = "<style> .carousel-control.right, .carousel-control.left {
              background-image:none;
            }

.carousel-inner {
  margin-bottom:35px;
}
.carousel-indicators {
    position: absolute;
    bottom:-30px;
    background-color: #fff;
    left: 50%;
    z-index: 15;
    width: 60%;
    padding-left: 0;
    text-align:center;
    list-style: none;
    display: inline-block;
}
.carousel-indicators .active{
  background-color:black;
  border-color: white;
}
.carousel-indicators li{
  background-color:white;
  border-color: black;
}
  </style>";
      $html1 = $html1 . "<div class ='row'><div class = 'col-md-12'>";
      if (file_exists($pfad . $name . '1.png.jpg')) {
         $ext = '.png.jpg';
      } else if (file_exists($pfad . $name . '1.png')) {
         $ext = '.png';
      } else if (file_exists($pfad . $name . '1.jpg')) {
         $ext = '.jpg';
      } else {
         $ext = '';
      }
      $html1 = $html1 . "<div id='" . $carouselId . "' class = 'carousel slide' data-interval='false' data-wrap='false'>";
      $html1 = $html1 . " <ol class='carousel-indicators' style = 'float: right;'> <li data-bs-target='#" . $carouselId . "' data-bs-slide-to='0' class='active' ></li>";
      for ($zaehler = 1; $zaehler < $anzahl; $zaehler++) {
         $nummer = $zaehler;
         $html2 = "<li data-bs-target='#" . $carouselId . "' data-bs-slide-to='" . $nummer . "' </li>";
         $html1 = $html1 . $html2;
      }
      $html1 = $html1 . "</ol><div class='carousel-inner' role='listbox'>";
      $html1 = $html1 . "<div class='carousel-item active'>

   <div class = 'row align-items-center'>
    <div class ='col-md-3' >
    </div>
    <div class ='col-md-6'>
      <img src='" . $pfad . $name . "1" . $ext . "' alt='" . $name . "'1 class='img-responsive center-block'>
    </div>
    <div class ='col-md-3'>
       <img src='" . $pfad . $name . "2" . $ext . "' alt='" . $name . "'2 class='img-responsive center-block'>
    </div>
    </div>

    </div>";
      for ($zaehler = 2; $zaehler <= $anzahl; $zaehler++) {
         $nummer = $zaehler;
         $vorgaenger = $nummer - 1;
         $nachfolger = $nummer + 1;
         if ($nachfolger > $anzahl) {
            $nachfolger = 1;
         }
         if ($nummer < $anzahl) {
            $html2 =
               "<div class='carousel-item'>

      <div class='row align-items-center'>
      <div class='col-md-3' >
      <img src='" . $pfad . $name . $vorgaenger . $ext . "' alt='" . $name . $vorgaenger . "' class='img-responsive'>
       </div>
       <div class='col-md-6 '>
         <img src='" . $pfad . $name . $nummer . $ext . "' alt='" . $name . $nummer . "' class='img-responsive center-block'>
       </div>
       <div class='col-md-3 '>
          <img src='" . $pfad . $name . $nachfolger . $ext . "' alt='" . $name . $nachfolger . "' class='img-responsive'>
       </div>
       </div>

    </div>";
         } else {
            $html2 =
               "<div class='carousel-item'>

      <div class='row align-items-center'>
      <div class='col-md-3' >
      <img src='" . $pfad . $name . $vorgaenger . $ext . "' alt='" . $name . $vorgaenger . "' class='img-responsive'>
       </div>
       <div class='col-md-6 '>
         <img src='" . $pfad . $name . $nummer . $ext . "' alt='" . $name . $nummer . "' class='img-responsive center-block'>
       </div>
       <div class='col-md-3 '>
       </div>
       </div>

    </div>";
         }
         $html1 = $html1 . $html2;
      }
      $html1 = $html1 . "</div>";

      $html2 = "<a class='left carousel-control' href='#" . $carouselId . "' role='button' data-bs-slide='prev'>
      <span class='glyphicon glyphicon-chevron-left' aria-hidden='true' style='color:black;'></span>
      <span class='sr-only'>Previous</span>
    </a>
    <a class='right carousel-control' href='#" . $carouselId . "' role='button' data-bs-slide='next'>
      <span class='glyphicon glyphicon-chevron-right' aria-hidden='true' style='color:black;'></span>
      <span class='sr-only'>Next</span>
    </a>
  </div></div></div>";
      $html3 =
         "<script type='text/javascript'>
	   $(document).ready(function(){
	     var numSlides = " . $anzahl . "-1;
	     var carousel = $('" . $id . "');

	     carousel.find('.left').hide();
	    carousel.bind('slid.bs.carousel', function (ev)
	   {

	     var carouselEl = $(this);
	     var carouselItems = carouselEl.find('.carousel-item');
	     var number =carouselItems.siblings('.active').index();
	     if (number===0){
	       carouselEl.find('.left').hide();
	        carouselEl.find('.right').show();}
	    else if (number === numSlides) {
	       carouselEl.find('.right').hide();
	       carouselEl.find('.left').show();
	       number = 0;
	     } else {
	       carouselEl.find('.carousel-control').show();
	     }
	   });
	});
</script>";

      $html1 = $html1 . $html2 . $html3;
      $this->write($html1);
      $this->tagend($s, $p);
   }



   function edittable(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $zeilen = $s;
      $this->tagdivision($s, $p);
      $spalten = $s;
      /* $this->tagdivision($s, $p);*/
      $anzahlDaten = $zeilen * $spalten;
      $this->tagdivision($s, $p);
      $name = substr($s, 0, 1);
      $zufall = rand(1, 10000);
      $name = $name . $zufall;
      $datenarray = explode("|", $s);
      $jsArray = json_encode($datenarray);
      $parameter = $jsArray;
      //$name = "table".$number;
      $script1 = '<script type="text/javascript">';
      $script3 = 'ko.applyBindings(new table2ViewModel(' . $parameter . '), document.getElementById("' . $name . '"));';
      $script4 = '</script>';
      $script = $script1 . $script3 . $script4;
      $html = '<div id = "' . $name . '"> <table class = " table table-bordered" >
      <tbody >';
      for ($i = 0; $i < $zeilen; $i++) {
         $zeilentext = '<tr>';
         $datenspalten = $spalten;
         for ($j = 0; $j < $datenspalten; $j++) {
            $index = $i * $datenspalten + $j;
            if ($datenarray[$index][0] == "#") {
               $spaltentext = '<td> <input class = "gelb" style = "text-align:center;width:100%;" data-bind="value: $data.zahlen()[' . $index . '].input, style: {color:$data.zahlen()[' . $index . '].colour}"></td>';
            } else {
               $spaltentext = '<td  style = "text-align:center;" data-bind="html:$data.zahlen()[' . $index . '].wert"></td>';
            }
            $zeilentext = $zeilentext . $spaltentext;
         }
         $html = $html . $zeilentext . '</tr>';
      }
      $html = $html . '</tbody></table>
  <button type = "button" class="btn btn-warning hidden-print" data-bind="click:$data.pruefe">prüfen</button>
  <button type = "button" class="btn btn-danger hidden-print" data-bind="click:$data.loeschen">löschen</button>
    <button type = "button" class="btn btn-success hidden-print" data-bind="click:$data.loesen">lösen</button></div>';
      $this->write($html);
      $this->write($script);
      $this->tagend($s, $p);
   }

/*alle Zellen zentriert*/
   function edittable2(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $zeilen = $s;
      $this->tagdivision($s, $p);
      $spalten = $s;
      /* $this->tagdivision($s, $p);*/
      $anzahlDaten = $zeilen * $spalten;
      $this->tagdivision($s, $p);
      $name = substr($s, 0, 1);
      $zufall = rand(1, 10000);
      $name = $name . $zufall;
      $datenarray = explode("|", $s);
      $jsArray = json_encode($datenarray);
      $parameter = $jsArray;

      $number = $this->tablenumber;
      //$name = "table".$number;
      $script1 = '<script>';
      $script3 = 'ko.applyBindings(new table2ViewModel(' . $parameter . '), document.getElementById("' . $name . '"));';
      $script4 = '</script>';
      $script = $script1 . $script3 . $script4;
      $html = '<div id = "' . $name . '"> <table class = " table table-bordered" >
       <tbody >';
      for ($i = 0; $i < $zeilen; $i++) {
         $zeilentext = '<tr>';
         $datenspalten = $spalten;
         for ($j = 0; $j < $datenspalten; $j++) {
            $index = $i * $datenspalten + $j;
            if ($datenarray[$index][0] == "#") {
               $spaltentext = '<td> <input class = "gelb" style = "text-align:center;width:100%;" data-bind="value: $data.zahlen()[' . $index . '].input, style: {color:$data.zahlen()[' . $index . '].colour}"></td>';
            } else {
               $spaltentext = '<td  style = "text-align:left;" data-bind="html: $data.zahlen()[' . $index . '].wert"></td>';
            }
            $zeilentext = $zeilentext . $spaltentext;
         }
         $html = $html . $zeilentext . '</tr>';
      }
      $html = $html . '</tbody></table>
   <button type = "button" class="btn btn-warning hidden-print" data-bind="click:$data.pruefe">prüfen</button>
   <button type = "button" class="btn btn-danger hidden-print" data-bind="click:$data.loeschen">löschen</button>
     <button type = "button" class="btn btn-success hidden-print" data-bind="click:$data.loesen">lösen</button></div>';
      $this->write($html);
      $this->write($script);
      $this->tagend($s, $p);
   }
/*alle Zellen linksbündig*/
   function edittabletext(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $zeilen = $s;
      $this->tagdivision($s, $p);
      $spalten = $s;
      /* $this->tagdivision($s, $p);*/
      $anzahlDaten = $zeilen * $spalten;
      $this->tagdivision($s, $p);
      $anzahlzeilen = $s;
      $this->tagdivision($s, $p);
      $name = substr($s, 0, 1);
      $zufall = rand(1, 10000);
      $name = $name . $zufall;
      $datenarray = explode("|", $s);
      $jsArray = json_encode($datenarray);
      $parameter = $jsArray;
      //$name = "table".$number;
      $script1 = '<script type="text/javascript">';
      $script3 = 'ko.applyBindings(new table2ViewModel(' . $parameter . '), document.getElementById("' . $name . '"));';
      $script4 = '</script>';
      $script = $script1 . $script3 . $script4;
      $html = '<div id = "' . $name . '"> <table class = " table table-bordered" >
       <tbody >';
      for ($i = 0; $i < $zeilen; $i++) {
         $zeilentext = '<tr>';
         $datenspalten = $spalten;
         for ($j = 0; $j < $datenspalten; $j++) {
            $index = $i * $datenspalten + $j;
            if ($datenarray[$index][0] == "#") {
               $spaltentext = '<td> <textarea class = "gelb" style = "text-align:left;width:100%;" rows = "' . $anzahlzeilen . '" data-bind="value: $data.zahlen()[' . $index . '].input,
                                 style: {color:$data.zahlen()[' . $index . '].colour}"></textarea> </td>';
            } else {
               $spaltentext = '<td  style = "text-align:left;" data-bind="html: $data.zahlen()[' . $index . '].wert"></td>';
            }
            $zeilentext = $zeilentext . $spaltentext;
         }
         $html = $html . $zeilentext . '</tr>';
      }
      $html = $html . '</tbody></table>
   <button type = "button" class="btn btn-danger hidden-print" data-bind="click:$data.loeschen">löschen</button></div>';
      $this->write($html);
      $this->write($script);
      $this->tagend($s, $p);
   }

   function edittableol(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $zeilen = $s;
      $this->tagdivision($s, $p);
      $spalten = $s;
      /* $this->tagdivision($s, $p);*/
      $anzahlDaten = $zeilen * $spalten;
      $zufall = rand(1, 10000);
      $name = "tab" . $zufall;
      $this->tagdivision($s, $p);
      $datenarray = explode("|", $s);
      $jsArray = json_encode($datenarray);

      $parameter = $jsArray;
      $script1 = '<script type="text/javascript">';
      $script3 = 'ko.applyBindings(new table2ViewModel(' . $parameter . '), document.getElementById("' . $name . '"));';
      $script4 = '</script>';
      $script = $script1 . $script3 . $script4;
      //$this->tablenumber++;
      $html = '<div id = "' . $name . '"> <table class = " table table-bordered" >
      <tbody >';
      for ($i = 0; $i < $zeilen; $i++) {
         $zeilentext = '<tr>';
         $datenspalten = $spalten;
         for ($j = 0; $j < $datenspalten; $j++) {
            $index = $i * $datenspalten + $j;
            //*debug*/_debug('index', $index, '', __LINE__, __FILE__);
            //*debug*/_debug('datenarray', $datenarray, '', __LINE__, __FILE__);//die("STOP: ".__LINE__);
            if (isset($datenarray[$index][0]) and $datenarray[$index][0] == "#") {
               $spaltentext = '<td> <input class = "gelb" style = "text-align:center;width:100%;" data-bind="value: $data.zahlen()[' . $index . '].input, style: {color:$data.zahlen()[' . $index . '].colour, textDecoration:$data.zahlen()[' . $index . '].textdeco}"></td>';
            } else {
               $spaltentext = '<td  style = "text-align:center; text-decoration:normal;" data-bind="html: $data.zahlen()[' . $index . '].wert"></td>';
            }
            $zeilentext = $zeilentext . $spaltentext;
         }
         $html = $html . $zeilentext . '</tr>';
      }
      $html = $html . '</tbody></table>
  <button type = "button" class="btn btn-warning hidden-print" data-bind="click:$data.pruefe">prüfen</button>
  <button type = "button" class="btn btn-danger hidden-print" data-bind="click:$data.loeschen">löschen</button></div>';
      /*  <button type = "button" class="btn btn-success hidden-print" data-bind="click:$data.loesen">lösen</button></div>';*/
      $this->write($html);
      $this->write($script);
      $this->tagend($s, $p);
   }

   function edittable2ol(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $zeilen = $s;
      $this->tagdivision($s, $p);
      $spalten = $s;
      /* $this->tagdivision($s, $p);*/
      $anzahlDaten = $zeilen * $spalten;
      $this->tagdivision($s, $p);
      $name = "table";
      $zufall = rand(1, 10000);
      $name = $name . $zufall;
      $datenarray = explode("|", $s);
      $jsArray = json_encode($datenarray);

      $parameter = $jsArray;

      $script1 = '<script type="text/javascript">';
      $script3 = 'ko.applyBindings(new table2ViewModel(' . $parameter . '), document.getElementById("' . $name . '"));';
      $script4 = '</script>';
      $script = $script1 . $script3 . $script4;
      //$this->tablenumber++;
      $html = '<div id = "' . $name . '"> <table class = " table table-bordered" >
       <tbody >';
      for ($i = 0; $i < $zeilen; $i++) {
         $zeilentext = '<tr>';
         $datenspalten = $spalten;
         for ($j = 0; $j < $datenspalten; $j++) {
            $index = $i * $datenspalten + $j;
            if ($datenarray[$index][0] == "#") {
               $spaltentext = '<td> <input class = "gelb" style = "text-align:center;width:100%;" data-bind="value: $data.zahlen()[' . $index . '].input, style: {color:$data.zahlen()[' . $index . '].colour, textDecoration:$data.zahlen()[' . $index . '].textdeco}"></td>';
            } else {
               $spaltentext = '<td  style = "text-align:left;" data-bind="text: $data.zahlen()[' . $index . '].wert"></td>';
            }
            $zeilentext = $zeilentext . $spaltentext;
         }
         $html = $html . $zeilentext . '</tr>';
      }
      $html = $html . '</tbody></table>
   <button type = "button" class="btn btn-warning hidden-print" data-bind="click:$data.pruefe">prüfen</button>
   <button type = "button" class="btn btn-danger hidden-print" data-bind="click:$data.loeschen">löschen</button></div>';
      /*  <button type = "button" class="btn btn-success hidden-print" data-bind="click:$data.loesen">lösen</button></div>';*/
      $this->write($html);
      $this->write($script);
      $this->tagend($s, $p);
   }

   function editvt(&$s, &$p)
   { //Syntax: [@[editvt::|Spalte1|::|Spalte2|::|Zeile1|::|Zeile2|::DatenArray]@]
      // mit Datenarray: d1|d2|...|d9
      $this->tagdivision($s, $p);
      $spalte1 = $s;
      $this->tagdivision($s, $p);
      $spalte2 = $s;
      $this->tagdivision($s, $p);
      $zeile1 = $s;
      $this->tagdivision($s, $p);
      $zeile2 = $s;
      $this->tagdivision($s, $p);
      $name = $s;
      $datenarray = explode("|", $s);
      if (count($datenarray) == 9) {
         $jsArray = json_encode($datenarray);
         $parameter = $jsArray;
         //$number = $this->tablenumber;
         //$name = "vttable".$number;
         $script1 = '<script type="text/javascript">';
         $script3 = 'ko.applyBindings(new VierFelderViewModel(' . $parameter . '), document.getElementById("' . $name . '"))';
         $script4 = '</script>';
         $script = $script1 . $script3 . $script4;
         $this->tablenumber++;
         $html = '<div id = "' . $name . '"> <table class = " table table-bordered" >
      <tbody >
      <tr class="default" style ="border-bottom:2px solid #999;" >
        <td style = "text-align:center; border-right:2px solid #999;"></td>
        <td style = "text-align:center; ">' . $spalte1 . '</td>
        <td style = "text-align:center;border-right:2px solid #999; ">' . $spalte2 . '</td>
        <td style = "text-align:center; ">Gesamt</td>
      </tr>
     <tr class = "default">
        <td style = "text-align:center;border-right:2px solid #999;">' . $zeile1 . '</td>';

         if (substr($datenarray[0], 0, 1) != '#') {
            $spalte = '<td  style = "text-align:center;" data-bind="text: $data.zahlen()[0].wert"></td>';
         } else {
            $spalte = '<td> <input class = "gelb" style = "text-align:center;width:100%;" data-bind="value: $data.zahlen()[0].input, style: {color:$data.zahlen()[0].colour, textDecoration:$data.zahlen()[0].textdeco}"></td>';
         }
         $html = $html . $spalte;
         if (substr($datenarray[1], 0, 1) != '#') {
            $spalte = '<td  style = "text-align:center; border-right:2px solid #999;" data-bind="text: $data.zahlen()[1].wert"></td>';
         } else {
            $spalte = '<td style = "border-right:2px solid #999;"> <input class = "gelb" style = "text-align:center;width:100%;"   style = "text-align:center;" data-bind="value: $data.zahlen()[1].input, style: {color:$data.zahlen()[1].colour, textDecoration:$data.zahlen()[1].textdeco}"></td>';
         }
         $html = $html . $spalte;
         if (substr($datenarray[2], 0, 1) != '#') {
            $spalte = '<td  style = "text-align:center;" data-bind="text: $data.zahlen()[2].wert"></td>';
         } else {
            $spalte = '<td> <input class = "gelb" style = "text-align:center;width:100%;"   style = "text-align:center;" data-bind="value: $data.zahlen()[2].input, style: {color:$data.zahlen()[2].colour, textDecoration:$data.zahlen()[2].textdeco}"></td></tr>';
         }
         $html = $html . $spalte;
         $zeile = '<tr class = "default" style ="border-bottom:2px solid #999;">
        <td style = "text-align:center;border-right:2px solid #999;">' . $zeile2 . '</td>';
         if (substr($datenarray[3], 0, 1) != "#") {
            $spalte = '<td  style = "text-align:center;" data-bind="text: $data.zahlen()[3].wert"></td>';
         } else {
            $spalte = '<td> <input class = "gelb" style = "text-align:center;width:100%;"   style = "text-align:center;" data-bind="value: $data.zahlen()[3].input, style: {color:$data.zahlen()[3].colour, textDecoration:$data.zahlen()[3].textdeco}"></td>';
         }
         $zeile = $zeile . $spalte;
         if (substr($datenarray[4], 0, 1) != "#") {
            $spalte = '<td  style = "text-align:center; border-right:2px solid #999;" data-bind="text: $data.zahlen()[4].wert"></td>';
         } else {
            $spalte = '<td style = "border-right:2px solid #999;"> <input class = "gelb" style = "text-align:center;width:100%;"   style = "text-align:center;" data-bind="value: $data.zahlen()[4].input, style: {color:$data.zahlen()[4].colour, textDecoration:$data.zahlen()[4].textdeco}"></td>';
         }
         $zeile = $zeile . $spalte;
         if (substr($datenarray[5], 0, 1) != "#") {
            $spalte = '<td  style = "text-align:center;" data-bind="text: $data.zahlen()[5].wert"></td>';
         } else {
            $spalte = '<td> <input class = "gelb" style = "text-align:center;width:100%;"   style = "text-align:center;" data-bind="value: $data.zahlen()[5].input, style: {color:$data.zahlen()[5].colour, textDecoration:$data.zahlen()[5].textdeco}"></td></tr>';
         }
         $zeile = $zeile . $spalte;
         $html = $html . $zeile;
         $zeile = '<tr class = "default"  >
        <td style = "text-align:center;border-right:2px solid #999;">Gesamt</td>';
         if (substr($datenarray[6], 0, 1) != "#") {
            $spalte = '<td  style = "text-align:center" data-bind="text: $data.zahlen()[6].wert"></td>';
         } else {
            $spalte = '<td> <input class = "gelb" style = "text-align:center;width:100%;"   style = "text-align:center;" data-bind="value: $data.zahlen()[6].input, style: {color:$data.zahlen()[6].colour, textDecoration:$data.zahlen()[6].textdeco}"></td>';
         }
         $zeile = $zeile . $spalte;
         if (substr($datenarray[7], 0, 1) != "#") {
            $spalte = '<td  style = "text-align:center; border-right:2px solid #999;" data-bind="text: $data.zahlen()[7].wert"></td>';
         } else {
            $spalte = '<td style = "border-right:2px solid #999;"> <input class = "gelb" style = "text-align:center;width:100%;"   style = "text-align:center;" data-bind="value: $data.zahlen()[7].input, style: {color:$data.zahlen()[7].colour, textDecoration:$data.zahlen()[7].textdeco}"></td>';
         }
         $zeile = $zeile . $spalte;
         if (substr($datenarray[8], 0, 1) != "#") {
            $spalte = '<td  style = "text-align:center;" data-bind="text: $data.zahlen()[8].wert"></td>';
         } else {
            $spalte = '<td> <input class = "gelb" style = "text-align:center;width:100%;"   style = "text-align:center;" data-bind="value: $data.zahlen()[8].input, style: {color:$data.zahlen()[8].colour, textDecoration:$data.zahlen()[8].textdeco}"></td></tr>';
         }
         $zeile = $zeile . $spalte;
         $html = $html . $zeile;

         $html = $html . '</tbody></table>
  <button type = "button" class="btn btn-warning hidden-print" data-bind="click:$data.pruefe">prüfen</button>
  <button type = "button" class="btn btn-danger hidden-print" data-bind="click:$data.loeschen">löschen</button>
   <button type = "button" class="btn btn-success hidden-print" data-bind="click:$data.loesen">lösen</button> </div>';
         $this->write($html);
         $this->write($script);
         $this->tagend($s, $p);
      }
   }

   function lueckentext(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $id = rand(1, 10000);
      $datenarray = explode("|", $s);
      $anzahl = count($datenarray);
      $this->tagdivision($s, $p);
      $text = $s;
      $jsArray = json_encode($datenarray);
      $parameter = $jsArray;
      $number = $this->tablenumber;
      $name = "text" . $id;
      $script1 = '<script type="text/javascript">';
      $script3 = 'ko.applyBindings(new lueckentextViewModel(' . $parameter . ',' . $id . '), document.getElementById("' . $name . '"));';
      $script4 = '</script>';
      $script = $script1 . $script3 . $script4;
      //$this->tablenumber++;
      for ($i = 0; $i < $anzahl; $i++) {
         $text = preg_replace("/#/", '<input name ="' . $name . $i . '" size = "' . strlen($datenarray[$i]) . '" type = "text" autocomplete="off" style = "text-align:center; font-size: 1.1em;margin-top:2px;" data-bind="value: $data.luecken()[' . $i . '].input, style: {color:$data.luecken()[' . $i . '].colour,textDecoration:$data.luecken()[' . $i . '].textdeco}"></input>', $text, 1);
      }
      $html = '<div id = "' . $name . '" class ="panel panel-success " ><div   class = "panel-heading" style = "font-weight:normal;color: black; font-size:1.1em;">' . $text . '</div>';
      $btnid = "loesenbtn" . $id;
      $html = $html . '
  <div><button type="button" class = "btn btn-warning hidden-print" data-bind="click:$data.pruefe">prüfen</button>
  <button type = "button" class="btn btn-danger hidden-print" data-bind="click:$data.loeschen">löschen</button>
   <button type = "button" id="' . $btnid . '" style="visibility:hidden;" class="btn btn-success hidden-print" data-bind="click:$data.loesen">lösen</button></div></div>';
      $this->write($html);
      $this->write($script);
      $this->tagend($s, $p);
   }

   function dndluecke(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $id = rand(1, 10000);
      $datenarray = explode("|", $s);
      $anzahl = count($datenarray);
      $this->tagdivision($s, $p);
      $text = $s;
      $lueckenzahl = 0;
      for ($i = 0; $i < strlen($text); $i++) {
         if ($text[$i] == "#") {
            $lueckenzahl++;
         }
      }
      //$datenarray=htmlspecialchars($datenarray);
      $html = ' <style>
     .dndluecke {
        display: block;
        background-color: rgba(155, 196, 57, 0.1);
        font-size: 1.1em;
        width: 100%;
        padding:1em;
     }
     .dropdiv {
         display: inline-block;
         border: 1px solid black;
         vertical-align: middle;
         background-color:white;
         min-width: 3em;
         min-height: 1.2em;
     }
     .ldiv {
        display: inline-block;
        background-color:white;
        padding:3px;
        margin:2px;
     }
     .loesungen {
         background-color:#ccc;
         margin:0.2em;
         min-width:50%;
         min-height:3em;
         padding:2px;
     }
  </style> ';

      $name = "ddlueck" . $id;
      $script1 = '<script src="https://syslib.net-schulbuch.de/js/DragDropTouch.js"></script>
             <script src="https://syslib.net-schulbuch.de/js/dndluecke.js"></script>
             <script>
                window.tcmlDndLueckeAllowDrop = window.allowDrop;
                window.tcmlDndLueckeDrop = window.drop;
                window.tcmlDndLueckeDropZurueck = window.dropzurueck;
                window.tcmlDndLueckeDrag = window.drag;
                window.tcmlDndLueckeDragLeave1 = window.dragLeave1;
                window.tcmlDndLueckeDragLeave2 = window.dragLeave2;
             </script>';
      $html = $html . $script1;
      $dndid = "dndluecke" . $id;
      $html = $html . '<div class="dndluecke" id=' . $dndid . '><div>';
      $luecke = '<div id="ablage" class="dropdiv" ondrop="tcmlDndLueckeDrop(event)" ondragover="tcmlDndLueckeAllowDrop(event)" ondragleave="tcmlDndLueckeDragLeave1(event)"></div>';
      for ($i = 0; $i < $lueckenzahl; $i++) {
         $neuid = "ablage" . $id . "_" . $i;
         $lueck = str_replace('ablage', $neuid, $luecke);
         $text = preg_replace("/#/", $lueck, $text, 1);
         //$pos = strpos($text,'#');
         //$neutext = $neutext.substr($text,0,$pos);
         //$neutext = $neutext.$lueck;
         //$text = substr($text,$pos+1);
      }
      $text = $text . '</div>';
      $html = $html . $text;
      $loesid = "loes" . $id;
      $html = $html . '<div class="loesungen" id=' . $loesid . ' draggable=false ondrop="tcmlDndLueckeDropZurueck(event)" ondragover ="tcmlDndLueckeAllowDrop(event)" ondragleave="tcmlDndLueckeDragLeave2(event)">';
      $woerter = '<div class="ldiv" id="loesung"' . $id . '  draggable="true" ondragstart="tcmlDndLueckeDrag(event)">#</div>';
      for ($i = 0; $i < $anzahl; $i++) {
         $neuid = "loesung" . $id . "_" . $i;
         $woerterneu = str_replace('loesung', $neuid, $woerter);
         $woerterneu = str_replace('#', $datenarray[$i], $woerterneu);
         $html = $html . $woerterneu;
      }
      $html = $html . '</div>';

      $html = $html . '<button type="button" class = "btn btn-warning hidden-print" onclick="obj' . $id . '.pruefen()">prüfen</button>';
      $html = $html . '<button type="button" class = "btn btn-danger hidden-print" style = "margin-left:3px;" onclick="obj' . $id . '.reset()">löschen</button></div>';
      $html = $html . '<script> var obj' . $id . ' = gibObjekt(' . $id . ',' . $anzahl . ',' . $lueckenzahl . ')</script>';
      $this->write($html);
      $this->tagend($s, $p);
   }

   function menu_reiter(&$s, &$p)
   {
      //**/ ddebug ('$s=', $s, __LINE__, __FILE__);
      //08.18 $this->write ('<div id="tabmenu" class="tabmenu" style=" padding-top:1px; background-color:#ffffff; "><ul class="nav nav-tabs tabs" style="margin-left:10px;" >');
      $this->write('<div id="tabmenu" class="tabmenu" style=" padding-top:1px; background-color:#ffffff; "><ul class="nav nav-tabs hidden-print" style="margin-left:10px;" >');
      $s = $this->get_symbol($p);
      //**/ ddebug ('s=', $s, __LINE__, __FILE__);
      while (($s == TCML_SEP) and ($p < $this->inplen)) {
         //$this->tagdivision ($s, $p);
         //**/ ddebug ('s=', $s, __LINE__, __FILE__);
         $s = $this->get_symbol($p); // in $s steht der übergebene NET-CODE
         //**/ ddebug ('s=', $s, __LINE__, __FILE__);
         // HTML-Tags gnadenlos entfernen
         $netcode = trim(strip_tags($s));
         //**/ ddebug ('netcode=', $netcode, __LINE__, __FILE__);
         $this->tagdivision($s, $p);
         //$s = $this->get_symbol ($p); // in $s steht der übergebene Text
         //**/ ddebug ('s=', $s, __LINE__, __FILE__);
         $text = trim(strip_tags($s));
         //**/ ddebug ('text=', $text, __LINE__, __FILE__);
         $this->tagdivision($s, $p);
         //$s = $this->get_symbol ($p); // in $s steht der übergebene Tooltip
         //**/ ddebug ('s=', $s, __LINE__, __FILE__);
         $tooltip = trim(strip_tags($s));
         //**/ ddebug ('text=', $text, __LINE__, __FILE__);
         $tabHref = iRequest::href('d,s=' . $netcode);
         $tabQuery = parse_url($tabHref, PHP_URL_QUERY);
         $tabHref = 'index.php' . (($tabQuery !== null && $tabQuery !== '') ? '?' . $tabQuery : '');
         $this->write('<li><a href="' . $tabHref . '" title="' . $tooltip . '"' . (($this->iRequest->get_Seite() == $netcode) ? ' style="background-color:#fff;border:1px solid #eee;">' : ' style="background-color:#eee;border:1px solid #eee;">') . $text . '</a></li>');
         $s = $this->get_symbol($p);
         //**/ ddebug ('s=', $s, __LINE__, __FILE__);
      }
      $this->write('</ul></nav><!--div class="float-ende"></div--></div>');
      if ($s != TCML_END) {
         $this->error($s, __LINE__);
      }
      $s = $this->get_symbol($p);
   } //function menu_reiter



   function net_code(&$s, &$p)
   {
      global $_script;
      $this->tagdivision($s, $p); // in $s steht der übergebene NET-CODE
      // HTML-Tags gnadenlos entfernen
      //echo $s.' '.__LINE__.'<br>';
      $docref = strip_tags($s);
      //11.05 $_line = __LINE__; $_file = __FILE__; include_once ($_script['doc_identifier']);
      //11.05 $doc = new doc_identifier ($docref);
      //11.05 $doctype = $doc->type ();
      //***/debug ($doctype, '$doctype', '', 'net-code', __LINE__, __FILE__, true);
      //***/debug ($docref, '$docref', '', 'net-code', __LINE__, __FILE__, true);
      /*
   if ($doctype === 'a') { // Dokument eines Autors
      // *** /debug ($doctype, '$doctype', '', 'net-code', __LINE__, __FILE__, true);
      $pos = strpos ($docref, '.');
      if ($pos > 0) $docref = substr ($docref, 0, $pos);
      $autor = strval (intval ($docref));
      $filepattern = _AUTHOR_PATH . $autor . '/' . $docref . '.*';
      // *** /debug ($filepattern, '$filepattern', '', 'net_code', __LINE__, __FILE__, true);
      // Laden der files
      $files = glob ($filepattern);
      $file = array_pop ($files);
      // *** /debug ($file, '$file', '', 'net_code', __LINE__, __FILE__, true);
      if (! file_exists ($file)) {
         $this->write ('<img src="' . _SYSLIB_PATH
            . 'warnung.gif" title="Das Verweisziel existiert nicht!" '
            . 'border="0"> ');
      }// if
      else {
         $path_parts = pathinfo ($file);
         $docext = strtolower ($path_parts["extension"]);
         // *** /debug ($docext, '$docext', '', 'vcml_engine::net_code ()', __LINE__, __FILE__, true);
         $this->write ('<a class="' . $docext . '" href="' . iRequest::href ('docref=' . $docref) . '">');
            //. stdRequest ('device') . '&docref=' . $docref . '">');
      }
   }
   else if ($doctype === 'i') { // Internetlink
      $this->write ('<a class = "internet" href="'
         . iRequest::href ('d,netcode=' . $docref) . '" target="_blank">');
   }
   else if ($doctype === 's') {
   */
      // NET-Buch-Seite
      $this->write('<a class="net-schulbuch" href="' . iRequest::href('s=' . $docref) . '">');
      /*
   }
   else { // unbekannt
      $this->write ('<img src="' . _SYSLIB_PATH
         . 'warnung.gif" title="Das Verweisziel existiert nicht!" '
         . 'border="0"> ');
   }
   */
      $this->tagdivision($s, $p);
      while (($s != TCML_END) and ($p < $this->inplen)) {
         $this->parse($s, $p);
      }
      if ($s != TCML_END) {
         $this->error($s, __LINE__);
      }
      $s = $this->get_symbol($p);
      $this->write('</a>');
   } //function net-code

   function net_codet(&$s, &$p)
   {
      global $_script;
      $this->tagdivision($s, $p); // in $s steht der übergebene NET-CODE
      // HTML-Tags gnadenlos entfernen
      //echo $s.' '.__LINE__.'<br>';
      $docref = strip_tags($s);
      //11.05 $_line = __LINE__; $_file = __FILE__;
      //11.05 include_once ($_script['doc_identifier']);
      //11.05 $doc = new doc_identifier ($docref);
      //11.05 $doctype = $doc->type ();
      $this->tagdivision($s, $p);
      $text = $s;
      //***/debug ($doctype, '$doctype', '', 'net-code', __LINE__, __FILE__, true);
      //***/debug ($docref, '$docref', '', 'net-code', __LINE__, __FILE__, true);
      $this->write('<a class="net-schulbuch" href="' . iRequest::href('s=' . $docref) . '" target="_blank">' . $text . '</a>');
      $this->tagend($s, $p);
   } //function net-code

   function write($out)
   {
      //*****/ echo '<br /><font color="red">' . htmlentities ($out) . '</font><br />';
      $this->output .= $out;
   } // function write


   function error($s, $line = 0)
   {
      $this->output .= ' <font color="red">' . $line . ' <b>'
         . htmlentities($s) . '</b></font> ';
   } //error

   function unsupported_shortcut(&$s, &$p, $shortcut)
   {
      while (($s != TCML_END) and ($p < $this->inplen)) {
         $s = $this->get_symbol($p);
      }
      $this->error('Der Shortcut "' . $shortcut . '" ist in dieser Engine-Version nicht implementiert.', __LINE__);
      if ($s == TCML_END) {
         $s = $this->get_symbol($p);
      }
   }

   public static function modalLink($modalId, $text, $class = '', $attributes = '')
   {
      $classAttribute = ($class == '') ? '' : ' class="' . $class . '"';
      $attributes = trim($attributes);
      $attributes = ($attributes == '') ? '' : ' ' . $attributes;
      return '<a href="#' . $modalId . '"' . $classAttribute . ' data-bs-toggle="modal"' . $attributes . '>' . $text . '</a>';
   }

   public static function modalHtml($modalId, $title, $body, $footer = '', $dialogAttributes = '', $contentAttributes = '', $bodyAttributes = '', $titleTag = 'h3', $before = '', $closeOnClick = '', $titleAttributes = 'id="meinModalLabel"')
   {
      $dialogAttributes = trim($dialogAttributes);
      $dialogAttributes = ($dialogAttributes == '') ? '' : ' ' . $dialogAttributes;
      $contentAttributes = trim($contentAttributes);
      $contentAttributes = ($contentAttributes == '') ? '' : ' ' . $contentAttributes;
      $bodyAttributes = trim($bodyAttributes);
      $bodyAttributes = ($bodyAttributes == '') ? '' : ' ' . $bodyAttributes;
      $titleAttributes = trim($titleAttributes);
      $titleAttributes = ($titleAttributes == '') ? '' : ' ' . $titleAttributes;
      $closeOnClick = trim($closeOnClick);
      $closeOnClick = ($closeOnClick == '') ? '' : ' onclick="' . $closeOnClick . '"';
      $footerHtml = ($footer == '') ? '' : '
       <div class="modal-footer">' . $footer . '</div>';

      return $before . '
       <div class="modal fade" id="' . $modalId . '" style="display:none;" role="dialog">
       <div class="modal-dialog"' . $dialogAttributes . '>
       <div class="modal-content"' . $contentAttributes . '>
       <div class="modal-header">
       <button type="button" class="close" data-bs-dismiss="modal" aria-label="Schließen"' . $closeOnClick . '><span aria-hidden="true">&times;</span></button>
       <' . $titleTag . ' class="modal-title"' . $titleAttributes . '>' . $title . '</' . $titleTag . '>
       </div>
       <div class="modal-body"' . $bodyAttributes . '>' . $body . '</div>' . $footerHtml . '
       </div>
       </div>
       </div>
       ';
   }

   function dragdrop(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $dateiName = $s;
      $html0 = '<script src="https://syslib.net-schulbuch.de/js/DragDropTouch.js "      style="text/javascript "></script>
   <script src="https://syslib.net-schulbuch.de/js/html2canvas.min.js "></script>
   <script src="https://syslib.net-schulbuch.de/js/dnd2.js " style="text/javascript "></script>';

      $this->tagdivision($s, $p);
      $zuordnungen = intval($s);
      $this->tagdivision($s, $p);
      $anzahlschieben = intval($s);
      $anzahl = $zuordnungen + $anzahlschieben;
      $this->tagdivision($s, $p);
      $breite = $s;

      $zellbreite = intval($breite) + 8;
      $this->tagdivision($s, $p);
      $hoehe = $s;
      $zellhoehe = intval($hoehe) + 8;
      $dropbreite = $zellbreite - 2;
      $drophoehe = $zellhoehe - 2;
      $basisbreite = ($dropbreite + 2) * $anzahlschieben;
      $basishoehe = $drophoehe + 4;
      $bilder = array();
      for ($i = 0; $i < $anzahl; $i++) {
         $bilder[] = $dateiName . $i;
      }
      ;
      $pfad = $this->iRequest->get_SeitePathPublic();
      $html1 = '<style type="text/css" title="text/css">
      @import url("https://syslib.net-schulbuch.de/css/dnd.css");
      .droptarget' . $dateiName . '{
          background-color: yellow;
      }
      #basis' . $dateiName . ' {
         text-align: left;
         vertical-align: middle;
         display: table-cell;
         padding-top:2px;
         background-color: red;
         cursor: move;
     }   
   .imagediv' . $dateiName . ' {
      display: inline-block;
      vertical-align: middle;
      background-color: #fff;
      margin: 0 auto 0 auto;
      border: 1px solid #aaa;
      padding:1px;
   }

/* Dragging Zustand */
.imagediv' . $dateiName . ' :active {
    cursor: grabbing; /* Grabbing-Cursor */
}

/* Visuelles Feedback für Drop-Zone */
.hovered {
    border: 2px dashed #007bff;
    background-color: #d6e9ff;
}
     </style>';
      $i = 0;
      $html1 = $html1 . '<div id = "alles' . $dateiName . '"><div id = "' . $dateiName . '" style="display:inline-block;width:95%;">';
      while ($i < $zuordnungen) {
         $j = $i + 1;

         $html1 = $html1 . '
              <table  style="display:inline-block;">
                <tbody>
                   <tr align="center" valign="middle">
                     <td style="width:' . $zellbreite . 'px; height:' . $zellhoehe . 'px; border:1px solid #333;">
                         <div class="bildlinks' . $dateiName . '" style = "width:' . $dropbreite . 'px; height:' . $drophoehe . 'px;""><img class="bild" style = "width: ' . $breite . 'px; height: ' . $hoehe . 'px;"  src="' . $pfad . $bilder[$i] . '.png" draggable="false" /></div>
                      </td>
                      <td style="width:' . $zellbreite . 'px; height:' . $zellhoehe . 'px; border:1px solid #333;">
                         <div class="droptarget' . $dateiName . '" id = "' . $bilder[$i] . '" style = "width:' . $dropbreite . 'px; height:' . $drophoehe . 'px;" draggable="false"></div>
                      </td>
                     </tr>
                 </tbody>
               </table>';
         $i += 1;
      }
      $html1 = $html1 . '</div>';
      $breiteAblage = $basisbreite + 4;
      $hoeheAblage = $basishoehe + 4;
      //'.$basisbreite.'px; height: '.$basishoehe.'px;
      $id = "alles" . $dateiName;
      $html1 = $html1 . '<div id="basis' . $dateiName . '" style = "width:95%;background-color:silver;padding:0.2em;min-height:' . $basishoehe . ';margin-top:5px" draggable="false">';
      $div = '';
      $i = $zuordnungen;
      $reihenfolge = [];
      for ($z = 0; $z < $anzahlschieben; $z++) {
         $reihenfolge[] = $zuordnungen + $z;
      }
      //mischen
      for ($z = 0; $z < 10; $z++) {
         $index1 = mt_rand(0, $anzahlschieben - 1);
         $index2 = mt_rand(0, $anzahlschieben - 1);
         $tausch = $reihenfolge[$index1];
         $reihenfolge[$index1] = $reihenfolge[$index2];
         $reihenfolge[$index2] = $tausch;
      }

      while ($i < $anzahl) {
         $div = $div . '<img class = "imagediv' . $dateiName . '" style = "width: ' . $breite . 'px; height:' . $hoehe . 'px;border:1px solid #aaa;" id = "' . $bilder[$reihenfolge[$i - $zuordnungen]] . '" draggable="true" src="' . $pfad . $bilder[$reihenfolge[$i - $zuordnungen]] . '.png"/>';
         $i++;
      }
      $html1 = $html1 . $div . '</div></div>';
      $html1 = $html1 . '<div style = "display:block;margin-top:5px;"> <button type="button" class="btn btn-warning hidden-print" onClick="pruefen()">prüfen</button>
   <button type="button" class="btn btn-danger hidden-print" onClick="reset()">löschen</button>
   </div>
   <script> ladeBilder("' . $dateiName . '");</script>';
      $this->write($html0 . $html1);
      $this->tagend($s, $p);
   } //dragdrop


   function dragdrop2(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $dateiName = $s;
      $html0 = '<script src="https://syslib.net-schulbuch.de/js/DragDropTouch.js "  style="text/javascript "></script>
   <script src="https://syslib.net-schulbuch.de/js/html2canvas.min.js "></script>
   <script src="https://syslib.net-schulbuch.de/js/dnd4.js " style="text/javascript "></script>
   <script>
      if (typeof(obj)=="undefined") {
         var obj = {};
      }
      obj["' . $dateiName . '"] = new DDO("' . $dateiName . '");
      obj["' . $dateiName . '"].ladeBilder();</script>';
      $this->tagdivision($s, $p);
      $zuordnungen = intval($s);
      $this->tagdivision($s, $p);
      $anzahlschieben = intval($s);
      $anzahl = $zuordnungen + $anzahlschieben;
      $this->tagdivision($s, $p);
      $breite = $s;

      $zellbreite = intval($breite) + 8;
      $this->tagdivision($s, $p);
      $hoehe = $s;
      $zellhoehe = intval($hoehe) + 8;
      $dropbreite = $zellbreite - 2;
      $drophoehe = $zellhoehe - 2;
      $basisbreite = ($dropbreite + 2) * $anzahlschieben;
      $basishoehe = $drophoehe + 4;
      $bilder = array();
      for ($i = 0; $i < $anzahl; $i++) {
         $bilder[] = $dateiName . $i;
      }
      ;
      $pfad = $this->iRequest->get_SeitePathPublic();
      $html1 = '<style type="text/css" title="text/css">
      @import url("https://syslib.net-schulbuch.de/css/dnd.css");
     </style>';
      $i = 0;
      $html1 = $html1 . '<div id = "' . $dateiName . '" style="display:inline-block;width:95%;">';
      while ($i < $zuordnungen) {
         $html1 = $html1 . '

                   <table style="display:inline-block;"><tbody> <tr align="center" valign="middle">
                     <td style="width:' . $zellbreite . 'px; height:' . $zellhoehe . 'px; border:1px solid #333;">
                         <div class="bildlinks' . $dateiName . '" style = "width:' . $dropbreite . 'px; height:' . $drophoehe . 'px;margin-top:4px;""><img class="bild" style = "width: ' . $breite . 'px; height: ' . $hoehe . 'px;"  src="' . $pfad . $bilder[$i] . '.png" draggable="false" /></div>
                      </td>
                      <td style="width:' . $zellbreite . 'px; height:' . $zellhoehe . 'px; border:1px solid #333;">
                         <div class="droptarget' . $dateiName . '" id = "' . $bilder[$i] . '" style = "width:' . $dropbreite . 'px; height:' . $drophoehe . 'px;text-align:center;" draggable="false"></div>
                      </td>
                      </tr></body></table>';

         $i += 1;
      }
      ;
      $html1 = $html1 . '</div>';
      $breiteAblage = $basisbreite + 4;
      $hoeheAblage = $basishoehe + 4;
      //'.$basisbreite.'px; height: '.$basishoehe.'px;
      $html1 = $html1 . '<div id="basis' . $dateiName . '" style = "width:95%;min-height:' . $hoeheAblage . 'px;margin-bottom:3px;background-color:silver;padding:0.2em;" draggable=false>';
      $div = '';
      $i = $zuordnungen;
      $reihenfolge = [];
      for ($z = 0; $z < $anzahlschieben; $z++) {
         $reihenfolge[] = $zuordnungen + $z;
      }
      //mischen
      for ($z = 0; $z < 10; $z++) {
         $index1 = mt_rand(0, $anzahlschieben - 1);
         $index2 = mt_rand(0, $anzahlschieben - 1);
         $tausch = $reihenfolge[$index1];
         $reihenfolge[$index1] = $reihenfolge[$index2];
         $reihenfolge[$index2] = $tausch;
      }

      while ($i < $anzahl) {
         $div = $div . '<img class = "imagediv' . $dateiName . '" style = "width: ' . $breite . 'px; height:' . $hoehe . 'px;border:1px solid #aaa;margin-top:4px;" id = "' . $bilder[$reihenfolge[$i - $zuordnungen]] . '" draggable="true" src="' . $pfad . $bilder[$reihenfolge[$i - $zuordnungen]] . '.png"/>';
         $i++;
      }
      $html1 = $html1 . $div . '</div>';
      $html1 = $html1 . '<div style = "display:block;"> <button type="button" class="btn btn-warning hidden-print"  onClick=obj["' . $dateiName . '"].pruefen()>prüfen</button>
   <button type="button" class="btn btn-danger hidden-print"  onClick=obj["' . $dateiName . '"].reset()>löschen</button>
   </div>';


      $this->write($html1 . $html0);
      $this->tagend($s, $p);
   } //dragdrop4




   function dragline(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $name = $s;
      $this->tagdivision($s, $p);
      $anzahl = $s;
      $anz = intval($anzahl);
      $zeilen = [];

      for ($i = 0; $i < $anz; $i++) {
         $this->tagdivision($s, $p);
         $zeilen[] = $s;
      }

      $tauscharray = [];

      for ($i = 0; $i < $anz; $i++) {

         $textzeile = new Textzeile($i, $zeilen[$i]);
         $tauscharray[] = $textzeile;
      }

      for ($i = 0; $i < 30; $i++) {
         $zufall = mt_rand(0, $anz - 1);
         $dummy = $tauscharray[0];
         $tauscharray[0] = $tauscharray[$zufall];
         $tauscharray[$zufall] = $dummy;
      }
      $html1 = '';

      $pfad = $this->iRequest->get_SeitePathPublic();
      $html1 = '<style type="text/css" title="text/css">
      @import url("https://syslib.net-schulbuch.de/css/dndline.css");
     </style><div class ="divhintergrund" id = "' . $name . '">';
      for ($i = 0; $i < $anz; $i++) {
         $html1 = $html1 . '<p class = "programmzeile" id = "' . $name . $tauscharray[$i]->zeilennummer . '" draggable = true>' . $tauscharray[$i]->text . '</p>';
      }
      $html1 = $html1 . '</div>';
      $html1 = $html1 .
         '<div> <button type="button" class="btn btn-warning" onClick = pruefenline(' . $name . ')>prüfen</button>
    <button type="button" class="btn btn-danger" onClick = loeschenline(' . $name . ')>löschen</button>' .
         //<button type="button" class="btn btn-success" onClick = loesenline('.$name.')>lösen</button>
         '</div>
   <script src="https://syslib.net-schulbuch.de/js/DragDropTouch.js " style="text/javascript "></script>
   <script src="https://syslib.net-schulbuch.de/js/dndline.js " style="text/javascript "></script>';
      $this->write($html1);
      $this->tagend($s, $p);
   } //dragline


   function dragimg(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $name = $s;
      $this->tagdivision($s, $p);
      $anzahl = $s;
      $anz = intval($anzahl);
      $this->tagdivision($s, $p);
      $url = $s;
      $this->tagdivision($s, $p);
      if ($s == 1) {
         $klasse = "bildzeile";
      } else {
         $klasse = "bildzeileinline";
      }

      $tauscharray = [];

      for ($i = 0; $i < $anz; $i++) {
         $bildzeile = new Bildzeile($i, $url . $i);
         $tauscharray[] = $bildzeile;
      }

      for ($i = 0; $i < 30; $i++) {
         $zufall = mt_rand(0, $anz - 1);
         $dummy = $tauscharray[0];
         $tauscharray[0] = $tauscharray[$zufall];
         $tauscharray[$zufall] = $dummy;
      }
      $html1 = '';

      $html1 = '<style type="text/css" title="text/css">
      @import url("https://syslib.net-schulbuch.de/css/dndline.css");
     </style><div class ="bildhintergrund" id = "' . $name . '">';
      for ($i = 0; $i < $anz; $i++) {
         $html1 = $html1 . '<div class = "' . $klasse . '" id = "' . $name . $i . '" draggable = "true"><img id = "' . $tauscharray[$i]->url . '" draggable="false" src ="' . $tauscharray[$i]->url . '"></div>';
      }
      $html1 = $html1 . '</div>';
      $html1 = $html1 .
         '<div> <button type="button" class="btn btn-warning" onClick = "pruefenImg(document.getElementById(\'' . $name . '\'))">prüfen</button>
    <button type="button" class="btn btn-danger" onClick = "loeschenImg(document.getElementById(\'' . $name . '\'))">löschen</button>' .
         //<button type="button" class="btn btn-success" onClick = loesenline('.$name.')>lösen</button>
         '</div>
   <script src="https://syslib.net-schulbuch.de/js/DragDropTouch.js " style="text/javascript "></script>
   <script src="https://syslib.net-schulbuch.de/js/dndImage.js " style="text/javascript "></script>';
      $this->write($html1);
      $this->tagend($s, $p);
   } //dragline

   function checkboxes(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $id = $s;
      $this->tagdivision($s, $p);
      $frage = $s;
      $this->tagdivision($s, $p);
      $checks = explode("|", $s);
      $jsArray = json_encode($checks);
      $script1 = ' <script src="https://syslib.net-schulbuch.de/js/checkb.js"></script>';
      $script2 = '<script> ko.applyBindings(new CheckViewModel(' . $jsArray . ',"' . $id . '"), document.getElementById("' . $id . '")) </script>';
      $html = '<div id = "' . $id . '">';
      $html = $html . '<div  class="gelb" style = "padding: 1em;"><p>' . $frage . '</p>';
      for ($i = 0; $i < count($checks); $i++) {
         $pos = strPos($checks[$i], "#");
         if ($pos === 0) {
            $checks[$i] = substr($checks[$i], 1, strlen($checks[$i]));
         }
         $html = $html . '<p><input type="checkbox" style = "margin-right:1em;" data-bind="checked:
                     $data.checklines()[' . $i . '].checked, click:colorblack"/>
                    <span data-bind="style: {color:$data.checklines()[' . $i . '].colour,
                   textDecoration:$data.checklines()[' . $i . '].textdeco}">' . $checks[$i] . '</span></p>';
      }
      $html = $html . '</div>';
      $html = $html . '<div id="pbt' . $id . '" style="visibility:hidden;">';
      $html = $html . '<button type = "button" class="btn btn-danger hidden-print" style="margin-right:3px;" data-bind="click:loeschen">löschen</button>';
      $html = $html . '<button  type = "button" class="btn btn-warning hidden-print" style="margin-right:3px;" data-bind="click:pruefen">prüfen</button></div></div>'; //  $html=$html.'<button type = "button" class="btn btn-success hidden-print" data-bind="click:loesen">lösen</button>
      $this->write($html . $script1 . $script2);
      $this->tagend($s, $p);
   }

   function checkboxes2(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $id = $s;
      $this->tagdivision($s, $p);
      $frage = $s;
      $this->tagdivision($s, $p);
      $checks = explode("|", $s);
      $jsArray = json_encode($checks);
      $script1 = '<script src="https://syslib.net-schulbuch.de/js/checkb.js"></script>';
      $script2 = '<script> ko.applyBindings(new CheckViewModel(' . $jsArray . ',"' . $id . '"), document.getElementById("' . $id . '")) </script>';
      $html = '<div id = "' . $id . '">';
      $html = $html . '<div  class="gelb" style = "padding: 1em;"><p>' . $frage . '</p>';
      for ($i = 0; $i < count($checks); $i++) {
         $pos = strPos($checks[$i], "#");
         if ($pos === 0) {
            $checks[$i] = substr($checks[$i], 1, strlen($checks[$i]));
         }
         $html = $html . '<label class="checkbox-inline"><input type="checkbox" value = ""
                    data-bind="checked: $data.checklines()[' . $i . '].checked, click:colorblack" />
                    <span data-bind="style: {color:$data.checklines()[' . $i . '].colour, textDecoration:$data.checklines()[' . $i . '].textdeco}">' . $checks[$i] . '</span></label>';
      }
      $html = $html . '</div>';
      $html = $html . '<div id="pbt' . $id . '" style="visibility:hidden;">';
      $html = $html . '<button type = "button" class="btn btn-danger hidden-print" style="margin-right:3px;" data-bind="click:loeschen">löschen</button>';
      $html = $html . '<button  type = "button" class="btn btn-warning hidden-print" style="margin-right:3px;" data-bind="click:pruefen">prüfen</button></div></div>';
      //  $html=$html.'<button type = "button" class="btn btn-success hidden-print" data-bind="click:loesen">lösen</button>
      $this->write($html . $script1 . $script2);
      $this->tagend($s, $p);
   }

   function checkboxes1(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $id = $s;
      $this->tagdivision($s, $p);
      $frage = $s;
      $this->tagdivision($s, $p);
      $checks = explode("@", $s);
      $jsArray = json_encode($checks);
      $script1 = ' <script src="https://syslib.net-schulbuch.de/js/checkb.js"></script>';
      $script2 = '<script> ko.applyBindings(new CheckViewModel(' . $jsArray . ',"' . $id . '"), document.getElementById("' . $id . '")) </script>';
      $html = '<div id = "' . $id . '">';
      $html = $html . '<div  class="gelb" style = "padding: 1em;"><p>' . $frage . '</p>';
      for ($i = 0; $i < count($checks); $i++) {
         $pos = strPos($checks[$i], "#");
         if ($pos === 0) {
            $checks[$i] = substr($checks[$i], 1, strlen($checks[$i]));
         }
         $html = $html . '<p><input type="checkbox" style = "margin-right:1em;" data-bind="checked: $data.checklines()[' . $i . '].checked, click:colorblack" /><span data-bind="style: {color:$data.checklines()[' . $i . '].colour, textDecoration:$data.checklines()[' . $i . '].textdeco}">' . $checks[$i] . '</span></p>';
      }
      $html = $html . '</div>';
      $html = $html . '<div id="pbt' . $id . '" style="visibility:hidden;">';
      $html = $html . '<button type = "button" class="btn btn-warning hidden-print" style="margin-right:3px;" data-bind="click:pruefen">prüfen</button>';
      $html = $html . '<button type = "button" class="btn btn-danger hidden-print" style="margin-right:3px;" data-bind="click:loeschen">löschen</button></div></div>';

      $this->write($html . $script1 . $script2);
      $this->tagend($s, $p);
   }

   function radiobuttons(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $id = $s;
      $this->tagdivision($s, $p);
      $frage = $s;
      $this->tagdivision($s, $p);
      $checks = explode("|", $s);
      $jsArray = json_encode($checks);
      $this->tagdivision($s, $p);
      $richtig = $s;
      $script1 = ' <script src="https://syslib.net-schulbuch.de/js/checkb.js"></script>';
      $script2 = '<script> ko.applyBindings(new RadioViewModel(' . $s . '), document.getElementById("' . $id . '")) </script>';
      $html = '<div id = "' . $id . '">';
      $html = $html . '<div  class="gelb" style = "padding: 1em;"><p>' . $frage . '</p>';
      for ($i = 0; $i < count($checks); $i++) {
         $html = $html . '<p><input type="radio" name = 1' . $id . ' value = ' . $i . ' style = "margin-right:1em;" data-bind="checked: $data.checked, click:colorblack" /><span data-bind="style: {color:colour, textDecoration:textdeco}">' . $checks[$i] . '</span></p>';
      }
      $html = $html . '</div>';
      $html = $html . '<button type = "button" class="btn btn-warning hidden-print" style="margin-right:3px;" data-bind="click:pruefen">prüfen</button>';
      $html = $html . '<button type = "button" class="btn btn-danger hidden-print" style="margin-right:3px;" data-bind="click:loeschen">löschen</button></div>';
      // $html=$html.'<button type = "button" class="btn btn-success hidden-print" data-bind="click:loesen">lösen</button>;
      $this->write($html . $script1 . $script2);
      $this->tagend($s, $p);
   }

   function search(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $suchwort = $s;
      $s = $this->get_symbol($p);
      if ($s != TCML_END) {
         $s = $this->get_symbol($p);
         $s1 = $this->get_symbol($p);
         $s = $this->get_symbol($p);
         $s2 = $this->get_symbol($p);
      }
      $code = $GLOBALS['_iRequest']->value('s');
      $this->suchdatenbank->save($suchwort, $code, 0, '');
      $this->write($suchwort);
      $s = $this->get_symbol($p);
   }


   function geogebra(&$s, &$p)
   {
      /*
        [@[geogebra::!!Dateinname ohne Pfad!!::!!Breite!!::!!Höhe!!]@]

        Unabhängig davon wie die Geogebra-Datei vor dem Speichern aussah, wird nur das Grafik-Fenster angezeigt. Folgende Punkte sind zu beachten:

        Die Geogebra-Datei (ggb) muss hochgeladen sein.
        Bitte grundsätzlich Geogebra 6 verwenden.
        Die Anwendung wird in der angegebenen Breite und Höhe angezeigt. Wenn die gespeicherte Anwendung größer ist, wird nur ein Ausschnitt angezeigt, daher sollte man immer darauf achten, dass Geogebra-Fenster bei Entwicklung der Anwendung nicht zu groß ist (empfohlen 800x600 Pixel).
        Das Grafikfenster kann nicht vergrößert oder verkleinert werden und der Inhalt kann auch nicht verschoben werden.
        Alle statischen Objekte sollten fixiert werden, damit der Benutzer sie nicht verschieben kann.
        Die Geogebra-App kann in einen Tipp intergiert werden.
        Weil die Ladezeiten relativ lang sind, sollten nicht mehr als zwei bis drei Apps auf einer Website eingebunden werden.

        Beispiel Tangentensteigung in die Seite ahba_tangproblehr integriert.
    */
      $this->tagdivision($s, $p);
      $dateiname = $s;
      $appid = substr($dateiname, 0, strpos($dateiname, "."));
      $varname = "ggb" . $appid;
      $this->tagdivision($s, $p);
      $width = $s;
      $this->tagdivision($s, $p);
      $height = $s;
      $filename = $this->iRequest->get_SeitePathPublic() . $dateiname;
      /* $geofile = filesystem::LoadFile($filename);
    $ggbBase64="";
    if($geofile) {
       $ggbBase64= base64_encode($geofile);
    }*/
      $this->write('<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><img style="float: right; position: relative; top: -10px;" src="https://syslib.net-schulbuch.de/geogebra-logo.png" alt="" width="250" height="38" />
        &nbsp; <a class="ggb" title="Download" href="' . $filename . '" target="_blank">' . basename($filename) . '</a>&nbsp;
        </h3>
    </div>
    <div class="panel-body" style="padding:0px 0px 46px 0px;">');
      $this->write('<style>
               .applet_dimensions {
                 width:' . $width . 'px;
                 height:' . $height . 'px;
                }
              </style>
      <div id="' . $appid . '" class="applet_dimensions"></div>');
      $this->write('<script>
         var parameters10 = {"appName":"suite","id":"' . $appid . '","width":' . $width . ',"height":' . $height . ',"filename":"' . $filename . '","showToolBar":false,"borderColor":"#000000","showMenuBar":false,"showAlgebraInput":false,"showResetIcon":true,"enableLabelDrags":false,"enableShiftDragZoom":false,"enableRightClick":false,"capturingThreshold":null,"showToolBarHelp":false,"errorinjectsActive":false,"useBrowserForJS":false,"scaleContainerClass":"applet_dimensions","enableCAS":true,"language":"de","textmode":true,"preventFocus":false};
         var views = {"is3D": 0,"AV": 1,"SV": 0,"CV": 0,"EV2": 1,"CP": 0,"PC": 0,"DA": 0,"FI": 0,"macro": 0};
         var ' . $varname . ' = new GGBApplet(parameters10, "6.0","' . $appid . '",views);

        window.addEventListener("load",function() {
          ' . $varname . '.inject("' . $appid . '");
      });</script>');
      $this->write('</div></div>');
      $this->tagend($s, $p);
   }   // function geogebra

   function geogebratool(&$s, &$p)
   {
      /*
       [@[geogebra::!!Dateinname ohne Pfad!!::!!Breite!!::!!Höhe!!]@]

       Unabhängig davon wie die Geogebra-Datei vor dem Speichern aussah, wird nur das Grafik-Fenster angezeigt. Folgende Punkte sind zu beachten:

       Die Geogebra-Datei (ggb) muss hochgeladen sein.
       Bitte grundsätzlich Geogebra 6 verwenden.
       Die Anwendung wird in der angegebenen Breite und Höhe angezeigt. Wenn die gespeicherte Anwendung größer ist, wird nur ein Ausschnitt angezeigt, daher sollte man immer darauf achten, dass Geogebra-Fenster bei Entwicklung der Anwendung nicht zu groß ist (empfohlen 800x600 Pixel).
       Das Grafikfenster kann nicht vergrößert oder verkleinert werden und der Inhalt kann auch nicht verschoben werden.
       Alle statischen Objekte sollten fixiert werden, damit der Benutzer sie nicht verschieben kann.
       Die Geogebra-App kann in einen Tipp intergiert werden.
       Weil die Ladezeiten relativ lang sind, sollten nicht mehr als zwei bis drei Apps auf einer Website eingebunden werden.

       Beispiel Tangentensteigung in die Seite ahba_tangproblehr integriert.
   */
      $this->tagdivision($s, $p);
      $dateiname = $s;
      $appid = substr($dateiname, 0, strpos($dateiname, "."));
      $varname = "ggb" . $appid;
      $this->tagdivision($s, $p);
      $width = $s;
      $this->tagdivision($s, $p);
      $height = $s;
      $filename = $this->iRequest->get_SeitePathPublic() . $dateiname;
      /* $geofile = filesystem::LoadFile($filename);
   $ggbBase64="";
   if($geofile) {
      $ggbBase64= base64_encode($geofile);
   }*/
      $this->write('
    <div class="panel panel-default">
   <div class="panel-heading">
       <h3 class="panel-title"><img style="float: right; position: relative; top: -10px;" src="https://syslib.net-schulbuch.de/geogebra-logo.png" alt="" width="250" height="38" />
       &nbsp; <a class="ggb" title="Download" href="' . $filename . '" target="_blank">' . basename($filename) . '</a>&nbsp;
       </h3>
   </div>
   <div class="panel-body" style="padding:0px 0px 46px 0px;">');
      $this->write('<style>
              .applet_dimensions {
                width:' . $width . 'px;
                height:' . $height . 'px;
               }
             </style>
     <div id="' . $appid . '" class="applet_dimensions"></div>');
      $this->write('<script>
        var parameters11 = {"width":' . $width . ',"height":' . $height . ',"filename":"' . $filename . '","showToolBar":true,"borderColor":"#000000","showMenuBar":false,"showAlgebraInput":false,"showResetIcon":true,"enableLabelDrags":false,"enableShiftDragZoom":false,"enableRightClick":false,"capturingThreshold":null,"showToolBarHelp":false,"errorDialogsActive":false,"useBrowserForJS":false,"scaleContainerClass":"applet_dimensions","enableCAS":true,"language":"de"};
        var views = {"is3D": 0,"AV": 0,"SV": 0,"CV": 0,"EV2": 1,"CP": 0,"PC": 0,"DA": 0,"FI": 0,"macro": 0};
        var ' . $varname . ' = new GGBApplet(parameters11, "5.0",views);

       window.addEventListener("load",function() {
         ' . $varname . '.inject("' . $appid . '");
     });</script>');
      $this->write('</div></div>');
      $this->tagend($s, $p);
   }   // function geogebra

   /*function geogebramp(&$s, &$p) {
   /*
      [@[geogebra::!!Dateinname ohne Pfad!!::!!Breite!!::!!Höhe!!]@]

     Unabhängig davon wie die Geogebra-Datei vor dem Speichern aussah, wird nur das Grafik-Fenster angezeigt. Folgende Punkte sind zu beachten:

     Die Geogebra-Datei (ggb) muss hochgeladen sein.
     Bitte grundsätzlich Geogebra 6 verwenden.
     Die Anwendung wird in der angegebenen Breite und Höhe angezeigt. Wenn die gespeicherte Anwendung größer ist, wird nur ein Ausschnitt angezeigt, daher sollte man immer darauf achten, dass Geogebra-Fenster bei Entwicklung der Anwendung nicht zu groß ist (empfohlen 800x600 Pixel).
     Das Grafikfenster kann nicht vergrößert oder verkleinert werden und der Inhalt kann auch nicht verschoben werden.
     Alle statischen Objekte sollten fixiert werden, damit der Benutzer sie nicht verschieben kann.
     Die Geogebra-App kann in einen Tipp intergiert werden.
     Weil die Ladezeiten relativ lang sind, sollten nicht mehr als zwei bis drei Apps auf einer Website eingebunden werden.

     Beispiel Tangentensteigung in die Seite ahba_tangproblehr integriert.

   $this->tagdivision($s,$p);
   $pfad = $s;
   $this->tagdivision($s, $p);
   $dateiname = $s;
   $appid = substr($dateiname,0,strpos($dateiname,"."));
   $varname ="ggb".$appid;
   $this->tagdivision($s, $p);
   $width = $s;
   $this->tagdivision($s, $p);
   $height = $s;
   $filename =  $pfad.$dateiname;
   /* $geofile = filesystem::LoadFile($filename);
   $ggbBase64="";
   if($geofile) {
      $ggbBase64= base64_encode($geofile);
   }
   $this->write (' <script src="https://www.geogebra.org/apps/deployggb.js"></script>');
   $this->write('<style>
              .applet_dimensions {
                width:'.$width.'px;
                height:'.$height.'px;
               }
             </style>
     <div id="'.$appid.'" class="applet_dimensions"></div>');
   $this->write('<script>
        var parameters12 = {"width":'.$width.',"height":'.$height.',"filename":"'.$filename.'","showToolBar":true,"borderColor":"#000000","showMenuBar":false,"showAlgebraInput":false,"showResetIcon":true,"enableLabelDrags":false,"enableShiftDragZoom":false,"enableRightClick":false,"capturingThreshold":null,"showToolBarHelp":false,"errorDialogsActive":false,"useBrowserForJS":false,"scaleContainerClass":"applet_dimensions","enableCAS":true,"language":"de"};
        var views = {"is3D": 0,"AV": 0,"SV": 0,"CV": 0,"EV2": 1,"CP": 0,"PC": 0,"DA": 0,"FI": 0,"macro": 0};
        var '.$varname.' = new GGBApplet(parameters12, "6.0","'.$appid.'");

       window.addEventListener("load",function() {
         '.$varname.'.inject("'.$appid.'");
     });</script>');
   $this->tagend($s, $p);
}   // function geogebra*/

   function geogebra2d(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $ggbdateiname = $s;
      $appid = substr($ggbdateiname, 0, strpos($ggbdateiname, "."));
      $varname = "ggb" . $appid;
      $this->tagdivision($s, $p);
      $width = $s;
      $this->tagdivision($s, $p);
      $height = $s;
      $minheight = $height + 40;
      $this->tagdivision($s, $p);
      $perspective = "true";
      if (strcmp($s, "true") != 0) {
         $perspective = "false";
      }

      $filename = $this->iRequest->get_SeitePathPublic() . $ggbdateiname;

      $this->write('<div class="panel panel-default" id="geopanel">
    <div class="panel-heading">
        <h3 class="panel-title"><img style="float: right; position: relative; top: -10px;" src="https://syslib.net-schulbuch.de/geogebra-logo.png" alt="" width="250" height="38" />
        &nbsp; <a class="ggb" title="Download" href="' . $filename . 'b" target="_blank">' . basename($filename) . '</a>&nbsp;
        </h3>
    </div>
    <div class="panel-body" style="padding:0px 0px 0px 0px;height:' . $height . 'px;">');
      $this->write('<style>
                 .applet_dimensions {
                   width:' . $width . 'px;
                   height:' . $height . 'px;
                  }
                </style>
        <div id="' . $appid . '" class="applet_dimensions"></div>');
      $this->write('
      <script>
        var parameters22 = {
         "appName":"3d",
        "id":"' . $appid . '",
        "width":' . $width . ',
        "height":' . $height . ',
        "showMenuBar":false,
        "showAlgebraInput":false,
        "showToolBar":' . $perspective . ',
        "showToolBarHelp":false,
        "showResetIcon":true,
        "enableLabelDrags":false,
        "enableShiftDragZoom":false,
        "enableRightClick":false,
        "errorDialogsActive":false,
        "useBrowserForJS":false,
        "allowStyleBar":false,
        "preventFocus":false,
        "showZoomButtons":false,
        "capturingThreshold":3,
        "appletOnLoad":function(api){ },
        "showFullscreenButton":false,
        "scale":1,
        "disableAutoScale":false,
        "allowUpscale":false,
        "clickToLoad":false,
        "appName":"classic",
        "showSuggestionButtons":true,
        "buttonRounding":0.7,
        "buttonShadows":false,
        "language":"de",
        // use this instead of ggbBase64 to load a material from geogebra.org
        // "material_id":"RHYH3UQ8",
        // use this instead of ggbBase64 to load a .ggb file
        "filename":"' . $filename . '",
       "ggbBase64":"",
      };
       var views = {"is3D":0,"AV": 1,"SV": 0,"CV": 0,"EV2": 1,"CP": 0,"PC": 0,"DA": 0,"FI": 0,"PV": 0,"macro": 0};
      var applet1' . $appid . ' = new GGBApplet(parameters22, "6.0", views);

applet1' . $appid . '.setPreviewImage("data:image/gif;base64,R0lGODlhAQABAAAAADs=","https://www.geogebra.org/images/GeoGebra_loading.png","https://www.geogebra.org/images/applet_play.png");
window.addEventListener("load",function() {
      applet1' . $appid . '.inject("' . $appid . '");
});
</script></div></div>');
      $this->tagend($s, $p);
   } //geogebra2

   function geogebra3d(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $ggbdateiname = $s;
      $appid = substr($ggbdateiname, 0, strpos($ggbdateiname, "."));
      $varname = "ggb" . $appid;
      $this->tagdivision($s, $p);
      $width = $s;
      $this->tagdivision($s, $p);
      $height = $s;
      $divheight = $height + 30;
      $this->tagdivision($s, $p);
      $filename = $this->iRequest->get_SeitePathPublic() . $ggbdateiname;
      $this->write('<div class="panel panel-default">
  <div class="panel-heading">
      <h3 class="panel-title"><img style="float: right; position: relative; top: -10px;" src="https://syslib.net-schulbuch.de/geogebra-logo.png" alt="" width="250" height="38" />
    &nbsp; <a class="ggb" title="Download" href="' . $filename . 'b" target="_blank">' . basename($filename) . '</a>&nbsp;
    </h3>
  </div>
  <div class="panel-body" style="padding:0px 0px 0px 0px;height:' . $divheight . 'px;">');
      $this->write('<style>
               .applet_dimensions {
                 width:' . $width . 'px;
                 height:' . $height . 'px;
                }
              </style>
      <div id="' . $appid . '" class="applet_dimensions"></div>');
      $this->write('<script>
         var parameters20 = {"appName":"classic","id":"' . $appid . '","width":' . $width . ',"height":' . $height . ',"filename":"' . $filename . '","showToolBar":false,"borderColor":"#000000",
              "showMenuBar":false,"showAlgebraInput":false,"showResetIcon":true,"enableLabelDrags":true,"enableShiftDragZoom":true,
              "enableRightClick":false,"capturingThreshold":null,"showToolBarHelp":false,"errorinjectsActive":false,
              "useBrowserForJS":false,"scaleContainerClass":"applet_dimensions","enableCAS":true,"language":"de","textmode":true,"preventFocus":false};
         var views = {"is3D":1,"AV": 1,"SV": 0,"CV": 0,"EV2": 0,"CP": 0,"PC": 0,"DA": 0,"FI": 0,"PV": 0,"macro": 0};
         var ' . $varname . ' = new GGBApplet(parameters20, "6.0",views);
        window.addEventListener("load",function() {
          ' . $varname . '.inject("' . $appid . '");
      });</script>');
      $this->write('</div></div>');
      $this->tagend($s, $p);
   }




   function geogebratube(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $dateiname = $s;
      $appid = "m" . $dateiname;
      $varname = "ggb" . $appid;
      $this->tagdivision($s, $p);
      $width = $s;
      $this->tagdivision($s, $p);
      $height = $s;
      /* $geofile = filesystem::LoadFile($filename);
    $ggbBase64="";
    if($geofile) {
       $ggbBase64= base64_encode($geofile);
    }*/
      $this->write('<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><img style="float: right; position: relative; top: -10px;" src="https://syslib.net-schulbuch.de/geogebra-logo.png" alt="" width="250" height="38" />
        &nbsp;&nbsp;
        </h3>
    </div>
    <div class="panel-body" style="padding:0px;">');
      $this->write('<style>
               .applet_dimensions {
                 width:' . $width . 'px;
                 height:' . $height . 'px;
                }
              </style>
      <div class="table-responsive-sm"><table class="table"><tbody><tr><td>
      <div id="' . $appid . '" class="applet_dimensions"></div>
      </td></tr></tbody></table></div>');
      $this->write('<script>
         var parameters13 = {"width":' . $width . ',"height":' . $height . ',"material_id":"' . $dateiname . '","scaleContainerClass":"applet_dimensions"};
        var ' . $varname . ' = new GGBApplet(parameters13, "5.0");

        window.addEventListener("load",function() {
          ' . $varname . '.inject("' . $appid . '");
      });</script>');
      $this->write('</div></div>');
      $this->tagend($s, $p);
   }

   function notiz(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $zeilen = $s;
      $this->tagdivision($s, $p);
      $spalten = $s;
      $this->write('<h3>Notiz</h3>
   <div>
   <p><textarea class="copytextarea" rows = ' . $zeilen . ' cols= ' . $spalten . ' " >Notiz zur Seite: ' . $GLOBALS['_iRequest']->value('s') . '</textarea></p>
                <p><button class="js-textareacopybtn">Kopiere Notiz</button></p>
   </div>
<script>
var copyTextareaBtn = document.querySelector(".js-textareacopybtn");
copyTextareaBtn.addEventListener("click", function(event) {
  var copyTextarea = document.querySelector(".copytextarea");
  copyTextarea.select();
    var successful = document.execCommand("copy");
    var msg = successful ? "successful" : "unsuccessful";
    console.log("Copying text command was " + msg);
  });
  </script>');
      $this->tagend($s, $p);
   }


   private function weblink(&$s, &$p)
   {
      /*
        [@[weblink::httplink::Linktext::Vorschaubild::ErlaeuternderText]@]
        httplink: muss mit https:// oder https:// beginnen
        Linktext: zum Anklicken angezeigter Text
        Vorschaubild: muss hochgeladen sein; Dateiname mit Pfad angeben
        ErlaeuternderText: Enthält den Text links neben dem Bild
     */
      $this->tagdivision($s, $p);
      $httplink = $s;
      $this->tagdivision($s, $p);
      $linktext = $s;
      $this->tagdivision($s, $p);
      $bild = $s;
      $this->tagdivision($s, $p);
      //$erlaeuterung = strip_tags ($s);
      $erlaeuterung = $s;
      //$this->write('<script>$(document).ready(function(){ $("a").tooltip();})</script>');
      $this->write('<div class="panel panel-default">
    <div class="panel-body" style="border: 3px solid #eeeeee;">
        <a href="' . $httplink . '" target="_blank">
        <img style="float: right; margin-left: 5px; max-width: 200px; height=auto;" src="' . $bild . '" alt="Vorschau auf die Startseite"  />
        </a><p>');
      $this->write($erlaeuterung);
      $this->write('</p></div>');
      $this->write('<div class="panel-footer">
        <img style="float: left; margin-right: 10px; margin-left: 10px;" src="https://syslib.net-schulbuch.de/wwwt.png" alt="" width="31" height="30" />
        &nbsp;<span class="gg">
            <a href="' . $httplink . '" target="_blank">');
      $this->write($linktext);
      $this->write('</a></span>&nbsp;<br />
        &nbsp;&nbsp;<span class="k">(Quelle:&nbsp;' . $httplink . ')</span>
    </div></div>');

      $this->tagend($s, $p);
   } // function weblink




   function tooltip(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $begriff = $s;
      $name = str_replace(" ", "", $begriff);
      $this->tagdivision($s, $p);
      $tooltiptext = $s;
      $ts = 0;
      $this->write('<script>function is_touch_device1() {
   try {
      document.createEvent("TouchEvent");
      return true;
    } catch (e) {
      return false;
    }
 };
 var ts=is_touch_device1();</script>');

      $this->write('<script>
  if (ts==1) {
   document.write(\'<div class="modal" id="myModal' . $name . '" tabindex="-1" style="display:none;float:right;" role="dialog" aria-labelledby="meinModalLabel">\');
   document.write(\'<div class="modal-dialog" role="document">\');
   document.write(\'<div class="modal-content">\');
   document.write(\'<div class="modal-header">\');
   document.write(\' <button type="button" class="close" data-bs-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>\');
   document.write(\' <h4 class="modal-title" id="meinModalLabel">' . $begriff . '</h4>\');
   document.write(\'</div>\');
   document.write(\'<div class="modal-body">' . $tooltiptext . '</div>\');
   document.write(\'</div></div></div>\');
   document.write(\'<a href="#" data-bs-toggle="modal" data-bs-target="#myModal' . $name . '">' . $begriff . '</a>\');
 } else {
      $(document).ready(function(){
       });
       document.write(\'<a href="#" data-bs-toggle="tooltip" title="' . $tooltiptext . '" style="overflow:visible;">' . $begriff . '</a>\');
       }
</script>');
      $this->tagend($s, $p);
   } //tooltip


   function puw(&$s, &$p)
   {
      /* [@[puw::|Begriff|::|Tooltipptext|::"link" or "button"] ]@]
       * Diese Funktion hat in der Version 31 einen optionalen dritten Parameter bekommen,
       * der den Wert "link" (default) oder "button" haben kann.
       *
       */
      $this->tagdivision($s, $p);
      $begriff = $s;
      $this->tagdivision($s, $p);
      $tooltiptext = $s;
      $param = $this->get_symbol($p);
      $typ = 'link';
      if ($param == TCML_SEP) {
         $s = $this->get_symbol($p);
         if ($s == 'button') {
            $typ = 'button';
         }
         $this->tagend($s, $p);
      } else {
         $s = $this->get_symbol($p);
      }
      $modalnr = random_int(1000000, 9999999);   #$this->modalnr + 1;
      #$this->modalnr = $this->modalnr + 1;
      $modalId = 'myModal' . $modalnr;
      if ($typ == 'link') {
         $this->write(' ' . self::modalLink($modalId, $begriff) . ' ');
      } else {
         $this->write(' ' . self::modalLink($modalId, $begriff, 'btn btn-default btn-xs') . ' ');
         //<button class="btn btn-default btn-xs" type="submit"><img style="vertical-align: middle;" title="net-schulbuch.de" src="https://syslib.net-schulbuch.de/seite.gif" alt="" />&nbsp;a: Einf&uuml;hrungsphase</button>
      }
      $this->modal .= "\n" . self::modalHtml($modalId, $begriff, $tooltiptext);
   }   // function puw



   function glossar(&$s, &$p)
   {
      /* [@[glossar::|net-code|::|Begriff|[::|Tooltipptext|[::"link" or "button"]] ]@]
       * Diese Funktion hat in der Version 43 andere Parameter bekommen,
       * der den Wert "link" (default) oder "button" haben kann.
       *
       */
      $this->tagdivision($s, $p);
      $netcode = explode("_", $s);
      $this->tagdivision($s, $p);
      $begriff = $s;
      $tooltipp = '';
      $link = True;
      $s = $this->get_symbol($p);
      if ($s == TCML_SEP) { // Tooltipp holen
         $tooltipp = $this->get_symbol($p);
         $s = $this->get_symbol($p);
         if ($s == TCML_SEP) { // Link or button
            $s = $this->get_symbol($p);
            if ($s == 'button')
               $link = False;
         } else {
            if ($s != TCML_END) {
               $this->error($s, __LINE__);
            }
            $s = $this->get_symbol($p);
         }
      } else {
         if ($s != TCML_END) {
            $this->error($s, __LINE__);
         }
         $s = $this->get_symbol($p);
      }


      $modalnr = random_int(1000000, 9999999);   #$this->modalnr + 1;
      $gloss[] = new Glossar($modalnr, $netcode[0], $netcode[1], $begriff);
      $this->write($gloss[count($gloss) - 1]->gibModfensterLink());
      $this->write($gloss[count($gloss) - 1]->gibModalfenster());
   }   // function glossar

   function handschrift(&$s, &$p)
   {

      $this->tagdivision($s, $p);
      $aufgabe = $s;
      $this->tagdivision($s, $p);
      $name = $s;
      $id = $name;
      $lsgname = "losesung" . $name;
      $netCode = $GLOBALS['_iRequest']->value('s');

      $link1 = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.15.2/dist/katex.css" integrity="sha384-NFGicHNcq1l2DafLerXQeI3h3jJY3dCcDQF+29rtRBHW7P7ti+/XIRY7ALbJOaeh" crossorigin="anonymous">';
      $script1 = '<script defer src="https://cdn.jsdelivr.net/npm/katex@0.15.2/dist/katex.js" integrity="sha384-HELAAZU8xvHgfT/8z4Mhmu+E2z3oBrMEuywaMh/CEd5uTZIDSct7TEaX+S43+dOi" crossorigin="anonymous"></script>';
      $script2 = '<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script>';
      $script3 = '<script src="https://syslib.net-schulbuch.de/js/handschrift.js "></script>';
      $modalnr = random_int(1000000, 9999999);
      $modalId = 'puw' . $modalnr;
      $body = '<iframe id="handschrift' . $id . '" src="/handschrift/iink.html" style="border-width:0px; height:100%; width:98%;" name="inhalt"></iframe>';
      $footer = '<button class="btn btn-danger" type="button" data-bs-dismiss="modal" onclick="showInput(\'lsg' . $id . '\',\'' . $lsgname . '\',\'savebutton' . $id . '\',copyMath(\'' . $id . '\'))">schliessen</button>';
      $modal = "\n" . self::modalHtml($modalId, 'Aufgabe', $body, $footer, 'id="dialoghandschrift" style="width:90vw;height:90vh;"', 'style="width:90%; overflow-y: scroll;"', 'style="height:80vh;" id="mbody"', 'h3', '', '', 'id="glossarLabel' . $id . '"');
      $html = '<p id = "aufgabe' . $id . '">' . $aufgabe . '</p>
             ' . self::modalLink($modalId, 'Aufgabe bearbeiten', 'btn btn-success');
      $results = $this->textedatenbank->load(_USER_, $netCode, $name);

      $t = "";
      if (mysqli_num_rows($results) > 0) {
         foreach ($results as $result) {
            $t = $result["text"];
            $html .= '<div id="lsg' . $id . '" style="display:block;">' . $t . '</div>
                     <p><button id="savebutton' . $id . '" class="btn btn-warning"  onclick="saveHTMLText_db(\'' . _USER_ . '\',\'' . $netCode . '\',\'' . $name . '\',document.getElementById(\'lsg' . $id . '\').innerHTML)">
                     speichern</button></p>';
         }
      } else {
         $html .= '<div id="lsg' . $id . '" style="display:none;width:50%;">
                               <div id = "' . $lsgname . '" name="' . $lsgname . '" style = "background-color:#fff;border:0.5px solid black;padding:1em;margin-top:1em;width:50%;min-height:20px;"></div>
                               </div>
                               <p><button id="savebutton' . $id . '" class="btn btn-warning" style="display:none;" onclick="saveHTMLText_db(\'' . _USER_ . '\',\'' . $netCode . '\',\'' . $name . '\',document.getElementById(\'lsg' . $id . '\').innerHTML)">speichern</button></p>
                               ';
      }
      $html .= '<script>document.getElementById("glossarLabel' . $id . '").innerHTML=document.getElementById("aufgabe' . $id . '").innerHTML</script>';
      $this->write($link1 . $script3 . $modal . $html . $script1 . $script2);
      $this->tagend($s, $p);
   }   // function handschrift


   function iframe(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $datei = $s;
      $this->tagdivision($s, $p);
      $width = $s;
      $this->tagdivision($s, $p);
      $height = $s;
      $this->write('<iframe src="' . $datei . '" name="' . $datei . '" width="' . $width . '" height="' . $height . '" style="border-width:0px;"></iframe>');
      $this->tagend($s, $p);
   }

   function wtp(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $file = $this->iRequest->get_SeitePathPublic() /* . '/' */ . $s . ".txt";
      $pf = array();
      $source = '';
      if (file_exists($file)) {
         $this->write("File gefunden" . $file);
         $source = filesystem::LoadFile($file);

      }
      $this->tagdivision($s, $p);
      if ($s == "T") {
         $layout = '[[{"type":"Editor","size":80},{"type":"Console", "size":20}],"Canvas"]';

      } else {
         $layout = '["Editor","Console"]';
      }
      $this->write('<textarea style="display:none;" id="pt">');
      $this->write(html_entity_decode($source)); //, ENT_HTML5, "UTF-8"
      $this->write('</textarea>');
      $this->write('<div style="width:90%;height:768px"> 
                <iframe
      src="https://python.net-schulbuch.de"
      width="100%"
      height="100%",
      id="iframe"
    >
    </iframe>
  </div>');

      $this->write('<script>
      

        function newSettings() {
        var defobject = {
          type: "settings",
          data: {
            lang: "de",
            layout:' . $layout . ', 
            editor_size: 50,
            err_msg: ["TigerJython", "FriendlyTraceback"],
            dark_mode: false,
          },
        };
        return defobject;
      }

      function getSource(datei){
        let doc = document.getElementById("pt");
        alert(doc.value);
        python=doc.value;
        var source = JSON.stringify({
          type: "files",
          data: [
            {
              name: "main.py",
              data: python,
            },
          ],
        });
        return(source);
      }

      function sendMessage() {
        const ifr = document.getElementById("iframe");
        let setting = newSettings();
        ifr.contentWindow.postMessage( setting, "*");
        let source = getSource();
        ifr.contentWindow.postMessage( JSON.parse(source), "*");
      }

        window.addEventListener("message", function(event) {
          if (JSON.stringify(event.data) == "{\"type\":\"pythonReady\"}" ) {
            sendMessage()
	       }
        });

      </script>');


      $this->tagend($s, $p);
   }


   function netcode_modal(&$s, &$p)
   {
      global $_script;
      //11.05 include_once ($_script['doc_identifier']);
      $this->tagdivision($s, $p);
      $begriff = $s;
      $this->tagdivision($s, $p); // in $s steht der übergebene NET-CODE
      // HTML-Tags gnadenlos entfernen
      //echo $s.' '.__LINE__.'<br>';
      $docref = strip_tags($s);
      $this->modalnr = $this->modalnr + 1;
      $produkt = _ini('producturl');
      $link = $produkt . iRequest::href('s=' . $docref);
      $modalId = 'myModal' . $this->modalnr;
      $iframeId = $this->modalnr . 'inhalt';
      $modfenster = ' ' . self::modalLink($modalId, $begriff, 'net-schulbuch') . ' ';
      //***/debug ($docext, '$docext', '', 'vcml_engine::net_code ()', __LINE__, __FILE__, true);

      $before = '
          <script>
                function changeSrc' . $this->modalnr . '() {
                  document.getElementById("' . $iframeId . '").src="' . $link . '";
                }
          </script>';
      $body = '<iframe src="' . $link . '" width="100%" height="800px" style="border-width:0px" id="' . $iframeId . '" name="inhalt"></iframe>';
      $modfenster .= self::modalHtml($modalId, $begriff, $body, '', 'id="dialogmodalfenster" style="width:100%;"', 'style="overflow-y: scroll;"', 'style="height:80%;" id="mbody"', 'h1', $before, 'changeSrc' . $this->modalnr . '()');
      $this->write($modfenster);
      $this->tagend($s, $p);
   }

   function savetext(&$s, &$p)
   {
      $this->tagdivision($s, $p);
      $name = $s;
      $varname = preg_replace("/\s+/", "", $name);
      $this->tagdivision($s, $p);
      $spalten = $s;
      $this->tagdivision($s, $p);
      $zeilen = $s;
      $netCode = $GLOBALS['_iRequest']->value('s');
      $results = $this->textedatenbank->delete();
      $results = $this->textedatenbank->load(_USER_, $netCode, $varname);
      $t = "";
      if (mysqli_num_rows($results) > 0) {
         foreach ($results as $result) {
            $t = $result["text"];
         }
      }
      $src = '<div><strong>' . $name . '</strong></div>';

      $src .= '<div><textarea id = ' . $varname . ' name = "' . $varname . '" cols="' . $spalten . '" rows="' . $zeilen . '"
         style= "background-color:rgba(254,254,92,0.2);">' . $t . '</textarea>';
      $src .= '
                                               <p><button class="btn btn-warning"
                                                       onclick="saveText_db(\'' . _USER_ . '\',\'' . $netCode . '\',\'' . $name . '\',' . $varname . '.value)">speichern</button>
                                               </p>
                                           </div>';
      $this->write($src);
      $this->tagend($s, $p);
   }
}