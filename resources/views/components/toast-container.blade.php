{{-- Container pour les toasts - position fixed en haut à droite --}}
<div class="fixed top-4 right-4 z-50 flex flex-col gap-3 max-w-sm">
    @if(session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif

    @if(session('error'))
        <x-toast type="error" :message="session('error')" />
    @endif

    @if(session('warning'))
        <x-toast type="warning" :message="session('warning')" />
    @endif

    @if(session('info'))
        <x-toast type="info" :message="session('info')" />
    @endif
</div>
