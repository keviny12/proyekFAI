
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UnSoS - GroupProfilePage</title>

   <!-- Bootstrap core CSS -->
    <link href=<?php echo base_url("css/bootstrap.css");?> rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href=<?php echo base_url("css/style.css");?> rel="stylesheet">
    <link href=<?php echo base_url("css/font-awesome.css");?> rel="stylesheet">
 <script type="text/javascript" src="<?php echo base_url('js/jquery.js');?>"></script>
 <script>
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
	var friendctr = 0;
	$("#addmember-ui").hide();
	$("#changeprofile-ui").hide();
	$("#btn-changeprofile").click(function(){
		  $("#addmember-ui").hide();
		  $("#changeprofile-ui").show();
		  $("#successmsg").hide();
		});
		$("#btn-addmember").click(function(){
		  $("#addmember-ui").show();
		  $("#changeprofile-ui").hide();
		  $("#successmsg").hide();
		});
		$("img").click(function(){
		  if ($(this).attr("name"))
		  {
			if ($(this).attr("disabled"))
			{
			  friendctr--;
			  $(this).removeAttr("disabled");
			  $(this).css("border-color","#ddd");
			  $(this).css("border-width","1px");
			  $(this).css("opacity",1);
			  var username = $(this).attr("name");
			  $.ajax({
				type:"POST",
				url:"<?php echo base_url(); ?>"+'index.php/Welcome/deselect_group_request',
				data:{reqid:username},
				success:function(value){
				}
			  });
			}
			else
			{
			  friendctr++;
			  $(this).css("border-color","blue");
			  $(this).css("border-width","3px");
			  $(this).css("opacity",0.6);
			  $(this).attr("disabled","disabled");
			  var username = $(this).attr("name");
			  $.ajax({
				type:"POST",
				url:"<?php echo base_url(); ?>"+'index.php/Welcome/select_group_request',
				data:{reqid:username},
				success:function(value){
				}
			  });
			}
		  }
		});
		$("button[name='confirmgroupreq']").click(function(e){
		  if (friendctr == 0)
		  {
			e.preventDefault();
			$("#notice").html("<strong>No friend selected</strong>");
		  }
		});
	});
 </script>
  </head>
	<script type="text/javascript" src="<?php echo base_url('js/script.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
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
		  <li> <div class="form-group searchbar ">
              <input type="text" class="form-control" placeholder="search">
			    </div>
				<button type="submit" class="searchbtn" style="border:none;background:none;"><i class="fa fa-search"></i></button>
			 </li>
            <li ><a href="login_page">Home</a></li>
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
<?php echo form_close(); ?>
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
		<td>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  </td>
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
	<?php echo form_open_multipart('Welcome/group_manage'); ?>

    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-8">
		   <?php foreach($othergroup as $row){ ?>
            <div class="profile">
              <h1 class="page-header"><?php echo $row->group_name; ?></h1>
              <div class="row">
                <div class="col-md-4 profilepicture">
                  <img style="height:200px;"src=<?php echo base_url("gpicture/".$row->group_img);?> class="img-thumbnail"  alt="">        
				  <?php  form_upload('gpicture','asdasd',' class="ppicture" ');?><hr><br>
                   
                </div>
                <div class="col-md-8">
                  <table>
                    <tr><td><strong>Group Name </td><td> : </strong><?php echo $row->group_name; ?></td></tr>
                    <tr><td><strong>Description </td><td> : </strong><?php echo $row->caption; ?></td></tr>
                    <tr><td><strong>Established on </td><td> : </strong><?php echo $row->date; ?></td></tr>
					<?php echo form_hidden("idgroup",$row->id_group); ?>
                  </table>
				  <br>
				  <button id="btn-changeprofile" type="button" class="btn btn-default submit">Change Profile</button>
				  <button id="btn-addmember" type="button" class="btn btn-default submit">Add New Member</button>
         
                </div>
              </div>
			  <div class="row" id="successmsg">
                <div class="col-md-8"><strong><?php echo $this->session->flashdata("scsmsg");?></strong></div>
              </div>
              <div class="row" id="addmember-ui">
                <strong style="font-size: 16pt">Select friends to add :</strong><br><br>
                <div class="col-md-8" id="friends-toadd">
                  <?php
                    foreach ($myfriends as $row)
                		{
                			echo "<img style='width:75px;height:75px;' id='friend-thumb' name='".$row[0]->username."' src='".base_url("ppicture/").$row[0]->pp."' data-toggle='tooltip' data-placement='top' title='".$row[0]->name."' class='img-thumbnail' alt='' />&nbsp;";
                		}
                  ?>
                  <br><br><div id="notice"></div><br>
                 <button name="confirmgroupreq" type="submit" class="btn btn-default submit">Confirm</button>
                </div>
              </div>
              <div class="row" id="changeprofile-ui">
                <div class="col-md-8">
                  <strong style="font-size: 16pt">Edit Group Profile</strong><br><br>
                  <?php foreach ($othergroup as $row) { ?>
                  <strong>Group Picture</strong><hr style="margin-top:2%;"><?php echo form_upload("editgrouppicture","class='form-control'"); ?><br><br>
                  <strong>Group Name</strong><hr style="margin-top:2%;"><?php echo form_input("editgroupname",$row->group_name,"class='form-control'"); ?><br><br>
                  <strong>Description</strong><hr style="margin-top:2%;"><?php echo form_textarea("editdescription",$row->caption,"class='form-control'"); ?><br><br>
                  <div id="errmsg"><strong><?php echo $this->session->flashdata("errmsg");?></strong></div><br>
                  <button name="savechanges" type="submit" class="btn btn-default submit">Save Changes</button>
                  <?php }?>

                </div>
              </div>
              <br><br>
              <div class="row">
               	    <div class="col-md-12">
              <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Wall</h3>
              </div>
              <div class="panel-body">
                  <div class="form-group">
                    <textarea class="form-control" placeholder="Write on the wall" name="comment"></textarea>
                  </div>
				     <div class="btn-toolbar">
					<input type="file" name="openImage" id="openImage"  style="display:none;" accept="image/*">
                      <button type="button" name="inputImage" id="inputImage" class="btn btn-default"><i class="fa fa-file-image-o"></i>Image</button>
					<input type="file" name="openVideo" id="openVideo" style="display:none;" accept="video/*">
                      <button type="button" name="inputVideo" id="inputVideo" class="btn btn-default"><i class="fa fa-file-video-o"></i>Video</button>
                    </div>
				 <div class="pull-left">
					<p><strong><?php echo $this->session->flashdata("error"); ?></strong></p><br>
				 </div>
                 <div class="pull-right">
					 <input type="submit" class="btn btn-default submit" name="postBTN" value="Submit">
                  </div>

              </div>
			  <?php echo form_close(); ?>
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
                     <p class="post-actions"><span class="editmypost reactchoose" id=<?php  echo $row->id_post; ?> >Edit</span> | <a href="profile" class="deletepost" id=<?php echo $row->id_post;?> >Delete</a><span style="margin-left:35%;">
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
              </div>
            </div>
		   <?php } ?>
          </div>
          <div class="col-md-4">
            <div class="panel panel-default friends">
              <div class="panel-heading">
                <h3 class="panel-title">Group Member</h3>
              </div>
              <div class="panel-body">
                <ul>
				  <?php foreach ($groupmembers as $row){
                    echo "<li><a href=# class='thumbnail'><img src='".base_url("ppicture/").$row->pp."' data-toggle='tooltip' data-placement='top' title='".$row->name."' style='width:100px;height:100px;' alt='' /></a></li>";
                  }
                  ?>
                </ul>
                <div class="clearfix"></div>
                <a class="btn btn-primary mybutton" href="#">View All Members</a>
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
