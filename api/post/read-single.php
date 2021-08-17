<?php

    header("Allow-Access-Control-Origin: *");
    header("Content-Type: application/json");

    require_once $_SERVER['DOCUMENT_ROOT'].'/models/Post.php';

    $posts = new Post();

    $id = $_GET['id'];

    $single_post = $posts->readPost($id);

    if($single_post)
    {
        
        echo json_encode($single_post);

    }else{

        echo json_encode(['message'=>'Post not found']);
    }
    
    

    
