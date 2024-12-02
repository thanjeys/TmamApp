<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use App\Services\ZohoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    protected $mockApiResponse;

    protected $mockApiResOrganization;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();

        $this->mockApiResponse = json_decode(file_get_contents(base_path('tests/data/organizations_mock.json')), true);

        $this->mockApiResOrganization = $this->mockApiResponse['organizations'];
    }

    public function test_public_user_redirect_login_page()
    {
        $response = $this->get(route('organizations'));

        $response->assertRedirect('login');
    }

    public function test_can_view_organizations_page()
    {
        $user = User::factory()->create();

        Organization::factory(3)->create();

        $response = $this->actingAs($user)->get(route('organizations'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn ($page) => $page
                ->component('Organization')
                ->has('organizations')
                ->has('formatted_last_synced_time')
        );
    }

    public function test_sync_organization_success()
    {
        // Arrange - MockZoho Service
        $zohoService = Mockery::mock(ZohoService::class);
        $zohoService->shouldReceive('fetchOrganizations')
            ->once()
            ->andReturn($this->mockApiResOrganization);

        $this->app->instance(ZohoService::class, $zohoService);

        // Action
        $response = $this->actingAs($this->user)->post(route('sync.orgs'));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('message', __('ORG_SYNC_SUCCESS'));

        $this->assertDatabaseHas('organizations', [
            'organization_id' => $this->mockApiResOrganization[0]['organization_id'],
            'name' => $this->mockApiResOrganization[0]['name'],
            'contact_name' => $this->mockApiResOrganization[0]['contact_name'],
        ]);

        $this->assertDatabaseCount('organizations', count($this->mockApiResOrganization));
    }

    public function test_sync_organization_exception_fetch_organization()
    {
        // Arrange - MockZoho Service
        $zohoService = Mockery::mock(ZohoService::class);
        $zohoService->shouldReceive('fetchOrganizations')
            ->once()
            ->andThrow(new \Exception('API error: Invalid response'));

        $this->app->instance(ZohoService::class, $zohoService);

        // Action
        $response = $this->actingAs($this->user)->post(route('sync.orgs'));

        $this->assertDatabaseCount('organizations', 0);
    }

    private function createUser()
    {
        return User::factory()->create();
    }
}
