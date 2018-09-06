<div class="col-xs-4 invoice-col text-right">
    <b>Orden #{{ $nro_orden }}</b><br>
    <br>
    <b>Cantidad de Artículos:</b> {{ $cantidad_articulos }} <br>
    {{-- Si existe un cliente y ese cliente tiene saldo lo vamos a descontar o hay un articulo para cambiar --}}
    @if (isset($cliente) && $cliente && ($cliente->saldo || isset($articulo_a_cambiar)))
        {{-- Si el saldo es mayor a lo que tiene que pagar => no tiene que pagar nada, sino se hacen cuentas --}}
        @if ($cliente->saldo >= $total)
            <span id="total" data-total="{{ number_format($total,2) }}">
                <strong>Total a Pagar:</strong> $0.00 <br>
                <strong>Total a Descontar:</strong> ${{ number_format(abs($total), 2) }} <br>
            </span>
        @else
            <span id="total" data-total="{{ number_format($total,2) }}">
                <strong>Total a Pagar:</strong> ${{ number_format($total - $cliente->saldo, 2) }}
            </span>
        @endif
        <br>
        @if (isset($articulo_a_cambiar))
            <strong>El cliente tiene <i><u>${{ number_format($articulo_a_cambiar->DatosArticulo->precio + $cliente->saldo, 2) }}</u></i> a favor</strong>
        @else
            <strong>El cliente tiene <i><u>${{ number_format($cliente->saldo, 2) }}</u></i> a favor</strong>
        @endif
    @else
        <span id="total" data-total="{{ number_format($total,2) }}">
            <strong>Total a Pagar:</strong> ${{ number_format($total, 2) }}
        </span>
    @endif
</div>