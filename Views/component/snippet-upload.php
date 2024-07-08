<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.31.0/min/vs/loader.js"></script>
    <style>
    a {
    color: inherit; /* a要素のテキストカラーを親要素(header)のカラーに合わせる */
}

header, h2, h4, strong {
    color: gray;
}

/* CSSでフッターの下に隙間ができないようにする: https://zenn.dev/catnose99/articles/a873bbbe25b15b */
.wrapper {
    display: grid;
    grid-template-rows: auto 1fr auto;
    grid-template-columns: 100%;
    min-height: 100vh;
}

/* .container {
    margin-bottom: 50px; /* フッターの高さに応じて調整 } */

/* テーブルのスタイル */
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
.table td, .table th {
    word-wrap: break-word;
    max-width: 200px; /* ここで最大幅を設定 */
}
pre {
    white-space: pre-wrap; /* テキストの折り返し */
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}

.table tbody + tbody {
    border-top: 2px solid #dee2e6;
}

.table .table {
    background-color: #fff;
}

.footer {
    background-color: #ddeded;
    padding: 1rem;
    text-align: center;
    width: 100%;
    bottom: 0;
    /* left: 0; */
    /* position: fixed; */
    /* margin-top: auto; */
    /* margin-bottom: auto; */
}

.btn-custom {
    background-color: #f0f8ff; /* 淡い青色の例 */
    color: #000; /* 文字色 */
    border: 1px solid #b0c4de; /* 淡い青色のボーダー */
}

.btn-custom:hover {
    background-color: #e6f2ff; /* ホバー時の色 */
    border-color: #add8e6; /* ホバー時のボーダー色 */
}

.pagination {
    justify-content: center; /* ページネーションを中央寄せ */
}
.pagination .page-link {
    background-color: #f8f9fa !important; /* btn-customに合わせた背景色 */
    border: 1px solid #ced4da !important; /* 境界線の色 */
    color: #0d6efd !important; /* btn-customの文字色 */
}
.pagination .page-item.active .page-link {
    background-color: #0d6efd !important; /* btn-customのアクティブ背景色 */
    border-color: #0d6efd !important; /* アクティブなボーダー色 */
    color: #fff !important; /* アクティブな文字色 */
}
.pagination .page-link:hover {
    background-color: #e2e6ea !important; /* btn-customのホバー背景色 */
    border-color: #dae0e5 !important; /* ホーバーボーダー色 */
    color: #0a58ca !important; /* ホーバー文字色 */
}
.upload-button {
    text-align: right; /* ボタンを右寄せ */
}
    </style>
    <title>Upload Snippet</title>
    <script>
        require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.31.0/min/vs' }});
        require(['vs/editor/editor.main'], function() {
            window.editor = monaco.editor.create(document.getElementById('editor'), {
                value: '',
                language: 'plaintext',
                theme: 'vs-dark'
            });

            document.getElementById('programming_language').addEventListener('change', function() {
                monaco.editor.setModelLanguage(window.editor.getModel(), this.value);
            });
        });
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Upload Snippet</h2>
    <form action="/snippets" method="post">
        <div class="mb-3">
            <label for="snippet_name" class="form-label"><strong>Snippet Name</strong></label>
            <input type="text" class="form-control" id="snippet_name" name="snippet_name" required>
        </div>
        <div class="mb-3">
            <label for="validity_period" class="form-label"><strong>Validity Period</strong></label>
            <select class="form-control" id="validity_period" name="validity_period" required>
                <option value="10_minutes">10 Minutes</option>
                <option value="1_hour">1 Hour</option>
                <option value="1_day">1 Day</option>
                <option value="permanent">Permanent</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="programming_language" class="form-label"><strong>Programming Language</strong></label>
            <select class="form-select" id="programming_language" name="programming_language" required>
                <option value="plaintext">Plain Text</option>
                <option value="csharp">C#</option>
                <option value="go">Go</option>
                <option value="javascript">JavaScript</option>
                <option value="java">Java</option>
                <option value="kotlin">Kotlin</option>
                <option value="php">PHP</option>
                <option value="python">Python</option>
                <option value="ruby">Ruby</option>
                <option value="rust">Rust</option>
                <option value="scala">Scala</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="snippet_content" class="form-label"><strong>Snippet</strong></label>
            <div id="editor" style="height:300px;border:1px solid #ced4da;"></div>
            <textarea name="snippet_content" id="snippet_content" style="display:none;"></textarea>
        </div>
        <div class='text-center mb-3'>
            <button type="submit" class="btn btn-custom">Upload</button>
        </div>
    </form>
</div>
<script>
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('snippet_content').value = window.editor.getValue();
    });
</script>
</body>
</html>
