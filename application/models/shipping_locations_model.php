<?php
class Shipping_locations_model extends CI_Model {

private $primary_key='location_number';
private $table_name='shipping_locations';

function __construct(){
	parent::__construct();
}
	function get_paged_list($limit=10,$offset=0,
	$order_column='',$order_type='asc')
	{
                $nama='';
                if(isset($_GET['location_number'])){
                    $nama=$_GET['location_number'];
                }
                if($nama!='')$this->db->where("location_number like '%$nama%'");

		if (empty($order_column)||empty($order_type))
		$this->db->order_by($this->primary_key,'asc');
		else
		$this->db->order_by($order_column,$order_type);
		return $this->db->get($this->table_name,$limit,$offset);
	}
	function count_all(){
		return $this->db->count_all($this->table_name);
	}
	function get_by_id($id){
		$this->db->where($this->primary_key,$id);
		return $this->db->get($this->table_name);
	}
	function save($data){
		$this->db->insert($this->table_name,$data);
		return $this->db->insert_id();
	}
	function update($id,$data){
		$this->db->where($this->primary_key,$id);
		$this->db->update($this->table_name,$data);
	}
	function delete($id){
		$this->db->where($this->primary_key,$id);
		$this->db->delete($this->table_name);
	}
        function select_list(){
                $query=$this->db->query("select location_number from ".$this->table_name);
                $ret=array();
                $ret['']='- Select -';
                foreach ($query->result() as $row)
                {
                        $ret[$row->location_number]=$row->location_number;
                }		 
                return $ret;
        }
}
