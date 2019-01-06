<?php
namespace App\Http\Controllers\Location;
use App\Models\Location\Comment;
use App\Models\Location\CommentImage;
use App\Models\Location\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Validator;
class CommentController extends BaseController
{
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
			// 'content_id' => 'required',
			'content' => 'required',
		];
		$messages = [
			// 'content_id.required' => 'Name là trường bắt buộc',
			'content.required' => trans('Location'.DS.'preview.content_empty'),
		];
		if(Auth::guard('web_client')->user()){
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$arrReturn['message'] = $validator->errors()->first();
			} else {
				$user_id = Auth::guard('web_client')->user()->id;
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
		}else{
			$arrReturn['message'] = trans('Location'.DS.'preview.not_login');
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
		if(Auth::guard('web_client')->user()){
			$user_id = Auth::guard('web_client')->user()->id;
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
				$comment->like_comment = $comment->like_comment-1;
				$comment->save();
			}else{
				$comment_like = new CommentLike();
				$comment_like->user_id = $user_id;
				$comment_like->comment_id = $comment_id;
				$comment_like->save();
				$comment->like_comment = $comment->like_comment+1;
				$comment->save();
			}
			$arrReturn['error'] = 0;
			$arrReturn['data'] = ['like'=>$comment->like_comment];
		}else{
			$arrReturn['message'] = trans('Location'.DS.'preview.not_login');
		}
		return response($arrReturn);
	}

	public function postloadComment(Request $request){
		$page       = $request->page;
		$take       = $request->take;
		$comment_id = $request->comment_id;
		$content_id = $request->content_id;
		$arrReturn = [
			'nextPage'=>$page+1,
			'html'=>'',
			'message'=>''
		];
		if($comment_id==0){
			$comments = Comment::where('comment.model_id','=',$content_id)
													->where('comment.model_type','=','content')
													->where('comment.parent_id','=',$comment_id)
													->where('comment.active','=',1)
													->where('comment.approved','=',1)
													->with('_replies')
													->with('_images')
													->with('_comment_by')
													->with('_has_liked')
													->orderBy('created_at','DESC')
													->skip(5*($page-1))
													->take($take)
													->get();
		}else{
			$comments = Comment::where('comment.model_id','=',$content_id)
													->where('comment.model_type','=','content')
													->where('comment.parent_id','=',$comment_id)
													->where('comment.active','=',1)
													->where('comment.approved','=',1)
													->with('_replies')
													->with('_images')
													->with('_comment_by')
													->with('_has_liked')
													// ->orderBy('created_at','DESC')
													->skip(5*($page-1))
													->take($take)
													->get();
		}
		// pr($comments->toArray());die;
		$html = '';
		if($comments){
			if($comment_id==0){
				foreach ($comments as $comment) {
					if($comment->_comment_by)
					$html .= view('Location.content.comment_item_render',['comment'=>$comment])->render();
				}
			}else{
				foreach ($comments as $reply) {
					if($reply->_comment_by)
					$html .= view('Location.content.comment_item_sub_render',['reply'=>$reply])->render();
				}
			}
		}
		$arrReturn['html'] = $html;
		return response($arrReturn);
	}
}