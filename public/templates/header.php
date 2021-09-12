<!DOCTYPE html>
<?php 



?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> 

    <div class="container-fluid">


    <div class = "mx-auto" style = "width:40px;">

    <div class = "icon" >

        <a href = "#" class = "pull-left">

            <img src = "https://i.ibb.co/C1ZJZ6X/32.png"/>

        </a>

    </div>

    </div>

        <a class="navbar-brand"  href="/" >Bug Archer</a>

        <div class="collapse navbar-collapse">
            
            <ul class="navbar-nav">
            

                <li class="nav-item">

                <div class = "mx-auto" style = "width:70px;">

                    <a class="nav-link" href="#" >Home</a>

                </div>

                </li>

                <li class="nav-item">

                <div class = "mx-auto" style = "width:70px;">

                    <a class="nav-link" href="#">About</a>

                </div>

                </li>
            </ul>

            

            <form class="d-flex" action="/public/search.php" method="get">

                <input class="ml-auto form-control me-2" type="search" placeholder="Bug ID" aria-label="Search" name="query">
                
                <div class = "mx-auto">

                    <input class="ml-auto btn btn-primary" type="submit"></input>

                </div>

                }

            </form>
                <?php if(!isset($_SESSION["email"])){
                    echo '<a name="login" id="" class="btn btn-primary" href="/public/login.php" role="button">Login</a>';
                }?>

        </div>
                <?php if(!isset($_SESSION["email"])){
                    echo '<div class = "mx-auto">

                            <button type="button" class="btn btn-primary" href="/public/register.php">Register</button>

                        </div>';
                } ?>

    </div>

</nav>

