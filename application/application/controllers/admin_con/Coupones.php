<?php

class Coupones extends CI_Controller
{

	//-define everything for here
	protected $arr_values = array(
						   	'page_title'=>'Coupone',
						   	'table_name'=>'coupones',
						   	'upload_path'=>'media/uploads/coupones/',
						   	'load_path'=>'admin/coupones/',
						   	'redirect_path'=>'admin_con/coupones/',
						   	'back_url'=>'coupones',
						   	'add_url'=>'coupones',
						   	'edit_url'=>'coupones',
						   	'delete_url'=>'coupones',
						   	'view_url'=>'coupones',
						   	'status_value'=>'coupones',
						   	'multiple_delete'=>'admin_con/coupones/delete_all',
						   ); 


   //--check user login or not
	function chech_admin_login()
	{
		$ci = & get_instance();
	    if(empty($ci->session->userdata('id')))
	    {
	      redirect(base_url().'admin');
	    }
	}



	//insert

	function add()
	{
		$this->chech_admin_login();
		if(isset($_POST['submit']))
		{
			date_default_timezone_set('Asia/Kolkata');

			$data['name'] = $name = $this->input->post('name');			
			$data['amount'] = $amount = $this->input->post('amount');			
			$data['type'] = $type = $this->input->post('type');	

			$p_id = $this->input->post('p_id');

			$all_p_id = array();

			foreach($p_id as $key => $value)
			{
				$all_p_id[] = array(
					"p_id"=>$p_id[$key],
					"name"=>$name,
					"amount"=>$amount,
					"type"=>$type,
				);
			}
			$get_all_p_id = json_encode($all_p_id);
			$data['p_id'] = $get_all_p_id;

			$data['status'] = $this->input->post('status');			
			$data['addeddate'] = date('y-m-d h:i:sA');

			$this->crud->insert($this->arr_values['table_name'],$data);

			$insert_id = $this->db->insert_id();			
			$this->db->delete("coupen_product_wise",array("coupon_id"=>$insert_id,));
			
			if(!empty($all_p_id))
			{
				foreach($all_p_id as $key2 => $value_coupen)
				{
					$coupen_data = array(
						"coupon_id"=>$insert_id,
						"p_id"=>$p_id[$key2],
						"name"=>$value_coupen['name'],
						"amount"=>$value_coupen['amount'],
						"type"=>$value_coupen['type'],
					);
					$this->db->insert('coupen_product_wise',$coupen_data);
				}
			}

			$this->session->set_flashdata('message','<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><div class="bg-blue rounded w-25px h-25px d-flex align-items-center justify-content-center text-white"><i class="fa fa-bell"></i></div><strong class="me-auto ms-2">Message</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body" style="color:black;">Record has been Successfully Saved..</div></div>');			
			redirect($this->arr_values['redirect_path'].'listing');	
		}
		$data['page_title'] = $this->arr_values['page_title'];
		$data['back_url'] = base_url('admin_con/'.$this->arr_values['back_url'].'/listing');
		$this->load->view($this->arr_values['load_path'].'add',$data);
	}


	//----list dekhney ke lia 

	function listing()
	{
		$this->chech_admin_login();
		$this->db->order_by("id desc");
		$data['ALLDATA'] = $this->crud->get_data($this->arr_values['table_name']);

		$data['page_title'] = $this->arr_values['page_title'];
		$data['back_url'] = base_url('admin_con/'.$this->arr_values['back_url'].'/listing');
		$data['add_url'] = base_url('admin_con/'.$this->arr_values['add_url'].'/add');
		$data['edit_url'] = base_url('admin_con/'.$this->arr_values['edit_url'].'/edit/');
		$data['delete_url'] = base_url('admin_con/'.$this->arr_values['delete_url'].'/delete/');
		$data['view_url'] = base_url('admin_con/'.$this->arr_values['view_url'].'/view/');
		$data['status_value'] = base_url('admin_con/'.$this->arr_values['status_value'].'/new_status');
		$data['upload_path'] = $this->arr_values['upload_path'];
		$data['multiple_delete'] = base_url($this->arr_values['multiple_delete']);
		
		$this->load->view($this->arr_values['load_path'].'list',$data);
	}


	//------------delete 

	public function delete($id)
	  {
	    $delete=$this->crud->delete($this->arr_values['table_name'],$id);
	    if($delete)
        {
          echo "Success";
        }
        else
        {
          echo "Error";
        }
	  }

	  /*-------delete multiple*/
	  function delete_all()
		{
			$ids = $this->input->post('id');
		    if (!empty($ids)) {
		        $this->db->where_in('id', $ids);
		        $this->db->delete($this->arr_values['table_name']);
		    }
		}


	//----------------edit

	function edit()
	{
		$this->chech_admin_login();
		$args=func_get_args();

		if(isset($_POST['submit']))
		{
			date_default_timezone_set('Asia/Kolkata');

			$data['name'] = $name = $this->input->post('name');			
			$data['amount'] = $amount = $this->input->post('amount');			
			$data['type'] = $type = $this->input->post('type');		

			$p_id = $this->input->post('p_id');

			$all_p_id = array();

			foreach($p_id as $key => $value)
			{
				$all_p_id[] = array(
					"p_id"=>$p_id[$key],
					"name"=>$name,
					"amount"=>$amount,
					"type"=>$type,
				);
			}
			$get_all_p_id = json_encode($all_p_id);
			$data['p_id'] = $get_all_p_id;
		
			$data['status'] = $this->input->post('status');						
			$data['modifieddate'] = date('y-m-d h:i:sA');

			$this->crud->update($this->arr_values['table_name'],$args[0],$data);

			$insert_id = $args[0];			
			$this->db->delete("coupen_product_wise",array("coupon_id"=>$insert_id,));
			
			if(!empty($all_p_id))
			{
				foreach($all_p_id as $key2 => $value_coupen)
				{
					$coupen_data = array(
						"coupon_id"=>$insert_id,
						"p_id"=>$p_id[$key2],
						"name"=>$value_coupen['name'],
						"amount"=>$value_coupen['amount'],
						"type"=>$value_coupen['type'],
					);
					$this->db->insert('coupen_product_wise',$coupen_data);
				}
			}


			$this->session->set_flashdata('message','<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><div class="bg-blue rounded w-25px h-25px d-flex align-items-center justify-content-center text-white"><i class="fa fa-bell"></i></div><strong class="me-auto ms-2">Message</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body" style="color:black;">Record has been successfully Updated..</div></div>');
		    redirect($this->arr_values['redirect_path'].'listing');	
		}
		$data['page_title'] = $this->arr_values['page_title'];
		$data['upload_path'] = $this->arr_values['upload_path'];
		$data['back_url'] = base_url('admin_con/'.$this->arr_values['back_url'].'/listing');
		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],$this->arr_values['table_name']);
		$this->load->view($this->arr_values['load_path'].'edit',$data);
	}





	//----------------view

	function view()
	{
		$this->chech_admin_login();
		$args=func_get_args();
		$data['page_title'] = $this->arr_values['page_title'];
		$data['upload_path'] = $this->arr_values['upload_path'];
		$data['back_url'] = base_url('admin_con/'.$this->arr_values['back_url'].'/listing');
		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],$this->arr_values['table_name']);
		$this->load->view($this->arr_values['load_path'].'view',$data);
	}


//---------------------status

	public function status_change()
	{
		if(isset($_POST['submit']))
		{						
			$id = $this->input->post('id');						
			$data['status'] = $this->input->post('status');		
			$this->db->update($this->arr_values['table_name'],$data,array("id"=>$id));
			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully Updated.</div>');
		    redirect($this->arr_values['redirect_path'].'listing');	
		}

	}



	public function new_status()
	{
		$status = $this->input->post('status');
		$id = $this->input->post('id');
		$this->db->update($this->arr_values['table_name'],array('status'=>$status),array('id'=>$id));
		$status_html = status($status);
		$data['data'] = array("status"=>$status_html,);
		echo json_encode($data);
	}



	// public function statusupdate()
	// {	
	// 	//echo "string";
	// 	$data['status'] = $_GET['l_status'];
	// 	$this->crud->update($this->arr_values['table_name'],$_GET['ld'],$data);
	// 	redirect($this->arr_values['redirect_path'].'listing');	
	// }



}