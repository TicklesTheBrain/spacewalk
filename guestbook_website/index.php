<!DOCTYPE html>
<html>
<body>
	<div class="entry">
		<h2>Leave a Message</h2>
		<form action="submit.php" method="post">
			<input type="text" id="name" name="name" placeholder="enter name or leave empty for anon"><br><br>
			<textarea id="message" name="message" placeholder="enter your message"></textarea><br><br>
			<input type="submit" name="submit" value="Submit">
		</form>
		<div>

			<?php
				date_default_timezone_set('Europe/Vilnius');
				$data = json_decode(file_get_contents('data.json', true), true);
				foreach ($data as $message){
					$name = $message['name'];
					$text = $message['message'];
					$timestamp = $message['timestamp'];
					?>
					<div class= "message_header">
						<span class = "time"><?= date("Y-m-d H:i:s", $timestamp) ?> </span>
						<span class = "name"><?= htmlspecialchars( $name) ?></span>
					</div>
					<div class= "message_content">
						<span class="message_text"><?= htmlspecialchars( $text)?></span>
					</div>
					<?php
				}
			?>           
		</div>
	</div>
</body>
</html>