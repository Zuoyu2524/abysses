@if (($user->can('edit-in', $volume) || $user->can('sudo')) && $volume->isImageVolume() && !$volume->hasTiledImages())
    <sidebar-tab name="abysses" icon="fas fa-chevron-right" title="Perform Machine Learning Image Recognition Tool (Abysses)" href="{{route('volumes-abysses', $volume->id)}}"></sidebar-tab>
@endif
