<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript">



$( document ).ready(function() {
setTimeout("document.getElementById('time_remaining'). innerHTML ='5s'",1000);
setTimeout("document.getElementById('time_remaining'). innerHTML ='4s'",2000);
setTimeout("document.getElementById('time_remaining'). innerHTML ='3s'",3000);
setTimeout("document.getElementById('time_remaining'). innerHTML ='2s '",4000);
setTimeout("document.getElementById('time_remaining'). innerHTML ='1s '",5000);
setTimeout("changePage()",5000);
});

function changePage() {
    //code
    $('#form_transition').submit();
}

</script>
<style>
  form{
    display:none;
  }
</style>
</head>

<body>

<div>.......<h3 id='time_remaining'>6s</h3>......</div>
<form id='form_transition' action="index.php" method="post">
  <input type="hidden" name="new_transition" value="1">
</form>

</body>

</html>
