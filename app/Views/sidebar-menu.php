 <link rel="stylesheet" href="/assets/css/sidebar-menu.css">

<section id="sidebar-stick" class="animate-menu animate-menu-left" style="width: 200px">
    <ul class="sidebar-menu">
      <li class="sidebar-header">Menu Utama</li>
      <li>
        <a href="#">
          <i class="fa fa-dashboard"></i> <span>All Category</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-files-o"></i>
          <span>Java (Oracle)</span>
          <span class="label label-primary pull-right">4</span>
        </a>
        <ul class="sidebar-submenu" style="display: none;">
          <li><a href="top-nav.html"><i class="fa fa-circle-o"></i> Desktop - Swing</a></li>
          <li><a href="boxed.html"><i class="fa fa-circle-o"></i> Desktop - JavaFX</a></li>
          <li><a href="fixed.html"><i class="fa fa-circle-o"></i> Web - JSF</a></li>
          <li><a href="fixed.html"><i class="fa fa-circle-o"></i> Web - Springboot</a></li>
           <li><a href="fixed.html"><i class="fa fa-circle-o"></i> Android - Native</a></li>
          </li>
        </ul>
      </li>
     
      <li>
        <a href="#">
          <i class="fa fa-pie-chart"></i>
          <span>.NET Framework</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="sidebar-submenu">
          <li><a href="../charts/chartjs.html"><i class="fa fa-circle-o"></i> Desktop - C#</a></li>
          <li><a href="../charts/morris.html"><i class="fa fa-circle-o"></i> Desktop - C++</a></li>
          <li><a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Desktop - VB</a></li>
            <li><a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Web - ASP Core</a></li>
              <li><a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Web - ASP MVC</a></li>
        </ul>
      </li>
     
     
      <li>
        <a href="#">
          <i class="fa fa-table"></i> <span>PHP</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="sidebar-submenu">
          <li><a href="../tables/simple.html"><i class="fa fa-circle-o"></i>Php Native</a></li>
          <li><a href="../tables/data.html"><i class="fa fa-circle-o"></i>Codeigniter Framework</a></li>
           <li><a href="../tables/data.html"><i class="fa fa-circle-o"></i>Laravel Framework</a></li>
        </ul>
      </li>
     
   

      <li><a href="../../documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
      <li class="sidebar-header">Promo</li>
      <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Special Class</span></a></li>
    </ul>
  </section>

  <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
  <script src="/assets/js/sidebar-menu.js"></script>
  <script>
    $( document ).ready(function() {
    
    $.sidebarMenu($('.sidebar-menu'));

    $('#showLeft').click(function () {
      $('.animate-menu-left').toggleClass('animate-menu-open');
      $('#sidebar-space').toggleClass('col-lg-2');
      $('#sidebar-space').show();
    })

   

    });


  </script>