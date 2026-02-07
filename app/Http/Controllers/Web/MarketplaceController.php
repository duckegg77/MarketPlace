<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->published()->with('category');
        $term = trim((string) $request->query('q', ''));
        $categoryId = $request->query('category_id');

        if ($term !== '') {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $sort = $request->query('sort', 'newest');
        $query->when($sort === 'price_low', fn ($q) => $q->orderBy('price_cents'))
            ->when($sort === 'price_high', fn ($q) => $q->orderByDesc('price_cents'))
            ->when($sort === 'name', fn ($q) => $q->orderBy('name'))
            ->when($sort === 'newest', fn ($q) => $q->latest());

        return view('marketplace.index', [
            'products' => $query->paginate(16)->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
            'filters' => [
                'q' => $term ?? '',
                'category_id' => $categoryId,
                'sort' => $sort,
            ],
        ]);
    }
}
