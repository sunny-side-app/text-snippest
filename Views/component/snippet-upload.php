<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.31.0/min/vs/loader.js"></script>
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
            <label for="snippet_name" class="form-label">Snippet Name</label>
            <input type="text" class="form-control" id="snippet_name" name="snippet_name" required>
        </div>
        <div class="mb-3">
            <label for="validity_period" class="form-label">Validity Period</label>
            <select class="form-control" id="validity_period" name="validity_period" required>
                <option value="10_minutes">10 Minutes</option>
                <option value="1_hour">1 Hour</option>
                <option value="1_day">1 Day</option>
                <option value="permanent">Permanent</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="programming_language" class="form-label">Programming Language</label>
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
            <label for="snippet_content" class="form-label">Snippet</label>
            <div id="editor" style="height:300px;border:1px solid #ced4da;"></div>
            <textarea name="snippet_content" id="snippet_content" style="display:none;"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
<script>
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('snippet_content').value = window.editor.getValue();
    });
</script>
</body>
</html>
