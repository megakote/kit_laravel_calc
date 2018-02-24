@extends('main')

@section('content')
  <style>
    button {
      margin: 30px auto;
      display: block !important;
    }
    .form-row {
      margin: 10px auto;
    }
    p {
      text-align: center;
    }
  </style>

  <div class="container">
    <div class="row justify-content-md-center">
      <form method="post" action="/execute">
        {{ csrf_field() }}
        <div class="form-row">
          <div class="col-6">
            <label for="SCITY">Город отправления</label>
            <input name="SCITY" type="text" id="SCITY" class="form-control" placeholder="Москва">
          </div>
          <div class="col-6">
            <label for="RCITY">Город доставки</label>
            <input name="RCITY" type="text" id="RCITY" class="form-control" placeholder="Караганда">
          </div>
        </div>
        <div class="form-row">
          <div class="col-6">
            <label for="WEIGHT">Вес в кг</label>
            <input name="WEIGHT" type="text" id="WEIGHT" class="form-control" placeholder="10">
          </div>
          <div class="col-6">
            <label for="VOLUME">Объем в м3</label>
            <input name="VOLUME" type="text" id="VOLUME" class="form-control" placeholder="1.3">
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg">Посчитать</button>
      </form>
    </div>
    @if(isset($DAYS))
      <p>Доставка займет {{ $DAYS }} дней, будет стоить {{ $PRICE['TOTAL'] }}{{ $E_WAERS }} </p>
    @endif
  </div>
@endsection