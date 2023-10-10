<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('display')) {

    function display($text = null)
    {
        $ci = &get_instance();
        $ci->load->database();
        $table = 'language';
        $phrase = 'phrase';
        $setting_table = 'web_setting';
        $default_lang = 'english';

        //set language  
        $data = $ci->db->get($setting_table)->row();
        if (!empty($data->language)) {
            $language = $data->language;
        } else {
            $language = $default_lang;
        }

        if (!empty($text)) {

            if ($ci->db->table_exists($table)) {

                if ($ci->db->field_exists($phrase, $table)) {

                    if ($ci->db->field_exists($language, $table)) {

                        $row = $ci->db->select($language)
                            ->from($table)
                            ->where($phrase, $text)
                            ->get()
                            ->row();

                        if (!empty($row->$language)) {
                            return html_escape($row->$language);
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}


if (!function_exists('pos_display')) {

    function pos_display($text = null)
    {
        $ci = &get_instance();
        $ci->load->database();
        $table = 'language';
        $phrase = 'phrase';
        $setting_table = 'pos_setting';
        $default_lang = 'english';

        //set language  
        $data = $ci->db->where('field_name', 'language')->get($setting_table)->row();
        // echo "<pre>";
        // print_r($data->value);
        // exit();
        if (!empty($data->value)) {
            $language = $data->value;
        } else {
            $language = $default_lang;
        }

        if (!empty($text)) {

            if ($ci->db->table_exists($table)) {

                if ($ci->db->field_exists($phrase, $table)) {

                    if ($ci->db->field_exists($language, $table)) {

                        $row = $ci->db->select($language)
                            ->from($table)
                            ->where($phrase, $text)
                            ->get()
                            ->row();

                        if (!empty($row->$language)) {
                            return html_escape($row->$language);
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}


if (!function_exists('invoice_display')) {

    function invoice_display($text = null)
    {
        $ci = &get_instance();
        $ci->load->database();
        $table = 'language';
        $phrase = 'phrase';
        $setting_table = 'invoice_setting';
        $default_lang = 'english';

        //set language  
        $data = $ci->db->where('OptionSlug', 'language')->get($setting_table)->row();
        // echo "<pre>";
        // print_r($data->value);
        // exit();
        if (!empty($data->status)) {
            $language = $data->status;
        } else {
            $language = $default_lang;
        }

        if (!empty($text)) {

            if ($ci->db->table_exists($table)) {

                if ($ci->db->field_exists($phrase, $table)) {

                    if ($ci->db->field_exists($language, $table)) {

                        $row = $ci->db->select($language)
                            ->from($table)
                            ->where($phrase, $text)
                            ->get()
                            ->row();

                        if (!empty($row->$language)) {
                            return html_escape($row->$language);
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

 

// $autoload['helper'] =  array('language_helper');

/*display a language*/
// echo display('helloworld'); 

/*display language list*/
// $lang = languageList(); 
