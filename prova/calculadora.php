<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Calculadora</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
    
    .btn {
        height: 100%;
        width: 100%;
    }

    .tbGeral{
      color: white;
      border-collapse: collapse;
      height: 100%;
    }

    .colunaTab{
      border: 0px;
      width: 100px;
      height: 68px;     
    }

    #total {
        width: 98%;
        height: 100%;
        font-size: 100%;
        text-align: right;
    }

    </style>
</head>
<body>
    

    <?php 
        session_start();
       

        $_SESSION['calculadora'] = $_SESSION['calculadora'] . $_POST[ 'botao'];  

        $bConcatena = true;
        $sPC = substr($_SESSION['calculadora'], 0, 1);
        $bPrimeiroCharIsOperador = isCharOperador($sPC);

     
        if ($bPrimeiroCharIsOperador || $_POST[ 'botao'] == '=') {
            $bConcatena = false;
        }

        
        $sUltimoCaractereDigitado = $_POST['botao'];
        $iTotalChar = strlen($_SESSION['calculadora']);

        if($iTotalChar > 1) {
            $bPenultimoCharOperador = isCharOperador($_SESSION['calculadora'][$iTotalChar-2]);
            $bUltimoCharOperador = isCharOperador($_SESSION['calculadora'][$iTotalChar-1]);
            
            if($bPenultimoCharOperador && $bUltimoCharOperador) {
                $sNovaString = substr($_SESSION['calculadora'],0,-2);
                $_SESSION['calculadora'] = $sNovaString . $sUltimoCaractereDigitado;
            }
        }
            
        if($bConcatena) {
            if($_POST['botao'] !== 'C') {
                $itotal = $_SESSION['calculadora'];    
            }
        }


        switch ($_POST['botao']) {
            case 'C':
                $_SESSION['calculadora'] = '';
                session_destroy();
                break;
            case '=':
                global $itotal;
                $itotal = number_format(resultado(),2,",",".");
                $_SESSION['calculadora'] = resultado();
                break;
        }
        
        function isCharOperador($sChar) {
            return $sChar == '/' || $sChar == 'x' || $sChar == '-' || $sChar == '+' || $sChar == ',' || $sChar == '=';
        }

        function isCharOperadorMatematico($sChar) {
            return $sChar == '/' || $sChar == 'x' || $sChar == '-' || $sChar == '+';
        }

        function resultado() {
            $aArray = tratamentoString();
            
            $aArray = realizaCalculo($aArray, 'x');
            $aArray = realizaCalculo($aArray, '/');
            $aArray = realizaCalculo($aArray, '-');
            $aArray = realizaCalculo($aArray, '+');

            return $aArray[0];
        }    

        function tratamentoString() {
            $sString = $_SESSION['calculadora'];
            $iTotal = strlen($sString);

            for ($i=0; $i < $iTotal ; $i++) { 
                if($sString[$i] == ',') {
                    $sString[$i] = '.';
                }
            }

            if(isCharOperador($sString[strlen($sString)-1])) {
                $sString = substr($sString, 0,-1);
            } 
            
            preg_match_all('#([0-9.]+|[+-/x])#', $sString, $aMatches);

            return $aMatches[1];
        }

        function realizaCalculo($aArray, $sOperador) {
            $bContinuaCalculo = false;

            do {
                foreach ($aArray as $key => $value) {
                    if($value == $sOperador) {
                        $bContinuaCalculo = true;
                        $iAnt = $aArray[$key-1];
                        $iPos = $aArray[$key+1];
                        switch ($sOperador) {
                            case 'x':
                                $aArray[$key] = $iAnt * $iPos;
                                break;
                            case '/':
                                $aArray[$key] = $iAnt / $iPos;
                                break;
                            case '+':
                                $aArray[$key] = $iAnt + $iPos;
                                break;
                            case '-':
                                $aArray[$key] = $iAnt - $iPos;
                                break;
                        }

                        unset($aArray[$key-1]);
                        unset($aArray[$key+1]);
                    } else {
                        $bContinuaCalculo = false;
                    }
                }
            } while($bContinuaCalculo);
       
            $aNewArray = reoordenaArray($aArray);

            return $aNewArray;
        }

        function reoordenaArray($aArray) {
            $aNewArray = [];
            foreach($aArray as $key => $value) {
                $aNewArray[] = $value;
            }

            return $aNewArray;
        }

        
    ?>

<div id="calculator">
        <form method="POST">
            <table style="border: 1px solid black" class="tbGeral">
                <tr>
                    <td colspan="4" class="colunaTab"><input value="<?=$itotal?>" type="text" name="total" id="total"/></td>
                <tr>
                <tr>
                    <td class="colunaTab"><input type="submit" name="botao" value="C" class="btn"></td>
                    <td class="colunaTab"><button class="btn" disabled></button></td>
                    <td class="colunaTab"><button class="btn" disabled></button></td>
                    <td class="colunaTab"><input type="submit" name="botao" value="/" class="btn"></td>
                </tr>
                <tr>
                    <td class="colunaTab"><input type="submit" value="7" name="botao" class="btn"></td>
                    <td class="colunaTab"><input type="submit" value="8" name="botao" class="btn"></td>
                    <td class="colunaTab"><input type="submit" value="9" name="botao" class="btn"></td>
                    <td class="colunaTab"><input type="submit" value="x" name="botao" class="btn"></td>
                </tr>
                <tr>
                    <td class="colunaTab"><input type="submit" value="4" name="botao" class="btn"></td>
                    <td class="colunaTab"><input type="submit" value="5" name="botao" class="btn"></td>
                    <td class="colunaTab"><input type="submit" value="6" name="botao" class="btn"></td>
                    <td class="colunaTab"><input type="submit" value="-" name="botao" class="btn"></td>
                </tr>
                <tr>
                    <td class="colunaTab"><input type="submit" value="1" name="botao" class="btn"></td>
                    <td class="colunaTab"><input type="submit" value="2" name="botao" class="btn"></td>
                    <td class="colunaTab"><input type="submit" value="3" name="botao" class="btn"></td>
                    <td class="colunaTab"><input type="submit" value="+" name="botao" class="btn"></td>
                </tr>
                <tr>
                    <td class="colunaTab" colspan="2"><input type="submit" value="0" name="botao" class="btn"></td>
                    <td class="colunaTab" colspan="2"><input type="submit" value="=" name="botao" class="btn"></td>
                </tr>
            </table>
        </form>
    </div>
    
</body>
</html>