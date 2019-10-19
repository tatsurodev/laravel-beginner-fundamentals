<div class="form-group">
    <label>Title</label>
    {{-- oldの第二引数でデフォルトの値を指定できる --}}
    {{-- null合体演算子 expr1 ?? expr2 と isset(expr1) ? expr1 : expr2 は同値、expr1がnull, undefinedなら expr2を、そうでないならexpr1を返す --}}
    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? null) }}">
</div>
<div class="form-group">
    <label>Content</label>
    <input type="text" name="content" class="form-control" value="{{ old('content', $post->content ?? null) }}">
</div>
<div class="form-group">
    <label>Thumbnail</label>
    <input type="file" name="thumbnail" class="form-control-file">
</div>

@errors
@enderrors
