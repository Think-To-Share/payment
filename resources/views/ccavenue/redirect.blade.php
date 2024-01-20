
<form method="post" name="redirect" action="{{  ThinkToShare\Payment\Gateways\CcAvenue\CcAvenueGateway::getPaymentUrl() }}"> 
      <input type="hidden" name="encRequest" value="{{ $encrypted_data }}">
      <input type="hidden" name="access_code" value ="{{ $access_code }}">
</form>
    
<script language='javascript'>document.redirect.submit();</script>