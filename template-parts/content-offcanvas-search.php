 <!-- OFF CANVAS DROPDWON-->
 <div class="offcanvas offcanvas-top off-canvas-search" tabindex="-1" id="offcanvasTop" aria-labelledby="siteSearch" data-bs-scroll="true" data-bs-backdrop="false">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" tabindex="3"></button>
    </div>
    <div class="offcanvas-body" id="siteSearch">
       <!-- START SEARCH FORM -->
        <form class="d-flex" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <input class="form-control" type="search" placeholder="How can we help you?" tabindex="1" autofocus="true" aria-label="Search" value="">
            <a class="btn search-submit" type="submit" tabindex="2"><span class="visually-hidden-focusable">Search Submit</span></a>
        </form>
        <!-- END SEARCH FORM -->
    </div>
  </div>
 <!-- END OFF CANVAS DROPDWON-->