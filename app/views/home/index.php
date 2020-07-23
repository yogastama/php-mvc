<?php
view('templates/header');
?>
<div class="row">
    <div class="col-md-12">
        <?php Flasher::flash() ?>
    </div>
</div>
<h1>
    <?php
    var_dump(BASE_URL);
    ?>
</h1>
<?php
view('templates/footer');
?>