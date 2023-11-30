<?php
switch($pg)
{
  case "designer":
    if ($action=="portfolio" || $action=="gallery-detail")
    {
      ?>
<script src="<?php echo DIR_WWW_ROOT; ?>project/src/public/js/ekko-lightbox.min.js"></script>
<link rel="stylesheet" href="<?php echo DIR_WWW_ROOT; ?>project/src/public/css/ekko-lightbox.css" />
<script type="text/javascript" language="javascript">
  $j(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $j(this).ekkoLightbox();
            });
</script>
      <?php
    }
    break;
}
?>
