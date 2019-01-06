<?php

namespace App\Http\Controllers\Admin;
use App\Models\Location\Comment;
use App\Models\Location\CommentImage;
use App\Models\Location\CommentLike;
use App\Models\Location\Content;
use App\Models\Location\Notifi;
use App\Models\Location\Client;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class CommentController extends BaseController
{
	public function anyIndex(Request $request){
		$all_comments = Comment::select('comment.*')
													 ->with('_content')
													 ->with('_comment_by')
													 ->with('_images')
													 ->where('approved','=',0)
													 ->where('declined','=',0)
													 ->orderBy('model_type','ASC')
													 ->orderBy('created_at','DESC');
		$keyword = '';
		$created_by = 0;
		if($request->keyword){
			$keyword = $request->keyword;
			$all_comments = $all_comments->leftJoin('contents',function ($query){
				$query->on('contents.id', '=', 'comment.model_id');
			})->where(function($query) use($keyword){
				$query->where('contents.name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('contents.alias', 'LIKE', '%' . $keyword . '%');
			});
		}
		if($request->created_by){
			$created_by = $request->created_by;
			$all_comments = $all_comments->where('comment.created_by','=',$created_by);
		}
		$clients = Client::get();
		$list_comments = $all_comments->paginate(15);
		return view('Admin.comment.list', ['list_comments' => $list_comments,'created_by' => $created_by,'keyword' => $keyword,'clients'=>$clients]);
	}

	public function anyList(Request $request){
		$all_comments = Comment::select('comment.*')
													 ->with('_content')
													 ->with('_comment_by')
													 ->with('_images')
													 ->orderBy('model_type','ASC')
													 ->orderBy('created_at','DESC');
		$keyword = '';
		$created_by = 0;
		if($request->keyword){
			$keyword = $request->keyword;
			$all_comments = $all_comments->leftJoin('contents',function ($query){
				$query->on('contents.id', '=', 'comment.model_id');
			})->where(function($query) use($keyword){
				$query->where('contents.name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('contents.alias', 'LIKE', '%' . $keyword . '%');
			});
		}
		if($request->created_by){
			$created_by = $request->created_by;
			$all_comments = $all_comments->where('comment.created_by','=',$created_by);
		}
		$clients = Client::get();
		$list_comments = $all_comments->paginate(15);
		return view('Admin.comment.list_all', ['list_comments' => $list_comments,'created_by' => $created_by,'keyword' => $keyword,'clients'=>$clients]);
	}

	public function anyApprove($comment_id){
		$comment=Comment::find($comment_id);
		$comment->approved = 1;
		$comment->declined = 0;
		$comment->active = 1;
		$comment->save();

		$content = Content::find($comment->model_id);
		$notifi = new Notifi();
		$notifi->createNotifiUserByTemplate('Admin'.DS.'comment.approved_comment_notifi',$content->created_by,['content'=>$content->name]);
		return redirect()->back()->with(['success'=>trans('Admin'.DS.'comment.approved_comment')]);
	}

	public function anyDecline($comment_id){
		$comment=Comment::find($comment_id);
		$comment->approved = 0;
		$comment->declined = 1;
		$comment->save();

		$content = Content::find($comment->model_id);
		$notifi = new Notifi();
		$notifi->createNotifiUserByTemplate('Admin'.DS.'comment.declined_comment_notifi',$content->created_by,['content'=>$content->name]);
		return redirect()->back()->with(['error' => trans('Admin'.DS.'comment.declined_comment')]);
	}

	public function anyDelete($comment_id){
		$comment=Comment::find($comment_id);
		$comment->delete();
		CommentImage::where('comment_id','=',$comment_id)->delete();
		CommentLike::where('comment_id','=',$comment_id)->delete();
		return redirect()->back()->with(['error' => trans('Admin'.DS.'comment.deleted_comment')]);
	}
}
