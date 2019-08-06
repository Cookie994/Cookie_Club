<?php session_start(); ?> <!-- Because this is an includes file, I had to start session here also-->
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-fixed-top navbar-light justify-content-center" style="background-color: #e3f2fd;">
            <a class="navbar-brand" href="index.php">Cookie Club</a>
             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#colapseNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="colapseNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="catalog.php" class="nav-link">Catalog</a></li>
                    <li class="nav-item"><a href="pricelist.php" class="nav-link">Pricelist</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                </ul>
            </div>
            <script>
                //to clear search options
                function clr() {
                    $('#query').val("");
                    $('#textbox-clr').text("");
                    $('#livesearch').css({"display":"none"});	
                    //$("#query").focus();
                 }

                //to show search options
                function Search(){
                    var q = document.getElementById('query').value;
                    var xhttp = new XMLHttpRequest();

                    xhttp.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            document.getElementById("livesearch").innerHTML = this.responseText;
                           document.getElementById("livesearch").style.display="block";
                            $('#textbox-clr').text("X");
                        }
                }
                xhttp.open('GET', 'search.php?search='+q, true);
                xhttp.send();
                }
            </script>
            <div class="nav justify-content-end">
                <form class="from-inline" action="search.php" method="get">
                   <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search product" name="search" id="query" onkeyup="Search(this.value)" autocomplete="off">
                        <button type="button" class="textbox-clr btn btn-light" id="textbox-clr" onclick="clr()" style="background-color: #e3f2fd; border: none;"></button>
                        <button class="btn btn-light" type="submit" style="background-color: #e3f2fd;">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
                <div class="navbar-nav mt-lg-2">
                    <?php if(isset($_SESSION['user'])){ //if user from the session is set (see login.php)?>
                        Hello, <?php echo $_SESSION['user']['username'] ?>
                        <a href="profile.php" class="mx-2">Profile</a>
                        <a href="logout.php" class="mx-2">Log out</a>
                    <?php } else{?>
                        <a href="signup.php" class="mx-2"><span class="fa fa-user-plus" aria-hidden="true"></span> Sign Up</a>
                        <a href="login.php" class="mx-2"><span class="fa fa-sign-in" aria-hidden="true"></span> Sign In</a>
                    <?php } ?>
                </div>
            </div>
        </nav>
        <div id="livesearch"></div><!-- This div is for all search options to put in-->
    </div>