<?php
use App\Models\Idea;
use function Livewire\Volt\{state};

$ideas = Idea::with('user')->get();
?>
<x-layouts.app>
    <h2>Ideas</h2>
    @foreach (Idea::getSortedIdeas() as $idea)
        <li>
            <livewire:ideas-card :idea="$idea" :vote-count="$idea->votes" :key="$idea->id"/>
        </li>
    @endforeach
</x-layouts.app>
