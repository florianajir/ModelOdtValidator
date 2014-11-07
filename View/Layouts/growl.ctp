<script type="text/javascript">
<?php if (isset($type) && $type == 'important') : ?>
	jQuery.jGrowl("<?php echo '<div class=\'important\'>'.$content_for_layout.'</div>'; ?>", { header:'Important :', glue:'before' });
<?php elseif (isset($type) && $type == 'erreur') : ?>
	jQuery.jGrowl("<?php echo '<div class=\'erreur\'>'.$content_for_layout.'</div>'; ?>", { header:'Erreur :', sticky:true });
<?php else : ?>
	jQuery.jGrowl("<?php echo $content_for_layout; ?>", {});
<?php endif; ?>
</script>
