<div class="container mt-3">
    <div class="row mb-3">
        <div class="col">
            <h2>Snippet Details</h2>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card" style="width: 100%;">
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
                </div>
            </div>
        </div>
    </div>
</div>
