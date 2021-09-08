<!DOCTYPE html>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Bug Archer</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
            </ul>
            <form class="d-flex" action="public/search.php" method="post">
                <input class="ml-auto form-control me-2" type="search" placeholder="Bug ID" aria-label="Search" name="query">
                <input class="ml-auto btn btn-primary" type="submit"></input>
            </form>
            <a name="login" id="" class="btn btn-primary" href="public/login.php" role="button">Login</a>
        </div>
    </div>
</nav>