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

	const LEVEL_ID_RCU=1;//RCU
	const LEVEL_ID_UMCHS=2;//UMCHS
	const LEVEL_ID_ROCHS=3;//ROCHS

	/* each level has property 'can_edit' and 'is_admin'.
	UMCHS level : umchs, rosn g.Minsk(all rosn), ugz g.Minsk(all), avia g.Minsk(all).
	ROCHS level: rochs, rosn, ugz, avia.
	*/

	const ORGAN_ID_RCU=5;//RCU
	const ORGAN_ID_UMCHS=4;//UMCHS

	const ORGAN_ID_PASO=6;//PASO
	const ORGAN_ID_PASO_OBJECT=7;//PASO OBJECT

	const ORGAN_ID_GOCHS=1;//GOCHS
	const ORGAN_ID_ROCHS=2;//ROCHS
	const ORGAN_ID_GROCHS=3;//GROCHS

	const ORGAN_ID_ROSN=8;//ROSN
	const ORGAN_ID_UGZ=9;//UGZ
	const ORGAN_ID_AVIA=12;//AVIA


        const REGION_BREST=1;
        const REGION_VITEBSK=2;
        const REGION_MINSK=3;
        const REGION_GOMEL=4;
        const REGION_GRODNO=5;
        const REGION_MOGILEV=6;
        const REGION_MINOBL=7;

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
    public function get_organs_in_local()
    {

        $this->db->select("o.name, l.id_local, l.id_organ as id")
            ->join('ss.organs as o', 'o.id=l.id_organ', 'left')
            ->where_in('o.id', array(5, 8, 9, 12))
            // ->group_by('o.name')
            ->order_by('o.name', 'ASC');

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
}
