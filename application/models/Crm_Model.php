<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crm_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    //Count Card
    public function count_card()
    {
        return $this->db->count_all("card_types");
    }
    //Count Earning Setting
    public function count_earning_setting()
    {
        return $this->db->count_all("crm_setting");
    }
     //Count Burning Setting
     public function count_burning_setting()
     {
         return $this->db->count_all("burning_setting");
     }

    public function getCardList($postData = null)
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
            $searchQuery = " (a.name like '%" . $searchValue . "%')";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('card_types a');
        $this->db->group_by('a.id');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('card_types a');
        $this->db->group_by('a.id');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("a.*");
        $this->db->from('card_types a');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $button = '';
            $base_url = base_url();
            $jsaction = "return confirm('Are You Sure ?')";
                $button .= '<a href="' . $base_url . 'crm/card_update_form/' . $record->id . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
                $button .= '<a href="' . $base_url . 'crm/card_delete/' . $record->id . '" class="btn btn-danger btn-xs " onclick="' . $jsaction . '"><i class="fa fa-trash"></i></a>';




            $data[] = array(
                'sl'               => $sl,
                'name'    => $record->name,
                'button'           => $button

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
    public function getEarninglist($postData = null)
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
        // if ($searchValue != '') {
        //     $searchQuery = " (a.name like '%" . $searchValue . "%')";
        // }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('crm_setting');
        $this->db->group_by('crm_setting.id');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('crm_setting');
        $this->db->group_by('crm_setting.id');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("crm_setting.*,card_types.name");
        $this->db->from('crm_setting');
        $this->db->join('crm_card_map', 'crm_card_map.crm_setting_id = crm_setting.id', 'left');
        $this->db->join('card_types', 'card_types.id = crm_card_map.card_id', 'left');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        foreach ($records as $row) {
            $id = $row->id;
            
            if (!isset($groupedData[$id])) {
                $groupedData[$id] = array(
                    'id' => $row->id,
                    'points' => $row->points,
                    'names' => array($row->name) // Store the name in an array
                );
            } else {
                $groupedData[$id]['names'][] = $row->name; // Add the name to the array
            }
        }

        $finalData = array_values($groupedData);
        $data = array();
        $sl = 1;

        foreach ($finalData as $record) {
            
            $button = '';
            $base_url = base_url();
                $button .= '<a href="' . $base_url . 'crm/edit_earning_setting/' . $record['id'] . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
            $data[] = array(
                'sl'               => $sl,
                'card_name'    => implode(", ",$record['names']),
                'points'    => $record['points'],
                'button'           => $button

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
    //Retrieve card Edit Data
    public function card_edit_data($card_id)
    {
        $this->db->select('*');
        $this->db->from('card_types');
        $this->db->where('id', $card_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //Update Card Data
    public function update_card($data, $card_id)
    {
        $this->db->where('id', $card_id);
        $this->db->update('card_types', $data);
        return true;
    }
    public function delete_card($card_id)
    {
        $this->db->where('id', $card_id);
        $this->db->delete('card_types');
        return true;
    }
    public function getProductList($postData = null)
    {
        $cardListString = '';
        $idListString = '';
        $category_id = $this->input->post('category_id');
        $card_id = $this->input->post('card_id');
        if($card_id)
        {
            $length = count($card_id);
            $card = $card_id[$length -1];
            $cardListString = implode(',', $card);
        }
       
        if($category_id)
        {
            $length = count($category_id);
            $cat_id = $category_id[$length -1];
            $idListString = implode(',', $cat_id);
        }
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
            $searchQuery = " (a.product_name like '%" . $searchValue . "%' or a.product_name_bn like '%" . $searchValue . "%' or a.product_model like '%" . $searchValue  . "%' or a.sku like '%" . $searchValue . "%' or a.price like'%" . $searchValue . "%'  ) ";
        }
         // Card Type Product Array
         $this->db->select("crm_product_map.*");
         $this->db->from('crm_product_map');
         $this->db->join('crm_card_map', 'crm_card_map.crm_setting_id = crm_product_map.crm_setting_id', 'left');
         $this->db->where_in('crm_card_map.card_id', $cardListString);
         $card_records = $this->db->get()->result();

        ## Total number of records without filtering
        $this->db->select("a.*
                ");
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id',$idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $resultArray = array_udiff($records, $card_records, function($a, $b) {
            return strcmp($a->product_id,$b->product_id);
        });
        $totalRecords = count($resultArray);
        ## Total number of record with filtering
        $this->db->select("a.*
                ");
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id', $idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $resultArray = array_udiff($records, $card_records, function($a, $b) {
            return strcmp($a->product_id,$b->product_id);
        });
        $totalRecordwithFilter = count($resultArray);

        ## Fetch records
        $this->db->select("a.*
                ");
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id', $idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $resultArray = array_udiff($records, $card_records, function($a, $b) {
            return strcmp($a->product_id,$b->product_id);
        });
        $data = array();
        $sl = 1;

        foreach ($resultArray as $record) {
            $chk_product = '<input type="hidden" name="product_id" id="product_id" 
            value="'
                . $record->product_id . '"/>';
            $chk_product .= '<input type="checkbox" name="product_id" class="data-check flat-green" />';
            $data[] = array(
                'chk_product'     => $chk_product,
                'sl'               => $sl,
                'product_name'     => $record->product_name,
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
     //Retrieve card Edit Data
     public function getEditData($setting_id)
     {
         $this->db->select('*');
         $this->db->from('crm_setting');
         $this->db->where('id', $setting_id);
         $query = $this->db->get();
         if ($query->num_rows() > 0) {
             return $query->result_array();
         }
         return false;
     }
    public function CheckProductList_Setting_wise($postData = null)
    {
         ## Fetch records
         $desiredSettingId = $this->input->post('setting_id');
         
        $category_id = $this->input->post('category_id');
        $idListString = '';
        if($category_id)
        {
            $length = count($category_id);
            $cat_id = $category_id[$length -1];
            $idListString = implode(',', $cat_id);
        }
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
            $searchQuery = " (pi.product_name like '%" . $searchValue . "%' or pi.product_name_bn like '%" . $searchValue . "%' or a.product_model like '%" . $searchValue  . "%' or a.sku like '%" . $searchValue . "%' or a.price like'%" . $searchValue . "%'  ) ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id',$idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecords = $records[0]->allcount;


        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id', $idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = $records[0]->allcount;


        ## Fetch records
        $this->db->distinct();
         $this->db->select('pi.product_id,pi.product_name');
         $this->db->select('(CASE WHEN cpm.crm_setting_id IS NOT NULL THEN 1 ELSE 0 END) AS exists_in_crm_product_map', FALSE);
         $this->db->from('product_information pi');
         $this->db->join('cats', 'cats.id = pi.category_id', 'left');
         $this->db->join('crm_product_map cpm', 'pi.product_id = cpm.product_id AND cpm.crm_setting_id = ' . $this->db->escape($desiredSettingId), 'left');
         if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('pi.category_id', $idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        // echo "<pre>";
        // print_r($records);
        // exit();
        $data = array();
        $sl = 1;
        
        foreach ($records as $record) {
            $checkbox = '';
            if ($record->exists_in_crm_product_map == 1) {
                $checkbox = ' checked';
            }

            $chk_product = '<input type="hidden" name="product_id" id="product_id" 
            value="' . $record->product_id . '"/>';
            $chk_product .= '<input type="checkbox" name="product_id" class="data-check flat-green"' . $checkbox . ' />';
            // if($record->exists_in_crm_product_map == 1)
            // {
            //     echo "<pre>";
            //     print_r($chk_product);
            //     exit();
            // }
                        $data[] = array(
                'chk_product'     => $chk_product,
                'sl'               => $sl,
                'product_name'     => $record->product_name,
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
    public function getCardMap($setting_id)
    {
        return $this->db->select('crm_card_map.card_id, card_types.name')
            ->from('crm_card_map')
            ->join('card_types', 'card_types.id = crm_card_map.card_id', 'left')
            ->where('crm_card_map.crm_setting_id', $setting_id)
            ->get()
            ->result();
    }

    public function getCardData($card_info)
    {
        $data = $this->db->select('entity_id, name, mobile')
            ->from('users')
            ->where('status', 1)
            ->group_start()
            ->like('name', $card_info, 'both')
            ->group_end()
            ->limit(50)
            ->get()
            ->result_array();

        return $data;
    }
    public function updateData($Data, $tblName, $fieldName, $ID)
    {
        $this->db->where($fieldName, $ID);
        $this->db->update($tblName, $Data);
        return $this->db->affected_rows();
    }
    public function getBurningProductList($postData = null)
    {
        $cardListString = '';
        $idListString = '';
        $category_id = $this->input->post('category_id');
        $card_id = $this->input->post('card_id');
        if($card_id)
        {
            $length = count($card_id);
            $card = $card_id[$length -1];
            $cardListString = implode(',', $card);
        }
        if($category_id)
        {
            $length = count($category_id);
            $cat_id = $category_id[$length -1];
            $idListString = implode(',', $cat_id);
        }
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
            $searchQuery = " (a.product_name like '%" . $searchValue . "%' or a.product_name_bn like '%" . $searchValue . "%' or a.product_model like '%" . $searchValue  . "%' or a.sku like '%" . $searchValue . "%' or a.price like'%" . $searchValue . "%'  ) ";
        }
         // Card Type Product Array
         $this->db->select("burning_product_map.*");
         $this->db->from('burning_product_map');
         $this->db->join('burning_card_map', 'burning_card_map.crm_setting_id = burning_product_map.crm_setting_id', 'left');
         $this->db->where_in('burning_card_map.card_id', $cardListString);
         $card_records = $this->db->get()->result();
        


        ## Total number of records without filtering
        $this->db->select('*');
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id',$idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
      
        $records = array_udiff($records, $card_records, function($a, $b) {
            return strcmp($a->product_id,$b->product_id);
        });
        $totalRecords = count($records);

        ## Total number of record with filtering
        $this->db->select('*');
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id', $idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $records = array_udiff($records, $card_records, function($a, $b) {
            return strcmp($a->product_id,$b->product_id);
        });
        $totalRecordwithFilter = count($records);
        ## Fetch records
        $this->db->select("a.*");
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id', $idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $resultArray = array_udiff($records, $card_records, function($a, $b) {
            return strcmp($a->product_id,$b->product_id);
        });
        $data = array();
        $sl = 1;

        foreach ($resultArray as $record) {
            $chk_product = '<input type="hidden" name="product_id" id="product_id" 
            value="'
                . $record->product_id . '"/>';
            $chk_product .= '<input type="checkbox" name="product_id" class="data-check flat-green" />';
            $data[] = array(
                'chk_product'     => $chk_product,
                'sl'               => $sl,
                'product_name'     => $record->product_name,
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
    public function getBurninglist($postData = null)
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
        // if ($searchValue != '') {
        //     $searchQuery = " (a.name like '%" . $searchValue . "%')";
        // }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('burning_setting');
        $this->db->group_by('burning_setting.id');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('burning_setting');
        $this->db->group_by('burning_setting.id');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("burning_setting.*,card_types.name");
        $this->db->from('burning_setting');
        $this->db->join('burning_card_map', 'burning_card_map.crm_setting_id = burning_setting.id', 'left');
        $this->db->join('card_types', 'card_types.id = burning_card_map.card_id', 'left');
        if ($searchValue != '')
            $this->db->where($searchQuery);
            
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $groupedData = array();

        foreach ($records as $row) {
            $id = $row->id;
            
            if (!isset($groupedData[$id])) {
                $groupedData[$id] = array(
                    'id' => $row->id,
                    'points' => $row->points,
                    'percentage' => $row->percentage,
                    'status' => $row->status,
                    'names' => array($row->name) // Store the name in an array
                );
            } else {
                $groupedData[$id]['names'][] = $row->name; // Add the name to the array
            }
        }

        $finalData = array_values($groupedData);
        $data = array();
        $sl = 1;
        // echo "<pre>";
        // print_r($finalData);
        // exit();
        foreach ($finalData as $record) {
            
            $button = '';
            $base_url = base_url();
                $button .= '<a href="' . $base_url . 'crm/edit_burning_setting/' . $record['id'] . '" class="btn btn-info btn-xs"  data-placement="left" title="' . display('update') . '"><i class="fa fa-edit"></i></a> ';
            $data[] = array(
                'sl'               => $sl,
                'card_name'    => implode(", ",$record['names']),
                'points'    => $record['points'],
                'percentage'    => $record['percentage'],
                'button'           => $button

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
    public function getBurningCardMap($setting_id)
    {
        return $this->db->select('burning_card_map.card_id, card_types.name')
            ->from('burning_card_map')
            ->join('card_types', 'card_types.id = burning_card_map.card_id', 'left')
            ->where('burning_card_map.crm_setting_id', $setting_id)
            ->get()
            ->result();
    }
    //Retrieve card Edit Data
    public function getBurningEditData($setting_id)
    {
        $this->db->select('*');
        $this->db->from('burning_setting');
        $this->db->where('id', $setting_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function CheckProductList_BurningSetting_wise($postData = null)
    {
        $idListString = '';
        $desiredSettingId = $this->input->post('setting_id');
        $category_id = $this->input->post('category_id');
        if($category_id)
        {
            $idListString = implode(',', $category_id);
        }
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
            $searchQuery = " (a.product_name like '%" . $searchValue . "%' or a.product_name_bn like '%" . $searchValue . "%' or a.product_model like '%" . $searchValue  . "%' or a.sku like '%" . $searchValue . "%' or a.price like'%" . $searchValue . "%'  ) ";
        }
        ## Total number of records without filtering
        $this->db->select('*');
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id',$idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecords = count($records);

        ## Total number of record with filtering
        $this->db->select('*');
        $this->db->from('product_information a');
        $this->db->join('cats', 'cats.id = a.category_id', 'left');
        if ($idListString && $idListString != '') {
			$this->db->group_start();
			$this->db->where_in('a.category_id', $idListString);
			$this->db->group_end();
		}
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $records = $this->db->get()->result();
        $totalRecordwithFilter = count($records);
        ## Fetch records
        $this->db->distinct();
        $this->db->select('pi.product_id,pi.product_name');
        $this->db->select('(CASE WHEN bpm.crm_setting_id IS NOT NULL THEN 1 ELSE 0 END) AS exists_in_burning_product_map', FALSE);
        $this->db->from('product_information pi');
        $this->db->join('cats', 'cats.id = pi.category_id', 'left');
        $this->db->join('burning_product_map bpm', 'pi.product_id = bpm.product_id AND bpm.crm_setting_id = ' . $this->db->escape($desiredSettingId), 'left');
        if ($idListString && $idListString != '') {
           $this->db->group_start();
           $this->db->where_in('pi.category_id', $idListString);
           $this->db->group_end();
       }
       if ($searchValue != '')
       $this->db->where($searchQuery);
       $this->db->order_by($columnName, $columnSortOrder);
       $this->db->limit($rowperpage, $start);
       $records = $this->db->get()->result();
       $data = array();
       $sl = 1;
       
       foreach ($records as $record) {
           $checkbox = '';
           if ($record->exists_in_burning_product_map == 1) {
               $checkbox = ' checked';
           }

           $chk_product = '<input type="hidden" name="product_id" id="product_id" 
           value="' . $record->product_id . '"/>';
           $chk_product .= '<input type="checkbox" name="product_id" class="data-check flat-green"' . $checkbox . ' />';
                       $data[] = array(
               'chk_product'     => $chk_product,
               'sl'               => $sl,
               'product_name'     => $record->product_name,
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
    // Earning Burning Report
    public function getCrmReport($postData = null)
    {
        $response = array();
        $customer_id = $this->input->post('customer_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
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
            $searchQuery = " (card_types.name like '%" . $searchValue . "%' or customer_information.customer_name like '%" . $searchValue . "%' or customer_information.customer_mobile like '%" . $searchValue . "%') ";
        }
         // Total records
         $this->db->select("card_types.name as card_name, customer_information.customer_name, customer_information.customer_mobile,
        (SELECT SUM(ce1.points) FROM customers_earning ce1
        WHERE ce1.type = '1' AND ce1.customer_id = customer_information.customer_id) as earning_points,
        (SELECT SUM(ce2.points) FROM customers_earning ce2
        WHERE ce2.type = '2' AND ce2.customer_id = customer_information.customer_id) as burning_points,
        (SELECT SUM(i.discounted_points_amount) FROM invoice i WHERE i.customer_id = customer_information.customer_id) as burned_amount");
    $this->db->from('customer_information');
    $this->db->join('card_types', 'card_types.id = customer_information.membership_id', 'left');
    $this->db->join('customers_earning', 'customers_earning.customer_id = customer_information.customer_id', 'left');
    $this->db->group_by('customer_information.customer_id'); // Group by customer_id to avoid duplicate rows
        
    if($customer_id)
         {
            $this->db->where('customer_information.customer_id', $customer_id);
         }
         if ($from_date) {
            $this->db->where('customers_earning.created_date  >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('customers_earning.created_date  <=', $to_date);
        }
         $records = $this->db->get()->result();
         $totalRecords = count($records);
         // Total records
         $this->db->select("card_types.name as card_name, customer_information.customer_name, customer_information.customer_mobile,
         (SELECT SUM(ce1.points) FROM customers_earning ce1
         WHERE ce1.type = '1' AND ce1.customer_id = customer_information.customer_id) as earning_points,
         (SELECT SUM(ce2.points) FROM customers_earning ce2
         WHERE ce2.type = '2' AND ce2.customer_id = customer_information.customer_id) as burning_points,
         (SELECT SUM(i.discounted_points_amount) FROM invoice i WHERE i.customer_id = customer_information.customer_id) as burned_amount");
     $this->db->from('customer_information');
     $this->db->join('card_types', 'card_types.id = customer_information.membership_id', 'left');
     $this->db->join('customers_earning', 'customers_earning.customer_id = customer_information.customer_id', 'left');
    $this->db->group_by('customer_information.customer_id'); // Group by customer_id to avoid duplicate rows
         
     if($customer_id)
         {
            $this->db->where('customer_information.customer_id', $customer_id);
         }
         if ($from_date) {
            $this->db->where('customers_earning.created_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('customers_earning.created_date  <=', $to_date);
        }
         $records = $this->db->get()->result();
        $totalRecordwithFilter = count($records);
        ## Fetch records
        $this->db->select("card_types.name as card_name, customer_information.customer_name, customer_information.customer_mobile,
        (SELECT SUM(ce1.points) FROM customers_earning ce1
        WHERE ce1.type = '1' AND ce1.customer_id = customer_information.customer_id) as earning_points,
        (SELECT SUM(ce2.points) FROM customers_earning ce2
        WHERE ce2.type = '2' AND ce2.customer_id = customer_information.customer_id) as burning_points,
        (SELECT SUM(i.discounted_points_amount) FROM invoice i WHERE i.customer_id = customer_information.customer_id) as burned_amount");
    $this->db->from('customer_information');
    $this->db->join('card_types', 'card_types.id = customer_information.membership_id', 'left');
    $this->db->join('customers_earning', 'customers_earning.customer_id = customer_information.customer_id', 'left');
    if ($customer_id) {
        $this->db->where('customer_information.customer_id', $customer_id);
    }
    if ($from_date) {
        $this->db->where('customers_earning.created_date  >=', $from_date);
    }
    if ($to_date) {
        $this->db->where('customers_earning.created_date  <=', $to_date);
    }
    if ($searchValue != '') {
        $this->db->where($searchQuery);
    }
    $this->db->group_by('customer_information.customer_id'); // Group by customer_id to avoid duplicate rows
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $data[] = array(
                
                'sl'               => $sl,
                'customer_name'     => $record->customer_name,
                'customer_mobile'     => $record->customer_mobile,
                'card_name'     => $record->card_name,
                'earning'     => $record->earning_points? $record->earning_points : 0.00,
                'burning'     => $record->burning_points ? $record->burning_points : 0.00,
                'remaining'     => ($record->earning_points? $record->earning_points : 0.00) - ($record->burning_points ? $record->burning_points : 0.00),
                'burned_taka' => $record->burned_amount ? $record->burned_amount : 0
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
    // Product Earning Report
    public function getProductEarningReport($postData = null)
    {
        $response = array();
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
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
            $searchQuery = " (product_information.product_name like '%" . $searchValue . "%' or product_information.product_name_bn like '%" . $searchValue . "%' or card_types.name like '%" . $searchValue  . "%' or crm_setting.points like '%" . $searchValue . "%') ";
        }
         // Total records
         $this->db->select("product_information.product_name,crm_setting.points,card_types.name as card_name");
         $this->db->from('crm_product_map');
         $this->db->join('product_information', 'product_information.product_id = crm_product_map.product_id', 'left');
         $this->db->join('crm_card_map', 'crm_card_map.crm_setting_id = crm_product_map.crm_setting_id', 'left');
         $this->db->join('card_types', 'card_types.id = crm_card_map.card_id', 'left');
         $this->db->join('crm_setting', 'crm_setting.id = crm_product_map.crm_setting_id', 'left');
         if ($from_date) {
            $this->db->where('crm_setting.created_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('crm_setting.created_date <=', $to_date);
        }
         $records = $this->db->get()->result();
         $totalRecords = count($records);
         // Total records
         $this->db->select("product_information.product_name,crm_setting.points,card_types.name as card_name");
         $this->db->from('crm_product_map');
         $this->db->join('product_information', 'product_information.product_id = crm_product_map.product_id', 'left');
         $this->db->join('crm_card_map', 'crm_card_map.crm_setting_id = crm_product_map.crm_setting_id', 'left');
         $this->db->join('card_types', 'card_types.id = crm_card_map.card_id', 'left');
         $this->db->join('crm_setting', 'crm_setting.id = crm_product_map.crm_setting_id', 'left');
         if ($from_date) {
            $this->db->where('crm_setting.created_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('crm_setting.created_date <=', $to_date);
        }
         $records = $this->db->get()->result();
        $totalRecordwithFilter = count($records);
        ## Fetch records
        $this->db->select("product_information.product_name,crm_setting.points,card_types.name as card_name");
         $this->db->from('crm_product_map');
         $this->db->join('product_information', 'product_information.product_id = crm_product_map.product_id', 'left');
         $this->db->join('crm_card_map', 'crm_card_map.crm_setting_id = crm_product_map.crm_setting_id', 'left');
         $this->db->join('card_types', 'card_types.id = crm_card_map.card_id', 'left');
         $this->db->join('crm_setting', 'crm_setting.id = crm_product_map.crm_setting_id', 'left');
         
         if ($from_date) {
            $this->db->where('crm_setting.created_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('crm_setting.created_date <=', $to_date);
        }
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $data[] = array(
                
                'sl'               => $sl,
                'product_name'     => $record->product_name,
                'card_name'     => $record->card_name,
                'points'     => $record->points
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
    // Product Earning Report
    public function getProductBurningReport($postData = null)
    {
        $this->db->select('*');
        $this->db->from('web_setting');
        $this->db->where('setting_id', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $results =  $query->result_array();
        }
        $response = array();
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
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
            $searchQuery = " (product_information.product_name like '%" . $searchValue . "%' or product_information.product_name_bn like '%" . $searchValue . "%' or card_types.name like '%" . $searchValue  . "%' or crm_setting.points like '%" . $searchValue . "%') ";
        }
         // Total records
         $this->db->select("product_information.product_name,burning_setting.points,burning_setting.percentage,card_types.name as card_name");
         $this->db->from('burning_product_map');
         $this->db->join('product_information', 'product_information.product_id = burning_product_map.product_id', 'left');
         $this->db->join('burning_card_map', 'burning_card_map.crm_setting_id = burning_product_map.crm_setting_id', 'left');
         $this->db->join('card_types', 'card_types.id = burning_card_map.card_id', 'left');
         $this->db->join('burning_setting', 'burning_setting.id = burning_product_map.crm_setting_id', 'left');
         
         if ($from_date) {
            $this->db->where('burning_setting.created_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('burning_setting.created_date <=', $to_date);
        }
         $records = $this->db->get()->result();
         $totalRecords = count($records);
         // Total records
          $this->db->select("product_information.product_name,burning_setting.points,burning_setting.percentage,card_types.name as card_name");
          $this->db->from('burning_product_map');
          $this->db->join('product_information', 'product_information.product_id = burning_product_map.product_id', 'left');
          $this->db->join('burning_card_map', 'burning_card_map.crm_setting_id = burning_product_map.crm_setting_id', 'left');
          $this->db->join('card_types', 'card_types.id = burning_card_map.card_id', 'left');
          $this->db->join('burning_setting', 'burning_setting.id = burning_product_map.crm_setting_id', 'left');
          
          if ($from_date) {
             $this->db->where('burning_setting.created_date >=', $from_date);
         }
         if ($to_date) {
             $this->db->where('burning_setting.created_date <=', $to_date);
         }
         $records = $this->db->get()->result();
        $totalRecordwithFilter = count($records);
         // Fetch records
         $this->db->select("product_information.product_name,burning_setting.points,burning_setting.percentage,card_types.name as card_name");
         $this->db->from('burning_product_map');
         $this->db->join('product_information', 'product_information.product_id = burning_product_map.product_id', 'left');
         $this->db->join('burning_card_map', 'burning_card_map.crm_setting_id = burning_product_map.crm_setting_id', 'left');
         $this->db->join('card_types', 'card_types.id = burning_card_map.card_id', 'left');
         $this->db->join('burning_setting', 'burning_setting.id = burning_product_map.crm_setting_id', 'left');
         
         if ($from_date) {
            $this->db->where('burning_setting.created_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('burning_setting.created_date <=', $to_date);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;

        foreach ($records as $record) {
            $data[] = array(
                
                'sl'               => $sl,
                'product_name'     => $record->product_name,
                'card_name'     => $record->card_name,
                'points'     => $record->points,
                'percentage'     => $record->percentage,
                'eligible_points'     => $results[0]['eligible_points']
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

    // Invoice Report
    public function getInvoiceReport($postData = null)
    {
        $response = array();
        $customer_id = $this->input->post('customer_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
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
            $searchQuery = " (card_types.name like '%" . $searchValue . "%' or customer_information.customer_name like '%" . $searchValue . "%' or customer_information.customer_mobile like '%" . $searchValue . "%') ";
        }
         // Total records
         $this->db->select("invoice.date,invoice.invoice_id,invoice.total_amount,card_types.name as card_name, customer_information.customer_name, customer_information.customer_mobile,
         (SELECT SUM(ce1.points) FROM customers_earning ce1
         WHERE ce1.type = '1' AND ce1.invoice_id = invoice.invoice_id LIMIT 1) as earning_points,
         (SELECT ce2.points FROM customers_earning ce2
         WHERE ce2.type = '2' AND ce2.invoice_id = invoice.invoice_id LIMIT 1) as burning_points,
        invoice.discounted_points_amount");
         $this->db->from('invoice');
         $this->db->join('customer_information', 'customer_information.customer_id = invoice.customer_id', 'left');
         $this->db->join('card_types', 'card_types.id = customer_information.membership_id', 'left');
         $this->db->join('customers_earning', 'customers_earning.customer_id = customer_information.customer_id', 'left');
        $this->db->group_by('invoice.invoice_id'); // Group by customer_id to avoid duplicate rows
        
        if($customer_id)
         {
            $this->db->where('invoice.customer_id', $customer_id);
         }
         if ($from_date) {
            $this->db->where('invoice.date  >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('invoice.date  <=', $to_date);
        }
         $records = $this->db->get()->result();
         $totalRecords = count($records);
         // Total records
         $this->db->select("invoice.date,invoice.invoice_id,invoice.total_amount,card_types.name as card_name, customer_information.customer_name, customer_information.customer_mobile,
         (SELECT SUM(ce1.points) FROM customers_earning ce1
         WHERE ce1.type = '1' AND ce1.invoice_id = invoice.invoice_id LIMIT 1) as earning_points,
         (SELECT ce2.points FROM customers_earning ce2
         WHERE ce2.type = '2' AND ce2.invoice_id = invoice.invoice_id LIMIT 1) as burning_points,
        invoice.discounted_points_amount");
         $this->db->from('invoice');
         $this->db->join('customer_information', 'customer_information.customer_id = invoice.customer_id', 'left');
         $this->db->join('card_types', 'card_types.id = customer_information.membership_id', 'left');
         $this->db->join('customers_earning', 'customers_earning.customer_id = customer_information.customer_id', 'left');
        $this->db->group_by('invoice.invoice_id'); // Group by customer_id to avoid duplicate rows
         
        if($customer_id)
         {
            $this->db->where('invoice.customer_id', $customer_id);
         }
         if ($from_date) {
            $this->db->where('invoice.date  >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('invoice.date  <=', $to_date);
        }
         $records = $this->db->get()->result();
        $totalRecordwithFilter = count($records);
        ## Fetch records
        $this->db->select("invoice.date,invoice.invoice_id,invoice.total_amount,card_types.name as card_name, customer_information.customer_name, customer_information.customer_mobile,
        (SELECT SUM(ce1.points) FROM customers_earning ce1
        WHERE ce1.type = '1' AND ce1.invoice_id = invoice.invoice_id LIMIT 1) as earning_points,
        (SELECT ce2.points FROM customers_earning ce2
        WHERE ce2.type = '2' AND ce2.invoice_id = invoice.invoice_id LIMIT 1) as burning_points,
       invoice.discounted_points_amount");
        $this->db->from('invoice');
        $this->db->join('customer_information', 'customer_information.customer_id = invoice.customer_id', 'left');
        $this->db->join('card_types', 'card_types.id = customer_information.membership_id', 'left');
        $this->db->join('customers_earning', 'customers_earning.customer_id = customer_information.customer_id', 'left');
    
         if($customer_id)
         {
            $this->db->where('invoice.customer_id', $customer_id);
         }
         if ($from_date) {
            $this->db->where('invoice.date  >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('invoice.date  <=', $to_date);
        }
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->group_by('invoice.invoice_id'); // Group by customer_id to avoid duplicate rows
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        //  echo '<pre>';
        // print_r($records);
        // exit();
        $data = array();
        $sl = 1;
        $base_url = base_url();
        foreach ($records as $record) {
            $invoice_id = '  <a href="' . $base_url . 'Cinvoice/invoice_inserted_data/' . $record->invoice_id . '" class="" >' . $record->invoice_id . '</a>';
            $data[] = array(
                
                'sl'               => $sl,
                'date'              => $record->date,
                'customer_name'     => $record->customer_name,
                'customer_mobile'     => $record->customer_mobile,
                'card_name'     => $record->card_name,
                'invoice_no'     => $invoice_id,
                'price'     => number_format($record->total_amount, 2, '.', ','),
                'earning'     => $record->earning_points? $record->earning_points : 0.00,
                'burning'     => $record->burning_points ? $record->burning_points : 0.00,
                'remaining'     => ($record->earning_points? $record->earning_points : 0.00) - ($record->burning_points ? $record->burning_points : 0.00),
                'burned_taka' => $record->discounted_points_amount ? $record->discounted_points_amount : 0
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

}
