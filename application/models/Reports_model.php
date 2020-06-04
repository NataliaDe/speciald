<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class Reports_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_time_sd($filter)
    {
        $this->db->select("d.is_copy, u.id_region as creator_region,u.id_organ as creator_organ,u.fio as creator_fio,d.id as id_dones,d.official_date_start, d.official_date_end,d.specd_date,d.specd_number,d.short_description,d.opening_description, (CASE WHEN (`u`.`id_organ` = 5) THEN CONVERT(CONCAT('РЦУРЧС') USING utf8)
WHEN ((`u`.`id_organ` = 8) AND (`r`.`id` = 3)) THEN CONCAT('РОСН',', ',`r`.`name`)
WHEN ((`u`.`id_organ` = 8) AND (`r`.`id` <> 3)) THEN CONCAT('РОСН',', ',`loc`.`name`,' район')
WHEN ((`u`.`id_organ` = 9) AND (`r`.`id` = 3)) THEN CONCAT('УГЗ',', ',`r`.`name`) WHEN ((`u`.`id_organ` = 9) AND (`r`.`id` <> 3)) THEN CONCAT('УГЗ',', ',`loc`.`name`)
WHEN (`u`.`id_organ` = 12) THEN CONCAT('Авиация',', ',`r`.`name`)
 WHEN ((`loc`.`name` IS NOT NULL) AND (LOCATE('.',`loc`.`name`) > 0) AND (`r`.`id` <> 3)) THEN CONCAT(`r`.`name`,' область, ',`loc`.`name`)
 WHEN ((`loc`.`name` IS NOT NULL) AND (LOCATE('.',`loc`.`name`) > 0) AND (`r`.`id` = 3)) THEN CONCAT(`r`.`name`)
 WHEN ((`loc`.`name` IS NOT NULL) AND (LOCATE('.',`loc`.`name`) <= 0) AND (`r`.`id` <> 3)) THEN CONCAT(`r`.`name`,' область, ',`loc`.`name`,' район')
  WHEN ((`loc`.`name` IS NOT NULL) AND (LOCATE('.',`loc`.`name`) <= 0) AND (`r`.`id` = 3)) THEN CONCAT(`r`.`name`,', ',`loc`.`name`,' район')
  ELSE CONCAT(`r`.`name`,' область') END) AS `full_creator_name`");
        $this->db->join('users as u', 'u.id=d.created_by', 'left');
        $this->db->join('regions as r', 'r.id = u.id_region', 'left');
        $this->db->join('locals as loc', 'loc.id = u.id_local', 'left');
        $this->db->where('d.is_delete', 0);


        if (isset($filter['id_region_creator']) && !empty($filter['id_region_creator'])) {
            $this->db->where('u.id_region', $filter['id_region_creator']);
        } elseif (isset($filter['id_organ_creator']) && !empty($filter['id_organ_creator'])) {
            $this->db->where('u.id_organ', $filter['id_organ_creator']);
        } else {
            $this->db->order_by('u.id_region', 'asc');
        }

        if (isset($filter['from']) && !empty($filter['from']) && isset($filter['to']) && !empty($filter['to'])) {
            $this->db->where('d.specd_date >=', $filter['from']);
            $this->db->where('d.specd_date <=', $filter['to']);
        }
        $this->db->order_by('d.date_insert', 'desc');

        $result = $this->db->get('dones as d')->result_array();
        return $result;
    }
}
