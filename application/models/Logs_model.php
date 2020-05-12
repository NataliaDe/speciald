<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class Logs_model extends CI_Model
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


    const ACTION_CREATE_SD = 1;
    const ACTION_EDIT_SD = 2;
    const ACTION_DELETE_SD = 3;
    const ACTION_PROVE_SD_UMCHS = 4;
    const ACTION_REFUSE_SD_UMCHS = 5;
    const ACTION_PROVE_SD_RCU = 6;
    const ACTION_REFUSE_SD_RCU = 7;
    const ACTION_OPEN = 8;
    const ACTION_CLOSE = 9;
    const ACTION_SET_NUMBER_SD = 10;
    const ACTION_UPDATE_REFUSE_UMCHS = 11;
    const ACTION_UPDATE_REFUSE_RCU = 12;

    const ACTION_COPY_SD = 13;

    public function __construct()
    {
        parent::__construct();
    }

    public function add_logs($data)
    {
        $this->db->insert('speciald.dones_logs', $data);
        return $this->db->insert_id();
    }

    public function add_dones_status($data)
    {
        $this->db->insert('speciald.dones_status', $data);
        return $this->db->insert_id();
    }

    public function delete_dones_statuses($data)
    {
        $this->db->where('id_dones', $data['id_dones']);
        $this->db->where_in('id_action', $data['history_actions']);
        $this->db->update('speciald.dones_logs', ['is_history' => 1]);
    }

    public function update_dones_description_refuse($data)
    {
        $this->db->where('id_dones', $data['id_dones']);
        $this->db->where('id_user', $data['id_user']);
        $this->db->where('id_action', $data['id_action']);
        $this->db->update('speciald.dones_logs', ['description_refuse' => $data['description_refuse']]);
    }

    public function delete_dones_statuses_of_user($data)
    {
        $this->db->where('id_dones', $data['id_dones']);
        $this->db->where('id_user', $data['id_user']);
        $this->db->where_in('id_action', $data['history_actions']);
        $this->db->update('speciald.dones_logs', ['is_history' => 1]);
    }

    public function get_dones_description_refuse($data)
    {
        $this->db->where('id_dones', $data['id_dones']);
        $this->db->where('id_user', $data['id_user']);
        $this->db->where('id_action', $data['id_action']);
        $this->db->where('is_history', 0);
        $this->db->limit(1);
        return $this->db->get('speciald.dones_logs')->row_array();

    }


        public function get_dones_satatus_by_user($data)
    {
        $this->db->where('id_dones', $data['id_dones']);
        $this->db->where('id_user', $data['id_user']);
        $this->db->where('id_action', $data['id_action']);
        $this->db->where('is_history', 0);
        $this->db->limit(1);
        $this->db->get('speciald.dones_logs')->row_array();
    }
}
