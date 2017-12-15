<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->helper('file');
		$this->load->model('Model');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->library('cart');
		
	}
	
	public function index()
	{
		$this->session->set_userdata('file_name','user.png');
		$this->load->view('index');
	}
	
	public function simpan_id()
	{
		$post = $this->input->post();
		$id = $post['id'];
		$pass = $post['passw'];
		$found = $this->Model->cek_login($id);
		if ($found == null)
		{
			echo 'Username not found';
		}
		else
		{
			if ($found['password'] != $pass)
			{
				echo 'Wrong password';
			}
			else
			{
				
				$mydata = $this->Model->select_user_byusername($id);
				foreach($mydata as $row)
				{
					$myname = explode(" ",$row->name);
				}
				$this->session->set_userdata('myaccount',$myname[0]);
				$this->session->set_userdata('myusername',$id);
				echo 'success '.$myname[0];
			}
		}
		
	}
	public function login()
	{
		$input = $this->input->post();
		
		if(isset($_POST['member'])){
			
			$this->load->view('member');
		}
		if(isset($_POST['logout'])){
			$this->session->sess_destroy();
			$this->load->view('index');				
		}
		else if(isset($_POST['register'])){
			$this->load->view('register');
		}
		else
		{
			$count=0;
			//mencari user yg bukan diri sendiri
			$lookuser = $this->Model->select_user_notme($this->session->userdata('myusername'));
			//mencari user aktif yg bukan diri sendiri
			$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			$data['allposting'] = $this->Model->select_post_friend($this->session->userdata('myusername'));
			//komentar posting
			$data['percomment'] = $this->Model->select_comment();
			
			foreach($lookuser as $row)
			{
				//mencari user yg bukan teman
				$friend = $this->Model->select_user_notfriend($row->username,$this->session->userdata('myusername'));
				
				if(!$friend)
				{
					//ambil data user yang bukan teman
					$data["alluser"][$count]=$this->Model->select_user_notmyfriend($row->username);
					$count++;
				}
			}

			$this->load->view('home',$data);
		}
		
	}
	
	public function register()
	{
		$post = $this->input->post();

		if(isset($_POST['upload'])){
			$kode = $this->session->userdata("kodegbr");
			$urutan = $this->Model->get_urutan($kode) + 1;
			$config['upload_path'] = './ppicture/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['file_name'] = 'pp_'.$kode.str_pad($urutan,4,"0",STR_PAD_LEFT);
			$config['overwrite']  = true;
			$this->load->library('upload', $config);
			
			if($this->upload->do_upload('ppicture'))
			{
				$te = $this->upload->data();
				$namafile = $te["file_name"];
				$this->session->set_userdata('file_name',$namafile);
			}
			else
			{
				echo $this->upload->display_errors('<div id="output" style="opacity:0;">', '</div>');
			}
			$this->load->view("register2");
		}
		
		else if(isset($_POST['next'])){
			
			$this->form_validation->set_rules('fname','Firstname','required|max_length[15]');
			$this->form_validation->set_rules('lname','Lastname','required|max_length[15]');
			$this->form_validation->set_rules('address','Address','required|min_length[10]|max_length[50]');
			$this->form_validation->set_rules('email','Email','required|valid_email');
			$this->form_validation->set_rules('birth','Date of Birth','required');
			
			
					if($this->form_validation->run())
					{
						$sessiondata = array(
							"kodegbr" => strtoupper(substr($post['fname'],0,1).substr($post['lname'],0,1)),
							"fname" => $post['fname'],
							"name" => $post['fname']." ".$post['lname'],
							"email" => $post['email'],
							"birth" => $post['birth'],
							"alamat" => $post['address'],
							"gender" => $post['gender']
						);
						$this->session->set_userdata($sessiondata);
						$this->load->view('register2');
					
						
						
					}
					else
					{
						echo validation_errors('<div id="output" style="opacity:0;">', '</div>');
						$this->load->view('register');
					}
		
			
		}
		else if(isset($_POST['register'])){
			//cek apakah username ada
			$data['data-user'] = $this->Model->select_user_byusername($post['user']);
			
			$this->form_validation->set_rules('user','Username','required|min_length[8]|max_length[8]');
			$this->form_validation->set_rules('pass','Password','required|min_length[8]|max_length[12]|alpha_numeric');
			$this->form_validation->set_rules('forgotpass','Forgot','required|min_length[8]|max_length[30]');

			
			if($data['data-user'] == null && $this->form_validation->run())
			{
				if($post['pass'] == $post['cpass'])
				{
					$user = $post['user'];
					$pass = $post['pass'];
					$forgot = $post['forgotpass'];
					$name = $this->session->userdata("name");
					$email = $this->session->userdata("email");
					$birth = $this->session->userdata("birth");
					$alamat = $this->session->userdata("alamat");
					$gender = $this->session->userdata("gender");
					$pp = $this->session->userdata("file_name");
					$this->Model->insert_user($user,$pass,$forgot,$name,$email,$birth,$alamat,$gender,$pp);
					$this->session->sess_destroy();
					echo "<script type='text/javascript'>";
					echo "alert('Register Success')";
					echo "</script>";
					$this->load->view('index');
				}
				else
				{
					//kalau salah pass dan confirm
					echo '<div id="output" style="opacity:0;">Wrong confirm password</div>';
					$this->load->view('register2');
				}
			}
			else if($data['data-user'])
			{
				//kalau ada
				echo '<div id="output" style="opacity:0;">Username already used</div>';
				$this->load->view('register2');
			}
			else
			{
				//error lainnya
				echo validation_errors('<div id="output" style="opacity:0;">', '</div>');
				$this->load->view('register2');
			}
		}
		else if(isset($_POST['back_reg']))
		{
			$this->session->set_userdata('file_name','user.png');
			$this->load->view('register');
		}
		else {
			$this->load->view('index');
		}
	}
	
	public function member()
	{	
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			
		$count=0;
		$data["alluser"]=null;
			//mencari user yg bukan diri sendiri
			$lookuser = $this->Model->select_user_notme($this->session->userdata('myusername'));
			
			foreach($lookuser as $row)
			{
				//mencari user yg bukan teman
				$friend = $this->Model->select_user_notfriend($row->username,$this->session->userdata('myusername'));
				
				if(!$friend)
				{
					//ambil data user yang bukan teman
					$data["alluser"][$count]=$this->Model->select_user_notmyfriend($row->username);
					$count++;
				}
			}

		$counter=0;
		$data["friend"]=null;
		$frienddata = $this->Model->select_user_notme($this->session->userdata('myusername'));
		foreach ($frienddata as $row)
		{
			$lookfriend = $this->Model->select_friend($row->username,$this->session->userdata('myusername'));
			if($lookfriend)
			{
				$data["friend"][$counter] = $this->Model->select_user_myfriend($row->username);
				$counter++;
			}
		}
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		
		$this->load->view('members',$data);
	}
	
	public function profile()
	{
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			
		$data["mydata"] = $this->Model->select_user_byusername($this->session->userdata('myusername'));
		$this->load->view('profile',$data);
	}
	
	public function otherprofile()
	{
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			
		//ajax ambil id
		$post = $this->input->post();
		
		$this->session->set_userdata('userfriend',$post["friend"]);
	}
	public function goto_otherprofile()
	{
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			
		//menuju ke other profile
		$data['otherprofile'] = $this->Model->get_profile_friend($this->session->userdata('userfriend'));
		
		//cek user sdh friend apa belum
		$data["isfriend"] = 'yes';
		if(!$this->Model->select_friend_avail($this->session->userdata('myusername'),$this->session->userdata('userfriend')))
		{
			$data["isfriend"] = 'no'; 
		}
		if($this->Model->select_request($this->session->userdata('myusername'),$this->session->userdata('userfriend')))
		{
			$data["isfriend"] = 'req'; 
		}

		$this->load->view('profile2',$data);
	}
	
	public function add_request()
	{
		//add friend
		$post = $this->input->post();
		$this->Model->request_friend($this->session->userdata('myusername'),$post["friend"]);
		//$this->Model->add_friend($this->session->userdata('myusername'),$post["friend"]);
	}
	public function cancel_request()
	{
		$post = $this->input->post();
		echo $this->Model->cancel_request($this->session->userdata('myusername'),$post["friend"]);
		//$this->Model->add_friend($this->session->userdata('myusername'),$post["friend"]);
	}
	
	public function cancel_request_friend()
	{
		$post = $this->input->post();
		echo $this->Model->cancel_request($post["friend"],$this->session->userdata('myusername'));
		//$this->Model->add_friend($this->session->userdata('myusername'),$post["friend"]);
	}
	
	public function add_friend()
	{
		$post = $this->input->post();
		$this->Model->add_friend($this->session->userdata('myusername'),$post["friend"]);
	}
	
	public function del_friend()
	{
		$post = $this->input->post();
		echo $this->Model->del_friend($this->session->userdata('myusername'),$post["friend"]);
	}
	
	public function group()
	{
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			
		$post = $this->input->post();
		$data['group'] = $this->Model->select_group_byusername($this->session->userdata('myusername'));
		$data['group_member'] = $this->Model->select_group_member_byusername($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		$this->load->view('groups',$data);
	}
	
	public function add_group()
	{
		$post = $this->input->post();
		$this->Model->add_friend_group($this->session->userdata('myusername'),$post["group"]);
	}
	
	public function cancel_group()
	{
		$post = $this->input->post();
		echo $this->Model->cancel_request_group($post["group"],$this->session->userdata('myusername'));
		//$this->Model->add_friend($this->session->userdata('myusername'),$post["friend"]);
	}
	
	public function exit_group_member()
	{
		$post = $this->input->post();
		echo $this->Model->exit_group_member($post["group"],$this->session->userdata('myusername'));
		//$this->Model->add_friend($this->session->userdata('myusername'),$post["friend"]);
	}
	
	public function exit_group_admin()
	{
		$post = $this->input->post();
		echo $this->Model->exit_group_admin($post["group"],$this->session->userdata('myusername'));
		//$this->Model->add_friend($this->session->userdata('myusername'),$post["friend"]);
	}
	
	public function group_data()
	{
		//ajax ambil id
		$post = $this->input->post();
		$this->session->set_userdata('usergroup',$post["group"]);
	}
	
	public function goto_group()
	{
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			
		//mencari data grup
		$data['groupmembers'] = $this->Model->select_all_group_members($this->session->userdata('usergroup'));
		$data['othergroup'] = $this->Model->get_profile_group($this->session->userdata('usergroup'));
		$counter = 0;
		$data['myfriends'] = null;
		$frienddata = $this->Model->select_user_notme($this->session->userdata('myusername'));
		foreach ($frienddata as $row)
		{
			$lookfriend = $this->Model->select_friend($row->username,$this->session->userdata('myusername'));
			$lookgroup = $this->Model->select_group($this->session->userdata('usergroup'),$row->username);
			if($lookfriend && !$lookgroup)
			{
				$data['myfriends'][$counter] = $this->Model->select_user_myfriend($row->username);
				$counter++;
			}
		}
		$this->load->view('group_profile',$data);
	}
	
	public function goto_group_member()
	{
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			
		//mencari data grup
		$data['groupmembers'] = $this->Model->select_all_group_members($this->session->userdata('usergroup'));
		$data['othergroup'] = $this->Model->get_profile_group($this->session->userdata('usergroup'));
		$this->load->view('group_profile2',$data);
	}
	
	public function group_maker()
	{	//masuk untuk membuat grup
		$this->session->set_userdata('temp_file',null);
		$this->session->set_userdata('file_name_group','group.jpg');
		$this->load->view('group_make');
	}
	
	public function group_profile()
	{	//membuat grup
		$post = $this->input->post();
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
		$data['group'] = $this->Model->select_group_byusername($this->session->userdata('myusername'));
		$data['group_member'] = $this->Model->select_group_member_byusername($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		
		if(isset($_POST['upload'])){
			$data['data-group'] = $this->Model->select_group_byidgroup($post['gname'][0].$post['gname'][1].$post['gname'][2].$post['gname'][3]);
			$id_group = $post['gname'][0].$post['gname'][1].$post['gname'][2].$post['gname'][3].str_pad(count($data['data-group'])+1,4,"0",STR_PAD_LEFT);	
			$config['upload_path'] = './gpicture/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['overwrite']  = true;
			$config['file_name'] = $id_group;
			$this->load->library('upload', $config);
			
			if($this->upload->do_upload('gpicture'))
			{
				$te = $this->upload->data();
				$namafile = $te["file_name"];
				$this->session->set_userdata('file_name_group',$namafile);
				$this->session->set_userdata('temp_file',$post['gname']);
				$this->session->set_userdata('id_group',$id_group);
			}
			else
			{
				echo $this->upload->display_errors('<div id="output" style="opacity:0;">', '</div>');
			}

			$this->load->view("group_make");
		}
		if(isset($_POST['make'])){
			$this->form_validation->set_rules('gname','Group Name','required');

			if($this->form_validation->run())
			{
				$this->Model->insert_group($this->session->userdata('id_group'),$post['gname'],$this->session->userdata('myusername'),$this->session->userdata('file_name_group'),$post['caption']);
			}
			$this->session->unset_userdata('temp_file');
			echo "<script type='text/javascript'>";
			echo "alert('Group has been established')";
			echo "</script>";
			redirect('Welcome/group');
			//$this->load->view('groups',$data);
		}
		if(isset($_POST['back'])){
			$this->session->unset_userdata('temp_file');


			echo "<script type='text/javascript'>";
			echo "alert('Cancel')";
			echo "</script>";


			$this->load->view('groups',$data);
		}


	}

	public function group_manage()
	{ //mengelola grup
		$post = $this->input->post();
		if (isset($_POST['confirmgroupreq']))
		{
			$idgroup = $post['idgroup'];
			$allreq = $this->session->userdata("temprequest");
			foreach($allreq as $row)
			{
				$this->Model->insert_request_group($idgroup,$row);
			}
			$this->session->unset_userdata("temprequest");
			$this->session->set_flashdata("scsmsg","Your request has been sent.");
			redirect("Welcome/goto_group");
		}
		else if (isset($_POST['savechanges']))
		{
			$idgroup = $post['idgroup'];
			$groupname = $post['editgroupname'];
			$caption = $post['editdescription'];
			$config['upload_path'] = './gpicture/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['overwrite']  = true;
			$config['file_name'] = $idgroup;
			$this->load->library('upload', $config);
			if($this->upload->do_upload('editgrouppicture'))
			{
				$te = $this->upload->data();
				$groupimage = $te["file_name"];
				$this->Model->update_group($idgroup,$groupname,$groupimage,$caption);
				$this->session->set_flashdata("scsmsg","Your changes has been saved successfully.");
			}
			else
			{
				$this->session->set_flashdata("errmsg",$this->upload->display_errors());
			}
			redirect("Welcome/goto_group");
		}
		
		
	}
	
	public function login_page()
	{
		$count=0;
		$countfriend=0;
		$data["alluser"]=null;
		//komentar posting
		$data['percomment'] = $this->Model->select_comment();
	
		
			//mencari user yg bukan diri sendiri
			$lookuser = $this->Model->select_user_notme($this->session->userdata('myusername'));
			$data['allposting'] = $this->Model->select_post_friend($this->session->userdata('myusername'));
			
			$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			foreach($lookuser as $row)
			{
				//mencari user yg bukan teman
				$friend = $this->Model->select_user_notfriend($row->username,$this->session->userdata('myusername'));
				
				if(!$friend)
				{
					//ambil data user yang bukan teman
					$data["alluser"][$count]=$this->Model->select_user_notmyfriend($row->username);
					$count++;
				}
			}

		$this->load->view('home',$data);
	}
	
	public function chat(){
		//tulis ke file untuk chatnya
		$post = $this->input->post();
		$username = $this->session->userdata('myusername');
		$format = substr($post['friend'],25,count($post['friend'])-7);// dipotong karena mengandung spasi dan karakter lainnya
		
		//mencari id yang dichat
		$chatter = $this->Model->get_idchatter($format);
		foreach($chatter as $row){ 
			//digabung untuk mencari id sender dan receiver
			$idgabungan = $username."_".$row->username;
		}
		//Masukan chatnya
		$this->Model->insert_chat($idgabungan,$idgabungan,$post['simpanchat'],$username);

		//tampilkan chat kembali
		$ourchat = $this->Model->get_chatter($username,$format);
		
		foreach($ourchat as $row){ 

			if($row->id_chater == $username)//penanda user sapa yang chat
			{
				echo '<br><div class="text-left mychat col-sm-11">'.$row->chat.'<div style="font-size:8pt;">at '.$row->date.'</div></div>';
			}
			else
			{
				echo '<br><div class="text-left yourchat col-sm-11">'.$row->chat.'<div style="font-size:8pt;">at '.$row->date.'</div></div>';
			}
		}	
	}
	
	public function see_mychat_realtime(){
		$post = $this->input->post();
		$username = $this->session->userdata('myusername');
		$format = substr($post['friend'],25,count($post['friend'])-7);// dipotong karena mengandung spasi dan karakter lainnya
		
		$ourchat = $this->Model->get_chatter($username,$format);
		foreach($ourchat as $row){ 
			if($row->id_chater == $username)//penanda user sapa yang chat
			{
				echo '<br><div class="text-left mychat col-sm-11">'.$row->chat.'<div style="font-size:8pt;">at '.$row->date.'</div></div>';
			}
			else
			{
				echo '<br><div class="text-left yourchat col-sm-11">'.$row->chat.'<div style="font-size:8pt;">at '.$row->date.'</div></div>';
			}
		}		
	}
	
	public function see_mychat(){
		//baca file untuk chatnya
		$post = $this->input->post();
		$username = $this->session->userdata('myusername');
		$format = substr($post['friend'],5,count($post['friend'])-4);// dipotong karena mengandung spasi dan karakter lainnya
		//$cari = strpos($post['friend'],'o');
		$ourchat = $this->Model->get_chatter($username,$format);
		foreach($ourchat as $row){ 
			if($row->id_chater == $username)//penanda user sapa yang chat
			{
				echo '<br><div class="text-left mychat col-sm-11">'.$row->chat.'<div style="font-size:8pt;">at '.$row->date.'</div></div>';
			}
			else
			{
				echo '<br><div class="text-left yourchat col-sm-11">'.$row->chat.'<div style="font-size:8pt;">at '.$row->date.'</div></div>';
			}
		}		
	}
	public function select_group_request()
	{
		$post = $this->input->post();
		$requestid = $post['reqid'];
		$arr = array();
		if ($this->session->has_userdata("temprequest"))
		{
			$arr = $this->session->userdata("temprequest");
		}
		array_push($arr,$requestid);
		$this->session->set_userdata("temprequest",$arr);
	}


	public function deselect_group_request()
	{
		$post = $this->input->post();
		$requestid = $post['reqid'];
		if ($this->session->has_userdata("temprequest"))
		{
			$arr = $this->session->userdata("temprequest");
		}
		$idx = array_search($requestid,$arr);
		unset($arr[$idx]);
		$this->session->set_userdata("temprequest",$arr);
	}
	
	public function add_comment()
	{
		$post = $this->input->post();
		$this->Model->insert_comment($post["simpankomen"],$this->session->userdata('myusername'),$post["simpanpost"],$post["simpanreply"]);		
	}
	
	public function goto_mention($usermention)
	{
		$this->session->set_userdata("userfriend",$usermention);
		redirect("Welcome/goto_otherprofile");
	}
	
	public function home()
	{
		
		$post = $this->input->post();
		if(isset($_POST['postBTN'])){
				$postingan = $post['comment'];
				preg_match_all('/(#\w+)/', $postingan, $results);
				foreach ($results[0] as $hashtag)
				{
					$postingan = str_replace($hashtag,"<a href='search_hashtag/".trim($hashtag,"#")."'>".$hashtag."</a>",$postingan);
				}
				preg_match_all('/(@\w+)/', $postingan, $results);
				foreach ($results[0] as $mention)
				{
					$allfriends = $this->Model->select_userfriend_notme($this->session->userdata("myusername"));
					if ($allfriends != null)
					{	
						foreach ($allfriends as $row)
						{	
							if ($row->username == trim($mention,"@"))
							{
								$postingan = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$postingan);
							}
						}
					}
				}
		        $config['upload_path']          = './posts/';
                $config['allowed_types']        = 'jpeg|jpg|png|mp4|mkv|avi|wmv';
                $this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('openVideo'))
				{		
					$this->session->set_flashdata("error",$this->upload->display_errors());
					if ($post['comment'] != "")
					{
						$this->Model->insert_post($this->session->userdata('myusername'),$postingan,0,1,1);
					}
					redirect('Welcome/login_page');
				}
				else
				{
					$te = $this->upload->data();
					$namafile = $te["file_name"];
					$this->Model->insert_post($this->session->userdata('myusername'),$postingan,$namafile,1,1);
					redirect('Welcome/login_page');
				}
						
		        if ( ! $this->upload->do_upload('openImage'))
                {		
					$this->session->set_flashdata("error",$this->upload->display_errors());
					if ($post['comment'] != "")
					{
						$this->Model->insert_post($this->session->userdata('myusername'),$postingan,0,1,1);
					}
					redirect('Welcome/login_page');	
                }
                else
                {
					$te = $this->upload->data();
					$namafile = $te["file_name"];
					$this->Model->insert_post($this->session->userdata('myusername'),$postingan,$namafile,1,1);

					redirect('Welcome/login_page');
                }
				
				
		}
		else if(isset($_POST['logout'])){
			$this->load->view('index');
		}
		else {
			$this->load->view('index');
		}
	}
}
