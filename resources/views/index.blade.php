@extends('main')

@section('content')
  <style>
    form {
      width: 600px;
      padding: 30px 50px;
      border: 2px solid #CCC;
      border-radius: 28px;
      margin: 50px auto;
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
    .suggestions-wrapper {
      display: block;
    }
    .form-check {
      padding-left: 1.25rem !important;
    }
    legend {
      font-size: 13px;
    }
  </style>


  <div class="container">
    <div class="row justify-content-md-center">
      <form method="post" action="/execute">
        {{ csrf_field() }}
        <div class="form-row">
          <div class="col-6 form-group">
            <label for="from_city">Город отправления*</label>
            <input name="SCITY" id="SCITY" type="hidden">
            <input type="text" id="from_city" class="form-control" placeholder="Москва" required>
          </div>
          <div class="col-6 form-group">
            <label for="to_city">Город доставки*</label>
            <input name="RCITY" id="RCITY" type="hidden">
            <input type="text" id="to_city" class="form-control" placeholder="Караганда" required>
          </div>
        </div>
        <div class="form-row">
          <div class="col-6 form-group">
            <label for="WEIGHT">Вес в кг*</label>
            <input name="WEIGHT" type="number" id="WEIGHT" class="form-control" placeholder="10" required>
          </div>
          <div class="col-6 form-group">
            <label for="VOLUME">Объем в м3*</label>
            <input name="VOLUME" type="number" id="VOLUME" class="form-control" placeholder="1.3" required>
          </div>
        </div>
        <div class="form-row">
          <div class="col-6 form-group">
            <label for="PRICE">Объявленная стоимость*</label>
            <div class="input-group col">
              <input name="PRICE" type="text" id="PRICE" class="form-control" placeholder="1000" required>
              <div class="input-group-append">
                <span class="input-group-text">руб</span>
              </div>
            </div>
          </div>
          <div class="col-6 form-group">
            <label>Цену могу обосновать документами*</label>
            <div class="form-check form-check-inline-row">
              <label class="form-check-label" for="DOC_YES">Конечно</label>
              <input name="I_HAVE_DOC" type="radio"  id="DOC_YES" value="on" required>
              <label class="form-check-label" for="DOC_NO">Сомневаюсь</label>
              <input name="I_HAVE_DOC" type="radio"  id="DOC_NO" value="false" required>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-6 form-check">
            <input name="DELIVERY" type="checkbox" id="DELIVERY" class="form-check-input">
            <label class="form-check-label" for="DELIVERY">Нужна доставка груза по адресу получателя</label>
          </div>
          <div class="col-6 form-check">
            <input name="PICKUP" type="checkbox" id="PICKUP" class="form-check-input">
            <label class="form-check-label" for="PICKUP">Нужен забор груза по адресу отправителя</label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg">Посчитать</button>
        <div style="width:100%; display:none;" id="CITY_INFO" class="alert alert-danger" role="alert">
        </div>
        <legend>* - поле обязательно для заполнения</legend>
      </form>

    </div>

    @if(isset($DAYS))
      <p>Доставка займет {{ $DAYS }} дней, будет стоить {{ $PRICE['TOTAL'] }}{{ $E_WAERS }} </p>
    @endif
  </div>
  <script>
      function isCity (city, obj) {
          $.ajax({
              url: '/api/iscity',
              type: 'POST',
              dataType: 'json',
              data: {
                  victim: city
              }
          })
          .done(function(data) {
              if (!data) {
                  obj.addClass('is-invalid').removeClass('is-valid');
                  obj.val('');
                  $('#CITY_INFO').show().text('С городом ' + city + ' Не работаем');
              } else {
                  obj.removeClass('is-invalid').addClass('is-valid');
                  $('#CITY_INFO').hide();
              }
          })
          .fail(function() {
              console.log("error");
          });
      }
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
              $('#SCITY').val(data.data.city);
              isCity(data.data.city, $('#from_city'))
          }
      });
      $RCITY.suggestions({
          token: token,
          type: type,
          hint: false,
          bounds: "city-settlement",
          formatSelected: function (data) {
              $('#RCITY').val(data.data.city);
              isCity(data.data.city, $('#to_city'))
          }
      });
  </script>
@endsection