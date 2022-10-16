<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    public function menus()
    {
        return $this->hasMany('App\MenuItem', 'parent');
    }
}
