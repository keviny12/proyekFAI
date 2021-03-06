<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends CI_Model {
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function insert_user($user,$pass,$forgot,$name,$email,$birth,$alamat,$gender,$pp){
		//$query = "insert into user values ('".$user."','".$fname."','".$lname."','".$email."','".$telp."','".$pass."','".$cpass."','".$address."','".$pcode."')";
		$data = array(
			'username' => $user,
			'password' => $pass,
			'forgot' => $forgot,
			'name' => $name,
			'email' => $email,
			'birth' => $birth,
			'alamat' => $alamat,
			'gender' => $gender,
			'pp' => $pp,
			'active'=>1,
			'date' =>	date("Y-m-d h:i:sa"),
			'month' =>	date("m")
		);
		
		$this->db->insert('user',$data);
	}
	
	function insert_chat($id_sender,$id_receiver,$chat,$id_chater){
		$data = array(
			'id_sender' => $id_sender,
			'id_receiver' => $id_receiver,
			'chat' => $chat,
			'date' =>	date("Y-m-d h:i:sa"),
			'id_chater' => $id_chater	
		);
		
		$this->db->insert('chat',$data);
	}
	
	function insert_post($id_user,$hashtagtext,$id_mention,$caption,$attach,$suka,$jum_comment){
		$data = array(
			'id_user' => $id_user,
			'text' => $caption,
			'attach' => $attach,
			'disukai' => $suka,
			'jum_comment' => $jum_comment,
			'id_group' => 'none',
			'date' =>	date("Y-m-d h:i:sa")
		);
		
		
		
		$this->db->insert('post',$data);
		
		if ($id_mention != null)
		{
			foreach($id_mention as $row)
			{
				$data = array(
					'id_user' => $row,
					'id_subject' => $id_user,

					'id_group' => "none",
					'type' => "mention",
					'status' => 0,
					'date' => date("Y-m-d h:i:sa")
				);
				$this->db->insert('history',$data);
			}
		}
		
		if ($hashtagtext != null)
		{
			foreach($hashtagtext as $row)
			{
				$this->db->select("*");
				$this->db->from("hashtag");
				$this->db->where("hashtag_text",$row);
				$jumlah= $this->db->get()->row()->jumlah;
				if ($this->db->count_all_results() == 0)
				{
					$data = array(
						"hashtag_text" => $row,
						"jumlah" => 1
					);
					$this->db->insert("hashtag",$data);
				}
				else
				{
					$data = array("jumlah" => $jumlah + 1);
					$this->db->where("hashtag_text",$row);
					$this->db->update("hashtag",$data);
				}
			}
		}
	}
	
	function get_hashtags()
	{
		$this->db->select("*");
		$this->db->from("hashtag");
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_rep()
	{
		$this->db->select("*");
		$this->db->from("rep");
		$result = $this->db->get();
		return $result->result();
	}
	function insert_post_group($id_user,$hastagtext,$id_mention,$caption,$attach,$suka,$jum_comment,$idgroup){
		$data = array(
			'id_user' => $id_user,
			'text' => $caption,
			'attach' => $attach,
			'disukai' => $suka,
			'jum_comment' => $jum_comment,
			'id_group' => $idgroup,
			'date' =>	date("Y-m-d h:i:sa")
		);
		
		$this->db->insert('post',$data);
		
		if ($id_mention != null)
		{
			foreach($id_mention as $row)
			{
				$data = array(
					'id_user' => $row,
					'id_subject' => $id_user,
					'id_group' => $idgroup,
					'type' => "mentiongroup",
					'status' => 0,
					'date' => date("Y-m-d h:i:sa")
				);
				$this->db->insert('history',$data);
			}
		}
	}
	
	function insert_comment($text,$user,$idpost,$reply){
		$data = array(
			'text' => $text,
			'id_user' => $user,
			'id_post' => $idpost,
			'id_reply' => $reply,
			'date' =>	date("Y-m-d h:i:sa")
		);
		$this->db->insert('comment',$data);
	}
	
	function insert_report($idpost,$idposted,$text,$username){
		$data = array(
			'user_id' => $username,
			'reported_id' => $idposted,
			'id_post' => $idpost,
			'note' => $text
		);
		$this->db->insert('report',$data);
	}
	
	function insert_emo($post,$user,$emo){
		$data = array(
			'id_post' => $post,
			'id_user' => $user,
			'jenislike' =>	$emo
		);
		$this->db->insert('disukai',$data);
	}
	
	function insert_group($idgroup,$groupname,$myusername,$groupimage,$caption){
		$data = array(
			'id_group' => $idgroup,
			'group_name' => $groupname,
			'date' => date("Y-m-d h:i:sa"),
			'id_admin' => $myusername,
			'group_img' => $groupimage,
			'caption' => $caption	
		);
		
		$this->db->insert('group_social',$data);
	}
	function update_group($idgroup,$groupname,$groupimage,$caption)
	{
		$data = array(
			'group_name' => $groupname,
			'group_img' => $groupimage,
			'caption' => $caption
		);

		$this->db->where('id_group',$idgroup);
		$this->db->update('group_social',$data);
	}
	
	function edit_mypost($idpost,$text,$attach)
	{
		$data = array(
			'text' => $text,
			'attach' => $attach
		);

		$this->db->where('id_post',$idpost);
		$this->db->update('post',$data);
		
		$this->db->where("id_post",$idpost);
		$this->db->delete('disukai');
		
		$this->db->where("id_post",$idpost);
		$this->db->delete('comment');
	}
	
	function logout_status($username)
	{
		$data = array(
			'on' => 0
		);

		$this->db->where('username',$username);
		$this->db->update('user',$data);
	}
	
	function add_friend($iduser,$idfriend){
		$data = array(
			'id_friend' => $iduser."_".$idfriend,
			'date' =>	date("Y-m-d h:i:sa")
		);
		
		$this->db->insert('friend',$data);
		
		$data = array(
			'id_user' => $idfriend,
			'id_subject' => $iduser,
			'id_group' => "none",
			'type' => 'accfriend',
			'status' => 0,
			'date' => date("Y-m-d h:i:sa")
		);
		
		$this->db->insert("history",$data);
	}
	
	function get_group_admin($idgroup){
		$this->db->select("id_admin");
		$this->db->from("group_social");
		$this->db->where("id_group",$idgroup);	
		$result = $this->db->get();
		return $result->row();		
	}
	
	function add_friend_group($idadmin,$iduser,$idgroup){
		$data = array(
			'id_user' => $idadmin,
			'id_subject' => $iduser,
			'id_group' => $idgroup,
			'type' => "accgroup",
			'status' => 0,
			'date' => date("Y-m-d h:i:sa")
		);
		
		$this->db->insert("history",$data);
		
		$data = array(
			'id_group' => $idgroup,
			'id_user' => $iduser,
			'date' => date("Y-m-d h:i:sa")
		);
		
		$this->db->insert('group_member',$data);
		
		$this->db->select("*");
		$this->db->from("group_request");
		$this->db->where("id_group",$idgroup);
		$this->db->where("id_requested",$iduser);
		$result = $this->db->get();
		
		$this->db->where("id_group_request",$result->row()->id_group_request);
		$this->db->delete('group_request');
	}
	
	
	function request_friend($iduser,$idfriend){
		$data = array(
			'id_requester' => $iduser,
			'id_requested' => $idfriend,
			'date' =>	date("Y-m-d h:i:sa")
		);
		
		$this->db->insert('friend_request',$data);
		
		$data = array(
			'id_user' => $idfriend,
			'id_subject' => $iduser,
			'id_group' => "none",
			'type' => "reqfriend",
			'status' => 0,
			'date' =>	date("Y-m-d h:i:sa")
		);
		
		$this->db->insert('history',$data);
	}
	
	function get_urutan($kode)
	{
		$this->db->like("pp",$kode);
		return $this->db->count_all_results("user");
	}
	
	function get_profile_friend($kode)
	{
		$this->db->select("*");
		$this->db->from("user");
		$this->db->like("username",$kode);
		$result = $this->db->get();
		return $result->result();
	}
	
	function get_profile_group($kode)
	{
		$this->db->select("*");
		$this->db->from("group_social gs");
		$this->db->join("user u","gs.id_admin = u.username");
		$this->db->like("id_group",$kode);
		$result = $this->db->get();
		return $result->result();
	}
	
	function get_chatter($iduser,$nama){
		$this->db->select("*");
		$this->db->from("user");
		$this->db->like("name",$nama);
		$result = $this->db->get();
		
		$this->db->select("*");
		$this->db->from("chat");
		$this->db->like("id_sender",$result->row()->username);
		$this->db->like("id_receiver",$iduser);
		$this->db->order_by("date");
		$result = $this->db->get();
		return $result->result();
	}
	
	function get_idchatter($nama){
		$this->db->select("*");
		$this->db->from("user");
		$this->db->like("name",$nama);
		$result = $this->db->get();
		return $result->result();
	}
	
		
	function update_status_notif(){
		$data = array(
			'status' =>1
		);
		$this->db->update('history',$data);
	}
	
	function update_user($username,$name,$email,$birth,$address,$gender,$pp)
	{
		$this->db->where("username",$username);
		$data = array(
			'name' => $name,
			'email' => $email,
			'birth' => $birth,
			'alamat' => $address,
			'gender' => $gender,
			'pp' => $pp
		);
		$this->db->update('user',$data);
	}
	
	function update_user_password($username,$pass,$cpass){
		//$query = "update user set password = '".$pass."',confirmpassword = '".$cpass."' where username = '".$username."'";
		
		$this->db->where("username",$username);
		$data = array(
			'password' => $pass,
			'confirmpassword' =>$cpass
		);
		$this->db->update('user',$data);
	}
	
	function delete_request_group($id){
		$this->db->where("id_requested",$id);
		$this->db->delete('group_request');
	}
	
	function active_on($id){
		$this->db->where("username",$id);
		$data = array(
			'active' => 1,
		);
		$this->db->update('user',$data);
	}
	
	
	function user_banned($idreport,$username){
		//$query = "update user set password = '".$pass."',confirmpassword = '".$cpass."' where username = '".$username."'";
		
		$this->db->where("username",$username);
		$data = array(
			'active' => 0
		);
		$this->db->update('user',$data);
		
		$this->db->where("id_report",$idreport);
		$this->db->delete('report');
	}
	
	function cancel_request($username,$friend){		
		$this->db->select("*");
		$this->db->from("friend_request");
		$this->db->where("id_requester",$username);
		$this->db->where("id_requested",$friend);
		$result = $this->db->get();
		
		$this->db->where("id_request",$result->row()->id_request);
		$this->db->delete('friend_request');
	}
	function insert_request_group($iduser,$idgroup,$idrequest){
		$data = array(
			'id_group' => $idgroup,
			'id_requested' => $idrequest,
			'date' => date("Y-m-d h:i:sa")
		);

		$this->db->insert('group_request',$data);
		
		$data = array(
			'id_user' => $idrequest,
			'id_subject' => $iduser,
			'id_group' => $idgroup,
			'type' => "reqgroup",
			'status' => 0,
			'date' =>	date("Y-m-d h:i:sa")
		);
		
		$this->db->insert('history',$data);
	}
	
	function cancel_request_group($idgroup,$username){
		$this->db->select("*");
		$this->db->from("group_request");
		$this->db->where("id_group",$idgroup);
		$this->db->where("id_requested",$username);
		$result = $this->db->get();
		
		$this->db->where("id_group_request",$result->row()->id_group_request);
		$this->db->delete('group_request');
	}
	
	
	function delete_report($idreport){
		$this->db->where("id_report",$idreport);
		$this->db->delete('report');
	}
	
	function delete_report_post($idreport,$idpost){
		$this->db->where("id_report",$idreport);
		$this->db->delete('report');
		
		$this->db->where("id_post",$idpost);
		$this->db->delete('post');
		
		$this->db->where("id_post",$idpost);
		$this->db->delete('comment');
		
		$this->db->where("id_post",$idpost);
		$this->db->delete('disukai');
	}
	
	function exit_group_member($idgroup,$username){
		$this->db->select("*");
		$this->db->from("group_member");
		$this->db->where("id_group",$idgroup);
		$this->db->where("id_user",$username);
		$result = $this->db->get();
		
		$this->db->where("id_member",$result->row()->id_member);
		$this->db->delete('group_member');
	}
	
	function exit_group_admin($idgroup,$username){
		
		$this->db->where("id_group",$idgroup);
		$this->db->where("id_admin",$username);
		$this->db->delete('group_social');
	}
	function del_friend($username,$friend){
		
		$this->db->select("*");
		$this->db->from("friend");
		$this->db->like("id_friend",$friend);
		$this->db->like("id_friend",$username);
		$result = $this->db->get();
		
		$this->db->where("fid",$result->row()->fid);
		$this->db->delete('friend');
	}
	
	function delete_mypost($idpost){
		$this->db->where("id_post",$idpost);
		$this->db->delete('post');
		
		$this->db->where("id_post",$idpost);
		$this->db->delete('disukai');
		
		$this->db->where("id_post",$idpost);
		$this->db->delete('comment');
	}
	
	function select_user(){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("user");
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_user_banned(){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("active",0);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_request($username,$friend){
		//cek permintaan apa ada
		$this->db->select("*");
		$this->db->from("friend_request f");
		$this->db->where("f.id_requester",$username);
		$this->db->where("f.id_requested",$friend);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_request_group($username,$group){
		//cek permintaan apa ada
		$this->db->select("*");
		$this->db->from("group_request g");
		$this->db->where("g.id_requested",$username);
		$this->db->where("g.id_group",$group);
		$result = $this->db->get();
		return $result->result();
	}

	
	function select_request_me($username){
		//permintaan pertemanan user yg aktif
		$this->db->select("*");
		$this->db->from("friend_request f");
		$this->db->join("user u","f.id_requester = u.username");
		$this->db->where("f.id_requested",$username);
		$result = $this->db->get();
		return $result->result();
	}
	function select_alluser(){
		//mencari semua user yang bukan user aktif
		$this->db->select("*");
		$this->db->distinct();
		$this->db->from("user");
		$result = $this->db->get();
		return $result->result();
	}
	function select_user_notme($id){
		//mencari semua user yang bukan user aktif
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where_not_in("username",$id);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_user_bydate($month){
		//mencari semua user yang bukan user aktif
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("month",$month);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_report_byabusive(){
		//mencari semua user yang bukan user aktif
		$this->db->select("*");
		$this->db->from("report");
		$this->db->where("note","Abusive Content");
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_report_bysexual(){
		//mencari semua user yang bukan user aktif
		$this->db->select("*");
		$this->db->from("report");
		$this->db->where("note","Sexual Content");
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_report_byspam(){
		//mencari semua user yang bukan user aktif
		$this->db->select("*");
		$this->db->from("report");
		$this->db->where("note","Spam Content");
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_userfriend_notme($id){
		//mencari semua user yang merupakan teman dan aktif
		$this->db->select("*");
		$this->db->from("user u");
		$this->db->distinct();
		$this->db->join("friend f","f.id_friend like  CONCAT('%', u.username, '%')");
		$this->db->where_not_in("u.username",$id);
		$this->db->like("f.id_friend",$id);
		$this->db->where("u.active",1);
		$this->db->where("u.on",1);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_user_notfriend($id_friend, $id){
		//mencari user yang bukan friend dari si user aktif
		$this->db->select("*");
		$this->db->distinct();
		$this->db->from("friend");
		$this->db->like("id_friend",$id);
		$this->db->like("id_friend",$id_friend);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_user_notmyfriend($id){
		//ambil data user yg bukan friend
		$this->db->select("*");
		$this->db->from("user");
		$this->db->distinct();
		$this->db->where("username",$id);
		$result = $this->db->get();
		return $result->result();
	}
	function select_friend($id,$friend)
	{
		//cek apakah user yg friend
		$this->db->select("*");
		$this->db->from("friend f");
		$this->db->distinct();
		$this->db->like("f.id_friend",$id);
		$this->db->like("f.id_friend",$friend);
		$result = $this->db->get();
		return $result->result();
	}
	function select_group($idgroup,$username)
	{
		$this->db->select("*");
		$this->db->from("group_member");
		$this->db->distinct();
		$this->db->where("id_group",$idgroup);
		$this->db->where("id_user",$username);
		$result = $this->db->get();
		return $result->result();
	}
	function select_user_postfriend($id)
	{ //teman yang pernah post
		$this->db->select("*");
		$this->db->from("friend f");
		$this->db->like("f.id_friend",$id);
		$result = $this->db->get();
		return $result->result();
	}
	function select_user_myfriend($id){
		//ambil data user yg friend
		$this->db->select("*");
		$this->db->from("user");
		$this->db->distinct();
		$this->db->where("username",$id);
		$result = $this->db->get();
		return $result->result();
	}
	function select_friend_avail($username,$idfriend){
		//menentukan dia sudah berteman apa belum
		$this->db->select("*");
		$this->db->from("friend");
		$this->db->like("id_friend",$username);
		$this->db->like("id_friend",$idfriend);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_allchat(){
		$this->db->select("*");
		$this->db->from("chat");
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_post_friend(){
		$this->db->select("*");
		$this->db->from("post p");
		$this->db->join("user u","u.username = p.id_user");
		$this->db->order_by('p.id_post', 'desc');
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_post_group($idgroup){
		$this->db->select("*");
		$this->db->from("post p");
		$this->db->join("user u","u.username = p.id_user");
		$this->db->where('p.id_group', $idgroup);
		$this->db->order_by('p.id_post', 'desc');
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_friend_search($keyword){
		$this->db->select("*");
		$this->db->from("user");
		$this->db->like("username",$keyword);
		$this->db->or_like("name",$keyword);
		$result = $this->db->get();
		return $result->result();
	}
	
	function count_friends_search($keyword){
		$this->db->select("*");
		$this->db->from("user");
		$this->db->like("username",$keyword);
		$this->db->or_like("name",$keyword);
		return $this->db->count_all_results();
	}
	
	function select_post_search($keyword){
		$this->db->select("p.id_post as id_post,p.id_user,p.text,p.attach,p.disukai,p.jum_comment,p.id_group,p.date,
		u.username,u.name,u.pp");
		$this->db->from("post p");
		$this->db->join("user u","u.username = p.id_user");
		$this->db->like("p.text",$keyword);
		$this->db->group_by("p.id_post");
		$this->db->order_by('p.id_post', 'desc');
		$result = $this->db->get();
		return $result->result();
	}
	
	function count_posts_search($keyword){
		$this->db->select("p.id_post as id_post,p.id_user,p.text,p.attach,p.disukai,p.jum_comment,p.id_group,p.date,
		u.username,u.name,u.pp");
		$this->db->from("post p");
		$this->db->join("user u","u.username = p.id_user");
		$this->db->like("p.text",$keyword);
		$this->db->group_by("p.id_post");
		$this->db->order_by('p.id_post', 'desc');
		return $this->db->count_all_results();
	}
	
	function select_post_hashtag($hashtag){
		$this->db->select("p.id_post as id_post,p.id_user,p.text,p.attach,p.disukai,p.jum_comment,p.id_group,p.date,
		u.username,u.name,u.pp");
		$this->db->from("post p");
		$this->db->join("user u","u.username = p.id_user");
		$this->db->like("p.text","#".$hashtag);
		$this->db->group_by("p.id_post");
		$this->db->order_by('p.id_post', 'desc');
		$result = $this->db->get();
		return $result->result();
	}
	
	function count_hashtags($hashtag){
		$this->db->select("p.id_post as id_post,p.id_user,p.text,p.attach,p.disukai,p.jum_comment,p.id_group,p.date,
		u.username,u.name,u.pp");
		$this->db->from("post p");
		$this->db->join("user u","u.username = p.id_user");
		$this->db->like("p.text","#".$hashtag);
		$this->db->group_by("p.id_post");
		$this->db->order_by('p.id_post', 'desc');
		return $this->db->count_all_results();
	}
	
	function select_mypost_friend($id){
		$this->db->select("*");
		$this->db->from("post p");
		$this->db->join("user u","u.username = p.id_user");
		$this->db->where("u.username",$id);
		$this->db->order_by('p.id_post', 'desc');
		$result = $this->db->get();
		return $result->result();
	}
	
	function cek_login($id){
		$this->db->select("password,active");
		$this->db->from("user");
		$this->db->where("username",$id);
		$result = $this->db->get();
		return $result->row_array();
	}
	
	function select_user_byusername($username){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("username",$username);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_user_children(){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("2017 - YEAR(BIRTH) > ",-1);
		$this->db->where("2017 - YEAR(BIRTH) < ",14);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_user_youth(){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("2017 - YEAR(BIRTH) > ",13);
		$this->db->where("2017 - YEAR(BIRTH) < ",26);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_user_adult(){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("2017 - YEAR(BIRTH) > ",25);
		$this->db->where("2017 - YEAR(BIRTH) < ",51);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_user_elder(){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("2017 - YEAR(BIRTH) > ",50);
		$result = $this->db->get();
		return $result->result();
	}
	
	
	function select_group_byidgroup($groupid){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("group_social");
		$this->db->like("id_group",$groupid);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_group_byusername($username){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("group_social");
		$this->db->like("id_admin",$username);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_group_member_byusername($username){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("group_member gm");
		$this->db->join("group_social gs","gm.id_group = gs.id_group");
		$this->db->like("id_user",$username);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_report(){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("report r");
		$this->db->join("user u","r.reported_id = u.username");
		$this->db->join("post p","p.id_post = r.id_post");
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_all_group_members($idgroup){
		$this->db->select("*");
		$this->db->from("user u");
		$this->db->join("group_member gm","u.username = gm.id_user");
		$this->db->where("id_group",$idgroup);
		$this->db->group_by("u.username");
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_group_permission_byusername($username){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("group_request gr");
		$this->db->join("group_social gs","gs.id_group = gr.id_group");
		$this->db->where("gr.id_requested",$username);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_group_permission_show($idgroup)
	{
		$this->db->select("*");
		$this->db->from("group_request gr");
		$this->db->join("user u ","gr.id_requested = u.username");
		$this->db->where("gr.id_group",$idgroup);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_comment(){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("comment c");
		$this->db->join("user u","c.id_user = u.username");
		$this->db->order_by('c.id_comment', 'desc');
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_emo(){
		//$query = "select * from user";
		$this->db->select("*");
		$this->db->from("disukai");
		$result = $this->db->get();
		return $result->result();
	}

	
	function select_sidenotif_friend($username){
		$this->db->select("*");
		$this->db->from("history h");
		$this->db->join("user u","u.username = h.id_subject");
		$this->db->where("h.id_group","none");
		$this->db->where("h.id_user",$username);
		$result = $this->db->get();
		return $result->result();
	}
	
	function count_select_sidenotif_friend($username){
		$this->db->select("*");
		$this->db->from("history h");
		$this->db->join("user u","u.username = h.id_subject");
		$this->db->where("h.id_group","none");
		$this->db->where("h.id_user",$username);
		$this->db->where("h.status",0);
		$result = $this->db->get();
		return $result->result();
	}
	
	function select_sidenotif_group($username){
		$this->db->select("*");
		$this->db->from("history h");
		$this->db->join("user u","u.username = h.id_subject");
		$this->db->join("group_social gs","gs.id_group = h.id_group");
		$this->db->where("h.id_user",$username);
		$result = $this->db->get();
		return $result->result();
	}
	
	function count_select_sidenotif_group($username){
		$this->db->select("*");
		$this->db->from("history h");
		$this->db->join("user u","u.username = h.id_subject");
		$this->db->join("group_social gs","gs.id_group = h.id_group");
		$this->db->where("h.id_user",$username);
		$this->db->where("h.status",0);
		$result = $this->db->get();
		return $result->result();
	}
	
	public function fetch_contain($keyword,$limit,$start)
    {
		$this->db->where("status", 1);
		$this->db->like("nama_menu",$keyword);
		$this->db->from("menu");
		$this->db->limit($limit,$start);
        $query = $this->db->get();

        return $query->result();
    }
	
	public function activity_post()
	{
		return $this->db->count_all("post");
	}
	
	public function activity_like()
	{
		return $this->db->count_all("disukai");
	}
	
	public function activity_comment()
	{
		return $this->db->count_all("comment");
	}
	
	public function activity_report()
	{
		return $this->db->count_all("report");
	}
	
	public function record_count_contain($keyword)
    {
		return $this->db->where(['status'=>1])->like(['nama_menu'=>$keyword])->from("menu")->count_all_results();
		
        //return $this->db->count_all("menu");
    }
}
