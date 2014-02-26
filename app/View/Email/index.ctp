
		<div id="lista_de_email">
                    <ul>
                    <?php foreach( $emails as $email ){ ?>
                        <li><?=$email['Email']['email']?></li>
                    <?php } ?>    
                    </ul>
		</div>


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