@if ($errors->any())
    <div id="app-flash-message" data-type="error" data-message="{{ $errors->first() }}" class="hidden"></div>
@elseif (session('success'))
    <div id="app-flash-message" data-type="success" data-message="{{ session('success') }}" class="hidden"></div>
@elseif (session('error'))
    <div id="app-flash-message" data-type="error" data-message="{{ session('error') }}" class="hidden"></div>
@elseif (session('info'))
    <div id="app-flash-message" data-type="info" data-message="{{ session('info') }}" class="hidden"></div>
@elseif (session('warning'))
    <div id="app-flash-message" data-type="warning" data-message="{{ session('warning') }}" class="hidden"></div>
@endif
