<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cproduct extends CI_Controller
{

    public $product_id;

    function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->model('Suppliers');
        $this->load->library('auth');
    }

    //Index page load
    public function index()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->product_add_form();
        $this->template->full_admin_html_view($content);
    }

    //Barcode Print

    public function barcode_print()
    {
        $CI = &get_instance();
        $CI->load->model('Warehouse');
        $CI->load->model('Purchases');


        $data = array(
            'title'     => 'Barcode Print',
            'outlet_list'     =>  $CI->Warehouse->get_outlet_user(),
            'cw'            => $CI->Warehouse->central_warehouse(),
            'access'  => '',

        );



        $view = $this->parser->parse('product/barcode_print', $data, true);
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
                        <td style=\"width: 10%\">
                                $product_details->sku
                        </td>
						<td class=\"\" style=\"width: 30%\">

                            $product_details->product_name

							<input type=\"hidden\" class=\"form-control autocomplete_hidden_value product_id_" . $product_details->product_id . "\" name=\"product_id[]\" id=\"SchoolHiddenId_" . $product_details->product_id . "\" value = \"$product_details->product_id\"/>
                            <input type=\"hidden\" name=\"purchase_price[]\" class=\"purchase_price_" . $product_details->product_id . " form-control text-right\" id=\"purchase_price_" . $product_details->product_id . "\" placeholder=\"0.00\" min=\"0\" value='" . $product_details->purchase_price_ecom . "'/>

						</td>	
							<td class=\"\" style=\"width: 10%\">

                            $product_details->price

						
						</td>	
							<td class=\"\" style=\"width: 10%\">

                            $product_details->purchase_price

						
						</td>

                        <td>
                            <input type=\"hidden\" name=\"sku[]\" class=\"sku_" . $product_details->product_id . " form-control text-left\" id=\"sku_" . $product_details->product_id . "\" placeholder=\"sku\" min=\"0\" value='" . $product_details->sku . "'/>
                            <input type=\"text\" name=\"category_name[]\" class=\"category_name_" . $product_details->product_id . " form-control text-left\" id=\"category_name_" . $product_details->product_id . "\" placeholder=\"Category Name\" min=\"0\" value=''/>
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



    public function insert_barcode_print()
    {
        $CI = &get_instance();
        $CI->load->library('zend');
        $CI->zend->load('Zend/Barcode');
        $barcode_id = mt_rand();

        $date = date('Y-m-d');
        $sku = $this->input->post('sku', TRUE);
        $category_name = $this->input->post('category_name', TRUE);
        $product_id = $this->input->post('product_id', TRUE);
        $quantity = array_filter($this->input->post('p_qty', TRUE));

        // echo '<pre>';print_r($purchase_price);exit();

        if (empty($quantity)) {
            $this->session->set_userdata(array('error_message' => 'Quantity  Required!!'));
            redirect(base_url('Cproduct/barcode_print'));
            exit();
        }



        $data1 = array(
            'barcode_id'   => $barcode_id,
            'date'      => $this->input->post('date', TRUE),
            'total_product'      => count(array_filter($quantity, function ($x) {
                return !empty($x);
            })),

        );


        // echo '<pre>';print_r($data1);exit();
        $this->db->insert('barcode_print', $data1);


        for ($i = 0; $i < count($product_id); $i++) {
            $pr_id = $product_id[$i];
            $qty = $quantity[$i];
            $cat_name = $category_name[$i];
            $sk = $sku[$i];



            $file = Zend_Barcode::draw('code128', 'image', array('text' => $sk), array());
            $code = time() . $pr_id;
            $store_image = imagepng($file, "my-assets/image/barcode/{$code}.png");
            $barcode_url = base_url() . "my-assets/image/barcode/{$code}.png";

            $data2 = array(
                'barcode_details_id'    => mt_rand(),
                'barcode_id'           => $barcode_id,
                'product_id'        => $pr_id,
                'category_name'        => $cat_name,
                'quantity'        => $qty,
                'create_date'            => $date,
                'barcode_url'         => $barcode_url,

            );


            // echo '<pre>';print_r($data1);
            if (!empty($qty)) {
                $this->db->insert('barcode_print_details', $data2);
            }
        }


        redirect(base_url('Cproduct/barcode_print_html/' . $barcode_id));
    }


    public function barcode_print_html($barcode_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->barcode_print_html($barcode_id);
        $this->template->full_admin_html_view($content);
    }
    //Sync Product
    public function insert_finished_product_ecom()
    {


        $this->db->where('finished_raw', 1)->delete('product_information');

        $url = api_url() . "products/get_products_all";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        $records = json_decode($resp);

        //echo '<pre>';print_r($records);exit();
        //
        $data2 = array();
        foreach ($records as $r) {

            if ($r->discount_type == 'percent') {
                $discount_price = $r->unit_price - ($r->unit_price * ($r->discount / 100));
            } else {
                $discount_price = $r->unit_price - $r->discount;
            }
            $image_url = ecom_url() . 'public/' . $r->thumbnail_img;
            $data2['product_id']   = $r->product_id;
            $data2['category_id']  = $r->cats;
            $data2['brand_id']  = '';
            $data2['product_name'] = $r->name;
            $data2['finished_raw']  = 1;
            $data2['price']        = $r->unit_price;
            $data2['purchase_price_ecom']        = $r->purchase_price;
            $data2['purchase_price']        = $discount_price;
            $data2['unit']         = $r->unit;
            $data2['sku']  = $r->sku;
            $data2['tax']          = 0;
            $data2['product_details'] = '';
            $data2['image']        = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));
            $data2['status']       = 1;
            $data2['created_date']       =  $r->created_at;
            $result =  $this->db->insert('product_information', $data2);

            //            $check_product = $this->db->select('product_id')->from('product_information')->where('product_id', $r->sku)->get()->row();
            //            if (!empty($check_product)) {
            //                $this->db->where('product_id', $r->sku);
            //                $result= $this->db->update('product_information', $data2);
            //            }else{
            //                $result=  $this->db->insert('product_information', $data2);
            //
            //            }



        }

        $this->session->set_userdata(array('message' => 'Synchronized Successfully'));
        redirect(base_url('Cproduct/manage_product'));
    }
    //Insert Product and uload
    public function insert_product()
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $CI->load->model('Products');

        $product_id = (!empty($this->input->post('product_id', TRUE)) ? $this->input->post('product_id', TRUE) : $this->generator(8));
        $check_product = $this->db->select('*')->from('product_information')->where('product_id', $product_id)->get()->num_rows();
        if ($check_product > 0) {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            redirect(base_url('Cproduct'));
        }

        $sku = (!empty($this->input->post('sku', TRUE)) ? $this->input->post('sku', TRUE) : $this->generator(8));
        $check_sku = $this->db->select('*')->from('product_information')->where('sku', $sku)->get()->num_rows();
        if ($check_sku > 0) {
            $this->session->set_userdata(array('error_message' => "Product ID already exists"));
            redirect(base_url('Cproduct'));
        }

        $product_id_two = $this->input->post('product_id_two', TRUE);

        $product_model = $this->input->post('model', TRUE);
        $product_code = $this->input->post('product_code', TRUE);


        $pr_code_list = $CI->Products->all_product_code();




        if ($_FILES['thumbnail_img']['name']) {
            //Chapter chapter add start
            $config['upload_path']   = './my-assets/image/product/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|GIF|JPG|PNG';
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('thumbnail_img')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_userdata(array('error_message' => $this->upload->display_errors()));
                redirect(base_url('Cproduct'));
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

        $price = $this->input->post('sell_price', TRUE);

        $tax_percentage = $this->input->post('tax', TRUE);
        $tax = $tax_percentage / 100;

        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column = count($tablecolumn) - 4;
        if ($num_column > 0) {
            $taxfield = [];
            for ($i = 0; $i < $num_column; $i++) {
                $taxfield[$i] = 'tax' . $i;
            }
            foreach ($taxfield as $key => $value) {
                $data[$value] = $this->input->post($value) / 100;
            }
        }

        $category_id = $this->input->post('category_id', TRUE);
        $size_id = $this->input->post('product_size', TRUE);
        $color = $this->input->post('color', TRUE);

        //        if ($category_id) {
        //            $catsdata = implode(",", $category_id);
        //        } else {
        //            $catsdata = json_encode([]);
        //        }

        if ($size_id) {
            $sizedata = implode(",", $size_id);
        } else {
            $sizedata = json_encode([]);
        }
        if ($color) {
            $colordata = implode(",", $color);
        } else {
            $colordata = json_encode([]);
        }

        $finished_raw = $this->input->post('product_status', TRUE);

        //        if ($finished_raw == 1){
        //            $api_url=api_url();
        //            $url = $api_url."products/last_id";
        //
        //
        //            $curl = curl_init($url);
        //            curl_setopt($curl, CURLOPT_URL, $url);
        //            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //
        ////for debug only!
        //            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        //            $last_id =json_decode(curl_exec($curl));
        //            curl_close($curl);
        //
        //            $product_id=$last_id+1;
        //
        //        }

        $data2['product_id']   = $sku;
        //        $data2['category_id']  = $catsdata;
        $data2['category_id']  =  $this->input->post('category_id', TRUE);
        $data2['brand_id']  = $this->input->post('brand_id', TRUE);
        $data2['product_name'] = $this->input->post('product_name', TRUE);
        $data2['product_name_bn'] = $this->input->post('product_name_bn', TRUE);
        $data2['finished_raw']  = $this->input->post('product_status', TRUE);
        $data2['price']        = $price;
        $data2['unit']         = $this->input->post('unit', TRUE);
        $data2['sku']  = $sku;
        $data2['tax']          = 0;
        $data2['product_details'] = $this->input->post('description', TRUE);
        $data2['image']        = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));
        $data2['status']       = 1;
        $data2['created_date']       =  date('Y-m-d');

        $data['barcode']   = $sku;
        $data['name'] = $this->input->post('product_name', TRUE);
        $data['added_by'] = 'ERP';
        //        $data['cats']  = $catsdata;
        $data['brand_id']  = $this->input->post('brand_id', TRUE);
        $data['video_provider']  = $this->input->post('video_provider', TRUE);
        $data['video_link']  = $this->input->post('video_link', TRUE);
        $data['tags']  = $this->input->post('tags', TRUE);
        $data['sku']  = $sku;
        $data['description']  = $this->input->post('description', TRUE);
        $data['product_summary']  = $this->input->post('summery', TRUE);
        $data['information']  = $this->input->post('additional_information', TRUE);
        $data['tc']  = $this->input->post('additional_terms', TRUE);
        $data['variations']  = $sizedata;
        $data['colors']  = $colordata;
        $data['product_status']  = $this->input->post('product_status', TRUE);
        $data['unit']         = $this->input->post('unit', TRUE);
        $data['min_qty']         = $this->input->post('min_qty', TRUE);
        $data['tax']          = 0;
        $data['unit_price']        = $price;
        $data['refundable'] = $this->input->post('refund', TRUE);
        $data['thumbnail_img']        = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));

        //echo '<pre>';print_r($data);exit();

        $result = $CI->lproduct->insert_product($data, $data2);


        if ($result == 1) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            if (isset($_POST['add-product'])) {
                redirect(base_url('Cproduct/manage_product'));
                exit;
            } elseif (isset($_POST['add-product-another'])) {
                redirect(base_url('Cproduct'));
                exit;
            }
        } else {
            $this->session->set_userdata(array('error_message' => display('product_model_already_exist')));
            redirect(base_url('Cproduct'));
        }
    }

    //Product Update Form
    public function product_update_form($product_id)
    {
        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->product_edit_data($product_id);
        $this->template->full_admin_html_view($content);
    }

    // Product Update
    public function product_update()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit();

        $CI = &get_instance();
        $CI->auth->check_admin_auth();
        $CI->load->model('Products');
        $config = array(
            'upload_path'   => "./my-assets/image/product/",
            'allowed_types' => "png|jpg|jpeg|gif|bmp|tiff",
            'overwrite'     => TRUE,
            'encrypt_name' => TRUE,
            'max_size'      => '0',
        );
        $image_data = array();
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('image')) {
            $image_data = $this->upload->data();
            $image_name = base_url() . "my-assets/image/product/" . $image_data['file_name'];
            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_data['full_path'];
            $config['maintain_ratio'] = TRUE;
            $config['height'] = '100';
            $config['width'] = '100';
            $this->load->library('image_lib', $config);
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            if (!$this->image_lib->resize()) {
                echo $this->image_lib->display_errors();
            }
        } else {
            $image_name = $this->input->post('old_image', TRUE);
        }
        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column = count($tablecolumn) - 4;
        if ($num_column > 0) {
            $taxfield = [];
            for ($i = 0; $i < $num_column; $i++) {
                $taxfield[$i] = 'tax' . $i;
            }
            foreach ($taxfield as $key => $value) {
                $data[$value] = $this->input->post($value) / 100;
            }
        }
        // echo "<pre>";
        // print_r($image_name);
        // exit();



        $product_id = $this->input->post('product_id', TRUE);
        $product_name = $this->input->post('product_name', TRUE);
        $product_name_bn = $this->input->post('product_name_bn', TRUE);
        $unit = $this->input->post('unit', TRUE);
        $price = $this->input->post('sell_price', TRUE);
        $brand_id = $this->input->post('brand_id', TRUE);

        $product_type = $this->input->post('product_status', TRUE);
        $sku = $this->input->post('sku', TRUE);
        $details = $this->input->post('description', TRUE);
        $summary =
            $this->input->post('summery', TRUE);
        $additional_information =
            $this->input->post('additional_information', TRUE);
        $term_condition =
            $this->input->post('additional_terms', TRUE);
        $min_qty = $this->input->post('min_qty', TRUE);
        $tags = $this->input->post('tags', TRUE);
        $refund =
            $this->input->post('refund', TRUE);
        $video_provider = $this->input->post('video_provider', TRUE);
        $video_link        = $this->input->post('video_link', TRUE);
        $size_id = $this->input->post('product_size', TRUE);
        $color = $this->input->post('color', TRUE);
        $category_id = $this->input->post('category_id', TRUE);

        //        if ($category_id) {
        //            $catsdata = implode(",", $category_id);
        //        } else {
        //            $catsdata = json_encode([]);
        //        }

        if ($size_id) {
            $sizedata = implode(",", $size_id);
        } else {
            $sizedata = json_encode([]);
        }
        if ($color) {
            $colordata = implode(",", $color);
        } else {
            $colordata = json_encode([]);
        }

        $data2['barcode']   = $product_id;
        $data2['brand_id']  = $brand_id;
        $data2['name'] = $product_name;
        $data2['added_by'] = 'ERP';
        $data['finished_raw']  = $product_type;
        $data['price']        = $price;
        //        $data2['cats']  = $catsdata;
        $data2['unit']         = $unit;
        $data2['tax']          = 0;
        $data['product_details'] = $details;
        $data2['thumbnail_img']        = (!empty($image_url) ? $image_url : base_url('my-assets/image/product.png'));
        $data['status']       = 1;
        $data2['product_summary']  = $summary;
        $data2['information']  = $additional_information;
        $data2['tc']  = $term_condition;
        $data2['video_provider']  = $video_provider;
        $data2['video_link']  = $video_link;
        $data2['description']  = $details;


        $data['product_id']   = $product_id;
        $data['product_name'] = $product_name;
        $data['category_id']  = $category_id;
        $data['image']          = $image_name;
        $data['unit']          = $unit;
        $data['product_name_bn'] = $product_name_bn;
        $data['brand_id']  = $brand_id;
        $data2['tags']  = $tags;
        $data2['sku']  = $sku;
        $data2['variations']  = $sizedata;
        $data2['colors']  = $colordata;
        $data2['product_status']  = $product_type;
        $data2['unit']         = $unit;
        $data2['min_qty']         = $min_qty;
        $data['tax']          = 0;
        $data2['unit_price']        = $price;
        $data2['refundable'] = $refund;
        $data2['thumbnail_img']          = $image_name;

        $vat_tax_id = $this->input->post('vat_tax_id');
        $percent = $this->input->post('percent');
        $vat_type = $this->input->post('vat_tax_type_vat');
        $tax_type = $this->input->post('vat_tax_type_tax');

        //  echo '<pre>';print_r($_POST);exit();

        for ($i = 0; $i < count($vat_tax_id); $i++) {
            $vat_id = $vat_tax_id[$i];
            $perc = $percent[$i];

            $check_type = $this->db->select('*')
                ->from('vat_tax_setting')
                ->where(array(
                    'id' => $vat_id,
                ))->get()->row()->vat_tax;

            if ($check_type == 'vat') {
                $data_vat = array(
                    'vat_tax_type' => $vat_type,
                    'percent'      => $perc,


                );
            }
            if ($check_type == 'tax') {
                $data_vat = array(
                    'vat_tax_type' => $tax_type,
                    'percent'      => $perc,


                );
            }


            $this->db->where(array('id' => $vat_id,));
            $result = $this->db->update('vat_tax_setting', $data_vat);
        }
        //         echo "<pre>";
        //         print_r($data);
        //         //print_r($data2);
        //         exit();

        $result = $CI->Products->update_product($data, $data2, $product_id);
        if ($result == true) {
            $this->session->set_userdata(array('message' => display('successfully_updated')));
            redirect(base_url('Cproduct/manage_product'));
        } else {
            $this->session->set_userdata(array('error_message' => 'Somthing went wrong!'));
            redirect(base_url('Cproduct/manage_product'));
        }
    }

    //Manage Product
    public function manage_product()
    {

        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $CI->load->model('Products');
        $content = $this->lproduct->product_list();
        $this->template->full_admin_html_view($content);
    }

    public function manage_finished_product()
    {

        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $CI->load->model('Products');


        $content = $this->lproduct->finished_product_list();
        $this->template->full_admin_html_view($content);
    }

    public function CheckProductList()
    {
        // GET data
        $this->load->model('Products');
        $postData = $this->input->post();
        $data = $this->Products->getProductList($postData);
        echo json_encode($data);
    }


    public function CheckFinishedProductList()
    {
        // GET data
        $this->load->model('Products');
        $postData = $this->input->post();
        $data = $this->Products->getFinishedProductList($postData);
        echo json_encode($data);
    }
    //Add Product CSV
    public function add_product_csv()
    {
        $CI = &get_instance();
        $data = array(
            'title' => display('add_product_csv')
        );
        $content = $CI->parser->parse('product/add_product_csv', $data, true);
        $this->template->full_admin_html_view($content);
    }

    //CSV Upload File
    function uploadCsv()
    {
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
                    for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                        $insert_csv = array();
                        // print_r($csv_line[1]);
                        // exit();
                        $product_id = $this->generator(10);
                        $insert_csv['color_id']   = '';
                        $insert_csv['size_id']   = '';
                        $insert_csv['category_id']   = '';


                        $insert_csv['product_name_bn']    = (!empty($csv_line[0]) ? $csv_line[0] : null);
                        $insert_csv['product_name']    = (!empty($csv_line[1]) ? $csv_line[1] : null);
                        $insert_csv['ptype_id']    = (!empty($csv_line[2]) ? $csv_line[2] : null);
                        $insert_csv['product_code']    = (!empty($csv_line[3]) ? $csv_line[3] : null);
                        $insert_csv['product_model']    = (!empty($csv_line[4]) ? $csv_line[4] : null);
                        $insert_csv['color_id']   = (!empty($csv_line[5]) ? $csv_line[5] : null);
                        $insert_csv['size_id']   = (!empty($csv_line[6]) ? $csv_line[6] : null);
                        // $insert_csv['quantity']   = (!empty($csv_line[6]) ? $csv_line[6] : null);
                        $insert_csv['unit']    = (!empty($csv_line[7]) ? $csv_line[7] : null);
                        $insert_csv['trxn_unit']    = (!empty($csv_line[8]) ? $csv_line[8] : null);
                        $insert_csv['unit_multiplier']    = (!empty($csv_line[9]) ? $csv_line[9] : null);
                        $insert_csv['category_id']  = (!empty($csv_line[10]) ? $csv_line[10] : null);
                        $insert_csv['supplier_id']    = (!empty($csv_line[11]) ? $csv_line[11] : null);
                        $insert_csv['supplier_price']    = (!empty($csv_line[12]) ? $csv_line[12] : null);
                        $insert_csv['price']    = (!empty($csv_line[13]) ? $csv_line[13] : null);
                        $insert_csv['finished_raw']    = (!empty($csv_line[14]) ? $csv_line[14] : 0);
                        // // $insert_csv['re_order_level']    = (!empty($csv_line[10])?$csv_line[10]:null);
                        // $insert_csv['price']          = (!empty($csv_line[10]) ? $csv_line[10] : null);
                        // $insert_csv['supplier_price'] = (!empty($csv_line[11]) ? $csv_line[11] : null);

                    }

                    echo "<pre>";
                    print_r($$insert_csv);
                    exit();

                    $check_supplier = $this->db->select('*')->from('supplier_information')->where('supplier_name', $insert_csv['supplier_id'])->get()->row();
                    if (!empty($check_supplier)) {
                        $supplier_id = $check_supplier->supplier_id;
                    } else {
                        $supplierinfo = array(
                            'supplier_name' => $insert_csv['supplier_id'],
                            'address'           => '',
                            'mobile'            => '',
                            'details'           => '',
                            'status'            => 1
                        );
                        if ($count > 0) {
                            $this->db->insert('supplier_information', $supplierinfo);
                        }
                        $supplier_id = $this->db->insert_id();
                        $coa = $this->suppliers->headcode();
                        if ($coa->HeadCode != NULL) {
                            $headcode = $coa->HeadCode + 1;
                        } else {
                            $headcode = "502020001";
                        }
                        $c_acc = $supplier_id . '-' . $insert_csv['supplier_id'];
                        $createby = $this->session->userdata('user_id');
                        $createdate = date('Y-m-d H:i:s');


                        $supplier_coa = [
                            'HeadCode'         => $headcode,
                            'HeadName'         => $c_acc,
                            'PHeadName'        => 'Account Payable',
                            'HeadLevel'        => '3',
                            'IsActive'         => '1',
                            'IsTransaction'    => '1',
                            'IsGL'             => '0',
                            'HeadType'         => 'L',
                            'IsBudget'         => '0',
                            'IsDepreciation'   => '0',
                            'supplier_id'      => $supplier_id,
                            'DepreciationRate' => '0',
                            'CreateBy'         => $createby,
                            'CreateDate'       => $createdate,
                        ];

                        if ($count > 0) {
                            $this->db->insert('acc_coa', $supplier_coa);
                        }
                    }

                    $category_id = null;

                    $check_category = $this->db->select('*')->from('product_category')->where('category_name', $insert_csv['category_id'])->get()->row();
                    if (!empty($check_category)) {
                        $category_id = $check_category->category_id;
                    } else {
                        $category_id = $this->auth->generator(15);
                        $categorydata = array(
                            'category_id' => $category_id,
                            'category_name' => $insert_csv['category_id'],
                            'status' => 1
                        );
                        if ($count > 0) {
                            $this->db->insert('product_category', $categorydata);
                        }
                    }

                    $color_id = null;
                    if (!empty(($insert_csv['color_id']))) {
                        $check_color = $this->db->select('*')->from('color_list')->where('color_name', $insert_csv['color_id'])->get()->row();
                        if (!empty($check_color)) {
                            $color_id = $check_color->color_id;
                        } else {
                            $color_id = $this->auth->generator(15);
                            $categorydata = array(
                                'color_id' => $color_id,
                                'color_name' => $insert_csv['color_id'],
                                'status' => 1
                            );
                            if ($count > 0) {
                                $this->db->insert('color_list', $categorydata);
                            }
                        }
                    }

                    $size_id = null;
                    if (!empty($insert_csv['size_id'])) {
                        $check_size = $this->db->select('*')->from('size_list')->where('size_name', $insert_csv['size_id'])->get()->row();
                        if (!empty($check_size)) {
                            $size_id = $check_size->size_id;
                        } else {
                            $size_id = $this->auth->generator(15);
                            $categorydata = array(
                                'size_id' => $size_id,
                                'size_name' => $insert_csv['size_id'],
                                'status' => 1
                            );
                            if ($count > 0) {
                                $this->db->insert('size_list', $categorydata);
                            }
                        }
                    }

                    $check_ptype = $this->db->select('*')->from('product_type')->where('ptype_name', $insert_csv['ptype_id'])->get()->row();

                    if (!empty($check_ptype)) {

                        $ptype_id = $check_ptype->ptype_id;
                    } else {
                        $ptype_id = $this->auth->generator(15);
                        $ptypedata = array(
                            'ptype_id' => $ptype_id,
                            'ptype_name' => $insert_csv['ptype_id'],
                            'status' => 1
                        );
                        if ($count > 0) {

                            $this->db->insert('product_type', $ptypedata);
                        }
                    }




                    // $check_brand = $this->db->select('*')->from('product_brand')->where('brand_name', $insert_csv['brand_id'])->get()->row();
                    // if (!empty($check_brand)) {
                    //     $brand_id = $check_brand->brand_id;
                    // } else {
                    //     $brand_id = $this->auth->generator(15);
                    //     $branddata = array(
                    //         'brand_id' => $brand_id,
                    //         'brand_name' => $insert_csv['brand_id'],
                    //         'status' => 1
                    //     );
                    //     if ($count > 0) {
                    //         $this->db->insert('product_brand', $branddata);
                    //     }
                    // }

                    $data = array(
                        'product_id'    => $product_id,
                        //'product_id'    => $insert_csv['product_id'],
                        'category_id'   => $category_id,
                        // 'brand_id'      => $brand_id,
                        'ptype_id'      => $ptype_id,
                        'product_name'  => $insert_csv['product_name'],
                        'product_name_bn'  => $insert_csv['product_name_bn'],
                        // 'product_id_two' => $insert_csv['product_id_two'],
                        'product_model' => $insert_csv['product_model'],
                        'price'         => $insert_csv['price'],
                        'product_code'         => $insert_csv['product_code'],
                        'size'         => $size_id,
                        'color'         => $color_id,
                        // 're_order_level'=> $insert_csv['re_order_level'],
                        'unit'          => $insert_csv['unit'],
                        'trxn_unit'           => $insert_csv['trxn_unit'],
                        'unit_multiplier'           => $insert_csv['unit_multiplier'],
                        'finished_raw'           => $insert_csv['finished_raw'],
                        'tax'           => '',
                        'product_details' => 'Csv Product',
                        'image'         => base_url('my-assets/image/product.png'),
                        'status'        => 1,
                        'created_date'      =>  date('Y-m-d')
                    );

                    if ($count > 0) {

                        $result = $this->db->select('*')
                            ->from('product_information')
                            ->where('product_name', $data['product_name'])
                            ->where('product_model', $data['product_model'])
                            ->where('ptype_id', $ptype_id)
                            ->where('category_id', $category_id)
                            ->where('size', $insert_csv['size_id'])
                            ->where('color',  $insert_csv['color_id'])
                            // ->where('brand_id', $brand_id)
                            ->where('ptype_id', $ptype_id)
                            ->get()
                            ->row();
                        if (empty($result)) {
                            $this->db->insert('product_information', $data);
                            $product_id = $product_id;
                        } else {
                            $product_id = $result->product_id;
                            $udata = array(
                                'product_id'     => $result->product_id,
                                // 'product_id_two'     => $insert_csv['product_id_two'],
                                'category_id'    => $category_id,
                                // 'brand_id'    => $brand_id,
                                'ptype_id'    => $ptype_id,
                                'product_name'   => $result->product_name,
                                'product_name_bn'   =>  $insert_csv['product_name_bn'],
                                'product_model'  => $insert_csv['product_model'],
                                'price'          => $insert_csv['price'],
                                'product_code'         => $insert_csv['product_code'],
                                'size'         => $insert_csv['size_id'],
                                'color'         => $insert_csv['color_id'],
                                'unit'           => $insert_csv['unit'],
                                'trxn_unit'           => $insert_csv['trxn_unit'],
                                'unit_multiplier'           => $insert_csv['unit_multiplier'],
                                'finished_raw'           => $insert_csv['finished_raw'],

                                //  're_order_level' => $insert_csv['re_order_level'],
                                'tax'            => '',
                                'product_details' => 'Csv Uploaded Product',
                                'image'         => base_url('my-assets/image/product.png'),
                                'status'        => 1
                            );
                            $this->db->where('product_id', $result->product_id);
                            $this->db->update('product_information', $udata);
                        }

                        $supp_prd = array(
                            //'product_id'     => $insert_csv['product_id'],
                            'product_id' => $product_id,
                            'supplier_id'    => $supplier_id,
                            // 'product_id_two' => $insert_csv['product_id_two'],
                            'supplier_price' => $insert_csv['supplier_price'],
                            'products_model' => $insert_csv['product_model'],
                        );

                        // $splprd = $this->db->select('*')
                        //      ->from('supplier_product')
                        //      ->where('supplier_id', $supplier_id)
                        //      ->where('product_id', $product_id)
                        //      ->get()
                        //      ->num_rows();
                        $this->db->insert('supplier_product', $supp_prd);
                        // if (!empty($splprd)) {

                        // }else{
                        //     $supp_prd = array(
                        //         'supplier_id'    => $supplier_id,
                        //         'supplier_price' => $insert_csv['supplier_price'],
                        //         'products_model' => $insert_csv['product_model']
                        //     );
                        //     $this->db->where('product_id', $product_id);
                        //     $this->db->where('supplier_id', $supplier_id);
                        //     $this->db->update('supplier_product', $supp_prd);
                        // }
                        $data_service = array(

                            'service_name' => $insert_csv['product_name'],
                            // 'description' =>$this->input->post('description',TRUE)


                        );


                        $this->db->insert('product_service', $data_service);
                    }
                    $count++;
                }
            }

            $this->db->select('*');
            $this->db->from('product_information');
            $this->db->where('status', 1);
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
            }
            $cache_file = './my-assets/js/admin_js/json/product.json';
            $productList = json_encode($json_product);
            file_put_contents($cache_file, $productList);
            fclose($fp) or die("can't close file");
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect(base_url('Cproduct/manage_product'));
        } else {
            $this->session->set_userdata(array('error_message' => 'Please Import Only Csv File'));
            redirect(base_url('Cproduct/manage_product'));
        }
    }

    function uploadCsv_new()
    {
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
                    for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                        $insert_csv = array();
                        // print_r($csv_line[1]);
                        // exit();
                        // $product_id = $this->generator(8);

                        $insert_csv['product_name_bn'] = (!empty($csv_line[0]) ? $csv_line[0] : null);
                        $insert_csv['product_name'] = (!empty($csv_line[1]) ? $csv_line[1] : null);
                        $insert_csv['product_id'] = (!empty($csv_line[2]) ? $csv_line[2] : $this->generator(8));
                        $insert_csv['category_id'] = (!empty($csv_line[3]) ? $csv_line[3] : null);
                        $insert_csv['brand_id'] = (!empty($csv_line[4]) ? $csv_line[4] : null);
                        $insert_csv['unit'] = (!empty($csv_line[5]) ? $csv_line[5] : null);
                        $insert_csv['price'] = (!empty($csv_line[6]) ? $csv_line[6] : 0);
                        $insert_csv['finished_raw'] = (!empty($csv_line[7]) ? $csv_line[7] : 0);
                    }

                    $category_id = null;

                    $check_category = $this->db->select('*')->from('cats')->where('name', $insert_csv['category_id'])->get()->row();
                    if (!empty($check_category)) {
                        $category_id = $check_category->id;
                    } else {
                        // $category_id = $this->auth->generator(15);
                        $categorydata = array(
                            // 'id' => $category_id,
                            'name' => $insert_csv['category_id'],
                            'status' => 1
                        );
                        if ($count > 0) {
                            $this->db->insert('cats', $categorydata);
                            $category_id = $this->db->insert_id();
                        }
                    }

                    $check_brand = $this->db->select('*')->from('product_brand')->where('brand_name', $insert_csv['brand_id'])->get()->row();
                    if (!empty($check_brand)) {
                        $brand_id = $check_brand->id;
                    } else {
                        $brand_auto_id = $this->auth->generator(15);
                        $branddata = array(
                            'brand_id' => $brand_auto_id,
                            'brand_name' => $insert_csv['brand_id'],
                            'status' => 1
                        );
                        if ($count > 0) {
                            $this->db->insert('product_brand', $branddata);
                            $brand_id = $this->db->insert_id();
                        }
                    }

                    $check_unit = $this->db->select('*')->from('units')->where(
                        'unit_name',
                        $insert_csv['unit']
                    )->get()->row();
                    if (!empty($check_unit)) {
                        $unit_id = $check_unit->unit_id;
                    } else {
                        $unit_id = $this->auth->generator(15);
                        $unitdata = array(
                            'unit_id' => $unit_id,
                            'unit_name' => $insert_csv['unit'],
                            'status' => 1
                        );
                        if ($count > 0) {
                            $this->db->insert('units', $unitdata);
                        }
                    }

                    $data = array(
                        // 'product_id'    => $product_id,
                        // 'sku'    => $product_id,

                        'product_id' => $insert_csv['product_id'],
                        'sku' => $insert_csv['product_id'],
                        'category_id' => $category_id,
                        'brand_id' => $brand_id,
                        'product_name' => $insert_csv['product_name'],
                        'product_name_bn' => $insert_csv['product_name_bn'],
                        'price' => $insert_csv['price'],
                        'unit' => $insert_csv['unit'],
                        'finished_raw' => $insert_csv['finished_raw'],
                        'tax' => 0,
                        'product_details' => 'Csv Product',
                        'image' => base_url('my-assets/image/product.png'),
                        'status' => 1,
                        'created_date' =>  date('Y-m-d')
                    );

                    if ($count > 0) {

                        $result = $this->db->select('*')
                            ->from('product_information')
                            // ->where('product_name', $data['product_name'])
                            // ->where('category_id', $category_id)
                            // ->where('brand_id', $brand_id)
                            ->where('product_id', $insert_csv['product_id'])
                            ->get()
                            ->row();
                        if (empty($result)) {
                            $this->db->insert('product_information', $data);
                            // $product_id = $product_id;

                            $product_id = $insert_csv['product_id'];
                        } else {
                            $product_id = $result->product_id;
                            $udata = array(
                                // 'product_id'     => $result->product_id,
                                'sku'    => $result->product_id,
                                'category_id'    => $category_id,
                                'brand_id'    => $brand_id,
                                'product_name'   => $result->product_name,
                                'product_name_bn'   =>  $insert_csv['product_name_bn'],
                                'price'          => $insert_csv['price'],
                                'unit'           => $insert_csv['unit'],
                                'finished_raw'   => $insert_csv['finished_raw'],
                                'tax'            => 0,
                                'product_details' => 'Csv Uploaded Product',
                                'image'         => base_url('my-assets/image/product.png'),
                                'status'        => 1
                            );
                            $this->db->where('product_id', $product_id);
                            $this->db->update('product_information', $udata);
                        }
                    }

                    $count++;
                }
            }

            $this->db->select('*');
            $this->db->from('product_information');
            $this->db->where('status', 1);
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $json_product[] = array('label' => $row->product_name . "-(" . $row->product_model . ")", 'value' => $row->product_id);
            }
            $cache_file = './my-assets/js/admin_js/json/product.json';
            $productList = json_encode($json_product);
            file_put_contents($cache_file, $productList);
            fclose($fp) or die("can't close file");
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect(base_url('Cproduct/manage_product'));
        } else {
            $this->session->set_userdata(array('error_message' => 'Please Import Only Csv File'));
            redirect(base_url('Cproduct/manage_product'));
        }
    }



    //Add supplier by ajax
    public function add_supplier()
    {
        $this->load->model('Suppliers');

        $data = array(
            'supplier_id'   => $this->auth->generator(20),
            'supplier_name' => $this->input->post('supplier_name', TRUE),
            'address'       => $this->input->post('address', TRUE),
            'mobile'        => $this->input->post('mobile', TRUE),
            'details'       => $this->input->post('details', TRUE),
            'status'        => 1
        );

        $supplier = $this->Suppliers->supplier_entry($data);

        if ($supplier == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            echo TRUE;
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            echo FALSE;
        }
    }

    // Insert category by ajax
    public function insert_category()
    {
        $this->load->model('Categories');

        $category_id = $this->auth->generator(15);
        $brand_id = $this->auth->generator(15);
        $ptype_id = $this->auth->generator(15);

        //Customer  basic information adding.
        $data = array(
            'category_id'   => $category_id,
            'category_name' => $this->input->post('category_name', TRUE),
            'status'        => 1
        );

        $result = $this->Categories->category_entry($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            echo TRUE;
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            echo FALSE;
        }
    }
    public function insert_brand()
    {
        $this->load->model('Brands');


        $brand_id = $this->auth->generator(15);


        //Customer  basic information adding.
        $data = array(
            'brand_id'   => $brand_id,
            'brand_name' => $this->input->post('brand_name', TRUE),
            'status'        => 1
        );

        $result = $this->Brands->category_entry($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            echo TRUE;
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            echo FALSE;
        }
    }
    public function insert_ptype()
    {
        $this->load->model('Ptype');


        $ptype_id = $this->auth->generator(15);


        //Customer  basic information adding.
        $data = array(
            'ptype_id'   => $ptype_id,
            'ptype_name' => $this->input->post('ptype_name', TRUE),
            'status'        => 1
        );

        $result = $this->Ptype->category_entry($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));
            echo TRUE;
        } else {
            $this->session->set_userdata(array('error_message' => display('already_exists')));
            echo FALSE;
        }
    }

    // product_delete
    public function product_delete($product_id)
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Products');
        $check_calculation = $CI->Products->check_calculaton($product_id);
        if ($check_calculation > 0) {
            $this->session->set_userdata(array('error_message' => display('you_cant_delete_this_product')));
            redirect(base_url('Cproduct/manage_product'));
        } else {
            $result = $CI->Products->delete_product($product_id);
            $this->session->set_userdata(array('message' => display('successfully_delete')));
            redirect(base_url('Cproduct/manage_product'));
        }
    }

    //Retrieve Single Item  By Search
    public function product_by_search()
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $product_id = $this->input->post('product_id', TRUE);

        $content = $CI->lproduct->product_search_list($product_id);
        $this->template->full_admin_html_view($content);
    }

    //Retrieve Single Item  By Search
    public function product_details($product_id)
    {
        $this->product_id = $product_id;
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->product_details($product_id);
        $this->template->full_admin_html_view($content);
    }

    //Retrieve Single Item  By Search
    public function product_sales_supplier_rate($product_id = null, $startdate = null, $enddate = null)
    {
        if ($startdate == null) {
            $startdate = date('Y-m-d', strtotime('-30 days'));
        }
        if ($enddate == null) {
            $enddate = date('Y-m-d');
        }
        $product_id_input = $this->input->post('product_id', TRUE);
        if (!empty($product_id_input)) {
            $product_id = $this->input->post('product_id', TRUE);
            $startdate  = $this->input->post('from_date', TRUE);
            $enddate    = $this->input->post('to_date', TRUE);
        }

        $this->product_id = $product_id;

        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->library('lproduct');
        $content = $CI->lproduct->product_sales_supplier_rate($product_id, $startdate, $enddate);
        $this->template->full_admin_html_view($content);
    }

    //This function is used to Generate Key
    public function generator($lenth)
    {
        $CI = &get_instance();
        $this->auth->check_admin_auth();
        $CI->load->model('Products');

        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }

        $result = $this->Products->product_id_check($con);

        if ($result === true) {
            $this->generator(8);
        } else {
            return $con;
        }
    }

    //Export CSV
    public function exportCSV()
    {
        // file name
        $this->load->model('Products');
        $filename = 'product_' . date('Ymd') . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // get data
        $usersData = $this->Products->product_csv_file();

        // file creation
        $file = fopen('php://output', 'w');

        $header = array('product_id', 'supplier_id', 'category_id', 'brand_id', 'ptype_id', 'product_name', 'price', 'supplier_price', 'unit', 'tax', 'product_model', 'product_details', 'image', 'status');
        fputcsv($file, $header);
        foreach ($usersData as $line) {
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
    }

    // product pdf download
    public function product_downloadpdf()
    {
        $CI = &get_instance();
        $CI->load->model('Products');
        $CI->load->model('Invoices');
        $CI->load->model('Web_settings');
        $CI->load->library('pdfgenerator');
        $product_list = $CI->Products->product_list_pdf();
        if (!empty($product_list)) {
            $i = 0;
            if (!empty($product_list)) {
                foreach ($product_list as $k => $v) {
                    $i++;
                    $product_list[$k]['sl'] = $i + $CI->uri->segment(3);
                }
            }
        }
        $currency_details = $CI->Web_settings->retrieve_setting_editdata();
        $company_info = $CI->Invoices->retrieve_company();
        $data = array(
            'title'         => display('manage_product'),
            'product_list'  => $product_list,
            'currency'      => $currency_details[0]['currency'],
            'logo'          => $currency_details[0]['logo'],
            'position'      => $currency_details[0]['currency_position'],
            'company_info'  => $company_info
        );
        $this->load->helper('download');
        $content = $this->parser->parse('product/product_list_pdf', $data, true);
        $time = date('Ymdhi');
        $dompdf = new DOMPDF();
        $dompdf->load_html($content);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/' . 'product' . $time . '.pdf', $output);
        $file_path = 'assets/data/pdf/' . 'product' . $time . '.pdf';
        $file_name = 'product' . $time . '.pdf';
        force_download(FCPATH . 'assets/data/pdf/' . $file_name, null);
    }


    public function validate_pr_code()
    {
        $CI = &get_instance();
        $CI->load->model('Products');


        $product_code = $this->input->post('pr_code', TRUE);
        // print_r($product_code); exit();

        $pr_code_list = $CI->Products->all_product_code();
        $data = array();
        //  exit();
        foreach ($pr_code_list as $pc) {
            // print_r($pc['product_code']);
            if ($pc['product_code'] == $product_code) {
                $data['found'] = true;
                break;
            }
        }

        echo json_encode($data);
    }

    public function attributes()
    {
        $CI = &get_instance();
        $CI->load->library('lproduct');

        $content = $CI->lproduct->size_home();
        $this->template->full_admin_html_view($content);
    }

    public function insert_attr()
    {

        $this->load->model('Products');

        $size_id = $this->auth->generator(15);

        $data = array(

            'name' => $this->input->post('category_name', TRUE),

        );

        $result = $this->Products->attr_entry($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));

            redirect(base_url('Cproduct/attributes'));
        } else {
            $this->session->set_userdata(array('error_message' => display('already_inserted')));
            redirect(base_url('Cproduct/attributes'));
        }
    }

    public function attr_update_form($size_id)
    {
        $this->load->library('lproduct');
        $content = $this->lproduct->attr_edit_data($size_id);
        $this->template->full_admin_html_view($content);
    }

    public function delete_attr($size_id)
    {
        $this->db->where('id', $size_id);
        $this->db->delete('attributes');
        redirect(base_url('Cproduct/attributes'));
    }

    public function attr_update()
    {
        $this->load->model('Products');
        $size_id = $this->input->post('category_id', TRUE);
        $data = array(
            'name' => $this->input->post('category_name', TRUE),

        );

        $this->Products->update_attr($data, $size_id);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Cproduct/attributes'));
    }

    function uploadCsv_size()
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
                        $insert_csv['size_name'] = (!empty($csv_line[0]) ? $csv_line[0] : null);
                    }

                    $categorydata = array(
                        'size_id'      => $this->auth->generator(15),
                        'size_name'    => $insert_csv['size_name'],
                        'status'           => 1
                    );


                    if ($count > 0) {
                        $this->db->insert('size_list', $categorydata);
                    }
                    $count++;
                }
            }
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect(base_url('Cproduct/size'));
        } else {
            $this->session->set_userdata(array('error_message' => 'Please Import Only Csv File'));
            redirect(base_url('Cproduct/size'));
        }
    }

    public function color()
    {
        $CI = &get_instance();
        $CI->load->library('lproduct');

        $content = $CI->lproduct->color_home();
        $this->template->full_admin_html_view($content);
    }

    public function insert_color()
    {

        $this->load->model('Products');

        $color_id = $this->auth->generator(15);

        $data = array(
            'color_id'   => $color_id,
            'color_name' => $this->input->post('category_name', TRUE),
            'status'        => 1
        );

        $result = $this->Products->color_entry($data);

        if ($result == TRUE) {
            $this->session->set_userdata(array('message' => display('successfully_added')));

            redirect(base_url('Cproduct/color'));
        } else {
            $this->session->set_userdata(array('error_message' => display('already_inserted')));
            redirect(base_url('Cproduct/color'));
        }
    }

    public function color_update_form($color_id)
    {
        $this->load->library('lproduct');
        $content = $this->lproduct->color_edit_data($color_id);
        $this->template->full_admin_html_view($content);
    }

    public function delete_color($color_id)
    {
        $this->db->where('color_id', $color_id);
        $this->db->delete('color_list');
        redirect(base_url('Cproduct/color'));
    }

    public function color_update()
    {
        $this->load->model('Products');
        $color_id = $this->input->post('category_id', TRUE);
        $data = array(
            'color_name' => $this->input->post('category_name', TRUE),
            'status'        => 1,
        );

        $this->Products->update_color($data, $color_id);
        $this->session->set_userdata(array('message' => display('successfully_updated')));
        redirect(base_url('Cproduct/color'));
    }

    function uploadCsv_color()
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
                        $insert_csv['color_name'] = (!empty($csv_line[0]) ? $csv_line[0] : null);
                    }

                    $categorydata = array(
                        'color_id'      => $this->auth->generator(15),
                        'color_name'    => $insert_csv['color_name'],
                        'status'           => 1
                    );


                    if ($count > 0) {
                        $this->db->insert('color_list', $categorydata);
                    }
                    $count++;
                }
            }
            $this->session->set_userdata(array('message' => display('successfully_added')));
            redirect(base_url('Cproduct/color'));
        } else {
            $this->session->set_userdata(array('error_message' => 'Please Import Only Csv File'));
            redirect(base_url('Cproduct/color'));
        }
    }

    public function get_statuswise_category($status)
    {
        $this->load->model('categories');
        $cat_list = $this->categories->cates();

        $html = "<option value=''></option>";

        foreach ($cat_list as $ct) {
            $html .= '<option value="' . $ct['id'] . '">' . $ct['name'] . '</option>';
        }

        echo $html;
    }

    public function get_statuswise_ptype($status)
    {
        $this->load->model('ptype');
        $cat_list = $this->ptype->category_list($status);

        $html = "<option value=''></option>";

        foreach ($cat_list as $ct) {
            $html .= '<option value="' . $ct['ptype_id'] . '">' . $ct['ptype_name'] . '</option>';
        }

        echo $html;
    }
}
