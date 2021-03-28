<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?></title>
    <?php
    include '_meta.php';
    include '_css.php';
    if (isset($custom_css)) {
        if (!empty($custom_css)) {
            $this->load->view($custom_css);
        }
    }
    ?>
</head>

<body data-layout="horizontal" data-topbar="colored">
    <div id="layout-wrapper">
        <?php
        include '_header.php';
        ?>
        <div class="main-content">
            <?php
            if (isset($content)) {
                if (!empty($content)) {
                    $this->load->view($content);
                }
            }
            ?>
        </div>

        <?php include '_footer.php' ?>

    </div>

    <?php
    include '_js.php';
    if (isset($custom_js)) {
        if (!empty($custom_js)) {
            $this->load->view($custom_js);
        }
    }
    ?>

</body>

</html>