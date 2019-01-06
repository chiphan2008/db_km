<?php
namespace App\Http\Controllers\API;

use App\Models\Location\Raovat;
use App\Models\Location\RaovatType;
use App\Models\Location\RaovatImage;
use App\Models\Location\RaovatSubType;
use App\Models\Location\SubtypeRaovat;

use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;
use Validator;
use Carbon;

class RaovatController extends BaseController {
	public function getRaovat($id){
		try{
			$data=[];
			if($id){
				$data = Raovat::select('raovat.*')
									  ->with('_created_by')
//										->where('date_from','<=',date("Y-m-d H:i:s"))
//                    ->where('date_to','>=',date("Y-m-d H:i:s"))
										->where('id','=',$id)
										->with('_subtypes')
										->with('_type')
										->first();
				if($data) {
                    $data['_images'] = $this->convert_image(RaovatImage::where('raovat_id', $id)->pluck('link'));
                }
			}
			return $this->response([$data],200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getListRaovat(Request $request){
		try{
			$data=[];
			$data = Raovat::select('raovat.*')
									->with('_images')
									->with('_created_by')
//									->where('date_from','<=',date("Y-m-d H:i:s"))
//                  ->where('date_to','>=',date("Y-m-d H:i:s"))
									->with('_subtypes')
									->with('_type')
									->where('active','=',1)
									->orderBy('created_at','DESC');

			$skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;

			if($request->kind){
				$data = $data->where('kind',$request->kind);
			}

			if($request->raovat_type){
				$data = $data->where('raovat_type',$request->raovat_type);
			}

			if($request->country){
				$data = $data->where('country',$request->country);
			}

			if($request->city){
				$data = $data->where('city',$request->city);
			}

			if($request->district){
				$data = $data->where('district',$request->district);
			}

			if($request->subtype){
				$data = $data->leftJoin('raovat_raovat_subtype','raovat.id','=','raovat_raovat_subtype.raovat_id')
										 ->where('raovat_subtype_id',$request->subtype);
			}

			if($request->id_user){
				$data = $data->where('created_by',$request->id_user);
			}

			$data = $data->limit($limit)
									 ->skip($skip);

			$data = $data->get()->toArray();

			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postCreateRaovat(Request $request){
		try{
			$rules = [
				'name' => 'required',
				'raovat_type' => 'required',
				'kind' => 'required',
				'content' => 'required',
				'user_id' => 'required'
			];
			$messages = [
				'name.required'       => trans('valid.raovat_name_required'),
				'raovat_type.required'=> trans('valid.raovat_type_required'),
				'kind.required'       => trans('valid.raovat_kind_required'),
				'content.required'    => trans('valid.raovat_content_required'),
				'user_id.required' 		=> trans('Location'.DS.'preview.must_be_login')


			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{

				$raovat = new Raovat();
				$raovat->name        = $request->name;
				$raovat->content     = $request->content;
				$raovat->raovat_type = $request->raovat_type;
				$raovat->kind        = $request->kind;
				$raovat->price = $request->price?$request->price:0;

				$raovat->country        = $request->country?$request->country:0;
				$raovat->city           = $request->city?$request->city:0;
				$raovat->district       = $request->district?$request->district:0;

				$raovat->quantity        = $request->quantity?$request->quantity:0;
				$raovat->size           = $request->size?$request->size:'';
				$raovat->material       = $request->material?$request->material:'';


				$raovat->premium = $request->premium?$request->premium:0;
				if($request->date_from && $request->date_to){
					$raovat->date_from   = new Carbon($request->date_from);
					$raovat->date_to     = new Carbon($request->date_to);
				}else{
					$raovat->date_from   = Carbon::now();
					$raovat->date_to     = Carbon::now()->addDays(7);
				}

				if($request->position){
					$raovat->position    = $request->position;
				}

				if($request->show){
					$raovat->show        = $request->show;
				}

				$raovat->active =  isset($request->active);

				$raovat->created_by = $request->user_id;
				$raovat->updated_by = $request->user_id;
				$raovat->created_at = Carbon::now();
				$raovat->updated_at = Carbon::now();
				if($raovat->save()){
					if ($request->image) {
			      $path = public_path() . '/upload/raovat/';
			      $path_thumbnail = public_path() . '/upload/raovat_thumbnail/';
			      if (!\File::exists($path)) {
			        \File::makeDirectory($path, $mode = 0777, true, true);
			      }
			      if (!\File::exists($path_thumbnail)) {
			        \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
			      }
			      foreach ($request->image as $file) {

			        $img_name = time() . '_raovat_' . vn_string($file->getClientOriginalName());

			        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
								self::waterMark($file, $img_name, $path, $path_thumbnail);

			        $image = '/upload/raovat/' . $img_name;

			        RaovatImage::create([
			          'raovat_id' => $raovat->id,
			          'link' => $image,
			        ]);
			      }
			    }
			    if ($request->subtype) {
			      foreach ($request->subtype as $value) {
			        SubtypeRaovat::create([
			          'raovat_id' => $raovat->id,
			          'raovat_subtype_id' => $value,
			        ]);
			      }
			    }
					$data = Raovat::where('id',$raovat->id)
												->with('_images')
												->with('_subtypes')
												->with('_type')
												->first();
					return $this->response([$data],200);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postEditRaovat(Request $request){
		try{
			$rules = [
				'name' => 'required',
				'raovat_type' => 'required',
				'kind' => 'required',
				'content' => 'required',
				'user_id' => 'required',
				'id' => 'required'
			];
			$messages = [
				'name.required'       => trans('valid.raovat_name_required'),
				'raovat_type.required'=> trans('valid.raovat_type_required'),
				'kind.required'       => trans('valid.raovat_kind_required'),
				'content.required'    => trans('valid.raovat_content_required'),
				'user_id.required' 		=> trans('Location'.DS.'preview.must_be_login'),
				'id.required' 					=> trans('valid.raovat_id_required')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{

				$raovat = Raovat::where('id',$request->id)
												->where('created_by',$request->user_id)
												->first();
				if(!$raovat){
					$e = new \Exception(trans('valid.raovat_not_found'),400);
					return $this->error($e);
				}

				$raovat->name        = $request->name;
				$raovat->content     = $request->content;
				$raovat->raovat_type = $request->raovat_type;
				$raovat->kind        = $request->kind;
				$raovat->price = $request->price?$request->price:0;
				
				$raovat->country        = $request->country?$request->country:0;
				$raovat->city           = $request->city?$request->city:0;
				$raovat->district       = $request->district?$request->district:0;

				$raovat->quantity        = $request->quantity?$request->quantity:0;
				$raovat->size           = $request->size?$request->size:'';
				$raovat->material       = $request->material?$request->material:'';


				$raovat->premium = $request->premium?$request->premium:0;
				if($request->date_from && $request->date_to){
					$raovat->date_from   = new Carbon($request->date_from);
					$raovat->date_to     = new Carbon($request->date_to);
				}
				if($request->position){
					$raovat->position    = $request->position;
				}

				if($request->show){
					$raovat->show        = $request->show;
				}

				$raovat->active =  isset($request->active);

				$raovat->updated_by = $request->user_id;
				$raovat->updated_at = Carbon::now();
				if($raovat->save()){
					if ($request->image) {
			      $path = public_path() . '/upload/raovat/';
			      $path_thumbnail = public_path() . '/upload/raovat_thumbnail/';
			      if (!\File::exists($path)) {
			        \File::makeDirectory($path, $mode = 0777, true, true);
			      }
			      if (!\File::exists($path_thumbnail)) {
			        \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
			      }
			      foreach ($request->image as $file) {

			        $img_name = time() . '_raovat_' . vn_string($file->getClientOriginalName());

			        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
								self::waterMark($file, $img_name, $path, $path_thumbnail);

			        $image = '/upload/raovat/' . $img_name;

			        RaovatImage::create([
			          'raovat_id' => $raovat->id,
			          'link' => $image,
			        ]);
			      }
			    }
			    if ($request->subtype) {
			    	SubtypeRaovat::where('raovat_id',$raovat->id)->delete();
			      foreach ($request->subtype as $value) {
			        SubtypeRaovat::create([
			          'raovat_id' => $raovat->id,
			          'raovat_subtype_id' => $value,
			        ]);
			      }
			    }
					$data = Raovat::where('id',$raovat->id)
												->with('_images')
												->with('_subtypes')
												->with('_type')
												->first();
					return $this->response([$data],200);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postDeleteRaovat(Request $request){
		try{
			$rules = [
				'user_id' => 'required',
				'id' => 'required'
			];
			$messages = [
				'user_id.required' 		=> trans('Location'.DS.'preview.must_be_login'),
				'id.required' 					=> trans('valid.raovat_id_required')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{

				$raovat = Raovat::where('id',$request->id)
												->where('created_by',$request->user_id)
												->first();
				if(!$raovat){
					$e = new \Exception(trans('valid.raovat_not_found'),400);
					return $this->error($e);
				}

				$raovat->delete();
				RaovatImage::where('raovat_id',$raovat->id)->delete();
				SubtypeRaovat::where('raovat_id',$raovat->id)->delete();
				$data = [];
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postDeleteImageRaovat(Request $request){
		try{
			RaovatImage::where('id',$request->id)->delete();
			$data = [];
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}


	public function getListRaovatByType(Request $request,$raovat_type){
		try{
			$data=[];

			$data = Raovat::select('raovat.*')
									->with('_images')
									->with('_subtypes')
									->with('_type')
									// ->where('date_from','<=',date("Y-m-d H:i:s"))
         					// ->where('date_to','>=',date("Y-m-d H:i:s"))
									// ->where('active','=',1)
									->where('raovat_type',$raovat_type);

			$skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;
			$data = $data->limit($limit)
									 ->skip($skip);

			$data = $data->get();

			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}
	public function getListRaovatByKind(Request $request,$raovat_kind){
		try{
			$data=[];

			$data = Raovat::select('raovat.*')
									->with('_images')
									->with('_subtypes')
									->with('_type')
									// ->where('date_from','<=',date("Y-m-d H:i:s"))
         //          ->where('date_to','>=',date("Y-m-d H:i:s"))
									->where('active','=',1)
									->where('kind',$raovat_kind);

			$skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;
			$data = $data->limit($limit)
									 ->skip($skip);

			$data = $data->get();

			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getListRaovatType(Request $request){
		try{
			$data = RaovatType::select('raovat_type.*')
												->with('_subtypes')
												->where('active','=',1);
			$skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;
			$data = $data->limit($limit)
									 ->skip($skip);
			$data = $data->orderBy('weight','asc');
			$data = $data->get();
			if($request->language){
				\App::setLocale($request->language);
			}
			if($data){
				//$data->toArray();
				foreach ($data as $key => $value) {
					$count_raovat_array = [
						'mua' => 0,
						'ban' => 0,
						'thue' => 0,
						'cho_thue' => 0
					];
					$count_raovat = Raovat::where('raovat_type',$value->id)
																 ->where('active',1)
																 ->selectRaw("id,kind, count('id') as total")
																 ->groupBy('kind')
																 ->pluck('total','kind')
																 ->toArray();

					$data[$key]->count_raovat = array_merge($count_raovat_array,$count_raovat);

					$data[$key]->name = app('translator')->getFromJson($value->name);
					if($data[$key]->_subtypes){
						foreach ($data[$key]->_subtypes as $key2 => $value2) {
							$count_raovat_sub_array = [
								'mua' => 0,
								'ban' => 0,
								'thue' => 0,
								'cho_thue' => 0
							];
							$count_raovat_sub = Raovat::leftJoin('raovat_raovat_subtype','raovat.id','=','raovat_raovat_subtype.raovat_id')
	 																		  ->where('raovat_subtype_id',$value2->id)
																			  ->where('active',1)
																			  ->selectRaw("raovat.id,kind, count('id') as total")
																			  ->groupBy('kind')
																			  ->pluck('total','kind')
																			  ->toArray();
							$data[$key]->_subtypes[$key2]->count_raovat = array_merge($count_raovat_sub_array,$count_raovat_sub);
						}
					}
				}
                $data= $data->toArray();
			}else{
				$data=[];
			}

			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}

	}
	public function getRaovatType($id){
		try{
			if($id){
				$data = RaovatType::select('raovat_type.*')
													->with('_subtypes')
													->where('id','=',$id)
													->where('active','=',1);
				$data = $data->first();
			}else{
				$data = null;
			}

			if($data){
				$count_raovat_array = [
					'mua' => 0,
					'ban' => 0,
					'thue' => 0,
					'cho_thue' => 0
				];
				$count_raovat = Raovat::where('raovat_type',$data->id)
															 ->where('active',1)
															 ->selectRaw("id,kind, count('id') as total")
															 ->groupBy('kind')
															 ->pluck('total','kind')
															 ->toArray();

				$data->count_raovat = array_merge($count_raovat_array,$count_raovat);
		
				$data->name = app('translator')->getFromJson($data->name);
				if($data->_subtypes){
					foreach ($data->_subtypes as $key => $value) {
						$count_raovat_sub_array = [
							'mua' => 0,
							'ban' => 0,
							'thue' => 0,
							'cho_thue' => 0
						];
						$count_raovat_sub = Raovat::leftJoin('raovat_raovat_subtype','raovat.id','=','raovat_raovat_subtype.raovat_id')
 																		  ->where('raovat_subtype_id',$value->id)
																		  ->where('active',1)
																		  ->selectRaw("raovat.id,kind, count('id') as total")
																		  ->groupBy('kind')
																		  ->pluck('total','kind')
																		  ->toArray();
						$data->_subtypes[$key]->count_raovat = array_merge($count_raovat_sub_array,$count_raovat_sub);
					}
				}
			}else{
				$data=[];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
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
}
