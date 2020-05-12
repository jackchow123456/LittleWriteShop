
<button class="btn btn-primary order_status" value="all">
    <span class="d-none d-sm-inline">所有订单</span>
</button>

<button class="btn btn-primary order_status" value="notPay">
    <span class="d-none d-sm-inline">未付款</span>
    <span class="badge bg-danger">{{$options['notPay']}}</span>
</button>

<button class="btn btn-primary order_status" value="notShipping">
    <span class="d-none d-sm-inline">未发货</span>
    <span class="badge bg-danger">{{$options['notShipping']}}</span>
</button>
