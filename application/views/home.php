
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UnSoS - HomePage</title>

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
               // document.getElementById('fileid').addEventListener('change', submitForm);
                //function submitForm() {
                 //   document.getElementById('formid').submit();
                //}
            }
	$(document).ready(function(){
		
		$('.reaction').slideUp();
		
		$('.reactchoose').click(function(){
			var title = $(this).attr('value');
			$('#'+title).slideDown();
		});
		
		$('.emo').click(function(){
			var myemo = $(this).attr('value');
			alert(myemo);
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
		
		$(".add").click(function(){
			var idpost = $(this).attr('id');
			var komen = $("."+idpost).val();
			var reply = $("."+idpost).attr('id'); 
			
			$.post("<?php echo base_url(); ?>"+'index.php/Welcome/add_comment',{simpankomen:komen,simpanpost:idpost,simpanreply:reply},function(value){	
				window.location = "<?php echo base_url(); ?>"+'index.php/Welcome/login_page';
			});
			
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
            <li class="actived"><a href="login">Home</a></li>
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


	
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
              <div class="panel-body friends">
			  Do you know them ?


					<?php if($alluser != null){
            echo "<ul>";
            foreach($alluser as $row){ ?>


						<li class="otherfriend" id="<?php echo $row[0]->username ?>"><a class="thumbnail"><img src=<?php echo base_url("ppicture/".$row[0]->pp);?> width='100' alt=""></a></li>
					<?php }
					echo '<li><a class="btn btn-primary mybutton" href="#"> More Suggested > </a></li>';

          echo "</ul>";}
          else {
            echo "<br><br><strong>No friend suggestions</strong>";
          }?>

            </div>
        </div>
    </nav>

    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Wall</h3>
              </div>
			  <?php echo form_open_multipart('Welcome/home'); ?>
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
                     <p class="post-actions"><?php if($row->username == $this->session->userdata('myusername')){?><a href="#">Edit / Delete</a> - <?php } ?><span class="reactchoose" value=<?php echo $row->id_post ?>>Reaction</span> - <a href="#">Report</a> <span style="margin-left:55%;"><?php echo count($row->disukai); ?> Likes</span></p>
                     
					 <div class="comment-form ">
                       <form class="form-inline">
					   <div class="reaction" id=<?php echo $row->id_post ?>><img src=<?php echo base_url("emo/like.png");?> class="emo" value="like" width=40> <img src=<?php echo base_url("emo/love.png");?> class="emo" value="love" width=40> <img src=<?php echo base_url("emo/laugh.png");?> class="emo" value="laugh" width=40> <img src=<?php echo base_url("emo/wow.png");?> class="emo" value="wow" width=40> <img src=<?php echo base_url("emo/sad.png");?> class="emo" value="sad" width=40> <img src=<?php echo base_url("emo/angry.png");?> class="emo" value="angry" width=40> 
						</div>
                        <div class="form-group">
                          <input type="text" class="form-control <?php $postnow = $row->id_post; echo $row->id_post;?>" id=<?php echo $row->username; ?> placeholder="enter comment">
                        </div>
                        <button type="button" id=<?php echo $row->id_post;?> class="btn btn-default add">Add</button>
						</form>
                     </div>
                     <div class="clearfix"></div>

                     <div class="comments">


					 <!-- data di for terus dipilah dengan if milik siapa komen tsb -->
					 <?php foreach($percomment as $row) { if($row->id_post == $postnow && $row->id_user == $this->session->userdata('myusername') ){ ?>
                       <div class="comment">
                         <a href="otherprofile" class="comment-avatar pull-left"><img src=<?php echo base_url("ppicture/".$row->pp);?> alt=""></a>
                         <div class="comment-text">
                           <?php echo $row->text;?>
						   <div class='datetime'> at: <?php echo $row->date;?> | by: <a href="otherprofile"> <?php echo $row->name;?></a>   </div>
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
            <div class="panel panel-default groups">
              <div class="panel-heading">
                <h3 class="panel-title">Latest Groups</h3>
              </div>
              <div class="panel-body">
                <div class="group-item">
                  <img src="img/group.png" alt="">
                  <h4><a href="group_prof" class="">Sample Group One</a></h4>
                  <p>This is a paragraph of intro text, or whatever I want to call it.</p>
                </div>
                <div class="clearfix"></div>
                <div class="group-item">
                  <img src="img/group.png" alt="">
                  <h4><a href="group_prof" class="">Sample Group Two</a></h4>
                  <p>This is a paragraph of intro text, or whatever I want to call it.</p>
                </div>
                <div class="clearfix"></div>
                <div class="group-item">
                  <img src="img/group.png" alt="">
                  <h4><a href="group_prof" class="">Sample Group Three</a></h4>
                  <p>This is a paragraph of intro text, or whatever I want to call it.</p>
                </div>
                <div class="clearfix"></div>
                <a href="Group" class="btn btn-primary mybutton">View All Groups</a>
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
