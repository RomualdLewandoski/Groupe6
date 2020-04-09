<?php
function menu($page)
{
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">DamienLocation</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item <?= isActive($page, 'index'); ?>">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item <?= isActive($page, 'location'); ?>">
                    <a class="nav-link" href="/&?page=location">Location</a>
                </li>
                <li class="nav-item <?= isActive($page, 'contact'); ?>">
                    <a class="nav-link" href="/&?page=contact">Contact</a>
                </li>
            </ul>
        </div>
    </nav>

    <?php
}

function isActive($page, $model)
{
    return $page == $model ? "active" : "";
}