<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class Create_model extends CI_Model
{

//	const LEVEL_ID_RCU=1;//RCU
//	const LEVEL_ID_UMCHS=2;//UMCHS
//	const LEVEL_ID_ROCHS=3;//ROCHS
//
//	/* each level has property 'can_edit' and 'is_admin'.
//	UMCHS level : umchs, rosn g.Minsk(all rosn), ugz g.Minsk(all), avia g.Minsk(all).
//	ROCHS level: rochs, rosn, ugz, avia.
//	*/
//
//	const ORGAN_ID_RCU=5;//RCU
//	const ORGAN_ID_UMCHS=4;//UMCHS
//
//	const ORGAN_ID_PASO=6;//PASO
//	const ORGAN_ID_PASO_OBJECT=7;//PASO OBJECT
//
//	const ORGAN_ID_GOCHS=1;//GOCHS
//	const ORGAN_ID_ROCHS=2;//ROCHS
//	const ORGAN_ID_GROCHS=3;//GROCHS
//
//	const ORGAN_ID_ROSN=8;//ROSN
//	const ORGAN_ID_UGZ=9;//UGZ
//	const ORGAN_ID_AVIA=12;//AVIA

    public function __construct()
    {
        parent::__construct();
    }

    public function add_new_dones($data)
    {
        $this->db->insert('speciald.dones', $data);
        return $this->db->insert_id();
    }

    public function edit_dones($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.dones', $data);
    }

    public function add_new_dones_silymchs($data)
    {
        $this->db->insert('speciald.dones_silymchs', $data);
        return $this->db->insert_id();
    }

    public function edit_new_dones_silymchs($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.dones_silymchs', $data);
    }

    public function get_dones_silymchs($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones_silymchs')
                ->where('id_dones', $id_dones)
                ->order_by('sort', 'asc')
                ->get()
                ->result_array();
    }

    public function delete_dones_silymchs_by_ids($id_dones, $ids)
    {

        $this->db->where('id_dones', $id_dones);
        $this->db->where_in('id', $ids);
        $this->db->delete('speciald.dones_silymchs');
    }

    public function get_dones_innerservice($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones_innerservice')
                ->where('id_dones', $id_dones)
                ->order_by('sort', 'asc')
                ->get()
                ->result_array();
    }

    public function add_new_dones_innerservice($data)
    {
        $this->db->insert('speciald.dones_innerservice', $data);
        return $this->db->insert_id();
    }

    public function edit_dones_innerservice($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.dones_innerservice', $data);
    }

    public function delete_dones_innerservice_by_ids($id_dones, $ids)
    {
        $this->db->where('id_dones', $id_dones);
        $this->db->where_in('id', $ids);
        $this->db->delete('speciald.dones_innerservice');
    }

    public function get_dones_innerservice_work($id_dones_innerservice)
    {
        return $this->db->select('*')
                ->from('speciald.dones_innerservice_work')
                ->where('id_dones_innerservice', $id_dones_innerservice)
                ->get()
                ->result_array();
    }

    public function add_new_dones_innerservice_work($data)
    {
        $this->db->insert('speciald.dones_innerservice_work', $data);
        return $this->db->insert_id();
    }

    public function delete_dones_innerservice_work($id_dones_innerservice)
    {
        $this->db->where('id_dones_innerservice', $id_dones_innerservice)->delete('speciald.dones_innerservice_work');
    }

    public function get_dones_informing($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones_informing')
                ->where('id_dones', $id_dones)
                ->order_by('sort', 'asc')
                ->get()
                ->result_array();
    }

    public function add_new_dones_informing($data)
    {
        $this->db->insert('speciald.dones_informing', $data);
        return $this->db->insert_id();
    }

    public function edit_dones_informing($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.dones_informing', $data);
    }

    public function delete_dones_informing_by_ids($id_dones, $ids)
    {

        $this->db->where('id_dones', $id_dones);
        $this->db->where_in('id', $ids);
        $this->db->delete('speciald.dones_informing');
    }

    public function get_dones_str($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones_str')
                ->where('id_dones', $id_dones)
                ->order_by('sort', 'asc')
                ->get()
                ->result_array();
    }

    public function add_new_dones_str($data)
    {
        $this->db->insert('speciald.dones_str', $data);
        return $this->db->insert_id();
    }

    public function edit_dones_str($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.dones_str', $data);
    }

    public function delete_dones_str_by_ids($id_dones, $ids)
    {

        $this->db->where('id_dones', $id_dones);
        $this->db->where_in('id', $ids);
        $this->db->delete('speciald.dones_str');
    }

    public function get_dones_str_text($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones_str_text')
                ->where('id_dones', $id_dones)
                ->order_by('sort', 'asc')
                ->get()
                ->result_array();
    }

    public function add_new_dones_str_text($data)
    {
        $this->db->insert('speciald.dones_str_text', $data);
        return $this->db->insert_id();
    }

    public function edit_dones_str_text($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.dones_str_text', $data);
    }

    public function delete_dones_str_text_by_ids($id_dones, $ids)
    {

        $this->db->where('id_dones', $id_dones);
        $this->db->where_in('id', $ids);
        $this->db->delete('speciald.dones_str_text');
    }

    public function get_dones_trunks($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones_trunks')
                ->where('id_dones', $id_dones)
                ->order_by('sort', 'asc')
                ->get()
                ->result_array();
    }

    public function add_new_dones_trunks($data)
    {
        $this->db->insert('speciald.dones_trunks', $data);
        return $this->db->insert_id();
    }

    public function edit_dones_trunks($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.dones_trunks', $data);
    }

    public function delete_dones_trunks_by_ids($id_dones, $ids)
    {

        $this->db->where('id_dones', $id_dones);
        $this->db->where_in('id', $ids);
        $this->db->delete('speciald.dones_trunks');
    }

    public function get_dones_water_source($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones_water_source')
                ->where('id_dones', $id_dones)
            ->order_by('sort', 'asc')
                ->get()
                ->result_array();
    }

    public function add_new_dones_water_source($data)
    {
        $this->db->insert('speciald.dones_water_source', $data);
        return $this->db->insert_id();
    }

    public function edit_dones_water_source($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.dones_water_source', $data);
    }

    public function delete_dones_water_source_by_ids($id_dones, $ids)
    {

        $this->db->where('id_dones', $id_dones);
        $this->db->where_in('id', $ids);
        $this->db->delete('speciald.dones_water_source');
    }

    public function add_new_dones_object($data)
    {
        $this->db->insert('speciald.dones_object', $data);
        return $this->db->insert_id();
    }

    public function edit_dones_object($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.dones_object', $data);
    }

    public function get_dones_by_id($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones')
                ->where('id', $id_dones)
                ->get()
                ->row_array();
    }


        public function get_dones_object($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones_object')
                ->where('id_dones', $id_dones)
                ->get()
                ->row_array();
    }
}
