<?php
use App\Models\Idea;
use Livewire\Volt\Component;
use function Livewire\Volt\{state};

$ideas = Idea::with('user')->get();

new class extends Component {
    public $title = '';
    public $content = '';

    public function submitIdea()
    {
        $this->validate([
            'title' => 'required|min:10|max:420',
            'content' => 'required|min:10|max:420',
        ]);

        $user = auth()->user()->ideas()->create([
            'idea_title' => $this->title,
            'idea_explanation' => $this->content,
        ]);

        // let the frontEnd Update
        $this->title = '';
        $this->content = '';
        $this->redirect('ideas');

    }
}
?>
<x-layouts.app>
<div>
<flux:heading size="xl">Ideas</flux:heading2>
<livewire:ideas-form />
</div>

    <div class ="mt-6 grid grid-cols-2 gap-2">
    <div>
        <flux:heading size="xl">Hot Ideas</flux:heading2>
        <flux:separator />
        @foreach (Idea::getSortedIdeas() as $idea)
            <li>
                <livewire:ideas-card
                    :idea="$idea"
                    :vote-count="$idea->votes"
                    :key="$idea->id"
                />
            </li>
        @endforeach
    </div>

    <div>
        <flux:heading size="xl">Latest Ideas</flux:heading>
        <flux:separator />
        @foreach (Idea::getLatestIdeas() as $idea)
            <li>
                <livewire:ideas-card
                    :idea="$idea"
                    :vote-count="$idea->votes"
                    :key="$idea->id"
                />
            </li>
        @endforeach
    </div>
    </div>
</x-layouts.app>
