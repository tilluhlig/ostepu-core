<?php 


/**
 * @file DBCourseStatus.php contains the DBCourseStatus class
 *
 * @author Till Uhlig
 * @author Felix Schmidt
 * @example DB/DBCourseStatus/CourseStatusSample.json
 * @date 2013-2014
 */

include_once ( dirname(__FILE__) . '/../../Assistants/Model.php' );

/**
 * A class, to abstract the "CourseStatus" table from database
 */
class DBCourseStatus
{

    /**
     * REST actions
     *
     * This function contains the REST actions with the assignments to
     * the functions.
     *
     * @param Component $conf component data
     */
    private $_component = null;
    public function __construct( )
    {
        $component = new Model('coursestatus', dirname(__FILE__), $this);
        $this->_component=$component;
        $component->run();
    }

    /**
     * Edits the course status of a user in a specific course.
     *
     * Called when this component receives an HTTP PUT request to
     * /coursestatus/course/$courseid/user/$userid(/).
     * The request body should contain a JSON object representing the user's new
     * course status.
     *
     * @param int $courseid The id of the course.
     * @param int $userid The id of the user whose status is being updated.
     */
    public function editMemberRight( $callName, $input, $params = array() )
    {
        return $this->_component->callSqlTemplate('out',dirname(__FILE__).'/Sql/EditMemberRight.sql',array_merge($params,array( 'values' => $input->getCourseStatusInsertData( ))),201,'Model::isCreated',array(new User()),'Model::isProblem',array(new User()));
    }

    /**
     * Deletes the course status of a user in a specific course.
     *
     * Called when this component receives an HTTP DELETE request to
     * /coursestatus/course/$courseid/user/$userid(/).
     *
     * @param int $courseid The id of the course.
     * @param int $userid The id of the user whose status is being deleted.
     */
    public function removeCourseMember($callName, $input, $params = array())
    {
        return $this->_component->callSqlTemplate('out',dirname(__FILE__).'/Sql/RemoveCourseMember.sql',array_merge($params,array()),201,'Model::isCreated',array(new User()),'Model::isProblem',array(new User()));  
    }

    /**
     * Adds a course status to a user in a specific course.
     *
     * Called when this component receives an HTTP POST request to
     * /coursestatus(/).
     * The request body should contain a JSON object representing the user's
     * course status.
     */
    public function addCourseMember( $callName, $input, $params = array() )
    {
        $positive = function($input) {
            // sets the new auto-increment id
            $obj = new User( );
            //$obj->setId( $input[0]->getInsertId( ) );
            return array("status"=>201,"content"=>$obj);
        };
        return $this->_component->callSqlTemplate('out',dirname(__FILE__).'/Sql/AddCourseMember.sql',array( 'values' => $input->getCourseStatusInsertData( )),201,$positive,array(),'Model::isProblem',array(new User()));
    }

    public function get( $functionName, $linkName, $params=array(),$singleResult = false, $checkSession = true )
    {
        $positive = function($input, $singleResult) {
            //$input = $input[count($input)-1];
            $result = Model::isEmpty();$result['content']=array();
            foreach ($input as $inp){
                if ( $inp->getNumRows( ) > 0 ){
                    // extract user data from db answer
                    $result['content'] = array_merge($result['content'], User::ExtractUser( $inp->getResponse( ), $singleResult));
                    $result['status'] = 200;
                }
            }
            return $result;
        };
        
        $params = DBJson::mysql_real_escape_string( $params );
        return $this->_component->call($linkName, $params, '', 200, $positive, array($singleResult), 'Model::isProblem', array(), 'Query');
    }

    public function getMatch($callName, $input, $params = array())
    {
        return $this->get($callName,$callName,$params);
    }
    public function getMatchSingle($callName, $input, $params = array())
    {
        return $this->get($callName,$callName,$params,true,false);
    }
    
    /**
     * Removes the component from the platform
     *
     * Called when this component receives an HTTP DELETE request to
     * /platform.
     */
    public function deletePlatform( $callName, $input, $params = array())
    {
        return $this->_component->callSqlTemplate('out2',dirname(__FILE__).'/Sql/DeletePlatform.sql',array(),200,'Model::isCreated',array(new Platform()),'Model::isProblem',array(new Platform()),false);
    }
    
    /**
     * Adds the component to the platform
     *
     * Called when this component receives an HTTP POST request to
     * /platform.
     */
    public function addPlatform( $callName, $input, $params = array())
    {
        return $this->_component->callSqlTemplate('out2',dirname(__FILE__).'/Sql/AddPlatform.sql',array('object' => $input),200,'Model::isCreated',array(new Platform()),'Model::isProblem',array(new Platform()),false);
    }
}

 
?>