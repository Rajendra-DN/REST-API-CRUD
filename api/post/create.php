<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/models/Post.php';

   
        $posts = new Post();
      
        $data = json_decode(file_get_contents("php://input"),true);
        
        $create_post = $posts->createPost($data);

        if($create_post)
        {
            echo json_encode(['message'=>'Post has been created','status'=>true]);

        }else{

            echo json_encode(['message'=>'something went wrong','status'=>false]);
        }
    

   
    

    
