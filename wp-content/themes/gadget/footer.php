<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer class="footer">
		<div class="container">
    <div class="row">
      <aside class="widget widget_text" id="text-3">
        <div class="textwidget">
          <div class="col-md-3">
             <?php dynamic_sidebar('mobile-recharge' ); ?> 
          </div>
          <div class="col-md-3">
            <div class="data-recharge">
               <?php dynamic_sidebar( 'dth-recharge' ); ?> 
            </div>
            <div class="data-recharge">
              <?php dynamic_sidebar( 'datacard' ); ?> 
            </div>
          </div>
          <div class="col-md-3">
             <?php dynamic_sidebar( 'userful-links' ); ?> 
            
          </div>
          <div class="col-md-3">
             <?php dynamic_sidebar( 'lets-connect' ); ?> 
          </div>
        </div>
      </aside>
    </div>
  </div>
	</footer><!-- #colophon -->
</div><!-- #page -->
<script src="<?php bloginfo('template_url')?>/js/bootstrap.min.js"></script>
<?php wp_footer(); ?>
</body>
</html>