<!DOCTYPE html>
<html lang="en">
<head>
  <style>
  body {
    font-family: Arial;
    margin: 20px;
    background-color: #f2f2f2;
}

.course-row {
    margin-bottom: 10px;
}

input, select {
    padding: 5px;
    margin-right: 5px;
}
  </style>
<meta charset="UTF-8">
<title>GPA Calculator</title>
<link rel="stylesheet" href="style.css">
<script src="script.js"></script>
</head>
<body>
  <script> 
  function addCourse() {
    var row = document.createElement('div');
    row.className = 'course-row';

    row.innerHTML =
    '<label>Course:</label>' +
    '<input type="text" name="course[]" required>' +

    '<label>Credits:</label>' +
    '<input type="number" name="credits[]" min="1" required>' +

    '<label>Grade:</label>' +
    '<select name="grade[]">' +
    '<option value="4.0">A</option>' +
    '<option value="3.0">B</option>' +
    '<option value="2.0">C</option>' +
    '<option value="1.0">D</option>' +
    '<option value="0.0">F</option>' +
    '</select>' +

    '<button type="button" onclick="this.parentNode.remove()">Remove</button>';

    document.getElementById('courses').appendChild(row);
}

function validateForm() {
    var courses = document.querySelectorAll('[name="course[]"]');
    var credits = document.querySelectorAll('[name="credits[]"]');

    for (var i = 0; i < courses.length; i++) {
        if (courses[i].value === "") {
            alert("All course fields are required!");
            return false;
        }
    }

    for (var j = 0; j < credits.length; j++) {
        if (isNaN(credits[j].value) || credits[j].value <= 0) {
            alert("Credits must be positive numbers!");
            return false;
        }
    }

    return true;
}
  </script>
<h1>GPA Calculator</h1>

<form action="calculate.php" method="post" onsubmit="return validateForm();">

<div id="courses">
<div class="course-row">
<label>Course:</label>
<input type="text" name="course[]" required>

<label>Credits:</label>
<input type="number" name="credits[]" min="1" required>

<label>Grade:</label>
<select name="grade[]">
<option value="4.0">A</option>
<option value="3.0">B</option>
<option value="2.0">C</option>
<option value="1.0">D</option>
<option value="0.0">F</option>
</select>
</div>
</div>

<button type="button" onclick="addCourse()">+ Add Course</button>
<br><br>
<input type="submit" value="Calculate GPA">
<?php
$result = "";
$tableHtml = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $courses = $_POST['course'] ?? [];
    $credits = $_POST['credits'] ?? [];
    $grades  = $_POST['grade'] ?? [];

    $totalPoints = 0;
    $totalCredits = 0;

    $tableHtml .= "<table border='1' cellpadding='8'>";
    $tableHtml .= "<tr>
                    <th>Course</th>
                    <th>Credits</th>
                    <th>Grade</th>
                    <th>Points</th>
                   </tr>";

    for ($i = 0; $i < count($courses); $i++) {

        $course = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $gr = floatval($grades[$i]);

        if ($cr <= 0) continue;

        $points = $cr * $gr;

        $totalPoints += $points;
        $totalCredits += $cr;

        $tableHtml .= "<tr>
                        <td>$course</td>
                        <td>$cr</td>
                        <td>$gr</td>
                        <td>$points</td>
                       </tr>";
    }

    $tableHtml .= "</table>";

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;

        if ($gpa >= 3.7) $status = "Distinction";
        elseif ($gpa >= 3.0) $status = "Merit";
        elseif ($gpa >= 2.0) $status = "Pass";
        else $status = "Fail";

        $result = "Your GPA is " . number_format($gpa, 2) . " ($status)";
    } else {
        $result = "No valid courses entered.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>GPA Calculator</title>
</head>
<body>

<h1>GPA Calculator</h1>

<?php
if ($result != "") {
    echo $tableHtml;
    echo "<h2>$result</h2>";
}
?>

<form method="post">

Course:
<input type="text" name="course[]" required>

Credits:
<input type="number" name="credits[]" min="1" required>

Grade:
<select name="grade[]">
<option value="4.0">A</option>
<option value="3.0">B</option>
<option value="2.0">C</option>
<option value="1.0">D</option>
<option value="0.0">F</option>
</select>

<br><br>

<input type="submit" value="Calculate">

</form>

</body>
</html>
</form>
</body>
</html>
  
  
