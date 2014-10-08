<div id="footer">
   <div class="container">
      
      <?php 
         $settings = array(
            'theme_location'  => 'footer-menu',
            'menu'            => '',
            'container'       => '',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul id="%1$s" class="%2$s nav nav-justified">%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
         );
      
         wp_nav_menu( $settings );
      ?>
      
      <div class="disclaimer">
         <p> Open UN-Habitat Transparency Initiative. Back to <a href="<?php bloginfo('url'); ?>">main website</a> of UN-Habitat<br>
            Content licensed under a Creative Commons Attribution 3.0 Unported License </p>
      </div>
      <div class="footer-logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/un-habitat-logo-footer.png" width="376" height="68" class="img-responsive" alt="UN-Habitat Logo"/></div>
   </div>
</div>
<?php /*
<!-- ******************************** BS MODALS ******************************** -->

<!-- login | register dialogue -->
<div class="modal fade bs-login-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
         <div id="sign-in">
            <div class="signin-column">
               <h2>Sign in</h2>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="signin-column">
               <form role="form" id="signin-form" action="dashboard.php">
                  <div class="form-group">
                     <label for="username">Username</label>
                     <input type="email" class="form-control" id="username" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                     <label for="password">Password</label>
                     <input type="password" class="form-control" id="password" placeholder="Password">
                  </div>
                  <div class="checkbox">
                     <label>
                        <input type="checkbox">
                        Remember me </label>
                     <div class="lost-password"><a href="#" id="show-lost-password">Forgot username or password</a></div>
                  </div>
                  <button type="submit" class="btn btn-default">Sign in</button>
               </form>
               <div class="clearfix"></div>
               <div class="alt-sign-in">
                  <p>Or sign in with</p>
                  <p class="sign-in-gmail"><a href="#">Gmail</a></p>
                  <p class="sign-in-linkedin"><a href="#">LinkedIn</a></p>
               </div>
               <div class="clearfix visible-xs-block"></div>
               <hr class="visible-xs-block">
            </div>
            <div class="register-column">
               <h2>Register</h2>
            </div>
            <div class="clearfix visible-xs-block"></div>
            <hr class="visible-xs-block">
            <div class="register-column">
               <form role="form" id="register-form">
                  <div class="register-col1">
                     <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email">
                     </div>
                     <div class="form-group">
                        <label for="first-name">First name</label>
                        <input type="text" class="form-control" id="first-name" placeholder="Enter first name">
                     </div>
                     <div class="form-group">
                        <label for="last-name">Last name</label>
                        <input type="text" class="form-control" id="last-name" placeholder="Enter last name">
                     </div>
                     <div class="form-group">
                        <label for="company">Company/Organisartion/University</label>
                        <input type="text" class="form-control" id="company" placeholder="Enter name">
                     </div>
                  </div>
                  <div class="register-col2">
                     <div class="form-group">
                        <label for="job-title">Job title</label>
                        <input type="text" class="form-control" id="job-title" placeholder="Enter job title">
                     </div>
                     <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" placeholder="Enter location">
                     </div>
                     <div class="form-group">
                        <label for="password1">Password</label>
                        <input type="password" class="form-control" id="password1" placeholder="Enter password">
                     </div>
                     <div class="form-group">
                        <label for="password2">Password</label>
                        <input type="password" class="form-control" id="password2" placeholder="Enter password again">
                     </div>
                     <button type="submit" class="btn btn-default" style="float:right;">Send</button>
                  </div>
               </form>
            </div>
            <div class="clearfix"></div>
            <hr>
         </div>

         <div id="lost-password" style="display:none">
            <div class="signin-column">
               <h2>Request new password</h2>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="signin-column">
               <p> Fill in your email address to request your account information. An email will be sent to the email address providedwith a new password. </p>
               <form role="form" id="signin-form">
                  <div class="form-group">
                     <label for="username">Email</label>
                     <input type="email" class="form-control" id="username" placeholder="Enter email">
                  </div>
                  <button type="submit" class="btn btn-default" >Retrieve</button>
               </form>
            </div>
            <div class="clearfix"></div>
            <hr>
         </div>
      </div>
   </div>
   <!-- /.modal-dialog --> 
    
</div>
<!-- /.modal --> 

<!-- ******************************** / BS MODALS ******************************** -->
*/ ?>


<?php wp_footer(); ?>
</body></html>