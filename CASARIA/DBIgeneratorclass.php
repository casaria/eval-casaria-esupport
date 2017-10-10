<?php
class DBIGenerator{
    private $table;
    private $name;
    private $path;
   
    public function __construct($table, $name='default_file.php',$path='DEFAULTPATH/')
    {
        $this->table=$table;
        $this->name=$name;
        $this->path=$path;
    } 
   
    public function generate(){
        // build class header
        $lt = chr(0x0A);
   
        $str='<?php '.$lt.'class '.$this->name.'{'.$lt;
        if(!$result=mysql_query('SHOW COLUMNS FROM '.$this->table)){
            throw new Exception('Failed to run query');
        }
        // build data member declaration
        if(mysql_num_rows($result)<1){
            throw new Exception('Not available columns in table');
        }
        $methods='';
        while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
            $str.='private $'.$row['Field'].'=\'\';'.$lt;
            $methods.='public function set'.$row['Field'].'($'.$row['Field'].') {$this->'.$row['Field'].'=$'.$row['Field'].';}'.$lt;
            $methods.='public function get'.$row['Field'].'(){return $this->'.$row['Field'].';}'.$lt;
            // store field names in array
            $fields[]=$row['Field'];
        }
         // build empty constructor
        $str.='public function __construct(){}'.$lt;
         // build modifiers and accessors
        $str.=$methods;
         // build load() method
        $str.='public function load(){'.$lt.'$r=mysql_query("SELECT * FROM
                 '.$this->table.' WHERE id=\'$this->id\'");'.$lt;
        $str.='$row=mysql_fetch_array($r,MYSQL_ASSOC);';
        // set properties from data record
        foreach($fields as $field){
            $str.=($field!='id')?'$this->'.$field.'=$row[\''.$field.'\'];':'';
            $str.=$lt;
        }
                 
        $str.='return $row;'.$lt.'}'.$lt;
        // build submit() method
        $str.='public function submit(){mysql_query("INSERT INTO '.$this->table.' SET ';
        foreach($fields as $field){
            $str.=($field!='id')?$field.'=\'$this->'.$field.'\',':'';
        }
        $str.='");$this->id=mysql_insert_id();';
        $str=preg_replace("/,\"/","\"",$str).'}';
        // build update() method
        $str.='public function update(){mysql_query("UPDATE '.$this->table.' SET ';
        foreach($fields as $field){
            $str.=($field!='id')?$field.'=\'$this->'.$field.'\',':'';
        }
        $str=preg_replace("/,$/","",$str);
        $str.=' WHERE id=\'$this->id\'");}';
        // build delete() method
        $str.='public function delete(){mysql_query("DELETE FROM '.
                 $this->table.' WHERE id=\'$this->id\'");}';
        $str.='}?>';
        // open or create class file
        if(!$fp=fopen($this->path.$this->name.'.php','w')){
            throw new Exception('Failed to create class file');
        }
        // lock class file
        if(!flock($fp,LOCK_EX)){
            throw new Exception('Unable to lock class file');
        }
        // write class code to file
        if(!fwrite($fp,$str)){
            throw new Exception('Error writing to class file');
        }
        flock($fp,LOCK_UN);
        fclose($fp);
        // delete temporary variables
        unset($fp,$str,$row,$fields,$field,$methods);
    }
    public function getObject(){
        // check if class file exists
        if(!file_exists($this->path.$this->name.'.php')){
            throw new Exception('Failed to include class file');
        }
        require_once($this->path.$this->name.'.php');
        // create data access object
        return new $this->name;
    }
}



class MySQL{
    private $conId;
    private $host;
    private $user;
    private $password;
    private $database;
    private $result;
    public function __construct($options=array()){
        if(count($options)<4){
            throw new Exception('Invalid number of connectionparameters');
        }
        foreach($options as $parameter=>$value){
            if(!$value){
                throw new Exception('Invalid parameter'.$parameter);
            }
            $this->{$parameter}=$value;
        }
        $this->connectDB();
    }
    private function connectDB(){
        if(!$this->conId=mysql_connect($this->host,$this->user,$this->password)){
            throw new Exception('Error connecting to the server');
        }
        if(!mysql_select_db($this->database,$this->conId)){
            throw new Exception('Error selecting database');
        }
    }
    public function query($query){
        if(!$this->result=mysql_query($query,$this->conId)){
            throw new Exception('Error performing query'.$query);
        }
        return new Result($this,$this->result);
    }
}
// define ‘Result’ class
class Result {
    private $mysql;
    private $result;
    public function __construct(&$mysql,$result){
        $this->mysql=&$mysql;
        $this->result=$result;
    }
    public function fetchRow(){
        return mysql_fetch_assoc($this->result);
    }
    public function countRows(){
        if(!$rows=mysql_num_rows($this->result)){
            throw new Exception('Error counting rows');
        }
        return $rows;
    }
    public function countAffectedRows(){
        if(!$rows=mysql_affected_rows($this->mysql->conId)){
            throw new Exception('Error counting affected rows');
        }
        return $rows;
    }
    public function getInsertID(){
        if(!$id=mysql_insert_id($this->mysql->conId)){
            throw new Exception('Error getting ID');
        }
        return $id;
    }
    public function seekRow($row=0){
        if(!int($row)||$row<0){
            throw new Exception('Invalid result set offset');
        }
        if(!mysql_data_seek($this->result,$row)){
            throw new Exception('Error seeking data');
        }
    }
    public function fetchFormattedResult($query,$closeTag='</p>'){
        if(preg_match("/^SELECT/",$query)){
            throw new Exception('Query must begin with SELECT');
        }
        $output='';
        $opentag=str_replace('/','',$endTag);
        while($row=$this->fetchRow()){
            $output.=$openTag.$row.$closeTag;
        }
        unset($openTag,$closeTag);
        return $output;
    }
}


?>