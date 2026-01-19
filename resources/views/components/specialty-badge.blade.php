@props(['specialty', 'level' => null])

@php
    $levelColors = [
        'beginner' => 'bg-blue-100 text-blue-800',
        'intermediate' => 'bg-indigo-100 text-indigo-800',
        'expert' => 'bg-purple-100 text-purple-800',
    ];

    $levelLabels = [
        'beginner' => 'Débutant',
        'intermediate' => 'Intermédiaire',
        'expert' => 'Expert',
    ];

    $colorClass = $level ? $levelColors[$level] ?? 'bg-gray-100 text-gray-800' : 'bg-indigo-100 text-indigo-800';
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
    {{ $specialty->name }}
    @if($level)
        <span class="ml-1 opacity-75">({{ $levelLabels[$level] ?? $level }})</span>
    @endif
</span>
