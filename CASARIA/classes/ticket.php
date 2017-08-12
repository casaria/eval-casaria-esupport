<?php 
class ticket{
private $id='';
private $create_date='';
private $groupid='';
private $ugroupid='';
private $supporter='';
private $supporter_id='';
private $priority='';
private $status='';
private $BILLING_STATUS='';
private $user='';
private $email='';
private $office='';
private $phone='';
private $equipment='';
private $category='';
private $platform='';
private $short='';
private $description='';
private $update_log='';
private $survey='';
private $lastupdate='';
private $emailgroup='';
private $emailstatuschange='';
private $emailcc='';
public function __construct(){}
public function setid($id) {$this->id=$id;}
public function getid(){return $this->id;}
public function setcreate_date($create_date) {$this->create_date=$create_date;}
public function getcreate_date(){return $this->create_date;}
public function setgroupid($groupid) {$this->groupid=$groupid;}
public function getgroupid(){return $this->groupid;}
public function setugroupid($ugroupid) {$this->ugroupid=$ugroupid;}
public function getugroupid(){return $this->ugroupid;}
public function setsupporter($supporter) {$this->supporter=$supporter;}
public function getsupporter(){return $this->supporter;}
public function setsupporter_id($supporter_id) {$this->supporter_id=$supporter_id;}
public function getsupporter_id(){return $this->supporter_id;}
public function setpriority($priority) {$this->priority=$priority;}
public function getpriority(){return $this->priority;}
public function setstatus($status) {$this->status=$status;}
public function getstatus(){return $this->status;}
public function setBILLING_STATUS($BILLING_STATUS) {$this->BILLING_STATUS=$BILLING_STATUS;}
public function getBILLING_STATUS(){return $this->BILLING_STATUS;}
public function setuser($user) {$this->user=$user;}
public function getuser(){return $this->user;}
public function setemail($email) {$this->email=$email;}
public function getemail(){return $this->email;}
public function setoffice($office) {$this->office=$office;}
public function getoffice(){return $this->office;}
public function setphone($phone) {$this->phone=$phone;}
public function getphone(){return $this->phone;}
public function setequipment($equipment) {$this->equipment=$equipment;}
public function getequipment(){return $this->equipment;}
public function setcategory($category) {$this->category=$category;}
public function getcategory(){return $this->category;}
public function setplatform($platform) {$this->platform=$platform;}
public function getplatform(){return $this->platform;}
public function setshort($short) {$this->short=$short;}
public function getshort(){return $this->short;}
public function setdescription($description) {$this->description=$description;}
public function getdescription(){return $this->description;}
public function setupdate_log($update_log) {$this->update_log=$update_log;}
public function getupdate_log(){return $this->update_log;}
public function setsurvey($survey) {$this->survey=$survey;}
public function getsurvey(){return $this->survey;}
public function setlastupdate($lastupdate) {$this->lastupdate=$lastupdate;}
public function getlastupdate(){return $this->lastupdate;}
public function setemailgroup($emailgroup) {$this->emailgroup=$emailgroup;}
public function getemailgroup(){return $this->emailgroup;}
public function setemailstatuschange($emailstatuschange) {$this->emailstatuschange=$emailstatuschange;}
public function getemailstatuschange(){return $this->emailstatuschange;}
public function setemailcc($emailcc) {$this->emailcc=$emailcc;}
public function getemailcc(){return $this->emailcc;}
public function load(){
$r=mysql_query("SELECT * FROM
                 tickets WHERE id='$this->id'");
$row=mysql_fetch_array($r,MYSQL_ASSOC);
$this->create_date=$row['create_date'];
$this->groupid=$row['groupid'];
$this->ugroupid=$row['ugroupid'];
$this->supporter=$row['supporter'];
$this->supporter_id=$row['supporter_id'];
$this->priority=$row['priority'];
$this->status=$row['status'];
$this->BILLING_STATUS=$row['BILLING_STATUS'];
$this->user=$row['user'];
$this->email=$row['email'];
$this->office=$row['office'];
$this->phone=$row['phone'];
$this->equipment=$row['equipment'];
$this->category=$row['category'];
$this->platform=$row['platform'];
$this->short=$row['short'];
$this->description=$row['description'];
$this->update_log=$row['update_log'];
$this->survey=$row['survey'];
$this->lastupdate=$row['lastupdate'];
$this->emailgroup=$row['emailgroup'];
$this->emailstatuschange=$row['emailstatuschange'];
$this->emailcc=$row['emailcc'];
return $row;
}
public function submit(){mysql_query("INSERT INTO tickets SET create_date='$this->create_date',groupid='$this->groupid',ugroupid='$this->ugroupid',supporter='$this->supporter',supporter_id='$this->supporter_id',priority='$this->priority',status='$this->status',BILLING_STATUS='$this->BILLING_STATUS',user='$this->user',email='$this->email',office='$this->office',phone='$this->phone',equipment='$this->equipment',category='$this->category',platform='$this->platform',short='$this->short',description='$this->description',update_log='$this->update_log',survey='$this->survey',lastupdate='$this->lastupdate',emailgroup='$this->emailgroup',emailstatuschange='$this->emailstatuschange',emailcc='$this->emailcc'");$this->id=mysql_insert_id();}public function update(){mysql_query("UPDATE tickets SET create_date='$this->create_date',groupid='$this->groupid',ugroupid='$this->ugroupid',supporter='$this->supporter',supporter_id='$this->supporter_id',priority='$this->priority',status='$this->status',BILLING_STATUS='$this->BILLING_STATUS',user='$this->user',email='$this->email',office='$this->office',phone='$this->phone',equipment='$this->equipment',category='$this->category',platform='$this->platform',short='$this->short',description='$this->description',update_log='$this->update_log',survey='$this->survey',lastupdate='$this->lastupdate',emailgroup='$this->emailgroup',emailstatuschange='$this->emailstatuschange',emailcc='$this->emailcc' WHERE id='$this->id'");}public function delete(){mysql_query("DELETE FROM tickets WHERE id='$this->id'");}}?>