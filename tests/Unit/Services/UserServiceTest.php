<?php

use App\Models\User;
use App\Services\UserService;
use Illuminate\Pagination\Paginator;

class UserServiceTest extends TestCase
{
    public function testTestedPaginate()
    {
        $this->mockDependence(User::class, function (User $user) {
            $user->shouldReceive('search')->with(null)->once()->andReturnSelf();
            $user->shouldReceive('simplePaginate')->with(15)->once()->andReturn(Mockery::mock(Paginator::class));

            return $user;
        });

        $this->app->make(UserService::class)->listUsersPaginate();
    }

    public function testUpdateUser()
    {
        $data = [];

        $user = Mockery::mock(User::class);

        $user->shouldReceive('fillAndSave')->with($data)->once();

        $this->app->make(UserService::class)->update($user, $data);
    }

    public function testShowSpecificUser()
    {
        $id = 'fooo-12345';

        $this->mockDependence(User::class, function (User $user) use ($id) {
            $user->shouldReceive('where')->with('id', $id)->once()->andReturnSelf()
                ->shouldReceive('firstOrFail')->once()->andReturn(new User());

            return $user;
        });

        $this->app->make(UserService::class)->show($id);
    }

    public function testDeleteSpecificUser()
    {
        $user = Mockery::mock(User::class);

        $user->shouldReceive('delete')->once()->andReturn(true);

        $this->assertEquals(true, $this->app->make(UserService::class)->delete($user));
    }
}
