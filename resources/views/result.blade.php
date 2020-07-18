<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Filter database</title>
  </head>
  <body>
    @foreach($results as $key => $product)
    <p>{{ $product->nazwa}}</p>
    @endforeach


  