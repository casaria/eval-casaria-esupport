<?php 
class tequipment{
private $id='';
private $platform_id='';
private $rank='';
private $equipcat='';
private $groupid='';
private $short='';
private $make='';
private $model='';
private $Serialnum='';
private $suplementary_data='';
private $runtimemiles='';
private $dt_rtm_inception='';
private $rtm_update='';
private $dt_rtm_updae='';
private $mfctdate='';
private $activation='';
private $state='';
public function __construct(){}
public function setid($id) {$this->id=$id;}
public function getid(){return $this->id;}
public function setplatform_id($platform_id) {$this->platform_id=$platform_id;}
public function getplatform_id(){return $this->platform_id;}
public function setrank($rank) {$this->rank=$rank;}
public function getrank(){return $this->rank;}
public function setequipcat($equipcat) {$this->equipcat=$equipcat;}
public function getequipcat(){return $this->equipcat;}
public function setgroupid($groupid) {$this->groupid=$groupid;}
public function getgroupid(){return $this->groupid;}
public function setshort($short) {$this->short=$short;}
public function getshort(){return $this->short;}
public function setmake($make) {$this->make=$make;}
public function getmake(){return $this->make;}
public function setmodel($model) {$this->model=$model;}
public function getmodel(){return $this->model;}
public function setSerialnum($Serialnum) {$this->Serialnum=$Serialnum;}
public function getSerialnum(){return $this->Serialnum;}
public function setsuplementary_data($suplementary_data) {$this->suplementary_data=$suplementary_data;}
public function getsuplementary_data(){return $this->suplementary_data;}
public function setruntimemiles($runtimemiles) {$this->runtimemiles=$runtimemiles;}
public function getruntimemiles(){return $this->runtimemiles;}
public function setdt_rtm_inception($dt_rtm_inception) {$this->dt_rtm_inception=$dt_rtm_inception;}
public function getdt_rtm_inception(){return $this->dt_rtm_inception;}
public function setrtm_update($rtm_update) {$this->rtm_update=$rtm_update;}
public function getrtm_update(){return $this->rtm_update;}
public function setdt_rtm_updae($dt_rtm_updae) {$this->dt_rtm_updae=$dt_rtm_updae;}
public function getdt_rtm_updae(){return $this->dt_rtm_updae;}
public function setmfctdate($mfctdate) {$this->mfctdate=$mfctdate;}
public function getmfctdate(){return $this->mfctdate;}
public function setactivation($activation) {$this->activation=$activation;}
public function getactivation(){return $this->activation;}
public function setstate($state) {$this->state=$state;}
public function getstate(){return $this->state;}
public function load(){
$r=mysql_query("SELECT * FROM
                 tequipment WHERE id='$this->id'");
$row=mysql_fetch_array($r,MYSQL_ASSOC);
$this->platform_id=$row['platform_id'];
$this->rank=$row['rank'];
$this->equipcat=$row['equipcat'];
$this->groupid=$row['groupid'];
$this->short=$row['short'];
$this->make=$row['make'];
$this->model=$row['model'];
$this->Serialnum=$row['Serialnum'];
$this->suplementary_data=$row['suplementary_data'];
$this->runtimemiles=$row['runtimemiles'];
$this->dt_rtm_inception=$row['dt_rtm_inception'];
$this->rtm_update=$row['rtm_update'];
$this->dt_rtm_updae=$row['dt_rtm_updae'];
$this->mfctdate=$row['mfctdate'];
$this->activation=$row['activation'];
$this->state=$row['state'];
return $row;
}
public function submit(){mysql_query("INSERT INTO tequipment SET platform_id='$this->platform_id',rank='$this->rank',equipcat='$this->equipcat',groupid='$this->groupid',short='$this->short',make='$this->make',model='$this->model',Serialnum='$this->Serialnum',suplementary_data='$this->suplementary_data',runtimemiles='$this->runtimemiles',dt_rtm_inception='$this->dt_rtm_inception',rtm_update='$this->rtm_update',dt_rtm_updae='$this->dt_rtm_updae',mfctdate='$this->mfctdate',activation='$this->activation',state='$this->state'");$this->id=mysql_insert_id();}public function update(){mysql_query("UPDATE tequipment SET platform_id='$this->platform_id',rank='$this->rank',equipcat='$this->equipcat',groupid='$this->groupid',short='$this->short',make='$this->make',model='$this->model',Serialnum='$this->Serialnum',suplementary_data='$this->suplementary_data',runtimemiles='$this->runtimemiles',dt_rtm_inception='$this->dt_rtm_inception',rtm_update='$this->rtm_update',dt_rtm_updae='$this->dt_rtm_updae',mfctdate='$this->mfctdate',activation='$this->activation',state='$this->state' WHERE id='$this->id'");}public function delete(){mysql_query("DELETE FROM tequipment WHERE id='$this->id'");}}?>