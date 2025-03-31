<link rel="stylesheet" href="<?= $generalStyle ?>">
<h1><?= $header ?></h1>

<div class="section">
    <h2><?= $data['byDevice']['h2'] ?></h2>
    <table>
        <thead>
        <tr>
            <?php foreach ($data['byDevice']['theads'] as $item): ?>
                <th><?= $item ?></th>
            <?php endforeach ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['byDevice']['results'][0] as $item): ?>
        <tr>
            <td><a href="#"><?= $item['sessionId'] ?></a></td>
            <td><?= $item['event'] ?></td>
            <td><?= $item['coordX'] ?> x <?= $item['coordY'] ?></td>
            <td><?= $item['screenWidth'] ?> x <?= $item['screenHeight'] ?></td>
            <td><?= $item['timestamp'] ?></td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <div class="filters">
        <div>
            <span>Total: <?= $data['byDevice']['total'] ?></span>
        </div>
        <div class="page-buttons">
            <span>Previous</span>
            <span>Next</span>
        </div>
    </div>

</div>

<div class="section">
    <h2><?= $headingTables['byDevice'] ?></h2>
    <table>
        <thead>
        <tr>
            <?php foreach ($tables['byDevice'] as $link): ?>
                <th><?= $link ?></th>
            <?php endforeach ?>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>PCs</td>
            <td>300</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="section">
    <h2><?= $headingTables['bySesion'] ?></h2>
    <table>
        <thead>
        <tr>
            <?php foreach ($tables['bySesion'] as $link): ?>
                <th><?= $link ?></th>
            <?php endforeach ?>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>3LKJDFljkj34</td>
            <td>3</td>
            <td>2025-02-01</td>
        </tr>
        </tbody>
    </table>
</div>
