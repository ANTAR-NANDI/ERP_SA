<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ccwarehouse extends CI_Controller
{

    public $menu;

    function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('Lwarehouse');
        $this->load->library('session');
        $this->load->model('Warehouse');
        $this->load->model('Customers');
        $this->auth->check_admin_auth();
    }

    //Default loading for Category system.
    public function index()
    {
        $content = $this->lwarehouse->category_add_form();
        $this->template->full_admin_html_view($content);
    }

    //Manage category form
    public function manage_category()
    {
        $content = $this->lwarehouse->category_list();
        $this->template->full_admin_html_view($content);
    }

    //Insert category and upload
    public function insert_category()
    {
        $courier_id = $this->auth->generator(15);

        $data = array(
            'warehouse_id'   => $courier_id,
            'central_warehouse' => $this->input->post('category_name', TRUE),
            'status'        => 1
        );

        $result = $this->Warehouse->category_entry($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));

            redirect(base_url('Ccwarehouse'));
        } else {
            $this->session->set_userdata(array('error_message' => display('already_inserted')));
            redirect(base_url('Ccwarehouse'));
        }
    }

    //Category Update Form
    public function category_update_form($courier_id)
    {
        $content = $this->lwarehouse->category_edit_data($courier_id);
        $this->template->full_admin_html_view($content);
    }

    // Category Update
    public function category_update()
    {
        $this->load->model('Warehouse');
        $courier_id = $this->input->post('courier_id', TRUE);
        $data = array(
            'central_warehouse' => $this->input->post('category_name', TRUE),
            'status'        => 1,
        );



        $this->Warehouse->update_category($data, $courier_id);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Ccwarehouse'));
    }

    // Category delete
    public function category_delete($courier_id)
    {
        $this->load->model('Warehouse');
        $this->Warehouse->delete_category($courier_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect(base_url('Ccwarehouse'));
    }
    //csv upload
    function uploadCsv_category()
    {
        $filename = $_FILES['upload_csv_file']['name'];
        $ext = end(explode('.', $filename));
        $ext = substr(strrchr($filename, '.'), 1);
        if ($ext == 'csv') {
            $count = 0;
            $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");

            if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {

                while ($csv_line = fgetcsv($fp, 1024)) {
                    //keep this if condition if you want to remove the first row
                    for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                        $insert_csv = array();
                        $insert_csv['category_name'] = (!empty($csv_line[0]) ? $csv_line[0] : null);
                    }

                    $categorydata = array(
                        'warehouse_id'      => $this->auth->generator(15),
                        'central_warehouse'    => $insert_csv['central_warehouse'],
                        'status'           => 1
                    );


                    if ($count > 0) {
                        $this->db->insert('product_category', $categorydata);
                    }
                    $count++;
                }
            }
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect(base_url('Ccategory'));
        } else {
            $this->session->set_userdata(array('error_message' => 'Please Import Only Csv File'));
            redirect(base_url('Ccategory'));
        }
    }
    // category pdf download
    public function category_downloadpdf()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $CI->load->model('Web_settings');
        $CI->load->model('Invoices');
        $CI->load->library('pdfgenerator');
        $category_list = $CI->Warehouse->category_list();
        if (!empty($category_list)) {
            $i = 0;
            if (!empty($category_list)) {
                foreach ($category_list as $k => $v) {
                    $i++;
                    $category_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('manage_category'),
            'category_list' => $category_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
            'company_info'  => $company_info
        );
        $this->load->helper('download');
        $content = $this->parser->parse('category/category_list_pdf', $data, true);
        $time = date('Ymdhi');
        $dompdf = new DOMPDF();
        $dompdf->load_html($content);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/' . 'category' . $time . '.pdf', $output);
        $file_path = 'assets/data/pdf/' . 'category' . $time . '.pdf';
        $file_name = 'category' . $time . '.pdf';
        force_download(FCPATH . 'assets/data/pdf/' . $file_name, null);
    }
    public function branch()
    {
        $content = $this->lwarehouse->branch_add_form();
        $this->template->full_admin_html_view($content);
    }

    //Manage outlet form
    public function manage_branch()
    {
        $content = $this->lwarehouse->branch_list();
        $this->template->full_admin_html_view($content);
    }

    //Insert outlet and upload
    public function insert_branch()
    {
        $branch_id = $this->auth->generator(15);
        $warehouse_id = $this->input->post('warehouse_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);
        $customer_id = $this->input->post('customer_id', TRUE);
        $bin = $this->input->post('bin', TRUE);
        $mushak = $this->input->post('mushak', TRUE);
        $phn_no = $this->input->post('phn_no', TRUE);
        $outlet_name = $this->Customers->customer_personal_data($customer_id);
        if ($_FILES['thumbnail_img']['name']) {
            //Chapter chapter add start
            $config['upload_path']   = './my-assets/image/outlet/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('thumbnail_img')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                redirect(base_url('Ccwarehouse'));
            } else {

                $imgdata = $this->upload->data();
                $image = $config['upload_path'] . $imgdata['file_name'];
                $config['image_library']  = 'gd2';
                $config['source_image']   = $image;
                $config['create_thumb']   = false;
                $config['maintain_ratio'] = TRUE;
                $config['width']          = 100;
                $config['height']         = 100;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $image_url = base_url() . $image;
            }
        }

        $data = array(
            'outlet_id'   => $branch_id,
            'warehouse_id'   => $warehouse_id,
            'user_id' => $user_id,
            'bin' => $bin,
            'mushak' => $mushak,
            'phn_no' => $phn_no,
            'customer_id' => $customer_id,
            'outlet_name' => $customer_id,
            'image'    => (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png')),
            'address' => $this->input->post('address', TRUE),
            'status'        => 1
        );

        // echo '<pre>'; print_r($data); exit();

        $result = $this->Warehouse->branch_entry($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));

            redirect(base_url('Ccwarehouse/branch'));
        } else {
            $this->session->set_userdata(array('error_message' => display('already_inserted')));
            redirect(base_url('Ccwarehouse/branch'));
        }
    }

    //Outlet Update Form
    public function branch_update_form($courier_id)
    {
        $content = $this->lwarehouse->branch_edit_data($courier_id);
        $this->template->full_admin_html_view($content);
    }

    // Outlet Update
    public function branch_update()
    {
        $this->load->model('Warehouse');
        $outlet_id = $this->input->post('outlet_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);
        $data = array(
            'outlet_name' => $this->input->post('outlet_name', TRUE),
            'bin' => $this->input->post('bin', TRUE),
            'mushak' => $this->input->post('mushak', TRUE),
            'phn_no' => $this->input->post('phn_no', TRUE),
            'user_id'        => $user_id,
            'address' => $this->input->post('address', TRUE),
            'status'        => 1,
        );



        $this->Warehouse->update_branch($data, $outlet_id);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Ccwarehouse/branch'));
    }

    // Category delete
    public function branch_delete($courier_id)
    {
        $this->load->model('Warehouse');
        $this->Warehouse->delete_branch($courier_id);
        $this->session->set_userdata(array('message' => display('successfully_delete')));
        redirect(base_url('Ccwarehouse/branch'));
    }
    //csv upload
    function uploadCsv_branch()
    {
        $filename = $_FILES['upload_csv_file']['name'];
        $ext = end(explode('.', $filename));
        $ext = substr(strrchr($filename, '.'), 1);
        if ($ext == 'csv') {
            $count = 0;
            $fp = fopen($_FILES['upload_csv_file']['tmp_name'], 'r') or die("can't open file");

            if (($handle = fopen($_FILES['upload_csv_file']['tmp_name'], 'r')) !== FALSE) {

                while ($csv_line = fgetcsv($fp, 1024)) {
                    //keep this if condition if you want to remove the first row
                    for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                        $insert_csv = array();
                        $insert_csv['category_name'] = (!empty($csv_line[0]) ? $csv_line[0] : null);
                    }

                    $categorydata = array(
                        'outlet_id'      => $this->auth->generator(15),
                        'outlet_name'    => $insert_csv['outlet_name'],
                        'status'           => 1
                    );


                    if ($count > 0) {
                        $this->db->insert('product_category', $categorydata);
                    }
                    $count++;
                }
            }
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect(base_url('Ccwarehouse'));
        } else {
            $this->session->set_userdata(array('error_message' => 'Please Import Only Csv File'));
            redirect(base_url('Ccwarehouse'));
        }
    }
    // category pdf download
    public function branch_downloadpdf()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $CI->load->model('Web_settings');
        $CI->load->model('Invoices');
        $CI->load->library('pdfgenerator');
        $category_list = $CI->Warehouse->branch_list();
        if (!empty($category_list)) {
            $i = 0;
            if (!empty($category_list)) {
                foreach ($category_list as $k => $v) {
                    $i++;
                    $category_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('manage_category'),
            'category_list' => $category_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
            'company_info'  => $company_info
        );
        $this->load->helper('download');
        $content = $this->parser->parse('category/category_list_pdf', $data, true);
        $time = date('Ymdhi');
        $dompdf = new DOMPDF();
        $dompdf->load_html($content);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/' . 'category' . $time . '.pdf', $output);
        $file_path = 'assets/data/pdf/' . 'category' . $time . '.pdf';
        $file_name = 'category' . $time . '.pdf';
        force_download(FCPATH . 'assets/data/pdf/' . $file_name, null);
    }
}
