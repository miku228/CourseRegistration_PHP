<?php 
    include("./Common/header.php"); 
    
    
    include_once "Functions.php";
    include_once "EntityClassLib.php";
    session_start();
    session_destroy();
    
    header("Location: index.php");
    exit();
    
    
?>
<div class="container">
    <h1>Log Out</h1>
    <hr class="solid">
    <p>you successfully logged out.</p>
    
    <!--  Input Form -->
    
</div>
<?php 
    // session_destroy();
    include('./Common/footer.php'); 
?>
