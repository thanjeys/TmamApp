<?php

namespace Tests\Feature;

use App\Models\ChartOfAccount;
use App\Models\Organization;
use App\Models\User;
use App\Services\ZohoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class ChartOfAccountTest extends TestCase
{
    use RefreshDatabase;

    protected $mockApiResponse;

    protected $mockApiResChartOfAccount;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();

        $this->mockApiResponse = json_decode(file_get_contents(base_path('tests/data/chart_of_accounts_mock.json')), true);

        $this->mockApiResChartOfAccount = $this->mockApiResponse['chartofaccounts'];
    }

    public function test_public_user_redirect_login_page()
    {
        $response = $this->get(route('chart.accounts'));

        $response->assertRedirect('login');
    }

    public function test_can_view_chart_of_accounts_page()
    {
        // ChartOfAccount::factory(3)->create();

        $response = $this->actingAs($this->user)->get(route('chart.accounts'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn ($page) => $page
                ->component('ChartOfAccount')
                ->has('accounts')
                ->has('formatted_last_synced_time')
        );
    }

    public function test_sync_chart_of_accounts_success()
    {
        try {
            // Arrange - Mock Zoho Service
            $zohoService = Mockery::mock(ZohoService::class);
            $zohoService->shouldReceive('fetchChartOfAccounts')
                ->once()
                ->andReturn($this->mockApiResChartOfAccount);

            $zohoService->shouldReceive('getOrganizationIdForUser')
                ->once()
                ->andReturn(123456);

            // Organization::factory()->create([
            // 	'organization_id' => 123456,
            // ]);

            // $this->assertDatabaseHas('organizations', [
            // 	'organization_id' => 123456,
            // ]);

            $this->app->instance(ZohoService::class, $zohoService);

            // Action
            $response = $this->actingAs($this->user)->post(route('sync.chart.accounts'));

            // Assert
            $response->assertRedirect();
            $response->assertSessionHas('message', __('CHARTOFACCOUNT_SYNC_SUCCESS'));

            $this->assertDatabaseHas('chart_of_accounts', [
                'account_id' => $this->mockApiResChartOfAccount[0]['account_id'],
                'account_name' => $this->mockApiResChartOfAccount[0]['account_name'],
                'account_type' => $this->mockApiResChartOfAccount[0]['account_type'],
            ]);

            $this->assertDatabaseCount('chart_of_accounts', count($this->mockApiResChartOfAccount));
        } catch (\Exception $e) {
            Log::debug('Failed Test chart_of_accounts_success'.$e->getMessage());
            $this->fail();
        }
    }

    public function test_sync_chart_of_accounts_exception_fetch_chart_of_accounts()
    {
        // Arrange - Mock Zoho Service
        $zohoService = Mockery::mock(ZohoService::class);
        $zohoService->shouldReceive('fetchChartOfAccounts')
            ->once()
            ->andThrow(new \Exception('API error: Invalid response'));

        $this->app->instance(ZohoService::class, $zohoService);

        // Action
        $response = $this->actingAs($this->user)->post(route('sync.chart.accounts'));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('error', __('CHARTOFACCOUNT_SYNC_ERROR'));

        // Ensure no new accounts are added to the database
        $this->assertDatabaseCount('chart_of_accounts', 0);
    }

    private function createUser()
    {
        return User::factory()->create();
    }
}
