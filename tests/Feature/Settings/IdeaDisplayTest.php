<?php

use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use Livewire\Volt\Volt;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('ideas page loads successfully', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/ideas')
        ->assertOk();
});

test('ideas are displayed on the page', function () {
    $user = User::factory()->create();
    $idea1 = Idea::factory()->create([
        'idea_title' => 'First Test idea',
        'idea_explanation' => 'First test explanation',
        'user_id' => $user->id
    ]);
    $idea2 = Idea::factory()->create([
        'idea_title' => 'Second Test idea',
        'idea_explanation' => 'Second test explanation',
        'user_id' => $user->id
    ]);

    $this->actingAs($user)
        ->get('/ideas')
        ->assertOk()
        ->assertSee('First Test idea')
        ->assertSee('Second Test idea')
        ->assertSee('First test explanation')
        ->assertSee('Second test explanation');
});

test('Idea is being created through Livewire', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Volt::test('ideas-form')
        // properties from the submitIdea method
        ->set('title', 'new test Idea')
        ->set('content', 'test Explanation')
        ->call('submitIdea')
        ->assertHasNoErrors();


    $this->assertDatabaseHas('ideas', [
        'idea_title' => 'new test Idea',
        'idea_explanation' => 'test Explanation',
        'user_id' => $user->id
    ]);
});

test('idea can be deleted', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    Volt::test('ideas-card', ['idea' => $idea])
        ->call('deleteIdea', $idea->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('ideas', [
        'id' => $idea->id
    ]);
});

test('only authenticated users can access ideas page', function () {
    $this->get('/ideas')
        ->assertRedirect('/login');
});
