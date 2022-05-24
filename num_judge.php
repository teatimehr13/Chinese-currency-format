<?php
session_start();

function odd($var)
{
    if ($var % 2 != 0)
        return TRUE;
    else
        return FALSE;
}



$num = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'];
$unit = ['十', '百', '千', '萬'];
$unit_flip = array_flip($unit);

$string ="";

if (isset($_GET['text'])) {
    $string = $_GET['text'];
}

$str = mb_str_split($string);
$str_lenght = count($str) - 1; //由0開始
$str_flip = array_flip($str);


$status = true;
for ($i = 0; $i <= $str_lenght; $i++) {
    $tmp = $i;

    //輸入的字串有非中文數字、單位格式的話為false
    if(!in_array($str[$i],$unit) && !in_array($str[$i],$num)){
        $status = false;
    }

    //陣列偶數位為數字，出現單位的話為false
    if ($tmp % 2 == 0 && in_array($str[$i], $unit)) {
        $status = false;
        if ($str[0] == "十") {
            $status = true;
        }
    }
    //奇數位為單位
    if ($tmp % 2 != 0 && in_array($str[$i], $num)) {
        $status = false;
    }

    if ($tmp & 1) {
        //判斷輸入的內容陣列中是否有單位重複
        $answer = array_count_values($str); //ex: Array ( [一] => 1 [萬] => 2 [二] => 1 ) 
        // print_r($answer);
        // echo $str[$i]; //取得單位
        if ($answer[$str[$i]] > 1) {
            $status = false;
        }
    }
}

// 篩出輸入字串中的奇數位其值,key為單位 ; // 用str_flip的每個值進行function的運算
$str_filter = array_filter($str_flip, "odd");
// print_r(array_filter($str_flip,"odd"));


//篩選後的$str的key值(單位)放進規範的單位陣列，並產生新陣列
$arr_filter = [];
foreach ($str_filter as $key => $val) {
    @array_push($arr_filter, $unit_flip[$key]);
}

$arr_filter2 = $arr_filter;
rsort($arr_filter2);


//判斷陣列的值是否有依照大到小排序，判斷標準為rsort過的陣列
if ($arr_filter != $arr_filter2) {
    $status = false;
}
?>

<div class="container">
    <h1>請輸入萬元以下的中文數字，判斷其格式是否正確</h1>

    <div style="display:flex; padding:10px; margin-bottom:20px;">
        <div style="margin-right: 50px;">
            <span style="color:blue; font-size:large">正確範例:</span><br>
            <ul>
                <li>三萬</li>
                <li>六萬三千</li>
                <li>五十</li>
            </ul>

        </div>

        <div>
            <span style="color:red; font-size:large">錯誤範例:</span><br>
            <ul>
                <li>萬九</li>
                <li>三百五萬七千</li>
                <li>兩萬四萬</li>
            </ul>
        </div>
    </div>
    <?php
    if (!empty($string)) {
        if ($status == true) {
            echo "<span style='color:blue'> 您輸入的字串<" .  $string . ">為正確格式</span>";
        } else {
            echo "<span style='color:red'>您輸入的字串<" . $string . ">為錯誤格式</span>";
        }

    }
    ?>

    <form action="./num_judge.php" method="get">
        <input type="text" name="text">
        <button type="submit">驗證</button>
    </form>
</div>