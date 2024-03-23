<!--Confirm Modal-->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="delete-message"></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-outline-dark btn-confirm-archive" type="button">Yes</button>
          <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="importModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                      <div class="modal-body">

                        <form action="usermaintenance/import" method="POST" enctype="multipart/form-data">
                          @csrf

                          <label for="">Are you sure you want to import User data?</label>
                            <input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                          </div>

                          <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-success">Import</button>
                            <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                          </div>
                        </form>
                  </div>
                </div>
              </div>


               <!--Expor Modal-->
               <div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Export User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                      <div class="modal-body">

                        <form action="usermaintenance/export" method="POST" enctype="multipart/form-data">
                          @csrf

                          <label for="">Are you sure you want to export User data?</label>

                          </div>

                          <div class="modal-footer">
                            <form action="usermaintenance/export" method="POST" enctype="multipart/form-data">
                              @csrf
                            <button type="submit" class="btn btn-sm btn-success">Export</button>
                            </form>
                            <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                          </div>
                        </form>
                  </div>
                </div>
              </div>