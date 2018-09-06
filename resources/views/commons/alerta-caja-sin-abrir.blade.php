<div style="padding: 15px 15px 0">
    {{-- Si no abrió caja que le salga la alerta. Si es admin la puede sacar, sino no --}}
    @if (!Auth::user()->abrioCaja())
        <div class="alert callout callout-danger">
            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
            <strong>Atención!</strong>
            La caja aún no ha sido abierta. Se recomienda que lo haga
            cuanto antes haciendo <a href="{{ url('caja/abrir') }}">click aquí</a>
        </div>
    @endif
</div>