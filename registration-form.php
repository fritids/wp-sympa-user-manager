<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta charset="utf-8"/>
    <title>Formulaire d'inscription</title>
    <link href="registration-form.css" rel="stylesheet" type="text/css"/>
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  </head>

  <body>

    <hgroup>
      <h1>S'inscrire au Club Rock</h1>
    </hgroup>

    <form action="wp-content/plugins/wp-sympa-user-manager/sympa-add-member.php" method="post">

      <fieldset>
	<legend>Veuillez remplir les champs suivants</legend><br/>
	<label>Nom 
	<input name="last_name" required autofocus/>
	</label><br/>

	<label>Prénom
	<input name="first_name"  required/>
	</label><br/>

	<label>Login
	<input name="user_name" required/>
	</label><br/>

	<label>Mot de passe <input name="user_pass" type="password" required/><br/></label>

	<label>E-mail
	<input name="user_mail" type="email" required/>
	</label><br/>

	<label>Confirmez l'adresse e-mail
	<input type="email" name="user_email_repeat" oninput="check(this)"/>
	</label><br/>

	<label>Etudiant à l'ENS ? 
	<input type="radio" name="member_type" value="ens"/> Oui 
	<input type="radio" name="member_type" value="exterieur"/> Non 
	</label><br/>

	<label>Abonnement à la newsletter? 
	<input name="list_diff" type="radio" value="oui"/>Oui
	<input name="list_diff" type="radio" value="non"/>Non
	</label>

      </fieldset>


      <script>
	function check(input) {
	if (input.value != document.getElementById('email_addr').value) {
	input.setCustomValidity('Les deux adresses e-mail doivent correspondre');
	} else {
	// input is valid -- reset the error message
	input.setCustomValidity('');
	}
	}
      </script>


      <input type="submit" value="Inscription" name="validation" />

    </form>

  </body>
</html>
