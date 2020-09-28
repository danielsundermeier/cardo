<div class="card mb-3">
    <div class="card-header">Allgemein</div>
    <div class="card-body">

        <div class="form-group">
            <label for="number">Nummer</label>
            <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ old('number') ?? $model->number }}">
            @error('number')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="company_name">Firma</label>
            <input type="text" class="form-control {{ ($errors->has('company_name') ? 'is-invalid' : '') }}" id="company_name" name="company_name" value="{{ old('company_name') ?? $model->company_name }}">
            @error('company_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="lastname">Nachname</label>
            <input type="text" class="form-control {{ ($errors->has('lastname') ? 'is-invalid' : '') }}" id="lastname" name="lastname" value="{{ old('lastname') ?? $model->lastname }}">
            @error('lastname')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="firstname">Vorname</label>
            <input type="text" class="form-control {{ ($errors->has('firstname') ? 'is-invalid' : '') }}" id="firstname" name="firstname" value="{{ old('firstname') ?? $model->firstname }}">
            @error('firstname')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="birthday_formatted">Geburtstag</label>
            <input type="text" class="form-control {{ ($errors->has('birthday_formatted') ? 'is-invalid' : '') }}" id="birthday_formatted" name="birthday_formatted" value="{{ old('birthday_formatted') ?? $model->birthday_formatted }}">
            @error('birthday_formatted')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        @if ($model->is_client)
            <div class="form-group">
                <label for="job">Beruf</label>
                <input type="text" class="form-control {{ ($errors->has('job') ? 'is-invalid' : '') }}" id="job" name="job" value="{{ old('job') ?? $model->job }}">
                @error('job')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        @endif

        <div class="form-group">
            <label for="address">Straße</label>
            <input type="text" class="form-control {{ ($errors->has('address') ? 'is-invalid' : '') }}" id="address" name="address" value="{{ old('address') ?? $model->address }}">
            @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="postcode">PLZ</label>
            <input type="text" class="form-control {{ ($errors->has('postcode') ? 'is-invalid' : '') }}" id="postcode" name="postcode" value="{{ old('postcode') ?? $model->postcode }}">
            @error('postcode')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="city">Stadt</label>
            <input type="text" class="form-control {{ ($errors->has('city') ? 'is-invalid' : '') }}" id="city" name="city" value="{{ old('city') ?? $model->city }}">
            @error('city')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="country">Land</label>
            <input type="text" class="form-control {{ ($errors->has('country') ? 'is-invalid' : '') }}" id="country" name="country" value="{{ old('country') ?? $model->country }}">
            @error('country')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="phonenumber">Telefon</label>
            <input type="text" class="form-control {{ ($errors->has('phonenumber') ? 'is-invalid' : '') }}" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') ?? $model->phonenumber }}">
            @error('phonenumber')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="mobilenumber">Mobil</label>
            <input type="text" class="form-control {{ ($errors->has('mobilenumber') ? 'is-invalid' : '') }}" id="mobilenumber" name="mobilenumber" value="{{ old('mobilenumber') ?? $model->mobilenumber }}">
            @error('mobilenumber')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="faxnumber">Fax</label>
            <input type="text" class="form-control {{ ($errors->has('faxnumber') ? 'is-invalid' : '') }}" id="faxnumber" name="faxnumber" value="{{ old('faxnumber') ?? $model->faxnumber }}">
            @error('faxnumber')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">E-Mail</label>
            <input type="email" class="form-control {{ ($errors->has('email') ? 'is-invalid' : '') }}" id="email" name="email" value="{{ old('email') ?? $model->email }}">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="website">Web</label>
            <input type="text" class="form-control {{ ($errors->has('website') ? 'is-invalid' : '') }}" id="website" name="website" value="{{ old('website') ?? $model->website }}">
            @error('website')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
