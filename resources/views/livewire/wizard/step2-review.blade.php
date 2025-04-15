<div class="space-y-6">
    <h2 class="text-xl font-bold">Review Extracted Fields</h2>

    <div wire:loading wire:target="runExtraction, mount" class="text-center my-4">
        <div class="flex items-center justify-center space-x-2">
            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span class="text-sm text-gray-600 font-medium">Extracting fields using AI...</span>
        </div>
    </div>

    @if (session()->has('message'))
    <div class="text-green-600 font-semibold bg-green-100 p-2 rounded shadow">
        {{ session('message') }}
    </div>
@endif


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
            Continue to Suggestions →
        </button>
    </form>
</div>
