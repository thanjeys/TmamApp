<?php

namespace Tests\Feature;

use App\Jobs\SyncContactJob;
use App\Models\SyncLog;
use App\Models\User;
use App\Services\SyncLogService;
use App\Services\TokenService;
use App\Services\ZohoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ContactTest extends TestCase
{
	use RefreshDatabase;
	
	protected $user;

	protected function setUp(): void
	{
		parent::setUp();

		$this->user = $this->createUser();
	}
	
	public function test_public_user_redirect_login_page()
	{
		$response = $this->get(route('contacts'));

		$response->assertRedirect('login');
	}
	


	private function createUser()
	{
		return User::factory(1)->create();
	}
}
