<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6">
            <h2 class="text-2xl font-bold text-white">Resume Preview</h2>
            <p class="text-blue-100 mt-1">Step 4: Review and download your final resume</p>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            @if(isset($document) && !empty($document->extracted_data))
                <div class="space-y-6">
                    @foreach($document->extracted_data as $key => $value)
                        <div class="mb-6 pb-6 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0">
                            <h3 class="font-semibold text-lg capitalize mb-3 text-blue-600">
                                {{ str_replace('_', ' ', $key) }}
                            </h3>

                            @if(is_array($value))
                                @if(isset($value[0]) && is_array($value[0]))
                                    <div class="space-y-4">
                                        @foreach($value as $item)
                                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                                @foreach($item as $subKey => $subValue)
                                                    <p class="mb-2">
                                                        <span class="font-medium capitalize text-gray-700">{{ $subKey }}:</span>
                                                        <span class="text-gray-600">
                                                            @if(is_array($subValue))
                                                                {{ implode(', ', $subValue) }}
                                                            @else
                                                                {{ $subValue }}
                                                            @endif
                                                        </span>
                                                    </p>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($value as $item)
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                                {{ $item }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <p class="text-gray-700">{{ $value }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Download Options Section -->
                <div class="mt-8 bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Download Your Resume</h3>
                    <p class="text-gray-600 mb-6">Choose from our professionally designed templates:</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Template 1 -->
                        <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <div class="bg-blue-50 p-4 border-b">
                                <h4 class="font-medium text-blue-800">Modern Professional</h4>
                            </div>
                            <div class="p-4">
                                <div class="h-32 bg-gradient-to-r from-blue-100 to-blue-50 mb-3 rounded flex items-center justify-center">
                                    <span class="text-blue-400 text-sm">Template Preview</span>
                                </div>
                                <button wire:click="downloadResume('modern')"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        <!-- Template 2 -->
                        <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <div class="bg-green-50 p-4 border-b">
                                <h4 class="font-medium text-green-800">Creative</h4>
                            </div>
                            <div class="p-4">
                                <div class="h-32 bg-gradient-to-r from-green-100 to-green-50 mb-3 rounded flex items-center justify-center">
                                    <span class="text-green-400 text-sm">Template Preview</span>
                                </div>
                                <button wire:click="downloadResume('creative')"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>

                        <!-- Template 3 -->
                        <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <div class="bg-purple-50 p-4 border-b">
                                <h4 class="font-medium text-purple-800">Minimalist</h4>
                            </div>
                            <div class="p-4">
                                <div class="h-32 bg-gradient-to-r from-purple-100 to-purple-50 mb-3 rounded flex items-center justify-center">
                                    <span class="text-purple-400 text-sm">Template Preview</span>
                                </div>
                                <button wire:click="downloadResume('minimalist')"
                                        class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Download Options -->
                    {{-- <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-medium text-gray-800 mb-3">Advanced Options</h4>
                        <div class="flex flex-wrap gap-3">
                            <button wire:click="downloadResume('pdf')"
                                    class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                PDF Format
                            </button>
                            <button wire:click="downloadResume('docx')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Word Document
                            </button>
                            <button wire:click="downloadResume('text')"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Plain Text
                            </button>
                        </div>
                    </div> --}}
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <p class="text-yellow-700">No extracted data available. Please go back and extract fields first.</p>
                </div>
            @endif

            <div class="flex justify-between items-center pt-6 mt-6 border-t border-gray-200">
                <a href="{{ route('wizard.step3', ['document' => $document->id]) }}"
                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Editing
                </a>
            </div>
        </div>
    </div>
</div>
