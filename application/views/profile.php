
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UnSoS - MyProfilePage</title>

   <!-- Bootstrap core CSS -->
    <link href=<?php echo base_url("css/bootstrap.css");?> rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href=<?php echo base_url("css/style.css");?> rel="stylesheet">
    <link href=<?php echo base_url("css/font-awesome.css");?> rel="stylesheet">
  	<script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
  </head>
	

	<script type="text/javascript" src="<?php echo base_url('js/script.js');?>"></script>
<script type="text/javascript">

				function setup() {
					document.getElementById('inputImage').addEventListener('click', openDialog);
					function openDialog() {
						document.getElementById('openImage').click();
					}
						document.getElementById('inputVideo').addEventListener('click', openDialog2);
					function openDialog2() {
						document.getElementById('openVideo').click();
					}
				}
				
				
	$(document).ready(function(){
		$('.editposting').slideUp();
		
		$(".editmypost").click(function(){
			var id_post = $(this).attr('id');
			$('.'+id_post).slideToggle();
		});
		
		$(".deletepost").click(function(){
			var deletemypost = confirm('Are you sure ?');
			var id_post = $(this).attr('id');
			if(deletemypost)
			{
				$.post("<?php echo base_url(); ?>"+'index.php/Welcome/delete_post',{idpost:id_post},function(value){				
					window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/profile';
				});
			}
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
  <body onload="setup()">
 <?php echo form_open_multipart('Welcome/home'); ?>
  <header>
    <div class="container">
       <img src=<?php echo base_url("img/logo.png");?> height=50 class="logo col-sm-2" alt="">
      <form class="form-inline">
         <div class="form-group col-sm-offset-4 col-sm-6">
		  <ul class="nav navbar-nav">
            <li ><a href="login_page">Home</a></li>
            <li><a href="member" type='button' name='member'>Friends</a></li>
            <li><a href="group">Groups</a></li>
            <li><a href="photos.php">Photos</a></li>
            <li class="user"><a href="profile" style="background:none; color:white;"><?php echo $this->session->userdata('myaccount'); ?></a></li>
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
    </nav>

    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-8">
		   <?php foreach($mydata as $row){ ?>
            <div class="profile">
              <h1 class="page-header"><?php echo $row->name; ?></h1>
              <div class="row">
                <div class="col-md-4 profilepicture">
				 <img src=<?php echo base_url("ppicture/".$row->pp);?> class="img-thumbnail" alt="">        
				  <?php  form_upload('ppicture','asdasd',' class="ppicture" ');?><br>
                   
                </div>
                <div class="col-md-8">
                  <table>
                    <tr><td><strong>First Name </td><td> : </strong><?php $name = explode(" ",$row->name); echo $name[0]; ?></td></tr>
                    <tr><td><strong>Last Name </td><td> : </strong><?php echo $name[1]; ?></td></tr>
                    <tr><td><strong>Birth of Date </td><td> : </strong><?php echo $row->birth; ?></td></tr>
                    <tr><td><strong>Address </td><td> : </strong><?php echo $row->alamat; ?></td></tr>
                    <tr><td><strong>Telephone </td><td> : </strong><?php echo $row->email; ?></td></tr>
                    <tr><td><strong>Sex </td><td> : </strong><?php echo $row->gender; ?></td></tr>
                  </table>
				  <br>
				  <button type="submit" class="btn btn-default submit">Change Profile</button>
                     
                </div>
              </div>
            <hr></hr>
            </div>
			
          </div>
		  
		  <?php } ?>
		    <div class="col-md-8">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">My Posting</h3>
              </div>
            </div>
	
			<?php foreach($allposting as $row){?>
            <div class="panel panel-default post">
              <div class="panel-body">
                 <div class="row">
				 <?php $posting = $row->id_post; ?>
                   <div class="col-sm-2">
                     <a href="otherprofile" class="post-avatar thumbnail"><img src=<?php echo base_url("ppicture/".$row->pp);?> alt=""><div class="text-center"><?php echo $row->name ?></div></a>
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
                     <p class="post-actions"><span class="editmypost reactchoose" id=<?php  echo $row->id_post; ?> >Edit</span> | <a href="profile" class="deletepost" id=<?php echo $row->id_post;?> >Delete</a><span style="margin-left:38%;">
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
                     
                     <div class="comments">
					  
					  <div class="form-group editposting <?php echo $row->id_post; ?>" style="padding:10px;">
					  <?php echo form_open_multipart('Welcome/edit_post'); ?>
					  <p>
                        <input type="text" class="form-control" id=<?php echo $row->username; ?> name='edittext' placeholder="change caption here">
						<div class="btn-toolbar">
						
						<input type="hidden" name="idpost" value=<?php echo $row->id_post;?> >
						<input type="hidden" name="textpast" value=<?php echo $row->text;?> >
						<input type="hidden" name="postimg" value=<?php echo $row->attach;?> >
						
						<input type="file" name="openImage" id="openImage"  style="display:none;" accept="image/*">
						<button type="button" name="inputImage" id="inputImage" class="btn btn-default"><i class="fa fa-file-image-o"></i>Image</button>
						<input type="file" name="openVideo" id="openVideo"  style="display:none;" accept="video/*">
						<button type="button" name="inputVideo" id="inputVideo" class="btn btn-default"><i class="fa fa-file-video-o"></i>Video</button><button style="margin-left:62%;" class="btn btn-default edit submit">Edit</button>
						</p>
						</div>
						  <?php echo form_close(); ?>
					  </div>
						
						
					 <!-- data di for terus dipilah dengan if milik siapa komen tsb -->
					 <?php $postnow = $row->id_post; foreach($percomment as $rowss) { if($rowss->id_post == $postnow){ ?>
                       <div class="comment">
                         <a href="otherprofile" class="comment-avatar pull-left"><img src=<?php echo base_url("ppicture/".$rowss->pp);?> alt=""></a>
                         <div class="comment-text">
                           <?php echo $rowss->text;?>
						   <div class='datetime'> at: <?php echo $rowss->date;?> | by: <a href="otherprofile"> <?php echo $rowss->name;?></a> </div>
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
          <div class="col-md-4">
            <div class="panel panel-default friends">
              <div class="panel-heading">
                <h3 class="panel-title">My Friends</h3>
              </div>
              <div class="panel-body">
                <ul>
				
				 <?php if($friend != null){ foreach($friend as $row){?>
                  <li><a href="otherprofile" class="thumbnail"><img src=<?php echo base_url("ppicture/".$row[0]->pp);?> alt=""></a></li>
				 <?php }}?>
				 
                </ul>
                <div class="clearfix"></div>
                <a class="btn btn-primary mybutton" href="member">View All Friends</a>
              </div>
            </div>
            <div class="panel panel-default groups">
              <div class="panel-heading">
                <h3 class="panel-title">Latest Groups</h3>
              </div>
              <div class="panel-body">
                <div class="group-item">
                  <img src="img/group.png" alt="">
                  <h4><a href="#" class="">Sample Group One</a></h4>
                  <p>This is a paragraph of intro text, or whatever I want to call it.</p>
                </div>
                <div class="clearfix"></div>
                <div class="group-item">
                  <img src="img/group.png" alt="">
                  <h4><a href="#" class="">Sample Group Two</a></h4>
                  <p>This is a paragraph of intro text, or whatever I want to call it.</p>
                </div>
                <div class="clearfix"></div>
                <div class="group-item">
                  <img src="img/group.png" alt="">
                  <h4><a href="#" class="">Sample Group Three</a></h4>
                  <p>This is a paragraph of intro text, or whatever I want to call it.</p>
                </div>
                <div class="clearfix"></div>
                <a href="#" class="btn btn-primary mybutton">View All Groups</a>
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
