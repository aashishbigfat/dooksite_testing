<html>

<head>
  <title> Custom Form Kit </title>
</head>

<body>
  <center>
    <form method="POST" name="redirect" action="{{route('frontend.purchase')}}" id="customerDataForm">
      <input type="text" name="customer_id" id="customer_id" hidden value="{{ $customerId }}">
      <input type="text" name="tid" id="tid" hidden>
      <input type="text" name="merchant_id" value="177375" hidden />
      <input type="text" name="billing_name" value="{{ $customerData->first_name }}" hidden />
      <input type="text" name="priceId" value="{{ $order['priceId'] }}" hidden />
      <input type="text" name="departure_dateid" value="{{ $order['departure_dateid'] }}" hidden />
      <input type="text" name="order_id" value="{{ $order['order_id'] }}" hidden />
      <!-- <input type="text" name="amount" value="1" hidden /> -->
       <input type="text" name="amount" value="{{$order['price']}}" hidden /> 
      <input type="text" name="billing_email" value="{{ $customerData->email_id }}" hidden />
      <input type="text" name="currency" value="INR" hidden />
      <input type="text" name="language" value="EN" hidden />
      <!-- <input type="submit" id="payButton" class="btn btn-primary mt-4 w-100" value="CheckOut"> -->
      @csrf
    </form>

  </center>
  <!-- <script language='javascript'>document.redirect.submit();</script> -->
  <script>
    window.onload = function() {
    console.log('Form submitted automatically');
    document.getElementById('customerDataForm').submit();
};
  </script>
</body>

</html>