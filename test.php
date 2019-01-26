<?php
function sumXN($arAux) {
    global $N;
    $s = 0;
    $i = $j = 0;
    foreach ($arAux as $j => $i) {$s += $arAux[$j]*$N[$j];}
    return $s;
}
function tableX1Do($i,$b,$limit) {
    global $X1,$X2,$N,$T,$S,$R,$Result;
    $X1[$i]=$b;
    if (($i+1)>=$limit)  {
        $T[sumXN($X1)] = $X1;
        foreach ($S as $sum) {
            if (@$T[$sum]) {
                echo ($sum/100)." = " . join("",$T[$sum]) . "" . join("",$X2) . "</br>";
                $R = join(",",$T[$sum]) . ",". join(",",$X2);
                $Result = ($sum/100);
                return true;
            }
        }
        return false;
    } else {
        if (!(strlen($R)>0)) tableX1Do($i+1,0,$limit);
        if (!(strlen($R)>0)) tableX1Do($i+1,1,$limit);
    }
    return true;
}

function tableX2Do($i,$b,$limit) {
    global $X2,$N,$T,$S,$R,$Result;
    $X2[$i]=$b;
    if (($i+1)>=$limit)  {
        foreach ($S as $sum) {
            $L = $sum - sumXN($X2);
            if (@$T[$L]) {
                echo ($sum/100)." = " . join("",$T[$L]) . "" . join("",$X2) . "</br>";
                $R = join(",",$T[$L]) . "," . join(",",$X2);
                $Result = ($sum/100);
                return true;
            }
        }
        return false;
    } else {
        if (!(strlen($R)>0)) tableX2Do($i+1,1,$limit);
        if (!(strlen($R)>0)) tableX2Do($i+1,0,$limit);
    }
    return true;
}

$N = array();
$X1 = array();
$X2 = array();
$T = array();
$S = array();
$R = "";
$Result = "";

if(!empty($_POST['sum'])) {
    $set = str_replace(",",".",$_POST['set']);
    $N = split("\n",trim($set));
    //rsort($N);
    $sum = str_replace(",",".",$_POST['sum']);
    $S = split("\n",trim($sum));
}

?>
NP-Hard Problem</br>
Smallest Subset Sum solved with a Meet-in-the-middle Algorithm</br>
Based on http://www2-fs.informatik.uni-tuebingen.de/~reinhard/krypto/English/4.5.1.e.html</br>
Created by Henrique Dias</br>
<form method="POST" action="">
    <input type="submit" value="execute">
    <div style="float:left">
        Total Reembolso: </br>
        <textarea cols="20" rows="20" name="sum"><?php echo join("\n",$S)?></textarea>
    </div>
    <div style="float:left">
        Notas: </br>
        <textarea cols="20" rows="20" name="set"><?php echo join("\n",$N)?></textarea>
    </div>
    <?php if (count($N)>0) {?>
        <div style="float:left">
            &nbsp;</br>
            <select id="sumSel" multiple="true" size="21" style="width:100px;">
                <option><?php echo join("</option><option>",$N)?></option>
            </select>
        </div>
        <div style="margin:10px; font-size:20px;" id="result">
        </div>
        <br><br>
    <?php } ?>
</form>
<?php
if (count($N)>0 && count($N)<=300 && count($S)>0) {
    $dTime = time();
    echo "Num. of elements:" .count($N)."</br>";
    for ($i=0;$i<(count($N)/2);$i++) { $X1[$i]=0;}
    for ($i=ceil(count($N)/2);$i<count($N);$i++) { $X2[$i]=0;}

    for ($i=0;$i<count($N);$i++) { $N[$i]=$N[$i]*100;}
    for ($i=0;$i<count($S);$i++) { $S[$i]=$S[$i]*100;}

    tableX1Do(0,1,count($X1));
    if (!(strlen($R)>0)) tableX1Do(0,0,count($X1));
    if (!(strlen($R)>0)) tableX2Do(ceil(count($N)/2),1,count($N));
    if (!(strlen($R)>0)) tableX2Do(ceil(count($N)/2),0,count($N));
    if (!(strlen($R)>0)) echo "Nenhum registro encontrado.</br>";

    echo "Time:". (time() - $dTime)." s</br>";
}
?>

<script language="Javascript">
    var sel = document.getElementById("sumSel");
    var res = document.getElementById("result");
    if (res)
        res.innerHTML = "<br><br>Resultado: R$ <?php echo $Result?>";
    if (sel) {
        for (var i=0;i<sel.options.length;i++) {
            sel.options[i].selected = false;
        }
        <?php
        foreach (split(",",$R) as $i => $j) {
            if ($j) {echo "sel.options[$i].selected = true;";}
        }
        ?>
    }
</script>