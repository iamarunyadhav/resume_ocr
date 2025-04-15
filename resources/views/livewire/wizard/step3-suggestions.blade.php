<div class="space-y-6">
    <h2 class="text-xl font-bold">Optional AI Suggestions</h2>

    <div class="flex flex-wrap gap-3 mb-6">
        @foreach($suggestions as $item)
            <button wire:click="applySuggestion('{{ $item }}')"
                    class="bg-gray-200 hover:bg-blue-500 hover:text-white text-sm font-semibold px-3 py-1 rounded shadow">
                + {{ ucfirst($item) }}
            </button>
        @endforeach
    </div>

    <h3 class="text-lg font-semibold">Updated Extracted Fields</h3>

    <form wire:submit.prevent="save" class="space-y-4">
        @foreach($fields as $key => $value)
            <div>
                <label class="block font-semibold capitalize">{{ str_replace('_', ' ', $key) }}</label>
                @if(is_array($value))
                    <textarea wire:model.defer="fields.{{ $key }}"
                              class="w-full p-2 border rounded" rows="3">{{ json_encode($value) }}</textarea>
                @else
                    <input type="text" wire:model.defer="fields.{{ $key }}"
                           class="w-full p-2 border rounded" />
                @endif
            </div>
        @endforeach

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow">
            Continue to Preview →
        </button>
    </form>
</div>
