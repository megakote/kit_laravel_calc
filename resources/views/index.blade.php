@extends('main')

@section('content')
  <style>
    form {
      width: 500px;
    }
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
            <label for="from_city">Город отправления</label>
            <input name="SCITY" id="SCITY" type="hidden">
            <input type="text" id="from_city" class="form-control" placeholder="Москва">
          </div>
          <div class="col-6">
            <label for="to_city">Город доставки</label>
            <input name="RCITY" id="RCITY" type="hidden">
            <input type="text" id="to_city" class="form-control" placeholder="Караганда">
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
  <script>

      token = "19ee934bb33404454e3d1047e8c51a80f408a0df",
          type  = "ADDRESS",
          $SCITY = $("#from_city"),
          $RCITY = $("#to_city"),

          $SCITY.suggestions({
              token: token,
              type: type,
              hint: false,
              bounds: "city-settlement",
              formatSelected: function (data) {
                  $('#SCITY').val(data.data.city)
              }
          });
      $RCITY.suggestions({
          token: token,
          type: type,
          hint: false,
          bounds: "city-settlement",
          formatSelected: function (data) {
              $('#RCITY').val(data.data.city)
          }
      });


  </script>
@endsection