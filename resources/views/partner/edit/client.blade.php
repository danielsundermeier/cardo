<div class="card mb-3">
    <div class="card-header">Sonstiges</div>
    <div class="card-body">
        <div class="form-group">
            <label for="job">Beruf</label>
            <input type="text" class="form-control {{ ($errors->has('job') ? 'is-invalid' : '') }}" id="job" name="job" value="{{ old('job') ?? $model->job }}">
            @if ($errors->has('job'))
                <div class="invalid-feedback">
                    {{ $errors->first('job') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="height_in_cm">Größe [cm]</label>
            <input type="text" class="form-control {{ ($errors->has('height_in_cm') ? 'is-invalid' : '') }}" id="height_in_cm" name="height_in_cm" value="{{ old('height_in_cm') ?? $model->height_in_cm }}">
            @if ($errors->has('height_in_cm'))
                <div class="invalid-feedback">
                    {{ $errors->first('height_in_cm') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="medical_conditions">Aktuelle Beschwerden</label>
            <textarea name="medical_conditions" id="medical_conditions" class="form-control {{ ($errors->has('medical_conditions') ? 'is-invalid' : '') }}">{{ old('medical_conditions') ?? $model->medical_conditions }}</textarea>
            @if ($errors->has('medical_conditions'))
                <div class="invalid-feedback">
                    {{ $errors->first('medical_conditions') }}
                </div>
            @endif
        </div>
    </div>
</div>