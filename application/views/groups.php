
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
        <div class="form-group col-sm-offset-4 col-sm-6">
		  <ul class="nav navbar-nav">
		    <li> <div class="form-group searchbar ">
              <input type="text" class="form-control" placeholder="search">
			    </div>
				<button type="submit" class="searchbtn" style="border:none;background:none;"><i class="fa fa-search"></i></button>
			 </li>
            <li><a href="login_page">Home</a></li>
            <li><a href="member" type='button' name='member'>Friends
			<?php if(count($request) < 1){
			}else if(count($request) > 99){?>
			<i class="notif"><?php echo '99+';?></i>
			<?php }else{?>
			<i class="notif"><?php echo count($request);?></i>
			<?php }?></a></li>
            <li class="actived"><a href="group">Groups
			<?php if(count($group_permission) < 1){
			}else if(count($group_permission) > 99){?>
			<i class="notif"><?php echo '99+';?></i>
			<?php }else{?>
			<i class="notif"><?php echo count($group_permission);?></i>
			<?php }?></a></li>
            <li class="user"><a href="profile" style="background:none; "><img src=<?php echo base_url("ppicture/".$this->session->userdata('profilepict'));?> style="height:50px; 	border-radius:50px;" width=50 alt=""></a></li>
			<li><button type="submit" name="logout" class="btn btn-default logout">Log out</button></li>
          </ul>
        </div>
		</form>
    </div>
  </header>
<div class="mSidebar">
		<div class='header-chat'>Active Users (30)</div>
		<div class='contains-chat'>
		<?php foreach($chatuser as $row){ ?>
		<br>
		<div class="row member-row contact">
			<div class="col-md-4">
				<img src=<?php echo base_url("ppicture/".$row->pp);?> style="height:50px;" class="img-thumbnail" width=100 alt="">
			</div>
			<div class="text-left chat" style="margin-top:1%;">
				<?php echo $row->name; ?>
			</div>
		</div>
		<?php } ?>
		</div>
	</div>
	
	<div class="myChat">
		<table>
		<td class="chatnow1">
			<div class='header-mychat'><img src="img/user.png" class="img-thumbnail" width=50 alt=""> Kevin Yudibrata </div> <a href="#" class='quit'>X</a>
			<div class='contains-mychat'>
				<div class="row member-row">
				<br>
					<div class="text-left yourchat col-sm-11">
						Kevin, ayo siap2x FAI<div style='font-size:8pt;'>at 08:50</div>
					</div>
				</div>
			</div>
	
			<input type='text' class='chat-text'><button type='button' class='send'> > </button>
		</td>
		<td>&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp  </td>
		<td class="chatnow2">
			<div class='header-mychat'><img src="img/user.png" class="img-thumbnail" width=50 alt=""> Lucas Emil </div><a href="#" class='quit'>X</a>
			<div class='contains-mychat'>
				<div class="row member-row">
					
				
				</div>
			</div>
	
			<input type='text' class='chat-text'><button type='button' class='send'> > </button>
		</td>
		</table>
	</div>
    <nav class="navbar navbar-default">
      <div class="container">
		<div class="col-md-offset-10 col-md-2">
				<p><a href="group_maker" class="btn btn-primary mybutton btn-block"><i class="fa fa-plus"></i> Make a Group </a></p>
		</div>
        <div class="navbar-header">
			
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
      </div>
    </nav>

    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <div class="groups">
              <h1 class="page-header">Group Admin</h1>
              
			  <?php foreach($group as $row){?>
			  <div class="group-item group-now">
                <img src=<?php echo base_url("gpicture/".$row->group_img);?> width=150 height=150 alt="">
                <h4><a href="#"><?php echo $row->group_name;?></a></h4>
                <p><?php echo $row->caption;?>
					
					<div class="col-md-3">
							<p><a href="#" id=<?php echo $row->id_group; ?> class="btn btn-primary mybuttonprof btn-block group-prof"><i class="fa fa-edit"></i> View Profile</a></p>
					</div>
					
					<div class="col-md-3">
							<p><a href="#" id=<?php echo $row->id_group; ?> style="margin-top:35px;" class="btn btn-danger btn-block exit-group-admin"><i class="fa fa-close"></i> Exit from Group</a></p>
					</div>
				  
                </p>
              </div>
			  <?php }?>
              <div class="clearfix"></div>
            </div>
			 <div class="groups">
              <h1 class="page-header">Group Member</h1>
              
			  <?php foreach($group_member as $row){?>
			  <div class="group-item group-now">
                <img src=<?php echo base_url("gpicture/".$row->group_img);?> width=150 height=150 alt="">
                <h4><a href="#"><?php echo $row->group_name;?></a></h4>
                <p><?php echo $row->caption;?>
					
					<div class="col-md-3">
							<p><a href="#" id=<?php echo $row->id_group;?> class="btn btn-primary mybuttonprof btn-block group-prof-member"><i class="fa fa-edit"></i> View Profile</a></p>
					</div>
					
					<div class="col-md-3">
							<p><a href="#" id=<?php echo $row->id_group;?> style="margin-top:35px;" class="btn btn-danger btn-block exit-group-member"><i class="fa fa-close"></i> Exit from Group</a></p>
					</div>
				  
                </p>
              </div>
			  <?php }?>
              <div class="clearfix"></div>
            </div>
          </div>
          <div class="col-md-4">
  
            <div class="panel panel-default groups">
              <div class="panel-heading">
                <h3 class="panel-title">Groups Permission</h3>
              </div>
              <div class="panel-body">
              <?php foreach($group_permission as $row){?>
				<div class="group-item">
					<div class="col-md-4">
							<img src=<?php echo base_url("gpicture/".$row->group_img); ?> class="img-thumbnail" style="height:85px;" alt="">
					</div>	
						<div class="text-left col-md-8">
							<h4><?php echo $row->group_name;?></h4>
						</div>
						<div class="text-left col-md-8">
							<?php echo $row->caption;?>
						</div>
					
						<div class="col-md-8 col-md-offset-4">
							<p><a href="#" id=<?php echo $row->id_group; ?> class="btn btn-success btn-block add_group"><i class="fa fa-users"></i> Confirm Request</a></p>
						</div>
						<div class="col-md-8 col-md-offset-4">
							<p><a href="#" id=<?php echo $row->id_group; ?> class="btn btn-danger btn-block cancel_group"><i class="fa fa-close"></i> Cancel Request</a></p>
						</div>

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
