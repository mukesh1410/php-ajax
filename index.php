<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
    #success-message{
    background: #DEF1D8;
    color: green;
    padding: 10px;
    margin: 10px;
    display: none;
    position: absolute;
    right: 15px;
    top: 15px;
    }

    #error-message{
    background: #EFDCDD;
    color: red;
    padding: 10px;
    margin: 10px;
    display: none;
    position: absolute;
    right: 15px;
    top: 15px;
    }
    </style>
</head>
<body>
    <table id="main" border="0" cellspacing="0" width="100%">
        <tr> 
            <td id="header">    
                <h1>PHP AND AJAX CRUD</h1>
            </td>
        </tr>   
        <tr>
        <td id="table-form">
            <form id="addForm">
                <div id="table-form-div">
                    First Name: <input type="text" id="fname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Last Name: <input type="text" id="lname">
                    <input type="submit" id="save-button" value="Save">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div id="search-bar">
                        <label>Search:</label>
                        <input type="text" id="search" autocomplete="off">
                    </div>
               </div>
            </form>
        </td>
        </tr>
        <tr>
            <td id="table-data">
                <!-- <table border="1" width="100%" cellspacing="0" cellpadding="10px">
                    <tr id="table-head">
                        <th width="100px">Id</th>
                        <th>Name</th>
                    </tr>
                    <tr>
                        <td align="center">1</td>
                        <td>Yahoo Baba</td>
                    </tr>
                </table> -->
            </td>
        </tr>
    </table>
    <div id="error-message"></div>
    <div id="success-message"></div>
    <div id="modal">
        <div id="modal-form">
            <h2>Edit Form</h2>
            <table cellpadding="10px" width="100%"> 
            </table>
            <div id="close-btn">X</div>
        </div>
    </div>

    <script> 
    $(document).ready(function(){

        //Load Table records
        function loadTable(){
            $.ajax({
               url: "ajax-load.php",
               type: "POST",
               success: function(data) {
               $("#table-data").html(data);
               } 
            });
        }
        loadTable();

        //insert new record
        $("#save-button").on("click",function(e){
            e.preventDefault();
            var fname = $("#fname").val();
            var lname = $("#lname").val();

            if(fname == "" || lname == ""){
                $("#error-message").html("All feilds are required").slideDown();
                $("#success-message").slideUp();
            }else{
                $.ajax({
                    url: "ajax-insert.php",
                    type: "POST",
                    data: {
                       first_name: fname,
                       last_name: lname
                    },
                    success: function(data){
                       if(data == 1){
                           loadTable();
                           $("#addForm").trigger("reset");
                           $("#success-message").html("Data Inserted Successfully").slideDown();
                           $("#error-message").slideUp();
                        }else{
                           $("#error-message").html("cant save record").slideDown();
                           $("#success-message").slideUp();
                        }
                    }
                });
            }
        });

        //delete record   -   if the button is show dynamically then we can use document type
        $(document).on("click", ".delete-btn", function(){
            if(confirm("Do you really want to delete this record ?")){
                var studentId = $(this).data("id");
                var element = this; 
                $.ajax({
                    url: "ajax-delete.php",
                    type: "POST",
                    data: {
                        id: studentId
                    },
                    success: function(data){
                        if(data == 1){
                            $(element).closest("tr").fadeOut();
                        }else{
                            $("#error-message").html("Cant delete record").slideDown();
                            $("#success-message").slideUp();
                        }
                    }
                });
            }
        });

        //show modal box
        $(document).on("click", ".edit-btn", function(){
            $("#modal").show();
            var studentId = $(this).data("eid");

            $.ajax({
                url: "load-update-form.php",
                type: "POST",
                data: {
                    id: studentId
                },
                success: function(data){
                    $("#modal-form table").html(data);
                }
            });
        });

        //hide modal box
        $("#close-btn").on("click", function(){
            $("#modal").hide();
        });

        //save update form
        $(document).on("click", "#edit-submit", function(){
            var stuId = $("#edit-id").val();
            var fname = $("#edit-fname").val();
            var lname = $("#edit-lname").val();

            $.ajax({
                url: "ajax-update-form.php",
                type: "POST",
                data: {
                    id: stuId,
                    first_name: fname,
                    last_name: lname
                },
                success: function(data){
                    if(data == 1){
                        $("#modal").hide();
                        loadTable();
                    }
                }
            });
        });

        //live search
        $("#search").on("keyup", function(){
            var search_term = $(this).val();

            $.ajax({
                url: "ajax-live-search.php",
                type: "POST",
                data: {
                    search: search_term
                },
                success: function(data){
                    $("#table-data").html(data);
                }
            });
        });
    });
    </script>
</body>                     
</html>