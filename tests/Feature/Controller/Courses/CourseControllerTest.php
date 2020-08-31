<?php

namespace Tests\Feature\Controller\Courses;

use App\Models\Courses\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    protected $baseRouteName = 'course';
    protected $baseViewPath = 'course';
    protected $className = Course::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'index' => [],
            'create' => [],
            'store' => [],
            'show' => ['course' => $id],
            'edit' => ['course' => $id],
            'update' => ['course' => $id],
            'destroy' => ['course' => $id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = factory($this->className)->create();

        $this->a_user_can_not_see_models_from_a_different_user(['course' => $modelOfADifferentUser->id]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->getIndexViewResponse();
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_models()
    {
        $models = factory($this->className, 3)->create([
            'user_id' => $this->user->id,
        ]);

        $this->getPaginatedCollection();
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $data = [
            'name' => 'New Model',
        ];

        $this->post(route($this->baseRouteName . '.store'), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), [
            'user_id' => $this->user->id,
        ] + $data);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->getShowViewResponse(['course' => $model->id]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createModel();

        $this->getEditViewResponse(['course' => $model->id]);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->signIn($this->user);

        $data = [

        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['course' => $model->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        Arr::forget($data, [

        ]);

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
        ] + $data);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_model()
    {
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->deleteModel($model, ['course' => $model->id])
            ->assertRedirect();
    }
}
