<?php

namespace Tests\Feature\Sale;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use LaravelIdea\Helper\App\Models\_IH_User_C;
use Tests\TestCase;

class TotalProfitTest extends TestCase
{
    /**
     * @var User|User[]|Collection|Model|_IH_User_C|mixed
     */
    private mixed $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->user()->create();
    }

    public function test_should_return_total_profit(): void
    {
        $this->actingAs($this->user);

        $user = User::factory()->createQuietly();

        Product::factory() // @phpstan-ignore-line
            ->hasStock(1, ['quantity' => 5])
            ->hasSales(2, ['total_price' => 10, 'purchaser_id' => $user->getAttribute('id')])
            ->createQuietly(['price' => 10]);

        Product::factory() // @phpstan-ignore-line
            ->hasStock(1, ['quantity' => 6])
            ->hasSales(2, ['total_price' => 20, 'purchaser_id' => $user->getAttribute('id')])
            ->createQuietly(['price' => 20]);

        $this
            ->getJson(route('total-profit'))
            ->assertOk()
            ->assertJson([
                'status' => 'success',
                'totalProfit' => 60,
            ]);
    }
}
