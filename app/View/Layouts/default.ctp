<!DOCTYPE html>
<html>
<head>
	<title>Listagem de Emails</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('cake.generic');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Listagem de Emails no último segundo</h1>
		</div>
		<div id="content">

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			
		</div>
	</div>

</body>
<script>

setInterval(ajaxCall, 1000);
function ajaxCall(){
    $.ajax({
        url: "email/ajax",
        cache: false
    })  
    .done(function( html ) {
        $( "#lista_de_email" ).html( html );
    });
}    

</script>
</html>
