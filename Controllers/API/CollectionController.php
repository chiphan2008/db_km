<?php
namespace App\Http\Controllers\API;

use App\Models\Location\Collection;
use App\Models\Location\CollectionContent;

use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
class CollectionController extends BaseController {
	public function postCreateCollection(Request $request){
		try{
			$rules = [
				'name' => 'required',
				'user_id' => 'required'
			];
			$messages = [
				'name.required' => trans('Location'.DS.'preview.name_collection_empty'),
				'user_id.required' => trans('Location'.DS.'preview.must_be_login')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$name = $request->name;
				$user_id = $request->user_id;
				$collection = new Collection();
				$collection->name = $name;
				$collection->created_by = $user_id;
				$collection->updated_by = $user_id;
				$collection->created_at = Carbon::now();
				$collection->updated_at = Carbon::now();
				if($collection->save()){
					$data = $collection->toArray();
					return $this->response($data,200);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postEditCollection(Request $request){
		try{
			$rules = [
				'name' => 'required',
				'collection_id' => 'required',
				'user_id' => 'required'
			];
			$messages = [
				'name.required' => trans('Location'.DS.'preview.name_collection_empty'),
				'collection_id.required' => trans('Location'.DS.'preview.collection_id_require'),
				'user_id.required' => trans('Location'.DS.'preview.must_be_login')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$name = $request->name;
				$collection_id = $request->collection_id;
				$user_id = $request->user_id;
				$collection = Collection::find($collection_id);
				$collection->name = $name;
				$collection->updated_by = $user_id;
				$collection->updated_at = Carbon::now();
				if($collection->save()){
					$data = [];
					$collection = Collection::where('id',$collection_id)->with('_contents')->first();
					if($collection){
						$data = $collection->toArray();
					}
					return $this->response($data,200);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getCollection($id){
		try{
			$data = [];
			$collection = Collection::where('id',$id)->with('_contents')->first();
			if($collection){
				$data = $collection->toArray();
			}
			return $this->response([$data],200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getCollectionByUser(Request $request,$user_id){
		try{
			$data = [];
      $skip = $request->skip?$request->skip:0;
      $limit = $request->limit?$request->limit:20;
			$collections = Collection::where('created_by',$user_id)->with('_contents');
      $collections = $collections->limit($limit)
          ->skip($skip);
      $collections = $collections->get();

      if($collections){
				foreach ($collections as $key => $value) {
					$tmp = $value->toArray();
					$tmp['arr_content_id'] = [];
					foreach ($tmp['_contents'] as $key2 => $content) {
						$tmp['arr_content_id'][] = $content['id'];
					}
					$data[] = $tmp;
				}
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postAddCollection(Request $request){
		try{
			$rules = [
				'collection_id' => 'required',
				'content_id' => 'required'
			];
			$messages = [
				'collection_id.required' => trans('Location'.DS.'preview.collection_id_require'),
				'content_id.required' => trans('Location'.DS.'preview.content_id_require')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$collection_id = $request->collection_id;
				$content_id = $request->content_id;
				$collectioncontent = CollectionContent::where('content_id','=',$content_id)
																					->where('collection_id','=',$collection_id)
																					->first();
				if(!$collectioncontent){
					$collectioncontent = new CollectionContent();
					$collectioncontent->content_id = $content_id;
					$collectioncontent->collection_id = $collection_id;
					$collectioncontent->save();
				}

				$data = [];
				$collection = Collection::where('id',$collection_id)->with('_contents')->first();
				if($collection){
					$data = $collection->toArray();
				}
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}


	public function postDeleteCollection(Request $request){
		// pr($request->all());
		try{
			$rules = [
				'collection_id' => 'required',
				'user_id' => 'required'
			];
			$messages = [
				'collection_id.required' => trans('Location'.DS.'preview.collection_id_require'),
				'user_id.required' => trans('Location'.DS.'preview.must_be_login')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$collection_id = $request->collection_id;
				$user_id = $request->user_id;

				$collection = Collection::where('id',$collection_id)->where('created_by',$user_id)->first();
				if($collection){
					CollectionContent::where('collection_id','=',$collection_id)
											 ->delete();
					Collection::where('id','=',$collection_id)
										->delete();
				}
				$data = [];
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postRemoveCollection(Request $request){
		try{
			$rules = [
				'collection_id' => 'required',
				'content_id' => 'required'
			];
			$messages = [
				'collection_id.required' => trans('Location'.DS.'preview.collection_id_require'),
				'content_id.required' => trans('Location'.DS.'preview.content_id_require')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$collection_id = $request->collection_id;
				$content_id = $request->content_id;
				$collectioncontent = CollectionContent::where('content_id','=',$content_id)
																					->where('collection_id','=',$collection_id)
																					->delete();
				$data = [];
				$collection = Collection::where('id',$collection_id)->with('_contents')->first();
				if($collection){
					$data = $collection->toArray();
				}
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

}