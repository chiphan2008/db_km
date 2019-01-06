<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Comment extends Base
{
		//
	protected $table = 'comment';

	public function _replies()
	{
			return $this->hasMany('App\Models\Location\Comment','parent_id')
									->where('comment.active','=',1)
									->where('comment.approved','=',1)
									->orderBy('created_at')
									->with('_images')
									->with('_comment_by')
									->with('_has_liked')
									->limit(5);
	}

	public function _all_replies(){
			return $this->hasMany('App\Models\Location\Comment','parent_id')
										->where('comment.active','=',1)
										->where('comment.approved','=',1)
										->orderBy('created_at');
	}

	public function _images()
	{
			return $this->hasMany('App\Models\Location\CommentImage', 'comment_id', 'id');
	}

	public function _comment_by()
	{
			return $this->belongsTo('App\Models\Location\Client', 'created_by', 'id');
	}

	public function _content()
	{
			return $this->belongsTo('App\Models\Location\Content', 'model_id', 'id');
	}

	public function _has_liked()
	{
		if(Auth::guard('web_client')->user())
			return $this->hasMany('App\Models\Location\CommentLike', 'comment_id', 'id')
									->where('user_id','=',Auth::guard('web_client')->user()->id);
		else
			return $this->hasMany('App\Models\Location\CommentLike', 'comment_id', 'id')
									->where('user_id','=',0);
	}

}
