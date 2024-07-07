Create database IPT;
CREATE TABLE users(
    Id int PRIMARY KEY AUTO_INCREMENT,
    Username varchar(200),
    Email varchar(255),
    Course varchar(255),
    YearandSet varchar(255),
    Age int,
    Password varchar(255)
);

CREATE TABLE student_grades (
    student_id INT NOT NULL,
    prelim FLOAT DEFAULT 0,
    midterm FLOAT DEFAULT 0,
    semifinal FLOAT DEFAULT 0,
    final FLOAT DEFAULT 0,
    PRIMARY KEY (student_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    email VARCHAR(255),
    course VARCHAR(255),
    year_and_set VARCHAR(255),
    age INT
);

ALTER TABLE students ADD COLUMN category VARCHAR(255) NOT NULL;
ALTER TABLE students ADD category VARCHAR(50);