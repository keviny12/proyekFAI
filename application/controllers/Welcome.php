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
		$this->load->library('email');
		
	}
	
	public function index()
	{
		if($this->session->userdata('myusername'))
		{
			redirect('Welcome/Login_page');
		}
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
				if ($found['active'] == 0)
				{
					echo 'Your account has been banned';
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
		
	}
	public function admin(){
		$post = $this->input->post();
		$this->Model->active_on($post['user']);
		redirect("Welcome/Login_page");
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
		else if($input['user'] == 'admin' && $input['pass'] == 'admin'){
			$this->load->view("admin");
		}
		else
		{
			redirect("Welcome/Login_page");
		}
		
	}

	public function search_hashtag($hashtag)
	{
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		
		$data['percomment'] = $this->Model->select_comment();
		$data['peremo'] = $this->Model->select_emo();
		$data['resultfriend'] = null;
		$data['textfriends'] = "Sorry, we couldn't find users with the keyword #".$hashtag;
		//mencari user yg bukan diri sendiri
		$lookuser = $this->Model->select_user_notme($this->session->userdata('myusername'));
		//mencari user aktif yg bukan diri sendiri
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
		$data['allposting'] = $this->Model->select_post_hashtag($hashtag);
		if ($data['allposting'] == null)
		{
			$data["textresults"] = "Sorry, we couldn't find posts with the keyword #".$hashtag;	
		}
		else
		{
			$tagcount = $this->Model->count_hashtags($hashtag);
			$data["textresults"] = "Showing ".$tagcount." posts with the keyword #".$hashtag. " : ";
		}
		//komentar posting
		$data['percomment'] = $this->Model->select_comment();
		$this->load->view('explore',$data);
	}
	
	
	public function delete_post()
	{
		$post = $this->input->post();
		$this->Model->delete_mypost($post['idpost']);
	}
	
	public function edit_post()
	{
		$post = $this->input->post();
		
				$config['upload_path']  = './posts/';
                $config['allowed_types']  = 'jpeg|jpg|png|mp4|mkv|avi|wmv|mov';
                $config['file_name']  = $_POST['idpost'];
                $config['overwrite']  = true;
                $this->load->library('upload',$config);
			
				$text = $post['edittext'];
				if($post['edittext'] == null)
				{
					$text = $post['textpast'];
				}
				
		        if ( ! $this->upload->do_upload('openImage'))
                {		
					if ( ! $this->upload->do_upload('openVideo'))
					{		
						$this->Model->edit_mypost($_POST['idpost'],$text,$post['postimg']);
						redirect("Welcome/profile");
					}
					else
					{
						$te = $this->upload->data();
						$namafile = $te["file_name"];
						$this->Model->edit_mypost($_POST['idpost'],$text,$namafile);
						redirect("Welcome/profile");
					}
                }
                else
                {
					$te = $this->upload->data();
					$namafile = $te["file_name"];
					$this->Model->edit_mypost($_POST['idpost'],$text,$namafile);
					redirect("Welcome/profile");
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
				$this->session->set_userdata("kodegbr","user.png");
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
		else if(isset($_POST['email_ver'])){

		//$id = $this->user->getId('yoelvndr');
		$this->load->library('email');
		$config['sendgrid'] = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.sendgrid.net',
			'smtp_user' => 'apikey',
			'smtp_pass' => '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',
			'smtp_port' => 465,
			'crlf' => "\r\n",
			'mailtype' => 'html',
			'newline' => "\r\n"
		);
		$this->email->initialize($config['sendgrid']);

		$subject = 'Verify Email';
		$message = '<a href='.site_url('Cont/acceptverify/').'>Click Here To Verify</a>';

		// Get full html:
		$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
			<title>' . html_escape($subject) . '</title>
			<style type="text/css">
				body {
					font-family: Arial, Verdana, Helvetica, sans-serif;
					font-size: 16px;
				}
			</style>
		</head>
		<body>
		' . $message . '
		</body>
		</html>';
		//$email=$this->user->getEmail('yoelvndr');
		$this->email->from('no-reply@mail.josefchristian.me', 'Encekbook Social Media');
		$this->email->to('yoelisnotyul@gmail.com');
		$this->email->subject($subject);

		$this->email->message($body);
		$this->email->send(false);

		//Pengecekan Terkirim (saat mengirim harus menambahkan paramenter false saat memanggil send)
		//echo $this->email->print_debugger();
		
				
				
					
			$this->Model->insert_user(
				$this->session->userdata("username"),
				$this->session->userdata("password"),
				$this->session->userdata("forgot"),
				$this->session->userdata("name"),
				$this->session->userdata("email"),
				$this->session->userdata("birth"),
				$this->session->userdata("alamat"),
				$this->session->userdata("gender"),
				$this->session->userdata("kodegbr")	
			);
			
			$this->session->sess_destroy();
			echo "<script type='text/javascript'>";
			echo "alert('Register Success')";
			echo "</script>";
			
			$this->load->view('index');
			
		}
		else if(isset($_POST['register'])){
			//cek apakah username ada
			$data['data-user'] = $this->Model->select_user_byusername($post['user']);
			
			$this->form_validation->set_rules('user','Username','required|min_length[8]|max_length[8]');
			$this->form_validation->set_rules('pass','Pas0sword','required|min_length[8]|max_length[12]|alpha_numeric');
			$this->form_validation->set_rules('forgotpass','Forgot','required|min_length[8]|max_length[30]');

			
			if($data['data-user'] == null && $this->form_validation->run())
			{
				if($post['pass'] == $post['cpass'])
				{

					$this->session->set_userdata("username",$post['user']);
					$this->session->set_userdata("password",$post['pass']);
					$this->session->set_userdata("forgot",$post['forgotpass']);

					//$this->Model->insert_user($user,$pass,$forgot,$name,$email,$birth,$alamat,$gender,$pp);
					//$this->session->sess_destroy();
					//echo "<script type='text/javascript'>";
					//echo "alert('Register Success')";
					//echo "</script>";
					$this->load->view('register3');
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
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		
		$this->load->view('members',$data);
	}
	
	public function profile()
	{
		$data['percomment'] = $this->Model->select_comment();
		$data['peremo'] = $this->Model->select_emo();
		$data["friend"]=null;
		$counter=0;
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
		
			//mencari user yg bukan diri sendiri
		$data['allposting'] = $this->Model->select_mypost_friend($this->session->userdata('myusername'));
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		$hashtaglist = array();
		$mentionuserlist = array();
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
			
		$data["mydata"] = $this->Model->select_user_byusername($this->session->userdata('myusername'));
		
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
					$ownmention = $this->session->userdata("myusername");
					if (trim($mention,"@") == $ownmention)
					{
						$postingan = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$postingan);
						array_push($hashtaglist,$hashtag);
					}
					else
					{
						$allfriends = $this->Model->select_userfriend_notme($this->session->userdata("myusername"));
						if ($allfriends != null)
						{	
							foreach ($allfriends as $row)
							{	
								if ($row->username == trim($mention,"@"))
								{
									$postingan = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$postingan);
									array_push($mentionuserlist, trim($mention,"@"));
								}
							}
						}
					}
				}
		        $config['upload_path']          = './posts/';
                $config['allowed_types']        = 'jpeg|jpg|png|mp4|mkv|avi|wmv|mov';
                $this->load->library('upload', $config);
			
						
		        if ( ! $this->upload->do_upload('openImage'))
                {		
					if ( ! $this->upload->do_upload('openVideo'))
					{		
						if ($post['comment'] != "")
						{
							$this->Model->insert_post($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,0,1,1);
						}
					}
					else
					{
						$te = $this->upload->data();
						$namafile = $te["file_name"];
						$this->Model->insert_post($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,$namafile,1,1);
					}
                }
                else
                {
					$te = $this->upload->data();
					$namafile = $te["file_name"];
					$this->Model->insert_post($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,$namafile,1,1);
                }
		}
		
		else if (isset($_POST['savechanges']))
		{
			$username = $post['username'];
			$firstname = $post['editfirstname'];
			$lastname = $post['editlastname'];
			$birth = $post['editbirth'];
			$address = $post['editaddress'];
			$email = $post['editemail'];
			$gender = $post['editgender'];
			$kode = strtoupper(substr($firstname,0,1).substr($lastname,0,1));
			$urutan = $this->Model->get_urutan($kode) + 1;
			$config['upload_path'] = './ppicture/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['overwrite']  = true;
			$config['file_name'] = 'pp_'.$kode.str_pad($urutan,4,"0",STR_PAD_LEFT);
			$this->load->library('upload', $config);
			if($this->upload->do_upload('editprofilepicture'))
			{
				$te = $this->upload->data();
				$pp = $te["file_name"];
				$this->Model->update_user($username,$firstname." ".$lastname,$email,$birth,$address,$gender,$pp);
				$this->session->set_flashdata("scsmsg","Your changes has been saved successfully.");
			}
			else
			{
				$this->Model->update_user($username,$firstname." ".$lastname,$email,$birth,$address,$gender,$post['gbr_group']);
			}
			redirect("Welcome/profile");
		}
				
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
		$post = $this->input->post();
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		
		$data["friend"]=null;
		$counter=0;
		$frienddata = $this->Model->select_user_notme($this->session->userdata('userfriend'));
		foreach ($frienddata as $row)
		{
			$lookfriend = $this->Model->select_friend($row->username,$this->session->userdata('userfriend'));
			if($lookfriend)
			{
				$data["friend"][$counter] = $this->Model->select_user_myfriend($row->username);
				$counter++;
			}
		}	
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
		
		$data['percomment'] = $this->Model->select_comment();
		$data['peremo'] = $this->Model->select_emo();
		$data['allposting'] = $this->Model->select_mypost_friend($this->session->userdata('userfriend'));

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
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		
		$this->load->view('groups',$data);
	}
	
	public function add_group()
	{
		$post = $this->input->post();
		$admin = $this->Model->get_group_admin($post["group"]);
		$this->Model->add_friend_group($admin->id_admin,$this->session->userdata('myusername'),$post["group"]);
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
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		$data['group_permission_user'] = $this->Model->select_group_permission_show($this->session->userdata('usergroup'));
		$data['percomment'] = $this->Model->select_comment();
		$data['peremo'] = $this->Model->select_emo();
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
		$data['allposting'] = $this->Model->select_post_group($this->session->userdata('usergroup'));
		$this->load->view('group_profile',$data);
	}

	
	public function goto_group_member()
	{
		$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		$data['allposting'] = $this->Model->select_post_group($this->session->userdata('usergroup'));
		$data['percomment'] = $this->Model->select_comment();
		$data['peremo'] = $this->Model->select_emo();
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
	
	public function cancel_report(){
		$post = $this->input->post();
		$this->Model->delete_report($post['simpanreport']);
		redirect('Welcome/Login_page');
	}
	
	public function delete_post_report(){
		$post = $this->input->post();
		$this->Model->delete_report_post($post['simpanreport'],$post['simpanpost']);
		redirect('Welcome/Login_page');
	}
	
	public function banned_user(){
		$post = $this->input->post();
		$this->Model->user_banned($post['simpanreport'],$post['simpanuser']);
		redirect('Welcome/Login_page');
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
			$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
			$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
			

			echo "<script type='text/javascript'>";
			echo "alert('Cancel')";
			echo "</script>";


			$this->load->view('groups',$data);
		}


	}
	public function group_post_member(){
		$post = $this->input->post();
		$hashtaglist = array();
		$mentionuserlist = array();
		if(isset($_POST['postBTN'])){
				$postingan = $post['comment'];
				preg_match_all('/(#\w+)/', $postingan, $results);
				foreach ($results[0] as $hashtag)
				{
					$postingan = str_replace($hashtag,"<a href='search_hashtag/".trim($hashtag,"#")."'>".$hashtag."</a>",$postingan);
					array_push($hashtaglist,$hashtag);
				}
				preg_match_all('/(@\w+)/', $postingan, $results);
				foreach ($results[0] as $mention)
				{
					$ownmention = $this->session->userdata("myusername");
					if (trim($mention,"@") == $ownmention)
					{
						$postingan = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$postingan);
					}
					else
					{
						$allfriends = $this->Model->select_userfriend_notme($this->session->userdata("myusername"));
						if ($allfriends != null)
						{	
							foreach ($allfriends as $row)
							{	
								if ($row->username == trim($mention,"@"))
								{
									$postingan = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$postingan);
									array_push($mentionuserlist, trim($mention,"@"));
								}
							}
						}
					}
				}
		        $config['upload_path']          = './posts/';
                $config['allowed_types']        = 'jpeg|jpg|png|mp4|mkv|avi|wmv|mov';
                $this->load->library('upload', $config);
			
						
		        if ( ! $this->upload->do_upload('openImage'))
                {		
					if ( ! $this->upload->do_upload('openVideo'))
					{		
						if ($post['comment'] != "")
						{
							$this->Model->insert_post_group($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,0,1,1,$this->session->userdata('usergroup'));
						}
					}
					else
					{
						$te = $this->upload->data();
						$namafile = $te["file_name"];
						$this->Model->insert_post_group($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,$namafile,1,1,$this->session->userdata('usergroup'));
					}
                }
                else
                {
					$te = $this->upload->data();
					$namafile = $te["file_name"];
					$this->Model->insert_post_group($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,$namafile,1,1,$this->session->userdata('usergroup'));
                }
				redirect('Welcome/goto_group_member');
		}
	}
	public function group_manage()
	{ //mengelola grup
		$post = $this->input->post();
		$hashtaglist = array();
		$mentionuserlist = array();
		if(isset($_POST['postBTN'])){
				$postingan = $post['comment'];
				preg_match_all('/(#\w+)/', $postingan, $results);
				foreach ($results[0] as $hashtag)
				{
					$postingan = str_replace($hashtag,"<a href='search_hashtag/".trim($hashtag,"#")."'>".$hashtag."</a>",$postingan);
					array_push($hashtaglist,$hashtag);
				}
				preg_match_all('/(@\w+)/', $postingan, $results);
				foreach ($results[0] as $mention)
				{
					$ownmention = $this->session->userdata("myusername");
					if (trim($mention,"@") == $ownmention)
					{
						$postingan = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$postingan);
					}
					else
					{
						$allfriends = $this->Model->select_userfriend_notme($this->session->userdata("myusername"));
						if ($allfriends != null)
						{	
							foreach ($allfriends as $row)
							{	
								if ($row->username == trim($mention,"@"))
								{
									$postingan = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$postingan);
									array_push($mentionuserlist, trim($mention,"@"));
								}
							}
						}
					}
				}
		        $config['upload_path']          = './posts/';
                $config['allowed_types']        = 'jpeg|jpg|png|mp4|mkv|avi|wmv|mov';
                $this->load->library('upload', $config);
			
						
		        if ( ! $this->upload->do_upload('openImage'))
                {		
					if ( ! $this->upload->do_upload('openVideo'))
					{		
						if ($post['comment'] != "")
						{
							$this->Model->insert_post_group($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,0,1,1,$this->session->userdata('usergroup'));
						}
					}
					else
					{
						$te = $this->upload->data();
						$namafile = $te["file_name"];
						$this->Model->insert_post_group($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,$namafile,1,1,$this->session->userdata('usergroup'));
					}
                }
                else
                {
					$te = $this->upload->data();
					$namafile = $te["file_name"];
					$this->Model->insert_post_group($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,$namafile,1,1,$this->session->userdata('usergroup'));
                }
				redirect('Welcome/goto_group');
		}
		else if(isset($_POST['cancel_request'])){
			$this->Model->delete_request_group($post['id_user']);
			redirect('Welcome/goto_group');
		}
		else if (isset($_POST['confirmgroupreq']))
		{
			$idgroup = $post['idgroup'];
			$allreq = $this->session->userdata("temprequest");
			
			
				foreach($allreq as $row)
				{
					$already_request = $this->Model->select_request_group($row,$idgroup);
					if($already_request == null)
					{
						$this->Model->insert_request_group($this->session->userdata("myusername"),$idgroup,$row);
					}
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
				$this->Model->update_group($idgroup,$groupname,$post["gbr_group"],$caption);
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
		$data['sidenotif_friend'] = $this->Model->select_sidenotif_friend($this->session->userdata('myusername'));
		$data['count_sidenotif_friend'] = $this->Model->count_select_sidenotif_friend($this->session->userdata('myusername'));
		$data['sidenotif_group'] = $this->Model->select_sidenotif_group($this->session->userdata('myusername'));
		$data['count_sidenotif_group'] = $this->Model->count_select_sidenotif_group($this->session->userdata('myusername'));
		$data['percomment'] = $this->Model->select_comment();
		$data['peremo'] = $this->Model->select_emo();
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		
		
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
			
			
			
			$counter=0;
			$data["friend"]=null;
			$frienddata = $this->Model->select_alluser();
			foreach ($frienddata as $row)
			{
				if($row->username == $this->session->userdata('myusername')){
					$this->session->set_userdata('profilepict',$row->pp);
				}
				$lookfriend = $this->Model->select_friend($row->username,$this->session->userdata('myusername'));
				if($lookfriend)
				{
					$data["friend"][$counter] = $this->Model->select_user_myfriend($row->username);
					$counter++;
				}
			}
		$post = $this->input->post();
		
		if($this->session->userdata('myusername') == 'admin')
		{
			$data['reports'] = $this->Model->select_report();
			$data['hashtags'] = $this->Model->get_hashtags();

			$data['abusive'] = count($this->Model->select_report_byabusive());
			$data['sexual'] = count($this->Model->select_report_bysexual());
			$data['spam'] = count($this->Model->select_report_byspam());
			
			$data['posting'] = $this->Model->activity_post();	
			$data['like'] = $this->Model->activity_like();	
			$data['comment'] = $this->Model->activity_comment();	
			$data['report'] = $this->Model->activity_report();
			$data['total'] = $data['posting'] + $data['like'] + $data['comment'] + $data['report'];
			
			$data['children'] = count($this->Model->select_user_children());
			$data['youth'] = count($this->Model->select_user_youth());
			$data['adult'] = count($this->Model->select_user_adult());
			$data['elder'] = count($this->Model->select_user_elder());
			$data['totalage'] = $data['children'] + $data['youth'] + $data['adult'] + $data['elder'];
			
			$data['userbanned'] = $this->Model->select_user_banned();
			
			for($i=0;$i<12;$i++)
			{
				$data['userchart'][$i] = count($this->Model->select_user_bydate($i+1));
			}
			
			$this->load->view('admin',$data);

		}
		else
		{
			$this->load->view('home',$data);
		}
		
	}
	
	public function report(){
		$post = $this->input->post();
		$this->Model->insert_report($post['idpost'],$post['idposted'],$post['myreport'],$this->session->userdata('myusername'));
		redirect('Welcome/login_page');
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
		$komentar = $post['simpankomen'];
		preg_match_all('/(#\w+)/', $komentar, $results);
		foreach ($results[0] as $hashtag)
		{
			$komentar = str_replace($hashtag,"<a href='search_hashtag/".trim($hashtag,"#")."'>".$hashtag."</a>",$komentar);
		}
		preg_match_all('/(@\w+)/', $komentar, $results);
		foreach ($results[0] as $mention)
		{
			$ownmention = $this->session->userdata("myusername");
			if (trim($mention,"@") == $ownmention)
			{
				$komentar = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$komentar);
			}
			else
			{
				$allfriends = $this->Model->select_userfriend_notme($this->session->userdata("myusername"));
				if ($allfriends != null)
				{	
					foreach ($allfriends as $row)
					{	
						if ($row->username == trim($mention,"@"))
						{
							$komentar = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$komentar);
						}
					}
				}
			}
		}
		$this->Model->insert_comment($komentar,$this->session->userdata('myusername'),$post["simpanpost"],$post["simpanreply"]);		
	}
	
	public function read_notif()
	{
		$this->Model->update_status_notif();
		redirect('Welcome/login_page');
	}
	public function goto_mention($usermention)
	{
		if ($usermention == $this->session->userdata("myusername"))
		{
			redirect("Welcome/profile");
		}
		else
		{
			$this->session->set_userdata("userfriend",$usermention);
			redirect("Welcome/goto_otherprofile");
		}
	}
	
	public function add_emo(){
		
		$post = $this->input->post();
		$this->Model->insert_emo($post["simpanpost"],$this->session->userdata('myusername'),$post["simpanemo"]);		
	
		redirect("Welcome/login_page");
	}
	public function home()
	{
		$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
		$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
		$hashtaglist = array();
		$mentionuserlist = array();
		$post = $this->input->post();
		if(isset($_POST['postBTN'])){
				$postingan = $post['comment'];
				preg_match_all('/(#\w+)/', $postingan, $results);
				foreach ($results[0] as $hashtag)
				{
					$postingan = str_replace($hashtag,"<a href='search_hashtag/".trim($hashtag,"#")."'>".$hashtag."</a>",$postingan);
					array_push($hashtaglist,$hashtag);
				}
				preg_match_all('/(@\w+)/', $postingan, $results);
				foreach ($results[0] as $mention)
				{
					$ownmention = $this->session->userdata("myusername");
					if (trim($mention,"@") == $ownmention)
					{
						$postingan = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$postingan);
					}
					else
					{
						$allfriends = $this->Model->select_userfriend_notme($this->session->userdata("myusername"));
						if ($allfriends != null)
						{	
							foreach ($allfriends as $row)
							{	
								if ($row->username == trim($mention,"@"))
								{
									$postingan = str_replace($mention,"<a href='goto_mention/".trim($mention,"@")."'>".$mention."</a>",$postingan);
									array_push($mentionuserlist, trim($mention,"@"));
								}
							}
						}
					}
				}
		        $config['upload_path'] = './posts/';
                $config['allowed_types'] = 'jpeg|jpg|png|mp4|mkv|avi|wmv|mov';
                $this->load->library('upload', $config);
			
						
		        if ( ! $this->upload->do_upload('openImage'))
                {		
					if ( ! $this->upload->do_upload('openVideo'))
					{		
						if ($post['comment'] != "")
						{
							$this->Model->insert_post($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,0,1,1);
						}
						redirect('Welcome/login_page');
					}
					else
					{
						$te = $this->upload->data();
						$namafile = $te["file_name"];
						$this->Model->insert_post($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,$namafile,1,1);
						redirect('Welcome/login_page');
					}
                }
                else
                {
					$te = $this->upload->data();
					$namafile = $te["file_name"];
					$this->Model->insert_post($this->session->userdata('myusername'),$hashtaglist,$mentionuserlist,$postingan,$namafile,1,1);

					redirect('Welcome/login_page');
                }
				
				
		}
		else if(isset($_POST['search'])){
			$keyword = $post["keyword"];
			if ($keyword == "")
			{
				redirect("Welcome/Login_page");
			}
			else
			{
				$data['request']=$this->Model->select_request_me($this->session->userdata('myusername'));
				$data['group_permission'] = $this->Model->select_group_permission_byusername($this->session->userdata('myusername'));
				
				$data['percomment'] = $this->Model->select_comment();
				$data['peremo'] = $this->Model->select_emo();
				$data['resultfriend'] = $this->Model->select_friend_search($keyword);
				if ($data['resultfriend'] == null)
				{
					$data['textfriends'] = "Sorry, we couldn't find users with the keyword ".$keyword;
				}
				else 
				{
					$friendcount = $this->Model->count_friends_search($keyword);
					$data["textfriends"] = "Showing ".$friendcount." users with the keyword ".$keyword. " : ";
				}
				//mencari user yg bukan diri sendiri
				$lookuser = $this->Model->select_user_notme($this->session->userdata('myusername'));
				//mencari user aktif yg bukan diri sendiri
				$data['chatuser'] = $this->Model->select_userfriend_notme($this->session->userdata('myusername'));
				$data['allposting'] = $this->Model->select_post_search($keyword);
				if ($data['allposting'] == null)
				{
					$data["textresults"] = "Sorry, we couldn't find posts with the keyword ".$keyword;
				}
				else
				{
					$postcount = $this->Model->count_posts_search($keyword);
					$data["textresults"] = "Showing ".$postcount." posts with the keyword ".$keyword. " : ";
				}
				//komentar posting
				$data['percomment'] = $this->Model->select_comment();
				$this->load->view('explore',$data);
			}
		}
		else if(isset($_POST['logout'])){
			$this->load->view('index');
			$this->Model->logout_status($this->session->userdata('myusername'));
			$this->session->sess_destroy();
			
		}
		else {
			$this->load->view('index');
		}
	}
}
