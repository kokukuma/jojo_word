<?php
// LSA
require_once("./php-lsa.php");

//-------------------- 引数取得 
if($argc>1) {
	$tname   = $argv[1]; 
}else {
	return;
	$tname   = "エリナ"; 
}


//-------------------- データの作成
$dataset = array();
$num =0;
for ($i=1; $i < 4; $i++) { 
	// read jojo_file
	$filearray = @file("./dat_jojo/jojo$i.txt");
	foreach ($filearray as $key => $value) {
		$dataset[$num] = use_mecab($value);	
		$num++;
	}
}


//-------------------- 共起行列の作成
$co = LSA::gen_co_occurrence_matrix($dataset);

echo "Making LSM wieth $tname ... \n";

//-------------------- 特異値分解の実施 
$svd = LSA::singular_value_decomposition($co,0.6);



//-------------------- 特徴ベクトルの作成
// 

$feature_vector = LSA::gen_feature_vector2($tname,$co,$svd);
//var_dump($feature_vector);


//-------------------- コサインの計算 
$cos_array = array();
foreach ($svd->V as $key => $vector) {
	$cos_array[$key] = LSA::cos($svd->V[$key],$feature_vector);
}
arsort($cos_array);


//-------------------- 結果表示
echo "\n";
echo "--------- ". $tname ."\n";
echo "\n";
foreach ($cos_array as $key => $value) {
	if($value < 0.1)exit;
	echo round($value,8)."		".$svd->keywords[$key];
	echo "\n";
}

return;

function use_mecab($content)
{
	$keywords = array();
	$mecab = new Mecab();

	$nodes = $mecab->parseToNode(strip_tags($content));
	
	foreach ($nodes as $node) {
		//if ($node->posid >= 0 && $node->posid <=66) {
		if (($node->posid >= 31 && $node->posid <=68) ||
			($node->posid >= 10 && $node->posid <=12) ) {
			//echo "品詞ID" . $node->posid . ":" . $node->surface."<br>\r\n";
			if (!isset($keywords[$node->surface])) {
				$keywords[$node->surface] = 1;
			}else {
				$keywords[$node->surface]++;	
			}
		}
	}
	arsort($keywords);

	return $keywords;
}

?>
