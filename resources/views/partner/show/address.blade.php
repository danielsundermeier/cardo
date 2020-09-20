<div class="card mb-3">
    <div class="card-header">{{ $model->name }}</div>
    <div class="card-body">
        @if ($model->number)
            <div class="row">
                <div class="col-label"><b>Nummer</b></div>
                <div class="col-value">{{ $model->number }}</div>
            </div>
        @endif
        <div class="row mb-3">
            <div class="col-label"><b>Adresse</b></div>
            <div class="col-value">{!! nl2br($model->billing_address) !!}</div>
        </div>
        @if ($model->birthday_at)
            <div class="row">
                <div class="col-label"><b>Geburtstag</b></div>
                <div class="col-value">{{ $model->birthday_formatted }}</div>
            </div>
        @endif
        @if ($model->phonenumber)
            <div class="row">
                <div class="col-label"><b>Telefon</b></div>
                <div class="col-value"><a href="tel:{{ $model->mobilenumber }}">{{ $model->phonenumber }}</a></div>
            </div>
        @endif
        @if ($model->mobilenumber)
            <div class="row">
                <div class="col-label"><b>Mobil</b></div>
                <div class="col-value"><a href="tel:{{ $model->mobilenumber }}">{{ $model->mobilenumber }}</a></div>
            </div>
        @endif
        @if ($model->faxnumber)
            <div class="row">
                <div class="col-label"><b>Fax</b></div>
                <div class="col-value">{{ $model->faxnumber }}</div>
            </div>
        @endif
        @if ($model->email)
            <div class="row">
                <div class="col-label"><b>E-Mail</b></div>
                <div class="col-value"><a href="mailto:{{ $model->email }}">{{ $model->email }}</a></div>
            </div>
        @endif
        @if ($model->website)
            <div class="row">
                <div class="col-label"><b>Webseite</b></div>
                <div class="col-value"><a href="{{ $model->website }}" target="_blank">{{ $model->website }}</a></div>
            </div>
        @endif
    </div>
</div>