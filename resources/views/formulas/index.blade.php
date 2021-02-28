<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formula</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <form class="" action="{{url('execute-formula')}}" method="POST" id="formualform">
                    <div class="form-group">
                        <label>Enter The Formula</label>
                        <textarea class="form-control" name="formula" placeholder="$a+$b-minOfTwo($x,$y)">2+3*pi-minOfTwo($x,$y)</textarea>
                        <p class="text-muted">
                            NOTE : for testing availabel varialbels are x=1,y=2,z=3,a=5,b=5
                            <br/><br/>Allowed PHP function : sinh?|cosh?|tanh?|abs|acosh?|asinh?|atanh?|exp|log10|deg2rad|rad2deg|sqrt|ceil|floor|round
                            <br/><br/>Available Custom Function : 
                            <br/><b>minOfTwo($x,$y)</b>
                        </p>
                    </div>
                    <button class="btn btn-primary" type="submit">EXECUTE</button>
                </form>
            </div>
            <div class="col-6" id="resultdiv">

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
$(document).on("submit","#formualform",function(event){
    event.preventDefault();
    var el = $(this);
    $.post(el.attr("action"),el.serialize(),function(){

    }).done(function(res){

        $("#resultdiv").html(res);

    }).fail(function(){

    });
});
</script>
</body>
</html>