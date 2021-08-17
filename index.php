<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rest API</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>

<h3 class="text-center text-success">REST API CRUD Using PHP, jQuery and AJAX</h3>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 pt-5">
                <div id="message"></div>
                <div class="card">
                    <div class="card-header">
                        <h5>Create Post</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="createForm" class="create_form">
                            <div class="form-group">
                                <input type="text" name="post_title" id="post_title" class="form-control" placeholder="post title">
                            </div>
                            <div class="form-group">
                                <textarea name="post_body" id="post_body" class="form-control" placeholder="post body"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="text" name="author" id="author" class="form-control" placeholder="author">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="save" id="save" value="save" class="btn btn-info btn-block">
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        
            <div class="col-lg-8 pt-5 mx-auto">
                <div class="d-flex justify-content-between align-center">
                    <h5 class="text-info">List of all posts</h5>
                    <form action="" id="searchForm">
                        <input type="text" name="search" id="search" placeholder="search post">
                    </form>
                </div>
                <table class="table">
                    <thead id="thead"></thead>
                    <tbody id="tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="editPostModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Edit Post</div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" class="update_form">
                        <input type="hidden" name="postId" id="postId">
                        <div class="form-group">
                            <input type="text" name="post_title" id="edit_post_title" class="form-control" placeholder="post title">
                        </div>
                        <div class="form-group">
                            <textarea name="post_body" id="edit_post_body" class="form-control" placeholder="post body"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="text" name="author" id="edit_author" class="form-control" placeholder="author">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="update" id="update" value="Update" class="btn btn-info btn-block">
                        </div>
                    </form>
                  
                </div>
            </div>
        </div>
    </div>

    <!-- show modal -->

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="viewPostModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-light">
                    <div class="modal-title">Post Details</div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                   
                    <table class="table table-bordered text-center align-middle" id="showPost"></table>
                  
                </div>
            </div>
        </div>
    </div>
 
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            readPosts();

            //  show success or error message
            function showMessage(type, message)
            {
                if(type === true)
                {
                    $("#message").html("<div class='alert alert-success'><h6>"+message+"</h6></div>");
                    $("#message").slideDown();
                    setTimeout(function(){
                        $("#message").slideUp();
                    },4000);

                }else{

                    var message = "<div class='alert alert-danger'><h4>"+message+"</h4></div>";
                    return message;
                }
            }

            //  Load data in a HTML table after fetching all posts or searching posts
            function showPosts(response)
            {
                $("#thead").html('');
                $("#tbody").html('');
                if(response.message)
                {
                    $("#thead").html("<h4 class='text-danger'>"+response.message+"</h4>");

                }else{

                    $("#thead").html("<tr><th>ID</th><th>Post Title</th><th>Post Body</th><th>Author</th><th>Action</th></tr>");
                    $.each(response, function (indexInArray, valueOfElement) {                                  
                        $("#tbody").append("<tr><td>"+ ++indexInArray+"</td><td>"+valueOfElement.post_title+"</td><td>"+valueOfElement.post_body.slice(0,30)+"...</td><td>"+valueOfElement.author+"</td><td><a href='' data-toggle = 'modal' data-target = '#viewPostModal' id="+valueOfElement.id+" class='view_post text-info'><i class='bi bi-info-lg'></i></a>&nbsp;<a href='' data-toggle = 'modal' data-target = '#editPostModal' id="+valueOfElement.id+" class='edit_post text-success'><i class='bi bi-pencil-square'></i></a>&nbsp;<a href='' id="+valueOfElement.id+" class='delete_post text-danger'><i class='bi bi-trash'></i></a></td></tr>");
                    });
                }
            }

            //  convert form data into json object
            function getJsonData(form_name)
            {
                var arr = $("#"+form_name).serializeArray();
                var obj = {};
                for(var i=0; i<arr.length; i++)
                {
                    obj[arr[i].name] = arr[i].value;
                }

                var json_data = JSON.stringify(obj);

                return json_data;
            }

            //  fetch all posts from database
            function readPosts()
            {
                $.ajax({
                    type: "get",
                    url: "http://localhost:90/api/post/read-all.php",
                    dataType: "JSON",
                    success: function (response) {

                        showPosts(response);
                    }
                });
            }

            // create post
            $("#createForm").submit(function (e) { 
                e.preventDefault();
                
                var json_data = getJsonData("createForm");
                $.ajax({
                    type: "POST",
                    contentType:"application/json",
                    url: "http://localhost:90/api/post/create.php",
                    data: json_data,
                    dataType: "JSON",
                    success: function (response) {
                        if(response.status === true)
                        {
                            showMessage(response.status,response.message);                           
                            $("#createForm").trigger('reset');
                            readPosts();
                        }
                    }
                });
            });

            //  edit post 
            $("body").on("click",".edit_post", function(e){

                e.preventDefault();

                // $("#editPostModal").show();
                var post_id = $(this).attr('id');                
                var url = "http://localhost:90/api/post/read-single.php?id="+post_id;

                $.ajax({
                    type: "GET",
                    url: url,
                    dataType:"JSON",
                    success: function (response) {
                        
                        $("#edit_post_title").val(response.post_title);
                        $("#edit_post_body").val(response.post_body);
                        $("#edit_author").val(response.author);
                        $("#postId").val(response.id);
                        
                    }
                });

            });

            //  update post
            $("#updateForm").submit(function(e){

                e.preventDefault();
                var json_data = getJsonData("updateForm");
                var url = "http://localhost:90/api/post/update.php";

                $.ajax({
                    type: "POST",
                    contentType:"application/json",
                    url: url,
                    data: json_data,
                    dataType: "JSON",
                    success: function (response) {                        
                        
                        if(response.status === true)
                        {
                            showMessage(response.status,response.message);                           
                            $("#createForm").trigger('reset');
                            readPosts();
                            $("#editPostModal").modal('hide');
                        }else{

                            showMessage(response.status,response.message);
                        }
                    }
                });
            });

            //  delete post
            $("body").on("click",".delete_post",function(e){

                e.preventDefault();

                var post_id = $(this).attr('id');
                var id = {id : post_id};
                id = JSON.stringify(id);
                
                var url = "http://localhost:90/api/post/delete.php";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: id,
                    success: function (response) {
                        
                        if(response.status === true)
                        {
                            readPosts();
                            showMessage(response.status,response.message);
                        }else{

                            showMessage(response.status,response.message);
                        }
                    }
                });
            });

            //  view a  single post based on id
            $("body").on("click",".view_post", function(e){

                e.preventDefault();

                var post_id = $(this).attr('id');                
                var url = "http://localhost:90/api/post/read-single.php?id="+post_id;
                
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (response) {
                        $("#showPost").html('');
                        $("#showPost").append("<tr><td><b> Title</b></td><td>"+response.post_title+"</td></tr><tr><td><b> Body</b></td><td>"+response.post_body+"</td></tr><tr><td><b>Author</b></td><td>"+response.author+"</td></tr>");
                    }
                });
            });

            //  search for post
            $("#search").keyup(function(e){

                e.preventDefault();

                var search_string = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "http://localhost:90/api/post/read-all.php?search="+search_string,
                    success: function (response) {
                       
                        showPosts(response);
                    }
                });
            });
        });
    </script>
</body>
</html>