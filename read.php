<?php
// Check if the id parameter exists.
if (isset($_GET['id']) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";

    // Prepare a SELECT stmt
    $sql = "SELECT * FROM employees WHERE id = :id";

    if ($stmt = $pdo->prepare($sql)){
        //Binding vars to the prepared stmt as params
        $stmt->bindParam(":id", $param_id);
        //Setting params
        $param_id = trim(($_GET["id"]));
        //Attempt to execute the prep stmt
        if ($stmt->execute()){
            if($stmt->rowCount() == 1){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retrieving single field value
                $name = $row["name"];
                $position = $row["position"];
                $salary = $row["salary"];
            } else {
                header("location: error.php");
                exit();
            }
        } else {
            echo "Something went wrong. Try again later";
        }
    }
    unset($stmt);
    unset($pdo);
} else {
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>View Record</h1>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <p class="form-control-static"><?php echo $row["name"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <p class="form-control-static"><?php echo $row["position"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Salary</label>
                    <p class="form-control-static"><?php echo $row["salary"]; ?></p>
                </div>
                <p><a href="index.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
