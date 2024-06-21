<div class="container mt-3">
    <div class="row mb-3">
        <div class="col">
            <h2>Snippets</h2>
            <p>Page: <?= htmlspecialchars($page) ?></p>
            <p>Items per Page: <?= htmlspecialchars($perPage) ?></p>
            <a href="/snippet-upload" class="btn btn-primary">Upload Snippet</a>
        </div>
    </div>
    <div class="row">
        <?php if (empty($snippets)): ?>
            <div class="col">
                <div class="alert alert-warning" role="alert">
                    No snippets found.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($snippets as $snippet): ?>
                <div class="col-md-4 mb-3">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($snippet['snippet_name']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($snippet['programming_language']) ?></h6>
                            <p class="card-text">
                                <strong>Snippet:</strong><br />
                                <pre><?= htmlspecialchars($snippet['snippet']) ?></pre>
                            </p>
                            <p class="card-text">
                                <strong>Validity Period:</strong> <?= htmlspecialchars($snippet['validity_period']) ?><br />
                                <strong>URL:</strong> <?= htmlspecialchars($snippet['url']) ?><br />
                            </p>
                            <p class="card-text"><small class="text-muted">Created at <?= htmlspecialchars($snippet['created_at']) ?></small></p>
                            <p class="card-text"><small class="text-muted">Updated at <?= htmlspecialchars($snippet['updated_at']) ?></small></p>
                            <a href="/snippets?id=<?= htmlspecialchars($snippet['id']) ?>" class="card-link">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
