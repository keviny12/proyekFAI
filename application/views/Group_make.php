<html>
<head>
	<title>UnSoS - MakeGroupPage</title>
	<script type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery-ui.js');?>"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.8.24/themes/base/jquery-ui.css">

	<link href=<?php echo base_url("css/jquery-ui.css");?> rel="stylesheet">
	<script>
	$(document).ready(function(){
		$("#output").addClass("alert alert-danger animated fadeInUp").html();

		$("#nama_group").change(function(){
			$(".login").attr("disabled",false);
			$(".ppicture").attr("disabled",false);
		})

	});
	</script>
	
	
	<style>

	body{
		background: #eee url(<?php echo base_url("css_design/back_login.jpg"); ?>);
		background-repeat: no-repeat;
		background-attachment: fixed;
		background-size: cover;
		font-family:'Tw Cen MT Condensed';
		font-size:15pt;
		color:#dfcfe5;
	}
	img{
		margin-left:37%;
	}
	html,body{
		position: relative;
		height: 100%;
	}
	
	.login-container{
		position: relative;
		width: 300px;
		margin: 80px auto;
		padding: 20px 40px 40px;
		text-align: center;
		background-image: linear-gradient(#480064, #770091 60%, #510062);
	}
	
	#output{
		position: absolute;
		width: 300px;
		top: -75px;
		left: 0;
		color: #fff;
		text-align:center;
		margin-left:600px;
	}
	
	button{
		background:white;
		border-radius:5px;
		color:#74008d;		
		border: 1px solid gray;
		font-size: 12pt;		
	}
	
	#output.alert-success{
		background: rgb(25, 204, 25);
	}
	
	#output.alert-danger{
		background: rgb(228, 105, 105);
	}
	
	
	.login-container::before,.login-container::after{
		content: "";
		position: absolute;
		width: 100%;height: 100%;
		top: 3.5px;left: 0;
		background:#000;
		z-index: -1;
		-webkit-transform: rotateZ(4deg);
		-moz-transform: rotateZ(4deg);
		-ms-transform: rotateZ(4deg);
	
	}
	
	.login-container::after{
		top: 5px;
		z-index: -2;
		-webkit-transform: rotateZ(-2deg);
		-moz-transform: rotateZ(-2deg);
		-ms-transform: rotateZ(-2deg);
	
	}
	
	.avatar{
		width: 100px;height: 100px;
		margin: 10px auto 30px;
		border-radius: 100%;
		border: 2px solid #aaa;
		background-size: cover;
	}
	
	.form-box input{
		width: 99%;
		padding: 10px;
		text-align: center;
		height:40px;
		border: 1px solid #ccc;;
		background: #fafafa;
		transition:0.2s ease-in-out;
	
	}
	
	.form-box input[type="checkbox"]{
		width: 5%;
		margin-right:55%;
	}
	
	.form-box input[type="radio"]{
		width: 5%;
	}
	.form-box input[type="date"]{
		width: 69%;
		
	}
	.form-box input:focus{
		outline: 0;
		background: #eee;
	}
	
	.form-box input[name="fname"]{
		border-radius: 5px 0 0 0;
		width: 49%;
		text-transform: lowercase;
	}
	
	.form-box input[name="lname"]{
		border-radius: 0 5px 0 0;
		width: 48.5%;
		text-transform: lowercase;
	}
	
	.desc[name="caption"]{
		border-radius: 0 0 5px 5px;
		width: 99%;
		text-align:center;
		border:none;
		text-transform: lowercase;
		margin-top:0px;
	}
	
	.form-box input[name="gname"]{
		border-radius: 5px 5px 0 0 ;
	}
	
	.form-box input[name="birth"]{
		border-radius: 0 0 5px 5px;
	}
	.form-box input[type="password"]{
		border-radius: 0 0 5px 5px;
		border-top: 0;
	}
	
	.form-box input[name="telp"]{
		border-top: 0;
	}
	
	.form-box button.login{
		margin-top:15px;
		padding: 10px 20px;
	}
	
	
	.animated {
	-webkit-animation-duration: 1s;
	animation-duration: 1s;
	-webkit-animation-fill-mode: both;
	animation-fill-mode: both;
	}
	
	@-webkit-keyframes fadeInUp {
	0% {
		opacity: 0;
		-webkit-transform: translateY(20px);
		transform: translateY(20px);
	}
	
	100% {
		opacity: 1;
		-webkit-transform: translateY(0);
		transform: translateY(0);
	}
	}
	
	@keyframes fadeInUp {
	0% {
		opacity: 0;
		-webkit-transform: translateY(20px);
		-ms-transform: translateY(20px);
		transform: translateY(20px);
	}
	
	100% {
		opacity: 1;
		-webkit-transform: translateY(0);
		-ms-transform: translateY(0);
		transform: translateY(0);
	}
	}
	
	.fadeInUp {
	-webkit-animation-name: fadeInUp;
	animation-name: fadeInUp;
	}
	
	#breadcrumb{
		text-align:left;
		margin-left:-10px;
	}
	.picture{
		margin-left:0px;
		border-radius: 100%;
	}
	.ppicture{
		background:black;
	}
	.form-box input[type="file"]{
		width:60%;
		border:0;
		background:white;
	}
	</style>
</head>
<body>
<div class="container">
	<div class="login-container">
			<?php echo form_open_multipart('Welcome/group_profile');?>
			
			<div id="breadcrumb">Group > NewGroup <img src=<?php echo base_url("img/logo.png");?> height=20 class="logo col-sm-2" alt=""></div> 
            <div id="output"></div>
            <div class="avatar"><img src=<?php echo base_url("gpicture/".$this->session->userdata('file_name_group')."");?> name="group_picture" height=100 width=100 class="picture col-sm-2" alt="">Group Picture</div>
            <div class="form-box">
					<?php  echo form_upload('gpicture','',' class="ppicture" disabled');?> <button name="upload" class="btn btn-info btn-block login next" type="submit" disabled>Upload</button><hr><br>
					<input name="gname" type="text" value="<?php echo $this->session->userdata('temp_file'); ?>"  placeholder="Group Name" id="nama_group">
					<textarea placeholder="Description" name="caption" class="desc" ></textarea>
                    <button name="make" class="btn btn-info btn-block login makegroup" type="submit">Make Group</button>
					<button name="back" class="btn btn-info btn-block login cancelgroup" type="submit">Cancel</button>
			 <?php echo form_close(); ?>
               
            </div>
        </div>
        
</div>
</body>
</html>