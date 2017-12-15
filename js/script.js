
	
	var isUp=true;
	var counter=1;
	var message='';
	
	var youraccount='';
	$(document).ready(function(){

		$(".contains-chat").slideUp();
		$(".chatnow").slideUp();

		$(".quit").click(function(){
			var index = $(this).index('.quit')+1;
			counter=index;
			$(".chatnow"+counter).css('z-index',0);
			$(".chatnow"+counter).css('opacity',0);
						
		});
		
		$(".contact").click(function(){
			var title = $(this).text();
			$(".chatnow"+counter).css('opacity',1);
			$(".quit").css('opacity',1);
			$(".chatnow"+counter).css('z-index',2);
			$(".chatnow"+counter).ready(function(){
					$(".chatnow"+counter+" > .header-mychat").html("<img src='img/user.png' class='img-thumbnail' id="+title+"width=50 alt=''> &nbsp"+title);
			});
			
		
		});
		
		$(".quit").click(function(){
				$(this).css('opacity',0);
		});
		
		$(window).scroll(function() {
			var scroll = $(window).scrollTop();
			
			$('.mSidebar').css("position", "fixed");
			$('.myChat').css("position", "fixed");
			$('.chatnow1').css("position", "fixed");
			$('.chatnow2').css("position", "fixed");
			
			if(isUp)
				{
					$('.mSidebar').css("top", 700);
					$('.mSidebar').css("height", 40);
					
				}
			else
				{
					$('.mSidebar').css("top", 200);
					$('.mSidebar').css("height", 550);
					$(".chatnow1").css("height", 550);
					$(".chatnow2").css("height", 550);

				}
		
		});
		
		$(".header-chat").click(function(){
			
			$(".contains-chat").slideToggle();
			
			if(isUp)
				{
					$('.mSidebar').css("top", 200);
					$('.mSidebar').css("height", 550);
					isUp=false;
				}
			else
				{
					$('.mSidebar').css("top", 700);
					$('.mSidebar').css("height", 40);
					isUp=true;
				}
			
		});
		
	});
	