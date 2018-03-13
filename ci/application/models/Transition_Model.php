<?php
	class Transition_Model extends CI_Model{
		public function __construct(){
			parent::__construct();
			$this->load->database();
		}
        public function insert_txn_id($data){
			$sql="insert into Reservation (Order_Num,Order_Date,User_Email,Button_id,Status) values (?,NOW(),?,?,?);";
			return $this->db->query($sql,$data);
		}
		
		public function get_lastid(){
			echo $this->db->insert_id().'fghjkl';
		}
    }
?>     
        
        
