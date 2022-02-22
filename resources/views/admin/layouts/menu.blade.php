@php
    $blogs_create=['blogs.create'];
    $create=array_merge($blogs_create);

    $blogs_edit=['blogs.index', 'blog.edit', 'blog.destroy', 'blog.show'];
    $edit=array_merge($blogs_edit);


    $rubric=['rubrics.index', 'rubric.edit', 'rubric.create'];
    $moderation=array_merge($rubric);

    $users=['admin_user_list','admin_user_edit', 'admin_user_add'];
    $settings=array_merge($users);

@endphp

<ul class="uk-nav uk-nav-side  uk-nav-parent-icon" data-uk-nav="{multiple:true}">
    <li>
        <a href="/" title="Вернуться на сайт">
            <i class="uk-icon">
                <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="world">
                    <path fill="none" stroke="#000" d="M1,10.5 L19,10.5"></path>
                    <path fill="none" stroke="#000" d="M2.35,15.5 L17.65,15.5"></path>
                    <path fill="none" stroke="#000" d="M2.35,5.5 L17.523,5.5"></path>
                    <path fill="none" stroke="#000"
                          d="M10,19.46 L9.98,19.46 C7.31,17.33 5.61,14.141 5.61,10.58 C5.61,7.02 7.33,3.83 10,1.7 C10.01,1.7 9.99,1.7 10,1.7 L10,1.7 C12.67,3.83 14.4,7.02 14.4,10.58 C14.4,14.141 12.67,17.33 10,19.46 L10,19.46 L10,19.46 L10,19.46 Z"></path>
                    <circle fill="none" stroke="#000" cx="10" cy="10.5" r="9"></circle>
                </svg>
            </i>
            <span>Вернуться на сайт</span>
        </a>
    </li>
    <li class="{{ in_array(Route::currentRouteName(),['admin']) ? 'uk-active' : '' }}">
        <a href="{{ route('admin') }}" title="Главная страница">
            <i class="uk-icon">
                <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="18.65 11.35 10 2.71 1.35 11.35 0.65 10.65 10 1.29 19.35 10.65"></polygon>
                    <polygon points="15 4 18 4 18 7 17 7 17 5 15 5"></polygon>
                    <polygon
                            points="3 11 4 11 4 18 7 18 7 12 12 12 12 18 16 18 16 11 17 11 17 19 11 19 11 13 8 13 8 19 3 19"></polygon>
                </svg>
            </i>
            <span>Главная страница</span>
        </a>
    </li>

    <li class="uk-parent {{(in_array(Route::currentRouteName(),$create)?'uk-open uk-active':'')}}">
        <a href="#" title="Создать">
            <i uk-icon="plus-circle"></i>
            <span>Создать</span>
        </a>
        <ul class="uk-nav-sub" aria-hidden="true" hidden="">
            
            <li class="{{ in_array(Route::currentRouteName(),$blogs_create) ? 'uk-active' : '' }}">
                <a href="{{ route('blog.create') }}" title="Ответы">
                    <i uk-icon="newspaper" class="uk-icon"></i>
                    <span>Blog</span>
                </a>
            </li>
        </ul>
    </li>


    <li class="uk-parent {{(in_array(Route::currentRouteName(),$edit)?'uk-open uk-active':'')}}">
        <a href="#" title="Редактировать">
            <i class="uk-icon">
                <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <rect x=".4" y="1" width="19.2" height="3"></rect>
                    <rect x="3" y="5" width="1" height="1"></rect>
                    <rect x="5" y="5" width="12.5" height="1"></rect>
                    <rect x="3" y="8" width="1" height="1"></rect>
                    <rect x="5" y="8" width="12.5" height="1"></rect>
                    <rect x="3" y="11" width="1" height="1"></rect>
                    <rect x="5" y="11" width="12.5" height="1"></rect>
                    <rect x="3" y="14" width="1" height="1"></rect>
                    <rect x="5" y="14" width="12.5" height="1"></rect>
                    <rect fill="none" stroke="#000" stroke-width="1.3" x="1" y="3" width="18"
                            height="14"></rect>
                </svg>
            </i>
            <span>Редактировать</span>
        </a>
        <ul class="uk-nav-sub">
            <li class="{{ in_array(Route::currentRouteName(),$blogs_edit) ? 'uk-active' : '' }}">
                <a href="{{ route('blogs.index') }}" title="Материалы">
                    <i uk-icon="newspaper" class="uk-icon"></i>
                    <span>Материалы</span>
                </a>
            </li>
        </ul>
    </li>
    <li class="uk-parent {{(in_array(Route::currentRouteName(),$moderation)?'uk-open uk-active':'')}}">
        <a href="#" title="Модерация"><i uk-icon="file-edit"></i><span>Модерация</span></a>
        <ul class="uk-nav-sub">
            <li class="{{ in_array(Route::currentRouteName(),$rubric) ? 'uk-active' : '' }}">
                <a href="{{ route('rubrics.index') }}" title="Рубрики">
                    <i uk-icon="folder"></i><span>Рубрики</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="uk-parent {{(in_array(Route::currentRouteName(),$settings)?'uk-open uk-active':'')}}">
        <a href="#" title="Настройки"><i uk-icon="cog"></i><span>Настройки</span></a>
        <ul class="uk-nav-sub">
            <li class="{{ in_array(Route::currentRouteName(),$users) ? 'uk-active' : '' }}">
                <a href="#" title="Пользователи админки">
                    <i uk-icon="users"></i><span>Пользователи</span>
                </a>
            </li>
        </ul>
    </li>

</ul>
@php
    $pages = [];
    $page_class=['#ffa500','#1e87f0','#32d296'];
    $page_icon=['<i class="uk-margin-small-left uk-icon" uk-icon="rss"></i>','<i class="uk-margin-small-left uk-icon" uk-icon="database"></i>','<i class="uk-margin-small-left uk-icon" uk-icon="youtube"></i>',];
@endphp
<ul class="uk-nav uk-nav-side  uk-nav-parent-icon" data-uk-nav="{multiple:true}">
    @foreach($pages as $key=>$page)
        <li class="uk-padding-small" style=" background: {{$page_class[$key]}};">
            <a href="/admin/page/single/{{ $page->id }}" title="{{$page->title}}">
                {!! $page_icon[$key] !!}
                <span> {{ $page->title }}</span>
            </a>
        </li>
    @endforeach
</ul>
