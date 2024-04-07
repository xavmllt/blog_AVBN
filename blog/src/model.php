<?php
// src/model.php

function getPosts()
{
    // ... (déjà écrite)
}

function getPost($identifier) {
    
	$database = dbConnect();
 
    $statement = $database->prepare(
        "SELECT id, title, content, DATE_FORMAT(creation_date, '%d/%m/%Y à %Hh%imin%ss') AS french_creation_date FROM posts WHERE id = ?"
    );
    $statement->execute([$identifier]);
 
    $row = $statement->fetch();
    $post = [
        'title' => $row['title'],
        'french_creation_date' => $row['french_creation_date'],
        'content' => $row['content'],
    ];
 
    return $post;
}

function getComments($identifier) {
    $database = dbConnect();
 
    $statement = $database->prepare(
        "SELECT id, author, comment, DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%imin%ss') AS french_creation_date FROM comments WHERE post_id = ? ORDER BY comment_date DESC"
    );
    $statement->execute([$identifier]);
 
    $comments = [];
    while (($row = $statement->fetch())) {
        $comment = [
            'author' => $row['author'],
            'french_creation_date' => $row['french_creation_date'],
            'comment' => $row['comment'],
        ];
        $comments[] = $comment;
    }

    return $comments;
}

function dbConnect() {
	try {
        $database = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', 'root');
		return $database;
    } catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
    }
}
