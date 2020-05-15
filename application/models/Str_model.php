<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class Str_model extends CI_Model
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

    public function is_last_dateduty_car($id_teh)
    {
        $query = $this->db->where([
                'id_teh' => $id_teh
            ])
            ->where('dateduty IS NOT NULL')
            ->order_by('dateduty', 'desc')
            ->limit(1)
            ->get('str.car');

        return $query->num_rows();
    }

    public function get_last_dateduty_car($id_teh)
    {
        $query = $this->db->select('dateduty')
                ->from('str.car')
                ->where([
                    'id_teh' => $id_teh
                ])
                ->order_by('dateduty', 'desc')
                ->limit(1)
                ->get()->row_array();

        return $query['dateduty'];
    }

    public function get_man_per_car($id_teh)
    {
        $dateduty = $this->get_last_dateduty_car($id_teh);
        $this->db->select('COUNT(fc.`id_fio`) AS cnt_man');
        $this->db->join('str.fiocar AS fc', 'c.`id`=fc.`id_tehstr`', 'left');
        $this->db->where('c.id_teh', $id_teh);
        $this->db->where('c.dateduty', $dateduty);

        $res = $this->db->get('str.car AS c')->row_array();
        return $res['cnt_man'];
    }

    public function get_man_per_car_by_ch($id_teh, $ch)
    {
        //$dateduty=$this->get_last_dateduty_car($id_teh);
        $this->db->select('COUNT(fc.`id_fio`) AS cnt_man');
        $this->db->join('str.fiocar AS fc', 'c.`id`=fc.`id_tehstr`', 'left');
        $this->db->where('c.id_teh', $id_teh);
        $this->db->where('c.ch', $ch);
        $query = $this->db->get('str.car AS c');


        $num = $query->num_rows();

        $res = $query->row_array();
        return array('cnt_man' => $res['cnt_man'], 'is_result' => $num);
    }

    public function get_inf_by_id_pasp($id_pasp)
    {
        return $this->db->select('*, concat(pasp_name_spec," ",locorg_name_spec) as full_name_podr ')
                ->from('str.spec_tbl_dones')
                ->where_in('id_pasp', $id_pasp)
                ->order_by('locorg_name_spec', 'asc')
                ->order_by('sort_diviz', 'asc')
                ->order_by('divizion_num', 'asc')
                ->get()
                ->result_array();
    }

    public function get_main_by_id_pasp($id_pasp)
    {

        $this->db->select('m.dateduty,m.ch,m.id_card,m.listls as on_list_ch,m.countls as shtat_ch, m.vacant as vacant_ch,m.face as face_ch,m.calc as br_ch, m.duty as cnt_naryd, m.gas, m.fio_duty');
        $this->db->where('m.id_card', $id_pasp);
        $this->db->where('m.is_duty', 1);
        $this->db->order_by('m.dateduty', 'desc');
        $this->db->limit(1);

        $res = $this->db->get('str.main AS m')->row_array();
        return $res;
    }

    public function get_shtat_of_pasp_by_id_pasp($id_pasp)
    {
        /* all man in pasp */
        $this->db->select('count(l.id) as shtat');
        $this->db->join('str.listfio as l ', 'l.id_cardch=c.id', 'left');
        $this->db->where('c.id_card', $id_pasp);

        $res = $this->db->get('str.cardch AS c')->row_array();
        return $res['shtat'];
    }

    public function get_vacant_of_pasp_by_id_pasp($id_pasp)
    {
        /* all vacant in pasp */
        $this->db->select('count(l.id) as vacant');
        $this->db->join('str.listfio as l ', 'l.id_cardch=c.id', 'left');
        $this->db->where('c.id_card', $id_pasp);
        $this->db->where('l.is_vacant', 1);

        $res = $this->db->get('str.cardch AS c')->row_array();
        return $res['vacant'];
    }

    public function get_cnt_trip_by_id_pasp($id_pasp, $dateduty, $ch)
    {
        /* man in trip  */
        $this->db->select('t.id, t.id_fio,date_format(t.date1,"%d.%m.%Y") AS date1,date_format(t.date2,"%d.%m.%Y") AS date2,'
            . ' t.place,t.is_cosmr, t.prikaz,l.fio, po.name as position');
        $this->db->join('str.listfio AS l ', 't.id_fio=l.id', 'left');
        $this->db->join('str.position as po ', 'po.id=l.id_position', 'left');
        $this->db->join('str.cardch AS c', 'l.id_cardch=c.id', 'left');
        $this->db->where('(c.id_card = ' . $id_pasp . ' AND c.ch = ' . $ch . ') AND ( (t.date1 <= "' . $dateduty . '" AND t.date2 >= "' . $dateduty . '")  OR ( t.date1 <= "' . $dateduty . '" AND t.date2 is null) )');


        $res = $this->db->get('str.trip AS t')->result_array();

//        $str = $this->db->last_query();
//
//    echo "<pre>";
//    print_r($str);
//    exit();
        return $res;
    }

    public function get_cnt_holiday_by_id_pasp($id_pasp, $dateduty, $ch)
    {
        /* man on holiday */
        $this->db->select('h.id, h.id_fio,date_format(h.date1,"%d.%m.%Y") AS date1,date_format(h.date2,"%d.%m.%Y") AS date2,'
            . ' h.prikaz, l.fio, po.name as position');
        $this->db->join('str.listfio AS l ', 'h.id_fio=l.id', 'left');
        $this->db->join('str.position as po ', 'po.id=l.id_position', 'left');
        $this->db->join('str.cardch AS c', 'l.id_cardch=c.id', 'left');
        $this->db->where('(c.id_card = ' . $id_pasp . ' AND c.ch = ' . $ch . ') AND ( (h.date1 <= "' . $dateduty . '" AND h.date2 >= "' . $dateduty . '")  OR ( h.date1 <= "' . $dateduty . '" AND h.date2 is null) )');


        $res = $this->db->get('str.holiday AS h')->result_array();

//        $str = $this->db->last_query();
//
//    echo "<pre>";
//    print_r($str);
//    exit();
        return $res;
    }

    public function get_cnt_ill_by_id_pasp($id_pasp, $dateduty, $ch)
    {
        /* man on holiday */
        $this->db->select('i.id, i.id_fio,date_format(i.date1,"%d.%m.%Y") AS date1,date_format(i.date2,"%d.%m.%Y") AS date2,'
            . ' i.diagnosis,l.fio, ma.name as maim, po.name as position');
        $this->db->join('str.listfio AS l ', 'i.id_fio=l.id', 'left');
        $this->db->join('str.position as po ', 'po.id=l.id_position', 'left');
        $this->db->join('str.cardch AS c', 'l.id_cardch=c.id', 'left');
        $this->db->join('str.maim AS ma', 'i.maim=ma.id', 'left');
        $this->db->where('(c.id_card = ' . $id_pasp . ' AND c.ch = ' . $ch . ') AND ( (i.date1 <= "' . $dateduty . '" AND i.date2 >= "' . $dateduty . '")  OR ( i.date1 <= "' . $dateduty . '" AND i.date2 is null) )');


        $res = $this->db->get('str.ill AS i')->result_array();

//        $str = $this->db->last_query();
//
//    echo "<pre>";
//    print_r($str);
//    exit();
        return $res;
    }

    public function get_cnt_other_by_id_pasp($id_pasp, $dateduty, $ch)
    {
        /* man on holiday */
        $this->db->select('o.id, o.id_fio,date_format(o.date1,"%d.%m.%Y") AS date1, date_format(o.date2,"%d.%m.%Y") AS date2,'
            . ' o.reason, o.note, l.fio, po.name as position');
        $this->db->join('str.listfio AS l ', 'o.id_fio=l.id', 'left');
        $this->db->join('str.position as po ', 'po.id=l.id_position', 'left');
        $this->db->join('str.cardch AS c', 'l.id_cardch=c.id', 'left');

        $this->db->where('(c.id_card = ' . $id_pasp . ' AND c.ch = ' . $ch . ') AND ( (o.date1 <= "' . $dateduty . '" AND o.date2 >= "' . $dateduty . '")  OR ( o.date1 <= "' . $dateduty . '" AND o.date2 is null) )');


        $res = $this->db->get('str.other AS o')->result_array();

//        $str = $this->db->last_query();
//
//    echo "<pre>";
//    print_r($str);
//    exit();
        return $res;
    }

    public function get_cnt_vacant_inf_by_id_pasp($id_pasp, $ch)
    {
        /* man on holiday */
        $this->db->select('l.fio, po.name as position');
        $this->db->join('str.cardch AS c ', 'l.id_cardch=c.id', 'left');
        $this->db->join('str.position as po', 'po.id=l.id_position', 'left');


        $this->db->where('c.id_card', $id_pasp);
        $this->db->where('c.ch', $ch);
        $this->db->where('l.is_vacant', 1);


        $res = $this->db->get('str.listfio AS l')->result_array();

//        $str = $this->db->last_query();
//
//    echo "<pre>";
//    print_r($str);
//    exit();
        return $res;
    }

    public function get_maincou_by_id_pasp($id_pasp)
    {

        $this->db->select('m.dateduty,m.ch,m.id_card');
        $this->db->where('m.id_card', $id_pasp);
        $this->db->where('m.is_duty', 1);
        $this->db->order_by('m.dateduty', 'desc');
        $this->db->limit(1);

        $res = $this->db->get('str.maincou AS m')->row_array();
        return $res;
    }

    public function get_vacant_in_ch_cou($id_pasp, $ch)
    {
        /* all man in pasp */
        $this->db->select('count(l.id) as vacant_ch');
        $this->db->join('str.listfio as l ', 'l.id_cardch=c.id', 'left');
        $this->db->where('c.id_card', $id_pasp);
        $this->db->where('l.is_vacant', 1);
        $this->db->where('c.ch', $ch);

        $res = $this->db->get('str.cardch AS c')->row_array();
        return $res['vacant_ch'];
    }

    public function get_listls_in_ch_cou($id_pasp, $ch)
    {
        /* all man in pasp */
        $this->db->select('count(l.id) as vacant_ch');
        $this->db->join('str.listfio as l ', 'l.id_cardch=c.id', 'left');
        $this->db->where('c.id_card', $id_pasp);
        $this->db->where('l.is_vacant', 0);
        $this->db->where('c.ch', $ch);

        $res = $this->db->get('str.cardch AS c')->row_array();
        return $res['vacant_ch'];
    }

    public function get_all_in_ch_cou($id_pasp, $ch)
    {

        $this->db->select('*');
        $this->db->where('m.id_card', $id_pasp);
        $this->db->where('m.ch', $ch);
        $this->db->where('m.is_duty', 1);
        $this->db->order_by('m.dateduty', 'desc');

        $res = $this->db->get('str.maincou AS m')->result_array();
        return $res;
    }

    public function is_car_in_ch_cou($id_pasp, $ch)
    {

        $this->db->select('id');
        $this->db->where('id_card', $id_pasp);
        $this->db->where('ch', $ch);
        $this->db->order_by('dateduty', 'desc');
        $this->db->limit(1);

        $res = $this->db->get('str.carcou')->num_rows();
        return $res;
    }

    public function br_in_pasp_on_date($id_pasp, $dateduty)
    {

        $result = $this->db->query("CALL str.`getBrOfPasp`({$id_pasp}, '{$dateduty}')");
        //$this->db->reconnect();
        mysqli_next_result($this->db->conn_id);
        $res = $result->result_array();

        return array_sum(array_column($res, 'cnt_in_br'));
    }

    public function get_shtat_in_ch_cou($id_pasp, $ch)
    {
        /* all man in pasp */
        $this->db->select('count(l.id) as shtat_ch');
        $this->db->join('str.listfio as l ', 'l.id_cardch=c.id', 'left');
        $this->db->where('c.id_card', $id_pasp);
        $this->db->where('c.ch', $ch);

        $res = $this->db->get('str.cardch AS c')->row_array();
        return $res['shtat_ch'];
    }

    public function get_mainrcu_by_id_pasp($id_pasp)
    {

        $this->db->select('m.dateduty,m.ch,m.id_card');
        $this->db->where('m.id_card', $id_pasp);
        $this->db->where('m.is_duty', 1);
        $this->db->order_by('m.dateduty', 'desc');
        $this->db->limit(1);

        $res = $this->db->get('str.mainrcu AS m')->row_array();
        return $res;
    }

    public function get_all_in_rcu_cou($id_pasp, $ch)
    {

        $this->db->select('*');
        $this->db->where('m.id_card', $id_pasp);
        $this->db->where('m.ch', $ch);
        $this->db->where('m.is_duty', 1);
        $this->db->order_by('m.dateduty', 'desc');

        $res = $this->db->get('str.mainrcu AS m')->result_array();
        return $res;
    }

    public function get_locorg_cp_list($ids_region = null)
    {
        $this->db->select('distinct (id_loc_org), locorg_name  ');

        if ($ids_region != null && !empty($ids_region) && is_array($ids_region))
            $this->db->where_in('id_region', $ids_region);
        elseif ($ids_region != null && !empty($ids_region) && !is_array($ids_region))
            $this->db->where('id_region', $ids_region);

        $this->db->order_by('locorg_name', 'asc');

        return $this->db->get('str.spec_list_podr_for_search_str_cars')->result_array();
    }

    public function get_pasp_cp_list($ids_locorg = null)
    {
        $this->db->select('*');

        if ($ids_locorg != null && !empty($ids_locorg) && is_array($ids_locorg))
            $this->db->where_in('id_loc_org', $ids_locorg);
        elseif ($ids_locorg != null && !empty($ids_locorg) && !is_array($ids_locorg))
            $this->db->where('id_loc_org', $ids_locorg);

        $this->db->order_by('of_locorg_name_spec', 'asc');
        $this->db->order_by('sort_diviz', 'asc');
        $this->db->order_by('divizion_num', 'asc');

        return $this->db->get('str.spec_list_podr_for_search_str_cars')->result_array();
    }

    public function get_dutych()
    {
        $query = $this->db->select('*')
                ->from('str.dutych')
                ->order_by('start_date', 'desc')
                ->limit(1)
                ->get()->row_array();

        return $query;
    }

    public function get_cars_by_pasp($id_pasp, $dateduty, $ch)
    {

        $result = $this->db->query("CALL str.`getAllCarsInPasp`({$id_pasp}, '{$dateduty}', '{$ch}')");
        //$this->db->reconnect();
        mysqli_next_result($this->db->conn_id);
        $res = $result->result_array();

        return $res;
    }

    public function get_main_by_id_pasp_and_dateduty($id_pasp, $dateduty)
    {

        $this->db->select('m.dateduty,m.ch,m.id_card,m.listls as on_list_ch,m.countls as shtat_ch, m.vacant as vacant_ch,m.face as face_ch,m.calc as br_ch, m.duty as cnt_naryd, m.gas, m.fio_duty');
        $this->db->where('m.id_card', $id_pasp);
        $this->db->where('m.dateduty', $dateduty);
        $this->db->limit(1);

        $res = $this->db->get('str.main AS m')->row_array();
        return $res;
    }

    public function get_maincou_by_id_pasp_and_dateduty($id_pasp, $dateduty)
    {

        $this->db->select('m.dateduty,m.ch,m.id_card');
        $this->db->where('m.id_card', $id_pasp);
        $this->db->where('m.dateduty', $dateduty);
        $this->db->limit(1);

        $res = $this->db->get('str.maincou AS m')->row_array();
        return $res;
    }

    public function get_mainrcu_by_id_pasp_and_dateduty($id_pasp, $dateduty)
    {

        $this->db->select('m.dateduty,m.ch,m.id_card');
        $this->db->where('m.id_card', $id_pasp);
        $this->db->where('m.dateduty', $dateduty);
        $this->db->limit(1);

        $res = $this->db->get('str.mainrcu AS m')->row_array();
        return $res;
    }
}
