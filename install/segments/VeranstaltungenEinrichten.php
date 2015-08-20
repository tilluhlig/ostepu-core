<?php
#region VeranstaltungenEinrichten
class VeranstaltungenEinrichten
{
    private static $initialized=false;
    public static $name = 'initCourses';
    public static $installed = false;
    public static $page = 4;
    public static $rank = 100;
    public static $enabledShow = true;
    
    public static $onEvents = array('install'=>array('name'=>'initCourses','event'=>array('actionInstallCourses','install', 'update')));
    
    public static function getDefaults()
    {
        return array(
                     'c_details' => array('data[C][c_details]', null)
                     );
    }
        
    public static function init($console, &$data, &$fail, &$errno, &$error)
    {
        $def = self::getDefaults();
        
        $text = '';
        $text .= Design::erstelleVersteckteEingabezeile($console, $data['C']['c_details'], 'data[C][c_details]', $def['c_details'][1],true);
        echo $text;
        self::$initialized = true;
    }
    
    public static function show($console, $result, $data)
    {
        $isUpdate = (isset($data['action']) && $data['action']=='update') ? true : false;
        
        $text='';
        $text .= Design::erstelleBeschreibung($console,Language::Get('courses','description'));  

        if (!$console){
            $text .= Design::erstelleZeile($console, Language::Get('courses','createTables'), 'e', '', 'v', Design::erstelleSubmitButton(self::$onEvents['install']['event'][0]), 'h');
            $text .= Design::erstelleZeile($console, Language::Get('courses','details'), 'e', Design::erstelleAuswahl($console, $data['C']['c_details'], 'data[C][c_details]', 'details', null), 'v');
        }
        
        if (isset($result[self::$onEvents['install']['name']]) && $result[self::$onEvents['install']['name']]!=null){
           $result =  $result[self::$onEvents['install']['name']];
        } else 
            $result = array('content'=>null,'fail'=>false,'errno'=>null,'error'=>null);
        
        $fail = $result['fail'];
        $error = $result['error'];
        $errno = $result['errno'];
        $content = $result['content'];
        
        if (self::$installed){
            if (!$console && isset($data['C']['c_details']) && $data['C']['c_details'] === 'details' && !$isUpdate){
                foreach ($content as $courseid => $dat){
                    $text .= "<tr><td class='e' rowspan='1'>({$dat['course']->getId()}) {$dat['course']->getSemester()}</td><td class='v'>{$dat['course']->getName()}</td><td class='e'><div align ='center'>".((isset($dat['status']) && $dat['status']===201) ? Language::Get('main','ok') : "<font color='red'>".Language::Get('main','fail')." ({$dat['status']})</font>")."</align></td></tr>";
                }
            } else 
                $text .= Design::erstelleZeile($console, Language::Get('courses','countCourses'), 'e', count($content) , 'v_c');
            $text .= Design::erstelleInstallationszeile($console, $fail, $errno, $error);
        }

        echo Design::erstelleBlock($console, Language::Get('courses','title'), $text);
        return null;
    }
    
    public static function install($data, &$fail, &$errno, &$error)
    {
        $res = array();
    
        if (!$fail){
            // die /course Befehle der LCourse auslösen
            
            // alle Veranstaltungen abrufen
            $multiRequestHandle = new Request_MultiRequest();
            $handler = Request_CreateRequest::createGet($data['PL']['url'].'/DB/DBCourse/course',array(),'');
            $multiRequestHandle->addRequest($handler);            
            $result = $multiRequestHandle->run();
            if (isset($result[0]['content']) && isset($result[0]['status']) && $result[0]['status'] === 200){
                // /course ausloesen
                $courses = Course::decodeCourse($result[0]['content']);
                if (!is_array($courses)) $courses = array($courses);
                
                $multiRequestHandle = new Request_MultiRequest();
                foreach($courses as $course){
                    $handler = Request_CreateRequest::createPost($data['PL']['url'].'/logic/LCourse/course',array(),Course::encodeCourse($course));
                    $multiRequestHandle->addRequest($handler);   
                }        
                $answer = $multiRequestHandle->run();
                
                if (count($courses) != count($answer)){
                    $fail = true;
                    $error = Language::Get('courses','differentAnswers')."\n".Language::Get('main','line').':'.__LINE__;
                }
                
                $i=0;
                foreach($courses as $course){            
                    $result = $answer[$i];
                    $res[$course->getId()] = array();
                        $res[$course->getId()]['course'] = $course;
                    if (isset($result['content']) && isset($result['status']) && $result['status'] === 201){
                        $res[$course->getId()]['status'] = 201;
                    } else {
                        $res[$course->getId()]['status'] = 409;
                        $fail = true;
                        if (isset($result['status'])){
                            $errno = $result['status'];
                            $res[$course->getId()]['status'] = $result['status'];
                        }
                    }
                    $i++;
                    if ($i>=count($answer)) break;
                }
                
            } else {
                $fail = true;
                $error = "GET /DB/DBCourse/course ".Language::Get('courses','operationFailed');
                if (isset($result[0]['status'])){
                    $errno = $result[0]['status'];
                }
            }
        }
        
        return $res;
    }
}
#endregion VeranstaltungenEinrichten