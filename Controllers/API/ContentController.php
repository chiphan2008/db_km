<?php
namespace App\Http\Controllers\API;

use App\Models\Location\Comment;
use App\Models\Location\CommentImage;
use App\Models\Location\CommentLike;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

use App\Models\Location\Category;
use App\Models\Location\CategoryContent;
use App\Models\Location\CategoryItem;
use App\Models\Location\CategoryService;
use App\Models\Location\City;
use App\Models\Location\Content;
use App\Models\Location\Client;
use App\Models\Location\Country;
use App\Models\Location\District;
use App\Models\Location\GroupContent;
use App\Models\Location\ImageMenu;
use App\Models\Location\ImageSpace;
use App\Models\Location\LikeContent;
use App\Models\Location\SeoContent;
use App\Models\Location\ServiceContent;
use App\Models\Location\VoteContent;
use App\Models\Location\Checkin;
use App\Models\Location\SaveLikeContent;
use App\Models\Location\LinkContent;
use App\Models\Location\ServiceItem;
use App\Models\Location\DateOpen;
use App\Models\Location\Product;
use App\Models\Location\NotifiAdmin;
use App\Models\Location\Discount;
use App\Models\Location\Collection;
use App\Models\Location\CollectionContent;

use App\Models\Ads\PaymentAds;

use App\Models\Location\CTV;
use App\Models\Location\Daily;


use Illuminate\Http\Request;
use Validator;

class ContentController extends BaseController {

	public function postcreateCommentContent(Request $request){
		if(!$request->content && $request->image){
			$request->merge(['content' => "&nbsp;"]);
		}
		$arrReturn = [
			'error'=>1,
			'message'=>'',
			'data'=>[],
		];
		$rules = [
			'user_id' => 'required',
			'content' => 'required',
			'comment_id' => 'required',
		];
		$messages = [
			// 'content_id.required' => 'Name là trường bắt buộc',
			'content.required' => trans('Location'.DS.'preview.content_empty'),
		];

			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$arrReturn['message'] = $validator->errors()->first();
			} else {
				$user_id = $request->user_id;
				$comment = new Comment();
				$comment->model_type = 'content';
				$comment->model_id = $request->content_id;
				$comment->parent_id = $request->comment_id;
				$comment->content = $request->content;
				$comment->created_by = $user_id;
				$comment->updated_by = $user_id;
				$comment->created_at = Carbon::now();
				$comment->updated_at = Carbon::now();

				if($comment->save()){

					if($request->image){
						$path = public_path().'/upload/comment/';
						$path_thumb = public_path().'/upload/comment_thumb/';
						if(!\File::exists($path)) {
							\File::makeDirectory($path, $mode = 0777, true, true);
						}
						if(!\File::exists($path_thumb)) {
							\File::makeDirectory($path_thumb, $mode = 0777, true, true);
						}
						foreach ($request->image as $key => $file) {
							$image_comment = new CommentImage();
							$image_comment->comment_id = $comment->id;
							$name = uniqid('comment_'). '.' . $file->getClientOriginalExtension();
							$img = Image::make($file->getRealPath())->orientate();
					    $width = $img->getSize()->getWidth();
					    $height = $img->getSize()->getHeight();
					    $max_height = 720;
					    $max_width = 1280;
					    if($width>$max_width || $height>$max_height){
					      $img = Image::make($file->getRealPath())->orientate()->resize(1280, 720, function ($constraint) {
					        $constraint->aspectRatio();
					        $constraint->upsize();
					      });
					    }
					    $max = $width>$height?$width:$height;
					    $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
    					$img->insert($wt, 'center');
    					$img->insert($wt, 'center');
					    $img->save($path . $name);

							$img_thumbnail = Image::make($file->getRealPath())->orientate()->fit(200, 200, function ($constraint) {
                  $constraint->upsize();
              });
            	$img_thumbnail->save($path_thumb . $name);
							$image_comment->link = '/upload/comment/'.$name;
							$image_comment->thumb = '/upload/comment_thumb/'.$name;
							$image_comment->save();
							// sleep(1);
						}
					}
					$arrReturn['error'] = 0;
					$arrReturn['message'] = trans('Location'.DS.'preview.pending_comment');
				}

			}

		return response($arrReturn);
	}

	public function postlikeComment(Request $request){
		$arrReturn = [
			'error'=>1,
			'info'=>'like',
			'message'=>'',
			'data'=>[],
		];

			$user_id = $request->user_id;
			$comment_id = $request->comment_id;
			$comment = Comment::find($comment_id);
			$comment_like = CommentLike::where('user_id','=',$user_id)
																 ->where('comment_id','=',$comment_id)
																 ->first();
			if($comment_like){
				$arrReturn['info'] = 'unlike';
				CommentLike::where('user_id','=',$user_id)
									 ->where('comment_id','=',$comment_id)
									 ->delete();
				$comment->like_comment = round($comment->like_comment)-1;
				$comment->save();
			}else{
				$comment_like = new CommentLike();
				$comment_like->user_id = $user_id;
				$comment_like->comment_id = $comment_id;
				$comment_like->save();
				$comment->like_comment = round($comment->like_comment)+1;
				$comment->save();
			}
			$arrReturn['error'] = 0;
			$arrReturn['data'] = ['like'=>$comment->like_comment];

		return response($arrReturn);
	}

	public function getContentByCategory(Request $request){
		try{
			$rules = [
				'location' => 'required',
				'category' => 'required'
			];
			$messages = [
				'location.required' => 'Location is required',
				'category.required' => 'Category is required',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$data = [];
				$currentLocation = explode(',', $request->location);
				if(count($currentLocation)==2){
					$lat = $currentLocation[0];
					$lng = $currentLocation[1];
					$contents = Content::select(
												'contents.id',
												'contents.name',
												'contents.address',
												'contents.lat',
												'contents.lat as latitude',
												'contents.lng',
												'contents.lng as longitude',
												'contents.vote',
												'contents.like',
												'contents.alias',
												'contents.avatar',
												\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"),
												'contents.country',
												'contents.id_category',
												'contents.city',
												'contents.district',
												'contents.last_push',
												'contents.end_push'
											)
											->where('contents.active','=',1)
											->where('moderation','=','publish')
											->where('contents.id_category','=',$request->category)
											// ->orderBy('contents.last_push','desc')
											// ->orderBy('contents.end_push','desc')
											->with('_category_type')
											->with('_country')
											->with('_city')
											->with('_district');

					$contents = $contents->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= 500
													")
												->orderBy('line');

					if($request->subcategory){
						$contents =  $contents->leftJoin('category_content','contents.id','=','category_content.id_content')
																	->where('category_content.id_category_item','=',$request->subcategory);
					}

					$skip = $request->skip?$request->skip:0;
					$limit = $request->limit?$request->limit:20;

					$contents =  $contents->limit($limit)
																->skip($skip)
																->get();
					if($contents){
						$data = $contents->toArray();
					}else{
						$data = [];
					}
					return $this->response($data,200);
				}else{
					$e = new \Exception('Wrong location',400);
					return $this->error($e);
				}

				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getListContent(Request $request){

		try{
			\DB::enableQueryLog();
			$data = [];

			$contents = Content::select(
												'contents.id',
												'contents.name',
												'contents.address',
												'contents.lat',
												'contents.lat as latitude',
												'contents.lng',
												'contents.lng as longitude',
												'contents.vote',
												'contents.like',
												'contents.alias',
												'contents.avatar',
												\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"),
												'contents.country',
												'contents.id_category',
												'contents.city',
												'contents.district',
												'contents.last_push',
												'contents.end_push'
											)
											->where('contents.active','=',1)
											->where('moderation','=','publish')
											// ->where('contents.id_category','=',$request->category)
											// ->orderBy('contents.last_push','desc')
											// ->orderBy('contents.end_push','desc')
											->leftJoin('districts','contents.district','=','districts.id')
											->with('_category_type')
											->with('_country')
											->with('_city')
											->with('_district')
											
											
											;
			// if($request->keyword){
			// 	$key = $request->keyword;
			// 	$arrKey = [];
			// 	if(str_word_count(str_slug_custom($key))<5){
			// 		$arrKey = array_keyword($key);
			// 	}
			// 	array_unshift($arrKey,clear_str($key));
			// 	$contents = $contents->search($arrKey);
			// }

			if($request->keyword!=''){
				$key = $request->keyword;
				$contents = $contents->search(clear_str($key));
			}

			if($request->country){
				$contents = $contents->leftJoin('countries','contents.country','=','countries.id')
													   ->where('contents.country','=',$request->country);
			}
			if($request->city){
				$contents = $contents->leftJoin('cities','contents.city','=','cities.id')
														 ->where('contents.city','=',$request->city);
			}
			if($request->district){
				$contents = $contents->where('contents.district','=',$request->district);
			}
			if($request->category){
				$contents = $contents->where('contents.id_category','=',$request->category);
			}
			if($request->subcategory){
				$arrCategoryItem = [];
				$arrCategoryItem = array_map('intval',explode(',',$request->subcategory));
				$contents = $contents ->leftJoin('category_content','contents.id','=','category_content.id_content')
															->whereIn('category_content.id_category_item',$arrCategoryItem);
			}
			if($request->service){
				$arrService = array_map('intval',explode(',',$request->service));
				$sql_service = "SELECT `service_content`.`id_content` FROM `service_content` ";
				foreach ($arrService as $key => $value) {
					$sql_service .= "INNER JOIN(SELECT `service_content`.`id_content` FROM `service_content` where `id_service_item`=$value) tb_$key on `tb_$key`.`id_content` = `service_content`.`id_content`";
				}
				$sql_service .="where `service_content`.`id_service_item`=".$arrService[0];
				$content_service = \DB::select(\DB::raw($sql_service));
				$arr_service = [];
				foreach ($content_service as $key => $value) {
					$arr_service[] = $value->id_content;
				}
				// dd($arr_service, \DB::getQueryLog());
				$contents = $contents ->whereIn('contents.id',$arr_service);
			}
			$contents = $contents->distinct('contents.id');
			$contents1 = clone $contents;
			$contents2 = clone $contents;

			if($request->location){
				$currentLocation = explode(',', $request->location);
				if(count($currentLocation)==2){
					$lat = $currentLocation[0];
					$lng = $currentLocation[1];
					$distance = $request->distance?$request->distance:500;
					$contents = $contents->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= ".$distance
													)
												->orderBy('line');
					$contents1 = $contents1->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= 25000"
													)
												->orderBy('line');
					$contents2 = $contents2->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
				}
			}


			$skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;
			
			$contents =  $contents
									 // ->offset($skip)
									 // ->limit($limit)
									 ->get();

			// dd($contents, \DB::getQueryLog());
			if(count($contents)){
				$data = $contents->toArray();
			}else{
				$contents1 = $contents1->offset($skip)
															 ->distinct('contents.id')
															 ->limit($limit)
															 ->get();
				if(count($contents1)){
					$data = $contents1->toArray();
				}else{
					$contents2 = $contents2->offset($skip)
															 ->distinct('contents.id')
															 ->limit($limit)
															 ->get();
					if(count($contents2)){
						$data = $contents2->toArray();
					}								
				}
			}

			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function detail(Request $request, $id=0){
		try{
			$content = null;
			$data = [];
			$content = Content::select('*')
												->where([['id','=',$id],['moderation','=','publish'],['active','=',1]])
												->with('_country')
												->with('_city')
												->with('_district')
												->with('_category_type')
												->with('_branchs')
                        ->with('_category_items')
                        ->with('_date_open')
                        ->with('_date_open_api')
												->with('_comments');

			if($request->location){
				$currentLocation = explode(',', $request->location);
				if(count($currentLocation)==2){
					$lat = $currentLocation[0];
					$lng = $currentLocation[1];
					$content = $content->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													");
				}
			}
			$content = $content->first();
			if($content){
				$timezone = $this->getTimezoneGeo($content->lat,$content->lng);
				$current_time = $this->getTimeByTimeZone($timezone);
				$content->open = check_open_time($content->_date_open, $current_time);
				$content->open_time = create_open_time($content->_date_open, \App::getLocale());

				$data['image_space'] = $this->convert_image(ImageSpace::where('id_content', '=', $content->id)->get());
				$data['image_menu'] = $this->convert_image(ImageMenu::where('id_content', '=', $content->id)->get());
				$data['link_video'] = LinkContent::where('id_content', '=', $content->id)->get();

				$data['products'] = Product::where('content_id', '=', $content->id)->orderBy('group_name')->get();
		    $data['group_product'] = Product::where('content_id', '=', $content->id)
		                                    ->groupBy('group_name')
		                                    ->whereNotNull('group_name')
		                                    ->pluck('group_name');
				$data['list_product'] = [];
		    $data['list_product']['no_group']['group_name'] = '';
		    $arr_has_group=[];
		    $arr_no_group=[];
		    foreach ($data['group_product'] as $key => $group) {
		        $data['list_product'][$key]['group_name'] = $group;
		        foreach ($data['products'] as $key2 => $product) {
		            if($product->group_name === $group && !in_array($product->id,$arr_has_group)){
		                $data['list_product'][$key][] = $product;
		                $arr_has_group[] = $product->id;
		            }else{
		                if($product->group_name===null && !in_array($product->id,$arr_no_group)){
		                    $data['list_product']['no_group'][] = $product;
		                    $arr_no_group[] = $product->id;
		                }
		            }
		        }
		    }

		    if(count($data['list_product']['no_group'])<2){
		        unset($data['list_product']['no_group']);
		        $data['list_product'] = array_values($data['list_product']);
		    }

		    $data['discounts'] = [];
		    $data['discounts'] = Discount::where('id_content',$content->id)
		                                    ->where('date_from','<=',Carbon::now())
		                                    ->where('date_to','>=',Carbon::now())
		                                    ->with('_products')
		                                    ->get();

				$data['list_service'] =  CategoryService::select(
																									'id_service_item',
																									'service_items.name'
																								)
																								->where('id_category', '=', $content->id_category)
																								->leftJoin('service_items','id_service_item','=','service_items.id')
																								->get();
				$data['service_content'] = ServiceContent::where('id_content', '=', $content->id)->pluck('id_service_item')->toArray();
				$id_category_content = $content->id_category;
				$data['list_suggest'] =  Content::select(
																					'contents.id',
																					'contents.name',
																					'contents.tag',
																					'contents.address',
																					'contents.lat',
																					'contents.lat as latitude',
																					'contents.lng',
																					'contents.lng as longitude',
																					'contents.vote',
																					'contents.like',
																					'contents.alias',
																					'contents.avatar',
																					\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"),
																					'contents.country',
																					'contents.city',
																					'contents.district'
																				)
																				->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
																				->orderBy('line')
																				->distinct()
																				->where('contents.id_category', '=', $id_category_content)
																				->where('contents.moderation', '=', 'publish')
																				->where('contents.active', '=', 1)
																				->where('contents.id', '!=', $content->id)
																				->with('_country')
																				->with('_city')
																				->with('_district')
																				->limit(20)
																				->get();

				$id_group_content = GroupContent::where('id_content', '=', $content->id)->pluck('id_group')->first();
				$data['list_group'] = [];
				if ($id_group_content) {
					$data['list_group'] = Content::select('contents.*')
						->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
						->orderBy('line')
						->distinct()
						->with('_country')
						->with('_city')
						->with('_district')
						->join('group_content', 'contents.id', '=', 'group_content.id_content')
						->where('group_content.id_group', '=', $id_group_content)
						->where('contents.id', '!=', $content->id)
						->where('contents.moderation', '=', 'publish')
						->where('contents.active', '=', 1)
						// ->limit(8)
						->get();
				}
				
				$content->_date_open = $content->_date_open_api;
				$data['content'] = $content->toArray();
				$data['content']['has_vote'] = 0;
				$data['content']['has_like'] = 0;
				$data['content']['has_checkin'] = 0;
				$data['content']['has_save_like'] = 0;
				$data['content']['has_collection'] = [];

				if(count($data['content']['_comments'])){
					foreach ($data['content']['_comments'] as $key => $comment) {
						if(count($data['content']['_comments'][$key]['_images'])){
							$arr_image = [];
							foreach ($data['content']['_comments'][$key]['_images'] as $key2 => $img) {
								$arr_image[] = $img['link'];
							}
							$data['content']['_comments'][$key]['_images'] = $this->convert_image($arr_image);
						}
						$data['content']['_comments'][$key]['content'] = str_replace("&nbsp;", " ", $data['content']['_comments'][$key]['content']);
					}
				}
				if(\Auth::guard('web_client')->user()){
					$has_vote = VoteContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					$has_like = LikeContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();

					$has_checkin = Checkin::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					$has_save_like = SaveLikeContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					$has_collection = CollectionContent::where('content_id',$content->id)
																						 ->leftJoin('collection','collection_id','=','collection.id')
																						 ->where('collection.created_by',\Auth::guard('web_client')->user()->id)
																						 ->pluck('collection_id');

					if($has_vote){
						$data['content']['has_vote'] = $has_vote->vote_point;
					}
					if($has_like){
						$data['content']['has_like'] = 1;
					}

					if($has_checkin){
						$data['content']['has_checkin'] = 1;
					}

					if($has_save_like){
						$data['content']['has_save_like'] = 1;
					}

					if($has_collection){
						$data['content']['has_collection'] = $has_collection->toArray();
					}
				}

				$data['category'] = Category::find($content->id_category);
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function detail_update(Request $request, $id=0){
		try{
			$content = null;
			$data = [];
			$content = Content::select('*')
												->where('id',$id)
												->with('_country')
												->with('_city')
												->with('_district')
												->with('_category_type')
												->with('_branchs')
                        ->with('_category_items')
                        ->with('_date_open')
                        ->with('_date_open_api')
												->with('_comments');

			if($request->location){
				$currentLocation = explode(',', $request->location);
				if(count($currentLocation)==2){
					$lat = $currentLocation[0];
					$lng = $currentLocation[1];
					$content = $content->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													");
				}
			}
			$content = $content->first();
			if($content){
				$timezone = $this->getTimezoneGeo($content->lat,$content->lng);
				$current_time = $this->getTimeByTimeZone($timezone);
				$content->open = check_open_time($content->_date_open, $current_time);
				$content->open_time = create_open_time($content->_date_open, \App::getLocale());

				$data['image_space'] = $this->convert_image(ImageSpace::where('id_content', '=', $content->id)->get());
				$data['image_menu'] = $this->convert_image(ImageMenu::where('id_content', '=', $content->id)->get());
				// $link_video = LinkContent::where('id_content', '=', $content->id)->pluck('link')->toArray();
				// $data['link_video'] = [];
				// foreach($link_video as $value){
				// 	if (strpos($value,'facebook.com') == TRUE){
				// 		// $data['link_video'][] = "https://www.facebook.com/plugins/video.php?href=$value";
				// 		$data['link_video'][] = $value;
				// 	}elseif(strpos($value,'vimeo.com') == TRUE){
				// 		$data['link_video'][] = str_replace('vimeo.com','player.vimeo.com/video',$value);
				// 	}elseif(strpos($value,'youtube.com') == TRUE || strpos($value,'youtu.be') == TRUE){
				// 		$data['link_video'][] = str_replace('watch?v=','embed/',$value);
				// 	}else{
				// 		$data['link_video'][] = $value;
				// 	}
				// }
				$data['link_video'] = LinkContent::where('id_content', '=', $content->id)->get();

				$data['products'] = Product::where('content_id', '=', $content->id)->orderBy('group_name')->get();
		    $data['group_product'] = Product::where('content_id', '=', $content->id)
		                                    ->groupBy('group_name')
		                                    ->whereNotNull('group_name')
		                                    ->pluck('group_name');
				$data['list_product'] = [];
		    $data['list_product']['no_group']['group_name'] = '';
		    $arr_has_group=[];
		    $arr_no_group=[];
		    foreach ($data['group_product'] as $key => $group) {
		        $data['list_product'][$key]['group_name'] = $group;
		        foreach ($data['products'] as $key2 => $product) {
		            if($product->group_name === $group && !in_array($product->id,$arr_has_group)){
		                $data['list_product'][$key][] = $product;
		                $arr_has_group[] = $product->id;
		            }else{
		                if($product->group_name===null && !in_array($product->id,$arr_no_group)){
		                    $data['list_product']['no_group'][] = $product;
		                    $arr_no_group[] = $product->id;
		                }
		            }
		        }
		    }

		    if(count($data['list_product']['no_group'])<2){
		        unset($data['list_product']['no_group']);
		        $data['list_product'] = array_values($data['list_product']);
		    }

		    $data['discounts'] = [];
		    $data['discounts'] = Discount::where('id_content',$content->id)
		                                    ->where('date_from','<=',Carbon::now())
		                                    ->where('date_to','>=',Carbon::now())
		                                    ->with('_products')
		                                    ->get();

				$data['list_service'] =  CategoryService::select(
																									'id_service_item',
																									'service_items.name'
																								)
																								->where('id_category', '=', $content->id_category)
																								->leftJoin('service_items','id_service_item','=','service_items.id')
																								->get();
				$data['service_content'] = ServiceContent::where('id_content', '=', $content->id)->pluck('id_service_item')->toArray();
				$id_category_content = $content->id_category;
				$data['list_suggest'] =  Content::select(
																					'contents.id',
																					'contents.name',
																					'contents.tag',
																					'contents.address',
																					'contents.lat',
																					'contents.lat as latitude',
																					'contents.lng',
																					'contents.lng as longitude',
																					'contents.vote',
																					'contents.like',
																					'contents.alias',
																					'contents.avatar',
																					\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"),
																					'contents.country',
																					'contents.city',
																					'contents.district'
																				)
																				->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
																				->orderBy('line')
																				->distinct()
																				->where('contents.id_category', '=', $id_category_content)
																				->where('contents.moderation', '=', 'publish')
																				->where('contents.active', '=', 1)
																				->where('contents.id', '!=', $content->id)
																				->with('_country')
																				->with('_city')
																				->with('_district')
																				->limit(20)
																				->get();

				$id_group_content = GroupContent::where('id_content', '=', $content->id)->pluck('id_group')->first();
				$data['list_group'] = [];
				if ($id_group_content) {
					$data['list_group'] = Content::select('contents.*')
						->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
						->orderBy('line')
						->distinct()
						->with('_country')
						->with('_city')
						->with('_district')
						->join('group_content', 'contents.id', '=', 'group_content.id_content')
						->where('group_content.id_group', '=', $id_group_content)
						->where('contents.id', '!=', $content->id)
						->where('contents.moderation', '=', 'publish')
						->where('contents.active', '=', 1)
						// ->limit(8)
						->get();
				}
				
				$content->_date_open = $content->_date_open_api;
				$data['content'] = $content->toArray();
				$data['content']['has_vote'] = 0;
				$data['content']['has_like'] = 0;
				$data['content']['has_checkin'] = 0;
				$data['content']['has_save_like'] = 0;
				$data['content']['has_collection'] = [];

				if(count($data['content']['_comments'])){
					foreach ($data['content']['_comments'] as $key => $comment) {
						if(count($data['content']['_comments'][$key]['_images'])){
							$arr_image = [];
							foreach ($data['content']['_comments'][$key]['_images'] as $key2 => $img) {
								$arr_image[] = $img['link'];
							}
							$data['content']['_comments'][$key]['_images'] = $this->convert_image($arr_image);
						}

					}
				}
				if(\Auth::guard('web_client')->user()){
					$has_vote = VoteContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					$has_like = LikeContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();

					$has_checkin = Checkin::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					$has_save_like = SaveLikeContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					$has_collection = CollectionContent::where('content_id',$content->id)
																						 ->leftJoin('collection','collection_id','=','collection.id')
																						 ->where('collection.created_by',\Auth::guard('web_client')->user()->id)
																						 ->pluck('collection_id');

					if($has_vote){
						$data['content']['has_vote'] = $has_vote->vote_point;
					}
					if($has_like){
						$data['content']['has_like'] = 1;
					}

					if($has_checkin){
						$data['content']['has_checkin'] = 1;
					}

					if($has_save_like){
						$data['content']['has_save_like'] = 1;
					}

					if($has_collection){
						$data['content']['has_collection'] = $has_collection->toArray();
					}
				}

				
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function likeContent(Request $request){
		try{
			$rules = [
				'content' => 'required',
				'user' => 'required'
			];
			$messages = [
				'content.required' => 'Content is required',
				'user.required' => 'User is required',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$id_content = $request->content;
				$id_user = $request->user;

				$check_exits = LikeContent::where('id_content', '=', $id_content)
																			->where('id_user', '=', $id_user)->first();

				if ($check_exits) {
					$content = Content::find($id_content);
					$content->like = $content->like - 1;

					if ($content->save()) {
						LikeContent::where('id_content', '=', $id_content)
										->where('id_user', '=', $id_user)->delete();
						$data['like'] = $content->like;
						$data['is_like'] = 0;
						return $this->response($data,200);
					}
				} else {
					LikeContent::create([
						'id_content' => $id_content,
						'id_user' => $id_user,
					]);

					$content = Content::find($id_content);
					$content->like = $content->like + 1;
					if ($content->save()) {
						$data['like'] = $content->like;
						$data['is_like'] = 1;
						return $this->response($data,200);
					}
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function saveLikeContent(Request $request){
		try{
			$rules = [
				'content' => 'required',
				'user' => 'required'
			];
			$messages = [
				'content.required' => 'Content is required',
				'user.required' => 'User is required',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$id_content = $request->content;
				$id_user = $request->user;

				$check_exits = SaveLikeContent::where('id_content', '=', $id_content)
																			->where('id_user', '=', $id_user)
																			->first();

				if ($check_exits) {
					$content = Content::find($id_content);
					$content->like = $content->like - 1;
					if($content->like < 0){
		        $content->like = 0;
		      }
					if ($content->save()) {
						SaveLikeContent::where('id_content', '=', $id_content)
										->where('id_user', '=', $id_user)->delete();
						$data['like'] = $content->like;
						$data['is_like'] = 0;
						return $this->response($data,200);
					}
				} else {
					SaveLikeContent::create([
						'id_content' => $id_content,
						'id_user' => $id_user,
					]);

					$content = Content::find($id_content);
					$content->like = $content->like + 1;
					if ($content->save()) {
						$data['like'] = $content->like;
						$data['is_like'] = 1;
						return $this->response($data,200);
					}
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function voteContent(Request $request){
		try{
			$rules = [
				'content' => 'required',
				'user' => 'required',
				'point' => 'required|integer|between:0.5,5',
			];
			$messages = [
				'content.required' => 'Content is required',
				'user.required' => 'User is required',
				'point.required' => 'Point is required',
				'point.between' => 'Point in 0.5 - 5',
				'point.integer' => 'Point must be numeric',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$id_content = $request->content;
				$id_user = $request->user;
				$point = $request->point;

				VoteContent::where('id_content', '=', $id_content)
                   ->where('id_user', '=', $id_user)->delete();
		    VoteContent::create([
		      'id_content' => $id_content,
		      'id_user' => $id_user,
		      'vote_point' => $point,
		    ]);
		    
				$data['vote'] = $point;
				$avg_point = VoteContent::where('id_content', '=', $id_content)->avg('vote_point');

				$content = Content::find($id_content);
				$content->vote = round($avg_point,2);
				if ($content->save()) {
					$data['vote_average'] = $content->vote;
					return $this->response($data,200);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function checkinContent(Request $request){
		try{
			$rules = [
				'content' => 'required',
				'user' => 'required'
			];
			$messages = [
				'content.required' => 'Content is required',
				'user.required' => 'User is required',
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$id_content = $request->content;
				$id_user = $request->user;

				$check_exits = Checkin::where('id_content', '=', $id_content)
																			->where('id_user', '=', $id_user)->first();

				if ($check_exits) {
					$content = Content::find($id_content);
					$content->checkin = $content->checkin - 1;

					if ($content->save()) {
						Checkin::where('id_content', '=', $id_content)
										->where('id_user', '=', $id_user)->delete();
						$data['checkin'] = $content->checkin;
						$data['is_like'] = 0;
						return $this->response($data,200);
					}
				} else {
					Checkin::create([
						'id_content' => $id_content,
						'id_user' => $id_user,
					]);

					$content = Content::find($id_content);
					$content->checkin = $content->checkin + 1;
					if ($content->save()) {
						$data['checkin'] = $content->checkin;
						$data['is_like'] = 1;
						return $this->response($data,200);
					}
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getTimezoneGeo($latitude, $longitude) {
		$json = file_get_contents("https://maps.googleapis.com/maps/api/timezone/json?location=".$latitude.",".$longitude."&timestamp=0&key=AIzaSyCAYUliiygXBI8KNyn5oSlwrprv5eZ-Cl8");
		$data = json_decode($json);
		$tzone=$data->timeZoneId;
		return $tzone;
	}

	public function getTimeByTimeZone($timezone)
	{
		$current_tz = new \DateTimeZone($timezone);
		$now = new \DateTime('now', $current_tz);
		return $now->format('d-m-Y H:i:s');
	}


	public function getStatic(){
		try{
			$data = Content::getStatic();
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getListLocation(Request $request){
		$data = [
			'your_location' => [],
			'new_location' => 0,
			'pay_money' => [
				'quang_cao' => 0,
				'rao_vat' => 0,
				'thue_web' => 0,
				'khuyen_mai' => 0,
				'phan_mem' => 0,
			],
			'make_money' => 0,
		];
		if($request->code){
			$contents = Content::select('contents.id','contents.name')
												 ->where('code_invite',$request->code)
												 ->get();
			$data['new_location'] = Content::where('code_invite',$request->code)
												 ->whereMonth('created_at', '=', date('m'))
												 ->count();
			$arr_id_content = Content::where('code_invite',$request->code)
												 ->pluck('id');

			$data['pay_money']['quang_cao'] = PaymentAds::whereIn('content_id',$arr_id_content)
																			 ->whereMonth('created_at', '=', date('m'))->sum('total');
			$user = Client::where('code_invite',$request->code)->first();

			$data['make_money'] = ( $data['pay_money']['quang_cao'] +
				                      $data['pay_money']['rao_vat'] +
				                      $data['pay_money']['thue_web'] +
				                      $data['pay_money']['khuyen_mai'] +
				                      $data['pay_money']['phan_mem'] ) * $user->rate_revenue/100 ;
			if($contents){
				$data['your_location'] = $contents->toArray();
			}
			return $this->response([$data],200);
		}else{
			return $this->response([],200);
		}
	}


	public function postCreateCategoryItem(Request $request){
		if($request->category_item){
      $name = $request->category_item;
      $check = CategoryItem::where('machine_name',str_replace('-', '_',str_slug(str_slug_custom(clear_str($name)))))->first();
      if($check){
      	$err = new \Exception(trans('valid.category_item_unique'),400);
        return $this->error($err);
      }else{
        $weight = CategoryItem::where('category_id', '=',$request->category)->max('weight');
        $category_item = new CategoryItem();
        $category_item->name = $name;
        $category_item->alias = str_slug(str_slug_custom(clear_str($name)));
        $category_item->machine_name = str_replace('-', '_',str_slug(str_slug_custom(clear_str($name))));
        $category_item->active = 0;
        $category_item->approved = 0;

        $category_item->language = 'vn';
        $category_item->weight = isset($weight)?$weight + 1:0;
        $category_item->category_id = $request->category;
        $category_item->description = $request->description;
        $category_item->image ='/frontend/assets/img/upload/cate3.png';
        if($category_item->save()){
          $link = ADMIN_URL.'/category_item/'.$request->category.'/approve';
          $content_notifi = trans('valid.notify_admin_create_category_item',['name'=>$category_item->name]);
          $notifi_admin = new NotifiAdmin();
          $notifi_admin->createNotifi($content_notifi,$link);

          $data = CategoryItem::find($category_item->id)->toArray();
          return $this->response($data,200);
        }
      }
    }else{
    	$err = new \Exception(trans('valid.category_item_input'),400);
      return $this->error($err);
    }
	}
	public function postCreateCategory(Request $request){
		if($request->category){
      $name = $request->category;
      $check = Category::where('machine_name',str_replace('-', '_',str_slug(str_slug_custom(clear_str($name)))))->first();
      if($check){
        $err = new \Exception(trans('valid.category_unique'),400);
        return $this->error($err);
      }else{
        $category = new Category();
        $category->name = $name;
        $category->alias = str_slug(str_slug_custom(clear_str($name)));
        $category->machine_name = str_replace('-', '_',str_slug(str_slug_custom(clear_str($name))));
        $category->image ='/frontend/assets/img/icon/logo-large.png';
        $category->background ='/frontend/assets/img/upload/bg-food2.jpg';
        $category->marker ='/img_default/marker.svg';
        $category->language = 'vn';
        $category->weight = Category::max('weight') + 1;
        $category->active = 0;
        $category->approved = 0;

        if($category->save()){

          $link = ADMIN_URL.'/category/approve';
          $content_notifi = trans('valid.notify_admin_create_category',['name'=>$category->name]);
          $notifi_admin = new NotifiAdmin();
          $notifi_admin->createNotifi($content_notifi,$link);

          $data = Category::find($category->id)->toArray();
          return $this->response($data,200);
        }
      }
    }else{
    	$err = new \Exception(trans('valid.category_input'),400);
      return $this->error($err);
    }
	}
	public function postCreateService(Request $request){
		if($request->service){
      $name = $request->service;
      $check = ServiceItem::where('machine_name',str_replace('-', '_',str_slug(str_slug_custom(clear_str($name)))))->first();
      if($check){
        $err = new \Exception(trans('valid.service_unique'),400);
        return $this->error($err);
      }else{
        $service = new ServiceItem();
        $service->name = $name;
        $service->machine_name = str_replace('-', '_',str_slug(str_slug_custom(clear_str($name))));
        $service->active = 0;
        $service->approved = 0;

        if($service->save()){
          $category_service = new CategoryService();
          $category_service->id_category = $request->category;
          $category_service->id_service_item = $service->id;
          $category_service->save();

          $link = ADMIN_URL.'/service_item/approve';
          $content_notifi = trans('valid.notify_admin_create_service',['name'=>$service->name]);
          $notifi_admin = new NotifiAdmin();
          $notifi_admin->createNotifi($content_notifi,$link);

          $data = ServiceItem::find($service->id)->toArray();
          return $this->response($data,200);
        }
      }
    }else{
      $err = new \Exception(trans('valid.service_input'),400);
        return $this->error($err);
    }
	}


	public function postCreateLocation(Request $request)
  {
  	$check_create = false;

  	$rules = [
      'name' => 'required|unique:contents,name',
      // 'alias' => 'required|unique:contents,alias',
      'id_category' => 'required',
      'category_item' => 'required',
      'email' => 'email',
      'date_open' => 'required',
      // 'open_to' => 'required',
      // 'open_from' => 'required',
      // 'price_from' => 'required|integer|min:0',
      // 'price_to' => 'required|integer|min:0',
      'country' => 'required',
      'city' => 'required',
      'district' => 'required',
      'avatar' => 'required',
      'address' => 'required',
      'lat' => 'required',
      'lng' => 'required',
      'tag' => 'required',
    ];

    if($request->id_category == 5)
    {
      unset($rules['price_from']);
      unset($rules['price_to']);
    }
    if(!isset($request->email))
    {
      unset($rules['email']);
    }

    if(isset($request->type_submit) && $request->type_submit == 'update')
    {
      unset($rules['avatar']);
      $content_update = Content::find($request->id_edit_content);
      if (trim($content_update->name) == $request->name) {
        $rules['name'] = 'required';
      }

      if ($content_update->alias == $request->alias) {
        $rules['alias'] = 'required';
      }
    }
    $messages = [
      'name.required' => \Lang::get('Location/layout.name_required'),
      'name.unique' => \Lang::get('Location/layout.name_unique'),
      'id_category.required' => \Lang::get('Location/layout.id_category_required'),
      'category_item.required' => \Lang::get('Location/layout.category_item_required'),
      // 'alias.required' => \Lang::get('Location/layout.alias_required'),
      // 'alias.unique' => \Lang::get('Location/layout.alias_unique'),
      'country.required' => \Lang::get('Location/layout.country_required'),
      'city.required' => \Lang::get('Location/layout.city_required'),
      'district.required' => \Lang::get('Location/layout.district_required'),
      'address.required' => \Lang::get('Location/layout.address_required'),
      'lat.required' => \Lang::get('Location/layout.lat_required'),
      'lng.required' => \Lang::get('Location/layout.lng_required'),
      'email.email' => \Lang::get('Location/layout.email_email'),
      'date_open.required' => \Lang::get('Location/layout.open_from_required'),
      // 'open_to.required' => \Lang::get('Location/layout.open_to_required'),
      // 'price_from.required' => \Lang::get('Location/layout.price_from_required'),
      // 'price_from.integer' => \Lang::get('Location/layout.price_from_integer'),
      // 'price_from.min' => \Lang::get('Location/layout.price_from_min'),
      // 'price_to.required' => \Lang::get('Location/layout.price_to_required'),
      // 'price_to.integer' => \Lang::get('Location/layout.price_to_integer'),
      // 'price_to.min' => \Lang::get('Location/layout.price_to_min'),
      'avatar.required' => \Lang::get('Location/layout.avatar_required'),
      'tag.required' => \Lang::get('Location/layout.tag_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      $err = new \Exception($validator->errors()->first(),400);
      return $this->error($err);
    } else {
      $check_create = true;
    }

    if($check_create === false){
    	$err = new \Exception('Error create location',400);
      return $this->error($err);
    }

    $id_user = \Auth::guard('web_client')->user()?\Auth::guard('web_client')->user()->id:$request->id_user;
    if($id_user){
    	if ($request->hasFile('avatar')) {
	      $file = $request->file('avatar');

	      $path = public_path() . '/upload/img_content/';
	      $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
	      if (!\File::exists($path)) {
	        \File::makeDirectory($path, $mode = 0777, true, true);
	      }
	      if (!\File::exists($path_thumbnail)) {
	        \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
	      }

	      $img_name = time() . '_avatar_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

	      if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
					self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

	      $content_avatar = '/upload/img_content/' . $img_name;
	    }

	    if($request->tag)
	    {
	      $tag = '';
	      foreach ($request->tag as $value){
	        $tag .= $value .',';
	      }
	    }

	    $data = [
	      'name' => $request->name,
	      'alias' => str_slug($request->name),
	      'id_category' => $request->id_category,
	      'country' => $request->country,
	      'city' => $request->city,
	      'district' => $request->district,
	      'address' => $request->address?$request->address:"",
	      'tag' => isset($tag)?rtrim($tag,','):'',
	      'phone' => isset($request->phone) ? $request->phone : '',
	      'price_from' => isset($request->price_from) ? $request->price_from : 0,
	      'price_to' => isset($request->price_to) ? $request->price_to : 0,
	      'currency' => $request->currency?$request->currency:'VND',
	      'website' => $request->website,
	      'email' => isset($request->email) ? $request->email : '',
	      'description' => $request->description?$request->description:"",
				'wifi' => $request->wifi?$request->wifi:'',
				'pass_wifi' => $request->pass_wifi?$request->pass_wifi:'',
	      'avatar' => $content_avatar,
	      'vote' => 0,
	      'like' => 0,
	      'type_user' => 0,
	      'active' => 0,
	      'lat' => $request->lat,
	      'lng' => $request->lng,
	      'moderation' => 'request_publish',
	      'created_by' => $id_user,
	      'updated_by' => $id_user,
	      'code_invite' => $request->ma_dinh_danh?$request->ma_dinh_danh:'',

	    ];

	    if($request->id_category == 5)
	    {
	      $data['extra_type'] = $request->bank_type;
	    }

	    $content = Content::create($data);

	    if(\Auth::guard('web_client')->user()){
				$role = \Auth::guard('web_client')->user()->getRole('cong_tac_vien')->first();
				if($role && $role->active){
					$content->code_invite = \Auth::guard('web_client')->user()->ma_dinh_danh;
					$content->daily_code = \Auth::guard('web_client')->user()->daily_code;
					$ctv = CTV::where('client_id',\Auth::guard('web_client')->user()->id)->first();
					$content->ctv_id = $ctv->id;
					$content->daily_id = $ctv->daily_id;
					$content->save();
				}
			}
			
	    $lastIdContent = $content->id;

	    // if ($request->product) {
	    //     foreach ($request->product as $group) {
	    //         $group_name =  $group['group_name'];
	    //         foreach ($group as $key => $value) {
	    //             if($key !== 'group_name'){
	    //                 if($value['id'] == 0){
	    //                     $product = new Product();
	    //                 }else{
	    //                     $product = Product::find($value['id']);
	    //                 }

	    //                 if($product && isset($value['name']) && $value['name']!=''){
	    //                     $product->name       = $value['name'];
	    //                     $product->price      = $value['price']?$value['price']:0;
	    //                     if(isset($value['image'])){
	    //                         $file = $value['image'];
	    //                         $path = public_path() . '/upload/product/';
	    //                         $path_thumbnail = public_path() . '/upload/product_thumbnail/';
	    //                         if (!\File::exists($path)) {
	    //                             \File::makeDirectory($path, $mode = 0777, true, true);
	    //                         }
	    //                         if (!\File::exists($path_thumbnail)) {
	    //                             \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
	    //                         }

	    //                         $img_name = time() . '_product_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

	    //                         if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
	    //                           self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

	    //                         $image_product = '/upload/product/' . $img_name;
	    //                         $product->image      = $image_product;
	    //                     }
	    //                     $product->content_id = $lastIdContent;
	    //                     $product->type_user  = 0;
	    //                     $product->created_by = Auth::guard('web_client')->user()->id;
	    //                     $product->updated_by = Auth::guard('web_client')->user()->id;
	    //                     $product->group_name = $group_name;
	    //                     $product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
	    //                     $product->currency   = $value['currency']?$value['currency']:'VND';
	    //                     $product->save();
	    //                 }
	    //             }
	    //         }
	    //     }
	    // }

	    if ($request->date_open) {
	        foreach ($request->date_open as $value) {
	            if ($value['from_hour'] && $value['to_hour']) {
	                DateOpen::create([
	                    'id_content' => $lastIdContent,
	                    'date_from' => $value['from_date'],
	                    'date_to' => $value['to_date'],
	                    'open_from' => $value['from_hour'],
	                    'open_to' => $value['to_hour'],
	                    'angle_from' => $value['angle_from']?$value['angle_from']:0,
	                    'angle_to' => $value['angle_to']?$value['angle_to']:0,
	                ]);
	            }
	        }
	    }

      foreach ($request->category_item as $value) {
        CategoryContent::create([
          'id_content' => $lastIdContent,
          'id_category_item' => $value,
        ]);
      }

	    if ($request->service) {
	      foreach ($request->service as $value) {
	        ServiceContent::create([
	          'id_content' => $lastIdContent,
	          'id_service_item' => $value,
	        ]);
	      }
	    }

	    if ($request->group) {
	      GroupContent::create([
	        'id_content' => $lastIdContent,
	        'id_group' => $request->group,
	      ]);
	    }

	    if ($request->image_space) {
	      $path = public_path() . '/upload/img_content/';
	      $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
	      if (!\File::exists($path)) {
	        \File::makeDirectory($path, $mode = 0777, true, true);
	      }
	      if (!\File::exists($path_thumbnail)) {
	        \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
	      }
	      $arr_des_space = $request->des_space;
	      $arr_title_space = $request->title_space;
	      foreach ($request->image_space as $key => $file) {

	        $img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

	        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
	          self::waterMark($file, $img_name, $path, $path_thumbnail);

	        $image_space = '/upload/img_content/' . $img_name;

	        ImageSpace::create([
	          'id_content' => $lastIdContent,
	          'name' => $image_space,
	          'title'=> $arr_title_space[$key],
	          'description'=> $arr_des_space[$key]
	        ]);
	      }
	    }

	    if ($request->image_menu) {
	      $path = public_path() . '/upload/img_content/';
	      $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
	      if (!\File::exists($path)) {
	        \File::makeDirectory($path, $mode = 0777, true, true);
	      }
	      if (!\File::exists($path_thumbnail)) {
	        \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
	      }
	      $arr_des_menu = $request->des_menu;
	      $arr_title_menu = $request->title_menu;
	      foreach ($request->image_menu as $key => $file) {

	        $img_name = time() . '_menu_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

	        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
	          self::waterMark($file, $img_name, $path, $path_thumbnail);

	        $image_menu = '/upload/img_content/' . $img_name;

	        ImageMenu::create([
	          'id_content' => $lastIdContent,
	          'name' => $image_menu,
	          'title'=> $arr_title_menu[$key],
	          'description'=> $arr_des_menu[$key]
	        ]);
	      }
	    }
	    
	    if($request->link){
	      foreach ($request->link as $value)
	      {
	        if(isset($value))
	        {
	            $infoVideo = app('App\Http\Controllers\Admin\ContentController')->getInfoVideo($value);
	            if(count($infoVideo)>0){
	                LinkContent::create([
	                    'id_content' => $lastIdContent,
	                    'link' => $value,
	                    'type'=> $infoVideo['type'],
	                    'time'=>$infoVideo['time'],
	                    'title'=>$infoVideo['title'],
	                    'id_video'=>$infoVideo['id_video'],
	                    'thumbnail'=>$infoVideo['thumbnail']
	                ]);
	            };
	        }
	      }
	    }


	    // if(!$data['description'] || empty($data['description'])){
	    //   $description = '';
	    //   $content = Content::where('id','=',$lastIdContent)
	    //                  ->with('_category_type')
	    //                  ->with('_category_items')
	    //                  ->with('_country')
	    //                  ->with('_city')
	    //                  ->with('_district')
	    //                  ->with('_date_open_api')
	    //                  ->first();
	    //   $description .= $content->name.' ';
	    //   $description .= 'tại '.$content->address.' '.$content->_district->name.' '.$content->_city->name.' '.$content->_country->name.', ';
	    //   if($content->_category_items){
	    //       $description .= 'thuộc thể loại ';
	    //       foreach ($content->_category_items as $key_cat => $cat_item) {
	    //           if($key_cat==0){
	    //               $description .= mb_strtolower($cat_item->name);
	    //           }else{
	    //               $description .= ' - '.mb_strtolower($cat_item->name);
	    //           }

	    //       }
	    //   }else{
	    //       if($content->_category_type){
	    //           $description .= 'thuộc thể loại '.mb_strtolower($content->_category_type->name);
	    //       }
	    //   }

	    //   if($content->_date_open_api){
	    //       $description .= ', mở cửa '.mb_strtolower(create_open_time($content->_date_open_api, \App::getLocale())).', ';
	    //   }
	    //   if($content->price_from > 0 && $content->price_to > 0){
	    //       $description .= 'giá từ '.$content->price_from.$content->currency.' ';
	    //       $description .= 'đến '.$content->price_to.$content->currency;
	    //   }
	    //   $description .='.';
	    //   $content->description = $description;
	    //   $content->save();
	    // }
	    $_category_type = Category::find($request->id_category);
			$link = ADMIN_URL.'/content/update/'.$_category_type->machine_name.'/'.$lastIdContent;
			$content_notifi = trans('valid.notify_admin_create_content',['name'=>$content->name]);
			$notifi_admin = new NotifiAdmin();
			$notifi_admin->createNotifi($content_notifi,$link);
			
	    create_tag_search($lastIdContent);

	    $id = $lastIdContent;

	    $content = null;
			$data = [];
			$content = Content::select('*')
												->where([['id','=',$id]])
												->with('_country')
												->with('_city')
												->with('_district')
												->with('_comments')
												->with('_date_open')
												->with('_date_open_api');

			if($request->location){
				$currentLocation = explode(',', $request->location);
				if(count($currentLocation)==2){
					$lat = $currentLocation[0];
					$lng = $currentLocation[1];
					$content = $content->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													");
				}
			}
			$content = $content->first();
			if($content){
				$timezone = $this->getTimezoneGeo($content->lat,$content->lng);
				$current_time = $this->getTimeByTimeZone($timezone);
				$content->open = check_open_time($content->_date_open, $current_time);
				$content->open_time = create_open_time($content->_date_open, \App::getLocale());

				$data['image_space'] = $this->convert_image(ImageSpace::where('id_content', '=', $content->id)->pluck('name'));
				$data['image_menu'] = $this->convert_image(ImageMenu::where('id_content', '=', $content->id)->pluck('name'));
				$link_video = LinkContent::where('id_content', '=', $content->id)->pluck('link')->toArray();
				$data['link_video'] = [];
				foreach($link_video as $value){
					if (strpos($value,'facebook.com') == TRUE){
						// $data['link_video'][] = "https://www.facebook.com/plugins/video.php?href=$value";
						$data['link_video'][] = $value;
					}elseif(strpos($value,'vimeo.com') == TRUE){
						$data['link_video'][] = str_replace('vimeo.com','player.vimeo.com/video',$value);
					}elseif(strpos($value,'youtube.com') == TRUE || strpos($value,'youtu.be') == TRUE){
						$data['link_video'][] = str_replace('watch?v=','embed/',$value);
					}else{
						$data['link_video'][] = $value;
					}
				}

				$data['list_service'] =  CategoryService::select(
																									'id_service_item',
																									'service_items.name'
																								)
																								->where('id_category', '=', $content->id_category)
																								->leftJoin('service_items','id_service_item','=','service_items.id')
																								->get();
				$data['service_content'] = ServiceContent::where('id_content', '=', $content->id)->pluck('id_service_item')->toArray();
				$id_category_content = $content->id_category;
				$data['list_suggest'] =  Content::select(
																					'contents.id',
																					'contents.name',
																					'contents.tag',
																					'contents.address',
																					'contents.lat',
																					'contents.lat as latitude',
																					'contents.lng',
																					'contents.lng as longitude',
																					'contents.vote',
																					'contents.like',
																					'contents.alias',
																					'contents.avatar',
																					\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"),
																					'contents.country',
																					'contents.city',
																					'contents.district'
																				)
																				->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
																				->orderBy('line')
																				->distinct()
																				->where('contents.id_category', '=', $id_category_content)
																				->where('contents.moderation', '=', 'publish')
																				->where('contents.active', '=', 1)
																				->where('contents.id', '!=', $content->id)
																				->with('_country')
																				->with('_city')
																				->with('_district')
																				->limit(20)
																				->get();

				$id_group_content = GroupContent::where('id_content', '=', $content->id)->pluck('id_group')->first();
				$data['list_group'] = [];
				if ($id_group_content) {
					$data['list_group'] = Content::select('contents.*')
						->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
						->orderBy('line')
						->distinct()
						->with('_country')
						->with('_city')
						->with('_district')
						->join('group_content', 'contents.id', '=', 'group_content.id_content')
						->where('group_content.id_group', '=', $id_group_content)
						->where('contents.id', '!=', $content->id)
						->where('contents.moderation', '=', 'publish')
						->where('contents.active', '=', 1)
						// ->limit(8)
						->get();
				}
				$content->_date_open = $content->_date_open_api;
				$data['content'] = $content->toArray();
				$data['content']['has_vote'] = 0;
				$data['content']['has_like'] = 0;
				if(\Auth::guard('web_client')->user()){
					$has_vote = VoteContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					$has_like = LikeContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					if($has_vote){
						$data['content']['has_vote'] = $has_vote->vote_point;
					}
					if($has_like){
						$data['content']['has_like'] = 1;
					}
				}
			}
			return $this->response($data,200);
    }else{
    	$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
			return $this->error($e);
    }

  }

  public function postUpdateLocation(Request $request)
  {
  	$check_create = false;

  	$rules = [
      // 'name' => 'required|unique:contents,name',
      // 'alias' => 'required|unique:contents,alias',
      'id_category' => 'required',
      'category_item' => 'required',
      'email' => 'email',
      'date_open' => 'required',
      // 'open_from' => 'required',
      // 'open_to' => 'required',
      // 'price_from' => 'required|integer|min:0',
      // 'price_to' => 'required|integer|min:0',
      'country' => 'required',
      'city' => 'required',
      'district' => 'required',
      // 'avatar' => 'required',
      'address' => 'required',
      'lat' => 'required',
      'lng' => 'required',
      'tag' => 'required',
    ];

    if($request->id_category == 5)
    {
      unset($rules['price_from']);
      unset($rules['price_to']);
    }
    if(!isset($request->email))
    {
      unset($rules['email']);
    }

    if(isset($request->type_submit) && $request->type_submit == 'update')
    {
      unset($rules['avatar']);
      $content_update = Content::find($request->id_edit_content);
      if (trim($content_update->name) == $request->name) {
        $rules['name'] = 'required';
      }

      if ($content_update->alias == $request->alias) {
        $rules['alias'] = 'required';
      }
    }
    $messages = [
      // 'name.required' => \Lang::get('Location/layout.name_required'),
      // 'name.unique' => \Lang::get('Location/layout.name_unique'),
      'id_category.required' => \Lang::get('Location/layout.id_category_required'),
      'category_item.required' => \Lang::get('Location/layout.category_item_required'),
      // 'alias.required' => \Lang::get('Location/layout.alias_required'),
      // 'alias.unique' => \Lang::get('Location/layout.alias_unique'),
      'country.required' => \Lang::get('Location/layout.country_required'),
      'city.required' => \Lang::get('Location/layout.city_required'),
      'district.required' => \Lang::get('Location/layout.district_required'),
      'address.required' => \Lang::get('Location/layout.address_required'),
      'lat.required' => \Lang::get('Location/layout.lat_required'),
      'lng.required' => \Lang::get('Location/layout.lng_required'),
      'email.email' => \Lang::get('Location/layout.email_email'),
      'date_open.required' => \Lang::get('Location/layout.open_from_required'),
      // 'open_to.required' => \Lang::get('Location/layout.open_to_required'),
      // 'price_from.required' => \Lang::get('Location/layout.price_from_required'),
      // 'price_from.integer' => \Lang::get('Location/layout.price_from_integer'),
      // 'price_from.min' => \Lang::get('Location/layout.price_from_min'),
      // 'price_to.required' => \Lang::get('Location/layout.price_to_required'),
      // 'price_to.integer' => \Lang::get('Location/layout.price_to_integer'),
      // 'price_to.min' => \Lang::get('Location/layout.price_to_min'),
      // 'avatar.required' => \Lang::get('Location/layout.avatar_required'),
      'tag.required' => \Lang::get('Location/layout.tag_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      $err = new \Exception($validator->errors()->first(),400);
      return $this->error($err);
    } else {
      $check_create = true;
    }

    if($check_create === false){
    	$err = new \Exception('Error create location',400);
      return $this->error($err);
    }

    $id_user = $request->id_user;

    if($id_user){
    	$id_content = $request->id_content?$request->id_content:0;
    	$content_update = Content::where('id',$id_content)
    													 ->where('created_by',$id_user)
    													 ->where('type_user',0)
    													 ->first();

    	if(!$content_update){
    		$e = new \Exception(trans('valid.not_found',['object'=>trans('global.locations')]),400);
    		return $this->error($e);
    	}

    	if ($request->hasFile('avatar')) {
	      $file = $request->file('avatar');
	      $path = public_path() . '/upload/img_content/';
	      $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
	      if (!\File::exists($path)) {
	        \File::makeDirectory($path, $mode = 0777, true, true);
	      }
	      if (!\File::exists($path_thumbnail)) {
	        \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
	      }

	      $img_name = time() . '_avatar_' . $file->getClientOriginalName();

	      if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
	        self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);
	      if (file_exists(public_path($content_update->avatar))) {
	        unlink(public_path($content_update->avatar));
	      }
	      if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)))) {
	        unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)));
	      }
	      $content_update->avatar = '/upload/img_content/' . $img_name;
	    }

	    $tag = '';
	    if($request->tag){
	      foreach ($request->tag as $value){
	        $tag .= $value .',';
	      }
	    }

	    $content_update->name = $request->name;
	    $content_update->alias = str_slug($request->name);
	    $content_update->id_category = $request->id_category;
	    $content_update->country = $request->country;
	    $content_update->city = $request->city;
	    $content_update->district = $request->district;
	    $content_update->address = $request->address?$request->address:"";
	    $content_update->tag = rtrim($tag,',');
	    $content_update->phone = isset($request->phone) ? $request->phone : '';
	    $content_update->price_from = isset($request->price_from) ? $request->price_from : 0;
	    $content_update->price_to = isset($request->price_to) ? $request->price_to : 0;
	    $content_update->currency = isset($request->currency) ? $request->currency : '';
	    $content_update->website = isset($request->website) ? $request->website : '';
	    $content_update->email = isset($request->email) ? $request->email : '';
	    $content_update->wifi = isset($request->wifi) ? $request->wifi : '';
	    $content_update->pass_wifi = isset($request->pass_wifi) ? $request->pass_wifi : '';
	    $content_update->description = $request->description?$request->description:"";
	    $content_update->lat = $request->lat;
	    $content_update->lng = $request->lng;
	    $content_update->updated_by = Auth::guard('web_client')->user()->id;
	    $content_update->type_user_update = 0;
	    if ($content_update->save()) {

	      $id = $id_content;

	      // if ($request->product) {
	      //     foreach ($request->product as $group) {
	      //         $group_name =  $group['group_name'];
	      //         foreach ($group as $key => $value) {
	      //             if($key !== 'group_name'){
	      //                 if($value['id'] == 0){
	      //                     $product = new Product();
	      //                 }else{
	      //                     $product = Product::find($value['id']);
	      //                 }

	      //                 if($product && isset($value['name']) && $value['name']!=''){
	      //                     $product->name       = $value['name'];
	      //                     $product->price      = $value['price']?$value['price']:0;
	      //                     if(isset($value['image'])){
	      //                         $file = $value['image'];
	      //                         $path = public_path() . '/upload/product/';
	      //                         $path_thumbnail = public_path() . '/upload/product_thumbnail/';
	      //                         if (!\File::exists($path)) {
	      //                             \File::makeDirectory($path, $mode = 0777, true, true);
	      //                         }
	      //                         if (!\File::exists($path_thumbnail)) {
	      //                             \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
	      //                         }

	      //                         $img_name = time() . '_product_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

	      //                         if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
	      //                             self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

	      //                         $image_product = '/upload/product/' . $img_name;
	      //                         $product->image      = $image_product;
	      //                     }
	      //                     $product->content_id = $id;
	      //                     $product->type_user  = 0;
	      //                     $product->updated_by = Auth::guard('web_client')->user()->id;
	      //                     $product->group_name = $group_name;
	      //                     $product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
	      //                     $product->currency   = $value['currency']?$value['currency']:'VND';
	      //                     $product->save();
	      //                 }
	      //             }
	      //         }
	      //     }
	      // }

	      DateOpen::where('id_content', '=', $id)->delete();
	      if ($request->date_open) {
	          foreach ($request->date_open as $value) {
	              if ($value['from_hour'] && $value['to_hour']) {
	                  DateOpen::create([
	                      'id_content' => $id,
	                      'date_from' => $value['from_date'],
	                      'date_to' => $value['to_date'],
	                      'open_from' => $value['from_hour'],
	                      'open_to' => $value['to_hour'],
	                      'angle_from' => $value['angle_from']?$value['angle_from']:0,
	                    	'angle_to' => $value['angle_to']?$value['angle_to']:0,
	                  ]);
	              }
	          }
	      }

	      if ($request->category_item) {
	        CategoryContent::where('id_content', '=', $id)->delete();
	        foreach ($request->category_item as $value) {
	          CategoryContent::create([
	            'id_content' => $id,
	            'id_category_item' => $value,
	          ]);
	        }
	      }

	      if ($request->service) {
	        ServiceContent::where('id_content', '=', $id)->delete();
	        foreach ($request->service as $value) {
	          ServiceContent::create([
	            'id_content' => $id,
	            'id_service_item' => $value,
	          ]);
	        }
	      }

	      $check_group_content = GroupContent::where('id_content', '=', $id)->first();
	      if ($request->group) {
	        if ($check_group_content) {
	          $check_group_content->id_group = $request->group;
	          $check_group_content->save();
	        } else {
	          GroupContent::create([
	            'id_content' => $id,
	            'id_group' => $request->group,
	          ]);
	        }

	      } else {
	        if ($check_group_content) {
	          $check_group_content->delete();
	        }
	      }


	      if ($request->image_space) {
	        $path = public_path() . '/upload/img_content/';
	        $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
	        $arr_des_space = $request->des_space;
	        $arr_title_space = $request->title_space;
	        foreach ($request->image_space as $key => $file) {

	          $img_name = (time() + $key) . '_space_' . $file->getClientOriginalName();

	          if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
	            self::waterMark($file, $img_name, $path, $path_thumbnail);

	          $image_space = '/upload/img_content/' . $img_name;

	          ImageSpace::create([
	            'id_content' => $id,
	            'name' => $image_space,
	            'title'=> $arr_title_space[$key],
	            'description'=> $arr_des_space[$key]
	          ]);
	        }
	      }

	      if ($request->image_menu) {
	        $path = public_path() . '/upload/img_content/';
	        $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
	        $arr_des_menu = $request->des_menu;
	        $arr_title_menu = $request->title_menu;
	        foreach ($request->image_menu as $key => $file) {

	          $img_name = time() . '_menu_' . $file->getClientOriginalName();

	          if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
	            self::waterMark($file, $img_name, $path, $path_thumbnail);

	          $image_menu = '/upload/img_content/' . $img_name;

	          ImageMenu::create([
	            'id_content' => $id,
	            'name' => $image_menu,
	            'title'=> $arr_title_menu[$key],
	            'description'=> $arr_des_menu[$key]
	          ]);
	        }
	      }

	      LinkContent::where('id_content', '=', $id)->delete();
	      if($request->link){
	        foreach ($request->link as $value)
	        {
	          if(isset($value))
	          {
	              $infoVideo = app('App\Http\Controllers\Admin\ContentController')->getInfoVideo($value);
	              if(count($infoVideo)>0){
	                  LinkContent::create([
	                      'id_content' => $id,
	                      'link' => $value,
	                      'type'=> $infoVideo['type'],
	                      'time'=>$infoVideo['time'],
	                      'title'=>$infoVideo['title'],
	                      'id_video'=>$infoVideo['id_video'],
	                      'thumbnail'=>$infoVideo['thumbnail']
	                  ]);
	              };
	          }
	        }
	      }
	    }

	    // if(!$request->description && $request->id_edit_content){
	    //   $description = '';
	    //   $content = Content::where('id','=',$request->id_edit_content)
	    //                  ->with('_category_type')
	    //                  ->with('_category_items')
	    //                  ->with('_country')
	    //                  ->with('_city')
	    //                  ->with('_district')
	    //                  ->with('_date_open_api')
	    //                  ->first();
	    //   $description .= $content->name.' ';
	    //   $description .= 'tại '.$content->address.' '.$content->_district->name.' '.$content->_city->name.' '.$content->_country->name.', ';
	    //   if($content->_category_items){
	    //       $description .= 'thuộc thể loại ';
	    //       foreach ($content->_category_items as $key_cat => $cat_item) {
	    //           if($key_cat==0){
	    //               $description .= mb_strtolower($cat_item->name);
	    //           }else{
	    //               $description .= ' - '.mb_strtolower($cat_item->name);
	    //           }

	    //       }
	    //   }else{
	    //       if($content->_category_type){
	    //           $description .= 'thuộc thể loại '.mb_strtolower($content->_category_type->name);
	    //       }
	    //   }

	    //   if($content->_date_open_api){
	    //       $description .= ', mở cửa '.mb_strtolower(create_open_time($content->_date_open_api, \App::getLocale())).', ';
	    //   }
	    //   if($content->price_from > 0 && $content->price_to > 0){
	    //       $description .= 'giá từ '.$content->price_from.$content->currency.' ';
	    //       $description .= 'đến '.$content->price_to.$content->currency;
	    //   }
	    //   $description .='.';
	    //   $content->description = $description;
	    //   $content->save();
	    // }

	    create_tag_search($id);

	    $id = $id_content;

	    $content = null;
			$data = [];
			$content = Content::select('*')
												->where([['id','=',$id]])
												->with('_country')
												->with('_city')
												->with('_district')
												->with('_comments')
												->with('_date_open')
												->with('_date_open_api');

			if($request->location){
				$currentLocation = explode(',', $request->location);
				if(count($currentLocation)==2){
					$lat = $currentLocation[0];
					$lng = $currentLocation[1];
					$content = $content->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													");
				}
			}
			$content = $content->first();
			if($content){
				$timezone = $this->getTimezoneGeo($content->lat,$content->lng);
				$current_time = $this->getTimeByTimeZone($timezone);
				$content->open = check_open_time($content->_date_open, $current_time);
				$content->open_time = create_open_time($content->_date_open, \App::getLocale());

				$data['image_space'] = $this->convert_image(ImageSpace::where('id_content', '=', $content->id)->pluck('name'));
				$data['image_menu'] = $this->convert_image(ImageMenu::where('id_content', '=', $content->id)->pluck('name'));
				$link_video = LinkContent::where('id_content', '=', $content->id)->pluck('link')->toArray();
				$data['link_video'] = [];
				foreach($link_video as $value){
					if (strpos($value,'facebook.com') == TRUE){
						// $data['link_video'][] = "https://www.facebook.com/plugins/video.php?href=$value";
						$data['link_video'][] = $value;
					}elseif(strpos($value,'vimeo.com') == TRUE){
						$data['link_video'][] = str_replace('vimeo.com','player.vimeo.com/video',$value);
					}elseif(strpos($value,'youtube.com') == TRUE || strpos($value,'youtu.be') == TRUE){
						$data['link_video'][] = str_replace('watch?v=','embed/',$value);
					}else{
						$data['link_video'][] = $value;
					}
				}

				$data['list_service'] =  CategoryService::select(
																									'id_service_item',
																									'service_items.name'
																								)
																								->where('id_category', '=', $content->id_category)
																								->leftJoin('service_items','id_service_item','=','service_items.id')
																								->get();
				$data['service_content'] = ServiceContent::where('id_content', '=', $content->id)->pluck('id_service_item')->toArray();
				$id_category_content = $content->id_category;
				$data['list_suggest'] =  Content::select(
																					'contents.id',
																					'contents.name',
																					'contents.tag',
																					'contents.address',
																					'contents.lat',
																					'contents.lat as latitude',
																					'contents.lng',
																					'contents.lng as longitude',
																					'contents.vote',
																					'contents.like',
																					'contents.alias',
																					'contents.avatar',
																					\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"),
																					'contents.country',
																					'contents.city',
																					'contents.district'
																				)
																				->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
																				->orderBy('line')
																				->distinct()
																				->where('contents.id_category', '=', $id_category_content)
																				->where('contents.moderation', '=', 'publish')
																				->where('contents.active', '=', 1)
																				->where('contents.id', '!=', $content->id)
																				->with('_country')
																				->with('_city')
																				->with('_district')
																				->limit(20)
																				->get();

				$id_group_content = GroupContent::where('id_content', '=', $content->id)->pluck('id_group')->first();
				$data['list_group'] = [];
				if ($id_group_content) {
					$data['list_group'] = Content::select('contents.*')
						->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
						->orderBy('line')
						->distinct()
						->with('_country')
						->with('_city')
						->with('_district')
						->join('group_content', 'contents.id', '=', 'group_content.id_content')
						->where('group_content.id_group', '=', $id_group_content)
						->where('contents.id', '!=', $content->id)
						->where('contents.moderation', '=', 'publish')
						->where('contents.active', '=', 1)
						// ->limit(8)
						->get();
				}
				$content->_date_open = $content->_date_open_api;
				$data['content'] = $content->toArray();
				$data['content']['has_vote'] = 0;
				$data['content']['has_like'] = 0;
				if(\Auth::guard('web_client')->user()){
					$has_vote = VoteContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					$has_like = LikeContent::where('id_content',$content->id)->where('id_user',\Auth::guard('web_client')->user()->id)->first();
					if($has_vote){
						$data['content']['has_vote'] = $has_vote->vote_point;
					}
					if($has_like){
						$data['content']['has_like'] = 1;
					}
				}
			}
			return $this->response($data,200);
    }else{
    	$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
			return $this->error($e);
    }
  }

  public function waterMarkAvatar($file, $img_name, $path, $path_thumbnail)
	{
		// if ($width > 770 || $height > 468) {
		$img = Image::make($file->getRealPath())->orientate()->fit(660,347, function ($constraint) {
			$constraint->upsize();
		});

        $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
        $img->insert($wt, 'center');
        $img->insert($wt, 'center');

		$img->save($path . $img_name);

		$img_thumbnail = Image::make($file->getRealPath())->orientate()->fit(270,202, function ($constraint) {
			$constraint->upsize();
		})->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');
		$img_thumbnail->save($path_thumbnail . $img_name);
	}

  

  public function waterMark($file, $img_name, $path, $path_thumbnail)
  {
    $img = Image::make($file->getRealPath())->orientate();
    $width = $img->getSize()->getWidth();
    $height = $img->getSize()->getHeight();

    $max_height = 720;
    $max_width = 1280;

    if($width>$max_width || $height>$max_height){
      $img = Image::make($file->getRealPath())->orientate()->resize(1280, 720, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });
    }

    $max = $width>$height?$width:$height;

    $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
    $img->insert($wt, 'center');
    $img->insert($wt, 'center');

    $img->save($path . $img_name);

    $img_thumbnail =
      Image::make($file->getRealPath())->orientate()->fit(270, 202, function ($constraint) {
        $constraint->upsize();
      })
      ->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center')
      ->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');

    $img_thumbnail->save($path_thumbnail . $img_name);
  }

  public function getPosition(Request $request){
		$arrReturn = [
			'country'=> 0,
			'city'=> 0,
			'district'=> 0,
		];
		if($request->location){
			$currentLocation = explode(',', $request->location);
			if(count($currentLocation)==2){
				$lat = $currentLocation[0];
				$lng = $currentLocation[1];
				$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&language=vi&key=AIzaSyCCCOoPlN2D-mfrYEMWkz-eN7MZnOsnZ44";
				$data = file_get_contents($url);
				$jsondata = json_decode($data,true);
				$location = array();
				if(isset($jsondata['results']['0'])){
					foreach($jsondata['results']['0']['address_components'] as $element){
						$location[ implode(' ',$element['types']) ] = $element['long_name'];
					}

					$country_str = isset($location['country political'])?$location['country political']:'';
					$city_str = isset($location['administrative_area_level_1 political'])?$location['administrative_area_level_1 political']:'';
					$district_str = isset($location['administrative_area_level_2 political'])?$location['administrative_area_level_2 political']:'';
					$country 	= null;
					$city    	= null;
					$district	= null;
					if($country_str != ''){
						$country = Country::select('countries.id')
														->selectRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) as math_score")
														->whereRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) >1")
														->orderBy('math_score', 'desc')
														->orwhere('name','like','%'.$country_str.'%')->first();
						if($country){
							$arrReturn['country'] = $country->id;
						}
						if($city_str != ''){
							$city = City::select('cities.id')
																->selectRaw("MATCH(`name`) AGAINST ('".$city_str." \"".$city_str."\"' in boolean mode) as math_score")
																->whereRaw("MATCH(`name`) AGAINST ('".$city_str." \"".$city_str."\"' in boolean mode) >1")
																->orderBy('math_score', 'desc')
																->orwhere('name','like','%'.$city_str.'%')->first();
							if($city){
								$arrReturn['city'] = $city->id;
							}
							if($district_str != ''){
								$district = District::select('districts.id')
																	->selectRaw("MATCH(`name`) AGAINST ('".$district_str." \"".$district_str."\"' in boolean mode) as math_score")
																	->whereRaw("MATCH(`name`) AGAINST ('".$district_str." \"".$district_str."\"' in boolean mode) >1")
																	->orderBy('math_score', 'desc')
																	->orwhere('name','like','%'.$district_str.'%')->first();
								if($district){
									$arrReturn['district'] = $district->id;
								}
							}
						}
					}
				}
			}
		}

		return $this->response([$arrReturn],200);
  }


  public function updateImage(Request $request, $type){
  	try{
  		$path = public_path() . '/upload/img_content/';
      $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
      if (!\File::exists($path)) {
        \File::makeDirectory($path, $mode = 0777, true, true);
      }
      if (!\File::exists($path_thumbnail)) {
        \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
      }

  		if($type == 'space'){
  			$id = $request->id?$request->id:0;
  			$image = ImageSpace::find($id);
  			if(!$image){
  				$e = new \Exception(trans('valid.not_found',['object'=>'Image space']),400);
					return $this->error($e);
  			}
  			if($request->title){
  				$image->title = $request->title;
  			}
  			if($request->description){
  				$image->description = $request->description;
  			}
  			if($request->image){
  				$file = $request->image;
  				$key = 1;
  				$img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

	        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
	          self::waterMark($file, $img_name, $path, $path_thumbnail);

	        $image_space = '/upload/img_content/' . $img_name;

	        $image->name = $image_space;
  			}

  			$image->save();
  			$data = $image;
  			return $this->response($data,200);
  		}

  		if($type == 'menu'){
  			$id = $request->id?$request->id:0;
  			$image = ImageMenu::find($id);
  			if(!$image){
  				$e = new \Exception(trans('valid.not_found',['object'=>'Image menu']),400);
					return $this->error($e);
  			}
  			if($request->title){
  				$image->title = $request->title;
  			}
  			if($request->description){
  				$image->description = $request->description;
  			}
  			if($request->image){
  				$file = $request->image;
  				$key = 1;
  				$img_name = (time() + $key) . '_menu_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

	        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
	          self::waterMark($file, $img_name, $path, $path_thumbnail);

	        $image_menu = '/upload/img_content/' . $img_name;

	        $image->name = $image_menu;
  			}

  			$image->save();
  			$data = $image;
  			return $this->response($data,200);
  		}

  		$e = new \Exception('Wrong type image',400);
			return $this->error($e);
		}catch(Exception $e){
			return $this->error($e);
		}
  }

  public function deleteImage(Request $request, $type,$id){
  	try{
  		if($type == 'space'){
  			$image = ImageSpace::find($id);
  			if(!$image){
  				$e = new \Exception(trans('valid.not_found',['object'=>'Image space']),400);
					return $this->error($e);
  			}
  			$image->delete();
  			return $this->response([],200);
  		}

  		if($type == 'menu'){
  			$image = ImageMenu::find($id);
  			if(!$image){
  				$e = new \Exception(trans('valid.not_found',['object'=>'Image menu']),400);
					return $this->error($e);
  			}
				$image->delete();
  			return $this->response([],200);
  		}

  		$e = new \Exception('Wrong type image',400);
			return $this->error($e);
		}catch(Exception $e){
			return $this->error($e);
		}
  }

  public function getImageContent(Request $request, $type, $content_id){
  	try{
  		if($type == 'space'){
  			$image = ImageSpace::where('id_content',$content_id);
  			$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;

				$image =  $image->limit($limit)
												->skip($skip)
												->get();

  			$data = [];
  			if($image){
  				$data = $this->convert_image($image);
  			}
				return $this->response($data,200);
  		}

  		if($type == 'menu'){
  			$image = ImageMenu::where('id_content',$content_id);
  			$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;

				$image =  $image->limit($limit)
												->skip($skip)
												->get();

  			$data = [];
  			if($image){
  				$data = $this->convert_image($image);
  			}
				return $this->response($data,200);
  		}

  		if($type == 'video'){
  			$image = LinkContent::where('id_content',$content_id);
  			$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;

				$image =  $image->limit($limit)
												->skip($skip)
												->get();

  			$data = [];
  			if($image){
  				$data = $image->toArray();
  			}
				return $this->response($data,200);
  		}

  		if($type == 'product'){
  			$image = Product::where('content_id',$content_id);
  			$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;

				$image =  $image->limit($limit)
												->skip($skip)
												->get();

  			$data = [];
  			if($image){
  				$data = $image->toArray();
  				foreach ($data as $key => $value) {
  					$data[$key]['image'] = url($data[$key]['image']);
  				}
  			}
				return $this->response($data,200);
  		}
		}catch(Exception $e){
			return $this->error($e);
		}
  }
}
