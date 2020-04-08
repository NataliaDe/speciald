<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class Dones_model extends CI_Model
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

    /* catalog table.
     * level 3
     *      */
    public function get_dones_by_grochs($filter, $id_organ = false)
    {

        $this->db->select('d.*, author.id_local as author_local_id, vid.name as specd_vid_name');
        $this->db->join('permissions as author', 'author.id_user=d.created_by', 'left');
        $this->db->join('vid_specd as vid', 'vid.id=d.specd_vid', 'left');

        //who created SD
        if (isset($filter['author_local_id']))
            $this->db->where('author.id_local', $filter['author_local_id']);

        if ($id_organ != FALSE)
            $this->db->where('author.id_organ', $id_organ);

        if (isset($filter['without_cp']))
             $this->db->where_not_in('author.id_organ', $filter['without_cp']);


        if (isset($filter['is_delete']))
            $this->db->where('d.is_delete', $filter['is_delete']);

        $this->db->order_by('d.specd_date');


        $result = $this->db->get('speciald.dones as d')->result_array();
        return $result;
    }
    /*

     * $is_history=3 - get all statuses
     * $is_history=1 - get statuses in history
     * $is_history=0 - get statuses active
     *      */

    public function get_statuses_by_id_dones($id_dones, $is_history = 3, $status = false)
    {

        $this->db->select('dl.*, dl.id_user as id_user_action, a.*, act.name as action_name');
        $this->db->where('dl.id_dones', $id_dones);
        $this->db->join('permissions as a', 'a.id_user=dl.id_user', 'left');
        $this->db->join('actions as act', 'act.id=dl.id_action', 'left');

        if ($status != FALSE) {
            if (is_array($status))
                $this->db->where_in('dl.id_action', $status);
            else {
                $this->db->where('dl.id_action', $status);
            }
        }

        if ($is_history != 3)
            $this->db->where('dl.is_history', $is_history);

        $this->db->order_by('dl.date_action', 'desc');

        $result = $this->db->get('speciald.dones_logs as dl')->result_array();
        return $result;
    }



    public function set_number_dones($id_dones, $number)
    {
        $this->db->where('id', $id_dones);
        $this->db->set('specd_number', $number);
        $this->db->update('speciald.dones');

    }


    /* catalog table.
     * level 2
     *      */
        public function get_dones_by_region($filter, $id_organ = false)
    {

        $this->db->select('d.*, author.id_local as author_local_id,author.id_region as author_region_id, vid.name as specd_vid_name, author.auth_organ');
        $this->db->join('permissions as author', 'author.id_user=d.created_by', 'left');
        $this->db->join('vid_specd as vid', 'vid.id=d.specd_vid', 'left');

        //who created SD
        if (isset($filter['author_region_id']))
            $this->db->where('author.id_region', $filter['author_region_id']);

        if ($id_organ != FALSE)
            $this->db->where('author.id_organ', $id_organ);

        if (isset($filter['without_cp']))
             $this->db->where_not_in('author.id_organ', $filter['without_cp']);


        if (isset($filter['is_delete']))
            $this->db->where('d.is_delete', $filter['is_delete']);

        $this->db->order_by('d.specd_date');


        $result = $this->db->get('speciald.dones as d')->result_array();
        return $result;
    }




        public function is_status_by_id_dones($id_dones, $status)
    {

        $this->db->select('dl.*');
        $this->db->where('dl.id_dones', $id_dones);

        $this->db->where('dl.id_action', $status);


        $this->db->where('dl.is_history', 0);

        $this->db->order_by('dl.date_action', 'desc');

        $result = $this->db->get('speciald.dones_logs as dl')->result_array();
        return $result;
    }



    /* catalog table.
     * level 1 RCU
     *      */
        public function get_dones_for_rcu($filter)
    {

        $this->db->select('d.*, author.id_local as author_local_id,author.id_region as author_region_id,author.level as author_level,'
            . 'author.id_organ as author_id_organ,author.auth_organ, vid.name as specd_vid_name');
        $this->db->join('permissions as author', 'author.id_user=d.created_by', 'left');
        $this->db->join('vid_specd as vid', 'vid.id=d.specd_vid', 'left');


        if (isset($filter['is_delete']))
            $this->db->where('d.is_delete', $filter['is_delete']);

        $this->db->order_by('d.specd_date');


        $result = $this->db->get('speciald.dones as d')->result_array();
        return $result;
    }


        public function set_open_update($id_dones, $is_open_update)
    {
        $this->db->where('id', $id_dones);
        $this->db->set('is_open_update', $is_open_update);
        $this->db->update('speciald.dones');

    }




        public function get_dones_by_id($id_dones)
    {
        return $this->db->select('d.*')
                ->from('speciald.dones as d')
                ->where('d.id', $id_dones)
                ->get()
                ->row_array();
    }
}
