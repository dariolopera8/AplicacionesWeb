<div class="container">
    <div class="panel">
        <div class="panel-heading">
            {l s='Codigos actuales en la base de datos'}
        </div>
        <div class="panel-body">
            {if isset($codigos) && $codigos|count}
                <table class="table">
                    <thead>
                        <tr>
                            <th>{l s='CODIGO' mod='descuentodario'}</th>
                            <th>{l s='PORCENTAJE' mod='descuentodario'}</th>
                            <th colspan="2">{l s='ACCIONES' mod='descuentodario'}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $codigos as $c}
                            <form method="post" action="">
                                <tr>
                                    <input type=hidden name="id_codigo" value="{$c.id_cod}" />
                                    <td><input type=text name="cod_modbor" value="{$c.cod_desc}" /></td>
                                    <td><input type=number name="porcen_modbor" value="{$c.porcen}" />%</td>
                                    <td><input type="submit" name="modificar" class="btn btn-primary" value="Actualizar" class="button" /></td>
                                    <td><input type="submit" name="borrar" class="btn btn-danger" value="BORRAR" class="button" /></td>
                                </tr>
                            </form>
                        {/foreach}
                    </tbody>
                </table>
                <form action ="" method="POST">
                    <fieldset>
                        <label>Codigo de descuento</label>
                        <input type="text" id="cod" name="cod"/>
                        <div class="clear">&nbsp;</div>
                        <label>Porcentaje a descontar</label>
                        <input type="number" id="porce" name="porce"/>
                        <div class="clear">&nbsp;</div>
                        <div class="margin-form">
                            <input type="submit" name="add" value="AGREGAR" class="btn btn-success"/>
                        </div>
                    </fieldset>
                </form>
            {else}
                <p class="alert alert-info">{l s='Aun no hay ningun codigo'}</p>
                <form action ="" method="POST">
                    <fieldset>
                        <label>Codigo de descuento</label>
                        <input type="text" id="cod" name="cod"/>
                        <div class="clear">&nbsp;</div>
                        <label>Porcenaje a descontar</label>
                        <input type="number" id="porce" name="porce"/>
                        <div class="clear">&nbsp;</div>
                        <div class="margin-form">
                            <input type="submit" name="add" value="AGREGAR" class="btn btn-success"/>
                        </div>
                    </fieldset>
                </form>
            {/if}
        </div>
    </div>
</div>