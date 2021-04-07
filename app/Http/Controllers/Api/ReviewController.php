<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\ReviewReplay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ReviewController extends Controller
{
    public function getReview($id)
    {
        $reviews = Review::where('product_id', $id)->with('user', 'images', 'reviewReplays', 'reviewReplays.user')->latest()->paginate(20);
        return response()->json(compact('reviews'));
    }

    public function postReview(Request $request)
    {
        $this->validate($request, [
            'rating' => 'required|integer',
            'message' => 'required|max:1500',
            'product_id' => 'required|integer',
        ]);

        $exist = Review::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();
        if (!isset($exist)) {
            $product = Product::with('reviews')->find($request->product_id);
            $orders = Order::where('user_id', Auth::id())->with('products')->get();
            $purchaseStatus = false;
            foreach ($orders as $order) {
                foreach ($order->products as $data) {
                    if ($data->product_id === $product->id) {
                        $purchaseStatus = true;
                        break;
                    };
                }
            }

            if ($purchaseStatus === true) {
                $review = new Review();
                $review->user_id = Auth::id();
                $review->product_id = $request->product_id;
                $review->rating = $request->rating;
                $review->message = $request->message;
                $review->save();

                $totalRating = 0;
                $ratingCount = 0;
                foreach ($product['reviews'] as $rating) {
                    $totalRating += $rating->rating;
                    $ratingCount += 1;
                }
                $product->rating = $ratingCount === 0 ? $request->rating:($totalRating+$request->rating) / ($ratingCount+1);
                $product->save();

                foreach ($request->images as $image){
                    $path = 'images/product/review/';
                    $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                    $name = $path . Str::random(2) .time() . '.' . $extension;
                    if (!File::exists($path)) {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }
                    Image::make($image)->encode($extension, 75)->save($name);

                    $newImage = new ReviewImage();
                    $newImage->user_id = Auth::id();
                    $newImage->review_id = $review->id;
                    $newImage->image = $name;
                    $newImage->save();
                }
            } else {
                return response()->json(['error'=>'You Need To Purchase This Item For Review'],422);
            }
        } else {
            return response()->json(['error'=>'You Already Review This Product'], 422);
        }
    }

    public function postReviewReplay(Request $request)
    {
        $this->validate($request, [
            'message' => 'required|max:1500',
            'review_id' => 'required|integer',
        ]);

        $reviewReplay = new ReviewReplay();
        $reviewReplay->user_id = Auth::id();
        $reviewReplay->review_id = $request->review_id;
        $reviewReplay->message = $request->message;
        $reviewReplay->save();
    }

    public function deleteReview($id)
    {
        $this->authorize('authCheck');
        $review = Review::with('images')->find($id);
        $product = Product::where('id', $review->product_id)->with('reviews')->first();
        $totalRating = 0;
        $ratingCount = 0;
        foreach ($product['reviews'] as $rating) {
            $totalRating += $rating->rating;
            $ratingCount += 1;
        }
        $product->rating = $ratingCount <= 1 ? 0:($totalRating - $review->rating) / ($ratingCount -1);
        $product->save();

        foreach ($review->images as $image){
            if (File::exists($image->image)) {
                unlink($image->image);
            }
            $image->delete();
        }

        $review->delete();

    }

    public function deleteReviewReplay($id)
    {
        $this->authorize('authCheck');
        ReviewReplay::where('id', $id)->first()->delete();
    }
}
