<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.31.0/min/vs/loader.js"></script>
    <title>Snippet Details</title>
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
</head>
<body>
<div class="container mt-3">
    <div class="row mb-3">
        <div class="col">
            <h2>Snippet Details</h2>
            <!-- <a href="/snippets" class="btn btn-primary">Back to Snippets List</a>
            <a href="/snippet-upload" class="btn btn-primary">Upload New Snippet</a> -->
        </div>
        <div class="col text-end">
            <a href="/snippets" class="btn btn-custom">Back to Snippets List</a>
            <a href="/snippet-upload" class="btn btn-custom">Upload New Snippet</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <h4 class="card-title">
                        <strong>Snippet Name:</strong> <?= htmlspecialchars($snippet['snippet_name']) ?>
                    </h4>
                    <!-- <h6 class="card-subtitle mb-2 text-muted"></h6> -->
                    <div class="card-text">
                        <strong>Language:</strong> <?= htmlspecialchars($snippet['programming_language']) ?><br />
                        <strong>Snippet:</strong><br />
                        <div id="editor" style="height:300px;border:1px solid #ced4da;"></div>
                        <strong>Validity Period:</strong> <?= htmlspecialchars($snippet['validity_period']) ?><br />
                        <strong>Unique String:</strong> <a href="/snippets/<?= htmlspecialchars($snippet['unique_string']) ?>"><?= htmlspecialchars($snippet['unique_string']) ?></a><br />
                        <small class="text-muted">Created at <?= htmlspecialchars($snippet['created_at']) ?></small><br />
                        <small class="text-muted">Updated at <?= htmlspecialchars($snippet['updated_at']) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.31.0/min/vs' }});
    require(['vs/editor/editor.main'], function() {
        monaco.editor.create(document.getElementById('editor'), {
            value: <?= json_encode(htmlspecialchars_decode($snippet['snippet'])) ?>,
            language: '<?= htmlspecialchars($snippet['programming_language']) ?>',
            theme: 'vs-dark',
            readOnly: true
        });
    });
</script>
</body>
</html>

