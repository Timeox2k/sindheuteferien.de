@php
    $states = config('holiday.states', []);
    $currentSlug = request()->route('bundesland');
@endphp

<nav class="navbar" aria-label="Schnellnavigation Bundesländer">
    <ul>
        @foreach($states as $state)
            <li>
                <a href="{{ route('bundesland', ['bundesland' => $state['slug']]) }}"
                   class="{{ $currentSlug === $state['slug'] ? 'active' : '' }}"
                   title="Ferien in {{ $state['name'] }}"
                   @if($currentSlug === $state['slug']) aria-current="page" @endif>
                    {{ $state['short'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
