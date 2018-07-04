<script type="text/javascript">
	// trucks
	$(document).on("click" , "#total-trucks" , function(){
    	$(".panel-trucks").toggle();

    	$(".panel-active-trucks").attr("style", "display: none");
    	
        $(".panel-active-trucks").parent().addClass("hidden");

        if($(".panel-trucks").parent().hasClass("hidden")){
            $(".panel-trucks").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#active-trucks" , function(){
    	$(".panel-active-trucks").toggle();

    	$(".panel-trucks").attr("style", "display: none");
    	
        $(".panel-trucks").parent().addClass("hidden");

        if($(".panel-active-trucks").parent().hasClass("hidden")){
            $(".panel-active-trucks").parent().removeClass("hidden");
        }
    });

    // trailers
    $(document).on("click" , "#total-trailers" , function(){
    	$(".panel-trailers").toggle();

    	$(".panel-active-trailers").attr("style", "display: none");
    	
        $(".panel-active-trailers").parent().addClass("hidden");

        if($(".panel-trailers").parent().hasClass("hidden")){
            $(".panel-trailers").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#active-trailers" , function(){
    	$(".panel-active-trailers").toggle();

    	$(".panel-trailers").attr("style", "display: none");
    	
        $(".panel-trailers").parent().addClass("hidden");

        if($(".panel-active-trailers").parent().hasClass("hidden")){
            $(".panel-active-trailers").parent().removeClass("hidden");
        }
    });

    // reports
    $(document).on("click" , "#today-reports" , function(){
    	$(".panel-today-reports").toggle();

    	$(".panel-def-und").attr("style", "display: none");
    	$(".panel-fixed-today").attr("style", "display: none");
    	
        $(".panel-def-und").parent().addClass("hidden");
        $(".panel-fixed-today").parent().addClass("hidden");

        if($(".panel-today-reports").parent().hasClass("hidden")){
            $(".panel-today-reports").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#defects-under-maintenance" , function(){
    	$(".panel-def-und").toggle();

    	$(".panel-today-reports").attr("style", "display: none");
    	$(".panel-fixed-today").attr("style", "display: none");
    	
        $(".panel-today-reports").parent().addClass("hidden");
        $(".panel-fixed-today").parent().addClass("hidden");

        if($(".panel-def-und").parent().hasClass("hidden")){
            $(".panel-def-und").parent().removeClass("hidden");
        }
    });
     $(document).on("click" , "#fixed-reports" , function(){
    	$(".panel-fixed-today").toggle();

    	$(".panel-today-reports").attr("style", "display: none");
    	$(".panel-def-und").attr("style", "display: none");
    	
        $(".panel-today-reports").parent().addClass("hidden");
        $(".panel-def-und").parent().addClass("hidden");

        if($(".panel-fixed-today").parent().hasClass("hidden")){
            $(".panel-fixed-today").parent().removeClass("hidden");
        }
    });
	
</script>
<div class="container-fluid">
    <div class="side-body padding-top">
        <div class="container">
        	<h1 class="text-left">Welcome, <?php echo $this->data['session_data']->display_name;?>!</h1>
        	<div class="card">
	    		<div class="card-body">
	    			
	    		</div>
	    	</div>
        </div>
        <div class="text-center">
			<p class="help-block">For help with setting up <?php echo $application_name; ?>, check out the <a href="#" class="link-style"> Getting Started Guide</a></p>
		</div>
    </div>
</div>