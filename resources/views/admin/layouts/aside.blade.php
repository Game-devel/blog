<div data-side-nav="" class="tm-sidebar-left uk-visible@m uk-background-secondary">
    <aside class="uk-light">
        {{--        {{Route::currentRouteName()}}--}}
        <div class="uk-panel uk-panel-box no-border">
            @include('admin.layouts.menu')
        </div>
    </aside>
    <div id="offcanvas" uk-offcanvas="mode: push; overlay: true" class="uk-offcanvas">
        <div class="uk-offcanvas-bar">
            <div class="uk-panel">
                @include('admin.layouts.menu')
            </div>
        </div>
    </div>
</div>
