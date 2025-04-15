<div class="space-y-6">
    <h2 class="text-xl font-bold">Resume Preview</h2>

    @if(isset($document) && !empty($document->extracted_data))
        <div class="bg-white p-6 rounded shadow">
            @foreach($document->extracted_data as $key => $value)
                <div class="mb-4 border-b pb-4">
                    <h3 class="font-semibold text-lg capitalize mb-2 text-blue-600">
                        {{ str_replace('_', ' ', $key) }}
                    </h3>

                    @if(is_array($value))
                        @if(isset($value[0]) && is_array($value[0]))
                            {{-- Handle nested arrays (like experience/education) --}}
                            <div class="space-y-4">
                                @foreach($value as $item)
                                    <div class="bg-gray-50 p-3 rounded">
                                        @foreach($item as $subKey => $subValue)
                                            <p class="mb-1">
                                                <span class="font-medium capitalize">{{ $subKey }}:</span>
                                                @if(is_array($subValue))
                                                    {{ implode(', ', $subValue) }}
                                                @else
                                                    {{ $subValue }}
                                                @endif
                                            </p>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Simple arrays (like skills) --}}
                            <div class="flex flex-wrap gap-2">
                                @foreach($value as $item)
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                        {{ $item }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    @else
                        {{-- Simple values --}}
                        <p class="text-gray-700">{{ $value }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <p class="text-yellow-700">No extracted data available. Please go back and extract fields first.</p>
        </div>
    @endif

    <div class="flex justify-between items-center mt-6">
        <a href="{{ route('wizard.step3', ['document' => $document->id]) }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Editing
        </a>

        <button wire:click="downloadResume"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
            Download Resume PDF
         </button>

    </div>
</div>
