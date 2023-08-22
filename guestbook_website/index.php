<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<title>Guestbook</title>
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
	<header><link rel="stylesheet" type="text/css" href="assets/css/style.css"></header>
	<?php
		date_default_timezone_set('Europe/Vilnius');
		$idToUse = 1;
		$messages = array_reverse(json_decode(file_get_contents('messages.json', true), true));
		$data = json_decode(file_get_contents('../data.json', true), true)[$idToUse];
		$last = isset($data['complete']);
		$title = $data['title'];
		$location = $last ? null : $data['location'];
		$gps = $last ? null : $data['gps'];
		$completeText = $last ? $data['complete'] : null;
	?>
	<div class="title">
		<h2 style="padding-bottom: 0px;margin-top: 11px;margin-bottom: 12px;margin-left: 29px;margin-right: 29px;text-align: center;">
		<?= $title ?>
	</h2>
	</div>
	<div>
		<div class="header_info">
			<?php if (!$last){ ?>
				<p class="nextStage">The next stage is at <?= $location?></p>
				<p class="nextStage gps" style="background: url(&quot;assets/img/location.png&quot;) left / contain no-repeat, rgba(233,235,115,0.5);padding-left: 43px;"><?= $gps ?></p>
			<?php }
			else{ ?>
				<p class="nextStage complete"><?= $completeText ?></p>
			<?php
			} ?>
			<p>We invite you to leave a message to record your time below!</p>
		</div>
	</div>
	<div class="entry input_box">
		<form action="submit.php" method="post"><input type="text" id="name" class="name_input" maxlength="50" name="name" placeholder="Name or team name">
			<div class="d-md-flex justify-content-md-center align-items-md-end content_input"><textarea id="message" maxlength="256" name="message" placeholder="Leave a message"></textarea><input type="submit" name="submit" value="Submit" style="min-width: 50px;margin-left: 0px;"></div>
		</form>
	</div>
	<div class="d-flex d-xxl-flex flex-column flex-nowrap justify-content-xxl-center align-items-xxl-start messages_container">
		<?php
			foreach ($messages as $message) {
						$name = $message['name'];
						$text = $message['message'];
						$timestamp = $message['timestamp'];
					?>
					<div class="single_message">
						<div class="message_header" style="margin: 3px;"><span class="name"><?= htmlspecialchars($name) ?></span><span class="time"><?= date("Y-m-d H:i:s", $timestamp) ?> </span></div>
						<div class="text-break message_content" style="margin: 3px;"><span class="message_text">><?= htmlspecialchars($text) ?></span></div>
					</div>
					
			<?php
			}
		?>
	</div>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>