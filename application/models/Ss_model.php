<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class Ss_model extends CI_Model
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

    public function get_regions()
    {
        return $this->db->select('*')
                ->from('ss.regions')
                ->get()
                ->result_array();
    }

    public function set_regions_cp_list()
    {
        $regions = $this->get_regions();
        $cp_region[] = array('id' => 8, 'name' => 'РОСН');
        $cp_region[] = array('id' => 9, 'name' => 'УГЗ');
        $cp_region[] = array('id' => 12, 'name' => 'Авиация');
        return array_merge($regions, $cp_region);
    }

    public function set_regions_cp_list_reports()
    {
        $regions = $this->get_regions();
        $cp_region[] = array('id' => 8, 'name' => 'РОСН');
        $cp_region[] = array('id' => 9, 'name' => 'УГЗ');
        $cp_region[] = array('id' => 12, 'name' => 'Авиация');
        $cp_region[] = array('id' => 50, 'name' => 'РЦУРЧС');
        return array_merge($regions, $cp_region);
    }

    public function get_organ_name_by_id($id)
    {
        $res = $this->db->select('*')
            ->from('ss.organs')
            ->where('id', $id)
            ->get()
            ->row_array();
        return $res['name'];
    }

    public function get_region_name_by_id($id)
    {
        $res = $this->db->select('*')
            ->from('ss.regions')
            ->where('id', $id)
            ->get()
            ->row_array();
        return $res['name'];
    }

        public function get_locorg_name_by_id($id)
    {
        $res = $this->db->select('auth')
            ->from('ss.caption')
            ->where('locorg_id', $id)
            ->limit(1)
            ->get()
            ->row_array();
        return $res['auth'];
    }

    public function get_shtat_ch_by_id_pasp_and_ch($id_pasp, $ch)
    {

        if ($ch == 1)
            $this->db->select('m.change_one as cnt');
        elseif ($ch == 2)
            $this->db->select('m.change_two as cnt');
        elseif ($ch == 3)
            $this->db->select('m.change_three as cnt');

        $this->db->where('m.id_record', $id_pasp);
        $this->db->limit(1);

        $res = $this->db->get('ss.staff AS m')->row_array();
        return $res;
    }
}
