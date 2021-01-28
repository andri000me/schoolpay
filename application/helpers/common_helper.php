<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	function wordLimit($str, $limit) {
	    $arr = explode(' ', $str);
	    if(count($arr) <= $limit){
	        return $str;   
	    }
	    $result = '';
	    for($i = 0; $i < $limit; $i++){
	        $result .= $arr[$i].' ';
	    }
	    return trim($result);
	}

	function amountinWords($num){
		$angka = array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas');
		$num = (float)$num;
		if($num < 12) {
			return $angka[$num];
		} else if($num < 20) {
			return amountinWords($num - 10). " Belas";
		} else if($num < 100) {
			return amountinWords($num / 10). " Puluh".amountinWords($num % 10);
		} else if($num < 200) {
			return " Seratus ". amountinWords($num - 100);
		} else if($num < 1000) {
			return amountinWords($num / 100). " Ratus". amountinWords($num % 100);
		} else if($num < 2000) {
			return " Seribu ". amountinWords($num - 1000);
		} else if($num < 1000000) {
			return amountinWords($num / 1000). " Ribu". amountinWords($num % 1000);
		} else if($num < 1000000000) {
			return amountinWords($num / 1000000). " Juta". amountinWords($num % 1000000);
		} else if($num < 1000000000000) {
			return amountinWords($num / 1000000000). " Miliar". amountinWords($num % 1000000000);
		} else {
			return amountinWords($num / 1000000000000). " Trilyun". amountinWords($num % 1000000000000);
		}
	}
?>