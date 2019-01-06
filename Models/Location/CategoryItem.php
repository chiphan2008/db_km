<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Base {

    protected $table = 'category_items';
    protected $visible = ['id', 'name', 'alias','image','weight'];

    public function _created_by()
    {
        return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
    }

     public function _updated_by()
    {
        return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
    }

    public function _category()
    {
        return $this->belongsTo('App\Models\Location\Category', 'category_id', 'id');
    }

    public function _category_content()
    {
        return $this->hasMany('App\Models\Location\CategoryContent', 'id_category_item', 'id');
    }
}
