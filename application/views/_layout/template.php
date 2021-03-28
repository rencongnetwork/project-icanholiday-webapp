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

    <?php
    include '_header.php';
    ?>

    <?php
    include '_sidebar.php';
    ?>

    <?php
    include '_breadcrumb.php';
    ?>

    <section class="content">
        <?php
        if (isset($content)) {
            if (!empty($content)) {
                $this->load->view($content);
            }
        }
        ?>
    </section>

    </div>
    <!-- /.content-wrapper -->

    <?php include '_footer.php' ?>


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