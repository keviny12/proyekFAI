
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UnSoS - GroupPage</title>

   <!-- Bootstrap core CSS -->
    <link href=<?php echo base_url("css/bootstrap.css");?> rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href=<?php echo base_url("css/style.css");?> rel="stylesheet">
    <link href=<?php echo base_url("css/font-awesome.css");?> rel="stylesheet">
  	<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
	<script src="<?php echo base_url('js/Chart.js');?>"></script>
	<script>
		var myChart = new Chart(ctx, {...});
	</script>
  </head>
	

	<script type="text/javascript" src="<?php echo base_url('js/script.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){

		$('.group-prof').click(function(){
			var username = $(this).attr('id');			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/group_data',{group:username},function(value){	
				
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/goto_group';
			});
		});
		
		$('.group-prof-member').click(function(){
			var username = $(this).attr('id');			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/group_data',{group:username},function(value){	
				
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/goto_group_member';
			});
		});
		
		$(".add_group").click(function(){
			//confirmation complete
			var username = $(this).attr('id');				
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/add_group',{group:username},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/group';
			});
			
		});
		
		$(".cancel_report").click(function(){
			//confirmation cancel
			var idreport = $(this).attr('id');			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/cancel_report',{simpanreport:idreport},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/login_page';
			});
		});
		
		$(".delete_post_report").click(function(){
			//confirmation cancel
			var id = $(this).attr('id').split("_");			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/delete_post_report',{simpanreport:id[0],simpanpost:id[1]},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/login_page';
			});
		});
		
		$(".banned").click(function(){
			//confirmation cancel
			var id = $(this).attr('id').split("_");			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/banned_user',{simpanreport:id[0],simpanuser:id[1]},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/login_page';
			});
		});
		
		$(".cancel_group").click(function(){
			//confirmation cancel
			var username = $(this).attr('id');				
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/cancel_group',{group:username},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/group';
			});
			
		});
		
		$(".exit-group-member").click(function(){
			//confirmation complete
			var username = $(this).attr('id');				
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/exit_group_member',{group:username},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/group';
			});
			
		});
		
		$(".exit-group-admin").click(function(){
			//confirmation complete
			var username = $(this).attr('id');				
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/exit_group_admin',{group:username},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/group';
			});
			
		});
		
		$(".contact > .chat").click(function(){
			//ambil chat dari db
			var title = $(this).text();
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/see_mychat',{friend:title},function(value){				
					$(".chatnow"+counter+" > .contains-mychat > .member-row").html(value);
			});

			
			var waktu = setInterval(function(){ 				
					if(counter==2){
						title = $(".chatnow2 > .header-mychat").text();
						$.post("<?php echo base_url(); ?>"+'index.php/Welcome/see_mychat_realtime',{friend:title},function(value){
							$(".chatnow2 > .contains-mychat > .member-row").html(value);
						});
					}
					else if(counter==1)
					{
						title = $(".chatnow1 > .header-mychat").text();
						$.post("<?php echo base_url(); ?>"+'index.php/Welcome/see_mychat_realtime',{friend:title},function(value){
							$(".chatnow1 > .contains-mychat > .member-row").html(value);
						});
					}
			}, 1000);

				
			

			counter--;
			if(counter == 0)
			{
				counter=2;
			}

		});
		
		$(".send").click(function(){
			//ambil chatnya trus passing ke ajax untuk disimpan melalui controller
			var index = $(this).index('.send')+1;
			var messagein = $(".chatnow"+index+" > .chat-text").val();
			youraccount = $(".chatnow"+index+" > .header-mychat").text();
			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/chat',{simpanchat:messagein,friend:youraccount},function(value){	
				$(".chatnow"+index+" > .contains-mychat > .member-row").html(value);
			});
			$(".chatnow"+index+" > .chat-text").val("");
			
			//menambahkan waktu
		});
		
	});

</script>
  <body>

 <?php echo form_open('Welcome/home'); ?>
  <header>
    <div class="container">
      <img src=<?php echo base_url("img/logo.png");?> height=50 class="logo col-sm-2" alt="">
      <form class="form-inline ">
        <div class="form-group col-sm-offset-8 col-sm-2">
		  <ul class="nav navbar-nav">
            <li class="user"><a href="profile" style="background:none; "><img src=<?php echo base_url("ppicture/".$this->session->userdata('profilepict'));?> style="height:50px; 	border-radius:50px;" width=50 alt=""></a></li>
			<li><button type="submit" name="logout" class="btn btn-default logout">Log out</button></li>
          </ul>
        </div>
		</form>
    </div>
  </header>
    <nav class="navbar navbar-default">

    </nav>

    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <div class="groups">
              <h1 class="page-header">Administrator</h1>
				<p><strong style="font-size:16pt;">User Chart</strong></p><br>
				<canvas id="myChart" width="400" height="400"></canvas>
				<script>
					var ctx = document.getElementById("myChart");
					var myChart = new Chart(ctx, {
						type: 'line',
						data: {
							labels: ["January","February","March","April","May","June","July","September","October","November","December"],
							datasets: [{
								label: 'Registered users',
								data: [1, 1, 1, 1, 1, 1, 1, 1, 15, 28, 42],
								backgroundColor: [
									'rgba(255, 99, 132, 0.2)',
									'rgba(54, 162, 235, 0.2)',
									'rgba(255, 206, 86, 0.2)',
									'rgba(75, 192, 192, 0.2)',
									'rgba(153, 102, 255, 0.2)',
									'rgba(255, 159, 64, 0.2)'
								],
								borderColor: [
									'rgba(255,99,132,1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)',
									'rgba(255, 159, 64, 1)'
								],
								borderWidth: 1
							}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							}
						}
					});
				</script>
            </div>
			
          </div>
          <div class="col-md-4">
  
            <div class="panel panel-default groups">
              <div class="panel-heading">
                <h3 class="panel-title">Reported Posting</h3>
              </div>
              <div class="panel-body" style="height:700px; overflow-x:hidden; overflow-y:scroll; ">
              <?php foreach($report as $row){?>
				<div class="group-item">
					 <?php if($row->attach != '0'){?>
				   <embed src=<?php echo base_url("posts/".$row->attach);?>  autostart="false" loop="false" width="100%" height="300px" controller="true" bgcolor="#333333"></embed>
				   <?php } ?>   <div class="pointer"  >
                         <p><br>Caption: <br><?php echo $row->text; ?><hr></hr></p>
                       </div>

					<div id="report data">
					<?php foreach($alluser as $rows){ if($rows[0]->username == $row->user_id){?>
					<table><tr>
					<td>Posted by </td><td> &nbsp : <a href="" id="$row->user_id"><?php echo $rows[0]->name; ?></a></td></tr>
					<?php }}?><tr>
					<td>Reported by </td><td>&nbsp :  <a href="" id="$row->reported_id"><?php echo $row->name; ?></a></td></tr>
					<tr>
					<td>Reason </td><td> &nbsp : <?php echo $row->note; ?></td></tr>
					</table>
					</div><br>					
					

				
					<p><span id=<?php echo $row->id_report; ?> class="btn btn-danger btn-block cancel_report"><i class="fa fa-close"></i> Cancel Report</span></p>
					<p><span id=<?php echo $row->id_report."_".$row->id_post; ?> class="btn btn-danger btn-block delete_post_report"><i class="fa fa-close"></i> Delete Post</span></p>
					<p><span id=<?php echo $row->id_report."_".$row->id_user; ?> class="btn btn-danger btn-block banned"><i class="fa fa-close"></i> Banned User</span></p>
				

				</div>
			  <?php } ?>
				
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
<?php echo form_close(); ?>
    <footer>
     <div class="container">
        <p>Unsos Copyright &copy, 2017</p>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>
