<script>
	$(function(){
		$(".button-action").on("click",function(e){
				// var bottom = ($('html').height()*10/100)+$('.group-action').height()-10;
				// bottom = parseInt(bottom);
				// $('.group-action').css("bottom",bottom+'px');
        $('.group-action').toggle('500');

        event.stopPropagation();
		})

        $("#social-footer-button").on("click",function(e){
            $('#social-footer-popup').toggle('500');
        })
	})
</script>