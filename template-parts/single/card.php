<?php
global $post;
?><div class="card card-bg bg-white card-thumb-rounded">
	<div class="card-body">
		<div class="card-content">
			<h4 class="h5"><a href="<?php echo get_permalink($post); ?>"><?php echo get_the_title($post); ?></a></h4>
            <p><?php echo dci_get_meta("descrizione", "", $post->ID) ?  dci_get_meta("descrizione", "", $post->ID) :  get_the_excerpt($post); ?></p>
		</div>
	</div><!-- /card-body -->
</div><!-- /card --><?php
