<?php

use PhpParser\Node\Expr\Cast\Double;

class descuentodario extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'descuentodario';
        $this->tab = 'pricing_promotion';
        $this->version = '1.0.0';
        $this->author = 'Dario Lopera';
        $this->need_instance = 1;
        $this->bootstrap = 1;
        parent::__construct();
        $this->displayName = $this->l('Codigos de descuento');
        $this->description = $this->l('En este modulo se podrán adminisrtar códigos de descuento en los productos');
        $this->confirmUninstall = $this->l('¿Estas seguro de que quieres desinstalar el modulo?');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayShoppingCartFooter') && $this->installDB();
    }

    public function installDb()
    {
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'cod_descuentos (
            id_cod int(11) NOT NULL AUTO_INCREMENT,
            cod_desc VARCHAR(255),
            porcen INT(3),
            PRIMARY KEY (`id_cod`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
        return true;
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->unistallDB();
    }

    public function unistallDB()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'cod_descuentos');
        return true;
    }

    private function submitTools()
    {
        if (Tools::isSubmit('add')){
            Db::getInstance()->insert('cod_descuentos', [
                'cod_desc' => pSQL(Tools::getValue('cod')),
                'porcen' => pSQL(Tools::getValue('porce')),
            ]);
        }
        if (Tools::isSubmit('modificar')){
            $idactu = "id_cod=" . Tools::getValue('id_codigo');
            db::getInstance()->update('cod_descuentos', [
                'cod_desc' => pSQL(Tools::getValue('cod_modbor')),
                'porcen' => pSQL(Tools::getValue('porcen_modbor')),
            ],
            $idactu, 1, true
            );
        }
        if (Tools::isSubmit('borrar')){
            $idborrar = "id_cod=" . Tools::getValue('id_codigo');
            db::getInstance()->delete('cod_descuentos', $idborrar);
        }
        
    }

    private function adminCodigos()
    {
        $this->context->smarty->assign([
            'codigos' => $this->getCodigos(),
        ]);

        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/codadmin.tpl');
    }

    public function getContent()
    {
        return $this->submitTools() . $this->adminCodigos();
    }

    private function getCodigos()
    {
        return Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'cod_descuentos');
    }

    public function hookDisplayShoppingCartFooter()
    {
        if(Tools::isSubmit('aplicarcodigo')){
            if(!empty(Tools::getValue('codigocarro'))){
                $sql = 'SELECT porcen FROM '._DB_PREFIX_.'cod_descuentos
                    WHERE cod_desc = ' . '"' . Tools::getValue('codigocarro') . '"';
                $codiguito = Db::getInstance()->executeS($sql);
                if(empty($codiguito)){
                    echo '<h2>Codigo no valido</h2>';
                }else{
                    $productos = Context::getContext()->cart->getProducts();
                    $descuento = $codiguito[0]['porcen'] / 100;
                    for ($i=0; $i < count($productos); $i++) {
                        $precio = Product::getPriceStatic($productos[$i]['id_product']);
                        $specific_price = new SpecificPrice();
                        $specific_price->id_product = (int)$productos[$i]['id_product'];
                        $specific_price->id_product_attribute = $productos[$i]['id_product_attribute'];
                        $specific_price->id_cart = (int)$this->context->cart->id;
                        $specific_price->id_shop = (int)context::getContext()->shop->id;
                        $specific_price->id_currency = 0;
                        $specific_price->id_country = 0;
                        $specific_price->id_group = 0;
                        $specific_price->id_customer = 0;
                        $specific_price->from_quantity = 1;
                        $specific_price->price = round(($precio - ($precio * $descuento)) / 1.21, 2);
                        $specific_price->reduction_type = 'percentage';
                        $specific_price->reduction_tax = 0;
                        $specific_price->reduction = 0;
                        $specific_price->from = date("0000-00-00 00:00:00");
                        $specific_price->to = date("Y-m-d").' 23:59:59';
                        $specific_price->add();
                    }
                    $_SESSION['porcentajeCodigo'] = $codiguito[0]['porcen'];
                    $_SESSION['idTienda'] = (int)$this->context->cart->id;
                    $this->context->smarty->assign([
                        'porcentaje' => $codiguito[0]['porcen'],
                    ]);
                    return $this->context->smarty->fetch($this->local_path . 'views/templates/carrito/cart.tpl') && header("Refresh:0");
                }
            }else{
                echo '<h2>No se ha introducido ningun codigo</h2>';
            }
        }
        if(Tools::isSubmit('eliminarcodigo')){
            unset($_SESSION['porcentajeCodigo']);
            SpecificPrice::deleteByIdCart((int)$this->context->cart->id);
            return $this->context->smarty->fetch($this->local_path . 'views/templates/carrito/cart.tpl') && header("Refresh:0");
        }
        if($_SESSION['idTienda'] != (int)$this->context->cart->id){
            unset($_SESSION['porcentajeCodigo']);
        }
        if(isset($_SESSION['porcentajeCodigo'])){
            $this->context->smarty->assign([
                'porcentaje' => $_SESSION['porcentajeCodigo'],
                'yaAplicado' => 1,
            ]);
        }
        return $this->context->smarty->fetch($this->local_path . 'views/templates/carrito/cart.tpl');
    }
}
?>