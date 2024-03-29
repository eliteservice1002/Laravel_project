<div>
    <input type="text" wire:model="name" />
    <input type="checkbox" wire:model="loud" />
    <select wire:model="greeting" multiple>
        <option>Hello</option>
        <option>Goodbye</option>
    </select>

    {{ implode(', ', $greeting) }} {{ $name }}
    @if ($loud) ! @endif

    <form action="#" wire:submit.prevent="resetName('Bingo')">
        <button>Reset name</button>
    </form>
</div>
