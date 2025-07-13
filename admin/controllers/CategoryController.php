<?php
require_once '../config/connection.php';
require_once '../config/helpers.php';

class CategoryController{

    private mysqli $conn;

    public function __construct()
    {
        // 👇 Get connection directly
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function index(){
        require 'views/category/index.php';
    }

    public function datatableList(){
       
       $columns = ['id', 'name', 'status'];

        // Read DataTables parameters
        $draw = $_POST['draw'];
        $start = $_POST['start'];
        $length = $_POST['length'];
        $searchValue = $_POST['search']['value'];

        // ORDER
        $orderColumnIndex = $_POST['order'][0]['column'];
        $orderColumn = $columns[$orderColumnIndex];
        $orderDir = $_POST['order'][0]['dir'];

        // Total records
        $totalRecordsQuery = mysqli_query($this->conn, "SELECT COUNT(*) as total FROM categories");
        $totalRecords = mysqli_fetch_assoc($totalRecordsQuery)['total'];

        // Filtered records
        $where = "";
        if (!empty($searchValue)) {
            $searchValue = mysqli_real_escape_string($this->conn, $searchValue);
            $where = "WHERE name LIKE '%$searchValue%' OR status LIKE '%$searchValue%'";
        }

        $filteredQuery = mysqli_query($this->conn, "SELECT COUNT(*) as total FROM categories $where");
        $filteredRecords = mysqli_fetch_assoc($filteredQuery)['total'];

        // Fetch data
        $sql = "SELECT * FROM categories $where ORDER BY $orderColumn $orderDir LIMIT $start, $length";
        $result = mysqli_query($this->conn, $sql);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            // Add Edit/Delete HTML buttons
            $row['action'] = '
                <a href="category/edit.php?id=' . $id . '" class="btn btn-sm btn-primary mr-1">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $id . '">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            ';

            $data[] = $row;
        }

        // JSON response
        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ]);
    }
}