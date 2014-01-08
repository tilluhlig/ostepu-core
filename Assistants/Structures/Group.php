<?php 
/**
 * 
 */
class Group extends Object implements JsonSerializable
{
    /**
     * all members of the group of the current users
     * 
     * type: User[]
     */
    private $members = array();
    public function getMembers(){
        return $this->members;
    }
    public function setMembers($value){
        $this->members = $value;
    }

    /**
     * the id of the user that is the leader of the group
     *
     * type: User
     */
    private $leader = null;
    public function getLeader(){
        return $this->leader;
    }
    public function setLeader($value){
        $this->leader = $value;
    }

    /**
     * the id of the sheet for which this group exists
     *
     * type: string
     */
    private $sheetId = null;
    public function getSheetId(){
        return $this->sheetId;
    }
    public function setSheetId($value){
        $this->sheetId = $value;
    }
    
    
    
    
    /**
     * (description)
     */  
    public static function getDBConvert(){
        return array(
           'U_member' => 'members',
           'U_leader' => 'leader',
           'ES_id' => 'sheetId',
        );
    }
    
    /**
     * (description)
     */
    public function getInsertData(){
        $values = "";
        
        if ($this->sheetId != null) $this->addInsertData($values, 'ES_id', $this->sheetId );
        if ($this->members != array()) $this->addInsertData($values, 'U_id_leader', $this->member[0]->getId());
        if ($this->leader != null) $this->addInsertData($values, 'U_id_member', $this->leader->getId());
        
        if ($values != ""){
            $values=substr($values,1);
        }
        return $values;
    } 
    
    /**
     * (description)
     */
    public static function getDBPrimaryKey(){
        return array('U_id', 'ES_id');
    }
   
    /**
     * (description)
     */
    public function __construct($data=array()) {
        foreach ($data AS $key => $value) {
             if (isset($key)){
                if ($key == 'leader' || $key == 'members'){
                    $this->{$key} = User::decodeUser($value, false);
                }
                else
                    $this->{$key} = $value;
            }
        }
    }
    
    /**
     * (description)
     */
    public static function encodeGroup($data){
        return json_encode($data);
    }
    
    /**
     * (description)
     */
    public static function decodeGroup($data){
        $data = json_decode($data);
        if (is_array($data)){
            $result = array();
            foreach ($data AS $key => $value) {
                array_push($result, new Group($value));
            }
            return $result;   
        }
        else
            return new Group($data);
    }
    
    /**
     * (description)
     */
    public function jsonSerialize() {
        return array(
            'members' => $this->members,
            'leader' => $this->leader,
            'sheetId' => $this->sheetId
        );
    }
}
?>