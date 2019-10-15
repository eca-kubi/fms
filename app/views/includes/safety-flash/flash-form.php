<!--  Flash Form Modal -->
<div class="modal fade" id="flashFormModal" tabindex="-1" role="dialog" aria-labelledby="flashFormModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="flashFormModalTitle">HSSEC Incident Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="flashForm">
                    <div class="form-group">
                        <label for="incidentTitle" class="col-form-label">Incident/ Alert Title</label>
                        <select class="form-control" id="incidentTitle">
                            <option></option>
                            <option value="First Aid Injury">First Aid Injury</option>
                            <option value="Hydraulic Oil Spill">Hydraulic Oil Spill</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="typeOfAlert" class="col-form-label">Type of Alert</label>
                        <select class="form-control" id="typeOfAlert" multiple="multiple">
                            <option value="Health">Health</option>
                            <option value="Safety">Safety</option>
                            <option value="Environment">Environment</option>
                            <option value="Community">Community</option>
                            <option value="Security">Security</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="typeOfIncident" class="col-form-label">Type of Incident</label>
                        <select class="form-control" id="typeOfIncident" multiple="multiple">
                            <option value="Injury">Injury</option>
                            <option value="Property Damage">Property Damage</option>
                            <option value="Property Loss">Property Loss</option>
                            <option value="Environmental">Environmental</option>
                            <option value="Near Miss">Near Miss</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dateOfIncident" class="col-form-label">Date of Incident</label>
                        <input type="text" class="form-control" id="dateOfIncident" data-provide="datepicker" data-date-today-highlight="true">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-squared" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-squared">Save changes</button>
            </div>
        </div>
    </div>
</div>