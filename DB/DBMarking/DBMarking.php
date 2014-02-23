<?php
/**
 * @file DBMarking.php contains the DBMarking class
 */ 

require_once( 'Include/Slim/Slim.php' );
include_once( 'Include/Structures.php' );
include_once( 'Include/Request.php' );
include_once( 'Include/DBJson.php' );
include_once( 'Include/DBRequest.php' );
include_once( 'Include/CConfig.php' );
include_once( 'Include/Logger.php' );

\Slim\Slim::registerAutoloader();

// runs the CConfig
$com = new CConfig(DBMarking::getPrefix());

// runs the DBExerciseSheet
if (!$com->used())
    new DBMarking($com->loadConfig());  
    
/**
 * A class, to abstract the "Marking" table from database
 */
class DBMarking
{
    /**
     * @var $_app the slim object
     */ 
    private $_app=null;
    
    /**
     * @var $_conf the component data object
     */ 
    private $_conf=null;
    
    /**
     * @var $query a list of links to a query component
     */ 
    private $query=array();
    
    /**
     * @var $_prefix the prefix, the class works with
     */ 
    private static $_prefix = "marking";
    
    /**
     * the $_prefix getter
     *
     * @return the value of $_prefix
     */ 
    public static function getPrefix()
    {
        return DBMarking::$_prefix;
    }
    
    /**
     * the $_prefix setter
     *
     * @param $value the new value for $_prefix
     */ 
    public static function setPrefix($value)
    {
        DBMarking::$_prefix = $value;
    }
    
    /**
     * the component constructor
     *
     * @param $conf component data
     */ 
    public function __construct($conf)
    {
        // initialize component
        $this->_conf = $conf;
        $this->query = array(CConfig::getLink($conf->getLinks(),"out"));
        
        // initialize slim
        $this->_app = new \Slim\Slim();
        $this->_app->response->headers->set('Content-Type', 'application/json');

        // PUT EditMarking
        $this->_app->put('/' . $this->getPrefix() . 
                        '/marking/:mid',
                        array($this,'editMarking'));
        
        // DELETE DeleteMarking
        $this->_app->delete('/' . $this->getPrefix() . 
                            '/marking/:mid',
                            array($this,'deleteMarking'));
        
        // POST SetMarking
        $this->_app->post('/' . $this->getPrefix(),
                         array($this,'setMarking'));  
        
        // GET GetMarking
        $this->_app->get('/' . $this->getPrefix() . '/marking/:mid',
                        array($this,'getMarking'));
        
        // GET GetSubmissionMarking
        $this->_app->get('/' . $this->getPrefix() . '/submission/:suid',
                        array($this,'getSubmissionMarking'));
                        
        // GET GetAllMarkings
        $this->_app->get('/' . $this->getPrefix() . '/marking',
                        array($this,'getAllMarkings')); 
                        
        // GET GetExerciseMarkings
        $this->_app->get('/' . $this->getPrefix() . 
                        '/exercise/:eid',
                        array($this,'getExerciseMarkings'));  
                        
        // GET GetSheetMarkings
        $this->_app->get('/' . $this->getPrefix() . 
                        '/exercisesheet/:esid',
                        array($this,'getSheetMarkings'));
                        
        // GET GetUserGroupMarkings
        $this->_app->get('/' . $this->getPrefix() . '/exercisesheet/:esid/user/:userid',
                        array($this,'getUserGroupMarkings'));  
                        
        // starts slim only if the right prefix was received
        if (strpos ($this->_app->request->getResourceUri(),'/' . 
                    $this->getPrefix()) === 0){
        
            // run Slim
            $this->_app->run();
        }
    }
    
    /**
     * PUT EditMarking
     *
     * @param $mid a database marking identifier
     */
    public function editMarking($mid)
    {
        // decode the received marking data, as an object
        $insert = Marking::decodeMarking($this->_app->request->getBody());
        
        // always been an array
        if (!is_array($insert))
            $insert = array($insert);

        foreach ($insert as $in){
            // generates the update data for the object
            $data = $in->getInsertData();
            
            // starts a query, by using a given file
            $result = DBRequest::getRoutedSqlFile($this->query, 
                                    "Sql/EditMarking.sql", 
                                    array("mid" => $mid));                   
            
            // checks the correctness of the query
            if ($result['status']>=200 && $result['status']<=299){
                $this->_app->response->setStatus(201);
                if (isset($result['headers']['Content-Type']))
                    header($result['headers']['Content-Type']);
                
            } else{
                Logger::Log("PUT EditMarking failed",LogLevel::ERROR);
                $this->_app->response->setStatus(451);
                $this->_app->stop();
            }
        }
    }
    
    /**
     * DELETE DeleteMarking
     *
     * @param $mid a database marking identifier
     */
    public function deleteMarking($mid)
    {
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/DeleteMarking.sql", 
                                        array("mid" => $mid));    
        
        // checks the correctness of the query                          
        if ($result['status']>=200 && $result['status']<=299){
        
            $this->_app->response->setStatus($result['status']);
            if (isset($result['headers']['Content-Type']))
                header($result['headers']['Content-Type']);
                
        } else{
            Logger::Log("DELETE DeleteMarking failed",LogLevel::ERROR);
            $this->_app->response->setStatus(409);
            $this->_app->stop();
        }
    }
    
    /**
     * POST SetMarking
     */
    public function SetMarking()
    {
        // decode the received marking data, as an object
        $insert = Marking::decodeMarking($this->_app->request->getBody());
        
        // always been an array
        if (!is_array($insert))
            $insert = array($insert);

        foreach ($insert as $in){
            // generates the insert data for the object
            $data = $in->getInsertData();
            
            // starts a query, by using a given file
            $result = DBRequest::getRoutedSqlFile($this->query, 
                                            "Sql/SetMarking.sql", 
                                            array("values" => $data));                   
            
            // checks the correctness of the query 
            if ($result['status']>=200 && $result['status']<=299){
                 $queryResult = Query::decodeQuery($result['content']);
                
                // sets the new auto-increment id
                $obj = new Marking();
                $obj->setId($queryResult->getInsertId());
            
                $this->_app->response->setBody(Marking::encodeMarking($obj)); 
                
                $this->_app->response->setStatus(201);
                if (isset($result['headers']['Content-Type']))
                    header($result['headers']['Content-Type']);
                
            } else{
                Logger::Log("POST SetMarking failed",LogLevel::ERROR);
                $this->_app->response->setStatus(451);
                $this->_app->stop();
            }
        }
    }
    
    /**
     * GET GetAllMarkings
     */
    public function getAllMarkings()
    {      
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetAllMarkings.sql", 
                                        array());
        
        // checks the correctness of the query                                        
        if ($result['status']>=200 && $result['status']<=299){
            $query = Query::decodeQuery($result['content']);
            
            $data = $query->getResponse();

            // generates an assoc array of files by using a defined list of 
            // its attributes
            $files = DBJson::getObjectsByAttributes($data, 
                                            File::getDBPrimaryKey(), 
                                            File::getDBConvert());
                                            
            // generates an assoc array of markings by using a defined list of 
            // its attributes
            $markings = DBJson::getObjectsByAttributes($data, 
                                    Marking::getDBPrimaryKey(), 
                                    Marking::getDBConvert());  
                                    
            // concatenates the markings and the associated files
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $markings,
                            Marking::getDBPrimaryKey(),
                            Marking::getDBConvert()['M_file'] ,
                            $files,
                            File::getDBPrimaryKey());
                            
            // to reindex
            $res = array_values($res); 
            
            $this->_app->response->setBody(Marking::encodeMarking($res));
        
            $this->_app->response->setStatus($result['status']);
            if (isset($result['headers']['Content-Type']))
                header($result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetAllMarkings failed",LogLevel::ERROR);
            $this->_app->response->setStatus(409);
            $this->_app->response->setBody(Marking::encodeMarking(new Marking()));
            $this->_app->stop();
        }
    }
    
    /**
     * GET GetMarking
     *
     * @param $mid a database marking identifier
     */
    public function getMarking($mid)
    {         
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetMarking.sql", 
                                        array("mid" => $mid));
        
        // checks the correctness of the query                                        
        if ($result['status']>=200 && $result['status']<=299){
            $query = Query::decodeQuery($result['content']);
            
            $data = $query->getResponse();

            // generates an assoc array of a file by using a defined list of 
            // its attributes
            $file = DBJson::getObjectsByAttributes($data, 
                                            File::getDBPrimaryKey(), 
                                            File::getDBConvert());
                                            
            // generates an assoc array of a marking by using a defined list of 
            // its attributes
            $marking = DBJson::getObjectsByAttributes($data, 
                                    Marking::getDBPrimaryKey(), 
                                    Marking::getDBConvert());  
                                    
            // concatenates the markings and the associated files
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $marking,
                            Marking::getDBPrimaryKey(),
                            Marking::getDBConvert()['M_file'] ,
                            $file,
                            File::getDBPrimaryKey());
                            
            // to reindex
            $res = array_values($res);
            
            // only one object as result
            if (count($res)>0)
                $res = $res[0]; 
                
            $this->_app->response->setBody(Marking::encodeMarking($res));
        
            $this->_app->response->setStatus($result['status']);
            if (isset($result['headers']['Content-Type']))
                header($result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetMarking failed",LogLevel::ERROR);
            $this->_app->response->setStatus(409);
            $this->_app->response->setBody(Marking::encodeMarking(new Marking()));
            $this->_app->stop();
        }  
    }
    
    /**
     * GET GetSubmissionMarking
     *
     * @param $suid a database submission identifier
     */
    public function getSubmissionMarking($suid)
    {         
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetSubmissionMarking.sql", 
                                        array("suid" => $suid));
        
        // checks the correctness of the query                                        
        if ($result['status']>=200 && $result['status']<=299){
            $query = Query::decodeQuery($result['content']);
            
            $data = $query->getResponse();

            // generates an assoc array of a file by using a defined list of 
            // its attributes
            $file = DBJson::getObjectsByAttributes($data, 
                                            File::getDBPrimaryKey(), 
                                            File::getDBConvert());
                                            
            // generates an assoc array of a marking by using a defined list of 
            // its attributes
            $marking = DBJson::getObjectsByAttributes($data, 
                                    Marking::getDBPrimaryKey(), 
                                    Marking::getDBConvert());  
                                    
            // concatenates the markings and the associated files
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $marking,
                            Marking::getDBPrimaryKey(),
                            Marking::getDBConvert()['M_file'] ,
                            $file,
                            File::getDBPrimaryKey());
                            
            // to reindex
            $res = array_values($res);
            
            // only one object as result
            if (count($res)>0)
                $res = $res[0]; 
                
            $this->_app->response->setBody(Marking::encodeMarking($res));
        
            $this->_app->response->setStatus($result['status']);
            if (isset($result['headers']['Content-Type']))
                header($result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetSubmissionMarking failed",LogLevel::ERROR);
            $this->_app->response->setStatus(409);
            $this->_app->response->setBody(Marking::encodeMarking(new Marking()));
            $this->_app->stop();
        }   
    }
    
   /**
     * GET GetExerciseMarkings
     *
     * @param $eid a database exercise identifier
     */
    public function getExerciseMarkings($eid)
    {         
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetExerciseMarkings.sql", 
                                        array('eid' => $eid));
        
        // checks the correctness of the query                                        
        if ($result['status']>=200 && $result['status']<=299){
            $query = Query::decodeQuery($result['content']);
            
            $data = $query->getResponse();

            // generates an assoc array of files by using a defined list of 
            // its attributes
            $files = DBJson::getObjectsByAttributes($data, 
                                            File::getDBPrimaryKey(), 
                                            File::getDBConvert());
                                            
            // generates an assoc array of markings by using a defined list of 
            // its attributes
            $markings = DBJson::getObjectsByAttributes($data, 
                                    Marking::getDBPrimaryKey(), 
                                    Marking::getDBConvert());  
                                    
            // concatenates the markings and the associated files
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $markings,
                            Marking::getDBPrimaryKey(),
                            Marking::getDBConvert()['M_file'] ,
                            $files,
                            File::getDBPrimaryKey());
                            
            // to reindex
            $res = array_values($res); 
            
            $this->_app->response->setBody(Marking::encodeMarking($res));
        
            $this->_app->response->setStatus($result['status']);
            if (isset($result['headers']['Content-Type']))
                header($result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetExerciseMarkings failed",LogLevel::ERROR);
            $this->_app->response->setStatus(409);
            $this->_app->response->setBody(Marking::encodeMarking(new Marking()));
            $this->_app->stop();
        }
    }
    
    /**
     * GET GetSheetMarkings
     *
     * @param $esid a database exercise sheet identifier
     */
    public function getSheetMarkings($esid)
    {         
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetSheetMarkings.sql", 
                                        array('esid' => $esid));
        
        // checks the correctness of the query                                        
        if ($result['status']>=200 && $result['status']<=299){
            $query = Query::decodeQuery($result['content']);
            
            $data = $query->getResponse();

            // generates an assoc array of files by using a defined list of 
            // its attributes
            $files = DBJson::getObjectsByAttributes($data, 
                                            File::getDBPrimaryKey(), 
                                            File::getDBConvert());
                                            
            // generates an assoc array of markings by using a defined list of 
            // its attributes
            $markings = DBJson::getObjectsByAttributes($data, 
                                    Marking::getDBPrimaryKey(), 
                                    Marking::getDBConvert());  
                                    
            // concatenates the markings and the associated files
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $markings,
                            Marking::getDBPrimaryKey(),
                            Marking::getDBConvert()['M_file'] ,
                            $files,
                            File::getDBPrimaryKey());
                            
            // to reindex
            $res = array_values($res); 
            
            $this->_app->response->setBody(Marking::encodeMarking($res));
        
            $this->_app->response->setStatus($result['status']);
            if (isset($result['headers']['Content-Type']))
                header($result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetSheetMarkings failed",LogLevel::ERROR);
            $this->_app->response->setStatus(409);
            $this->_app->response->setBody(Marking::encodeMarking(new Marking()));
            $this->_app->stop();
        }  
    }
    
    /**
     * GET GetUserGroupMarkings
     *
     * @param $esid a database exercise sheet identifier
     * @param $userid a database user identifier
     */
    public function getUserGroupMarkings($esid,$userid)
    {         
        // starts a query, by using a given file
        $result = DBRequest::getRoutedSqlFile($this->query, 
                                        "Sql/GetUserGroupMarkings.sql", 
                                        array('esid' => $esid,
                                            'userid' => $userid));
        
        // checks the correctness of the query                                        
        if ($result['status']>=200 && $result['status']<=299){
            $query = Query::decodeQuery($result['content']);
            
            $data = $query->getResponse();

            // generates an assoc array of files by using a defined list of 
            // its attributes
            $files = DBJson::getObjectsByAttributes($data, 
                                            File::getDBPrimaryKey(), 
                                            File::getDBConvert());
                                            
            // generates an assoc array of markings by using a defined list of 
            // its attributes
            $markings = DBJson::getObjectsByAttributes($data, 
                                    Marking::getDBPrimaryKey(), 
                                    Marking::getDBConvert());  
                                    
            // concatenates the markings and the associated files
            $res = DBJson::concatObjectListsSingleResult($data, 
                            $markings,
                            Marking::getDBPrimaryKey(),
                            Marking::getDBConvert()['M_file'] ,
                            $files,
                            File::getDBPrimaryKey());
                            
            // to reindex
            $res = array_values($res); 
            
            $this->_app->response->setBody(Marking::encodeMarking($res));
        
            $this->_app->response->setStatus($result['status']);
            if (isset($result['headers']['Content-Type']))
                header($result['headers']['Content-Type']);
                
        } else{
            Logger::Log("GET GetUserGroupMarkings failed",LogLevel::ERROR);
            $this->_app->response->setStatus(409);
            $this->_app->response->setBody(Marking::encodeMarking(new Marking()));
            $this->_app->stop();
        }    
    }
}
?>