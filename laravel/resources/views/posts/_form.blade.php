<div class="form-group">
    <label>{{ __('Title') }}</label>
    {{-- old helperでセッションにフラッシュデータとして保存されている入力を取り出す。第二引数でデフォルトの値を指定 --}}
    {{-- null合体演算子 expr1 ?? expr2 と isset(expr1) ? expr1 : expr2 は同値、expr1がnull, undefinedなら expr2を、そうでないならexpr1を返す --}}
    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? null) }}">
</div>
<div class="form-group">
    <label>{{ __('Content') }}</label>
    <input type="text" name="content" class="form-control" value="{{ old('content', $post->content ?? null) }}">
</div>
<div class="form-group">
    <label>{{ __('Thumbnail') }}</label>
    <input type="file" name="thumbnail" class="form-control-file">
</div>

@errors
@enderrors
