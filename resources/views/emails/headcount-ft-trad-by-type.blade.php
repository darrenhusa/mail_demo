<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>CCSJ IR Report Email</title>
</head>
<body>
  <h1>Term = {{ $term }}</h1>
  <h1>{{ $term }} TRAD FT Headcounts by Entry Type and Athletic Status</h1>
  <h2>By Entry Type and by Athletic Status</h2>
  <table border="1">
    <thead>
      <tr>
        <th></th>
        <th>Athlete Status</th>
        <th></th>
        <th></th>
      </tr>
      <tr>
        <th>Entry Type</th>
        <th>TRUE</th>
        <th>FALSE</th>
        <th>Totals</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>continuing/returning</td>
        <td>{{ $data['data11'] }}</td>
        <td>{{ $data['data12'] }}</td>
        <td>{{ $data['data13'] }}</td>
      </tr>
      <tr>
        <tr>
          <td>first-time</td>
          <td>{{ $data['data21'] }}</td>
          <td>{{ $data['data22'] }}</td>
          <td>{{ $data['data23'] }}</td>
        </tr>
      </tr>
      <tr>
        <td>transfer</td>
        <td>{{ $data['data31'] }}</td>
        <td>{{ $data['data32'] }}</td>
        <td>{{ $data['data33'] }}</td>
      </tr>
      <tr>
        <td>Totals</td>
        <td>{{ $data['data41'] }}</td>
        <td>{{ $data['data42'] }}</td>
        <td>{{ $data['data43'] }}</td>
      </tr>
    </tbody>
  </table>

  <h4>where first-time = AH, HS, or GE; transfer = TR, T2, T4, or U2; continuing/returning = CS or RS.</h4>
  <h4>where TRUE = Is Athlete (in Empower AT or SR); FALSE = Is Non-Athlete</h4>

  <address>Darren Henderson<br>
    dhenderson@ccsj.edu</address>
</body>
</html>
