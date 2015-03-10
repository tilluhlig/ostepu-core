<?php 


/**
 * @file Setting.php contains the Setting class
 */

include_once ( dirname( __FILE__ ) . '/Object.php' );

/**
 * the Setting structure
 *
 * @author Till Uhlig
 * @date 2013-2014
 */
class Setting extends Object implements JsonSerializable
{

    /**
     * @var string $name db name of the Setting
     */
    private $name = null;

    /**
     * the $name getter
     *
     * @return the value of $name
     */
    public function getName( )
    {
        return $self::getNameFromSettingName($this->name);
    }

    /**
     * the $name setter
     *
     * @param string $value the new value for $name
     */
    public function setName( $value = null )
    {
        $this->name = $value;
    }
    
    /**
     * @var string $id db id of the Setting
     */
    private $id = null;

    /**
     * the $id getter
     *
     * @return the value of $id
     */
    public function getId( )
    {
        return $this->id;
    }

    /**
     * the $id setter
     *
     * @param string $value the new value for $id
     */
    public function setId( $value = null )
    {
        $this->id = $value;
    }
    
    public static function getCourseFromSettingId($Id)
    {
        $arr = explode('_',$Id);
        if (count($arr)==2){
            return $arr[0];
        }
        else
        return '';
    }
    
    public static function getIdFromSettingId($Id)
    {
        $arr = explode('_',$Id);
        if (count($arr)==2){
            return $arr[1];
        }
        else
        return $Id;
    }
    
    public function getObjectCourseFromSettingId()
    {
        return Setting::getCourseFromSettingId($this->id);
    }
    
    public function getObjectIdFromSettingId()
    {
        return Setting::getIdFromSettingId($this->id);
    }

    /**
     
     * @var string $state The id of the exercise this Setting belongs to.
     */
    private $state = null;

    /**
     * the $state getter
     *
     * @return the value of $state
     */
    public function getState( )
    {
        return $this->state;
    }
    
    /**
     * the $state setter
     *
     * @param string $value the new value for $state
     */
    public function setState( $value = null )
    {
        $this->state = $value;
    }
    
    /**
     * Creates an Setting object, for database post(insert) and put(update).
     * Not needed attributes can be set to null.
     *
     * @param string $settingName The id of the Setting.
     * @param string $state The id of the exercise.
     *
     * @return an Setting object.
     */
    public static function createSetting( 
                                            $settingId,
                                            $settingName,
                                            $state
                                            )
    {
        return new Setting( array( 
                                     'id' => $settingId,
                                     'name' => $settingName,
                                     'state' => $state
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
                     'SET_id' => 'id',
                     'SET_name' => 'name',
                     'SET_state' => 'state'
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
        
        if ( $this->id != null )
            $this->addInsertData( 
                                 $values,
                                 'SET_id',
                                 DBJson::mysql_real_escape_string( $this->id )
                                 );
        if ( $this->name != null )
            $this->addInsertData( 
                                 $values,
                                 'SET_name',
                                 DBJson::mysql_real_escape_string( $this->name )
                                 );
        if ( $this->state != null )
            $this->addInsertData( 
                                 $values,
                                 'SET_state',
                                 DBJson::mysql_real_escape_string( $this->state )
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
        return'SET_id';
    }

    /**
     * the constructor
     *
     * @param $data an assoc array with the object informations
     */
    public function __construct( $data = array( ) )
    {
        foreach ( $data AS $key => $value ){
            if ( isset( $key ) ){
                $func = 'set' . strtoupper($key[0]).substr($key,1);
                $methodVariable = array($this, $func);
                if (is_callable($methodVariable)){
                    $this->$func($value);
                } else
                    $this->{$key} = $value;
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
    public static function encodeSetting( $data )
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
    public static function decodeSetting( 
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
                $result[] = new Setting( $value );
            }
            return $result;
            
        } else 
            return new Setting( $data );
    }

    /**
     * the json serialize function
     *
     * @return an array to serialize the object
     */
    public function jsonSerialize( )
    {
        $list = array( );
        if ( $this->id !== null )
            $list['id'] = $this->id;
        if ( $this->name !== null )
            $list['name'] = $this->name;
        if ( $this->state !== null )
            $list['state'] = $this->state;
        return array_merge($list,parent::jsonSerialize( ));
    }

    public static function ExtractSetting( 
                                             $data,
                                             $singleResult = false,
                                             $SettingExtension = '',
                                             $isResult = true
                                             )
    {

        // generates an assoc array of files by using a defined list of
        // its attributes
        $files = DBJson::getObjectsByAttributes( 
                                                $data,
                                                File::getDBPrimaryKey( ),
                                                File::getDBConvert( ),
                                                $FileExtension
                                                );

        // generates an assoc array of Settings by using a defined list of
        // its attributes
        $Settings = DBJson::getObjectsByAttributes( 
                                                      $data,
                                                      Setting::getDBPrimaryKey( ),
                                                      Setting::getDBConvert( ),
                                                      $SettingExtension
                                                      );

        if ($isResult){
            // to reindex
            $res = array_values( $res );

            if ( $singleResult == true ){

                // only one object as result
                if ( count( $res ) > 0 )
                    $res = $res[0];
            }
        }

        return $res;
    }
}

 
?>