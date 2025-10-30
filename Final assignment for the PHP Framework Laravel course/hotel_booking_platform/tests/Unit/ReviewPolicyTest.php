<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Review;
use App\Policies\ReviewPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected ReviewPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ReviewPolicy();
    }

    public function test_anyone_can_view_any_reviews()
    {
        $this->assertTrue($this->policy->viewAny(null));

        $user = User::factory()->create();
        $this->assertTrue($this->policy->viewAny($user));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($this->policy->viewAny($admin));
    }

    public function test_anyone_can_view_approved_reviews()
    {
        $approvedReview = Review::factory()->approved()->create();

        $this->assertTrue($this->policy->view(null, $approvedReview));

        $user = User::factory()->create();
        $this->assertTrue($this->policy->view($user, $approvedReview));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($this->policy->view($admin, $approvedReview));
    }

    public function test_nobody_can_view_pending_reviews()
    {
        $pendingReview = Review::factory()->pending()->create();

        $this->assertFalse($this->policy->view(null, $pendingReview));

        $user = User::factory()->create();
        $this->assertFalse($this->policy->view($user, $pendingReview));

        $author = $pendingReview->user;
        $this->assertFalse($this->policy->view($author, $pendingReview));
    }

    public function test_only_authenticated_users_can_create_reviews()
    {
        $this->assertFalse($this->policy->create(null));

        $user = User::factory()->create();
        $this->assertTrue($this->policy->create($user));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($this->policy->create($admin));

        $manager = User::factory()->create(['role' => 'manager']);
        $this->assertTrue($this->policy->create($manager));
    }

    public function test_only_admin_can_update_reviews()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($this->policy->update($user, $review));

        $manager = User::factory()->create(['role' => 'manager']);
        $this->assertFalse($this->policy->update($manager, $review));

        $author = $review->user;
        $author->role = 'user';
        $author->save();
        $this->assertFalse($this->policy->update($author, $review));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($this->policy->update($admin, $review));
    }

    public function test_admin_and_author_can_delete_reviews()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($this->policy->delete($user, $review));

        $manager = User::factory()->create(['role' => 'manager']);
        $this->assertFalse($this->policy->delete($manager, $review));

        $author = $review->user;
        $author->role = 'user';
        $author->save();
        $this->assertTrue($this->policy->delete($author, $review));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($this->policy->delete($admin, $review));
    }

    public function test_admin_can_restore_reviews()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($this->policy->restore($user, $review));

        $manager = User::factory()->create(['role' => 'manager']);
        $this->assertFalse($this->policy->restore($manager, $review));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($this->policy->restore($admin, $review));
    }

    public function test_admin_can_force_delete_reviews()
    {
        $review = Review::factory()->create();

        $user = User::factory()->create(['role' => 'user']);
        $this->assertFalse($this->policy->forceDelete($user, $review));

        $manager = User::factory()->create(['role' => 'manager']);
        $this->assertFalse($this->policy->forceDelete($manager, $review));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertTrue($this->policy->forceDelete($admin, $review));
    }

    public function test_policy_handles_null_user_gracefully()
    {
        $review = Review::factory()->approved()->create();

        $this->assertTrue($this->policy->viewAny(null));
        $this->assertTrue($this->policy->view(null, $review));
        $this->assertFalse($this->policy->create(null));
        $this->assertFalse($this->policy->update(null, $review));
        $this->assertFalse($this->policy->delete(null, $review));
        $this->assertFalse($this->policy->restore(null, $review));
        $this->assertFalse($this->policy->forceDelete(null, $review));
    }

    public function test_policy_with_different_review_states()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $approvedReview = Review::factory()->approved()->create();
        $this->assertTrue($this->policy->view($user, $approvedReview));
        $this->assertTrue($this->policy->view(null, $approvedReview));

        $pendingReview = Review::factory()->pending()->create();
        $this->assertFalse($this->policy->view($user, $pendingReview));
        $this->assertFalse($this->policy->view(null, $pendingReview));

        $this->assertTrue($this->policy->update($admin, $approvedReview));
        $this->assertTrue($this->policy->update($admin, $pendingReview));
    }
}
