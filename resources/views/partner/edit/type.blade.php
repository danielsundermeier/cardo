<div class="card mb-3">
    <div class="card-header">Typ</div>
    <div class="card-body">
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="is_client" id="is_client" value="1" {{ $model->is_client ? 'checked="checked"' : '' }}>
            <label class="form-check-label" for="is_client">Kunde</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="is_staff" id="is_staff" value="1" {{ $model->is_staff ? 'checked="checked"' : '' }}>
            <label class="form-check-label" for="is_staff">Personal</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="is_supplier" id="is_supplier" value="1" {{ $model->is_supplier ? 'checked="checked"' : '' }}>
            <label class="form-check-label" for="is_supplier">Lieferant</label>
        </div>
    </div>
</div>