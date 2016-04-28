@extends('app')
@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/sale.js', array('type' => 'text/javascript')) !!}
<link rel="stylesheet" href="bower_components/selectize/dist/css/selectize.default.css ">
<script type="text/javascript" src="bower_components/selectize/dist/js/standalone/selectize.min.js"></script>
<script type="text/javascript" src="bower_components/angular-selectize2/dist/angular-selectize.js"></script>

<div class="container-fluid">
   <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{trans('sale.sales_register')}}</div>

            <div class="panel-body pos-body">

                @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
                {!! Html::ul($errors->all()) !!}
                
                <div class="row" ng-controller="SearchItemCtrl">

                    <div class="col-md-4 ">
                        <label>{{trans('sale.search_item')}} <input ng-model="searchKeyword" class="form-control"></label>

                        <div class="list-group item-list">
                            <a href="#" class="list-group-item singleItem" ng-repeat="item in items  | filter: searchKeyword" ng-click="addToInvoice(item)">
                                <h4 class="list-group-item-heading">@{{item.name}}</h4>
                                <p class="list-group-item-text">@{{item.description}}</p>
                            </a>
                        </div>
                        <!--
                        <table class="table table-hover">
                            <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">

                                <td>@{{item.name}}</td>
                                <td><button class="btn btn-success btn-xs" type="button" ng-click="addSaleTemp(item, newsaletemp)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button></td>

                            </tr>
                        </table>
                        -->
                    </div>

                    <div class="col-md-8">

                        <div class="row">
                            
                            {!! Form::open(array('url' => 'sales', 'class' => 'form-horizontal')) !!}

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="user" class="col-sm-3 control-label">{{trans('sale.user')}}</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="user" value="{{ Auth::user()->username }}" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="user" class="col-sm-3 control-label">{{trans('sale.customer')}}</label>
                                        <div class="col-sm-9">
                                            <selectize config='customerListConfig' options='customerList' ng-model="customer"></selectize>
                                        </div>
                                    </div>

                                </div>

                            
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="min-height: 300px;">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>{{trans('sale.item_code')}}</th>
                                        <th>{{trans('sale.item_name')}}</th>
                                        <th>{{trans('sale.price')}}</th>
                                        <th>{{trans('sale.quantity')}}</th>
                                        <th>{{trans('sale.total')}}</th>
                                        <th></th>
                                    </tr>
                                    <tr ng-repeat="item in invoiceItems">
                                        <td>
                                            @{{ item.code }}
                                        </td>
                                        <td>
                                            @{{ item.name }}
                                        </td>
                                        <td>
                                            @{{ item.selling_price }}
                                        </td>
                                        <td>
                                            <input type="number" ng-model = "item.quantity" maxlength="4" size="4" min="0" ng-pattern="onlyNumbers"/>
                                        </td>
                                        <td>
                                            @{{item.selling_price * item.quantity | currency}}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-xs" aria-label="Left Align" ng-really-message="Are you sure to delete the item ?"  ng-really-click="removeItem(item)">
                                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                            </button>


                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service_type" class="col-sm-4 control-label">{{trans('sale.service_type')}}</label>
                                        <div class="col-sm-8">
                                            {!! Form::select('service_type', array('Check-In' => 'Check-In', 'Take-Away' => 'Take-Away', 'Home-Delivery' => 'Home-Delivery'), Input::old('service_type'), array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="total" class="col-sm-4 control-label">{{trans('sale.add_payment')}}</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">$</div>
                                                <input type="number" class="form-control" id="add_payment" ng-model="paid"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="payment_type" class="col-sm-4 control-label">{{trans('sale.payment_type')}}</label>
                                        <div class="col-sm-8">
                                            {!! Form::select('payment_type', array('Cash' => 'Cash', 'Check' => 'Check', 'Debit Card' => 'Debit Card', 'Credit Card' => 'Credit Card'), Input::old('payment_type'), array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="employee" class="col-sm-4 control-label">{{trans('sale.comments')}}</label>
                                        <div class="col-sm-8">
                                        <input type="text" class="form-control" name="comments" id="comments" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="discount" class="col-sm-4 control-label">Discounts</label>
                                        <div class="col-sm-8" class="form-control" style="margin-top: 10px;">
                                            <div class="span1" ng-repeat="discount in discountOptions">
                                                <a href="#" class="btn btn-primary btn-lg" ng-really-message="Are you sure to add the discount ?"  ng-really-click="addDiscount(discount)">
                                                    <i class="icon-pencil icon-white"></i>
                                                    <span>
                                                        <strong>@{{discount.name}}</strong>
                                                        <p>
                                                            @{{discount.amount | number : 2}}
                                                            @{{ (discount.type == 1)?'%':'-' }}
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supplier_id" class="col-sm-6 control-label">{{trans('sale.sum')}}</label>
                                        <div class="col-sm-6">
                                            <p class="form-control-static">
                                                <span>BDT </span><b>@{{getTotalWithRealPrice()}}</b>
                                            </p>
                                        </div>
                                    </div>





                                    <div class="form-group" ng-repeat="charge in charges">
                                        <label for="@{{charge.name}}" class="col-sm-6 control-label">@{{charge.name}} @{{charge.amount}} @{{charge.type ==1 ? '%' : '+'}}</label>
                                        <div class="col-sm-6">
                                            <p class="form-control-static"><span>+ BDT </span>@{{ charge.value | number: 2}}</p>
                                        </div>
                                    </div>



                                    <div class="form-group" ng-repeat="discount in discountTrack">
                                        <label for="@{{discount.name}}" class="col-sm-6 control-label">@{{discount.name}} @{{discount.amount}} @{{discount.type ==1 ? '%' : '-'}}</label>
                                        <div class="col-sm-6">
                                            <p class="form-control-static">
                                                <span>- BDT </span>@{{ getDiscountedPrice(discount.amount, discount.type) | number: 2}}
                                                <span>
                                                    <button type="button" class="btn btn-danger btn-xs" aria-label="Right Align" ng-click="removeDiscount(discount)">
                                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                    </button>
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="supplier_id" class="col-sm-6 control-label">{{trans('sale.grand_total')}}</label>
                                        <div class="col-sm-6">
                                            <p class="form-control-static">
                                                <span>BDT </span><b>@{{getTotal()}}</b>
                                            </p>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="amount_due" class="col-sm-6 control-label">{{trans('sale.amount_due')}}</label>
                                        <div class="col-sm-6">
                                            <p class="form-control-static"><span>BDT </span>@{{ getDue() }}</p>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <button type="submit" class="btn btn-success btn-block">{{trans('sale.submit')}}</button>
                                            <button type="button" class="btn btn-warning btn-block">{{trans('sale.hold')}}</button>
                                            <button type="button" class="btn btn-danger btn-block">{{trans('sale.clear')}}</button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            {!! Form::close() !!}
                            
                        

                    </div>

                </div>

            </div>
            </div>
        </div>
    </div>
</div>
@endsection