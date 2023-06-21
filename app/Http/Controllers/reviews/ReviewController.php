<?php

namespace App\Http\Controllers\reviews;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $msg = 'All Reviews are Right Here';
            $data = Review::with(['product', 'user' => function($query) {
                $query->select('id', 'name');
            }])->get();
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'comment'=>'regex:/[a-zA-Z\s]+/',
            'stars' => 'required|integer|between:1,5',
                'price'=>'required|numeric'
            ]
        );
                if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }
        try {
            $product = Product::firstOrCreate([
                'product_name' => $request->product_name
            ]);
            $review = Review::create($request->all());
            $userId = Auth::user()->id;
            $review->product()->associate($product)->save();
            $review->user()->associate($userId)->save();
           $data=$review;
           $msg='review is created successfully';
            return $this->successResponse($data,$msg,201);
        }
        catch (\Exception $ex)
        {
            return $this->errorResponse($ex->getMessage(),500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        try {
            $review->delete();
            $msg = 'The Review is deleted successfully';
            return $this->successResponse($review, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function  filterReviewsByUser($userId)
    {
        try {
            $data = Review::where('user_id', $userId)->with('user')->get();
            $msg='Got data Successfully';
            return $this->successResponse($data,$msg);
        }
    catch (\Exception $ex)
    { return $this->errorResponse($ex->getMessage(),500); }
    }

    public function usersReviewsFrequency()
{
    try {
        $userComments = Review::select('users.name', DB::raw('count(*) as comment_count'))
                        ->join('users', 'reviews.user_id', '=', 'users.id')
                        ->groupBy('users.id', 'users.name')
                        ->orderByDesc('comment_count')
                        ->get();

        $msg = 'Got data successfully';
        return $this->successResponse($userComments, $msg);
    } catch (\Exception $ex) {
        return $this->errorResponse($ex->getMessage(), 500);
    }
}
}
