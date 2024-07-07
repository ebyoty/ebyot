<?php 
   session_start();
   include("php/config.php");

   // Enable error reporting
   error_reporting(E_ALL);
   ini_set('display_errors', 1);

   if (!isset($_SESSION['valid'])) {
       header("Location: index.php");
       exit();
   }

   // Add student
   if (isset($_POST['add_student'])) {
       $username = $_POST['username'];
       $email = $_POST['email'];
       $course = $_POST['course'];
       $yearandset = $_POST['yearandset'];
       $age = $_POST['age'];
       $category = $_POST['category'];

       $query = "INSERT INTO students (username, email, course, year_and_set, age, category) VALUES ('$username', '$email', '$course', '$yearandset', '$age', '$category')";
       if (mysqli_query($con, $query)) {
           header("Location: students.php?successAddingStudent=true");
       } else {
           echo "Error: " . mysqli_error($con);
       }
       exit();
   }

   // Delete student
   if (isset($_GET['delete_id'])) {
       $delete_id = $_GET['delete_id'];
       $query = "DELETE FROM students WHERE id=$delete_id";
       if (mysqli_query($con, $query)) {
           header("Location: students.php?successDeletingStudent=true");
       } else {
           echo "Error: " . mysqli_error($con);
       }
       exit();
   }

   // Edit student
   if (isset($_POST['edit_student'])) {
       $edit_id = $_POST['edit_id'];
       $username = $_POST['edit_username'];
       $email = $_POST['edit_email'];
       $course = $_POST['edit_course'];
       $yearandset = $_POST['edit_yearandset'];
       $age = $_POST['edit_age'];
       $category = $_POST['edit_category'];
       $prelim = $_POST['edit_prelim'];
       $midterm = $_POST['edit_midterm'];
       $semi_final = $_POST['edit_semi_final'];
       $final = $_POST['edit_final'];

       $query = "UPDATE students SET username='$username', email='$email', course='$course', year_and_set='$yearandset', age='$age', category='$category' WHERE id=$edit_id";
       if (!mysqli_query($con, $query)) {
           echo "Error: " . mysqli_error($con);
       }

       $query = "INSERT INTO student_grades (student_id, prelim, midterm, semi_final, final) VALUES ('$edit_id', '$prelim', '$midterm', '$semi_final', '$final') 
                 ON DUPLICATE KEY UPDATE prelim='$prelim', midterm='$midterm', semi_final='$semi_final', final='$final'";
       if (mysqli_query($con, $query)) {
           header("Location: students.php?successEditingStudent=true");
       } else {
           echo "Error: " . mysqli_error($con);
       }
       exit();
   }

   // Fetch students and their grades
   $students_query = mysqli_query($con, "
       SELECT students.*, student_grades.prelim, student_grades.midterm, student_grades.semi_final, student_grades.final 
       FROM students 
       LEFT JOIN student_grades ON students.id = student_grades.student_id
   ");
   $students = [];
   while ($student = mysqli_fetch_assoc($students_query)) {
       $students[] = $student;
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/student.css">
    <script type="text/javascript" src="jquery/jquery-3.7.1-jquery.min.js"></script>
    <style>
        body {
            background-image: url('img/vincent.png');
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh;
        }
    </style>
    <title>List of Students</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">Integrative Programming Technology</a> </p>
        </div>

        <div class="right-links">
            <a href="home.php"> <button class="btn">Home</button> </a>
            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>
        </div>
    </div>
    <main class="main">
       <div class="main-box students">
           <h2>List of Students</h2>

           <?php if (isset($_GET['successAddingStudent'])): ?>
               <div class="alert alert-success">Student added successfully!</div>
           <?php endif; ?>

           <?php if (isset($_GET['successDeletingStudent'])): ?>
               <div class="alert alert-success">Student deleted successfully!</div>
           <?php endif; ?>

           <?php if (isset($_GET['successEditingStudent'])): ?>
               <div class="alert alert-success">Student updated successfully!</div>
           <?php endif; ?>

           <button type="button" class="btn btn-success mb-2" onclick="toggleAddStudentForm()">Add New Student</button>
           <button type="button" class="btn btn-warning mb-2" onclick="toggleEditStudentForm()">Edit Student</button>

           <div class="add-student-form">
               <form action="students.php" method="post">
                   <div class="box">
                       <label for="username">Username</label>
                       <input type="text" id="username" name="username" required>
                   </div>
                   <div class="box">
                       <label for="email">Email</label>
                       <input type="email" id="email" name="email" required>
                   </div>
                   <div class="box">
                       <label for="course">Course</label>
                       <input type="text" id="course" name="course" required>
                   </div>
                   <div class="box">
                       <label for="yearandset">Year and Set</label>
                       <input type="text" id="yearandset" name="yearandset" required>
                   </div>
                   <div class="box">
                       <label for="age">Age</label>
                       <input type="number" id="age" name="age" required>
                   </div>
                   <div class="box">
                       <label for="category">Category</label>
                       <select id="category" name="category" required>
                           <option value="Mathematics">Mathematics</option>
                           <option value="Language Arts">Language Arts</option>
                           <option value="Sciences">Sciences</option>
                           <option value="Social Studies">Social Studies</option>
                       </select>
                   </div>
                   <div class="box">
                       <input type="submit" name="add_student" value="Add Student">
                   </div>
               </form>
           </div>

           <!-- Form to Edit a Student -->
           <div class="edit-student-form">
               <form action="students.php" method="post">
                   <input type="hidden" id="edit_id" name="edit_id">
                   <div class="box">
                       <label for="edit_username">Username</label>
                       <input type="text" id="edit_username" name="edit_username" required>
                   </div>
                   <div class="box">
                       <label for="edit_email">Email</label>
                       <input type="email" id="edit_email" name="edit_email" required>
                   </div>
                   <div class="box">
                       <label for="edit_course">Course</label>
                       <input type="text" id="edit_course" name="edit_course" required>
                   </div>
                   <div class="box">
                       <label for="edit_yearandset">Year and Set</label>
                       <input type="text" id="edit_yearandset" name="edit_yearandset" required>
                   </div>
                   <div class="box">
                       <label for="edit_age">Age</label>
                       <input type="number" id="edit_age" name="edit_age" required>
                   </div>
                   <div class="box">
                       <label for="edit_category">Category</label>
                       <select id="edit_category" name="edit_category" required>
                           <option value="Mathematics">Mathematics</option>
                           <option value="Language Arts">Language Arts</option>
                           <option value="Sciences">Sciences</option>
                           <option value="Social Studies">Social Studies</option>
                       </select>
                   </div>
                   <div class="box">
                       <label for="edit_prelim">Prelim Grade</label>
                       <input type="number" step="0.01" id="edit_prelim" name="edit_prelim" required>
                   </div>
                   <div class="box">
                       <label for="edit_midterm">Midterm Grade</label>
                       <input type="number" step="0.01" id="edit_midterm" name="edit_midterm" required>
                   </div>
                   <div class="box">
                       <label for="edit_semi_final">Semi-Final Grade</label>
                       <input type="number" step="0.01" id="edit_semi_final" name="edit_semi_final" required>
                   </div>
                   <div class="box">
                       <label for="edit_final">Final Grade</label>
                       <input type="number" step="0.01" id="edit_final" name="edit_final" required>
                   </div>
                   <div class="box">
                       <input type="submit" name="edit_student" value="Update Student">
                   </div>
               </form>
           </div>

           <!-- Table of Students -->
           <table border="1">
               <tr>
                   <th>ID</th>
                   <th>Username</th>
                   <th>Email</th>
                   <th>Course</th>
                   <th>Year and Set</th>
                   <th>Age</th>
                   <th>Category</th>
                   <th>Prelim Grade</th>
                   <th>Midterm Grade</th>
                   <th>Semi-Final Grade</th>
                   <th>Final Grade</th>
                   <th>Actions</th>
               </tr>
               <?php foreach ($students as $student): ?>
               <tr>
                   <td><?php echo $student['id']; ?></td>
                   <td><?php echo $student['username']; ?></td>
                   <td><?php echo $student['email']; ?></td>
                   <td><?php echo $student['course']; ?></td>
                   <td><?php echo $student['year_and_set']; ?></td>
                   <td><?php echo $student['age']; ?></td>
                   <td><?php echo $student['category']; ?></td>
                   <td><?php echo isset($student['prelim']) ? $student['prelim'] : "N/A"; ?></td>
                   <td><?php echo isset($student['midterm']) ? $student['midterm'] : "N/A"; ?></td>
                   <td><?php echo isset($student['semi_final']) ? $student['semi_final'] : "N/A"; ?></td>
                   <td><?php echo isset($student['final']) ? $student['final'] : "N/A"; ?></td>
                   <td>
                       <a href="students.php?delete_id=<?php echo $student['id']; ?>">Delete</a> | 
                       <a href="#" onclick="editStudent(
                           '<?php echo $student['id']; ?>',
                           '<?php echo $student['username']; ?>',
                           '<?php echo $student['email']; ?>',
                           '<?php echo $student['course']; ?>',
                           '<?php echo $student['year_and_set']; ?>',
                           '<?php echo $student['age']; ?>',
                           '<?php echo $student['category']; ?>',
                           '<?php echo isset($student['prelim']) ? $student['prelim'] : ""; ?>',
                           '<?php echo isset($student['midterm']) ? $student['midterm'] : ""; ?>',
                           '<?php echo isset($student['semi_final']) ? $student['semi_final'] : ""; ?>',
                           '<?php echo isset($student['final']) ? $student['final'] : ""; ?>'
                       )">Edit</a>
                   </td>
               </tr>
               <?php endforeach; ?>
           </table>
       </div>
    </main>

    <script>
        function toggleAddStudentForm() {
            var form = document.querySelector('.add-student-form');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        function toggleEditStudentForm() {
            var form = document.querySelector('.edit-student-form');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        function editStudent(id, username, email, course, yearandset, age, category, prelim, midterm, semi_final, final) {
            document.querySelector('.edit-student-form').style.display = 'block';
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_course').value = course;
            document.getElementById('edit_yearandset').value = yearandset;
            document.getElementById('edit_age').value = age;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_prelim').value = prelim;
            document.getElementById('edit_midterm').value = midterm;
            document.getElementById('edit_semi_final').value = semi_final;
            document.getElementById('edit_final').value = final;
        }
    </script>
</body>
</html>
