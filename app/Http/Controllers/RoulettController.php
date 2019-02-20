<?php
/**
* Контроллер рулетки
* 
* @author SimonProud <simon.proud@ro.ru>
* @version 1.0
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Config;

class RoulettController extends Controller
{
    public function gamestart(){
    	$prizes = Config::get('prizes.data');
    	foreach ($prizes as $key => $value) {
    		$prizesRoulett[$key] = $value['chance']['static'];
    	}
    	$prize = $this->roulette($prizesRoulett);
    	
    	if(Config::get('prizes.data.'.$prize.'.chance.byformule') == true){
    		
    		if(Config::get('prizes.data.'.$prize.'.chance.formule') == 'random'){
    			
    			$prizeCount = $this->getrandom(Config::get('prizes.data.'.$prize.'.interval'));
    			
    			return [$prize => Config::get('prizes.data.'.$prize), 'count' => $prizeCount];
    		
    		}
    		
    	}

    	if($prize == 'good'){
    		$item = $this->roulette(Config::get('prizes.data.'.$prize.'.items'));
    	}
    	return [$prize => ['item' => $item], 'count' => 1];
    }


	private function getrandom($interval)
	{
		$count = mt_rand ($interval['min']*10, $interval['max']*10) / 10;
		return $count;
	}


    private function checkDecimal($value)
	{
	    if ((int)$value == $value)
	    {
	        return 0;
	    }
	    else if (! is_numeric($value))
	    {
	        return false;
	    }

	    return strlen($value) - strrpos($value, '.') - 1;
	}

	private function roulette($items)
	{
		$sumOfPercents = 0;
		
		foreach($items as $itemsPercent)
		{
			$sumOfPercents += $itemsPercent;
		}

		$decimals = $this->checkDecimal($sumOfPercents);
		$multiplier = 1;
		for ($i=0; $i < $decimals; $i++) 
		{ 
			$multiplier *= 10;
		}

		$sumOfPercents *= $multiplier;
		$rand = rand(1, $sumOfPercents);
		
		$rangeStart = 1;
		foreach($items as $itemKey => $itemsPercent)
		{
		$rangeFinish = $rangeStart + ($itemsPercent * $multiplier);
		
		if($rand >= $rangeStart && $rand <= $rangeFinish)
		{
			return $itemKey;
		}

		$rangeStart = $rangeFinish + 1;
		}
	}
}
