<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lcrm
{
   //Card Add 
    public function card_add_form()
    {
        $CI = &get_instance();
        $data = array(
            'title' => "Add Card"
        );
        $cardForm = $CI->parser->parse('crm/add_card_form', $data, true);
        return $cardForm;
    }

    public function insert_customer($data)
    {
        $CI = &get_instance();
        $CI->load->model('Customers');
        $CI->Customers->customer_entry($data);
        return true;
    }
    //Retrieve Membership Card List
    public function card_list()
    {
        $CI = &get_instance();
        $CI->load->model('Crm_Model');
        $CI->load->model('Web_settings');
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $data['total_card']    = $CI->Crm_Model->count_card();
        $data['currency']          = $currency_details[0]['currency'];
        $data['title']             = "Manage Card";
        $customerList = $CI->parser->parse('crm/card', $data, true);
        return $customerList;
    }
    //customer Edit Data
    public function card_edit_data($card_id)
    {
        $CI = &get_instance();
        $CI->load->model('Crm_Model');
        $card_detail = $CI->Crm_Model->card_edit_data($card_id);
        // echo "<pre>";
        // print_r($card_detail);
        // exit();
        $data = array(
            'title'           => "Card Edit",
            'id'     => $card_detail[0]['id'],
            'name'     => $card_detail[0]['name']
        );
        $chapterList = $CI->parser->parse('crm/edit_card_form', $data, true);
        return $chapterList;
    }
    //Card Add 
    public function earning_setting_form()
    {
        $CI = &get_instance();
        $CI->load->model('Categories');
        $CI->load->model('Customers');
        $category_list = $CI->Categories->cates();
        $data = array(
            'title' => "Earning Setting",
            'category_list' => $category_list,
            'card_list'=> $CI->Customers->card_list()
        );
        $Earning_setting_Form = $CI->parser->parse('crm/earning_setting_form', $data, true);
        return $Earning_setting_Form;
    }
    public function earning_setting_view()
    {
        $CI = &get_instance();
        $CI->load->model('Crm_Model');
        $data['total_earning']    = $CI->Crm_Model->count_earning_setting();
        $data['title']             = "Manage Earning Setting";
        $customerList = $CI->parser->parse('crm/earning_setting', $data, true);
        return $customerList;
    }
    public function burning_setting_form()
    {
        $CI = &get_instance();
        $CI->load->model('Categories');
        $CI->load->model('Customers');
        $CI->load->model('Web_settings');
        $category_list = $CI->Categories->cates();
        $data = array(
            'title' => "Burning Setting",
            'category_list' => $category_list,
            'card_list'=> $CI->Customers->card_list(),
            'eligible_points'=> $CI->Web_settings->retrieve_setting_editdata()[0]['eligible_points']
        );
        $Burning_setting_Form = $CI->parser->parse('crm/burning_setting_form', $data, true);
        return $Burning_setting_Form;
    }
    public function burning_setting_view()
    {
        $CI = &get_instance();
        $CI->load->model('Crm_Model');
        $data['total_burning']    = $CI->Crm_Model->count_burning_setting();
        $data['title']             = "Manage Burning Setting";
        $customerList = $CI->parser->parse('crm/burning_setting', $data, true);
        return $customerList;
    }
    // Earning Burning Report
    public function earning_burning_report()
    {
        $CI = &get_instance();
        $CI->load->model('Customers');
        $data = array(
            'title' => "Earning Burning Report",
            'customer_list'=> $CI->Customers->customer_list()
        );
        $earning_burning_report = $CI->parser->parse('crm/earning_burning_report', $data, true);
        return $earning_burning_report;
    }
    // Product Earning Report
    public function product_earning_report()
    {
        $CI = &get_instance();
        $CI->load->model('Products');
        $data = array(
            'title' => "Product Earning Report",
        );
        $product_earning_report = $CI->parser->parse('crm/product_earning_report', $data, true);
        return $product_earning_report;
    }
    // Product Earning Report
    public function product_burning_report()
    {
        $CI = &get_instance();
        $data = array(
            'title' => "Product Burning Report",
        );
        $product_earning_report = $CI->parser->parse('crm/product_burning_report', $data, true);
        return $product_earning_report;
    }
     //  Invoice Wise Report
     public function invoice_report()
     {
         $CI = &get_instance();
         $CI->load->model('Customers');
         $data = array(
             'title' => "Invoice Report",
             'customer_list'=> $CI->Customers->customer_list()
         );
         $earning_burning_report = $CI->parser->parse('crm/invoice_report', $data, true);
         return $earning_burning_report;
     }

}
