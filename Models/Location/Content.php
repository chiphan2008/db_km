<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AnalyticsCustom;
use App\LikeContent;
use Illuminate\Support\Facades\Cache;
use App\Models\Location\Client;
class Content extends Base
{

	protected $table = 'contents';

  protected $fillable = [
    'name', 'id_category', 'alias',
    'country', 'city', 'district', 'tag', 'address', 'phone', 'open_from', 'open_to',
    'price_from', 'price_to', 'currency', 'website', 'email', 'description', 'avatar', 'vote', 'like',
    'image_space', 'image_menu', 'services', 'lat', 'lng', 'moderation',
    'created_by', 'updated_by', 'type_user', 'active', 'extra_type','unique_code','code_invite','wifi','pass_wifi'
  ];

  public function _products()
  {
      return $this->hasMany('App\Models\Location\Product', 'content_id', 'id');
  }

  public function _discount()
  {
  		// dd(Carbon::now());
      return $this->hasMany('App\Models\Location\Discount','id_content','contents.id')
      						->where('discount.active',1)
      						->where('discount.date_from','<=',Carbon::now())
      						->where('discount.date_to','>=',Carbon::now())
      						->with('_images')
      						->with('_date_open')
      						->with('_contents')
      						->orderBy('id','DESC');
  }

  public function _discount_basic()
  {
  		// dd(Carbon::now());
      return $this->hasMany('App\Models\Location\Discount','id_content','contents.id')
      						->where('discount.active',1)
      						->where('discount.date_from','<=',Carbon::now())
      						->where('discount.date_to','>=',Carbon::now())
      						->orderBy('id','DESC');
  }

	public function _country()
	{
		return $this->belongsTo('App\Models\Location\Country', 'country', 'id');
	}

	public function _city()
	{
		return $this->belongsTo('App\Models\Location\City', 'city', 'id');
	}

	public function _district()
	{
		return $this->belongsTo('App\Models\Location\District', 'district', 'id');
	}

  public function _category_type()
  {
    return $this->belongsTo('App\Models\Location\Category', 'id_category', 'id');
  }

  public function _category_content()
  {
      return $this->hasMany('App\Models\Location\CategoryContent', 'id_content', 'id');
  }

  public function _comments()
  {
      return $this->hasMany('App\Models\Location\Comment', 'model_id', 'id')
      			 ->where('comment.model_type','=','content')
      			 ->where('comment.parent_id','=',0)
      			 ->where('comment.active','=',1)
      			 ->where('comment.approved','=',1)
      			 ->with('_replies')
      			 ->with('_images')
      			 ->with('_comment_by')
      			 ->with('_has_liked')
      			 ->orderBy('created_at','DESC')
      			 ->limit(5);
  }

  public function _all_comments()
  {
      return $this->hasMany('App\Models\Location\Comment', 'model_id', 'id')
      			 ->where('comment.model_type','=','content')
      			 ->where('comment.parent_id','=',0)
      			 ->where('comment.active','=',1)
      			 ->where('comment.approved','=',1)
      			 ->orderBy('created_at','DESC');
  }

  public function _category_items()
  {
      return $this->belongsToMany('App\Models\Location\CategoryItem', 'category_content','id_content','id_category_item');
  }

  public function _branchs()
  {
      return $this->belongsToMany('App\Models\Location\Content', 'branch','id_content','id_content_other')
      						->with('_category_type')
									->with('_category_items')
									->with('_country')
									->with('_city')
									->with('_district')
									->where('moderation','publish')
									->select('contents.*',\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"));
  }

  public function _services()
  {
      return $this->belongsToMany('App\Models\Location\ServiceItem', 'service_content','id_content','id_service_item');
  }

   public function _date_open()
  {
      return $this->hasMany('App\Models\Location\DateOpen', 'id_content', 'id');
  }

   public function _date_open_api()
  {
      return $this->hasMany('App\Models\Location\DateOpen', 'id_content', 'id')
      						->select(
      							'id_content',
      							'date_from as from_date',
      							'date_to as to_date',
      							'open_from as from_hour',
      							'open_to as to_hour',
      							'angle_from',
      							'angle_to'
      						);
  }

  public function _created_by()
  {
    return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
  }
  public function _updated_by()
  {
    return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
  }

  public function _created_by_client()
  {
    return $this->belongsTo('App\Models\Location\Client', 'created_by', 'id');
  }
  public function _updated_by_client()
  {
    return $this->belongsTo('App\Models\Location\Client', 'updated_by', 'id');
  }

	public function scopeSearch($query, $keywords)
	{
		// if(count($keywords)){
		// 	$str_query = 'CASE';
		// }

		// foreach ($keywords as $key => $keyword) {
		// 	if($key==0){
		// 		$query = $query->where('name', 'LIKE', '% '.$keyword.' %')
		// 									 ->orWhere('tag', 'LIKE', '% '.$keyword.' %')
		// 									 ->orWhere('address', 'LIKE', '% '.$keyword.' %')
		// 									 ->orWhere('description', 'LIKE', '% '.$keyword.' %');
											 
		// 	}else{
		// 		$query = $query->orWhere('name', 'LIKE', '% '.$keyword.' %')
		// 									 ->orWhere('tag', 'LIKE', '% '.$keyword.' %')
		// 									 ->orWhere('address', 'LIKE', '% '.$keyword.' %')
		// 									 ->orWhere('description', 'LIKE', '% '.$keyword.' %');
											 
		// 	}
		// 	$str_query .=' WHEN `name` LIKE \'% '.$keyword.' %\' then '.($key+1)*1;
		// 	$str_query .=' WHEN `tag` LIKE \'% '.$keyword.' %\'  then '.($key+1)*2;
		// 	$str_query .=' WHEN `address` LIKE \'% '.$keyword.' %\' then '.($key+1)*3;
		// 	$str_query .=' WHEN `description` LIKE \'% '.$keyword.' %\' then '.($key+1)*4;
		// }


		// foreach ($keywords as $key => $keyword) {
		// 	if($key==0){
		// 		$query = $query->whereRaw(" concat(' ',contents.name,' ') LIKE '% $keyword %'")
		// 									 ->orwhereRaw(" concat(' ',contents.tag,' ') LIKE '% $keyword %'")
		// 									 ->orwhereRaw(" contents.name LIKE '%$keyword%'")
		// 									 ->orwhereRaw(" concat(' ',contents.address,' ',districts.name,' ',cities.name,' ',countries.name,' ') LIKE '% $keyword %'");
		// 									 // ->orwhereRaw(" concat(' ',contents.description,' ') LIKE '% $keyword %'")							 
		// 	}else{
		// 		$query = $query->orwhereRaw(" concat(' ',contents.name,' ') LIKE '% $keyword %'")
		// 									 ->orwhereRaw(" concat(' ',contents.tag,' ') LIKE '% $keyword %'")
		// 									 ->orwhereRaw(" concat(' ',contents.address,' ') LIKE '% $keyword %'");
		// 									 // ->orwhereRaw(" concat(' ',contents.description,' ') LIKE '% $keyword %'");
											 
		// 	}
		// 	$str_query .=' WHEN concat(\' \',contents.name,\' \') LIKE \'% '.$keyword.' %\' then '.(($key+1)*$key*2+1);
		// 	$str_query .=' WHEN concat(\' \',contents.tag,\' \') LIKE \'% '.$keyword.' %\'  then '.(($key+1)*$key*2+2);
		// 	$str_query .=' WHEN contents.name LIKE \'%'.$keyword.'%\'  then '.(($key+1)*$key*2+3);
		// 	$str_query .=' WHEN concat(\' \',contents.address,\' \',districts.name,\' \',cities.name,\' \',countries.name,\' \') LIKE \'% '.$keyword.' %\' then '.(($key+1)*$key*2+4);
		// 	// $str_query .=' WHEN concat(\' \',contents.description,\' \') LIKE \'% '.$keyword.' %\' then '.($key+4)*17;
		// }

		// if(count($keywords)){
		// 	$str_query.= ' END ASC';
		// 	$query->orderByRaw($str_query);
		// }

		$arr_prevent = [

		];
		$keywords = strtolower(vn_string(clean_str($keywords)));

		$str_match = '';
		$str_match .= '"+'.str_replace(' ', '+', $keywords).'"';
		$arr_keywords = explode(' ',$keywords);
    $number_keywords = 0;
    $check = 0;
    $count_less = 0;
		foreach ($arr_keywords as $key => $keyword){
			if(is_numeric($keyword)){
				unset($arr_keywords[$key]);
			}
      if(is_numeric($keyword)){
          $number_keywords++;
      }
      if(strlen($keyword)<3){
	    	$count_less++;
	    }
	    if(
	    	(strlen($keyword)<3 && !is_numeric($keyword) && count(explode(' ',$keywords)) < 4) ||
	    	($count_less > 1 && count(explode(' ',$keywords)) >= 4 && !is_numeric($keyword))
	    ){
	    	$check = 0.5;
	    }
		}
		$number_keywords += $check;
		$arr_keywords = array_values($arr_keywords);
		$arr_more_keywords = array_keyword($keywords);

		if(count(explode(' ',$keywords)) <= 6){
	  	if(count(explode(' ',$keywords)) < 4){
	  		$match_score = count(explode(' ',$keywords)) + count($arr_more_keywords)*0.55-$number_keywords;
	  	}else{
	  		$match_score = count(explode(' ',$keywords)) + count($arr_more_keywords)*0.66675-$number_keywords;
	  	}
		}else{
			$match_score = count(explode(' ',$keywords)) + count($arr_more_keywords)*0.66675;
		}
	  $match_score = round($match_score,6);

	  if(count(explode(' ',$keywords)) >= 6){
	  	$match_score = $match_score+1.33325;
	  }

		$arr_keywords = array_unique($arr_keywords); 
		

		// if(count($arr_keywords)<5){
			foreach ($arr_keywords as $key => $keyword){
				if(strlen($keyword) > 1 &&  !in_array($keyword,$arr_prevent) && !is_numeric($keyword)){
					if($key==0){
						$str_match.='"+';
						$str_match.= $keyword;
						$str_match.='"';
					}else{
						$str_match.=' "+';
						$str_match.=$keyword;
						$str_match.='"';
					}
				}
			}
		// }

		

		if(count($arr_more_keywords)){
			foreach ($arr_more_keywords as $key => $keyword){
				if(strlen($keyword) > 1 && !in_array($keyword,$arr_prevent) && !is_numeric($keyword)){
					if($key==0){
						$str_match.='"+';
						$str_match.= str_replace(' ', '+', $keyword);
						$str_match.='"';
					}else{
						$str_match.=' "+';
						$str_match.= str_replace(' ', '+', $keyword);
						$str_match.='"';
					}
				}
			}
		}
		
		// $match_score = 1;
		// $keyword_length = count($keywords);
		// $match_score = count(explode(' ', $keywords[0])) / 2 > 1 ? count(explode(' ', $keywords[0])) / 2 : 1;
		// $match_score = count($arr_keywords);
		
		
		// dd($str_match, $keywords, $match_score);
		// echo($match_score."<br/>");
		// echo($str_match."<br/>");
		$str_match = vn_string($str_match);
		// $match_score = 0.5;
		// $match_score = 1;
		if($str_match != ''){
			// $query->selectRaw("MATCH (contents.name) AGAINST ('".$keywords."' IN BOOLEAN MODE) AS name_match");
			$query->selectRaw("MATCH (contents.tag_search) AGAINST ('".$str_match."' IN BOOLEAN MODE) AS tag_match");
			// $query->selectRaw("MATCH (contents.tag) AGAINST ('".$str_match."' IN BOOLEAN MODE) >=1 AS tag_bool_match");
			// $query->selectRaw("MATCH (contents.address) AGAINST ('".$str_match."' IN BOOLEAN MODE) >=1 AS address_match");
			// $query->selectRaw("MATCH (districts.name) AGAINST ('".$str_match."' IN BOOLEAN MODE) >= 1 AS district_match");
			$query->selectRaw("(MATCH (districts.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)> 1)   AS district_match");
			$query->selectRaw("(MATCH (districts.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)> 1) + (MATCH (contents.tag_search) AGAINST ('".$str_match."' IN BOOLEAN MODE)) AS city_match");
			// $query->selectRaw("MATCH (cities.name) AGAINST ('".$str_match."' IN BOOLEAN MODE) >=1 AS city_match");
			// $query->selectRaw("MATCH (countries.name) AGAINST ('".$str_match."' IN BOOLEAN MODE) >=1 AS country_match");
			// $query->selectRaw("
			// 				MATCH (contents.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)*3+
			// 				MATCH (contents.address) AGAINST ('".$str_match."' IN BOOLEAN MODE)+
			// 				MATCH (districts.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)+
			// 				MATCH (cities.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)+
			// 				MATCH (countries.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)
			// 				AS sum_match
			// 	");
			
			// $query->whereRaw("MATCH (contents.name) AGAINST ('".$str_match."' IN BOOLEAN MODE) >= $match_score");

			// $query->whereRaw("
			// 			(MATCH (contents.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)*3+
			// 			MATCH (contents.tag) AGAINST ('".$str_match."' IN BOOLEAN MODE)/1.5+
			// 			MATCH (contents.address) AGAINST ('".$str_match."' IN BOOLEAN MODE)+
			// 			MATCH (districts.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)*1.3+
			// 			MATCH (cities.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)+
			// 			MATCH (countries.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)) >= ".$match_score
			// 	);

			// $query->where(function($query) use ($str_match, $match_score){
			// 	$query->whereRaw("MATCH (contents.name) AGAINST ('".$str_match."' IN BOOLEAN MODE) > 1");
			// 	$query->orwhereRaw("MATCH (contents.tag) AGAINST ('".$str_match."' IN BOOLEAN MODE) > 1");
			// 	$query->orwhereRaw("MATCH (contents.address) AGAINST ('".$str_match."' IN BOOLEAN MODE) > 1");
			// });
			
			$query->whereRaw("(MATCH (contents.tag_search) AGAINST ('".$str_match."' IN BOOLEAN MODE)) >=".$match_score);
			$query->orwhereRaw("(MATCH (districts.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)> 1) + (MATCH (contents.tag_search) AGAINST ('".$str_match."' IN BOOLEAN MODE)) >".$match_score);
			if(count(explode(' ',$keywords)) < 3){
				$query->orWhere('contents.tag_search','like',$keywords.' %');
			}
			
			// $query->whereRaw("MATCH (districts.name) AGAINST ('".$str_match."' IN BOOLEAN MODE) >= 1");
			// $query->orwhereRaw("MATCH (cities.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)");
			// $query->orwhereRaw("MATCH (countries.name) AGAINST ('".$str_match."' IN BOOLEAN MODE)");
			$query->orderByRaw("tag_match DESC");
			$query->orderByRaw("tag_match+district_match DESC");
			// $query->orderByRaw("line ASC");
			// $query->orderByRaw("tag_match+name_match DESC");
			// $query->orderByRaw("tag_match+name_match+address_match+district_match+city_match DESC");
			// $query->orderByRaw("(tag_match+district_match*2+name_match*2) DESC");
			// $query->orderByRaw("(line/1000)*(name_match*line+district_match*(line*line)) ASC");
			
			
			
			
			
		}
		
		return $query;
	}

	public function scopeSearchAd($query, $keyword)
	{
		$str_match = '%'.$keyword.'%';
		$query->where('contents.keyword_ad','like',$str_match);
		$query->where('contents.view_ad','>',0);
		$query->where('contents.active_ad','>',0);
		return $query;
	}

	public function _checkin()
  {
    return $this->hasMany('App\Models\Location\Checkin', 'id', 'id_user');
  }

  public static function pushContent($id, $hour){
  	$content = self::find($id);
  	$content->last_push = Carbon::now();
  	$content->end_push = Carbon::now()->addHour($hour);
  	$trans = new TransactionCoin();
		$check = $trans->pay(Auth::guard('web_client')->user(), ($hour*PRICE_PUSH), trans_choice('transaction.pay_for_push_content',$hour,['content'=>$content->name, 'hour'=> $hour, 'coin'=>($hour*PRICE_PUSH)]));
		if($check===true){
			// return $content->save();
			return true;
  	}else{
  		return $check;
  	}
  }


  public static function getStatic(){
  	$cacheName = determineCacheName(__FUNCTION__);
		$arr_Data =  Cache::store('file')->remember($cacheName, 30, function (){
			$countContent = Content::where('active','=',1)
														 ->where('moderation','=','publish')
														 ->count();
			$newContent = Content::where('active','=',1)
													 ->where('moderation','=','publish')
													 ->where('created_at','>',Carbon::now()->subDays(30))
													 ->count();
			$countLike = Content::sum('like');
			$countUser = Client::count();
			return [
				'countContent'	=>	$countContent,
				'newContent'  	=>	$newContent,
				'countLike'   	=>	$countLike,
				'countUser'			=>  $countUser
			];
		});
		$arr_Data['countOnline'] = AnalyticsCustom::getTotalCurrentUser();
		$arr_Data['countShare'] = AnalyticsCustom::getTotalShare();
		
		// $arr_Data['countShare'] = (int) round($arr_Data['countContent']/4.4);
		return $arr_Data;
  }

}
