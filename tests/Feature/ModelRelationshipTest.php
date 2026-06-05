<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Opinion;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_roles(): void
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => 'admin']);

        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($role->users->contains($user));
    }

    public function test_project_connects_category_opinions_and_polymorphic_comments(): void
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'category_id' => $category->id,
            'created_by' => $user->id,
        ]);
        $opinion = Opinion::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_type' => Project::class,
            'commentable_id' => $project->id,
        ]);

        $this->assertTrue($project->category->is($category));
        $this->assertTrue($project->creator->is($user));
        $this->assertTrue($project->opinions->contains($opinion));
        $this->assertTrue($project->comments->contains($comment));
        $this->assertTrue($comment->commentable->is($project));
    }
}
