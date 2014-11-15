<?php
 class ModelToolDatabase extends Model{

   public function hasSetting( $val ) {
	$sql = '
	SELECT
		*
	FROM
		`' . DB_PREFIX . 'setting`
	WHERE
		`key` = \'' . $val . '\'';

	$result = $this->db->query( $sql );

	if( count( $result->row ) == 0 ) {
		return false;
	}

	return true;
   }
}
