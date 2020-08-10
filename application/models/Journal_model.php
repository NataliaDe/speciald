<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class Journal_model extends CI_Model
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

    public function get_rig_by_id($id)
    {
        return $this->db->select('*')
                ->from('journal.rigtable')
                ->where([
                    'id' => $id
                ])
                ->get()
                ->row_array();
    }

    public function get_people_by_rig_id($id)
    {
        return $this->db->select('*')
                ->from('journal.people')
                ->where([
                    'id_rig' => $id
                ])
                ->get()
                ->row_array();
    }

    public function get_silymchs_by_rig_id($id)
    {
        return $this->db->select('*, concat(pasp_name_spec," ",locorg_name_spec) as pasp_name_full')
                ->from('journal.jrig_spec')
                ->where([
                    'id_rig' => $id
                ])
                ->where('mark is not null')
                ->order_by('region_name', 'asc')
                ->order_by('locorg_name', 'asc')
                //->order_by('pasp_name','asc')
                ->order_by('diviz_name', 'desc')
                ->order_by('divizion_num', 'asc')
                ->get()
                ->result_array();
    }


        public function get_silymchs_by_rig_id_sort_distance($id)
    {

            $result = $this->db->query("CALL journal.`spec_basic_sort_teh`({$id})");
        //$this->db->reconnect();
        mysqli_next_result($this->db->conn_id);
        $res = $result->result_array();

        return $res;

    }

    public function get_innerservice_by_rig_id($id)
    {
        return $this->db->select('i.id_rig, i.time_msg, i.time_arrival, i.distance, i.note, s.name as service_name, s.id as service_id')
                ->from('journal.innerservice as i')
                ->where([
                    'i.id_rig' => $id
                ])
                ->join('journal.service as s', 's.id=i.id_service', 'left')
                ->get()
                ->result_array();
    }

    public function get_informing_by_rig_id($id)
    {
        return $this->db->select(" `inf`.`id`               AS `id`,
  `inf`.`id_rig`           AS `id_rig`,
  `inf`.`id_destination`   AS `id_destination`,
  `inf`.`time_msg`         AS `time_msg`,
  `inf`.`time_exit`        AS `time_exit`,
  `inf`.`time_arrival`     AS `time_arrival`,
  `p`.`name`      AS `position_name`,
  `r`.`name`          AS `rank_name`,
  `inf`.`destination_text` AS `destination_text`,

    (CASE WHEN (`inf`.`id_destination` = 0) THEN `inf`.`destination_text`

WHEN (`inf`.`id_destination` <> 0 AND `d`.`pos_place` IS NOT NULL AND `d`.`pos_place` <> '') THEN CONCAT(`p`.`name`,' ',d.pos_place,' ',r.full_name,' ', d.`fio`)
WHEN (`inf`.`id_destination` <> 0 AND (`d`.`pos_place` IS NULL OR `d`.`pos_place` = '')) THEN CONCAT(`p`.`name`,' ',r.full_name,' ', d.`fio`)
  ELSE `d`.`fio` END) AS `fio`,

  `d`.`id_level`           AS `id_level`,
  `inf`.`id_level_created` AS `id_level_created`")
                ->from('journal.informing as inf')
                ->join('journal.`destination` as `d`', '`inf`.`id_destination` = `d`.`id`', 'left')
                ->join('journal.rank AS r ', 'r.id=d.id_rank', 'left')
                ->join('journal.`position` `p`', '`p`.`id` = `d`.`id_position`', 'left')
                ->where([
                    'id_rig' => $id
                ])
                ->get()
                ->result_array();
    }

    public function serach_rigs($filter)
    {

        $this->db
            ->select('r.*, p.fio as fio_people, p.phone as phone_people,p.address as address_people, p.position as position_people')
            ->join('journal.people as p', 'p.id_rig = r.id', 'left');

        if (isset($filter['id_rig']) && !empty($filter['id_rig']) && $filter['id_rig'] != 0) {
            $this->db->like('r.id', $filter['id_rig']);
        } else {


            if (isset($filter['id_region']) && !empty($filter['id_region']) && $filter['id_region'] != 0) {
                $this->db->where('r.id_region', $filter['id_region']);
            }
            if (isset($filter['id_local']) && !empty($filter['id_local']) && $filter['id_local'] != 0) {
                $this->db->where('r.id_local', $filter['id_local']);
            }


            if (isset($filter['date_msg']) && !empty($filter['date_msg']) && $filter['date_msg'] != 0) {
                $this->db->where('r.date_msg', $filter['date_msg']);
            }

            if (isset($filter['id_reasonrig']) && !empty($filter['id_reasonrig']) && $filter['id_reasonrig'] != 0) {
                $this->db->where('r.id_reasonrig', $filter['id_reasonrig']);
            }

            if (isset($filter['address']) && !empty($filter['address'])) {
                $this->db->group_start();
                $this->db->like('r.address', $filter['address']);
                $this->db->or_like('r.additional_field_address', $filter['address']);
                $this->db->group_end();
            }
        }

        $return = $this->db->get('journal.rigtable r');
//print_r($this->db->last_query());
        return $return->result_array();
    }

    public function get_trunks_by_id_rig($id)
    {
        return $this->db->select('*, concat(pasp_name_spec," ",locorg_name_spec) as pasp_name_full')
                ->from('journal.spec_trunks_tbl')
                ->where([
                    'id_rig' => $id
                ])
                ->where('mark is not null')
                ->order_by('region_name', 'asc')
                ->order_by('locorg_name', 'asc')
                //->order_by('pasp_name','asc')
                // ->order_by('pasp_name_spec', 'desc')
                ->order_by('diviz_name', 'desc')
                ->order_by('divizion_num', 'asc')
                ->get()
                ->result_array();
    }



    public function get_trunks_by_id_rig_sort_distance($id)
    {



            $result = $this->db->query("CALL journal.`spec_basic_sort_trunks`({$id})");
        //$this->db->reconnect();
        mysqli_next_result($this->db->conn_id);
        $res = $result->result_array();

        return $res;

    }

    public function is_work_innerservice($name)
    {
        $query = $this->db->where([
                'name'      => $name,
                'is_delete' => 0
            ])
            ->get('speciald.work_innerservice');

        return $query->num_rows();
    }

    public function add_work_innerservice($data)
    {
        $this->db->insert('speciald.work_innerservice', $data);
        return $this->db->insert_id();
    }

    public function edit_work_innerservice_by_id($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.work_innerservice', $data);
    }

    public function delete_work_innerservice_by_id($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.work_innerservice', $data);
    }

    public function get_officebelong()
    {
        return $this->db->select('*')
                ->from('journal.officebelong')
                ->where([
                    'is_delete' => 0
                ])
                ->where('id != ', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }



    public function get_owner_categories()
    {
        return $this->db->select('*')
                ->from('journal.owner_categories')
                ->get()
                ->result_array();
    }


       public function get_view_work()
    {
        $this->db->select("w.*,r.name as reason_name");
        $this->db->join('journal.reasonrig as r', 'r.id=w.id_reasonrig');
        $this->db->order_by('name');
        $this->db->where('w.is_delete',0);
        $this->db->where('r.is_delete',0);
        $this->db->where('w.id != ',0);
        $res = $this->db->get('journal.workview as w')->result_array();
        return $res;
    }
}
