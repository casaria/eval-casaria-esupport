<?php 
class user{
private $id='';
private $first_name='';
private $last_name='';
private $user_name='';
private $email='';
private $pager_email='';
private $password='';
private $office='';
private $phone='';
private $user='';
private $supporter='';
private $admin='';
private $theme='';
private $msn='';
private $yahoo='';
private $icq='';
private $lastactive='';
private $language='';
private $time_offset='';
public function __construct(){}
public function setid($id) {$this->id=$id;}
public function getid(){return $this->id;}
public function setfirst_name($first_name) {$this->first_name=$first_name;}
public function getfirst_name(){return $this->first_name;}
public function setlast_name($last_name) {$this->last_name=$last_name;}
public function getlast_name(){return $this->last_name;}
public function setuser_name($user_name) {$this->user_name=$user_name;}
public function getuser_name(){return $this->user_name;}
public function setemail($email) {$this->email=$email;}
public function getemail(){return $this->email;}
public function setpager_email($pager_email) {$this->pager_email=$pager_email;}
public function getpager_email(){return $this->pager_email;}
public function setpassword($password) {$this->password=$password;}
public function getpassword(){return $this->password;}
public function setoffice($office) {$this->office=$office;}
public function getoffice(){return $this->office;}
public function setphone($phone) {$this->phone=$phone;}
public function getphone(){return $this->phone;}
public function setuser($user) {$this->user=$user;}
public function getuser(){return $this->user;}
public function setsupporter($supporter) {$this->supporter=$supporter;}
public function getsupporter(){return $this->supporter;}
public function setadmin($admin) {$this->admin=$admin;}
public function getadmin(){return $this->admin;}
public function settheme($theme) {$this->theme=$theme;}
public function gettheme(){return $this->theme;}
public function setmsn($msn) {$this->msn=$msn;}
public function getmsn(){return $this->msn;}
public function setyahoo($yahoo) {$this->yahoo=$yahoo;}
public function getyahoo(){return $this->yahoo;}
public function seticq($icq) {$this->icq=$icq;}
public function geticq(){return $this->icq;}
public function setlastactive($lastactive) {$this->lastactive=$lastactive;}
public function getlastactive(){return $this->lastactive;}
public function setlanguage($language) {$this->language=$language;}
public function getlanguage(){return $this->language;}
public function settime_offset($time_offset) {$this->time_offset=$time_offset;}
public function gettime_offset(){return $this->time_offset;}
public function load(){
$r=mysql_query("SELECT * FROM
                 users WHERE id='$this->id'");
$row=mysql_fetch_array($r,MYSQL_ASSOC);
$this->first_name=$row['first_name'];
$this->last_name=$row['last_name'];
$this->user_name=$row['user_name'];
$this->email=$row['email'];
$this->pager_email=$row['pager_email'];
$this->password=$row['password'];
$this->office=$row['office'];
$this->phone=$row['phone'];
$this->user=$row['user'];
$this->supporter=$row['supporter'];
$this->admin=$row['admin'];
$this->theme=$row['theme'];
$this->msn=$row['msn'];
$this->yahoo=$row['yahoo'];
$this->icq=$row['icq'];
$this->lastactive=$row['lastactive'];
$this->language=$row['language'];
$this->time_offset=$row['time_offset'];
return $row;
}
public function submit(){mysql_query("INSERT INTO users SET first_name='$this->first_name',last_name='$this->last_name',user_name='$this->user_name',email='$this->email',pager_email='$this->pager_email',password='$this->password',office='$this->office',phone='$this->phone',user='$this->user',supporter='$this->supporter',admin='$this->admin',theme='$this->theme',msn='$this->msn',yahoo='$this->yahoo',icq='$this->icq',lastactive='$this->lastactive',language='$this->language',time_offset='$this->time_offset'");$this->id=mysql_insert_id();}public function update(){mysql_query("UPDATE users SET first_name='$this->first_name',last_name='$this->last_name',user_name='$this->user_name',email='$this->email',pager_email='$this->pager_email',password='$this->password',office='$this->office',phone='$this->phone',user='$this->user',supporter='$this->supporter',admin='$this->admin',theme='$this->theme',msn='$this->msn',yahoo='$this->yahoo',icq='$this->icq',lastactive='$this->lastactive',language='$this->language',time_offset='$this->time_offset' WHERE id='$this->id'");}public function delete(){mysql_query("DELETE FROM users WHERE id='$this->id'");}}?>