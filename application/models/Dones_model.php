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

        $this->db->select('distinct(d.id), d.*, author.id_local as author_local_id, vid.name as specd_vid_name, author.id_organ as author_id_organ');
        $this->db->join('permissions as author', 'author.id_user=d.created_by', 'left');
        $this->db->join('vid_specd as vid', 'vid.id=d.specd_vid', 'left');

        if (isset($filter['status_sd']) && !empty($filter['status_sd'])) {

            $this->db->join('dones_logs as dl', "dl.id_dones=d.id and dl.`is_history`=0 AND dl.`id_action`=" . $filter['status_sd'], 'RIGHT');
        }

        //who created SD
        if (isset($filter['merge_locals']) && !empty($filter['merge_locals']))
            $this->db->where_in('author.id_local', $filter['merge_locals']);
        elseif (isset($filter['author_local_id']))
            $this->db->where('author.id_local', $filter['author_local_id']);

        if ($id_organ != FALSE)
            $this->db->where('author.id_organ', $id_organ);

        if (isset($filter['without_cp']))
            $this->db->where_not_in('author.id_organ', $filter['without_cp']);


        if (isset($filter['is_delete']))
            $this->db->where('d.is_delete', $filter['is_delete']);


                // by last month
        if (isset($filter['id_range']) && $filter['id_range'] == 1) {
            $date = new DateTime();
            $date->modify('-1 month');
            $from= $date->format('Y-m-d');
            $to=new DateTime();
            $to=$to->format('Y-m-d');

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $from);
            $this->db->where('d.specd_date <=', $to);
            $this->db->group_end();
        }
        //by current year
        elseif (isset($filter['id_range']) && $filter['id_range'] == 2){
                        $date = new DateTime();
            $date->modify('-1 year');
            $from= $date->format('Y-m-d');
            $to=new DateTime();
            $to=$to->format('Y-m-d');

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $from);
            $this->db->where('d.specd_date <=', $to);
            $this->db->group_end();
        }
        elseif(isset($filter['id_range']) && $filter['id_range'] == 3){
                        $date = new DateTime();
            $date->modify('-24 hours');
            $from= $date->format('Y-m-d');
            $to=new DateTime();
            $to=$to->format('Y-m-d');

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $from);
            $this->db->where('d.specd_date <=', $to);
            $this->db->group_end();
        }



        if (isset($filter['id_dones']) && !empty($filter['id_dones'])) {

            $this->db->like('d.id', $filter['id_dones']);
        }

        if ((isset($filter['start_date_dones']) && !empty($filter['start_date_dones'])) && (isset($filter['end_date_dones']) && !empty($filter['end_date_dones']))) {

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $filter['start_date_dones']);
            $this->db->where('d.specd_date <=', $filter['end_date_dones']);
            $this->db->group_end();
        }
        if (isset($filter['number_dones']) && !empty($filter['number_dones'])) {

            $this->db->like('d.specd_number', $filter['number_dones']);
        }

        if (isset($filter['address_dones']) && !empty($filter['address_dones'])) {

            $this->db->like('d.address', $filter['address_dones']);
        }

        if (isset($filter['creator_name']) && !empty($filter['creator_name'])) {


            $this->db->like('author.auth_organ', $filter['creator_name']);
        }
        if (isset($filter['short_description']) && !empty($filter['short_description'])) {
            $this->db->group_start();
            $this->db->like('d.short_description', $filter['short_description']);
            $this->db->or_like('d.opening_description', $filter['short_description']);
            $this->db->group_end();
        }

        if (isset($filter['specd_vid']) && !empty($filter['specd_vid'])) {

            $this->db->where('d.specd_vid', $filter['specd_vid']);
        }

        if (isset($filter['is_to_daily_summary']) && $filter['is_to_daily_summary'] == 1)
            $this->db->where('d.is_to_daily_summary', 1);

        $this->db->order_by('d.date_insert', 'DESC');


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
        //echo $this->db->last_query();
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

        $this->db->select('distinct(d.id), d.*, author.id_local as author_local_id,author.id_region as author_region_id, vid.name as specd_vid_name, author.auth_organ,author.id_organ as author_id_organ');
        $this->db->join('permissions as author', 'author.id_user=d.created_by', 'left');
        $this->db->join('vid_specd as vid', 'vid.id=d.specd_vid', 'left');


                if (isset($filter['status_sd']) && !empty($filter['status_sd'])) {

            $this->db->join('dones_logs as dl', "dl.id_dones=d.id and dl.`is_history`=0 AND dl.`id_action`=" . $filter['status_sd'], 'RIGHT');
        }

        //who created SD
        if (isset($filter['author_region_id']))
            $this->db->where('author.id_region', $filter['author_region_id']);

        if ($id_organ != FALSE)
            $this->db->where('author.id_organ', $id_organ);

        if (isset($filter['without_cp']))
            $this->db->where_not_in('author.id_organ', $filter['without_cp']);


        if (isset($filter['is_delete']))
            $this->db->where('d.is_delete', $filter['is_delete']);


                // by last month
        if (isset($filter['id_range']) && $filter['id_range'] == 1) {
            $date = new DateTime();
            $date->modify('-1 month');
            $from= $date->format('Y-m-d');
            $to=new DateTime();
            $to=$to->format('Y-m-d');

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $from);
            $this->db->where('d.specd_date <=', $to);
            $this->db->group_end();
        }
        //by current year
        elseif (isset($filter['id_range']) && $filter['id_range'] == 2){
                        $date = new DateTime();
            $date->modify('-1 year');
            $from= $date->format('Y-m-d');
            $to=new DateTime();
            $to=$to->format('Y-m-d');

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $from);
            $this->db->where('d.specd_date <=', $to);
            $this->db->group_end();
        }
                elseif(isset($filter['id_range']) && $filter['id_range'] == 3){
                        $date = new DateTime();
            $date->modify('-24 hours');
            $from= $date->format('Y-m-d');
            $to=new DateTime();
            $to=$to->format('Y-m-d');

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $from);
            $this->db->where('d.specd_date <=', $to);
            $this->db->group_end();
        }




        if (isset($filter['id_dones']) && !empty($filter['id_dones'])) {

            $this->db->like('d.id', $filter['id_dones']);
        }

        if ((isset($filter['start_date_dones']) && !empty($filter['start_date_dones'])) && (isset($filter['end_date_dones']) && !empty($filter['end_date_dones']))) {

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $filter['start_date_dones']);
            $this->db->where('d.specd_date <=', $filter['end_date_dones']);
            $this->db->group_end();
        }
        if (isset($filter['number_dones']) && !empty($filter['number_dones'])) {

            $this->db->like('d.specd_number', $filter['number_dones']);
        }

        if (isset($filter['address_dones']) && !empty($filter['address_dones'])) {

            $this->db->like('d.address', $filter['address_dones']);
        }

        if (isset($filter['creator_name']) && !empty($filter['creator_name'])) {


            $this->db->like('author.auth_organ', $filter['creator_name']);
        }
        if (isset($filter['short_description']) && !empty($filter['short_description'])) {
            $this->db->group_start();
            $this->db->like('d.short_description', $filter['short_description']);
            $this->db->or_like('d.opening_description', $filter['short_description']);
            $this->db->group_end();
        }

        if (isset($filter['specd_vid']) && !empty($filter['specd_vid'])) {

            $this->db->where('d.specd_vid', $filter['specd_vid']);
        }
        if (isset($filter['is_to_daily_summary']) && $filter['is_to_daily_summary'] == 1)
            $this->db->where('d.is_to_daily_summary', 1);

        $this->db->order_by('d.date_insert', 'DESC');


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

        $this->db->select('distinct(d.id), d.*, author.id_local as author_local_id,author.id_region as author_region_id,author.level as author_level,'
            . 'author.id_organ as author_id_organ,author.auth_organ, vid.name as specd_vid_name');
        $this->db->join('permissions as author', 'author.id_user=d.created_by', 'left');
        $this->db->join('vid_specd as vid', 'vid.id=d.specd_vid', 'left');



        if (isset($filter['status_sd']) && !empty($filter['status_sd'])) {

            $this->db->join('dones_logs as dl', "dl.id_dones=d.id and dl.`is_history`=0 AND dl.`id_action`=" . $filter['status_sd'] , 'RIGHT');
        }

        if (isset($filter['is_delete']))
            $this->db->where('d.is_delete', $filter['is_delete']);

        // by last month
        if (isset($filter['id_range']) && $filter['id_range'] == 1) {
            $date = new DateTime();
            $date->modify('-1 month');
            $from = $date->format('Y-m-d');
            $to = new DateTime();
            $to = $to->format('Y-m-d');

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $from);
            $this->db->where('d.specd_date <=', $to);
            $this->db->group_end();
        }
        //by current year
        elseif (isset($filter['id_range']) && $filter['id_range'] == 2) {
            $date = new DateTime();
            $date->modify('-1 year');
            $from = $date->format('Y-m-d');
            $to = new DateTime();
            $to = $to->format('Y-m-d');

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $from);
            $this->db->where('d.specd_date <=', $to);
            $this->db->group_end();
        } elseif (isset($filter['id_range']) && $filter['id_range'] == 3) {
            $date = new DateTime();
            $date->modify('-24 hours');
            $from = $date->format('Y-m-d');
            $to = new DateTime();
            $to = $to->format('Y-m-d');

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $from);
            $this->db->where('d.specd_date <=', $to);
            $this->db->group_end();
        }

        if (isset($filter['id_dones']) && !empty($filter['id_dones'])) {

            $this->db->like('d.id', $filter['id_dones']);
        }

        if ((isset($filter['start_date_dones']) && !empty($filter['start_date_dones'])) && (isset($filter['end_date_dones']) && !empty($filter['end_date_dones']))) {

            $this->db->group_start();
            $this->db->where('d.specd_date >=', $filter['start_date_dones']);
            $this->db->where('d.specd_date <=', $filter['end_date_dones']);
            $this->db->group_end();
        }
        if (isset($filter['number_dones']) && !empty($filter['number_dones'])) {

            $this->db->like('d.specd_number', $filter['number_dones']);
        }

        if (isset($filter['address_dones']) && !empty($filter['address_dones'])) {

            $this->db->like('d.address', $filter['address_dones']);
        }

        if (isset($filter['creator_name']) && !empty($filter['creator_name'])) {


            $this->db->like('author.auth_organ', $filter['creator_name']);
        }
        if (isset($filter['short_description']) && !empty($filter['short_description'])) {
            $this->db->group_start();
            $this->db->like('d.short_description', $filter['short_description']);
            $this->db->or_like('d.opening_description', $filter['short_description']);
            $this->db->group_end();
        }

        if (isset($filter['specd_vid']) && !empty($filter['specd_vid'])) {

            $this->db->where('d.specd_vid', $filter['specd_vid']);
        }

        if (isset($filter['is_to_daily_summary']) && $filter['is_to_daily_summary'] == 1)
            $this->db->where('d.is_to_daily_summary', 1);

        $this->db->order_by('d.date_insert', 'DESC');


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

    public function delete_dones_media($id_dones)
    {
        $this->db->where('id_dones', $id_dones)->delete('dones_media');
    }

    public function add_dones_media($data)
    {
        $data['created_by'] = $this->session->userdata('id_user');
        $data['date_create'] = date('Y-m-d H:i:s');
        $this->db->insert('dones_media', $data);
    }

    public function get_dones_media($id_dones)
    {

        $this->db->select('*');
        $this->db->where('id_dones', $id_dones);

        $result = $this->db->get('dones_media')->result_array();
        return $result;
    }

    public function delete_settings_accordion($id_dones, $id_user)
    {
        $this->db->where('id_dones', $id_dones)->where('id_user', $id_user)->delete('settings_accordion');
    }

    public function add_settings_accordion($data)
    {

        $this->db->insert('settings_accordion', $data);
    }

    public function get_settings_accordion($id_dones, $id_user)
    {

        $this->db->select('*');
        $this->db->where('id_dones', $id_dones);
        $this->db->where('id_user', $id_user);
        return $this->db->get('settings_accordion')->row_array();
    }


        public function get_cnt_dones()
    {
        $this->db->select('count(id) as cnt');
        $res= $this->db->get('dones')->row_array();
        return $res['cnt'];
    }


        public function get_cnt_dones_per_region($id_region)
    {
        $this->db->select('count(d.id) as cnt');
        $this->db->join('users as u', 'u.id=d.created_by', 'left');
        $this->db->where('u.id_region', $id_region);
        $res= $this->db->get('dones as d')->row_array();
        return $res['cnt'];
    }


        public function get_cnt_dones_per_organ($id_organ)
    {
        $this->db->select('count(d.id) as cnt');
        $this->db->join('users as u', 'u.id=d.created_by', 'left');
        $this->db->where('u.id_organ', $id_organ);
        $res= $this->db->get('dones as d')->row_array();
        return $res['cnt'];
    }

        public function delete_dones_live_together($id_dones)
    {
        $this->db->where('id_dones', $id_dones)->delete('dones_live_together');
    }

        public function add_dones_live_together($data)
    {
        $this->db->insert('speciald.dones_live_together', $data);
        return $this->db->insert_id();
    }

        public function get_dones_live_together($id_dones)
    {
        return $this->db->select('*')
                ->from('speciald.dones_live_together')
                ->where('id_dones', $id_dones)
                ->order_by('sort', 'asc')
                ->get()
                ->result_array();
    }


        public function get_range_filter_sd($id_user)
    {
        return $this->db->select('*')
                ->from('filter_range_sd')
                ->where('id_user', $id_user)
                ->get()
                ->row_array();
    }

    public function set_range_filter_sd($id_range,$id_user)
    {
        $data['date_create'] = date('Y-m-d H:i:s');
        $data['last_update'] = date('Y-m-d H:i:s');
        $data['id_range']=$id_range;
        $data['id_user']=$id_user;
        $this->db->insert('filter_range_sd', $data);

    }


        public function update_range_filter_sd($id_range,$id_user)
    {
        $this->db->where('id_user', $id_user);
        $this->db->set('id_range', $id_range);
        $this->db->set('last_update', date('Y-m-d H:i:s'));
        $this->db->update('filter_range_sd');

    }


        public function date_action_by_id_dones($id_dones, $status,$id_user=false)
    {

        $this->db->select('dl.*');
        $this->db->where('dl.id_dones', $id_dones);

        $this->db->where('dl.id_action', $status);
        if($id_user)
        $this->db->where('dl.id_user', $id_user);


        $this->db->where('dl.is_history', 0);

        $this->db->order_by('dl.date_action', 'desc');
        $this->db->limit(1);

        $result = $this->db->get('speciald.dones_logs as dl')->row_array();
        return $result['date_action'];
    }

}
