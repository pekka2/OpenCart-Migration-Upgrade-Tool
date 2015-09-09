 <?php
class ModelUpgradeCollate extends Model{
  private $lang;
  private $simulate;
  private $showOps;
  private $version;
  private $collatecounter;
  private $columncollatecounter;
  private $max;
  private $min;

  public function addCollate( $data ) {  
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        $this->upgrade  = $data['upgrade'];
        $this->max = 9;
        $this->min = 4;
        $this->version = $this->structure->getVersion();
        $this->lang = $this->lmodel->get('upgrade_database');
        $this->languages = $this->structure->language(); 

        $this->collatecounter = 0;
        $this->columncollatecounter = 0;
        $text = '';
        
       $text .= $this->collateTable();
	$text .= '<div class="header round"> ';
        $text .=  sprintf( addslashes($this->lang['msg_column_collate_count']), $this->columncollatecounter, '' );
        $text .= ' </div>';
        
        return $text;
  }

  public function collateTable(){
  	$text = '';

  	  if( $this->version > $this->min ){
  	  	
  	  	$tables = $this->structure->tables();
  	  	$columns = array();
 
  	  	foreach ($tables as $table){
  	  		if( $table != DB_PREFIX . 'affiliate' && $table != DB_PREFIX . 'customer' && $table != DB_PREFIX . 'user'){
  	  			
  	  			  $sql = "
		  	     ALTER TABLE `" . $table . " COLLATE `utf8_general_ci`";
		     if( !$this->simulate )  {
                            $this->db->query( $sql );
                     }
                     if( $this->showOps ) {
                            $text .= '<p><pre>' . $sql .'</pre></p>';
                     }
                     ++$this->collatecounter;
  	  		 $columns[$table] = $this->structure->getColumnTypeVarchar($table);
  	  		}
  	  	}
  	  	
	       $text .= '<div class="header round"> ';
        $text .=  sprintf( addslashes($this->lang['msg_collate_count']), $this->collatecounter, '' );
        $text .= ' </div>';
        
  	  $keys = array_keys($columns);
  	  for($i = 0; $i<count($keys);$i++){
  	    if( count($columns[$keys[$i]]) > 0 ){ 	
		  	    	for( $j = 0; $j<count( $columns[$keys[$i]] ); $j++){
		  	     $sql = "
		  	     ALTER TABLE `" . $keys[$i] . "` MODIFY `" . $columns[$keys[$i]][$j]['field'] . "` " . $columns[$keys[$i]][$j]['type'] ." " . $columns[$keys[$i]][$j]['null'] . " COLLATE `utf8_general_ci`";
					if( !$this->simulate )  {
		                               $this->db->query( $sql );
		                        }
		                        if( $this->showOps ) {
		                               $text .= '<p><pre>' . $sql .'</pre></p>';
		                        }
                     ++$this->columncollatecounter;
		  	     }
	  	  }
  	  }
  	  }
      return  $text;
  }
