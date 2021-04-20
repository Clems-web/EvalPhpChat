<?php

// Connection to database

$db = new PDO('mysql:host=localhost;dbname=chat;charset=utf8', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

/**
 * Analyse URL's request to get message or write one
 */
$task = "list";

if(array_key_exists("task", $_GET)){
  $task = $_GET['task'];
}

if($task === "write"){
  postMessage();
} else {
  getMessages();
}

/**
 * If we want to get message, we need to send JSON
 */
function getMessages(){
  global $db;

  //  We send a request to get the last 25 messages
  $resultats = $db->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 25");

  $messages = $resultats->fetchAll();
  // We display data on JSON format
  echo json_encode($messages);
}


/**
 * If we want to write a message then we need to analyse URL's parameters send in POST and register them in the Database
 */
function postMessage(){
  global $db;

  // Analyse POST parameters (author, content)
  if(!array_key_exists('author', $_POST) || !array_key_exists('content', $_POST)){

    echo json_encode(["status" => "error", "message" => "Un ou plusieurs champs n'ont pas été envoyés"]);
    return;

  }

  $author = strip_tags($_POST['author']);
  $content = strip_tags($_POST['content']);

  // Create a request that insert data
  $query = $db->prepare('INSERT INTO messages SET author = :author, content = :content, created_at = NOW()');

  $query->execute([
    "author" => $author,
    "content" => $content
  ]);

  // Give a statut of error/success for JSON format
  echo json_encode(["status" => "success"]);
}
