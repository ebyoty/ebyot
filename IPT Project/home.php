<?php
session_start();

include("php/config.php");
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit(); // Make sure to exit after redirection
}

$id = $_SESSION['id'];
$query = mysqli_query($con, "SELECT * FROM users WHERE Id = $id");

if ($query) {
    $result = mysqli_fetch_assoc($query);
    if ($result) {
        $res_Uname = $result['Username'];
        $res_Email = $result['Email'];
        $res_Course = $result['Course'];
        $res_YearandSet = $result['YearandSet'];
        $res_Age = $result['Age'];
        $res_id = $result['Id'];
    } else {
        // Handle case where user data is not found
        // Redirect or display an error message
        header("Location: index.php");
        exit();
    }
} else {
    // Handle database query error
    // Redirect or display an error message
    header("Location: index.php");
    exit();
}

// Fetch students and their grades
$students_query = mysqli_query($con, "SELECT * FROM students");
$students = [];
while ($student = mysqli_fetch_assoc($students_query)) {
    $student_id = $student['id'];
    $grades_query = mysqli_query($con, "SELECT * FROM student_grades WHERE student_id = $student_id");
    if ($grades_query) {
        $grades = mysqli_fetch_assoc($grades_query);
        // Assign grades if found, otherwise assign empty strings
        $student['prelim'] = isset($grades['prelim']) ? $grades['prelim'] : '';
        $student['midterm'] = isset($grades['midterm']) ? $grades['midterm'] : '';
        $student['semi_final'] = isset($grades['semi_final']) ? $grades['semi_final'] : '';
        $student['final'] = isset($grades['final']) ? $grades['final'] : '';
    } else {
        // Handle case where grades query fails
        // Log error or display a message
        $student['prelim'] = '';
        $student['midterm'] = '';
        $student['semi_final'] = '';
        $student['final'] = '';
    }
    $students[] = $student;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/styles.css">
    <script type="text/javascript" src="jquery/jquery-3.7.1-jquery.min.js"></script>
    <style>
        body {
            background-image: url('img/vincent.png');
            background-size: cover;
            background-repeat: no-repeat;
            margin: 0; 
            padding: 0;
        }
    </style>
    <title>Home</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">Integrative Programming Technology</a> </p>
        </div>

        <div class="right-links">
            <?php
            echo "<a href='edit.php?Id=$res_id'>Change Profile</a>";
            ?>
            <a href="students.php"><button class="btn">Add Student</button></a>
            <a href="php/logout.php"><button class="btn">Log Out</button></a>
        </div>
    </div>
    <main>
    <div class="main-box top">
      <div class="top">
        <div class="box">
            <p>Hello Admin <b><?php echo $res_Uname ?></b>, Welcome to your page</p>
        </div>
        <div class="box">
            <p>Your email is: <b><?php echo $res_Email ?></b>.</p>
        </div>
    </div>
    <div class="bottom">
        <div class="box">
            <p>Your Course is:  <b><?php echo $res_Course ?></b>.</p> 
        </div>
        <div class="box">
            <p>And your Year and Set: <b><?php echo $res_YearandSet ?> </b>.</p> 
        </div>
        <div class="box">
            <p>And you are: <b><?php echo $res_Age ?> years old</b>.</p> 
        </div>
    </div>
</div>

       <div class="main-box">
          <table>
             <thead>
               <h1><center>Student List</center></h1>
                <tr>
                   <th>Student Name</th>
                   <th>Category</th>
                   <th>Prelim</th>
                   <th>Midterm</th>
                   <th>Semi-final</th>
                   <th>Final</th>
                </tr>
             </thead>
             <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                   <td><?php echo $student['username']; ?></td>
                   <td><?php echo $student['category']; ?></td>
                   <td><?php echo $student['prelim']; ?></td>
                   <td><?php echo $student['midterm']; ?></td>
                   <td><?php echo $student['semi_final']; ?></td>
                   <td><?php echo $student['final']; ?></td>
                </tr>
                <?php endforeach; ?>
             </tbody>
          </table>
       </div>
    </main>
</body>
</html>
