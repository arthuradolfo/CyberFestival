<!DOCTYPE html>
<html>
	<head>
		<title>Cyber Festival</title>
		<link rel="stylesheet" href="login.css" />
	<script language="javascript">
		var img = 1;
		var opacityDiv = 1;

		function transicaoBackground() {
			var content = document.getElementById("background");
			var content1 = document.getElementById("background1");
			content.style.opacity = 1;
			content1.style.opacity = opacityDiv;
			opacityDiv=opacityDiv-0.01;
			if (opacityDiv <= 0) {
				content1.style.background = "url('imgCadastro"+(img)+".jpg')";
				content1.style.backgroundSize = "100%";
				opacityDiv = 1;
				content1.style.opacity = opacityDiv;
				img++;
				if (img > 4) {
					img = 1;
				}
				clearInterval(window.t);
			}
			
		}

		function trocaBackground() {
			var content = document.getElementById("background");
			content.style.background = "url('imgCadastro"+img+".jpg')";
			content.style.backgroundSize = "100%";
			clearInterval(window.t);
			t = setInterval("transicaoBackground()", 1);
		}

		function chamaTrocaBackground() {
			clearTimeout(window.t1);
			t1 = setInterval("trocaBackground()", 7000);
		}

		function preloader()

                {

                        heavyImage = new Image();

                        heavyImage.src = "imgCadastro2.jpg";

                }



		function preloader1()

                {

                        heavyImage = new Image();

                        heavyImage.src = "imgCadastro3.jpg";

                }



		function preloader2()

                {

                        heavyImage = new Image();

                        heavyImage.src = "imgCadastro4.jpg";

                }


	</script>
	<style>
		.formCampo {
			float: left; 
			width: 30%; 
			height: 50px; 
			font-family: cursive; 
			font-size: 25px; 
			color: rgba(100, 130, 255, 0.7); 
			font-weight: 900;
			line-height: 50px;
			text-shadow: 0 0 0.05em #333, 0 0 0.05em #333;
			margin-bottom: 10px;
		}
		.formInput {
			float: right;
			margin-bottom: 10px;
			width: 70%; 
			text-align: right;
		}
	</style>
</head>
	<body onload="chamaTrocaBackground(); preloader(); preloader1(); preloader2();">
		<header>
		</header>
		<div id="section">
			<div id="section-column">
				<div id="background">
				<div id="background1">
				</div>
				</div>
				<div id="content-section">
					<p style="padding-left: 40%; padding-top: 3%; font-size: 50px; font-family: cursive; margin: 0; color: #fff; text-shadow: 0 0 0.2em #333, 0 0 0.2em #333;">Torne-se um astro! Cadastre-se!</p>
					<div id="register-content">
						<div id="register-form">
							<form action="cadastrar.php" method="POST">
								<div class="formCampo">Nome artístico:</div><div class="formInput"><input type="text" placeholder="Zé da Viola..."></div>
								<div class="formCampo">Nome de usuário:</div><div class="formInput"><input type="text" placeholder="ze_viola..."></div>
								<div class="formCampo">Email:</div><div class="formInput"><input type="text" placeholder="ze_viola@violeiro.com"></div>
								<div class="formCampo">Senha:</div><div class="formInput"><input type="password" placeholder="******"></div>
								<div class="formCampo">Repetir senha:</div><div class="formInput"><input type="password" placeholder="******"></div>
								Cantor Solo: <input type="radio" name="tipoUsuario">Banda: <input type="radio" name="tipoUsuario">
								<div style="width: 100%; text-align: center;"><input type="submit" value="Cadastrar"></div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer>
		</footer>
	</body>
</html>