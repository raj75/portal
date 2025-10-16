<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">

<style>

body {
  height: 100vh;
  background-image: radial-gradient(closest-side, #2EA3F2, #000000);
  text-align: center;
  color: #fff;
  font-family: "Helvetica Neue";
  font-size: 16px;
  line-height: 1.5;
}

.logo {
  margin: 40px auto;
  display: block;
}

.navbar-links{
    display: inline-block;
    float: right;
    margin-right: 15px;
    text-transform: uppercase;
}

.custom-navbar {
    padding-top: 15px;
}

h1 {
  font-size: 24px;
  line-height: 40px;
  margin: 0 auto 16px;
  padding: 0 20px;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  letter-spacing: 0.5px;
  font-weight: 600;
  max-width: 600px;
}

p {
  color: #D6DCE0;
  font-weight: 300;
  max-width: 600px;
  margin: 0 auto 24px;
  text-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
  letter-spacing: 0.5px;
  padding: 0 20px;
}
p a {
  color: inherit;
}
p a:hover {
  color: #fff;
}

.button {
  color: #2EA3F2;
  background: #fff;
  border-radius: 3px;
  display: inline-block;
  padding: 12px 48px;
  text-decoration: none;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  transition: all 0.2s ease-out;
}
.button:hover {
  margin-top: -2px;
  box-shadow: 0 6px 24px rgba(0, 0, 0, 0.4);
}

.browser {
  width: 400px;
  min-width: 200px;
  min-height: 264px;
  background: #FFFFFF;
  box-shadow: 0 40px 80px 0 rgba(0, 0, 0, 0.25);
  border-radius: 3px;
  animation: bobble 1.8s ease-in-out infinite;
  position: relative;
  left: 50%;
  margin-left: -200px;
}
.browser .controls {
  width: 100%;
  height: 32px;
  background: #E8ECEF;
  border-radius: 3px 3px 0 0;
  box-sizing: border-box;
  padding: 10px 12px;
}
.browser .controls i {
  height: 12px;
  width: 12px;
  border-radius: 100%;
  display: block;
  float: left;
  background: #D6DCE0;
  margin-right: 8px;
}



@keyframes bobble {
  0%, 100% {
    margin-top: 40px;
    margin-bottom: 48px;
    box-shadow: 0 40px 80px rgba(0, 0, 0, 0.24);
  }
  50% {
    margin-top: 54px;
    margin-bottom: 34px;
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.4);
  }
}
@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
  .browser {
    width: 280px;
    margin-left: -140px;
  }
  .browser .eye {
    left: 30%;
  }
  .browser .eye + .eye {
    left: auto;
    right: 30%;
  }
}

.btn-request{
    padding: 10px 25px;
    border: 1px solid #FFCB39;
    border-radius: 100px;
    font-weight: 400;
}

.btn-request:hover {
    background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
}

.btn-go-home {
    position: relative;
    z-index: 200;
    margin: 15px auto;
    width: 100px;
    padding: 10px 15px;
    border: 1px solid #FFCB39;
    border-radius: 100px;
    font-weight: 400;
    display: block;
    color: white;
    text-align: center;
    text-decoration: none;
    letter-spacing : 2px;
    font-size: 11px;

    -webkit-transition: all 0.3s ease-in;
    -moz-transition: all 0.3s ease-in;
    -ms-transition: all 0.3s ease-in;
    -o-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
}

.btn-go-home:hover {
    background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
}


li {
    float: left;
    padding: 0px 15px;
}

li a {
    display: block;
    color: white;
    text-align: center;
    text-decoration: none;
    letter-spacing : 2px;
    font-size: 12px;

    -webkit-transition: all 0.3s ease-in;
    -moz-transition: all 0.3s ease-in;
    -ms-transition: all 0.3s ease-in;
    -o-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
}

li a:hover {
    color: #ffcb39;
}

#logo{
	position: fixed;
	top:25;
}

</style>

</head>
<body>

<div id="logo">
   <img src="/assets/img/error/error_logo.png" width="250px">
</div>

<div class="custom-navbar">
   <div class="navbar-links">
      <ul>
	<li><a href="mailto:support@vervantis.com" class="btn-request" target="_blank">Contact us</a></li>
      </ul>
   </div>
</div>

<br><br><br><br>


<div class="browser">
  <div class="controls">
    <i></i>
    <i></i>
    <i></i>

    <img src="/assets/img/error/500.png" class="logo" width="300">

  </div>


</div>

<h1>Unfortunately, something has gone wrong.</h1>
<p>We're unable to fulfill your request. Rest assured we have been notified and are looking into the issue. Please try again by refreshing your browser.</p>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script><div id="bcl"></div>
<script>
setTimeout(function(){
   window.location.replace("http://www.vervantis.com");
}, 30000)
</script>
</body>
</html>
