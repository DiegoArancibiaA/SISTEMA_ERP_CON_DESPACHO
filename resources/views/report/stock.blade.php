@extends('include.master')


@section('title','AlphaERP | Stock-Report')


@section('page-title','Stock Report')


@section('content')




<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>
					
					Reporte de Stock
					
				</h2>

				<h2 class="pull-right">
					
			<a href="{{ url('print-report') }}?type={{ $type }}&start_date={{ $start_date }}&end_date={{ $end_date }}&category_id={{ $category_id }}&product_id={{ $product_id }}&stock_id={{ $stock_id }}&vendor_id={{ $vendor_id }}&customer_id={{ $customer_id }}&user_id={{ $user_id }}" target="_blank" type="button" class="btn bg-teal btn-circle waves-effect waves-circle waves-float">
                               <i class="material-icons">Imprimir</i>
                                     </a>
					
				</h2>
			</div>


			<div class="body">

				<div class="table-responsive">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<td colspan="11" style="border: none !important;">
									<h3 style="text-align: center;">{{ $company->name }}</h3>
								</td>

							</tr>		

							<tr style="border: none !important;">
								<td colspan="11" style="border: none !important;">
									<p style="text-align: center;">{{ $company->address }} <br>
										 {{ $company->phone }}</p></td>

							</tr>  				

							<tr style="border: none !important;">
								<td colspan="11" style="border: none !important;"><p style="text-align: center;font-weight: bold;">Reporte de Stock desde{{ date('j M Y',strtotime($start_date)) }} a {{ date('j M Y',strtotime($end_date)) }}</p></td>

							</tr>
							<tr>
								<th>Categoría</th> {{-- Traducción --}}
								<th>Producto</th> {{-- Traducción --}}
								<th>Comprobante</th> {{-- Ya estaba en español --}}
								<th>Fecha</th> {{-- Ya estaba en español --}}
								<th>Ingresado Por</th> {{-- Traducción de "Entry By" --}}
								<th>Precio de Compra</th> {{-- Traducción --}}
								<th>Precio de Venta</th> {{-- Traducción --}}
								<th>Cantidad en Stock</th> {{-- Traducción --}}
								<th>Cantidad Vendida</th> {{-- Traducción --}}
								<th>Cantidad Actual</th> {{-- Traducción --}}
							</tr>
						</thead>


						<tbody>
							<?php
							$total_quantity = 0;
							$total_sold_quantiy = 0;

							?>
							@foreach($data as $value)

							<?php 
							$total_quantity += $value->stock_quantity;
							$total_sold_quantiy += $value->sold_qty;
							?>
							<tr>

								<td>{{ $value->category->name }}</td>
								<td>{{ $value->product->product_name }}</td>
								<td>{{ $value->chalan_no }}</td>
								<td>{{ date("j M Y", strtotime($value->created_at) ) }}</td>
								<td>{{ $value->user->name }}</td>
								<td>{{ $value->buying_price }}</td>
								<td>{{ $value->selling_price }}</td>
								<td>{{ $value->stock_quantity }}</td>
								<td>{{ $value->sold_qty }}</td>
								<td>{{ $value->stock_quantity - $value->sold_qty }}</td>
								
							</tr>
							@endforeach

							<tr>
								<th colspan="7" style="text-align: right;">Total =</th>
								<th >{{ round($total_quantity) }}</th>
								<th >{{ round($total_sold_quantiy) }}</th>
								<th >{{ round($total_quantity-$total_sold_quantiy) }}</th>
							
							</tr>


						</tbody>
					</table>
				</div>


			</div>


		</div>
	</div>
</div>




@endsection

