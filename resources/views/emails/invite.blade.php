<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Invite</title>
</head>
<body>
    
    <h1>{{Auth::user()->name}} Invite You For Login</h1>
    
    <p>Your Login Cordential</p>

    <p>Email : {{$user['email']}}</p>
    <p>Password : {{$user['password']}}</p>

</body>
</html>