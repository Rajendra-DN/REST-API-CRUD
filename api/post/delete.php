<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/models/Post.php';

    $posts = new Post();
       
    $data = json_decode(file_get_contents("php://input"),true);
    $id = $data['id'];
    $delete_post = $posts->deletePost($id);

    if($delete_post)
    {
        echo json_encode(['message'=>'Post has been deleted','status'=>true]);

    }else{

        echo json_encode(['message'=>'something went wrong','status'=>false]);
    }