<?php
if(isset($_POST['edit_student'])) {
    $edit_id = $_POST['edit_id'];
    $username = $_POST['edit_username'];
    $email = $_POST['edit_email'];
    $course = $_POST['edit_course'];
    $yearandset = $_POST['edit_yearandset'];
    $age = $_POST['edit_age'];
    $prelim = $_POST['edit_prelim'];
    $midterm = $_POST['edit_midterm'];
    $semi_final = $_POST['edit_semi_final'];
    $final = $_POST['edit_final'];

    // Update the user information
    $query = "UPDATE users SET Username='$username', Email='$email', Course='$course', YearandSet='$yearandset', Age='$age' WHERE Id=$edit_id";
    mysqli_query($con, $query);

    // Check if grades exist for the user
    $grades_check_query = "SELECT * FROM grades WHERE user_id='$edit_id'";
    $grades_check_result = mysqli_query($con, $grades_check_query);

    if (mysqli_num_rows($grades_check_result) > 0) {
        // Update existing grades
        $query = "UPDATE grades SET prelim='$prelim', midterm='$midterm', semi_final='$semi_final', final='$final' WHERE user_id='$edit_id'";
    } else {
        // Insert new grades
        $query = "INSERT INTO grades (user_id, prelim, midterm, semi_final, final) VALUES ('$edit_id', '$prelim', '$midterm', '$semi_final', '$final')";
    }
    mysqli_query($con, $query);

    header("Location: students.php?successEditingStudent=true");
    exit();
}
?>