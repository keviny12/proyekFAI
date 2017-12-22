<html>
<head>
	<title>UnSoS - RegisterPage</title>
	<script type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
	<script>
	$(document).ready(function(){
        $("#output").addClass("alert alert-danger animated fadeInUp");	
	});
	</script>
	
	<style>
	body{
		background: #eee url(<?php echo base_url("css_design/back_login.jpg"); ?>);
		background-repeat: no-repeat;
		background-attachment: fixed;
		background-size: cover;
		font-size:15pt;
		font-family:'Tw Cen MT Condensed';
		color:#dfcfe5;
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
		background: #fff;
		background-image: linear-gradient(#480064, #770091 60%, #510062);
	}
	
	#output{
		position: absolute;
		width: 300px;
		top: -75px;
		left: 0;
		color: #fff;
		margin-left:600px;
		text-align:center;
		z-index:2;
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
		background: #fff;
		z-index: -1;
		-webkit-transform: rotateZ(4deg);
		-moz-transform: rotateZ(4deg);
		-ms-transform: rotateZ(4deg);
		background:#000;
	
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
	
	.form-box input[name="user"]{
		border-radius: 5px 5px 0 0 ;
	}
	
	.form-box input[type="password"]{
		
		border-top: 0;
	}
	#cpass{
		border-radius: 0 0 5px 5px;
	}
	.form-box input[name="telp"]{
		border-radius: 0 0 5px 5px;
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
	}
	#forgot{
		text-align:left;
	}
	
	button{
		background:white;
		border-radius:5px;
		color:#74008d;		
		border: 1px solid gray;
		font-size: 12pt;		
	}
	
	.logo{
		margin-left:25px;
	}
	.picture{
		margin-left:0px;
		border-radius: 100%;
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
			<div id="breadcrumb">Register > MyData > MyAccount <img src=<?php echo base_url("img/logo.png");?> height=20 class="logo col-sm-2" alt=""></div>
            <div id="output"></div>
			
            <div class="avatar"><img id="avatar-pic" src=<?php echo base_url("ppicture/".$this->session->userdata('kodegbr')."");?> height=100 width=100 class="picture col-sm-2" alt="">Hi, <?php echo $this->session->userdata('fname');?></div>
            <div class="form-box">
             <?php echo form_open_multipart('Welcome/Register'); ?>    
					<?php  echo form_upload('ppicture','',' class="ppicture" ');?> <button name="upload" class="btn btn-info btn-block login next" type="submit">Upload</button><hr><br>
                    <input name="user" type="text" placeholder="username">
                    <input name="pass" type="password" placeholder="password">
                    <input name="cpass" id='cpass' type="password" placeholder="confirm password">
					<Br><br>
					<div id="Forgot">Forgot Password ?</div>
					<input name="forgotpass"style="text-align:left;"; type="text" placeholder="type anything you can remember it easily">
                    <button name="register" class="btn btn-info btn-block login" type="submit">Register</button>
					<button name="back_group" class="btn btn-info btn-block login" type="submit">Back</button>
			 <?php echo form_close(); ?>
               
            </div>
        </div>
        
</div>
</body>
</html>