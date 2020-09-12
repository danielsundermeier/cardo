<div class="card mb-5">
    <div class="card-header">Bank</div>
    <div class="card-body">
        <div class="form-group">
            <label for="bankname">Bank</label>
            <input type="text" class="form-control {{ ($errors->has('bankname') ? 'is-invalid' : '') }}" id="bankname" name="bankname" value="{{ old('bankname') ?? $model->bankname }}">
            @if ($errors->has('bankname'))
                <div class="invalid-feedback">
                    {{ $errors->first('bankname') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="bic">BIC</label>
            <input type="text" class="form-control {{ ($errors->has('bic') ? 'is-invalid' : '') }}" id="bic" name="bic" value="{{ old('bic') ?? $model->bic }}">
            @if ($errors->has('bic'))
                <div class="invalid-feedback">
                    {{ $errors->first('bic') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="iban">IBAN</label>
            <input type="text" class="form-control {{ ($errors->has('iban') ? 'is-invalid' : '') }}" id="iban" name="iban" value="{{ old('iban') ?? $model->iban }}">
            @if ($errors->has('iban'))
                <div class="invalid-feedback">
                    {{ $errors->first('iban') }}
                </div>
            @endif
        </div>
    </div>
</div>