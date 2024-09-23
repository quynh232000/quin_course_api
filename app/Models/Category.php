<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";

    protected $fillable = [
        "icon_url",
        "slug",
        "name",
        "parent_id"
    ];
    protected $hidden = [
        "created_at",
        'updated_at'
    ];
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }
    public function countProducts()  {
        $allCateIds = $this->allChildren()->pluck('id');
        $allCateIds[] =$this->id;
        $count = Course::whereIn('category_id', $allCateIds)->count();
        return $count ??0;
     
        
    } 
    public function hasChild()
    {
        return Category::where('parent_id', $this->id)->exists() ?? false;
    }
    public function childCategories($id =null)
    {
        $id = $id == null? $this->id : $id;
        $all =  Category::where('parent_id', $id)->get() ?? [];
        return $all;
    }
    public function sameparent(){
        return Category::where('parent_id', $this->parent_id)->get() ?? [];
    }

    public function parent($id = null)
    {
        $id = $id == null ? $this->parent_id : $id;
        $parent = Category::where('id', $id)->first();

        return $parent;
    }
    public function getAllParents($id = null)
    {
        $parents = collect();
        $parent = $this->parent($id);
        while ($parent) {
            $parents->prepend($parent);
            $parent = $parent->parent();
        }

        return $parents ?? [];
    }
}
