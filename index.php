<?php
session_start();
mb_internal_encoding("UTF-8");


$hlaska = '';
$ok=false;
    if ($_POST) // V poli _POST něco je, odeslal se formulář
    {

        if (isset($_POST['senderName']) && $_POST['senderName'] &&
      			isset($_POST['senderEmail']) && $_POST['senderEmail'] &&
      			isset($_POST['senderMessage']) && $_POST['senderMessage'] &&
      			isset($_POST['year']) && $_POST['year'] == date('Y'))
        {
            $header = 'From:' . $_POST['senderEmail'];
            $header .= "\nMIME-Version: 1.0\n";
            $header .= "Content-Type: text/html; charset=\"utf-8\"\n";
            $address = 'bofin@skaut.cz';
            $subject = 'Nová zpráva z mailformu';
            
            if (mb_send_mail($address, $subject, $_POST['senderMessage'], $header))
            {
            $hlaska = '<div class="alert alert-success">' . '<strong>Zpráva byla úspěšně odeslána.</strong> Budeme se Vám snažit odpovědět co nejdříve.' . '</div>';
            $ok=true;

            }
            else
                $hlaska = '<div class="alert alert-danger">' . '<strong>Email se nepodařilo odeslat.</strong>' . '</div>';

        }
        else
            $hlaska = '<div class="alert alert-danger">' . 'Formulář <strong>není správně vyplněný!</strong>' . '</div>';
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Oddíl Velena Fandrlíka</title>

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
		  <li><a class="active" href="index.php">Úvod</a></li>
		  <li><a href="clanky.php">Články</a></li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
		  <!--<li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>-->
		  <?php 
			if(isset($_SESSION['user_id']))
				echo('<li><a href="user.php"><span class="glyphicon glyphicon-user"></span> ' . htmlspecialchars($_SESSION['username'])  . '</a></li>');
			else
				echo('<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>');
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
  				<h1>O nás</h1>
  			</header>
  			<section>
  				<p> Skautský oddíl nese ve svém názvu jméno bývalého starosty Junáka, Velena Fanderlika, který v říjnu roku 1948 ilegálně opustil Československo a v zahraničí převzal vedení československých skautů a skautek v exilu.<hr></p>
  <p><em>Skautský oddíl Velena Fanderlika (zkr. SOVF) nemá po organizační stránce nic společného s Junákem – svazem skautů a skautek ČR či s jinými existujícími skautskými organizacemi v České republice. Jedná se o samostatnou oldskautskou organizaci (občanské sdružení), které zaregistrovalo své stanovy u MV ČR. Oddíl vznikl 14. června 1994 v Hradci Králové a dnes má rozmanitou (neziskovou) skautskou činnost na mnoha místech ČR.</em><hr>
  </p><p>Členy SOVF se mohou stát oldskauti a oldskautky ze všech existujících skautských organizací, pokud splňují podmínky dané platnými stanovami SOVF pro členství. Proto členové SOVF aktivně pracují v různých činovnických funkcích i v jiných skautských organizacích. Stát se členem SOVF je ctí pro každého, neboť členstvo SOVF tvoří ti bratři a sestry, kteří ani v těžkých dobách komunistické totality nezradili skautskou myšlenku. Pro své pevné životní a politické postoje jsou mnozí členové SOVF „trnem v oku“ bývalým funkcionářům SSM, KSČ a tajným spolupracovníkům StB.<hr>
  </p><p>Oddílový pokřik: <blockquote class="blockquote-reverse"><i>Před zlem nikdy neutíká skaut Velena Fanderlika!</i></blockquote></p>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
          <a data-toggle="collapse" href="#contactCollapse">Kontakt</a>
        </h3>
      </div>

      <div id="contactCollapse" class="panel-collapse collapse">
        <div class="panel-body">
          <article>
            <header><h1>Adresa</h1></header>
            <section>
              <address>
Občanské sdružení „Skautský oddíl Velena Fanderlika“
Bohunická 43
619 00 Brno</address>
            </section>
          </article>
<article>
  <header>
    <h1>Kontakt</h1>
  </header>
  <section>
    <form method="POST">
		<div class="form-group">
        <?php if(!$ok){
			$senderName = (isset($_POST['senderName'])) ? $_POST['senderName'] : '';
			$senderEmail = (isset($_POST['senderEmail'])) ? $_POST['senderEmail'] : '';
			$senderMessage = (isset($_POST['senderMessage'])) ? $_POST['senderMessage'] : '';
			$year = (isset($_POST['year'])) ? $_POST['year'] : '';
        }
        ?>
        <?php
        if ($hlaska!==''){
            echo($hlaska);
            echo("
            <script>
            $('.collapse').collapse('show');
            </script>
            ");
        }
        ?>
        Vaše jméno<br />
        <input class="responsive fancy-input" placeholder="Vaše jméno" name="senderName" type="text" required="required" value="<?= htmlspecialchars($senderName) ?>" />

        <br /><br />Váš email<br />
        <input class="responsive fancy-input" placeholder="Váš email" name="senderEmail" type="email" required="required" value="<?= htmlspecialchars($senderEmail) ?>" />

        <br /><br />Aktuální rok<br />
                <input class="responsive fancy-input" placeholder="Aktuální rok pro ověření" name="year" type="text" value="<?= htmlspecialchars($year) ?>" required="required"  />
        <br /><br />Zpráva<br />
        <textarea class="responsive fancy-areainput" placeholder="Napište sem zprávu" rows="8" required="required" name="senderMessage"><?= htmlspecialchars($senderMessage) ?></textarea>

        <br /><br />
        <input type="submit" class="btn btn-primary form-control" value="Odeslat" />
		</div>
    </form>
  </section>

</article>
      </div>
      </div>
    </div>
        </section>


  		</article>
    </div>
    <div class="col-sm-4">
      <article>
      <header><h1>Nejnovější články</h1></header>
      <section>
      <b>Veřejná konference členům SOVF je zde:</b>
      <br>
      <iframe id="forum_embed"
      src="javascript:void(0)"
      scrolling="no"
      frameborder="3"
      >
      </iframe>
      <script type="text/javascript">
      document.getElementById('forum_embed').src =
      'https://groups.google.com/a/skaut.cz/forum/embed/?place=forum/sovf#!forum/sovf'
      + '&showsearch=true&showpopout=true&showtabs=false'
      + '&parenturl=' + encodeURIComponent(window.location.href);
      </script>

      </section>
      </article>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <article>
      <header><h1>Žofka</h1></header>
      <section>
      <img class="img-responsive img-rounded responsive" src="obrazky/zovf.jpg">
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
