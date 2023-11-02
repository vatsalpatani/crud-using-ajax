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
            echo "<script> alert('connection error')</script>";
        }
    }

    public function checkValues($array)
    {
        foreach ($array as $value) {
            if (empty($value)) {
                return false;
            }
        }
        return true;
    }

    public function insert($getdata)
    {
        $data = [
            "name"   => $getdata['name'],
            "email"  => $getdata['email'],
            "pwd"    => $getdata['pwd'],
            "conpwd" => $getdata['conpwd'],
        ];

        if($this->checkValues($data) == false){
            $error = "All field is required";
            echo json_encode(['message' => $error, 'type' => 'text-danger']);
        } elseif ($data['pwd'] != $data['conpwd']) {
            $error = "Password and Confirm Password is not same .";
            echo json_encode(['message' => $error, 'type' => 'text-danger']);
        } else {
            array_pop($data);
            $keys = implode("`,`", array_keys($data));
            $val = implode("','", array_values($data));
            $query = "insert into crud_ajax (`" . $keys . "`) values('" . $val . "')";
            $this->query = $query;
            $this->res = mysqli_query($this->con, $this->query);
            if ($this->res) {
                $error = "Data Inserted Successfully !!";
                echo json_encode(['message' => $error, 'type' => 'text-success']);
            }
            if (!$this->res) {
                echo "<script> alert('query error')</script>";
            }
        }
    }
    public function selectId($data)
    {
        $id = $data['id'];
        $sql = "select * from crud_ajax where id = $id";
        $this->query = $sql;
        $this->res = mysqli_query($this->con, $this->query);
        if ($this->res && $id != null) {
            $data = mysqli_fetch_assoc($this->res);
            print_r(json_encode($data));
        }
        if (!$this->res) {
            echo "<script> alert('query error')</script>";
            print_r(json_encode("error"));
        }
    }
    public function select($id = null)
    {
        $sql = "select * from crud_ajax $id";
        $this->query = $sql;
        $this->res = mysqli_query($this->con, $this->query);
        if ($this->res && $id != null) {
            $data = mysqli_fetch_assoc($this->res);
            print_r(json_encode($data));
        }
        if (!$this->res) {
            echo "<script> alert('query error')</script>";
            print_r(json_encode("error"));
        }
    }
    public function delete($data)
    {
        $id= $data['id'];
        $sql = "delete from crud_ajax where id=$id";
        $this->query = $sql;
        $this->res = mysqli_query($this->con, $this->query);
        if ($this->res) {
            echo json_encode(['message' => 'Record Deleted', 'type' => 'text-danger']);
        } else {
            echo "<script> alert('query error')</script>";
        }
    }
    public function update($data)
    {
        $updData = [
            "name"   => $data['name'],
            "email"  => $data['email'],
            "pwd"    => $data['pwd'],
            "conpwd" => $data['conpwd'],
        ];
        $id = $data['id'];
        if ($this->checkValues($data) == false) {
            $error = "All field is required";
            echo json_encode(['message' => $error, 'type' => 'text-danger']);
        } elseif ($updData['pwd'] != $updData['conpwd']) {
            $error = "Password and Confirm Password is not same .";
            echo json_encode(['message' => $error, 'type' => 'text-danger']);
        } else {
            array_pop($updData);
            $dataupd = " ";
            foreach ($updData as $key => $value) {
                $dataupd .= "$key = '$value', ";
            }
            $dataupd = rtrim($dataupd, ', '); 
            $query = "UPDATE crud_ajax SET $dataupd WHERE id = $id";
            $this->query = $query;
            $this->res = mysqli_query($this->con, $this->query);
            if ($this->res) {
                $error = "Data Updated Successfully !!";
                echo json_encode(['message' => $error, 'type' => 'text-success']);
            } else {
                echo "<script> alert('query error')</script>";
            }
        }
    }
}

$crud = new Crud();
if (isset($_REQUEST['fun'])) {
    $fun = $_REQUEST['fun'];
    $crud->$fun($_REQUEST);
}
