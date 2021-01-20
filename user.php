<?php
session_start();
if (!isset($_SESSION['user_id']))
{
    header('Location: login.php');
    exit();
}

if($_GET){
	if(isset($_GET['passwordChangeSuccessful']))
		$passwordChangeMessage = '<div class="alert alert-success">Heslo bylo úspěšně změněno</div>';
	
	else if (isset($_GET['logout'])) {
		session_destroy();
		header('Location: login.php');
		exit();
	}
}

require('Db.php');
Db::connect('127.0.0.1', 'ners', 'root', '');
$user = Db::queryOne('
		SELECT *
		FROM users
		WHERE user_id=?
	', $_SESSION['user_id']);

	

if($_POST){
    if(isset($_POST['save'])){
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
	else if(isset($_POST['changePassword'])){
		if($_POST['newPassword'] == $_POST['newPasswordAgain'] && $_POST['year'] == date('Y')){
			$newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            Db::query('
            UPDATE users
            SET password=?
            WHERE user_id=?
            ', $newPassword, $_SESSION['user_id']);
            
            header('Location: user.php?passwordChangeSuccessful');
            exit();
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
		  <?php
			if(!empty($_SESSION['level'])){
				echo('<li><a href="editor.php"><span class="glyphicon glyphicon-font"></span> Editor</a></li>');
			}
		  ?>
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
	  <h1>Skautský oddíl Velena Fanderlika</h1>
	  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit....</p>
  </div>
  
  <div class="row">
    <div class="col-sm-8">
      <article>
  			<header>
  				<h1>Administrace</h1>
  			</header>
  			<section>
				<p>Vítejte v administraci. Vaše jméno je <?= htmlspecialchars($_SESSION['username']) ?> a vaše úroveň je <?= htmlspecialchars($_SESSION['level']) ?>.</p>
				<?php
					if(isset($message)){
						echo($message);
					}
				?>
				<form method="post">
                            <label for="username">Jméno:</label><br />
                            <input type="text" id="username" name="username" class="responsive fancy-input" value="<?= htmlspecialchars($user['username'])?>"><br /><br />
                            
                            <label for="email">Email:</label><br />
                            <input type="email" id="email" name="email" class="responsive fancy-input" value="<?= htmlspecialchars($user['email'])?>"><br /><br />
                            
                            <label for="about">Popisek:</label><br />
                            <textarea rows="8" id="about" name="about" class="fancy-areainput" ><?= htmlspecialchars($user['about'])?></textarea><br /><br />
     
                            <input name="save" type="submit" class="btn btn-primary" value="Uložit" />
                 </form>
                 <br />
				<a href="user.php?logout" class="btn btn-lg btn-default">Odhlásit se</a>
			</section>
  		</article>
    </div>
    
    
    <div class="col-sm-4">
      <article>
		<header><h1>Mé články</h1></header>
		  <section>
			  <?php
				if(!empty($_SESSION['level'])){
					echo('
						<a href="editor.php" class="btn btn-default">Editor článků</a>
					');
				} else{
					echo('<p>Nemáte oprávnění na psaní článků. Požádejte nás, abychom vám ho přidělili v sekci kontakt.</p>');
				}
				?>
		  </section>
      </article>
    </div>
    <div class="col-sm-4">
      <article>
		<header><h1>Změna hesla</h1></header>
		  <section>
			<?php
				$newPassword = (isset($_POST['newPassword'])) ? $_POST['newPassword'] : '';
				$newPasswordAgain = (isset($_POST['newPasswordAgain'])) ? $_POST['newPasswordAgain'] : '';
				$year = (isset($_POST['year'])) ? $_POST['year'] : '';
				
				if(isset($passwordChangeMessage)){
					echo($passwordChangeMessage);
				}
			?>
			<form method="post">
				<label for="newPassword">Nové heslo</label><br />
				<input type="password" id="newPassword" name="newPassword" value="<?= htmlspecialchars($newPassword) ?>" class="responsive fancy-input" ><br /><br />
				
				<label for="newPasswordAgain">Nové heslo znovu</label><br />
				<input type="password" id="newPasswordAgain" name="newPasswordAgain" value="<?= htmlspecialchars($newPasswordAgain) ?>" class="responsive fancy-input" ><br /><br />
				
				<label for="year">Rok</label><br />
				<input type="text" id="year" name="year" value="<?= htmlspecialchars($year) ?>" class="responsive fancy-input" ><br /><br />
				
				<input name="changePassword" type="submit" class="form-control btn btn-primary" value="Uložit" />
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