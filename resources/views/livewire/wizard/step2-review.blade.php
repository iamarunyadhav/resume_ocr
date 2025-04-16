<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6">
            <h2 class="text-2xl font-bold text-white">Review Extracted Fields</h2>
            <p class="text-blue-100 mt-1">Step 2: Verify and edit extracted information</p>
        </div>

        <!-- Card Body -->
        <div class="p-6 space-y-6">
            <div wire:loading wire:target="runExtraction, mount" class="text-center my-4">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span class="text-sm text-gray-600 font-medium">Extracting fields using AI...</span>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="text-green-600 font-semibold bg-green-100 p-3 rounded-lg border border-green-200">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save" class="space-y-6">
                @foreach($fields as $key => $value)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 capitalize mb-2">
                            {{ str_replace('_', ' ', $key) }}
                        </label>
                        @if(is_array($value))
                            <textarea wire:model.defer="fields.{{ $key }}"
                                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    rows="4">{{ json_encode($value, JSON_PRETTY_PRINT) }}</textarea>
                        @else
                            <input type="text"
                                   wire:model.defer="fields.{{ $key }}"
                                   class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
                        @endif
                    </div>
                @endforeach

                <div class="pt-4 flex justify-between items-center border-t border-gray-200 mt-6">
                    <a href="{{ route('wizard.step1') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow font-medium transition-colors flex items-center">
                        Continue to Suggestions
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
