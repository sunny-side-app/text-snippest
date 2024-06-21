<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Snippet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        #editor {
            height: 400px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Upload Snippet</h2>
    <form id="upload-form" action="/snippets" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="snippet-name" class="form-label">Snippet Name</label>
            <input type="text" class="form-control" id="snippet-name" name="snippet_name" required>
        </div>
        <div class="mb-3">
            <label for="programming-language" class="form-label">Programming Language</label>
            <input type="text" class="form-control" id="programming-language" name="programming_language" required>
        </div>
        <div class="mb-3">
            <label for="validity-period" class="form-label">Validity Period</label>
            <select class="form-select" id="validity-period" name="validity_period" required>
                <option value="10_minutes">10 minutes</option>
                <option value="1_hour">1 hour</option>
                <option value="1_day">1 day</option>
                <option value="permanent">Permanent</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="editor" class="form-label">Snippet</label>
            <div id="editor"></div>
            <textarea id="snippet-content" name="snippet_content" style="display: none;"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.28.1/min/vs/loader.js"></script>
<script>
    require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.28.1/min/vs' }});
    require(['vs/editor/editor.main'], function() {
        var editor = monaco.editor.create(document.getElementById('editor'), {
            value: '',
            language: 'javascript'
        });

        document.getElementById('upload-form').addEventListener('submit', function() {
            var snippetContent = document.getElementById('snippet-content');
            snippetContent.value = editor.getValue();
        });
    });
</script>
</body>
</html>
