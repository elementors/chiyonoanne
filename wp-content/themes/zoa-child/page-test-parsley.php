<?php 
/* Template Name: Thank you page */
get_header(); 
?>
<main id="main" class="page-content page-short_content">
	
    <div class="container">
				<form id="form">
  <label for="name">Name * :</label>
  <input type="text" name="name" class="bank-routing" data-parsley-trigger="focusout" required>
  <br>
  <input type="submit" value="validate">

  <p><small>This is a simplistic example, good to start from when giving examples of use of Parsley</small></p>
</form>
            </div>
</main>

<?php
    get_footer();