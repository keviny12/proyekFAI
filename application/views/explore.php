
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UnSoS - Explore</title>

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
		
		$('.reaction').slideUp();
		
		$('.reactchoose').click(function(){
			var title = $(this).attr('value');
			$('#'+title).slideToggle();
		});
		
		$('.emo').click(function(){
			var myemo = $(this).attr('value');
			var idpost = $(this).attr('id');
			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/add_emo',{simpanpost:idpost,simpanemo:myemo},function(value){				
					window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/login_page';
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
			var d = new Date();
			var curr_hour = d.getHours();
			var curr_min = d.getMinutes();

			if (curr_hour < 10) {
				curr_hour = "0" + curr_hour;
			}

			if (curr_min < 10) {
				curr_hour = "0" + curr_min;
			}

			$(".chatnow"+index+" > .contains-mychat > .member-row").append('<div class="text-left mychat col-sm-11">'+messagein+'<div style="font-size:8pt;">at '+curr_hour + ":" + curr_min+'</div></div>');
		});
		
		$(".otherfriend").click(function(){
			//suggested friend
			var username = $(this).attr('id');			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/otherprofile',{friend:username},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/goto_otherprofile';
			});
		});
		
		$(".postpic").click(function(){
			//suggested friend
			var username = $(this).attr('id');			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/otherprofile',{friend:username},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/goto_otherprofile';
			});
		});
		
		$(".add").click(function(){
			var idpost = $(this).attr('id');
			var komen = $("."+idpost).val();
			var reply = $("."+idpost).attr('id'); 
			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/add_comment',{simpankomen:komen,simpanpost:idpost,simpanreply:reply},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/login_page';
			});
			
		});
		
		$(".reply").click(function(e){
			e.preventDefault();
			var replyname = $(this).attr("name");
			var postid = $(this).attr("id");
			$("input[name='" + postid + "']").val("@" + replyname + " ");
		});
		
	});
	</script>
	
  <body onload="setup()">
  
<?php echo form_open_multipart('Welcome/home'); ?>
  <header>
    <div class="container">
      <img src=<?php echo base_url("img/logo.png");?> height=50 class="logo col-sm-2" alt="">
      <form class="form-inline ">
        <div class="form-group col-sm-offset-4 col-sm-6">
		  <ul class="nav navbar-nav">
            <li class="actived"><a href=<?php echo base_url()."/index.php/Welcome/Login_page"?>>Home</a></li>
            <li><a href=<?php echo base_url()."/index.php/Welcome/member"?> type='button' name='member'>Friends</a></li>
            <li><a href=<?php echo base_url()."/index.php/Welcome/group"?>>Groups</a></li>
            <li><a href="photos.php">Photos</a></li>
            <li class="user"><a href="profile" style="background:none; "><img src=<?php echo base_url("ppicture/".$this->session->userdata('profilepict'));?> style="height:50px; border-radius:50px;" width=50 alt=""></a></li>
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

    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <h1 class="page-header">Search Results</h1>
			<div class="row">
				<div class="col-md-12">
				<p><strong style="font-size:16pt;">Users</strong></p>
				<p><?php echo $textfriends;?></p>
				<?php if ($resultfriend != null) {?>
				<div class="panel panel-default post">
					<div class="panel-body">
						<?php foreach ($resultfriend as $row) { ?>
							<div class="group-item">
								<div style="float:left;padding-right:2%;">
								<?php echo "<a href='".base_url()."index.php/Welcome/goto_mention/".$row->username."'>"; ?>
								<img src=<?php echo base_url("ppicture/".$row->pp);?> style="border-radius:50%;width:50px;height:50px;" alt="">
								<?php echo "</a>"; ?>
								</div>
								<div>
								<p><strong><?php echo $row->name; ?></strong></p>
								<p><?php echo $row->username; ?></p>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				</div>
			</div><br><br>
			<div class="row">
				<div class="col-md-12">
				<p><strong style="font-size:16pt;">Posts</strong></p>
				<p><?php echo $textresults;?></p>
				<?php foreach($allposting as $row){?>
				<div class="panel panel-default post">
				  <div class="panel-body">
					 <div class="row">
					 <?php $posting = $row->id_post; ?>
					   <div class="col-sm-2">
					   
					   <?php if($row->username != $this->session->userdata('myusername')){?>
						 <a href="#" class="post-avatar thumbnail postpic" id=<?php echo$row->username;?> >
					   <?php }else{?>
						<a href="profile" class="post-avatar thumbnail" id=<?php echo$row->username;?> >
					   <?php }?>
						<img src=<?php echo base_url("ppicture/".$row->pp);?> alt=""><div class="text-center"><?php echo $row->name ?></div></a>
					   
					   </div>
					   <div class="col-sm-10">
					   <?php if($row->attach != '0'){?>
					   <embed src=<?php echo base_url("posts/".$row->attach);?>  autostart="false" loop="false" width="80%" height="450px" controller="true" bgcolor="#333333"></embed>
					   <?php } ?>
						 <div class="bubble">
						   <div class="pointer">
							 <p><?php echo $row->text ?></p>
						   </div>
						   <div class="pointer-border"></div>
						 </div>
						 <p class="post-actions"><span class="reactchoose" value=<?php echo $row->id_post ?>>What do you feel ?</span> | <a href="#">Report</a> <span style="margin-left:21%;">
						 <?php
							
							 $like=0;
							 $love=0;					 
							 $laugh=0;	
							 $wow=0;	
							 $sad=0;
							 $angry=0;
							 $count=0;
							 
						 foreach($peremo as $rows){
							 if($rows->id_post ==  $posting)
							 {
								if($rows->jenislike == 'like')
								{
									$like++;
								}
								else if($rows->jenislike == 'love')
								{
									$love++;
								}
								else if($rows->jenislike == 'laugh')
								{
									$laugh++;
								}
								else if($rows->jenislike == 'wow')
								{
									$wow++;
								}
								else if($rows->jenislike == 'sad')
								{
									$sad++;
								}
								else if($rows->jenislike == 'angry')
								{
									$angry++;
								}
								$count++;
							 }
						 } ?>
						 
						 <?php echo $like; ?> <img src=<?php echo base_url("emo/like.png");?> width=20> 
						 <?php echo $love; ?>  <img src=<?php echo base_url("emo/love.png");?> width=20> 
						 <?php echo $laugh; ?>  <img src=<?php echo base_url("emo/laugh.png");?> width=20> 
						 <?php echo $wow; ?> <img src=<?php echo base_url("emo/wow.png");?> width=20> 
						 <?php echo $sad; ?> <img src=<?php echo base_url("emo/sad.png");?> width=20> 
						 <?php echo $angry; ?> <img src=<?php echo base_url("emo/angry.png");?> width=20>
						 &nbsp;|&nbsp;<?php echo $count; ?> Likes</span></p>
						 
						 <div class="comment-form ">
						   <form class="form-inline">
						   <div class="reaction" id=<?php echo $row->id_post ?>><img src=<?php echo base_url("emo/like.png");?> class="emo" value="like" id=<?php echo $row->id_post ?> width=40> <img src=<?php echo base_url("emo/love.png");?> class="emo" value="love" id=<?php echo $row->id_post ?> width=40> <img src=<?php echo base_url("emo/laugh.png");?> id=<?php echo $row->id_post ?> class="emo" value="laugh" width=40> <img src=<?php echo base_url("emo/wow.png");?> id=<?php echo $row->id_post ?> class="emo" value="wow" width=40> <img src=<?php echo base_url("emo/sad.png");?> class="emo" id=<?php echo $row->id_post ?> value="sad" width=40> <img src=<?php echo base_url("emo/angry.png");?> class="emo" value="angry"  id=<?php echo $row->id_post ?> width=40> 
							</div>
							<div class="form-group">
							  <input type="text" class="form-control <?php $postnow = $row->id_post; echo $row->id_post;?>" id=<?php echo $row->username; ?> name=<?php echo $row->id_post?> placeholder="enter comment">
							</div>
							<button type="button" id=<?php echo $row->id_post;?> class="btn btn-default add">Add</button>
							</form>
						 </div>
						 <div class="clearfix"></div>

						 <div class="comments">


						 <!-- data di for terus dipilah dengan if milik siapa komen tsb -->
						 <?php foreach($percomment as $rowss) { if($rowss->id_post == $postnow){ ?>
						   <div class="comment">
							 <a href="#" class="comment-avatar pull-left"><img src=<?php echo base_url("ppicture/".$rowss->pp);?> alt=""></a>
							 <div class="comment-text">
							   <?php echo $rowss->text;?>
							   <div class='datetime'> at: <?php echo $rowss->date;?> | by: <a href="otherprofile"> <?php echo $rowss->name;?></a> | <a href="#" name=<?php echo $rowss->username;?> id=<?php echo $rowss->id_post;?> class="reply"> Reply</a> </div>
							 </div>
						   </div>
						   <div class="clearfix"></div>
				<?php }} ?>

						 </div>
					   </div>
					 </div>
				  </div>
				</div>
				<?php } ?>

						 </div>
					   </div>
					 </div>
				  </div>
				</div>
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
