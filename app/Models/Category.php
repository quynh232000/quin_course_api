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
    // public function children()
    // {
    //     return $this->hasMany(Category::class, 'parent_id');
    // }

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
