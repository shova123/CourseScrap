<table>
    <thead>
    <tr>
        <th>University Name</th>
        <th>Course Name</th>
        <th>Level Of Study</th>
        <th>Start_date</th>
        <th>Duration</th>
        <th>Fees</th>
        <th>Total Fees</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{ $row['university_name'] }}</td>
            <td>{{ $row['course_name'] }}</td>
            <td>{{ $row['level'] }}</td>
            <td>{{ $row['start_date'] }}</td>
            <td>{{ $row['duration'] }}</td>
            <td>{{ $row['fees'] }}</td>
            <td>{{ $row['total_fees'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>