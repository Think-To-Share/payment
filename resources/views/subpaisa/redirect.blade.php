<form name="redirect" action="{{ ThinkToShare\Payment\Gateways\SabPaisa\SabPaisaGateway::getPaymentUrl() }}" method="post">
    <input type="hidden" name="encData" value="{{ $data }}" id="frm1">
    <input type="hidden" name="clientCode" value ="{{ $clientCode }}" id="frm2">
</form>
<script language='javascript'>document.redirect.submit();</script>