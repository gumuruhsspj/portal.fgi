

<div class="modal fade" id="customerServicesModal" tabindex="-1" role="dialog" aria-labelledby="customerServicesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="customerServicesForm" action="/customer-services/update" method="post" >

      <div class="modal-header">
        <h5 class="modal-title" id="customerServicesModalLabel">Customer Services</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
         <ul class="nav nav-tabs">
         <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" id="customer-services-dropdown" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Choose Profile</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item customer-opt" data-id="1" href="#">~ #01</a></li>
      <li><a class="dropdown-item customer-opt" data-id="2" href="#">~ #02</a></li>
    </ul>
  </li>
</ul>

    <section id="cs-01-form">

        <input type="hidden" id="cs-01-id" name="id[]" value="1" >

       

        <div class="form-group">
            <label for="nama" class="col-form-label">Nama 1:</label>
            <input type="text" name="nama[]" id="nama1" class="form-control" placeholder="nama orangnya" >
        </div>

        <div class="form-group">
            <label for="whatsapp" class="col-form-label">No. Whatsapp 1:</label>
            <textarea name="whatsapp[]" id="whatsapp1" class="form-control" ></textarea>
        </div>

         <div class="form-group">
            <label for="status" class="col-form-label">Status 1:</label>
            <select name="status[]" id="status1" class="form-control" >
                <option value="enable" >Enable</option>
                <option value="disabled" >Disabled</option>
            </select>
        </div>

    </section>

       

    <section id="cs-02-form">

        <input type="hidden" id="cs-02-id" name="id[]" value="2" >

        

        <div class="form-group">
            <label for="nama" class="col-form-label">Nama 2:</label>
            <input type="text" name="nama[]" id="nama2" class="form-control" placeholder="nama orangnya" >
        </div>

        <div class="form-group">
            <label for="whatsapp" class="col-form-label">No. Whatsapp 2:</label>
            <textarea name="whatsapp[]" id="whatsapp2" class="form-control" ></textarea>
        </div>

         <div class="form-group">
            <label for="status" class="col-form-label">Status 2:</label>
            <select name="status[]" id="status2" class="form-control" >
                <option value="enable" >Enable</option>
                <option value="disabled" >Disabled</option>
            </select>
        </div>

    </section>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <button id="btn-reset-customer-services" class="btn btn-secondary">Reset</button>
      </div>

    </form>
    </div>


  </div>
</div>

