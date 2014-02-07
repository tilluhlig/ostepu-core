<?php
/**
 * @file DBExerciseSheet.php contains the DBExerciseSheet class
 * 
 * @author Till Uhlig
 * @author Felix Schmidt
 * @example DB/DBExerciseSheet/ExerciseSheetSample.json
 */ 

require_once( '../../Assistants/Slim/Slim.php' );
include_once( '../../Assistants/Structures.php' );
include_once( '../../Assistants/Request.php' );
include_once( '../../Assistants/DBJson.php' );
include_once( '../../Assistants/DBRequest.php' );
include_once( '../../Assistants/CConfig.php' );
include_once( '../../Assistants/Logger.php' );

\Slim\Slim::registerAutoloader();

// runs the CConfig
$com = new CConfig(DBExerciseSheet::getPrefix());

// runs the DBExerciseSheet
if (!$com->used())
    new DBExerciseSheet($com->loadConfig());  
    
/**
 * A class, to abstract the "ExerciseSheet" table from database
 */
class DBExerciseSheet
{
    /**
     * @var Slim $_app the slim object
     */ 
    private $_app=null;
    
    /**
     * @var Component $_conf the component data object
     */ 
    private $_conf=null;
    
    /**
     * @var Link[] $query a list of links to a query component
     */ 
    private $query=array();
    
    /**
     * @var string $_prefix the prefixes, the class works with (comma separated)
     */ 
    private static $_prefix = "exercisesheet";
    
    /**
     * the $_prefix getter
     *
     * @return the value of $_prefix
     */ 
    public static function getPrefix()
    {
        return DBExerciseSheet::$_prefix;
    }
    
    /**
     * the $_prefix setter
     *
     * @param string $value the new value for $_prefix
     */ 
    public static function setPrefix($value)
    {
        DBExerciseSheet::$_prefix = $value;
    }


    /**
     * REST actions
     *
     * This function contains the REST actions with the assignments to
     * the functions.
     *
     * @param Component $conf component data
     */
    public function __construct($conf)
    {
        // initialize component
        $this->_conf = $conf;
        $this->query = array(CConfig::getLink($conf->getLinks(),"out"));
        
        // initialize slim
        $this->_app = new \Slim\Slim();
        $this->_app->response->headers->set('Content-Type', 'application/json');

        // PUT EditExerciseSheet
        $this->_app->put('/' . $this->getPrefix() . '(/exercisesheet)/:esid(/)',
                        array($this,'editExerciseSheet'));
        
        // DELETE DeleteExerciseSheet
        $this->_app->delete('/' . $this->getPrefix() . '(/exercisesheet)/:esid(/)',
                           array($this,'deleteExerciseSheet'));
        
        // POST SetExerciseSheet
        $this->_app->post('/' . $this->getPrefix() . '(/)',
                         array($this,'addExerciseSheet'));
               
        // GET GetExerciseSheetURL
        $this->_app->get('/' . $this->getPrefix() . '(/exercisesheet)/:esid/url(/)',
                        array($this,'getExerciseSheetURL'));
                        
        // GET GetCourseSheetURLs
        $this->_app->get('/' . $this->getPrefix() . '/course/:courseid/url(/)',
                        array($this,'getCourseSheetURLs'));
                        
        // GET GetCourseSheets
        $this->_app->get('/' . $this->getPrefix() . '/course/:courseid+(/)',  
                        array($this,'getCourseSheets'));
                        
        // GET GetExerciseSheet
        $this->_app->get('/' . $this->getPrefix() . '(/exercisesheet)/:esid+(/)',
                        array($this,'getExerciseSheet'));
        
        // starts slim only if the right prefix was received
        if (strpos ($this->_app->request->getResourceUri(),'/' . 
                    $this->getPrefix()) === 0){
                    
            // run Slim
            $this->_app->run();
        }
    }


    /**
     * Edits an exercise sheet.
     *
     * Called when this component receives an HTTP PUT request to
     * /exercisesheet/$esid(/) or /exercisesheet/exercisesheet/$esid(/).
     * The request body should contain a JSON object representing the exercise
     * sheet's new attributes.
     *
     * @param int $esid The id of the exercise sheet that is being updated.
     */
    public function editExerciseSheet($esid)
    {
        Logger::Log("starts PUT EditExerciseSheet",LogLevel::DEBUG);
        
        // checks whether incoming data has the correct data type
        DBJson::checkInput($this->_app, 
                            ctype_digit($esid));
                            
        // decode the received exercise sheet data, as an object
        $insert = ExerciseSheet::decodeExerciseSheet($this->_app->request->getBody());
        
        // always been an array
        if (!is_array($insert))
            $insert = array($insert);

        foreach ($insert as $in){
            // generates the update data for the object
            $data = $in->getInsertData();
            
            // starts a query, by using a given file
            $result = DBRequest::getRoutedSqlFile($this->query, 
                                            "Sql/EditExerciseSheet.sql", 
                                            array("esid" => $esid, "values" => $data));                   
           
            // checks the correctness of the query
            if ($result['status']>=200 && $result['status']<=299){
                $this->_app->response->setStatus(201);
                if (isset($result['headers']['Content-Type']))
                    $this->_app->response->headers->set('Content-Type', $result['headers']['Content-Type']);
                
            } else{
                Logger::Log("PUT EditExerciseSheet failed",LogLevel::ERROR);
                $this->_app->response->setStatus(isset($result['status']) ? $result['status'] : 409);
                $this->_app->stop();
            }
        }
    }


    /**
     * Deletes an exercise sheet.
     *
     * Called when this component receives an HTTP DELETE request to
     * /exercisesheet/$esid(/) or /exercisesheet/exercisesheet/$esid(/).
     *
     * @param int $esid The id of the exercise sheet that is being deleted.
     */
    public function deleteExerciseSheet($esid)
    {
        Logger::Log("starts DELETE DeleteExerciseSheet",LogLevel::DEBUG);
        
        // checks whether incoming data has the correct data type
        DBJson::checkInput($this->_app, 
                            ctype_digit($esid));
                            
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/DeleteExerciseSheet.sql", 
                                        array("esid" => $esid));    
                                        
        // checks the correctness of the query                          
        if ($result['status']>=200 && $result['status']<=299){
            $this->_app->response->setStatus(201);
            if (isset($result['headers']['Content-Type']))
                $this->_app->response->headers->set('Content-Type', $result['headers']['Content-Type']);
                
        } else{
            Logger::Log("DELETE DeleteExerciseSheet failed",LogLevel::ERROR);
                $this->_app->response->setStatus(isset($result['status']) ? $result['status'] : 409);
            $this->_app->stop();
        }
    }


    /**
     * Adds an exercise sheet.
     *
     * Called when this component receives an HTTP POST request to
     * /exercisesheet(/).
     * The request body should contain a JSON object representing the exercise
     * sheet's attributes.
     */
    public function addExerciseSheet()
    {
        Logger::Log("starts POST AddExerciseSheet",LogLevel::DEBUG);
        
        // decode the received exercise sheet data, as an object
        $insert = ExerciseSheet::decodeExerciseSheet($this->_app->request->getBody());
        
        // always been an array
        if (!is_array($insert))
            $insert = array($insert);
        
        // this array contains the indices of the inserted objects
        $res = array();
        foreach ($insert as $in){
            // generates the insert data for the object
            $data = $in->getInsertData();
            
            // starts a query, by using a given file
            $result = DBRequest::getRoutedSqlFile($this->query, 
                                            "Sql/AddExerciseSheet.sql", 
                                            array("values" => $data));                   
           
            // checks the correctness of the query    
            if ($result['status']>=200 && $result['status']<=299){
                $queryResult = Query::decodeQuery($result['content']);
                
                // sets the new auto-increment id
                $obj = new ExerciseSheet();
                $obj->setId($queryResult->getInsertId());
            
                array_push($res, $obj);
                $this->_app->response->setStatus(201);
                if (isset($result['headers']['Content-Type']))
                    $this->_app->response->headers->set('Content-Type', $result['headers']['Content-Type']);
                
            } else{
                Logger::Log("POST AddExerciseSheet failed",LogLevel::ERROR);
                $this->_app->response->setStatus(isset($result['status']) ? $result['status'] : 409);
                $this->_app->response->setBody(ExerciseSheet::encodeExerciseSheet($res)); 
                $this->_app->stop();
            }
        }
        
        if (count($res)==1){
            $this->_app->response->setBody(ExerciseSheet::encodeExerciseSheet($res[0])); 
        }
        else
            $this->_app->response->setBody(ExerciseSheet::encodeExerciseSheet($res)); 
    }


    /**
     * Returns the URL to a given exercise sheet.
     *
     * Called when this component receives an HTTP GET request to
     * /exercisesheet/$esid/url(/) or /exercisesheet/exercisesheet/$esid/url(/).
     *
     * @param int $esid The id of the exercise sheet the returned URL belongs to.
     */
    public function getExerciseSheetURL($esid)
    {     
        Logger::Log("starts GET GetExerciseSheetURL",LogLevel::DEBUG);
        
        // checks whether incoming data has the correct data type
        DBJson::checkInput($this->_app, 
                            ctype_digit($esid));
                            
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetExerciseSheetURL.sql", 
                                        array("esid" => $esid));        

        // checks the correctness of the query                                
        if ($result['status']>=200 && $result['status']<=299){ 
            $query = Query::decodeQuery($result['content']);

            $data = $query->getResponse();
            
            // generates an assoc array of an file by using a defined 
            // list of its attributes
            $exerciseSheetFile = DBJson::getResultObjectsByAttributes($data, 
                                                        File::getDBPrimaryKey(), 
                                                        File::getDBConvert());
            
            // only one object as result
            if (count($exerciseSheetFile)>0)
                $exerciseSheetFile = $exerciseSheetFile[0];
            
            $this->_app->response->setBody(File::encodeFile($exerciseSheetFile));
            $this->_app->response->setStatus(200);
            if (isset($result['headers']['Content-Type']))
                $this->_app->response->headers->set('Content-Type', $result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetExerciseSheetURL failed",LogLevel::ERROR);
                $this->_app->response->setStatus(isset($result['status']) ? $result['status'] : 409);
            $this->_app->response->setBody(File::encodeExerciseSheet(new File()));
            $this->_app->stop();
        }
    }


    /**
     * Returns the URLs to all exercise sheets of a given course.
     *
     * Called when this component receives an HTTP GET request to
     * /exercisesheet/course/$courseid/url(/).
     *
     * @param int $courseid The id of the course.
     */
    public function getCourseSheetURLs($courseid)
    {     
        Logger::Log("starts GET GetCourseSheetURLs",LogLevel::DEBUG);
        
        // checks whether incoming data has the correct data type
        DBJson::checkInput($this->_app, 
                            ctype_digit($courseid));
                            
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetCourseSheetURLs.sql", 
                                        array("courseid" => $courseid));        

        // checks the correctness of the query                             
        if ($result['status']>=200 && $result['status']<=299){ 
            $query = Query::decodeQuery($result['content']);

            $data = $query->getResponse();
            
            // generates an assoc array of files by using a defined list of its attributes
            $exerciseSheetFiles = DBJson::getResultObjectsByAttributes($data, File::getDBPrimaryKey(), File::getDBConvert());
            
            $this->_app->response->setBody(File::encodeFile($exerciseSheetFiles));
            $this->_app->response->setStatus(200);
            if (isset($result['headers']['Content-Type']))
                $this->_app->response->headers->set('Content-Type', $result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetCourseSheetURLs failed",LogLevel::ERROR);
                $this->_app->response->setStatus(isset($result['status']) ? $result['status'] : 409);
            $this->_app->response->setBody(File::encodeFile(new File()));
            $this->_app->stop();
        }
    }


    /**
     * Returns an exercise sheet.
     *
     * Called when this component receives an HTTP GET request to
     * /exercisesheet/$esid(/) or /exercisesheet/exercisesheet/$esid(/).
     *
     * @param int $esid The id of the exercise sheet that should be returned.
     */
    public function getExerciseSheet($esid)
    {     
        Logger::Log("starts GET GetExerciseSheet",LogLevel::DEBUG);  
                            
        if (count($esid)<1){
            Logger::Log("PUT EditExerciseSheet wrong use",LogLevel::ERROR);
                $this->_app->response->setStatus(isset($result['status']) ? $result['status'] : 409);
            $this->_app->response->setBody(ExerciseSheet::encodeExerciseSheet(new ExerciseSheet()));
            $this->_app->stop();
            return;
        }

        $options = array_splice($esid,1);
        $esid = $esid[0];
        
        // checks whether incoming data has the correct data type
        DBJson::checkInput($this->_app, 
                            ctype_digit($esid));
                            
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetExerciseSheet.sql", 
                                        array("esid" => $esid));  
                                        
        // checks the exercise option          
        if (in_array('exercise',$options)){
            // starts a query, by using a given file
            $result2 = DBRequest::getRoutedSqlFile($this->query, 
                                    "Sql/GetSheetExercises.sql", 
                                    array("esid" => $esid)); 
        }

        // checks the correctness of the query    
        if ($result['status']>=200 && $result['status']<=299 && (!isset($result2) || ($result2['status']>=200 && $result2['status']<=299))){
            $query = Query::decodeQuery($result['content']);

            $data = $query->getResponse();
            
            // generates an assoc array of an exercise sheet by using a defined list of its attributes
            $exerciseSheet = DBJson::getObjectsByAttributes($data, 
                                    ExerciseSheet::getDBPrimaryKey(), 
                                    ExerciseSheet::getDBConvert());
            
            // generates an assoc array of an file by using a defined list of its attributes
            $sampleSolutions = DBJson::getObjectsByAttributes($data, 
                                            File::getDBPrimaryKey(), 
                                            File::getDBConvert());
            
            // generates an assoc array of an file by using a defined list of its attributes
            $exerciseSheetFile = DBJson::getObjectsByAttributes($data, 
                                            File::getDBPrimaryKey(), 
                                            File::getDBConvert(), 
                                            '2');
          
            // concatenates the exercise sheet and the associated sample solution
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $exerciseSheet,
                            ExerciseSheet::getDBPrimaryKey(),
                            ExerciseSheet::getDBConvert()['F_id_sampleSolution'],
                            $sampleSolutions,
                            File::getDBPrimaryKey());  
            
            // concatenates the exercise sheet and the associated exercise sheet file
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $res,
                            ExerciseSheet::getDBPrimaryKey(),
                            ExerciseSheet::getDBConvert()['F_id_file'] ,
                            $exerciseSheetFile,
                            File::getDBPrimaryKey(), 
                            '2');
            
            // checks the exercise option
            if (in_array('exercise',$options)){
                $query = Query::decodeQuery($result2['content']);
                $data = $query->getResponse();
            
                // generates an assoc array of exercises by using a defined list of its attributes
                $exercises = DBJson::getObjectsByAttributes($data, 
                                        Exercise::getDBPrimaryKey(), 
                                        Exercise::getDBConvert());
            
                // concatenates the exercise sheet and the associated exercises
                $res = DBJson::concatResultObjectLists($data, 
                                    $res,
                                    ExerciseSheet::getDBPrimaryKey(),
                                    ExerciseSheet::getDBConvert()['ES_exercises'],
                                    $exercises,Exercise::getDBPrimaryKey());
            }
           
            // to reindex
            $res = array_merge($res);
            
            // only one object as result
            if (count($res)>0)
                $res = $res[0];
                
            $this->_app->response->setBody(ExerciseSheet::encodeExerciseSheet($res));
            $this->_app->response->setStatus(200);
            if (isset($result['headers']['Content-Type']))
                $this->_app->response->headers->set('Content-Type', $result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetExerciseSheet failed",LogLevel::ERROR);
                $this->_app->response->setStatus(isset($result['status']) ? $result['status'] : 409);
            $this->_app->response->setBody(ExerciseSheet::encodeExerciseSheet(new ExerciseSheet()));
            $this->_app->stop();
        }
    }


    /**
     * Returns all exercise sheets of a given course.
     *
     * Called when this component receives an HTTP GET request to
     * /exercisesheet/course/$courseid(/).
     *
     * @param int $courseid The id of the course the exercise sheets belong to.
     */
    public function getCourseSheets($courseid)
    {     
        Logger::Log("starts GET GetCourseSheets",LogLevel::DEBUG);
                            
        if (count($courseid)<1){
            $this->_app->response->setStatus(isset($result['status']) ? $result['status'] : 409);
            $this->_app->response->setBody(ExerciseSheet::encodeExerciseSheet(new ExerciseSheet()));
            $this->_app->stop();
            return;
        }
        
        $options = array_splice($courseid,1);
        $courseid = $courseid[0];
        
        // checks whether incoming data has the correct data type
        DBJson::checkInput($this->_app, 
                            ctype_digit($courseid));
        
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetCourseSheets.sql", 
                                        array("courseid" => $courseid));  
                                        
        // checks the exercise option                           
        if (in_array('exercise',$options)){
            $result2 = DBRequest::getRoutedSqlFile($this->query, 
                                    "Sql/GetCourseExercises.sql", 
                                    array("courseid" => $courseid)); 
        }
        
        // checks the correctness of the query    
        if ($result['status']>=200 && $result['status']<=299 && (!isset($result2) || ($result2['status']>=200 && $result2['status']<=299))){
            $query = Query::decodeQuery($result['content']);

            $data = $query->getResponse();
            
            // generates an assoc array of an exercise sheet by using a defined list of its attributes
            $exerciseSheet = DBJson::getObjectsByAttributes($data, 
                                ExerciseSheet::getDBPrimaryKey(), 
                                ExerciseSheet::getDBConvert());

            // sets the sheet names
            $id = 1;
            foreach ($exerciseSheet as &$sheet){
                if (!isset($sheet['sheetName']) || $sheet['sheetName']==null){
                    $sheet['sheetName'] = 'Serie '. (string) $id;
                    $id++;
                }
            }
            
            // generates an assoc array of an file by using a defined list of its attributes
            $sampleSolutions = DBJson::getObjectsByAttributes($data, 
                                                File::getDBPrimaryKey(), 
                                                File::getDBConvert());
            
            // generates an assoc array of an file by using a defined list of its attributes
            $exerciseSheetFile = DBJson::getObjectsByAttributes($data, 
                                                File::getDBPrimaryKey(), 
                                                File::getDBConvert(), '2');
          
            // concatenates the exercise sheet and the associated sample solution
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $exerciseSheet,ExerciseSheet::getDBPrimaryKey(),
                            ExerciseSheet::getDBConvert()['F_id_sampleSolution'],
                            $sampleSolutions,
                            File::getDBPrimaryKey());  
            
            // concatenates the exercise sheet and the associated exercise sheet file
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $res,
                            ExerciseSheet::getDBPrimaryKey(),
                            ExerciseSheet::getDBConvert()['F_id_file'],
                            $exerciseSheetFile,File::getDBPrimaryKey(), '2');
           
            // checks the exercise option
            if (in_array('exercise',$options)){
                $query = Query::decodeQuery($result2['content']);
                            $data = $query->getResponse();
                            
                $exercises = DBJson::getObjectsByAttributes($data, 
                            Exercise::getDBPrimaryKey(), 
                            Exercise::getDBConvert());
            
                // concatenates the exercise sheet and the associated exercises
                $res = DBJson::concatResultObjectLists($data, 
                            $res,
                            ExerciseSheet::getDBPrimaryKey(),
                            ExerciseSheet::getDBConvert()['ES_exercises'], 
                            $exercises,
                            Exercise::getDBPrimaryKey());
            }
           
            // to reindex
            $res = array_merge($res);        
            
            $this->_app->response->setBody(ExerciseSheet::encodeExerciseSheet($res));
            $this->_app->response->setStatus(200);
            if (isset($result['headers']['Content-Type']))
                $this->_app->response->headers->set('Content-Type', $result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetCourseSheets failed",LogLevel::ERROR);
                $this->_app->response->setStatus(isset($result['status']) ? $result['status'] : 409);
            $this->_app->response->setBody(ExerciseSheet::encodeExerciseSheet(new ExerciseSheet()));
            $this->_app->stop();
        }    

    }

}
?>