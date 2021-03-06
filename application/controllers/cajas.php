<?php

class cajas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct('cajas');
    }

    public function index($fastUserId = '')
    {
        $this->change($fastUserId);
    }

    public function logout()
    {
        $this->change();
    }

    public function change($fastUserId = '')
    {
        $this->load->model('Caja');
        $tabular_data = [];
        $con = $this->load->database($this->session->userdata('dblocation'), true);
        $this->Sale->create_sales_items_temp_table($con);

        $this->Receiving->create_receivings_items_temp_table($con);
        $this->Caja->con = $con;
        $report_data = $this->Caja->getDetailsPayments();

        foreach ($report_data as $row) {
            $tabular_data[] = [$row['payment_type'], to_currency($row['payment_amount'])];
        }

        $data = [
            'data'         => $tabular_data,
            'headers'      => $this->Caja->getDataColumns(),
            'summary_data' => $this->Caja->getCierreDetails(),
            'fastUser'     => $fastUserId,
        ];

        $this->load->view('caja/cierre_caja', $data);
    }
}
