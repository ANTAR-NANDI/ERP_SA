<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Creport extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $CI = &get_instance();
        $CI->load->model('Web_settings');
    }

    public function index()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->stock_report_single_item();

        $this->template->full_admin_html_view($content);
    }
    //Product Wise Sales Report
    public function product_wise_sales_report()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->product_wise_sales_report();

        $this->template->full_admin_html_view($content);
    }
    //Product Wise Sales Report Ajax Call
    public function ProductSalesReport()
    {
        $this->load->model('Reports');
        $postData = $this->input->post();
        $data = $this->Reports->ProductSalesReport($postData, null);
        echo json_encode($data);
    }
     //Details Sales Report 
    public function product_details_report($product_id, $from_date = null, $to_date = null, $outlet_id = null)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->product_details_report($product_id, $from_date, $to_date, $outlet_id);
        $this->template->full_admin_html_view($content);
    }
    //Supplier Wise Sales Report
    public function supplier_wise_sales_report()
    {
        $this->load->model('Reports');
        $postData = $this->input->post();
        $data = $this->Reports->SupplierSalesReport($postData, null);
        echo json_encode($data);
    }
    //Product Wise Purchase Report
    public function product_wise_purchase_report()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->product_wise_purchase_report();

        $this->template->full_admin_html_view($content);
    }
    //Product Wise Sales Report Ajax Call
    public function ProductPurchaseReport()
    {
        $this->load->model('Reports');
        $postData = $this->input->post();
        $data = $this->Reports->ProductPurchaseReport($postData, null);
        echo json_encode($data);
    }
     //Details Sales Report 
     public function product_purchase_details_report($product_id, $from_date = null, $to_date = null)
     {
         $CI = &get_instance();
         $CI->auth->check_admin_auth();
         $CI->load->library('lreport');
         $content = $CI->lreport->product_purchase_details_report($product_id, $from_date, $to_date);
         $this->template->full_admin_html_view($content);
     }
    public function all_purchase_id()
    {
        $this->db->select('purchase_id');
        $this->db->from('product_purchase');
        $result = $this->db->get()->result_array();
        $balance = $result[0]['balance'];
        $b = (!empty($balance) ? $balance : 0);
        if ($b) {
            echo  $b;
        } else {
            echo  $b;
        }
    }


    public function expiry_stock()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->expiry_stock_report_single_item();

        $this->template->full_admin_html_view($content);
    }

    public function supplier_wise_stock()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->expiry_stock_report_single_item();

        $this->template->full_admin_html_view($content);
    }

    public function raw_stock()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->stock_report_raw();

        $this->template->full_admin_html_view($content);
    }
    public function stock_taking()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $CI->load->model('Purchases');
        $CI->load->model('Rqsn');

        $outlet_id = $CI->session->userdata('outlet_id');

        if ($outlet_id == "HK7TGDT69VFMXB7") {
            $outlet = $CI->Warehouse->get_central_warehouse($outlet_id);
        } else {
            $outlet = $CI->Warehouse->get_outlet($outlet_id);
        }

        // $outlet = $CI->Warehouse->outlet_or_cw_logged_in_2();

        // echo "<pre>";
        // print_r($outlet);
        // exit();


        $data = array(
            'title'     => 'Stock Taking',
            // 'outlet_list'     =>  $CI->Warehouse->get_outlet_user(),
            'outlet_list'     =>  $CI->Rqsn->outlet_list_to(),
            // 'cw'            => $CI->Warehouse->central_warehouse(),
            'cw'            => $CI->Rqsn->cw_list(),
            'access'  => '',
            'outlet'     =>  $outlet,
            'outlet_id' => $outlet_id,
        );

        $view = $this->parser->parse('report/stock_taking', $data, true);
        $this->template->full_admin_html_view($view);
    }

    public function stock_taking_edit($id)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $CI->load->model('Purchases');
        $CI->load->model('Reports');


        $res = $CI->Reports->stock_taking_details_by_id($id);



        $sl = 1;
        foreach ($res as $k => $v) {
            $res[$k]['sl']  = $sl;
            $sl++;
        }

        if ($res[0]['out'] == "HK7TGDT69VFMXB7") {
            $outlet_name = $this->db->select('central_warehouse')->from('central_warehouse')->where('warehouse_id', 'HK7TGDT69VFMXB7')->get()->row()->central_warehouse;
            // echo '<pre>';print_r($outlet_name);exit();

        } else {
            $outlet_name = $res[0]['outlet_name'];
        }

        $data = array(
            'title'     => 'Edit Stock Taking',
            'product_list'  => $res,
            'access'  => 'edit',
            'stid'  => $res[0]['stid'],
            'st'  => $res[0]['stid_no'],
            'st_date'  => $res[0]['date'],
            'notes'  => $res[0]['notes'],
            'approve'  => $res[0]['approve'],
            'outlet_name'  => $outlet_name,
            'outlet_id'  => $res[0]['out'],


        );


        $view = $this->parser->parse('report/stock_taking', $data, true);
        $this->template->full_admin_html_view($view);
    }


    public function stock_taking_view($id, $outlet_id)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $CI->load->model('Purchases');
        $CI->load->model('Reports');
        $CI->load->model('Rqsn');

        $res = $CI->Reports->stock_taking_details_by_id($id);

        if ($res[0]['out'] == "HK7TGDT69VFMXB7") {
            $outlet_name = $this->db->select('central_warehouse')->from('central_warehouse')->where('warehouse_id', 'HK7TGDT69VFMXB7')->get()->row()->central_warehouse;
            // echo '<pre>';print_r($outlet_name);exit();

        } else {
            $outlet_name = $res[0]['outlet_name'];
        }

        $sl = 1;
        foreach ($res as $k => $v) {
            $res[$k]['sl']  = $sl;

            if ($outlet_id == 'HK7TGDT69VFMXB7') {
                // $stock = $CI->Reports->getCheckList(null, $res[$k]['product_id'], '', '', '')['central_stock'];
                $stock = $CI->Reports->available_getCheckListNew2(null, $res[$k]['product_id'], '', '', '', '', $outlet_id)['central_stock'];
                //   $available_quantity = $this->Reports->current_stock($product_id,1);
            } else {
                // $stock = $CI->Rqsn->outlet_stock(null, $res[$k]['product_id'], '', '')['outlet_stock'];
                $stock = $CI->Rqsn->availbale_outlet_stock2(null, $res[$k]['product_id'], '', '', $outlet_id)['outlet_stock'];

                // echo '<pre>';print_r($available_quantity);exit();
            }



            $res[$k]['stock_qty'] = $stock;
            $res[$k]['difference'] = $res[$k]['physical_stock'] - $res[$k]['stock_qty'];
            $sl++;
        }

        $data = array(
            'title'     => 'View Stock Taking',
            'product_list'  => $res,
            'access'  => 'view',
            'stid'  => $res[0]['stid'],
            'st'  => $res[0]['stid_no'],
            'st_date'  => $res[0]['date'],
            'notes'  => $res[0]['notes'],
            'approve'  => $res[0]['approve'],
            'outlet_name'  => $outlet_name,

        );



        $view = $this->parser->parse('report/stock_taking', $data, true);
        $this->template->full_admin_html_view($view);
    }


    public function wastage_dec()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->wastage_dec();
        $this->template->full_admin_html_view($content);
    }

    public function wastage_entry()
    {
        $CI = &get_instance();

        //echo "Ok";exit();

        $CI->auth->check_admin_auth();
        $CI->load->model('Reports');

        $result = $CI->Reports->wastage_entry();




        //   echo "ok";exit();

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));

            redirect(base_url('Creport/wastage_dec'));
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Creport/wastage_dec'));
        }
    }
    public function manage_stock_taking()
    {
        $CI = &get_instance();
        $CI->load->model('Reports');
        $CI->load->library('occational');
        $CI->load->model('warehouse');
        // $outlet_id = $this->warehouse->get_outlet_user()[0]['outlet_id'];
        $outlet_id = $this->session->userdata('outlet_id');
        if ($outlet_id == "HK7TGDT69VFMXB7") {
            $outlet_id = null;
        }
        $response = $this->Reports->manage_stock_taking($outlet_id);


        $sl = 1;
        foreach ($response as $k => $v) {
            $response[$k]['sl']  = $sl;
            $response[$k]['date']  = $this->occational->dateConvert($response[$k]['date']);
            if ($response[$k]['out'] == "HK7TGDT69VFMXB7") {
                $response[$k]['outlet_name'] = $this->db->select('central_warehouse')->from('central_warehouse')->where('warehouse_id', 'HK7TGDT69VFMXB7')->get()->row()->central_warehouse;
                // echo '<pre>';print_r($outlet_name);exit();

            } else {
                $response[$k]['outlet_name'] = $response[$k]['outlet_name'];
            }


            $sl++;
        }

        $data = array(
            'title'     => 'Manage Stock Taking',
            'response_list'  => $response,

        );



        $view = $this->parser->parse('report/manage_stock_taking', $data, true);
        $this->template->full_admin_html_view($view);
    }

    public function append_product()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->model('Warehouse');
        $CI->load->model('Products');
        $product_id = $this->input->post('product_id', TRUE);
        $rowCount = $this->input->post('rowCount', TRUE);


        $product_details  = $CI->Products->product_details($product_id)[0];

        //echo '<pre>';print_r($rowCount);exit();
        $tr = " ";
        if (!empty($product_details)) {
            $qty = 0;
            //         $sl=$rowCount+1;

            $tr .= "
            <tr id=\"row_" . $product_details->product_id . "\">
                        <td style=\"width: 5%\">
                                    $rowCount
                        </td>
                        <td style=\"width: 30%\">
                                $product_details->sku
                        </td>
						<td class=\"\" style=\"width: 50%\">

                            $product_details->product_name

							<input type=\"hidden\" class=\"form-control autocomplete_hidden_value product_id_" . $product_details->product_id . "\" name=\"product_id[]\" id=\"SchoolHiddenId_" . $product_details->product_id . "\" value = \"$product_details->product_id\"/>
                            <input type=\"hidden\" name=\"purchase_price[]\" class=\"purchase_price_" . $product_details->product_id . " form-control text-right\" id=\"purchase_price_" . $product_details->product_id . "\" placeholder=\"0.00\" min=\"0\" value='" . $product_details->purchase_price_ecom . "'/>

						</td>

                        <td>
                            <input type=\"text\" name=\"p_qty[]\" class=\"total_qntt_" . $product_details->product_id . " form-control text-right\" id=\"total_qntt_" . $product_details->product_id . "\" placeholder=\"0.00\" min=\"0\" value='" . $qty . "'/>
                        </td>

       

						<td>";
            $sl = 0;


            $tr .= "<button  class=\"btn btn-danger btn-md text-center\" type=\"button\"  onclick=\"deleteRow(this)\">" . '<i class="fa fa-close"></i>' . "</button>
						</td>
					</tr>";
            echo $tr;
        } else {
            return false;
        }
    }


    //Retrive right now inserted data to cretae html
    public function stock_taking_print($id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lreport');
        $content = $CI->lreport->stock_taking_print($id);
        $this->template->full_admin_html_view($content);
    }
    public function stock_taking_delete($stid)
    {
        $this->db->where('stid', $stid);
        $this->db->delete('stock_taking');

        $this->db->where('stid', $stid);
        $this->db->delete('stock_taking_details');

        $this->session->set_userdata(array('message' => 'Successfully Deleted'));
        redirect(base_url('Creport/manage_stock_taking'));
    }

    // public function save_stock()
    // {
    //     $access = $this->input->post('access', TRUE);
    //     $stid = (!empty($access)) ?  $this->input->post('st_id', TRUE) : mt_rand();

    //     $date = date('Y-m-d');
    //     $outlet_id = $this->input->post('outlet_name', TRUE);
    //     $product_id = $this->input->post('product_id', TRUE);
    //     $purchase_price = $this->input->post('purchase_price', TRUE);
    //     $quantity = array_filter($this->input->post('p_qty', TRUE));

    //     // echo '<pre>';print_r($purchase_price);exit();

    //     if (empty($quantity)) {
    //         $this->session->set_userdata(array('error_message' => 'Physical count is empty!!'));
    //         redirect(base_url('Creport/stock_taking'));
    //         exit();
    //     }





    //     $data1 = array(
    //         'stid'   => $stid,
    //         'outlet_id'   => $outlet_id,
    //         'stid_no'      => $this->input->post('stid', TRUE),
    //         'date'      => $this->input->post('date', TRUE),
    //         'total_product'      => count(array_filter($quantity, function ($x) {
    //             return !empty($x);
    //         })),
    //         'notes'   => $this->input->post('notes', TRUE),

    //     );


    //     // echo '<pre>';print_r($data1);exit();

    //     if ($access == 'edit') {
    //         $this->db->where('stid', $stid);
    //         $this->db->delete('stock_taking_details');
    //         $this->db->where('stid', $stid);
    //         $this->db->update('stock_taking', $data1);

    //         $this->db->where('VNo', $stid);
    //         $this->db->delete('acc_transaction');
    //     } elseif ($access == 'view') {
    //         $this->db->where('stid', $stid);
    //         $this->db->delete('stock_taking_details');
    //         $this->db->set('approve', 1);
    //         $this->db->where('stid', $stid);
    //         $this->db->update('stock_taking');
    //     } else {
    //         $this->db->insert('stock_taking', $data1);
    //     }

    //     for ($i = 0; $i < count($product_id); $i++) {
    //         $pr_id = $product_id[$i];
    //         $qty = $quantity[$i];
    //         $purchase_ecom = $purchase_price[$i];


    //         $prinfo  = $this->db->select('Avg(rate) as product_rate')->from('product_purchase_details')->where_in('product_id', $pr_id)->get()->row()->product_rate;
    //         $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $pr_id)->get()->row()->cost;

    //         if (!empty($purchase_ecom)) {
    //             $total_amount[] = $quantity[$i] * $purchase_ecom;
    //         } else if (!empty($prinfo)) {
    //             $total_amount[] = $quantity[$i] * $prinfo;
    //         } else if (!empty($production_cost)) {
    //             $total_amount[] = $quantity[$i] * $production_cost;
    //         } else {
    //             $total_amount[] = 0;
    //         }

    //         $data2 = array(
    //             'st_details_id'    => mt_rand(),
    //             'stid'           => $stid,
    //             'product_id'        => $pr_id,
    //             'current_stock'             => (!empty($this->input->post('stock_qty', TRUE)[$i]) ? $this->input->post('stock_qty', TRUE)[$i] : 0),
    //             'physical_stock'             => $qty,
    //             'difference'             => (!empty($this->input->post('difference', TRUE)[$i]) ? $this->input->post('difference', TRUE)[$i] : 0),
    //             'create_date'            => $date,
    //             'status'            => ($access == 'view') ?  '1' : '0',

    //         );


    //         // echo '<pre>';print_r($data1);
    //         if (!empty($qty)) {
    //             $this->db->insert('stock_taking_details', $data2);
    //         }
    //     }

    //     if (!empty($total_amount)) {
    //         $stcr = array(
    //             'VNo'            =>  $stid,
    //             'Vtype'          =>  'Stock Taking',
    //             'VDate'          =>  $date,
    //             'COAID'          =>  201,
    //             'Narration'      =>  'Stock Taking For STID -  ' . $stid . '',
    //             'Credit'          => array_sum($total_amount),
    //             'Debit'         =>   0,
    //             'IsPosted'       =>  1,
    //             'CreateBy'       => $this->session->userdata('user_id'),
    //             'CreateDate'     => $date,
    //             'IsAppove'       => 1
    //         );
    //         //$this->db->insert('acc_transaction', $stcr);
    //     }

    //     //   echo '<pre>';print_r(array_sum($total_amount));exit();


    //     redirect(base_url('Creport/manage_stock_taking'));
    // }
    public function save_stock()
    {
        $access = $this->input->post('access', TRUE);
        $stid = (!empty($access)) ?  $this->input->post('st_id', TRUE) : mt_rand();

        $date = date('Y-m-d');
        $outlet_id = $this->input->post('outlet_name', TRUE);
        $product_id = $this->input->post('product_id', TRUE);
        $purchase_price = $this->input->post('purchase_price', TRUE);
        // $quantity = array_filter($this->input->post('p_qty', TRUE));
        $quantity = ($this->input->post('p_qty', TRUE));

        // echo '<pre>';print_r($purchase_price);exit();

        // if (empty($quantity)) {
        //     $this->session->set_userdata(array('error_message' => 'Physical count is empty!!'));
        //     redirect(base_url('Creport/stock_taking'));
        //     exit();
        // }


        $data1 = array(
            'stid'   => $stid,
            'outlet_id'   => $outlet_id,
            'stid_no'      => $this->input->post('stid', TRUE),
            'date'      => $this->input->post('date', TRUE),
            // 'total_product'      => count(array_filter($quantity, function ($x) {
            //     // return !empty($x);
            //     return $x;
            // })),
            'total_product'      => count($quantity),
            'notes'   => $this->input->post('notes', TRUE),

        );


        if ($access == 'edit') {
            $this->db->where('stid', $stid);
            $this->db->delete('stock_taking_details');
            $this->db->where('stid', $stid);
            $this->db->update('stock_taking', $data1);

            $this->db->where('VNo', $stid);
            $this->db->delete('acc_transaction');
        } elseif ($access == 'view') {
            $this->db->where('stid', $stid);
            $this->db->delete('stock_taking_details');
            $this->db->set('approve', 1);
            $this->db->where('stid', $stid);
            $this->db->update('stock_taking');
        } else {
            $this->db->insert('stock_taking', $data1);
        }

        for ($i = 0; $i < count($product_id); $i++) {
            $pr_id = $product_id[$i];
            $qty = $quantity[$i];
            $purchase_ecom = $purchase_price[$i];


            $prinfo  = $this->db->select('Avg(rate) as product_rate')->from('product_purchase_details')->where_in('product_id', $pr_id)->get()->row()->product_rate;
            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $pr_id)->get()->row()->cost;

            if (!empty($purchase_ecom)) {
                $total_amount[] = $quantity[$i] * $purchase_ecom;
            } else if (!empty($prinfo)) {
                $total_amount[] = $quantity[$i] * $prinfo;
            } else if (!empty($production_cost)) {
                $total_amount[] = $quantity[$i] * $production_cost;
            } else {
                $total_amount[] = 0;
            }

            $data2 = array(
                'st_details_id'    => mt_rand(),
                'stid'           => $stid,
                'product_id'        => $pr_id,
                'current_stock'             => (!empty($this->input->post('stock_qty', TRUE)[$i]) ? $this->input->post('stock_qty', TRUE)[$i] : 0),
                'physical_stock'             => $qty,
                'difference'             => (!empty($this->input->post('difference', TRUE)[$i]) ? $this->input->post('difference', TRUE)[$i] : 0),
                'create_date'            => $date,
                'status'            => ($access == 'view') ?  '1' : '0',

            );


            // echo '<pre>';print_r($data1);
            // if (!empty($qty)) {
            //     $this->db->insert('stock_taking_details', $data2);
            // }
            $this->db->insert('stock_taking_details', $data2);
        }

        if (!empty($total_amount)) {
            $stcr = array(
                'VNo'            =>  $stid,
                'Vtype'          =>  'Stock Taking',
                'VDate'          =>  $date,
                'COAID'          =>  201,
                'Narration'      =>  'Stock Taking For STID -  ' . $stid . '',
                'Credit'          => array_sum($total_amount),
                'Debit'         =>   0,
                'IsPosted'       =>  1,
                'CreateBy'       => $this->session->userdata('user_id'),
                'CreateDate'     => $date,
                'IsAppove'       => 1
            );
            //$this->db->insert('acc_transaction', $stcr);
        }

        //   echo '<pre>';print_r(array_sum($total_amount));exit();


        redirect(base_url('Creport/manage_stock_taking'));
    }

    public function tools_stock()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->stock_report_tools();

        $this->template->full_admin_html_view($content);
    }


    public function supplier_wise_stock_report()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->supplier_wise_stock_report();

        $this->template->full_admin_html_view($content);
    }


    public function CheckList()
    {
        // GET data
        $this->load->model('Reports');
        $postData = $this->input->post();

        // echo "<pre>";
        // print_r($_POST);
        // exit();

        $pr_status = $this->input->post('pr_status');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $value = $this->input->post('value');
        // echo $from_date;
        // exit();


        // $data = $this->Reports->getCheckListNew2($postData, null, null, $pr_status, $from_date, $to_date);

        $data = $this->Reports->getCheckListNew2(
            $postData,
            null,
            null,
            $pr_status,
            $from_date,
            $to_date,
            $value
        );

        // echo "<pre>";
        // print_r($data);
        // exit();
        echo json_encode($data);
    }

    public function expiryCheckList()
    {
        // GET data
        $this->load->model('Reports');
        $postData = $this->input->post();

        $pr_status = $this->input->post('pr_status');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $data = $this->Reports->getExpiryCheckListReport2($postData, null, $pr_status, $from_date, $to_date);
        echo json_encode($data);
    }

    public function supplierStockCheckList()
    {
        // GET data
        $this->load->model('Reports');
        $postData = $this->input->post();

        $pr_status = $this->input->post('pr_status');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $data = $this->Reports->getSupplierCheckListReport($postData, null, $pr_status, $from_date, $to_date);
        echo json_encode($data);
    }

    public function suppliestock()
    {
        $this->load->model('Reports');
        $postData = $this->input->post();
        $data = $this->Reports->getSupplierStockList($postData);
        echo json_encode($data);
    }

    public function out_of_stock()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->out_of_stock();

        $this->template->full_admin_html_view($content);
    }

    public function birthday_noti()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->birthday_noti();

        $this->template->full_admin_html_view($content);
    }

    //Stock report supplir report
    public function stock_report_product_wise()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $CI->load->model('Reports');
        $today = date('Y-m-d');

        $product_id = $this->input->post('product_id', TRUE) ? $this->input->post('product_id', TRUE) : "";
        $supplier_id = $this->input->post('supplier_id', TRUE) ? $this->input->post('supplier_id', TRUE) : "";

        $date = $this->input->post('stock_date', TRUE) ? $this->input->post('stock_date', TRUE) : $today;
        $alldata = $this->input->post('all', TRUE);
        if (!empty($alldata)) {
            $perpagdata = $this->Reports->product_counter_by_supplier($supplier_id);
        } else {
            $perpagdata = 50;
        }
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Creport/stock_report_product_wise/');
        $config["total_rows"] = $this->Reports->product_counter_by_supplier($supplier_id);
        $config["per_page"] = $perpagdata;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
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
        $content = $this->lreport->stock_report_supplier_wise($product_id, $supplier_id, $date, $links, $config["per_page"], $page);


        $this->template->full_admin_html_view($content);
    }

    // date wise product report
    public function stock_date_to_date_product_wise()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $CI->load->model('Reports');
        $today = date('Y-m-d');
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $alldata = $this->input->get('all');
        if (!empty($alldata)) {
            $perpagdata = $this->Reports->product_counter_by_productdatetodate($from_date, $to_date);
        } else {
            $perpagdata = 50;
        }

        #exit;
        #pagination starts
        #
        $config["base_url"] = base_url('Creport/stock_date_to_date_product_wise/');
        $config["total_rows"] = $this->Reports->product_counter_by_productdatetodate($from_date, $to_date);
        $config["per_page"] = $perpagdata;
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
        $content = $this->lreport->stock_report_product_date_date_wise($from_date, $to_date, $links, $config["per_page"], $page);


        $this->template->full_admin_html_view($content);
    }

    //Stock report supplir report
    public function stock_report_supplier_wise()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');
        $CI->load->model('Reports');
        $today = date('Y-m-d');

        $product_id = $this->input->post('product_id', TRUE) ? $this->input->post('product_id', TRUE) : "";
        $supplier_id = $this->input->post('supplier_id', TRUE) ? $this->input->post('supplier_id', TRUE) : "";
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $alldata = $this->input->post('all', TRUE);
        if (!empty($alldata)) {
            $perpagdata = $this->Reports->stock_report_product_bydate_count($supplier_id, $from_date, $to_date);
        } else {
            $perpagdata = 50;
        }
        #
        #pagination starts
        #
        $config["base_url"] = base_url('Creport/stock_report_supplier_wise');
        $config["total_rows"] = $this->Reports->stock_report_product_bydate_count($supplier_id, $from_date, $to_date);

        $config["per_page"] = $perpagdata;
        $config["uri_segment"] = 3;
        $config["num_links"] = 5;
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
        $content = $this->lreport->stock_report_product_wise($supplier_id, $from_date, $to_date, $links, $config["per_page"], $page);

        $this->template->full_admin_html_view($content);
    }

    //Get product by supplier
    public function get_product_by_supplier()
    {
        $supplier_id = $this->input->post('supplier_id', TRUE);

        $product_info_by_supplier = $this->db->select('a.*,b.*')
            ->from('product_information a')
            ->join('supplier_product b', 'a.product_id=b.product_id')
            ->where('b.supplier_id', $supplier_id)
            ->get()
            ->result();

        if ($product_info_by_supplier) {
            echo "<select class=\"form-control\" id=\"supplier_id\" name=\"supplier_id\">
	                <option value=\"\">" . display('select_one') . "</option>";
            foreach ($product_info_by_supplier as $product) {
                echo "<option value='" . $product->product_id . "'>" . $product->product_name . '-(' . $product->product_model . ')' . " </option>";
            }
            echo " </select>";
        }
    }

    #===============Report paggination=============#

    public function pagination($per_page, $page)
    {
        $CI = &get_instance();
        $CI->load->model('Reports');
        $product_id = $this->input->post('product_id', TRUE);

        $config = array();
        $config["base_url"] = base_url() . $page;
        $config["total_rows"] = $this->Reports->product_counter($product_id);
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 4;
        $config["num_links"] = 5;
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



        $this->pagination->initialize($config);

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $limit = $config["per_page"];
        return $links = $this->pagination->create_links();
    }

    //pdf stock report
    public function stock_report_downloadpdf()
    {
        $CI = &get_instance();
        $CI->load->model('Reports');
        $CI->load->model('Web_settings');
        $CI->load->model('Invoices');
        $CI->load->library('pdfgenerator');
        $stok_report = $CI->Reports->stock_report_pdf();
        $sub_total_in = 0;
        $sub_total_out = 0;
        $sub_total_stock = 0;
        $sub_total_stokpurchase = 0;
        $sub_total_stoksale = 0;
        if (!empty($stok_report)) {
            $i = $page;
            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['sl'] = $i;
            }

            foreach ($stok_report as $k => $v) {
                $i++;
                $stok_report[$k]['stok_quantity_cartoon'] = ($stok_report[$k]['totalPurchaseQnty'] - $stok_report[$k]['totalSalesQnty']);
                $stok_report[$k]['SubTotalOut'] = ($sub_total_out + $stok_report[$k]['totalSalesQnty']);
                $sub_total_out = $stok_report[$k]['SubTotalOut'];
                $stok_report[$k]['SubTotalIn'] = ($sub_total_in + $stok_report[$k]['totalPurchaseQnty']);
                $sub_total_in = $stok_report[$k]['SubTotalIn'];
                $stok_report[$k]['SubTotalStock'] = ($sub_total_stock + $stok_report[$k]['stok_quantity_cartoon']);
                $sub_total_stock = $stok_report[$k]['SubTotalStock'];

                $stok_report[$k]['total_sale_price'] = $stok_report[$k]['stok_quantity_cartoon'] * $stok_report[$k]['price'];

                $stok_report[$k]['sales_price'] = $stok_report[$k]['price'];

                $sub_total_stoksale += $stok_report[$k]['total_sale_price'];
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Reports->retrieve_company();
        $data = array(
            'title'            => display('stock_report'),
            'stok_report'      => $stok_report,
            'product_model'    => $stok_report[0]['product_model'],
            'date'             => $date,
            'sub_total_in'     => number_format($sub_total_in, 2, '.', ','),
            'sub_total_out'    => number_format($sub_total_out, 2, '.', ','),
            'sub_total_stock'  => number_format($sub_total_stock, 2, '.', ','),
            'company_info'     => $company_info,
            'stock_purchase'   => number_format($sub_total_stokpurchase, 2, '.', ','),
            'stock_sale'       => number_format($sub_total_stoksale, 2, '.', ','),
            'currency'         => $currency_details[0]['currency'],
            'position'         => $currency_details[0]['currency_position'],
            'software_info'    => $currency_details,
            'company'          => $company_info,
        );
        $this->load->helper('download');
        $content = $this->parser->parse('report/stock_report_pdf', $data, true);
        $time = date('Ymdhi');
        $dompdf = new DOMPDF();
        $dompdf->load_html($content);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/' . 'stock_report' . $time . '.pdf', $output);
        $file_path = 'assets/data/pdf/' . 'stock_report' . $time . '.pdf';
        $file_name = 'stock_report' . $time . '.pdf';
        force_download(FCPATH . 'assets/data/pdf/' . $file_name, null);
    }

    public function transfer_report()
    {
        $CI = &get_instance();
        $CI->load->model('Rqsn');
        $CI->load->model('Warehouse');

        $outlet_id = null;
        // $signed_outlet = $CI->Warehouse->get_outlet_user();
        // if ($signed_outlet) {
        //     $outlet_id = $signed_outlet[0]['outlet_id'];
        // }
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] ? $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] : null;
        $transfer_count = $CI->Rqsn->transfer_count($outlet_id);



        $data = array(
            'title'     => 'Transfer Report',
            'length'    => $transfer_count[0]->allcount,
        );

        // echo '<pre>';
        // print_r($data);
        // exit();

        $view = $CI->parser->parse('report/transfer_report', $data, true);
        $this->template->full_admin_html_view($view);
    }

    public function view_individual_transfer($rqsn_id)
    {
        $CI = &get_instance();
        $CI->load->model('Rqsn');
        $rqsn_details = $CI->Rqsn->get_rqsn_transfer($rqsn_id);
        $sl = 1;
        $rcv_by = $this->db->select('b.first_name,b.last_name')->from('outlet_warehouse a')
            ->join('users b', 'a.user_id=b.user_id')->where('a.outlet_id', $rqsn_details[0]['from_id'])->get()->row();

        foreach ($rqsn_details as $rq) {





            $rq['sl'] = $sl;
            //            $rq['send_by'] = $send_by;
            $rq['balance'] = $rq['quantity'] - $rq['a_qty'];
            $new_rq[] = $rq;
            $sl++;
        }

        $data = array(
            'title'     => 'View Transfer Report',
            'rqsn_list' => $new_rq,
            'rcv_by' => $rcv_by->first_name . ' ' . $rcv_by->last_name
        );



        $view = $CI->parser->parse('report/individual_transfer', $data, true);
        $this->template->full_admin_html_view($view);
    }

    public function transferAlldata()
    {
        $CI = &get_instance();
        $this->load->library('occational');
        $this->load->model('Web_settings');
        $CI->load->model('Rqsn');
        $this->load->model('Warehouse');
        $postData = $this->input->post();
        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $response = array();

        $outlet_id = null;
        // $signed_outlet = $CI->Warehouse->get_outlet_user();
        // if ($signed_outlet) {
        //     $outlet_id = $signed_outlet[0]['outlet_id'];
        // }
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] ? $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] : null;
        // echo '<pre>';
        // print_r($outlet_id);
        // exit();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (a.rqsn_id like '%" . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('*');
        $this->db->from('rqsn a');
        $this->db->join('rqsn_details b', 'b.rqsn_id=a.rqsn_id');
        $this->db->where('a.to_id != 3');
        $this->db->where('b.status', 3);
        $this->db->group_by('a.rqsn_id');

        if ($outlet_id) {
            $this->db->group_start()
                ->where('a.from_id', $outlet_id)
                ->or_where('a.to_id', $outlet_id)
                ->group_end();
        }


        $records = $this->db->get()->result();
        $totalRecords = count($records);

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('rqsn a');
        $this->db->join('rqsn_details b', 'b.rqsn_id=a.rqsn_id');
        $this->db->where('a.to_id != 3');
        $this->db->where('b.status', 3);
        $this->db->group_by('a.rqsn_id');
        if ($outlet_id) {
            $this->db->group_start()
                ->where('a.from_id', $outlet_id)
                ->or_where('a.to_id', $outlet_id)
                ->group_end();
        }

        if ($searchValue != '')
            $this->db->where($searchQuery);

        $records = $this->db->get()->result();
        $this->db->limit($rowperpage, $start);
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $this->db->select('a.*')
            ->from('rqsn a')
            ->where('a.to_id != 3')
            ->join('rqsn_details b', 'b.rqsn_id=a.rqsn_id')
            ->where('b.status', 3)
            ->group_by('a.rqsn_id')
            ->order_by('a.date', 'desc');


        if ($outlet_id) {
            $this->db->group_start()
                ->where('a.from_id', $outlet_id)
                ->or_where('a.to_id', $outlet_id)
                ->group_end();
        }


        if ($searchValue != '')
            $this->db->where($searchQuery);

        // $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
            
        $data = array();
        $sl = 1;


        foreach ($records as $record) {
            // echo '<pre>';
            // print_r($record);
            // exit();
            $rq_details = $CI->Rqsn->get_rqsn_transfer($record->rqsn_id, null, 7);
            $html = '';
            $html = $html . '<table class="table table-bordered table-striped"><thead><tr><th>Product Name</th><th>Transferred Quantity</th></tr></thead><tbody>';
            foreach ($rq_details as $det) {
                $html = $html . '<tr><td>' . $det['product_name'] . '-' . $det['product_model'] . '-' . $det['size_name'] . '-' . $det['color_name'] . '</td>' .
                    '<td>' . $det['a_qty'] . '</td>' .
                    '</tr>';
            }
            $html .= "</tbody></table>";

            $from_outlet = $this->Warehouse->get_outlet_or_cw_info($record->from_id)[0]['outlet_name'];
            $to_outlet = $this->Warehouse->get_outlet_or_cw_info($record->to_id)[0]['outlet_name'];
            $date = $record->date;
            $rqsn_id = '<a href="' . base_url("Creport/view_individual_transfer/" . $record->rqsn_id) . '">' . $record->rqsn_id . '</a>';
            $action = '<button class="btn btn-sm"><i class="fa fa-eye"></i></button>';

            $action = '<a href="' . base_url("Creport/view_individual_transfer/" . $record->rqsn_id) . '"><button type="button" class="btn btn-sm info_button_' . $sl . '" aria-describedby="tooltip"><i class="fa fa-eye"></i></button>
                                                    <div id="tooltip" class="tooltip' . $sl . '" role="tooltip" data-html="true">'
                . $html .
                '<div id="arrow" data-popper-arrow></div>
                                                    </div>
                                                </a>';

            // print_r($record->outlet_id);exit();
            $data[] = array(
                'sl'               => $sl,
                'from_outlet'        => $to_outlet,
                'to_outlet'        => $from_outlet,
                'rqsn_id'      => $rqsn_id,
                'date'      => $date,
                'button'           => $action,

            );
            $sl++;
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );

        // echo '<pre>';
        // print_r($response);
        // exit();

        echo json_encode($response);
    }
    public function daywise_sales_report() {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lreport');

        $content = $CI->lreport->daywise_sales_report();

        $this->template->full_admin_html_view($content);
        
    }
}
