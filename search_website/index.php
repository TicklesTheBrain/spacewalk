<html>
	<head>
	</head>
	<body>
		<script>setInterval(function(){location.reload(true);}, 50000);</script>
		<h1>
			Simo paieškėlė
		</h1>
		<form action = "index.php" method = "get">
			<input name = "q" type="text">
			<input type="submit" name="submit" value = "search">
		</form>
	  
			<?php if (isset($_GET['submit'])) { ?>
				<h2>
				Results
				</h2>
			<?php }
			if (isset($_GET['q'])){
				$db = json_decode(file_get_contents('search_db.json',true), true)['db'];
				$results = array();
				$queryWords = explode(" ", $_GET['q']);
				foreach ($queryWords as $word){
					foreach ($db as $uniqueMatch){
						if (in_array($word, $uniqueMatch['matches']) && !in_array($uniqueMatch['content'], $results)){
							array_push($results, $uniqueMatch['content']);
						}
					}
				}
				if (sizeof($results) == 0){
					echo "No results";
				} else
				foreach ($results as $result){ ?>
					<div><?= $result?></div>

					<?php
				}}
			?>

	</body>
</html>