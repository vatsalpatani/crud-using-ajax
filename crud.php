<?php

class Crud
{
    private $query;
    public $con;
    public $res;

    public function __construct()
    {
        $this->con = mysqli_connect("localhost", "root", "root", "vatsal");
        if (!$this->con) {
            print_r(json_encode("Connection error"));
        }
    }

    public function checkEmail($data)
    {
        $email = $data['email'];
        if (isset($data['oldEmail'])) {
            if ($data['oldEmail'] === $email) {
                return;
            }
        }
        $sql = "select * from crud_ajax where email = '$email'";
        $this->query = $sql;
        $this->res = mysqli_query($this->con, $this->query);
        if ($this->res) {
            $count = mysqli_num_rows($this->res);
            if ($count > 0)
                print_r(json_encode("email already exists"));
        }
    }

    // This function is used to check values that is empty or not
    public function checkValues($array)
    {
        foreach ($array as $value) {
            if (empty($value)) {
                return false;
            }
        }
        return true;
    }

    // This function is use for insert data in database
    public function saveData($postdata)
    {
        if ($postdata['id'] != "") {
            try {
                $id = $postdata['id'];
                if ($this->checkValues($postdata) == false) {
                    $error = "All field is required";
                    echo json_encode(['message' => $error, 'type' => 'text-danger']);
                } elseif ($postdata['pwd'] != $postdata['conpwd']) {
                    $error = "Password and Confirm Password is not same .";
                    echo json_encode(['message' => $error, 'type' => 'text-danger']);
                } else {
                    array_pop($postdata);
                    array_pop($postdata);
                    $dataupd = " ";
                    foreach ($postdata as $key => $value) {
                        $dataupd .= "$key = '$value', ";
                    }
                    $dataupd = rtrim($dataupd, ', ');
                    $query = "UPDATE crud_ajax SET $dataupd WHERE id = $id";
                    $this->query = $query;
                    $this->res = mysqli_query($this->con, $this->query);
                    if ($this->res) {
                        $msg = "Data Updated Successfully !!";
                        echo json_encode(['message' => $msg, 'type' => 'text-success']);
                        return;
                    }
                    print_r(json_encode("Database error"));
                }
            } catch (\Throwable $th) {
                print_r(json_encode(["message" => $th->getMessage()]));
            }
        } else {
            try {
                array_shift($postdata);
                if ($this->checkValues($postdata) == false) {
                    $error = "All field is required";
                    echo json_encode(['message' => $error, 'type' => 'text-danger']);
                } elseif ($postdata['pwd'] != $postdata['conpwd']) {
                    $error = "Password and Confirm Password is not same .";
                    echo json_encode(['message' => $error, 'type' => 'text-danger']);
                } else {
                    array_pop($postdata);
                    array_pop($postdata);
                    $keys = implode("`,`", array_keys($postdata));
                    $val = implode("','", array_values($postdata));
                    $query = "insert into crud_ajax (`" . $keys . "`) values('" . $val . "')";
                    $this->query = $query;
                    $this->res = mysqli_query($this->con, $this->query);
                    if (!$this->res) {
                        print_r(json_encode("Database error"));
                        return;
                    }
                    $msg = "Data Inserted Successfully !!";
                    echo json_encode(['message' => $msg, 'type' => 'text-success']);
                }
            } catch (\Throwable $th) {
                print_r(json_encode(['message' => $th->getMessage()]));
            }
        }
    }

    // This function is use for select data from database
    public function select($data = null)
    {
        if ($data) {
            $id = $data['id'];
            $sql = "select * from crud_ajax where id = $id";
            $this->query = $sql;
            $this->res = mysqli_query($this->con, $this->query);
            if ($this->res) {
                $data = mysqli_fetch_assoc($this->res);
                print_r(json_encode($data));
            }
        } else {
            $sql = "select * from crud_ajax";
            $this->query = $sql;
            $this->res = mysqli_query($this->con, $this->query);
            if (!$this->res) {
                print_r(json_encode("Database error"));
            }
        }
    }

    // This function is use for delete data from database
    public function delete($data)
    {
        $id = $data['id'];
        $sql = "delete from crud_ajax where id=$id";
        $this->query = $sql;
        $this->res = mysqli_query($this->con, $this->query);
        if ($this->res) {
            echo json_encode(['message' => 'Record Deleted', 'type' => 'text-danger']);
            return;
        }
        echo "<script> alert('query error')</script>";
    }
}

$crud = new Crud();
if (isset($_REQUEST['method'])) {
    $fun = $_REQUEST['method'];
    $crud->$fun($_REQUEST);
}
