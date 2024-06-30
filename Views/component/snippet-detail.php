<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.31.0/min/vs/loader.js"></script>
    <title>Snippet Details</title>
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

