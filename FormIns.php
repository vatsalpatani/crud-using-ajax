<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <title>Insert Data</title>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center bg-secondary vh-100">
        <div class="card rounded-4">
            <div class="card-body">
                <div class="col mt-5">
                    <div class="row m-5">
                        <h2 class="text-success" align="center">Insert Data</h1>
                    </div>
                    <form action="" method="post">
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
                        <div class="row form-group m-4">
                            <div class="col">
                                <button type="submit" class="btn btn-primary" id="subbtn">Submit Data</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-secondary" id="resetbtn">Reset Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="liveToastBtn" hidden>Show live toast</button>

            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Insert Data</strong>
                        <small></small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.min.js"></script>
<script>
    function showAlert(type, message) {
        if (message != "" && type != "") {
            $(".toast-body").addClass(type);
            $(".toast-body").text(message);
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

    function test() {
        console.log('hi');
    }

    function resetData() {
        $("form *").val("");
    }

    $(document).ready(function() {
        $("form").submit(function(event) {
            event.preventDefault();
            $.ajax({
                    url: 'insertData.php',
                    type: 'POST',
                    dataType: 'json',
                    data: ($("form").serialize())
                })
                .done(function(response) {
                    resetData();
                    showAlert(response.type, response.message);
                });
        });
        
        $("#resetbtn").click(function() {
            resetData();
        });
    });
</script>

</html>
