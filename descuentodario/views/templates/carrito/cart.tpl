<div class="container mt-5">
    <div>
        <div class="d-flex flex-row justify-content-end off">
        {if isset($porcentaje) && $porcentaje|count}
            <h1>Codigo válido, obtienes un {$porcentaje}% de descuento</h1>
        {else}
            <p class="alert alert-info">{l s='Aun no hay ningun codigo'}</p>
        {/if}
        </div>
        <form action="" method="POST">
        {if $yaAplicado}
            <p class="alert alert-success">{l s='Codigo ya aplicado'}</p>
            <input type="submit" name="eliminarcodigo" value="Eliminar codigo actual" class="btn btn-danger">
        {else}
            <div class="d-flex flex-row"><h3>Codigo de promocion</h3><input type="text" value="" name="codigocarro"></div>
            <br>
            <input type="submit" name="aplicarcodigo" value="Aplicar codigo" class="btn btn-primary">
        {/if}
        </form>
    </div>
</div>