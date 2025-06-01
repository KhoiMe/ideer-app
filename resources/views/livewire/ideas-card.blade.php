<?php
use Livewire\Volt\Component;
use App\Models\Idea;

new class extends Component  {
    public Idea $idea;
    public int $voteCount;

    public function upvote(Idea $idea) {
        DB::table('idea_votes')->updateOrInsert([
            'idea_id' => $idea->id,
            'user_id' => Auth::user()->id,
        ], [
            'count' => 1
        ]);


        $this->voteCount = $idea->voteCount();
    }

    public function downvote(Idea $idea) {
        DB::table('idea_votes')->updateOrInsert([
            'idea_id' => $idea->id,
            'user_id' => Auth::user()->id,
        ], [
            'count' => -1
        ]);
        $this->voteCount = $idea->voteCount();
    }

    public function deleteIdea(Idea $idea)
    {
         $idea = DB::table('ideas')->where('id', $idea->id)->first();

        if (!$idea) return;

        if ($idea->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::table('ideas')->where('id', $idea->id)->delete();

        $this->redirect('ideas');
    }
}

?>
<div class="ideas-card">
    <div class="p-3 sm:p-4 rounded-lg">
    <flux:heading> {{ $idea->idea_title }} </flux:heading>
    <flux:text variant="ghost">{{ $idea->created_at }}</flux:text>
            <div class="flex flex-col gap-0.5 sm:gap-2 sm:flex-row sm:items-center">
                <div class="flex flex-row sm:items-center gap-2">
                <flux:text variant="strong"> {{ $idea->user->name }}</flux:text>
                </div>
            </div>
    </div>
    <div class="pl-8">
        <flux:text variant="ghost"> {{ $idea->idea_explanation }}</flux:text>

        <div class="flex items-center gap-0">
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 tabular-nums">{{ $voteCount }}</flux:text>
            <flux:button wire:click="upvote({{ $idea->id }})" variant="ghost" size="sm">
                <flux:icon.hand-thumb-up name="hand-thumb-up" variant="outline" class="size-4 text-zinc-400"></flux:icon>
            </flux:button>
            <flux:button wire:click="downvote({{ $idea->id }})" variant="ghost" size="sm">
                <flux:icon.hand-thumb-down name="hand-thumb-down" variant="outline" class="size-4 text-zinc-400"></flux:icon>
            </flux:button>
            @if (Auth::user()->id === $idea->user->id)
            <flux:button wire:click="deleteIdea({{ $idea->id }})" variant="ghost" size="sm">
                <flux:icon.trash name="trash" variant="outline" class="size-4 text-zinc-400"></flux:icon>
            </flux:button>
            @endif
        </div>
    </div>
</div>
