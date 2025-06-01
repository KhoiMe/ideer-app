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
    <h2>Ideas</h2>
    @volt
    <div>
        <form wire:submit="submitIdea">
                <flux:input
                    wire:model="title"
                    placeholder="An inspiring title"
                    class="mt-2 mb-2"
                />
                <flux:text class="mt-2 mb-2" color="red">
                    @error('title') {{ $message }} @enderror
                </flux:text>

                <flux:textarea
                    wire:model="content"
                    placeholder="Make your explanation standout"
                    class="mt-2 mb-2"
                />
                <flux:text class="mt-2 mb-2" color="red">
                    @error('content') {{ $message }} @enderror
                </flux:text>

                <flux:button
                    type="submit"
                    variant="primary"
                    icon="plus"
                    class="cursor-pointer"
                >
                    Post to ideer
                </flux:button>
        </form>
    @endvolt
    </div>
    @foreach (Idea::getSortedIdeas() as $idea)
        <li>
            <livewire:ideas-card
                :idea="$idea"
                :vote-count="$idea->votes"
                :key="$idea->id"
            />
        </li>
    @endforeach
</x-layouts.app>
