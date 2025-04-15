<div class="space-y-6">
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block font-semibold">Select Context</label>
            <select wire:model="context" class="w-full p-2 border rounded">
                <option value="resume">Resume</option>
                <option value="property">Property</option>
                <option value="hotel">Hotel</option>
                <option value="education">Education</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold">Upload Resume (PDF/DOCX)</label>
            <input type="file" wire:model="file" class="w-full border p-2" />
            @error('file') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow">
            Continue to Review →
        </button>
    </form>
</div>
