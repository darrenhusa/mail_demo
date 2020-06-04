<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>TRAD Headcount Report</title>
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
  <h1>Fall 2020 Full-time TRAD Enrolled Students</h1>
  <table border='1'>
    <theader>
      <tr>
        <td>Student ID</td>
        <td>Last</td>
        <td>First</td>
        <td>Entry Type</td>
        <td>Is AT or SR Athlete</td>
      </tr>
    </theader>
    <tbody>
      @foreach($students as $student)
      <tr>
        <td>{{ $student->DFLT_ID }}</td>
        <td>{{ $student->LAST_NAME }}</td>
        <td>{{ $student->FIRST_NAME }}</td>
        <td>{{ $student->EntryTypeAlt }}</td>
        <td>{{ $student->IsAthlete }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $students->links() }}
</body>
</html>
