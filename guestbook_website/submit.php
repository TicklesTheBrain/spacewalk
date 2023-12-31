<?php
// check if form was submitted
if(isset($_POST['submit'])){
    // get form data
    $name = $_POST['name'];
    $message = $_POST['message'];
    $timestamp = time();

    // if name is empty, set it to "anon"
    if(empty($name)){
        $name = "anon";
    }

    // if message is not empty
    if(!empty($message)){
        // get existing json data
        if (file_exists('messages.json') && filesize('messages.json') > 0) {
            $data = json_decode(file_get_contents('messages.json'), true);
        } else {
            $data = array();
        }
        $flag = true;
        // loop through previous submissions
        foreach ($data as $submission) {
            // check if current submission matches any previous submissions in both name and message
            if ($submission['name'] == $name && $submission['message'] == $message) {
                $flag = false;
                break;
            }
        }
        // if current submission does not match any previous submissions, append it to the JSON file
        if ($flag) {
            array_unshift($data, array("name" => $name, "message" => $message, "timestamp" => $timestamp));
            file_put_contents('messages.json', json_encode($data));
        }
    }
}
header("Location: index.php");
exit();
