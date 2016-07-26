<?php
/**
* this is a controller 
* named after public/ in URL 
*/
class Home extends Controller
{
	/**
	* this a function located after public/Controller/
	*/
	public function index($name='')
	{
		$db = $this->DB();
		$employees = $db->selectAll('employee');

		//Here you can use model
		$user = $this->model('User');
		$user->name = $name;
		//here you can use view and pass data
		$this->view('home/index',  ['employees'=>$employees]);
	}

	/**
	* this a function located after public/Controller/
	* add new employee to DB
	*/
	public function add()
	{
		$db = $this->DB();
		//upload image function
		$image = $db->upload_image('image');
		$name  = filter_input(INPUT_POST,'name');;
		$email = filter_input(INPUT_POST,'email');;
		if(!$image)
		{
			$data['error'] = true;
			echo json_encode($data);
			return;
		}
		$data = [
			'name'=>$name,
			'email'=>$email,
			'image'=>$image
		];
		$add = $db->insert('employee',$data);
		if($add>0)
		{
			$data['id']     = $add;
			$data['add']    = true;
			$data['url']    = PUBLIC_PATH."images/".$image;
			$data['delete'] = PUBLIC_PATH."home/delete";
			echo json_encode($data);
		}
	}

	/**
	* this a function located after public/Controller/
	* edit employee data
	*/
	public function edit()
	{
		$db = $this->DB();
		$name  = filter_input(INPUT_POST, "name");
		$email = filter_input(INPUT_POST, "email");
		$id    = filter_input(INPUT_POST, "edit_id");
		
		$dataEdit['name']  = $name;
		$dataEdit['email'] = $email;
		//upload image function and check if there is an image
		if($_FILES['image']['error']==0)
		{
			$image = $db->upload_image('image');
			if(!$image)
			{
				$data['error'] = true;
				echo json_encode($data);
				return;
			}else{
				$dataEdit['image'] = $image;
			}
		}else{
			$employe = $db->selectById('employee',$id);
			$image   = $employe['image'];
			$dataEdit['image'] = $image;
		}
		
		$where = "id=".$id;
		$edit   = $db->update('employee',$dataEdit,$where);
		if($edit>0)
		{
			$data['id']     = $id;
			$data['name']   = $name;
			$data['email']  = $email;
			$data['edit']   = true;
			$data['url']    = PUBLIC_PATH."images/".$image;
			$data['delete'] = PUBLIC_PATH."home/delete";
			echo json_encode($data);
		}
	}

	/**
	* this a function located after public/Controller/
	* Delete employee from DB
	*/
	public function delete()
	{
		$id = filter_input(INPUT_POST, "id");
		$data['id'] = $id;
		if($id>0)
		{
			$db = $this->DB();
			$delete = $db->deleteById('employee',$id);
			$data['delete'] = $delete;
			echo json_encode($data);
		}else
		{
			$delete = false;
			$data['delete'] = $delete;
			echo json_encode($data);
		}
	}
}