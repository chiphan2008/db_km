<?php

namespace App\Http\Controllers\Location;
use App\Models\Location\Collection;
use App\Models\Location\CollectionContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class CollectionController extends BaseController
{
	public function postCreateCollection(Request $request){
		$arrReturn = [
			'error'=>1,
			'message'=>'',
			'collections'=>null,
		];
		if($request->name){
			if(Auth::guard('web_client')->check() == true){
				$collection = new Collection();
				$collection->name = $request->name;
				$collection->created_by = Auth::guard('web_client')->user()->id;
				$collection->updated_by = Auth::guard('web_client')->user()->id;
				$collection->created_at = Carbon::now();
				$collection->updated_at = Carbon::now();
				if($collection->save()){
					$arrReturn['error'] = 0;
					$arrReturn['message'] = trans('Location'.DS.'preview.create_collection_success');
					$arrReturn['collections'] = Collection::where('created_by','=',Auth::guard('web_client')->user()->id)
																							 	->with('_contents')
                                          			->get();
		      foreach ($arrReturn['collections'] as $key => $collection) {
		        $arrReturn['collections'][$key]->check = false;
		        foreach ($collection->_contents as $key2 => $cont) {
		          if($cont->id == $request->content_id){
		            $arrReturn['collections'][$key]->check = true;
		            break;
		          }
		        }
		      }
				}
			}else{
				$arrReturn['message'] = trans('Location'.DS.'preview.must_be_login');
			}
		}else{
			$arrReturn['message'] = trans('Location'.DS.'preview.name_collection_empty');
		}

		return response($arrReturn);
	}


	public function postAddCollection(Request $request){
		$arrReturn = [
			'error'=>1,
			'message'=>trans('Location'.DS.'preview.add_collection_error'),
			'collections'=>null,
		];
		$collection_id = $request->collection_id?$request->collection_id:0;
		$content_id = $request->content_id?$request->content_id:0;
		if($collection_id && $content_id){
			$collectioncontent = CollectionContent::where('content_id','=',$content_id)
																					->where('collection_id','=',$collection_id)
																					->first();
			if(!$collectioncontent){
				$collectioncontent = new CollectionContent();
				$collectioncontent->content_id = $content_id;
				$collectioncontent->collection_id = $collection_id;
				$collectioncontent->save();
			}
			$arrReturn = [
				'error'=>0,
				'message'=>trans('Location'.DS.'preview.add_collection_success'),
				'collections'=>null,
			];
			$arrReturn['collections'] = Collection::where('created_by','=',Auth::guard('web_client')->user()->id)
																							 	->with('_contents')
                                          			->get();
      foreach ($arrReturn['collections'] as $key => $collection) {
        $arrReturn['collections'][$key]->check = false;
        foreach ($collection->_contents as $key2 => $cont) {
          if($cont->id == $request->content_id){
            $arrReturn['collections'][$key]->check = true;
            break;
          }
        }
      }
		}
		return response($arrReturn);
	}

	public function postRemoveCollection(Request $request){
		$arrReturn = [
			'error'=>1,
			'message'=>trans('Location'.DS.'preview.remove_collection_error'),
			'collections'=>null,
		];
		$collection_id = $request->collection_id?$request->collection_id:0;
		$content_id = $request->content_id?$request->content_id:0;
		if($collection_id && $content_id){
			$collectioncontent = CollectionContent::where('content_id','=',$content_id)
																					->where('collection_id','=',$collection_id)
																					->delete();
			// if($collectioncontent){
			// 	$collectioncontent->delete();
			// }
			$arrReturn = [
				'error'=>0,
				'message'=>trans('Location'.DS.'preview.remove_collection_success'),
				'collections'=>null,
			];
			$arrReturn['collections'] = Collection::where('created_by','=',Auth::guard('web_client')->user()->id)
																							 	->with('_contents')
                                          			->get();
      foreach ($arrReturn['collections'] as $key => $collection) {
        $arrReturn['collections'][$key]->check = false;
        foreach ($collection->_contents as $key2 => $cont) {
          if($cont->id == $request->content_id){
            $arrReturn['collections'][$key]->check = true;
            break;
          }
        }
      }
		}
		return response($arrReturn);
	}

	public function postDeleteCollection(Request $request){
		$arrReturn = [
			'error'=>1,
			'message'=>trans('Location'.DS.'preview.delete_collection_error'),
			'collections'=>null,
		];
		$collection_id = $request->collection_id?$request->collection_id:0;
		$content_id = $request->content_id?$request->content_id:0;
		if($collection_id){
			CollectionContent::where('collection_id','=',$collection_id)
											 ->delete();
			Collection::where('id','=',$collection_id)
								->delete();
			// if($collectioncontent){
			// 	$collectioncontent->delete();
			// }
			$arrReturn = [
				'error'=>0,
				'message'=>trans('Location'.DS.'preview.delete_collection_success'),
				'collections'=>null,
			];
			$arrReturn['collections'] = Collection::where('created_by','=',Auth::guard('web_client')->user()->id)
																							 	->with('_contents')
                                          			->get();
      foreach ($arrReturn['collections'] as $key => $collection) {
        $arrReturn['collections'][$key]->check = false;
        foreach ($collection->_contents as $key2 => $cont) {
          if($cont->id == $request->content_id){
            $arrReturn['collections'][$key]->check = true;
            break;
          }
        }
      }
		}
		return response($arrReturn);
	}

	public function postUpdateCollection(Request $request){
		$arrReturn = [
			'error'=>1,
			'message'=>trans('Location'.DS.'preview.update_collection_error'),
			'collections'=>null,
		];
		$collection_id = $request->collection_id?$request->collection_id:0;
		$content_id = $request->content_id?$request->content_id:0;
		if($collection_id){
			$collection = Collection::find($collection_id);
			if($request->name)
				$collection->name = $request->name;
			$collection->save();
			$arrReturn = [
				'error'=>0,
				'message'=>trans('Location'.DS.'preview.update_collection_success'),
				'collections'=>null,
			];
			$arrReturn['collections'] = Collection::where('created_by','=',Auth::guard('web_client')->user()->id)
																							 	->with('_contents')
                                          			->get();
      foreach ($arrReturn['collections'] as $key => $collection) {
        $arrReturn['collections'][$key]->check = false;
        foreach ($collection->_contents as $key2 => $cont) {
          if($cont->id == $request->content_id){
            $arrReturn['collections'][$key]->check = true;
            break;
          }
        }
      }
		}
		return response($arrReturn);
	}
}
