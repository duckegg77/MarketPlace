<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CurrencyRate;
use App\Models\LedgerAccount;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@marketplace.local',
            'password' => Hash::make('password'),
            'pin_hash' => Hash::make('111111'),
            'public_pgp_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----\nADMIN-DEMO-KEY\n-----END PGP PUBLIC KEY BLOCK-----',
            'role' => 'admin',
        ]);

        $vendor = User::query()->create([
            'name' => 'Vendor',
            'username' => 'vendor',
            'email' => 'vendor@marketplace.local',
            'password' => Hash::make('password'),
            'pin_hash' => Hash::make('222222'),
            'public_pgp_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----\nVENDOR-DEMO-KEY\n-----END PGP PUBLIC KEY BLOCK-----',
            'role' => 'vendor',
            'bio' => 'Trusted vendor profile bio',
        ]);

        VendorProfile::query()->create([
            'user_id' => $vendor->id,
            'display_name' => 'Demo Vendor',
            'payout_details_encrypted' => ['account' => 'acct_12345'],
            'verification_status' => 'approved',
            'approved_at' => now(),
        ]);

        User::query()->create([
            'name' => 'Buyer',
            'username' => 'buyer',
            'email' => 'buyer@marketplace.local',
            'password' => Hash::make('password'),
            'pin_hash' => Hash::make('333333'),
            'public_pgp_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----\nBUYER-DEMO-KEY\n-----END PGP PUBLIC KEY BLOCK-----',
            'role' => 'buyer',
            'bio' => 'Regular buyer account',
        ]);

        $category = Category::query()->firstOrCreate(['slug' => 'general'], ['name' => 'General']);

        Product::query()->create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'name' => 'Demo Product',
            'slug' => 'demo-product',
            'description' => 'A seeded product.',
            'sku' => 'SKU-DEMO-1',
            'inventory' => 50,
            'price_cents' => 2500,
            'status' => 'published',
        ]);

        foreach ([
            ['name' => 'Standard', 'price_cents' => 500, 'estimated_days' => 5],
            ['name' => 'Express', 'price_cents' => 1200, 'estimated_days' => 2],
            ['name' => 'Priority', 'price_cents' => 2200, 'estimated_days' => 1],
        ] as $method) {
            ShippingMethod::query()->firstOrCreate(['vendor_id' => $vendor->id, 'name' => $method['name']], $method + ['is_active' => true]);
        }

        foreach ([
            ['base_currency' => 'USD', 'quote_currency' => 'USD', 'rate' => 1],
            ['base_currency' => 'USD', 'quote_currency' => 'GBP', 'rate' => 0.79],
            ['base_currency' => 'USD', 'quote_currency' => 'EUR', 'rate' => 0.92],
            ['base_currency' => 'USD', 'quote_currency' => 'XMR', 'rate' => 0.0062],
        ] as $rate) {
            CurrencyRate::query()->updateOrCreate([
                'base_currency' => $rate['base_currency'],
                'quote_currency' => $rate['quote_currency'],
            ], $rate + ['as_of' => now()]);
        }

        foreach ([
            ['code' => 'platform_escrow', 'name' => 'Platform Escrow', 'type' => 'liability'],
            ['code' => 'vendor_payable', 'name' => 'Vendor Payable', 'type' => 'liability'],
            ['code' => 'buyer_receivable', 'name' => 'Buyer Receivable', 'type' => 'asset'],
        ] as $account) {
            LedgerAccount::query()->firstOrCreate(['code' => $account['code']], $account);
        }
    }
}
