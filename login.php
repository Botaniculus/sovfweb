<?php
session_start();
require('Db.php');
Db::connect('127.0.0.1', 'ners', 'root', '');
if(isset($_SESSION['user_id'])){
	header('Location: user.php');
	exit();
}

if($_POST)
{
	if($_POST['loginBtn']){
		if(strpos($_POST['loginUsername'], '@')){
			$user = Db::queryOne('
					SELECT user_id, level, password, username FROM users WHERE email=?
			', htmlspecialchars($_POST['loginUsername']));
		}
		else{
			$user = Db::queryOne('
                SELECT user_id, level, password, username FROM users WHERE username=?
			', htmlspecialchars($_POST['loginUsername']));
		}
		
		if(!$user || !password_verify($_POST['loginPassword'], $user['password'])){
			$loginMessage = 'Neplatné uživatelské jméno nebo heslo';
		}
		else{
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['level'] = $user['level'];
			header('Location: user.php');
			exit();
		}
		
	}
	else if($_POST['registerBtn']){
		if($_POST['registerYear'] != date('Y')){
			$registerMessage = 'Zkontrolujte prosím správnost antispamu.';
		
		} else if($_POST['registerPassword'] != $_POST['registerPasswordAgain']){
			$registerMessage = 'Hesla nesouhlasí.';
		}
		else{
			$usernameTaken = Db::querySingle('
				SELECT COUNT(*) FROM users WHERE username=? OR email=? LIMIT 1
			', htmlspecialchars($_POST['registerUsername']), $_POST['registerEmail']);
			
			if(!$usernameTaken){
				$passwordHash = password_hash($_POST['registerPassword'], PASSWORD_DEFAULT);
				Db::query('
					INSERT INTO users (username, password, email) VALUES (?, ?, ?)
				', $_POST['registerUsername'], $passwordHash, $_POST['registerEmail']);
				$_SESSION['user_id'] = Db::getLastId();
				$_SESSION['username'] = $_POST['registerUsername'];
				$_SESSION['level'] = 0;
				//echo('<p>Username: ' . $_POST['registerUsername'] . '</p');
				header('Location: user.php');
				exit();
			}
			else{
				$registerMessage = 'Toto jméno je již obsazené. Vyberte si prosím jiné.';
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Oddíl Velena Fandrlíka | Přihlášení</title>

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
		  <li><a class="active" href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
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
  				<h1>Přihlášení</h1>
  			</header>
  			<section>
				<?php
                    if (isset($loginMessage))
                        echo('<div class="alert alert-danger">' . $loginMessage . '</div>');
                ?>
				<form method="post">
					<div class="form-group">
						Jméno<br />
						<input type="text" name="loginUsername" class="responsive fancy-input" value="<?php if (isset($_POST['loginUsername'])) echo($_POST['loginUsername']);?>"/><br />
						Heslo<br />
						<input type="password" name="loginPassword" class="responsive fancy-input"value="<?php if (isset($_POST['loginPassword'])) echo($_POST['loginPassword']);?>"/><br /><br />
						<input name="loginBtn" type="submit" class="btn btn-primary btn-lg" value="Přihlásit" class="form-control"/>
					</div>
				</form>
			</section>
  		</article>
    </div>
    
    <div class="col-sm-4">
      <article>
		<header><h1>Registrace</h1></header>
		  <section>
			  <?php
                    if (isset($registerMessage))
                        echo('<div class="alert alert-danger">' . $registerMessage . '</div>');
                ?>
		  <form method="post">
			  <div class="form-group">
						Jméno<br />
						<input type="text" name="registerUsername" class="responsive fancy-input" /><br />
						Email<br />
						<input type="email" name="registerEmail" class="responsive fancy-input" /><br />
						Heslo<br />
						<input type="password" name="registerPassword" class="responsive fancy-input"/><br />
						Heslo znovu<br />
						<input type="password" name="registerPasswordAgain" class="responsive fancy-input" /><br />
						Zadejte aktuální rok (antispam)<br />
						<input type="text" name="registerYear" class="responsive fancy-input" /><br /><br />
						<input type="submit" name="registerBtn"class="btn btn-primary btn-lg" value="Registrovat" class="form-control" />
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
