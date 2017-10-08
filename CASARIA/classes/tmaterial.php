<?php 
class tmaterial{
private $id='';
private $ourpartnum='';
private $partnum='';
private $short='';
private $description='';
private $cost='';
private $qty_reorder='';
private $qty_pending='';
private $fixedprice='';
private $price_cat='';
private $stock='';
private $unit='';
private $category='';
private $vendor_notes='';
private $drawingref='';
private $used_for='';
private $item='';
private $data_url='';
public function __construct(){}
public function setid($id) {$this->id=$id;}
public function getid(){return $this->id;}
public function setourpartnum($ourpartnum) {$this->ourpartnum=$ourpartnum;}
public function getourpartnum(){return $this->ourpartnum;}
public function setpartnum($partnum) {$this->partnum=$partnum;}
public function getpartnum(){return $this->partnum;}
public function setshort($short) {$this->short=$short;}
public function getshort(){return $this->short;}
public function setdescription($description) {$this->description=$description;}
public function getdescription(){return $this->description;}
public function setcost($cost) {$this->cost=$cost;}
public function getcost(){return $this->cost;}
public function setqty_reorder($qty_reorder) {$this->qty_reorder=$qty_reorder;}
public function getqty_reorder(){return $this->qty_reorder;}
public function setqty_pending($qty_pending) {$this->qty_pending=$qty_pending;}
public function getqty_pending(){return $this->qty_pending;}
public function setfixedprice($fixedprice) {$this->fixedprice=$fixedprice;}
public function getfixedprice(){return $this->fixedprice;}
public function setprice_cat($price_cat) {$this->price_cat=$price_cat;}
public function getprice_cat(){return $this->price_cat;}
public function setstock($stock) {$this->stock=$stock;}
public function getstock(){return $this->stock;}
public function setunit($unit) {$this->unit=$unit;}
public function getunit(){return $this->unit;}
public function setcategory($category) {$this->category=$category;}
public function getcategory(){return $this->category;}
public function setvendor_notes($vendor_notes) {$this->vendor_notes=$vendor_notes;}
public function getvendor_notes(){return $this->vendor_notes;}
public function setdrawingref($drawingref) {$this->drawingref=$drawingref;}
public function getdrawingref(){return $this->drawingref;}
public function setused_for($used_for) {$this->used_for=$used_for;}
public function getused_for(){return $this->used_for;}
public function setitem($item) {$this->item=$item;}
public function getitem(){return $this->item;}
public function setdata_url($data_url) {$this->data_url=$data_url;}
public function getdata_url(){return $this->data_url;}
public function load(){
$r=mysql_query("SELECT * FROM
                 tmaterial WHERE id='$this->id'");
$row=mysql_fetch_array($r,MYSQL_ASSOC);
$this->ourpartnum=$row['ourpartnum'];
$this->partnum=$row['partnum'];
$this->short=$row['short'];
$this->description=$row['description'];
$this->cost=$row['cost'];
$this->qty_reorder=$row['qty_reorder'];
$this->qty_pending=$row['qty_pending'];
$this->fixedprice=$row['fixedprice'];
$this->price_cat=$row['price_cat'];
$this->stock=$row['stock'];
$this->unit=$row['unit'];
$this->category=$row['category'];
$this->vendor_notes=$row['vendor_notes'];
$this->drawingref=$row['drawingref'];
$this->used_for=$row['used_for'];
$this->item=$row['item'];
$this->data_url=$row['data_url'];
return $row;
}
public function submit(){mysql_query("INSERT INTO tmaterial SET ourpartnum='$this->ourpartnum',partnum='$this->partnum',short='$this->short',description='$this->description',cost='$this->cost',qty_reorder='$this->qty_reorder',qty_pending='$this->qty_pending',fixedprice='$this->fixedprice',price_cat='$this->price_cat',stock='$this->stock',unit='$this->unit',category='$this->category',vendor_notes='$this->vendor_notes',drawingref='$this->drawingref',used_for='$this->used_for',item='$this->item',data_url='$this->data_url'");$this->id=mysql_insert_id();}public function update(){mysql_query("UPDATE tmaterial SET ourpartnum='$this->ourpartnum',partnum='$this->partnum',short='$this->short',description='$this->description',cost='$this->cost',qty_reorder='$this->qty_reorder',qty_pending='$this->qty_pending',fixedprice='$this->fixedprice',price_cat='$this->price_cat',stock='$this->stock',unit='$this->unit',category='$this->category',vendor_notes='$this->vendor_notes',drawingref='$this->drawingref',used_for='$this->used_for',item='$this->item',data_url='$this->data_url' WHERE id='$this->id'");}public function delete(){mysql_query("DELETE FROM tmaterial WHERE id='$this->id'");}}?>