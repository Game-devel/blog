<template>
    <div class="uk-position-relative">
        <div class="upload-file" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Headline</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                    culpa qui officia deserunt mollit anim id est laborum.</p>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary" type="button">Save</button>
                </p>
            </div>
        </div>
        <div ref="html-editor">
            <editor
                
                v-model="text"
                @onInit="editorInit"
                @onKeyUp="handleChange"
                :init="{
                    height: 500,
                    menubar: true,
                    gecko_spellcheck: false,
                    relative_urls: false,
                    allow_unsafe_link_target: true,
                    spellchecker_languages: '+Русский=ru,English=en',
                    images_upload_handler: uploadImage,
                    paste_as_text: true,
                    paste_preprocess : paste_preprocess,
                    paste_postprocess : paste_postprocess,
                    forced_root_block : 'p',
                    invalid_elements : 'pre,font,colgroup',
                    inline: false,
                    extended_valid_elements:extended_valid_elements,
                    plugins: 'print preview importcss searchreplace autolink directionality paste visualblocks visualchars fullscreen link codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help formatpainter charmap mentions code textcolor image imagetools',
                    toolbar:'formatselect | undo redo | bold italic underline strikethrough example_blockquote | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor formatpainter | pagebreak | charmap | fullscreen | link codesample code | example_removeformat video_insert example example_nofollow  | example_lat example_kaz | card_add wordcount | example_accordion | link image',
                }"
            ></editor>
        </div>
    </div>

</template>

<script>
    export default {
        model: {
            prop: 'checked',
            event: 'change'
        },
        props: {
            checked: {},
            id: {},
            is_card: {}
        },
        data() {
            return {
                editor: {},
                text: "",
                spellchecker_word_separator_chars: '\\s!"#$%&()*+,./:;<=>?@[\]^_{|}\xa7 \xa9\xab\xae\xb1\xb6\xb7\xb8\xbb\xbc\xbd\xbe\u00bf\xd7\xf7\xa4\u201d\u201c',
                extended_valid_elements: "div[*],p[*],img[*],style[*],list[*],iframe[*],tn-test_percent-component[*],tn-test_percent-kaz-component[*],tn-test-data-component[*],tn-test_nonum-component[*],tn-test-kaz-component[*],tn-test-component[*],tn-poll-component[*],tn-test_nonum-kaz-component[*],h1[*],br[*],li[*],ul[*],lo[*],a[*],blockquote[*],article[*],hr[*],table[*],tr[*],td[*],th[*],source[*],video[*],noindex[*],span[*],i[*],tn-calc-component[*],tn-calc-kaz-component[*]",
                container: null,
                words: 0,
                symbols: 0,
            }
        },
        watch: {
            text: async function (text) {
                // if (oldtext)
                    this.$emit('change', text);
            },
            checked: async function (i) {
                if (!this.text && this.checked) {
                    this.text = this.checked;
                    // this.AddContent(i);
                }
            },
        },
        mounted() {
            this.container = this.$refs['html-editor'];
        },
        methods: {
            editorInit(event, editor) {
                this.editor = editor;
                this.countWordsAndSymbols();
            },
            handleChange() {
                this.countWordsAndSymbols();
            },
            countWordsAndSymbols() {
                this.words = this.editor.plugins.wordcount.getCount();
                this.symbols = this.editor.getContent({format: 'text'}).length;
                let characters = this.$refs['html-editor'].querySelector('.mce-statusbar .mce-branding');
                characters.innerText = ' символов: ' + this.symbols;
                this.$emit('onCount', { words: this.words, characters: this.symbols });
            },
            paste_preprocess: function (plugin, args) {
            },
            paste_postprocess: function (plugin, args) {
                const tables = args.node.getElementsByTagName('table');
                Array.from(tables).forEach((child) => {
                    const wrapper = document.createElement('DIV');
                    wrapper.classList.add('ms-table-responsive');
                    wrapper.appendChild(child);
                    args.node.appendChild(wrapper);
                });
            },
            up: function (e) {
                this.drag = true;
            },
            move: function (e) {
                if (this.drag) {
                }
            },
            down: function (e) {
                this.drag = false;
            },

            _change: function (e) {
            },
            AddClass: function () {
                if (this.class_name) {
                    this.properties.classes.push(this.class_name);
                    this.class_name = '';
                }
            },
            removeClass: function (index) {
                this.properties.classes.splice(index, 1);
            },
            Apply: function () {
                let __class = this.properties.classes.join(' ');
                this.current_edit_element.classList.value = __class;

                if (this.properties.href) {
                    this.current_edit_element.setAttribute('href', this.properties.href);
                }

                if (this.properties.src) {
                    this.current_edit_element.setAttribute('src', this.properties.src);
                }

                if (this.properties.width) {
                    this.current_edit_element.setAttribute('width', this.properties.width);
                }

                if (this.properties.height) {
                    this.current_edit_element.setAttribute('height', this.properties.height);
                }

                if (this.properties.nofollow) {
                    this.current_edit_element.setAttribute('rel', 'nofollow');
                } else {
                    this.current_edit_element.setAttribute('rel', '');
                }

                UIkit.modal(this.edit_properties).hide();
            },
            onUpload: function (e) {
                let files = e.target.files;

                for (let i = 0; i < files.length; i++) {
                    let formData = new FormData();

                    if (this.$parent.checkAcceptFile(files[i])) {
                        formData.append('image', files[i]);
                    } else {
                        UIkit.notification({
                            message: `Нельзя загрузить файл <strong>"` + files[i].name + `"</strong> не верный тип файла ` + files[i].type + ` !`,
                            status: 'danger'
                        });
                    }

                    this.$http.post('/admin/upload/image',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(response => {
                        let data = response.data;
                        this.editor_window.focus();
                        this.editor_window.document.execCommand("insertImage", null, data.url);
                        UIkit.notification({message: 'Изображения успешно загружены!', status: 'success'});
                    }).catch(function () {
                        UIkit.notification({message: 'При загрузки изображений произошла ошибка!', status: 'danger'});
                    });
                }

            },
            update: async function (e) {
                await this.ClearFormat();
                this.$emit('change', this.text);
            },
            convert: function (method, text) {

                let translite_lat = {
                    А: "A", а: "a",
                    Ә: "A'", ә: "a'",
                    Б: "B", б: "b",
                    Д: "D", д: "d",
                    Е: "E", е: "e",
                    Ф: "F", ф: "f",
                    "Ғ": "G'", "ғ": "g'",
                    Г: "G", г: "g",
                    Х: "H", х: "h",
                    І: "I", i: "i",
                    І: "I", i: "i",
                    И: "I'", и: "i'",
                    Й: "I'", й: "i'",
                    H: "H", h: "h",
                    Ж: "J", ж: "j",
                    К: "K", к: "k",
                    Л: "L", л: "l",
                    М: "M", м: "m",
                    Н: "N", н: "n",
                    Ң: "N'", ң: "n'",
                    О: "O", о: "o",
                    Ө: "O'", ө: "o'",
                    П: "P", п: "p",
                    Қ: "Q", қ: "q",
                    Р: "R", р: "r",
                    Ш: "S'", ш: "s'",
                    С: "S", с: "s",
                    Т: "T", т: "t",
                    Ұ: "U", ұ: "u",
                    Ү: "U'", ү: "u'",
                    В: "V", в: "v",
                    Ы: "Y", ы: "y",
                    У: "Y'", у: "y'",
                    З: "Z", з: "z",
                    Ч: "C'", ч: "c'",
                    Э: "E", э: "e",
                    Щ: "s'", щ: "s'",
                    ь: '',
                    ъ: '',

                };

                let translite_kaz = {
                    "A'": "Ә", "a'": "ә",
                    "A": "А", "a": "а",
                    "B": "Б", "b": "б",
                    "D": "Д", "d": "д",
                    "E": "Е", "e": "е",
                    "F": "Ф", "f": "ф",
                    "G'": "Ғ", "g'": "ғ",
                    "G": "Г", "g": "г",
                    "H": "Х", "h": "х",
                    "I": "І", "i": "i",
                    "I": "І", "i": "i",
                    "I'": "Й", "i'": "й",
                    "I'": "И", "i'": "и",
                    "H": "H", "h": "h",
                    "J": "Ж", "j": "ж",
                    "K": "К", "k": "к",
                    "L": "Л", "l": "л",
                    "M": "М", "m": "м",
                    "N'": "Ң", "n'": "ң",
                    "N": "Н", "n": "н",
                    "O'": "Ө", "o'": "ө",
                    "O": "О", "o": "о",
                    "P": "П", "p": "п",
                    "Q": "Қ", "q": "қ",
                    "R": "Р", "r": "р",
                    "S'": "Ш", "s'": "ш",
                    "S": "С", "s": "с",
                    "T": "Т", "t": "т",
                    "U'": "Ү", "u'": "ү",
                    "U": "Ұ", "u": "ұ",
                    "V": "В", "v": "в",
                    "Y'": "У", "y'": "у",
                    "Z": "З", "z": "з",
                    "C'": "Ч", "c'": "ч",
                    "Y": "Ы", "y": "ы",
                    "s'": "Щ", "s'": "щ"

                };

                let _translate_kaz = {
                    "I'a'": "Иә", "i'a'": "иә",
                    "I'a": "Я", "i'a": "я",
                    "I'y'": "Ю", "i'y'": "ю",
                };
                let _translate_lat = {
                    Я: "I'a", я: "i'a",
                    Ю: "I'y'", ю: "i'y'",
                    Ц: "Ts", ц: "ts"

                };

                if (method === 'lat') {
                    text += "";
                    for (let key in _translate_lat) {
                        text = text.replace(new RegExp(key.toString(), 'g'), _translate_lat[key]);
                    }

                    for (let key in translite_lat) {
                        text = text.replace(new RegExp(key.toString(), 'g'), translite_lat[key]);
                    }

                    return text;
                }

                if (method === 'kaz') {

                    text + i
                    n = "";
                    for (let key in _translate_kaz) {
                        text = text.replace(new RegExp(key.toString(), 'g'), _translate_kaz[key]);
                    }

                    for (let key in translite_kaz) {
                        text = text.replace(new RegExp(key.toString(), 'g'), translite_kaz[key]);
                    }

                    return text;
                }

            },
            ShowCode: function () {
                this.show_code = !this.show_code;
            },
            CurlToLat: function () {

                let div = document.createElement('div');
                let child = div.childNodes;
                for (let item in child) {
                    let temp = child[item].childNodes;
                    for (let i in temp) {
                        if (temp[i].nodeName === 'A' || temp[i].nodeName === 'IFRAME') {
                            break;
                        }
                        if (temp[i].nodeName === '#text') {
                            child[item].innerText = this.convert('lat', child[item].innerText);
                        }
                    }
                }
//
            },
            LatToCurl: function () {
                let div = document.createElement('div');
                let child = div.childNodes;
                for (let item in child) {
                    let temp = child[item].childNodes;
                    for (let i in temp) {
                        if (temp[i].nodeName === 'A' || temp[i].nodeName === 'IFRAME') {
                            break;
                        }
                        if (temp[i].nodeName === '#text') {
                            child[item].innerText = this.convert('kaz', child[item].innerText);
                        }
                    }
                }
//
//                content = this.convert('lat',content);
            },
            ClearHtml: (node) => {

                let p = document.createElement('p');
                if (node.tagName !== 'P' && node.tagName !== 'A' && node.tagName !== 'DIV' && node.tagName !== 'BR') {
                    node.parentNode.insertBefore(p, node);
                    node.remove();
                }

                node.childNodes.forEach((j) => {
                    this.ClearHtml(j);
                });

            },
            ClearFormat: function () {
                let validate = {
                    '&nbsp;': ' ',
                    '&ensp;': '  ',
                    '&emsp;': '   ',
                    '&ndash;': '–',
                    '&mdash;': '—',
                    '&shy;': '&shy;',
                    '&copy;': '©',
                    '&reg;': '®',
                    '&trade;': '™',
                    '&ordm;': 'º',
                    '&ordf;': 'ª',
                    '&permil;': '‰',
                    '&pi;': 'π',
                    '&laquo;': '"',
                    '&raquo;': '"',
                    '«': '"',
                    '»': '"',
                    '&lt;': '<',
                    '&gt;': '>'
                };

                let div = document.createElement('DIV');
                div.innerHTML = this.text;
                let nodes = div.querySelectorAll('*[style]');
                if (nodes.length) {
                    // for (let node = 0; node < nodes.length; node++) {
                    //     nodes[node].removeAttribute('style');
                    // }

                    // nodes = div.childNodes;
                    // nodes.forEach((i) => {
                    //     this.ClearHtml(i);
                    // });

                    for (let key in validate) {
                        div.innerHTML = div.innerHTML.replace(new RegExp(key.toString(), 'g'), validate[key].toString());
                    }

                    this.AddContent(div.innerHTML);
                    this.text = div.innerHTML;
                }
            },
            AddSlider: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("insertHTML", null, "<p><div class=\"text_slider\"><span class=\"prev tn-hidden-mobile\">&nbsp;</span><span class=\"next tn-hidden-mobile\">&nbsp;</span><div class=\"slider-container\"><p class=\"item active\"></p></div><ul class=\"slider-dot-nav\"><li>&nbsp;</li></ul></div></p>");
            },
            AddSlide: function () {
                let container = this.findContainer(this.current_edit_element, 'slider-container');
                if (container) {
                    let item = document.createElement('P');
                    item.classList.add('item');
                    container.append(item);
                }

            },
            AddCard: function () {
                if (!this.findContainer(this.current_edit_element, 'custom') && !this.findContainer(this.current_edit_element, 'custom-content') && !this.findContainer(this.current_edit_element, 'custom-content') && !this.findContainer(this.current_edit_element, 'card-title') && !this.findContainer(this.current_edit_element, 'card-content')) {
                    this.editor_window.focus();
                    this.editor_window.document.execCommand("insertHTML", null, "<p><div class=\"card-wrap\"><div class=\"card-title\">&nbsp;</div><div class=\"card-content\"><p>&nbsp;</p></div></div></p><p><br></p>");
                }
            },
            setCardHeaderColor: function () {
                if (this.current_edit_element.classList.contains('card-title')) {
                    this.current_edit_element.style.backgroundColor = 'rgb(67, 172, 56)';
                    this.current_edit_element.style.color = 'rgb(255, 255, 255)';
                }
            },
            AddCardCustom: function () {
                if (!this.findContainer(this.current_edit_element, 'custom') && !this.findContainer(this.current_edit_element, 'custom-content') && !this.findContainer(this.current_edit_element, 'custom-content') && !this.findContainer(this.current_edit_element, 'card-title') && !this.findContainer(this.current_edit_element, 'card-content')) {
                    this.editor_window.focus();
                    this.editor_window.document.execCommand("insertHTML", null, "<p><div class=\"custom\"><div class=\"custom-content\">&nbsp;</div></div></p><p><br></p>");
                }
            },
            findContainer: function (obj, _class) {
                if (obj.tagName !== 'HTML') {
                    if (obj.classList.value.indexOf(_class) > -1) {
                        return obj;
                    } else {
                        return this.findContainer(obj.parentNode, _class);
                    }
                } else {
                    return false;
                }
            },
            AddStyle: function () {
                let link = document.createElement('LINK');
                link.setAttribute('href', '/css/editor.css');
                link.setAttribute('rel', 'stylesheet');
                this.editor_head.append(link);
            },
            AddContent: async function (text) {
                // let div = document.createElement('div');
                // div.innerHTML = text;
                // await div.querySelectorAll('*').forEach(function (i) {
                //     // i.removeAttribute('style')
                // });
                // text = div.innerHTML;
                //
                // if (text) {
                //     this.text = text;
                // } else {
                //     this.text = "<p>&nbsp;</p>";
                // }
            },
            exec: function (command) {
                if (this[command.action]) {
                    this[command.action](command);
                }
            },
            Bold: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("bold", null, "");
            },
            Italic: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("italic", null, "");
            },
            InsertImage: function () {
                if (this.upload_input) {
                    this.upload_input.click();
                }
            },
            InsertVideo: function () {
                if (this.add_video) {
                    UIkit.modal(this.add_video).show();
                }
            },
            AddVideo: function () {
                if (this.video_url.url.indexOf('https://www.youtube.com/embed') <= -1) {
                    let url = this.video_url.url.split('?v=');
                    this.video_url.url = 'https://www.youtube.com/embed/' + url[1];
                }
                this.editor_window.focus();
                this.editor_window.document.execCommand("insertHTML", null, "<p><iframe width=\"" + this.video_url.width + "\" height=\"" + this.video_url.height + "\" src=\"" + this.video_url.url + "\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe></p><p><br></p>");
                this.video_url = {
                    url: "#",
                    width: 560,
                    height: 315,
                };
                this.video_code = '';
                UIkit.modal(this.add_video).hide();
            },
            AddVideoCode: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("insertHTML", null, "<p>" + this.video_code + "</p><p><br></p>");
                this.video_url = {
                    url: "#",
                    width: 560,
                    height: 315,
                };
                this.video_code = '';
                UIkit.modal(this.add_video).hide();
            },
            JustifyLeft: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("justifyLeft", null, "");
            },
            JustifyCenter: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("justifyCenter", null, "");
            },
            JustifyRight: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("justifyRight", null, "");
            },
            JustifyFull: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("justifyFull", null, "");
            },
            UnorderedList: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("insertUnorderedList", null, "");
            },
            OrderedList: function () {
                this.editor_window.focus();
                this.editor_window.document.execCommand("insertOrderedList", null, "");
            },
            CreateLink: function (href) {
                this.editor_window.focus();
                this.editor_window.document.execCommand("createLink", null, href.value);
            },
            FormatBlock: function (tag) {
                this.editor_window.focus();
                this.editor_window.document.execCommand("formatBlock", null, tag.value);
            },
            findPos: function (obj) {
                let curtop = 0;
                if (obj.offsetParent) {
                    do {
                        curtop += obj.offsetTop;
                    } while (obj = obj.offsetParent);
                    return [curtop];
                }
            },
            uploadImage(blobInfo, success, failure, progress) {
                const formData = new FormData();
                formData.append('image', blobInfo.blob());
                axios.post('/ajax/upload/image', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    const { image } = response.data;
                    success(`${image.url}`);
                }).catch(er => {
                    failure('Image upload failed. Code: ' + er.status)
                });
            },
        }
    }
</script>
<style>



    .tn_editor_tool_bar {
        border: 1px solid #e5e5e5;
        box-sizing: border-box;
        padding: 5px;
    }

    .tn_editor_tool_bar .uk-button-group {
        margin: 5px 5px 5px 0;
    }

    .tn_editor_tool_bar .tn_editor_tool_bar_button {
        padding: 3px 7px;
        background-color: transparent;
        color: #333;
        border: 1px solid #e5e5e5;
        cursor: pointer;
        margin-left: 0;
        margin-right: 5px;
    }

    .tn_editor_tool_bar .tn_editor_tool_bar_button:hover {
        box-shadow: 0 0 5px rgba(0, 0, 0, .3);
    }

    .tn_editor {
        position: relative;
        width: 100%;
        min-height: 500px;
        border: 1px solid #e5e5e5;
        box-sizing: border-box;
    }

    .tn_editor .tn_editor_container {
        width: 100%;
        height: 500px;
        box-sizing: border-box;
    }

    .tn_modal {
        width: 100%;
        position: absolute;
        height: 100%;
        top: 0;
        z-index: 5;
        display: flex;
        justify-content: center;
        padding: 10px;
        box-sizing: border-box;
    }

    .tn_modal_overlay {
        background: rgba(0, 0, 0, .5);
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
    }

    .tn_modal_header, .tn_modal_footer {
        min-height: 35px;
        background: #fff;
        width: 100%;
        padding: 10px;
        position: relative;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }

    .tn_modal_close {
        position: absolute;
        right: 10px;

    }

    .tn_modal_body {
        min-height: 200px;
        background: #fff;
        border-top: 2px solid rgba(0, 0, 0, .5);
        border-bottom: 2px solid rgba(0, 0, 0, .5);
    }

    .tn_modal_container {
        width: 100%;
        z-index: 5;
    }

    .tn-editor-code {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
    }

    input[name="image"] {
        opacity: 0;
    }

    .uk-drag {
        cursor: pointer;
        float: right;
    }
</style>
