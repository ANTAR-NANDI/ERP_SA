<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customers extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    //Count customer
    public function count_customer()
    {
        return $this->db->count_all("customer_information");
    }

    //customer List
    public function customer_list_count()
    {
        $this->db->select('*');
        $this->db->from('customer_information');
        $this->db->order_by('create_date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    //customer List
    public function customer_list($per_page = null, $page = null)
    {
        $this->db->select('*');
        $this->db->from('customer_information');
        $this->db->order_by('create_date', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function getCustomerList($postData = null)
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
        $outlet_id = $this->input->post('outlet_id',TRUE);
       

        if($outlet_id == "All")
        {
            $outlet_id = null;
        }

        ## Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (a.customer_name like '%" . $searchValue . "%' or a.customer_name_bn like '%"  . $searchValue . "%' or a.customer_mobile like '%" . $searchValue . "%' or a.customer_email like '%" . $searchValue . "%'or a.phone like '%" . $searchValue . "%' or a.customer_address like '%" . $searchValue . "%' or a.country like '%" . $searchValue . "%' or a.state like '%" . $searchValue . "%' or a.zip like '%" . $searchValue . "%' or a.city like '%" . $searchValue . "%' or a.customer_id_two like '%" . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        $this->db->group_by('a.customer_id');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('a.outlet_id', $outlet_id);
            }
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        $this->db->group_by('a.customer_id');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('a.outlet_id', $outlet_id);
            }
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("a.*,card_types.name as card_name,a.customer_name,a.customer_name_bn,a.customer_address,a.address2,a.phone,a.customer_mobile,a.customer_email,
        b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('card_types', 'a.membership_id = card_types.id', 'left');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        $this->db->group_by('a.customer_id');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            if ($outlet_id != '') {
                $this->db->where('a.outlet_id', $outlet_id);
            }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";



            if ($this->permission1->method('manage_customer', 'update')->access()) {
                $button .= '<a href="' . $base_url . 'Ccustomer/customer_update_form/' . $record->customer_id . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
            }
            if ($this->permission1->method('manage_customer', 'delete')->access()) {
                $button .= '<a href="' . $base_url . 'Ccustomer/customer_delete/' . $record->customer_id . '" class="btn btn-danger btn-xs " onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
            }




            $data[] = array(
                'sl'               => $sl,
                'customer_id_two'    => html_escape($record->customer_id_two),
                // 'friend_card'    => html_escape($record->friend_card),
                'customer_name_bn'    => html_escape($record->customer_name_bn),
                'customer_name'    => html_escape($record->customer_name),
                'card_name' => html_escape($record->card_name),
                'card_number' => html_escape($record->card_number),
                'shop_name'    => html_escape($record->shop_name),
                'address'          => html_escape($record->customer_address),
                'address2'         => html_escape($record->address2),
                'mobile'           => html_escape($record->customer_mobile),
                //'phone'            =>html_escape($record->phone),
                'email'            => html_escape($record->customer_email),
                'balance'          => (!empty($record->balance) ? $record->balance : 0),
                'button'           => $button,

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
    public function get_all_customerList($postData = null)
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

        $data = array();
        $sl = 1;

        //count product




        //search  product

        if ($searchValue != '') {

            $url = api_url() . "customers/count_customer_search/" . $searchValue;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $total_product = curl_exec($curl);
            curl_close($curl);



            $url = api_url() . "customers/search_customer/" . $searchValue . "/" . $rowperpage;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);

            $records = json_decode($resp); //fetch all product

            //            echo '<pre>';
            //            print_r($records);
            //            exit();
        } else {


            $url = api_url() . "order/count_c";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $total_product = curl_exec($curl);
            curl_close($curl);

            $url = api_url() . "customers/get_customer/" . $rowperpage;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);

            $records = json_decode($resp);
        }






        //        $totalRecordwithFilter = $records[0]->allcount;
        foreach ($records as $record) {
            $button = '';
            https: //swaponsworld.com/public/uploads/products/thumbnail/7nKgZ7HuR0f0qB4dwtxKCQ1CxFe37Qmnrulzjzp0.jpeg
            if ($this->permission1->method('manage_product', 'update')->access()) {
                $button .= ' <a href="'  . 'Cproduct/product_update_form/' . $record->id . '" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="left" title="' . display('update') . '"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>';
            }


            $data[] = array(
                'sl'               => $sl,
                'customer_name'     => $record->name,
                'email'            =>  $record->email,
                'phone'              =>  $record->phone,
                'balance'            =>  $record->balance,
                'button'           =>  $button,
            );

            $sl++;
        }



        //        var_dump($resp);


        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $total_product,
            "iTotalDisplayRecords" => $total_product,
            "aaData" => $data
            //"aaData" => $json_data
        );
        //        echo '<pre>';
        //        print_r(count($records));
        //        exit();
        return $response;
    }


    public function customer_product_buy($per_page, $page)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        //$outlet_id = $CI->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];

        // $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'];
        if($outlet_id == "HK7TGDT69VFMXB7")
        {
            $outlet_id = null;
        }

        $this->db->select('a.*,b.HeadName,i.invoice');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->join('invoice i', 'i.invoice_id=a.VNo');
        $this->db->where('b.PHeadName', 'Customer Receivable');
        $this->db->where('a.IsAppove', 1);
        if ($outlet_id) {
            $this->db->where('i.outlet_id', $outlet_id);
        }
        // $this->db->where(
        //     'i.outlet_id',
        //     $outlet_id
        // );
        $this->db->order_by('a.VDate', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function customer_product_buy_cheque($per_page, $page)
    {
        $this->db->select('a.*,b.HeadName,c.*');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->join('cus_cheque c', 'c.cheque_id=a.cheque_id');
        $this->db->where('b.PHeadName', 'Customer Receivable');
        $this->db->where('a.IsAppove', 1);
        $this->db->where('c.status', 1);
        $this->db->order_by('a.VDate', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }


    public function count_customer_ledger()
    {
        $this->db->select('a.*,b.HeadName');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->where('b.PHeadName', 'Customer Receivable');
        $this->db->where('a.IsAppove', 1);
        $this->db->order_by('a.VDate', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    public function count_customer_ledger_cheque()
    {
        $this->db->select('a.*,b.HeadName,c.*');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->join('cus_cheque c', 'c.cheque_id=a.cheque_id');
        $this->db->where('b.PHeadName', 'Customer Receivable');
        $this->db->where('a.IsAppove', 1);
        $this->db->where('c.status', 1);
        $this->db->order_by('a.VDate', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }




    //customer list
    public function customer_list_ledger()
    {
        $this->db->select('*');
        $this->db->from('customer_information');
        $this->db->order_by('customer_name', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function customer_list_ledger_cheque()
    {
        $this->db->select('*');
        $this->db->from('customer_information');
        $this->db->order_by('customer_name', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function customerledger_searchdata($customer_id, $start, $end)
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        // $outlet_id = $CI->Warehouse->outlet_or_cw_logged_in()[0]['outlet_id'];
        // $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $CI->Warehouse->get_outlet_id_user_id($user_id)[0]['outlet_id'];
        if($outlet_id == "HK7TGDT69VFMXB7")
        {
            $outlet_id = null;
        }
        // echo '<pre>';
        // print_r($outlet_id);
        // exit();

        $this->db->select('a.*,b.HeadName,i.invoice');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->join('invoice i', 'i.invoice_id=a.VNo');
        if ($outlet_id) {
            $this->db->where('i.outlet_id', $outlet_id);
        }
        // $this->db->where('i.outlet_id', $outlet_id);
        $this->db->where(array('b.customer_id' => $customer_id, 'a.VDate >=' => $start, 'a.VDate <=' => $end));
        $this->db->where('a.IsAppove', 1);
        $this->db->order_by('a.id', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function customerledger_searchdata_cheque($customer_id, $start, $end)
    {
        $this->db->select('a.*,b.HeadName,c.*');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->join('cus_cheque c', 'a.cheque_id=c.cheque_id');
        $this->db->where(array('b.customer_id' => $customer_id, 'a.VDate >=' => $start, 'a.VDate <=' => $end));
        $this->db->where('a.IsAppove', 1);
        $this->db->order_by('a.VDate', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }





    public function getCreditCustomerList($postData = null)
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
            $searchQuery = " (a.customer_name like '%" . $searchValue . "%' or a.customer_mobile like '%" . $searchValue . "%' or a.customer_email like '%" . $searchValue . "%'or a.phone like '%" . $searchValue . "%' or a.customer_address like '%" . $searchValue . "%' or a.country like '%" . $searchValue . "%' or a.state like '%" . $searchValue . "%' or a.zip like '%" . $searchValue . "%' or a.city like '%" . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->having('balance > 0');
        $this->db->group_by('a.customer_id');
        $totalRecords = $this->db->get()->num_rows();

        ## Total number of record with filtering
        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->having('balance > 0');
        $this->db->group_by('a.customer_id');
        $totalRecordwithFilter = $this->db->get()->num_rows();

        ## Fetch records
        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->having('balance > 0');
        $this->db->group_by('a.customer_id');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";

            $balance = $record->balance;


            if ($this->permission1->method('manage_customer', 'update')->access()) {
                $button .= '<a href="' . $base_url . 'Ccustomer/customer_update_form/' . $record->customer_id . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
            }
            if ($this->permission1->method('manage_customer', 'delete')->access()) {
                $button .= ' <a href="' . $base_url . 'Ccustomer/customer_delete/' . $record->customer_id . '" class="btn btn-danger  btn-xs" onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
            }


            $data[] = array(
                'sl'                => $sl,
                'customer_name'    => html_escape($record->customer_name),
                'address2'         => html_escape($record->address2),
                'mobile'           => html_escape($record->customer_mobile),
                'address'          => html_escape($record->customer_address),
                'phone'            => html_escape($record->phone),
                'email'            => html_escape($record->customer_email),
                'email_address'    => html_escape($record->email_address),
                'contact'          => html_escape($record->contact),
                'fax'              => html_escape($record->fax),
                'city'             => html_escape($record->city),
                'state'            => html_escape($record->state),
                'zip'              => html_escape($record->zip),
                'country'          => html_escape($record->country),
                'balance'          => (!empty($balance) ? html_escape($balance) : 0),
                'button'           => $button,

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



    public function getPaidCustomerList($postData = null)
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
            $searchQuery = " (a.customer_name like '%" . $searchValue . "%' or a.customer_mobile like '%" . $searchValue . "%' or a.customer_email like '%" . $searchValue . "%') ";
        }

        ## Total number of records without filtering
        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->having('balance <= 0');
        $this->db->group_by('a.customer_id');

        $totalRecords = $this->db->get()->num_rows();

        ## Total number of record with filtering
        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->having('balance <= 0');
        $this->db->group_by('a.customer_id');
        $totalRecordwithFilter = $this->db->get()->num_rows();

        ## Fetch records
        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->having('balance <= 0');
        $this->db->group_by('a.customer_id');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";

            $balance = $record->balance;


            if ($this->permission1->method('manage_customer', 'update')->access()) {
                $button .= '<a href="' . $base_url . 'Ccustomer/customer_update_form/' . $record->customer_id . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
            }
            if ($this->permission1->method('manage_customer', 'delete')->access()) {
                $button .= '<a href="' . $base_url . 'Ccustomer/customer_delete/' . $record->customer_id . '" class="btn btn-danger btn-xs " onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';
            }


            $data[] = array(
                'sl'              => $sl,
                'customer_name'    => html_escape($record->customer_name),
                'address2'         => html_escape($record->address2),
                'mobile'           => html_escape($record->customer_mobile),
                'address'          => html_escape($record->customer_address),
                'phone'            => html_escape($record->phone),
                'email'            => html_escape($record->customer_email),
                'email_address'    => html_escape($record->email_address),
                'contact'          => html_escape($record->contact),
                'fax'              => html_escape($record->fax),
                'city'             => html_escape($record->city),
                'state'            => html_escape($record->state),
                'zip'              => html_escape($record->zip),
                'country'          => html_escape($record->country),
                'balance'          => (!empty($balance) ? html_escape($balance) : 0),
                'button'           => $button,

            );
            $sl++;
        }

        ## Response
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );

        return $response;
    }

    public function count_credit_customer()
    {
        ## Fetch records
        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        $this->db->group_by('a.customer_id');
        $this->db->having('balance > 0');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    public function count_paid_customer()
    {
        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        $this->db->group_by('a.customer_id');
        $this->db->having('balance <= 0');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }



    //Count customer
    public function customer_entry($data)
    {

        //  echo '<pre>';print_r($data);exit();
        $this->db->select('*');
        $this->db->from('customer_information');
        if (isset($data['customer_mobile'])) {
            $this->db->where('customer_mobile', $data['customer_mobile']);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            $this->db->insert('customer_information', $data);
            return TRUE;
        }
    }

    //Customer Previous balance adjustment
    public function previous_balance_add($balance, $customer_id)
    {
        $this->load->library('auth');
        $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id', $customer_id)->get()->row();
        $headn = $customer_id . '-' . $cusifo->customer_name;
        $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
        $customer_headcode = $coainfo->HeadCode;
        $transaction_id = $this->auth->generator(10);


        // Customer debit for previous balance
        $cosdr = array(
            'VNo'            =>  $transaction_id,
            'Vtype'          =>  'PR Balance',
            'VDate'          =>  date("Y-m-d"),
            'COAID'          =>  $customer_headcode,
            'Narration'      =>  'Customer debit For ' . $cusifo->customer_name,
            'Debit'          =>  $balance,
            'Credit'         =>  0,
            'IsPosted'       => 1,
            'CreateBy'       => $this->session->userdata('user_id'),
            'CreateDate'     => date('Y-m-d H:i:s'),
            'IsAppove'       => 1
        );
        $inventory = array(
            'VNo'            =>  $transaction_id,
            'Vtype'          =>  'PR Balance',
            'VDate'          =>  date("Y-m-d"),
            'COAID'          =>  10107,
            'Narration'      =>  'Inventory credit For Old sale For' . $cusifo->customer_name,
            'Debit'          =>  0,
            'Credit'         =>  $balance, //purchase price asbe
            'IsPosted'       => 1,
            'CreateBy'       => $this->session->userdata('user_id'),
            'CreateDate'     => date('Y-m-d H:i:s'),
            'IsAppove'       => 1
        );


        if (!empty($balance)) {
            $this->db->insert('acc_transaction', $cosdr);
            $this->db->insert('acc_transaction', $inventory);
        }
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

    //Retrieve customer Edit Data
    public function retrieve_customer_editdata($customer_id)
    {
        $this->db->select('*');
        $this->db->from('customer_information');
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Retrieve customer Personal Data
    public function customer_personal_data($customer_id)
    {
        $this->db->select('*');
        $this->db->from('customer_information');
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Retrieve customer Invoice Data
    public function customer_invoice_data($customer_id)
    {
        $this->db->select('*');
        $this->db->from('customer_ledger');
        $this->db->where(array('customer_id' => $customer_id, 'receipt_no' => NULL, 'status' => 1));
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //Retrieve customer Receipt Data
    public function customer_receipt_data($customer_id)
    {
        $this->db->select('*');
        $this->db->from('customer_ledger');
        $this->db->where(array('customer_id' => $customer_id, 'invoice_no' => NULL, 'status' => 1));
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }



    //Update Categories
    public function update_customer($data, $customer_id)
    {
        $this->db->where('customer_id', $customer_id);
        $this->db->update('customer_information', $data);
        $this->db->select('*');
        $this->db->from('customer_information');
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $json_customer[] = array('label' => $row->customer_name, 'value' => $row->customer_id);
        }
        $cache_file = './my-assets/js/admin_js/json/customer.json';
        $customerList = json_encode($json_customer);
        file_put_contents($cache_file, $customerList);
        return true;
    }



    // custromer invoicedetails delete
    public function delete_invoicedetails($customer_id)
    {
        $this->db->where('customer_id', $customer_id);
        $this->db->delete('invoice_details');
    }

    // custromer invoice delete
    public function delete_invoic($customer_id)
    {
        $this->db->where('customer_id', $customer_id);
        $this->db->delete('invoice');
    }


    // Delete customer Item
    public function delete_customer($customer_id, $customer_head)
    {
        $this->db->where('HeadName', $customer_head);
        $this->db->delete('acc_coa');
        $this->db->where('customer_id', $customer_id);
        $this->db->delete('customer_information');

        $this->db->select('*');
        $this->db->from('customer_information');
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $json_customer[] = array('label' => $row->customer_name, 'value' => $row->customer_id);
        }
        $cache_file = './my-assets/js/admin_js/json/customer.json';
        $customerList = json_encode($json_customer);
        file_put_contents($cache_file, $customerList);
        return true;
    }


    public function headcode()
    {
        $query = $this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020301%'");
        return $query->row();
    }




    // Customer list
    public function customer_list_advance($customer_id = null)
    {
        $this->db->select('*');
        $this->db->from('customer_information');
        if ($customer_id) {
            $this->db->where('customer_id', $customer_id);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function advance_details($transaction_id, $customer_id)
    {
        // $headcode = $this->db->select('HeadCode')->from('acc_coa')->where('customer_id', $customer_id)->get()->row();
        return $this->db->select('*')
            ->from('acc_transaction')
            ->where('VNo', $transaction_id)
            ->LIKE('COAID', '1020301', 'after')
            ->get()
            ->result_array();
    }



    //Credit Customer Search List
    public function credit_customer_search_item($customer_id)
    {
        $this->db->distinct('customer_id');
        $this->db->select('*');
        $this->db->from('customer_information');
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    //autocomplete part
    public function customer_search($customer_id, $sale_type)
    {
        $query =
            $this->db->select('*')->from('customer_information')
            ->where('cus_type', $sale_type)
            ->group_start()
            ->like('customer_name', $customer_id)
            ->or_like('customer_name_bn', $customer_id)
            ->or_like('customer_mobile', $customer_id)
            ->or_like('customer_id_two', $customer_id)
            ->group_end()
            ->limit(30)
            ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // Customer Card List
    public function card_list()
    {
        $this->db->select('*');
        $this->db->from('card_types');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
