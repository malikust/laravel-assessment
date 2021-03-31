<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>
</head>
<style>
	.create_account{}
</style>
<body>
    Hello, <br /><br /> 
    
    Click <a href="{{url('/create/account',['id'=>$details['email']]) }}" >here</a> to signup
    <br /> <br /> <br /> 
    <p>Thank you</p>
</body>
</html>