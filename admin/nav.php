<div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-fixed-top navbar-light justify-content-center" style="background-color: #e3f2fd;">
            <a class="navbar-brand" href="index.php">Cookie Club</a>
             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#colapseNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="colapseNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="news-read.php" class="nav-link">News</a></li>
                    <li class="nav-item"><a href="products-read.php" class="nav-link">Catalog</a></li>
                    <li class="nav-item"><a href="genre-read.php" class="nav-link">Genres</a></li>
                    <li class="nav-item"><a href="user-read.php" class="nav-link">Users</a></li>
                </ul>
            </div>
            <div class="nav justify-content-end">
                <div class="navbar-nav mt-lg-2">
                   <?php if(isset($_SESSION['user'])){ //if user from the session is set (see login.php)?>
                        Hello, <?php echo $_SESSION['user']['username']; ?>
                        <a href="profile.php" class="mx-2">Profile</a>
                        <a href="logout.php" class="mx-2"><span class="fa fa-sign-out" aria-hidden="true"></span>Log out</a>
                    <?php } ?>
                </div>
            </div>
        </nav>
    </div>