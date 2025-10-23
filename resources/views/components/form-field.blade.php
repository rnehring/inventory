@props(['fieldName', 'labelText', 'type' => 'text', 'value' => ''])

<div class="mb-5 max-w-4xl">
    <x-form-label for="{{ $fieldName }}"> {{ $labelText }}</x-form-label>
    <x-form-input fieldName="{{ $fieldName }}" type="{{ $type }}" value="{{ $value }}" {{ $attributes->merge() }}/>
    <x-form-error name="{{ $fieldName }}"></x-form-error>
</div>
