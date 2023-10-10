<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crm extends CI_Controller
{

    public $menu;

    function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('auth');
        $this->load->library('lcrm');
        $this->auth->check_admin_auth();
    }

    public function index()
    {
        $content = $this->lcrm->card_add_form();
        $this->template->full_admin_html_view($content);
    }
    // Check Card Existance
    public function checkCard()
    {
        $card_name = ($this->input->post('name') != '') ? $this->input->post('name') : '';
        $this->db->select('*');
        $this->db->from('card_types');
        $this->db->where('name', $card_name);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $isExist =  $query->result_array();
        }
        if ($isExist) {
            $response = [
                'status'   => 1,
                'message' => 'Exist !'
    
            ];
        }
        else{
            $response = [
                'status'   => 0,
                'message' => 'Not Exist !'
    
            ];
        }
        echo json_encode($response);   
    }
    public function insert_card()
    {
       
        $data = array(
            'name'  => $this->input->post('name', TRUE),
            'status'          => 1,
            'created_date' => date('Y-m-d H:i:s'),
            'created_by'       => $this->session->userdata('user_id')
        );
        $this->db->insert('card_types', $data);
        $this->session->set_userdata(array('message' => display('successfully_added')));
        if (isset($_POST['add_card'])) {
            redirect(base_url('Crm/manage_card'));
            exit;
        } else {
            $this->session->set_userdata(array('error_message' => display('please_try_again')));
            redirect(base_url('Crm'));
            exit;
        }
    }
    //Manage Card
    public function manage_card()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lcrm');
        $content = $this->lcrm->card_list();
        $this->template->full_admin_html_view($content);
    }
    public function CardList()
    {
        $this->load->model('Crm_Model');
        $postData = $this->input->post();
        $data = $this->Crm_Model->getCardList($postData);
        echo json_encode($data);
    }
    //customer Update Form
    public function card_update_form($card_id)
    {
        $content = $this->lcrm->card_edit_data($card_id);
        $this->template->full_admin_html_view($content);
    }
     // customer Update
     public function card_update()
     {
         $this->load->model('Crm_Model');
         $card_id = $this->input->post('id', TRUE);
         $data = array(
             'name' => $this->input->post('name', TRUE),
         );
         $result = $this->Crm_Model->update_card($data, $card_id);
         if ($result == TRUE) {
             $this->session->set_userdata(array('message' => display('successfully_updated')));
             redirect(base_url('crm/manage_card'));
             exit;
         } else {
             $this->session->set_userdata(array('error_message' => display('please_try_again')));
             redirect(base_url('crm'));
         }
     }
    // product_delete
    public function card_delete($card_id)
    {
        $this->load->model('Crm_Model');
            $this->Crm_Model->delete_card($card_id);
            $this->session->set_userdata(array('message' => display('successfully_delete')));
            redirect(base_url('crm/manage_card'));
        
    }
    // Crm earning Setting
    public function earning_setting()
    {
        $content = $this->lcrm->earning_setting_form();
        $this->template->full_admin_html_view($content);
    }
    public function CheckProductList()
    {
        // GET data
        $this->load->model('Crm_Model');
        $postData = $this->input->post();
        $data = $this->Crm_Model->getProductList($postData);
        echo json_encode($data);
    }
    public function getBurningProductList()
    {
        // GET data
        $this->load->model('Crm_Model');
        $postData = $this->input->post();
        $data = $this->Crm_Model->getBurningProductList($postData);
        echo json_encode($data);
    }
    public function storeEarningSetting()
    {
        $CI = &get_instance();
        $CI->load->model('Crm_Model');
        $product_id = $this->input->post('product_id', true);
        $add_data = array(
            'points' => $this->input->post('point', true),
            'created_date' => date('Y-m-d'),
            'created_by'       => $this->session->userdata('user_id')
        );
         $this->db->insert('crm_setting', $add_data);
        $ID = $this->db->insert_id();
        // Card Map
        foreach ($this->input->post('card_id', true) as $key=> $value) {
            $cardData[] = array(
                'crm_setting_id'     => $ID,
                'card_id' => $value,
            );
        }
        $this->db->insert_batch('crm_card_map', $cardData);


        foreach ($product_id as $key=> $value) {
                $postData[] = array(
                    'crm_setting_id'     => $ID,
                    'product_id' => $value,
                );
            }
        $result = $this->db->insert_batch('crm_product_map', $postData);
        echo json_encode($result);

    }
     //Manage Card
     public function manage_earning_setting()
     {
         $CI = &get_instance();
         $this->auth->check_admin_auth();
         $CI->load->library('lcrm');
         $content = $this->lcrm->earning_setting_view();
         $this->template->full_admin_html_view($content);
     }
     public function Earning_list()
     {
         $this->load->model('Crm_Model');
         $postData = $this->input->post();
         $data = $this->Crm_Model->getEarninglist($postData);
         echo json_encode($data);
     }
     public function edit_earning_setting($setting_id)
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Crm_Model');
        $data['title'] = 'Edit Earning Setting';
        $CI->load->model('Categories');
        $CI->load->model('Customers');
        $data['category_list']  = $CI->Categories->cates();
        $data['card_list']= $CI->Customers->card_list();
        $data['card_map']= $CI->Crm_Model->getCardMap($setting_id);
        $data['edit_data'] = $this->Crm_Model->getEditData($setting_id);
        $content = $this->parser->parse('crm/edit_earning_setting', $data, true);
        $this->template->full_admin_html_view($content);
    }
    public function CheckProductList_Setting_wise()
    {
        // GET data
        $this->load->model('Crm_Model');
        $postData = $this->input->post();
        $data = $this->Crm_Model->CheckProductList_Setting_wise($postData);
        echo json_encode($data);
    }
    public function getCards()
    {
        $this->load->model('Crm_Model');
        $card_info   = $this->input->post('term', TRUE);
        $card_data   = $this->Crm_Model->getCardData($card_info);
      

        if (!empty($card_data)) {
            foreach ($card_data as $card) {
                $card_json[] = array(
                    'label' => $card['name'],
                    'id'    => $card['id']
                );
            }
        } else {
            $card_json[] = 'No Card Found';
        }
        echo json_encode($card_json);
    }
    public function updateEarningSetting()
    {
        // echo "<pre>";
        // print_r($this->input->post());
        // exit();
        $CI = &get_instance();
        $CI->load->model('Crm_Model');
        $product_id = $this->input->post('product_id', true);
        $edit_data = array(
            'points' => $this->input->post('point', true),
            'updated_date' => date('Y-m-d'),
            'updated_by'       => $this->session->userdata('user_id')
        );
        $this->Crm_Model->updateData($edit_data, 'crm_setting', 'id', $this->input->post('entity_id'));
        if ($this->input->post('entity_id')) {
            $this->db->where('crm_setting_id', $this->input->post('entity_id'));
            $this->db->delete('crm_card_map');
        }
        foreach ($this->input->post('card_id', true) as $key=> $value) {
            $cardData[] = array(
                'crm_setting_id'     => $this->input->post('entity_id'),
                'card_id' => $value,
            );
        }
        $this->db->insert_batch('crm_card_map', $cardData);
        if ($this->input->post('entity_id')) {
            $this->db->where('crm_setting_id', $this->input->post('entity_id'));
            $this->db->delete('crm_product_map');
        }
        foreach ($product_id as $key=> $value) {
                $postData[] = array(
                    'crm_setting_id'     => $this->input->post('entity_id'),
                    'product_id' => $value,
                );
            }
        $result = $this->db->insert_batch('crm_product_map', $postData);
        echo json_encode($result);

    }
    // Burning Setting
    public function burning_setting()
    {
        $content = $this->lcrm->burning_setting_form();
        $this->template->full_admin_html_view($content);
    }
    public function storeBurningSetting()
    {
        $CI = &get_instance();
        $CI->load->model('Crm_Model');
        $product_id = $this->input->post('product_id', true);
        $data = array(
            'eligible_points'     => $this->input->post('eligible_points', true),
        );
        $this->Crm_Model->updateData($data, 'web_setting', 'setting_id', 1);
        $add_data = array(
            'points' => $this->input->post('point', true),
            'percentage' => $this->input->post('percentage', true),
            'created_date' => date('Y-m-d'),
            'created_by'       => $this->session->userdata('user_id')
        );
         $this->db->insert('burning_setting', $add_data);
         $ID = $this->db->insert_id();
        // Card Map
        foreach ($this->input->post('card_id', true) as $key=> $value) {
            $cardData[] = array(
                'crm_setting_id'     => $ID,
                'card_id' => $value,
            );
        }
        $this->db->insert_batch('burning_card_map', $cardData);


        foreach ($product_id as $key=> $value) {
                $postData[] = array(
                    'crm_setting_id'     => $ID,
                    'product_id' => $value,
                );
            }
        $result = $this->db->insert_batch('burning_product_map', $postData);
        echo json_encode($result);
    }
    //Manage Burning Setting
    public function manage_burning_setting()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lcrm');
        $content = $this->lcrm->burning_setting_view();
        $this->template->full_admin_html_view($content);
    }
    public function Burning_list()
     {
         $this->load->model('Crm_Model');
         $postData = $this->input->post();
         $data = $this->Crm_Model->getBurninglist($postData);
         echo json_encode($data);
     }
     public function edit_burning_setting($setting_id)
     {
         $CI = &get_instance();
         $this->auth->check_admin_auth();
         $CI->load->model('Crm_Model');
         $CI->load->model('Web_settings');
         $data['title'] = 'Edit Burning Setting';
         $CI->load->model('Categories');
         $CI->load->model('Customers');
         $data['category_list']  = $CI->Categories->cates();
         $data['card_list']= $CI->Customers->card_list();
         $data['card_map']= $CI->Crm_Model->getBurningCardMap($setting_id);
         $data['edit_data'] = $this->Crm_Model->getBurningEditData($setting_id);
         $data['eligible_points']= $CI->Web_settings->retrieve_setting_editdata()[0]['eligible_points'];
        //  echo "<pre>";
        //  print_r($data);
        //  exit();
         $content = $this->parser->parse('crm/edit_burning_setting', $data, true);
         $this->template->full_admin_html_view($content);
     }
     public function CheckProductList_BurningSetting_wise()
     {
         // GET data
         $this->load->model('Crm_Model');
         $postData = $this->input->post();
         $data = $this->Crm_Model->CheckProductList_BurningSetting_wise($postData);
         echo json_encode($data);
     }
     public function updateBurningSetting()
     {
        //  echo "<pre>";
        //  print_r($this->input->post());
        //  exit();
         $CI = &get_instance();
         $CI->load->model('Crm_Model');
         $product_id = $this->input->post('product_id', true);
         $data = array(
            'eligible_points'     => $this->input->post('eligible_points', true)
        );
        $this->Crm_Model->updateData($data, 'web_setting', 'setting_id', 1);
         $edit_data = array(
             'points' => $this->input->post('point', true),
             'percentage' => $this->input->post('percentage', true),
             'updated_date' => date('Y-m-d'),
             'updated_by'       => $this->session->userdata('user_id')

         );
         $this->Crm_Model->updateData($edit_data, 'burning_setting', 'id', $this->input->post('entity_id'));
         if ($this->input->post('entity_id')) {
             $this->db->where('crm_setting_id', $this->input->post('entity_id'));
             $this->db->delete('burning_card_map');
         }
         foreach ($this->input->post('card_id', true) as $key=> $value) {
             $cardData[] = array(
                 'crm_setting_id' => $this->input->post('entity_id'),
                 'card_id' => $value,
             );
         }
         $this->db->insert_batch('burning_card_map', $cardData);
         if ($this->input->post('entity_id')) {
             $this->db->where('crm_setting_id', $this->input->post('entity_id'));
             $this->db->delete('burning_product_map');
         }
         foreach ($product_id as $key=> $value) {
                 $postData[] = array(
                     'crm_setting_id'     => $this->input->post('entity_id'),
                     'product_id' => $value,
                 );
             }
         $result = $this->db->insert_batch('burning_product_map', $postData);
         echo json_encode($result);
 
     }
     // Earning Burning Report 
        public function earning_burning_report()
        {
            $content = $this->lcrm->earning_burning_report();
            $this->template->full_admin_html_view($content);
        }
        public function getCrmReport()
    {
        // GET data
        $this->load->model('Crm_Model');
        $postData = $this->input->post();
        $data = $this->Crm_Model->getCrmReport($postData);
        echo json_encode($data);
    }
     // Product Earning Report
     public function product_earning_report()
      {
            $content = $this->lcrm->product_earning_report();
            $this->template->full_admin_html_view($content);
      }   
     public function getProductEarningReport()
       {
            $this->load->model('Crm_Model');
            $postData = $this->input->post();
            $data = $this->Crm_Model->getProductEarningReport($postData);
            echo json_encode($data);
       }
        // Product Burning Report
        public function product_burning_report()
        {
            $content = $this->lcrm->product_burning_report();
            $this->template->full_admin_html_view($content);
        }
        public function getProductBurningReport()
        {
            $this->load->model('Crm_Model');
            $postData = $this->input->post();
            $data = $this->Crm_Model->getProductBurningReport($postData);
            echo json_encode($data);
        }
        // Invoice Wise Report 
        public function invoice_report()
        {
            $content = $this->lcrm->invoice_report();
            $this->template->full_admin_html_view($content);
        }
        public function getInvoiceReport()
    {
        // GET data
        $this->load->model('Crm_Model');
        $postData = $this->input->post();
        $data = $this->Crm_Model->getInvoiceReport($postData);
        echo json_encode($data);
    }
}
