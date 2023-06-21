<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $appends=['total'];
    protected  $fillable=['user_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute(){
        $products = $this->products()->withPivot(columns:'quatity')->get();
        $sum=0;
        foreach($products as $product){
            $sum= $sum+($product->price * $product->pivot->quantity);
        }
    }
}
