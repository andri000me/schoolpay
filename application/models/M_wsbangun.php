<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_wsbangun extends Core_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ========================== SELECT DATA
        public function getData($cons, $table="") {
            $db2 = $this->load->database($cons, TRUE);
            $data = $db2->get($table);
            if ($data) {
                return $data->result();
            }
            else{
                return null;
            }
        }

        public function getData_by_query($cons, $sql=null){
            $db2 = $this->load->database($cons, TRUE);
            if(!empty($sql)) {
                $query = $db2->query($sql);
                $err = $db2->error();
                if($err["message"] != ""){
                    return $err["message"];
                }
                else{
                    $res = $query->result();
                    return $res;
                }
            } else {
                return null;
            }
        }

        public function getData_by_criteria($cons, $table="", $where=null, $like=null, $order = null){
            $db2 = $this->load->database($cons, TRUE);
            
            if(!is_null($where)) {
                $db2->where($where);
            } 
            if(!is_null($like)) {
                $db2->like($like);
            }
            if(!is_null($order)) {
                $db2->order_by($order[0], $order[1]);
            }
            $query = $db2->get($table);
            $res = $query->result();
            if ($res) {
                return $res;
            }
            else{
                return null;
            }
        }
    // ========================== SELECT DATA

    // ========================== INSERT DATA
        public function insertData($cons, $table="", $data=null){
            $db2 = $this->load->database($cons, TRUE);
            if($data != null) {
                $db2->insert($table, $data);
                $err = $db2->error();
                if($err["message"] != ""){
                    return $err["message"];
                }
                else{
                    return 'OK';
                }
            }
        }

        public function insertData_batch($con='',$table="", $data=null){
            $db2 = $this->load->database($cons, TRUE);
            if($data != null) {
                $ins = $db2->insert_batch($table, $data);
                if(!$ins) {
                    $msg = $db2->error();
                    $msg = $msg["message"];
                } else {
                    $msg = 'OK';
                }
                $db2->close();
            }
            return $msg;
        }    
    // ========================== INSERT DATA

    // ========================== UPDATE DATA
        public function updateData($cons, $table="", $data=null, $where=null){
            $db2 = $this->load->database($cons, TRUE);
            if($data != null && $where != null) {
                $db2->update( $table, $data, $where);
                $err = $db2->error();
                if($err["message"] != ""){
                    return $err["message"];
                }
                else{
                    return 'OK';
                }
            }
        }

        public function updateAllData($cons, $table="", $data=null){
            $db2 = $this->load->database($cons, TRUE);
            $db2->update( $table, $data);
            $err = $db2->error();
            if($err["message"] != ""){
                return $err["message"];
            }
            else{
                return 'OK';
            }
        }
    // ========================== UPDATE DATA

    // ========================== DELETE DATA
        public function deleteData($cons, $table=null,$where=null){
            $db2 = $this->load->database($cons, TRUE);
            if($where != null){
                $db2->delete($table, $where);
                $err = $db2->error();
                if($err["message"] != ""){
                    return $err["message"];
                }
                else{
                    return 'OK';
                }
            }
        }
    // ========================== DELETE DATA
}