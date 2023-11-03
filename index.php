<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Data</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container-md">
        <table class="table table-bordered mt-5 mb-5 table-striped">
            <tr>
                <td colspan=6><button type="button" class="btn btn-primary float-end" id="insmodel">Insert Data</button></td>
            </tr>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</td>
                <th>Password</th>
                <th>Update</td>
                <th>Delete</td>
            </tr>
            <?php
            require "crud.php";
            $cr = new Crud();
            $cr->select();
            while ($data = mysqli_fetch_assoc($cr->res)) {
            ?>
                <tr>
                    <td><?php echo $data['id']; ?></td>
                    <td><?php echo $data['name']; ?></td>
                    <td><?php echo $data['email']; ?></td>
                    <td><?php echo $data['pwd']; ?></td>
                    <td><a href="javascript:void(0)" class="btn btn-primary updmodel" data-id="<?php echo $data['id'] ?>">Update</a></td>
                    <td><a href="javascript:void(0)" class="btn btn-danger delete" data-id="<?php echo $data['id'] ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
        <div class="modal fade" id="myModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" id="myInput">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"></h5>
                        <button type="button" class="btn-close btnclose" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="form1">
                            <input type="hidden" name="id" id="rowId">
                            <div class="row form-group m-3">
                                <div class="col-md-3">
                                    <label for="name">Name</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                            </div>
                            <div class="row form-group m-3">
                                <div class="col-md-3">
                                    <label for="email">Email</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                            </div>
                            <div class="row form-group m-3">
                                <div class="col-md-3">
                                    <label for="pwd">Password</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="password" name="pwd" id="pwd" class="form-control">
                                </div>
                            </div>
                            <div class="row form-group m-3">
                                <div class="col-md-3">
                                    <label for="pwd">Confirm Password</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="password" name="conpwd" id="conpwd" class="form-control">
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btnclose" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnsub"></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Insert Data</strong>
                    <small></small>
                    <button type="button" class="btn-close btnclose" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body"></div>
            </div>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../jquery-validation/dist/jquery.validate.min.js"></script>
    <script>
        function showAlert(type, message) {
            if (message != "" && type != "") {
                $(".toast-body").addClass(type)
                $(".toast-body").text(message)
                const toastLiveExample = document.getElementById('liveToast')
                const toast = new bootstrap.Toast(toastLiveExample)
                toast.show()
                setTimeout(hideToast, 2000)

                function hideToast() {
                    toast.hide()
                    location.reload()
                }
            }
        }

        function resetData() {
            $("form *").val("")
            $("#form1").validate().destroy()
        }

        function showModel(name, data = null) {
            $("#form1").validate({
                rules: {
                    'name': {
                        required: true,
                    },
                    'email': {
                        required: true,
                        email: true
                    },
                    'pwd': {
                        required: true,
                    },
                    'conpwd': {
                        required: true,
                        equalTo: "#pwd"
                    }
                },
                submitHandler: function() {
                    $.ajax({
                            url: 'crud.php',
                            type: 'POST',
                            dataType: 'json',
                            data: $("form").serialize() + "&fun=" + $("#btnsub").html()
                        })
                        .done(function(response) {
                            showAlert(response.type, response.message);
                            resetData();
                        });
                    $("#myModel").modal('hide')
                },
            });
            $("#myModel").modal('show')
            $(".modal-title").html(name+" Data")
            $("#btnsub").html(name)
            if (name == "Update") {
                $("#name").val(data.name)
                $("#email").val(data.email)
                $("#pwd").val(data.pwd)
                $("#conpwd").val(data.pwd)
                $("#rowId").val(data.id)
            }
        }

        $(document).ready(function() {
            $("#insmodel").click(function() {
                showModel("Insert");
            });
            $(".delete").click(function() {
                var id = $(this).data("id")
                $.ajax({
                        url: 'crud.php?fun=delete&id=' + id,
                        type: 'GET',
                        dataType: 'json',
                    })
                    .done(function(response) {
                        console.log(response);
                        showAlert(response.type, response.message);
                    });
            });
            $(".updmodel").click(function() {
                var id = $(this).data("id")
                $.ajax({
                        url: 'crud.php?fun=selectID&id=' + id,
                        type: 'GET',
                        dataType: 'json',
                    })
                    .done(function(response) {
                        console.log(response);
                        showModel("Update", response)
                    });
            });
            $(".btnclose").click(function() {
                resetData();
            });
        });
    </script>
</body>

</html>
