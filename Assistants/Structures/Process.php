<?php 


/**
 * @file Process.php contains the Process class
 */

/**
 * the Process structure
 *
 * @author Till Uhlig
 */
class Process extends Object implements JsonSerializable
{

    /**
     * @var string $id  a string that identifies the exercise
     */
    private $exerciseId = null;
    public function getExerciseId( )
    {
        return $this->exerciseId;
    }
    public function setExerciseId( $value )
    {
        $this->exerciseId = $value;
    }

    private $processId = null;
    public function getProcessId( )
    {
        return $this->processId;
    }
    public function setProcessId( $value )
    {
        $this->processId = $value;
    }
    
    public static function getCourseFromProcessId($id)
    {
        $arr = explode('_',$processId);
        if (count($arr)==2){
            return $arr[0];
        }
        else
        return '';
    }
    
    public static function getIdFromProcessId($id)
    {
        $arr = explode('_',$processId);
        if (count($arr)==2){
            return $arr[1];
        }
        else
        return $id;
    }
    
    public function getObjectCourseFromProcessIdId()
    {
        return Form::getCourseFromProcessId($this->formId);
    }
    
    public function getObjectIdFromProcessId()
    {
        return Form::getIdFromProcessId($this->formId);
    }
    
    private $target = null;
    public function getTarget( )
    {
        return $this->target;
    }
    public function setTarget( $value )
    {
        $this->target = $value;
    }
    
    private $parameter = null;
    public function getParameter( )
    {
        return $this->parameter;
    }
    public function setParameter( $value )
    {
        $this->parameter = $value;
    }

    private $attachment = array();
    public function getAttachment( )
    {
        return $this->attachment;
    }
    public function setAttachment( $value )
    {
        $this->attachment = $value;
    } 
    
    private $workFiles = array();
    public function getWorkFiles( )
    {
        return $this->workFiles;
    }
    public function setWorkFiles( $value )
    {
        $this->workFiles = $value;
    } 
    
    private $rawSubmission = null;
    public function getRawSubmission( )
    {
        return $this->rawSubmission;
    }
    public function setRawSubmission( $value )
    {
        $this->rawSubmission = $value;
    } 
    
    private $submission = null;
    public function getSubmission( )
    {
        return $this->submission;
    }
    public function setSubmission( $value )
    {
        $this->submission = $value;
    } 
    
    private $marking = null;
    public function getMarking( )
    {
        return $this->marking;
    }
    public function setMarking( $value )
    {
        $this->marking = $value;
    }

    /**
     * Creates an Course object, for database post(insert) and put(update).
     * Not needed attributes can be set to null.
     *
     * @param string $courseId The id of the course.
     * @param string $name The course name.
     * @param string $semester The semester.
     * @param string $defaultGroupSize The default group size.
     *
     * @return an course object
     */
    public static function createProcess( 
                                        $processId,
                                        $exerciseId,
                                        $target,
                                        $parameter
                                        )
    {
        return new Process( array( 
                                 'processId' => $processId,
                                 'exerciseId' => $exerciseId,
                                 'target' => $target,
                                 'parameter' => $parameter
                                 ) );
    }

    /**
     * returns an mapping array to convert between database and structure
     *
     * @return the mapping array
     */
    public static function getDbConvert( )
    {
        return array( 
                     'PRO_id' => 'processId',
                     'E_id' => 'exerciseId',
                     'CO_id_target' => 'target',
                     'PRO_parameter' => 'parameter'
                     );
    }

    /**
     * converts an object to insert/update data
     *
     * @return a comma separated string e.g. "a=1,b=2"
     */
    public function getInsertData( )
    {
        $values = '';

        if ( $this->processId != null )
            $this->addInsertData( 
                                 $values,
                                 'PRO_id',
                                 DBJson::mysql_real_escape_string( $this->processId )
                                 );
        if ( $this->exerciseId != null )
            $this->addInsertData( 
                                 $values,
                                 'E_id',
                                 DBJson::mysql_real_escape_string( $this->exerciseId )
                                 );
        if ( $this->target != null )
            $this->addInsertData( 
                                 $values,
                                 'CO_id_target',
                                 DBJson::mysql_real_escape_string( $this->target )
                                 );
        if ( $this->parameter != null )
            $this->addInsertData( 
                                 $values,
                                 'PRO_parameter',
                                 DBJson::mysql_real_escape_string( $this->parameter )
                                 );

        if ( $values != '' ){
            $values = substr( 
                             $values,
                             1
                             );
        }
        return $values;
    }

    /**
     * returns a sting/string[] of the database primary key/keys
     *
     * @return the primary key/keys
     */
    public static function getDbPrimaryKey( )
    {
        return'PRO_id';
    }

    /**
     * the constructor
     *
     * @param $data an assoc array with the object informations
     */
    public function __construct( $data = array( ) )
    {
        if ( $data == null )
            $data = array( );

        foreach ( $data AS $key => $value ){
            if ( isset( $key ) ){
                if ( $key == 'attachments' ){
                    $this->{
                        $key
                        
                    } = File::decodeFile( 
                                                           $value,
                                                           false
                                                           );
                    
                } else 
                if ( $key == 'workFiles' ){
                    $this->{
                        $key
                        
                    } = File::decodeFile( 
                                                           $value,
                                                           false
                                                           );
                    
                } else 
                if ( $key == 'target' ){
                    $this->{
                        $key
                        
                    } = Component::decodeComponent( 
                                                           $value,
                                                           false
                                                           );
                    
                } else
                if ( $key == 'submission' ){
                    $this->{
                        $key
                        
                    } = Submission::decodeSubmission( 
                                                           $value,
                                                           false
                                                           );
                    
                }  else
                if ( $key == 'rawSubmission' ){
                    $this->{
                        $key
                        
                    } = Submission::decodeSubmission( 
                                                           $value,
                                                           false
                                                           );
                    
                }else
                if ( $key == 'marking' ){
                    $this->{
                        $key
                        
                    } = Marking::decodeMarking( 
                                                           $value,
                                                           false
                                                           );
                    
                } else
                    $this->{
                    $key
                    
                } = $value;
            }
        }
    }

    /**
     * encodes an object to json
     *
     * @param $data the object
     *
     * @return the json encoded object
     */
    public static function encodeProcess( $data )
    {
        return json_encode( $data );
    }

    /**
     * decodes $data to an object
     *
     * @param string $data json encoded data (decode=true)
     * or json decoded data (decode=false)
     * @param bool $decode specifies whether the data must be decoded
     *
     * @return the object
     */
    public static function decodeProcess( 
                                        $data,
                                        $decode = true
                                        )
    {
        if ( $decode && 
             $data == null )
            $data = '{}';

        if ( $decode )
            $data = json_decode( $data );
        if ( is_array( $data ) ){
            $result = array( );
            foreach ( $data AS $key => $value ){
                $result[] = new Process( $value );
            }
            return $result;
            
        } else 
            return new Process( $data );
    }

    /**
     * the json serialize function
     */
    public function jsonSerialize( )
    {
        $list = array( );
        if ( $this->processId !== null )
            $list['processId'] = $this->processId;
        if ( $this->exerciseId !== null )
            $list['exercieId'] = $this->exerciseId;
        if ( $this->target !== null )
            $list['target'] = $this->target;
        if ( $this->parameter !== null )
            $list['parameter'] = $this->parameter;
        if ( $this->attachments !== null && $this->attachments !== array( ) )
            $list['attachments'] = $this->attachments;
        if ( $this->workFiles !== null && $this->workFiles !== array( ) )
            $list['workFiles'] = $this->workFiles;    
        if ( $this->submission !== null )
            $list['submission'] = $this->submission;
        if ( $this->workingSubmission !== null )
            $list['rawSubmission'] = $this->rawSubmission;
        if ( $this->marking !== null )
            $list['marking'] = $this->marking;    
        return $list;
    }

    public static function ExtractProcess( 
                                         $data,
                                         $singleResult = false
                                         )
    {

        /*// generates an assoc array of courses by using a defined list of
        // its attributes
        $courses = DBJson::getObjectsByAttributes( 
                                                  $data,
                                                  Course::getDBPrimaryKey( ),
                                                  Course::getDBConvert( )
                                                  );

        // generates an assoc array of exercise sheets by using a defined list of
        // its attributes
        $exerciseSheets = DBJson::getObjectsByAttributes( 
                                                         $data,
                                                         ExerciseSheet::getDBPrimaryKey( ),
                                                         array( ExerciseSheet::getDBPrimaryKey( ) => ExerciseSheet::getDBConvert( )[ExerciseSheet::getDBPrimaryKey( )] )
                                                         );

        // concatenates the courses and the associated exercise sheet IDs
        $res = DBJson::concatResultObjectListAsArray( 
                                                     $data,
                                                     $courses,
                                                     Course::getDBPrimaryKey( ),
                                                     Course::getDBConvert( )['C_exerciseSheets'],
                                                     $exerciseSheets,
                                                     ExerciseSheet::getDBPrimaryKey( )
                                                     );

        if ( $singleResult == true ){

            // only one object as result
            if ( count( $res ) > 0 )
                $res = $res[0];
        }

        return $res;*/
    }
}

 
?>

