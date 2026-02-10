<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRatingRequest;
use App\Http\Requests\Product\ToggleFavoriteRequest;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use App\Services\Payments\CurrencyDisplayService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, CurrencyDisplayService $currencyDisplay)
    {
        $query = Product::query()->published();

        if ($term = $request->string('q')->toString()) {
            $query->whereFullText(['name', 'description'], $term);
        }

        $products = $query->paginate(20);
        $products->getCollection()->transform(function (Product $product) use ($currencyDisplay) {
            $product->display_prices = $currencyDisplay->convertFromUsdCents((int) $product->price_cents);
            $product->avg_stars = round((float) $product->ratings()->avg('stars'), 2);

            return $product;
        });

        return response()->json($products);
    }

    public function rateProduct(StoreRatingRequest $request, Product $product)
    {
        $rating = Rating::query()->create([
            'rater_id' => $request->user()->id,
            'product_id' => $product->id,
            'sub_order_id' => $request->validated('sub_order_id'),
            'stars' => $request->validated('stars'),
            'comment' => $request->validated('comment'),
        ]);

        return response()->json($rating, 201);
    }

    public function rateVendor(StoreRatingRequest $request, User $vendor)
    {
        $rating = Rating::query()->create([
            'rater_id' => $request->user()->id,
            'rateable_user_id' => $vendor->id,
            'sub_order_id' => $request->validated('sub_order_id'),
            'stars' => $request->validated('stars'),
            'comment' => $request->validated('comment'),
        ]);

        return response()->json($rating, 201);
    }

    public function toggleFavorite(ToggleFavoriteRequest $request)
    {
        $typeMap = ['product' => Product::class, 'vendor' => User::class];
        $type = $typeMap[$request->validated('favoritable_type')];

        $favorite = Favorite::query()->firstOrNew([
            'user_id' => $request->user()->id,
            'favoritable_type' => $type,
            'favoritable_id' => $request->validated('favoritable_id'),
        ]);

        if ($favorite->exists) {
            $favorite->delete();
            return response()->json(['favorited' => false]);
        }

        $favorite->save();
        return response()->json(['favorited' => true], 201);
    }
}
