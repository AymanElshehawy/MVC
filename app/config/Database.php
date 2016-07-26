<?php
require_once '../app/config/config.php';
/**
* 
*/
class Database
{
	public $connection;
	/**
	* here connect to DB
	*/
	public function __construct()
	{
		$this->connect();
	}

	/**
	* this function connect to DB
	*/
	public function connect()
	{
		$this->connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

		if(mysqli_connect_errno())
		{
		    echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	}

	/**
	* @param $tableName
	* @param $data in array
	* @return $last_id_insert
	* this function insert into table
	*/
	public function insert($table,$data)
	{
		$sql = "INSERT INTO `".$table."` ";
		$v=''; $n='';
		foreach($data as $key=>$val) {
			$n.="`$key`, ";
			if(empty($val)) $v.="'', ";
			else$v.= "'".$val."', ";
		}
	
		$sql .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";
		$query = $this->query($sql);
		if($query)
		{
			return mysqli_insert_id($this->connection);
		}
	}

	/**
	* @param $tableName
	* @return $array_values
	* This function all from table
	*/
	public function selectAll($table)
	{
		$sql = "SELECT * FROM `".$table."`";
		$query = mysqli_query($this->connection, $sql);
		$rows  = array();
		$i=0;
		if(mysqli_num_rows($query) > 0) {
		    while($row = mysqli_fetch_assoc($query)) {
		        $rows[$i] = $row;
		        $i++;
		    }
		}
		return $rows;
	}

	/**
	* @param $tableName
	* @param $id
	* @return $array_value
	* this function select from table by id
	*/
	public function selectById($table,$id=1)
	{
		$sql = "SELECT * FROM `".$table."` WHERE id='$id' ;";
		$query = mysqli_query($this->connection, $sql);
		$row = mysqli_fetch_assoc($query);
		return $row;
	}

	/**
	* @param $tableName
	* @param $data in array
	* @param $where
	* @return $Boolean
	* this function update table using where conditions
	*/
	public function update($table, $data, $where='1')
	{
		$sql = "UPDATE `".$table."` SET ";
	
		foreach($data as $key=>$val)
		{
			if(strtolower($val)==' ') $q.= "`$key` = ' ', ";
			else $sql.= "`$key`='".$val."', ";
		}
	
		$sql = rtrim($sql, ', ') . ' WHERE '.$where.';';
	
		return $this->query($sql);
	}

	/**
	* @param $tableName
	* @param $id
	* @return $Boolean
	* this function delete from table by id
	*/
	public function deleteById($table,$id=1)
	{
		$sql   = "DELETE FROM `".$table."` WHERE id='$id' ;";
		$query = $this->query($sql);
		return $query;
	}

	/**
	* @param $query
	* @return $Boolean
	* this function run query
	*/
	public function query($sql)
	{
		if(mysqli_query($this->connection, $sql))
		{
		    return true;
		}else
		{
		    return false;
		}
	}

	/**
	* @param $fieldName
	* @param $folder To save
	* @return $false OR image name
	* this function upload image
	*/
	public function upload_image($field_name,$folder='images/')
	{ 
	    $types=array('jpg','jpeg','png','gif',"image/jpeg","image/png","image/gif");
	    $extension= pathinfo($_FILES[$field_name]['name'],PATHINFO_EXTENSION);
	    if($_FILES[$field_name]['error']>0)
	    {
	        return false;
	    }
	    if(!in_array(strtolower($extension),$types))
	    {
	        return false;
	    }
	    $image_name = time().'.'.$extension;
	    $path=$folder.$image_name;
	    $move=move_uploaded_file($_FILES[$field_name]['tmp_name'],$path);
	    if(!$move)
	    {
	       return false;
	    }else{
	       return $image_name;
	    }
      
	}

	/**
	* this function Close Connection To DB
	*/
	public function close()
	{
		mysqli_close();
	}
}