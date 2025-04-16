<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-xl">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6">
            <h2 class="text-2xl font-bold text-white">Upload Your Document</h2>
            <p class="text-blue-100 mt-1">Step 1: Select context and upload your file</p>
        </div>

        <!-- Card Body -->
        <div class="p-6 space-y-6">
            <form wire:submit.prevent="save" class="space-y-6">
                <!-- Context Select Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Document Context</label>
                    <div class="relative">
                        <select wire:model="context"
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-lg transition-all"
                                wire:loading.attr="disabled">
                            <option value="resume">Resume</option>
                            <option value="property">Property</option>
                            <option value="hotel">Hotel</option>
                            <option value="education">Education</option>
                            <option value="other">Other</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- File Upload Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Document File</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg transition-all hover:border-blue-400">
                        <div class="space-y-1 text-center w-full">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input type="file" wire:model="file" class="sr-only" wire:loading.attr="disabled" />
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF or DOCX up to 10MB</p>
                            @error('file')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                            class="relative w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            wire:loading.attr="disabled"
                            wire:target="save">
                        <!-- Default state -->
                        <span wire:loading.remove wire:target="save" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                            </svg>
                            Continue to Review
                        </span>

                        <!-- Loading state -->
                        <span wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>

                    <!-- Progress indicator -->
                    <div wire:loading wire:target="save" class="mt-3">
                        <div class="overflow-hidden h-1.5 rounded-full bg-gray-200">
                            <div class="animate-progress h-full bg-gradient-to-r from-blue-500 to-blue-300" style="animation-duration: 40s"></div>
                        </div>
                        <p class="text-center text-xs text-gray-500 mt-2">This may take up to a minute. Please don't close this page.</p>
                    </div>
                </div>
            </form>
        </div>

        <!-- Card Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
            <div class="flex items-center text-sm text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                We securely process your documents and never store them longer than necessary.
            </div>
        </div>
    </div>
</div>
