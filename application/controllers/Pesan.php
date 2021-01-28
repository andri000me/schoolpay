<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Pesan extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->session->set_userdata('title', 'Pesan');
    }

    public function index(){
        $this->load_content_admin('pesan/index');
    }

    public function searchPengguna(){
        $id_pengguna = $this->session->userdata("id_pengguna");
        $status     = $this->session->userdata("status");
        $searchQ    = isset($_GET['search']) ? $_GET['search'] : '' ;
        $searchQ    = explode(' ', $searchQ);

        $keyword = "%";
        foreach ($searchQ as $key => $val) {
            $keyword .= $val."%";

            if ($key+1 < count($searchQ)) {
                $keyword .= "_%";
            }
        }

        $query = "
            SELECT *
            FROM pengguna
            WHERE
                id_pengguna     !=   '$id_pengguna' 
            AND (
                id_pengguna     LIKE '$keyword' OR
                username        LIKE '$keyword' OR
                nama_lengkap    LIKE '$keyword'
            )
            ORDER BY 'id_pengguna' DESC
        ";
        
        $res = $this->M_wsbangun->getData_by_query('default', $query);
        if ($res) {
            $results = array();
            foreach ($res as $val) {
                if ($status == 'admin') {
                    $results[] = array(
                        'id_pengguna'       => $val->id_pengguna,
                        'nama_lengkap'      => $val->nama_lengkap,
                        'profile_pic'       => rand(1,20).".png",
                    );
                }
                elseif($status == 'siswa'){
                    if ($val->status == 'admin') {
                        $results[] = array(
                            'id_pengguna'       => $val->id_pengguna,
                            'nama_lengkap'      => $val->nama_lengkap,
                            'profile_pic'       => rand(1,20).".png",
                        );
                    }
                }
                else{
                    if ($val->status != 'siswa') {
                        $results[] = array(
                            'id_pengguna'       => $val->id_pengguna,
                            'nama_lengkap'      => $val->nama_lengkap,
                            'profile_pic'       => rand(1,20).".png",
                        );
                    }
                }
            }

            if (count($results) < 1) {
                $results[] = array(
                    'err'          => true,
                    'profile_pic'  => base_url('app-assets/images/profile.png'),
                );
            }
        }
        else{
            $results[] = array(
                'err'          => true,
                'profile_pic'  => base_url('app-assets/images/profile.png'),
            );
        }

        $callback['results']        = $results;

        echo json_encode($callback);
    }

    public function loadPengguna(){
        $id_pengguna = $this->session->userdata("id_pengguna");
        $callback   = "";

        $query = "
            SELECT 
                detMessage.to_id,
                detMessage.from_id,
                sumMessage.unreadMessage,
                isiMessage.lastMessage,
                isiMessage.lastSent
            FROM (
                SELECT DISTINCT 
                    to_id,
                    from_id
                FROM inbox
            ) AS detMessage

            JOIN (
                SELECT 
                    to_id,
                    from_id,
                    SUM(status_read) AS unreadMessage
                FROM inbox 
                GROUP BY to_id, from_id
            ) AS sumMessage
            ON detMessage.to_id = sumMessage.to_id

            JOIN (
                SELECT
                    to_id,
                    from_id,
                    message AS lastMessage,
                    MAX(date) AS lastSent
                FROM inbox
                WHERE rowid IN (SELECT MAX(rowid) FROM inbox GROUP BY to_id, from_id)
                GROUP BY to_id, from_id
            ) AS isiMessage
            ON detMessage.to_id = isiMessage.to_id
            AND detMessage.from_id = isiMessage.from_id

            WHERE detMessage.to_id = '$id_pengguna'
            
            GROUP BY to_id, from_id
            ORDER BY lastSent DESC
        ";
        $detailMessage = $this->M_wsbangun->getData_by_query("default", $query);
        
        if ($detailMessage) {
            foreach ($detailMessage as $value){
                $profile_pic    = rand(1,20).".png";
                $pic            = base_url('app-assets/images/portrait/small/avatar-s-').$profile_pic;
                $lastMessage    = (count(str_split($value->lastMessage)) <= 25) ? substr($value->lastMessage, 0, 25) : substr($value->lastMessage, 0, 25).'...';
                
                $callback .="<a id='listPengguna-$value->from_id' onClick=goToMessage('$value->from_id');document.getElementById('isiPesan').focus(); style='padding:10px 15px 10px 15px;' class='media border-bottom-blue-grey border-bottom-lighten-4'>";
                $callback .=    "<div class='media-left'>";
                $callback .=        "<span class='avatar avatar-md'>";
                $callback .=            "<img class='media-object rounded-circle' src='".$pic."'>";
                $callback .=        "</span>";
                $callback .=    "</div>";
                $callback .=    "<div class='media-body w-100 pl-1'  style='height: auto;'>";
                $callback .=        "<h6 class='list-group-item-heading font-medium-1 text-bold-700'>";
                $callback .=            "<strong class='light-hover'>". $this->getNamaPengguna($value->from_id) ."</strong>";

                                        if ((int)$value->unreadMessage > 0):
                $callback .=                "<span class='float-right'>";
                $callback .=                    "<span class='badge badge-pill badge-danger lighten-3'>$value->unreadMessage</span>";
                $callback .=                "</span>";
                                        endif;

                $callback .=        "</h6>";
                $callback .=        "<p class='font-small-3 text-muted text-bold-500 position-relative display-inline'>";
                $callback .=            "<b>$lastMessage</b>";
                $callback .=        "</p>";
                $callback .=        "<p class='float-right font-small-3 mb-0 text-muted text-bold-500'>".date_format(date_create($value->lastSent), 'd F Y H:i')."</p>";
                $callback .=    "</div>";
                $callback .="</a>";
            }
        }
        else{
            $callback .= "";
        }

        echo $callback;
    }

    function getNamaPengguna($id_pengguna){
        $query          = "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'";
        $messageFrom    = $this->M_wsbangun->getData_by_query("default", $query);

        return $messageFrom[0]->nama_lengkap;
    }

    public function loadMessage(){
    	$id_pengguna   = $this->session->userdata("id_pengguna");
        
        $callback = array();

        if (isset($_GET['message'])) {
            $to             = $_GET['message'];
            
            $query          = "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'";
            $messageFrom    = $this->M_wsbangun->getData_by_query("default", $query);

            $query          = "SELECT * FROM pengguna WHERE id_pengguna = '$to'";
            $messageTo      = $this->M_wsbangun->getData_by_query("default", $query);

            if ($messageFrom && $messageTo) {
                $callback['messageFrom']  = $messageFrom[0]->id_pengguna;
                $callback['messageTo']    = $messageTo[0]->id_pengguna;
                $callback['nama_lengkap'] = str_replace(' ', '-', $messageTo[0]->nama_lengkap);
                $callback['username']     = $messageTo[0]->username;
                $callback['status']       = $messageTo[0]->status;
                $callback['profile_pic']  = rand(1,20).".png";

                $query = "
                    SELECT 
                        * 
                    FROM `inbox` 
                    WHERE from_id IN ('$to','$id_pengguna')
                    AND to_id IN ('$id_pengguna', '$to')
                    ORDER BY rowid ASC
                ";
                $messageContent = $this->M_wsbangun->getData_by_query("default", $query);
                
                $data   = array('status_read' => '0');
                $where  = array('from_id' => $to, 'to_id' => $id_pengguna, 'status_read' => '1');
                $update = $this->M_wsbangun->updateData('default', 'inbox', $data, $where);
                
                if ($messageContent) {
                    $callback['messageContent'] = $messageContent;
                }
                else {
                    $callback['messageContent'] = array();
                }
            }
        }
        echo json_encode($callback);
    }

    public function kirimPesan(){
        $id_pengguna   = $this->session->userdata("id_pengguna");
        $callback = array();

        if ($_POST) {
            $isiPesan   = $this->input->post('isiPesan', true);
            $to         = $this->input->post('message', true);

            $query      = "SELECT * FROM inbox WHERE from_id = '$to' AND to_id = '$id_pengguna' ";
            $cekinbox   = $this->M_wsbangun->getData_by_query("default", $query);

            if (count($cekinbox) == 0) {
                $data = array(
                    'from_id'   => $to,
                    'to_id'     => $id_pengguna,
                    'message'   => '',
                    'date'      => date("Y-m-d H:i:s")
                );
                $insert = $this->M_wsbangun->insertData('default', 'inbox', $data);
            }
            
            $data = array(
                'from_id'   => $id_pengguna,
                'to_id'     => $to,
                'message'   => $isiPesan,
                'date'      => date("Y-m-d H:i:s")
            );
            $insert = $this->M_wsbangun->insertData('default', 'inbox', $data);

            if ($insert == 'OK') {
                $callback['messageContent'] = $isiPesan;
            }
        }
        echo json_encode($callback);
    }

    public function delete(){
        $callback = array(
            'Data' => null,
            'Message' => null,
            'Error' => false
        );

        if ($_POST) {
            $id = $this->input->post("id",true);
            // $where = array('id_pengguna'=>$id);

            // $del = $this->M_wsbangun->deleteData('default', 'inbox', $where);
            // if ($del  == 'OK') {
                $callback['Message'] = "Data has been deleted successfully";
            // }
            // else {
            //     $callback['Message'] = $del;
            //     $callback['Error'] = true;
            // }
        }
        echo json_encode($callback);
    }
}