<?php
session_start();
if (!isset($_SESSION['user_id']))
{
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout']))
{
    session_destroy();
    header('Location: login.php');
    exit();
}
require('Db.php');
Db::connect('127.0.0.1', 'ners', 'root', '');
$user = Db::queryOne('
		SELECT *
		FROM users
		WHERE user_id=?
	', $_SESSION['user_id']);

	

if($_POST){
    if($_POST['save']){
        $successfulySaved = Db::query('
            UPDATE users
            SET about=?, email=?, username=?
            WHERE user_id=?
        ', $_POST['about'], $_POST['email'], $_POST['username'], $_SESSION['user_id']);
        
        if($successfulySaved){
            $message='<div class="alert alert-success">Úspěšně uloženo</div>';
            $user = Db::queryOne('
				SELECT *
				FROM users
				WHERE user_id=?
			', $_SESSION['user_id']);
			$_SESSION['username'] = htmlspecialchars($_POST['username']);
        }
        
	}
	else if($_POST['changePassword']){
		if($_POST['newPassword'] == $_POST['newPasswordAgain'] && $_POST['year'] == date('Y')){
			$newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            Db::query('
            UPDATE users
            SET password=?
            WHERE user_id=?
            ', $newPassword, $_SESSION['user_id']);
            $passwordChangeMessage = '<div class="alert alert-success">Heslo bylo úspěšně změněno</div>';
		}
		else{
			$passwordChangeMessage = '<div class="alert alert-danger">Špatně vyplněná hesla nebo rok.</div>';
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Oddíl Velena Fandrlíka | Administrace</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="styl.css" type="text/css"/>
</head>
<body>

<header>
	 <nav class="navbar navbar-inverse navbar-sticky container-fluid">
		<div class="navbar-header">
		  <a class="navbar-brand" href="index.php">SOVF</a>
		</div>
		<ul class="nav navbar-nav">
		  <li><a href="index.php">Úvod</a></li>
		  <li><a href="clanky.php">Články</a></li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
		  <li><a class="active" href="user.php"><span class="glyphicon glyphicon-user"></span> <?= htmlspecialchars($_SESSION['username']) ?></a></li>
		</ul>

	</nav>
	<!--
	<nav class="navbar navbar-default">
		<div class="container-fluid">
		<ul>
			<li><a class="active" href="index.php">Úvod</a></li>
			<li><a href="clanky.php?zapisnik">Zápisník</a></li>
			<li><a href="#">Z historie</a></li>
			<li><a href="clanky.php?osobnosti">Osobnosti</a></li>
			<li><a href="clanky.php">Články</a></li>
			<li><a href="kontakt.php">Kontakt</a></li>
		</ul>
		</div>
	</nav>-->
</header>

<div class="container">
  <div class="jumbotron text-center">
	  <h1>Skautský Oddíl Velena Fandrlíka</h1>
	  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit....</p>
  </div>
  
  <div class="row">
    <div class="col-sm-8">
      <article>
  			<header>
  				<h1>Administrace</h1>
  			</header>
  			<section>
				<p>Vítejte v administraci. Vaše jméno je <?=htmlspecialchars($_SESSION['username'])?> a vaše úroveň je <?=htmlspecialchars($_SESSION['level'])?></p>
				<a href="editor.php" class="btn btn-lg btn-default">Editor článků</a><br /><br />
				<?php
					if(isset($message)){
						echo($message);
					}
				?>
				<form method="post">
					<div class="form-group">
                            Jméno:
                            <br /><input type="text" name="username" class="responsive fancy-input" value="<?= htmlspecialchars($user['username'])?>"><br /><br />
                            Email:
                            <br /><input type="email" name="email" class="responsive fancy-input" value="<?= htmlspecialchars($user['email'])?>"><br /><br />
                            Popisek:
                            <br /><textarea rows="8" name="about" class="fancy-areainput" ><?= htmlspecialchars($user['about'])?></textarea><br /><br />
     
                            <input name="save" type="submit" class="form-control btn btn-primary" value="Uložit" />
                     </div>
                 </form>
				<a href="user.php?logout" class="btn btn-lg btn-default">Odhlásit se</a>
			</section>
  		</article>
    </div>
    
    <div class="col-sm-4">
      <article>
		<header><h1>Mé články</h1></header>
		  <section>
			<p>zde jsou moje články</p>
		  </section>
      </article>
    </div>
    <div class="col-sm-4">
      <article>
		<header><h1>Změna hesla</h1></header>
		  <section>
			<?php
					if(isset($passwordChangeMessage)){
						echo($passwordChangeMessage);
					}
				?>
				<form method="post">
					<div class="form-group">
                            Nové heslo
                            <br /><input type="password" name="newPassword" class="responsive fancy-input" ><br /><br />
                            Nové heslo znovu
                            <br /><input type="password" name="newPasswordAgain" class="responsive fancy-input" ><br /><br />
							Rok
                            <br /><input type="text" name="year" class="responsive fancy-input" ><br /><br />
                            <input name="changePassword" type="submit" class="form-control btn btn-primary" value="Uložit" />
                     </div>
                 </form>
		  </section>
      </article>
    </div>
    
  </div>
</div>

<footer class="text-center">
<p>&copy; SOVF <?php echo date('Y');?></p>
<!--<p class="pata"><a href="http://matejp.jecool.net/alioth/index.php">Webmaster</a></p> -->
</footer>
</body>
</html>