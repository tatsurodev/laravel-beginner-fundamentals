<p>
    <label>Title</label>
    {{-- oldの第二引数でデフォルトの値を指定できる --}}
    {{-- null合体演算子 expr1 ?? expr2 と isset(expr1) ? expr1 : expr2 は同値、expr1がnull, undefinedなら expr2を、そうでないならexpr1を返す --}}
    <input type="text" name="title" value="{{ old('title', $post->title ?? null) }}">
</p>
<p>
    <label>Content</label>
    <input type="text" name="content" value="{{ old('content', $post->content ?? null) }}">
</p>

@if($errors->any())
<div>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
