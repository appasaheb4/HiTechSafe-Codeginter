<?php

class MY_Model extends CI_Model {
    //put your code here
    
     public function getAdminData() {
        $q = $this->db->get("tbl_login");     
        return $q->result_array();   
    }
}
