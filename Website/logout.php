<?php
header("refresh:5;url=index.html"); 
session_start();
session_destroy();

echo <<< EOF
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<meta charset="utf-8">
<title>Movie Rater | Logout</title>
</head>
<script language="javascript">
var time_left = 5;
var cinterval;

function time_dec(){
  time_left--;
  document.getElementById('countdown').innerHTML = time_left;
  if(time_left == 0){
    clearInterval(cinterval);
  }
}
cinterval = setInterval('time_dec()', 1000);
</script>
</head>
<body>
<img src="img/neko3.png" alt="cat resting"><br>
You've successfully logged out, redirecting you back to the login page in <span id="countdown">5</span>s<br>
<a href="index.html">Click here if you aren't automatically redirected</a>
</body>
</html>
EOF;
?>
