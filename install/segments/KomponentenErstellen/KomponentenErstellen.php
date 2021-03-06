<?php

/**
 * @file KomponentenErstellen.php
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 *
 * @package OSTEPU (https://github.com/ostepu/system)
 * @since 0.3.3
 *
 * @author Till Uhlig <till.uhlig@student.uni-halle.de>
 * @date 2015-2016
 * @author Ralf Busch <ralfbusch92@gmail.com>
 * @date 2015
 */
#region KomponentenErstellen
class KomponentenErstellen {

    private static $initialized = false;
    public static $name = 'componentDefs';
    public static $installed = false;
    public static $page = 3;
    public static $rank = 50;
    public static $enabledShow = true;
    public static $enabledInstall = true;
    private static $langTemplate = 'KomponentenErstellen';
    public static $onEvents = array('install' => array('name' => 'componentDefs', 'event' => array('actionInstallComponentDefs', 'install', 'update')));

    /**
     * initialisiert das Segment
     * @param type $console
     * @param string[][] $data die Serverdaten
     * @param bool $fail wenn ein Fehler auftritt, dann auf true setzen
     * @param string $errno im Fehlerfall kann hier eine Fehlernummer angegeben werden
     * @param string $error ein Fehlertext für den Fehlerfall
     */
    public static function init($console, &$data, &$fail, &$errno, &$error) {
        Installation::log(array('text' => Installation::Get('main', 'functionBegin')));
        Language::loadLanguageFile('de', self::$langTemplate, 'json', dirname(__FILE__) . '/');
        Installation::log(array('text' => Installation::Get('main', 'languageInstantiated')));
        self::$initialized = true;
        Installation::log(array('text' => Installation::Get('main', 'functionEnd')));
    }

    public static function show($console, $result, $data) {
        // das Segment soll nur gezeichnet werden, wenn der Nutzer eingeloggt ist
        if (!Einstellungen::$accessAllowed) {
            return;
        }

        Installation::log(array('text' => Installation::Get('main', 'functionBegin')));
        $text = '';

        if (!$console) {
            $text .= Design::erstelleBeschreibung($console, Installation::Get('generateComponents', 'description', self::$langTemplate));
        }

        if (isset($result[self::$onEvents['install']['name']]) && $result[self::$onEvents['install']['name']] != null) {
            $result = $result[self::$onEvents['install']['name']];
        } else {
            $result = array('content' => null, 'fail' => false, 'errno' => null, 'error' => null);
        }

        $fail = $result['fail'];
        $error = $result['error'];
        $errno = $result['errno'];
        $content = $result['content'];

        if (!$console) {
            $text .= Design::erstelleZeile($console, Installation::Get('generateComponents', 'generateComponents', self::$langTemplate), 'e', '', 'v', Design::erstelleSubmitButton(self::$onEvents['install']['event'][0]), 'h');
        }

        if (self::$installed) {
            
            // gibt die Daten zu den installierten Komponenten und Verbindungen aus
            if (isset($content['components'])) {
                $text .= Design::erstelleZeile($console, Installation::Get('generateComponents', 'numberComponents', self::$langTemplate), 'v', $content['componentsCount'], 'v');
                $text .= Design::erstelleZeile($console, Installation::Get('generateComponents', 'numberLinks', self::$langTemplate), 'v', $content['linksCount'], 'v');
            }
            
            // gibt die Fehlermeldungen aus
            if (isset($content['errorMessages'])){
                foreach ($content['errorMessages'] as $messageText){
                    $text .= Design::erstelleZeileShort($console, $messageText, 'error');
                }
            }

            $text .= Design::erstelleInstallationszeile($console, $fail, $errno, $error);
        }
        
        if (!$console){
            $componentFiles = Paketverwaltung::getComponentFilesFromSelectedPackages($data, $fail, $errno, $error);
            $externals = array();
            foreach($componentFiles as $com){
                if ($com['location'] != 'external'){
                    // wir interessieren uns hier nur für externe
                    continue;
                }
                $externals[] = $com;
            }
            
            if (count($externals)>0){
                $text .= Design::erstelleZeileShort($console, '', '');
                $text .= Design::erstelleZeileShort($console, Installation::Get('generateComponents', 'configureExternalDefinitions', self::$langTemplate), 'e_c');
                foreach($externals as $ext){
                    // es handelt sich um eine externe Definition, sodass wir hier ein Feld zum Eintragen bereitstellen wollen
                    $text .= Design::erstelleZeile($console, $com['name'], 'e', Design::erstelleEingabezeile($console, $data['PLUG']['componentDef_'.$com['name']], 'data[PLUG][componentDef_'.$com['name'].']', $com['conf'], true), 'v');
                }
            }
        }

        echo Design::erstelleBlock($console, Installation::Get('generateComponents', 'title', self::$langTemplate), $text);
        Installation::log(array('text' => Installation::Get('main', 'functionEnd')));
        return null;
    }
    
    private static function extendComponentDefinition($component, &$extensions){
        foreach($extensions as $key => $extension){
            if ($component['name'] == $extension['name']){
                if (!isset($component['links'])) {$component['links'] = array();}
                if (!isset($extension['links'])) {$extension['links'] = array();}
                $component['links'] = array_merge($component['links'], $extension['links']);
                
                unset($extensions[$key]);
            }
        }
        return $component;
    }

    public static function install($data, &$fail, &$errno, &$error) {
        Installation::log(array('text' => Installation::Get('main', 'functionBegin')));
        $serverFiles = Installation::GibServerDateien();

        $installComponentDefsResult['components'] = array();
        $installComponentDefsResult['defExtensions'] = array();
        $installComponentDefsResult['errorMessages'] = array();
        
        foreach ($serverFiles as $sf) {
            $sf = pathinfo($sf)['filename'];
            $tempData = Einstellungen::ladeEinstellungenDirekt($sf, $data);
            if ($tempData === null) {
                $fail = true;
                $error = Installation::Get('generateComponents', 'noAccess', self::$langTemplate);
                return;
            }

            $componentList = Zugang::Ermitteln('actionInstallComponentDefs', 'KomponentenErstellen::installiereKomponentenDefinitionen', $tempData, $fail, $errno, $error);

            if (isset($componentList['components'])) {
                $installComponentDefsResult['components'] = array_merge($installComponentDefsResult['components'], $componentList['components']);
            }
            
            if (isset($componentList['errorMessages'])) {
                $installComponentDefsResult['errorMessages'] = array_merge($installComponentDefsResult['errorMessages'], $componentList['errorMessages']);
            }
            
            if (isset($componentList['defExtensions'])) {
                $installComponentDefsResult['defExtensions'] = array_merge($installComponentDefsResult['defExtensions'], $componentList['defExtensions']);
            }
        }
        //var_dump($installComponentDefsResult['components']);
        // Komponenten erzeugen
        $comList = array();
        $setDBNames = array();
        $ComponentList = array();

        // zunächst die Komponentenliste nach Namen sortieren
        $ComponentListInput = array();
        foreach ($installComponentDefsResult['components'] as $key => $input) {
            if (!isset($input['name'])) {
                continue;
            }
            if (!isset($ComponentListInput[$input['name']])) {
                $ComponentListInput[$input['name']] = array();
            }
            
            $input = self::extendComponentDefinition($input, $installComponentDefsResult['defExtensions']);
            $ComponentListInput[$input['name']][$key] = $input;
        }

        for ($zz = 0; $zz < 2; $zz++) {
            $tempList = array();
            foreach ($ComponentListInput as $key2 => $ComNames) {
                foreach ($ComNames as $key => $input) {
                    if (!isset($input['name'])) {
                        continue;
                    }

                    if (!isset($input['type']) || $input['type'] == 'normal') {
                        // normale Komponente

                        if (!isset($input['registered'])) {
                            $comList[] = "('{$input['name']}', '{$input['urlExtern']}/{$input['path']}', '" . (isset($input['option']) ? $input['option'] : '') . "', '" . implode(';', (isset($input['def']) ? $input['def'] : array())) . "', '".(isset($input['initialization']) ? $input['initialization'] : 'basic')."')";

                            // Verknüpfungen erstellen
                            $setDBNames[] = " SET @{$key}_{$input['name']} = (select CO_id from Component where CO_address='{$input['urlExtern']}/{$input['path']}' limit 1); ";
                            $input['dbName'] = $key . '_' . $input['name'];
                            $input['registered'] = '1';
                        }
                        if (!isset($tempList[$key2])) {
                            $tempList[$key2] = array();
                        }
                        $tempList[$key2][$key] = $input;
                    } elseif (isset($input['type']) && $input['type'] == 'clone') {
                        // Komponente basiert auf einer bestehenden
                        if (!isset($input['base'])) {
                            continue;
                        }
                        if (!isset($input['baseURI'])) {
                            $input['baseURI'] = '';
                        }

                        if (isset($ComponentListInput[$input['base']])) {
                            foreach ($ComponentListInput[$input['base']] as $key3 => $input2) {
                                if (!isset($input2['name'])) {
                                    continue;
                                }

                                // pruefe, dass die Eintraege nicht doppelt erstellt werden
                                $found = false;
                                if (isset($ComponentListInput[$input['name']])) {
                                    foreach ($ComponentListInput[$input['name']] as $input3) {
                                        if ((!isset($input3['type']) || $input3['type'] == 'normal') && $input['name'] == $input3['name'] && "{$input3['urlExtern']}/{$input3['path']}" == "{$input2['urlExtern']}/{$input2['path']}{$input['baseURI']}") {
                                            $found = true;
                                            break;
                                        }
                                    }
                                }
                                if ($found) {
                                    continue;
                                }

                                if (isset($tempList[$input['name']])) {
                                    foreach ($tempList[$input['name']] as $input3) {
                                        if ($input['name'] == $input3['name'] && "{$input3['urlExtern']}/{$input3['path']}" == "{$input2['urlExtern']}/{$input2['path']}{$input['baseURI']}") {
                                            $found = true;
                                            break;
                                        }
                                    }
                                }
                                if ($found) {
                                    continue;
                                }

                                $input2['path'] = "{$input2['path']}{$input['baseURI']}";
                                $input2['def'] = array_merge($input2['def'], $input['def']);
                                
                                if (isset($input['initialization'])){
                                    $input2['initialization'] = $input['initialization'];
                                }

                                $input2['links'] = array_merge((isset($input2['links']) ? $input2['links'] : array()), (isset($input['links']) ? $input['links'] : array()));
                                
                                if (isset($input2['initialization']) && $input2['initialization'] == 'virtual'){
                                    // eine virtuelle Komponente darf keine Links besitzen
                                    $input2['links'] = array();
                                }
                                
                                // virtuelle Komponenten sollen erstmal keine Konnektoren haben dürfen, keine vererbten
                                if (isset($input2['initialization']) && $input2['initialization'] == 'virtual'){
                                    $input2['connector'] = (isset($input['connector']) ? $input['connector'] : array());
                                }else {
                                    $input2['connector'] = array_merge((isset($input2['connector']) ? $input2['connector'] : array()), (isset($input['connector']) ? $input['connector'] : array()));
                                }
                                
                                if (isset($input['option'])) {
                                    $input2['option'] = $input['option'];
                                }

                                $input2['name'] = $input['name'];
                                $input2['registered'] = null;
                                if (!isset($tempList[$key2])) {
                                    $tempList[$key2] = array();
                                }
                                
                                $input2 = self::extendComponentDefinition($input2, $installComponentDefsResult['defExtensions']);
                                $tempList[$key2][$key] = $input2;
                            }
                        }
                    }
                }
            }
            $ComponentListInput = $tempList;
        }

        $sql = "START TRANSACTION;SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;TRUNCATE TABLE `ComponentLinkage`;SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;COMMIT;"; //TRUNCATE TABLE `Component`;
        Installation::log(array('text' => Installation::Get('generateComponents', 'createTruncateQuery', self::$langTemplate, array('sql' => $sql))));
        $res = DBRequest::request2($sql, false, $data, true);
        Installation::log(array('text' => Installation::Get('generateComponents', 'truncateQueryResult', self::$langTemplate, array('res' => json_encode($res)))));

        $sql = "UPDATE `Component` SET `CO_status` = '0';";
        Installation::log(array('text' => Installation::Get('generateComponents', 'createResetStatusQuery', self::$langTemplate, array('sql' => $sql))));
        $res = DBRequest::request2($sql, false, $data, true);
        Installation::log(array('text' => Installation::Get('generateComponents', 'resetStatusQueryResult', self::$langTemplate, array('res' => json_encode($res)))));

        $sql = "START TRANSACTION;SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;INSERT INTO `Component` (`CO_name`, `CO_address`, `CO_option`, `CO_def`, `CO_initialization`) VALUES ";
        $installComponentDefsResult['componentsCount'] = count($comList);
        $sql .= implode(',', $comList);
        unset($comList);
        $sql .= " ON DUPLICATE KEY UPDATE CO_status='1', CO_address=VALUES(CO_address), CO_option=VALUES(CO_option), CO_def=VALUES(CO_def), CO_initialization=VALUES(CO_initialization);SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;COMMIT;";
        Installation::log(array('text' => Installation::Get('generateComponents', 'createInsertQuery', self::$langTemplate, array('sql' => $sql))));
        $res = DBRequest::request2($sql, false, $data, true);
        Installation::log(array('text' => Installation::Get('generateComponents', 'insertQueryResult', self::$langTemplate, array('res' => json_encode($res)))));

        $sql = "START TRANSACTION;SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;";
        $sql .= implode('', $setDBNames);
        //var_dump($setDBNames);
        unset($setDBNames);
        $links = array();
        
        

        foreach ($ComponentListInput as $key2 => $ComNames) {
            foreach ($ComNames as $key => $input) {
                if (isset($input['type']) && $input['type'] != 'normal') {
                    continue;
                }
                if (isset($input['dbName'])) {
                    $input['link_type'] = 'full';
                    $input['link_availability'] = 'full';

                    // prüfe nun alle Verknüpfungen dieser Komponente und erstelle diese
                    if (isset($input['links'])) {
                        foreach ($input['links'] as $link) {
                            if (!isset($link['target'])) {
                                $link['target'] = '';
                            }
                            if (!is_array($link['target'])) {
                                $link['target'] = array($link['target']);
                            }

                            foreach ($link['target'] as $tar) {// $tar -> der Name der Zielkomponente
                                if (!isset($ComponentListInput[$tar])) {
                                    continue;
                                }
                                foreach ($ComponentListInput[$tar] as $target) {
                                    // $target -> das Objekt der Zielkomponente
                                    if (!isset($target['dbName'])) {
                                        continue;
                                    }
                                    $target['link_availability'] = 'full';
                                    
                                    if (!isset($input['link_type']) || $input['link_type'] == 'local' || $input['link_type'] == '') {
                                        if ($input['urlExtern'] == $target['urlExtern']) {

                                            $priority = (isset($input['priority']) ? ", CL_priority = {$input['priority']}" : '');
                                            $relevanz = (isset($input['relevanz']) ? $input['relevanz'] : '');
                                            $sql .= " INSERT INTO `ComponentLinkage` SET CO_id_owner = @{$input['dbName']}, CL_name = '{$link['name']}', CL_relevanz = '{$relevanz}', CO_id_target = @{$target['dbName']} {$priority};";
                                            $links[] = 1;
                                        }
                                    } elseif ($input['link_type'] == 'full') {
                                        if ($input['urlExtern'] == $target['urlExtern'] || (isset($target['link_availability']) && $target['link_availability'] == 'full')) {
                                
                                            $priority = (isset($input['priority']) ? ", CL_priority = {$input['priority']}" : '');
                                            $relevanz = (isset($input['relevanz']) ? $input['relevanz'] : '');
                                            $sql .= " INSERT INTO `ComponentLinkage` SET CO_id_owner = @{$input['dbName']}, CL_name = '{$link['name']}', CL_relevanz = '{$relevanz}', CO_id_target = @{$target['dbName']} {$priority};";
                                            $links[] = 1;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (isset($input['connector'])) {
                        foreach ($input['connector'] as $link) {
                            if (!isset($link['target'])) {
                                $link['target'] = '';
                            }
                            if (!is_array($link['target'])) {
                                $link['target'] = array($link['target']);
                            }
                            if (!isset($link['links'])) {
                                $link['links'] = array('a' => null);
                            }
                            foreach ($link['links'] as $callKey => $call) {
                                foreach ($link['target'] as $tar) {// $tar -> der Name der Zielkomponente
                                    if (!isset($ComponentListInput[$tar])) {
                                        continue;
                                    }
                                    foreach ($ComponentListInput[$tar] as $target) {
                                        // $target -> das Objekt der Zielkomponente
                                        if (!isset($target['dbName'])) {
                                            continue;
                                        }
                                        if (!isset($input['link_type']) || $input['link_type'] == 'local' || $input['link_type'] == '') {
                                            if ($input['urlExtern'] == $target['urlExtern']) {

                                                $priority = (isset($link['priority']) ? ", CL_priority = {$link['priority']}" : '');
                                                $method = (isset($call['method']) ? $call['method'] : 'GET');
                                                $path = (isset($call['path']) ? ", CL_path = '{$method} {$call['path']}'" : '');
                                                $relevanz = (isset($link['relevanz']) ? $link['relevanz'] : '');
                                                $sql .= " INSERT INTO `ComponentLinkage` SET CO_id_owner = @{$target['dbName']}, CL_name = '{$link['name']}', CL_relevanz = '{$relevanz}', CO_id_target = @{$input['dbName']} {$priority} {$path};";
                                                $links[] = 1;
                                            }
                                        } elseif ($input['link_type'] == 'full') {
                                            if ($input['urlExtern'] == $target['urlExtern'] || (isset($input['link_availability']) && $input['link_availability'] == 'full')) {

                                                $priority = (isset($link['priority']) ? ", CL_priority = {$link['priority']}" : '');
                                                $method = (isset($call['method']) ? $call['method'] : 'GET');
                                                $path = (isset($call['path']) ? ", CL_path = '{$method} {$call['path']}'" : '');
                                                $relevanz = (isset($link['relevanz']) ? $link['relevanz'] : '');
                                                $sql .= " INSERT INTO `ComponentLinkage` SET CO_id_owner = @{$target['dbName']}, CL_name = '{$link['name']}', CL_relevanz = '{$relevanz}', CO_id_target = @{$input['dbName']} {$priority} {$path};";
                                                $links[] = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $installComponentDefsResult['linksCount'] = count($links);
        $sql .= " SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;COMMIT;";
        Installation::log(array('text' => Installation::Get('generateComponents', 'createInsertLinksQuery', self::$langTemplate, array('sql' => $sql))));
        $res = DBRequest::request2($sql, false, $data, true);
        Installation::log(array('text' => Installation::Get('generateComponents', 'insertLinksQueryResult', self::$langTemplate, array('res' => json_encode($res)))));
        $installComponentDefsResult['components'] = $ComponentListInput;
        //var_dump($sql);

        Installation::log(array('text' => Installation::Get('main', 'functionEnd')));
        return $installComponentDefsResult;
    }

    public static function installiereKomponentenDefinitionen($data, &$fail,
            &$errno, &$error) {
        Installation::log(array('text' => Installation::Get('main', 'functionBegin')));
        $res = array();
        $res['errorMessages'] = array();

        if (!$fail) {
            $mainPath = realpath($data['PL']['localPath']);
            $componentFiles = Paketverwaltung::getComponentFilesFromSelectedPackages($data, $fail, $errno, $error);

            // Komponentennamen und Orte ermitteln
            $res['components'] = array();
            foreach ($componentFiles as $comFileEntry) {
                $comFile = $comFileEntry['conf'];
                if ($comFile == ''){
                    $res['errorMessages'][] = Installation::Get('generateComponents', 'missingConfFile', self::$langTemplate);
                }
                
                $input = @file_get_contents($comFile);   

                // wenn die Datei nicht gelesen werden kann, dann überspringen
                if ($input === false) {
                    $res['errorMessages'][] = Installation::Get('generateComponents', 'missingFile', self::$langTemplate, array('file'=>$comFile));
                    continue;
                }

                $input = json_decode($input, true);
                
                // wenn die Datei nicht wohlgeformt ist, dann überspringen
                if ($input == null) {
                    $res['errorMessages'][] = Installation::Get('generateComponents', 'jsonErrorInFile', self::$langTemplate, array('file'=>$comFile));
                    continue;
                }

                $res['components'][] = self::evaluateComponentData($data, $input, $comFile, $mainPath);
            }
            
            // sammle externe Komponenten ein
            $externalComponents = Installation::collect('getExternalComponents',$data, array(__CLASS__));
            foreach ($externalComponents as $comEntry) {
                $res['components'][] = self::evaluateComponentData($data, $comEntry);
            }
            
            // nun wollen wir noch externe Links einsammeln (diese sind Component-Objekte)
            $res['defExtensions'] = Installation::collect('getComponentDefinitionExtension',$data, array(__CLASS__));
        }

        Installation::log(array('text' => Installation::Get('main', 'functionEnd')));
        return $res;
    }
    
    public static function evaluateComponentData($data, $input, $comFile=null, $mainPath=null){
        if (isset($data['PL']['urlExtern']) && !isset($input['urlExtern'])) {
            $input['urlExtern'] = $data['PL']['urlExtern'];
        }
        if (isset($data['PL']['url']) && !isset($input['url'])) {
            $input['url'] = $data['PL']['url'];
        }
        
            if (!isset($input['path'])) {
                if ($comFile !== null){
                    $input['path'] = substr(dirname($comFile), strlen($mainPath) + 1);
                    $input['path'] = str_replace(array("\\"), array('/'), $input['path']);
                } else {
                    // nun ??
                }
            }
        
        if ($comFile !== null){
            $input['def'] = array($input['name'], str_replace("\\", "/", realpath($comFile)));
        } else {
            $input['def'] = array();
        }
        
        if (isset($data['CO']['co_link_type'])) {
            $input['link_type'] = $data['CO']['co_link_type'];
        }
        if (isset($data['CO']['co_link_availability'])) {
            $input['link_availability'] = $data['CO']['co_link_availability'];
        }

        if (isset($input['files'])) {
            unset($input['files']);
        }
        return $input;
    }

}

#endregion KomponentenErstellen