<html>
<head>
	<title>UnSoS - LoginPage</title>
	<script type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
	<script>
		$(function(){	
			var textfield = $("input[name=user]");
			//$("body").scrollTo("#output");
			$('.mylogin').click(function(e){
				e.preventDefault();
				if (textfield.val() != "") {
					var user = $("#user").val();
					var pass = $("#pass").val();
					$.post("<?php echo base_url(); ?>"+'index.php/Welcome/simpan_id',{id:user, passw:pass},function(value){
						var hasil = value.split(" ");
						if (hasil[0] == "success")
						{
							$("#output").addClass("alert  animated fadeInUp").html("Hi, " + "<span style='text-transform:capitalize'>" + hasil[1] + "</span>");
							$("#output").removeClass(' alert-danger');
							$("#output").css('margin-left','40px');
							$("#output").css('margin-top','210px');
							$(".remember").html('');
							$(".register").css('opacity','0');
							$(".register").css('margin-left','1000px');
							$(".mylogin").css('margin','auto');
							$("input").css({
							"height":"0",
							"padding":"0",
							"margin":"0",
							"opacity":"0"
							});
							//change button text 
							$('.mylogin').html("Continue");
							$('.mylogin').click(function(){
								//show avatar
								$(".avatar").css({
									"background-image": "url('http://api.randomuser.me/0.3.2/portraits/women/35.jpg')"
								});
								window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/login';
							})
						}
						else
						{
							$("#output").removeClass(' alert alert-success');
				
							$("#output").css('margin-left','40px');
							$("#output").addClass("alert alert-danger animated fadeInUp").html(value);
						}
					});
					
				}
				else {
					//remove success mesage replaced with error message
					$("#output").removeClass(' alert alert-success');
					
					$("#output").css('margin-left','40px');
					$("#output").addClass("alert alert-danger animated fadeInUp").html("Sorry, fill username first ");
					
				}
			});
});

	</script>
	
	<style>
	body{
		background: #eee url(<?php echo base_url("css_design/back_login.jpg"); ?>);
		background-repeat: no-repeat;
		background-attachment: fixed;
		background-size: cover;
		font-size:15pt;
		color:#ccb7d4;
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
	}
	
	#output.alert-success{
		background: rgb(25, 204, 25);
	}
	
	#output.alert-danger{
		background: rgb(228, 105, 105);
	}
	button{
		background:white;
		border-radius:5px;
		color:#74008d;		
		border: 1px solid gray;
		font-size: 12pt;		
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
		width: 100%;
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
		border-radius: 0 0 5px 5px;
	}
	
	.form-box input:focus{
		outline: 0;
		background: #eee;
	}
	
	.form-box input[type="text"]{
		border-radius: 5px 5px 0 0;
		text-transform: lowercase;
	}
	
	.form-box input[type="password"]{
		border-radius: 0 0 5px 5px;
		border-top: 0;
	}
	
	.form-box button.login{
		margin-top:15px;
		padding: 10px 20px;
	}
	.remember{
		margin-top:-32px;
		margin-right:-10px;
		font-size:14pt;	
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
	
	@keyframes fadeOutDown {
	0% {
		opacity: 1;
		-webkit-transform: translateY(-20px);
		-ms-transform: translateY(-20px);
		transform: translateY(-20px);
	}
	
	100% {
		opacity: 0;
		-webkit-transform: translateY(0);
		-ms-transform: translateY(0);
		transform: translateY(0);
	}
	}
	
	.fadeOutDown {
	-webkit-animation-name: fadeOutDown;
	animation-name: fadeOutDown;
	}
	
	
	
	</style>
</head>
<body>
<div class="container">
	<div class="login-container">
			<br>
            <div id="output"></div>
			<img src=<?php echo base_url("img/logo.png");?> height=80 >
            <div class="form-box">
			<Br>
			<Br>
			 <?php echo form_open('Welcome/login'); ?>
                    <input name="user" id="user" type="text" placeholder="username">
                    <input name="pass" id="pass" type="password" placeholder="password">
                    <button name="login" class="btn btn-info btn-block login mylogin" type="submit">Login</button>
                    <button name="register" class="btn btn-info btn-block login register" type="submit">Register</button></br>
					<input type="checkbox"><div class="remember">Remember Me?</div>
			 <?php echo form_close(); ?>
               
            </div>
        </div>
        
</div>
</body>
</html>