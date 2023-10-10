<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cvat extends CI_Controller {

    public $menu;

    function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('Lwarehouse');
        $this->load->library('session');
        $this->load->model('Warehouse');
        $this->load->model('Customers');
        $this->load->model('Products');
        $this->auth->check_admin_auth();
    }

    public function get_all_product() {


        $product = $this->Products->get_all_product();

        $data=form_dropdown("product_id[]",$product,1, 'class="selectpicker product_id form-control" data-live-search="true" multiple="multiple" id="py_cat"' ) ;

        $data['products']  =$data;

        //$data2['txnmber']        = $num_column;
        echo json_encode($data);
    }

    //Default loading for Category system.
    public function index() {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $CI->load->model('Purchases');
        $CI->load->model('Categories');
        $CI->load->model('Products');


        $data = array(
            'title'     => 'Vat & Tax',
//            'outlet_list'     =>  $CI->Warehouse->get_outlet_user(),
            'cw'            => $CI->Warehouse->central_warehouse(),
            'categories'            => $CI->Categories->cates(),
            'products'            => $CI->Products->allproductlist(),
            'access'  => '',

        );

        // echo "<pre>";
        // print_r($data);
        // exit();

        $view = $this->parser->parse('vat_tax/add_vat_tax', $data, true);
        $this->template->full_admin_html_view($view);
    }

    public function getProductbyCategory()
    {
        $this->load->model('Products');
        $cat_id = $this->input->post('cat_id');
        $html = '';
        if (!empty($cat_id)) {
            $result =  $this->Products->get_product_by_category($cat_id[0]);
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $html .= '<optgroup label="' . $value[0]->category_name . '">';
                    foreach ($value as $k => $val) {
                        $html .= '<option value=' . $val->product_id . '>' . $val->product_name . '</option>';
                    }
                    $html .= '</optgroup>';
                }
            }
        }

        echo $html;
    }



    //Product Update Form
    public function vat_tax_edit($product_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->vat_tax_data($product_id);
        $this->template->full_admin_html_view($content);
    }

    public function CheckVatProductList()
    {
        // GET data
        $this->load->model('Products');
        $postData = $this->input->post();
        $data = $this->Products->getVatProductList($postData);
        echo json_encode($data);
    }


    //Manage category form
    public function manage_vat() {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $CI->load->model('Purchases');


        $data = array(
            'title'     => 'Manage Vat & Tax',

        );



        $view = $this->parser->parse('vat_tax/manage_vat', $data, true);
        $this->template->full_admin_html_view($view);

    }

    public function insert_vat()
    {

        $product_id = $this->input->post('product_id', TRUE);
        $vat_tax      = $this->input->post('vat_tax', TRUE);
        $vat_tax_type      = $this->input->post('vat_tax_type', TRUE);
            $percent      = $this->input->post('percent', TRUE);




      //  echo '<pre>';print_r($_POST);exit();

        for ($i = 0; $i < count($product_id); $i++) {
            $pr_id = $product_id[$i];


            $check_product=$this->db->select('*')
                ->from('vat_tax_setting')
                ->where(array(
                    'vat_tax'=>$vat_tax,
                    'product_id'=>$pr_id,
                ))->get()->num_rows();



            $data2 = array(
                'vat_tax'      => $vat_tax,
                'vat_tax_type'      =>$vat_tax_type,
                'percent'      =>$percent,
                'product_id	'      => $pr_id,

            );

            if ($check_product > 0){
                $this->db->where(array('vat_tax'=>$vat_tax,'product_id'=>$pr_id));
                $result=$this->db->update('vat_tax_setting', $data2);
            }else{
                $result=  $this->db->insert('vat_tax_setting', $data2);
            }
          //   echo '<pre>';print_r($data2);exit();
//




        }


        if ($result == true){
            $this->session->set_userdata(array('message' => display('successfully_added')));

            redirect(base_url('Cvat/manage_vat'));

        }else{
            $this->session->set_userdata(array('error_message' => display('please_try_again')));

            redirect(base_url('Cvat'));
        }


    }

    //Category Update Form
    public function category_update_form($courier_id) {
        $content = $this->lwarehouse->category_edit_data($courier_id);
        $this->template->full_admin_html_view($content);
    }

    // Category Update
    public function category_update() {
        $this->load->model('Warehouse');
        $courier_id = $this->input->post('courier_id',TRUE);
        $data = array(
            'central_warehouse' => $this->input->post('category_name',TRUE),
            'status'        => 1,
        );



        $this->Warehouse->update_category($data, $courier_id);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Ccwarehouse'));
    }

    // Category delete
    public function category_delete($courier_id) {
        $this->load->model('Warehouse');
        $this->Warehouse->delete_category($courier_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
         redirect(base_url('Ccwarehouse'));
    }




}
