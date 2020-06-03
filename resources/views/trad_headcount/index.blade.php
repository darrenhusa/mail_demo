<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>TRAD Headcount Report</title>
</head>
<body>
  <h1>Fall 2020 Enrolled Students</h1>
  <table border='1'>
    <theader>
      <tr>
        <td>Student ID</td>
        <td>Last</td>
        <td>First</td>
      </tr>
    </theader>
    <tbody>
      @foreach($students as $student)
      <tr>
        <td>{{ $student->DFLT_ID }}</td>
        <td>{{ $student->LAST_NAME }}</td>
        <td>{{ $student->FIRST_NAME }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
