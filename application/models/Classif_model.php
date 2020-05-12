<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class Classif_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_vid_sd_by_id($id)
    {
        return $this->db->select('*')
                ->from('vid_specd')
                ->where('id', $id)
                ->get()
                ->row_array();
    }

    public function get_vid_sd_by_name($name)
    {
        return $this->db->select('*')
                ->from('vid_specd')
                ->where('name', $name)
                ->where('is_delete', 0)
                ->get()
                ->row_array();
    }

    public function add_vid_sd($data)
    {
        $this->db->insert('vid_specd', $data);
    }

    public function get_vid_sd($is_delete)
    {
        return $this->db->select('*')
                ->from('vid_specd')
                ->where('is_delete', $is_delete)
                ->get()
                ->result_array();
    }

    public function get_vid_sd_by_id_name($id, $name)
    {
        return $this->db->select('*')
                ->from('vid_specd')
                ->where('id != ', $id)
                ->where('name', $name)
                ->where('is_delete', 0)
                ->get()
                ->row_array();
    }

    public function edit_vid_sd($id, $data)
    {
        $this->db->update('vid_specd', $data, ['id' => $id]);
    }

        public function get_vid_hs_1($is_delete)
    {
        return $this->db->select('*')
                ->from('vid_hs_1')
                ->where('is_delete', $is_delete)
                ->get()
                ->result_array();
    }



        public function get_vid_hs_1_by_id($id)
    {
        return $this->db->select('*')
                ->from('vid_hs_1')
                ->where('id', $id)
                ->get()
                ->row_array();
    }

    public function get_vid_hs_1_by_name($name)
    {
        return $this->db->select('*')
                ->from('vid_hs_1')
                ->where('name', $name)
                ->where('is_delete', 0)
                ->get()
                ->row_array();
    }


        public function add_vid_hs_1($data)
    {
        $this->db->insert('vid_hs_1', $data);
    }


        public function get_vid_hs_1_by_id_name($id, $name)
    {
        return $this->db->select('*')
                ->from('vid_hs_1')
                ->where('id != ', $id)
                ->where('name', $name)
                ->where('is_delete', 0)
                ->get()
                ->row_array();
    }


    public function edit_vid_hs($id, $data)
    {
        $this->db->update('vid_hs_1', $data, ['id' => $id]);
    }




        public function get_type_hs($is_delete)
    {
        return $this->db->select('*')
                ->from('vid_hs_2')
                ->where('is_delete', $is_delete)
                ->get()
                ->result_array();
    }



            public function get_type_hs_by_id($id)
    {
        return $this->db->select('*')
                ->from('vid_hs_2')
                ->where('id', $id)
                ->get()
                ->row_array();
    }


        public function get_type_hs_by_name_and_vid($name,$vid)
    {
        return $this->db->select('*')
                ->from('vid_hs_2')
                ->where('name', $name)
             ->where('id_vid_hs_1', $vid)
                ->where('is_delete', 0)
                ->get()
                ->row_array();
    }

            public function add_type_hs($data)
    {
        $this->db->insert('vid_hs_2', $data);
    }



    public function edit_type_hs($id, $data)
    {
        $this->db->update('vid_hs_2', $data, ['id' => $id]);
    }
}
