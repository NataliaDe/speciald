<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class User_model
 * @property CI_DB_query_builder $db
 */
class User_model extends CI_Model
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
	

    /**
     * Возвращает список всех пользователей
     * @return array
     */
    public function all()
    {
        return $this->db->select('*')
            ->from('users')
            ->order_by('first_name, last_name')
            ->get()
            ->result_array();
    }

    public static function has_login($identity)
    {
        $CI = &get_instance();

        //Ищем пользователя по email или логину
        $userData = $CI->db->select('u.*, ug.group_id')
            ->join('users_groups ug', 'u.id = ug.user_id', 'inner')
            ->where('u.email', $identity)
            ->or_where('u.username', $identity)
            ->get('users u');

        $login = $userData->row('username');
        //Только для студентов
        if (!empty($login) && $userData->row('group_id') == 3) {
            $CI->ion_auth_model->identity_column = 'username';
        }
    }

    public static function has_first_entry()
    {

        $CI = &get_instance();

        $user = $CI->ion_auth->user()->row();

        if (empty($user->last_login)) {
            $CI->ion_auth->update($user->id, ['password_changed' => 0]);
        }
    }

    public function validate_login()
    {
        $CI = &get_instance();
    }

    /**
     * Получаем дату увольнения
     *
     */
    public static function has_expired($identity)
    {

        $CI = &get_instance();

        $userData = $CI->db->select('*')
            ->where('email', $identity)
            ->or_where('username', $identity)
            ->get('users');

        $dismissal = $userData->row('dismissal_date');
        return $dismissal;
    }

    public function has_login_api($identity)
    {

        $CI = &get_instance();

        $userData = $CI->db->where('email', $identity)
            ->or_where('username', $identity)
            ->get('users');

        $login = $userData->row('username');

        if (!empty($login)) {
            $CI->ion_auth_model->identity_column = 'username';
        }
    }

    public function getUserByExternalId($id)
    {
        $CI = &get_instance();

        $CI->db->where('external_idx', $id);
        return $CI->db->get('users')->row_array();
    }

    public function get_all_user_list($not = null)
    {
        $CI = &get_instance();
        $CI->db->where('role_default !=', 1);
        if ($not) {
            $CI->db->where_not_in('id', $not);
        }

        $CI->db->order_by('first_name, last_name', 'asc');

        return $CI->db->get('users')->result_array();
    }

    public function get_user($id)
    {
        $CI = &get_instance();

        $CI->db->select('first_name, midle_name, last_name, org_structure_level_id, position, parent_id_, person_id');
        $CI->db->where('id', $id);
        return $CI->db->get('users')->row_array();
    }

    public function get_user_role($id)
    {
        $CI = &get_instance();

        $CI->db->select('group_id');
        $CI->db->where('users.id', $id);
        $CI->db->join('users_groups', 'users_groups.user_id=users.id');
        return $CI->db->get('users')->row_array();
    }

    public function saveLRFill($user_id, $data)
    {
        $CI = &get_instance();
        return $CI->db->update('users', ['lr_fill_autosave' => (is_null($data)) ? null : json_encode($data)], ['id' => $user_id]);
    }

    /**
     * Отправляет уведомление пользователю
     * @param $data
     */
    public function sendNotification($data)
    {
        $this->db->insert('notification', $data);
    }
}
