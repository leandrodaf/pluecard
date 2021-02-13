<?php

use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Pagination\Paginator;

class ItemServiceTest extends TestCase
{
    public function testCreaeNewItem()
    {
        $data = [
            'title' => 'foo-title',
            'description' => 'bar-description',
            'picture_url' => 'http://url.com',
            'category_id' => 1,
            'unit_price' => 10,
        ];

        $this->mockDependence(Item::class, function (Item $item) use ($data) {
            $item->shouldReceive('create')->with($data)->once()->andReturn(new Item($data));

            return $item;
        });

        $this->app->make(ItemService::class)->create($data);
    }

    public function testListallItemsWithPaginate()
    {
        $this->mockDependence(Item::class, function (Item $item) {
            $item->shouldReceive('simplePaginate')->with(15)->once()->andReturn(new Paginator([], 10));

            return $item;
        });

        $this->app->make(ItemService::class)->listItemsPaginate();
    }

    public function testUpdateItem()
    {
        $update = ['title' => 'tested-123'];

        $fakeItem = Mockery::mock(Item::factory()->make());

        $fakeItem->shouldReceive('fillAndSave')->with($update)->once()->andReturn(true);

        $this->app->make(ItemService::class)->update($fakeItem, $update);
    }

    public function testShowSingleItem()
    {
        $fakeItem = new Item();

        $this->mockDependence(Item::class, function (Item $item) use ($fakeItem) {
            $item->shouldReceive('where')->with('id', 1)->once()->andReturnSelf()
                ->shouldReceive('firstOrFail')->once()->andReturn($fakeItem);

            return $item;
        });

        $result = $this->app->make(ItemService::class)->show(1);

        $this->assertEquals($fakeItem, $result);
    }

    public function testDeleteItem()
    {
        $fakeItem = Mockery::mock(Item::class);

        $fakeItem->shouldReceive('delete')->once();

        $this->app->make(ItemService::class)->destroy($fakeItem);
    }
}
