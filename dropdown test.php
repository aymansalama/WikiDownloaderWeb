<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<script type="text/javascript">
			//function to get the languages available for the source selected by the user
			//executed every time user changes the dropdown selection for date
			$(document).ready(function(){
				$("#sources").change(function(){
					var source = $("#sources").val();
					console.log(source);
					$.ajax({
						type:"post",
						url:"getlanguages.php",
						data: "source=" + source,
						success: function(data){
							$("#languages").html(data);
						} 
					});
				});
			});

			//function to get the dates available for the selected source and language
			//executed everytime user changes the dropdown selection for language
			$(document).ready(function(){
				$("#languages").change(function(){
					var language = $("#languages").val();
					var source = $("#sources").val();
					var langsource = language + source;
					console.log(langsource);
					$.ajax({
						type:"post",
						url:"getdates.php",
						data: "langsource=" + langsource,
						success: function(data){
							$("#dates").html(data);
						} 
					});
				});
			});
		</script>
	</head>

	<body>
		<form action="/action_page.php">
			<select name="sources" id="sources">
				<option>-select source-</option>
				<option value="wiki">Wikipedia</option>
				<option value="wikimedia">Wikimedia</option>
			</select>
				
			<br><br>

			<select name="languages" id="languages">
				<option>-select language-</option>
			</select>
			
			<br><br>

			<select name="dates" id="dates">
				<option>-select date-</option>
			</select>
			
			<br><br>
			
			<input type="submit">			
		</form>
	</body>
</html>