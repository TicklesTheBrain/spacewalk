<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Smaukst</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <section>
        <div
            class="d-sm-flex d-md-flex d-lg-flex d-xxl-flex justify-content-sm-center justify-content-md-center justify-content-lg-center justify-content-xxl-center logo_container">
            <img class="img-fluid" src="assets/img/smaukst.png"></div>
    </section>
    <section>
        <div
            class="d-flex d-sm-flex d-md-flex flex-grow-0 flex-shrink-0 justify-content-center justify-content-sm-center justify-content-md-center search_form_container">
            <form action = "index.php" method="get"><input type="text" class="search_field" name="q"><button class="btn btn-primary search_button"
                type="submit" value="search" name = "submit">Search!</button></form></div>
    </section>
    <section>
    <?php if (isset($_GET['submit'])) { ?>
        <div class="d-md-flex justify-content-md-start result">
				<h2>
				Results
				</h2>
        </div>
	    <?php
            function processBold($textToProcess, $queryWords){
                $processed = $textToProcess;
                foreach ($queryWords as $word){
                    $processed = preg_replace("/\b($word)\b/i",'<b>\1</b>', $processed);
                }
                return $processed;
            }

			if (isset($_GET['q'])){
				$db = json_decode(file_get_contents('search_db.json',true), true)['db'];
				$results = array();
                $trimmedQ = substr($_GET['q'], 0, 1000);
				$queryWords = explode(" ", strtolower($trimmedQ));
				foreach ($queryWords as $word){
					foreach ($db as $uniqueMatch){
						if (in_array($word, $uniqueMatch['matches']) && !in_array($uniqueMatch['content'], $results)){
							array_push($results, $uniqueMatch['content']);
						}
					}
				}
				if (sizeof($results) == 0){ ?> 
					<div class="d-md-flex justify-content-md-start result">
				        <p>No results found</p>
                    </div>
            <?php } else
                $counter = 1;
				foreach ($results as $result){ ?>
                    <div class="d-md-flex justify-content-md-start result">
                    <div class="number_container"><span><?= $counter . "." ?></span></div> <div class="result_content">
                    <?php if (isset($result['link'])) { ?>
                        <a href=<?= $result['link'] ?> class="result_link"><?= processBold($result['linkTitle'], $queryWords) ?></a> <?php
                    }
                    if (isset($result['textbox'])){
                        ?><p class="result_textbox"><?= processBold($result['textbox'], $queryWords) ?></p> <?php
                    }
                    if (isset($result['pics'])){
                        ?> <div class="result_pictures_container"> <?php
                        foreach($result['pics'] as $pic) {
                            ?> <a href=<?= $pic ?> ><img class="result_picture"
                            src=<?= $pic ?> ></a> <?php
                        } ?>
                        </div>
                        <?php
                    } ?>
                    </div></div>
                <?php
                $counter++;
                }
            }
        }
       ?>
    </section>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>