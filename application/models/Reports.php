<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class reports extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //Count report
    public function count_stock_report()
    {
        $this->db->select("a.unit,a.product_name,a.product_id,a.price,a.product_model,(select sum(quantity) from invoice_details where product_id= `a`.`product_id`) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->where(array('a.status' => 1));
        $this->db->group_by('a.product_id');
        $query = $this->db->get();
        $result = $query->result_array();
        $stock = 0;
        $i = 0;
        foreach ($result as $stockproduct) {
            $stokqty = $stockproduct['totalBuyQnty'] - $stockproduct['totalSalesQnty'];
            if ($stokqty < 10) {

                $stock = $stock + 1;
            }
            $i++;
        }
        return $stock;
    }

    //Out of stock
    public function out_of_stock()
    {

        $this->db->select("a.unit,a.product_name,a.product_id,a.price,a.product_model,(select sum(quantity) from invoice_details where product_id= `a`.`product_id`) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->where(array('a.status' => 1));
        $this->db->group_by('a.product_id');
        $query = $this->db->get();
        $result = $query->result_array();
        $stock = [];
        $i = 0;
        foreach ($result as $stockproduct) {
            $stokqty = $stockproduct['totalBuyQnty'] - $stockproduct['totalSalesQnty'];
            if ($stokqty < 10) {

                $stock[$i]['stock']         = $stockproduct['totalBuyQnty'] - $stockproduct['totalSalesQnty'];
                $stock[$i]['product_id']    = $stockproduct['product_id'];
                $stock[$i]['product_name']  = $stockproduct['product_name'];
                $stock[$i]['product_model'] = $stockproduct['product_model'];
                $stock[$i]['unit']          = $stockproduct['unit'];
            }
            $i++;
        }
        return $stock;
    }

    public function birthday_noti()
    {

        $month = date("m");
        $day = date("d");

        $this->db->select("a.*,b.designation as des");
        $this->db->from('employee_history a');
        $this->db->join('designation b', 'a.designation=b.id');

        $this->db->where('DATE_FORMAT(dob,"%m")', $month);
        $this->db->where('DATE_FORMAT(dob,"%d")', $day);
        //        $this->db->group_by('a.product_id');
        $query = $this->db->get();
        $result = $query->result_array();
        // $stock = [];
        $i = 0;
        foreach ($result as $r) {
            // $stokqty = $stockproduct['totalBuyQnty']-$stockproduct['totalSalesQnty'];


            //  $stock[$i]['stock']         = $stockproduct['totalBuyQnty']-$stockproduct['totalSalesQnty'];
            $data[$i]['id']    = $r['id'];
            $data[$i]['first_name']  = $r['first_name'];
            $data[$i]['designation'] = $r['des'];
            $data[$i]['dob']          = $r['dob'];
            $data[$i]['month']          = $month;
            $data[$i]['date']          = $day;

            $i++;
        }
        // return $data;
    }

    //Out of stock count
    public function out_of_stock_count()
    {

        $this->db->select("a.unit,a.product_name,a.product_id,a.price,a.product_model,(select sum(quantity) from invoice_details where product_id= `a`.`product_id`) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->where(array('a.status' => 1));
        $this->db->group_by('a.product_id');
        $query = $this->db->get();
        $result = $query->result_array();
        $stock = 0;
        $i = 0;
        foreach ($result as $stockproduct) {
            $stokqty = $stockproduct['totalBuyQnty'] - $stockproduct['totalSalesQnty'];
            if ($stokqty < 10) {

                $stock = $stock + 1;
            }
            $i++;
        }
        return $stock;
    }
    public function birthday_noti_count()
    {
        $month = date("m");
        $day = date("d");
        $this->db->select("a.*,b.designation as des");
        $this->db->from('employee_history a');
        $this->db->join('designation b', 'a.designation=b.id');
        $this->db->where('DATE_FORMAT(dob,"%m")', $month);
        $this->db->where('DATE_FORMAT(dob,"%d")', $day);
        //        $this->db->where(array('a.status' => 1));
        //        $this->db->group_by('a.product_id');
        $query = $this->db->get();
        if ($query->num_rows() >= 0) {
            return $query->num_rows();
        }
        return $query;
    }

    //Retrieve Single Item Stock Stock Report
    public function stock_report($limit, $page)
    {
        $this->db->select("a.product_name,a.product_id,a.cartoon_quantity,a.price, a.product_model,sum(b.quantity) as 'totalSalesQnty',(select sum(product_purchase_details.quantity) from product_purchase_details where product_id= `a`.`product_id`) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->join('invoice_details b', 'b.product_id = a.product_id');
        $this->db->where(array('a.status' => 1, 'b.status' => 1));
        $this->db->group_by('a.product_id');
        $this->db->order_by('a.product_id', 'desc');
        $this->db->limit($limit, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function all_purchase_id()
    {
        $this->db->select('purchase_id');
        $this->db->from('product_purchase');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }



    //Retrieve Single Item Stock Stock Report
    public function stock_report_single_item($product_id)
    {
        $this->db->select("a.product_name,a.cartoon_quantity,a.price,a.product_model,sum(b.quantity) as 'totalSalesQnty',sum(c.quantity) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->join('invoice_details b', 'b.product_id = a.product_id');
        $this->db->join('product_purchase_details c', 'c.product_id = a.product_id');
        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1, 'b.status' => 1));
        $this->db->group_by('a.product_id');
        $this->db->order_by('a.product_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Stock Report by date
    public function stock_report_bydate($product_id, $date, $limit, $page)
    {

        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                sum(b.sell) as 'totalSalesQnty',
                sum(b.Purchase) as 'totalPurchaseQnty',
                AVG(c.supplier_price) as 'purchasprice'
                ");
        $this->db->from('product_information a');

        $this->db->join('stock_history b', 'b.product_id = a.product_id', 'left');
        $this->db->join('supplier_product c', 'c.product_id = a.product_id', 'left');
        if (empty($product_id)) {
            $this->db->where(array('a.status' => 1));
        } else {
            //Single product information
            $this->db->where(array('a.status' => 1, 'b.vdate <= ' => $date, 'a.product_id' => $product_id));
        }

        $this->db->group_by('a.product_id');
        $this->db->order_by('a.product_name', 'asc');
        $this->db->limit($limit, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function wastage_entry()
    {
        $this->load->model('Web_settings');
        $this->load->model('warehouse');

        $quantity            = $this->input->post('product_quantity', true);
        $p_id             = $this->input->post('product_id', true);
        //  $unit             = $this->input->post('unit',true);


        $outlet_id = $this->warehouse->get_outlet_user()[0]['outlet_id'];
        if (empty($outlet_id)) {
            $out = 'HK7TGDT69VFMXB7';
        } else {
            $out = $outlet_id;
        }

        for ($i = 0, $n   = count($p_id); $i < $n; $i++) {
            $qty  = $quantity[$i];
            //   $un  = $unit[$i];
            $product_id   = $p_id[$i];

            $data = array(
                'outlet_id' => $out,
                'product_id' => $product_id,
                'wastage_quantity' => $qty,
                'date' => date('Y-m-d'),
                'status' => 1


            );
            if (!empty($quantity)) {
                $this->db->insert('wastage_dec', $data);
            }
        }

        return $data;
    }

    public function totalnumberof_product()
    {

        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                c.supplier_price
                ");
        $this->db->from('product_information a');

        $this->db->join('supplier_product c', 'c.product_id = a.product_id', 'left');
        $this->db->group_by('a.product_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    public function totalnumberof_purchase()
    {

        // $this->db->select("a.*,
        //         a.product_name,
        //         a.product_id,
        //         a.product_model,
        //         c.supplier_price
        //         ");
        // $this->db->from('product_information a');

        // $this->db->join('supplier_product c', 'c.product_id = a.product_id', 'left');
        // $this->db->group_by('a.product_id');

        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                b.name,
                p.purchase_id,
                p.rate,
                p.expired_date,
             
                ");
        $this->db->from('product_purchase_details p');
        $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    public function manage_stock_taking($outlet_id)
    {

        $this->db->select("a.*,o.outlet_name,a.outlet_id as out");
        $this->db->from('stock_taking a');
        $this->db->join('outlet_warehouse o', 'a.outlet_id=o.outlet_id', 'left');
        if (!empty($outlet_id)) {
            $this->db->where('a.outlet_id', $outlet_id);
        }
        $this->db->order_by('a.id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function stock_taking_details($category, $product_id, $from_date, $to_date)
    {
        $dateRange = "a.create_date BETWEEN '$from_date' AND '$to_date'";

        $this->db->select("*");
        $this->db->from('stock_taking_details a');
        $this->db->join('product_information c', 'c.product_id = a.product_id', 'left');
        if (!empty($category)) {
            $this->db->like('c.category_id', $category, 'both');
        }
        if (!empty($product_id)) {
            $this->db->where('a.product_id', $product_id);
        }
        if (!empty($from_date)) {
            $this->db->where($dateRange);
        }
        $this->db->order_by('a.create_date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }




    public function getCheckList($postData = null, $post_product_id = null, $product_sku = null, $pr_status = null, $from_date = null, $to_date = null)
    {
        // echo $from_date;
        // exit();



        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        // $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        // echo $outlet_id;exit();
        $outlet_id = $this->input->post('outlet_id', TRUE);
        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }



        $p_s = $this->input->post('product_status', TRUE);
        $product_sku = $this->input->post('product_sku', TRUE);
        if ($from_date) {
            // $date = date('Y-m-d');
            // $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
            $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        }

        // if ($from_date == null) {
        //     $date = date('Y-m-d');
        //     $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
        // } else {
        //     $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        // }


        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (a.product_name like '%"
                    . $searchValue .
                    "%' or a.product_model like '%"
                    . $searchValue .
                    "%' or a.sku like '%"
                    . $searchValue .
                    "%' or b.name like '%"
                    . $searchValue .
                    "%'  or a.product_id like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information a');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');

            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }




            if (isset($p_s) && $p_s != '') {
                $this->db->where('a.finished_raw', $p_s);
            }

            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
            $this->db->group_by('a.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;

            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information a');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');
            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }

            //            if ($product_sku != '') {
            //                for ($i = 0, $ien = count($product_sku); $i < $ien; $i++) {
            //                    $this->db->or_where('a.sku',$product_sku[$i]);
            //                }
            //            }


            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('a.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
        }
        ## Fetch records
        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                b.name
             
                ");
        $this->db->from('product_information a');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->group_by('a.product_id');
        $this->db->limit($rowperpage, $start);
        if ($product_sku != '') {
            $this->db->where_in('a.sku', $product_sku);
        }

        //        if ($product_sku != '') {
        //            for ($i = 0, $ien = count($product_sku); $i < $ien; $i++) {
        //                $this->db->or_where('a.sku',$product_sku[$i]);
        //            }
        //        }


        if (!$post_product_id && $searchValue != '')
            $this->db->where($searchQuery);

        if ($post_product_id) {
            $this->db->where('a.product_id', $post_product_id);
        }


        if (isset($p_s) && $p_s != '') {
            $this->db->where('a.finished_raw', $p_s);
        }

        //        if (!$post_product_id) {
        //            if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
        //            $this->db->order_by($columnName, $columnSortOrder);
        //            $this->db->group_by('a.product_id');
        //            $this->db->limit($rowperpage, $start);
        //        }
        $records = $this->db->get()->result();
        $data = array();

        // echo '<pre>';
        // print_r($records);
        // exit();

        $sl = 1;
        $stock = 0;
        $closing_stock = 0;
        $opening_stock = 0;


        // echo "<pre>";
        // print_r($records);
        // exit();





        foreach ($records as $record) {

            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

            $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);

            if ($from_date) {
                $this->db->where('b.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('b.date <=', $to_date);
            }

            if ($outlet_id) {
                $this->db->where('b.outlet_id', $outlet_id);
            }


            $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')->from('invoice_details a')->join('invoice b', 'b.invoice_id = a.invoice_id')->where('a.pre_order', 1)
                ->where('a.product_id', $record->product_id)
                ->get()->row();
            $warrenty_stock = $this->db->select('sum(ret_qty) as totalWarrentyQnty')->from('warrenty_return')->where('product_id', $record->product_id)->get()->row();
            //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();

            if ($from_date) {
                $this->db->where('product_purchase.purchase_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('product_purchase.purchase_date <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }

            $stockout = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as damaged_qty,Avg(product_purchase_details.rate) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where('product_purchase_details.product_id', $record->product_id)->get()->row();



            $stockout_for_purchase_price = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.total_amount) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where('product_purchase_details.product_id', $record->product_id)
                ->where('product_purchase_details.qty >=', 0)
                //->where('product_purchase_details.damaged_qty <=', 0)
                ->get()->row();

            $stockout_for_weighted_average = $stockout_for_purchase_price->purchaseprice / $stockout_for_purchase_price->totalPurchaseQnty;



            if ($from_date) {
                $this->db->where('product_return.date_return >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('product_return.date_return <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('product_return.outlet_id', $outlet_id);
            }
            $product_return = $this->db->select('sum(ret_qty) as totalReturn')->from('product_return')->where('usablity', 2)->where('product_id', $record->product_id)->get()->row();


            if ($from_date) {
                $this->db->where('rqsn.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('rqsn.date <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            $stockin_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 2)
                ->where('rqsn_details.product_id', $record->product_id)
                ->get()
                ->row();

            if ($from_date) {
                $this->db->where('rqsn.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('rqsn.date <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            $stockout_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('isaprv', 1)
                ->where('isrcv', 1)
                ->where('product_id', $record->product_id)
                ->get()
                ->row();
            $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);


            if ($from_date) {
                $this->db->where('opening_inventory.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('opening_inventory.date <=', $to_date);
            }
            $open_stock = $this->db->select('stock_qty')->from('opening_inventory')->where('product_id', $record->product_id)->get()->row();
            if ($from_date) {
                $this->db->where('production.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('production.date <=', $to_date);
            }
            $production_qty = $this->db->select('SUM(quantity) as pro_qty')
                ->from('production_goods')
                ->join('production', 'production_goods.pro_id=production.pro_id', 'left')
                ->where('production_goods.product_id', $record->product_id)
                ->group_by('production_goods.product_id')
                ->get()
                ->row();

            if ($from_date) {
                $this->db->where('production.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('production.date <=', $to_date);
            }
            $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
                ->from('item_usage')
                ->join('production', 'item_usage.production_id=production.pro_id', 'left')
                ->where('item_usage.item_id', $record->product_id)
                ->group_by('item_usage.item_id')
                ->get()
                ->row();





            $sprice = (!empty($record->price) ? $record->price : 0);
            // $pprice = (!empty($stockout->purchaseprice) ? sprintf('%0.2f', $stockout->purchaseprice) : 0);
            $pprice = $stockout_for_weighted_average;
            $total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);
            $total_out = '';

            if ($record->finished_raw == 1) {
                $total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) - (!empty($stockin_outlet->totaloutletQnty) ? $stockin_outlet->totaloutletQnty : 0);
            } else {
                if ($from_date) {
                    $this->db->where('transfer_items.date >=', $from_date);
                }
                if ($to_date) {
                    $this->db->where('transfer_items.date <=', $to_date);
                }
                $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                    ->from('transfer_item_details')
                    ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                    ->where('transfer_item_details.product_id', $record->product_id)
                    ->group_by('transfer_item_details.product_id')
                    ->get()
                    ->row();
                $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            }
            if ($from_date) {
                $this->db->where('a.create_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('a.create_date <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('b.outlet_id', $outlet_id);
            }
            $phy_count = $this->db->select('SUM(a.difference) as phy_qty')
                ->from('stock_taking_details a')
                ->join('stock_taking b', 'b.stid = a.stid')
                ->where(array(
                    'a.product_id' => $record->product_id,
                    //                    'create_date >=' => $date,
                    'a.status' => 1,

                ))
                ->group_by('a.product_id')
                ->order_by('a.id', 'desc')
                ->get()
                ->row();



            $newStock = (!empty($warrenty_stock->totalWarrentyQnty) ? $warrenty_stock->totalWarrentyQnty : 0);
            $diff = (!empty($phy_count->phy_qty) ? $phy_count->phy_qty : 0);

            // $stock = (($total_in - (($total_out + $product_return->totalReturn) - $newStock))) + $diff;

            $stock = (($total_in - (($total_out) - $newStock))) + $diff;



            /************************
             *  Opening Stock Start *
             * **********************/
            // echo "<pre>";
            // print_r($op_date);
            // exit();

            if ($op_date) {
                $this->db->where('b.date <=', $op_date);
                if ($outlet_id) {
                    $this->db->where('b.outlet_id', $outlet_id);
                }
                $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')->from('invoice_details a')->join('invoice b', 'b.invoice_id = a.invoice_id')->where('a.product_id', $record->product_id)->get()->row();

                $warrenty_stock = $this->db->select('sum(ret_qty) as totalWarrentyQnty')->from('warrenty_return')->where('product_id', $record->product_id)->get()->row();
                //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();

                $this->db->where('product_purchase.purchase_date <=', $op_date);

                if ($outlet_id) {
                    $this->db->where('product_purchase.outlet_id', $outlet_id);
                }



                $stockout = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as damaged_qty, Avg(product_purchase_details.rate) as purchaseprice')
                    ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                    ->from('product_purchase_details')
                    ->where('product_purchase_details.product_id', $record->product_id)
                    ->where('product_purchase.purchase_date <=', $op_date)
                    ->get()
                    ->row();

                // echo "<pre>";
                // print_r($stockout->damaged_qty);
                // exit();


                $stockout_for_purchase_price = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.total_amount) as purchaseprice')
                    ->from('product_purchase_details')
                    ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                    ->where('product_purchase_details.product_id', $record->product_id)
                    ->where('product_purchase_details.qty >=', 0)
                    // ->where('product_purchase_details.damaged_qty <=', 0)
                    ->get()->row();

                $stockout_for_weighted_average = $stockout_for_purchase_price->purchaseprice / $stockout_for_purchase_price->totalPurchaseQnty;


                if ($from_date) {
                    $this->db->where('product_return.date_return >=', $from_date);
                }
                if ($to_date) {
                    $this->db->where('product_return.date_return <=', $to_date);
                }
                if ($outlet_id) {
                    $this->db->where('product_return.outlet_id', $outlet_id);
                }
                $product_return = $this->db->select('sum(ret_qty) as totalReturn')->from('product_return')->where('usablity', 2)->where('outlet_id', $outlet_id)->where('product_id', $record->product_id)->get()->row();

                $this->db->where('rqsn.date <=', $op_date);
                if ($outlet_id) {
                    $this->db->where('rqsn.to_id', $outlet_id);
                }
                $this->db->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id');
                $stockout_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                    ->from('rqsn_details')
                    ->where('isaprv', 1)
                    ->where('isrcv', 1)

                    ->where('product_id', $record->product_id)
                    ->get()
                    ->row();

                $this->db->where('rqsn.date <=', $op_date);
                if ($outlet_id) {
                    $this->db->where('rqsn.from_id', $outlet_id);
                }
                $stockin_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                    ->from('rqsn_details')
                    ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                    ->where('rqsn_details.isaprv', 1)
                    ->where('rqsn_details.isrcv', 1)
                    ->where('rqsn_details.is_return', 2)
                    ->where('rqsn_details.product_id', $record->product_id)
                    ->get()
                    ->row();

                $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);

                // echo '<pre>';
                // print_r($product_supplier_price[0]);
                // exit();

                $open_stock = $this->db->select('stock_qty')->from('opening_inventory')->where('product_id', $record->product_id)->get()->row();

                $this->db->where('production.date <=', $op_date);
                $production_qty = $this->db->select('SUM(quantity) as pro_qty')
                    ->from('production_goods')
                    ->join('production', 'production.pro_id = production_goods.pro_id', 'left')
                    ->where('product_id', $record->product_id)
                    ->group_by('product_id')
                    ->get()
                    ->row();


                $this->db->where('production.date <=', $op_date);
                $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
                    ->from('item_usage')
                    ->join('production', 'production.pro_id = item_usage.production_id', 'left')
                    ->where('item_id', $record->product_id)
                    ->group_by('item_id')
                    ->get()
                    ->row();





                $sprice = (!empty($record->price) ? $record->price : 0);
                // $pprice = (!empty($stockout->purchaseprice) ? sprintf('%0.2f', $stockout->purchaseprice) : 0);
                $pprice = $stockout_for_weighted_average;
                //                $opening_total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);
                //                $opening_total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0) + (!empty($stockin_outlet
                //                        ->totaloutletQnty) ? $stockin_outlet
                //                        ->totaloutletQnty : 0);
                $opening_total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);

                $opening_total_out = '';

                if ($record->finished_raw == 1) {
                    $opening_total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0)  + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) - (!empty($stockin_outlet->totaloutletQnty) ? $stockin_outlet->totaloutletQnty : 0);
                } else {


                    $this->db->where('transfer_items.date <=', $op_date);
                    $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                        ->from('transfer_item_details')
                        ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                        ->where('transfer_item_details.product_id', $record->product_id)
                        ->group_by('transfer_item_details.product_id')
                        ->get()
                        ->row();
                    $opening_total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
                }

                if ($outlet_id) {
                    $this->db->where('b.outlet_id', $outlet_id);
                }

                $this->db->where('a.create_date <=', $op_date);
                $phy_count = $this->db->select('SUM(a.difference) as phy_qty')
                    ->from('stock_taking_details a')
                    ->join('stock_taking b', 'b.stid = a.stid')
                    ->where(array(
                        'a.product_id' => $record->product_id,
                        'create_date >=' => $op_date,
                        'a.status' => 1,

                    ))
                    ->group_by('a.product_id')
                    ->order_by('a.id', 'desc')
                    ->get()
                    ->row();
                $newStock = (!empty($warrenty_stock->totalWarrentyQnty) ? $warrenty_stock->totalWarrentyQnty : 0);
                $diff = (!empty($phy_count->phy_qty) ? $phy_count->phy_qty : 0);

                // $opening_stock = (($opening_total_in - ($opening_total_out + $product_return->totalReturn)) - $newStock) + $diff;

                $opening_stock = (($opening_total_in - ($opening_total_out)) - $newStock) + $diff;


                //                $opening_total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0);

                //  $opening_stock = $opening_total_in - $opening_total_out;
            } else {
                $opening_stock = $stock;
            }
            // Opening Stock End


            /*********************
             *
             *  Closing Stock Start
             *
             * ********************/
            if ($to_date) {
                if ($outlet_id) {
                    $this->db->where('b.outlet_id', $outlet_id);
                }

                $this->db->where('b.date <=', $to_date);
                $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')->from('invoice_details a')->join('invoice b', 'b.invoice_id = a.invoice_id')->where('a.product_id', $record->product_id)->get()->row();
                $warrenty_stock = $this->db->select('sum(ret_qty) as totalWarrentyQnty')->from('warrenty_return')->where('product_id', $record->product_id)->get()->row();
                //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();

                // $this->db->where('product_purchase.purchase_date <=', $to_date);
                if ($outlet_id) {
                    $this->db->where('product_purchase.outlet_id', $outlet_id);
                }

                $stockout = $this->db->select('sum(qty) as totalPurchaseQnty, sum(damaged_qty) as damaged_qty, Avg(rate) as purchaseprice')
                    ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                    ->from('product_purchase_details')
                    ->where('product_purchase_details.product_id', $record->product_id)
                    // ->where('product_purchase.purchase_date <=', $to_date)
                    ->get()->row();

                // echo "<pre>";
                // print_r($stockout->damaged_qty);
                // exit();





                $stockout_for_purchase_price = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.total_amount) as purchaseprice')
                    ->from('product_purchase_details')
                    ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                    ->where('product_purchase_details.product_id', $record->product_id)
                    ->where('product_purchase_details.qty >=', 0)
                    // ->where('product_purchase_details.damaged_qty <=', 0)
                    ->get()->row();

                $stockout_for_weighted_average = $stockout_for_purchase_price->purchaseprice / $stockout_for_purchase_price->totalPurchaseQnty;

                $this->db->where('rqsn.date <=', $from_date);
                if ($outlet_id) {
                    $this->db->where('rqsn.to_id', $outlet_id);
                }
                $stockout_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                    ->from('rqsn_details')
                    ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                    ->where('isaprv', 1)
                    ->where('isrcv', 1)
                    ->where('product_id', $record->product_id)
                    ->get()
                    ->row();

                $this->db->where('rqsn.date <=', $from_date);
                if ($outlet_id) {
                    $this->db->where('rqsn.from_id', $outlet_id);
                }
                $stockin_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                    ->from('rqsn_details')
                    ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                    ->where('rqsn_details.isaprv', 1)
                    ->where('rqsn_details.isrcv', 1)
                    ->where('rqsn_details.is_return', 2)
                    ->where('rqsn_details.product_id', $record->product_id)
                    ->get()
                    ->row();

                $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);


                if ($from_date) {
                    $this->db->where('product_return.date_return >=', $from_date);
                }
                if ($to_date) {
                    $this->db->where('product_return.date_return <=', $to_date);
                }
                if ($outlet_id) {
                    $this->db->where('product_return.outlet_id', $outlet_id);
                }
                $product_return = $this->db->select('sum(ret_qty) as totalReturn')->from('product_return')->where('usablity', 2)->where('product_id', $record->product_id)->get()->row();

                // echo '<pre>';
                // print_r($product_supplier_price[0]);
                // exit();

                $open_stock = $this->db->select('stock_qty')->from('opening_inventory')->where('product_id', $record->product_id)->get()->row();

                $this->db->where('production.date <=', $to_date);
                $production_qty = $this->db->select('SUM(quantity) as pro_qty')
                    ->from('production_goods')
                    ->join('production', 'production.pro_id = production_goods.pro_id', 'left')
                    ->where('product_id', $record->product_id)
                    ->group_by('product_id')
                    ->get()
                    ->row();

                $this->db->where('production.date <=', $to_date);


                $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
                    ->from('item_usage')
                    ->join('production', 'production.pro_id = item_usage.production_id', 'left')
                    ->where('item_id', $record->product_id)
                    ->group_by('item_id')
                    ->get()
                    ->row();

                if ($record->finished_raw == 1) {
                    $closing_total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) - (!empty($stockin_outlet->totaloutletQnty) ? $stockin_outlet->totaloutletQnty : 0);
                } else {

                    $this->db->where('transfer_items.date <=', $to_date);
                    $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                        ->from('transfer_item_details')
                        ->join('transfer_items', 'transfer_items.pro_id=transfer_items.pro_id', 'left')
                        ->where('transfer_item_details.product_id', $record->product_id)
                        ->group_by('transfer_item_details.product_id')
                        ->get()
                        ->row();
                    $closing_total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
                }
                $this->db->where('a.create_date <=', $to_date);
                if ($outlet_id) {
                    $this->db->where('b.outlet_id', $outlet_id);
                }
                $phy_count = $this->db->select('SUM(a.difference) as phy_qty')
                    ->from('stock_taking_details a')
                    ->join('stock_taking b', 'b.stid = a.stid')
                    ->where(array(
                        'a.product_id' => $record->product_id,
                        //                    'create_date >=' => $date,
                        'a.status' => 1,

                    ))
                    ->group_by('a.product_id')
                    ->order_by('a.id', 'desc')
                    ->get()
                    ->row();

                $sprice = (!empty($record->price) ? $record->price : 0);
                // $pprice = (!empty($stockout->purchaseprice) ? sprintf('%0.2f', $stockout->purchaseprice) : 0);
                $pprice = $stockout_for_weighted_average;

                $closing_total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);
                //   $closing_total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0);
                // $closing_stock = $closing_total_in - $closing_total_out;
                $diff = (!empty($phy_count->phy_qty) ? $phy_count->phy_qty : 0);

                // $closing_stock = (($closing_total_in - ($closing_total_out + $product_return->totalReturn)) - $newStock) + $diff;

                $closing_stock = (($closing_total_in - ($closing_total_out)) - $newStock) + $diff;
            } else {
                $closing_stock = $stock;
            }
            // Closing Stock End

            // echo "<pre>";
            // echo $opening_stock, $closing_stock;
            // exit();


            // echo "<pre>";
            // print_r($stockout->damaged_qty);
            // exit();


            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name_bn,
                'product_type'  =>  $record->finished_raw,
                'production_cost'  => $production_price,
                'product_model' => ($record->product_model ? $record->product_model : ''),
                'category' => ($record->name ? $record->name : ''),
                'sku' => ($record->sku ? $record->sku : ''),
                'sales_price'   =>  sprintf('%0.2f', $sprice),
                'purchase_p'    =>  $pprice,
                'totalPurchaseQnty' => $total_in,
                'damagedQnty'   => $stockout->damaged_qty,
                'totalSalesQnty' =>  $total_out,
                'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                'stok_quantity' => sprintf('%0.2f', $closing_stock),
                'opening_stock'     => $opening_stock,
                'total_sale_price' => $closing_stock * $sprice,
                'purchase_total' => (($closing_stock * $pprice) != 0)
                    ? ($closing_stock * $pprice)
                    : ($production_price
                        ? $production_price * $closing_stock
                        : 0),

                'opening_inventory' => (($opening_stock * $pprice) != 0)
                    ? ($opening_stock * $pprice)
                    : ($product_supplier_price
                        ? $production_price * $opening_stock
                        : 0),


            );

            $sl++;

            // $closing_inventory = array_sum(array_column($data, 'purchase_total'));

        }




        $opening_finished = 0;
        $opening_raw = 0;
        $opening_tools = 0;

        $closing_finished = 0;
        $closing_raw = 0;
        $closing_tools = 0;



        foreach ($data as $key => $value) {

            if ($value['product_type'] == 1) {
                $opening_finished += $value['opening_inventory'];
                $closing_finished += $value['purchase_total'];
            }
            if ($value['product_type'] == 2) {
                $opening_raw += $value['opening_inventory'];
                $closing_raw += $value['purchase_total'];
            }
            if ($value['product_type'] == 3) {
                $opening_tools += $value['opening_inventory'];
                $closing_tools += $value['purchase_total'];
            }
        }




        ## Response
        if (!$post_product_id) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,

                "opening_finished" => $opening_finished,
                "opening_raw" => $opening_raw,
                "opening_tools" => $opening_tools,

                "closing_finished" => $closing_finished,
                "closing_raw" => $closing_raw,
                "closing_tools" => $closing_tools,

            );
        } else {
            $response = array(
                "central_stock"     => $stock,
                "pprice"            => $stock * $pprice
            );
        }

        // echo '<pre>';
        // print_r($response);
        // exit();

        return $response;
    }

    public function getCheckListNew($postData = null, $post_product_id = null, $product_sku = null, $pr_status = null, $from_date = null, $to_date = null)
    {
        // echo $from_date;
        // exit();


        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        // $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        // echo $outlet_id;exit();
        $outlet_id = $this->input->post('outlet_id', TRUE);
        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }



        $p_s = $this->input->post('product_status', TRUE);
        $product_sku = $this->input->post('product_sku', TRUE);
        if ($from_date) {
            // $date = date('Y-m-d');
            // $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
            $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        }

        // if ($from_date == null) {
        //     $date = date('Y-m-d');
        //     $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
        // } else {
        //     $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        // }

        // echo "<pre>";
        // print_r($from_date);
        // exit();


        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (a.product_name like '%"
                    . $searchValue .
                    "%' or a.product_model like '%"
                    . $searchValue .
                    "%' or a.sku like '%"
                    . $searchValue .
                    "%' or b.name like '%"
                    . $searchValue .
                    "%'  or a.product_id like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information a');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');

            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }




            if (isset($p_s) && $p_s != '') {
                $this->db->where('a.finished_raw', $p_s);
            }

            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
            $this->db->group_by('a.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;

            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information a');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');
            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }

            //            if ($product_sku != '') {
            //                for ($i = 0, $ien = count($product_sku); $i < $ien; $i++) {
            //                    $this->db->or_where('a.sku',$product_sku[$i]);
            //                }
            //            }


            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('a.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
        }
        ## Fetch records
        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                b.name
             
                ");
        $this->db->from('product_information a');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->group_by('a.product_id');
        $this->db->limit($rowperpage, $start);
        if ($product_sku != '') {
            $this->db->where_in('a.sku', $product_sku);
        }

        //        if ($product_sku != '') {
        //            for ($i = 0, $ien = count($product_sku); $i < $ien; $i++) {
        //                $this->db->or_where('a.sku',$product_sku[$i]);
        //            }
        //        }


        if (!$post_product_id && $searchValue != '')
            $this->db->where($searchQuery);

        if ($post_product_id) {
            $this->db->where('a.product_id', $post_product_id);
        }


        if (isset($p_s) && $p_s != '') {
            $this->db->where('a.finished_raw', $p_s);
        }

        //        if (!$post_product_id) {
        //            if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
        //            $this->db->order_by($columnName, $columnSortOrder);
        //            $this->db->group_by('a.product_id');
        //            $this->db->limit($rowperpage, $start);
        //        }
        $records = $this->db->get()->result();
        $data = array();

        // echo '<pre>';
        // print_r($records);
        // exit();

        $sl = 1;
        $stock = 0;
        $closing_stock = 0;
        $opening_stock = 0;


        // echo "<pre>";
        // print_r($records);
        // exit();





        foreach ($records as $record) {

            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

            $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);

            if ($from_date) {
                $this->db->where('b.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('b.date <=', $to_date);
            }

            if ($outlet_id) {
                $this->db->where('b.outlet_id', $outlet_id);
            }


            $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')
                ->from('invoice_details a')
                ->join('invoice b', 'b.invoice_id = a.invoice_id')
                ->where('a.pre_order', 1)
                ->where('a.product_id', $record->product_id)
                ->get()
                ->row();
            $warrenty_stock = $this->db->select('sum(ret_qty) as totalWarrentyQnty')->from('warrenty_return')->where('product_id', $record->product_id)->get()->row();
            //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();

            if ($from_date) {
                $this->db->where('product_purchase.purchase_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('product_purchase.purchase_date <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }

            $stockout = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as damaged_qty,Avg(product_purchase_details.rate) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where('product_purchase_details.product_id', $record->product_id)->get()->row();



            $stockout_for_purchase_price = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.total_amount) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where('product_purchase_details.product_id', $record->product_id)
                ->where('product_purchase_details.qty >=', 0)
                //->where('product_purchase_details.damaged_qty <=', 0)
                ->get()->row();

            $stockout_for_weighted_average = 0;

            if ($stockout_for_purchase_price->totalPurchaseQnty == 0) {
                $stockout_for_weighted_average = $stockout_for_purchase_price->purchaseprice  / 1;
            } else {
                $stockout_for_weighted_average = ($stockout_for_purchase_price->purchaseprice) / ($stockout_for_purchase_price->totalPurchaseQnty);
            }


            if ($from_date) {
                $this->db->where('product_return.date_return >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('product_return.date_return <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('product_return.outlet_id', $outlet_id);
            }
            $product_return = $this->db->select('sum(ret_qty) as totalReturn')
                ->from('product_return')
                ->where('usablity', 2)
                ->where('product_id', $record->product_id)
                ->get()
                ->row();


            if ($from_date) {
                $this->db->where('rqsn.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('rqsn.date <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            $stockin_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 2)
                ->where('rqsn_details.product_id', $record->product_id)
                ->get()
                ->row();

            if ($from_date) {
                $this->db->where('rqsn.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('rqsn.date <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            $stockout_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('isaprv', 1)
                ->where('isrcv', 1)
                ->where('product_id', $record->product_id)
                ->get()
                ->row();
            $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);


            if ($from_date) {
                $this->db->where('opening_inventory.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('opening_inventory.date <=', $to_date);
            }
            $open_stock = $this->db->select('stock_qty')->from('opening_inventory')->where('product_id', $record->product_id)->get()->row();
            if ($from_date) {
                $this->db->where('production.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('production.date <=', $to_date);
            }
            $production_qty = $this->db->select('SUM(quantity) as pro_qty')
                ->from('production_goods')
                ->join('production', 'production_goods.pro_id=production.pro_id', 'left')
                ->where('production_goods.product_id', $record->product_id)
                ->group_by('production_goods.product_id')
                ->get()
                ->row();

            if ($from_date) {
                $this->db->where('production.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('production.date <=', $to_date);
            }
            $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
                ->from('item_usage')
                ->join('production', 'item_usage.production_id=production.pro_id', 'left')
                ->where('item_usage.item_id', $record->product_id)
                ->group_by('item_usage.item_id')
                ->get()
                ->row();





            $sprice = (!empty($record->price) ? $record->price : 0);
            // $pprice = (!empty($stockout->purchaseprice) ? sprintf('%0.2f', $stockout->purchaseprice) : 0);
            $pprice = $stockout_for_weighted_average;

            $total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);
            $total_out = '';

            if ($record->finished_raw == 1) {
                $total_out = (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) - (!empty($stockin_outlet->totaloutletQnty) ? $stockin_outlet->totaloutletQnty : 0);
            } else {
                if ($from_date) {
                    $this->db->where('transfer_items.date >=', $from_date);
                }
                if ($to_date) {
                    $this->db->where('transfer_items.date <=', $to_date);
                }
                $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                    ->from('transfer_item_details')
                    ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                    ->where('transfer_item_details.product_id', $record->product_id)
                    ->group_by('transfer_item_details.product_id')
                    ->get()
                    ->row();
                $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            }
            if ($from_date) {
                $this->db->where('a.create_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('a.create_date <=', $to_date);
            }
            if ($outlet_id) {
                $this->db->where('b.outlet_id', $outlet_id);
            }
            $phy_count = $this->db->select('SUM(a.difference) as phy_qty')
                ->from('stock_taking_details a')
                ->join('stock_taking b', 'b.stid = a.stid')
                ->where(array(
                    'a.product_id' => $record->product_id,
                    //                    'create_date >=' => $date,
                    'a.status' => 1,

                ))
                ->group_by('a.product_id')
                ->order_by('a.id', 'desc')
                ->get()
                ->row();



            $newStock = (!empty($warrenty_stock->totalWarrentyQnty) ? $warrenty_stock->totalWarrentyQnty : 0);
            $diff = (!empty($phy_count->phy_qty) ? $phy_count->phy_qty : 0);

            // $stock = (($total_in - (($total_out + $product_return->totalReturn) - $newStock))) + $diff;

            $stock = (($total_in - (($total_out) - $newStock))) + $diff;


            $new_from_date = $this->input->post('from_date');
            $new_to_date = $this->input->post('to_date');
            $new_opening_from_date = '';
            $new_opening_to_date = '';
            if ($new_from_date == '') {
                $new_date = date_create("0001-01-01");
                $new_change_date = date_format($new_date, "Y-m-d");
                $new_opening_from_date = date('Y-m-d', strtotime($new_change_date . ' -1 day'));
            } else {
                $new_opening_from_date = date('Y-m-d', strtotime($new_from_date . ' -1 day'));
            }

            if ($new_to_date == '') {
                $date = date('Y-m-d');
                $new_opening_to_date = date('Y-m-d', strtotime($date . ' -1 day'));
            } else {
                $new_opening_to_date = date('Y-m-d', strtotime($new_to_date . ' -1 day'));
            }


            $new_outlet_id = $this->input->post('outlet_id', TRUE);
            if ($new_outlet_id == '') {
                $new_outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
            } elseif ($new_outlet_id === 'All') {
                $new_outlet_id = null;
            } else {
                $new_outlet_id = $this->input->post('outlet_id', TRUE);;
            }

            if ($new_from_date) {
                $opening_stock = $this->stock2(null, $new_opening_from_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
            } else {
                $opening_stock = $this->stock2($new_opening_from_date, $new_opening_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
            }

            if ($new_to_date) {
                $closing_stock = $this->stock2(null, $new_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
            } else {
                $closing_stock = $this->stock2($new_from_date, $new_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
            }


            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name_bn,
                'product_type'  =>  $record->finished_raw,
                'production_cost'  => $production_price,
                'product_model' => ($record->product_model ? $record->product_model : ''),
                'category' => ($record->name ? $record->name : ''),
                'sku' => ($record->sku ? $record->sku : ''),
                'sales_price'   =>  sprintf('%0.2f', $sprice),
                'purchase_p'    =>  $pprice,
                'totalPurchaseQnty' => $total_in,
                'damagedQnty'   => $stockout->damaged_qty ? $stockout->damaged_qty : 0,
                'totalSalesQnty' =>  $total_out,
                'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                'stok_quantity' => sprintf('%0.2f', $closing_stock),
                'opening_stock'     => $opening_stock,
                'total_sale_price' => $closing_stock * $sprice,
                'purchase_total' => (($closing_stock * $pprice) != 0)
                    ? ($closing_stock * $pprice)
                    : ($production_price
                        ? $production_price * $closing_stock
                        : 0),

                'opening_inventory' => (($opening_stock * $pprice) != 0)
                    ? ($opening_stock * $pprice)
                    : ($product_supplier_price
                        ? $production_price * $opening_stock
                        : 0),


            );

            // $data[] = array(
            //     'sl'            =>   $sl,
            //     'product_name'  =>  $record->product_name_bn,
            //     'product_type'  =>  $record->finished_raw,
            //     'production_cost'  => $production_price,
            //     'product_model' => ($record->product_model ? $record->product_model : ''),
            //     'category' => ($record->name ? $record->name : ''),
            //     'sku' => ($record->sku ? $record->sku : ''),
            //     'sales_price'   =>  sprintf('%0.2f', $sprice),
            //     'purchase_p'    => 0,
            //     'totalPurchaseQnty' =>
            //     $total_in,
            //     'damagedQnty'   =>
            //     $stockout->damaged_qty,
            //     'totalSalesQnty' =>  0,
            //     'warrenty_stock' =>  0,
            //     //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
            //     'stok_quantity' => 0,
            //     'opening_stock'     => 0,
            //     'total_sale_price' => 0,
            //     'purchase_total' => 0,

            //     'opening_inventory' => 0,


            // );
            $sl++;

            // $closing_inventory = array_sum(array_column($data, 'purchase_total'));

        }




        $opening_finished = 0;
        $opening_raw = 0;
        $opening_tools = 0;

        $closing_finished = 0;
        $closing_raw = 0;
        $closing_tools = 0;



        foreach ($data as $key => $value) {

            if ($value['product_type'] == 1) {
                $opening_finished += $value['opening_inventory'];
                $closing_finished += $value['purchase_total'];
            }
            if ($value['product_type'] == 2) {
                $opening_raw += $value['opening_inventory'];
                $closing_raw += $value['purchase_total'];
            }
            if ($value['product_type'] == 3) {
                $opening_tools += $value['opening_inventory'];
                $closing_tools += $value['purchase_total'];
            }
        }




        ## Response
        if (!$post_product_id) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,

                "opening_finished" => $opening_finished,
                "opening_raw" => $opening_raw,
                "opening_tools" => $opening_tools,

                "closing_finished" => $closing_finished,
                "closing_raw" => $closing_raw,
                "closing_tools" => $closing_tools,

            );
        } else {
            $response = array(
                "central_stock"     => $stock,
                "pprice"            => $stock * $pprice
            );
        }

        // echo '<pre>';
        // print_r($response);
        // exit();

        return $response;
    }

    public function getCheckListNew2($postData = null, $post_product_id = null, $product_sku = null, $pr_status = null, $from_date = null, $to_date = null, $value = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();

        $type = 1;

        // $outlet_id = $this->input->post('outlet_id', TRUE);
        // if ($outlet_id == '') {
        //     $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        // } elseif ($outlet_id === 'All') {
        //     $outlet_id = null;
        //     $type = 0;
        // } else {
        //     $outlet_id = $this->input->post('outlet_id', TRUE);
        // }

        if ($this->input->post('outlet_id')) {
            $outlet_id = $this->input->post('outlet_id');
            if ($outlet_id === 'All') {
                $outlet_id = null;
            } else {
                $outlet_id = $this->input->post('outlet_id');
            }
        } else {
            $outlet_id = $this->session->userdata('outlet_id');
        }


        $p_s = $this->input->post('product_status', TRUE);
        $product_sku = $this->input->post('product_sku', TRUE);
        $cat_id = $this->input->post('cat_id', TRUE);

        // echo "<pre>";
        // print_r($cat_id);
        // exit();

        // $op_date = '';
        // if ($from_date) {
        //     $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        // } else {
        //     $date = date('Y-m-d');
        //     $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
        // }

        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (a.product_name like '%"
                    . $searchValue .
                    "%' or a.product_model like '%"
                    . $searchValue .
                    "%' or a.sku like '%"
                    . $searchValue .
                    "%' or b.name like '%"
                    . $searchValue .
                    "%'  or a.product_id like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information a');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');

            if (isset($cat_id) && $cat_id != '') {
                $this->db->like('a.category_id', $cat_id, 'both');
            }
            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if (isset($p_s) && $p_s != '') {
                $this->db->where('a.finished_raw', $p_s);
            }

            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
            $this->db->group_by('a.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;

            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information a');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');
            if (isset($cat_id) && $cat_id != '') {
                $this->db->like('a.category_id', $cat_id, 'both');
            }
            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('a.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
        }
        ## Fetch records
        $this->db->select("a.*, a.product_name, a.product_id, a.product_model, b.name");
        $this->db->from('product_information a');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->group_by('a.product_id');
        $this->db->limit($rowperpage, $start);
        if (isset($cat_id) && $cat_id != '') {
            $this->db->like('a.category_id', $cat_id, 'both');
        }
        if ($product_sku != '') {
            $this->db->where_in('a.sku', $product_sku);
        }

        if (!$post_product_id && $searchValue != '')
            $this->db->where($searchQuery);

        if ($post_product_id) {
            $this->db->where('a.product_id', $post_product_id);
        }
        if (isset($p_s) && $p_s != '') {
            $this->db->where('a.finished_raw', $p_s);
        }
        $records = $this->db->get()->result();
        $data = array();

        $sl = 1;
        $stock = 0;
        $closing_stock = 0;
        $opening_stock = 0;


        foreach ($records as $record) {

            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

            $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);


            //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();

            $stockout_for_purchase_price = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.total_amount) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where('product_purchase_details.product_id', $record->product_id)
                ->where('product_purchase_details.qty >=', 0)
                //->where('product_purchase_details.damaged_qty <=', 0)
                ->get()
                ->row();

            $stockout_for_weighted_average = 0;

            if ($stockout_for_purchase_price->totalPurchaseQnty == 0) {
                $stockout_for_weighted_average = $stockout_for_purchase_price->purchaseprice  / 1;
            } else {
                $stockout_for_weighted_average = ($stockout_for_purchase_price->purchaseprice) / ($stockout_for_purchase_price->totalPurchaseQnty);
            }


            $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);


            $sprice = (!empty($record->price) ? $record->price : 0);

            $pprice = $stockout_for_weighted_average;


            $total_in = $this->total_in($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $total_out = $this->total_out($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $total_sale = $this->total_sale($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $total_transfer = $this->total_transfer($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $total_return_given = $this->total_return_given($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $total_damage = $this->total_damage($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $stock = $total_in - $total_sale - $total_transfer - $total_return_given - $total_damage;

            // $stock = $total_in - $total_out - $total_return_given - $total_damage;

            // $closing_stock = $stock;


            $new_from_date = $this->input->post('from_date');
            $new_to_date = $this->input->post('to_date');
            $new_opening_from_date = '';
            $new_opening_to_date = '';
            if ($new_from_date == '') {
                $new_date = date_create("0001-01-01");
                $new_change_date = date_format($new_date, "Y-m-d");
                $new_opening_from_date = date('Y-m-d', strtotime($new_change_date . ' -1 day'));
            } else {
                $new_opening_from_date = date('Y-m-d', strtotime($new_from_date . ' -1 day'));
            }

            if ($new_to_date == '') {
                $date = date('Y-m-d');
                $new_opening_to_date = date('Y-m-d', strtotime($date . ' -1 day'));
            } else {
                $new_opening_to_date = date('Y-m-d', strtotime($new_to_date . ' -1 day'));
            }


            $new_outlet_id = $this->input->post('outlet_id', TRUE);
            if ($new_outlet_id == '') {
                $new_outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
            } elseif ($new_outlet_id === 'All') {
                $new_outlet_id = null;
            } else {
                $new_outlet_id = $this->input->post('outlet_id', TRUE);;
            }

            if ($new_from_date) {
                $opening_stock = $this->stock2(null, $new_opening_from_date, $new_outlet_id, $record->product_id, $record->finished_raw, null, $type);
            } else {
                $opening_stock = $this->stock2($new_opening_from_date, $new_opening_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null, $type);
            }

            if ($new_to_date) {
                $closing_stock = $this->stock2(null, $new_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null, $type);
                // $closing_stock = $opening_stock + $stock;
            } else {
                // $closing_stock = $this->stock2($new_from_date, $new_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
                $closing_stock = $stock;
            }

            if ($value == 1) {
                if ($closing_stock > 0) {
                    $data[] = array(
                        'sl'            =>   $sl,
                        'product_name'  =>  $record->product_name,
                        'product_type'  =>  $record->finished_raw,
                        'production_cost'  => $production_price,
                        'product_model' => ($record->product_model ? $record->product_model : ''),
                        'category' => ($record->name ? $record->name : ''),
                        'sku' => ($record->sku ? $record->sku : ''),
                        'sales_price'   =>  sprintf('%0.2f', $sprice),
                        'purchase_p'    =>  $pprice,
                        'totalPurchaseQnty' => $total_in,
                        'damagedQnty'   => $total_damage,
                        'returnQnty' => $total_return_given,
                        'totalSalesQnty' =>  $total_out,
                        'total_sale' =>  $total_sale,
                        'total_transfer' =>  $total_transfer,
                        'sold_value' => $total_sale * $pprice,
                        // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                        //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                        'stok_quantity' => sprintf('%0.2f', $closing_stock),
                        'opening_stock'     => $opening_stock,
                        'total_sale_price' => $closing_stock * $sprice,
                        'purchase_total' => (($closing_stock * $pprice) != 0)
                            ? ($closing_stock * $pprice)
                            : ($production_price
                                ? $production_price * $closing_stock
                                : 0),

                        'opening_inventory' => (($opening_stock * $pprice) != 0)
                            ? ($opening_stock * $pprice)
                            : ($product_supplier_price
                                ? $production_price * $opening_stock
                                : 0),
                    );
                    $sl++;
                }
            }
            // if ($value == 3) {
            //     if ($closing_stock > 0 || $total_damage > 0 || $total_return_given > 0 || $total_sale > 0 || $total_transfer > 0 || $total_in > 0) {
            //         $data[] = array(
            //             'sl'            =>   $sl,
            //             'product_name'  =>  $record->product_name_bn,
            //             'product_type'  =>  $record->finished_raw,
            //             'production_cost'  => $production_price,
            //             'product_model' => ($record->product_model ? $record->product_model : ''),
            //             'category' => ($record->name ? $record->name : ''),
            //             'sku' => ($record->sku ? $record->sku : ''),
            //             'sales_price'   =>  sprintf('%0.2f', $sprice),
            //             'purchase_p'    =>  $pprice,
            //             'totalPurchaseQnty' => $total_in,
            //             'damagedQnty'   => $total_damage,
            //             'returnQnty' => $total_return_given,
            //             'totalSalesQnty' =>  $total_out,
            //             'total_sale' =>  $total_sale,
            //             'total_transfer' =>  $total_transfer,
            //             'sold_value' => $total_sale * $pprice,
            //             // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
            //             //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
            //             'stok_quantity' => sprintf('%0.2f', $closing_stock),
            //             'opening_stock'     => $opening_stock,
            //             'total_sale_price' => $closing_stock * $sprice,
            //             'purchase_total' => (($closing_stock * $pprice) != 0)
            //                 ? ($closing_stock * $pprice)
            //                 : ($production_price
            //                     ? $production_price * $closing_stock
            //                     : 0),

            //             'opening_inventory' => (($opening_stock * $pprice) != 0)
            //                 ? ($opening_stock * $pprice)
            //                 : ($product_supplier_price
            //                     ? $production_price * $opening_stock
            //                     : 0),
            //         );
            //         $sl++;
            //     }
            // }
            if ($value == 4) {

                if (($closing_stock > 0 || $total_damage > 0 || $total_return_given > 0 || $total_sale > 0 || $total_transfer > 0 || $total_in > 0) && $closing_stock > 0) {
                    // if (($closing_stock > 0 && $total_damage > 0 && $total_return_given > 0 && $total_sale > 0 && $total_transfer > 0 && $total_in > 0)) {
                    $data[] = array(
                        'sl'            =>   $sl,
                        'product_name'  =>  $record->product_name,
                        'product_type'  =>  $record->finished_raw,
                        'production_cost'  => $production_price,
                        'product_model' => ($record->product_model ? $record->product_model : ''),
                        'category' => ($record->name ? $record->name : ''),
                        'sku' => ($record->sku ? $record->sku : ''),
                        'sales_price'   =>  sprintf('%0.2f', $sprice),
                        'purchase_p'    =>  $pprice,
                        'totalPurchaseQnty' => $total_in,
                        'damagedQnty'   => $total_damage,
                        'returnQnty' => $total_return_given,
                        'totalSalesQnty' =>  $total_out,
                        'total_sale' =>  $total_sale,
                        'total_transfer' =>  $total_transfer,
                        'sold_value' => $total_sale * $pprice,
                        // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                        //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                        'stok_quantity' => sprintf('%0.2f', $closing_stock),
                        'opening_stock'     => $opening_stock,
                        'total_sale_price' => $closing_stock * $sprice,
                        'purchase_total' => (($closing_stock * $pprice) != 0)
                            ? ($closing_stock * $pprice)
                            : ($production_price
                                ? $production_price * $closing_stock
                                : 0),

                        'opening_inventory' => (($opening_stock * $pprice) != 0)
                            ? ($opening_stock * $pprice)
                            : ($product_supplier_price
                                ? $production_price * $opening_stock
                                : 0),
                    );
                    $sl++;
                }
            }
            if ($value == 5) {
                // if (($closing_stock > 0 || $total_damage > 0 || $total_return_given > 0 || $total_sale > 0 || $total_transfer > 0 || $total_in > 0) && $closing_stock <= 0) {

                if (($closing_stock < 1 && $total_damage < 1 && $total_return_given < 1 && $total_sale < 1 && $total_transfer < 1 && $total_in < 1)) {
                    $data[] = array(
                        'sl'            =>   $sl,
                        'product_name'  =>  $record->product_name,
                        'product_type'  =>  $record->finished_raw,
                        'production_cost'  => $production_price,
                        'product_model' => ($record->product_model ? $record->product_model : ''),
                        'category' => ($record->name ? $record->name : ''),
                        'sku' => ($record->sku ? $record->sku : ''),
                        'sales_price'   =>  sprintf('%0.2f', $sprice),
                        'purchase_p'    =>  $pprice,
                        'totalPurchaseQnty' => $total_in,
                        'damagedQnty'   => $total_damage,
                        'returnQnty' => $total_return_given,
                        'totalSalesQnty' =>  $total_out,
                        'total_sale' =>  $total_sale,
                        'total_transfer' =>  $total_transfer,
                        'sold_value' => $total_sale * $pprice,
                        // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                        //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                        'stok_quantity' => sprintf('%0.2f', $closing_stock),
                        'opening_stock'     => $opening_stock,
                        'total_sale_price' => $closing_stock * $sprice,
                        'purchase_total' => (($closing_stock * $pprice) != 0)
                            ? ($closing_stock * $pprice)
                            : ($production_price
                                ? $production_price * $closing_stock
                                : 0),

                        'opening_inventory' => (($opening_stock * $pprice) != 0)
                            ? ($opening_stock * $pprice)
                            : ($product_supplier_price
                                ? $production_price * $opening_stock
                                : 0),
                    );
                    $sl++;
                }
            }
            if ($value == 0) {
                if ($closing_stock < 1) {
                    $data[] = array(
                        'sl'            =>   $sl,
                        'product_name'  =>  $record->product_name,
                        'product_type'  =>  $record->finished_raw,
                        'production_cost'  => $production_price,
                        'product_model' => ($record->product_model ? $record->product_model : ''),
                        'category' => ($record->name ? $record->name : ''),
                        'sku' => ($record->sku ? $record->sku : ''),
                        'sales_price'   =>  sprintf('%0.2f', $sprice),
                        'purchase_p'    =>  $pprice,
                        'totalPurchaseQnty' => $total_in,
                        'damagedQnty'   => $total_damage,
                        'returnQnty' => $total_return_given,
                        'totalSalesQnty' =>  $total_out,
                        'total_sale' =>  $total_sale,
                        'total_transfer' =>  $total_transfer,
                        'sold_value' => $total_sale * $pprice,
                        // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                        //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                        'stok_quantity' => sprintf('%0.2f', $closing_stock),
                        'opening_stock'     => $opening_stock,
                        'total_sale_price' => $closing_stock * $sprice,
                        'purchase_total' => (($closing_stock * $pprice) != 0)
                            ? ($closing_stock * $pprice)
                            : ($production_price
                                ? $production_price * $closing_stock
                                : 0),

                        'opening_inventory' => (($opening_stock * $pprice) != 0)
                            ? ($opening_stock * $pprice)
                            : ($product_supplier_price
                                ? $production_price * $opening_stock
                                : 0),
                    );
                    $sl++;
                }
            }
            if ($value == 6) {
                if ($closing_stock < 5) {
                    $data[] = array(
                        'sl'            =>   $sl,
                        'product_name'  =>  $record->product_name,
                        'product_type'  =>  $record->finished_raw,
                        'production_cost'  => $production_price,
                        'product_model' => ($record->product_model ? $record->product_model : ''),
                        'category' => ($record->name ? $record->name : ''),
                        'sku' => ($record->sku ? $record->sku : ''),
                        'sales_price'   =>  sprintf('%0.2f', $sprice),
                        'purchase_p'    =>  $pprice,
                        'totalPurchaseQnty' => $total_in,
                        'damagedQnty'   => $total_damage,
                        'returnQnty' => $total_return_given,
                        'totalSalesQnty' =>  $total_out,
                        'total_sale' =>  $total_sale,
                        'total_transfer' =>  $total_transfer,
                        'sold_value' => $total_sale * $pprice,
                        // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                        //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                        'stok_quantity' => sprintf('%0.2f', $closing_stock),
                        'opening_stock'     => $opening_stock,
                        'total_sale_price' => $closing_stock * $sprice,
                        'purchase_total' => (($closing_stock * $pprice) != 0)
                            ? ($closing_stock * $pprice)
                            : ($production_price
                                ? $production_price * $closing_stock
                                : 0),

                        'opening_inventory' => (($opening_stock * $pprice) != 0)
                            ? ($opening_stock * $pprice)
                            : ($product_supplier_price
                                ? $production_price * $opening_stock
                                : 0),
                    );
                    $sl++;
                }
            }
            if ($value == 2 || $value == 3) {
                $data[] = array(
                    'sl'            =>   $sl,
                    'product_name'  =>  $record->product_name,
                    'product_type'  =>  $record->finished_raw,
                    'production_cost'  => $production_price,
                    'product_model' => ($record->product_model ? $record->product_model : ''),
                    'category' => ($record->name ? $record->name : ''),
                    'sku' => ($record->sku ? $record->sku : ''),
                    'sales_price'   =>  sprintf('%0.2f', $sprice),
                    'purchase_p'    =>  $pprice,
                    'totalPurchaseQnty' => $total_in,
                    'damagedQnty'   => $total_damage,
                    'returnQnty' => $total_return_given,
                    'totalSalesQnty' =>  $total_out,
                    'total_sale' =>  $total_sale,
                    'total_transfer' =>  $total_transfer,
                    'sold_value' => $total_sale * $pprice,

                    // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                    //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                    'stok_quantity' => sprintf('%0.2f', $closing_stock),
                    'opening_stock'     => $opening_stock,
                    'total_sale_price' => $closing_stock * $sprice,
                    'purchase_total' => (($closing_stock * $pprice) != 0)
                        ? ($closing_stock * $pprice)
                        : ($production_price
                            ? $production_price * $closing_stock
                            : 0),

                    'opening_inventory' => (($opening_stock * $pprice) != 0)
                        ? ($opening_stock * $pprice)
                        : ($product_supplier_price
                            ? $production_price * $opening_stock
                            : 0),
                );
                $sl++;
            }
        }

        $opening_finished = 0;
        $opening_raw = 0;
        $opening_tools = 0;

        $closing_finished = 0;
        $closing_raw = 0;
        $closing_tools = 0;

        foreach ($data as $key => $value) {

            if ($value['product_type'] == 1) {
                $opening_finished += $value['opening_inventory'];
                $closing_finished += $value['purchase_total'];
            }
            if ($value['product_type'] == 2) {
                $opening_raw += $value['opening_inventory'];
                $closing_raw += $value['purchase_total'];
            }
            if ($value['product_type'] == 3) {
                $opening_tools += $value['opening_inventory'];
                $closing_tools += $value['purchase_total'];
            }
        }

        ## Response
        if (!$post_product_id) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,

                "opening_finished" => $opening_finished,
                "opening_raw" => $opening_raw,
                "opening_tools" => $opening_tools,

                "closing_finished" => $closing_finished,
                "closing_raw" => $closing_raw,
                "closing_tools" => $closing_tools,

            );
        } else {
            $response = array(
                "central_stock"     => $stock,
                "pprice"            => $stock * $pprice
            );
        }

        // echo '<pre>';
        // print_r($response);
        // exit();

        return $response;
    }

    public function available_getCheckListNew2($postData = null, $post_product_id = null, $product_sku = null, $pr_status = null, $from_date = null, $to_date = null, $outlet_id = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();

        $type = 1;

        // $outlet_id = $this->input->post('outlet_id', TRUE);
        // if ($outlet_id == '') {
        //     $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        // } elseif ($outlet_id === 'All') {
        //     $outlet_id = null;
        //     $type = 0;
        // } else {
        //     $outlet_id = $this->input->post('outlet_id', TRUE);;
        // }


        $p_s = $this->input->post('product_status', TRUE);
        $product_sku = $this->input->post('product_sku', TRUE);
        // $op_date = '';
        // if ($from_date) {
        //     $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        // } else {
        //     $date = date('Y-m-d');
        //     $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
        // }

        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (a.product_name like '%"
                    . $searchValue .
                    "%' or a.product_model like '%"
                    . $searchValue .
                    "%' or a.sku like '%"
                    . $searchValue .
                    "%' or b.name like '%"
                    . $searchValue .
                    "%'  or a.product_id like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information a');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');

            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if (isset($p_s) && $p_s != '') {
                $this->db->where('a.finished_raw', $p_s);
            }

            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
            $this->db->group_by('a.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;

            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information a');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');
            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('a.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
        }
        ## Fetch records
        $this->db->select("a.*, a.product_name, a.product_id, a.product_model, b.name");
        $this->db->from('product_information a');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->group_by('a.product_id');
        $this->db->limit($rowperpage, $start);
        if ($product_sku != '') {
            $this->db->where_in('a.sku', $product_sku);
        }

        if (!$post_product_id && $searchValue != '')
            $this->db->where($searchQuery);

        if ($post_product_id) {
            $this->db->where('a.product_id', $post_product_id);
        }
        if (isset($p_s) && $p_s != '') {
            $this->db->where('a.finished_raw', $p_s);
        }
        $records = $this->db->get()->result();
        $data = array();

        $sl = 1;
        $stock = 0;
        $closing_stock = 0;
        $opening_stock = 0;


        foreach ($records as $record) {

            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

            $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);


            //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();

            $stockout_for_purchase_price = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.total_amount) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where('product_purchase_details.product_id', $record->product_id)
                ->where('product_purchase_details.qty >=', 0)
                //->where('product_purchase_details.damaged_qty <=', 0)
                ->get()
                ->row();

            $stockout_for_weighted_average = 0;

            if ($stockout_for_purchase_price->totalPurchaseQnty == 0) {
                $stockout_for_weighted_average = $stockout_for_purchase_price->purchaseprice  / 1;
            } else {
                $stockout_for_weighted_average = ($stockout_for_purchase_price->purchaseprice) / ($stockout_for_purchase_price->totalPurchaseQnty);
            }


            $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);


            $sprice = (!empty($record->price) ? $record->price : 0);

            $pprice = $stockout_for_weighted_average;


            $total_in = $this->total_in($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $total_out = $this->total_out($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $total_return_given = $this->total_return_given($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $total_damage = $this->total_damage($from_date, $to_date, $outlet_id, $record->product_id, $record->finished_raw, null, $type);

            $stock = $total_in - $total_out - $total_return_given - $total_damage;



            $new_from_date = $this->input->post('from_date');
            $new_to_date = $this->input->post('to_date');
            $new_opening_from_date = '';
            $new_opening_to_date = '';
            if ($new_from_date == '') {
                $new_date = date_create("0001-01-01");
                $new_change_date = date_format($new_date, "Y-m-d");
                $new_opening_from_date = date('Y-m-d', strtotime($new_change_date . ' -1 day'));
            } else {
                $new_opening_from_date = date('Y-m-d', strtotime($new_from_date . ' -1 day'));
            }

            if ($new_to_date == '') {
                $date = date('Y-m-d');
                $new_opening_to_date = date('Y-m-d', strtotime($date . ' -1 day'));
            } else {
                $new_opening_to_date = date('Y-m-d', strtotime($new_to_date . ' -1 day'));
            }


            $new_outlet_id = $this->input->post('outlet_id', TRUE);
            if ($new_outlet_id == '') {
                $new_outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
            } elseif ($new_outlet_id === 'All') {
                $new_outlet_id = null;
            } else {
                $new_outlet_id = $this->input->post('outlet_id', TRUE);;
            }

            if ($new_from_date) {
                $opening_stock = $this->stock2(null, $new_opening_from_date, $new_outlet_id, $record->product_id, $record->finished_raw, null, $type);
            } else {
                $opening_stock = $this->stock2($new_opening_from_date, $new_opening_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null, $type);
            }

            if ($new_to_date) {
                $closing_stock = $this->stock2(null, $new_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null, $type);
                // $closing_stock = $opening_stock + $stock;
            } else {
                // $closing_stock = $this->stock2($new_from_date, $new_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
                $closing_stock = $stock;
            }



            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name_bn,
                'product_type'  =>  $record->finished_raw,
                'production_cost'  => $production_price,
                'product_model' => ($record->product_model ? $record->product_model : ''),
                'category' => ($record->name ? $record->name : ''),
                'sku' => ($record->sku ? $record->sku : ''),
                'sales_price'   =>  sprintf('%0.2f', $sprice),
                'purchase_p'    =>  $pprice,
                'totalPurchaseQnty' => $total_in,
                'damagedQnty'   => $total_damage,
                'returnQnty' => $total_return_given,
                'totalSalesQnty' =>  $total_out,
                // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                'stok_quantity' => sprintf('%0.2f', $closing_stock),
                'opening_stock'     => $opening_stock,
                'total_sale_price' => $closing_stock * $sprice,
                'purchase_total' => (($closing_stock * $pprice) != 0)
                    ? ($closing_stock * $pprice)
                    : ($production_price
                        ? $production_price * $closing_stock
                        : 0),

                'opening_inventory' => (($opening_stock * $pprice) != 0)
                    ? ($opening_stock * $pprice)
                    : ($product_supplier_price
                        ? $production_price * $opening_stock
                        : 0),
            );


            $sl++;
        }

        $opening_finished = 0;
        $opening_raw = 0;
        $opening_tools = 0;

        $closing_finished = 0;
        $closing_raw = 0;
        $closing_tools = 0;

        foreach ($data as $key => $value) {

            if ($value['product_type'] == 1) {
                $opening_finished += $value['opening_inventory'];
                $closing_finished += $value['purchase_total'];
            }
            if ($value['product_type'] == 2) {
                $opening_raw += $value['opening_inventory'];
                $closing_raw += $value['purchase_total'];
            }
            if ($value['product_type'] == 3) {
                $opening_tools += $value['opening_inventory'];
                $closing_tools += $value['purchase_total'];
            }
        }

        ## Response
        if (!$post_product_id) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,

                "opening_finished" => $opening_finished,
                "opening_raw" => $opening_raw,
                "opening_tools" => $opening_tools,

                "closing_finished" => $closing_finished,
                "closing_raw" => $closing_raw,
                "closing_tools" => $closing_tools,

            );
        } else {
            $response = array(
                "central_stock"     => $stock,
                "pprice"            => $stock * $pprice
            );
        }

        // echo '<pre>';
        // print_r($response);
        // exit();

        return $response;
    }

    public function opening_stock($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null)
    {
        $total_in = $this->total_in($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id);

        $total_out = $this->total_out($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id);

        $total_return_given = $this->total_return_given($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id);

        $total_damage = $this->total_damage($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id);

        $stock = $total_in - $total_return_given - $total_out;

        return $stock;
    }

    public function total_in($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {

        $purchase_qnty = $this->total_purchase($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        $rqsn_transfer_taken = $this->rqsn_transfer_taken($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        $total_stock_tack_in =
            $this->total_stock_tack_in($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        // $rqsn_return_taken = $this->rqsn_return_taken($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id);

        if ($type == 1) {
            // $total_in = (!empty($purchase_qnty->totalPurchaseQnty) ? $purchase_qnty->totalPurchaseQnty : 0) +
            //     (!empty($rqsn_transfer_taken->total_rqsn_transfer) ? $rqsn_transfer_taken->total_rqsn_transfer : 0);


            $total_in = (!empty($purchase_qnty->totalPurchaseQnty) ? $purchase_qnty->totalPurchaseQnty : 0) + (!empty($rqsn_transfer_taken->total_rqsn_transfer) ? $rqsn_transfer_taken->total_rqsn_transfer : 0) + (!empty($total_stock_tack_in->phy_qty) ? $total_stock_tack_in->phy_qty : 0);
        } else {
            // $total_in = (!empty($purchase_qnty->totalPurchaseQnty) ? $purchase_qnty->totalPurchaseQnty : 0);

            $total_in = (!empty($purchase_qnty->totalPurchaseQnty) ? $purchase_qnty->totalPurchaseQnty : 0) + (!empty($total_stock_tack_in->phy_qty) ? $total_stock_tack_in->phy_qty : 0);
        }


        return $total_in;
    }

    public function total_out($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {

        if ($from_date) {
            $this->db->where('b.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('b.date <=', $to_date);
        }

        if ($outlet_id) {
            $this->db->where('b.outlet_id', $outlet_id);
        }
        $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')
            ->from('invoice_details a')
            ->join('invoice b', 'b.invoice_id = a.invoice_id')
            ->where('a.pre_order', 1)
            ->where('a.product_id', $product_id)
            ->get()
            ->row();


        if ($from_date) {
            $this->db->where('production.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('production.date <=', $to_date);
        }

        $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
            ->from('item_usage')
            ->join('production', 'item_usage.production_id=production.pro_id', 'left')
            ->where('item_usage.item_id', $product_id)
            ->group_by('item_usage.item_id')
            ->get()
            ->row();


        $rqsn_transfer_given = $this->rqsn_transfer_given($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        $rqsn_return_taken = $this->rqsn_return_taken($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);


        $total_out = '';

        if ($finished_raw == 1) {

            if ($type == 1) {
                $total_out = (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) +
                    (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) +
                    (!empty($rqsn_transfer_given->total_rqsn_transfer) ? $rqsn_transfer_given->total_rqsn_transfer : 0) -
                    (!empty($rqsn_return_taken->total_rqsn_return) ? $rqsn_return_taken->total_rqsn_return : 0);
            } else {
                $total_out = (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) +
                    (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0);
            }
        } else {

            if ($from_date) {
                $this->db->where('transfer_items.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('transfer_items.date <=', $to_date);
            }
            $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                ->from('transfer_item_details')
                ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                ->where('transfer_item_details.product_id', $product_id)
                ->group_by('transfer_item_details.product_id')
                ->get()
                ->row();
            $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
        }

        return $total_out;
    }

    public function total_sale($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {

        if ($from_date) {
            $this->db->where('b.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('b.date <=', $to_date);
        }

        if ($outlet_id) {
            $this->db->where('b.outlet_id', $outlet_id);
        }
        $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')
            ->from('invoice_details a')
            ->join('invoice b', 'b.invoice_id = a.invoice_id')
            ->where('a.pre_order', 1)
            ->where('a.product_id', $product_id)
            ->get()
            ->row();


        if ($from_date) {
            $this->db->where('production.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('production.date <=', $to_date);
        }

        $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
            ->from('item_usage')
            ->join('production', 'item_usage.production_id=production.pro_id', 'left')
            ->where('item_usage.item_id', $product_id)
            ->group_by('item_usage.item_id')
            ->get()
            ->row();


        $total_sale = '';

        if ($finished_raw == 1) {

            if ($type == 1) {
                $total_sale = (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) +
                    (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0);
            } else {
                $total_sale = (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) +
                    (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0);
            }
        }

        return $total_sale;
    }

    public function total_transfer($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {


        $rqsn_transfer_given = $this->rqsn_transfer_given($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        $rqsn_return_taken = $this->rqsn_return_taken($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);


        $total_transfer = '';

        if ($finished_raw == 1) {

            if ($type == 1) {
                $total_transfer = (!empty($rqsn_transfer_given->total_rqsn_transfer) ? $rqsn_transfer_given->total_rqsn_transfer : 0) -
                    (!empty($rqsn_return_taken->total_rqsn_return) ? $rqsn_return_taken->total_rqsn_return : 0);
            }
        } else {

            if ($from_date) {
                $this->db->where('transfer_items.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('transfer_items.date <=', $to_date);
            }
            $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                ->from('transfer_item_details')
                ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                ->where('transfer_item_details.product_id', $product_id)
                ->group_by('transfer_item_details.product_id')
                ->get()
                ->row();
            $total_transfer = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
        }

        return $total_transfer;
    }

    public function total_return_given($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {
        $purchase_return = $this->purchase_return($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        $rqsn_return_given = $this->rqsn_return_given($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        if ($type == 1) {
            $total_return = (!empty($purchase_return->totalReturn) ? $purchase_return->totalReturn : 0) + (!empty($rqsn_return_given->total_rqsn_return) ? $rqsn_return_given->total_rqsn_return : 0);
        } else {
            $total_return = (!empty($purchase_return->totalReturn) ? $purchase_return->totalReturn : 0);
        }


        return $total_return;
    }

    public function total_damage($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {
        $total_purchase = $this->total_purchase($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        $total_wastage = $this->total_wastage($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);


        $total_damage = (!empty($total_purchase->totalDamagedQnty) ? $total_purchase->totalDamagedQnty : 0) + (!empty($total_wastage->total_wastage_qnty) ? $total_wastage->total_wastage_qnty : 0);

        // $total_damage = (!empty($total_purchase->totalDamagedQnty) ? $total_purchase->totalDamagedQnty : 0);

        return $total_damage;
    }

    public function total_purchase($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {
        if ($from_date) {
            $this->db->where('product_purchase.purchase_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('product_purchase.purchase_date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('product_purchase.outlet_id', $outlet_id);
        }
        if ($purchase_id) {
            $this->db->where('product_purchase_details.purchase_id', $purchase_id);
        }
        $total_purchase = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as totalDamagedQnty, sum(product_purchase_details.total_amount) as totalAmount')
            ->from('product_purchase_details')
            ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
            ->where('product_purchase_details.product_id', $product_id)
            ->where('product_purchase_details.qty >', 0)
            ->get()
            ->row();


        // $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0);

        return $total_purchase;
    }

    public function total_stock_tack_in($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {

        if ($from_date) {
            $this->db->where('a.create_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('a.create_date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('b.outlet_id', $outlet_id);
        }
        $total_phy_count = $this->db->select('SUM(a.difference) as phy_qty')
            ->from('stock_taking_details a')
            ->join('stock_taking b', 'b.stid = a.stid')
            ->where(array(
                'a.product_id' => $product_id,
                'a.status' => 1,

            ))
            ->group_by('a.product_id')
            ->order_by('a.id', 'desc')
            ->get()
            ->row();

        // $total_phy_count = (!empty($phy_count->phy_qty) ? $phy_count->phy_qty : 0);

        return $total_phy_count;
    }

    public function total_wastage($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {
        if ($from_date) {
            $this->db->where('wastage_dec.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('wastage_dec.date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('wastage_dec.outlet_id', $outlet_id);
        }
        // if ($purchase_id) {
        //     $this->db->where('wastage_dec.purchase_id', $purchase_id);
        // }
        $total_wastage_qnty = $this->db->select('sum(wastage_dec.wastage_quantity) as total_wastage_qnty')
            ->from('wastage_dec')
            ->where('wastage_dec.product_id', $product_id)
            ->get()
            ->row();

        // echo "<pre>";
        // print_r($total_wastage_qnty);
        // exit();

        return $total_wastage_qnty;
    }

    public function purchase_return($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {

        if ($from_date) {
            $this->db->where('product_return.date_return >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('product_return.date_return <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('product_return.outlet_id', $outlet_id);
        }
        $product_return = $this->db->select('sum(ret_qty) as totalReturn')
            ->from('product_return')
            ->where('usablity', 2)
            ->where('product_id', $product_id)
            ->where('customer_id', '')
            ->get()
            ->row();

        return $product_return;
    }

    public function rqsn_transfer_taken($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {
        if ($from_date) {
            $this->db->where('rqsn.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('rqsn.date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('rqsn.from_id', $outlet_id);
        }
        if ($purchase_id) {
            $this->db->where('rqsn_details.purchase_id', $purchase_id);
        }

        $total_rqsn_transfer = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
            ->from('rqsn_details')
            ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
            ->where('rqsn_details.product_id', $product_id)
            ->where('rqsn_details.status', 3)
            ->where('rqsn_details.isaprv', 1)
            ->where('rqsn_details.isrcv', 1)
            ->where('rqsn_details.is_return', 0)
            ->get()
            ->row();

        return $total_rqsn_transfer;
    }

    public function rqsn_transfer_given($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {


        if ($from_date) {
            $this->db->where('rqsn.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('rqsn.date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('rqsn.to_id', $outlet_id);
        }
        if ($purchase_id) {
            $this->db->where('rqsn_details.purchase_id', $purchase_id);
        }

        $total_rqsn_transfer = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
            ->from('rqsn_details')
            ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
            ->where('rqsn_details.product_id', $product_id)
            // ->where('rqsn_details.status', 3)
            ->where('rqsn_details.isaprv', 1)
            // ->where('rqsn_details.isrcv', 1)
            ->where('rqsn_details.is_return', 0)
            ->get()
            ->row();

        // echo "<pre>";
        // print_r($outlet_id);
        // exit();

        return $total_rqsn_transfer;
    }

    public function rqsn_return_taken($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {
        if ($from_date) {
            $this->db->where('rqsn.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('rqsn.date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('rqsn.from_id', $outlet_id);
        }
        if ($purchase_id) {
            $this->db->where('rqsn_details.purchase_id', $purchase_id);
        }

        $total_rqsn_return = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
            ->from('rqsn_details')
            ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
            ->where('rqsn_details.product_id', $product_id)
            ->where('rqsn_details.status', 3)
            ->where('rqsn_details.isaprv', 1)
            ->where('rqsn_details.isrcv', 1)
            ->where('rqsn_details.is_return', 2)
            ->get()
            ->row();

        return $total_rqsn_return;
    }

    public function rqsn_return_given($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {
        if ($from_date) {
            $this->db->where('rqsn.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('rqsn.date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('rqsn.to_id', $outlet_id);
        }
        if ($purchase_id) {
            $this->db->where('rqsn_details.purchase_id', $purchase_id);
        }

        $total_rqsn_return = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
            ->from('rqsn_details')
            ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
            ->where('rqsn_details.product_id', $product_id)
            // ->where('rqsn_details.status', 3)
            ->where('rqsn_details.isaprv', 1)
            // ->where('rqsn_details.isrcv', 1)
            ->where('rqsn_details.is_return !=', 0)
            ->get()
            ->row();

        return $total_rqsn_return;
    }


    public function stock($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null)
    {


        if ($from_date) {
            $this->db->where('b.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('b.date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('b.outlet_id', $outlet_id);
        }
        if ($purchase_id) {
            $this->db->where('a.purchase_id', $purchase_id);
        }
        $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')
            ->from('invoice_details a')
            ->join('invoice b', 'b.invoice_id = a.invoice_id')
            ->where('a.pre_order', 1)
            ->where('a.product_id', $product_id)
            ->get()
            ->row();



        if ($from_date) {
            $this->db->where('rqsn.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('rqsn.date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('rqsn.to_id', $outlet_id);
        }
        if ($purchase_id) {
            $this->db->where('rqsn_details.purchase_id', $purchase_id);
        }

        $stockout_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
            ->from('rqsn_details')
            ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
            ->where('rqsn_details.isaprv', 1)
            ->where('rqsn_details.isrcv', 1)
            ->where('rqsn_details.product_id', $product_id)
            ->get()
            ->row();


        if ($from_date) {
            $this->db->where('production.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('production.date <=', $to_date);
        }
        $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
            ->from('item_usage')
            ->join('production', 'item_usage.production_id=production.pro_id', 'left')
            ->where('item_usage.item_id', $product_id)
            ->group_by('item_usage.item_id')
            ->get()
            ->row();

        if ($from_date) {
            $this->db->where('rqsn.date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('rqsn.date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('rqsn.from_id', $outlet_id);
        }
        if ($purchase_id) {
            $this->db->where('rqsn_details.purchase_id', $purchase_id);
        }

        $stockin_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
            ->from('rqsn_details')
            ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
            ->where('rqsn_details.isaprv', 1)
            ->where('rqsn_details.isrcv', 1)
            ->where('rqsn_details.is_return', 2)
            ->where('rqsn_details.product_id', $product_id)
            ->get()
            ->row();


        if ($from_date) {
            $this->db->where('product_purchase.purchase_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('product_purchase.purchase_date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('product_purchase.outlet_id', $outlet_id);
        }
        if ($purchase_id) {
            $this->db->where('product_purchase_details.purchase_id', $purchase_id);
        }

        $stockout = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as damaged_qty, Avg(product_purchase_details.rate) as purchaseprice')
            ->from('product_purchase_details')
            ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
            ->where('product_purchase_details.product_id', $product_id)
            ->get()
            ->row();



        $total_in = (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0);


        $total_out = '';


        if ($finished_raw == 1) {
            $total_out = (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) - (!empty($stockin_outlet->totaloutletQnty) ? $stockin_outlet->totaloutletQnty : 0);
        } else {
            if ($from_date) {
                $this->db->where('transfer_items.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('transfer_items.date <=', $to_date);
            }
            if ($purchase_id) {
                $this->db->where('transfer_item_details.purchase_id', $purchase_id);
            }
            $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                ->from('transfer_item_details')
                ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                ->where('transfer_item_details.product_id', $product_id)
                ->group_by('transfer_item_details.product_id')
                ->get()
                ->row();

            $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
        }

        if ($from_date) {
            $this->db->where('a.create_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('a.create_date <=', $to_date);
        }
        if ($outlet_id) {
            $this->db->where('b.outlet_id', $outlet_id);
        }

        $phy_count = $this->db->select('SUM(a.difference) as phy_qty')
            ->from('stock_taking_details a')
            ->join('stock_taking b', 'b.stid = a.stid')
            ->where(array(
                'a.product_id' => $product_id,
                'a.status' => 1,

            ))
            ->group_by('a.product_id')
            ->order_by('a.id', 'desc')
            ->get()
            ->row();

        $diff = (!empty($phy_count->phy_qty) ? $phy_count->phy_qty : 0);


        $stock = ($total_in - $total_out - ($stockout->damaged_qty ? $stockout->damaged_qty : 0)) + $diff;

        // $stockout->damaged_qty
        return $stock;
    }

    public function stock2($from_date = null, $to_date = null, $outlet_id = null, $product_id = null, $finished_raw = null, $purchase_id = null, $type = null)
    {

        $total_in = $this->total_in($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        $total_out = $this->total_out($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        $total_sale = $this->total_sale($from_date, $to_date, $outlet_id, $product_id, $finished_raw, null, $type);

        $total_transfer = $this->total_transfer($from_date, $to_date, $outlet_id, $product_id, $finished_raw, null, $type);

        $total_return_given = $this->total_return_given($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id);

        $total_damage = $this->total_damage($from_date, $to_date, $outlet_id, $product_id, $finished_raw, $purchase_id, $type);

        $stock = $total_in - $total_return_given - $total_sale - $total_transfer - $total_damage;

        // $stock = $total_in - $total_out - $total_return_given - $total_damage;

        return $stock;
    }



    public function getExpiryCheckListReport($postData = null, $post_product_id = null, $pr_status = null, $from_date = null, $to_date = null)
    {
        $this->load->library('occational');
        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $outlet_id = $this->input->post('outlet_id', TRUE);
        $purchs_id = $this->input->post('purchase_id', TRUE);
        $is_exp = $this->input->post('is_exp', TRUE);
        $toDay = date('Y-m-d');

        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }

        $response = array();

        $p_s = $this->input->post('product_status', TRUE);
        $product_sku = $this->input->post('product_sku', TRUE);
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        if ($from_date == null) {
            $date = date('Y-m-d');
            $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
        } else {
            $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        }

        // echo '<pre>';
        // print_r($postData);
        // exit();





        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (a.product_name like '%"
                    . $searchValue .
                    "%' or a.product_model like '%"
                    . $searchValue .
                    "%' or a.sku like '%"
                    . $searchValue .
                    "%' or b.name like '%"
                    . $searchValue .
                    "%'  or a.product_id like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase_details p');
            $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');

            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if ($from_date) {
                $this->db->where('p.expired_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('p.expired_date <=', $to_date);
            }
            if ($purchs_id) {
                $this->db->where('p.purchase_id', $purchs_id);
            }
            if ($is_exp == 1) {
                $this->db->where('p.expired_date >=', $toDay);
            }

            if ($is_exp == 2) {
                $this->db->where('p.expired_date <', $toDay);
            }
            if (isset($p_s) && $p_s != '') {
                $this->db->where('a.finished_raw', $p_s);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
            $this->db->group_by(array('p.purchase_id', 'a.product_id'));
            $this->db->order_by('a.sku', 'asc');

            $records = $this->db->get()->num_rows();
            $totalRecords = $records;


            // echo "<pre>";
            // print_r($records);
            // exit();




            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase_details p');
            $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if ($from_date) {
                $this->db->where('p.expired_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('p.expired_date <=', $to_date);
            }

            if ($purchs_id) {
                $this->db->where('p.purchase_id ', $purchs_id);
            }
            if ($is_exp == 1) {
                $this->db->where('p.expired_date >=', $toDay);
            }

            if ($is_exp == 2) {
                $this->db->where('p.expired_date <', $toDay);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->order_by('a.sku', 'asc');
            $this->db->group_by(array('p.purchase_id', 'a.product_id'));

            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
        }
        ## Fetch records




        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                b.name,
                p.purchase_id,
                p.rate,
                p.expired_date,
             
                ");
        $this->db->from('product_purchase_details p');
        $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');
        //        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->order_by('p.product_id', 'asc');
        $this->db->group_by(array('p.purchase_id', 'a.product_id'));
        $this->db->limit($rowperpage, $start);
        if ($product_sku != '') {
            $this->db->where_in('a.sku', $product_sku);
        }
        if ($from_date) {
            $this->db->where('p.expired_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('p.expired_date <=', $to_date);
        }
        if ($purchs_id) {
            $this->db->where('p.purchase_id ', $purchs_id);
        }
        if ($is_exp == 1) {
            $this->db->where('p.expired_date >=', $toDay);
        }
        if ($is_exp == 2) {
            $this->db->where('p.expired_date <', $toDay);
        }
        if (!$post_product_id && $searchValue != '')
            $this->db->where($searchQuery);

        if ($post_product_id) {
            $this->db->where('a.product_id', $post_product_id);
        }
        if (isset($p_s) && $p_s != '') {
            $this->db->where('a.finished_raw', $p_s);
        }
        $records = $this->db->get()->result();



        $data = array();

        $sl = 1;
        $stock = 0;
        $closing_stock = 0;
        $opening_stock = 0;

        foreach ($records as $record) {
            $purchase_id = $record->purchase_id;
            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

            $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);

            // if ($from_date) {
            //     $this->db->where('b.date >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('b.date <=', $to_date);
            // }
            if ($purchase_id) {
                $this->db->where('a.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('b.outlet_id', $outlet_id);
            }
            $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')
                ->from('invoice_details a')
                ->join('invoice b', 'b.invoice_id = a.invoice_id')
                ->where('a.pre_order', 1)
                ->where(array('a.product_id' => $record->product_id))
                ->get()
                ->row();

            $warrenty_stock = $this->db->select('sum(ret_qty) as totalWarrentyQnty')->from('warrenty_return')->where('product_id', $record->product_id)->get()->row();
            //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();

            // if ($from_date) {
            //     $this->db->where('product_purchase.purchase_date >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('product_purchase.purchase_date <=', $to_date);
            // }
            if ($purchase_id) {
                $this->db->where('product_purchase.purchase_id', $purchase_id);
            }

            // if ($from_date) {
            //     $this->db->where('product_purchase.purchase_date >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('product_purchase.purchase_date <=', $to_date);
            // }
            if ($outlet_id) {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }
            $stockout = $this->db->select('sum(qty) as totalPurchaseQnty, sum(damaged_qty) as damaged_qty, Avg(rate) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where(array('product_purchase_details.product_id' => $record->product_id, 'product_purchase_details.purchase_id' => $record->purchase_id))
                ->get()->row();


            // $stockout = $this->db->select('sum(qty) as totalPurchaseQnty,sum(damaged_qty) as damaged_qty,Avg(rate) as purchaseprice')->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')->from('product_purchase_details')->where(array('product_purchase_details.product_id' => $record->product_id))->get()->row();

            // if ($from_date) {
            //     $this->db->where('product_return.date_return >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('product_return.date_return <=', $to_date);
            // }
            if ($purchase_id) {
                $this->db->where('product_return.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('product_return.outlet_id', $outlet_id);
            }
            $product_return = $this->db->select('sum(ret_qty) as totalReturn')->from('product_return')->where('usablity', 2)->where(array('product_id' => $record->product_id, 'purchase_id' => $record->purchase_id))->get()->row();


            // if ($from_date) {
            //     $this->db->where('rqsn.date >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('rqsn.date <=', $to_date);
            // }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            $stockin_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id', 'left')
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 2)
                ->where(array('rqsn_details.product_id' => $record->product_id))
                ->get()
                ->row();

            // if ($from_date) {
            //     $this->db->where('rqsn.date >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('rqsn.date <=', $to_date);
            // }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            $stockout_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('isaprv', 1)
                ->where('isrcv', 1)
                ->where(array('rqsn_details.product_id' => $record->product_id))
                ->get()
                ->row();
            $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);


            // if ($from_date) {
            //     $this->db->where('opening_inventory.date >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('opening_inventory.date <=', $to_date);
            // }
            $open_stock = $this->db->select('stock_qty')->from('opening_inventory')->where('product_id', $record->product_id)->get()->row();
            // if ($from_date) {
            //     $this->db->where('production.date >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('production.date <=', $to_date);
            // }
            $production_qty = $this->db->select('SUM(quantity) as pro_qty')
                ->from('production_goods')
                ->join('production', 'production_goods.pro_id=production.pro_id', 'left')
                ->where('production_goods.product_id', $record->product_id)
                ->group_by('production_goods.product_id')
                ->get()
                ->row();

            // if ($from_date) {
            //     $this->db->where('production.date >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('production.date <=', $to_date);
            // }
            $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
                ->from('item_usage')
                ->join('production', 'item_usage.production_id=production.pro_id', 'left')
                ->where('item_usage.item_id', $record->product_id)
                ->group_by('item_usage.item_id')
                ->get()
                ->row();

            $sprice = (!empty($record->price) ? $record->price : 0);
            $pprice = (!empty($stockout->purchaseprice) ? sprintf('%0.2f', $stockout->purchaseprice) : 0);
            $total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);
            $total_out = '';

            if ($record->finished_raw == 1) {
                // $total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) - (!empty($stockin_outlet->totaloutletQnty) ? $stockin_outlet->totaloutletQnty : 0);

                $total_out = (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) - (!empty($stockin_outlet->totaloutletQnty) ? $stockin_outlet->totaloutletQnty : 0);
            } else {
                // if ($from_date) {
                //     $this->db->where('transfer_items.date >=', $from_date);
                // }
                // if ($to_date) {
                //     $this->db->where('transfer_items.date <=', $to_date);
                // }
                $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                    ->from('transfer_item_details')
                    ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                    ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))
                    ->group_by(array('transfer_item_details.product_id', 'transfer_item_details.purchase_id'))
                    ->get()
                    ->row();
                $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            }
            // if ($from_date) {
            //     $this->db->where('a.create_date >=', $from_date);
            // }
            // if ($to_date) {
            //     $this->db->where('a.create_date <=', $to_date);
            // }

            $phy_count = $this->db->select('SUM(a.difference) as phy_qty')
                ->from('stock_taking_details a')
                ->join('stock_taking b', 'b.stid = a.stid')
                ->where(array(
                    'b.outlet_id' => $outlet_id,
                    'a.product_id' => $record->product_id,

                    //                    'create_date >=' =purchase_id
                    'a.status' => 1,

                ))
                ->group_by('a.product_id')
                ->order_by('a.id', 'desc')
                ->get()
                ->row();



            $newStock = (!empty($warrenty_stock->totalWarrentyQnty) ? $warrenty_stock->totalWarrentyQnty : 0);
            $diff = (!empty($phy_count->phy_qty) ? $phy_count->phy_qty : 0);

            $stock = ($total_in - $total_out - $stockout->damaged_qty) + $diff;

            // echo "<pre>";
            // print_r($stock);
            // exit();

            $date_now = new DateTime();
            $expired_date   = new DateTime($record->expired_date);
            if ($date_now > $expired_date) {
                $expiry_status = '<span class="label label-danger ">Expired</span>';
            } else {
                $expiry_status = '<span class="label label-success ">Available</span>';
            }
            /************************
             *  Opening Stock Start *
             * **********************/


            // if ($op_date) {
            //     $this->db->where('b.date <=', $op_date);
            //     if ($purchase_id) {
            //         $this->db->where('a.purchase_id', $purchase_id);
            //     }
            //     if ($outlet_id) {
            //         $this->db->where('b.outlet_id', $outlet_id);
            //     }
            //     $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')->from('invoice_details a')->join('invoice b', 'b.invoice_id = a.invoice_id')->where(array('a.product_id' => $record->product_id))->get()->row();
            //     $warrenty_stock = $this->db->select('sum(ret_qty) as totalWarrentyQnty')->from('warrenty_return')->where('product_id', $record->product_id)->get()->row();
            //     //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();

            //     $this->db->where('product_purchase.purchase_date <=', $op_date);
            //     if ($purchase_id) {
            //         $this->db->where('product_purchase_details.purchase_id', $purchase_id);
            //     }
            //     if ($outlet_id) {
            //         $this->db->where('product_purchase.outlet_id', $outlet_id);
            //     }

            //     $stockout = $this->db->select('sum(qty) as totalPurchaseQnty, sum(damaged_qty) as damaged_qty, Avg(rate) as purchaseprice')
            //         ->from('product_purchase_details')
            //         ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
            //         ->where(array('product_purchase_details.product_id' => $record->product_id, 'product_purchase_details.purchase_id' => $record->purchase_id))
            //         //                   ->group_by(array('product_purchase_details.product_id','product_purchase_details.purchase_id'))
            //         ->get()->row();


            //     if ($from_date) {
            //         $this->db->where('product_return.date_return >=', $from_date);
            //     }
            //     if ($to_date) {
            //         $this->db->where('product_return.date_return <=', $to_date);
            //     }

            //     if ($purchase_id) {
            //         $this->db->where('product_return.purchase_id', $purchase_id);
            //     }
            //     if ($outlet_id) {
            //         $this->db->where('product_return.outlet_id', $outlet_id);
            //     }
            //     $product_return = $this->db->select('sum(ret_qty) as totalReturn')
            //         ->from('product_return')
            //         ->where('usablity', 2)
            //         ->where(array('product_id' => $record->product_id))
            //         ->get()->row();

            //     $this->db->where('rqsn.date <=', $op_date);


            //     if ($purchase_id) {
            //         $this->db->where('rqsn_details.purchase_id', $purchase_id);
            //     }
            //     if ($outlet_id) {
            //         $this->db->where('rqsn.from_id', $outlet_id);
            //     }

            //     $this->db->where('rqsn.date <=', $op_date);
            //     $stockin_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
            //         ->from('rqsn_details')
            //         ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
            //         ->where('rqsn_details.isaprv', 1)
            //         ->where('rqsn_details.isrcv', 1)
            //         ->where('rqsn_details.is_return', 2)
            //         ->where(array('rqsn_details.product_id' => $record->product_id))

            //         ->get()
            //         ->row();



            //     $open_stock = $this->db->select('stock_qty')->from('opening_inventory')->where('product_id', $record->product_id)->get()->row();

            //     $this->db->where('production.date <=', $op_date);
            //     $production_qty = $this->db->select('SUM(quantity) as pro_qty')
            //         ->from('production_goods')
            //         ->join('production', 'production.pro_id = production_goods.pro_id', 'left')
            //         ->where('product_id', $record->product_id)
            //         ->group_by('product_id')
            //         ->get()
            //         ->row();


            //     $this->db->where('production.date <=', $op_date);
            //     $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
            //         ->from('item_usage')
            //         ->join('production', 'production.pro_id = item_usage.production_id', 'left')
            //         ->where('item_id', $record->product_id)
            //         ->group_by('item_id')
            //         ->get()
            //         ->row();





            //     $sprice = (!empty($record->price) ? $record->price : 0);
            //     $pprice = (!empty($stockout->purchaseprice) ? sprintf('%0.2f', $stockout->purchaseprice) : 0);
            //     //                $opening_total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);
            //     //                $opening_total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0) + (!empty($stockin_outlet
            //     //                        ->totaloutletQnty) ? $stockin_outlet
            //     //                        ->totaloutletQnty : 0);
            //     $opening_total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);

            //     $opening_total_out = '';

            //     if ($record->finished_raw == 1) {
            //         $opening_total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0)  + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) - (!empty($stockin_outlet->totaloutletQnty) ? $stockin_outlet->totaloutletQnty : 0);
            //     } else {


            //         $this->db->where('transfer_items.date <=', $op_date);
            //         $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
            //             ->from('transfer_item_details')
            //             ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
            //             ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))

            //             ->group_by(array('transfer_item_details.product_id', 'transfer_item_details.purchase_id'))
            //             ->get()
            //             ->row();
            //         $opening_total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            //     }
            //     //  $this->db->where('b.date <=', $op_date);
            //     $phy_count = $this->db->select('SUM(a.difference) as phy_qty')
            //         ->from('stock_taking_details a')
            //         ->join('stock_taking b', 'b.stid = a.stid')
            //         ->where(array(
            //             'b.outlet_id' => $outlet_id,
            //             'a.product_id' => $record->product_id,
            //             'create_date >=' => $op_date,
            //             'a.status' => 1,

            //         ))
            //         ->group_by('a.product_id')
            //         ->order_by('a.id', 'desc')
            //         ->get()
            //         ->row();
            //     $newStock = (!empty($warrenty_stock->totalWarrentyQnty) ? $warrenty_stock->totalWarrentyQnty : 0);
            //     $diff = (!empty($phy_count->phy_qty) ? $phy_count->phy_qty : 0);

            //     $opening_stock = (($opening_total_in - ($opening_total_out + $product_return->totalReturn)) - $newStock) + $diff;


            //     //                $opening_total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0);

            //     //  $opening_stock = $opening_total_in - $opening_total_out;
            // } else {
            //     $opening_stock = $stock;
            // }
            // Opening Stock End

            $closing_stock = $stock;
            /*********************
             *
             *  Closing Stock Start
             *
             * ********************/
            // if ($to_date) {
            //     $this->db->where('b.date <=', $to_date);
            //     if ($outlet_id) {
            //         $this->db->where('b.outlet_id', $outlet_id);
            //     }
            //     $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')->from('invoice_details a')->join('invoice b', 'b.invoice_id = a.invoice_id')->where(array('a.product_id' => $record->product_id))->get()->row();
            //     $warrenty_stock = $this->db->select('sum(ret_qty) as totalWarrentyQnty')->from('warrenty_return')->where('product_id', $record->product_id)->get()->row();
            //     //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();

            //     $this->db->where('product_purchase.purchase_date <=', $to_date);
            //     if ($purchase_id) {
            //         $this->db->where('product_purchase_details.purchase_id', $purchase_id);
            //     }
            //     if ($outlet_id) {
            //         $this->db->where('product_purchase.outlet_id', $outlet_id);
            //     }
            //     $stockout = $this->db->select('sum(qty) as totalPurchaseQnty, sum(damaged_qty) as damaged_qty, Avg(rate) as purchaseprice')
            //         ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
            //         ->from('product_purchase_details')
            //         ->where(array('product_purchase_details.product_id' => $record->product_id, 'product_purchase_details.purchase_id' => $record->purchase_id))
            //         ->get()->row();
            //     $this->db->where('rqsn.date <=', $from_date);

            //     if ($purchase_id) {
            //         $this->db->where('rqsn_details.purchase_id', $purchase_id);
            //     }
            //     if ($outlet_id) {
            //         $this->db->where('rqsn.to_id', $outlet_id);
            //     }
            //     $stockout_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
            //         ->from('rqsn_details')
            //         ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
            //         ->where('isaprv', 1)
            //         ->where('isrcv', 1)
            //         ->where(array('rqsn_details.product_id' => $record->product_id))
            //         ->get()
            //         ->row();

            //     $this->db->where('rqsn.date <=', $from_date);
            //     if ($purchase_id) {
            //         $this->db->where('rqsn_details.purchase_id', $purchase_id);
            //     }
            //     if ($outlet_id) {
            //         $this->db->where('rqsn.from_id', $outlet_id);
            //     }
            //     $stockin_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')
            //         ->from('rqsn_details')
            //         ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
            //         ->where('rqsn_details.isaprv', 1)
            //         ->where('rqsn_details.isrcv', 1)
            //         ->where('rqsn_details.is_return', 2)
            //         ->where(array('rqsn_details.product_id' => $record->product_id))
            //         ->get()
            //         ->row();




            //     if ($from_date) {
            //         $this->db->where('product_return.date_return >=', $from_date);
            //     }
            //     if ($to_date) {
            //         $this->db->where('product_return.date_return <=', $to_date);
            //     }
            //     if ($purchase_id) {
            //         $this->db->where('product_return.purchase_id', $purchase_id);
            //     }
            //     if ($outlet_id) {
            //         $this->db->where('product_return.outlet_id', $outlet_id);
            //     }
            //     $product_return = $this->db->select('sum(ret_qty) as totalReturn')->from('product_return')->where('usablity', 2)->where(array('product_id' => $record->product_id))->get()->row();

            //     // echo '<pre>';
            //     // print_r($product_supplier_price[0]);
            //     // exit();

            //     $open_stock = $this->db->select('stock_qty')->from('opening_inventory')->where('product_id', $record->product_id)->get()->row();

            //     $this->db->where('production.date <=', $to_date);
            //     $production_qty = $this->db->select('SUM(quantity) as pro_qty')
            //         ->from('production_goods')
            //         ->join('production', 'production.pro_id = production_goods.pro_id', 'left')
            //         ->where('product_id', $record->product_id)
            //         ->group_by('product_id')
            //         ->get()
            //         ->row();

            //     $this->db->where('production.date <=', $to_date);


            //     $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
            //         ->from('item_usage')
            //         ->join('production', 'production.pro_id = item_usage.production_id', 'left')
            //         ->where('item_id', $record->product_id)
            //         ->group_by('item_id')
            //         ->get()
            //         ->row();

            //     if ($record->finished_raw == 1) {
            //         $closing_total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0) - (!empty($stockin_outlet->totaloutletQnty) ? $stockin_outlet->totaloutletQnty : 0);
            //     } else {

            //         $this->db->where('transfer_items.date <=', $to_date);
            //         $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
            //             ->from('transfer_item_details')
            //             ->join('transfer_items', 'transfer_items.pro_id=transfer_items.pro_id', 'left')
            //             ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))
            //             ->group_by('transfer_item_details.product_id')
            //             ->get()
            //             ->row();
            //         $closing_total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            //     }
            //     $this->db->where('a.create_date <=', $to_date);
            //     $phy_count = $this->db->select('SUM(a.difference) as phy_qty')
            //         ->from('stock_taking_details a')
            //         ->join('stock_taking b', 'b.stid = a.stid')
            //         ->where(array(
            //             'b.outlet_id' => $outlet_id,
            //             'a.product_id' => $record->product_id,
            //             //                    'create_date >=' => $date,
            //             'a.status' => 1,

            //         ))
            //         ->group_by('a.product_id')
            //         ->order_by('a.id', 'desc')
            //         ->get()
            //         ->row();

            //     $sprice = (!empty($record->price) ? $record->price : 0);
            //     $pprice = (!empty($stockout->purchaseprice) ? sprintf('%0.2f', $stockout->purchaseprice) : 0);
            //     $closing_total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);
            //     //   $closing_total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0);
            //     // $closing_stock = $closing_total_in - $closing_total_out;
            //     $diff = (!empty($phy_count->phy_qty) ? $phy_count->phy_qty : 0);

            //     $closing_stock = (($closing_total_in - ($closing_total_out + $product_return->totalReturn)) - $newStock) + $diff;

            //     // echo "<pre>";
            //     // echo "closing_total_in " . $closing_total_in . "<br>";
            //     // echo "closing_total_out " . $closing_total_out . "<br>";
            //     // echo "product_return->totalReturn " . $product_return->totalReturn . "<br>";
            //     // // echo "stockout->damaged_qty " . $stockout->damaged_qty . "<br>";
            //     // echo "newStock " . $newStock . "<br>";

            //     // exit();
            // } else {
            //     $closing_stock = $stock;
            // }
            // Closing Stock End

            // $new_from_date = $this->input->post('from_date');
            // $new_to_date = $this->input->post('to_date');
            // $new_opening_from_date = '';
            // $new_opening_to_date = '';
            // if ($new_from_date == '') {
            //     $new_date = date_create("0001-01-01");
            //     $new_change_date = date_format($new_date, "Y-m-d");
            //     $new_opening_from_date = date('Y-m-d', strtotime($new_change_date . ' -1 day'));
            // } else {
            //     $new_opening_from_date = date('Y-m-d', strtotime($new_from_date . ' -1 day'));
            // }

            // if ($new_to_date == '') {
            //     $date = date('Y-m-d');
            //     $new_opening_to_date = date('Y-m-d', strtotime($date . ' -1 day'));
            // } else {
            //     $new_opening_to_date = date('Y-m-d', strtotime($new_to_date . ' -1 day'));
            // }


            // $new_outlet_id = $this->input->post('outlet_id', TRUE);
            // if ($new_outlet_id == '') {
            //     $new_outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
            // } elseif ($new_outlet_id === 'All') {
            //     $new_outlet_id = null;
            // } else {
            //     $new_outlet_id = $this->input->post('outlet_id', TRUE);;
            // }

            // if ($new_from_date) {
            //     $opening_stock = $this->expiryStock(null, $new_opening_from_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
            // } else {
            //     $opening_stock = $this->expiryStock($new_opening_from_date, $new_opening_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
            // }

            // if ($new_to_date) {
            //     $closing_stock = $this->expiryStock(null, $new_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
            // } else {
            //     $closing_stock = $this->expiryStock($new_from_date, $new_to_date, $new_outlet_id, $record->product_id, $record->finished_raw, null);
            // }



            //            if ($closing_stock > 0){
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name_bn,
                'expiry_status'  =>  $expiry_status,
                'purchase_id'  =>  $record->purchase_id,
                'expiry_date'  =>  $this->occational->dateConvert($record->expired_date),
                'expired_date'  =>  $record->expired_date,
                'product_type'  =>  $record->finished_raw,
                'production_cost'  => $production_price,
                'product_model' => ($record->product_model ? $record->product_model : ''),
                'category' => ($record->name ? $record->name : ''),
                'sku' => ($record->sku ? $record->sku : ''),
                'sales_price'   =>  sprintf('%0.2f', $sprice),
                'purchase_p'    =>  $record->rate,
                'totalPurchaseQnty' => $total_in,
                'damagedQnty'   => $stockout->damaged_qty,
                'totalSalesQnty' =>  $total_out,
                'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                'stok_quantity' => sprintf('%0.2f', ($closing_stock)),
                'opening_stock'     => $opening_stock,
                'total_sale_price' => $closing_stock * $sprice,

                'purchase_total' => (($closing_stock * $pprice) != 0)
                    ? ($closing_stock * $pprice)
                    : ($production_price
                        ? $production_price * $closing_stock
                        : 0),

            );
            $sl++;
            //            }


            // $closing_inventory = array_sum(array_column($data, 'purchase_total'));

        }

        // echo "<pre>";
        // print_r($data);
        // exit();




        ## Response
        if (!$post_product_id) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,


            );
        } else {

            $response = array(
                "purchase_id" => $data[0]['purchase_id'],
                "central_stock" => $stock,
                "aaData" =>  $data,


            );
        }


        return $response;
    }

    public function getExpiryCheckListReport2($postData = null, $post_product_id = null, $pr_status = null, $from_date = null, $to_date = null)
    {
        $this->load->library('occational');
        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $outlet_id = $this->input->post('outlet_id', TRUE);
        $purchs_id = $this->input->post('purchase_id', TRUE);
        $is_exp = $this->input->post('is_exp', TRUE);
        $toDay = date('Y-m-d');
        $type = 1;

        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
            $type = 0;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }

        // echo "<pre>";
        // print_r($outlet_id);
        // exit();

        $response = array();

        $p_s = $this->input->post('product_status', TRUE);
        $product_sku = $this->input->post('product_sku', TRUE);
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        // if ($from_date == null) {
        //     $date = date('Y-m-d');
        //     $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
        // } else {
        //     $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        // }

        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (a.product_name like '%"
                    . $searchValue .
                    "%' or a.product_model like '%"
                    . $searchValue .
                    "%' or a.sku like '%"
                    . $searchValue .
                    "%' or b.name like '%"
                    . $searchValue .
                    "%'  or a.product_id like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase_details p');
            $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');

            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if ($from_date) {
                $this->db->where('p.expired_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('p.expired_date <=', $to_date);
            }
            if ($purchs_id) {
                $this->db->where('p.purchase_id', $purchs_id);
            }
            if ($is_exp == 1) {
                $this->db->where('p.expired_date >=', $toDay);
            }

            if ($is_exp == 2) {
                $this->db->where('p.expired_date <', $toDay);
            }
            if (isset($p_s) && $p_s != '') {
                $this->db->where('a.finished_raw', $p_s);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->where('p.qty >', 0);
            if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
            $this->db->group_by(array('p.purchase_id', 'a.product_id'));
            $this->db->order_by('a.sku', 'asc');

            $records = $this->db->get()->num_rows();
            $totalRecords = $records;


            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase_details p');
            $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');
            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if ($from_date) {
                $this->db->where('p.expired_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('p.expired_date <=', $to_date);
            }

            if ($purchs_id) {
                $this->db->where('p.purchase_id ', $purchs_id);
            }
            if ($is_exp == 1) {
                $this->db->where('p.expired_date >=', $toDay);
            }

            if ($is_exp == 2) {
                $this->db->where('p.expired_date <', $toDay);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->where('p.qty >', 0);
            $this->db->order_by('a.sku', 'asc');
            $this->db->group_by(array('p.purchase_id', 'a.product_id'));

            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
        }
        ## Fetch records




        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                b.name,
                p.purchase_id,
                p.rate,
                p.expired_date,
             
                ");
        $this->db->from('product_purchase_details p');
        $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');
        //        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->order_by('p.product_id', 'asc');
        $this->db->group_by(array('p.purchase_id', 'a.product_id'));
        $this->db->limit($rowperpage, $start);
        if ($product_sku != '') {
            $this->db->where_in('a.sku', $product_sku);
        }
        if ($from_date) {
            $this->db->where('p.expired_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('p.expired_date <=', $to_date);
        }
        if ($purchs_id) {
            $this->db->where('p.purchase_id ', $purchs_id);
        }
        if ($is_exp == 1) {
            $this->db->where('p.expired_date >=', $toDay);
        }
        if ($is_exp == 2) {
            $this->db->where('p.expired_date <', $toDay);
        }
        if (!$post_product_id && $searchValue != '')
            $this->db->where($searchQuery);

        if ($post_product_id) {
            $this->db->where('a.product_id', $post_product_id);
        }
        if (isset($p_s) && $p_s != '') {
            $this->db->where('a.finished_raw', $p_s);
        }
        $this->db->where('p.qty >', 0);
        $records = $this->db->get()->result();



        $data = array();

        $sl = 1;
        $stock = 0;
        $closing_stock = 0;
        $opening_stock = 0;

        foreach ($records as $record) {
            $purchase_id = $record->purchase_id;
            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

            $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);


            if ($purchase_id) {
                $this->db->where('product_purchase.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }
            $total_purchase = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as damaged_qty, Avg(rate) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where(array('product_purchase_details.product_id' => $record->product_id, 'product_purchase_details.purchase_id' => $record->purchase_id))
                ->where('product_purchase_details.qty >', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_transfer_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 0)
                ->get()
                ->row();

            $total_in = '';
            if ($type == 1) {
                $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0) + (!empty($total_rqsn_transfer_taken->total_rqsn_transfer) ? $total_rqsn_transfer_taken->total_rqsn_transfer : 0);
            } else {
                $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0);
            }


            if ($purchase_id) {
                $this->db->where('product_return.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('product_return.outlet_id', $outlet_id);
            }
            $product_return = $this->db->select('sum(ret_qty) as totalReturn')
                ->from('product_return')
                ->where('usablity', 2)
                ->where('product_id', $record->product_id)
                ->where('customer_id', '')
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_transfer_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                // ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                // ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_return_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                // ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                // ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return !=', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }

            $total_rqsn_return_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 2)
                ->get()
                ->row();

            if ($purchase_id) {
                $this->db->where('a.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('b.outlet_id', $outlet_id);
            }
            $total_sale = $this->db->select('sum(a.quantity) as totalSalesQnty')
                ->from('invoice_details a')
                ->join('invoice b', 'b.invoice_id = a.invoice_id')
                ->where('a.pre_order', 1)
                ->where(array('a.product_id' => $record->product_id))
                ->get()
                ->row();


            $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);

            $sprice = (!empty($record->price) ? $record->price : 0);
            $pprice = (!empty($total_purchase->purchaseprice) ? sprintf('%0.2f', $total_purchase->purchaseprice) : 0);

            $total_damage = (!empty($total_purchase->damaged_qty) ?  $total_purchase->damaged_qty : 0);


            $total_out = '';

            if ($record->finished_raw == 1) {
                if ($type == 1) {
                    $total_out = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0) + (!empty($total_rqsn_transfer_given->total_rqsn_transfer) ? $total_rqsn_transfer_given->total_rqsn_transfer : 0) + (!empty($total_rqsn_return_given->total_rqsn_return) ? $total_rqsn_return_given->total_rqsn_return : 0) - (!empty($total_rqsn_return_taken->total_rqsn_return) ? $total_rqsn_return_taken->total_rqsn_return : 0);
                } else {
                    $total_out = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0);
                }
            } else {
                $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                    ->from('transfer_item_details')
                    ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                    ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))
                    ->group_by(array('transfer_item_details.product_id', 'transfer_item_details.purchase_id'))
                    ->get()
                    ->row();
                $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            }

            $stock = ($total_in - $total_out - $total_damage);

            $date_now = date("Y-m-d");
            $expired_date   = date($record->expired_date);


            if ($date_now > $expired_date) {
                $expiry_status = '<span class="label label-danger ">Expired</span>';
            } else {
                $expiry_status = '<span class="label label-success ">Available</span>';
            }


            $closing_stock = $stock;


            //            if ($closing_stock > 0){
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name_bn,
                'expiry_status'  =>  $expiry_status,
                'purchase_id'  =>  $record->purchase_id,
                'expiry_date'  =>  $this->occational->dateConvert($record->expired_date),
                'expired_date'  =>  $record->expired_date,
                'product_type'  =>  $record->finished_raw,
                'production_cost'  => $production_price,
                'product_model' => ($record->product_model ? $record->product_model : ''),
                'category' => ($record->name ? $record->name : ''),
                'sku' => ($record->sku ? $record->sku : ''),
                'sales_price'   =>  sprintf('%0.2f', $sprice),
                'purchase_p'    =>  $record->rate,
                'totalPurchaseQnty' => $total_in,
                'damagedQnty'   => $total_purchase->damaged_qty,
                'totalSalesQnty' =>  $total_out,
                // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                'stok_quantity' => sprintf('%0.2f', ($closing_stock)),
                'opening_stock'     => $opening_stock,
                'total_sale_price' => $closing_stock * $sprice,

                'purchase_total' => (($closing_stock * $pprice) != 0)
                    ? ($closing_stock * $pprice)
                    : ($production_price
                        ? $production_price * $closing_stock
                        : 0),

            );
            $sl++;
        }

        ## Response
        if (!$post_product_id) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {

            $response = array(
                "purchase_id" => $data[0]['purchase_id'],
                "central_stock" => $stock,
                "aaData" =>  $data,
            );
        }
        return $response;
    }

    public function getSupplierCheckListReport($postData = null, $post_product_id = null, $pr_status = null, $from_date = null, $to_date = null)
    {
        $this->load->library('occational');
        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $outlet_id = $this->input->post('outlet_id', TRUE);
        $purchs_id = $this->input->post('purchase_id', TRUE);
        $is_exp = $this->input->post('is_exp', TRUE);
        $toDay = date('Y-m-d');
        $type = 1;

        // if ($outlet_id == '') {
        //     $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        // } elseif ($outlet_id === 'All') {
        //     $outlet_id = null;
        //     $type = 0;
        // } else {
        //     $outlet_id = $this->input->post('outlet_id', TRUE);
        // }

        // $outlet_id = $this->input->post('outlet_id');
        // echo "<pre>";
        // print_r($outlet_id);
        // exit();


        if ($this->input->post('outlet_id')) {
            $outlet_id = $this->input->post('outlet_id');
            if ($outlet_id === 'All') {
                $outlet_id = null;
            } else {
                $outlet_id = $this->input->post('outlet_id');
            }
        } else {
            $outlet_id = $this->session->userdata('outlet_id');
        }

        // echo "<pre>";
        // print_r($outlet_id);
        // exit();

        $response = array();

        $p_s = $this->input->post('product_status', TRUE);
        $product_sku = $this->input->post('product_sku', TRUE);
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $supplier_id = $this->input->post('supplier_id', TRUE);
        // if ($from_date == null) {
        //     $date = date('Y-m-d');
        //     $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
        // } else {
        //     $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        // }

        // echo "<pre>";
        // print_r($supplier_id);
        // exit();

        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (a.product_name like '%"
                    . $searchValue .
                    "%' or a.product_model like '%"
                    . $searchValue .
                    "%' or a.sku like '%"
                    . $searchValue .
                    "%' or b.name like '%"
                    . $searchValue .
                    "%'  or a.product_id like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase_details p');
            $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
            $this->db->join('cats b', 'a.category_id=b.id', 'left');
            $this->db->join('product_purchase pro', 'pro.purchase_id=p.purchase_id', 'left');
            $this->db->join('supplier_information sup', 'pro.supplier_id=sup.supplier_id', 'left');

            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if ($supplier_id != '') {
                $this->db->where_in('pro.supplier_id', $supplier_id);
            }
            if ($from_date) {
                $this->db->where('p.expired_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('p.expired_date <=', $to_date);
            }
            if ($purchs_id) {
                $this->db->where('p.purchase_id', $purchs_id);
            }
            if ($is_exp == 1) {
                $this->db->where('p.expired_date >=', $toDay);
            }

            if ($is_exp == 2) {
                $this->db->where('p.expired_date <', $toDay);
            }
            if (isset($p_s) && $p_s != '') {
                $this->db->where('a.finished_raw', $p_s);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->where('p.qty >', 0);
            if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
            $this->db->group_by(array('p.purchase_id', 'a.product_id'));
            $this->db->order_by('a.sku', 'asc');

            $records = $this->db->get()->num_rows();
            $totalRecords = $records;


            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase_details p');
            $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
             $this->db->join('cats b', 'a.category_id=b.id', 'left');
            $this->db->join('product_purchase pro', 'pro.purchase_id=p.purchase_id', 'left');
            $this->db->join('supplier_information sup', 'pro.supplier_id=sup.supplier_id', 'left');
            if ($product_sku != '') {
                $this->db->where_in('a.sku', $product_sku);
            }
            if ($supplier_id != '') {
                $this->db->where_in('pro.supplier_id', $supplier_id);
            }
            if ($from_date) {
                $this->db->where('p.expired_date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('p.expired_date <=', $to_date);
            }

            if ($purchs_id) {
                $this->db->where('p.purchase_id ', $purchs_id);
            }
            if ($is_exp == 1) {
                $this->db->where('p.expired_date >=', $toDay);
            }

            if ($is_exp == 2) {
                $this->db->where('p.expired_date <', $toDay);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->where('p.qty >', 0);
            $this->db->order_by('a.sku', 'asc');
            $this->db->group_by(array('p.purchase_id', 'a.product_id'));

            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
        }
        ## Fetch records




        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                a.price,
                b.name,
                p.purchase_id,
                p.rate,
                p.expired_date,
                sup.supplier_name,
             
                ");
        $this->db->from('product_purchase_details p');
        $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');
        $this->db->join('product_purchase pro', 'pro.purchase_id=p.purchase_id', 'left');
        $this->db->join('supplier_information sup', 'pro.supplier_id=sup.supplier_id', 'left');
        //        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->order_by('p.product_id', 'asc');
        $this->db->group_by(array('p.purchase_id', 'a.product_id'));
        $this->db->limit($rowperpage, $start);
        if ($product_sku != '') {
            $this->db->where_in('a.sku', $product_sku);
        }
        if ($supplier_id != '') {
            $this->db->where_in('pro.supplier_id', $supplier_id);
        }
        if ($from_date) {
            $this->db->where('p.expired_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('p.expired_date <=', $to_date);
        }
        if ($purchs_id) {
            $this->db->where('p.purchase_id ', $purchs_id);
        }
        if ($is_exp == 1) {
            $this->db->where('p.expired_date >=', $toDay);
        }
        if ($is_exp == 2) {
            $this->db->where('p.expired_date <', $toDay);
        }
        if (!$post_product_id && $searchValue != '')
            $this->db->where($searchQuery);

        if ($post_product_id) {
            $this->db->where('a.product_id', $post_product_id);
        }
        if (isset($p_s) && $p_s != '') {
            $this->db->where('a.finished_raw', $p_s);
        }
        $this->db->where('p.qty >', 0);
        $records = $this->db->get()->result();



        $data = array();

        $sl = 1;
        $stock = 0;
        $closing_stock = 0;
        $opening_stock = 0;

        foreach ($records as $record) {
            $purchase_id = $record->purchase_id;
            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

            $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);


            if ($purchase_id) {
                $this->db->where('product_purchase.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }
            $total_purchase = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as damaged_qty, Avg(rate) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where(array('product_purchase_details.product_id' => $record->product_id, 'product_purchase_details.purchase_id' => $record->purchase_id))
                ->where('product_purchase_details.qty >', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_transfer_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 0)
                ->get()
                ->row();

            $total_in = '';
            if ($type == 1) {
                $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0) + (!empty($total_rqsn_transfer_taken->total_rqsn_transfer) ? $total_rqsn_transfer_taken->total_rqsn_transfer : 0);
            } else {
                $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0);
            }


            if ($purchase_id) {
                $this->db->where('product_return.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('product_return.outlet_id', $outlet_id);
            }
            $product_return = $this->db->select('sum(ret_qty) as totalReturn')
                ->from('product_return')
                ->where('usablity', 2)
                ->where('product_id', $record->product_id)
                ->where('customer_id', '')
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_transfer_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                // ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                // ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_return_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                // ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                // ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return !=', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }

            $total_rqsn_return_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 2)
                ->get()
                ->row();

            if ($purchase_id) {
                $this->db->where('a.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('b.outlet_id', $outlet_id);
            }
            $total_sale = $this->db->select('sum(a.quantity) as totalSalesQnty')
                ->from('invoice_details a')
                ->join('invoice b', 'b.invoice_id = a.invoice_id')
                ->where('a.pre_order', 1)
                ->where(array('a.product_id' => $record->product_id))
                ->get()
                ->row();


            $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);

            $sprice = (!empty($record->price) ? $record->price : 0);
            $pprice = (!empty($total_purchase->purchaseprice) ? sprintf('%0.2f', $total_purchase->purchaseprice) : 0);

            $total_damage = (!empty($total_purchase->damaged_qty) ?  $total_purchase->damaged_qty : 0);


            $total_out = '';

            if ($record->finished_raw == 1) {
                if ($type == 1) {
                    $total_out = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0) + (!empty($total_rqsn_transfer_given->total_rqsn_transfer) ? $total_rqsn_transfer_given->total_rqsn_transfer : 0) + (!empty($total_rqsn_return_given->total_rqsn_return) ? $total_rqsn_return_given->total_rqsn_return : 0) - (!empty($total_rqsn_return_taken->total_rqsn_return) ? $total_rqsn_return_taken->total_rqsn_return : 0);
                } else {
                    $total_out = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0);
                }
            } else {
                $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                    ->from('transfer_item_details')
                    ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                    ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))
                    ->group_by(array('transfer_item_details.product_id', 'transfer_item_details.purchase_id'))
                    ->get()
                    ->row();
                $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            }

            $sold_qnty = '';


            if ($record->finished_raw == 1) {
                if ($type == 1) {
                    $sold_qnty = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0);
                } else {
                    $sold_qnty = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0);
                }
            }

            $total_transfer = '';

            if ($record->finished_raw == 1) {
                if ($type == 1) {
                    $total_transfer = (!empty($total_rqsn_transfer_given->total_rqsn_transfer) ? $total_rqsn_transfer_given->total_rqsn_transfer : 0) + (!empty($total_rqsn_return_given->total_rqsn_return) ? $total_rqsn_return_given->total_rqsn_return : 0) - (!empty($total_rqsn_return_taken->total_rqsn_return) ? $total_rqsn_return_taken->total_rqsn_return : 0);
                }
            } else {
                $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                    ->from('transfer_item_details')
                    ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                    ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))
                    ->group_by(array('transfer_item_details.product_id', 'transfer_item_details.purchase_id'))
                    ->get()
                    ->row();
                $total_transfer = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            }

            $stock = ($total_in - $sold_qnty - $total_transfer - $total_damage);

            $date_now = date("Y-m-d");
            $expired_date   = date($record->expired_date);


            if ($date_now > $expired_date) {
                $expiry_status = '<span class="label label-danger ">Expired</span>';
            } else {
                $expiry_status = '<span class="label label-success ">Available</span>';
            }


            $closing_stock = $stock;


            //            if ($closing_stock > 0){
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name_bn,
                'expiry_status'  =>  $expiry_status,
                'purchase_id'  =>  $record->purchase_id,
                'supplier_name'  =>  $record->supplier_name,
                'expiry_date'  =>  $this->occational->dateConvert($record->expired_date),
                'expired_date'  =>  $record->expired_date,
                'product_type'  =>  $record->finished_raw,
                'production_cost'  => $production_price,
                'product_model' => ($record->product_model ? $record->product_model : ''),
                'category' => ($record->name ? $record->name : ''),
                'sku' => ($record->sku ? $record->sku : ''),
                'sales_price'   =>  sprintf('%0.2f', $sprice),
                'purchase_p'    =>  $record->rate,
                'totalPurchaseQnty' => $total_in,
                'damagedQnty'   => $total_purchase->damaged_qty,
                'totalSalesQnty' =>  $total_out,
                'sold_qnty' =>  $sold_qnty,
                'total_transfer' =>  $total_transfer,

                // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                'stok_quantity' => sprintf('%0.2f', ($closing_stock)),
                'opening_stock'     => $opening_stock,
                'total_sale_price' => $closing_stock * $sprice,

                // 'purchase_total' => (($closing_stock * $pprice) != 0)
                //     ? ($closing_stock * $pprice)
                //     : ($production_price
                //         ? $production_price * $closing_stock
                //         : 0),

                'purchase_total' => (($closing_stock * $record->rate) != 0) ? ($closing_stock * $record->rate) : 0,
                'sold_value' => (($sold_qnty * $record->rate) != 0) ? ($sold_qnty * $record->rate) : 0,

            );
            $sl++;
        }

        ## Response
        if (!$post_product_id) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {

            $response = array(
                "purchase_id" => $data[0]['purchase_id'],
                "central_stock" => $stock,
                "aaData" =>  $data,
            );
        }
        return $response;
    }


    public function getExpiryCheckList($post_product_id = null, $is_exp = null)
    {
        $this->load->library('occational');
        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        // $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        $outlet_id = $this->session->userdata('outlet_id');
        $response = array();


        $date = date('Y-m-d');


        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                b.name,
                p.purchase_id,
                p.expired_date,
             
                ");
        $this->db->from('product_purchase_details p');
        $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
        $this->db->join('cats b', 'a.category_id=b.id', 'left');

        if ($is_exp == 2) {
            $this->db->where('p.expired_date <', $date);
        } else {
            $this->db->where('p.expired_date >=', $date);
        }
        $this->db->order_by('p.id', 'asc');
        $this->db->group_by(array('p.purchase_id', 'a.product_id'));



        if ($post_product_id) {
            $this->db->where('a.product_id', $post_product_id);
        }


        //        }
        $records = $this->db->get()->result();

        //echo '<pre>';print_r($records);exit();
        $data = array();


        $sl = 1;
        $stock = 0;


        $expired_stock = 0;


        foreach ($records as $record) {
            $purchase_id = $record->purchase_id;
            $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

            $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);


            if ($purchase_id) {
                $this->db->where('product_purchase.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('product_purchase.outlet_id', $outlet_id);
            }
            $total_purchase = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as damaged_qty, Avg(rate) as purchaseprice')
                ->from('product_purchase_details')
                ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
                ->where('product_purchase_details.product_id', $record->product_id)
                ->where('product_purchase_details.qty >', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_transfer_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 0)
                ->get()
                ->row();

            $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0) + (!empty($total_rqsn_transfer_taken->total_rqsn_transfer) ? $total_rqsn_transfer_taken->total_rqsn_transfer : 0);




            if ($purchase_id) {
                $this->db->where('product_return.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('product_return.outlet_id', $outlet_id);
            }
            $product_return = $this->db->select('sum(ret_qty) as totalReturn')
                ->from('product_return')
                ->where('usablity', 2)
                ->where('product_id', $record->product_id)
                ->where('customer_id', '')
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_transfer_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                // ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                // ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.to_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }
            $total_rqsn_return_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                // ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                // ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return !=', 0)
                ->get()
                ->row();

            if ($outlet_id) {
                $this->db->where('rqsn.from_id', $outlet_id);
            }
            if ($purchase_id) {
                $this->db->where('rqsn_details.purchase_id', $purchase_id);
            }

            $total_rqsn_return_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
                ->from('rqsn_details')
                ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
                ->where('rqsn_details.product_id', $record->product_id)
                ->where('rqsn_details.status', 3)
                ->where('rqsn_details.isaprv', 1)
                ->where('rqsn_details.isrcv', 1)
                ->where('rqsn_details.is_return', 2)
                ->get()
                ->row();

            if ($purchase_id) {
                $this->db->where('a.purchase_id', $purchase_id);
            }
            if ($outlet_id) {
                $this->db->where('b.outlet_id', $outlet_id);
            }
            $total_sale = $this->db->select('sum(a.quantity) as totalSalesQnty')
                ->from('invoice_details a')
                ->join('invoice b', 'b.invoice_id = a.invoice_id')
                ->where('a.pre_order', 1)
                ->where(array('a.product_id' => $record->product_id))
                ->get()
                ->row();


            $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);

            $sprice = (!empty($record->price) ? $record->price : 0);
            $pprice = (!empty($total_purchase->purchaseprice) ? sprintf('%0.2f', $total_purchase->purchaseprice) : 0);

            $total_damage = (!empty($total_purchase->damaged_qty) ?  $total_purchase->damaged_qty : 0);

            $$total_out = '';

            if ($record->finished_raw == 1) {
                $total_out = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0) + (!empty($total_rqsn_transfer_given->total_rqsn_transfer) ? $total_rqsn_transfer_given->total_rqsn_transfer : 0) + (!empty($total_rqsn_return_given->total_rqsn_return) ? $total_rqsn_return_given->total_rqsn_return : 0) - (!empty($total_rqsn_return_taken->total_rqsn_return) ? $total_rqsn_return_taken->total_rqsn_return : 0);
            } else {
                $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
                    ->from('transfer_item_details')
                    ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
                    ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))
                    ->group_by(array('transfer_item_details.product_id', 'transfer_item_details.purchase_id'))
                    ->get()
                    ->row();
                $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
            }

            $stock = ($total_in - $total_out - $total_damage);

            // $date_now = new DateTime();
            // $expired_date   = new DateTime($record->expired_date);
            // if ($date_now > $expired_date) {
            //     $expiry_status = '<span class="label label-danger ">Expired</span>';
            // } else {
            //     $expiry_status = '<span class="label label-success ">Available</span>';
            // }

            $date_now = date("Y-m-d");
            $expired_date   = date($record->expired_date);

            if ($date_now > $expired_date) {
                $expiry_status = '<span class="label label-danger ">Expired</span>';
            } else {
                $expiry_status = '<span class="label label-success ">Available</span>';
            }


            // $closing_stock = $stock;
            $expired_stock += $stock;




            if ($stock > 0) {
                $data[] = array(
                    'sl'            =>   $sl,
                    'product_name'  =>  $record->product_name_bn,
                    'expiry_status'  =>  $expiry_status,
                    'purchase_id'  =>  $record->purchase_id,
                    'expiry_date'  =>  $this->occational->dateConvert($record->expired_date),
                    'expired_date'  =>  $record->expired_date,
                    'product_type'  =>  $record->finished_raw,
                    'product_model' => ($record->product_model ? $record->product_model : ''),
                    'category' => ($record->name ? $record->name : ''),
                    'sku' => ($record->sku ? $record->sku : ''),
                    'sales_price'   =>  sprintf('%0.2f', $sprice),
                    'purchase_p'    =>  $pprice,
                    'totalPurchaseQnty' => $total_in,
                    // 'damagedQnty'   => $stockout->damaged_qty,
                    'totalSalesQnty' =>  $total_out,
                    // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
                    //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
                    'stok_quantity' => sprintf('%0.2f', $stock),
                    'opening_stock'     => $stock,
                    'total_sale_price' => $stock * $sprice,
                    'out' => $outlet_id,



                );
                $sl++;
            }
        }

        //        $expired_stock= 0;
        //
        //        foreach($data as $key => $value){
        //            $date_now = new DateTime();
        //            $expired_date   = new DateTime( $value['expired_date'] );
        //            if ( $date_now > $expired_date ){
        //                $expired_stock += $value['stok_quantity'];
        //            }
        //
        //        }

        if ($outlet_id) {
            $this->db->where('b.outlet_id', $outlet_id);
        }
        $total_phy_count = $this->db->select('SUM(a.difference) as phy_qty')
            ->from('stock_taking_details a')
            ->join('stock_taking b', 'b.stid = a.stid')
            ->where(array(
                'a.product_id' => $post_product_id,
                'a.status' => 1,

            ))
            ->group_by('a.product_id')
            ->order_by('a.id', 'desc')
            ->get()
            ->row();

        $expired_stock += (!empty($total_phy_count->phy_qty) ? $total_phy_count->phy_qty : 0);

        // echo '<pre>';
        // print_r($expired_stock);
        // exit();

        // return $total_phy_count;

        if ($outlet_id) {
            $this->db->where('wastage_dec.outlet_id', $outlet_id);
        }
        $total_wastage_qnty = $this->db->select('sum(wastage_dec.wastage_quantity) as total_wastage_qnty')
            ->from('wastage_dec')
            ->where('wastage_dec.product_id', $post_product_id)
            ->get()
            ->row();

        $expired_stock -= (!empty($total_wastage_qnty->total_wastage_qnty) ?  $total_wastage_qnty->total_wastage_qnty : 0);



        $response = array(
            "purchase_id" => $data[0]['purchase_id'],
            "central_stock" => $stock,
            "aaData" =>  $data,
            "expired_stock" =>  $expired_stock,


        );



        //  echo '<pre>';print_r($response);exit();

        return $response;
    }

    // public function getExpiryCheckListNew($post_product_id = null, $is_exp = null)
    // {
    //     $this->load->library('occational');
    //     $this->load->model('Warehouse');
    //     $this->load->model('suppliers');
    //     $outlet_id = $this->input->post('outlet_id', TRUE);
    //     $purchs_id = $this->input->post('purchase_id', TRUE);
    //     $is_exp = $this->input->post('is_exp', TRUE);
    //     $toDay = date('Y-m-d');
    //     $type = 1;

    //     if ($outlet_id == '') {
    //         $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
    //     } elseif ($outlet_id === 'All') {
    //         $outlet_id = null;
    //         $type = 0;
    //     } else {
    //         $outlet_id = $this->input->post('outlet_id', TRUE);;
    //     }

    //     $response = array();

    //     $p_s = $this->input->post('product_status', TRUE);
    //     $product_sku = $this->input->post('product_sku', TRUE);
    //     $from_date = $this->input->post('from_date', TRUE);
    //     $to_date = $this->input->post('to_date', TRUE);
    //     // if ($from_date == null) {
    //     //     $date = date('Y-m-d');
    //     //     $op_date = date('Y-m-d', strtotime($date . ' -1 day'));
    //     // } else {
    //     //     $op_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
    //     // }

    //     ## Read value
    //     if (!$post_product_id) {
    //         $draw = $postData['draw'];
    //         $start = $postData['start'];
    //         $rowperpage = $postData['length']; // Rows display per page
    //         $columnIndex = $postData['order'][0]['column']; // Column index
    //         $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    //         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    //         $searchValue = $postData['search']['value']; // Search value

    //         ## Search
    //         $searchQuery = "";
    //         if ($searchValue != '') {
    //             $searchQuery = " (a.product_name like '%"
    //                 . $searchValue .
    //                 "%' or a.product_model like '%"
    //                 . $searchValue .
    //                 "%' or a.sku like '%"
    //                 . $searchValue .
    //                 "%' or b.name like '%"
    //                 . $searchValue .
    //                 "%'  or a.product_id like '%"
    //                 . $searchValue .
    //                 "%') ";
    //         }

    //         ## Total number of records without filtering
    //         $this->db->select('count(*) as allcount');
    //         $this->db->from('product_purchase_details p');
    //         $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');

    //         if ($product_sku != '') {
    //             $this->db->where_in('a.sku', $product_sku);
    //         }
    //         if ($from_date) {
    //             $this->db->where('p.expired_date >=', $from_date);
    //         }
    //         if ($to_date) {
    //             $this->db->where('p.expired_date <=', $to_date);
    //         }
    //         if ($purchs_id) {
    //             $this->db->where('p.purchase_id', $purchs_id);
    //         }
    //         if ($is_exp == 1) {
    //             $this->db->where('p.expired_date >=', $toDay);
    //         }

    //         if ($is_exp == 2) {
    //             $this->db->where('p.expired_date <', $toDay);
    //         }
    //         if (isset($p_s) && $p_s != '') {
    //             $this->db->where('a.finished_raw', $p_s);
    //         }
    //         if ($searchValue != '') {
    //             $this->db->where($searchQuery);
    //         }
    //         if ($pr_status) $this->db->where('a.finished_raw', $pr_status);
    //         $this->db->group_by(array('p.purchase_id', 'a.product_id'));
    //         $this->db->order_by('a.sku', 'asc');

    //         $records = $this->db->get()->num_rows();
    //         $totalRecords = $records;


    //         ## Total number of record with filtering
    //         $this->db->select('count(*) as allcount');
    //         $this->db->from('product_purchase_details p');
    //         $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
    //         if ($product_sku != '') {
    //             $this->db->where_in('a.sku', $product_sku);
    //         }
    //         if ($from_date) {
    //             $this->db->where('p.expired_date >=', $from_date);
    //         }
    //         if ($to_date) {
    //             $this->db->where('p.expired_date <=', $to_date);
    //         }

    //         if ($purchs_id) {
    //             $this->db->where('p.purchase_id ', $purchs_id);
    //         }
    //         if ($is_exp == 1) {
    //             $this->db->where('p.expired_date >=', $toDay);
    //         }

    //         if ($is_exp == 2) {
    //             $this->db->where('p.expired_date <', $toDay);
    //         }
    //         if ($searchValue != '') {
    //             $this->db->where($searchQuery);
    //         }
    //         $this->db->order_by('a.sku', 'asc');
    //         $this->db->group_by(array('p.purchase_id', 'a.product_id'));

    //         $records = $this->db->get()->num_rows();
    //         $totalRecordwithFilter = $records;
    //     }
    //     ## Fetch records




    //     $this->db->select("a.*,
    //             a.product_name,
    //             a.product_id,
    //             a.product_model,
    //             b.name,
    //             p.purchase_id,
    //             p.rate,
    //             p.expired_date,

    //             ");
    //     $this->db->from('product_purchase_details p');
    //     $this->db->join('product_information a', 'p.product_id=a.product_id', 'left');
    //     $this->db->join('cats b', 'a.category_id=b.id', 'left');
    //     //        $this->db->order_by($columnName, $columnSortOrder);
    //     $this->db->order_by('p.product_id', 'asc');
    //     $this->db->group_by(array('p.purchase_id', 'a.product_id'));
    //     $this->db->limit($rowperpage, $start);
    //     if ($product_sku != '') {
    //         $this->db->where_in('a.sku', $product_sku);
    //     }
    //     if ($from_date) {
    //         $this->db->where('p.expired_date >=', $from_date);
    //     }
    //     if ($to_date) {
    //         $this->db->where('p.expired_date <=', $to_date);
    //     }
    //     if ($purchs_id) {
    //         $this->db->where('p.purchase_id ', $purchs_id);
    //     }
    //     if ($is_exp == 1) {
    //         $this->db->where('p.expired_date >=', $toDay);
    //     }
    //     if ($is_exp == 2) {
    //         $this->db->where('p.expired_date <', $toDay);
    //     }
    //     if (!$post_product_id && $searchValue != '')
    //         $this->db->where($searchQuery);

    //     if ($post_product_id) {
    //         $this->db->where('a.product_id', $post_product_id);
    //     }
    //     if (isset($p_s) && $p_s != '') {
    //         $this->db->where('a.finished_raw', $p_s);
    //     }
    //     $records = $this->db->get()->result();



    //     $data = array();

    //     $sl = 1;
    //     $stock = 0;
    //     $closing_stock = 0;
    //     $opening_stock = 0;
    //     $expired_stock = 0;

    //     foreach ($records as $record) {
    //         $purchase_id = $record->purchase_id;
    //         $production_cost = $this->db->select('avg(production_cost) as cost')->from('production_cost a')->where('a.product_id', $record->product_id)->get()->row();

    //         $production_price = (!empty($production_cost->cost) ? sprintf('%0.2f', $production_cost->cost) : 0);


    //         if ($purchase_id) {
    //             $this->db->where('product_purchase.purchase_id', $purchase_id);
    //         }
    //         if ($outlet_id) {
    //             $this->db->where('product_purchase.outlet_id', $outlet_id);
    //         }
    //         $total_purchase = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.damaged_qty) as damaged_qty, Avg(rate) as purchaseprice')
    //             ->from('product_purchase_details')
    //             ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
    //             ->where(array('product_purchase_details.product_id' => $record->product_id, 'product_purchase_details.purchase_id' => $record->purchase_id))
    //             ->where('product_purchase_details.qty >', 0)
    //             ->get()
    //             ->row();

    //         if ($outlet_id) {
    //             $this->db->where('rqsn.from_id', $outlet_id);
    //         }
    //         if ($purchase_id) {
    //             $this->db->where('rqsn_details.purchase_id', $purchase_id);
    //         }
    //         $total_rqsn_transfer_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
    //             ->from('rqsn_details')
    //             ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
    //             ->where('rqsn_details.product_id', $record->product_id)
    //             ->where('rqsn_details.status', 3)
    //             ->where('rqsn_details.isaprv', 1)
    //             ->where('rqsn_details.isrcv', 1)
    //             ->where('rqsn_details.is_return', 0)
    //             ->get()
    //             ->row();

    //         $total_in = '';
    //         if ($type == 1) {
    //             $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0) + (!empty($total_rqsn_transfer_taken->total_rqsn_transfer) ? $total_rqsn_transfer_taken->total_rqsn_transfer : 0);
    //         } else {
    //             $total_in = (!empty($total_purchase->totalPurchaseQnty) ? $total_purchase->totalPurchaseQnty : 0);
    //         }




    //         if ($purchase_id) {
    //             $this->db->where('product_return.purchase_id', $purchase_id);
    //         }
    //         if ($outlet_id) {
    //             $this->db->where('product_return.outlet_id', $outlet_id);
    //         }
    //         $product_return = $this->db->select('sum(ret_qty) as totalReturn')
    //             ->from('product_return')
    //             ->where('usablity', 2)
    //             ->where('product_id', $record->product_id)
    //             ->where('customer_id', '')
    //             ->get()
    //             ->row();

    //         if ($outlet_id) {
    //             $this->db->where('rqsn.to_id', $outlet_id);
    //         }
    //         if ($purchase_id) {
    //             $this->db->where('rqsn_details.purchase_id', $purchase_id);
    //         }
    //         $total_rqsn_transfer_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_transfer')
    //             ->from('rqsn_details')
    //             ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
    //             ->where('rqsn_details.product_id', $record->product_id)
    //             ->where('rqsn_details.status', 3)
    //             ->where('rqsn_details.isaprv', 1)
    //             ->where('rqsn_details.isrcv', 1)
    //             ->where('rqsn_details.is_return', 0)
    //             ->get()
    //             ->row();

    //         if ($outlet_id) {
    //             $this->db->where('rqsn.to_id', $outlet_id);
    //         }
    //         if ($purchase_id) {
    //             $this->db->where('rqsn_details.purchase_id', $purchase_id);
    //         }
    //         $total_rqsn_return_given = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
    //             ->from('rqsn_details')
    //             ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
    //             ->where('rqsn_details.product_id', $record->product_id)
    //             ->where('rqsn_details.status', 3)
    //             ->where('rqsn_details.isaprv', 1)
    //             ->where('rqsn_details.isrcv', 1)
    //             ->where('rqsn_details.is_return', 2)
    //             ->get()
    //             ->row();

    //         if ($outlet_id) {
    //             $this->db->where('rqsn.from_id', $outlet_id);
    //         }
    //         if ($purchase_id) {
    //             $this->db->where('rqsn_details.purchase_id', $purchase_id);
    //         }

    //         $total_rqsn_return_taken = $this->db->select('sum(rqsn_details.a_qty) as total_rqsn_return')
    //             ->from('rqsn_details')
    //             ->join('rqsn', 'rqsn.rqsn_id = rqsn_details.rqsn_id')
    //             ->where('rqsn_details.product_id', $record->product_id)
    //             ->where('rqsn_details.status', 3)
    //             ->where('rqsn_details.isaprv', 1)
    //             ->where('rqsn_details.isrcv', 1)
    //             ->where('rqsn_details.is_return', 2)
    //             ->get()
    //             ->row();

    //         if ($purchase_id) {
    //             $this->db->where('a.purchase_id', $purchase_id);
    //         }
    //         if ($outlet_id) {
    //             $this->db->where('b.outlet_id', $outlet_id);
    //         }
    //         $total_sale = $this->db->select('sum(a.quantity) as totalSalesQnty')
    //             ->from('invoice_details a')
    //             ->join('invoice b', 'b.invoice_id = a.invoice_id')
    //             ->where('a.pre_order', 1)
    //             ->where(array('a.product_id' => $record->product_id))
    //             ->get()
    //             ->row();


    //         $product_supplier_price = $this->suppliers->pr_supp_price($record->product_id);

    //         $sprice = (!empty($record->price) ? $record->price : 0);
    //         $pprice = (!empty($total_purchase->purchaseprice) ? sprintf('%0.2f', $total_purchase->purchaseprice) : 0);


    //         $total_out = '';

    //         if ($record->finished_raw == 1) {
    //             if ($type == 1) {
    //                 $total_out = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0) + (!empty($total_rqsn_transfer_given->total_rqsn_transfer) ? $total_rqsn_transfer_given->total_rqsn_transfer : 0) + (!empty($total_rqsn_return_given->total_rqsn_return) ? $total_rqsn_return_given->total_rqsn_return : 0) - (!empty($total_rqsn_return_taken->total_rqsn_return) ? $total_rqsn_return_taken->total_rqsn_return : 0);
    //             } else {
    //                 $total_out = (!empty($total_sale->totalSalesQnty) ? $total_sale->totalSalesQnty : 0) + (!empty($product_return->totalReturn) ? $product_return->totalReturn : 0);
    //             }
    //         } else {
    //             $transfer_item = $this->db->select('SUM(transfer_item_details.quantity) as transfer_item')
    //                 ->from('transfer_item_details')
    //                 ->join('transfer_items', 'transfer_item_details.pro_id=transfer_items.pro_id', 'left')
    //                 ->where(array('transfer_item_details.product_id' => $record->product_id, 'transfer_item_details.purchase_id' => $record->purchase_id))
    //                 ->group_by(array('transfer_item_details.product_id', 'transfer_item_details.purchase_id'))
    //                 ->get()
    //                 ->row();
    //             $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
    //         }

    //         $stock = ($total_in - $total_out);

    //         $date_now = new DateTime();
    //         $expired_date   = new DateTime($record->expired_date);
    //         if ($date_now > $expired_date) {
    //             $expiry_status = '<span class="label label-danger ">Expired</span>';
    //         } else {
    //             $expiry_status = '<span class="label label-success ">Available</span>';
    //         }


    //         $closing_stock = $stock;
    //         $expired_stock += $stock;


    //         //            if ($closing_stock > 0){
    //         $data[] = array(
    //             'sl'            =>   $sl,
    //             'product_name'  =>  $record->product_name_bn,
    //             'expiry_status'  =>  $expiry_status,
    //             'purchase_id'  =>  $record->purchase_id,
    //             'expiry_date'  =>  $this->occational->dateConvert($record->expired_date),
    //             'expired_date'  =>  $record->expired_date,
    //             'product_type'  =>  $record->finished_raw,
    //             'production_cost'  => $production_price,
    //             'product_model' => ($record->product_model ? $record->product_model : ''),
    //             'category' => ($record->name ? $record->name : ''),
    //             'sku' => ($record->sku ? $record->sku : ''),
    //             'sales_price'   =>  sprintf('%0.2f', $sprice),
    //             'purchase_p'    =>  $record->rate,
    //             'totalPurchaseQnty' => $total_in,
    //             'damagedQnty'   => $total_purchase->damaged_qty,
    //             'totalSalesQnty' =>  $total_out,
    //             // 'warrenty_stock' =>  $warrenty_stock->totalWarrentyQnty,
    //             //'wastage_stock'=>  $wastage_stock->totalWastageQnty,
    //             'stok_quantity' => sprintf('%0.2f', ($closing_stock)),
    //             'opening_stock'     => $opening_stock,
    //             'total_sale_price' => $closing_stock * $sprice,

    //             'purchase_total' => (($closing_stock * $pprice) != 0)
    //                 ? ($closing_stock * $pprice)
    //                 : ($production_price
    //                     ? $production_price * $closing_stock
    //                     : 0),

    //         );
    //         $sl++;
    //     }

    //     ## Response
    //     if (!$post_product_id) {
    //         $response = array(
    //             "draw" => intval($draw),
    //             "iTotalRecords" => $totalRecordwithFilter,
    //             "iTotalDisplayRecords" => $totalRecords,
    //             "aaData" =>  $data,
    //         );
    //     } else {

    //         $response = array(
    //             "purchase_id" => $data[0]['purchase_id'],
    //             "central_stock" => $stock,
    //             "aaData" =>  $data,
    //         );
    //     }
    //     return $response;
    // }


    public function current_stock($product_id, $status)
    {
        $this->load->model('suppliers');
        $stockin = $this->db->select('sum(a.quantity) as totalSalesQnty')->from('invoice_details a')->join('invoice b', 'b.invoice_id = a.invoice_id')->where('a.pre_order', 1)->where('b.outlet_id', 'HK7TGDT69VFMXB7')->where('a.product_id', $product_id)->get()->row();
        $warrenty_stock = $this->db->select('sum(ret_qty) as totalWarrentyQnty')->from('warrenty_return')->where('product_id', $product_id)->get()->row();
        //$wastage_stock = $this->db->select('sum(ret_qty) as totalWastageQnty')->from('warrenty_return')->where('product_id',$record->product_id,'usablity',3)->get()->row();
        $stockout = $this->db->select('sum(qty) as totalPurchaseQnty,sum(damaged_qty) as damaged_qty,Avg(rate) as purchaseprice')->from('product_purchase_details')->where('status', 2)->where('product_id', $product_id)->get()->row();
        $stockout_outlet = $this->db->select('sum(a_qty) as totaloutletQnty')->from('rqsn_details')->where('isaprv', 1)->where('isrcv', 1)->where('product_id', $product_id)->get()->row();

        $product_supplier_price = $this->suppliers->pr_supp_price($product_id);



        $open_stock = $this->db->select('stock_qty')->from('opening_inventory')->where('product_id', $product_id)->get()->row();
        $production_qty = $this->db->select('SUM(quantity) as pro_qty')
            ->from('production_goods')
            ->where('product_id', $product_id)
            ->group_by('product_id')
            ->get()
            ->row();

        $used_qty = $this->db->select('SUM(usage_qty) as used_qty')
            ->from('item_usage')
            ->where('item_id', $product_id)
            ->group_by('item_id')
            ->get()
            ->row();



        $total_in = (!empty($open_stock->stock_qty) ? $open_stock->stock_qty : 0) + (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) + (!empty($production_qty->pro_qty) ? $production_qty->pro_qty : 0);
        $total_out = '';

        if ($status == 1) {
            $total_out = (!empty($stockout->damaged_qty) ? $stockout->damaged_qty : 0) + (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0) + (!empty($stockout_outlet->totaloutletQnty) ? $stockout_outlet->totaloutletQnty : 0) + (!empty($used_qty->used_qty) ? $used_qty->used_qty : 0);
        } else {

            $transfer_item = $this->db->select('SUM(quantity) as transfer_item')
                ->from('transfer_item_details')
                ->where('product_id', $product_id)
                ->group_by('product_id')
                ->get()
                ->row();
            $total_out = (!empty($transfer_item->transfer_item) ? $transfer_item->transfer_item : 0);
        }




        $stock = $total_in - $total_out;

        $newStock = (!empty($warrenty_stock->totalWarrentyQnty) ? $warrenty_stock->totalWarrentyQnty : 0);

        $closing_stock = sprintf('%0.2f', $stock - $newStock);

        return $closing_stock;
    }


    // supplier wise stock list
    public function getSupplierStockList($postData = null)
    {

        $response = array();
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
            $searchQuery = " (a.product_name like '%" . $searchValue . "%' or a.product_model like '%" . $searchValue . "%' or a.price like'%" . $searchValue . "%' or c.supplier_price like'%" . $searchValue . "%' or m.supplier_name like'%" . $searchValue . "%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_information a');
        $this->db->join('supplier_product c', 'c.product_id = a.product_id', 'left');
        $this->db->join('supplier_information m', 'm.supplier_id = c.supplier_id', 'left');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_information a');
        $this->db->join('supplier_product c', 'c.product_id = a.product_id', 'left');
        $this->db->join('supplier_information m', 'm.supplier_id = c.supplier_id', 'left');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                c.supplier_price,
                m.supplier_name,
                m.supplier_id,
                ");
        $this->db->from('product_information a');
        $this->db->join('supplier_product c', 'c.product_id = a.product_id', 'left');
        $this->db->join('supplier_information m', 'm.supplier_id = c.supplier_id', 'left');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;
        foreach ($records as $record) {
            $stockin = $this->db->select('sum(quantity) as totalSalesQnty')->from('invoice_details')->where('product_id', $record->product_id)->get()->row();
            $stockout = $this->db->select('sum(quantity) as totalPurchaseQnty,Avg(rate) as purchaseprice')->from('product_purchase_details')->where('product_id', $record->product_id)->get()->row();


            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name,
                'supplier_name' =>  $record->supplier_name,
                'product_model' =>  $record->product_model,
                'sales_price'   =>  $record->price,
                'purchase_p'    =>   number_format($stockout->purchaseprice, 2),
                'totalPurchaseQnty' => $stockout->totalPurchaseQnty,
                'totalSalesQnty' =>  $stockin->totalSalesQnty,
                'stok_quantity' =>  $stockout->totalPurchaseQnty - $stockin->totalSalesQnty,
                'total_sale_price' => ($stockout->totalPurchaseQnty - $stockin->totalSalesQnty) * $record->price,
                'purchase_total' => ($stockout->totalPurchaseQnty - $stockin->totalSalesQnty) * $record->supplier_price,
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

        return $response;
    }

    //Retrieve todays_total_sales_report
    public function todays_total_sales_report()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_id = ($CI->Warehouse->get_outlet_user()) ? $CI->Warehouse->get_outlet_user()[0]['outlet_id'] : null;
        $today = date('Y-m-d');
        $this->db->select("a.date,a.invoice,b.invoice_id, sum(a.total_amount) as total_amt, sum(b.total_price) as total_sale,sum(`quantity`*`supplier_rate`) as total_supplier_rate,(SUM(total_price) - SUM(`quantity`*`supplier_rate`)) AS total_profit");
        $this->db->from('invoice a');
        $this->db->join('invoice_details b', 'b.invoice_id = a.invoice_id');
        $this->db->where('a.date', $today);
        $this->db->where('a.outlet_id', $outlet_id);
        $this->db->order_by('a.invoice_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function todays_total_sales_amount()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        // $outlet_id = ($CI->Warehouse->get_outlet_user()) ? $CI->Warehouse->get_outlet_user()[0]['outlet_id'] : null;
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'];
        

        $today = date('Y-m-d');
        $this->db->select("sum(total_amount) as total_amount");
        $this->db->from('invoice');
        $this->db->where('date', $today);
        $this->db->where('outlet_id', $outlet_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Retrieve todays_total_sales_report
    public function todays_total_purchase_report()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] ? $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] : null;
        $today = date('Y-m-d');
        $this->db->select("sum(grand_total_amount) as ttl_purchase_amount");
        $this->db->from('product_purchase ');
        $this->db->where('purchase_date', $today);
        $this->db->where('outlet_id', $outlet_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // todays sales product
    public function todays_sale_product($outlet_id = null)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $user_id = $this->session->userdata('user_id');
        
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] ? $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'] : null;

        $today = date('Y-m-d');
        $this->db->select("c.product_name,c.price");
        $this->db->from('invoice a');
        $this->db->where('a.outlet_id', $outlet_id);
        $this->db->join('invoice_details b', 'b.invoice_id = a.invoice_id');
        $this->db->join('product_information c', 'c.product_id = b.product_id');
        $this->db->order_by('a.date', 'desc');
        $this->db->where('a.date', $today);
        $this->db->limit('3');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //
    //    public function todays_sales_report(){
    //
    //        $this->db->select("a.*");
    //        $this->db->from('invoice a');
    //        return $this->db->get()->result_array();
    //
    //    }

    //Retrieve todays_sales_report
    // public function todays_sales_report($per_page = null, $page = null, $outlet_id = null)
    // {

    //     $today = date('Y-m-d');
    //     $this->db->select("a.*,b.customer_id,b.customer_name,o.outlet_name");
    //     $this->db->from('invoice a');
    //     $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    //     $this->db->join('outlet_warehouse o', 'o.outlet_id = a.outlet_id', 'left');
    //     $this->db->where('a.date', $today);
    //     $this->db->where('a.outlet_id', $outlet_id);
    //     if ($per_page && $page)
    //         $this->db->limit($per_page, $page);
    //     $this->db->order_by('a.invoice', 'desc');
    //     $query = $this->db->get();
    //     if ($query->num_rows() > 0) {
    //         return $query->result_array();
    //     }
    //     return false;
    // }

    public function todays_sales_report(
        $per_page = null,
        $page = null,
        $outlet_id = null
    ) {

        $today = date('Y-m-d');
        $this->db->select('a.date,a.invoice,a.invoice_id,a.due_amount,a.outlet_id,a.changeamount,
        (SELECT sum(id.quantity) from invoice_details as id WHERE id.invoice_id = a.invoice_id) as total_sku,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "1" AND paid.invoice_id = a.invoice_id) as total_cash,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "3" AND paid.invoice_id = a.invoice_id) as total_bkash,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "6" AND paid.invoice_id = a.invoice_id) as total_card,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "5" AND paid.invoice_id = a.invoice_id) as total_nagad,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "7" AND paid.invoice_id = a.invoice_id) as total_rocket,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "2" AND paid.invoice_id = a.invoice_id) as total_cheque,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "4" AND paid.invoice_id = a.invoice_id) as total_bank,

        a.total_discount,a.total_amount,a.paid_amount,a.sales_return,a.shipping_cost,a.service_charge,a.invoice,b.customer_id,b.customer_name,pi.sku');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->join('paid_amount p', 'p.invoice_id = a.invoice_id', 'left');
        $this->db->join('invoice_details id', 'id.invoice_id = a.invoice_id', 'left');
        $this->db->join('product_information pi', 'pi.product_id = id.product_id', 'left');
        $this->db->where('a.date', $today);
        if ($outlet_id) {
            $this->db->where('a.outlet_id', $outlet_id);
        }
        if ($per_page && $page)
            $this->db->limit($per_page, $page);
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get()->result_array();
        $final_array = array();
        // $sl = 1;
        // echo "<pre>";
        // print_r($query);
        // exit();
        foreach ($query as $key => $value) {
            $test_array[$value['invoice_id']] = $value['invoice_id'];
            $final_array[$value['invoice_id']]['sales_date'] = $value['date'];
            $final_array[$value['invoice_id']]['invoice_id'] = $value['invoice'];
            $final_array[$value['invoice_id']]['invoice'] = $value['invoice_id'];
            $final_array[$value['invoice_id']]['customer_name'] = $value['customer_name'];
            $final_array[$value['invoice_id']]['outlet_id'] = $value['outlet_id'];
            if (array_key_exists($value['invoice_id'], $test_array)) {
                $final_array[$value['invoice_id']]['sku'] = $final_array[$value['invoice_id']]['sku'] . "," . $value['sku'];
                //$final_array[$value['invoice_id']]['sku'] = $this->stringToArray($final_array[$value['invoice_id']]['sku']);
            } else {
                $final_array[$value['invoice_id']]['sku'] = $value['sku'] . ",";
            }
            // if ($value['pay_type'] == 1) {

            //     $final_array[$value['invoice_id']]['cash'] += $value['amount'];
            // }
            // if ($value['pay_type'] == 3) {
            //     $final_array[$value['invoice_id']]['bkash'] += $value['amount'];
            // }
            // if ($value['pay_type'] == 6) {
            //     $final_array[$value['invoice_id']]['card'] += $value['amount'];
            // }
            // if ($value['pay_type'] == 5) {
            //     $final_array[$value['invoice_id']]['nagad'] += $value['amount'];
            // }
            // if ($value['pay_type'] == 7) {
            //     $final_array[$value['invoice_id']]['rocket'] += $value['amount'];
            // }
            $final_array[$value['invoice_id']]['changeamount'] = $value['changeamount'];
            $final_array[$value['invoice_id']]['bkash'] = $value['total_bkash'];
            $final_array[$value['invoice_id']]['cash'] = $value['total_cash'];
            $final_array[$value['invoice_id']]['nagad'] = $value['total_nagad'];
            $final_array[$value['invoice_id']]['card'] = $value['total_card'];
            $final_array[$value['invoice_id']]['rocket'] = $value['total_rocket'];
            $final_array[$value['invoice_id']]['cheque'] = $value['total_cheque'];
            $final_array[$value['invoice_id']]['bank'] = $value['total_bank'];
            $final_array[$value['invoice_id']]['total_amount'] = $value['total_amount'];
            $final_array[$value['invoice_id']]['service_charge'] = $value['service_charge'];
            $final_array[$value['invoice_id']]['shipping_cost'] = $value['shipping_cost'];
            $final_array[$value['invoice_id']]['sales_return'] = $value['sales_return'];
            $final_array[$value['invoice_id']]['due_amount'] = $value['due_amount'];
            $final_array[$value['invoice_id']]['total_discount'] = $value['total_discount'];
            $final_array[$value['invoice_id']]['net_sales'] = $value['total_amount'] - $value['total_discount'];
            $final_array[$value['invoice_id']]['qnty'] = $value['total_sku'];
            $final_array[$value['invoice_id']]['paid_amount'] = $value['paid_amount'];
        }
        // echo "<pre>";
        // print_r(array_values($final_array));
        // exit();

        if ($query) {
            return array_values($final_array);
        }

        return false;
    }
    // ======================= user sales report ================
    public function user_sales_report($per_page, $page, $from_date, $to_date, $user_id)
    {
        $this->db->select("sum(total_amount) as amount,count(a.invoice_id) as toal_invoice,a.*,b.first_name,b.last_name");
        $this->db->from('invoice a');
        $this->db->join('users b', 'b.user_id = a.sales_by', 'left');
        if (!empty($user_id)) {
            $this->db->where('a.sales_by', $user_id);
        }
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        if ($per_page && $page)
            $this->db->limit($per_page, $page);
        $this->db->group_by('a.sales_by');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // ====================== user sales count ==========================
    public function user_sales_count($from_date, $to_date, $user_id)
    {
        $this->db->select("sum(a.total_amount) as amount,count(a.invoice_id) as toal_invoice,a.*,b.first_name,b.last_name");
        $this->db->from('invoice a');
        $this->db->join('users b', 'b.user_id = a.sales_by', 'left');
        if (!empty($user_id)) {
            $this->db->where('a.sales_by', $user_id);
        }
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->group_by('a.sales_by');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    //Retrieve todays_sales_report_count
    public function todays_sales_report_count()
    {
        $today = date('Y-m-d');
        $this->db->select("a.*,b.customer_id,b.customer_name");
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.date', $today);
        $this->db->order_by('a.invoice_id', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }

    //     =============== its for purchase_report_category_wise_count =============
    public function purchase_report_category_wise_count()
    {
    }
    public function purchase_warrenty_report_category_wise_count()
    {
    }
    public function purchase_report_shelf_wise_count()
    {
    }
    public function supplier_payment_count()
    {
    }
    public function customer_recieve_count()
    {
    }

    //    ============= its for purchase_report_category_wise ===============
    public function purchase_report_category_wise($per_page = null, $page = null)
    {
        $this->db->select('b.product_name, b.product_model, SUM(a.quantity) as quantity, SUM(a.total_amount) as total_amount, d.purchase_date, c.category_name');
        $this->db->group_by('b.product_id, c.category_id');
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_category c', 'c.category_id = b.category_id');
        $this->db->join('product_purchase d', 'd.purchase_id = a.purchase_id');

        if ($per_page && $page)
            $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }
    public function purchase_warrenty_report_category_wise($per_page = null, $page = null)
    {
        $this->db->select('b.product_name, b.product_model, a.quantity,a.total_amount,a.warrenty_date, a.expired_date, c.category_name');
        //$this->db->group_by('b.product_id, c.category_id');
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_category c', 'c.category_id = b.category_id');
        //$this->db->join('product_purchase d', 'd.purchase_id = a.purchase_id');

        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }
    public function purchase_expired_report_category_wise($per_page = null, $page = null)
    {
        $this->db->select('b.product_name, b.product_model, a.quantity,a.total_amount,a.warrenty_date, a.expired_date, c.category_name');
        //$this->db->group_by('b.product_id, c.category_id');
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_category c', 'c.category_id = b.category_id');
        //$this->db->join('product_purchase d', 'd.purchase_id = a.purchase_id');

        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }
    public function purchase_report_shelf_wise()
    {
        $this->db->select('b.product_name, b.product_model,a.rate as purchase_price,b.price as sales_price,SUM(a.quantity) as quantity,SUM(a.total_amount) as total_amount,a.warehouse, c.category_name');
        $this->db->group_by('a.warehouse,b.id,c.category_id,b.product_id');
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        //$this->db->join('invoice_details e', 'e.product_id = a.product_id');
        $this->db->join('product_category c', 'c.category_id = b.category_id');
        //$this->db->group_by('b.product_id');
        //$this->db->where('a.quantity >',0);

        $this->db->limit('10000');
        //$product_id = $this->input->post('product_id',TRUE);


        //        $available_qty=$quantity-$sell_qty;
        $query = $this->db->get();
        return $query->result();
    }


    public function supplier_payment_report()
    {
        $this->db->select('a.VNo,a.Vtype,a.VDate,a.Narration,a.Debit as debit,a.Credit as credit');
        //$this->db->group_by('a.warehouse,b.id,c.category_id');
        $this->db->from('acc_transaction a');
        $this->db->where('a.Vtype', 'PM');
        $this->db->limit('500');
        //$product_id = $this->input->post('product_id',TRUE);


        //        $available_qty=$quantity-$sell_qty;
        $query = $this->db->get();
        return $query->result();
    }
    public function customer_recieve_report()
    {
        $this->db->select('a.VNo,a.Vtype,a.VDate,a.Narration,a.Debit as debit,a.Credit as credit');
        //$this->db->group_by('a.warehouse,b.id,c.category_id');
        $this->db->from('acc_transaction a');
        $this->db->where('a.Vtype', 'CR');
        $this->db->limit('500');
        //$product_id = $this->input->post('product_id',TRUE);


        //        $available_qty=$quantity-$sell_qty;
        $query = $this->db->get();
        return $query->result();
    }

    //    ============= its for purchase_report_category_wise ===============
    public function filter_purchase_report_category_wise($category = null, $from_date = null, $to_date = null, $per_page = null, $page = null)
    {
        $dateRange = "d.purchase_date BETWEEN '$from_date' AND '$to_date'";
        $this->db->select('b.product_name, b.product_model,SUM(a.quantity) as quantity, SUM(a.total_amount) as total_amount, d.purchase_date, c.category_name');
        $this->db->group_by('b.product_id, c.category_id');
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');

        $this->db->join('product_category c', 'c.category_id = b.category_id');
        $this->db->join('product_purchase d', 'd.purchase_id = a.purchase_id');

        if ($category) {
            $this->db->where('b.category_id', $category);
        }
        if ($category && $from_date && $to_date) {
            $this->db->where('b.category_id', $category);
            $this->db->where($dateRange);
        }
        if ($from_date && $to_date) {
            $this->db->where($dateRange);
        }
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }

    public function filter_purchase_warrenty_report_category_wise($category = null, $from_date = null, $to_date = null, $per_page = null, $page = null)
    {
        $dateRange = "a.warrenty_date BETWEEN '$from_date' AND '$to_date'";
        $this->db->select('b.product_name, b.product_model,a.quantity,a.total_amount, a.warrenty_date, c.category_name');
        // $this->db->group_by('b.product_id, c.category_id');
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');

        $this->db->join('product_category c', 'c.category_id = b.category_id');
        // $this->db->join('product_purchase d', 'd.purchase_id = a.purchase_id');

        if ($category) {
            $this->db->where('b.category_id', $category);
        }
        if ($category && $from_date && $to_date) {
            $this->db->where('b.category_id', $category);
            $this->db->where($dateRange);
        }
        if ($from_date && $to_date) {
            $this->db->where($dateRange);
        }
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }
    public function filter_purchase_expired_report_category_wise($category = null, $from_date = null, $to_date = null, $per_page = null, $page = null)
    {
        $dateRange = "a.expired_date BETWEEN '$from_date' AND '$to_date'";
        $this->db->select('b.product_name, b.product_model,a.quantity, a.total_amount, a.expired_date, c.category_name');
        // $this->db->group_by('b.product_id, c.category_id');
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');

        $this->db->join('product_category c', 'c.category_id = b.category_id');
        // $this->db->join('product_purchase d', 'd.purchase_id = a.purchase_id');

        if ($category) {
            $this->db->where('b.category_id', $category);
        }
        if ($category && $from_date && $to_date) {
            $this->db->where('b.category_id', $category);
            $this->db->where($dateRange);
        }
        if ($from_date && $to_date) {
            $this->db->where($dateRange);
        }
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }
    //    public function filter_purchase_report_shelf_wise($product=null,$category = null, $from_date = null, $to_date = null, $per_page = null, $page = null) {
    //        $dateRange = "d.purchase_date BETWEEN '$from_date' AND '$to_date'";
    //        $this->db->select('b.product_name, b.product_model,SUM(a.quantity) as quantity, SUM(a.total_amount) as total_amount,a.shelf_id, d.purchase_date, c.category_name');
    //        $this->db->group_by('a.product_id','a.shelf_id');
    //        $this->db->from('product_purchase_details a');
    //        $this->db->join('product_information b', 'b.product_id = a.product_id');
    //       // $this->db->join('invoice_details e', 'e.product_id = a.product_id');
    //        $this->db->join('product_category c', 'c.category_id = b.category_id');
    //        $this->db->join('product_purchase d', 'd.purchase_id = a.purchase_id');
    //       // $this->db->where(array('a.product_id' => $product , 'a.shelf_id' => $category));
    //
    //        if ($product) {
    //            $this->db->where('a.product_id', $product);
    //        } if ($category) {
    //            $this->db->where('a.shelf_id', $category);
    //        }
    //        if ($product && $category) {
    //            $this->db->where('a.shelf_id', $category);
    //            $this->db->where('a.product_id', $product);
    //
    //        }
    //        if ($product && $category && $from_date && $to_date) {
    //            $this->db->where('a.product_id', $product);
    //            $this->db->where('a.shelf_id', $category);
    //
    //            $this->db->where($dateRange);
    //        }
    //        if ($from_date && $to_date) {
    //            $this->db->where($dateRange);
    //        }
    //        $this->db->limit($per_page, $page);
    //        $query = $this->db->get();
    //        return $query->result();
    //
    //    }

    public function filter_purchase_report_shelf_wise($product = null, $category = null, $from_date = null, $to_date = null, $per_page = null, $page = null)
    {
        // $dateRange = "d.purchase_date BETWEEN '$from_date' AND '$to_date'";
        $this->db->select('b.product_name, b.product_model,SUM(a.quantity) as quantity,a.rate as purchase_price,b.price as sales_price,SUM(a.total_amount) as total_amount,a.warehouse, c.category_name');
        $this->db->group_by('a.warehouse,b.id,c.category_id,a.product_id');
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        //$this->db->join('invoice_details d', 'd.product_id = a.product_id');
        // $this->db->join('invoice_details e', 'e.product_id = a.product_id');
        $this->db->join('product_category c', 'c.category_id = b.category_id');
        // $this->db->join('product_purchase d', 'd.purchase_id = a.purchase_id');
        // $this->db->where(array('a.product_id' => $product , 'a.shelf_id' => $category));
        // $this->db->where('a.quantity >',0);
        if ($product) {
            $this->db->where('a.product_id', $product);
            $this->db->or_where('b.product_id_two', $product);
        }
        if ($category) {
            $this->db->where('a.warehouse', $category);
        }
        if ($product && $category) {
            $this->db->where('a.warehouse', $category);
            $this->db->where('a.product_id', $product);
            $this->db->or_where('a.product_id_two', $product);
        }
        //        if ($product && $category && $from_date && $to_date) {
        //            $this->db->where('a.product_id', $product);
        //            $this->db->where('a.product_id_two', $product);
        //            $this->db->where('a.shelf_id', $category);
        //
        //            //$this->db->where($dateRange);
        //        }
        //        if ($from_date && $to_date) {
        //            $this->db->where($dateRange);
        //        }
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }
    public function filter_sp_report_date_wise($from_date = null, $to_date = null, $per_page = null, $page = null)
    {
        $dateRange = "a.VDate BETWEEN '$from_date' AND '$to_date'";
        $this->db->select('a.VNo,a.Vtype,a.VDate,a.Narration,a.Debit as debit,a.Credit as credit');
        //$this->db->group_by('a.warehouse,b.id,c.category_id');
        $this->db->from('acc_transaction a');
        $this->db->where('a.Vtype', 'PM');
        $this->db->limit('500');


        if ($from_date && $to_date) {
            $this->db->where($dateRange);
        }
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }
    public function filter_cr_report_date_wise($from_date = null, $to_date = null, $per_page = null, $page = null)
    {
        $dateRange = "a.VDate BETWEEN '$from_date' AND '$to_date'";
        $this->db->select('a.VNo,a.Vtype,a.VDate,a.Narration,a.Debit as debit,a.Credit as credit');
        //$this->db->group_by('a.warehouse,b.id,c.category_id');
        $this->db->from('acc_transaction a');
        $this->db->where('a.Vtype', 'CR');
        $this->db->limit('500');


        if ($from_date && $to_date) {
            $this->db->where($dateRange);
        }
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }

    //    =============== its for sales_report_category_wise_count =============
    public function sales_report_category_wise_count()
    {
    }

    //=================Stock takig details ================//

    public function stock_taking_details_by_id($id)
    {
        $this->db->select('*,s.outlet_id as out')
            ->from('stock_taking_details a')
            ->join('stock_taking s', 's.stid=a.stid')
            ->join('product_information b', 'a.product_id=b.product_id')
            ->join('outlet_warehouse o', 'o.outlet_id=s.outlet_id', 'left')
            ->where('a.stid', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //    ============= its for sales_report_category_wise ===============
    public function sales_report_category_wise($per_page = null, $page = null)
    {
        $user_id = $this->session->userdata('user_id');

        $this->db->select('b.product_name, b.product_model, sum(a.quantity) as quantity, sum(a.total_price) as total_price, d.date, c.category_name');
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_category c', 'c.category_id = b.category_id');
        $this->db->join('invoice d', 'd.invoice_id = a.invoice_id');
        $this->db->join('outlet_warehouse x', 'x.outlet_id = d.outlet_id');
        $this->db->where('x.user_id', $user_id);
        $this->db->group_by('b.product_id, c.category_id');

        if ($per_page && $page)
            $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }

    //    ============= its for filter_sales_report_category_wise ===============
    public function filter_sales_report_category_wise($outlet_id = null, $category = null, $from_date = null, $to_date = null, $per_page = null, $page = null)
    {
        if ($outlet_id == 1) {
            $outlet_id = null;
        }
        $dateRange = "d.date BETWEEN '$from_date' AND '$to_date'";
        $this->db->select('b.product_name, b.product_model, sum(a.quantity) as quantity, sum(a.total_price) as total_price, d.date, c.category_name');
        $this->db->group_by('b.product_id, c.category_id');
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_category c', 'c.category_id = b.category_id');
        $this->db->join('invoice d', 'd.invoice_id = a.invoice_id');
        if ($outlet_id) {
            $this->db->where('d.outlet_id', $outlet_id);
        }
        if ($category) {
            $this->db->where('b.category_id', $category);
        }
        if ($outlet_id && $category && $from_date && $to_date) {
            $this->db->where('b.category_id', $category);
            $this->db->where('d.outlet_id', $outlet_id);
            $this->db->where($dateRange);
        }
        if ($from_date && $to_date) {
            $this->db->where($dateRange);
        }
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        return $query->result();
    }




    //    =============== its for sales_report_category_wise_count =============
    public function sales_warrenty_report_count()
    {
    }




    //Retrieve todays_purchase_report
    public function todays_purchase_report($per_page = null, $page = null, $outlet_id = null)
    {
        $today = date('Y-m-d');
        $this->db->select("a.*,b.supplier_id,b.supplier_name,c.outlet_name");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id', 'left');
        $this->db->join('outlet_warehouse c', 'c.outlet_id = a.outlet_id', 'left');
        if ($outlet_id) {
            $this->db->where('a.outlet_id', $outlet_id);
        }
        $this->db->where('a.purchase_date', $today);

        $this->db->order_by('a.purchase_id', 'desc');

        if ($per_page && $page)
            $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function purchase_warrenty_report($per_page = null, $page = null)
    {
        //$today = date('Y-m-d');
        $this->db->select("a.*,b.*,c.*,d.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('a.purchase_date');
        $this->db->order_by('a.purchase_id', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function purchase_expiry_report($per_page = null, $page = null)
    {
        //$today = date('Y-m-d');
        $this->db->select("a.*,b.*,c.*,d.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('a.purchase_date');
        $this->db->order_by('a.purchase_id', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function purchase_cheque_report($per_page = null, $page = null)
    {
        //$today = date('Y-m-d');
        $this->db->select("a.*,b.*,c.*,d.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('a.purchase_date');
        $this->db->where('a.status', 2);
        $this->db->order_by('a.purchase_id', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Retrieve todays_purchase_report count
    public function todays_purchase_report_count()
    {
        $today = date('Y-m-d');
        $this->db->select("a.*,b.supplier_id,b.supplier_name");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('a.purchase_date', $today);
        $this->db->order_by('a.purchase_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function purchase_warrenty_report_count()
    {
        // $today = date('Y-m-d');
        $this->db->select("a.*,b.*,c.*,d.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');

        $this->db->where('a.purchase_date');
        $this->db->order_by('a.purchase_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function purchase_expiry_report_count()
    {
        // $today = date('Y-m-d');
        $this->db->select("a.*,b.*,c.*,d.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');

        $this->db->where('a.purchase_date');
        $this->db->order_by('a.purchase_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function purchase_cheque_report_count()
    {
        // $today = date('Y-m-d');
        $this->db->select("a.*,b.*,c.*,d.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');

        $this->db->where('a.purchase_date');
        $this->db->order_by('a.purchase_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        return $query->num_rows();
        // return $query;
    }

    //Total profit report
    public function total_profit_report($perpage = null, $page = null)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select("a.date,a.invoice,b.invoice_id,
            CAST(a.total_amount AS DECIMAL(16,2)) as total_sale");
        $this->db->select('CAST(sum(`quantity`*`supplier_rate`) AS DECIMAL(16,2)) as total_supplier_rate', FALSE);
        $this->db->select("CAST(a.total_amount- SUM(`quantity`*`supplier_rate`) AS DECIMAL(16,2)) AS total_profit");
        $this->db->from('invoice a');
        $this->db->join('invoice_details b', 'b.invoice_id = a.invoice_id');

        $this->db->join('outlet_warehouse x', 'x.outlet_id = a.outlet_id');
        $this->db->where('x.user_id', $user_id);
        $this->db->group_by('b.invoice_id');
        $this->db->order_by('a.invoice', 'desc');

        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Total profit report
    public function total_profit_report_count()
    {

        $this->db->select("a.date,a.invoice,b.invoice_id,a.total_amount as total_sale");
        $this->db->select('sum(`quantity`*`supplier_rate`) as total_supplier_rate', FALSE);
        $this->db->select("(a.total_amount - SUM(`quantity`*`supplier_rate`)) AS total_profit");
        $this->db->from('invoice a');
        $this->db->join('invoice_details b', 'b.invoice_id = a.invoice_id');
        $this->db->group_by('b.invoice_id');
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }

    //Retrieve Monthly Sales Report
    public function monthly_sales_report()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_id = ($CI->Warehouse->get_outlet_user()) ? $CI->Warehouse->get_outlet_user()[0]['outlet_id'] : null;

        $query1 = $this->db->query("
            SELECT
                date,
                EXTRACT(MONTH FROM STR_TO_DATE(date,'%Y-%m-%d')) as month,
                COUNT(invoice_id) as total
            FROM
                invoice
            WHERE
                EXTRACT(YEAR FROM STR_TO_DATE(date,'%Y-%m-%d'))  >= EXTRACT(YEAR FROM NOW())
            AND
                outlet_id='" . $outlet_id . "'
            GROUP BY
                EXTRACT(YEAR_MONTH FROM STR_TO_DATE(date,'%Y-%m-%d'))
            ORDER BY
                month ASC
        ")->result();

        $query2 = $this->db->query("
            SELECT
                purchase_date,
                EXTRACT(MONTH FROM STR_TO_DATE(purchase_date,'%Y-%m-%d')) as month,
                COUNT(purchase_id) as total_month
            FROM
                product_purchase
            WHERE
                EXTRACT(YEAR FROM STR_TO_DATE(purchase_date,'%Y-%m-%d'))  >= EXTRACT(YEAR FROM NOW())
            AND
                outlet_id = '" . $outlet_id . "'
            GROUP BY
                EXTRACT(YEAR_MONTH FROM STR_TO_DATE(purchase_date,'%Y-%m-%d'))
            ORDER BY
                month ASC
        ")->result();
        return [$query1, $query2];
    }

    //Retrieve all Report
    // public function retrieve_dateWise_SalesReports($outlet_id, $from_date, $to_date, $per_page = null, $page = null)
    // {
    //     if ($outlet_id == 1) {
    //         $outlet_id = null;
    //     }
    //     $this->db->select("*");
    //     $this->db->from('invoice a');
    //     $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
    //     $this->db->join('outlet_warehouse o', 'o.outlet_id = a.outlet_id', 'left');
    //     $this->db->where('a.date >=', $from_date);
    //     $this->db->where('a.date <=', $to_date);
    //     if ($outlet_id) {
    //         $this->db->where('a.outlet_id', $outlet_id);
    //     }

    //     $this->db->order_by('a.date', 'desc');
    //     if ($per_page && $page)
    //         $this->db->limit($per_page, $page);
    //     $query = $this->db->get();
    //     if ($query->num_rows() > 0) {
    //         return $query->result_array();
    //     }
    //     return false;
    // }

    public function retrieve_dateWise_SalesReports($outlet_id, $from_date, $to_date, $per_page = null, $page = null)
    {
        if ($outlet_id == 1) {
            $outlet_id = null;
        }
        $this->db->select('a.date,a.invoice,a.invoice_id,a.due_amount,a.changeamount,a.outlet_id,
        (SELECT sum(id.quantity) from invoice_details as id WHERE id.invoice_id = a.invoice_id) as total_sku,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "1" AND paid.invoice_id = a.invoice_id) as total_cash,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "3" AND paid.invoice_id = a.invoice_id) as total_bkash,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "6" AND paid.invoice_id = a.invoice_id) as total_card,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "5" AND paid.invoice_id = a.invoice_id) as total_nagad,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "7" AND paid.invoice_id = a.invoice_id) as total_rocket,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "2" AND paid.invoice_id = a.invoice_id) as total_cheque,
        (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "4" AND paid.invoice_id = a.invoice_id) as total_bank,

        a.total_discount,a.total_amount,a.paid_amount,a.sales_return,a.shipping_cost,a.service_charge,a.invoice,b.customer_id,b.customer_name,pi.sku');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        $this->db->join('paid_amount p', 'p.invoice_id = a.invoice_id', 'left');
        $this->db->join('invoice_details id', 'id.invoice_id = a.invoice_id', 'left');
        $this->db->join('product_information pi', 'pi.product_id = id.product_id', 'left');
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        if ($outlet_id) {
            $this->db->where('a.outlet_id', $outlet_id);
        }

        $this->db->order_by('a.date', 'desc');
        if ($per_page && $page)
            $this->db->limit($per_page, $page);
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get()->result_array();
        $final_array = array();
        // $sl = 1;
        //  echo "<pre>";
        // print_r($query);
        // exit();
        foreach ($query as $key => $value) {
            $test_array[$value['invoice_id']] = $value['invoice_id'];
            $final_array[$value['invoice_id']]['sales_date'] = $value['date'];
            $final_array[$value['invoice_id']]['invoice_id'] = $value['invoice'];
            $final_array[$value['invoice_id']]['invoice'] = $value['invoice_id'];
            $final_array[$value['invoice_id']]['outlet_id'] = $value['outlet_id'];
            $final_array[$value['invoice_id']]['customer_name'] = $value['customer_name'];
            if (array_key_exists($value['invoice_id'], $test_array)) {
                $final_array[$value['invoice_id']]['sku'] = $final_array[$value['invoice_id']]['sku'] . "," . $value['sku'];
                //$final_array[$value['invoice_id']]['sku'] = $this->stringToArray($final_array[$value['invoice_id']]['sku']);
            } else {
                $final_array[$value['invoice_id']]['sku'] = $value['sku'] . ",";
            }
            // if ($value['pay_type'] == 1) {

            //     $final_array[$value['invoice_id']]['cash'] += $value['amount'];
            // }
            // if ($value['pay_type'] == 3) {
            //     $final_array[$value['invoice_id']]['bkash'] += $value['amount'];
            // }
            // if ($value['pay_type'] == 6) {
            //     $final_array[$value['invoice_id']]['card'] += $value['amount'];
            // }
            // if ($value['pay_type'] == 5) {
            //     $final_array[$value['invoice_id']]['nagad'] += $value['amount'];
            // }
            // if ($value['pay_type'] == 7) {
            //     $final_array[$value['invoice_id']]['rocket'] += $value['amount'];
            // }
            $final_array[$value['invoice_id']]['changeamount'] = $value['changeamount'];
            $final_array[$value['invoice_id']]['bkash'] = $value['total_bkash'];
            $final_array[$value['invoice_id']]['cash'] = $value['total_cash'];
            $final_array[$value['invoice_id']]['nagad'] = $value['total_nagad'];
            $final_array[$value['invoice_id']]['card'] = $value['total_card'];
            $final_array[$value['invoice_id']]['rocket'] = $value['total_rocket'];
            $final_array[$value['invoice_id']]['cheque'] = $value['total_cheque'];
            $final_array[$value['invoice_id']]['bank'] = $value['total_bank'];
            $final_array[$value['invoice_id']]['total_amount'] = $value['total_amount'];
            $final_array[$value['invoice_id']]['service_charge'] = $value['service_charge'];
            $final_array[$value['invoice_id']]['shipping_cost'] = $value['shipping_cost'];
            $final_array[$value['invoice_id']]['sales_return'] = $value['sales_return'];
            $final_array[$value['invoice_id']]['due_amount'] = $value['due_amount'];
            $final_array[$value['invoice_id']]['total_discount'] = $value['total_discount'];
            $final_array[$value['invoice_id']]['net_sales'] = $value['total_amount'] - $value['total_discount'];
            $final_array[$value['invoice_id']]['qnty'] = $value['total_sku'];
            $final_array[$value['invoice_id']]['paid_amount'] = $value['paid_amount'];
        }

        if ($query) {
            return array_values($final_array);
        }
        return false;





        // $today = date('Y-m-d');
        // $this->db->select('a.date,a.invoice,a.invoice_id,a.due_amount,a.changeamount,
        // (SELECT sum(id.quantity) from invoice_details as id WHERE id.invoice_id = a.invoice_id) as total_sku,
        // (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "1" AND paid.invoice_id = a.invoice_id) as total_cash,
        // (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "3" AND paid.invoice_id = a.invoice_id) as total_bkash,
        // (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "6" AND paid.invoice_id = a.invoice_id) as total_card,
        // (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "5" AND paid.invoice_id = a.invoice_id) as total_nagad,
        // (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "7" AND paid.invoice_id = a.invoice_id) as total_rocket,
        // (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "2" AND paid.invoice_id = a.invoice_id) as total_cheque,
        // (SELECT sum(paid.amount) from paid_amount as paid WHERE paid.pay_type = "4" AND paid.invoice_id = a.invoice_id) as total_bank,

        // a.total_discount,a.total_amount,a.paid_amount,a.sales_return,a.shipping_cost,a.service_charge,a.invoice,b.customer_id,b.customer_name,pi.sku');
        // $this->db->from('invoice a');
        // $this->db->join('customer_information b', 'b.customer_id = a.customer_id', 'left');
        // $this->db->join('paid_amount p', 'p.invoice_id = a.invoice_id', 'left');
        // $this->db->join('invoice_details id', 'id.invoice_id = a.invoice_id', 'left');
        // $this->db->join('product_information pi', 'pi.product_id = id.product_id', 'left');
        // $this->db->where('a.date', $today);
        // if ($outlet_id) {
        //     $this->db->where('a.outlet_id', $outlet_id);
        // }
        // if ($per_page && $page)
        //     $this->db->limit($per_page, $page);
        // $this->db->order_by('a.invoice', 'desc');
        // $query = $this->db->get()->result_array();
        // $final_array = array();
        // // $sl = 1;
        // //  echo "<pre>";
        // // print_r($query);
        // // exit();
        // foreach ($query as $key => $value) {
        //     $test_array[$value['invoice_id']] = $value['invoice_id'];
        //     $final_array[$value['invoice_id']]['sales_date'] = $value['date'];
        //     $final_array[$value['invoice_id']]['invoice_id'] = $value['invoice'];
        //     $final_array[$value['invoice_id']]['invoice'] = $value['invoice_id'];
        //     $final_array[$value['invoice_id']]['customer_name'] = $value['customer_name'];
        //     if (array_key_exists($value['invoice_id'], $test_array)) {
        //         $final_array[$value['invoice_id']]['sku'] = $final_array[$value['invoice_id']]['sku'] . "," . $value['sku'];
        //         //$final_array[$value['invoice_id']]['sku'] = $this->stringToArray($final_array[$value['invoice_id']]['sku']);
        //     } else {
        //         $final_array[$value['invoice_id']]['sku'] = $value['sku'] . ",";
        //     }
        //     // if ($value['pay_type'] == 1) {

        //     //     $final_array[$value['invoice_id']]['cash'] += $value['amount'];
        //     // }
        //     // if ($value['pay_type'] == 3) {
        //     //     $final_array[$value['invoice_id']]['bkash'] += $value['amount'];
        //     // }
        //     // if ($value['pay_type'] == 6) {
        //     //     $final_array[$value['invoice_id']]['card'] += $value['amount'];
        //     // }
        //     // if ($value['pay_type'] == 5) {
        //     //     $final_array[$value['invoice_id']]['nagad'] += $value['amount'];
        //     // }
        //     // if ($value['pay_type'] == 7) {
        //     //     $final_array[$value['invoice_id']]['rocket'] += $value['amount'];
        //     // }
        //     $final_array[$value['invoice_id']]['changeamount'] = $value['changeamount'];
        //     $final_array[$value['invoice_id']]['bkash'] = $value['total_bkash'];
        //     $final_array[$value['invoice_id']]['cash'] = $value['total_cash'];
        //     $final_array[$value['invoice_id']]['nagad'] = $value['total_nagad'];
        //     $final_array[$value['invoice_id']]['card'] = $value['total_card'];
        //     $final_array[$value['invoice_id']]['rocket'] = $value['total_rocket'];
        //     $final_array[$value['invoice_id']]['cheque'] = $value['total_cheque'];
        //     $final_array[$value['invoice_id']]['bank'] = $value['total_bank'];
        //     $final_array[$value['invoice_id']]['total_amount'] = $value['total_amount'];
        //     $final_array[$value['invoice_id']]['service_charge'] = $value['service_charge'];
        //     $final_array[$value['invoice_id']]['shipping_cost'] = $value['shipping_cost'];
        //     $final_array[$value['invoice_id']]['sales_return'] = $value['sales_return'];
        //     $final_array[$value['invoice_id']]['due_amount'] = $value['due_amount'];
        //     $final_array[$value['invoice_id']]['total_discount'] = $value['total_discount'];
        //     $final_array[$value['invoice_id']]['net_sales'] = $value['total_amount'] - $value['total_discount'];
        //     $final_array[$value['invoice_id']]['qnty'] = $value['total_sku'];
        //     $final_array[$value['invoice_id']]['paid_amount'] = $value['paid_amount'];
        // }
    }



    //due report
    //Retrieve all Report
    public function retrieve_dateWise_DueReports($from_date, $to_date, $per_page, $page, $outlet_id = null)
    {
        $this->db->select("a.*,b.*,c.*");
        $this->db->from('invoice a');
        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        if ($outlet_id) {
            $this->db->where('a.outlet_id', $outlet_id);
        }
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->group_by('a.invoice_id');
        $this->db->order_by('a.invoice', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // count sales report data
    public function count_retrieve_dateWise_SalesReports($from_date, $to_date)
    {
        $this->db->select("a.*,b.*");
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }




    //Retrieve all Report
    public function retrieve_dateWise_PurchaseReports($start_date, $end_date, $outlet_id, $supplier_id, $per_page = null, $page = null)
    {

        // echo $outlet_id;exit();
        if ($outlet_id) {
            $this->db->where('a.outlet_id', $outlet_id);
        }
        if ($supplier_id) {
            $this->db->where('a.supplier_id', $supplier_id);
        }

        $this->db->select("a.*,b.supplier_id,b.supplier_name,c.outlet_name");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id', 'left');
        $this->db->join('outlet_warehouse c', 'c.outlet_id = a.outlet_id', 'left');
        $this->db->where('a.purchase_date >=', $start_date);
        $this->db->where('a.purchase_date <=', $end_date);

        $this->db->group_by('a.purchase_id');
        $this->db->order_by('a.purchase_date', 'desc');
        if ($per_page && $page)
            $this->db->limit(
                $per_page,
                $page
            );
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_warrenty_dateWise_PurchaseReports($start_date, $end_date, $per_page, $page)
    {

        $this->db->select("a.*,b.*,c.*,d.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        //  $this->db->join('product_information c', 'c.product_id = a.product_id');
        $this->db->where('c.warrenty_date >=', $start_date);
        $this->db->where('c.warrenty_date <=', $end_date);
        $this->db->group_by('a.purchase_id');
        $this->db->order_by('a.purchase_date', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_expiry_dateWise_PurchaseReports($start_date, $end_date, $per_page, $page)
    {

        $this->db->select("a.*,b.*,c.*,d.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        //  $this->db->join('product_information c', 'c.product_id = a.product_id');
        $this->db->where('c.expired_date >=', $start_date);
        $this->db->where('c.expired_date <=', $end_date);
        $this->db->group_by('a.purchase_id');
        $this->db->order_by('a.purchase_date', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_cheque_dateWise_PurchaseReports($start_date, $end_date, $per_page, $page)
    {

        $this->db->select("a.*,b.*,c.*,d.*,e.bank_name");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->join('bank_add e', 'e.bank_id = a.bank_id');
        //  $this->db->join('product_information c', 'c.product_id = a.product_id');
        $this->db->where('a.cheque_date >=', $start_date);
        $this->db->where('a.cheque_date <=', $end_date);
        $this->db->group_by('a.purchase_id');
        $this->db->order_by('a.purchase_date', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // count purchase report data
    public function count_retrieve_dateWise_PurchaseReports($start_date, $end_date, $outlet_id, $supplier_id)
    {

        if ($outlet_id) {
            $this->db->where('a.outlet_id', $outlet_id);
        }

        if ($supplier_id) {
            $this->db->where('a.supplier_id', $supplier_id);
        }
        $this->db->select("a.*,b.supplier_id,b.supplier_name");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('a.purchase_date >=', $start_date);
        $this->db->where('a.purchase_date <=', $end_date);
        $this->db->group_by('a.purchase_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }
    public function count_retrieve_expiry_dateWise_PurchaseReports($start_date, $end_date)
    {
        $this->db->select("a.*,b.*,c.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->where('c.expired_date >=', $start_date);
        $this->db->where('c.expired_date <=', $end_date);
        $this->db->group_by('a.purchase_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }
    public function count_retrieve_cheque_dateWise_PurchaseReports($start_date, $end_date)
    {
        $this->db->select("a.*,b.*,c.*");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('product_purchase_details c', 'c.purchase_id = a.purchase_id');
        $this->db->join('bank_add d', 'd.bank_id = a.bank_id');
        $this->db->where('a.cheque_date >=', $start_date);
        $this->db->where('a.cheque_date <=', $end_date);
        $this->db->group_by('a.purchase_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    //Retrieve date wise profit report
    public function retrieve_dateWise_profit_report($outlet_id, $start_date, $end_date, $per_page = null, $page = null)
    {
        if ($outlet_id == 1) {
            $outlet_id = null;
        }
        $this->db->select("a.date,a.invoice,b.invoice_id,
            CAST(a.total_amount AS DECIMAL(16,2)) as total_sale, o.outlet_name");
        $this->db->select('CAST(sum(`quantity`*`supplier_rate`) AS DECIMAL(16,2)) as total_supplier_rate', FALSE);
        $this->db->select("CAST(a.total_amount - SUM(`quantity`*`supplier_rate`) AS DECIMAL(16,2)) AS total_profit");

        $this->db->from('invoice a');
        $this->db->join('invoice_details b', 'b.invoice_id = a.invoice_id');
        $this->db->join('outlet_warehouse o', 'a.outlet_id = o.outlet_id');
        $this->db->where('a.date >=', $start_date);
        $this->db->where('a.date <=', $end_date);
        if ($outlet_id)
            $this->db->where('a.outlet_id ', $outlet_id);

        $this->db->group_by('b.invoice_id');
        $this->db->order_by('a.invoice', 'desc');
        if ($per_page && $page)
            $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Retrieve date wise profit report
    public function retrieve_dateWise_profit_report_count($outlet_id, $start_date, $end_date)
    {
        $this->db->select("a.date,a.invoice,b.invoice_id,a.total_amount as total_sale");
        $this->db->select('sum(`quantity`*`supplier_rate`) as total_supplier_rate', FALSE);
        $this->db->select("(a.total_amount - SUM(`quantity`*`supplier_rate`)) AS total_profit");
        $this->db->from('invoice a');
        $this->db->join('invoice_details b', 'b.invoice_id = a.invoice_id');
        $this->db->where('a.date >=', $start_date);
        $this->db->where('a.date <=', $end_date);
        $this->db->where('a.outlet_id ', $outlet_id);
        $this->db->group_by('b.invoice_id');
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }

    //Product wise sales report
    public function product_wise_report()
    {
        $today = date('Y-m-d');
        $this->db->select("a.*,b.customer_id,b.customer_name");
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.date', $today);
        $this->db->order_by('a.invoice_id', 'desc');
        $this->db->limit('500');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //RETRIEVE DATE WISE SINGE PRODUCT REPORT
    public function retrieve_product_sales_report($perpage = null, $page = null)
    {

        $user_id = $this->session->userdata('user_id');

        $this->db->select("a.*,b.product_name,b.product_model,b.sku,c.date,c.invoice,c.total_amount,d.customer_name, sz.size_name, cl.color_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        $this->db->join('outlet_warehouse x', 'x.outlet_id = c.outlet_id');
        $this->db->join('size_list sz', 'b.size=sz.size_id', 'left');
        $this->db->join('color_list cl', 'b.color=cl.color_id', 'left');
        $this->db->where('x.user_id', $user_id);
        $this->db->order_by('c.date', 'desc');
        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_product_pre_sales_report($perpage = null, $page = null)
    {

        $user_id = $this->session->userdata('user_id');

        $this->db->select("a.*,b.product_name,b.product_model,b.sku,c.date,c.invoice,c.total_amount");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('outlet_warehouse x', 'x.outlet_id = c.outlet_id');
        $this->db->where('x.user_id', $user_id);
        $this->db->order_by('c.date', 'desc');
        $this->db->group_by('a.product_id');
        $this->db->where('a.pre_order', 2);
        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_product_purchase_report($perpage = null, $page = null)
    {
        $user_id = $this->session->userdata('user_id');

        $this->db->select("a.*,b.product_name,b.product_model,c.date,c.invoice,c.total_amount,d.customer_name, sz.size_name, cl.color_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        $this->db->join('outlet_warehouse x', 'x.outlet_id = c.outlet_id');
        $this->db->join('size_list sz', 'b.size=sz.size_id', 'left');
        $this->db->join('color_list cl', 'b.color=cl.color_id', 'left');
        $this->db->where('x.user_id', $user_id);
        $this->db->order_by('c.date', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function supplier_report($perpage, $page)
    {
        $this->db->select("a.*,b.*,c.*,d.*,e.*");
        $this->db->from('product_purchase a');
        $this->db->join('product_purchase_details b', 'b.purchase_id = a.purchase_id');
        $this->db->join('supplier_information c', 'c.supplier_id = a.supplier_id');
        $this->db->join('product_information d', 'd.product_id = b.product_id');
        $this->db->join('product_category e', 'e.category_id = d.category_id');
        // $this->db->group_by('d.product_id');
        //        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        //        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        //        $this->db->order_by('c.date', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function reorder_report($perpage, $page)
    {
        //$this->db->select ("sum(quantity) from product_purchase_details where product_id= product_information.product_id");
        $this->db->select("a.*,b.*,((select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`)-(select sum(quantity) from invoice_details where product_id= `a`.`product_id`)) as stock");
        //  $this->db->select("a.*,b.*");
        // $this->db->select("a.*,b.*,((select sum(quantity) from product_purchase_details)-(invoice_details.sum(quantity) from invoice_details)) as stock ");
        $this->db->from('product_information a');
        $this->db->join('product_category b', 'b.category_id = a.category_id');
        $this->db->join('invoice_details ', 'invoice_details.product_id = a.product_id');
        $this->db->join('product_purchase_details ', 'product_purchase_details.product_id = a.product_id');
        $this->db->group_by('a.product_id');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return false;
    }


    public function retrieve_sales_warrenty_reports($perpage, $page)
    {
        $this->db->select("a.*,b.product_name,b.product_model,c.date,c.invoice,c.total_amount,d.customer_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        $this->db->order_by('c.date', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_sales_cheque_reports($perpage, $page)
    {
        //        $this->db->select("c.*,e.invoice_id,d.customer_name");
        //        $this->db->from('cus_cheque c');
        //        //  $this->db->join('bank_add e', 'e.bank_id = c.bank_id');
        //        $this->db->join('invoice e', 'e.invoice_id = c.invoice_id');
        //        $this->db->join('customer_information d', 'd.customer_id = e.customer_id');
        //        //$this->db->where('c.status', 2);
        //       // $this->db->where('c.payment_type', 2);
        //        $this->db->order_by('c.cheque_date', 'desc');
        //        $this->db->limit($perpage, $page);
        //        $query = $this->db->get();
        //        if ($query->num_rows() > 0) {
        //            return $query->result_array();
        //        }
        //        return false;
    }

    public  function update_cheque_date($id)
    {

        // Update
        $data = array($field => $value);
        $this->db->where('invoice_id', $id);
        $this->db->update('invoice', $data);
    }



    //RETRIEVE DATE WISE SINGE PRODUCT REPORT
    public function retrieve_product_sales_report_count()
    {
        $this->db->select("a.*,b.product_name,b.product_model,c.date,c.total_amount,d.customer_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        $this->db->order_by('c.date', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function retrieve_product_purchase_report_count()
    {
        $this->db->select("a.*,b.*,c.purchase_date,c.grand_total_amount,c.rn_number");
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_purchase c', 'c.purchase_id = a.purchase_id');
        //$this->db->order_by('c.date', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function supplier_report_count()
    {
        $this->db->select("a.*,b.*,c.*,d.*,e.*");
        $this->db->from('product_purchase a');
        $this->db->join('product_purchase_details b', 'b.purchase_id = a.purchase_id');
        $this->db->join('supplier_information c', 'c.supplier_id = a.supplier_id');
        $this->db->join('product_information d', 'd.product_id = b.product_id');
        $this->db->join('product_category e', 'e.category_id = d.category_id');
        //$this->db->group_by('d.product_id');
        //        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        //        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        // $this->db->order_by('c.date', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function reorder_report_count()
    {

        $this->db->select("a.*,b.*,((select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`)-(select sum(quantity) from invoice_details where product_id= `a`.`product_id`)) as stock");
        // $this->db->select("a.*,b.*,(select sum(quantity) from invoice_details where product_id=a.product_id) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= a.product_id) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->join('invoice_details', 'invoice_details.product_id = a.product_id');
        $this->db->join('product_purchase_details', 'product_purchase_details.product_id = a.product_id');
        $this->db->join('product_category b', 'b.category_id = a.category_id');
        $this->db->group_by('a.product_id');

        $query = $this->db->get();
        //        $stockin = $this->db->select('sum(quantity) as totalSalesQnty')->from('invoice_details')->where('product_id',$query->product_id)->get()->row();
        //        $stockout = $this->db->select('sum(qty) as totalPurchaseQnty')->from('product_purchase_details')->where('product_id',$query->product_id)->get()->row();
        //        $stock =  (!empty($stockout->totalPurchaseQnty)?$stockout->totalPurchaseQnty:0)-(!empty($stockin->totalSalesQnty)?$stockin->totalSalesQnty:0);
        return $query->num_rows();
    }
    public function retrieve_product_sales_cheque_report_count()
    {
        //        $this->db->select("a.*,b.product_name,b.product_model,c.date,c.total_amount,c.cheque_no,c.cheque_no,d.customer_name,e.bank_name");
        //        $this->db->from('invoice_details a');
        //        $this->db->join('product_information b', 'b.product_id = a.product_id');
        //        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        //        $this->db->join('bank_add e', 'e.bank_id = c.bank_id');
        //        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        //        $this->db->order_by('c.cheque_date', 'desc');
        //        $query = $this->db->get();
        //        return $query->num_rows();F
    }


    //RETRIEVE DATE WISE SEARCH SINGLE PRODUCT REPORT
    public function retrieve_product_search_sales_report($outlet_id, $start_date, $end_date, $product_id, $cat_id, $perpage = null, $page = null)
    {
        $this->db->select("a.*,b.product_name,b.sku,b.category_id,c.invoice,c.date,d.customer_name, sz.size_name, cl.color_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id', 'left');

        if ($outlet_id) {
            $this->db->where('c.outlet_id', $outlet_id);
        }

        if ($product_id) {
            $this->db->where('b.product_id', $product_id);
        }
        if ($cat_id) {
            $this->db->like('b.category_id', $cat_id);
        }
        $this->db->where('c.date >=', $start_date);
        $this->db->where('c.date <=', $end_date);
        $this->db->join('size_list sz', 'b.size=sz.size_id', 'left');
        $this->db->join('color_list cl', 'b.color=cl.color_id', 'left');
        $this->db->order_by('c.date', 'desc');
        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_product_search_pre_sales_report($outlet_id, $start_date, $end_date, $product_id, $cat_id, $perpage = null, $page = null)
    {
        $this->db->select("a.*,b.product_name,b.sku,b.category_id,c.invoice,c.date");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');

        if ($outlet_id) {
            $this->db->where('c.outlet_id', $outlet_id);
        }

        if ($product_id) {
            $this->db->where('b.product_id', $product_id);
        }
        if ($cat_id) {
            $this->db->like('b.category_id', $cat_id);
        }
        $this->db->where('c.date >=', $start_date);
        $this->db->where('c.date <=', $end_date);
        $this->db->where('a.pre_order', 2);
        $this->db->order_by('c.date', 'desc');
        $this->db->group_by('a.product_id');
        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_product_search_purchase_report($outlet_id, $start_date, $end_date, $product_id, $perpage = null, $page = null)
    {
        $this->db->select("a.*,b.product_name,b.sku,c.purchase_id,c.purchase_date,d.supplier_name, sz.size_name, cl.color_name");
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_purchase c', 'c.purchase_id = a.purchase_id');
        $this->db->join('supplier_information d', 'd.supplier_id = c.supplier_id', 'left');

        if ($outlet_id) {
            $this->db->where('c.outlet_id', $outlet_id);
        }

        if ($product_id) {
            $this->db->where('b.product_id', $product_id);
        }
        $this->db->where('c.purchase_date >=', $start_date);
        $this->db->where('c.purchase_date <=', $end_date);
        $this->db->join('size_list sz', 'b.size=sz.size_id', 'left');
        $this->db->join('color_list cl', 'b.color=cl.color_id', 'left');
        $this->db->order_by('c.purchase_date', 'desc');
        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_product_search_produce_report($outlet_id, $start_date, $end_date, $product_id, $perpage = null, $page = null)
    {
        $this->db->select("a.*,b.product_name,b.sku,c.pro_id,c.base_number,c.date, sz.size_name, cl.color_name");
        $this->db->from('production_goods a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('production c', 'c.pro_id = a.pro_id');
        //        $this->db->join('supplier_information d', 'd.supplier_id = c.supplier_id','left');

        //        if ($outlet_id) {
        //            $this->db->where('c.outlet_id', $outlet_id);
        //        }

        if ($product_id) {
            $this->db->where('b.product_id', $product_id);
        }
        $this->db->where('c.date >=', $start_date);
        $this->db->where('c.date <=', $end_date);
        $this->db->join('size_list sz', 'b.size=sz.size_id', 'left');
        $this->db->join('color_list cl', 'b.color=cl.color_id', 'left');
        $this->db->order_by('c.date', 'desc');
        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_product_id_search_sales_report($customer_id, $product_id, $perpage, $page)
    {
        $this->db->select("a.*,b.product_name,b.product_model,c.invoice,c.date,d.customer_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        if ($customer_id) {
            $this->db->where('c.customer_id', $customer_id);
        }
        if ($product_id) {
            $this->db->where('a.product_id', $product_id);
            $this->db->or_where('a.product_id_two', $product_id);
        }
        if ($customer_id && $product_id) {
            $this->db->where('c.customer_id', $customer_id);
            $this->db->where('a.product_id', $product_id);
            $this->db->or_where('a.product_id_two', $product_id);
        }
        //  $this->db->where('d.customer_id', $customer_id);
        //  $this->db->where('b.product_id', $product_id);

        //        $this->db->where('c.date >=', $start_date);
        //        $this->db->where('c.date <=', $end_date);
        $this->db->order_by('c.date', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_supplier_id_report($supplier_id, $perpage, $page)
    {
        $this->db->select("a.*,b.*,c.*,d.*,e.*");
        $this->db->from('product_purchase a');
        $this->db->join('product_purchase_details b', 'b.purchase_id = a.purchase_id');
        $this->db->join('supplier_information c', 'c.supplier_id = a.supplier_id');
        $this->db->join('product_information d', 'd.product_id = b.product_id');
        $this->db->join('product_category e', 'e.category_id = d.category_id');
        $this->db->where('a.supplier_id', $supplier_id);
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_purchase_product_id_report($supplier_id, $perpage, $page)
    {
        $this->db->select("a.*,b.*,c.purchase_date,c.grand_total_amount,c.rn_number");
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_purchase c', 'c.purchase_id = a.purchase_id');;
        $this->db->where('a.product_id', $supplier_id);
        $this->db->or_where('a.product_id_two', $supplier_id);
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_sales_product_id_report($customer_id, $supplier_id, $perpage, $page)
    {
        $this->db->select("a.*,b.product_name,b.product_model,c.date,c.total_amount,c.invoice,d.customer_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        $this->db->order_by('c.date', 'desc');
        if ($customer_id) {
            $this->db->where('c.customer_id', $customer_id);
        }
        if ($supplier_id) {
            $this->db->where('a.product_id', $supplier_id);
            $this->db->or_where('a.product_id_two', $supplier_id);
        }
        if ($customer_id && $supplier_id) {
            $this->db->where('c.customer_id', $customer_id);
            $this->db->where('a.product_id', $supplier_id);
            $this->db->or_where('a.product_id_two', $supplier_id);
        }


        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_reorder_report($supplier_id, $perpage, $page)
    {
        $this->db->select("a.*,b.*,((select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`)-(select sum(quantity) from invoice_details where product_id= `a`.`product_id`)) as stock");
        // $this->db->select("a.*,b.*,(select sum(quantity) from invoice_details where product_id=a.product_id) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= a.product_id) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->join('invoice_details', 'invoice_details.product_id = a.product_id');
        $this->db->join('product_purchase_details', 'product_purchase_details.product_id = a.product_id');
        $this->db->join('product_category b', 'b.category_id = a.category_id');
        $this->db->group_by('a.product_id');
        $this->db->where('a.re_order_level', $supplier_id);

        //        if('stock' <= $reorder){
        //            $this->db->limit($perpage, $page);
        //            $query = $this->db->get();
        //            if ($query->num_rows() > 0) {
        //                return $query->result_array();
        //            }
        //
        //        }
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return false;

        //    $this->db->where('a.stock', $supplier_id);


    }


    //RETRIEVE DATE WISE SEARCH SINGLE PRODUCT REPORT
    public function retrieve_product_search_sales_report_count($start_date, $end_date, $product_id)
    {
        $this->db->select("a.*,b.product_name,b.product_model,c.date,d.customer_name, sz.size_name, cl.color_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id', 'left');
        $this->db->where('b.product_id', $product_id);
        $this->db->where('c.date >=', $start_date);
        $this->db->where('c.date <=', $end_date);
        $this->db->join('size_list sz', 'b.size=sz.size_id', 'left');
        $this->db->join('color_list cl', 'b.color=cl.color_id', 'left');
        $this->db->order_by('c.date', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function retrieve_product_search_purchase_report_count($start_date, $end_date, $product_id)
    {
        $this->db->select("a.*,b.product_name,b.product_model,c.date,d.customer_name, sz.size_name, cl.color_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id', 'left');
        $this->db->where('b.product_id', $product_id);
        $this->db->where('c.date >=', $start_date);
        $this->db->where('c.date <=', $end_date);
        $this->db->join('size_list sz', 'b.size=sz.size_id', 'left');
        $this->db->join('color_list cl', 'b.color=cl.color_id', 'left');
        $this->db->order_by('c.date', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function retrieve_product_id_search_sales_report_count($customer_id, $product_id)
    {
        $this->db->select("a.*,b.product_name,b.product_model,c.date,d.customer_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        if ($customer_id) {
            $this->db->where('c.customer_id', $customer_id);
        }
        if ($product_id) {
            $this->db->where('a.product_id', $product_id);
            $this->db->or_where('a.product_id_two', $product_id);
        }
        if ($customer_id && $product_id) {
            $this->db->where('c.customer_id', $customer_id);
            $this->db->where('a.product_id', $product_id);
            $this->db->or_where('a.product_id_two', $product_id);
        }
        //        $this->db->where('d.customer_id',$customer_id);
        //        $this->db->where('b.product_id',$product_id);

        //        $this->db->where('c.date >=', $start_date);
        //        $this->db->where('c.date <=', $end_date);
        $this->db->order_by('c.date', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function retrieve_supplier_id_count($supplier_id)
    {
        $this->db->select("a.*,b.*,c.*,d.*,e.*");
        $this->db->from('product_purchase a');
        $this->db->join('product_purchase_details b', 'b.purchase_id = a.purchase_id');
        $this->db->join('supplier_information c', 'c.supplier_id = a.supplier_id');
        $this->db->join('product_information d', 'd.product_id = b.product_id');
        $this->db->join('product_category e', 'e.category_id = d.category_id');
        $this->db->where('a.supplier_id', $supplier_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function retrieve_purchase_product_id_count($supplier_id)
    {
        $this->db->select("a.*,b.*,c.purchase_date,c.grand_total_amount,c.rn_number");
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_purchase c', 'c.purchase_id = a.purchase_id');;
        $this->db->where('a.product_id', $supplier_id);
        $this->db->or_where('a.product_id_two', $supplier_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function retrieve_sales_product_id_count($customer_id, $supplier_id)
    {
        $this->db->select("a.*,b.product_name,b.product_model,c.date,c.total_amount,c.invoice,d.customer_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        $this->db->order_by('c.date', 'desc');
        if ($customer_id) {
            $this->db->where('c.customer_id', $customer_id);
        }
        if ($supplier_id) {
            $this->db->where('a.product_id', $supplier_id);
            $this->db->or_where('a.product_id_two', $supplier_id);
        }
        if ($customer_id && $supplier_id) {
            $this->db->where('c.customer_id', $customer_id);
            $this->db->where('a.product_id', $supplier_id);
            $this->db->or_where('a.product_id_two', $supplier_id);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function reorder_count($supplier_id)
    {
        $this->db->select("a.*,b.*,((select sum(quantity) from product_purchase_details where product_id= `a`.`product_id`)-(select sum(quantity) from invoice_details where product_id= `a`.`product_id`)) as stock");
        // $this->db->select("a.*,b.*,(select sum(quantity) from invoice_details where product_id=a.product_id) as 'totalSalesQnty',(select sum(quantity) from product_purchase_details where product_id= a.product_id) as 'totalBuyQnty'");
        $this->db->from('product_information a');
        $this->db->join('invoice_details', 'invoice_details.product_id = a.product_id');
        $this->db->join('product_purchase_details', 'product_purchase_details.product_id = a.product_id');
        $this->db->join('product_category b', 'b.category_id = a.category_id');
        $this->db->group_by('a.product_id');
        $this->db->where('a.re_order_level', $supplier_id);
        // $this->db->where($data->stock, $supplier_id);
        //        if('stock' <= $reorder){
        //            $query = $this->db->get();
        //
        //        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    //RETRIEVE DATE WISE SEARCH SINGLE PRODUCT REPORT
    public function retrieve_product_search_sales_warrenty_report($start_date, $end_date, $product_id, $perpage, $page)
    {
        $this->db->select("a.*,b.product_name,b.product_model,d.customer_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        //$this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');

        $this->db->where('a.warrenty_date >=', $start_date);
        $this->db->where('a.warrenty_date <=', $end_date);
        $this->db->order_by('a.warrenty_date', 'desc');
        $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_product_search_sales_cheque_report_count($start_date, $end_date)
    {
        $this->db->select("c.*,e.invoice_id,e.bank_id,e.payment_type,e.customer_id,d.customer_name");
        $this->db->from('cus_cheque c');
        $this->db->join('invoice e', 'e.invoice_id = c.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = e.customer_id');
        $this->db->where('c.status', 2);
        $this->db->where('e.payment_type', 2);
        $this->db->where('c.cheque_date >=', $start_date);
        $this->db->where('c.cheque_date <=', $end_date);
        $this->db->order_by('c.cheque_date', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function retrieve_product_search_sales_cheque_report($from_date, $to_date)
    {

        $this->db->select("c.*,e.invoice_id,e.bank_id,e.payment_type,e.customer_id,d.customer_name");
        $this->db->from('cus_cheque c');
        $this->db->join('invoice e', 'e.invoice_id = c.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = e.customer_id');
        $this->db->where('c.status', 2);
        $this->db->where('e.payment_type', 2);
        $this->db->where('c.cheque_date >=', $from_date);
        $this->db->where('c.cheque_date <=', $to_date);
        $this->db->order_by('c.cheque_date', 'desc');
        //   $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function getChequeList($postData = null)
    {
        $this->load->library('occational');
        $this->load->model('Web_settings');
        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $response = array();
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        $fromdate = $postData['fromdate'];
        $todate   = $postData['todate'];
        $customer_id   = $postData['customer_id'];
        if (!empty($fromdate)) {
            $datbetween = "(c.cheque_date BETWEEN '$fromdate' AND '$todate')";
        } else if (!empty($customer_id)) {

            $customer = "(d.customer_id)";
        } else {
            $datbetween = "";
        }
        ## Read value

        ## Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (c.invoice_id like '%" . $searchValue . "%' or d.customer_name like '%" . $searchValue . "%' or d.customer_id like '%" . $searchValue . "%' or e.bank_id like '%" . $searchValue . "%' or c.cheque_date like'%" . $searchValue . "%'or c.cheque_no like'%" . $searchValue . "%'or c.status like'%" . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        // $this->db->select("c.*,e.invoice_id,e.bank_id,e.payment_type,d.customer_name");
        $this->db->from('cus_cheque c');
        //$this->db->join('bank_add e', 'e.bank_id = c.bank_id');
        $this->db->join('invoice e', 'e.invoice_id = c.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = e.customer_id');
        $this->db->where('c.status', 2);
        // $this->db->where('e.payment_type', 2);
        $this->db->order_by('c.cheque_date', 'desc');
        if (!empty($fromdate) && !empty($todate)) {
            $this->db->where($datbetween);
        }
        if ($searchValue != '')
            $this->db->where($searchQuery);

        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        //    $this->db->select("c.*,e.invoice_id,e.bank_id,e.payment_type,d.customer_name");
        $this->db->from('cus_cheque c');
        //$this->db->join('bank_add e', 'e.bank_id = c.bank_id');
        $this->db->join('invoice e', 'e.invoice_id = c.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = e.customer_id');
        $this->db->where('c.status', 2);
        $this->db->where('e.payment_type', 2);
        $this->db->order_by('c.cheque_date', 'desc');
        if (!empty($customer_id)) {
            $this->db->where('d.customer_id', $customer_id);
        }
        if (!empty($fromdate) && !empty($todate)) {
            $this->db->where($datbetween);
        }
        if (!empty($fromdate) && !empty($todate) && !empty($customer_id)) {
            $this->db->where($datbetween);
            $this->db->where('d.customer_id', $customer_id);
        }


        if ($searchValue != '')
            $this->db->where($searchQuery);

        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("c.*,e.invoice_id,e.bank_id,d.customer_name");
        $this->db->from('cus_cheque c');
        //$this->db->join('bank_add e', 'e.bank_id = c.bank_id');
        $this->db->join('invoice e', 'e.invoice_id = c.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = e.customer_id');
        $this->db->where('c.status', 2);
        // $this->db->where('e.payment_type', 2);+
        $this->db->order_by('c.cheque_date', 'desc');
        if (!empty($customer_id)) {
            $this->db->where('d.customer_id', $customer_id);
        }
        if (!empty($fromdate) && !empty($todate)) {
            $this->db->where($datbetween);
        }
        if (!empty($fromdate) && !empty($todate) && !empty($customer_id)) {
            $this->db->where($datbetween);
            $this->db->where('d.customer_id', $customer_id);
        }
        if ($searchValue != '')
            $this->db->where($searchQuery);

        // $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;
        foreach ($records as $r) {

            //            $nestedData['invoice_id']=$r->invoice_id;
            //            // $nestedData['cheque_id']=$r->cheque_id;
            //            $nestedData['customer_name']=$r->customer_name;
            //            $nestedData['bank_id']=$r->bank_id;
            //            $nestedData['cheque_no']=$r->cheque_no;
            //
            if ($r->status == 2) {
                $status = '<div style="color: red"><b>Pending</b></div>';
            }
            //            $nestedData['status']=$status;
            //            $nestedData['cheque_date']=$r->cheque_date;
            //          $nestedData['action']='<div><a href="javascript:;" class="glyphicon glyphicon-edit date-edit" data="'.$r->cheque_id.'" style="font-size:22px; color: #1bc9f5"></a></div>';
            $action = '<div><a href="javascript:;" class="glyphicon glyphicon-edit date-edit" data="' . $r->cheque_id . '" style="font-size:22px; color: #1bc9f5"></a></div>';
            //            $data[] = $nestedData;
            $image = '<img src="' . $r->image . '" class="img img-responsive" height="100" width="150">';
            $data[] = array(
                'sl'  =>   $sl++,
                'invoice_id'  =>   $r->invoice_id,
                'customer_name' =>   $r->customer_name,
                'bank_id'  => $r->bank_name,
                'cheque_type' =>  $r->cheque_type,
                'cheque_no' =>  $r->cheque_no,
                'status' => $status,
                'image' => $image,
                'cheque_date' => $r->cheque_date,
                'amount' => number_format($r->amount, 2, '.', ','),
                'action' => $action


            );
        }

        // echo '<pre>';
        // print_r($data);
        // exit();

        ## Response
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $data
        );

        //echo '<pre>';print_r($result);

        return $response;
    }

    //RETRIEVE DATE WISE SEARCH SINGLE PRODUCT REPORT
    public function retrieve_product_search_sales_warrenty_report_count($start_date, $end_date, $product_id)
    {
        //        $this->db->select("a.*,b.product_name,b.product_model,c.date,d.customer_name");
        //        $this->db->from('invoice_details a');
        //        $this->db->join('product_information b', 'b.product_id = a.product_id');
        //        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        //        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        //
        //        $this->db->where('a.warrenty_date >=', $start_date);
        //        $this->db->where('a.warrenty_date <=', $end_date);
        //        $this->db->order_by('a.warrenty_date', 'desc');
        //        $query = $this->db->get();
        //        return $query->num_rows();
    }


    public function product_list()
    {
        $this->db->select('*');
        $this->db->from('product_information a');
        $this->db->join('size_list sz', 'a.size=sz.size_id', 'left');
        $this->db->join('color_list cl', 'a.color=cl.color_id', 'left');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function customer_list()
    {
        $this->db->select('*');
        $this->db->from('customer_information');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function supplier_list()
    {
        $this->db->select('*');
        $this->db->from('supplier_information');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Retrieve company Edit Data
    public function retrieve_company()
    {
        $this->db->select('*');
        $this->db->from('company_information');
        $this->db->limit('1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // date to date product stock report



    //BANKING ENTRY
    public function daily_closing_entry($data)
    {
        $this->db->insert('daily_closing', $data);
    }

    // This function will find out all closing information of daily closing.
    public function accounts_closing_data()
    {
        $last_closing_amount = $this->get_last_closing_amount();
        $cash_in = $this->cash_data_receipt();
        $cash_out = $this->cash_data();
        if ($last_closing_amount != null) {
            $last_closing_amount = $last_closing_amount[0]['amount'];
            $cash_in_hand = ($last_closing_amount + $cash_in) - $cash_out;
        } else {
            $last_closing_amount = 0;
            $cash_in_hand = $cash_in - $cash_out;
        }

        $company_info = $this->Reports->retrieve_company();
        return array(
            "last_day_closing" => number_format($last_closing_amount, 2, '.', ','),
            "cash_in"          => number_format($cash_in, 2, '.', ','),
            "cash_out"         => number_format($cash_out, 2, '.', ','),
            "company_info"     => $company_info,
            "cash_in_hand"     => number_format($cash_in_hand, 2, '.', ',')
        );
    }
    public function get_last_closing_amount($outlet_id = null)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_list = $CI->Warehouse->get_outlet_user();
        $outlet_id = $outlet_list[0]['outlet_id'];

        $this->db->select('amount')
            ->from('daily_closing')
            ->order_by('date', 'desc')
            ->limit(1);

        if ($outlet_id) {
            $this->db->where('outlet_id', $outlet_id);
        }

        $query = $this->db->get();

        // $sql = "SELECT amount FROM daily_closing WHERE date = (SELECT MAX(date) FROM daily_closing)";
        // $query = $this->db->query($sql);
        $result = $query->result_array();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

    public function cash_data_receipt()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_list = $CI->Warehouse->get_outlet_user();
        $outlet_id = $outlet_list[0]['outlet_id'];

        if (!$outlet_id) {
            $cw_id = 'HK7TGDT69VFMXB7'; //central warehouse
        }
        //-----------
        $cash = 0;
        $datse = date('Y-m-d');
        $this->db->select('sum(Debit) as amount');
        $this->db->from('acc_transaction');
        $this->db->where('COAID', 1020101);
        $this->db->where('VDate', $datse);
        if ($outlet_id) {
            $this->db->join('outlet_warehouse', 'outlet_warehouse.user_id = acc_transaction.CreateBy');
            $this->db->where('outlet_warehouse.outlet_id', $outlet_id);
        } else {
            $this->db->join('central_warehouse', 'central_warehouse.warehouse_id = acc_transaction.CreateBy');
            $this->db->where('central_warehouse.warehouse_id', $cw_id);
        }
        $result_amount = $this->db->get();
        $amount = $result_amount->result_array();
        $cash += $amount[0]['amount'];
        return $cash;
    }
    public function cash_data()
    {

        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_list = $CI->Warehouse->get_outlet_user();
        $outlet_id = $outlet_list[0]['outlet_id'];

        if (!$outlet_id) {
            $cw_id = 'HK7TGDT69VFMXB7'; //central warehouse
        }
        //-----------
        $cash = 0;
        $datse = date('Y-m-d');
        $this->db->select('sum(Credit) as amount');
        $this->db->from('acc_transaction');
        $this->db->where('COAID', 1020101);
        $this->db->where('VDate', $datse);
        if ($outlet_id) {
            $this->db->join('outlet_warehouse', 'outlet_warehouse.user_id = acc_transaction.CreateBy');
            $this->db->where('outlet_warehouse.outlet_id', $outlet_id);
        } else {
            $this->db->join('central_warehouse', 'central_warehouse.warehouse_id = acc_transaction.CreateBy');
            $this->db->where('central_warehouse.warehouse_id', $cw_id);
        }
        $result_amount = $this->db->get();
        $amount = $result_amount->result_array();
        $cash += $amount[0]['amount'];
        return $cash;
    }

    // ================= Shipping cost ===========================
    public function retrieve_dateWise_Shippingcost($from_date, $to_date, $per_page = null, $page = null, $outlet_id = null)
    {
        if ($outlet_id == 1) {
            $outlet_id = null;
        }

        $this->db->select("a.*");
        $this->db->from('invoice a');
        if ($outlet_id) {
            $this->db->where('a.outlet_id', $outlet_id);
        }
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->group_by('a.invoice_id');
        $this->db->order_by('a.date', 'desc');
        if ($per_page && $page)
            $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //stock report pdg

    // sales return data
    public function sales_return_list($perpage, $page, $start, $end, $outlet_id = null)
    {
        if ($outlet_id == 1) {
            $outlet_id = null;
        }
        $this->db->select('a.net_total_amount,a.*,b.customer_name, x.outlet_id');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->join('invoice x', 'x.invoice_id = a.invoice_id');
        if ($outlet_id) {
            $this->db->where('x.outlet_id', $outlet_id);
        }
        $this->db->where('usablity', 1);
        $this->db->where('a.date_return >=', $start);
        $this->db->where('a.date_return <=', $end);
        $this->db->group_by('a.invoice_id', 'desc');
        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // count sales return
    public function sales_return_count($start, $end)
    {
        $this->db->select('a.net_total_amount,a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->where('a.date_return >=', $start);
        $this->db->where('a.date_return <=', $end);
        $this->db->group_by('a.invoice_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }
    // return supplier
    public function supplier_return($perpage = null, $page = null, $start, $end, $outlet_id = null)
    {
        $this->db->select('a.net_total_amount,a.*,b.supplier_name, x.outlet_id');
        $this->db->from('product_return a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->join('invoice x', 'x.invoice_id = a.invoice_id');
        if ($outlet_id) {
            $this->db->where('x.outlet_id', $outlet_id);
        }
        $this->db->where('usablity', 2);
        $this->db->where('a.date_return >=', $start);
        $this->db->where('a.date_return <=', $end);
        $this->db->group_by('a.purchase_id', 'desc');
        if ($perpage && $page)
            $this->db->limit($perpage, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // count supplier return list
    public function count_supplier_return($start, $end)
    {
        $this->db->select('a.net_total_amount,a.*,b.supplier_name');
        $this->db->from('product_return a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('usablity', 2);
        $this->db->where('a.date_return >=', $start);
        $this->db->where('a.date_return <=', $end);
        $this->db->group_by('a.purchase_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    // tax report query
    public function retrieve_dateWise_tax($from_date, $to_date, $per_page, $page)
    {
        $this->db->select("a.*");
        $this->db->from('invoice a');
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->group_by('a.invoice_id');
        $this->db->order_by('a.date', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    // money receipt query
    public function retrieve_money_receipt()
    {
        $this->db->select("a.*, b.customer_name, c.Credit");
        $this->db->from('money_receipt a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->join('acc_transaction c', 'c.VNo = a.VNo');
        $this->db->where('c.Credit >', 0);
        $this->db->order_by('a.date', 'desc');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function retrieve_dateWise_money_receipt($from_date, $to_date)
    {
        $this->db->select("a.*, b.customer_name, c.Credit");
        $this->db->from('money_receipt a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->join('acc_transaction c', 'c.VNo = a.VNo');
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->where('c.Credit >', 0);
        $this->db->order_by('a.date', 'desc');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


    public function userList()
    {
        $this->db->select("*");
        $this->db->from('users');
        $this->db->order_by('first_name', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


    public function yearly_invoice_report($month = null)
    {

        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_id = ($CI->Warehouse->get_outlet_user()) ? $CI->Warehouse->get_outlet_user()[0]['outlet_id'] : null;

        $result = $this->db->query("
                            SELECT sum(total_amount) as total_sale FROM `invoice`
                            WHERE MONTH(date)  = $month
                                AND YEAR(date) = YEAR(CURRENT_TIMESTAMP)
                                AND `outlet_id` = '" . $outlet_id . "'
                            ");

        return $result->row();
    }

    public function yearly_purchase_report($month = null)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $outlet_id = ($CI->Warehouse->get_outlet_user()) ? $CI->Warehouse->get_outlet_user()[0]['outlet_id'] : null;

        $result = $this->db->query("
                            SELECT sum(grand_total_amount) as total_purchase FROM `product_purchase`
                            WHERE MONTH(purchase_date)  = $month
                                AND YEAR(purchase_date) = YEAR(CURRENT_TIMESTAMP)
                                AND outlet_id = '" . $outlet_id . "'
                            ");

        return $result->row();
    }

    /// Total Report part

    public function total_sales_amount($date = null)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        // $outlet_id = $CI->Warehouse->get_outlet_user()[0]['outlet_id'];
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'];
        $date = (!empty($date) ? $date : date('F Y'));
        $days = $this->yearmonthval($date);
        $this->db->select("sum(total_amount) as totalsales");
        $this->db->from('invoice');
        $this->db->where('date >=', $days['start_date']);
        $this->db->where('date <=', $days['end_date']);
        $this->db->where('outlet_id', $outlet_id);
        $query = $this->db->get()->row();
        return $query->totalsales;
    }
    public function yearmonthval($date)
    {
        list($month, $year) = explode(' ', $date);
        switch ($month) {
            case "January":
                $month = '01';
                break;
            case "February":
                $month = '02';
                break;
            case "March":
                $month = '03';
                break;
            case "April":
                $month = '04';
                break;
            case "May":
                $month = '05';
                break;
            case "June":
                $month = '06';
                break;
            case "July":
                $month = '07';
                break;
            case "August":
                $month = '08';
                break;
            case "September":
                $month = '9';
                break;
            case "October":
                $month = '10';
                break;
            case "November":
                $month = '11';
                break;
            case "December":
                $month = '12';
                break;
        }
        $fdate = $year . '-' . $month . '-' . '01';
        $lastday = date('t', strtotime($fdate));
        $edate = $year . '-' . $month . '-' . $lastday;
        $startd    = $fdate;
        $data['start_date'] = $startd;
        $data['end_date'] = $edate;
        return $data;
    }


    public function total_purchase_amount($date = null)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        // $outlet_id = $CI->Warehouse->get_outlet_user()[0]['outlet_id'];
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'];
        $date = (!empty($date) ? $date : date('F Y'));
        $days = $this->yearmonthval($date);
        $this->db->select("sum(grand_total_amount) as totalpurchase");
        $this->db->from('product_purchase');
        $this->db->where('purchase_date >=', $days['start_date']);
        $this->db->where('purchase_date <=', $days['end_date']);
        $this->db->where('outlet_id', $outlet_id);
        $query = $this->db->get();
        if (!empty($query->row()->totalpurchase)) {
            return $query->row()->totalpurchase;
        } else {
            return 0;
        }
    }

    public function total_expense_amount($date = null)
    {
        $date = (!empty($date) ? $date : date('F Y'));
        $days = $this->yearmonthval($date);
        $this->db->select("*");
        $this->db->where('PHeadName', 'Expence');
        $this->db->from('acc_coa');
        $query = $this->db->get();
        $result =  $query->result_array();
        $totalamount = 0;
        foreach ($result as $expense) {
            $amount = $this->db->select('ifnull(sum(Debit),0) as amount')->from('acc_transaction')->where('VDate >=', $days['start_date'])->where('VDate <=', $days['end_date'])->where('COAID', $expense['HeadCode'])->get()->row();
            $totalamount = $totalamount + $amount->amount;
        }

        return $totalamount;
    }

    // Total Employee Salary
    public function total_employee_salary($date = null)
    {
        $date = (!empty($date) ? $date : date('F Y'));
        $days = $this->yearmonthval($date);
        $this->db->select("sum(total_salary) as totalsalary");
        $this->db->from('employee_salary_payment');
        $this->db->where('payment_date >=', $days['start_date']);
        $this->db->where('payment_date <=', $days['end_date']);
        $query = $this->db->get();
        if (!empty($query->row()->totalsalary)) {
            return $query->row()->totalsalary;
        } else {
            return 0.00;
        }
    }

    // Total Employee Salary
    public function total_service_amount($date = null)
    {
        $date = (!empty($date) ? $date : date('F Y'));
        $days = $this->yearmonthval($date);
        $this->db->select("sum(total_amount) as totalservice");
        $this->db->from('service_invoice');
        $this->db->where('date >=', $days['start_date']);
        $this->db->where('date <=', $days['end_date']);
        $query = $this->db->get();
        if (!empty($query->row()->totalservice)) {
            return $query->row()->totalservice;
        } else {
            return 0.00;
        }
    }


    public function dashboard_query1($invoice_id, $customer_id)
    {
        $sql =  "SELECT (SELECT SUM(total_price) FROM invoice_details a JOIN invoice b ON b.invoice_id = a.invoice_id WHERE a.invoice_id = '" . $invoice_id . "' AND b.customer_id = '" . $customer_id . "') as total_amount,
    (SELECT SUM(paid_amount) FROM invoice_details a JOIN invoice b ON b.invoice_id = a.invoice_id WHERE a.invoice_id = '" . $invoice_id . "' AND b.customer_id = '" . $customer_id . "') as total_paid,
    (SELECT SUM(due_amount) FROM invoice_details a JOIN invoice b ON b.invoice_id = a.invoice_id WHERE a.invoice_id = '" . $invoice_id . "' AND b.customer_id = '" . $customer_id . "') as total_due,
    (SELECT SUM(total_discount) FROM invoice_details a JOIN invoice b ON b.invoice_id = a.invoice_id WHERE a.invoice_id = '" . $invoice_id . "' AND b.customer_id = '" . $customer_id . "') as total_discount";
        return $sql;
    }

    public function get_data()
    {

        // $query=$this->db->query("SELECT invoice_id,cheque_date
        //                         FROM invoice
        //                         WHERE invoice_id = $invoice_id");
        // return $query->result();
        $cheque_id = $this->input->get('cheque_id');
        $this->db->select('a.*,a.status as st,b.*');
        $this->db->from('cus_cheque a');
        $this->db->join('invoice b', 'b.invoice_id = a.invoice_id');
        // $this->db->join('paid_amount x', 'x.invoice_id = a.invoice_id');
        $this->db->where('cheque_id', $cheque_id);
        $this->db->where('a.status', 2);
        $query = $this->db->get();

        // echo '<pre>';
        // print_r($query->result_array());
        // exit();


        // $this->db->select("c.*,e.*");
        //  $this->db->from('cus_cheque c');
        //$this->db->join('bank_add e', 'e.bank_id = c.bank_id');
        // $this->db->join('invoice e', 'e.invoice_id = c.invoice_id');
        //  $this->db->where('invoice_id', $invoice_id);
        // $this->db->join('customer_information d', 'd.customer_id = e.customer_id');
        // $this->db->where('c.status', 2);
        ///  $this->db->where('e.payment_type', 2);
        //  $this->db->order_by('c.cheque_date', 'desc');
        //$query = $this->db->get();



        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_puchase_cheque_data()
    {
        $purchase_id = $this->input->get('purchase_id');
        $this->db->where('purchase_id', $purchase_id);
        $query = $this->db->get('product_purchase');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function update_data()
    {
        // exit();
        $this->load->model('Invoices');


        $invoice_id = $this->input->post('invoice_id');


        $cheque_id = $this->input->post('cheque_id');
        $invoice_no = $this->input->post('invoice');
        $cheque_date = $this->input->post('cheque_date');
        $payment_date = $this->input->post('payment_date');
        $cheque_number = $this->input->post('cheque_no');
        $status = $this->input->post('hidden_status');
        $credit = $this->input->post('credit_amount');
        $due = $this->input->post('due_amount');
        $paid_amount = $this->input->post('paid_amount');
        $customer_id = $this->input->post('customer_id');
        $with_cash = $this->input->post('with_cash');
        $bank_id = $this->input->post('bank_id');
        $bkash_id = $this->input->post('bkash_id');
        $nagad_id = $this->input->post('nagad_id');
        $paytype = $this->input->post('paytype');


        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');

        $inv_details = $this->Invoices->get_invoice_details($invoice_id)[0];



        $new_due = $inv_details->due_amount - $credit;
        $new_paid = $inv_details->paid_amount + $credit;


        if (!empty($bank_id)) {
            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id)->get()->row()->bank_name;

            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
        } else {
            $bankcoaid = '';
        }
        if (!empty($bkash_id)) {
            $bkashname = $this->db->select('bkash_no')->from('bkash_add')->where('bkash_id', $bkash_id)->get()->row()->bkash_no;

            $bkashcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'BK - ' . $bkashname)->get()->row()->HeadCode;
        } else {
            $bkashcoaid = '';
        }
        if (!empty($nagad_id)) {
            $nagadname = $this->db->select('nagad_no')->from('nagad_add')->where('nagad_id', $nagad_id)->get()->row()->nagad_no;

            $nagadcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', 'NG - ' . $nagadname)->get()->row()->HeadCode;
        } else {
            $nagadcoaid = '';
        }

        // $cus=$this->db->query('SELECT * FROM `customer_information` WHERE '.$customer_id.'')->row();
        $cusifo = $this->db->select('customer_name')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
        // echo '<pre>';print_r($cusifo);
        $headn = $customer_id . '-' . $cusifo->customer_name;
        $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
        //    $coainfo=("SELECT * FROM `acc_coa` WHERE `HeadName` ='.$headn.'");
        $customer_headcode = $coainfo->HeadCode;

        $dd = '';
        $ddd = '';

        if ($status == 1) {
            if ($paytype == 1) {
                $data3 = array(
                    'VNo' => $invoice_id,
                    //'cheque_id' => $cheque_id,
                    'Vtype' => 'INV',
                    'VDate' => $createdate,
                    'COAID' => 1020101,
                    'Narration' => 'Customer Debit for Installment Amount For Customer Invoice NO- ' . $invoice_no . ' and Cheque No-' . $cheque_number . ' Customer- ' . $cusifo->customer_name,
                    'Debit' => $credit,
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,
                    //'paytype'=>$paytype

                );
                //  echo '<pre>';print_r($data3);exit();
                $ddd = $this->db->insert('acc_transaction', $data3);
            }
            if ($paytype == 2) {

                $bankc = array(
                    'VNo' => $invoice_id,
                    'Vtype' => 'INVOICE',
                    'VDate' => $createdate,
                    'COAID' => $bankcoaid,
                    'Narration' => 'Installment amount for customer  Invoice No - ' . $invoice_no . ' and Cheque No-' . $cheque_number . ' customer -' . $cusifo->customer_name,
                    'Debit' => $credit,
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );
                $this->db->insert('acc_transaction', $bankc);
            }
            if ($paytype == 3) {
                $bkashc = array(
                    'VNo' => $invoice_id,
                    'Vtype' => 'INVOICE',
                    'VDate' => $createdate,
                    'COAID' => $bkashcoaid,
                    'Narration' => 'Installment amount for customer  Invoice No - ' . $invoice_no . ' and Bkash No-' . $bkashname . ' customer -' . $cusifo->customer_name,
                    'Debit' => $credit,
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );
                $this->db->insert('acc_transaction', $bkashc);
            }
            if ($paytype == 4) {
                $nagadc = array(
                    'VNo' => $invoice_id,
                    'Vtype' => 'INVOICE',
                    'VDate' => $createdate,
                    'COAID' => $nagadcoaid,
                    'Narration' => 'Installment amount for customer  Invoice No - ' . $invoice_no . ' and Nagad No-' . $nagadcoaid . ' customer -' . $cusifo->customer_name,
                    'Debit' => $credit,
                    'Credit' => 0,
                    'IsPosted' => 1,
                    'CreateBy' => $createby,
                    'CreateDate' => $createdate,
                    'IsAppove' => 1,

                );

                $this->db->insert('acc_transaction', $nagadc);
            }


            $data = array(
                'VNo' => $invoice_id,
                'cheque_id' => $cheque_id,
                'Vtype' => 'INV',
                'VDate' => $createdate,
                'COAID' => $customer_headcode,
                'Narration' => 'Customer credit for Paid Amount For Customer Invoice NO- ' . $invoice_no . ' Customer- ' . $cusifo->customer_name,
                'Debit' => 0,
                'Credit' => $credit,
                'IsPosted' => 1,
                'CreateBy' => $createby,
                'CreateDate' => $createdate,
                'IsAppove' => 1
            );


            // echo '<pre>';print_r($data);exit();
            $dd = $this->db->insert('acc_transaction', $data);
            // }

            $this->db->where('invoice_id', $invoice_id);
            $this->db->set(array(
                'paid_amount' => $new_paid,
                'due_amount' => $new_due
            ));
            $this->db->update('invoice');

            $paid_data = array(
                'invoice_id' => $invoice_id,
                'pay_type' => 2,
                'amount' => $credit
            );
            //            echo '<pre>';
            //            print_r($paid_data);
            //            exit();

            $this->db->insert('paid_amount', $paid_data);
            $this->db->set('payment_date', $payment_date);
            $this->db->set('cheque_date', $cheque_date);
            $this->db->set('cheque_no', $cheque_number);
            $this->db->set('status', $status);
            //  $this->db->set('paid_amount', $credit+$paid_amount);
            $this->db->where('cheque_id', $cheque_id);
            $cheque = $this->db->update('cus_cheque');


            return array(

                'cheque' => $cheque,
                'dd' => $dd,
                'ddd' => $ddd,
            );
        }
    }


    public function update_purchase_data()
    {
        // $purchase_id=$this->input->post('purchase_id');
        // $cheque_date=$this->input->post('cheque_date');
        // $cheque_no=$this->input->post('cheque_no');
        // // $paid_amount=$this->input->post('paid_amount');
        // // $due_amount=$this->input->post('due_amount');
        // $status=$this->input->post('hidden_status');

        $purchase_id = $this->input->post('purchase_id');
        $supplier_id = $this->input->post('supplier_id');
        $cheque_date = $this->input->post('cheque_date');
        $cheque_no = $this->input->post('cheque_no');
        $paid_amount = $this->input->post('paid_amount');
        $debit_amount = $this->input->post('debit_amount');
        $due_amount = $this->input->post('due_amount');
        $status = $this->input->post('hidden_status');

        $createby = $this->session->userdata('user_id');
        $createdate = date('Y-m-d H:i:s');

        $cusifo = $this->db->select('*')->from('supplier_information')->where('supplier_id', $supplier_id)->get()->row();
        // echo '<pre>';print_r($cusifo);exit();
        $headn = $supplier_id . '-' . $cusifo->supplier_name;
        $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
        $customer_headcode = $coainfo->HeadCode;


        $bank_id = $this->input->post('bank_id', TRUE);
        if (!empty($bank_id)) {
            $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id', $bank_id)->get()->row()->bank_name;

            $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankname)->get()->row()->HeadCode;
        } else {
            $bankcoaid = '';
        }

        if ($status == 1) {
            $data = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Purchase',
                'VDate'          =>  $createdate,
                'COAID'          =>  $customer_headcode,
                'Narration'      =>  'Supplier debit for Paid Amount For Cheque NO- ' . $cheque_no . ' Supplier ' . $cusifo->supplier_name,
                'Debit'          =>  $debit_amount,
                'Credit'         =>  0,
                'IsPosted'       => 1,
                'CreateBy'       => $createby,
                'CreateDate'     => $createdate,
                'IsAppove'       => 1
            );
            $dd = $this->db->insert('acc_transaction', $data);
            $data3 = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Purchase',
                'VDate'          =>  $createdate,
                'COAID'          =>  $bankcoaid,
                'Narration'      =>   'Cash in  Bank for Paid Amount For Cheque NO- ' . $cheque_no . ' Supplier ' . $cusifo->supplier_name,
                'Debit'          =>  0,
                'Credit'         =>  $debit_amount,
                'IsPosted'       => 1,
                'CreateBy'       => $createby,
                'CreateDate'     => $createdate,
                'IsAppove'       => 1

            );
            $ddd = $this->db->insert('acc_transaction', $data3);
        } else {

            $data2 = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Purchase',
                'VDate'          =>  $createdate,
                'COAID'          =>  $customer_headcode,
                'Narration'      =>  'Supplier debit for Paid Amount For Cheque NO- ' . $cheque_no . ' Supplier ' . $cusifo->supplier_name,
                'Debit'          =>  $debit_amount,
                'Credit'         =>  0,
                'IsPosted'       => 1,
                'CreateBy'       => $createby,
                'CreateDate'     => $createdate,
                'IsAppove'       => 1

            );
            $dd = $this->db->insert('acc_transaction', $data2);
            $data3 = array(
                'VNo'            =>  $purchase_id,
                'Vtype'          =>  'Purchase',
                'VDate'          =>  $createdate,
                'COAID'          =>  $bankcoaid,
                'Narration'      =>   'Cash in  Bank for Paid Amount For Cheque NO- ' . $cheque_no . ' Supplier ' . $cusifo->supplier_name,
                'Debit'          =>  0,
                'Credit'         =>  $debit_amount,
                'IsPosted'       => 1,
                'CreateBy'       => $createby,
                'CreateDate'     => $createdate,
                'IsAppove'       => 1

            );
            $ddd = $this->db->insert('acc_transaction', $data3);
        }

        //        echo '<pre>';print_r($data);
        //        echo '<pre>';print_r($data2);
        //        echo '<pre>';print_r($data3);exit();
        $this->db->set('cheque_date', $cheque_date);
        $this->db->set('cheque_no', $cheque_no);
        $this->db->set('status', $status);
        $this->db->set('paid_amount', $debit_amount + $paid_amount);
        $this->db->where('purchase_id', $purchase_id);
        $result = $this->db->update('product_purchase');

        return array(
            'result' => $result,
            'dd' => $dd,
            'ddd' => $ddd,
        );
    }

    public function get_sales_data_pay_wise($outlet_id = null, $from_date = null, $to_date)
    {
        if ($outlet_id == 1) {
            $outlet_id = null;
        }
        $this->db->select('i.date, p.pay_type, o.outlet_name, sum(i.due_amount) as total_due, sum(p.amount) as paid_amnt')
            ->from('invoice i')
            ->join('paid_amount p', 'p.invoice_id = i.invoice_id');
        if ($outlet_id) {
            $this->db->where('i.outlet_id', $outlet_id);
        }

        if ($from_date && $to_date) {
            $this->db->where('i.date >=', $from_date);
            $this->db->where('i.date <=', $to_date);
        }

        if ($from_date && !$to_date) {
            $this->db->where('i.date >=', $from_date);
        }

        if ($to_date && !$from_date) {
            $this->db->where('i.date <=', $to_date);
        }


        $this->db->join('outlet_warehouse o', 'o.outlet_id = i.outlet_id')
            ->group_by('i.date')
            ->group_by('p.pay_type')
            ->order_by('i.id', 'desc');

        $res = $this->db->get()->result_array();

        $new_res = array();

        foreach ($res as $re) {

            $date = $re['date'];
            $sl = 1;

            if (!array_key_exists($date, $new_res)) {


                $new_res[$date] = array(
                    'pay_type_cash' => ($re['pay_type'] == 1) ? $re['paid_amnt'] : '0.00',
                    'pay_type_bkash' => ($re['pay_type'] == 3) ? $re['paid_amnt'] : '0.00',
                    'pay_type_nagad' => ($re['pay_type'] == 5) ? $re['paid_amnt'] : '0.00',
                    'pay_type_bank' => ($re['pay_type'] == 4) ? $re['paid_amnt'] : '0.00',
                    'pay_type_card' => ($re['pay_type'] == 6) ? $re['paid_amnt'] : '0.00',
                    'pay_type_cheque' => ($re['pay_type'] == 2) ? $re['paid_amnt'] : '0.00',
                    'pay_type_rocket' => ($re['pay_type'] == 7) ? $re['paid_amnt'] : '0.00',
                    'outlet_name' => $re['outlet_name'],
                    'total_due'     => $re['total_due']
                );
            } else {
                switch ($re['pay_type']) {
                    case 1:
                        $new_res[$date]['pay_type_cash'] = $re['paid_amnt'];
                        break;

                    case 3:
                        $new_res[$date]['pay_type_bkash'] = $re['paid_amnt'];
                        break;

                    case 5:
                        $new_res[$date]['pay_type_nagad'] = $re['paid_amnt'];
                        break;

                    case 4:
                        $new_res[$date]['pay_type_bank'] = $re['paid_amnt'];
                        break;

                    case 6:
                        $new_res[$date]['pay_type_card'] = $re['paid_amnt'];
                        break;

                    case 2:
                        $new_res[$date]['pay_type_cheque'] = $re['paid_amnt'];
                        break;

                    case 7:
                        $new_res[$date]['pay_type_rocket'] = $re['paid_amnt'];
                        break;
                }
            }
        }

        if (!$from_date && !$to_date) {
            if (count($new_res) > 6) {
                $new_res = array_slice($new_res, 0, 7);
            }
        }


        return $new_res;
    }
    //sales report Prodcut Wise
    public function ProductSalesReport($postData = null, $post_product_id = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $outlet_id = $this->input->post('outlet_id', TRUE);
        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }

        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (p.product_name like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information p');
            $this->db->join('invoice_details id', 'id.product_id = p.product_id');
            $this->db->join('invoice i', 'i.invoice_id  = id.invoice_id ', 'left');
            if ($from_date != '') {
                $this->db->where('i.date >=', $from_date);
            }

            if ($to_date) {
                $this->db->where('i.date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('i.outlet_id', $outlet_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('p.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;
            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            $this->db->from('product_information p');
            $this->db->join('invoice_details id', 'id.product_id = p.product_id');
            $this->db->join('invoice i', 'i.invoice_id  = id.invoice_id ', 'left');
            if ($from_date != '') {
                $this->db->where('i.date >=', $from_date);
            }

            if ($to_date) {
                $this->db->where('i.date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('i.outlet_id', $outlet_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('p.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
            ## Fetch records

            $this->db->select("p.product_id,p.product_name,
            (SELECT sum(i.total_discount) from invoice as i WHERE i.invoice_id = id.invoice_id) as total_discount,
            (SELECT sum(id.quantity) from invoice_details as id WHERE id.product_id = p.product_id) as total_sold,
            (SELECT sum(id.total_price_wd) from invoice_details as id WHERE id.product_id = p.product_id) as total_sales,
            (SELECT sum(id.tax) from invoice_details as id WHERE id.product_id = p.product_id) as total_tax,
            (SELECT sum(id.vat) from invoice_details as id WHERE id.product_id = p.product_id) as total_vat
            ");
            $this->db->join('invoice_details id', 'id.product_id = p.product_id');
            $this->db->join('invoice i', 'i.invoice_id  = id.invoice_id ', 'left');
            $this->db->from('product_information p');
            $this->db->order_by($columnName, $columnSortOrder);
            $this->db->group_by('p.product_id');
            $this->db->limit($rowperpage, $start);
            if ($from_date && $from_date != '') {
                $this->db->where('i.date >=', $from_date);
            }
            if ($to_date && $to_date != '') {
                $this->db->where('i.date <=', $to_date);
            }
            if ($outlet_id && $outlet_id != '') {
                $this->db->where('i.outlet_id', $outlet_id);
            }
            if ($searchValue != '')
                $this->db->where($searchQuery);
            $records = $this->db->get()->result();
            // echo "<pre>";
            // print_r($records);
            // exit();

        }
        if (!($from_date)) {
            $from_date = 0;
        }
        if (!($to_date)) {
            $to_date = 0;
        }
        $data = array();
        $sl = 1;
        $base_url = base_url();
        $net_sales = 0;
        $cost_price = 0;

        foreach ($records as $record) {
            $details_i = '<a href="' . $base_url . 'Creport/product_details_report/' . $record->product_id . '/' . $from_date . '/' . $to_date . '/' . $outlet_id . '" class="" >' . $record->product_name . '</a>';
            $purchase_price = $this->weighted_average_price($record->product_id);

            // $net_sales = ($record->total_sales - $record->total_discount) + $record->total_vat;
            $net_sales = $record->total_sales + ($record->total_vat + $record->total_tax);
            $cost_price = $purchase_price * $record->total_sold;
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $details_i,
                'qnty'  =>  $record->total_sold ? $record->total_sold : 0,
                'total_sales'  => $record->total_sales ? $record->total_sales : 0,
                // 'discount' => $record->total_discount ? ($record->total_discount) : 0,
                'discount' => 0,
                'vat' => $record->total_vat,
                'tax' => $record->total_tax,
                'net_sales' => $net_sales ? $net_sales : 0,
                'cost_price' => $cost_price ? number_format((float)$cost_price, 2, '.', '') : 0,
                'gross_profit' => number_format((float)($net_sales - $cost_price), 2, '.', ''),
                //number_format((float)$foo, 2, '.', ''); ($val->rate) ? $currency_symbol->currency_symbol . number_format_unchanged_precision($val->rate, $currency_symbol->currency_code) : '',
            );
            $sl++;
        }
        ## Response
        if ($data) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" =>  array(),
            );
        }
        return $response;
    }
    public function weighted_average_price($product_id, $qnty = null)
    {
        $stockout_for_purchase_price = $this->db->select('sum(product_purchase_details.qty) as totalPurchaseQnty, sum(product_purchase_details.total_amount) as purchaseprice')
            ->from('product_purchase_details')
            ->join('product_purchase', 'product_purchase.purchase_id = product_purchase_details.purchase_id')
            ->where('product_purchase_details.product_id', $product_id)
            ->where('product_purchase_details.qty >=', 0)
            ->get()->row();
        if (!empty($stockout_for_purchase_price->purchaseprice) && !empty($stockout_for_purchase_price->totalPurchaseQnty)) {
            $stockout_for_weighted_average = $stockout_for_purchase_price->purchaseprice / $stockout_for_purchase_price->totalPurchaseQnty;
        }
        if ($qnty) {
            return $stockout_for_purchase_price->totalPurchaseQnty;
        } else {
            return $stockout_for_weighted_average;
        }
    }
    ///Sales Report Product Wise Details
    public function product_sales_details_data($product_id, $from_date = null, $to_date = null, $outlet_id = null)
    {

        $this->db->select("p.product_name,
        i.date,sum(id.quantity) as total_sold,sum(id.tax) as total_tax,sum(id.vat) as total_vat,sum(id.total_price_wd) as total_sales,
        i.total_discount");
        $this->db->join('invoice_details id', 'id.product_id = p.product_id', 'left');
        $this->db->join('invoice i', 'i.invoice_id  = id.invoice_id ', 'left');
        $this->db->from('product_information p');
        $this->db->where('p.product_id', $product_id);
        $this->db->group_by('i.date');
        if ($outlet_id && $outlet_id != '') {
            $this->db->where('i.outlet_id', $outlet_id);
        }
        if ($from_date != 0) {
            $this->db->where('i.date >=', $from_date);
        }
        if ($to_date != 0) {
            $this->db->where('i.date <=', $to_date);
        }
        $records = $this->db->get()->result_array();
        $data = array();
        $sl = 1;
        $purchase_price = $this->weighted_average_price($product_id);
        foreach ($records as $record) {
            $net_sales = $record['total_sales'] + $record['total_vat'] + $record['total_tax'];
            $cost_price = $purchase_price * $record['total_sold'];
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record['product_name'],
                'date' => $record['date'],
                'qnty'  =>  $record['total_sold'],
                'total_sales'  => $record['total_sales'] ? $record['total_sales']
                    + $record['total_vat'] + $record['total_tax'] : 0,
                'discount' => 0,
                'net_sales' => $net_sales ? $net_sales : 0,
                'cost_price' => $cost_price ? $cost_price : 0,
                'gross_profit' => $net_sales - $cost_price,
            );
            $sl++;
        }
        return $data;
    }
    //Supplier Wise Sales Report
    public function SupplierSalesReport($postData = null, $post_product_id = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $supplier_id = $this->input->post('supplier_id', TRUE);

        if ($this->input->post('outlet_id')) {
            $outlet_id = $this->input->post('outlet_id');
            if ($outlet_id == 'All') {
                $outlet_id = null;
            } else {
                $outlet_id = $this->input->post('outlet_id');
            }
        } else {
            $outlet_id = $this->session->userdata('outlet_id');
        }

        // echo "<pre>";
        // print_r($outlet_id);
        // exit();

        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (p.product_name like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            // $this->db->select("id.purchase_id,id.product_id,pi.product_name,supplier_name,
            // (SELECT sum(id.quantity) from invoice_details as id WHERE id.product_id = pi.product_id) as total_sold,
            // (SELECT sum(id.rate) from invoice_details as id WHERE id.product_id = pi.product_id) as total_rate,
            // (SELECT sum(id.total_price_wd) from invoice_details as id WHERE id.product_id = pi.product_id) as total_sales,
            //  (SELECT sum(id.tax) from invoice_details as id WHERE id.product_id = pi.product_id) as total_tax,
            //  (SELECT sum(id.vat) from invoice_details as id WHERE id.product_id = pi.product_id) as total_vat,
            //  (SELECT sum(id.discount) from invoice_details as id WHERE id.product_id = pi.product_id) as total_discount
            // ");
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase p');
            $this->db->join('invoice_details id', 'id.purchase_id = p.purchase_id', 'left');
            $this->db->join('invoice i', 'i.invoice_id = id.invoice_id', 'left');
            $this->db->join('product_information pi', 'pi.product_id = id.product_id', 'left');
            if ($from_date != '') {
                $this->db->where('i.date >=', $from_date);
            }

            if ($to_date) {
                $this->db->where('i.date <=', $to_date);
            }
            if ($supplier_id) {
                $this->db->where('p.supplier_id', $supplier_id);
            }
            if ($outlet_id) {
                $this->db->where('i.outlet_id', $outlet_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('id.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;
            ## Total number of record with filtering
            // $this->db->select("id.purchase_id,id.product_id,pi.product_name,supplier_name,
            // (SELECT sum(id.quantity) from invoice_details as id WHERE id.product_id = pi.product_id) as total_sold,
            // (SELECT sum(id.rate) from invoice_details as id WHERE id.product_id = pi.product_id) as total_rate,
            // (SELECT sum(id.total_price_wd) from invoice_details as id WHERE id.product_id = pi.product_id) as total_sales,
            //  (SELECT sum(id.tax) from invoice_details as id WHERE id.product_id = pi.product_id) as total_tax,
            //  (SELECT sum(id.vat) from invoice_details as id WHERE id.product_id = pi.product_id) as total_vat,
            //  (SELECT sum(id.discount) from invoice_details as id WHERE id.product_id = pi.product_id) as total_discount
            // ");
            $this->db->select('count(*) as allcount');
            $this->db->from('product_purchase p');
            $this->db->join('invoice_details id', 'id.purchase_id = p.purchase_id', 'left');
            $this->db->join('invoice i', 'i.invoice_id = id.invoice_id', 'left');
            $this->db->join('product_information pi', 'pi.product_id = id.product_id', 'left');
            if ($from_date != '') {
                $this->db->where('i.date >=', $from_date);
            }

            if ($to_date) {
                $this->db->where('i.date <=', $to_date);
            }
            if ($supplier_id) {
                $this->db->where('p.supplier_id', $supplier_id);
            }
            if ($outlet_id) {
                $this->db->where('i.outlet_id', $outlet_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('id.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
            ## Fetch records
            // $this->db->select("id.purchase_id,id.product_id,pi.product_name,pi.sku,supplier_name,
            // (SELECT sum(id.quantity) from invoice_details as id WHERE id.product_id = pi.product_id) as total_sold,
            // (SELECT sum(id.rate) from invoice_details as id WHERE id.product_id = pi.product_id) as total_rate,
            // (SELECT sum(id.total_price_wd) from invoice_details as id WHERE id.product_id = pi.product_id) as total_sales,
            //  (SELECT sum(id.tax) from invoice_details as id WHERE id.product_id = pi.product_id) as total_tax,
            //  (SELECT sum(id.vat) from invoice_details as id WHERE id.product_id = pi.product_id) as total_vat,
            //  (SELECT sum(id.discount) from invoice_details as id WHERE id.product_id = pi.product_id) as total_discount,

            //  ");
            // $this->db->join('product_purchase p', 'p.purchase_id = id.purchase_id', 'left');
            // // $this->db->join('product_purchase_details pd','pd.purchase_id = p.purchase_id','left');
            // $this->db->join('invoice i', 'i.invoice_id = id.invoice_id', 'left');
            // $this->db->join('product_information pi', 'pi.product_id = id.product_id', 'left');
            // $this->db->join('supplier_information si', 'si.supplier_id = p.supplier_id', 'left');
            // $this->db->from('invoice_details id');
            // $this->db->order_by($columnName, $columnSortOrder);
            // $this->db->group_by('id.product_id');
            // $this->db->limit($rowperpage, $start);
            // if ($from_date && $from_date != '') {
            //     $this->db->where('i.date >=', $from_date);
            // }
            // if ($to_date && $to_date != '') {
            //     $this->db->where('i.date <=', $to_date);
            // }
            // if ($supplier_id) {
            //     $this->db->where('p.supplier_id', $supplier_id);
            // }
            // if ($outlet_id) {
            //     $this->db->where('i.outlet_id', $outlet_id);
            // }
            // if ($searchValue != '') {
            //     $this->db->where($searchQuery);
            // }



            $this->db->select("pi.product_name,pi.sku,sum(id.quantity) as sold_qty,(sum(id.total_price) /sum(id.quantity)) as rate,sum(id.item_total_discount) as total_discount,sum(id.vat) as total_vat,sum(id.tax) as total_tax,(sum(id.supplier_rate * id.quantity) / sum(id.quantity)) as cpu");
            $this->db->from('product_purchase p');
            $this->db->join('invoice_details id', 'id.purchase_id = p.purchase_id', 'left');
            $this->db->join('invoice i', 'i.invoice_id = id.invoice_id', 'left');
            $this->db->join('product_information pi', 'pi.product_id = id.product_id', 'left');

            $this->db->order_by($columnName, $columnSortOrder);
            $this->db->group_by('id.product_id');
            $this->db->limit($rowperpage, $start);
            if ($from_date) {
                $this->db->where('i.date >=', $from_date);
            }
            if ($to_date) {
                $this->db->where('i.date <=', $to_date);
            }
            if ($supplier_id) {
                $this->db->where('p.supplier_id', $supplier_id);
            }
            if ($outlet_id) {
                $this->db->where('i.outlet_id', $outlet_id);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }

            $records = $this->db->get()->result();
        }

        // echo "<pre>";
        // print_r($records);
        // exit();

        $data = array();
        $sl = 1;
        $base_url = base_url();
        $net_sales = 0;
        $cost_price = 0;

        foreach ($records as $record) {
            // $purchase_price = $this->weighted_average_price($record->product_id);
            // $purchased_qnty = $this->weighted_average_price($record->product_id, 1);
            // $net_sales = $record->total_sales - $record->total_discount + ($record->total_vat + $record->total_tax);
            // $cost_price = $purchase_price * $record->total_sold;
            // $data[] = array(
            //     'sl'            =>   $sl,
            //     'product_name'  =>  $record->product_name,
            //     'purchased_qnty'  => $record->sku,
            //     'qnty'  =>  $record->total_sold ? $record->total_sold : 0,
            //     'rate' => $record->total_rate ? ($record->total_rate) : 0,
            //     'total_sales'  => $record->total_rate ? ($record->total_rate * $record->total_sold) : 0,
            //     'discount' => $record->total_discount ? $record->total_discount : 0,
            //     'vat' => $record->total_vat ? $record->total_vat : 0,
            //     'tax' => $record->total_tax ? $record->total_tax : 0,

            //     'net_sales' => $net_sales ? $net_sales : 0,
            //     'cost_price' => $cost_price ? number_format((float)$cost_price, 2, '.', '') : 0,
            //     'gross_profit' => number_format((float)($net_sales - $cost_price), 2, '.', ''),
            //     //number_format((float)$foo, 2, '.', ''); ($val->rate) ? $currency_symbol->currency_symbol . number_format_unchanged_precision($val->rate, $currency_symbol->currency_code) : '',
            // );

            $sold_qty = $record->sold_qty ? $record->sold_qty : 0;
            $rate = $record->rate ? $record->rate : 0;

            $discount = $record->total_discount ? $record->total_discount : 0;
            $vat = $record->total_vat ? $record->total_vat : 0;
            $tax = $record->total_tax ? $record->total_tax : 0;

            $total_sales = $sold_qty * $rate;
            $net_sale = $total_sales - $discount + $vat + $tax;
            $cpu = $record->cpu ? $record->cpu : 0;
            $total_cpu = $sold_qty * $cpu;
            $gross_profit = $total_sales - $discount - $vat - $tax  - $total_cpu;

            // echo "<pre>";
            // print_r($rate . " ");
            // print_r($total_sales . " ");
            // exit();
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name,
                'sku'  => $record->sku,
                'qnty'  =>  $sold_qty,
                'rate' => number_format((float)($rate), 2, '.', ''),
                'total_sales'  => number_format((float)($total_sales), 2, '.', ''),
                'discount' => number_format((float)($discount), 2, '.', ''),
                'vat' => number_format((float)($vat), 2, '.', ''),
                'tax' => number_format((float)($tax), 2, '.', ''),
                'net_sales' => number_format((float)($net_sale), 2, '.', ''),
                'cost_price' => number_format((float)($cpu), 2, '.', ''),
                'total_cpu' => number_format((float)($total_cpu), 2, '.', ''),
                'gross_profit' => number_format((float)($gross_profit), 2, '.', ''),
                //number_format((float)$foo, 2, '.', ''); ($val->rate) ? $currency_symbol->currency_symbol . number_format_unchanged_precision($val->rate, $currency_symbol->currency_code) : '',
            );
            $sl++;
        }
        ## Response
        if ($data) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" =>  array(),
            );
        }
        return $response;
    }
    //Purchase report Product Wise
    public function ProductPurchaseReport($postData = null, $post_product_id = null)
    {

        $this->load->model('Warehouse');
        $this->load->model('suppliers');
        $response = array();
        $from_date = $this->input->post('from_date', TRUE);
        $to_date = $this->input->post('to_date', TRUE);
        $outlet_id = $this->input->post('outlet_id', TRUE);
        if ($outlet_id == '') {
            $outlet_id = $this->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        } elseif ($outlet_id === 'All') {
            $outlet_id = null;
        } else {
            $outlet_id = $this->input->post('outlet_id', TRUE);;
        }

        ## Read value
        if (!$post_product_id) {
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
                $searchQuery = " (p.product_name like '%"
                    . $searchValue .
                    "%') ";
            }

            ## Total number of records without filtering
            $this->db->select("pi.product_name,pi.sku,
             sum(ppd.quantity) as total_purchased_quantity,si.supplier_name
              ");
            $this->db->from('product_purchase_details ppd');
            $this->db->join('product_purchase p', 'p.purchase_id = ppd.purchase_id');
            $this->db->join('supplier_information si', 'si.supplier_id = p.supplier_id');
            $this->db->join('product_information pi', 'pi.product_id = ppd.product_id');
            if ($from_date && $from_date != '') {
                $this->db->where('p.purchase_date >=', $from_date);
            }
            if ($to_date && $to_date != '') {
                $this->db->where('p.purchase_date <=', $to_date);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('ppd.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecords = $records;
            ## Total number of record with filtering
            $this->db->select("pi.product_name,pi.sku,
            sum(ppd.quantity) as total_purchased_quantity,si.supplier_name
             ");
            $this->db->from('product_purchase_details ppd');
            $this->db->join('product_purchase p', 'p.purchase_id = ppd.purchase_id');
            $this->db->join('supplier_information si', 'si.supplier_id = p.supplier_id');
            $this->db->join('product_information pi', 'pi.product_id = ppd.product_id');
            if ($from_date && $from_date != '') {
                $this->db->where('p.purchase_date >=', $from_date);
            }
            if ($to_date && $to_date != '') {
                $this->db->where('p.purchase_date <=', $to_date);
            }
            if ($searchValue != '') {
                $this->db->where($searchQuery);
            }
            $this->db->group_by('ppd.product_id');
            $records = $this->db->get()->num_rows();
            $totalRecordwithFilter = $records;
            ## Fetch records

            $this->db->select("pi.product_name,pi.sku,ppd.product_id,
            sum(ppd.quantity) as total_purchased_quantity,si.supplier_name,
            sum(ppd.total_amount) as total_purchased_amount
             ");
            $this->db->join('product_purchase p', 'p.purchase_id = ppd.purchase_id');
            $this->db->join('supplier_information si', 'si.supplier_id = p.supplier_id');
            $this->db->join('product_information pi', 'pi.product_id = ppd.product_id');
            $this->db->from('product_purchase_details ppd');
            $this->db->where('ppd.quantity >=', 0);
            $this->db->order_by($columnName, $columnSortOrder);
            $this->db->group_by('ppd.product_id');
            $this->db->limit($rowperpage, $start);
            if ($from_date && $from_date != '') {
                $this->db->where('p.purchase_date >=', $from_date);
            }
            if ($to_date && $to_date != '') {
                $this->db->where('p.purchase_date <=', $to_date);
            }
            if ($searchValue != '')
                $this->db->where($searchQuery);
            $records = $this->db->get()->result();
            //  echo "<pre>";
            //  print_r($records);
            //  exit();

        }
        if (!($from_date)) {
            $from_date = 0;
        }
        if (!($to_date)) {
            $to_date = 0;
        }

        $data = array();
        $sl = 1;
        $base_url = base_url();
        $net_sales = 0;
        $cost_price = 0;

        foreach ($records as $record) {
            $details_i = '<a href="' . $base_url . 'Creport/product_purchase_details_report/' . $record->product_id . '/' . $from_date . '/' . $to_date . '" class="" >' . $record->product_name . '</a>';
            $purchase_price = $this->weighted_average_price($record->product_id);
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $details_i,
                'sku'  =>  $record->sku,
                'qnty'  =>  $record->total_purchased_quantity ? $record->total_purchased_quantity : 0,
                'total_purchased_amount' => $record->total_purchased_amount ? ($record->total_purchased_amount) : 0,
                //  'discount' => 0,
                'weighted_price' => $purchase_price ? $purchase_price : 0,
                //  'tax' => $record->total_tax,
                //  'net_sales' => $net_sales ? $net_sales : 0,
                // 'cost_price' => $cost_price ? number_format((float)$cost_price, 2, '.', '') : 0,
                //  'gross_profit' => number_format((float)($net_sales - $cost_price), 2, '.', ''),
                //number_format((float)$foo, 2, '.', ''); ($val->rate) ? $currency_symbol->currency_symbol . number_format_unchanged_precision($val->rate, $currency_symbol->currency_code) : '',
            );
            $sl++;
        }
        ## Response
        if ($data) {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "aaData" =>  $data,
            );
        } else {
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" =>  array(),
            );
        }
        return $response;
    }
    ///Sales Report Product Wise Details
    public function product_purchase_details_data($product_id, $from_date = null, $to_date = null)
    {
        $CI = &get_instance();
        $CI->load->library('occational');
        $this->db->select("pi.product_name,si.supplier_name,p.purchase_id,p.purchase_date,ppd.qty,ppd.rate,ppd.total_amount");
        // $this->db->join('product_purchase_details ppd','ppd.product_id = p.product_id','left');
        $this->db->join('product_purchase p', 'p.purchase_id  = ppd.purchase_id ', 'left');
        $this->db->join('supplier_information si', 'si.supplier_id = p.supplier_id');
        $this->db->join('product_information pi', 'pi.product_id = ppd.product_id');
        $this->db->from('product_purchase_details ppd');
        $this->db->where('ppd.product_id', $product_id);
        // $this->db->group_by('p.purchase_date');

        if ($from_date != 0) {
            $this->db->where('p.purchase_date >=', $from_date);
        }
        if ($to_date != 0) {
            $this->db->where('p.purchase_date <=', $to_date);
        }
        $records = $this->db->get()->result_array();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record['product_name'],
                'supplier_name'  =>  $record['supplier_name'],
                'purchase_id'  =>  $record['purchase_id'],
                'purchase_date' => $CI->occational->dateConvert($record['purchase_date']),
                'qnty'  =>  $record['qty'],
                'rate'  => $record['rate'] ? $record['rate'] : 0,
                'total_amount' => $record['total_amount'] ? $record['total_amount'] : 0
            );
            $sl++;
        }
        return $data;
    }
    public function daywise_sales_report() {
        $this->db->select('invoice.date as sale_date, product_information.product_name, COUNT(invoice_details.product_id) as totalSalesQnty');
        $this->db->join('invoice', 'invoice.invoice_id = invoice_details.invoice_id');
        $this->db->join('product_information', 'product_information.product_id = invoice_details.product_id');
        $this->db->where('invoice.date >=', date('Y-m-d', strtotime('-30 days')));
        $this->db->group_by('invoice.date, product_information.product_name');
        $query = $this->db->get('invoice_details');
        return $query->result_array();
    }
}
