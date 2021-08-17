<?php

    header("Allow-Access-Control-Origin: *");
    header("Content-Type: application/json");

    require_once $_SERVER['DOCUMENT_ROOT'].'/models/Post.php';

    $posts = new Post();

    if(isset($_GET['search']))
    {

        $search_string = $_GET['search'];
        $all_posts = $posts->searchString($search_string);

    }else{

        $all_posts = $posts->readAllPosts();
    }

    if($all_posts)
    {
        echo json_encode($all_posts);
        
    }else{

        echo json_encode(['message'=>'No posts found']);
    }

    
    
    

    
