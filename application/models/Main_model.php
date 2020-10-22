<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class Main_model extends CI_Model
{

    const LEVEL_ID_RCU = 1; //RCU
    const LEVEL_ID_UMCHS = 2; //UMCHS
    const LEVEL_ID_ROCHS = 3; //ROCHS

    /* each level has property 'can_edit' and 'is_admin'.
      UMCHS level : umchs, rosn g.Minsk(all rosn), ugz g.Minsk(all), avia g.Minsk(all).
      ROCHS level: rochs, rosn, ugz, avia.
     */
    const ORGAN_ID_RCU = 5; //RCU
    const ORGAN_ID_UMCHS = 4; //UMCHS
    const ORGAN_ID_PASO = 6; //PASO
    const ORGAN_ID_PASO_OBJECT = 7; //PASO OBJECT
    const ORGAN_ID_GOCHS = 1; //GOCHS
    const ORGAN_ID_ROCHS = 2; //ROCHS
    const ORGAN_ID_GROCHS = 3; //GROCHS
    const ORGAN_ID_ROSN = 8; //ROSN
    const ORGAN_ID_UGZ = 9; //UGZ
    const ORGAN_ID_AVIA = 12; //AVIA
    const REGION_BREST = 1;
    const REGION_VITEBSK = 2;
    const REGION_MINSK = 3;
    const REGION_GOMEL = 4;
    const REGION_GRODNO = 5;
    const REGION_MOGILEV = 6;
    const REGION_MINOBL = 7;

    const GOMEL_ROCHS=45;
    const GOMEL_GOCHS=44;

    const GOMEL_LOCAL=28;
    const GOMEL_CITY=22;

    const PHOTO_CNT_PER_SD = 4;
    const VIDEO_CNT_PER_SD = 2;
    const AUDIO_CNT_PER_SD = 1;
    const TYPE_SD_STANDART = 1;
    const TYPE_SD_SIMPLE = 2;
    const TYPE_SD_TEMPLATE = 3;
    const VID_SD_MINIROVANIE = 130;
    const REGION_ID_RCU = 50; //RCU
    const OBJECT_MANY_FLOOR = 12;
    const OBJECT_AVTO_TRANSPORT = 17;
    const DIVIZ_COU = 8;
    const POS_HEAD_GARNISON = 14; // str.maincou. pos duty
    const POS_HEAD_INSPECTOR = 13; // str.maincou. pos duty
    const POS_DISP = 6; // str.maincou. pos duty

    const ID_OWNER_DEAD = 6;

    const CAR_OSNOVNAYA=1;
        const CAR_SPEC=2;
        const CAR_VSPOM=3;
        const CAR_ENG=4;



    public function __construct()
    {
        parent::__construct();
    }

    public $reasonrig_for_search = [34, 14];

    public function get_regions()
    {
        return $this->db->select('*')
                ->from('regions')
                ->get()
                ->result_array();
    }

    public function get_locals()
    {
        return $this->db->select('*')
                ->from('locals')
                ->get()
                ->result_array();
    }

    // cp organs
    public function get_organs_in_local($in_organ = false)
    {

        $this->db->select("o.name, l.id_local, l.id_organ as id,l.id_organ,reg.id as id_region");
        $this->db->join('ss.organs as o', 'o.id=l.id_organ', 'left');
        $this->db->join('ss.locals as loc', 'loc.id=l.id_local', 'left');
        $this->db->join('ss.regions as reg', 'reg.id=loc.id_region', 'left');
        //->where_in('o.id', array(5, 8, 9, 12))
        // ->group_by('o.name')

        if (!empty($in_organ) && $in_organ !== FALSE)
            $this->db->where_in('o.id', $in_organ);

        $this->db->order_by('o.name', 'ASC');

        return $this->db->get('ss.locorg as l')->result_array();
    }

    public function get_positions()
    {
        return $this->db->select('*')
                ->from('position')
                ->where('is_hide', 0)
                ->get()
                ->result_array();
    }

    public function get_ranks()
    {
        return $this->db->select('*')
                ->from('ranks')
                ->where('is_hide', 0)
                ->get()
                ->result_array();
    }

    public function get_reasonrig()
    {
        return $this->db->select('*')
                ->from('journal.reasonrig')
                ->where('is_delete', 0)
                ->where('id !=', 0)
                ->order_by('name', 'asc')
                //  ->where_in('id', $this->reasonrig_for_search)
                ->get()
                ->result_array();
    }

    public function get_work_innerservice()
    {
        return $this->db->select('*')
                ->from('speciald.work_innerservice')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function get_object_house()
    {
        return $this->db->select('*')
                ->from('speciald.object_house')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function get_object_house_by_user($id_user)
    {
        return $this->db->select('*')
                ->from('speciald.object_house')
                ->where('is_delete', 0)
                ->where('created_by', $id_user)
                ->get()
                ->result_array();
    }

    public function is_object_house($name)
    {
        $query = $this->db->where([
                'name'      => $name,
                'is_delete' => 0
            ])
            ->get('speciald.object_house');

        return $query->num_rows();
    }

    public function add_object_house($data)
    {
        $this->db->insert('speciald.object_house', $data);
        return $this->db->insert_id();
    }

    public function edit_object_house($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.object_house', $data);
    }

    public function get_object_material()
    {
        return $this->db->select('*')
                ->from('speciald.object_material')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function is_object_material($name)
    {
        $query = $this->db->where([
                'name'      => $name,
                'is_delete' => 0
            ])
            ->get('speciald.object_material');

        return $query->num_rows();
    }

    public function add_object_material($data)
    {
        $this->db->insert('speciald.object_material', $data);
        return $this->db->insert_id();
    }

    public function edit_object_material($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.object_material', $data);
    }

    public function get_object_roof()
    {
        return $this->db->select('*')
                ->from('speciald.object_roof')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function is_object_roof($name)
    {
        $query = $this->db->where([
                'name'      => $name,
                'is_delete' => 0
            ])
            ->get('speciald.object_roof');

        return $query->num_rows();
    }

    public function add_object_roof($data)
    {
        $this->db->insert('speciald.object_roof', $data);
        return $this->db->insert_id();
    }

    public function edit_object_roof($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.object_roof', $data);
    }

    public function get_people_status()
    {
        return $this->db->select('*')
                ->from('speciald.people_status')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function is_people_status($name)
    {
        $query = $this->db->where([
                'name'      => $name,
                'is_delete' => 0
            ])
            ->get('speciald.people_status');

        return $query->num_rows();
    }

    public function add_people_status($data)
    {
        $this->db->insert('speciald.people_status', $data);
        return $this->db->insert_id();
    }

    public function edit_people_status($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.people_status', $data);
    }

    public function get_type_water_source()
    {
        return $this->db->select('*')
                ->from('speciald.type_water_source')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function is_water_source($name)
    {
        $query = $this->db->where([
                'name'      => $name,
                'is_delete' => 0
            ])
            ->get('speciald.type_water_source');

        return $query->num_rows();
    }

    public function add_water_source($data)
    {
        $this->db->insert('speciald.type_water_source', $data);
        return $this->db->insert_id();
    }

    public function edit_water_source($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.type_water_source', $data);
    }

    public function get_vid_specd()
    {
        return $this->db->select('*')
                ->from('speciald.vid_specd')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function get_vid_hs_1()
    {
        return $this->db->select('*')
                ->from('speciald.vid_hs_1')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function get_vid_hs_2()
    {
        return $this->db->select('*')
                ->from('speciald.vid_hs_2')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function get_innerservice_list()
    {
        return $this->db->select('*')
                ->from('journal.service')
                ->where('is_delete', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function get_firereason()
    {
        return $this->db->select('*')
                ->from('journal.firereason')
                ->where('is_delete', 0)
                ->where('id !=', 0)
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function get_first_part_number_sd($id_region)
    {
        $res = $this->db->select('first_part')
            ->from('number_sd')
            ->where('id_region', $id_region)
            ->get()
            ->row_array();
        return $res['first_part'];
    }

    public function get_face_belong()
    {
        return $this->db->select('*')
                ->from('face_belong')
                ->get()
                ->result_array();
    }

    public function get_api_source()
    {
        return $this->db->select('*')
                ->from('api_source')
                ->order_by('name', 'asc')
                ->get()
                ->result_array();
    }

    public function get_map_center_by_local($id_local)
    {
        return $this->db->select('*')
                ->from('speciald.map_center_locals')
                ->where('id_local', $id_local)
                ->get()
                ->row_array();
    }

    public function get_map_center_by_region($id_region)
    {
        return $this->db->select('*')
                ->from('speciald.map_center_regions')
                ->where('id_region', $id_region)
                ->get()
                ->row_array();
    }

    public function get_grochs_list($not_in_organ = false, $id_grochs = false)
    {

        $this->db->select("`reg`.`id`            AS `id_region`,
  `reg`.`name`          AS `regionn`,
  (CASE WHEN (`organ`.`id` = 8) THEN `organ`.`name` WHEN (`reg`.`name` = 'г.Минск') THEN CONVERT(CONCAT('Минское ГУМЧС') USING utf8) ELSE CONCAT(REPLACE(`reg`.`name`,'ая','ое'),' ','ОУМЧС') END) AS `region_name`,
  `loc`.`id`            AS `id_local`,
  `loc`.`name`          AS `local_name`,
  `organ`.`id`          AS `id_organ`,
  (CASE WHEN (`organ`.`id` = 7) THEN CONCAT(`organ`.`name`,' № ',`gr`.`no`) ELSE `organ`.`name` END) AS `organ_name`,
  (CASE WHEN (`organ`.`id` = 7) THEN CONCAT(`organ`.`name`,' № ',`gr`.`no`,' ',REPLACE(`loc`.`name`,'ий','ого'),' ',`orgg`.`name`) WHEN (`organ`.`id` = 13) THEN CONCAT(`organ`.`name`,' ',`loc`.`name`)
  ELSE  CONCAT(`loc`.`name`,' ',`organ`.`name`)  END) AS `full_grochs_name`,
  `gr`.`id`    AS `id_locorg`");
        $this->db->join('ss.`organs` `organ`', '`gr`.`id_organ` = `organ`.`id`', 'left');
        $this->db->join('ss.`organs` `orgg`', '`gr`.`oforg` = `orgg`.`id`', 'left');
        $this->db->join('ss.`locals` `loc`', '`gr`.`id_local` = `loc`.`id`', 'left');
        $this->db->join('ss.`regions` `reg`', '`loc`.`id_region` = `reg`.`id`', 'left');

        if (!empty($not_in_organ) && $not_in_organ !== FALSE)
            $this->db->where_not_in('organ.id', $not_in_organ);

        if ($id_grochs !== FALSE)
            $this->db->where('gr.id', $id_grochs);

        $this->db->group_by('gr.`id`');
        $this->db->order_by('loc.name', 'ASC');

        return $this->db->get('ss.`locorg` `gr`')->result_array();
    }

    public function get_id_umchs_by_region($id_region, $id_organ)
    {
        $this->db->select("l.*");
        $this->db->join('ss.`locals` `loc`', '`l`.`id_local` = `loc`.`id`', 'left');
        $this->db->where_in('loc.id_region', $id_region);
        $this->db->where_in('l.id_organ', $id_organ);
        $this->db->limit(1);

        $res = $this->db->get('ss.locorg as l')->row_array();
        return $res['id'];
    }

    public function get_head_garnison_from_str($id_grochs, $id_pos_duty, $id_divizion)
    {
        $this->db->select("m.*");
        $this->db->join('ss.`records` `rec`', '`rec`.`id` = `m`.`id_card`', 'left');
        $this->db->where('m.id_pos_duty', $id_pos_duty);
        $this->db->where('rec.id_divizion', $id_divizion);
        $this->db->where('rec.id_loc_org', $id_grochs);
        $this->db->where('m.is_duty', 1);
        $this->db->group_by('m.id_pos_duty');
        $this->db->limit(1);

        $res = $this->db->get('str.maincou as m')->row_array();
        return $res;
    }

    public function get_avtotransport_vid()
    {
        $this->db->select("*");
        $this->db->group_by('name');
        $res = $this->db->get('avtotransport_vid')->result_array();
        return $res;
    }

    public function get_theme_messages()
    {
        $this->db->select("*");
        $this->db->group_by('name');
        $res = $this->db->get('theme_messages')->result_array();
        return $res;
    }


        public function get_posduty_from_str_by_ch($id_grochs, $id_divizion)
    {
        $this->db->select("m.*, rec.id_loc_org as id_grochs");
        $this->db->join('ss.`records` `rec`', '`rec`.`id` = `m`.`id_card`', 'left');
        $this->db->where('rec.id_divizion', $id_divizion);
                if(is_array($id_grochs))
        $this->db->where_in('rec.id_loc_org', $id_grochs);
        else
            $this->db->where('rec.id_loc_org', $id_grochs);

        $this->db->group_by('m.id_card');
        $this->db->group_by('m.ch');
        $this->db->group_by('m.id_pos_duty');


        $res = $this->db->get('str.maincou as m')->result_array();
        //echo $this->db->last_query();
        return $res;
    }

            public function get_posduty_listfio_from_str_by_ch($id_grochs, $id_divizion)
    {
        $this->db->select("m.*, lf.fio, r.name as rank_name, rec.id_loc_org as id_grochs");
        $this->db->join('ss.`records` `rec`', '`rec`.`id` = `m`.`id_card`', 'left');
        $this->db->join('str.`listfio` `lf`', '`lf`.`id` = `m`.`id_fio`', 'left');
        $this->db->join('str.`rank` `r`', '`r`.`id` = `lf`.`id_rank`', 'left');
        $this->db->where('rec.id_divizion', $id_divizion);

        if(is_array($id_grochs))
        $this->db->where_in('rec.id_loc_org', $id_grochs);
        else
            $this->db->where('rec.id_loc_org', $id_grochs);


        $this->db->group_by('m.id_card');
        $this->db->group_by('m.ch');
        $this->db->group_by('m.id_pos_duty');
        $this->db->order_by('is_duty','desc');


        $res = $this->db->get('str.maincou as m')->result_array();
        //echo $this->db->last_query();exit();
        return $res;
    }


        public function get_filter_by_user($id_user)
    {
        $this->db->select("*");
        $this->db->where('id_user',$id_user);
        $res = $this->db->get('speciald.filter_user')->row_array();
        return $res;
    }

        public function edit_filter_user($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('speciald.filter_user', $data);
    }

        public function add_filter_user($data)
    {
        $this->db->insert('speciald.filter_user', $data);
    }

}
