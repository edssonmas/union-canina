<?php

/**
 * Jcrop image cropping plugin for jQuery
 * Example cropping script
 * @copyright 2008-2009 Kelly Hallman
 * More info: http://deepliquid.com/content/Jcrop_Implementation_Theory.html
 */

 $usuario= Session::get("usuario");
// If not a POST request, display page below:

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Live Cropping Demo</title>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
  <script src="../js/jquery.min.js"></script>
  <script src="../js/jquery.Jcrop.js"></script>
  <link rel="stylesheet" href="../css/main.css" type="text/css" />
  <link rel="stylesheet" href="../css/demos.css" type="text/css" />
  <link rel="stylesheet" href="../css/jquery.Jcrop.css" type="text/css" />
  <link rel="stylesheet" href="../css/home.css">

<script type="text/javascript">

  $(document).ready(function(){
      $(function(){

    $('#cropbox').Jcrop({
      aspectRatio: 1,
      onSelect: updateCoords
    });

  });

  function updateCoords(c)
  {
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
  };

  function checkCoords()
  {
    if (parseInt($('#w').val())) return true;
    alert('Please select a crop region then press submit.');
    return false;
  };
  })

</script>
<style type="text/css">
  #target {
    background-color: #ccc;
    width: 500px;
    height: 330px;
    font-size: 24px;
    display: block;
  }

</style>

</head>
<body>

  @php
      $usuario= Session::get("usuario");
  @endphp

  <div style="margin: 5%;">
      <!-- This is the image we're attaching Jcrop to -->
      <img src="{{url('/imagen/'.$usuario->foto )}}" id="cropbox" style="max-height: 380px; max-width: auto;">

      <!-- This is the form that our event handler fills -->
      <form action="{{ url('/cutpic') }}" method="post" onsubmit="return checkCoords();">
        {{ csrf_field() }}
        <input type="hidden" id="x" name="x" />
        <input type="hidden" id="y" name="y" />
        <input type="hidden" id="w" name="w" />
        <input type="hidden" id="h" name="h" />
        <input type="submit" value="Recortar imagen" class="btn btn-large btn-info" />
      </form>
    </div>
  </body>

</html>
