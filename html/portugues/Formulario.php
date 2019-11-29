<?php
session_start();
$nick_usuario =  strip_tags(trim($_POST['username']));
$senha_usuario =  strip_tags(trim($_POST['password']));;
$ldap_con = ldap_connect("stj.gov.br");
$ldaprdn = $nick_usuario . "@stj.jus.br";
ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);
$ramal;
$lotacao;
$nome;
$nick;

if (ldap_bind($ldap_con, $ldaprdn, $senha_usuario)) {
    $filter = "(sAMAccountName=$nick_usuario)";
    $search = ldap_search($ldap_con, 'DC=stj, DC=gov, DC=br', $filter) or exit("Conexão Falhou");
    $entries = ldap_get_entries($ldap_con, $search);
    for ($i = 0; $i < $entries["count"]; $i++) {
        if ($entries['count'] > 1)
            break;

        $ramal = $entries[$i]["telephonenumber"][0];
        $nome = $entries[$i]["displayname"][0];
        $nick = $nick_usuario;
        $lotacao = $entries[$i]["department"][0];
        /*echo '<pre>';
        var_dump($entries);
        echo '</pre>';*/
    }
   
    @ldap_close($ldap);
    
    
} else {
   $_SESSION["invalido"] = '<script type="text/javascript">alert("Usuário não encontrado!");</script>';
        header("location: ../WCMain.php");

}
?>
    <!DOCTYPE html>
    <HTML>
    <META http-equiv="Content-Type" content="text/html; charset=utf-8">
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Web Collaboration - Request Web Session</title>
    <link href="../../css/form.css" rel="stylesheet" type="text/css">
    <SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>
        //Globle variables

        //**********************************************************
        //trim
        //**********************************************************
        String.prototype.trim = function() {
            return this.replace(/^\s*/, "").replace(/\s*$/, "");
        }

        function alertKey(){
            alert(document.Form1.Key3.value);
            return;  
        }


        //**********************************************************
        //Check Source field
        //**********************************************************
        function checkForm() {

            //Check Source field
            varMySource = document.Form1.varSource.value;
            if (!IsValidAscII(varMySource)) {
                //if (ContainProhibitedWords(varMySource)) {
                return false;
                //}
            }

            //Check Destination field
            varMySource = document.Form1.varDestination.value;
            if (!IsValidAscII(varMySource)) {
                //if (ContainProhibitedWords(varMySource)) {
                return false;
                //}
            }

            win = window.open('', 'HPPC70_CHAT', 'left=20,top=20,width=500,height=500,toolbar=0,resizable=1, scrollbars=1');
            document.Form1.submit();
            history.back();
            win.focus();
            return true;
        };

        //**********************************************************
        //IsValidAscII?
        //Asterisk (* 42), Percentage sign (% 37), coma (, 44), Accent grave (` 96), 
        //Underscore (_ 95), Double quotes (" 34), Exclamation mark (! 33),PipeLine(| 124)
        //any non-ASCII/Non-printable characters.
        //**********************************************************
        function IsValidAscII(inputString) {
            var bRC = true;
            var temString = "";
            var asciiNum = 0;

            if (inputString == "" || inputString == null) {
                return bRC;
            }

            temString = inputString;
            for (var i = 0; i < temString.length; i++) {
                asciiNum = temString.charCodeAt(i);
                if ((asciiNum > 31 && asciiNum < 256)) {
                    if ((asciiNum == 34) || (asciiNum == 42) ||
                        (asciiNum == 44) || (asciiNum == 33) || (asciiNum == 37) ||
                        (asciiNum == 95) || (asciiNum == 96) || (asciiNum == 124) ||
                        (asciiNum == 127)) {
                        bRC = false;
                        alert("Character " + String.fromCharCode(asciiNum) + " is not allowed!");
                        break;
                    };
                    continue;
                } else {
                    bRC = false;
                    alert("Only ASCII characters are allowed!");
                    break;
                }
            } //for

            return bRC;
        }


        //**********************************************************
        //ContainProhibitedWords?
        //-	Add 
        //-	Drop 
        //-	Create 
        //-	Insert 
        //-	Select 
        //-	Update 
        //-	Replace 
        //-	Delete
        //**********************************************************
        function ContainProhibitedWords(inputString) {
            var bRC = false;
            var temString = "";
            if (inputString != "" || inputString != null) {
                temString = inputString.toUpperCase();
                temString = " " + temString.trim() + " ";
                if ((temString.indexOf(" ADD ") != -1) ||
                    (temString.indexOf(" DROP ") != -1) ||
                    (temString.indexOf(" CREATE ") != -1) ||
                    (temString.indexOf(" INSERT ") != -1) ||
                    (temString.indexOf(" SELECT ") != -1) ||
                    (temString.indexOf(" UPDATE ") != -1) ||
                    (temString.indexOf(" REPLACE ") != -1) ||
                    (temString.indexOf(" DELETE ") != -1)) {
                    alert("Found prohibited words!");
                    bRC = true;
                }
            }
            return bRC;
        }

        //-->
    </SCRIPT>

    <BODY>


        <section class="form-section">


            <div class="form-wrapper">
                <form METHOD="POST" ACTION="<WCSERVLET>" ID="Form1" NAME="Form1" target="HPPC70_CHAT">
                    <p>
                        <input type="hidden" name="varUserRequest" value="REQ_JS_START">
                        <input type="hidden" name="varUserLanguage" value="english">
                        <input type="hidden" name="varHPPCLanguage" value="English">
                        <input type="hidden" name="varSessionPriority" value="10">
                        <input type="hidden" name="varBusinessUnitName" value="<WCBUSINESSUNITNAME>">
                    </p>

                    <div class="title-block">
                        <h1> Para nos ajudar, forneça as seguintes informações:</h1>
                    </div>

                    <div class="input-block1">
                        <label for="Key1">Nick</label>
                        <input type="text" class="nick" NAME="Key1" ID="Key1" maxLength="30" readonly=“true” value="<?php echo $nick; ?>" />
                    </div>

                    <div class="input-block2">
                        <label for="varCustomerName">Nome</label>
                        <input type="text" NAME="varCustomerName" ID="varCustomerName" maxLength="30" readonly=“true” value="<?php echo $nome; ?>" />
                    </div>

                    <div class="input-block3">
                        <label for="Key2">Ramal</label>
                        <input type="text" class="ramal" name="Key2" class="t1Label" id="Key2" readonly=“true” maxLength="20" value="<?php echo $ramal; ?>" />
                    </div>

                    <div class="input-block4">
                        <label for="Source">Lotação</label>
                        <input type="text" name="varSource" id="Source" maxLength="20" readonly=“true” value="<?php echo $lotacao; ?>" />
                    </div>

                    <div class="select-block">
                        <label for="Key3">Selecione o canal para sua solicitação</label>
                        <select id="Key3" name="key3" required>
                        <option value="">Selecione</option>
                        <option value="Informações Processuais">Informações Processuais</option>
                        <option value="Jurisprudência">Jurisprudência</option>
                        <option value="Ouvidoria">Ouvidoria</option>
                        <option value="Serviços de informática">Serviços de Informática</option>
                        <option value="Serviços Administrativos">Serviços Administrativos</option>
                       </select>
                    </div>

                    <div class="textarea-block">
                        <label for="varCaption">Descreva sua solicitação</label>
                        <textarea name="varCaption" cols="60" rows="6" wrap="VIRTUAL" id="varCaption" required></textarea>
                    </div>

                    <input name="Submit" type="submit" class="btn" id="Submit1" onClick="return checkForm();" value="Iniciar Atendimento">
                    
                </form>
            </div>
        </section>





        <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="t1Section" scope="col">
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <td class="infoSmall">You have requested a live Web Collaboration session. To help us further assist you, please provide the following information. </td>
                    </tr>
                    <tr>
                        <td>
                            <FORM METHOD="get" ACTION="<WCSERVLET>" ID="Form1" NAME="Form1" target="HPPC70_CHAT">
                                <p>
                                    <input type="hidden" name="varUserRequest" value="REQ_JS_START">
                                    <input type="hidden" name="varUserLanguage" value="english">
                                    <input type="hidden" name="varHPPCLanguage" value="English">
                                    <input type="hidden" name="varSessionPriority" value="10">
                                    <input type="hidden" name="varBusinessUnitName" value="<WCBUSINESSUNITNAME>">
                                </p>
                                <p class="t1Group">Request Information </p>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr class="t1Label">
                                        <td width="32%">Name:</td>
                                        <td width="68%">
                                            <INPUT NAME="varCustomerName" class="t1Label" ID="varCustomerName" value="Name" maxLength="30">
                                        </td>
                                    </tr>
                                    <tr class="t1Label">
                                        <td>What is your question?</td>
                                        <td><textarea name="varCaption" cols="60" rows="2" wrap="VIRTUAL" class="t1Label" id="varCaption">Can you tell me about...</textarea></td>
                                    </tr>
                                </table>
                                <p class="t1Group">Other Information </p>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr class="t1Label">
                                        <td>Source (optional): </td>
                                        <td>
                                            <INPUT name="varSource" class="t1Label" id="Source" maxLength="20">
                                        </td>
                                    </tr>
                                    <tr class="t1Label">
                                        <td>Destination (optional):</td>
                                        <td>
                                            <INPUT name="varDestination" class="t1Label" id="varDestination" size=30 maxLength="40">
                                        </td>
                                    </tr>
                                    <tr class="t1Label">
                                        <td width="32%">Key 1: </td>
                                        <td width="68%">
                                            <INPUT name="Key1" class="t1Label" id="Key1" size="50" maxLength="50">
                                        </td>
                                    </tr>
                                    <tr class="t1Label">
                                        <td>Key 2: </td>
                                        <td>
                                            <INPUT name="Key2" class="t1Label" id="Key2" size="50" maxLength="50">
                                        </td>
                                    </tr>
                                    <tr class="t1Label">
                                        <td>Key 3 : </td>
                                        <td><input name="Key3" class="t1Label" id="Key3" size="50" maxlength="50"></td>
                                    </tr>
                                    <tr class="t1Label">
                                        <td>&nbsp;</td>
                                        <td><input name="Submit" type="button" class="bButton" id="Submit1" onClick="return checkForm();" value="Request Session">
                                            <input name="Reset1" type="reset" class="bButton" id="Reset1" value="Reset"></td>
                                    </tr>
                                </table>
                                <P>
                                    <CENTER>
                                    </CENTER>
                            </FORM>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>-->
    </BODY>

    </HTML>