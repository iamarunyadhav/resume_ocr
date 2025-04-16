<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6">
            <h2 class="text-2xl font-bold text-white">AI Suggestions</h2>
            <p class="text-blue-100 mt-1">Step 3: Enhance your resume with optional fields</p>
        </div>

        <!-- Card Body -->
        <div class="p-6 space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Available Suggestions</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($suggestions as $item)
                        @if(!in_array($item, $appliedSuggestions))
                            <button wire:click="applySuggestion('{{ $item }}')"
                                    wire:loading.attr="disabled"
                                    class="bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-800 text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-all flex items-center"
                                    :disabled="$isProcessing">
                                <span>+ {{ ucfirst($item) }}</span>
                                <span wire:loading wire:target="applySuggestion('{{ $item }}')" class="ml-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Resume Content</h3>

                <form wire:submit.prevent="save" class="space-y-6">
                    @foreach($fields as $key => $value)
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 relative group hover:border-blue-300 transition-colors">
                            <div class="flex justify-between items-center mb-3">
                                <label class="block font-semibold capitalize text-gray-700">
                                    {{ str_replace('_', ' ', $key) }}
                                </label>
                                @if(in_array($key, $suggestions))
                                    <button type="button"
                                            wire:click="removeField('{{ $key }}')"
                                            class="text-red-500 hover:text-red-700 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            @if(is_array($value))
                                @if(isset($value[0]) && is_array($value[0]))
                                    <!-- Nested array (like experience) -->
                                    <div class="space-y-3">
                                        @foreach($value as $index => $item)
                                            <div class="bg-white p-4 rounded-lg border border-gray-200 relative group-item hover:bg-gray-50 transition-colors">
                                                <div class="absolute top-3 right-3">
                                                    <button type="button"
                                                            wire:click="removeArrayItem('{{ $key }}', {{ $index }})"
                                                            class="text-red-400 hover:text-red-600 opacity-0 group-item-hover:opacity-100 transition-opacity">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @foreach($item as $subKey => $subValue)
                                                    <div class="mb-3">
                                                        <label class="block text-sm font-medium text-gray-600 capitalize mb-1">
                                                            {{ str_replace('_', ' ', $subKey) }}
                                                        </label>
                                                        @if(is_array($subValue))
                                                            <textarea wire:model="fields.{{ $key }}.{{ $index }}.{{ $subKey }}"
                                                                      class="w-full p-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                                                      rows="3">{{ json_encode($subValue, JSON_PRETTY_PRINT) }}</textarea>
                                                        @else
                                                            <input type="text"
                                                                   wire:model="fields.{{ $key }}.{{ $index }}.{{ $subKey }}"
                                                                   class="w-full p-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                        <button type="button"
                                                wire:click="addNestedItem('{{ $key }}')"
                                                class="mt-2 text-blue-600 hover:text-blue-800 text-sm flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Add {{ str_replace('_', ' ', $key) }} Item
                                        </button>
                                    </div>
                                @else
                                    <!-- Simple array (like skills) -->
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($value as $index => $item)
                                            <div class="flex items-center bg-blue-100 rounded-full px-3 py-1 group-tag hover:bg-blue-200 transition-colors">
                                                <span class="text-sm text-blue-800">{{ $item }}</span>
                                                <button type="button"
                                                        wire:click="removeArrayItem('{{ $key }}', {{ $index }})"
                                                        class="ml-1 text-blue-600 hover:text-blue-800 opacity-0 group-tag-hover:opacity-100 transition-opacity">
                                                    &times;
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="flex mt-2">
                                        <input type="text"
                                               wire:model="newItem.{{ $key }}"
                                               wire:keydown.enter="addArrayItem('{{ $key }}')"
                                               class="flex-1 p-2 border rounded-l-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                               placeholder="Add new {{ str_replace('_', ' ', $key) }}" />
                                        <button type="button"
                                                wire:click="addArrayItem('{{ $key }}')"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-lg text-sm transition-colors">
                                            Add
                                        </button>
                                    </div>
                                @endif
                            @else
                                <!-- Simple value -->
                                <input type="text"
                                       wire:model="fields.{{ $key }}"
                                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
                            @endif
                        </div>
                    @endforeach

                    <div class="pt-4 flex justify-between items-center border-t border-gray-200 mt-6">
                        <a href="{{ route('wizard.step2', ['document' => $document->id]) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back
                        </a>
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow font-medium transition-colors flex items-center">
                            Continue to Preview
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($isProcessing)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-center">
            <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-700 font-medium">Processing suggestion...</p>
            <p class="text-sm text-gray-500 mt-2">This may take a few moments</p>
        </div>
    </div>
@endif
