@props(['fieldName', 'labelText', 'type' => 'text', 'value' => '', 'js' => ''])

<div class="mb-5 max-w-4xl">
    <x-form-label for="{{ $fieldName }}"> {{ $labelText }}</x-form-label>
    <x-form-input fieldName="{{ $fieldName }}" type="{{ $type }}" value="{{ $value }}" js="{{ $js }}" {{ $attributes->merge() }}/>
    <x-form-error name="{{ $fieldName }}"></x-form-error>
</div>
