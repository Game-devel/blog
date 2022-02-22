@php $v=  config('app.css_js_version', '?v=72')  ; @endphp
<script src="/js/uikit/uikit.js{{$v}}" defer></script>
<script src="{{ mix('/js/uikit/uikit-icons.js') }}" defer></script>
<script src="{{ mix('/js/app.js') }}" defer></script>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>