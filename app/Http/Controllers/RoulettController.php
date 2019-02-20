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
use DB;
use App\Prize;
class RoulettController extends Controller
{
	/**
	* Начало работы скрипта. 
	* Описание и конфигурация призов находится в файле /config/prizes.php
	* 
	*/
    public function gamestart(){
    	//подключам конфиг
    	$prizes = Config::get('prizes.data');

    	//формируем массив для функции roulette  
    	foreach ($prizes as $key => $value) {
    		$prizesRoulett[$key] = $value['chance']['static'];
    	}

    	// Получаем случайный тип приза
    	$prize = $this->roulette($prizesRoulett);

    	//если приз является вещью
    	if($prize == 'good'){
    		$itemsAr = [];

    		//формируем массив вещей для функции roulette
    		foreach ($prizes[$prize]['items'] as $key => $value) {
    			$itemsAr[$key] = $value['chance'];
    		}

    		//получаем приз
    		$item = $this->roulette($itemsAr);

	    	//формируем проверяемый массив
			$return = ['item' => ['item' => $item, 'type' => $prize], 'count' => 1];

			//проверяем на наличие у нас такого приза, и если у нас его нет, запускаем игру заного
			if(!$this->checkCount($return)){
	    		$return = $this->gamestart();
	    	}

	    	// добавляем запись о выйгрыше в базу
	    	Prize::create([
	    				'user_id' => Auth::user()->id,
	    				'prize' => $item,
	    				'countp' => 1
	    			]);
    	}else{

    		//у нас поинты или деньги. определяем формулу
	    	if($prizes[$prize]['chance']['byformule'] == true){
	    		
	    		if($prizes[$prize]['chance']['formule'] == 'random'){

	    			//получаем случайное число по формуле в интервале
	    			$prizeCount = $this->getrandom($prizes[$prize]['interval']);

					//формируем проверяемый массив
	    			$return = ['item' => ['item' => $prize, 'type' => $prize], 'count' => $prizeCount];

	    			//проверяем на наличие у нас такого приза, и если у нас его нет, запускаем игру заного
	    			if(!$this->checkCount($return)){
	    				$return = $this->gamestart();
	    			}
	    			// добавляем запись о выйгрыше в базу
	    			Prize::create([
	    				'user_id' => Auth::user()->id,
	    				'prize' => $prize,
	    				'countp' => $prizeCount
	    			]);
	    		}
	    	}
		}
		return $return;
    }

    /**
	* Проверка наличия у нас разыгрываемого приза
	* @array prize  (формируемый массив на выход функции gamestart)
	*/
    public function checkCount($prize){

    	$prizes = [];

    	//получаем группированный по типам список всех выйгрышей и формируем соответствующий массив
    	$prizesDb = DB::table('prizes')->select(DB::raw('prize, sum(countp) as countp'))->groupBy('prize')->get();
    	foreach($prizesDb as $value){
    		$prizes[$value->prize] = $value->countp;
    	}

    	//подключаем конфиг
    	$config = Config::get('prizes.data');
    	
    	// проверяем не бесконечен ли пул разыгрываемых призов
    	if(  $config[$prize['item']['type']]['items'][$prize['item']['item']]['count'] == 'MAGIC'){return true;}

    	// проверяем выдавался ли хоть раз прих
    	if(!isset($prizes[$prize['item']['item']])){
    		return true;
    	}
    	
    	//проверяем если приз выдавался, то остался ли аналогичный приз на складе
    	if($prizes[$prize['item']['item']]+$prize['count'] <= $config[$prize['item']['type']]['items'][$prize['item']['item']]['count'])
    	{
    		return true;
    	}

    	return false;
    }
    /**
	* Возвращает рандомное десятичное число между min & max
	* @array interval [min, max]
    */
	private function getrandom($interval)
	{
		$count = mt_rand ($interval['min']*10, $interval['max']*10) / 10;
		return $count;
	}

 	/**
	* Проверка соответствия типа шансов
	* @int value  
	* @return шанс 
	*/
    private function checkDecimal($value)
	{
		//шанс должен быть меньше 1 и больше 0
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
	/**
	* Проверка наличия у нас разыгрываемого приза
	* @array items [приз => шанс] список типов/призов с указанием шанса 
	* @return приз 
	*/
	private function roulette($items)
	{
		//формируем шансы на получение определённого приза
		$sumOfPercents = 0;
		
		foreach($items as $itemsPercent)
		{
			$sumOfPercents += $itemsPercent;
		}
		//проверяем получившийся шанс на соответствие типа
		$decimals = $this->checkDecimal($sumOfPercents);

		//формируем множетель
		$multiplier = 1;
		for ($i=0; $i < $decimals; $i++) 
		{ 
			$multiplier *= 10;
		}

		$sumOfPercents *= $multiplier;
		$rand = rand(1, $sumOfPercents);
		
		$rangeStart = 1;
		//Попадает ли приз в пул шансов rangeStart и rangeFinish, в случае первого совпадения возвращаем $itemKey типа/приза
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
