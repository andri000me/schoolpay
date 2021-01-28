<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans extends Core_Controller {
	public function __construct(){
        parent::__construct();
    }
	
    public function notification(){
        //DATA
            $callback = array(
                'Data'      => null,
                'Message'   => null,
                'Error'     => false
            );

            header('Content-Type: application/json');
            $Data = file_get_contents("php://input");
            $Data = json_decode($Data, true);

            $dataMidtrans = array(
                'order_id'              => $Data['order_id'],
                'transaction_id'        => $Data['transaction_id'],
                'transaction_time'      => date_format(date_create($Data['transaction_time']), "Y-m-d H:i:s"),
                'transaction_status'    => $Data['transaction_status'],
                'status_message'        => $Data['status_message'],
                'payment_type'          => $Data['payment_type'],
                'gross_amount'          => $Data['gross_amount'],
            );
            $order_id       = $dataMidtrans['order_id'];
            $transaction_id = $dataMidtrans['transaction_id'];
        //DATA

        // PAYMENT TYPE
            if ($Data['payment_type'] == 'bank_transfer') {
                $dataMidtrans['va_number']            = $Data['va_numbers'][0]['va_number'];
                $dataMidtrans['bank']                 = $Data['va_numbers'][0]['bank'];
            }
            elseif ($Data['payment_type'] == 'credit_card') {
                $dataMidtrans['va_number']            = $Data['masked_card'];
                $dataMidtrans['bank']                 = $Data['bank'];
            }
            elseif ($Data['payment_type'] == 'cimb_clicks') {
                $dataMidtrans['va_number']            = '-';
                $dataMidtrans['bank']                 = 'cimb_clicks';
            }
            elseif ($Data['payment_type'] == 'bca_klikpay') {
                $dataMidtrans['va_number']            = '-';
                $dataMidtrans['bank']                 = 'bca_klikpay';
            }
            elseif ($Data['payment_type'] == 'danamon_online') {
                $dataMidtrans['va_number']            = '-';
                $dataMidtrans['bank']                 = 'danamon_online';
            }
            elseif ($Data['payment_type'] == 'echannel'){
                $dataMidtrans['va_number']            = $Data['bill_key'];
                $dataMidtrans['bank']                 = $Data['biller_code'];
            }
        // PAYMENT TYPE

        // PENDING
            if (!array_key_exists('settlement_time', $Data)) {
                $data_logs = array(
                    'order_id'          => $order_id,
                    'itemid'            => 0,
                    'transaction_id'    => $transaction_id,
                    'json'              => json_encode($dataMidtrans),
                    'date'              => date("Y-m-d H:i:s")
                );
                $this->db->insert('midtrans_logs', $data_logs);

                $dataUpdate = array(
                    'transaction_status'=> $dataMidtrans['transaction_status'],
                    'status_message'    => $dataMidtrans['status_message'],
                    'payment_type'      => $dataMidtrans['payment_type'],
                    'va_number'         => $dataMidtrans['va_number'],
                    'bank'              => $dataMidtrans['bank']
                );

                $where = array(
                    'order_id'          => $order_id
                );
                $updatemop = $this->M_wsbangun->updateData('default', 'midtrans_onlinepay', $dataUpdate, $where);
            }
        // PENDING

        // SETTLEMENT
            $query = "SELECT * FROM midtrans_onlinepay WHERE order_id = '$order_id' ";
            $dataTrans = $this->M_wsbangun->getData_by_query('default', $query);
            $dataItem = json_decode($dataTrans[0]->item);

            if (array_key_exists('settlement_time', $Data)) {
                $dataMidtrans['settlement_time']      = date_format(date_create($Data['settlement_time']), "Y-m-d H:i:s");

                $index = 1;
                foreach ($dataItem as $item) {
                    if ($item->category == 'bulanan') {
                        $idnya = substr($item->id, 1,1);
                        $datanya = array(
                            'tahun_ajaran'  => substr($item->brand, 0, 4).'1',
                            'nis'           => $dataTrans[0]->nis,
                            'id_jenis'      => $item->merchant_name,
                            'b'.$idnya      => date('Y-m-d'),
                            'l'.$idnya      => $item->price,
                            'p'.$idnya      => $dataTrans[0]->petugas ,
                        );

                        $this->db->where('tahun_ajaran', substr($item->brand, 0, 4).'1');
                        $this->db->where('nis',  $dataTrans[0]->nis);
                        $this->db->where('id_jenis', $item->merchant_name);

                        $cek = $this->db->get('pembayaran_siswa_bulanan');
                        if($cek->num_rows() == 1){
                            $idnya = $cek->result()[0]->id_pembayaran;
                            $this->db->where('id_pembayaran', $idnya);
                            $this->db->update('pembayaran_siswa_bulanan', $datanya);
                        }else{
                            $this->db->insert('pembayaran_siswa_bulanan', $datanya);
                        }
                    }
                    else{
                        $datanya = array(
                            'tahun_ajaran'  => substr($item->brand, 0, 4).'1',
                            'nis'           => $dataTrans[0]->nis,
                            'id_jenis'      => $item->merchant_name,
                            'tanggal'       => date('Y-m-d'),
                            'cicilan'       => $item->price,
                            'petugas'       => $dataTrans[0]->petugas,
                        );
                        $this->db->insert('pembayaran_siswa_bebas', $datanya);
                    }

                    $callbacks = array(
                        'dataMidtrans'  => $Data,
                        'dataItem'      => $datanya
                    );

                    $data_logs = array(
                        'order_id'          => $order_id,
                        'itemid'            => $index,
                        'transaction_id'    => $transaction_id,
                        'json'              => json_encode($callbacks),
                        'date'              => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('midtrans_logs', $data_logs);
                    $index++;
                }

                $dataUpdate = array(
                    'settlement_time'   => $dataMidtrans['settlement_time'],
                    'transaction_status'=> $dataMidtrans['transaction_status'],
                    'status_message'    => $dataMidtrans['status_message'],
                    'payment_type'      => $dataMidtrans['payment_type'],
                    'va_number'         => $dataMidtrans['va_number'],
                    'bank'              => $dataMidtrans['bank']
                );

                $where = array(
                    'order_id'          => $order_id
                );
                $updatemop = $this->M_wsbangun->updateData('default', 'midtrans_onlinepay', $dataUpdate, $where);
            }
        // SETTLEMENT

        echo json_encode($callback, JSON_UNESCAPED_SLASHES);
    }

    // REDIRECT MIDTRANS
        public function complete(){
            $this->load->view('midtrans/complete');
        }

        public function pending(){
            $this->load->view('midtrans/pending');
        }

        public function error(){
            $this->load->view('midtrans/error');
        }

        public function finish(){
            $this->load->view('midtrans/finish');
        }
    // REDIRECT MIDTRANS



}

/* End of file Midtrans.php */
/* Location: ./application/controllers/Midtrans.php */