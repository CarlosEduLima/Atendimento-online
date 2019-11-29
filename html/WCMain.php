<?php session_start(); ?>
<html>

<head>
    <title>Web Collaboration Templates</title>
    <link href="../css/styles.css" rel="stylesheet" type="text/css">
    <meta http-equiv="Content-Type" content="text/html">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta charset="UTF-8">
</head>

<body link="#FFFFFF" vlink="#FFFFFF">
    <section class="form-section">
        <div class="form-wrapper">
            <form METHOD="POST" ACTION="../html/portugues/Formulario.php" ID="Form1" NAME="Form1" target="_self">
                <?php
                // verifica se a variavel session['invalido'] existe, se existir mostra o erro
                if (isset($_SESSION["invalido"])) {
                    $dados_invalidos = $_SESSION["invalido"] ;
                    echo "$dados_invalidos";
                }
                ?>
                <div class="input-block">
                    <h1>Para solicitar uma sessão de colaboração na Web, preencha os dados abaixo.</h1>
                </div>
                <div class="input-block" align="center">
                    <label for="varCustomerName">Nick</label>
                    <input type="text" name="username" ID="varCustomerName" maxLength="30" required />
                </div>

                <div class="input-block" align="center">
                    <label for="varCustomerName">Senha de Rede</label>
                    <input type="password" name="password" ID="varCustomerName" required />
                </div>
                <input type="submit" class="btn" value="Solicitar Atendimento de Informática" onclick="fechar" />
            </form>
        </div>
    </section>
    </div>
</body>

</html>
<?php unset($_SESSION["invalido"]); ?>