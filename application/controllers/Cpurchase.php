<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cpurchase extends CI_Controller
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
        $CI->load->library('lpurchase');
        $content = $CI->lpurchase->purchase_add_form();
        $this->template->full_admin_html_view($content);
    }

    public function previous_stock()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $content = $CI->lpurchase->previous_stock_form();
        $this->template->full_admin_html_view($content);
    }

    //Manage purchase
    public function manage_purchase()
    {
        $this->load->library('lpurchase');
        $content = $this->lpurchase->purchase_list();

        $this->template->full_admin_html_view($content);
    }
    public function due_payment()
    {
        $purchase_id = $this->input->post('purchase_id');
        $notes = $this->input->post('notes');
        $bank_id = $this->input->post('bank_id');
        $bkash_id = $this->input->post('bkash_id');
        $nagad_id = $this->input->post('nagad_id');
        $rocket_id = $this->input->post('rocket_id');
        $pay_type = $this->input->post('paytype');
        $total_amount = $this->input->post('total_amount');
        $due_amount = $this->input->post('due_amount');
        $paid_amount = $this->input->post('paid_amount');
        $pay_amount = $this->input->post('pay_amount');
        $receive_by = $this->session->userdata('user_id');
        $receive_date = date('Y-m-d');

        $purchase_details = $this->db->from('product_purchase')->where('purchase_id', $purchase_id)->get()->row();
        $supinfo = $this->db->select('*')->from('supplier_information')->where('supplier_id', $purchase_details->supplier_id)->get()->row();
        $sup_head = $supinfo->supplier_id . '-' . $supinfo->supplier_name;
        $sup_coa = $this->db->select('*')->from('acc_coa')->where('HeadName', $sup_head)->get()->row();
        $purchasecoatran = array(
            'VNo'            =>  $purchase_id,
            'Vtype'          =>  'Purchase',
            'VDate'          =>  $receive_date,
            'COAID'          =>  $sup_coa->HeadCode,
            'Narration'      =>  'Supplier Debit for Purchase',
            'Debit'          =>  $pay_amount,
            'Credit'         => 0,
            'IsPosted'       =>  1,
            'CreateBy'       =>  $receive_by,
            'CreateDate'     =>  $receive_date,
            'IsAppove'       =>  1
        );

        $this->db->insert('acc_transaction', $purchasecoatran);

        if ($pay_type == 1) {
            $cc = array(

                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Purchase',
                'VDate'          =>  $receive_date,
                'COAID'          =>  1020101,
                'Narration'      =>  'Payment Received(in Cash) for Purchase',
                'Credit'         => $pay_amount,
                'Debit'          =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $receive_by,
                'CreateDate'     =>  $receive_date,
                'IsAppove'       =>  1,

            );
            $data = array(
                'purchase_id'    => $purchase_id,
                'pay_type'      => $pay_type,
                'amount'        => $pay_amount,
                'account'       => ''
            );

            if ($pay_amount > 0) {
                $this->db->insert('purchase_payment', $data);
                $this->db->insert('acc_transaction', $cc);
            }
        }
        if ($pay_type == 4) {
            if (!empty($bank_id)) {
                $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id)->get()->row()->bank_name;

                $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
            } else {
                $bankcoaid = '';
            }

            $bankc = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Purchase',
                'VDate'          =>  $receive_date,
                'COAID'          =>  $bankcoaid,
                'Narration'      =>  'Payment Received in bank for Purchase',
                'Credit'          =>  $pay_amount,
                'Debit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $receive_by,
                'CreateDate'     =>  $receive_date,
                'IsAppove'       =>  1,

            );

            $data = array(
                'purchase_id'    => $purchase_id,
                'pay_type'      => $pay_type,
                'amount'        => $pay_amount,
                'account'       => $bankname
            );


           

            if ($pay_amount > 0) {
                $this->db->insert('acc_transaction', $bankc);
                $this->db->insert('purchase_payment', $data);
            }

        }
        if ($pay_type == 3) {

            if (!empty($bkash_id)) {
                $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id)->get()->row()->bkash_no;

                $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK-' . $bkashname)->get()->row()->HeadCode;
            } else {
                $bkashcoaid = '';
            }
            $bkashc = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Purchase',
                'VDate'          =>  $receive_date,
                'COAID'          =>  $bkashcoaid,
                'Narration'      =>  'Payment Received in bkash for Purchase',
                'Credit'          =>  $pay_amount,
                'Debit'         =>  0,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $receive_by,
                'CreateDate'     =>  $receive_date,
                'IsAppove'       =>  1,

            );

            $data = array(
                'purchase_id'    => $purchase_id,
                'pay_type'      => $pay_type,
                'amount'        => $pay_amount,
                'account'       => $bkashname
            );


           

            if ($pay_amount >  0) {
                $this->db->insert('acc_transaction', $bkashc);
                $this->db->insert('purchase_payment', $data);
            }

        }
        if ($pay_type == 5) {
            if (!empty($nagad_id)) {
                $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id)->get()->row()->nagad_no;

                $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $nagadname)->get()->row()->HeadCode;
            } else {
                $nagadcoaid = '';
            }

            $nagadc = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Purchase',
                'VDate'          =>  $receive_date,
                'COAID'          =>  $nagadcoaid,
                'Narration'      =>  'Credit for company in nagad for Purchase',
                'Debit'          =>  0,
                'Credit'         => $pay_amount,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $receive_by,
                'CreateDate'     =>  $receive_date,
                'IsAppove'       =>  1,

            );

            $data = array(
                'purchase_id'    => $purchase_id,
                'pay_type'      => $pay_type,
                'amount'        => $pay_amount,
                'account'       => $nagadname
            );

            if ($pay_amount > 0) {
                $this->db->insert('acc_transaction', $nagadc);
                $this->db->insert('purchase_payment', $data);
            }
         
        }
        if ($pay_type == 7) {

            if (!empty($rocket_id)) {
                $rocketname = $this->db->select('rocket_no')->from('rocket_add')->where('rocket_id', $rocket_id)->get()->row()->rocket_no;

                $rocketcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'RK-' . $rocketname)->get()->row()->HeadCode;
            } else {
                $rocketcoaid = '';
            }

            $rocketc = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Purchase',
                'VDate'          =>  $receive_date,
                'COAID'          =>  $rocketcoaid,
                'Narration'      =>  'Credit for company in rocket for Purchase',
                'Debit'          =>  0,
                'Credit'         => $pay_amount,
                'IsPosted'       =>  1,
                'CreateBy'       =>  $receive_by,
                'CreateDate'     =>  $receive_date,
                'IsAppove'       =>  1,

            );

            $data = array(
                'purchase_id'    => $purchase_id,
                'pay_type'      => $pay_type,
                'amount'        => $pay_amount,
                'account'       => $rocketname
            );
            if ($pay_amount > 0) {
                $this->db->insert('acc_transaction', $rocketc);
                $this->db->insert('purchase_payment', $data);
            }
        }

        $data = array(
            'due_amount' => $due_amount,
            'paid_amount' => $paid_amount,
        );
        $this->db->where('purchase_id', $purchase_id);
        $result = $this->db->update('product_purchase', $data);
        if ($result == true) {

            redirect(base_url('Cpurchase/purchase_details_data/' . $purchase_id));
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Cpurchase/manage_purchase'));
        }
    }



    public function CheckPurchaseList()
    {
        // GET data
        $this->load->model('Purchases');
        $postData = $this->input->post();
        // echo "<pre>";
        // print_r($postData);
        // exit();
        $data = $this->Purchases->getPurchaseList($postData);
        echo json_encode($data);
    }
    // search purchase by supplier
    public function purchase_search()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $CI->load->model('Purchases');
        $supplier_id = $this->input->get('supplier_id');
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Cpurchase/purchase_search/');
        $config["total_rows"] = $this->Purchases->count_purchase_seach($supplier_id);
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
        $content = $this->lpurchase->purchase_search_supplier($supplier_id, $links, $config["per_page"], $page);
        $this->template->full_admin_html_view($content);
    }

    //purchase list by invoice no
    public function purchase_info_id()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $CI->load->model('Purchases');
        $invoice_no = $this->input->post('invoice_no', TRUE);
        $content = $this->lpurchase->purchase_list_invoice_no($invoice_no);
        $this->template->full_admin_html_view($content);
    }

    //Insert purchase
    public function insert_purchase()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Purchases');
        $purchase_id = $CI->Purchases->purchase_entry();
        $this->session->set_userdata(array('message' => display('successfully_added')));
        // if (isset($_POST['add-purchase'])) {
        //     redirect(base_url('Cpurchase/manage_purchase'));
        //     exit;
        // }
        //  elseif (isset($_POST['add-purchase-another'])) {
        //     redirect(base_url('Cpurchase'));
        //     exit;
        // }

       
        
        if (isset($_POST['add-purchase-another'])) {
            redirect(base_url('Cpurchase'));
        }
        else if (!empty($purchase_id)) {
            redirect(base_url('Cpurchase/manage_purchase'));
            exit;
        } 
        
    }
     //Add Product CSV
     public function add_purchase_csv()
     {
         $CI = &get_instance();
         $data = array(
             'title' => "Add Purchase(CSV)"
         );
         $content = $CI->parser->parse('purchase/add_purchase_csv', $data, true);
         $this->template->full_admin_html_view($content);
     }
     public function generator($lenth)
    {
        $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "N", "M", "O", "P", "Q", "R", "S", "U", "V", "T", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 61);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
     //Purchase Submit
     function uploadCsv()
     {
        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        $today = date('Y-m-d');
        $expired_date = date('Y-m-d', strtotime($today . ' + 50 years'));
        $purchase_id = date('YmdHis');
        $createdate = date('Y-m-d H:i:s');
        $createby = $this->session->userdata('user_id');
        $receive_by = $this->session->userdata('user_id');
        $receive_date = date('Y-m-d');

         $this->load->model('suppliers');
         $filename = $_FILES['upload_csv_file']['name'];
         $tmp = explode('.', $filename);
         $ext = end($tmp);
         $ext = substr(strrchr($filename, '.'), 1);
         $size_id = '';
         $color_id = '';
         if ($ext == 'csv') {
             $count = 0;
             $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");
 
             if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {
                 while ($csv_line = fgetcsv($fp, 1024)) {
                     //keep this if condition if you want to remove the first row
                     for ($i = 0, $j = count($csv_line[0]); $i < $j; $i++) {
                        
                        if($count == 0)
                        {
                            continue;
                        }
                        if($count == 1)
                        {
                           
                            // echo "<pre>";
                            // print_r($outlet_id);
                            // exit();
                            $supplier_name = $csv_line[0];
                            $purchase_date = $csv_line[1];
                            $product_type = $csv_line[2];
                            $subtotal = $csv_line[10];
                            $discount = $csv_line[11];
                            $labour_wages = $csv_line[12];
                            $transport_cost = $csv_line[13];
                            $paid_amount = $csv_line[15];
                            $due_amount = $csv_line[16];
                            $grand_total = $csv_line[14];
                            //Payment Calculation
                            $cash_amount = $csv_line[17];
                            $bkash_number = $csv_line[18];
                            $bkash_amount = $csv_line[19];
                            $nagad_number = $csv_line[20];
                            $nagad_amount = $csv_line[21];
                            $rocket_number = $csv_line[22];
                            $rocket_amount = $csv_line[23];
                            $bank_number = $csv_line[24];
                            $bank_amount = $csv_line[25];
                           
                            if ($product_type == "Finished Goods") {
                                $expense_code = 402;
                            }
                            if ($product_type == "Raw Materials") {
                                $expense_code = 405;
                            }
                            if ($product_type == "Tools") {
                                $expense_code = 404;
                            }
                            //Supplier Information
                            $supplier_id = null;
                            $check_supplier = $this->db->select('*')->from('supplier_information')->where('supplier_name', $supplier_name)->get()->row();
                            if (!empty($check_supplier)) {
                               
                                $supplier_id = $check_supplier->supplier_id;
                                $supplier_name = $check_supplier->supplier_name;
                            } 
                            else {
                            $supplierdata = array(
                                'supplier_name' => $supplier_name,
                                'status' => 1
                                );
                                $this->db->insert('supplier_information', $supplierdata);
                                $supplier_id = $this->db->insert_id();
                                $coa = $this->Suppliers->headcode();
                                if ($coa->HeadCode != NULL) {
                                    $headcode = $coa->HeadCode + 1;
                                } else {
                                    $headcode = "502020001";
                                }
                                $c_acc = $supplier_id . '-' . $supplier_name;
                                $supplier_createby = $this->session->userdata('user_id');
                                 $supplier_createdate = date('Y-m-d H:i:s');
                                 $supplier_coa = [
                                    'HeadCode'       => $headcode,
                                    'HeadName'         => $c_acc,
                                    'PHeadName'        => 'Account Payable',
                                    'HeadLevel'        => '3',
                                    'IsActive'         => '1',
                                    'IsTransaction'    => '0',
                                    'IsGL'             => '0',
                                    'HeadType'         => 'L',
                                    'IsBudget'         => '0',
                                    'supplier_id'      => $supplier_id,
                                    'IsDepreciation'   => '0',
                                    'DepreciationRate' => '0',
                                    'CreateBy'         => $supplier_createby,
                                    'CreateDate'       => $supplier_createdate,
                                ];
                                //Previous balance adding -> Sending to supplier model to adjust the data.
                                $this->db->insert('acc_coa', $supplier_coa);
                               $this->Suppliers->previous_balance_add(0, $supplier_id, $c_acc, $supplier_name);

                            }
                         
                            $sup_head = $supplier_id . '-' . $supplier_name;
                            $sup_coa = $this->db->select('*')->from('acc_coa')->where('HeadName', $sup_head)->get()->row();
                            //Purchase Entry
                                if ($paid_amount <= 0) {
                                    $data = array(
                                        'purchase_id'        => $purchase_id,
                                        'supplier_id'        => $supplier_id,
                                        'invoice_no'        => '',
                                        'labour_wages'     => $labour_wages,
                                        'transport_cost'     => $transport_cost,
                                        'grand_total_amount' => $grand_total,
                                        'total_discount'     => $discount,
                                        'purchase_date'      => $purchase_date,
                                        'purchase_details'   => '',
                                        'paid_amount'        => $paid_amount,
                                        'due_amount'         => $due_amount,
                                        'status'             => 2,
                                        'outlet_id'       =>  $outlet_id,
                                        'isFinal'          => 2
                                    );
                                    $this->db->insert('product_purchase', $data);
                                } 
                                else {
                                    $data = array(
                                        'purchase_id'        => $purchase_id,
                                        'supplier_id'        => $supplier_id,
                                        'invoice_no'        => '',
                                        'grand_total_amount' => $grand_total,
                                        'total_discount'     => $discount,
                                        'labour_wages'     => $labour_wages,
                                        'transport_cost'     => $transport_cost,
                                        'purchase_date'      => $purchase_date,
                                        'purchase_details'   => '',
                                        'paid_amount'        => $paid_amount,
                                        'due_amount'         => $due_amount,
                                        'status'             => 1,
                                        'outlet_id'       =>  $outlet_id,
                                        'isFinal'          => 2
                                    );
                                    // echo "<pre>";
                                    // print_r($data);
                                    // exit();
                                    $this->db->insert('product_purchase', $data);
                                }
                                $data1 = array(
                                    'purchase_detail_id' => $this->generator(15),
                                    'purchase_id'        => $purchase_id,
                                    'product_id'         => $csv_line[3],
                                    'quantity'           => $csv_line[6],
                                    'qty'                => $csv_line[6],
                                    'damaged_qty'        => $csv_line[7],
                                    'expired_date'       => $expired_date,
                                    'rate'               => $csv_line[8],
                                    'total_amount'       => $csv_line[9],
                                    'status'             => 2
                                );
                    
                                if (!empty($csv_line[6])) {
                                    $this->db->insert('product_purchase_details', $data1);
                                }
                                //Acc Transaction
                                $expense = array(
                                    'VNo'            => $purchase_id,
                                    'Vtype'          => 'Purchase',
                                    'VDate'          => $purchase_date,
                                    'COAID'          => $expense_code,
                                    'Narration'      => 'Company Debit For  ' . $supplier_name,
                                    'Debit'          => ($discount > 0) ? $subtotal : $grand_total,
                                    'Credit'         => 0,
                                    'IsPosted'       => 1,
                                    'CreateBy'       => $receive_by,
                                    'CreateDate'     => $createdate,
                                    'IsAppove'       => 1
                                );
                        
                                 $this->db->insert('acc_transaction', $expense);
                               // purchase Transaction
                                $purchasecoatran = array(
                                    'VNo'            =>  $purchase_id,
                                    'Vtype'          =>  'Purchase',
                                    'VDate'          =>  $purchase_date,
                                    'COAID'          =>  $sup_coa->HeadCode,
                                    'Narration'      =>  'Supplier .' . $supplier_name,
                                    'Debit'          =>  0,
                                    'Credit'         => ($discount > 0) ? $subtotal : $grand_total,
                                    'IsPosted'       =>  1,
                                    'CreateBy'       =>  $receive_by,
                                    'CreateDate'     =>  $receive_date,
                                    'IsAppove'       =>  1
                                );
                        
                                //this->db->insert('acc_transaction', $purchasecoatran);
                                //due_amount Transaction
                                if ($due_amount > 0) {
                                    $supplier_due_cr = array(
                                        'VNo'            =>  $purchase_id,
                                        'Vtype'          =>  'Purchase',
                                        'VDate'          =>  $purchase_date,
                                        'COAID'          =>  $sup_coa->HeadCode,
                                        'Narration'      =>  'Supplier .' . $supplier_name,
                                        'Debit'          =>  0,
                                        'Credit'         => ($discount > 0) ? ($due_amount) : $due_amount,
                                        'IsPosted'       =>  1,
                                        'CreateBy'       =>  $receive_by,
                                        'CreateDate'     =>  $receive_date,
                                        'IsAppove'       =>  1
                                    );
                                    $this->db->insert('acc_transaction', $supplier_due_cr);
                                }
                        
                        
                                // //Discount Transaction
                                if ($discount > 0) {
                        
                                    $dis_transaction = array(
                        
                                        'VNo'            =>  $purchase_id,
                                        'Vtype'          =>  'Purchase',
                                        'VDate'          =>  $purchase_date,
                                        'COAID'          =>  301,
                                        'Narration'      =>  'Purchase Discount for Purchase ID - ' . $purchase_id,
                                        'Credit'         =>  $discount,
                                        'Debit'          =>  0,
                                        'IsPosted'       =>  1,
                                        'CreateBy'       =>  $createby,
                                        'CreateDate'     =>  $createdate,
                                        'IsAppove'       =>  1,
                        
                                    );
                        
                                    $this->db->insert('acc_transaction', $dis_transaction);
                                }
                               
                                //----------------------//Payment Related Task----------------------//
                                //Cash Payment
                                if($cash_amount > 0)
                                {
                                    $cc = array(

                                        'VNo'            =>  $purchase_id,
                                        'Vtype'          =>  'Purchase',
                                        'VDate'          =>  $purchase_date,
                                        'COAID'          =>  1020101,
                                        'Narration'      =>  'Cash in Hand for Purchase ID - ' . $purchase_id,
                                        'Credit'         =>  $cash_amount,
                                        'Debit'          =>  0,
                                        'IsPosted'       =>  1,
                                        'CreateBy'       =>  $createby,
                                        'CreateDate'     =>  $createdate,
                                        'IsAppove'       =>  1,
                
                                    );
                                    $data = array(
                                        'purchase_id'    => $purchase_id,
                                        'pay_type'      => 1,
                                        'amount'        => $cash_amount,
                                        'account'       => ''
                                    );
                                        $this->db->insert('purchase_payment', $data);
                                        $this->db->insert('acc_transaction', $cc);
                                    
                                }
                                // //Bkash Payment
                                if($bkash_amount > 0)
                                {
                                    if (!empty($bkash_number)) {

                                        // $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id[$i])->get()->row()->bkash_no;
                
                                        $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK-' . $bkash_number)->get()->row()->HeadCode;
                                    } else {
                                        $bkashcoaid = '';
                                    }
                                    $bkashc = array(
                                        'VNo'            =>  $purchase_id,
                                        'Vtype'          =>  'Purchase',
                                        'VDate'          =>  $purchase_date,
                                        'COAID'          =>  $bkashcoaid,
                                        'Narration'      =>  'Credit for company in bkash for Purchase ID - ' . $purchase_id,
                                        'Credit'          =>  $bkash_amount,
                                        'Debit'         =>  0,
                                        'IsPosted'       =>  1,
                                        'CreateBy'       =>  $createby,
                                        'CreateDate'     =>  $createdate,
                                        'IsAppove'       =>  1,
                
                                    );
                
                                    $data = array(
                                        'purchase_id'    => $purchase_id,
                                        'pay_type'      => 3,
                                        'amount'        => $bkash_amount,
                                        'account'       => $bkash_number
                                    );
                                    $this->db->insert('acc_transaction', $bkashc);
                                $this->db->insert('purchase_payment', $data);
                                }
                                // //Nagad Payment
                                if($nagad_amount > 0)
                                {
                                    if (!empty($nagad_number)) {
                                        // $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id[$i])->get()->row()->nagad_no;
                
                                        $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG-' . $nagad_number)->get()->row()->HeadCode;
                                    } else {
                                        $nagadcoaid = '';
                                    }
                
                                    $nagadc = array(
                                        'VNo'            =>  $purchase_id,
                                        'Vtype'          =>  'Purchase',
                                        'VDate'          =>  $purchase_date,
                                        'COAID'          =>  $nagadcoaid,
                                        'Narration'      =>  'Credit for company in nagad for Purchase ID - ' . $purchase_id,
                                        'Credit'          =>  $nagad_amount,
                                        'Debit'         =>  0,
                                        'IsPosted'       =>  1,
                                        'CreateBy'       =>  $createby,
                                        'CreateDate'     =>  $createdate,
                                        'IsAppove'       =>  1,
                
                                    );
                
                                    $data = array(
                                        'purchase_id'    => $purchase_id,
                                        'pay_type'      => 5,
                                        'amount'        => $nagad_amount,
                                        'account'       => $nagad_number
                                    );
                                    $this->db->insert('acc_transaction', $nagadc);
                                    $this->db->insert('purchase_payment', $data);
                                }
                                // //Rocket Payment
                                if($rocket_amount > 0)
                                {
                                
                                    if (!empty($rocket_number)) {
                                        // $rocketname = $this->db->select('rocket_no')->from('rocket_add')->where('rocket_id', $rocket_id[$i])->get()->row()->rocket_no;
                
                                        $rocketcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'RK-' . $rocket_number)->get()->row()->HeadCode;
                                    } else {
                                        $rocketcoaid = '';
                                    }
                
                                    $rocketc = array(
                                        'VNo'            =>  $purchase_id,
                                        'Vtype'          =>  'INVOICE',
                                        'VDate'          =>  $purchase_date,
                                        'COAID'          =>  $rocketcoaid,
                                        'Narration'      =>  'Credit for company in nagad for Purchase ID - ' . $purchase_id,
                                        'Debit'          =>  $rocket_amount,
                                        'Credit'         =>  0,
                                        'IsPosted'       =>  1,
                                        'CreateBy'       =>  $createby,
                                        'CreateDate'     =>  $createdate,
                                        'IsAppove'       =>  1,
                
                                    );
                
                                    $data = array(
                                        'purchase_id'    => $purchase_id,
                                        'pay_type'      => 7,
                                        'amount'        => $rocket_amount,
                                        'account'       => $rocket_number
                                    );
                                    $this->db->insert('purchase_payment', $data);
                
                                    $this->db->insert('acc_transaction', $rocketc);
                                }
                                // //Bank Payment
                                 if($bank_amount > 0)
                                {
                                    if (!empty($bank_number)) {
                                        // $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id[$i])->get()->row()->bank_name;
                                    $bankname = $this->db->select('bank_name')->from('bank_add')->where('acc_number', $bank_number)->get()->row()->bank_name;
                                        $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
                                    } else {
                                        $bankcoaid = '';
                                    }
                
                                    $bankc = array(
                                        'VNo'            =>  $purchase_id,
                                        'Vtype'          =>  'Purchase',
                                        'VDate'          =>  $purchase_date,
                                        'COAID'          =>  $bankcoaid,
                                        'Narration'      =>  'Credit for company in bank for Purchase ID - ' . $purchase_id,
                                        'Credit'          =>  $bank_amount,
                                        'Debit'         =>  0,
                                        'IsPosted'       =>  1,
                                        'CreateBy'       =>  $createby,
                                        'CreateDate'     =>  $createdate,
                                        'IsAppove'       =>  1,
                
                                    );
                
                                    $data = array(
                                        'purchase_id'    => $purchase_id,
                                        'pay_type'      => 4,
                                        'amount'        => $bank_amount,
                                        'account'       => $bankname
                                    );
                                    $this->db->insert('acc_transaction', $bankc);
                                $this->db->insert('purchase_payment', $data);
                                }
                        }
                        if($count > 1){
                            $data1 = array(
                                'purchase_detail_id' => $this->generator(15),
                                'purchase_id'        => $purchase_id,
                                    'product_id'         => $csv_line[3],
                                    'quantity'           => $csv_line[6],
                                    'qty'                => $csv_line[6],
                                    'damaged_qty'        => $csv_line[7],
                                    'expired_date'       => $expired_date,
                                    'rate'               => $csv_line[8],
                                    'total_amount'       => $csv_line[9],
                                    'status'             => 2
                            );
                
                            if (!empty($csv_line[6])) {
                                $this->db->insert('product_purchase_details', $data1);
                            }
                        }
                     }
                     $count++;
                 }
             }
             $this->session->set_userdata(array('message' => display('successfully_added')));
             redirect(base_url('Cpurchase/manage_purchase'));
         } else {
             $this->session->set_userdata(array('error_message' => 'Please Import Only Csv File'));
             redirect(base_url('Cpurchase/manage_purchase'));
         }
     }

    public function insert_previous_purchase()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Purchases');
        $CI->Purchases->previous_purchase_entry();
        $this->session->set_userdata(array('message' => display('successfully_added')));
        if (isset($_POST['add-purchase'])) {
            redirect(base_url('Cpurchase/manage_purchase'));
            exit;
        } elseif (isset($_POST['add-purchase-another'])) {
            redirect(base_url('Cpurchase/previous_stock'));
            exit;
        }
    }

    //purchase Update Form
    public function purchase_update_form($purchase_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lpurchase');

        $content = $CI->lpurchase->purchase_edit_data($purchase_id);

        $this->template->full_admin_html_view($content);
    }

    // purchase Update
    public function purchase_update()
    {

        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Purchases');
        $CI->Purchases->update_purchase();
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Cpurchase/manage_purchase'));
        exit;
    }

    //Purchase item by search
    public function purchase_item_by_search()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $supplier_id = $this->input->post('supplier_id', TRUE);
        $content = $CI->lpurchase->purchase_by_search($supplier_id);
        $this->template->full_admin_html_view($content);
    }

    //Product search by supplier id
    public function product_search_by_supplier()
    {


        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $CI->load->model('Suppliers');
        $product_status = $this->input->post('product_status', TRUE);
        $product_name = $this->input->post('product_name', TRUE);
        $cat_id = $this->input->post('cat_id', TRUE);
        $product_info = $CI->Suppliers->product_search_item($product_name, $product_status, $cat_id);
        if (!empty($product_info)) {
            $list[''] = '';
            foreach ($product_info as $value) {
                $json_product[] = array('label' => $value['sku'] . '-(' . $value['product_name'] . ')', 'value' => $value['product_id']);
            }
        } else {
            $json_product[] = 'No Product Found';
        }
        echo json_encode($json_product);
    }

    //Retrive right now inserted data to cretae html
    public function purchase_details_data($purchase_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $content = $CI->lpurchase->purchase_details_data($purchase_id);
        $this->template->full_admin_html_view($content);
    }

    public function delete_purchase($purchase_id = null)
    {
        $this->load->model('Purchases');
        if ($this->Purchases->purchase_delete($purchase_id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect(base_url('Cpurchase/manage_purchase'));
    }

    // purchase info date to date
    public function manage_purchase_date_to_date()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $CI->load->model('Purchases');
        $start = $this->input->post('from_date', TRUE);
        $end = $this->input->post('to_date', TRUE);

        $content = $this->lpurchase->purchase_list_date_to_date($start, $end);
        $this->template->full_admin_html_view($content);
    }
    //purchase pdf download
    public function purchase_downloadpdf()
    {
        $CI = &get_instance();
        $CI->load->model('Purchases');
        $CI->load->model('Web_settings');
        $CI->load->model('Invoices');
        $CI->load->library('pdfgenerator');
        $purchase_list = $CI->Purchases->pdf_purchase_list();
        if (!empty($purchase_list)) {
            $i = 0;
            if (!empty($purchase_list)) {
                foreach ($purchase_list as $k => $v) {
                    $i++;
                    $purchase_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('manage_purchase'),
            'purchase_list' => $purchase_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
            'company_info'  => $company_info
        );
        $this->load->helper('download');
        $content = $this->parser->parse('purchase/purchase_list_pdf', $data, true);
        $time = date('Ymdhi');
        $dompdf = new DOMPDF();
        $dompdf->load_html($content);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/' . 'purchase' . $time . '.pdf', $output);
        $file_path = 'assets/data/pdf/' . 'purchase' . $time . '.pdf';
        $file_name = 'purchase' . $time . '.pdf';
        force_download(FCPATH . 'assets/data/pdf/' . $file_name, null);
    }

    public function purchase_order()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $content = $CI->lpurchase->purchase_order_add_form();
        $this->template->full_admin_html_view($content);
    }

    public function po_search_by_supplier()
    {


        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lpurchase');
        $CI->load->model('Suppliers');
        $supplier_id = $this->input->post('supplier_id', TRUE);
        $product_name = $this->input->post('product_name', TRUE);
        $product_info = $CI->Suppliers->po_search_item($supplier_id, $product_name);
        //  echo '<pre>';print_r($product_info);
        if (!empty($product_info)) {
            $list[''] = '';
            foreach ($product_info as $value) {
                $json_product[] = array('label' => $value['rqsn_detail_id'], 'value' => $value['product_id']);
            }
        } else {
            $json_product[] = 'No Purchase Order Found';
        }
        echo json_encode($json_product);
    }



    public function retrieve_po_data()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Rqsn');
        $product_id  = $this->input->post('product_id', TRUE);
        $supplier_id = $this->input->post('supplier_id', TRUE);

        $product_info = $CI->Rqsn->get_po_details($product_id, $supplier_id);

        echo json_encode($product_info);
    }

    public function get_purchase_details()
    {

        $po_id = $this->input->post('po_id', TRUE);

        $this->load->model("Purchases");
        $this->load->model("Products");
        $this->load->model("Suppliers");
        //   $product_id=$_POST["product_id"];
        $cart_list = $this->Purchases->purchase_details($po_id);
        $supp_id = $this->input->post('supp_id', TRUE);
        // $grand_total = array_sum(array_column($cart_list, 'total_amount'));
        // $total = array_sum(array_column($cart_list, 'total'));
        $op = '';
        $op .= '
            <div class="table-responsive">
            <table class="table table-bordered table-hover" id="purchaseTable">
                <thead>
                     <tr>
                        <th class="text-center" width="4%">SN</th>
                        <th class="text-center" width="8%">Product Name</th>
                        <th class="text-center">Current Stock</th>
                        <th class="text-center">Order Quantity</th>
                        <th class="text-center">Damaged Quantity</th>
                        <th class="text-center">Warranty</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody id="addPurchaseItem">';


        $count = 0;
        foreach ($cart_list as $items) {


            // echo '<pre>'; print_r($items['additional_cost']); exit();
            // $tot = "";

            // if ($items['total_amount']) {
            //     $tot = $items['total_amount'];
            // }

            $add_cost = "00";

            // if ($items['additional_cost']) {
            //     $add_cost = $items['additional_cost'];
            // }


            $product_id = $items['product_id'];
            $supplier_product = $this->Suppliers->supplier_product_info($supp_id, $product_id);
            // $product_info = $this->Products->retrieve_product_full_data($product_id)[0];
            // $supplier_list = $this->Suppliers->supplier_list_by_id($product_id);
            // echo '<pre>'; print_r($items['warrenty_date']); exit();
            $count++;
            $op .= '
                        <tr>
                        <td class="wt"> ' . $count . '</td>
                        <td class="span3 supplier">
                            <span>' . $items['product_name'] . ' (' . $items['product_model'] . ') ' . ' (' . $items['size_name'] . ') ' . ' (' . $items['color_name'] . ') ' . '</span>
                            <input type="hidden" name="product_id[]" id="product_id_' . $count . '" value="' . $items['product_id'] . '">
                            <input type="hidden" class="sl" value="' . $count . '">
                            <input type="hidden" id="product_name_' . $count . '" value="' . $items['product_name'] . '">
                            <input type="hidden" name="rqsn_detail_id[]" value="' . $items['rqsn_detail_id'] . '">
                        </td>
                            <td class="wt">
                                <input type="text"  id="available_quantity' . $count . '" class="form-control text-right stock_ctn' . $count . '" placeholder="0.00" readonly/>
                            </td>
                            <td class="test">
                                <input type="text" name="order_quantity[]" required="" id="order_quantity_' . $count . '" class="form-control product_rate' . $count . ' text-right" value="' . $items['quantity'] . '" min="0" tabindex="7" readonly/>
                            </td>

                            <td class="text-right">
                                <input type="text" name="damaged_qty[]" id="damaged_' . $count . '" min="0" class="form-control text-right store_cal_1" placeholder="0.00" value="" tabindex="6" />
                            </td>

                                <td class="text-right">
                                    <input type="date" name="warrenty_date[]" id="warrenty_date_' . $count . '" class="form-control text-right" value="' . ($items['warrenty_date'] ? $items['warrenty_date'] : "") . '"  tabindex="6"/>
                                </td>

                                <td class="test">
                                    <input type="text" name="rate[]" required="" onkeyup="calculate_store(' . $count . ');" onchange="calculate_store(' . $count . ');" id="product_rate_' . $count . '" class="form-control product_rate' . $count . ' text-right" placeholder="0.00" value="' . ($supplier_product[0]['supplier_price'] ? $supplier_product[0]['supplier_price'] : '0.00') . '" min="0" tabindex="7" readonly/>
                                </td>

                                <td class="text-left">
                                    <input type="text" class="form-control row_total text-right" name="row_total[]" value="' . $items['quantity'] * $supplier_product[0]['supplier_price'] . '" id = "total_price_' . $count . '" class="total_price" readonly>
                                </td>

                        </tr>
                        ';
        }
        $op .= '
            </tbody>
            <tfoot>
                                    <tr>

                                        <td class="text-right" colspan="7"><b>Total</td>
                                        <td class="text-right">
                                            <input type="text" id="Total" class="text-right form-control" name="total" value="0.00" readonly="readonly" />
                                        </td>

                                            <input type="hidden" name="baseUrl" class="baseUrl" value="' . base_url() . '"/></td>
                                    </tr>
                                        <tr>

                                        <td class="text-right" colspan="7"><b>Discounts:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="discount" class="text-right form-control discount" onkeyup="calculate_store(1)" name="discount" placeholder="0.00" value="" />
                                        </td>

                                    </tr>

                                        <tr>

                                        <td class="text-right" colspan="7"><b>Grand Total:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="grandTotal" class="text-right form-control" name="grand_total_price" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
                                         <tr>

                                        <td class="text-right" colspan="7"><b>Paid Amount:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="paidAmount" class="text-right form-control" onKeyup="invoice_paidamount()" name="paid_amount" value="" readonly/>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td class="text-right" colspan="7"><b>Due Amount:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="dueAmmount" class="text-right form-control" name="due_amount" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
                                </tfoot>
        </table>
                            ';

        if ($count == 0) {
            $op = '<h3 align="center">Purchase Order is empty</h3>';
        }

        echo $op;
    }

    public function save_purchase()
    {
        $this->load->model('Rqsn');
        $this->Rqsn->purchase_order_entry();

        redirect(base_url('Cpurchase/purchase_order'));
    }
}
