<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/config/Database.php';

    class Post extends Database
    {
        public static function filter_input($input)
        {
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input,ENT_QUOTES);

            return $input;
        }
        public function readAllPosts()
        {
            $sql = "SELECT * FROM posts";
            $statement = $this->conn->prepare($sql);
            $statement->execute();
            $posts = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $posts;
        }

        public function readPost($id)
        {
            $sql = "SELECT * FROM posts WHERE id = :id";
            $statement = $this->conn->prepare($sql);
            $statement->execute(['id'=>$id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);  
            
            return $result;
            
        }

        public function createPost($data)
        {
            foreach($data as $key=>$value)
            {
                $data[$key] = Post::filter_input($value);
                
            }

            $sql = "INSERT INTO posts(post_title,post_body,author) VALUES (:title,:body,:author)";
            $statement = $this->conn->prepare($sql);
            $result = $statement->execute(['title'=>$data['post_title'],'body'=>$data['post_body'],'author'=>$data['author']]);
            return $result;
        }

        public function updatePost($data)
        {
            foreach($data as $key=>$value)
            {
                $data[$key] = Post::filter_input($value);

            }

            $sql = "UPDATE posts SET post_title = :title, post_body = :body, author = :author WHERE id = :id";
            $statement = $this->conn->prepare($sql);
            $result = $statement->execute(['title'=>$data['post_title'],'body'=>$data['post_body'],'author'=>$data['author'],'id'=>$data['postId']]);

            return $result;
        }

        public function deletePost($id)
        {
            $sql = "DELETE FROM posts WHERE id = :id";
            $statement = $this->conn->prepare($sql);
            $result = $statement->execute(['id'=>$id]);

            return $result;
        }

        public function searchString($string)
        {
            $sql = "SELECT * FROM posts WHERE post_title LIKE :string";
            $statement = $this->conn->prepare($sql);
            $statement->execute(['string'=>'%'.$string.'%']);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

    }