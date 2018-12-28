<!DOCTYPE html>
<html>
<head>
	<title>Customer Note - {{ $cust_name }}</title>
</head>
<body>
	<h1>Customer Note For - {{ $cust_name }}</h1>
	<h2>Subject - {{ $note_subj }}</h2>
    {!! $description !!}
</body>
</html>