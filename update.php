<?php
// Including the config file
require_once "config.php";

// Defining variables and initializing with empty values
$name = $position = $salary = "";
$name_err = $position_err = $salary_err = "";

// Procesing form's data when it is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])){
    // Getting the hidden input value
    $id = $_POST["id"];
    //Name validation
    $input_name = trim($_POST["name"]);
    if (empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a correct name";
    } else {
        $name = $input_name;
    }
    // Position validation
    $input_position = trim($_POST["position"]);
    if (empty($input_position)){
        $position_err = "Please enter a Position.";
    } else {
        $position = $input_position;
    }
    // Salary validation
    $input_salary = trim($_POST["salary"]);
    if (empty($input_salary)){
        $salary_err = "Please enter a salary amount.";
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Please enter a positive number.";
    } else {
        $salary = $input_salary;
    }
    // Validating data errors before inserting in DB
    if(empty($name_err) && empty($position_err) && empty($salary_err)){
        // Prepare an update stmt
        $sql = "UPDATE employees SET name=:name, position=:position, salary=:salary WHERE id=:id";

        if ($stmt = $pdo->prepare($sql)) {
            //Binding vars to the prepared stmt as params
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":position", $param_position);
            $stmt->bindParam(":salary", $param_salary);
            $stmt->bindParam(":id", $param_id);
            //Setting params
            $param_name = $name;
            $param_position = $position;
            $param_salary = $salary;
            $param_id = $id;
            //Attempt to execute the prep stmt
            if ($stmt->execute()){
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Try again later.";
            }
        }
        unset($stmt);
    }
    unset($pdo);
} else {
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        //Get the URL param
        $id = trim($_GET["id"]);
        // Prepare a SELECT stmt
        $sql = "SELECT * FROM employees WHERE id = :id";

        if ($stmt = $pdo->prepare($sql)) {
            //Binding vars to the prepared stmt as params
            $stmt->bindParam(":id", $param_id);
            //Setting params
            $param_id = trim(($_GET["id"]));
            //Attempt to execute the prep stmt
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2>Update Record</h2>
                </div>
                <p>Please edit the input values and submit to update the record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                        <span class="help-block"><?php echo $name_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($position_err)) ? 'has-error' : ''; ?>">
                        <label>Position</label>
                        <textarea name="position" class="form-control"><?php echo $position; ?></textarea>
                        <span class="help-block"><?php echo $position_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                        <label>Salary</label>
                        <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
                        <span class="help-block"><?php echo $salary_err;?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
