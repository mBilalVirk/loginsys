<!DOCTYPE html>
<html lang="en">
<head>
   <style>
   
nav{
    width: 100%;
    
    position: relative;
    z-index: 10;
    
}
#navbar-primary.navbar-default {
  background: transparent;
  border: none;
    box-shadow: none;
    margin-top:10px;
    
}
#navbar-primary.navbar-default .navbar-nav {
  width: 100%;
  text-align: center;
}
#navbar-primary.navbar-default .navbar-nav > li {
  display: inline-block;
  float: none;
}
#navbar-primary.navbar-default .navbar-nav > li > a {
  padding-left: 30px;
  padding-right: 30px;
}

</style>

</head>
<body>
        
  
<nav id="navbar-primary" class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="collapse navbar-collapse" id="navbar-primary-collapse">
      <ul class="nav navbar-nav">
        
        <li><a href="{{ route('dashboard') }}">Home</a></li>
        <li><a href="{{ route('friends') }}">Friends</a></li>
        <li><a href="#">Messages</a></li>
        <li><a href="{{ route('user.profile', auth()->user()->id) }}">Profile</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

</body>
</html>

