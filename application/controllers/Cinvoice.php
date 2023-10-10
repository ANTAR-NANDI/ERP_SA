<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use \ConvertApi\ConvertApi;

class Cinvoice extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
    }

    public function index()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->invoice_add_form();
        $this->template->full_admin_html_view($content);
    }
    public function pre_sale()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->pre_invoice_add_form();
        $this->template->full_admin_html_view($content);
    }

    //Insert invoice
    public function insert_invoice()
    {

        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $invoice_id = $CI->Invoices->invoice_entry();
        $this->session->set_userdata(array('message' => display('successfully_added')));
        redirect(base_url('Cinvoice/invoice_inserted_data/' . $invoice_id));
    }

    // ================= manual sale insert ============================
    public function manual_sales_insert()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit();
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $invoice_id = $CI->Invoices->invoice_entry();

        if (!empty($invoice_id)) {
            redirect(base_url('Cinvoice/invoice_inserted_data/' . $invoice_id));
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Cinvoice/gui_pos'));
        }
        // if (!empty($invoice_id)) {
        //     $data['status'] = true;
        //     $data['invoice_id'] = $invoice_id;
        //     $data['message'] = display('save_successfully');

        //     $data['details'] = $this->load->view('invoice/invoice_html_manual_a5', $data, true);
        // } else {
        //     $data['status'] = false;
        //     $data['error_message'] = 'Sorry';
        // }

        // echo json_encode($data);
    }
    public function pos_sales_insert()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $invoice_id = $CI->Invoices->pos_invoice_entry();
        if (!empty($invoice_id)) {
            $data['status'] = true;
            $data['invoice_id'] = $invoice_id;
            $data['message'] = display('save_successfully');
            // $mailsetting = $this->db->select('*')->from('email_config')->get()->result_array();
            // if($mailsetting[0]['isinvoice']==1){
            //     // $mail = $this->invoice_pdf_generate($invoice_id);
            //     if($mail == 0){
            //         $data['message2'] = $this->session->set_userdata(array('error_message' => display('please_config_your_mail_setting')));
            //     }
            // }
            $data['details'] = $this->load->view('invoice/invoice_html', $data, true);
        } else {
            $data['status'] = false;
            $data['error_message'] = 'Sorry';
        }

        echo json_encode($data);
    }


    //    public function add_cheque(){
    //        $invoice_id=$this->input->post('invoice_id',TRUE);
    //        $cheque_date=$this->input->post('cheque_date',TRUE);
    //        $cheque_no=$this->input->post('cheque_no',TRUE);
    //        $amount=$this->input->post('amount',TRUE);
    //
    //
    //        if ( ! empty($cheque_no) && ! empty($cheque_date) )
    //        {
    //            foreach ($cheque_no as $key => $value )
    //            {
    //
    //                $data['cheque_no'] = $value;
    //                $data['invoice_id']=$invoice_id;
    //                $data['cheque_id']=$this->generator(10);
    //
    //                $data['cheque_date'] = $cheque_date[$key];
    //                $data['amount'] = $amount[$key];
    //                $data['status'] = 2;
    //
    //                //   echo '<pre>';print_r($data);exit();
    //                // $this->ProductModel->add_products($data);
    //                if ( ! empty($data))
    //                {
    //                    $this->db->insert('cus_cheque', $data);
    //                }
    //            }
    //
    //        }
    //        echo json_encode($data);
    //
    //
    //    }

    public function invoice_pdf_generate($invoice_id = null)
    {
        $id = $invoice_id;
        $this->load->model('Invoices');
        $this->load->model('Web_settings');
        $this->load->library('occational');
        $this->load->library('numbertowords');
        $invoice_detail = $this->Invoices->retrieve_invoice_html_data($invoice_id);
        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon = 0;
        $subTotal_discount = 0;
        $subTotal_ammount = 0;
        $descript = 0;
        $isserial = 0;
        $isunit = 0;
        $is_discount = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $this->occational->dateConvert($invoice_detail[$k]['date']);
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }
            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['discount_per'])) {
                    $is_discount = $is_discount + 1;
                }

                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }

        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $company_info     = $this->Invoices->retrieve_company();
        $totalbal         = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword    = $this->numbertowords->convert_number($totalbal);
        $user_id          = $invoice_detail[0]['sales_by'];
        $users            = $this->Invoices->user_invoice_data($user_id);
        $name            = $invoice_detail[0]['customer_name'];
        $email            = $invoice_detail[0]['customer_email'];
        $data = array(
            'title'             => display('invoice_details'),
            'invoice_id'        => $invoice_detail[0]['invoice_id'],
            'customer_info'     => $invoice_detail,
            'invoice_no'        => $invoice_detail[0]['invoice'],
            'customer_name'     => $invoice_detail[0]['customer_name'],
            'customer_address'  => $invoice_detail[0]['customer_address'],
            'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
            'customer_email'    => $invoice_detail[0]['customer_email'],
            'final_date'        => $invoice_detail[0]['final_date'],
            'invoice_details'   => $invoice_detail[0]['invoice_details'],
            'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
            'subTotal_quantity' => $subTotal_quantity,
            'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
            // 'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
            'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
            'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
            'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
            'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
            'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
            'invoice_all_data'  => $invoice_detail,
            'company_info'      => $company_info,
            'currency'          => $currency_details[0]['currency'],
            'position'          => $currency_details[0]['currency_position'],
            'discount_type'     => $currency_details[0]['discount_type'],
            'currency_details'  => $currency_details,
            'am_inword'         => $amount_inword,
            'is_discount'       => $is_discount,
            'users_name'        => $users->first_name . ' ' . $users->last_name,
            'tax_regno'         => $txregname,
            'is_desc'           => $descript,
            'is_serial'         => $isserial,
            'is_unit'           => $isunit,
        );

        $this->load->library('pdfgenerator');
        $html = $this->load->view('invoice/invoice_download', $data, true);
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/invoice/' . $id . '.pdf', $output);
        $file_path = getcwd() . '/assets/data/pdf/invoice/' . $id . '.pdf';
        $send_email = '';
        if (!empty($email)) {
            $send_email = $this->setmail($email, $file_path, $invoice_detail[0]['invoice'], $name);

            if ($send_email) {
                return 1;
            } else {
                return 0;
            }
        }
        return 0;
    }


    public function setmail($email, $file_path, $id = null, $name = null)
    {
        $setting_detail = $this->db->select('*')->from('email_config')->get()->row();
        $subject = 'Purchase  Information';
        $message = strtoupper($name) . '-' . $id;
        $config = array(
            'protocol'  => $setting_detail->protocol,
            'smtp_host' => $setting_detail->smtp_host,
            'smtp_port' => $setting_detail->smtp_port,
            'smtp_user' => $setting_detail->smtp_user,
            'smtp_pass' => $setting_detail->smtp_pass,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'wordwrap'  => TRUE
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($setting_detail->smtp_user);
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach($file_path);
        $check_email = $this->test_input($email);
        if (filter_var($check_email, FILTER_VALIDATE_EMAIL)) {
            if ($this->email->send()) {

                return true;
            } else {

                return false;
            }
        } else {

            return true;
        }
    }

    //Email testing for email
    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //invoice Update Form
    public function invoice_update_form($invoice_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->invoice_edit_data($invoice_id);
        $this->template->full_admin_html_view($content);
    }

    // invoice Update
    public function invoice_update()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $invoice_id = $CI->Invoices->update_invoice();
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        $this->invoice_inserted_data($invoice_id);
    }

    //Search Inovoice Item
    public function search_inovoice_item()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('linvoice');

        $customer_id = $this->input->post('customer_id', TRUE);
        $content     = $CI->linvoice->search_inovoice_item($customer_id);
        $this->template->full_admin_html_view($content);
    }
    //Due Collection

    public function due_payment()
    {
        $invoice_id = $this->input->post('invoice_id');
        $notes = $this->input->post('notes');

        $inv_details = $this->db->from('invoice')->where('invoice_id', $invoice_id)->get()->row();

        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');

        $bank_id = $this->input->post('bank_id');
        $bkash_id = $this->input->post('bkash_id');
        $nagad_id = $this->input->post('nagad_id');
        $rocket_id = $this->input->post('rocket_id');
        $paytype = $this->input->post('paytype');

        $total_amount = $this->input->post('total_amount');
        $due_amount = $this->input->post('due_amount');
        $paid_amount = $this->input->post('paid_amount');
        $pay_amount = $this->input->post('pay_amount');

        if ($inv_details->sale_type == 1 || 2) {


            $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $inv_details->customer_id)->get()->row();
            $headn = $inv_details->customer_id . '-' . $cusifo->customer_name;
            $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
            $customer_headcode = $coainfo->HeadCode;
            $cs_name = $cusifo->customer_name;
        }

        if ($inv_details->sale_type == 3) {

            $cusifo = $this->db->select('*')->from('aggre_list')->where('id', $inv_details->agg_id)->get()->row();
            $headn =  $inv_details->agg_id . '-' . $cusifo->aggre_name;
            $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
            $customer_headcode = $coainfo->HeadCode;
            $cs_name = $cusifo->aggre_name;
        }

        //  echo '<pre>';print_r($cs_name);exit();

        if (!empty($bank_id)) {
            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id)->get()->row()->bank_name;

            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
        } else {
            $bankcoaid = '';
        }
        if (!empty($bkash_id)) {
            $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id)->get()->row()->bkash_no;

            $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK-' . $bkashname)->get()->row()->HeadCode;
        } else {
            $bkashcoaid = '';
        }
        if (!empty($nagad_id)) {
            $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id)->get()->row()->nagad_no;

            $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $nagadname)->get()->row()->HeadCode;
        } else {
            $nagadcoaid = '';
        }

        if (!empty($rocket_id)) {
            $rocketname = $this->db->select('rocket_no')->from('rocket_add')->where('rocket_id', $rocket_id)->get()->row()->rocket_no;

            $rocketcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'RK-' . $rocketname)->get()->row()->HeadCode;
        } else {
            $rocketcoaid = '';
        }

        $cosdr = array(
            'VNo' => $invoice_id,
            'Vtype' => 'INV',
            'VDate' => $createdate,
            'COAID' => $customer_headcode,
            'Narration' => 'Customer debit For Invoice No -  ' . $inv_details->invoice . ' Customer ' . $cs_name,
            'Debit' => 0,
            'Credit' => $pay_amount,
            'IsPosted' => 1,
            'CreateBy' => $createby,
            'CreateDate' => $createdate,
            'IsAppove' => 1
        );
        $this->db->insert('acc_transaction', $cosdr);

        if ($paytype == 1) {
            $data3 = array(
                'VNo'            =>  $invoice_id,
                //'cheque_id' => $cheque_id,
                'Vtype'          =>  'INV',
                'VDate'          =>  $createdate,
                'COAID'          =>  1020101,
                'Narration'      =>  'Customer Cash Debit Amount For  Invoice NO- ' . $inv_details->invoice . ' Customer- ' . $cs_name,
                'Debit'          =>  $pay_amount,
                'Credit'         =>  0,
                'IsPosted'       => 1,
                'CreateBy'       => $createby,
                'CreateDate'     => $createdate,
                'IsAppove'       => 1,
                //'paytype'=>$paytype

            );
            //  echo '<pre>';print_r($data3);exit();
            $ddd = $this->db->insert('acc_transaction', $data3);

            $payment_data = array(

                'invoice_id'    => $invoice_id,
                'pay_type'      => $paytype,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => 1020101
            );

            $this->db->insert('paid_amount', $payment_data);
        }
        if ($paytype == 4) {

            $bankc = array(
                'VNo' => $invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $createdate,
                'COAID' => $bankcoaid,
                'Narration' => 'Customer Bank Debit Due Amount For  Invoice NO- ' . $inv_details->invoice . ' Customer- ' . $cs_name . 'in' . $bankname,
                'Debit' => $pay_amount,
                'Credit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );
            $this->db->insert('acc_transaction', $bankc);

            $payment_data = array(

                'invoice_id'    => $invoice_id,
                'pay_type'      => 4,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => $bankcoaid
            );

            $this->db->insert('paid_amount', $payment_data);
        }
        if ($paytype == 3) {
            $bkashc = array(
                'VNo' => $invoice_id,
                'Vtype' => 'INVOICE',
                'VDate' => $createdate,
                'COAID' => $bkashcoaid,
                'Narration' => 'Customer Bkash Debit Amount For  Invoice NO- ' . $inv_details->invoice . ' Customer- ' . $cs_name . 'in' . $bkashname,
                'Debit' => $pay_amount,
                'Credit' => 0,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1,

            );
            $this->db->insert('acc_transaction', $bkashc);


            $payment_data = array(

                'invoice_id'    => $invoice_id,
                'pay_type'      => 3,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => $bkashcoaid
            );

            $this->db->insert('paid_amount', $payment_data);
        }
        if ($paytype == 5) {
            $nagadc = array(
                'VNo'            =>  $invoice_id,
                'Vtype'          =>  'INVOICE',
                'VDate'          =>  $createdate,
                'COAID'          =>  $nagadcoaid,
                'Narration'      =>  'Customer Nagad Debit Amount For  Invoice NO- ' . $inv_details->invoice . ' Customer- ' . $cs_name . 'in' . $nagadname,
                'Debit'          =>  $pay_amount,
                'Credit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $createby,
                'CreateDate'     =>  $createdate,
                'IsAppove'       =>  1,

            );

            $this->db->insert('acc_transaction', $nagadc);

            $payment_data = array(

                'invoice_id'    => $invoice_id,
                'pay_type'      => $paytype,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => $nagadcoaid
            );

            $this->db->insert('paid_amount', $payment_data);
        }
        if ($paytype == 7) {
            $rocketc = array(
                'VNo'            =>  $invoice_id,
                'Vtype'          =>  'INVOICE',
                'VDate'          =>  $createdate,
                'COAID'          =>  $rocketcoaid,
                'Narration'      =>  'Customer Rocket Debit Amount For  Invoice NO- ' . $inv_details->invoice . ' Customer- ' . $cs_name . 'in' . $nagadname,
                'Debit'          =>  $pay_amount,
                'Credit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $createby,
                'CreateDate'     =>  $createdate,
                'IsAppove'       =>  1,

            );

            $this->db->insert('acc_transaction', $rocketc);

            $payment_data = array(

                'invoice_id'    => $invoice_id,
                'pay_type'      => $paytype,
                'amount'        => $pay_amount,
                'pay_date'      =>  date('Y-m-d'),
                'status'        =>  1,
                'account'       => '',
                'notes'       => $notes,
                'COAID'         => $rocketcoaid
            );

            $this->db->insert('paid_amount', $payment_data);
        }

        $data = array(

            'total_amount' => $total_amount,
            'due_amount' => $due_amount,
            'paid_amount' => $paid_amount,
        );


        $this->db->where('invoice_id', $invoice_id);
        $result = $this->db->update('invoice', $data);


        //

        if ($result == true) {

            redirect(base_url('Cinvoice/invoice_inserted_data/' . $invoice_id));
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Cinvoice/manage_invoice'));
        }
    }


    //Manage invoice list
    public function manage_invoice()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $CI->load->model('Invoices');
        $content = $this->linvoice->invoice_list();
        $this->template->full_admin_html_view($content);
    }

    public function manage_payment_history()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $CI->load->model('Invoices');
        $content = $this->linvoice->invoice_list1();
        $this->template->full_admin_html_view($content);
    }
    public function CheckInvoiceList()
    {
        // GET data
        $this->load->model('Invoices');
        $postData = $this->input->post();
        $data = $this->Invoices->getInvoiceList($postData);
        echo json_encode($data);
    }
    // invoice list pdf download
    public function sale_downloadpdf()
    {
        $CI = &get_instance();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $CI->load->library('linvoice');
        $CI->load->library('pdfgenerator');
        $invoices_list = $CI->Invoices->invoice_list_pdf();
        if (!empty($invoices_list)) {
            $i = 0;
            if (!empty($invoices_list)) {
                foreach ($invoices_list as $k => $v) {
                    $invoices_list[$k]['final_date'] = $CI->occational->dateConvert($invoices_list[$k]['date']);
                }
                foreach ($invoices_list as $k => $v) {
                    $i++;
                    $invoices_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('manage_invoice'),
            'invoices_list' => $invoices_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
            'company_info'  => $company_info
        );
        $this->load->helper('download');
        $content = $this->parser->parse('invoice/invoice_list_pdf', $data, true);
        $time = date('Ymdhi');
        $dompdf = new DOMPDF();
        $dompdf->load_html($content);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/' . 'sales' . $time . '.pdf', $output);
        $file_path = 'assets/data/pdf/' . 'sales' . $time . '.pdf';
        $file_name = 'sales' . $time . '.pdf';
        force_download(FCPATH . 'assets/data/pdf/' . $file_name, null);
    }


    public function order()
    {
        $CI = &get_instance();
        $CI->load->model('Rqsn');


        $data = array(
            'title'         => "Order",
        );


        $view  = $this->parser->parse('invoice/order', $data, true);
        $this->template->full_admin_html_view($view);
    }


    public function customer_order()
    {

        $CI = &get_instance();
        $data = array(
            'title'         => "Customer's Order",
        );


        $view  = $this->parser->parse('invoice/customer_order', $data, true);
        $this->template->full_admin_html_view($view);
    }


    public function invoicdetails_download($invoice_id = null)
    {

        require_once(APPPATH . "../vendor/dompdf/dompdf/autoload.inc.php");

        $CI = &get_instance();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->library('occational');
        $CI->load->library('numbertowords');
        $CI->load->model('Warehouse');

        $redirect_url = $_SESSION['redirect_uri'];

        $invoice_detail = $CI->Invoices->retrieve_invoice_html_data($invoice_id);
        $cus_id = $invoice_detail[0]['customer_id'];
        $customer_balance = $CI->Invoices->customer_balance($cus_id);
        $taxfield = $CI->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon = 0;
        $subTotal_discount = 0;
        $subTotal_ammount = 0;
        $descript = 0;
        $isserial = 0;
        $isunit = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $CI->occational->dateConvert($invoice_detail[$k]['date']);
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }

            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }

        $outlet = $CI->Warehouse->branch_search_item($invoice_detail[0]['outlet_id']);

        $inwords = $CI->numbertowords->convert_number($invoice_detail[0]['total_amount']);

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        // $totalbal = $invoice_detail[0]['total_amount']+$invoice_detail[0]['prevous_due'];
        $totalbal = $invoice_detail[0]['total_amount'];
        $amount_inword = $CI->numbertowords->convert_number($totalbal);
        $user_id = $invoice_detail[0]['sales_by'];
        $users = $CI->Invoices->user_invoice_data($user_id);
        $data = array(
            'title'             => display('invoice_details'),
            'balance'           => $customer_balance[0]['balance'],
            'pay_type'          => $invoice_detail[0]['payment_type'],
            'invoice_id'        => $invoice_detail[0]['invoice_id'],
            'invoice_no'        => $invoice_detail[0]['invoice'],
            // 'outlet_name'    => $outlet[0]['outlet_name'],
            'customer_name'     => $invoice_detail[0]['customer_name'],
            'customer_address'  => $invoice_detail[0]['customer_address'],
            'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
            'customer_email'    => $invoice_detail[0]['customer_email'],
            'final_date'        => $invoice_detail[0]['final_date'],
            'inv_date'        => $invoice_detail[0]['date'],
            'invoice_details'   => $invoice_detail[0]['invoice_details'],
            'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
            'total'      => number_format($invoice_detail[0]['total_amount'], 2, '.', ','),
            'subTotal_quantity' => $subTotal_quantity,
            'invoice_discount'    => number_format($invoice_detail[0]['invoice_discount'], 2, '.', ','),
            'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
            'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
            'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
            'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
            'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
            'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
            'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
            'invoice_all_data'  => $invoice_detail,
            'company_info'      => $company_info,
            'currency'          => $currency_details[0]['currency'],
            'position'          => $currency_details[0]['currency_position'],
            'discount_type'     => $currency_details[0]['discount_type'],
            'inv_logo'          => $currency_details[0]['invoice_logo'],
            'am_inword'         => $amount_inword,
            'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
            'users_name'        => $users->first_name . ' ' . $users->last_name,
            'tax_regno'         => $txregname,
            'is_desc'           => $descript,
            'is_serial'         => $isserial,
            'is_unit'           => $isunit,
            'inwords'           => $inwords,
            'red_url'           => isset($redirect_url) ? $redirect_url : null,

        );

        $dompdf = new Dompdf\Dompdf();
        $page = $this->load->view('invoice/invoice_pdf', $data, true);

        $dompdf->loadHTml($page, 'UTF-8');
        $dompdf->render();
        $dompdf->stream("Invoice-" . $data['invoice_no'] . ".pdf");
    }

    // search invoice by customer id
    public function invoice_search()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $CI->load->model('Invoices');
        $customer_id = $this->input->get('customer_id');
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Cinvoice/invoice_search/');
        $config["total_rows"] = $this->Invoices->invoice_search_count($customer_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
        $config['suffix'] = '?' . http_build_query($_GET);
        $config['first_url'] = $config["base_url"] . $config['suffix'];
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #
        $content = $this->linvoice->invoice_search($customer_id, $links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    public function view_invoice_payment_history($invoice_id)
    {
        $CI = &get_instance();
        $details = $CI->db->select('*')
            ->from('paid_amount')
            ->where('invoice_id', $invoice_id)
            ->get()
            ->result_array();

        $data = array(
            'details' => $details
        );
        // echo "<pre>";
        // print_r($taxfield);
        // exit();
        $payment_details = $CI->parser->parse('report/payment_details', $data, true);
        $this->template->full_admin_html_view($payment_details);
    }

    // search invoice by invoice id
    public function manage_invoice_invoice_id()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $CI->load->model('Invoices');
        $invoice_no = $this->input->post('invoice_no', TRUE);
        $content = $this->linvoice->invoice_list_invoice_no($invoice_no);
        $this->template->full_admin_html_view($content);
    }

    // invoice list date to date
    public function date_to_date_invoice()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $CI->load->model('Invoices');
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');

        #
        #pagination starts
        #
        $config["base_url"] = base_url('Cinvoice/date_to_date_invoice/');
        $config["total_rows"] = $this->Invoices->invoice_list_date_to_date_count($from_date, $to_date);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
        $config['suffix'] = '?' . http_build_query($_GET, '', '&');
        $config['first_url'] = $config["base_url"] . $config['suffix'];
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $links = $this->pagination->create_links();
        #
        #pagination ends
        #

        $content = $this->linvoice->invoice_list_date_to_date($from_date, $to_date, $links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    //POS invoice page load
    public function pos_invoice()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->pos_invoice_add_form();
        $this->template->full_admin_html_view($content);
    }

    // New for Vat tax Calculation
    public function pos_invoice_new()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->pos_invoice_add_form_new();
        $this->template->full_admin_html_view($content);
    }

    //Insert pos invoice
    public function insert_pos_invoice()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->model('Settings');
        $CI->load->model('Warehouse');
        $CI->load->model('Products');
        $product_id = $this->input->post('product_id', TRUE);

        // $user_id = $this->session->userdata('user_id');

        // $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        $outlet_id = $this->session->userdata('outlet_id');

        $product_details  = $CI->Invoices->pos_invoice_setup($product_id, $outlet_id);

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $vat_setting   = $CI->Settings->read_all_pos_setting('vat')[0]['value'];
        $tax_setting   = $CI->Settings->read_all_pos_setting('tax')[0]['value'];
        // echo '<pre>';
        // print_r($vat_setting);
        // exit();
        $taxfield = $CI->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $prinfo = $this->db->select('*')->from('product_information')->where('product_id', $product_id)->get()->result_array();
        $tr = " ";
        $vat = $this->db->select('percent,vat_tax_type')->from('vat_tax_setting')->where(array('vat_tax' => 'vat', 'product_id' => $product_id))->get()->row();
        $tax = $this->db->select('percent,vat_tax_type')->from('vat_tax_setting')->where(array('vat_tax' => 'tax', 'product_id' => $product_id))->get()->row();
        if ($vat->percent > 0 || $tax->percent > 0) {

            if ($vat->vat_tax_type == 'in' && $tax->vat_tax_type == 'ex') {
                $product_real_price = ($product_details->price / (100 + $vat->percent)) * 100;
                $product_vat = $product_real_price * ($vat->percent / 100);
                $product_tax = $product_real_price * ($tax->percent / 100);
            }

            if ($vat->vat_tax_type == 'ex' && $tax->vat_tax_type == 'in') {
                $product_real_price = ($product_details->price / (100 + $tax->percent)) * 100;
                $product_vat = $product_real_price * ($vat->percent / 100);
                $product_tax = $product_real_price * ($tax->percent / 100);
            }

            if ($vat->vat_tax_type == 'in' && $tax->vat_tax_type == 'in') {
                $product_real_price = ($product_details->price / (100 + $tax->percent + $vat->percent)) * 100;
                $product_vat = $product_real_price * ($vat->percent / 100);
                $product_tax = $product_real_price * ($tax->percent / 100);
            }

            if ($vat->vat_tax_type == 'ex' && $tax->vat_tax_type == 'ex') {
                $product_real_price = $product_details->price;
                $product_vat = $product_real_price * ($vat->percent / 100);
                $product_tax = $product_real_price * ($tax->percent / 100);
            }
        } else {
            $product_real_price = $product_details->price;
            $product_vat = 0;
            $product_tax = 0;
        }
        if (!empty($product_details)) {
            $product_id = $this->generator(5);
            $serialdata = explode(',', $product_details->serial_no);
            if ($product_details->total_product > 0) {
                $qty = 1;
            } else {
                $qty = 1;
            }

            $html = "";
            if (empty($serialdata)) {
                $html .= "No Serial Found !";
            } else {
                // Select option created for product
                $date = date('Y-m-d');
                $html .= "<select name=\"serial_no[]\"   class=\"serial_no_1 form-control\" id=\"serial_no_" . $product_details->product_id . "\">";
                $html .= "<option value=''>" . display('select_one') . "</option>";
                foreach ($serialdata as $serial) {
                    $html .= "<option value=" . $serial . ">" . $serial . "</option>";
                }
                $html .= "</select>";
            }

            $tr .= "<tr id=\"row_" . $product_details->product_id . "\">
						<td class=\"\" style=\"width: 30%\">

							<input type=\"text\"  size='100' name=\"product_name\" onkeypress=\"invoice_productList('" . $product_details->product_id . "');\" class=\"form-control productSelection \" value='" . html_escape($product_details->sku) . "-" . html_escape($product_details->product_name) . "' placeholder='" . display('product_name') . "' required=\"\" id=\"product_name_" . $product_details->product_id . "\" tabindex=\"\" readonly>

							<input type=\"hidden\" class=\"form-control autocomplete_hidden_value product_id_" . $product_details->product_id . "\" name=\"product_id[]\" id=\"SchoolHiddenId_" . $product_details->product_id . "\" value = \"$product_details->product_id\"/>
							<input type=\"hidden\" class=\"form-control  product_id_" . $product_details->sku . "\" name=\"skuu[]\" id=\"SchoolHiddenSku_" . $product_details->sku . "\" value = \"$product_details->sku\"/>

						</td>


	  					<td>
                            <input type=\"text\" name=\"available_quantity[]\" class=\"form-control text-right available_quantity_" . $product_details->product_id . "\" value='" . $product_details->total_product . "' readonly=\"\" id=\"available_quantity_" . $product_details->product_id . "\"/>
                        </td>

                        <td>
                            <input class=\"form-control text-right unit_'" . $product_details->product_id . "' valid\" value=\"$product_details->unit\" readonly=\"\" aria-invalid=\"false\" type=\"text\">
                        </td>

                        <td width=\"60px\">
                            <input type=\"text\" name=\"product_quantity[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" class=\"total_qntt_" . $product_details->sku . " form-control text-right\" id=\"total_qntt_" . $product_details->product_id . "\" placeholder=\"0.00\" min=\"0\" value='" . $qty . "'/>
                        </td>

                  







						<td class=\"text-center\"  width=\"130px\">
							<input style=\"width: 120px; display:inline-block\" type=\"text\" name=\"product_rate[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" value='" . $product_details->price . "' id=\"price_item_" . $product_details->product_id . "\" class=\"price_item1 form-control text-right\" required  placeholder=\"0.00\" min=\"0\" readonly/>


						</td>
						
						<td class=\"text-right\" style=\"width:100px\">
							<input class=\"total_price_wd form-control text-right\" type=\"text\" name=\"total_price_wd[]\" id=\"total_price_wd_" . $product_details->product_id . "\" value='" . $product_details->purchase_price . "' tabindex=\"-1\" readonly=\"readonly\"/>
						</td>";

            $vat_setting == "enable" ? $tr .= "<td class=\"text-right\" style=\"width:100px\">
							<input class=\" form-control text-right\" type=\"text\" name=\"vat[]\" id=\"vat_" . $product_details->product_id . "\" value='" . $product_vat . "' tabindex=\"-1\" readonly=\"readonly\"/>
							<input type=\"hidden\" class=\"form-control\" id=\"vat_percent_" . $product_details->product_id . "\" value='" . $vat->percent . "' tabindex=\"-1\"/>
                            </td>" : "";
            $tax_setting == "enable" ? $tr .= "<td class=\"text-right\" style=\"width:100px\">
							<input class=\" form-control text-right\" type=\"text\" name=\"tax[]\" id=\"tax_" . $product_details->product_id . "\" value='" . $product_tax . "' tabindex=\"-1\" readonly=\"readonly\"/>
							<input type=\"hidden\" class=\"form-control\" id=\"tax_percent_" . $product_details->product_id . "\" value='" . $tax->percent . "' tabindex=\"-1\"/>
                            </td>" : "";

            $tr .= "<td class=\"\">
							<input type=\"text\" name=\"discount[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" id=\"discount_" . $product_details->product_id . "\" class=\"form-control text-right\" placeholder=\"0.00\" min=\"0\"/>

							<input type=\"hidden\" value=" . $currency_details[0]['discount_type'] . " name=\"discount_type\" id=\"discount_type_" . $product_details->product_id . "\">
						</td>

						<td class=\"text-right\" style=\"width:100px\">
							<input class=\"total_price form-control text-right\" type=\"text\" name=\"total_price[]\" id=\"total_price_" . $product_details->product_id . "\" value='" . $product_details->purchase_price . "' tabindex=\"-1\" readonly=\"readonly\"/>
						</td>

						<td>";
            $sl = 0;
            foreach ($taxfield as $taxes) {
                $txs = 'tax' . $sl;
                $tr .= "<input type=\"hidden\" id=\"total_tax" . $sl . "_" . $product_details->product_id . "\" class=\"total_tax" . $sl . "_" . $product_details->product_id . "\" value='" . $prinfo[0][$txs] . "'/>
                            <input type=\"hidden\" id=\"all_tax" . $sl . "_" . $product_details->product_id . "\" class=\" total_tax" . $sl . "\" value='" . $prinfo[0][$txs] * $product_details->purchase_price . "' name=\"tax[]\"/>";
                $sl++;
            }

            $tr .= "<input type=\"hidden\"  class=\"discount\"  name=\"total_discount[]\" id=\"total_discount_" . $product_details->product_id . "\" />
            
							<input type=\"hidden\" id=\"all_discount_" . $product_details->product_id . "\" class=\" dppr\"/>
							<button  class=\"btn btn-danger  text-center\" type=\"button\"  onclick=\"deleteRow(this)\">" . '<i class="fa fa-close"></i>' . "</button>
						</td>
					</tr>";
            echo $tr;
        } else {
            return false;
        }
    }

    public function insert_pos_invoice_new()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->model('Settings');
        $CI->load->model('Warehouse');
        $CI->load->model('Products');
        $product_id = $this->input->post('product_id', TRUE);

        // $user_id = $this->session->userdata('user_id');

        // $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        $outlet_id = $this->session->userdata('outlet_id');

        $product_details  = $CI->Invoices->pos_invoice_setup($product_id, $outlet_id);

        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $vat_setting   = $CI->Settings->read_all_pos_setting('vat')[0]['value'];
        $tax_setting   = $CI->Settings->read_all_pos_setting('tax')[0]['value'];
        // echo '<pre>';
        // print_r($product_details);
        // exit();
        $taxfield = $CI->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $prinfo = $this->db->select('*')->from('product_information')->where('product_id', $product_id)->get()->result_array();
        $tr = " ";
        // $vat = $this->db->select('percent,vat_tax_type')->from('vat_tax_setting')->where(array('vat_tax' => 'vat', 'product_id' => $product_id))->get()->row();
        // $tax = $this->db->select('percent,vat_tax_type')->from('vat_tax_setting')->where(array('vat_tax' => 'tax', 'product_id' => $product_id))->get()->row();
        // if (empty($vat)) {
        //     $vat->percent = 0;
        //     $vat->vat_tax_type = 'ex';
        // }

        // if (empty($tax)) {
        //     $tax->percent = 0;
        //     $tax->vat_tax_type = 'ex';
        // }
        // if ($vat->percent > 0 || $tax->percent > 0) {

        //     if ($vat->vat_tax_type == 'in' && $tax->vat_tax_type == 'ex') {
        //         $product_real_price = ($product_details->price / (100 + $vat->percent)) * 100;
        //         $product_vat = $product_real_price * ($vat->percent / 100);
        //         $product_tax = $product_real_price * ($tax->percent / 100);
        //     }

        //     if ($vat->vat_tax_type == 'ex' && $tax->vat_tax_type == 'in') {
        //         $product_real_price = ($product_details->price / (100 + $tax->percent)) * 100;
        //         $product_vat = $product_real_price * ($vat->percent / 100);
        //         $product_tax = $product_real_price * ($tax->percent / 100);
        //     }

        //     if ($vat->vat_tax_type == 'in' && $tax->vat_tax_type == 'in') {
        //         $product_real_price = ($product_details->price / (100 + $tax->percent + $vat->percent)) * 100;
        //         $product_vat = $product_real_price * ($vat->percent / 100);
        //         $product_tax = $product_real_price * ($tax->percent / 100);
        //     }

        //     if ($vat->vat_tax_type == 'ex' && $tax->vat_tax_type == 'ex') {
        //         $product_real_price = $product_details->price;
        //         $product_vat = $product_real_price * ($vat->percent / 100);
        //         $product_tax = $product_real_price * ($tax->percent / 100);
        //     }
        // } else {
        //     $product_real_price = $product_details->price;
        //     $product_vat = 0;
        //     $product_tax = 0;
        // }

        // echo "<pre>";
        // print_r($product_details);
        // exit();


        if (!empty($product_details)) {
            $product_id = $this->generator(5);
            $serialdata = explode(',', $product_details->serial_no);
            if ($product_details->total_product > 0) {
                $qty = 1;
            } else {
                $qty = 1;
            }

            $html = "";
            if (empty($serialdata)) {
                $html .= "No Serial Found !";
            } else {
                // Select option created for product
                $date = date('Y-m-d');
                $html .= "<select name=\"serial_no[]\"   class=\"serial_no_1 form-control\" id=\"serial_no_" . $product_details->product_id . "\">";
                $html .= "<option value=''>" . display('select_one') . "</option>";
                foreach ($serialdata as $serial) {
                    $html .= "<option value=" . $serial . ">" . $serial . "</option>";
                }
                $html .= "</select>";
            }

            $tr .= "<tr id=\"row_" . $product_details->product_id . "\">
						<td class=\"\" style=\"width: 30%\">

							<input type=\"text\"  size='100' name=\"product_name\" onkeypress=\"invoice_productList('" . $product_details->product_id . "');\" class=\"form-control productSelection \" value='" . html_escape($product_details->sku) . "-" . html_escape($product_details->product_name) . "' placeholder='" . display('product_name') . "' required=\"\" id=\"product_name_" . $product_details->product_id . "\" tabindex=\"\" readonly>

							<input type=\"hidden\" class=\"form-control autocomplete_hidden_value product_id_" . $product_details->product_id . "\" name=\"product_id[]\" id=\"SchoolHiddenId_" . $product_details->product_id . "\" value = \"$product_details->product_id\"/>
							<input type=\"hidden\" class=\"form-control  product_id_" . $product_details->sku . "\" name=\"skuu[]\" id=\"SchoolHiddenSku_" . $product_details->sku . "\" value = \"$product_details->sku\"/>

						</td>


	  					<td>
                            <input type=\"number\" name=\"available_quantity[]\" class=\"form-control text-right available_quantity_" . $product_details->product_id . "\" value='" . $product_details->total_product . "' readonly=\"\" disabled min=\"1\" id=\"available_quantity_" . $product_details->product_id . "\"/>
                        </td>

                        <td>
                            <input class=\"form-control text-right unit_'" . $product_details->product_id . "' valid\" value=\"$product_details->unit\" readonly=\"\" aria-invalid=\"false\" type=\"text\">
                        </td>

                        <td width=\"60px\">
                            <input type=\"text\" name=\"product_quantity[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" class=\"total_qntt_" . $product_details->sku . " form-control text-right\" id=\"total_qntt_" . $product_details->product_id . "\" placeholder=\"0.00\" min=\"0\" value='" . $qty . "'/>
                        </td>


						<td class=\"text-center\"  width=\"130px\">
							<input style=\"width: 120px; display:inline-block\" type=\"text\" name=\"product_rate[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" value='" . $product_details->price . "' id=\"price_item_" . $product_details->product_id . "\" class=\"price_item1 form-control text-right\" required  placeholder=\"0.00\" min=\"0\" readonly/>


						</td>
						
						<td class=\"text-right\" style=\"width:100px\">
							<input class=\"total_price_wd form-control text-right\" type=\"text\" name=\"total_price_wd[]\" id=\"total_price_wd_" . $product_details->product_id . "\" value='" . $product_details->purchase_price . "' tabindex=\"-1\" readonly=\"readonly\"/>

                            <input class=\"total_discounted_price_wd form-control text-right\" type=\"hidden\" name=\"total_discounted_price_wd[]\" id=\"total_discounted_price_wd_" . $product_details->product_id . "\" value='0.00' tabindex=\"-1\" readonly=\"readonly\"/>
                            
                            <input class=\" form-control text-right vat\" type=\"hidden\" name=\"vat[]\" id=\"vat_" . $product_details->product_id . "\" value='" . $product_details->vat . "' tabindex=\"-1\" readonly=\"readonly\"/>
							<input type=\"hidden\" class=\"form-control\" id=\"vat_percent_" . $product_details->product_id . "\" value='" . $product_details->vat_percent . "' tabindex=\"-1\"/>
                            <input class=\" form-control text-right tax\" type=\"hidden\" name=\"tax[]\" id=\"tax_" . $product_details->product_id . "\" value='" . $product_details->tax . "' tabindex=\"-1\" readonly=\"readonly\"/>
							<input type=\"hidden\" class=\"form-control\" id=\"tax_percent_" . $product_details->product_id . "\" value='" . $product_details->tax_percent . "' tabindex=\"-1\"/>
						</td>";

            // $vat_setting == "enable" ? $tr .= "<td class=\"text-right\" style=\"width:100px\">
            // 				<input class=\" form-control text-right\" type=\"text\" name=\"vat[]\" id=\"vat_" . $product_details->product_id . "\" value='" . $product_vat . "' tabindex=\"-1\" readonly=\"readonly\"/>
            // 				<input type=\"hidden\" class=\"form-control\" id=\"vat_percent_" . $product_details->product_id . "\" value='" . $vat->percent . "' tabindex=\"-1\"/>
            //                 </td>" : "";
            // $tax_setting == "enable" ? $tr .= "<td class=\"text-right\" style=\"width:100px\">
            // 				<input class=\" form-control text-right\" type=\"text\" name=\"tax[]\" id=\"tax_" . $product_details->product_id . "\" value='" . $product_tax . "' tabindex=\"-1\" readonly=\"readonly\"/>
            // 				<input type=\"hidden\" class=\"form-control\" id=\"tax_percent_" . $product_details->product_id . "\" value='" . $tax->percent . "' tabindex=\"-1\"/>
            //                 </td>" : "";

            $tr .= "<td class=\"\">
							<input type=\"text\" name=\"discount[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" id=\"discount_" . $product_details->product_id . "\" class=\"form-control text-right\" placeholder=\"0.00\" value=\"0.00\" min=\"0\"/>

                            <input type=\"hidden\" name=\"distributed_discount[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" id=\"distributed_discount_" . $product_details->product_id . "\" class=\"form-control text-right\" placeholder=\"0.00\"  readonly=\"readonly\" value=\"0.00\" min=\"0\"/>

                            <input type=\"hidden\" name=\"item_total_discount[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" id=\"item_total_discount_" . $product_details->product_id . "\" class=\"form-control text-right\"  readonly=\"readonly\" placeholder=\"0.00\" value=\"0.00\" min=\"0\"/>

							<input type=\"hidden\" value=" . $currency_details[0]['discount_type'] . " name=\"discount_type\" id=\"discount_type_" . $product_details->product_id . "\">
						</td>

						<td class=\"text-right\" style=\"width:100px\">
							<input class=\"total_price form-control text-right\" type=\"text\" name=\"total_price[]\" id=\"total_price_" . $product_details->product_id . "\" value='" . $product_details->purchase_price . "' tabindex=\"-1\" readonly=\"readonly\"/>
						</td>

						<td>";
            $sl = 0;
            foreach ($taxfield as $taxes) {
                $txs = 'tax' . $sl;
                $tr .= "<input type=\"hidden\" id=\"total_tax" . $sl . "_" . $product_details->product_id . "\" class=\"total_tax" . $sl . "_" . $product_details->product_id . "\" value='" . $prinfo[0][$txs] . "'/>
                            <input type=\"hidden\" id=\"all_tax" . $sl . "_" . $product_details->product_id . "\" class=\" total_tax" . $sl . "\" value='" . $prinfo[0][$txs] * $product_details->purchase_price . "' name=\"tax[]\"/>";
                $sl++;
            }

            $tr .= "<input type=\"hidden\"  class=\"discount\"  name=\"total_discount[]\" id=\"total_discount_" . $product_details->product_id . "\" />
            
							<input type=\"hidden\" id=\"all_discount_" . $product_details->product_id . "\" class=\" dppr\"/>
							<button  class=\"btn btn-danger  text-center\" type=\"button\"  onclick=\"deleteRow(this)\">" . '<i class="fa fa-close"></i>' . "</button>
						</td>
					</tr>";
            echo $tr;
        } else {
            return false;
        }
    }

    //Retrive right now inserted data to cretae html
    public function invoice_inserted_data($invoice_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->invoice_html_data_manual($invoice_id, 1);

        $this->template->full_admin_html_view($content);
    }

    public function invoice_inserted_data_manual()
    {
        //        echo 'Hello';
        //        exit();
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $invoice_id = $this->input->post('invoice_id', TRUE);
        // $chalan_value = $this->input->post('chalan_value', TRUE);
        $CI->load->library('linvoice');

        $_SESSION['redirect_uri'] = 'Cinvoice/manage_invoice';
        //echo '<pre>';print_r($_POST['chalan_value']);exit();
        if (isset($_POST['chalan_value'])) {
            $content = $CI->linvoice->invoice_chalan_html_data_manual($invoice_id);
            $this->template->full_admin_html_view($content);
            //  echo $_POST['chalan_value']; // Displays value of checked checkbox.
        } else {

            // echo "value Not found";
            $content = $CI->linvoice->invoice_html_data_manual($invoice_id);
            $this->template->full_admin_html_view($content);
        }
    }
    public function pos_invoice_inserted_data_manual()
    {

        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $invoice_id = $this->input->post('invoice_id', TRUE);
        $url = $this->input->post('url', TRUE);
        $content = $CI->linvoice->invoice_html_data_manual($invoice_id, $url);
        $this->template->full_admin_html_view($content);
    }


    //Retrive right now inserted data to cretae html
    public function pos_invoice_inserted_data($invoice_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->pos_invoice_html_data($invoice_id);
        $this->template->full_admin_html_view($content);
    }
    //Min invoice data
    public function min_invoice_inserted_data($invoice_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->min_invoice_html_data($invoice_id);
        $this->template->full_admin_html_view($content);
    }

    //Chalan invoice data
    public function chalan_invoice_inserted_data($invoice_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->chalan_invoice_html_data($invoice_id);
        $this->template->full_admin_html_view($content);
    }
    // Retrieve_product_data
    public function retrieve_product_data()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $product_id  = $this->input->post('product_id', TRUE);
        $product_status  = $this->input->post('product_status', TRUE);
        $supplier_id = $this->input->post('supplier_id', TRUE);

        $product_info = $CI->Invoices->get_total_product($product_id, $product_status);

        echo json_encode($product_info);
    }

    //product info retrive by product id for invoice
    public function retrieve_product_data_inv()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $product_id = $this->input->post('product_id', TRUE);
        $customer_id = $this->input->post('outlet_id', TRUE);

        // echo "<pre>";
        // echo $product_id . " " . $customer_id;
        // exit();


        $product_info = $CI->Invoices->get_total_product_invoic($product_id, $customer_id);
        // echo '<pre>';
        // print_r($product_info);
        // exit();

        echo json_encode($product_info);
    }

    // Invoice delete
    public function invoice_delete($invoice_id)
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $result = $CI->Invoices->delete_invoice($invoice_id);
        if ($result) {
            $this->session->set_userdata(array('message' => display('successfully_delete')));
            redirect('Cinvoice/manage_invoice');
        }
    }

    public function autocompleteproductsearch()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $product_name   = $this->input->post('product_name', TRUE);
        $pr_status = $this->input->post('pr_status', TRUE);
        $product_info   = $CI->Invoices->autocompletproductdata($product_name, $pr_status);

        if (!empty($product_info)) {
            $list[''] = '';
            foreach ($product_info as $value) {
                $json_product[] = array('label' => $value['product_name_bn'] . '-' . $value['product_name'] . '', 'value' => $value['product_id']);
            }
        } else {
            $json_product[] = 'No Product Found';
        }
        echo json_encode($json_product);
    }

    //AJAX INVOICE STOCKs
    public function product_stock_check($product_id)
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $purchase_stocks = $CI->Invoices->get_total_purchase_item($product_id);
        $total_purchase = 0;
        if (!empty($purchase_stocks)) {
            foreach ($purchase_stocks as $k => $v) {
                $total_purchase = ($total_purchase + $purchase_stocks[$k]['quantity']);
            }
        }
        $sales_stocks = $CI->Invoices->get_total_sales_item($product_id);
        $total_sales = 0;
        if (!empty($sales_stocks)) {
            foreach ($sales_stocks as $k => $v) {
                $total_sales = ($total_sales + $sales_stocks[$k]['quantity']);
            }
        }

        $final_total = ($total_purchase - $total_sales);
        return $final_total;
    }

    //    =========== its for 1 increment =============
    function randomChange($myValue)
    {
        $random = rand(0, 1);
        if ($random > 0)
            return $myValue + 1;

        return $myValue - 1;
    }

    //This function is used to Generate Key
    public function generator($lenth)
    {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
    //customer previous due
    public function previous()
    {
        $CI = &get_instance();
        $CI->load->model('Customers');
        $customer_id = $this->input->post('customer_id', TRUE);
       

        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        $this->db->where('a.customer_id', $customer_id);
        $result = $this->db->get()->result_array();
        $balance = $result[count($result)-1]['balance'];
        $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
        $data['balance'] = !empty($balance) ? $balance : 0;
        $data['membership_id'] = $cusifo->membership_id;
        $this->db->select("
        (SELECT SUM(ce1.points) FROM customers_earning ce1
        WHERE ce1.type = '1' AND ce1.customer_id = customer_information.customer_id) as earning_points,
        (SELECT SUM(ce2.points) FROM customers_earning ce2
        WHERE ce2.type = '2' AND ce2.customer_id = customer_information.customer_id) as burning_points");
        $this->db->from('customer_information');
        $this->db->join('customers_earning', 'customers_earning.customer_id = customer_information.customer_id', 'left');
         $this->db->where('customers_earning.customer_id', $customer_id);
         $result = $this->db->get()->result_array();
        $data['available_points'] = $result ? $result[0]['earning_points'] - $result[0]['burning_points'] : 0;
        // $data['available_points'] = $$result[0]['total_points'];
        // $b = (!empty($balance) ? $balance : 0);
        // if ($b) {
        //     echo  $b;
        // } else {
        //     echo  $b;
        // }
        echo json_encode($data);
    }



    public function customer_autocomplete()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $CI->load->model('Customers');
        $customer_id    = $this->input->post('customer_id', TRUE);
        $sale_type    = $this->input->post('sale_type', TRUE);
        $customer_info   = $CI->Customers->customer_search($customer_id, $sale_type);

        if ($customer_info) {
            $json_customer[''] = '';
            foreach ($customer_info as $value) {
                $json_customer[] = array('label' => $value['customer_name'] . ' (' . $value['customer_name_bn'] . ')'.' (' . $value['customer_mobile'] . ')', 'value' => ["id" => $value['customer_id'], "mobile" => $value['customer_mobile']]);
            }
        } else {
            $json_customer[] = 'Not found';
        }
        echo json_encode($json_customer);
    }
    //csv excel
    public function exportinvocsv()
    {
        // file name
        $this->load->model('Invoices');
        $filename = 'sale_' . date('Ymd') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
        // get data
        $invoicedata = $this->Invoices->invoice_csv_file();
        // file creation
        $file = fopen('php://output', 'w');

        $header = array('invoice_no', 'invoice_id', 'customer_name', 'date', 'total_amount');
        fputcsv($file, $header);
        foreach ($invoicedata as $line) {
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
    }


    public function gui_pos()
    {
        $CI = &get_instance();
        $this->load->model('Invoices');
        $this->load->model('Products');
        $this->load->model('Web_settings');
        $CI->load->model('Warehouse');
        $CI->load->model('Courier');
        $CI->load->model('Customers');
        $CI->load->model('Settings');
        $CI->load->model('Rqsn');
        $CI->load->model('Reports');
        $outlet_list_to    = $CI->Rqsn->outlet_list_to();
        $cw_list    = $CI->Rqsn->cw_list();
        $outlet_id = $CI->session->userdata('outlet_id');
        $outlet_user = $CI->Warehouse->get_outlet($outlet_id);
        //$outlet_user        = $CI->Warehouse->get_outlet_user();

        $cw = $CI->Warehouse->central_warehouse();

        $bank_list          = $CI->Web_settings->bank_list();
        $bkash_list        = $CI->Web_settings->bkash_list();
        $nagad_list        = $CI->Web_settings->nagad_list();
        $branch_list        = $CI->Courier->get_branch_list();
        // $outlet_id = $CI->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        $outlet_id = $this->session->userdata('outlet_id');
        $outlet_info = $CI->Warehouse->get_outlet($outlet_id);

        //        echo $outlet_id;exit();
        $itemlist = $CI->Products->allproduct();
        // $itemlist=$CI->Products->allproduct_batch_wise();

        // echo '<pre>';
        // print_r($outlet_info);
        // print_r($cw);
        // print_r($outlet_id);
        // exit();
        $vat   = $CI->Settings->read_all_pos_setting('vat')[0]['value'];
        $delivery_charge   = $CI->Settings->read_all_pos_setting('delivery_charge')[0]['value'];
        $service_charge   = $CI->Settings->read_all_pos_setting('service_charge')[0]['value'];
        $tax   = $CI->Settings->read_all_pos_setting('tax')[0]['value'];
        $crm   = $CI->Settings->read_all_pos_setting('crm')[0]['value'];
        $sale_discount   = $CI->Settings->read_all_pos_setting('total_sale_flat_discount')[0]['value'];
        $sale_discount_percent   = $CI->Settings->read_all_pos_setting('total_sale_percentage_discount')[0]['value'];
        $item_wise_flat_discount   = $CI->Settings->read_all_pos_setting('item_wise_flat_discount')[0]['value'];
        foreach ($itemlist as $k => $v) {

            if ($outlet_id == 'HK7TGDT69VFMXB7') {
                $expired_stock  = $this->Reports->getExpiryCheckList($v->product_id, 1)['expired_stock'];
                $itemlist[$k]->stock = $expired_stock;
            } else {
                $expired_stock  = $this->Rqsn->expiry_outlet_stock($v->product_id, 1)['expired_stock'];
                $itemlist[$k]->stock = $expired_stock;
            }

            // if ($outlet_id == 'HK7TGDT69VFMXB7') {
            //     $stock_details = $CI->Reports->getExpiryCheckList($product_id, 1)['aaData'];
            // } else {
            //     $stock_details = $CI->Rqsn->expiry_outlet_stock($product_id, 1)['aaData'];
            // }
            // $stock = $stock_details[0]['stok_quantity'];
        }
        $eligible_points = $this->db->select('eligible_points')
            ->from('web_setting')
            ->get()
            ->result_array();
          //echo '<pre>';print_r($eligible_points);exit();
        $taxfield = $this->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column = count($tablecolumn) - 4;
        $data['title'] = display('gui_pos');
        $saveid = $this->session->userdata('user_id');
        $walking_customer      = $this->Invoices->walking_customer();
        $data['customer_id']   = $walking_customer[0]['customer_id'];
        $data['customer_name'] = $walking_customer[0]['customer_name'];
        $data['categorylist']  = $this->Invoices->category();
        $customer_details      = $this->Invoices->pos_customer_setup();
        $data['customerlist']  = $this->Invoices->customer_dropdown();
        $currency_details      = $this->Web_settings->retrieve_setting_editdata();
        $data['customer_name'] = $customer_details[0]['customer_name'];
        $data['customer_name_bn'] = $customer_details[0]['customer_name_bn'];
        $data['customer_id']   = $customer_details[0]['customer_id'];
        $data['itemlist']      =  $itemlist;
        $data['discount_type'] = $currency_details[0]['discount_type'];
        $data['position']       = $currency_details[0]['currency_position'];
        $data['currency']       = $currency_details[0]['currency'];
        $data['taxes']         = $taxfield;
        $data['taxnumber']     = $num_column;
        $data['todays_invoice'] = $this->Invoices->todays_invoice();
        $data['outlet_id']    = $outlet_id;
        $data['outlet_list']    = $outlet_user;
        $data['outlet_info']    = $outlet_info;
        $data['cw']             = $cw;
        $data['bkash_list']             = $bkash_list;
        $data['bank_list']             = $bank_list;
        $data['nagad_list']             = $nagad_list;
        $data['rocket_list']             = $CI->Web_settings->rocket_list();
        $data['vat']           = $vat;
        $data['tax']     = $tax;
        $data['crm']     = $crm;
        $data['points_eligibility']     = $eligible_points[0]['eligible_points'];
        $data['service_charge'] = $service_charge;
        $data['delivery_charge'] =$delivery_charge;
        $data['sale_discount']           = $sale_discount;
        $data['sale_discount_percent']     = $sale_discount_percent;
        $data['item_wise_flat_discount'] = $item_wise_flat_discount;
        $data['card_list'] = $CI->Customers->card_list();
        $data['cw_list'] = $cw_list;
        $data['outlet_list_to'] = $outlet_list_to;
        // $content  = $this->parser->parse('invoice/gui_pos_invoice_copy', $data, true);
        //echo '<pre>';print_r($data);exit();
        $content  = $this->parser->parse('invoice/gui_pos_invoice', $data, true);
        $this->template->full_admin_html_view($content);
    }

    public function get_cat_wise_product()
    {

        $CI = &get_instance();
        $this->load->model('Invoices');
        $this->load->model('Products');
        $this->load->model('Warehouse');
        $this->load->model('Rqsn');
        $this->load->model('Reports');
        $cat_id = $this->input->post('category_id');
        $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        $itemlist = '';
        if ($cat_id == 'All') {
            $itemlist = $this->Products->allproduct();
        } else {
            $itemlist = $this->Products->get_product_by_cat(1, $cat_id);
        }

        if (!empty($itemlist)) {
            foreach ($itemlist as $k => $v) {

                if ($outlet_id == 'HK7TGDT69VFMXB7') {

                    $expired_stock  = $this->Reports->getExpiryCheckList($v->product_id)['expired_stock'];


                    $itemlist[$k]->stock = $expired_stock;
                } else {
                    $expired_stock  = $this->Rqsn->expiry_outlet_stock($v->product_id)['expired_stock'];

                    $itemlist[$k]->stock = $expired_stock;
                }
            }
        } else {
            $title['title'] = 'Product Not found';
            $this->load->view('invoice/productnot_found', $title);
        }


        // echo '<pre>';print_r($itemlist);exit();



        $data['itemlist']      =  $itemlist;
        $this->load->view('invoice/product_list', $data);
    }

    //gui pos invoice
    public function gui_pos_invoice()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $product_id = $this->input->post('product_id', TRUE);
        $outlet_id  = $this->input->post('outlet_id', TRUE);
        $membership_id  = $this->input->post('membership_id', TRUE);

        $product_details = $CI->Invoices->pos_invoice_setup($product_id, $outlet_id);
        $crm_points = $CI->Invoices->getPoints($product_id,$membership_id);
        $crm_burning_points = $CI->Invoices->getBurningPoints($product_id,$membership_id);
        $product_points_discount = $crm_burning_points ? $crm_burning_points[0]['points'] : 0;
        $product_percentage_discount = $crm_burning_points ? $crm_burning_points[0]['percentage'] : 0;
        // echo "<pre>";
        // print_r($crm_points);
        // exit();
        $points = $crm_points ? $crm_points[0]['points'] : 0;
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $taxfield = $CI->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $prinfo = $this->db->select('*')->from('product_information')->where('product_id', $product_id)->get()->result_array();

        $tr = " ";
        if (!empty($product_details)) {
            $product_id = $this->generator(5);
            $serialdata = explode(',', $product_details->serial_no);
            if ($product_details->total_product > 0) {
                $qty = 1;
            } else {
                $qty = 1;
            }


            $tr .= "<tr id=\"row_" . $product_details->product_id . "\">
				        <td class=\"\" style=\"width:200px\">
						<div class='col-sm-12 ' >
						<div class='form-group row cart-list'>
						
						<div class='col-sm-4 '  >

							<a  class=\"btn btn-xs btn-success  add_bt text-center\" type=\"button\"  onclick=\"add_qty('" . $product_details->product_id . "')\">" . ' <i class="fa fa-plus"></i>' . "</a>
							<a  class=\"btn btn-xs btn-danger  minus_bt text-center\" type=\"button\"  onclick=\"reduce_qty('" . $product_details->product_id . "')\">" . '<i class="fa fa-minus"></i>' . "</a>
                            </div>
                            <div class='col-sm-8'>
							<input type=\"text\"  name=\"product_name\" onkeypress=\"invoice_productList('" . $product_details->product_id . "');\" class=\"form-control  box productSelection \" value='" . html_escape($product_details->product_name_bn) . "' placeholder='" . display('product_name') . "' required=\"\" id=\"product_name_" . $product_details->product_id . "\" tabindex=\"\" readonly>
                              </div>

                        </div>
                        </div>
                        							<input type=\"hidden\" class=\"form-control autocomplete_hidden_value product_id_" . $product_details->product_id . "\" name=\"product_id[]\" id=\"SchoolHiddenId_" . $product_details->product_id . "\" value = \"$product_details->product_id\"/>
							<input type=\"hidden\" class=\"form-control  product_id_" . $product_details->sku . "\" name=\"skuu[]\" id=\"SchoolHiddenSku_" . $product_details->sku . "\" value = \"$product_details->sku\"/>
						</td>


                        <td  width=\"100\">
                                                    <input type=\"hidden\" name=\"available_quantity[]\" class=\"form-control text-right available_quantity_" . $product_details->product_id . "\" value='" . $product_details->total_product . "' readonly=\"\" id=\"available_quantity_" . $product_details->product_id . "\"/>

                            <input type=\"text\" name=\"product_quantity[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" class=\"total_qntt_" . $product_details->sku . " form-control box total_qnty text-right\" id=\"total_qntt_" . $product_details->product_id . "\" placeholder=\"0.00\" min=\"0\" value='" . $qty . "'/>
                        </td>

                  


		                <td class=\"text-center\"  width=\"200\">
							<input style=\"width: 120px; display:inline-block\" type=\"text\" name=\"product_rate[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" value='" . $product_details->price . "' id=\"price_item_" . $product_details->product_id . "\" class=\"price_item1 form-control box text-right\" required  placeholder=\"0.00\" min=\"0\" readonly/>
		

						</td>

	
						<td class=\"text-right\" style=\"width:100px\" hidden>
							<input class=\"total_price_wd box form-control text-right\" type=\"text\" name=\"total_price_wd[]\" id=\"total_price_wd_" . $product_details->product_id . "\" value='" . $product_details->purchase_price . "' tabindex=\"-1\" readonly=\"readonly\"/>
						</td>
						
							<td >
							<input type=\"text\" name=\"discount[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" id=\"discount_" . $product_details->product_id . "\" class=\"form-control text-right\" placeholder=\"0.00\" min=\"0\"/>
                            <input type=\"hidden\" name=\"distributed_discount[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" id=\"distributed_discount_" . $product_details->product_id . "\" class=\"form-control text-right\" placeholder=\"0.00\" min=\"0\"/>
                            <input type=\"hidden\" name=\"item_total_discount[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" id=\"item_total_discount_" . $product_details->product_id . "\" class=\"form-control text-right\" placeholder=\"0.00\" min=\"0\"/>
							<input type=\"hidden\" value=" . $currency_details[0]['discount_type'] . " name=\"discount_type\" id=\"discount_type_" . $product_details->product_id . "\">
						</td>


				

						<td class=\"text-right\" style=\"width:100px\" hidden>
							<input class=\"vat form-control box text-right\" type=\"text\" name=\"vat[]\" id=\"vat_" . $product_details->product_id . "\" value='" . $product_details->vat . "' tabindex=\"-1\" readonly=\"readonly\"/>
							<input class=\"form-control box text-right\" type=\"text\" name=\"vat_percent[]\" id=\"vat_percent_" . $product_details->product_id . "\" value='" . $product_details->vat_percent . "' tabindex=\"-1\" readonly=\"readonly\"/>
                            <input class=\" form-control box text-right\" type=\"hidden\" name=\"vat_type[]\" id=\"vat_type_" . $product_details->product_id . "\" value='" . $product_details->vat_type . "' tabindex=\"-1\" readonly=\"readonly\"/>

						</td>
						<td class=\"text-right\" style=\"width:100px\" hidden>
							<input class=\"tax form-control box text-right\" type=\"text\" name=\"tax[]\" id=\"tax_" . $product_details->product_id . "\" value='" . $product_details->tax . "' tabindex=\"-1\" readonly=\"readonly\"/>
							<input class=\"form-control box text-right\" type=\"text\" name=\"tax_percent[]\" id=\"tax_percent_" . $product_details->product_id . "\" value='" . $product_details->tax_percent . "' tabindex=\"-1\" readonly=\"readonly\"/>
                            <input class=\" form-control box text-right\" type=\"hidden\" name=\"tax_type[]\" id=\"tax_type_" . $product_details->product_id . "\" value='" . $product_details->tax_type . "' tabindex=\"-1\" readonly=\"readonly\"/>
						</td>
					

					

						<td class=\"text-right\" style=\"width:100px\">
                        <input class=\"points box form-control text-right\" type=\"hidden\" name=\"points[]\" id=\"points_" . $product_details->product_id . "\" value='" . $points . "' tabindex=\"-1\" readonly=\"readonly\"/>
							<input class=\"total_points box form-control text-right\" type=\"hidden\" name=\"total_points[]\" id=\"total_points_" . $product_details->product_id . "\" value='' tabindex=\"-1\" readonly=\"readonly\"/>
							<input class=\"product_points_discount box form-control text-right\" type=\"hidden\" name=\"product_points_discount[]\" id=\"product_points_discount_" . $product_details->product_id . "\" value='" . $product_points_discount . "' tabindex=\"-1\" readonly=\"readonly\"/>
							<input class=\"product_percentage_discount box form-control text-right\" type=\"hidden\" name=\"product_percentage_discount[]\" id=\"product_percentage_discount_" . $product_details->product_id . "\" value='" . $product_percentage_discount . "' tabindex=\"-1\" readonly=\"readonly\"/>
                            <input class=\"product_percentage_discount_amount box form-control text-right\" type=\"hidden\" name=\"product_percentage_discount_amount[]\" id=\"product_percentage_discount_amount_" . $product_details->product_id . "\" value='' tabindex=\"-1\" readonly=\"readonly\"/>
                            <input class=\"product_burned_points box form-control text-right\" type=\"hidden\" name=\"product_burned_points[]\" id=\"product_burned_points_" . $product_details->product_id . "\" value='' tabindex=\"-1\" readonly=\"readonly\"/>
                            <input class=\"total_price box form-control text-right\" type=\"text\" name=\"total_price[]\" id=\"total_price_" . $product_details->product_id . "\" value='" . $product_details->purchase_price . "' tabindex=\"-1\" readonly=\"readonly\"/>
						</td>

						<td>";
            $sl = 0;
            foreach ($taxfield as $taxes) {
                $txs = 'tax' . $sl;
                $tr .= "<input type=\"hidden\" id=\"total_tax" . $sl . "_" . $product_details->product_id . "\" class=\"total_tax" . $sl . "_" . $product_details->product_id . "\" value='" . $prinfo[0][$txs] . "'/>
                            <input type=\"hidden\" id=\"all_tax" . $sl . "_" . $product_details->product_id . "\" class=\" total_tax" . $sl . "\" value='" . $prinfo[0][$txs] * $product_details->purchase_price . "' name=\"tax[]\"/>";
                $sl++;
            }

            $tr .= "<input type=\"hidden\"  class=\"total_discount\"  name=\"total_discount[]\" id=\"total_discount_" . $product_details->product_id . "\" />
            
							<input type=\"hidden\" id=\"all_discount_" . $product_details->product_id . "\" class=\" dppr\"/>
							<a  class=\"btn btn-xs btn-danger  text-center\" type=\"button\"  onclick=\"deleteRow(this)\">" . '<i class="fa fa-close"></i>' . "</a>
						</td>
					</tr>";
            echo $tr;
        } else {
            return false;
        }
    }

    public function getitemlist()
    {
        $this->load->model('Invoices');
        $this->load->model('Warehouse');
        $this->load->model('Rqsn');
        $this->load->model('Reports');
        $prod = $this->input->post('product_name', TRUE);
        $catid = $this->input->post('category_id', TRUE);
        $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        $getproduct = $this->Invoices->searchprod($prod);
        if (!empty($getproduct)) {

            foreach ($getproduct as $k => $v) {

                if ($outlet_id == 'HK7TGDT69VFMXB7') {
                    $expired_stock  = $this->Reports->getExpiryCheckList($v->product_id)['expired_stock'];
                    $getproduct[$k]->stock = $expired_stock;
                } else {
                    $expired_stock  = $this->Rqsn->expiry_outlet_stock($v->product_id)['expired_stock'];
                    $getproduct[$k]->stock = $expired_stock;
                }
            }
            $data['itemlist'] = $getproduct;
            $this->load->view('invoice/getproductlist', $data);
        } else {
            $title['title'] = 'Product Not found';
            $this->load->view('invoice/productnot_found', $title);
        }
    }
    public function instant_customer()
    {
        $this->load->model('Customers');

        // echo '<pre>';print_r($_POST);exit();

        $data = array(
            'customer_id_two'    => $this->input->post('customer_id_two', TRUE),
            'contact_person'    => $this->input->post('contact_person', TRUE),
            'shop_name'    => $this->input->post('shop_name', TRUE),
            'contact'    => $this->input->post('contact', TRUE),
            'customer_name'    => $this->input->post('customer_name', TRUE),
            'customer_name_bn'    => $this->input->post('customer_name_bn', TRUE),
            'membership_id'   => $this->input->post('membership_id', TRUE),
            'card_number'   => $this->input->post('card_number', TRUE),
            'customer_address' => $this->input->post('address', TRUE),
            'customer_mobile' => $this->input->post('mobile', TRUE),
            //            'customer_mobile'  => (!empty($this->input->post('mobile', TRUE)) ? $this->input->post('mobile', TRUE) : ''),
            'customer_email'   => $this->input->post('email', TRUE),
            'cus_type'   => $this->input->post('cus_type', TRUE),
            'outlet_id'       => $this->input->post('outlet_id', TRUE),
            'status'           => 1
        );



        $result = $this->Customers->customer_entry($data);
        if ($result) {

            $customer_id = $this->db->insert_id();
            $vouchar_no = $this->auth->generator(10);
            //Customer  basic information adding.
            $coa = $this->Customers->headcode();
            if ($coa->HeadCode != NULL) {
                $headcode = $coa->HeadCode + 1;
            } else {
                $headcode = "102030001";
            }
            $c_acc = $customer_id . '-' . $this->input->post('customer_name', TRUE);
            $createby = $this->session->userdata('user_id');
            $createdate = date('Y-m-d H:i:s');

            $customer_coa = [
                'HeadCode'         => $headcode,
                'HeadName'         => $c_acc,
                'PHeadName'        => 'Customer Receivable',
                'HeadLevel'        => '4',
                'IsActive'         => '1',
                'IsTransaction'    => '1',
                'IsGL'             => '0',
                'HeadType'         => 'A',
                'IsBudget'         => '0',
                'IsDepreciation'   => '0',
                'customer_id'      => $customer_id,
                'DepreciationRate' => '0',
                'CreateBy'         => $createby,
                'CreateDate'       => $createdate,
            ];
            //Previous balance adding -> Sending to customer model to adjust the data.
            $this->db->insert('acc_coa', $customer_coa);
            $this->Customers->previous_balance_add($this->input->post('previous_balance', TRUE), $customer_id);

            //            $this->session->set_userdata(array('message' => display('save_successfully')));
            //redirect(base_url('Cinvoice'));
            $data['status']        = true;
            $data['message']       = display('save_successfully');
            $data['customer_id']   = $customer_id;
            $data['customer_name'] = $data['customer_name'];
        } else {

            //            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            //  redirect(base_url('Cinvoice'));
            $data['status'] = false;
            $data['error_message'] = display('please_try_again');
        }
        echo json_encode($data);
    }


    public function add_cheque()
    {
        $this->load->model('Invoices');
        $invoice_id = $this->input->post('invoice_id', TRUE);
        $cheque_date = $this->input->post('cheque_date', TRUE);
        $cheque_no = $this->input->post('cheque_no', TRUE);
        $cheque_type = $this->input->post('cheque_type', TRUE);
        $amount = $this->input->post('amount', TRUE);

        $this->load->library('upload');
        $image = array();
        $ImageCount = count($_FILES['image']['name']);
        for ($i = 0; $i < $ImageCount; $i++) {
            $_FILES['file']['name']       = $_FILES['image']['name'][$i];
            $_FILES['file']['type']       = $_FILES['image']['type'][$i];
            $_FILES['file']['tmp_name']   = $_FILES['image']['tmp_name'][$i];
            $_FILES['file']['error']      = $_FILES['image']['error'][$i];
            $_FILES['file']['size']       = $_FILES['image']['size'][$i];

            // File upload configuration
            $uploadPath = 'my-assets/image/cheque/';
            $config['upload_path'] = $uploadPath;
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['encrypt_name']  = TRUE;

            // Load and initialize upload library
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            // Upload file to server
            if ($this->upload->do_upload('file')) {
                // Uploaded file data
                $imageData = $this->upload->data();
                $uploadImgData[$i]['image'] = $config['upload_path'] . $imageData['file_name'];
                $image_url = base_url() . $uploadImgData[$i]['image'];
            }

            // echo '<pre>';print_r( $uploadImgData[$i]['image']);exit();
        }





        if (!empty($cheque_no) && !empty($cheque_date)) {
            foreach ($cheque_no as $key => $value) {


                $data['cheque_no'] = $value;
                $data['invoice_id'] = $invoice_id;
                $data['cheque_id'] = $this->generator(10);

                $data['cheque_type'] = $cheque_type[$key];
                $data['cheque_date'] = $cheque_date[$key];
                $data['amount'] = $amount[$key];
                $data['image'] = (!empty($image_url) ?    $image_url : base_url('my-assets/image/product.png'));;
                $data['status'] = 2;

                //   echo '<pre>';print_r($data);exit();
                // $this->ProductModel->add_products($data);


                $result = $this->db->insert('cus_cheque', $data);
            }
        }

        //        $data=array(
        //
        //            'cheque_id'=>$this->generator(10),
        //            'invoice_id'=>$this->generator(10),
        //            'cheque_no'=>$cheque_no,
        //            'amount'=>$amount,
        //            'cheque_date'=>$cheque_date,
        //            'status'=>2,
        //
        //        );

        // echo '<pre>';print_r($data);


        // $this->db->insert('cus_cheque', $data);


        if ($result) {

            $data['status']        = true;
            $data['message']       = display('save_successfully');
            redirect('Admin_dashboard/sales_cheque_report');
        } else {
            $data['status'] = false;
            $data['error_message'] = display('please_try_again');
            redirect('Admin_dashboard/sales_cheque_report');
        }
        // echo json_encode($data);
    }

    public function delete_payment_row()
    {
        $row_id = $this->input->post('id', true);

        $this->db->where('id', $row_id);
        $this->db->delete('paid_amount');
    }

    public function add_receiver()
    {
        $receiver_name = $this->input->post('receiver_name', TRUE);
        $receiver_number = $this->input->post('receiver_number', TRUE);

        $data = array(
            'receiver_name'  => $receiver_name,
            'receiver_number'   => $receiver_number
        );

        $html = "";
        $ret = array();

        if ($this->db->insert('receiever_info', $data)) {
            $rec_id = $this->db->insert_id();
            $html .= '<option value="' . $rec_id . '" selected>' . $receiver_name . '</option>';
            $ret['status']  = true;
            $ret['message'] = 'Inserted Successfully';
            $ret['html']    = $html;
        } else {
            $ret['error_message'] = 'Error in insertion';
        }

        echo json_encode($ret);
    }

    public function get_receiver_num()
    {
        $rec_id = $this->input->post('rec_id', TRUE);

        $num = $this->db->select('receiver_number')
            ->from('receiever_info')
            ->where('id', $rec_id)
            ->get()
            ->result_array();

        // echo '<pre>';
        // print_r($num);


        // $data['number'] = $num->receiver_number;

        echo $num[0]['receiver_number'];
    }

    public function customer_due_payment()
    {
        $CI = &get_instance();
        $CI->load->model('Invoices');
        $CI->load->model('Customers');

        $due_invoices = $CI->Invoices->due_invoices();

        // echo '<pre>';
        // print_r($due_invoices);
        // exit();


        foreach ($due_invoices as $k => $v) {
            $cus_id = $due_invoices[$k]['customer_id'];
            $cus_info = $CI->Customers->retrieve_customer_editdata($cus_id)[0];

            // echo '<pre>';
            // print_r($cus_info);

            $due_invoices[$k]['cus_details'] = $cus_info['customer_name'] . ' (' . $cus_info['customer_mobile'] . ')';
        }
        // exit();

        $data = array(
            'title' => 'Invoices with Due',
            'due_invoices'  => $due_invoices,
        );

        $view  = $this->parser->parse('invoice/due_invoice_list', $data, true);
        $this->template->full_admin_html_view($view);
    }

    public function due_invoice_view($invoice_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $content = $CI->linvoice->due_invoice_edit_data($invoice_id);
        $this->template->full_admin_html_view($content);
    }

    public function due_invoice_update()
    {
        $CI = &get_instance();
        $this->load->library('upload');


        $tot_pay_amount = $this->input->post('paid_amount', TRUE);
        $tot_due_amount = $this->input->post('due_amount', TRUE);

        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');
        $invoice_id = $this->input->post('invoice_id', TRUE);
        $pay_type = $this->input->post('paytype', TRUE);

        $p_amnt_total = $this->input->post('p_amnt_total', TRUE);

        $paid = $this->input->post('p_amount', TRUE);

        $customer_id = $this->input->post('customer_id', TRUE);

        $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
        $headn = $customer_id . '-' . $cusifo->customer_name;
        $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
        $customer_headcode = $coainfo->HeadCode;

        $invoice_no  = $this->input->post('invoice', TRUE);

        $cq_bank = $this->input->post('bank_id', TRUE);
        $cheque_date = $this->input->post('cheque_date', TRUE);
        $cheque_no = $this->input->post('cheque_no', TRUE);
        $cheque_type = $this->input->post('cheque_type', TRUE);
        $amount = $this->input->post('amount', TRUE);


        $bank_id = $this->input->post('bank_id_m', TRUE);
        $bkash_id = $this->input->post('bkash_id', TRUE);
        $bkashname = '';
        $card_id = $this->input->post('card_id', TRUE);
        $nagad_id = $this->input->post('nagad_id', TRUE);

        // echo '<pre>';
        // print_r($bkash_id);
        // exit();

        if ($_FILES['image']['name']) {
            $ImageCount = count($_FILES['image']['name']);
            for ($i = 0; $i < $ImageCount; $i++) {
                $_FILES['file']['name']       = $_FILES['image']['name'][$i];
                $_FILES['file']['type']       = $_FILES['image']['type'][$i];
                $_FILES['file']['tmp_name']   = $_FILES['image']['tmp_name'][$i];
                $_FILES['file']['error']      = $_FILES['image']['error'][$i];
                $_FILES['file']['size']       = $_FILES['image']['size'][$i];

                // File upload configuration
                $uploadPath = 'my-assets/image/cheque/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
                $config['encrypt_name']  = TRUE;

                // Load and initialize upload library
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                // print_r('ues');

                // Upload file to server
                if ($this->upload->do_upload('file')) {
                    // Uploaded file data

                    $imageData = $this->upload->data();
                    $uploadImgData[$i]['image'] = $config['upload_path'] . $imageData['file_name'];
                    $image_url = base_url() . $uploadImgData[$i]['image'];
                    // print_r($image_url);
                }

                // echo '<pre>';print_r( $uploadImgData[$i]['image']);exit();
            }
        }

        if (!empty($cheque_no) && !empty($cheque_date)) {

            foreach ($cheque_no as $key => $value) {


                $data['cheque_no'] = $value;
                $data['invoice_id'] = $invoice_id;
                $data['cheque_id'] = $this->generator(10);
                $data['cheque_type'] = $cheque_type[$key];
                $data['cheque_date'] = $cheque_date[$key];
                $data['amount'] = $amount[$key];
                $data['bank_name'] = $cq_bank[$key];
                $data['image'] = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));
                $data['status'] = 2;

                //  echo '<pre>';print_r($data);
                // $this->ProductModel->add_products($data);
                if (!empty($data)) {
                    $this->db->insert('cus_cheque', $data);
                }
            }
        }

        for ($i = 0; $i < count($pay_type); $i++) {

            // $account_name = '';

            // switch ($pay_type[$i]) {
            //     case 1:
            //         $account_name = '';
            //         break;

            //     case 2:
            //         $account_name = '';
            //         break;

            //     case 3:
            //         $account_name = $bkashname;
            //         break;

            //     case 4:
            //         $account_name = $bankname;
            //         break;

            //     case 5:
            //         $account_name = $nagadname;
            //         break;

            //     case 6:
            //         $account_name = '';
            //         break;
            // }

            // $data = array(
            //     'invoice_id'    => $invoice_id,
            //     'pay_type'      => $pay_type[$i],
            //     'amount'        => $p_amount[$i],
            //     'account'       => $account_name
            // );

            // $this->db->insert('paid_amount', $data);

            if ($pay_type[$i] == 1) {

                $cc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  1020101,
                    'Narration'      =>  'Cash in Hand in Sale for Invoice ID - ' . $invoice_id . ' customer- ' . $cusifo->customer_name,
                    'Debit'          =>  $paid[$i],
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       =>  $createby,
                    'CreateDate'     =>  $createdate,
                    'IsAppove'       =>  1,

                );

                $data = array(
                    'invoice_id'    => $invoice_id,
                    'pay_type'      => $pay_type[$i],
                    'pay_date'      => $createdate,
                    'amount'        => $paid[$i],
                    'account'       => '',
                    'COAID'         => 1020101,
                    'status'        =>  1,
                );

                $this->db->insert('paid_amount', $data);

                $cuscredit = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  $customer_headcode,
                    'Narration'      =>  'Customer credit (Cash In Hand) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                    'Debit'          =>  0,
                    'Credit'         =>  $paid[$i],
                    'IsPosted'       => 1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $cuscredit);

                $this->db->insert('acc_transaction', $cc);
            }
            if ($pay_type[$i] == 4) {
                if (!empty($bank_id)) {
                    $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id[$i])->get()->row()->bank_name;

                    $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                } else {
                    $bankcoaid = '';
                }
                $bankc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INVOICE',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  $bankcoaid,
                    'Narration'      =>  'Paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                    'Debit'          =>  $paid[$i],
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       =>  $createby,
                    'CreateDate'     =>  $createdate,
                    'IsAppove'       =>  1,

                );

                $data = array(
                    'invoice_id'    => $invoice_id,
                    'pay_type'      => $pay_type[$i],
                    'amount'        => $paid[$i],
                    'pay_date'      => $createdate,
                    'account'       => $bankname,
                    'COAID'         => $bankcoaid,
                    'status'        =>  1,
                );

                $this->db->insert('paid_amount', $data);

                $cuscredit = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  $customer_headcode,
                    'Narration'      =>  'Customer credit (Cash In Bank) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                    'Debit'          =>  0,
                    'Credit'         =>  $paid[$i],
                    'IsPosted'       => 1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $cuscredit);

                $this->db->insert('acc_transaction', $bankc);
            }
            if ($pay_type[$i] == 3) {
                if (!empty($bkash_id)) {
                    $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id[$i])->get()->row()->bkash_no;

                    $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK - ' . $bkashname)->get()->row()->HeadCode;
                } else {
                    $bkashcoaid = '';
                }
                $bkashc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INVOICE',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  $bkashcoaid,
                    'Narration'      =>  'Cash in Bkash paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                    'Debit'          =>  $paid[$i],
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       =>  $createby,
                    'CreateDate'     =>  $createdate,
                    'IsAppove'       =>  1,

                );

                $data = array(
                    'invoice_id'    => $invoice_id,
                    'pay_type'      => $pay_type[$i],
                    'amount'        => $paid[$i],
                    'account'       => $bkashname,
                    'pay_date'      => $createdate,
                    'COAID'         => $bkashcoaid,
                    'status'        =>  1,
                );

                $this->db->insert('paid_amount', $data);

                $cuscredit = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  $customer_headcode,
                    'Narration'      =>  'Customer credit (Cash In Bkash) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                    'Debit'          =>  0,
                    'Credit'         =>  $paid[$i],
                    'IsPosted'       => 1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $cuscredit);

                $this->db->insert('acc_transaction', $bkashc);
            }
            if ($pay_type[$i] == 5) {

                if (!empty($nagad_id)) {
                    $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id[$i])->get()->row()->nagad_no;

                    $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG - ' . $nagadname)->get()->row()->HeadCode;
                } else {
                    $nagadcoaid = '';
                }

                $nagadc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INVOICE',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  $nagadcoaid,
                    'Narration'      =>  'Cash in Nagad paid amount for customer  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                    'Debit'          =>  $paid[$i],
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       =>  $createby,
                    'CreateDate'     =>  $createdate,
                    'IsAppove'       =>  1,

                );

                $data = array(
                    'invoice_id'    => $invoice_id,
                    'pay_type'      => $pay_type[$i],
                    'amount'        => $paid[$i],
                    'account'       => $nagadname,
                    'pay_date'      => $createdate,
                    'COAID'         => $nagadcoaid,
                    'status'        =>  1,
                );

                $this->db->insert('paid_amount', $data);

                $cuscredit = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  $customer_headcode,
                    'Narration'      =>  'Customer credit (Cash In Nagad) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                    'Debit'          =>  0,
                    'Credit'         =>  $paid[$i],
                    'IsPosted'       => 1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );
                $this->db->insert('acc_transaction', $cuscredit);

                $this->db->insert('acc_transaction', $nagadc);
            }
            if ($pay_type[$i] == 6) {

                $card_info = $CI->Settings->get_real_card_data($card_id[$i]);

                if (!empty($card_id)) {
                    $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $card_info[0]['bank_id'])->get()->row()->bank_name;

                    $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                } else {
                    $bankcoaid = '';
                }
                $bankc = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INVOICE',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  $bankcoaid,
                    'Narration'      =>  'Paid amount for customer in card - ' . $card_info[0]['card_no'] . '  Invoice ID - ' . $invoice_id . ' customer -' . $cusifo->customer_name,
                    'Debit'          => ($paid[$i]) - ($paid[$i] * ($card_info[0]['percentage'] / 100)),
                    'Credit'         =>  0,
                    'IsPosted'       =>  1,
                    'CreateBy'       =>  $createby,
                    'CreateDate'     =>  $createdate,
                    'IsAppove'       =>  1,

                );

                $data = array(
                    'invoice_id'    => $invoice_id,
                    'pay_type'      => $pay_type[$i],
                    'amount'        => $paid[$i],
                    'pay_date'      => $createdate,
                    'account'       => $bankname,
                    'COAID'         => $bankcoaid,
                    'status'        =>  1,
                );

                $this->db->insert('paid_amount', $data);

                $cuscredit = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  $customer_headcode,
                    'Narration'      =>  'Customer credit (Cash In Bank) for Paid Amount For Customer Invoice ID - ' . $invoice_id . ' Customer- ' . $cusifo->customer_name,
                    'Debit'          =>  0,
                    'Credit'         =>  $paid[$i],
                    'IsPosted'       => 1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );

                $carddebit = array(
                    'VNo'            =>  $invoice_id,
                    'Vtype'          =>  'INV',
                    'VDate'          =>  $createdate,
                    'COAID'          =>  '40404',
                    'Narration'      =>  'Expense Debit for card no. ' . $card_info[0]['card_no'] . ' Invoice NO- ' . $invoice_id,
                    'Debit'          =>  $paid[$i] * ($card_info[0]['percentage'] / 100),
                    'Credit'         =>  0,
                    'IsPosted'       => 1,
                    'CreateBy'       => $createby,
                    'CreateDate'     => $createdate,
                    'IsAppove'       => 1
                );


                $this->db->insert('acc_transaction', $cuscredit);
                $this->db->insert('acc_transaction', $carddebit);
                $this->db->insert('acc_transaction', $bankc);
            }
        }




        // echo '<pre>';print_r($CUS);



        $this->db->set(array(
            'paid_amount' => $tot_pay_amount,
            'due_amount' => $tot_due_amount
        ));
        $this->db->where('invoice_id', $invoice_id);
        $this->db->update('invoice');

        redirect(base_url('Cinvoice/customer_due_payment'));
    }
    //Setting of Invoice
    public function setting()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit();
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('linvoice');
        $CI->load->model('Invoices');
        if ($this->input->post('InvoiceSetting') == "Submit") {
            //     $systemOptionCount = count($_POST['id']);
            //     $InvoiceSettingData = array();
            //     for ($nCount = 0; $nCount < $systemOptionCount; $nCount++) {
            //         $val = ($_POST['status'][$nCount] == "on" ||  $_POST['status'][$nCount] == "1") ? 1 : 0;
            //         $InvoiceSettingData[] = array(
            //             'id'  => $_POST['id'][$nCount],
            //             'status'  => ($val == 1) ? $val : ($_POST['status'][$nCount] ? $_POST['status'][$nCount] : 0),
            //             'updated_by'    => $this->session->userdata('user_id'),
            //             'updated_date'  => date('Y-m-d h:i:s')
            //         );
            //     }
            //     $CI->Invoices->upateSystemOption($InvoiceSettingData);
            //     $this->session->set_flashdata('SystemOptionMSG', $this->lang->line('success_update'));

            //     //Newly added
            $all_lists = $CI->Invoices->getSettingData();
            $data = [];
            foreach ($all_lists as $list) {
                $new_data = array(
                    'OptionSlug' => $list->OptionSlug,
                    'status' => $this->input->post($list->OptionSlug)
                );
                array_push($data, $new_data);
            }
            if ($CI->Invoices->invoice_setting_update($data)) {
                #set success message
                $this->session->set_flashdata('message', display('successfully_updated'));
            } else {
                #set exception message
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            // redirect($_SERVER['HTTP_REFERER']);

        }
        $content = $CI->linvoice->getSettingData();
        $this->template->full_admin_html_view($content);
    }
}
