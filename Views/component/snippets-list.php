<?php
// snippet-list.php

error_log("Including snippet-list.php");

?>

<div class="container mt-3 mb-3">
    <div class="row mb-3">
        <div class="col">
            <h2>Snippets List</h2>
            <strong>Page:</strong> <?= htmlspecialchars($page) ?></br>
            <strong>Items per Page:</strong><?= htmlspecialchars($perPage) ?></br>
        </div>
        <div class="col text-end">
            <a href="/snippet-upload" class="btn btn-custom">Upload Snippet</a>
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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Language</th>
                        <th>Snippet</th>
                        <th>Validity Period</th>
                        <th>Link to the Snippet</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($snippets as $snippet): ?>
                        <tr>
                            <td><?= htmlspecialchars($snippet['snippet_name']) ?></td>
                            <td><?= htmlspecialchars($snippet['programming_language']) ?></td>
                            <td><pre><?= htmlspecialchars($snippet['snippet']) ?></pre></td>
                            <td><?= htmlspecialchars($snippet['validity_period']) ?></td>
                            <td><a href="/snippets/<?= htmlspecialchars($snippet['unique_string']) ?>"><?= htmlspecialchars($snippet['unique_string']) ?></a></td>
                            <!-- <td>
                                <a href="/snippets/<?= htmlspecialchars($snippet['unique_string']) ?>" class="btn btn-primary btn-sm">View</a>
                            </td> -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <!-- ページングの追加 -->
    <nav>
        <ul class="pagination">
            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                <a class="page-link btn-custom" href="?page=1&perpage=<?= $perPage ?>">First</a>
            </li>
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link btn-custom" href="?page=<?= $page - 1 ?>&perpage=<?= $perPage ?>">Previous</a>
                </li>
            <?php endif; ?>
            <?php 
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);
            for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link btn-custom" href="?page=<?= $i ?>&perpage=<?= $perPage ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link btn-custom" href="?page=<?= $page + 1 ?>&perpage=<?= $perPage ?>">Next</a>
                </li>
            <?php endif; ?>
            <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link btn-custom" href="?page=<?= $totalPages ?>&perpage=<?= $perPage ?>">Last</a>
            </li>
        </ul>
    </nav>
</div>
