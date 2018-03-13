
<!DOCTYPE html>
	<?php $b64image = base64_encode(file_get_contents('https://api.qrserver.com/v1/create-qr-code/?data=http://zcilok.cloudapp.net/ci/index.php/test/index/1234567890sfdfsdf&format=jpeg'));
?>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
<style>
	  #content{
			display:inline-block;
			max-width: 600px;
			margin:30px;
	  }
	  #img_con{
			border: 2px solid red;
			width:250px;
			height:250px;
			display: inline-block;
			position: relative;
			bottom:30px;
			margin : 30px;
	  }
	  table {
			border-collapse: collapse;
			width: 100%;
			font-size: 20px;
	  }

	  tr,th,td {  
			padding-top: 8px;
			padding-bottom: 8px;
			padding-left: 5px;
			padding-right:5px;
			text-align: center;
			border-bottom: 5px solid #ddd;	
	  }

	  tr:hover{background-color:#f5f5f5}
</style>
</head>
<script src="<?=base_url('application/third_party/jspdf.js')?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript">
      $(document).ready(function(){ 
            var specialElementHandlers = {
                '#editor': function (element,renderer) {
                    return true;
                }
            };
         $('#cmd').click(function () {
                var doc = new jsPDF('p', 'pt', 'a4');
				
				doc.setFont("helvetica");
				  doc.setFontType("bold");
				  doc.text(40, 50, 'This is helvetica bold.');
	  
                doc.fromHTML($('#content').html(), 40, 60, {
                    'width': 50,
					'elementHandlers': specialElementHandlers
                });
				
				doc.setFont("helvetica");
				  doc.setFontType("bold");
				  doc.text(40, 270, 'This is helvetica bold.');
				  
				var show="data:image/jpeg;base64,<?=$b64image?>";
				doc.addImage(show, 'JPEG', 40, 300, 0, 0);
				
				
				
				 var temp = doc.output('datauristring');
				 
				 var pdf=temp.replace('data:application/pdf;base64,','');
				 
				 //pdf=pdf.replace('+','%20');
				  //console.log(pdf);
				  
				

				
				 var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      console.log(xhttp.responseText);
    }
  };
  xhttp.open("POST", "<?=site_url('pdfprocess/createPdf')?>", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("pdf="+pdf);

			

//doc.save('sample-file.pdf');
            });  
        });
	  
	
</script>
<body id="target">
	  <div id="content">  
	  <table>
			<tr>
					<td>NAME:</td>
					<td>Smith Borde</td>
			</tr>
			<tr>
				  <td>GAME:</td>
				  <td>Team1 VS  Team2</td>
			</tr>
			<tr>
				  <td>DATE:</td>
				  <td>03/13/2015 4:00PM</td>
			</tr>
			<tr>
				  <td>LOCATION:</td>
				  <td>XXXX XXXX XXXX XXXX XXXX  XXXX XXXX XXXX XXXX XXXX XXXX XXXX XXXX XXXX XXXX  XXXX XXXX XXXX XXXX XXXX</td>
			</tr>
			<tr>
				  <td>PRICE:</td>
				  <td>$25</td>
			</tr>
	  </table>
	  </div>
	  <div id="img_con">
			<img id="myImg" src="https://api.qrserver.com/v1/create-qr-code/?data=http://zcilok.cloudapp.net/ci/index.php/test/index/123dfgfdsg2342343234;size=250*250">	
	  </div>
     <button id="cmd">generate PDF</button>
    </body>
	

</html>