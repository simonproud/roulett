<?php
/**
* Контроллер домашней страницы
* 
* @author SimonProud <simon.proud@ro.ru>
* @version 1.0
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Prize;
use Config;

class HomeController extends Controller
{
	/**
	* домашняя страница с выводом призов авторизованого пользователя
	*/
    public function index() {
    	$data = [];
    	$data['prizes_list'] = User::find(Auth::user()->id)->prizes()->get()->toArray();
    
   		return view('welcome', $data);
	}
	/**
	* Проверка наличия у нас разыгрываемого приза
	* @Request request  (сюда приходит лишь id конвертируемого приза)
	*/
	public function convert(Request $request) {

		$multiplier = Config::get('prizes.multiplier');

    	$prize = Prize::find($request->id);
    	if($prize->prize == "Деньги"){
    	$prize->countp = $prize->countp * $multiplier;
    	$prize->prize = "Очки лояльности";
    	$prize->save();
		return ['status' => 'success'];
		}
		return ['status' => 'error'];
   		}
}
